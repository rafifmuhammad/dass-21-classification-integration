<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../../index.php");
  exit;
}

require __DIR__ . '/../../vendor/autoload.php';
require './../../includes/functions.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$kd_user = $_SESSION['kd_user'];
$role = $_SESSION['role'];

if ($role == 'Admin') {
  $tests = query("
    SELECT tb_pengujian.*, tb_user.*
    FROM tb_pengujian, tb_user
    WHERE tb_pengujian.kd_user = tb_user.kd_user
  ");
} else {
  $tests = query("
    SELECT tb_pengujian.*, tb_user.*
    FROM tb_pengujian, tb_user
    WHERE tb_pengujian.kd_user = tb_user.kd_user
    AND tb_pengujian.kd_user = '$kd_user'
  ");
}

$html = '
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Laporan Hasil Pengujian DASS</title>
<style>
body {
  font-family: "DejaVu Sans", Arial, Helvetica, sans-serif;
  font-size: 12px;
  color: #000;
  margin: 30px 40px;
}
h1 {
  text-align: center;
  font-size: 20px;
  margin-bottom: 10px;
}
.identitas {
  margin-bottom: 10px;
}
.identitas table {
  width: 100%;
  border-collapse: collapse;
}
.identitas td {
  padding: 3px 0;
  border: none !important;
}
table.hasil {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}
table.hasil th, table.hasil td {
  border: 1px solid #000;
  padding: 6px;
  text-align: center;
  vertical-align: middle;
}
table.hasil th {
  background-color: #f5f5f5;
  font-weight: bold;
}
thead { display: table-header-group; }
tfoot { display: table-row-group; }
tr { page-break-inside: avoid; }
</style>
</head>
<body>
  <h1>Laporan Hasil Pengujian Tingkat Kesehatan Mental</h1>';

if ($role != 'Admin') {
  $html .= '
  <div class="identitas">
    <table>
      <tr><td><strong>Nama Lengkap</strong></td><td>: ' . htmlspecialchars($tests[0]['nama']) . '</td></tr>
      <tr><td><strong>Tanggal Lahir</strong></td><td>: ' . htmlspecialchars($tests[0]['tanggal_lahir']) . '</td></tr>
    </table>
  </div>';
}

$html .= '
  <table class="hasil">
    <thead>
      <tr>
        <th>No</th>
        <th>Tanggal Pengujian</th>
        <th>Nama Lengkap</th>
        <th>Parameter Depresi</th>
        <th>Parameter Kecemasan</th>
        <th>Parameter Stres</th>
        <th>Hasil Klasifikasi</th>
      </tr>
    </thead>
    <tbody>';

$no = 1;
foreach ($tests as $t) {
  $html .= '
      <tr>
        <td>' . $no++ . '</td>
        <td>' . htmlspecialchars($t['tanggal_pengujian']) . '</td>
        <td>' . htmlspecialchars($t['nama']) . '</td>
        <td>' . htmlspecialchars($t['P1']) . '</td>
        <td>' . htmlspecialchars($t['P2']) . '</td>
        <td>' . htmlspecialchars($t['P3']) . '</td>
        <td>' . htmlspecialchars($t['hasil_klasifikasi']) . '</td>
      </tr>';
}

$html .= '
    </tbody>
  </table>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("laporan_dass_21.pdf", ["Attachment" => false]);
