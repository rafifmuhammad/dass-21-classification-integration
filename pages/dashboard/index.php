<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../../index.php");
  exit;
}

$showLoader = false;
if (isset($_SESSION['just_logged_in']) && $_SESSION['just_logged_in']) {
  $showLoader = true;
  unset($_SESSION['just_logged_in']);
}

include './../../includes/functions.php';

$jumlahUser = query("SELECT count(*) as jumlah_user FROM tb_user");
$jumlahDataTraining = query("SELECT count(*) as jumlah_data FROM tb_data WHERE Jenis = 'Training'");
$jumlahDataTesting = query("SELECT count(*) as jumlah_data FROM tb_data WHERE Jenis = 'Testing'");
$jumlahPengujian = query("SELECT count(*) as jumlah_data FROM tb_pengujian");
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
  <title>Dashboard | DASS-21</title>
</head>

<body>
  <?php if ($showLoader) : ?>
    <div id="page-loader">
      <div class="loader"></div>
    </div>
  <?php endif; ?>
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
            <li class="active">
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
              <ul class="collapse list-unstyled" id="submenuDataset">
                <li>
                  <i
                    class="ri-database-2-line"
                    onclick="location.href='./data.php'"></i>
                  <a href="./data.php">Data</a>
                </li>
                <li>
                  <i class="ri-flask-line"></i>
                  <a href="./training.php">Training</a>
                </li>
                <li>
                  <i class="ri-test-tube-line"></i>
                  <a href="./testing.php">Testing</a>
                </li>
              </ul>
              <!-- Dropdown menu end -->
            <?php endif; ?>
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
              <li>
                <i class="ri-infinity-fill"></i>
                <a href="./probability.php">Probabilitas</a>
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
              <h3>Dashboard</h3>
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
          <div class="box-main-content">
            <div class="box">
              <div>
                <div>
                  <i class="ri-user-line"></i>
                  <h4>Pengguna</h4>
                </div>
                <p>Rentang 30 hari</p>
              </div>
              <div>
                <h5><?= $jumlahUser[0]['jumlah_user']; ?> Pengguna</h5>
                <div>
                  <p>4</p>
                  <i class="ri-arrow-right-up-line"></i>
                </div>
              </div>
            </div>
            <div class="box">
              <div>
                <div>
                  <i class="ri-test-tube-line"></i>
                  <h4>Training</h4>
                </div>
                <p>Rentang 30 hari</p>
              </div>
              <div>
                <h5><?= $jumlahDataTraining[0]['jumlah_data']; ?> Data</h5>
                <div>
                  <p>852</p>
                  <i class="ri-arrow-right-up-line"></i>
                </div>
              </div>
            </div>
            <div class="box">
              <div>
                <div>
                  <i class="ri-flask-line"></i>
                  <h4>Testing</h4>
                </div>
                <p>Rentang 30 hari</p>
              </div>
              <div>
                <h5><?= $jumlahDataTesting[0]['jumlah_data']; ?> Data</h5>
                <div>
                  <p>252</p>
                  <i class="ri-arrow-right-up-line"></i>
                </div>
              </div>
            </div>
            <div class="box">
              <div>
                <div>
                  <i class="ri-questionnaire-line"></i>
                  <h4>Pengujian</h4>
                </div>
                <p>Rentang 30 hari</p>
              </div>
              <div>
                <h5><?= $jumlahPengujian[0]['jumlah_data']; ?> Dilakukan</h5>
                <div>
                  <p>3</p>
                  <i class="ri-arrow-right-up-line"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Content end -->
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
    document.addEventListener("DOMContentLoaded", function() {
      const loader = document.getElementById("page-loader");
      // kasih sedikit delay biar animasi kelihatan
      setTimeout(() => loader.classList.add("hidden"), 3000);
    });
  </script>
</body>

</html>