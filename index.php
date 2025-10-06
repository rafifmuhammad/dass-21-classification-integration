<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: ./pages/dashboard/index.php");
    exit;
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
    <link rel="stylesheet" href="./dist/bootstrap-4.0.0-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./dist/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <title>Get Started</title>
</head>

<body>
    <!-- Navigation start -->
    <section class="navigation fixed-top" data-aos="fade-down" data-aos-duration="1000">
        <div class="wrapper">
            <div class="box-navigation">
                <div class="box">
                    <h1>DASS-21</h1>
                </div>
                <div class="box">
                    <ul>
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#tentang">Tentang</a></li>
                        <li><a href="./login.php" class="button">Masuk</a></li>
                        <li><a href="./register.php" class="button secondary">Daftar</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- Navigation end -->

    <!-- Hero start -->
    <section class="hero">
        <div class="wrapper">
            <div class="box-hero">
                <div class="box" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="500">
                    <h1>Ketahui Tingkat Kesehatan Mental Anda</h1>
                    <p>Lakukan tes dan dapatkan kesimpulan yang dapat menjadi preferensi Anda sebagai acuan dalam membutuhkan bantuan profesional.</p>
                </div>
                <div class="box" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="500">
                    <img src="./img/ilustration-mental.png" alt="ilustration-1">
                </div>
            </div>
            <button class="btn btn-primary btn-sm button-center small-button" onclick="location.href='./register.php'" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="1000">Coba Sekarang</button>
        </div>
    </section>
    <!-- Hero end -->

    <!-- About start -->
    <section class="about" id="tentang">
        <div class="wrapper">
            <div class="box-about">
                <div class="box" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="300">
                    <img src="./img/ilustration-system.jpg" alt="ilustration-2">
                </div>
                <div class="box" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="500">
                    <h1>Tentang Sistem</h1>
                    <p>DASS-21 merupakan sistem yang mengimplementasikan model machine learning yaitu Naive Bayes dalam menentukan tingkat kesehatan mental seorang individu terutama mahasiswa dengan menggunakan skala DASS-21.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- About end -->

    <!-- About start -->
    <section class="about">
        <div class="wrapper">
            <div class="box-about reverse">
                <div class="box">
                    <img src="./img/ilustration-method.jpg" alt="ilustration-2" data-aos="fade-right" data-aos-duration="1500" data-aos-delay="700">
                </div>
                <div class="box" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="1000">
                    <h1>Metode</h1>
                    <p>Naïve Bayes adalah algoritma klasifikasi berbasis probabilitas yang menggunakan Teorema Bayes untuk memprediksi kelas suatu data. Algoritma ini mengasumsikan bahwa setiap fitur bersifat independen satu sama lain, sehingga probabilitas gabungan dapat dihitung dari masing-masing fitur secara terpisah. Dalam konteks DASS-21, Naïve Bayes digunakan untuk mengklasifikasikan tingkat kesehatan mental responden berdasarkan tiga parameter utama, yaitu depresi, kecemasan, dan stres. Setiap responden direpresentasikan oleh nilai ketiga dimensi tersebut, dan model menghitung probabilitas responden termasuk dalam salah satu kategori: normal, ringan, sedang, berat, atau sangat berat. Kelas dengan probabilitas tertinggi kemudian dipilih sebagai prediksi tingkat kesehatan mental individu tersebut.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- About end -->

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
                        <li><a href="./login.php">Masuk</a></li>
                        <li><a href="./register.php">Daftar</a></li>
                    </ul>
                    <ul>
                        <li>
                            <h2>Metode dan Data</h2>
                        </li>
                        <li><a href="https://en.wikipedia.org/wiki/Naive_Bayes_classifier">Naive Bayes</a></li>
                        <li><a href="https://data.mendeley.com/datasets/br82d4xkj7/1">Sumber Data</a></li>
                        <li><a href="./pages/dashboard/experiment.php">Eksperimen</a></li>
                    </ul>
                </div>
            </div>
            <p>© 2025 RafifBanner</p>
        </div>
    </section>
    <!-- Footer end -->

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>