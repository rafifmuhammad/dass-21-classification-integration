<?php
session_start();

include './../../includes/functions.php';

if (!isset($_SESSION['login'])) {
  header("Location: ../../index.php");
  exit;
}

if ($_SESSION['role'] != 'Admin') {
  header("Location: index.php");
  exit;
}

$users = query("SELECT * FROM tb_user WHERE role = 'User'");
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
  <title>Pilih User Testing | DASS-21</title>
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
                onclick="location.href='./index.php'"></i>
              <a href="./index.php">Dashboard</a>
            </li>
            <?php if ($_SESSION['role'] == 'Admin') : ?>
              <li>
                <i
                  class="ri-group-line"
                  onclick="location.href='./user_management.php'"></i>
                <a href="./user_management.php">Manajemen Pengguna</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
        <div class="box">
          <h4>Learning & Testing</h4>
          <ul>
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
                    onclick="location.href='./data.php'"></i>
                  <a href="./data.php">Data</a>
                </li>
                <li>
                  <i class="ri-flask-line" onclick="location.href='./training.php'"></i>
                  <a href="./training.php">Training</a>
                </li>
                <li>
                  <i class="ri-test-tube-line" onclick="location.href='./testing.php'"></i>
                  <a href="./testing.php">Testing</a>
                </li>
              </ul>
              <!-- Dropdown menu end -->
            <?php endif; ?>
            <li class="active">
              <i class="ri-pages-line" onclick="location.href='<?php echo $_SESSION['role'] == 'Admin' ? './select_user.php' : './../pengujian/index.php'; ?>'"></i>
              <a href="<?php echo $_SESSION['role'] == 'Admin' ? './select_user.php' : './../pengujian/index.php'; ?>">Klasifikasi</a>
            </li>
            <li>
              <i
                class="ri-contract-line"
                onclick="location.href='./history.php'"></i>
              <a href="./history.php">Riwayat Pengujian</a>
            </li>
            <?php if ($_SESSION['role'] == 'Admin') : ?>
              <li>
                <i class="ri-infinity-fill" onclick="location.href='./probability.php'"></i>
                <a href="./probability.php">Probabilitas</a>
              </li>
              <li>
                <i class="ri-formula" onclick="location.href='./confusion_matrix.php'"></i>
                <a href="./confusion_matrix.php">Confusion Matrix</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
        <div class="box">
          <h4>Action</h4>
          <ul>
            <li>
              <i class="ri-logout-circle-line" onclick="location.href='./logout.php'"></i>
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
              <h3>Pilih User Testing</h3>
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
                <a href="./index.php">Klasifikasi</a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                Pilih User Testing
              </li>
            </ol>
          </nav>
          <!-- Breadcrumb end -->

          <!-- Form Pilih User Testing -->
          <div class="card p-5">
            <div>
              <h5 class="mb-4">Pilih User Testing</h5>
              <form action="./../pengujian/index.php" method="get">
                <div class="form-group">
                  <select class="form-control w-50 form-mobile" id="user_testing" name="user_testing">
                    <?php foreach ($users as $user) : ?>
                      <option value="<?= $user['username']; ?>"><?= $user['nama']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <button type="submit" class="btn btn-success btn-sm small-button" name="select_user">
                  Pilih
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