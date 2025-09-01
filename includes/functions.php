<?php

include __DIR__ . '/../db/connect.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Functions For Retrieving Data
function queryWithPagination($sql, $perPage = 10)
{
    global $conn;

    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $start = ($page - 1) * $perPage;

    $paginatedSql = $sql . " LIMIT $start, $perPage";

    $result = mysqli_query($conn, $paginatedSql);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    // Hitung total data untuk pagination
    $totalData = count(query($sql));
    $totalPages = ceil($totalData / $perPage);

    return [
        'data' => $rows,
        'total_pages' => $totalPages,
        'current_page' => $page,
        'per_page' => $perPage
    ];
}

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// Naive Bayes Functions
// Hitung Banyak Kelas Tertentu
function banyakKelas($data)
{
    $normal = 0;
    $ringan = 0;
    $sedang = 0;
    $berat = 0;
    $sangatBerat = 0;

    foreach ($data as $dt) {
        if ($dt['Kelas'] == 'Normal') {
            $normal += 1;
        } else if ($dt['Kelas'] == 'Ringan') {
            $ringan += 1;
        } else if ($dt['Kelas'] == 'Sedang') {
            $sedang += 1;
        } else if ($dt['Kelas'] == 'Berat') {
            $berat += 1;
        } else if ($dt['Kelas'] == 'Sangat Berat') {
            $sangatBerat += 1;
        }
    }

    return [
        'jumlahNormal' => $normal,
        'jumlahRingan' => $ringan,
        'jumlahSedang' => $sedang,
        'jumlahBerat' => $berat,
        'jumlahSangatBerat' => $sangatBerat
    ];
}

// Mengambil banyaknya kategori di parameter terhadap kelas tertentu
// probabilitas kondisional dimana $c merupakan parameter
function banyakData($data, $c, $kelas)
{
    $normal = 0;
    $ringan = 0;
    $sedang = 0;
    $berat = 0;
    $sangatBerat = 0;

    foreach ($data as $dt) {
        if ($dt[$c] == $kelas && $dt['Kelas'] == 'Normal') {
            $normal += 1;
        } else if ($dt[$c] == $kelas && $dt['Kelas'] == 'Ringan') {
            $ringan += 1;
        } else if ($dt[$c] == $kelas && $dt['Kelas'] == 'Sedang') {
            $sedang += 1;
        } else if ($dt[$c] == $kelas && $dt['Kelas'] == 'Berat') {
            $berat += 1;
        } else if ($dt[$c] == $kelas && $dt['Kelas'] == 'Sangat Berat') {
            $sangatBerat += 1;
        }
    }

    return [
        "Normal" => $normal,
        "Ringan" => $ringan,
        "Sedang" => $sedang,
        "Berat" => $berat,
        "SangatBerat" => $sangatBerat
    ];
}

function formatKeyKelas($kelas)
{
    $formatted = str_replace(' ', '', ucwords(strtolower($kelas)));

    return match ($formatted) {
        'Normal' => 'Normal',
        'Ringan' => 'Ringan',
        'Sedang' => 'Sedang',
        'Berat' => 'Berat',
        'Sangatberat' => 'SangatBerat',
        default => $formatted,
    };
}

function hitungProbabilitas($jumlahParameter, $jumlahKelas, $prefix = 'jumlah')
{
    $hasil = [];

    foreach ($jumlahParameter as $kelas => $jumlahNilai) {
        $formattedKelasKey = $prefix . formatKeyKelas($kelas);
        $jumlahKelasK = $jumlahKelas[$formattedKelasKey] ?? 0;

        if ($jumlahKelasK > 0) {
            $nilai = $jumlahNilai / $jumlahKelasK;
        } else {
            $nilai = 0;
        }

        $hasil[$kelas] = number_format($nilai, 4, '.');
    }

    return $hasil;
}

// Function hitung probabilitas kondisional tiap parameter
function hitungProbabilitasKondisional($jumlahParameter, $jumlahKelas, $prefix = 'jumlah', $jumlahKategori = 5)
{
    $hasil = [];

    foreach ($jumlahParameter as $kelas => $jumlahNilai) {
        $formattedKelasKey = $prefix . formatKeyKelas($kelas);
        $jumlahKelasK = $jumlahKelas[$formattedKelasKey] ?? 0;

        // Laplace smoothing HANYA jika jumlahNilai == 0
        if ($jumlahKelasK > 0) {
            if ($jumlahNilai == 0) {
                // Laplace smoothing untuk nilai nol
                $nilai = ($jumlahNilai + 1) / ($jumlahKelasK + $jumlahKategori);
            } else {
                // Tidak perlu smoothing
                $nilai = $jumlahNilai / $jumlahKelasK;
            }
        } else {
            // Kelas belum pernah muncul, smoothing penuh
            $nilai = 1 / $jumlahKategori;
        }

        $hasil[$kelas] = number_format($nilai, 4, '.');
    }

    return $hasil;
}

function convertTo4Decimals($num)
{
    return number_format($num, 4, '.');
}

// Handle API Functions
function convertCategorialtoNum($string)
{
    if ($string == 'Normal') {
        return 0;
    } else if ($string == 'Ringan') {
        return 1;
    } else if ($string == 'Sedang') {
        return 2;
    } else if ($string == 'Berat') {
        return 3;
    } else {
        return 4;
    }
}

// User's Functions
function addUser($data)
{
    global $conn;

    $kdUser = uniqid();
    $username = htmlspecialchars($data['username']);
    $nama = htmlspecialchars($data['nama']);
    $tanggalLahir = htmlspecialchars($data['tanggal_lahir']);
    $role = htmlspecialchars($data['role']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $createdAt = date('Y-m-d');

    $query = "INSERT INTO tb_user(kd_user, username, nama, tanggal_lahir, role, password, created_at) VALUES
            ('$kdUser', '$username', '$nama', '$tanggalLahir', '$role', '$password', '$createdAt')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function updateUser($data)
{
    global $conn;

    $kdUser = htmlspecialchars($data['kd_user']);
    $username = htmlspecialchars($data['username']);
    $nama = htmlspecialchars($data['nama']);
    $tanggalLahir = htmlspecialchars($data['tanggal_lahir']);
    $role = htmlspecialchars($data['role']);

    if (!empty($data['password'])) {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $query = "UPDATE tb_user SET 
                    username = '$username',
                    nama = '$nama',
                    tanggal_lahir = '$tanggalLahir',
                    role = '$role',
                    password = '$password'
                  WHERE kd_user = '$kdUser'";
    } else {
        $query = "UPDATE tb_user SET 
                    username = '$username',
                    nama = '$nama',
                    tanggal_lahir = '$tanggalLahir',
                    role = '$role'
                  WHERE kd_user = '$kdUser'";
    }

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function deleteUser($kdUser)
{
    global $conn;

    $kdUser = htmlspecialchars($kdUser);
    mysqli_query($conn, "DELETE FROM tb_user WHERE kd_user = '$kdUser'");

    return mysqli_affected_rows($conn);
}


// Function Handle Questionnaire
function convertToIntegerPerParameter($array)
{
    for ($i = 0; $i < count($array); $i++) {
        if ($array[$i] == 'Tidak Pernah') {
            $array[$i] = 0;
        } else if ($array[$i] == 'Kadang-kadang') {
            $array[$i] = 1;
        } else if ($array[$i] == 'Lumayan Sering') {
            $array[$i] = 2;
        } else {
            $array[$i] = 3;
        }
    }

    return $array;
}

function getCategory($value, $parameter)
{
    switch ($parameter) {
        case 1:
            // Depresi
            if ($value >= 0 && $value <= 6) {
                return 'Normal';
            } else if ($value >= 7 && $value <= 8) {
                return 'Ringan';
            } else if ($value >= 9 && $value <= 13) {
                return 'Sedang';
            } else if ($value >= 14 && $value <= 16) {
                return 'Berat';
            } else if ($value >= 17) {
                return 'Sangat Berat';
            }
        case 2:
            // Kecemasan
            if ($value >= 0 && $value <= 5) {
                return 'Normal';
            } else if ($value >= 6 && $value <= 7) {
                return 'Ringan';
            } else if ($value >= 8 && $value <= 12) {
                return 'Sedang';
            } else if ($value >= 13 && $value <= 15) {
                return 'Berat';
            } else if ($value >= 16) {
                return 'Sangat Berat';
            }
        case 3:
            // Stres
            if ($value >= 0 && $value <= 11) {
                return 'Normal';
            } else if ($value >= 12 && $value <= 13) {
                return 'Ringan';
            } else if ($value >= 14 && $value <= 16) {
                return 'Sedang';
            } else if ($value >= 17 && $value <= 18) {
                return 'Berat';
            } else if ($value >= 19) {
                return 'Sangat Berat';
            }
        default:
            return null;
    }
}

function getClassCategory($value)
{
    $category = '';

    if ($value >= 0 && $value <= 23) {
        $category = 'Normal';
    } else if ($value >= 24 && $value <= 29) {
        $category = 'Ringan';
    } else if ($value >= 30 && $value <= 39) {
        $category = 'Sedang';
    } else if ($value >= 40 && $value <= 46) {
        $category = 'Normal';
    } else {
        $category = 'Sangat Berat';
    }

    return $category;
}

function addData($data, $code, $kd_user)
{
    global $conn;

    $kd_pengujian = $code;
    $kd_user = $kd_user;

    // Parameter depresi
    $d1 = htmlspecialchars($data['D1']);
    $d2 = htmlspecialchars($data['D2']);
    $d3 = htmlspecialchars($data['D3']);
    $d4 = htmlspecialchars($data['D4']);
    $d5 = htmlspecialchars($data['D5']);
    $d6 = htmlspecialchars($data['D6']);
    $d7 = htmlspecialchars($data['D7']);
    // Parameter kecemasan
    $a1 = htmlspecialchars($data['A1']);
    $a2 = htmlspecialchars($data['A2']);
    $a3 = htmlspecialchars($data['A3']);
    $a4 = htmlspecialchars($data['A4']);
    $a5 = htmlspecialchars($data['A5']);
    $a6 = htmlspecialchars($data['A6']);
    $a7 = htmlspecialchars($data['A7']);
    // Parameter stres
    $s1 = htmlspecialchars($data['S1']);
    $s2 = htmlspecialchars($data['S2']);
    $s3 = htmlspecialchars($data['S3']);
    $s4 = htmlspecialchars($data['S4']);
    $s5 = htmlspecialchars($data['S5']);
    $s6 = htmlspecialchars($data['S6']);
    $s7 = htmlspecialchars($data['S7']);
    $tanggal_pengujian = date('Y-m-d');

    // Array per parameter
    $depresi = [$d1, $d2, $d3, $d4, $d5, $d6, $d7];
    $kecemasan = [$a1, $a2, $a3, $a4, $a5, $a6, $a7];
    $stres = [$s1, $s2, $s3, $s4, $s5, $s6, $s7];

    // Convert array to integer and sum the array
    $jumlah_depresi = array_sum(convertToIntegerPerParameter($depresi));
    $jumlah_kecemasan = array_sum(convertToIntegerPerParameter($kecemasan));
    $jumlah_stres = array_sum(convertToIntegerPerParameter($stres));

    // Get the category of p1, p2, and p3
    $p1 = getCategory($jumlah_depresi, 1);
    $p2 = getCategory($jumlah_kecemasan, 2);
    $p3 = getCategory($jumlah_stres, 3);

    $query = "INSERT INTO tb_pengujian
    (kd_pengujian, kd_user, D1, D2, D3, D4, D5, D6, D7, A1, A2, A3, A4, A5, A6, A7, S1, S2, S3, S4, S5, S6, S7, P1, P2, P3, hasil_klasifikasi, tanggal_pengujian) VALUES
    ('$kd_pengujian', '$kd_user', '$d1', '$d2', '$d3', '$d4', '$d5', '$d6', '$d7', '$a1', '$a2', '$a3', '$a4', '$a5', '$a6', '$a7', '$s1', '$s2','$s3','$s4','$s5','$s6','$s7', '$p1', '$p2', '$p3', null, '$tanggal_pengujian')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function updateTest($kd_pengujian, $hasil)
{
    global $conn;

    $query = "UPDATE tb_pengujian set hasil_klasifikasi = '$hasil' WHERE kd_pengujian = '$kd_pengujian'";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function deleteTest($kd_pengujian)
{
    global $conn;

    $query = "DELETE FROM tb_pengujian WHERE kd_pengujian = '$kd_pengujian'";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Handle dataset
function insertNewData($data)
{
    global $conn;

    $kd_data = uniqid();

    // Parameter depresi
    $d1 = htmlspecialchars($data['D1']);
    $d2 = htmlspecialchars($data['D2']);
    $d3 = htmlspecialchars($data['D3']);
    $d4 = htmlspecialchars($data['D4']);
    $d5 = htmlspecialchars($data['D5']);
    $d6 = htmlspecialchars($data['D6']);
    $d7 = htmlspecialchars($data['D7']);
    // Parameter kecemasan
    $a1 = htmlspecialchars($data['A1']);
    $a2 = htmlspecialchars($data['A2']);
    $a3 = htmlspecialchars($data['A3']);
    $a4 = htmlspecialchars($data['A4']);
    $a5 = htmlspecialchars($data['A5']);
    $a6 = htmlspecialchars($data['A6']);
    $a7 = htmlspecialchars($data['A7']);
    // Parameter stres
    $s1 = htmlspecialchars($data['S1']);
    $s2 = htmlspecialchars($data['S2']);
    $s3 = htmlspecialchars($data['S3']);
    $s4 = htmlspecialchars($data['S4']);
    $s5 = htmlspecialchars($data['S5']);
    $s6 = htmlspecialchars($data['S6']);
    $s7 = htmlspecialchars($data['S7']);

    // Array per parameter
    $depresi = [$d1, $d2, $d3, $d4, $d5, $d6, $d7];
    $kecemasan = [$a1, $a2, $a3, $a4, $a5, $a6, $a7];
    $stres = [$s1, $s2, $s3, $s4, $s5, $s6, $s7];

    // Convert array to integer and sum the array
    $jumlah_depresi = array_sum(convertToIntegerPerParameter($depresi));
    $jumlah_kecemasan = array_sum(convertToIntegerPerParameter($kecemasan));
    $jumlah_stres = array_sum(convertToIntegerPerParameter($stres));

    // Get the category of p1, p2, and p3
    $p1 = getCategory($jumlah_depresi, 1);
    $p2 = getCategory($jumlah_kecemasan, 2);
    $p3 = getCategory($jumlah_stres, 3);

    $jumlahTotal = $jumlah_depresi + $jumlah_kecemasan + $jumlah_stres;
    $kelas = getClassCategory($jumlahTotal);

    $query = "INSERT INTO tb_data
    (kd_data, D1, D2, D3, D4, D5, D6, D7, A1, A2, A3, A4, A5, A6, A7, S1, S2, S3, S4, S5, S6, S7, P1, P2, P3, Kelas, Jenis) VALUES
    ('$kd_data', '$d1', '$d2', '$d3', '$d4', '$d5', '$d6', '$d7', '$a1', '$a2', '$a3', '$a4', '$a5', '$a6', '$a7', '$s1', '$s2','$s3','$s4','$s5','$s6','$s7', '$p1', '$p2', '$p3', '$kelas', null)";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function deleteData($kd_data)
{
    global $conn;

    $query = "DELETE FROM tb_data WHERE kd_data = '$kd_data'";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function updateData($data, $kd_data)
{
    global $conn;

    // Parameter depresi
    $d1 = htmlspecialchars($data['D1']);
    $d2 = htmlspecialchars($data['D2']);
    $d3 = htmlspecialchars($data['D3']);
    $d4 = htmlspecialchars($data['D4']);
    $d5 = htmlspecialchars($data['D5']);
    $d6 = htmlspecialchars($data['D6']);
    $d7 = htmlspecialchars($data['D7']);
    // Parameter kecemasan
    $a1 = htmlspecialchars($data['A1']);
    $a2 = htmlspecialchars($data['A2']);
    $a3 = htmlspecialchars($data['A3']);
    $a4 = htmlspecialchars($data['A4']);
    $a5 = htmlspecialchars($data['A5']);
    $a6 = htmlspecialchars($data['A6']);
    $a7 = htmlspecialchars($data['A7']);
    // Parameter stres
    $s1 = htmlspecialchars($data['S1']);
    $s2 = htmlspecialchars($data['S2']);
    $s3 = htmlspecialchars($data['S3']);
    $s4 = htmlspecialchars($data['S4']);
    $s5 = htmlspecialchars($data['S5']);
    $s6 = htmlspecialchars($data['S6']);
    $s7 = htmlspecialchars($data['S7']);
    $jenis = htmlspecialchars($data['jenis']);

    // Array per parameter
    $depresi = [$d1, $d2, $d3, $d4, $d5, $d6, $d7];
    $kecemasan = [$a1, $a2, $a3, $a4, $a5, $a6, $a7];
    $stres = [$s1, $s2, $s3, $s4, $s5, $s6, $s7];

    // Convert array to integer and sum the array
    $jumlah_depresi = array_sum(convertToIntegerPerParameter($depresi));
    $jumlah_kecemasan = array_sum(convertToIntegerPerParameter($kecemasan));
    $jumlah_stres = array_sum(convertToIntegerPerParameter($stres));

    // Get the category of p1, p2, and p3
    $p1 = getCategory($jumlah_depresi, 1);
    $p2 = getCategory($jumlah_kecemasan, 2);
    $p3 = getCategory($jumlah_stres, 3);

    $jumlahTotal = $jumlah_depresi + $jumlah_kecemasan + $jumlah_stres;
    $kelas = getClassCategory($jumlahTotal);

    $query = " UPDATE tb_data SET
        D1 = '$d1', 
        D2 = '$d2', 
        D3 = '$d3', 
        D4 = '$d4', 
        D5 = '$d5', 
        D6 = '$d6', 
        D7 = '$d7', 
        A1 = '$a1', 
        A2 = '$a2', 
        A3 = '$a3', 
        A4 = '$a4', 
        A5 = '$a5', 
        A6 = '$a6', 
        A7 = '$a7', 
        S1 = '$s1', 
        S2 = '$s2', 
        S3 = '$s3', 
        S4 = '$s4', 
        S5 = '$s5', 
        S6 = '$s6', 
        S7 = '$s7', 
        P1 = '$p1',
        P2 = '$p2', 
        P3 = '$p3', 
        Kelas = '$kelas',
        Jenis = '$jenis'
        WHERE kd_data = '$kd_data'
    ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Handle excel file
function importDataExcel($filePath)
{
    global $conn;

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    $affectedRows = 0;

    // Mulai dari baris kedua (baris pertama header)
    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];

        $kd_data = uniqid();
        // D1–D7 mulai dari kolom 3 (indeks 3)
        $d1 = htmlspecialchars($row[3]);
        $d2 = htmlspecialchars($row[4]);
        $d3 = htmlspecialchars($row[5]);
        $d4 = htmlspecialchars($row[6]);
        $d5 = htmlspecialchars($row[7]);
        $d6 = htmlspecialchars($row[8]);
        $d7 = htmlspecialchars($row[9]);
        $a1 = htmlspecialchars($row[10]);
        $a2 = htmlspecialchars($row[11]);
        $a3 = htmlspecialchars($row[12]);
        $a4 = htmlspecialchars($row[13]);
        $a5 = htmlspecialchars($row[14]);
        $a6 = htmlspecialchars($row[15]);
        $a7 = htmlspecialchars($row[16]);
        $s1 = htmlspecialchars($row[17]);
        $s2 = htmlspecialchars($row[18]);
        $s3 = htmlspecialchars($row[19]);
        $s4 = htmlspecialchars($row[20]);
        $s5 = htmlspecialchars($row[21]);
        $s6 = htmlspecialchars($row[22]);
        $s7 = htmlspecialchars($row[23]);
        $kelas = htmlspecialchars($row[24]);

        $query = "INSERT INTO tb_data
            (kd_data, D1, D2, D3, D4, D5, D6, D7, A1, A2, A3, A4, A5, A6, A7, S1, S2, S3, S4, S5, S6, S7, Kelas, Jenis)
            VALUES
            ('$kd_data', '$d1', '$d2', '$d3', '$d4', '$d5', '$d6', '$d7',
             '$a1', '$a2', '$a3', '$a4', '$a5', '$a6', '$a7',
             '$s1', '$s2', '$s3', '$s4', '$s5', '$s6', '$s7',
             '$kelas', NULL)";

        mysqli_query($conn, $query);
        $affectedRows += mysqli_affected_rows($conn);
    }

    return $affectedRows;
}
