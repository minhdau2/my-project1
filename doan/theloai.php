<?php
include '../config/connect.php';

// ======= THÊM THỂ LOẠI =======
if (isset($_POST['add'])) {
    $ten = trim($_POST['ten_the_loai']);
    if ($ten != "") {
        mysqli_query($conn, "INSERT INTO theloai (ten_the_loai) VALUES ('$ten')");
        header("Location: ?page=theloai");
        exit;
    }
}

// ======= XÓA THỂ LOẠI =======
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM theloai WHERE id = $id");
    header("Location: ?page=theloai");
    exit;
}

// ======= CẬP NHẬT THỂ LOẠI =======
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $ten = trim($_POST['ten_the_loai']);
    if ($ten != "") {
        mysqli_query($conn, "UPDATE theloai SET ten_the_loai = '$ten' WHERE id = $id");
        header("Location: ?page=theloai");
        exit;
    }
}

// ======= LẤY DANH SÁCH THỂ LOẠI =======
$result = mysqli_query($conn, "SELECT * FROM theloai ORDER BY id DESC");
?>

<!-- Giao diện quản lý thể loại -->
<div class="container mt-4">
  <h2 class="mb-4 text-primary fw-bold"><i class="fas fa-tags"></i> Quản lý Thể loại</h2>

  <!-- Form thêm thể loại -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <form method="POST" class="row g-3">
        <div class="col-md-6">
          <label for="ten_the_loai" class="form-label fw-semibold">Tên thể loại:</label>
          <input type="text" name="ten_the_loai" id="ten_the_loai" class="form-control" placeholder="Nhập tên thể loại..." required>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" name="add" class="btn btn-success w-100">
            <i class="fas fa-plus-circle"></i> Thêm thể loại
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Bảng danh sách thể loại -->
  <div class="card shadow-sm">
    <div class="card-body">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-primary text-center">
          <tr>
            <th width="10%">ID</th>
            <th width="60%">Tên thể loại</th>
            <th width="30%">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td class="text-center"><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['ten_the_loai']) ?></td>
                <td class="text-center">
                  <!-- Nút sửa -->
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                    <i class="fas fa-edit"></i> Sửa
                  </button>

                  <!-- Nút xóa -->
                  <a href="?page=theloai&delete=<?= $row['id'] ?>" 
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('Bạn chắc chắn muốn xóa thể loại này?')">
                     <i class="fas fa-trash-alt"></i> Xóa
                  </a>
                </td>
              </tr>

              <!-- Modal chỉnh sửa -->
              <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form method="POST">
                      <div class="modal-header bg-warning">
                        <h5 class="modal-title text-white fw-bold" id="editModalLabel<?= $row['id'] ?>">
                          <i class="fas fa-edit"></i> Chỉnh sửa thể loại
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <div class="form-group mb-3">
                          <label class="fw-semibold">Tên thể loại:</label>
                          <input type="text" name="ten_the_loai" value="<?= htmlspecialchars($row['ten_the_loai']) ?>" class="form-control" required>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" name="update" class="btn btn-primary">Cập nhật</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" class="text-center text-muted">Chưa có thể loại nào.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
