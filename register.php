<?php
session_start();

include __DIR__ . '/includes/functions.php';

if (isset($_SESSION['login'])) {
  header("Location: ./pages/dashboard/index.php");
  exit;
}

if (isset($_POST['submit'])) {
  if (addUser($_POST) > 0) {
    echo "
      <script>
        alert('Registrasi berhasil!');
        window.location.href = './login.php';
      </script>
    ";
    exit;
  } else {
    echo "
      <script>
        alert('Registrasi gagal!');
      </script>
    ";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;900&display=swap"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="./dist/bootstrap-4.0.0-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="./dist/css/style.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <title>Login | DASS-21</title>
</head>

<body>
  <!-- Main auth start -->
  <section class="main-auth">
    <div class="wrapper">
      <div class="box-main-auth">
        <div class="box">
          <img src="./img/ilustration-login.jpg" alt="ilustation-1" />
          <p>
            Daftar sekarang untuk mendapatkan akses penuh ke semua fitur dan layanan evaluasi kesehatan mental mandiri. Prosesnya cepat dan mudah, cukup isi data diri dan buat akun.
          </p>
        </div>
        <div class="box">
          <div class="card" style="width: 28rem; padding: 24px">
            <div class="card-body">
              <h4>Daftar Akun</h4>
              <p></p>
              <form action="" method="post">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    required />
                </div>
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="nama"
                    name="nama"
                    placeholder="Masukkan nama"
                    required />
                </div>
                <div class="form-group">
                  <input
                    type="date"
                    class="form-control"
                    id="tanggal_lahir"
                    name="tanggal_lahir"
                    placeholder="Masukkan tanggal lahir"
                    required />
                </div>
                <div class="form-group">
                  <select
                    type="select"
                    class="form-control"
                    id="role"
                    name="role"
                    placeholder="Masukkan role"
                    required>
                    <option value="User">User</option>
                    <option value="Admin">Admin</option>
                  </select>
                </div>
                <div class="form-group">
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    required />
                </div>
                <button
                  type="submit"
                  class="btn btn-primary btn-md"
                  name="submit">
                  Daftar
                </button>
              </form>
              <a href="./login.php">Telah memiliki akun? Masuk</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Main auth end -->
</body>

</html>