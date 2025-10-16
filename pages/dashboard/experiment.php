<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../../login.php");
  exit;
}

include './../../includes/functions.php';

$url = "http://127.0.0.1:8000/model-experiment";
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
  echo 'Error: ' . curl_error($ch);
}
curl_close($ch);

$result = json_decode($response, true);

if (isset($_GET['submit'])) {
  $split_percentage = $_GET['split_percentage'];
  $url = $url = "http://127.0.0.1:8000/model-experiment?split_percentage=$split_percentage";
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);

  if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
  }
  curl_close($ch);

  $result = json_decode($response, true);
  $success = true;
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
  <link rel="stylesheet" href="./../../dist/css/testing-style.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
    rel="stylesheet" />
  <title>Eksperimen Model | DASS-21</title>
</head>

<body>
  <!-- Navbar start -->
  <section class="navbar">
    <div class="wrapper">
      <div class="box-navbar">
        <div class="box">
          <a href="./../dashboard/index.php">DASS-21</a>
        </div>
        <div class="box">
          <ul>
            <li>
              <a href="./../dashboard/index.php">Keluar</a>
            </li>
            <li>
              <img src="./../../img/il-no-profile.jpg" alt="user_profile" />
              <div>
                <h4><?= $_SESSION['name']; ?></h4>
                <p><?= $_SESSION['username']; ?></p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>
  <!-- Navbar end -->

  <!-- Content start -->
  <div class="content">
    <div class="wrapper">
      <div class="box-content">
        <h4>Tentang Halaman Eksperimen</h4>
        <!-- Alert start -->
        <div
          class="alert alert-light alert-border-left-primary d-flex"
          role="alert">
          Halaman eksperimen merupakan halaman yang menampilkan hasil evaluasi
          model berdasarkan model-model machine learning yang diujicobakan. Di halaman ini pengguna
          dapat melihat bagaimana persentase recall, precision, dan f1-score,
          serta akurasi dari masing-masing model. Halaman eksperimen juga
          menyertakan bagaimana hasil skor model jika pembagian data dilakukan
          dalam persentase tertentu.
        </div>
        <!-- Alert end -->
        <!-- Data table start -->
        <section class="data-table">
          <div class="wrapper">
            <div class="card text-left">
              <div class="card-body">
                <h4 class="card-title">Tabel Eksperimen</h4>
                <hr />
                <?php if (isset($success)) : ?>
                  <div class="alert <?php echo $success ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                    <?php echo $success ? 'Berhasil melakukan split sebesar ' . $_GET['split_percentage'] * 100 . "% testing" : 'Gagal melakukan split'; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                <?php endif; ?>
                <div class="table-card-wrapper">
                  <table class="table table-bordered table-hover">
                    <div class="form-group d-flex justify-content-around align-items-center">
                      <form action="">
                        <label for="split_percentage">Tentukan Pembagian Data</label>
                        <select class="form-control col-md-4" id="split_percentage" name="split_percentage">
                          <option value="0.2">0.2 (Default)</option>
                          <option value="0.15">0.15</option>
                          <option value="0.3">0.3</option>
                          <option value="0.4">0.4</option>
                        </select>
                        <button class="btn btn-sm btn-primary" name="submit">Split Data</button>
                      </form>
                    </div>
                    <thead>
                      <tr>
                        <th
                          rowspan="2"
                          class="text-center align-middle bg-info text-white">
                          Model
                        </th>
                        <th
                          class="text-center align-middle bg-info text-white">
                          Recall
                        </th>
                        <th
                          class="text-center align-middle bg-info text-white">
                          Precision
                        </th>
                        <th
                          class="text-center align-middle bg-info text-white">
                          F1-Score
                        </th>
                        <th
                          class="text-center align-middle bg-info text-white">
                          Akurasi
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td
                          class="text-dark font-weight-bold">
                          Naive Bayes
                        </td>
                        <td class="text-center"><?= $result['naive_bayes']['recall'] * 100 . "%"; ?></td>
                        <td class="text-center"><?= $result['naive_bayes']['precision'] * 100 . "%"; ?></td>
                        <td class="text-center"><?= $result['naive_bayes']['f1_score'] * 100 . "%"; ?></td>
                        <td class="text-center"><?= $result['naive_bayes']['akurasi'] * 100 . "%"; ?></td>
                      </tr>
                      <tr>
                        <td
                          class="text-dark font-weight-bold">
                          Decision Tree
                        </td>
                        <td class="text-center"><?= $result['decision_tree']['recall'] * 100 . "%"; ?></td>
                        <td class="text-center"><?= $result['decision_tree']['precision'] * 100 . "%"; ?></td>
                        <td class="text-center"><?= $result['decision_tree']['f1_score'] * 100 . "%"; ?></td>
                        <td class="text-center"><?= $result['decision_tree']['akurasi'] * 100 . "%"; ?></td>
                      </tr>
                      <tr>
                        <td
                          class="text-dark font-weight-bold">
                          K-Nearest Neighbors
                        </td>
                        <td class="text-center"><?= $result['knn']['recall'] * 100 . "%"; ?></td>
                        <td class="text-center"><?= $result['knn']['precision'] * 100 . "%"; ?></td>
                        <td class="text-center"><?= $result['knn']['f1_score'] * 100 . "%"; ?></td>
                        <td class="text-center"><?= $result['knn']['akurasi'] * 100 . "%"; ?></td>
                      </tr>
                      <tr>
                        <td
                          class="text-dark font-weight-bold">
                          Support Vector Machine
                        </td>
                        <td class="text-center"><?= $result['svm']['recall'] * 100 . "%"; ?></td>
                        <td class="text-center"><?= $result['svm']['precision'] * 100 . "%"; ?></td>
                        <td class="text-center"><?= $result['svm']['f1_score'] * 100 . "%"; ?></td>
                        <td class="text-center"><?= $result['svm']['akurasi'] * 100 . "%"; ?></td>
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
    </div>
  </div>
  </div>
  <!-- Content end -->

  <!-- Footer start -->
  <section class="footer">
    <div class="wrapper">
      <div class="box-footer">
        <div class="box">
          <h1>DASS-21</h1>
        </div>
        <div class="box">
          <ul>
            <li>
              <h2>Website</h2>
            </li>
            <li><a href="https://en.wikipedia.org/wiki/DASS_(psychology)">Skala DASS-21</a></li>
          </ul>
          <ul>
            <li>
              <h2>Metode dan Data</h2>
            </li>
            <li><a href="https://en.wikipedia.org/wiki/Naive_Bayes_classifier">Naive Bayes</a></li>
            <li><a href="https://data.mendeley.com/datasets/br82d4xkj7/1">Sumber Data</a></li>
            <li><a href="./experiment.php">Eksperimen</a></li>
          </ul>
        </div>
      </div>
      <p>© 2025 RafifBanner</p>
    </div>
  </section>
  <!-- Footer end -->

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
</body>

</html>