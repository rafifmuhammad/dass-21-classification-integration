<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../../index.php");
  exit;
}

if ($_SESSION['role'] != 'Admin') {
  header("Location: index.php");
  exit;
}

include './../../includes/functions.php';

$kdUser = $_GET['kd_user'];

$user = query("SELECT * FROM tb_user WHERE kd_user = '$kdUser'");

if (isset($_POST['submit'])) {
  if (updateUser($_POST) > 0) {
    $error = false;
    echo "
      <script>
        alert('Pengguna berhasil diubah!');
        document.location.href = './user_management.php';
      </script>
    ";
  } else {
    $error = true;
    // header("Location: edit_user.php?kd_user=$kdUser");
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
    href="./../../dist/bootstrap-4.0.0-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="./../../dist/css/dashboard-style.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
    rel="stylesheet" />
  <title>Edit User | DASS-21</title>
</head>

<body>
  <div class="main-app">
    <!-- Sidebar start -->
    <section class="sidebar">
      <div class="sidebar-box">
        <div class="box toggle-header">
          <h1>DASS-21</h1>
          <i class="ri-contract-left-line toggle-sidebar-btn"></i>
        </div>
        <div class="box">
          <h4>General</h4>
          <ul>
            <li>
              <i
                class="ri-dashboard-line"
                onclick="location.href='./index.html'"></i>
              <a href="./index.php">Dashboard</a>
            </li>
            <?php if ($_SESSION['role'] == 'Admin') : ?>
              <li class="active">
                <i
                  class="ri-group-line"
                  onclick="location.href='./user_management.html'"></i>
                <a href="./user_management.html">Manajemen Pengguna</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
        <div class="box">
          <h4>Learning & Testing</h4>
          <ul>
            <li>
              <i class="ri-pages-line"></i>
              <a href="<?php echo $_SESSION['role'] == 'Admin' ? './select_user.php' : './../pengujian/index.php'; ?>">Klasifikasi</a>
            </li>
            <li>
              <i
                class="ri-contract-line"
                onclick="location.href='./history.php'"></i>
              <a href="./history.php">Riwayat Pengujian</a>
            </li>
            <?php if ($_SESSION['role'] == 'Admin') : ?>
              <!-- Dropdown menu start -->
              <li class="dropdown">
                <div>
                  <i
                    class="ri-line-chart-line"
                    href="#submenuDataset"
                    data-toggle="collapse"
                    aria-expanded="false"></i>
                  <a
                    href="#submenuDataset"
                    data-toggle="collapse"
                    aria-expanded="false">Dataset</a>
                </div>
                <i class="ri-arrow-drop-down-line"></i>
              </li>
              <ul class="collapse pl-0 ml-0" id="submenuDataset">
                <li>
                  <i
                    class="ri-database-2-line"
                    onclick="location.href='./data.html'"></i>
                  <a href="./data.html">Data</a>
                </li>
                <li>
                  <i class="ri-flask-line"></i>
                  <a href="./training.php">Training</a>
                </li>
                <li>
                  <i class="ri-test-tube-line"></i>
                  <a href="./testing.html">Testing</a>
                </li>
              </ul>
              <!-- Dropdown menu end -->
            <?php endif; ?>
            <?php if ($_SESSION['role'] == 'Admin') : ?>
              <li>
                <i class="ri-infinity-fill"></i>
                <a href="./probability.html">Probabilitas</a>
              </li>
              <li>
                <i class="ri-formula"></i>
                <a href="./confusion_matrix.php">Confusion Matrix</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
        <div class="box">
          <h4>Action</h4>
          <ul>
            <li>
              <i class="ri-logout-circle-line"></i>
              <a href="./logout.php">Keluar</a>
            </li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Sidebar end -->

    <!-- Main wrapper start -->
    <section class="main-wrapper">
      <!-- Navbar start -->
      <section class="navbar">
        <div class="wrapper">
          <div class="navbar-box">
            <div class="box">
              <h3>Ubah Data Pengguna</h3>
            </div>
            <div class="box">
              <img src="./../../img/il-no-profile.jpg" alt="user_image" />
              <div>
                <h4><?= $_SESSION['name']; ?></h4>
                <p><?= $_SESSION['username']; ?></p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Navbar end -->

      <!-- Content start -->
      <section class="main-content">
        <div class="wrapper">
          <!-- Breadcrumb start -->
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="./index.php">Dashboard</a>
              </li>
              <li class="breadcrumb-item">
                <a href="./user_management.html">Manajemen Pengguna</a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                Ubah Data Pengguna
              </li>
            </ol>
          </nav>
          <!-- Breadcrumb end -->

          <!-- Form Edit User -->
          <div class="card p-5">
            <div>
              <h5 class="mb-4">Ubah Data Pengguna</h5>
              <?php if (isset($error)) : ?>
                <div class="alert <?php echo $error ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
                  <?php echo $error ? 'Pengguna Gagal Diubah' : 'Pengguna berhasil diubah'; ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php endif; ?>
              <form action="" method="post">
                <div class="form-group w-50">
                  <input type="hidden" name="kd_user" id="kd_user" value="<?= $kdUser; ?>">
                  <input
                    type="text"
                    class="form-control"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    value="<?= $user[0]['username']; ?>"
                    readonly />
                </div>
                <div class="form-group w-50">
                  <input
                    type="text"
                    class="form-control"
                    id="nama"
                    name="nama"
                    placeholder="Masukkan nama lengkap"
                    value="<?= $user[0]['nama']; ?>" />
                </div>
                <div class="form-group w-50">
                  <input
                    type="date"
                    class="form-control"
                    id="tanggal_lahir"
                    name="tanggal_lahir"
                    placeholder="Masukkan tanggal lahir"
                    value="<?= $user[0]['tanggal_lahir']; ?>" />
                </div>
                <div class="form-group">
                  <select class="form-control w-50" id="role" name="role" value='<?= $user[0]['role']; ?>'>
                    <option value="User">User</option>
                    <option value="Admin">Admin</option>
                  </select>
                </div>
                <div class="form-group w-50">
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    placeholder="Masukkan password" />
                </div>
                <button type="submit" class="btn btn-primary btn-sm" name="submit">
                  Submit
                </button>
              </form>
            </div>
          </div>
        </div>
      </section>
    </section>
  </div>

  <!-- Main wrapper end -->

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <!-- Bootstrap 4 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables BS4 -->
  <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js"></script>
  <script>
    $(document).ready(function() {
      $(".toggle-sidebar-btn").on("click", function() {
        $(".main-app").toggleClass("sidebar-collapsed");
      });
    });
  </script>
</body>

</html>