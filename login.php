<?php
session_start();

include __DIR__ . '/db/connect.php';
include __DIR__ . '/includes/functions.php';

if (isset($_SESSION['login'])) {
  header("Location: ./pages/dashboard/index.php");
  exit;
}

global $conn;

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Cek username di database
  $result = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$username'");

  if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);

    // Verifikasi password
    if (password_verify($password, $row['password'])) {
      $_SESSION['login'] = true;
      $_SESSION['username'] = $row['username'];
      $_SESSION['kd_user'] = $row['kd_user'];
      $_SESSION['name'] = $row['nama'];
      $_SESSION['role'] = $row['role'];
      $_SESSION['just_logged_in'] = true;

      header("Location: ./pages/dashboard/index.php");
      exit;
    }
  } else
    $error = true;
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
            Login untuk mulai mendapatkan hasil tingkat kesehatan mental Anda
            secara instan.
          </p>
        </div>
        <div class="box">
          <div class="card" style="width: 28rem; padding: 24px">
            <div class="card-body">
              <h4>Selamat Datang Kembali!</h4>
              <p>Masuk untuk melanjutkan</p>
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
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    required />
                </div>
                <?php if (isset($error)) : ?>
                  <p style="color: #e63946; text-align: left; margin-bottom: 0;">Username atau password salah!</p>
                <?php endif; ?>
                <button
                  type="submit"
                  class="btn btn-primary btn-md"
                  name="submit">
                  Masuk
                </button>
              </form>
              <a href="./register.php">Belum memiliki akun? Daftar</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Main auth end -->
</body>

</html>