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

// Dapatkan evaluasi model Naive Bayes
$url = "http://127.0.0.1:8000/model-evaluation";
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
  echo 'Error: ' . curl_error($ch);
}
curl_close($ch);

// Result evaluasi model
$result = json_decode($response, true);

// Result Aktual vs Prediksi
$url = "http://127.0.0.1:8000/actual-predicted";
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
  echo 'Error: ' . curl_error($ch);
}
curl_close($ch);

$result_actual_predicted = json_decode($response, true);

// Result confusion metrix metrics
$url = "http://127.0.0.1:8000/confusion-matrix-metrics";
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
  echo 'Error: ' . curl_error($ch);
}
curl_close($ch);

$result_metrics = json_decode($response, true);
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
  <title>Confusion Matrix | DASS-21</title>
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
              <li>
                <i class="ri-infinity-fill"></i>
                <a href="./probability.php">Probabilitas</a>
              </li>
              <li class="active">
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
              <h3>Confusion Matrix</h3>
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
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">
                Confusion Matrix
              </li>
            </ol>
          </nav>
          <!-- Breadcrumb end -->

          <!-- Data table start -->
          <section class="data-table">
            <div class="wrapper">
              <div class="card text-left">
                <img class="card-img-top" src="holder.js/100px180/" alt="" />
                <div class="card-body">
                  <h4 class="card-title">Confusion Matrix</h4>
                  <hr />
                  <div class="table-card-wrapper">
                    <table class="table table-bordered table-hover">
                      <h4 class="text-center mb-4">
                        Tabel Hasil Klasifikasi
                      </h4>
                      <thead>
                        <tr>
                          <th
                            rowspan="2"
                            class="text-center align-middle text-white bg-info">
                            Aktual / Prediksi
                          </th>
                          <th
                            colspan="5"
                            class="text-center text-white bg-info">
                            Predicted Label
                          </th>
                        </tr>
                        <tr>
                          <th class="text-center text-white bg-info">
                            Normal
                          </th>
                          <th class="text-center text-white bg-info">
                            Ringan
                          </th>
                          <th class="text-center text-white bg-info">
                            Sedang
                          </th>
                          <th class="text-center text-white bg-info">
                            Berat
                          </th>
                          <th class="text-center text-white bg-info">
                            Sangat Berat
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th
                            class="text-center font-weight-bold text-secondary">
                            Normal
                          </th>
                          <td class="text-center"><?= $result_actual_predicted['Normal']['Normal']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Normal']['Ringan']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Normal']['Sedang']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Normal']['Berat']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Normal']['Sangat Berat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            class="text-center font-weight-bold text-secondary">
                            Ringan
                          </th>
                          <td class="text-center"><?= $result_actual_predicted['Ringan']['Normal']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Ringan']['Ringan']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Ringan']['Sedang']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Ringan']['Berat']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Ringan']['Sangat Berat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            class="text-center font-weight-bold text-secondary">
                            Sedang
                          </th>
                          <td class="text-center"><?= $result_actual_predicted['Sedang']['Normal']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Sedang']['Ringan']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Sedang']['Sedang']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Sedang']['Berat']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Sedang']['Sangat Berat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            class="text-center font-weight-bold text-secondary">
                            Berat
                          </th>
                          <td class="text-center"><?= $result_actual_predicted['Berat']['Normal']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Berat']['Ringan']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Berat']['Sedang']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Berat']['Berat']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Berat']['Sangat Berat']; ?></td>
                        </tr>
                        <tr>
                          <th
                            class="text-center font-weight-bold text-secondary">
                            Sangat Berat
                          </th>
                          <td class="text-center"><?= $result_actual_predicted['Sangat Berat']['Normal']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Sangat Berat']['Ringan']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Sangat Berat']['Sedang']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Sangat Berat']['Berat']; ?></td>
                          <td class="text-center"><?= $result_actual_predicted['Sangat Berat']['Sangat Berat']; ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="table-card-wrapper">
                    <table class="table table-bordered table-hover">
                      <h4 class="text-center mb-4">Tabel Evaluasi Model</h4>
                      <thead>
                        <tr>
                          <th
                            rowspan="2"
                            class="text-center bg-success text-white align-middle">
                            Kelas
                          </th>
                          <th
                            colspan="4"
                            class="text-center bg-success text-white">
                            Predicted Label
                          </th>
                          <th
                            colspan="3"
                            class="text-center bg-success text-white">
                            Metrik
                          </th>
                        </tr>
                        <tr>
                          <th class="text-center bg-success text-white">
                            TP
                          </th>
                          <th class="text-center bg-success text-white">
                            TN
                          </th>
                          <th class="text-center bg-success text-white">
                            FP
                          </th>
                          <th class="text-center bg-success text-white">
                            FN
                          </th>
                          <th class="text-center bg-success text-white">
                            Precision
                          </th>
                          <th class="text-center bg-success text-white">
                            Recall
                          </th>
                          <th class="text-center bg-success text-white">
                            F1 Score
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td
                            class="text-center text-secondary font-weight-bold">
                            Normal
                          </td>
                          <td class="text-center"><?= $result_metrics['Normal']['tp']; ?></td>
                          <td class="text-center"><?= $result_metrics['Normal']['tn']; ?></td>
                          <td class="text-center"><?= $result_metrics['Normal']['fp']; ?></td>
                          <td class="text-center"><?= $result_metrics['Normal']['fn']; ?></td>
                          <td class="text-center"><?= $result['per_class']['Normal']['precision'] * 100 . '%'; ?></td>
                          <td class="text-center"><?= $result['per_class']['Normal']['recall'] * 100 . '%'; ?></td>
                          <td class="text-center"><?= $result['per_class']['Normal']['f1_score'] * 100 . '%'; ?></td>
                        </tr>
                        <tr>
                          <td
                            class="text-center text-secondary font-weight-bold">
                            Ringan
                          </td>
                          <td class="text-center"><?= $result_metrics['Ringan']['tp']; ?></td>
                          <td class="text-center"><?= $result_metrics['Ringan']['tn']; ?></td>
                          <td class="text-center"><?= $result_metrics['Ringan']['fp']; ?></td>
                          <td class="text-center"><?= $result_metrics['Ringan']['fn']; ?></td>
                          <td class="text-center"><?= $result['per_class']['Ringan']['precision'] * 100 . '%'; ?></td>
                          <td class="text-center"><?= $result['per_class']['Ringan']['recall'] * 100 . '%'; ?></td>
                          <td class="text-center"><?= $result['per_class']['Ringan']['f1_score'] * 100 . '%'; ?></td>
                        <tr>
                          <td
                            class="text-center text-secondary font-weight-bold">
                            Sedang
                          </td>
                          <td class="text-center"><?= $result_metrics['Sedang']['tp']; ?></td>
                          <td class="text-center"><?= $result_metrics['Sedang']['tn']; ?></td>
                          <td class="text-center"><?= $result_metrics['Sedang']['fp']; ?></td>
                          <td class="text-center"><?= $result_metrics['Sedang']['fn']; ?></td>
                          <td class="text-center"><?= $result['per_class']['Sedang']['precision'] * 100 . '%'; ?></td>
                          <td class="text-center"><?= $result['per_class']['Sedang']['recall'] * 100 . '%'; ?></td>
                          <td class="text-center"><?= $result['per_class']['Sedang']['f1_score'] * 100 . '%'; ?></td>
                        </tr>
                        <tr>
                          <td
                            class="text-center text-secondary font-weight-bold">
                            Berat
                          </td>
                          <td class="text-center"><?= $result_metrics['Berat']['tp']; ?></td>
                          <td class="text-center"><?= $result_metrics['Berat']['tn']; ?></td>
                          <td class="text-center"><?= $result_metrics['Berat']['fp']; ?></td>
                          <td class="text-center"><?= $result_metrics['Berat']['fn']; ?></td>
                          <td class="text-center"><?= $result['per_class']['Berat']['precision'] * 100 . '%'; ?></td>
                          <td class="text-center"><?= $result['per_class']['Berat']['recall'] * 100 . '%'; ?></td>
                          <td class="text-center"><?= $result['per_class']['Berat']['f1_score'] * 100 . '%'; ?></td>
                        </tr>
                        <tr>
                          <td
                            class="text-center text-secondary font-weight-bold">
                            Sangat Berat
                          </td>
                          <td class="text-center"><?= $result_metrics['Sangat Berat']['tp']; ?></td>
                          <td class="text-center"><?= $result_metrics['Sangat Berat']['tn']; ?></td>
                          <td class="text-center"><?= $result_metrics['Sangat Berat']['fp']; ?></td>
                          <td class="text-center"><?= $result_metrics['Sangat Berat']['fn']; ?></td>
                          <td class="text-center"><?= $result['per_class']['Sangat Berat']['precision'] * 100 . '%'; ?></td>
                          <td class="text-center"><?= $result['per_class']['Sangat Berat']['recall'] * 100 . '%'; ?></td>
                          <td class="text-center"><?= $result['per_class']['Sangat Berat']['f1_score'] * 100 . '%'; ?></td>
                        </tr>
                        <tr>
                          <td
                            class="text-center text-secondary font-weight-bold"
                            colspan="5">
                            Akurasi
                          </td>
                          <td class="text-center" colspan="3"><?= $result['accuracy'] * 100 . '%'; ?></td>
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