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

if (isset($_POST['submit'])) {
  if (addUser($_POST) > 0) {
    echo "
      <script>
        alert('Pengguna berhasil ditambahkan!');
        location.href = 'user_management.php';
      </script>
    ";
  } else {
    echo "
      <script>
        alert('Pengguna berhasil ditambahkan!');
        location.href = 'user_management.php';
      </script>
    ";
  }
}

$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

$pagination = queryWithPagination("SELECT * FROM tb_user", 10);

if (isset($_POST['cari'])) {
  $q = $_POST['q'];

  $pagination = queryWithPagination("SELECT * FROM tb_user WHERE username LIKE '%$q%' OR nama LIKE '%$q%' OR role LIKE '%$q%'", 10);
}

$users = $pagination['data'];
$totalPages = $pagination['total_pages'];
$page = $pagination['current_page'];
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
    href="./../../dist/bootstrap-4.0.0-dist/css/bootstrap.css" />
  <link rel="stylesheet" href="./../../dist/css/dashboard-style.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
    rel="stylesheet" />
  <title>Manajemen Pengguna | DASS-21</title>
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
              <h3>Manajemen Pengguna</h3>
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
                Manajemen Pengguna
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
                  <h4 class="card-title">Tabel Manajemen Pengguna</h4>
                  <!-- Modal start -->
                  <div
                    class="modal fade"
                    id="addUserModal"
                    tabindex="-1"
                    role="dialog"
                    aria-labelledby="addUserModalTitle"
                    aria-hidden="true">
                    <div
                      class="modal-dialog modal-dialog-centered"
                      role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="addUserModalTitle">
                            Tambah Pengguna
                          </h5>
                          <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form action="" method="POST">
                          <div class="modal-body">
                            <div class="form-group">
                              <label for="username">Username</label>
                              <input
                                type="text"
                                class="form-control"
                                id="username"
                                name="username"
                                placeholder="Masukkan username Anda" />
                            </div>
                            <div class="form-group">
                              <label for="nama">Nama Lengkap</label>
                              <input
                                type="text"
                                class="form-control"
                                id="nama"
                                name="nama"
                                placeholder="Masukkan nama lengkap Anda" />
                            </div>
                            <div class="form-group">
                              <label for="tanggal_lahir">Tanggal Lahir</label>
                              <input
                                type="date"
                                class="form-control"
                                id="tanggal_lahir"
                                name="tanggal_lahir"
                                placeholder="name@example.com" />
                            </div>
                            <div class="form-group">
                              <label for="role">Pilih Role</label>
                              <select class="form-control" id="role" name="role">
                                <option value="User">User</option>
                                <option value="Admin">Admin</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="password">Password</label>
                              <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Masukkan password Anda" />
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button
                              type="button"
                              class="btn btn-secondary"
                              data-dismiss="modal">
                              Close
                            </button>
                            <button type="submit" class="btn btn-primary" name="submit">
                              Save changes
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- Modal end -->
                  <div class="table-action">
                    <button
                      class="btn btn-primary btn-sm"
                      data-toggle="modal"
                      data-target="#addUserModal">
                      <i class="ri-add-circle-line"></i> Tambah Pengguna
                    </button>
                    <form action="" method="post">
                      <input
                        class="form-control form-control-sm"
                        type="text"
                        placeholder="Cari"
                        name="q" />
                      <button class="btn btn-primary btn-sm" name="cari">
                        <i class="ri-search-line"></i> Cari Pengguna
                      </button>
                    </form>
                  </div>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kode Pengguna</th>
                        <th scope="col">Nama Pengguna</th>
                        <th scope="col">Username</th>
                        <th scope="col">Tanggal Lahir</th>
                        <th scope="col">role</th>
                        <th scope="col" colspan="2">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($users)) : ?>
                        <?php
                        $i = 1;
                        foreach ($users as $user) : ?>
                          <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?= $user['kd_user']; ?></td>
                            <td><?= $user['nama']; ?></td>
                            <td><?= $user['username']; ?></td>
                            <td><?= $user['tanggal_lahir']; ?></td>
                            <td><?= $user['role']; ?></td>
                            <td>
                              <a href="./edit_user.php?kd_user=<?= $user['kd_user']; ?>" class="btn btn-warning btn-sm"><i class="ri-pencil-line"></i></a>
                            </td>
                            <td>
                              <a href="./delete_user.php?kd_user=<?= $user['kd_user']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data?');"><i class=" ri-delete-bin-line"></i></a>
                            </td>
                          </tr>
                        <?php
                          $i++;
                        endforeach; ?>
                      <?php else : ?>
                        <tr>
                          <td colspan="8" class="text-center">Tidak ada data</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                  <nav>
                    <ul class="pagination justify-content-end">
                      <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $page - 1; ?>" aria-label="Previous">
                          <span aria-hidden="true">&laquo;</span>
                        </a>
                      </li>

                      <!-- Nomor Halaman -->
                      <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                          <a class="page-link <?= ($i == $page) ? 'bg-light border-info' : ''; ?>" href="?page=<?= $i; ?>">
                            <?= $i; ?>
                          </a>
                        </li>
                      <?php endfor; ?>

                      <!-- Tombol Selanjutnya -->
                      <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $page + 1; ?>" aria-label="Next">
                          <span aria-hidden="true">&raquo;</span>
                        </a>
                      </li>
                    </ul>
                  </nav>
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
  </script>
</body>

</html>