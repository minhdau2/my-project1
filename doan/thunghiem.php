<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản lý rạp phim - MinHouse Cinema</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root {
      --primary: #ff4c4c;
      --primary-dark: #cc3b3b;
      --dark-bg: #1a1a1a;
    }
  </style>
</head>
<body class="bg-gray-100 text-gray-800">
  <div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-[var(--dark-bg)] to-black text-white p-6 space-y-8 shadow-2xl border-r border-gray-700/40 backdrop-blur-xl">
      <div class="flex items-center space-x-3">
        <div class="p-3 bg-[var(--primary)] rounded-xl shadow-lg"></div>
        <h1 class="text-2xl font-bold tracking-wide">Cinema Booking Admin</h1>
      </div>

      <nav class="space-y-2">
        <a href="#cinemas" class="group block py-3 px-4 rounded-lg hover:bg-white/10 transition-all flex items-center space-x-3 backdrop-blur-sm hover:shadow-lg hover:shadow-[var(--primary)]/20">
          <i data-lucide="building-2" class="w-5 h-5"></i><span>Rạp phim</span>
        </a>
        <a href="#movies" class="group block py-3 px-4 rounded-lg hover:bg-white/10 transition-all flex items-center space-x-3 backdrop-blur-sm hover:shadow-lg hover:shadow-[var(--primary)]/20">
          <i data-lucide="film" class="w-5 h-5"></i><span>Phim</span>
        </a>
        <a href="#rooms" class="group block py-3 px-4 rounded-lg hover:bg-white/10 transition-all flex items-center space-x-3 backdrop-blur-sm hover:shadow-lg hover:shadow-[var(--primary)]/20">
          <i data-lucide="panel-top" class="w-5 h-5"></i><span>Phòng chiếu</span>
        </a>
        <a href="#shows" class="group block py-3 px-4 rounded-lg hover:bg-white/10 transition-all flex items-center space-x-3 backdrop-blur-sm hover:shadow-lg hover:shadow-[var(--primary)]/20">
          <i data-lucide="clock-8" class="w-5 h-5"></i><span>Suất chiếu</span>
        </a>
        <a href="#tickets" class="group block py-3 px-4 rounded-lg hover:bg-white/10 transition-all flex items-center space-x-3 backdrop-blur-sm hover:shadow-lg hover:shadow-[var(--primary)]/20">
          <i data-lucide="ticket" class="w-5 h-5"></i><span>Vé đã đặt</span>
        </a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-8" id="cinemas">
      <h2 class="text-4xl font-bold mb-8 text-[var(--primary)] drop-shadow-lg" font-semibold mb-6 text-[var(--primary)]">Quản lý rạp phim</h2>

      <div class="bg-white p-6 rounded-xl shadow mb-8">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-2xl font-semibold">Danh sách rạp phim</h3>
          <button onclick="openCinemaModal()" class="px-4 py-2 bg-[var(--primary)] text-white rounded-lg hover:bg-[var(--primary-dark)]">+ Thêm rạp</button>
        </div>

        <table class="w-full mt-4 border-collapse">
          <thead>
            <tr class="bg-gray-200 text-left">
              <th class="p-3">ID</th>
              <th class="p-3">Tên rạp</th>
              <th class="p-3">Địa chỉ</th>
              <th class="p-3">Số phòng</th>
              <th class="p-3">Hành động</th>
            </tr>
          </thead>
          <tbody id="cinemaTable">
            <!-- Dữ liệu load từ PHP -->
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- MODAL THÊM / SỬA PHIM -->
  <div id="movieModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white w-[450px] rounded-xl shadow p-6">
      <h2 id="modalTitle" class="text-xl font-bold mb-4">Thêm phim</h2>

      <form id="movieForm" class="space-y-4">
        <input type="hidden" id="movie_id" />

        <div>
          <label class="font-medium">Tên phim</label>
          <input id="movie_name" class="w-full p-2 border rounded" required />
        </div>

        <div>
          <label class="font-medium">Thể loại</label>
          <input id="movie_genre" class="w-full p-2 border rounded" required />
        </div>

        <div>
          <label class="font-medium">Thời lượng (phút)</label>
          <input id="movie_duration" type="number" class="w-full p-2 border rounded" required />
        </div>

        <div class="flex justify-end space-x-2">
          <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-400 text-white rounded">Hủy</button>
          <button type="submit" class="px-4 py-2 bg-[var(--primary)] text-white rounded">Lưu</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal(edit = false, data = null) {
      document.getElementById('movieModal').classList.remove('hidden');

      if (edit) {
        document.getElementById('modalTitle').innerText = "Sửa phim";
        document.getElementById('movie_id').value = data.id;
        document.getElementById('movie_name').value = data.name;
        document.getElementById('movie_genre').value = data.genre;
        document.getElementById('movie_duration').value = data.duration;
      } else {
        document.getElementById('modalTitle').innerText = "Thêm phim";
        document.getElementById('movieForm').reset();
        document.getElementById('movie_id').value = "";
      }
    }

    function closeModal() {
      document.getElementById('movieModal').classList.add('hidden');
    }

    document.getElementById('movieForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const id = document.getElementById('movie_id').value;
      const name = document.getElementById('movie_name').value;
      const genre = document.getElementById('movie_genre').value;
      const duration = document.getElementById('movie_duration').value;

      const action = id ? 'update_movie.php' : 'add_movie.php';

      fetch(action, {
        method: "POST",
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&name=${name}&genre=${genre}&duration=${duration}`
      })
      .then(res => res.text())
      .then(data => {
        alert(data);
        location.reload();
      });
    });
  </script>
  <!-- MODAL THÊM / SỬA RẠP -->
  <div id="cinemaModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center transition-opacity duration-300">
    <div class="bg-white w-[450px] rounded-xl shadow p-6 transform scale-75 opacity-0 transition-all duration-300" id="cinemaModalContent">
      <h2 id="cinemaModalTitle" class="text-xl font-bold mb-4">Thêm rạp</h2>

      <form id="cinemaForm" class="space-y-4"> id="cinemaForm" class="space-y-4">
        <input type="hidden" id="cinema_id" />

        <div>
          <label class="font-medium">Tên rạp</label>
          <input id="cinema_name" class="w-full p-2 border rounded" required />
        </div>

        <div>
          <label class="font-medium">Địa chỉ</label>
          <textarea id="cinema_address" class="w-full p-2 border rounded" required></textarea>
        </div>

        <div>
          <label class="font-medium">Số phòng</label>
          <input type="number" id="cinema_rooms" class="w-full p-2 border rounded" required />
        </div>

        <div class="flex justify-end space-x-2">
          <button type="button" onclick="closeCinemaModal()" class="px-4 py-2 bg-gray-400 text-white rounded">Hủy</button>
          <button type="submit" class="px-4 py-2 bg-[var(--primary)] text-white rounded">Lưu</button>
        </div>
      </form>
    </div>
  </div>

<script>
  function openCinemaModal(edit = false, data = null) {
    const modal = document.getElementById('cinemaModal');
    const box = document.getElementById('cinemaModalContent');

    modal.classList.remove('hidden');
    setTimeout(() => {
      modal.classList.add('opacity-100');
      box.classList.remove('scale-75','opacity-0');
      box.classList.add('scale-100','opacity-100');
    }, 10);

    if (edit) {(edit = false, data = null) {
    document.getElementById('cinemaModal').classList.remove('hidden');

    if (edit) {
      document.getElementById('cinemaModalTitle').innerText = "Sửa rạp";
      document.getElementById('cinema_id').value = data.id;
      document.getElementById('cinema_name').value = data.name;
      document.getElementById('cinema_address').value = data.address;
      document.getElementById('cinema_rooms').value = data.rooms;
    } else {
      document.getElementById('cinemaModalTitle').innerText = "Thêm rạp";
      document.getElementById('cinemaForm').reset();
      document.getElementById('cinema_id').value = "";
    }
  }

  function closeCinemaModal() {
    const modal = document.getElementById('cinemaModal');
    const box = document.getElementById('cinemaModalContent');

    box.classList.remove('scale-100','opacity-100');
    box.classList.add('scale-75','opacity-0');
    modal.classList.remove('opacity-100');

    setTimeout(() => {
      modal.classList.add('hidden');
    }, 300);
  }() {
    document.getElementById('cinemaModal').classList.add('hidden');
  }

  document.getElementById('cinemaForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('cinema_id').value;
    const name = document.getElementById('cinema_name').value;
    const address = document.getElementById('cinema_address').value;
    const rooms = document.getElementById('cinema_rooms').value;

    const action = id ? 'update_cinema.php' : 'add_cinema.php';

    fetch(action, {
      method: "POST",
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${id}&name=${name}&address=${address}&rooms=${rooms}`
    })
    .then(res => res.text())
    .then(data => {
      alert(data);
      location.reload();
    });
  });
</script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
</body>
</html>
