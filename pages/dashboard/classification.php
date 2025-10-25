<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../../index.php");
  exit;
}

include './../../includes/functions.php';

$kd_pengujian = empty($_GET['kd_pengujian']) ? null : $_GET['kd_pengujian'];

if (!empty($kd_pengujian)) {

  $data = query("SELECT * FROM tb_pengujian WHERE kd_pengujian = '$kd_pengujian'");

  // Dapatkan nilai parameter
  $p1 = $data[0]['P1'];
  $p2 = $data[0]['P2'];
  $p3 = $data[0]['P3'];

  // Dapatkan hasil klasifikasi
  $url = "http://127.0.0.1:8000/classification-result?" .
    "p1=" . urlencode($p1) . "&" .
    "p2=" . urlencode($p2) . "&" .
    "p3=" . urlencode($p3);
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);

  if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
  }
  curl_close($ch);

  $result = json_decode($response, true);

  updateTest($kd_pengujian, $result['result']);
} else {
  $data = [
    'p1' => $_GET['p1'],
    'p2' => $_GET['p2'],
    'p3' => $_GET['p3'],
  ];
  $p1 = $data['p1'];
  $p2 = $data['p2'];
  $p3 = $data['p3'];

  // Dapatkan hasil klasifikasi
  $url = "http://127.0.0.1:8000/classification-result?" .
    "p1=" . urlencode($p1) . "&" .
    "p2=" . urlencode($p2) . "&" .
    "p3=" . urlencode($p3);
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);

  if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
  }
  curl_close($ch);

  $result = json_decode($response, true);
}

// Set Information
$info = [
  "normal" => "Berdasarkan hasil evaluasi, tingkat kesehatan mental Anda berada dalam kategori Normal. Artinya, saat ini Anda tidak menunjukkan gejala yang mengarah pada stres, kecemasan, maupun depresi yang signifikan. Meskipun demikian, tetap penting untuk menjaga keseimbangan emosional dengan menerapkan pola hidup sehat, menjaga waktu istirahat, serta meluangkan waktu untuk kegiatan positif dan relaksasi.",
  "ringan" => "Berdasarkan hasil evaluasi, tingkat kesehatan mental Anda berada dalam kategori Ringan. Artinya, Anda menunjukkan adanya gejala stres, kecemasan, atau depresi, namun masih dalam tingkat yang relatif rendah. Kondisi ini biasanya dapat diatasi dengan menjaga keseimbangan aktivitas, memperhatikan pola tidur, serta meluangkan waktu untuk relaksasi dan aktivitas yang menyenangkan.",
  "sedang" => "Berdasarkan hasil evaluasi, tingkat kesehatan mental Anda berada dalam kategori Sedang. Artinya, gejala stres, kecemasan, atau depresi yang Anda alami cukup nyata dan dapat memengaruhi keseharian. Penting bagi Anda untuk mulai menerapkan strategi pengelolaan stres yang lebih konsisten, menjaga komunikasi dengan orang terdekat, serta mempertimbangkan untuk berkonsultasi dengan tenaga profesional bila dirasa perlu.",
  "berat" => "Berdasarkan hasil evaluasi, tingkat kesehatan mental Anda berada dalam kategori Berat. Artinya, gejala stres, kecemasan, atau depresi yang muncul cukup signifikan dan berpotensi mengganggu aktivitas harian maupun kualitas hidup Anda. Pada kondisi ini, sangat disarankan untuk mencari dukungan, baik melalui lingkungan sosial maupun bantuan dari tenaga profesional, agar Anda mendapatkan strategi penanganan yang tepat.",
  "sangat_berat" => "Berdasarkan hasil evaluasi, tingkat kesehatan mental Anda berada dalam kategori Sangat Berat. Artinya, Anda menunjukkan gejala stres, kecemasan, atau depresi yang cukup serius sehingga dapat berdampak besar terhadap keseharian dan kesejahteraan emosional. Pada kondisi ini, penting bagi Anda untuk segera mendapatkan bantuan profesional agar dapat memperoleh dukungan dan penanganan yang sesuai.",
];


// Probabilitas Prior
// $probabilitasPrior = [
//   "normal" => number_format($jumlahKelas['jumlahNormal'] / $jumlahData[0]['banyak_data'], 4, '.'),
//   "ringan" => number_format($jumlahKelas['jumlahRingan'] / $jumlahData[0]['banyak_data'], 4, '.'),
//   "sedang" => number_format($jumlahKelas['jumlahSedang'] / $jumlahData[0]['banyak_data'], 4, '.'),
//   "berat" => number_format($jumlahKelas['jumlahBerat'] / $jumlahData[0]['banyak_data'], 4, '.'),
//   "sangatBerat" => number_format($jumlahKelas['jumlahSangatBerat'] / $jumlahData[0]['banyak_data'], 4, '.')
// ];

// Data testing Normal, Ringan, Normal
// $p1Normal = banyakData($data, 'P1', 'Normal');
// $p2Ringan = banyakData($data, 'P2', 'Ringan');
// $p3Normal = banyakData($data, 'P3', 'Normal');

// Hitung Probabilitas Kondisional
// $probabilitasP1Normal = hitungProbabilitasKondisional($p1Normal, $jumlahKelas);
// $probabilitasP2Ringan = hitungProbabilitasKondisional($p2Ringan, $jumlahKelas);
// $probabilitasP3Normal = hitungProbabilitasKondisional($p3Normal, $jumlahKelas);

// Hitung Likelihood
// $likelihoodNormal = convertTo4Decimals($probabilitasPrior['normal'] * $probabilitasP1Normal['Normal'] * $probabilitasP2Ringan['Normal'] * $probabilitasP3Normal['Normal']);
// $likelihoodRingan = convertTo4Decimals($probabilitasPrior['ringan'] * $probabilitasP1Normal['Ringan'] * $probabilitasP2Ringan['Ringan'] * $probabilitasP3Normal['Ringan']);
// $likelihoodSedang = convertTo4Decimals($probabilitasPrior['sedang'] * $probabilitasP1Normal['Sedang'] * $probabilitasP2Ringan['Sedang'] * $probabilitasP3Normal['Sedang']);
// $likelihoodBerat = convertTo4Decimals($probabilitasPrior['berat'] * $probabilitasP1Normal['Berat'] * $probabilitasP2Ringan['Berat'] * $probabilitasP3Normal['Berat']);
// $likelihoodSangatBerat = convertTo4Decimals($probabilitasPrior['sangatBerat'] * $probabilitasP1Normal['SangatBerat'] * $probabilitasP2Ringan['SangatBerat'] * $probabilitasP3Normal['SangatBerat']);

// Hitung Probabilitas Posterior
// $probabilitasNormal = number_format($likelihoodNormal / ($likelihoodNormal + $likelihoodRingan + $likelihoodSedang + $likelihoodBerat + $likelihoodSangatBerat), 4, '.');
// $probabilitasRingan = number_format($likelihoodRingan / ($likelihoodNormal + $likelihoodRingan + $likelihoodSedang + $likelihoodBerat + $likelihoodSangatBerat), 4, '.');
// $probabilitasSedang = number_format($likelihoodSedang / ($likelihoodNormal + $likelihoodRingan + $likelihoodSedang + $likelihoodBerat + $likelihoodSangatBerat), 4, '.');
// $probabilitasBerat = number_format($likelihoodBerat / ($likelihoodNormal + $likelihoodRingan + $likelihoodSedang + $likelihoodBerat + $likelihoodSangatBerat), 4, '.');
// $probabilitasSangatBerat = number_format($likelihoodSangatBerat  / ($likelihoodNormal + $likelihoodRingan + $likelihoodSedang + $likelihoodBerat + $likelihoodSangatBerat), 4, '.');
?>
<!DOCTYPE php>
<php lang="en">

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
    <link rel="stylesheet" href="./../../dist/css/dashboard-style.css?v=1.2" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
      rel="stylesheet" />
    <title>Klasifikasi | DASS-21</title>
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
              <li>
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
                <h3>Klasifikasi</h3>
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
                  <a href="./testing.php">Testing</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                  Klasifikasi
                </li>
              </ol>
            </nav>
            <!-- Breadcrumb end -->

            <!-- Data table start -->
            <section class="data-table">
              <div class="wrapper">
                <div class="card text-left">
                  <div class="card-body">
                    <h4 class="card-title">Klasifikasi</h4>
                    <hr />
                    <div class="table-card-wrapper">
                      <table class="table table-bordered table-hover">
                        <h5 class="text-center mb-2">
                          Data Input
                        </h5>
                        <thead>
                          <tr>
                            <th
                              scope="col"
                              class="text-center align-middle bg-success text-white">
                              Tingkat Kesehatan Mental
                            </th>
                            <th
                              scope="col"
                              class="text-center bg-success text-white">
                              P1<br />Depresi
                            </th>
                            <th
                              scope="col"
                              class="text-center bg-success text-white">
                              P2<br />Kecemasan
                            </th>
                            <th
                              scope="col"
                              class="text-center bg-success text-white">
                              P3<br />Stres
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th
                              scope="row"
                              class="text-dark text-center font-weight-bold">
                              Kategori
                            </th>
                            <td class="text-center"><?= !empty($kd_pengujian) ? $data[0]['P1'] : $data['p1']; ?></td>
                            <td class="text-center"><?= !empty($kd_pengujian) ? $data[0]['P2'] : $data['p2']; ?></td>
                            <td class="text-center"><?= !empty($kd_pengujian) ? $data[0]['P3'] : $data['p3']; ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!-- Result card start -->
                    <div
                      class="result-card mt-4"
                      data-aos="fade-down"
                      data-aos-duration="1000"
                      data-aos-delay="100">
                      <div class="box-result-card card">
                        <div
                          class="box"
                          data-aos="fade-right"
                          data-aos-duration="1000"
                          data-aos-delay="300">
                          <img
                            src="./../../img/il-result.jpg"
                            alt="ilustration-result" />
                        </div>
                        <div class="box">
                          <h4
                            data-aos="fade-left"
                            data-aos-duration="1000"
                            data-aos-delay="500">
                            <?= $result['result']; ?>
                          </h4>
                          <p
                            data-aos="fade-left"
                            data-aos-duration="1000"
                            data-aos-delay="500">
                            Tingkat kesehatan mental Anda diklasifikasikan
                            sebagai <strong><?= $result['result']; ?></strong>
                          </p>
                          <p
                            data-aos="fade-left"
                            data-aos-duration="1000"
                            data-aos-delay="500">
                            <?php if ($result['result'] == 'Normal') : ?>
                              <?= $info['normal']; ?>
                            <?php elseif ($result['result'] == 'Ringan') : ?>
                              <?= $info['ringan']; ?>
                            <?php elseif ($result['result'] == 'Sedang') : ?>
                              <?= $info['sedang']; ?>
                            <?php elseif ($result['result'] == 'Berat') : ?>
                              <?= $info['berat']; ?>
                            <?php else : ?>
                              <?= $info['sangat_berat']; ?>
                            <?php endif; ?>
                          </p>
                          <a
                            href="./index.php"
                            class="btn btn-danger mt-4 btn-sm"
                            data-aos="fade-left"
                            data-aos-duration="1000"
                            data-aos-delay="500"><i class="ri-home-4-line"></i> Kembali</a>
                        </div>
                      </div>
                    </div>
                    <!-- Result card end -->
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

    <script
      src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
      integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
      crossorigin="anonymous"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
      integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
      crossorigin="anonymous"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
      integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
      crossorigin="anonymous"></script>
    <script>
      $(document).ready(function() {
        $(".toggle-sidebar-btn").on("click", function() {
          $(".main-app").toggleClass("sidebar-collapsed");
        });
      });
      window.onload = function() {
        window.scrollTo(0, document.body.scrollHeight);
      };
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
      AOS.init();
    </script>
  </body>

</php>