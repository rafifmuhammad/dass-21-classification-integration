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

// Pagination
$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

$pagination = queryWithPagination("SELECT * FROM tb_data WHERE Jenis = 'Training'", 10);
$dataTraining = $pagination['data'];
$totalPages = $pagination['total_pages'];
$page = $pagination['current_page'];

$jumlahLink = 5; // Maksimal Link Halaman
$startPage = max(1, $page - floor($jumlahLink / 2));
$endPage = min($totalPages, $startPage + $jumlahLink - 1);

if ($endPage - $startPage + 1 < $jumlahLink) {
    $startPage = max(1, $endPage - $jumlahLink + 1);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./../../dist/bootstrap-4.0.0-dist/css/bootstrap.css">
    <link rel="stylesheet" href="./../../dist/css/dashboard-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <title>Data Training | DASS-21</title>
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
                            <i class="ri-dashboard-line" onclick="location.href='./index.html'"></i>
                            <a href="./index.php">Dashboard</a>
                        </li>
                        <?php if ($_SESSION['role'] == 'Admin') : ?>
                            <li>
                                <i class="ri-group-line" onclick="location.href='./user_management.html'"></i>
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
                            <li class="dropdown active">
                                <div>
                                    <i class="ri-line-chart-line" href="#submenuDataset" data-toggle="collapse" aria-expanded="false"></i>
                                    <a href="#submenuDataset" data-toggle="collapse" aria-expanded="false">Dataset</a>
                                </div>
                                <i class="ri-arrow-drop-down-line"></i>
                            </li>
                            <ul class="collapse pl-0 ml-0" id="submenuDataset">
                                <li>
                                    <i class="ri-database-2-line" onclick="location.href='./data.html'"></i>
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
                            <i class="ri-infinity-fill"></i>
                            <a href="./probability.php">Probabilitas</a>
                        </li>
                        <li>
                            <i class="ri-formula"></i>
                            <a href="./confusion_matrix.php">Confusion Matrix</a>
                        </li>
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
                            <h3>Data Training</h3>
                        </div>
                        <div class="box">
                            <img src="./../../img/il-no-profile.jpg" alt="user_image">
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
                            <li class="breadcrumb-item active" aria-current="page">Data Training</li>
                        </ol>
                    </nav>
                    <!-- Breadcrumb end -->

                    <!-- Data table start -->
                    <section class="data-table">
                        <div class="wrapper">
                            <div class="card text-left">
                                <img class="card-img-top" src="holder.js/100px180/" alt="">
                                <div class="card-body">
                                    <h4 class="card-title">Tabel Data Training</h4>
                                    <div class="table-action justify-content-end">
                                        <form action="">
                                            <input class="form-control form-control-sm" type="text" placeholder="Cari">
                                            <button class="btn btn-primary btn-sm"><i class="ri-search-line"></i> Cari Data</button>
                                        </form>
                                    </div>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Kode Data</th>
                                                <th scope="col">Kelas</th>
                                                <th scope="col">Jenis</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($dataTraining as $data) : ?>
                                                <tr>
                                                    <th scope="row"><?= $i; ?></th>
                                                    <td><?= $data['kd_data'] ?></td>
                                                    <td><?= $data['Kelas'] ?></td>
                                                    <td class="<?php echo $dt['Jenis'] != 'Training' ? 'text-success' : 'text-info'; ?>"><?= $data['Jenis'] ?></td>
                                                </tr>
                                            <?php
                                                $i++;
                                            endforeach; ?>
                                        </tbody>
                                    </table>
                                    <nav>
                                        <ul class="pagination justify-content-end ">
                                            <!-- Tombol Sebelumnya -->
                                            <?php if ($page > 1) : ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?= $page - 1; ?>" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <!-- Nomor halaman dinamis -->
                                            <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
                                                <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                                                    <a class="page-link <?= ($i == $page) ? 'bg-light border-info' : ''; ?>" href="?page=<?= $i; ?>"><?= $i; ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <!-- Tombol Selanjutnya -->
                                            <?php if ($page < $totalPages) : ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?= $page + 1; ?>" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
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

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('.toggle-sidebar-btn').on('click', function() {
                $('.main-app').toggleClass('sidebar-collapsed');
            });
        });
    </script>
</body>

</html>