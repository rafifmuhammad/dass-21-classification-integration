<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../../index.php");
  exit;
}

include './../../includes/functions.php';

$kd_pengujian = uniqid();

if ($_SESSION['role'] != 'Admin') {

  if (isset($_POST['submit'])) {
    if (addData($_POST, $kd_pengujian, $_SESSION['kd_user']) > 0) {
      $error = false;
      header('Location: ./../dashboard/classification.php?kd_pengujian=' . $kd_pengujian);
    } else {
      var_dump(mysqli_errno($conn));
      $error = true;
    }
  }
} else {
  $user_testing = $_GET['user_testing'];
  $user_data = query("SELECT * FROM tb_user WHERE username = '$user_testing'");

  if (isset($_POST['submit'])) {
    if (addData($_POST, $kd_pengujian, $user_data[0]['kd_user']) > 0) {
      $error = false;
      header('Location: ./../dashboard/classification.php?kd_pengujian=' . $kd_pengujian);
    } else {
      var_dump(mysqli_errno($conn));
      $error = true;
    }
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
  <link rel="stylesheet" href="./../../dist/css/testing-style.css" />
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
        <h4>Lakukan Tes</h4>
        <!-- Alert start -->
        <div
          class="alert alert-light alert-border-left-primary d-flex"
          role="alert">
          Bacalah setiap pertanyaan dan pilih jawaban dari opsi yang diberikan atas apa yang dialami/rasakan responden dalam seminggu terakhir.<br>
          Skala: <br>
          0 = Tidak pernah atau tidak sesuai sama sekali dengan saya <br>
          1 = Kadang-kadang atau sesuai dengan saya pada tingkat tertentu <br>
          2 = Lumayan sering atau sesuai dengan saya pada tingkat yang masih dapat dipertimbangkan <br>
          3 = Sering sekali atau sangat sesuai dengan saya
        </div>
        <!-- Alert end -->

        <!-- If error start -->
        <?php if (isset($error)) : ?>
          <div class="alert <?php echo $error ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
            <?php echo $error ? 'Gagal mengisi kuesioner' : 'Berhasil mengisi kuesioner'; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>
        <!-- If error end -->

        <!-- Question card start -->
        <form action="" method="post">
          <div class="accordion" id="accordionExample">
            <div class="card">
              <div class="card-header bg-primero" id="headingOne">
                <h2 class="mb-0">
                  <button
                    class="btn btn-link btn-block text-left text-white"
                    type="button"
                    data-toggle="collapse"
                    data-target="#collapseOne"
                    aria-expanded="true"
                    aria-controls="collapseOne">
                    Parameter 1 - Depresi
                  </button>
                </h2>
              </div>

              <div
                id="collapseOne"
                class="collapse show"
                aria-labelledby="headingOne"
                data-parent="#accordionExample">
                <div class="card-body">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr class="bg-primero">
                        <th scope="col" class="text-white font-weight-normal">
                          Pertanyaan
                        </th>
                        <th scope="col" class="text-white font-weight-normal">
                          Tidak Pernah
                        </th>
                        <th scope="col" class="text-white font-weight-normal">
                          Kadang-kadang
                        </th>
                        <th scope="col" class="text-white font-weight-normal">
                          Lumayan Sering
                        </th>
                        <th scope="col" class="text-white font-weight-normal">
                          Sering Sekali
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- D1 -->
                      <tr>
                        <td>
                          Saya merasa sama sekali tidak dapat merasakan
                          perasaan positif.
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D1"
                              id="inlineRadio1"
                              value="Tidak Pernah"
                              required />
                            <label class="form-check-label" for="inlineRadio1">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D1"
                              id="inlineRadio2"
                              value="Kadang-kadang" />
                            <label class="form-check-label" for="inlineRadio2">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D1"
                              id="inlineRadio3"
                              value="Lumayan Sering" />
                            <label class="form-check-label" for="inlineRadio3">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D1"
                              id="inlineRadio4"
                              value="Sering Sekali" />
                            <label class="form-check-label" for="inlineRadio4">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- D2 -->
                      <tr>
                        <td>
                          Saya merasa sulit untuk meningkatkan inisiatif dalam
                          melakukan sesuatu.
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D2"
                              id="inlineRadio5"
                              value="Tidak Pernah"
                              required />
                            <label class="form-check-label" for="inlineRadio5">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D2"
                              id="inlineRadio6"
                              value="Kadang-kadang" />
                            <label class="form-check-label" for="inlineRadio6">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D2"
                              id="inlineRadio7"
                              value="Lumayan Sering" />
                            <label class="form-check-label" for="inlineRadio7">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D2"
                              id="inlineRadio8"
                              value="Sering Sekali" />
                            <label class="form-check-label" for="inlineRadio8">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- D3 -->
                      <tr>
                        <td>
                          Saya merasa tidak ada hal yang dapat diharapkan di
                          masa depan.
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D3"
                              id="inlineRadio9"
                              value="Tidak Pernah"
                              required />
                            <label class="form-check-label" for="inlineRadio9">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D3"
                              id="inlineRadio10"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio10">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D3"
                              id="inlineRadio11"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio11">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D3"
                              id="inlineRadio12"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio12">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- D4 -->
                      <tr>
                        <td>Saya merasa putus asa dan sedih.</td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D4"
                              id="inlineRadio13"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio13">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D4"
                              id="inlineRadio14"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio14">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D4"
                              id="inlineRadio15"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio15">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D4"
                              id="inlineRadio16"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio16">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- D5 -->
                      <tr>
                        <td>Saya tidak merasa antusias dalam hal apapun.</td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D5"
                              id="inlineRadio17"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio17">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D5"
                              id="inlineRadio18"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio18">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D5"
                              id="inlineRadio19"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio19">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D5"
                              id="inlineRadio20"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio20">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- D6 -->
                      <tr>
                        <td>Saya merasa bahwa saya tidak berharga sebagai seorang manusia.</td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D6"
                              id="inlineRadio21"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio21">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D6"
                              id="inlineRadio22"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio22">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D6"
                              id="inlineRadio23"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio23">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D6"
                              id="inlineRadio24"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio24">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- D7 -->
                      <tr>
                        <td>Saya merasa bahwa hidup tidak berarti.</td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D7"
                              id="inlineRadio25"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio25">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D7"
                              id="inlineRadio26"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio26">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D7"
                              id="inlineRadio27"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio27">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="D7"
                              id="inlineRadio28"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio28">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header bg-primero" id="headingTwo">
                <h2 class="mb-0">
                  <button
                    class="btn btn-link btn-block text-left collapsed text-white"
                    type="button"
                    data-toggle="collapse"
                    data-target="#collapseTwo"
                    aria-expanded="false"
                    aria-controls="collapseTwo">
                    Parameter 2 - Kecemasan
                  </button>
                </h2>
              </div>
              <div
                id="collapseTwo"
                class="collapse"
                aria-labelledby="headingTwo"
                data-parent="#accordionExample">
                <div class="card-body">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr class="bg-primero">
                        <th scope="col" class="text-white font-weight-normal">
                          Pertanyaan
                        </th>
                        <th scope="col" class="text-white font-weight-normal">
                          Tidak Pernah
                        </th>
                        <th scope="col" class="text-white font-weight-normal">
                          Kadang-kadang
                        </th>
                        <th scope="col" class="text-white font-weight-normal">
                          Lumayan Sering
                        </th>
                        <th scope="col" class="text-white font-weight-normal">
                          Sering Sekali
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- A1 -->
                      <tr>
                        <td>Saya merasa bibir saya sering kering.</td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A1"
                              id="inlineRadio29"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio29">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A1"
                              id="inlineRadio30"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio30">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A1"
                              id="inlineRadio31"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio31">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A1"
                              id="inlineRadio32"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio32">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- A2 -->
                      <tr>
                        <td>
                          Saya mengalami kesulitan bernafas (misalnya:
                          seringkali terengah-engah atau tidak dapat bernafas
                          padahal tidak melakukan aktifitas fisik sebelumnya).
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A2"
                              id="inlineRadio33"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio33">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A2"
                              id="inlineRadio34"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio34">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A2"
                              id="inlineRadio35"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio35">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A2"
                              id="inlineRadio36"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio36">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- A3 -->
                      <tr>
                        <td>Saya merasa gemetar (misalnya: pada tangan).</td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A3"
                              id="inlineRadio37"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio37">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A3"
                              id="inlineRadio38"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio38">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A3"
                              id="inlineRadio39"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio39">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A3"
                              id="inlineRadio40"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio40">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- A4 -->
                      <tr>
                        <td>
                          Saya merasa khawatir dengan situasi dimana saya
                          mungkin menjadi panik dan mempermalukan diri
                          sendiri.
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A4"
                              id="inlineRadio41"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio41">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A4"
                              id="inlineRadio42"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio42">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A4"
                              id="inlineRadio43"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio43">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A4"
                              id="inlineRadio44"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio44">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- A5 -->
                      <tr>
                        <td>Saya merasa saya hampir panik.</td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A5"
                              id="inlineRadio45"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio45">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A5"
                              id="inlineRadio46"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio46">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A5"
                              id="inlineRadio47"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio47">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A5"
                              id="inlineRadio48"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio48">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- A6 -->
                      <tr>
                        <td>
                          Saya menyadari kegiatan jantung, walaupun saya tidak
                          sehabis melakukan aktifitas fisik (misalnya: merasa
                          detak jantung meningkat atau melemah).
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A6"
                              id="inlineRadio49"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio49">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A6"
                              id="inlineRadio50"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio50">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A6"
                              id="inlineRadio51"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio51">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A6"
                              id="inlineRadio52"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio52">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- A7 -->
                      <tr>
                        <td>Saya merasa takut tanpa alasan yang jelas.</td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A7"
                              id="inlineRadio53"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio53">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A7"
                              id="inlineRadio54"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio54">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A7"
                              id="inlineRadio55"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio55">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="A7"
                              id="inlineRadio56"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio56">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header bg-primero" id="headingThree">
                <h2 class="mb-0">
                  <button
                    class="btn btn-link btn-block text-left collapsed text-white"
                    type="button"
                    data-toggle="collapse"
                    data-target="#collapseThree"
                    aria-expanded="false"
                    aria-controls="collapseThree">
                    Parameter 3 - Stres
                  </button>
                </h2>
              </div>
              <div
                id="collapseThree"
                class="collapse"
                aria-labelledby="headingThree"
                data-parent="#accordionExample">
                <div class="card-body">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr class="bg-primero">
                        <th scope="col" class="text-white font-weight-normal">
                          Pertanyaan
                        </th>
                        <th scope="col" class="text-white font-weight-normal">
                          Tidak Pernah
                        </th>
                        <th scope="col" class="text-white font-weight-normal">
                          Kadang-kadang
                        </th>
                        <th scope="col" class="text-white font-weight-normal">
                          Lumayan Sering
                        </th>
                        <th scope="col" class="text-white font-weight-normal">
                          Sering Sekali
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- S1 -->
                      <tr>
                        <td>Saya merasa sulit untuk beristirahat.</td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S1"
                              id="inlineRadio57"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio57">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S1"
                              id="inlineRadio58"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio58">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S1"
                              id="inlineRadio59"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio59">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S1"
                              id="inlineRadio60"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio60">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- S2 -->
                      <tr>
                        <td>
                          Saya cenderung bereaksi berlebihan terhadap suatu
                          situasi.
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S2"
                              id="inlineRadio61"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio61">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S2"
                              id="inlineRadio62"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio62">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S2"
                              id="inlineRadio63"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio63">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S2"
                              id="inlineRadio64"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio64">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- S3 -->
                      <tr>
                        <td>
                          Saya merasa telah menghabiskan banyak energi untuk
                          merasa cemas.
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S3"
                              id="inlineRadio65"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio65">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S3"
                              id="inlineRadio66"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio66">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S3"
                              id="inlineRadio67"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio67">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S3"
                              id="inlineRadio68"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio68">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- S4 -->
                      <tr>
                        <td>Saya menemukan diri saya mudah gelisah.</td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S4"
                              id="inlineRadio69"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio69">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S4"
                              id="inlineRadio70"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio70">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S4"
                              id="inlineRadio71"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio71">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S4"
                              id="inlineRadio72"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio72">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- S5 -->
                      <tr>
                        <td>Saya merasa sulit untuk bersantai.</td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S5"
                              id="inlineRadio73"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio73">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S5"
                              id="inlineRadio74"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio74">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S5"
                              id="inlineRadio75"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio75">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S5"
                              id="inlineRadio76"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio76">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- S6 -->
                      <tr>
                        <td>
                          Saya tidak dapat memaklumi hal apapun yang
                          menghalangi saya untuk menyelesaikan hal yang sedang
                          saya lakukan.
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S6"
                              id="inlineRadio77"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio77">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S6"
                              id="inlineRadio78"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio78">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S6"
                              id="inlineRadio79"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio79">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S6"
                              id="inlineRadio80"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio80">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                      <!-- S7 -->
                      <tr>
                        <td>Saya merasa bahwa saya mudah tersinggung</td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S7"
                              id="inlineRadio81"
                              value="Tidak Pernah"
                              required />
                            <label
                              class="form-check-label"
                              for="inlineRadio81">Tidak Pernah</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S7"
                              id="inlineRadio82"
                              value="Kadang-kadang" />
                            <label
                              class="form-check-label"
                              for="inlineRadio82">Kadang-kadang</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S7"
                              id="inlineRadio83"
                              value="Lumayan Sering" />
                            <label
                              class="form-check-label"
                              for="inlineRadio83">Lumayan Sering</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              type="radio"
                              name="S7"
                              id="inlineRadio84"
                              value="Sering Sekali" />
                            <label
                              class="form-check-label"
                              for="inlineRadio84">Sering Sekali</label>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <button
            type="submit"
            class="btn btn-md bg-primero mt-4 text-white w-100"
            name="submit">
            Akhiri Tes
          </button>
        </form>
        <!-- Question card end -->
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
            <li><a href="./../dashboard/experiment.php">Eksperimen</a></li>
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