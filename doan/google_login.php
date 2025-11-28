<?php
session_start();
require_once 'vendor/autoload.php'; 
require_once 'config/db.php';      



$redirectUri = 'https://minhouse.id.vn/google_login.php'; 
$clientID = 'YOUR_GOOGLE_CLIENT_ID'; // Thay bằng text này
$clientSecret = 'YOUR_GOOGLE_CLIENT_SECRET'; // Thay bằng text này

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);
        
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        
        $email = $google_account_info->email;
        $name = $google_account_info->name;
        $google_id = $google_account_info->id;

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (empty($user['google_id'])) {
                $update = $conn->prepare("UPDATE users SET google_id = ? WHERE id = ?");
                $update->bind_param("si", $google_id, $user['id']);
                $update->execute();
            }
            
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
                
            ];
        } else {
            $insert = $conn->prepare("INSERT INTO users (name, email, google_id, password) VALUES (?, ?, ?, NULL)");
            $insert->bind_param("sss", $name, $email, $google_id);
            
            if ($insert->execute()) {
                $new_user_id = $conn->insert_id;
                $_SESSION['user'] = [
                    'id' => $new_user_id,
                    'name' => $name,
                    'email' => $email
                ];
            } else {
                $_SESSION['error'] = "Lỗi tạo tài khoản: " . $conn->error;
                header("Location: index.php");
                exit;
            }
        }

        header("Location: index.php");
        exit;
        
    } else {
        $_SESSION['error'] = "Lỗi xác thực Google.";
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: " . $client->createAuthUrl());
    exit;
}
?>