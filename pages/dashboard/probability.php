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

$data = query("SELECT * FROM tb_data WHERE Jenis = 'Training'");

$jumlahKelas = banyakKelas($data);
$jumlahData = query("SELECT count(*) as banyak_data FROM tb_data WHERE Jenis = 'Training'");

// Probabilitas Prior
$probabilitasPrior = [
  "normal" => number_format($jumlahKelas['jumlahNormal'] / $jumlahData[0]['banyak_data'], 4, '.'),
  "ringan" => number_format($jumlahKelas['jumlahRingan'] / $jumlahData[0]['banyak_data'], 4, '.'),
  "sedang" => number_format($jumlahKelas['jumlahSedang'] / $jumlahData[0]['banyak_data'], 4, '.'),
  "berat" => number_format($jumlahKelas['jumlahBerat'] / $jumlahData[0]['banyak_data'], 4, '.'),
  "sangatBerat" => number_format($jumlahKelas['jumlahSangatBerat'] / $jumlahData[0]['banyak_data'], 4, '.')
];

// Probabilitas Kondisional
// Jumlah parameter 1
$p1Normal = banyakData($data, 'P1', 'Normal');
$p1Ringan = banyakData($data, 'P1', 'Ringan');
$p1Sedang = banyakData($data, 'P1', 'Sedang');
$p1Berat = banyakData($data, 'P1', 'Berat');
$p1SangatBerat = banyakData($data, 'P1', 'Sangat Berat');

// Jumlah parameter 2
$p2Normal = banyakData($data, 'P2', 'Normal');
$p2Ringan = banyakData($data, 'P2', 'Ringan');
$p2Sedang = banyakData($data, 'P2', 'Sedang');
$p2Berat = banyakData($data, 'P2', 'Berat');
$p2SangatBerat = banyakData($data, 'P2', 'Sangat Berat');

// Jumlah parameter 3
$p3Normal = banyakData($data, 'P3', 'Normal');
$p3Ringan = banyakData($data, 'P3', 'Ringan');
$p3Sedang = banyakData($data, 'P3', 'Sedang');
$p3Berat = banyakData($data, 'P3', 'Berat');
$p3SangatBerat = banyakData($data, 'P3', 'Sangat Berat');

// Hitung Probabilitas Kondisional Tiap Parameter
// Parameter 1
$probabilitasP1Normal = hitungProbabilitas($p1Normal, $jumlahKelas);
$probabilitasP1Ringan = hitungProbabilitas($p1Ringan, $jumlahKelas);
$probabilitasP1Sedang = hitungProbabilitas($p1Sedang, $jumlahKelas);
$probabilitasP1Berat = hitungProbabilitas($p1Berat, $jumlahKelas);
$probabilitasP1SangatBerat = hitungProbabilitas($p1SangatBerat, $jumlahKelas);

// Parameter 2
$probabilitasP2Normal = hitungProbabilitas($p2Normal, $jumlahKelas);
$probabilitasP2Ringan = hitungProbabilitas($p2Ringan, $jumlahKelas);
$probabilitasP2Sedang = hitungProbabilitas($p2Sedang, $jumlahKelas);
$probabilitasP2Berat = hitungProbabilitas($p2Berat, $jumlahKelas);
$probabilitasP2SangatBerat = hitungProbabilitas($p2SangatBerat, $jumlahKelas);

// Parameter 3
$probabilitasP3Normal = hitungProbabilitas($p3Normal, $jumlahKelas);
$probabilitasP3Ringan = hitungProbabilitas($p3Ringan, $jumlahKelas);
$probabilitasP3Sedang = hitungProbabilitas($p3Sedang, $jumlahKelas);
$probabilitasP3Berat = hitungProbabilitas($p3Berat, $jumlahKelas);
$probabilitasP3SangatBerat = hitungProbabilitas($p3SangatBerat, $jumlahKelas);
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
  <title>Probability | DASS-21</title>
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
              <li>
                <i
                  class="ri-group-line"
                  onclick="location.href='./user_management.html'"></i>
                <a href="./user_management.php">Manajemen Pengguna</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
        <div class="box">
          <h4>Learning & Testing</h4>
          <ul>
            <li>
              <i class="ri-pages-line"></i>
              <a href="<?php echo $_SESSION['role'] == 'Admin' ? './select_user.php' : './../pengujian/index.php'; ?>">Ikuti Tes</a>
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
            <?php if ($_SESSION['role'] == 'Admin') : ?>
              <li class="active">
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
              <h3>Probabilitas</h3>
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
              <li class="breadcrumb-item"><a href="./index.php">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">
                Probabilitas
              </li>
            </ol>
          </nav>
          <!-- Breadcrumb end -->

          <!-- Data table start -->
          <section class="data-table">
            <div class="wrapper">
              <div class="card text-left">
                <div class="card-body">
                  <h4 class="card-title">Probabilitas</h4>
                  <hr />
                  <div class="table-card-wrapper">
                    <table class="table table-bordered table-hover">
                      <h4 class="text-center mb-4">Probabilitas Prior</h4>
                      <thead>
                        <tr>
                          <th
                            rowspan="2"
                            class="text-center align-middle bg-info text-white">
                            Tingkat<br />Kesehatan<br />Mental
                          </th>
                          <th
                            class="text-center align-middle bg-info text-white">
                            Normal
                          </th>
                          <th
                            class="text-center align-middle bg-info text-white">
                            Ringan
                          </th>
                          <th
                            class="text-center align-middle bg-info text-white">
                            Sedang
                          </th>
                          <th
                            class="text-center align-middle bg-info text-white">
                            Berat
                          </th>
                          <th
                            class="text-center align-middle bg-info text-white">
                            Sangat Berat
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td
                            class="text-center text-dark text-center font-weight-bold">
                            Probabilitas
                          </td>
                          <td class="text-center"><?php echo $probabilitasPrior['normal']; ?></td>
                          <td class="text-center"><?php echo $probabilitasPrior['ringan']; ?></td>
                          <td class="text-center"><?php echo $probabilitasPrior['sedang']; ?></td>
                          <td class="text-center"><?php echo $probabilitasPrior['berat']; ?></td>
                          <td class="text-center"><?php echo $probabilitasPrior['sangatBerat']; ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="table-card-wrapper">
                    <table class="table table-bordered table-hover">
                      <h4 class="text-center mb-4">
                        Probabilitas Kondisional
                      </h4>
                      <h5 class="text-center mb-2">
                        Probabilitas Kondisional untuk Parameter Depresi
                      </h5>
                      <thead>
                        <tr>
                          <th
                            scope="col"
                            class="text-center align-middle bg-success text-white">
                            P1
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Normal
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Ringan
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Sedang
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Berat
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Sangat Berat
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Normal
                          </th>
                          <td class="text-center"><?= $probabilitasP1Normal['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Normal['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Normal['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Normal['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Normal['SangatBerat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Ringan
                          </th>
                          <td class="text-center"><?= $probabilitasP1Ringan['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Ringan['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Ringan['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Ringan['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Ringan['SangatBerat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Sedang
                          </th>
                          <td class="text-center"><?= $probabilitasP1Sedang['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Sedang['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Sedang['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Sedang['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Sedang['SangatBerat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Berat
                          </th>
                          <td class="text-center"><?= $probabilitasP1Berat['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Berat['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Berat['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Berat['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP1Berat['SangatBerat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Sangat Berat
                          </th>
                          <td class="text-center"><?= $probabilitasP1SangatBerat['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP1SangatBerat['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP1SangatBerat['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP1SangatBerat['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP1SangatBerat['SangatBerat']; ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="table-card-wrapper">
                    <table class="table table-bordered table-hover">
                      <h5 class="text-center mb-2">
                        Probabilitas Kondisional untuk Parameter Kecemasan
                      </h5>
                      <thead>
                        <tr>
                          <th
                            scope="col"
                            class="text-center align-middle bg-success text-white">
                            P2
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Normal
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Ringan
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Sedang
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Berat
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Sangat Berat
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Normal
                          </th>
                          <td class="text-center"><?= $probabilitasP2Normal['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP2Normal['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP2Normal['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP2Normal['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP2Normal['SangatBerat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Ringan
                          </th>
                          <td class="text-center"><?= $probabilitasP2Ringan['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP2Ringan['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP2Ringan['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP2Ringan['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP2Ringan['SangatBerat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Sedang
                          </th>
                          <td class="text-center"><?= $probabilitasP3Sedang['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Sedang['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Sedang['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Sedang['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Sedang['SangatBerat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Berat
                          </th>
                          <td class="text-center"><?= $probabilitasP2Berat['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP2Berat['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP2Berat['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP2Berat['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP2Berat['SangatBerat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Sangat Berat
                          </th>
                          <td class="text-center"><?= $probabilitasP2SangatBerat['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP2SangatBerat['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP2SangatBerat['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP2SangatBerat['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP2SangatBerat['SangatBerat']; ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="table-card-wrapper">
                    <table class="table table-bordered table-hover">
                      <h5 class="text-center mb-2">
                        Probabilitas Kondisional untuk Parameter Stres
                      </h5>
                      <thead>
                        <tr>
                          <th
                            scope="col"
                            class="text-center align-middle bg-success text-white">
                            P3
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Normal
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Ringan
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Sedang
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Berat
                          </th>
                          <th
                            scope="col"
                            class="text-center bg-success text-white">
                            Sangat Berat
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Normal
                          </th>
                          <td class="text-center"><?= $probabilitasP3Normal['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Normal['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Normal['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Normal['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Normal['SangatBerat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Ringan
                          </th>
                          <td class="text-center"><?= $probabilitasP3Ringan['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Ringan['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Ringan['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Ringan['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Ringan['SangatBerat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Sedang
                          </th>
                          <td class="text-center"><?= $probabilitasP3Sedang['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Sedang['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Sedang['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Sedang['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Sedang['SangatBerat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Berat
                          </th>
                          <td class="text-center"><?= $probabilitasP3Berat['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Berat['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Berat['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Berat['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP3Berat['SangatBerat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            scope="row"
                            class="text-dark text-center font-weight-bold">
                            Sangat Berat
                          </th>
                          <td class="text-center"><?= $probabilitasP3SangatBerat['Normal']; ?></td>
                          <td class="text-center"><?= $probabilitasP3SangatBerat['Ringan']; ?></td>
                          <td class="text-center"><?= $probabilitasP3SangatBerat['Sedang']; ?></td>
                          <td class="text-center"><?= $probabilitasP3SangatBerat['Berat']; ?></td>
                          <td class="text-center"><?= $probabilitasP3SangatBerat['SangatBerat']; ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
        <!-- Data table end -->
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