<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../index.php");
    exit;
}

if ($_SESSION['role'] == 'Admin') {
    header('Location: ./history.php');
}

require __DIR__ . '/../../vendor/autoload.php';
require './../../includes/functions.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$kd_user = $_SESSION['kd_user'];
$kd_pengujian = $_GET['kd_pengujian'];
$role = $_SESSION['role'];

$tests = query("
  SELECT tb_pengujian.*, tb_user.*
  FROM tb_pengujian, tb_user
  WHERE tb_pengujian.kd_user = tb_user.kd_user
  AND tb_pengujian.kd_pengujian = '$kd_pengujian'
");

if (count($tests) == 0) {
    die('Data tidak ditemukan.');
}

$t = $tests[0];

$html = '
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Laporan Hasil Pengujian DASS-21</title>
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
  margin-bottom: 15px;
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
table.dass {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}
table.dass th, table.dass td {
  border: 1px solid #000;
  padding: 6px;
  text-align: center;
  vertical-align: middle;
}
table.dass th {
  background-color: #f0f0f0;
  font-weight: bold;
}
table.dass td:nth-child(2) {
  text-align: left;
}
table.hasil {
  width: 100%;
  border-collapse: collapse;
  margin-top: 25px;
}
table.hasil th, table.hasil td {
  border: 1px solid #000;
  padding: 6px;
  text-align: center;
}
table.hasil th {
  background-color: #f5f5f5;
}
thead { display: table-header-group; }
tr { page-break-inside: avoid; }
</style>
</head>
<body>
<h1>Laporan Hasil Pengujian Tingkat Kesehatan Mental (DASS-21)</h1>
<div class="identitas">
  <table>
    <tr><td><strong>Nama Lengkap</strong></td><td>: ' . htmlspecialchars($t['nama']) . '</td></tr>
    <tr><td><strong>Tanggal Lahir</strong></td><td>: ' . htmlspecialchars($t['tanggal_lahir']) . '</td></tr>
    <tr><td><strong>Parameter Depresi</strong></td><td>: ' . htmlspecialchars($t['P1']) . '</td></tr>
    <tr><td><strong>Parameter Kecemasan</strong></td><td>: ' . htmlspecialchars($t['P2']) . '</td></tr>
    <tr><td><strong>Parameter Stres</strong></td><td>: ' . htmlspecialchars($t['P3']) . '</td></tr>
    <tr><td><strong>Hasil Klasifikasi Tingkat Kesehatan Mental</strong></td><td>: ' . htmlspecialchars($t['hasil_klasifikasi']) . '</td></tr>
    <tr><td><strong>Tanggal Pengujian</strong></td><td>: ' . htmlspecialchars($t['tanggal_pengujian']) . '</td></tr>
  </table>
</div>

<table class="dass">
  <thead>
    <tr>
      <th style="width:30px;">No</th>
      <th style="width:60%;">Pertanyaan</th>
      <th>Tidak Pernah</th>
      <th>Kadang-Kadang</th>
      <th>Lumayan Sering</th>
      <th>Sangat Sering</th>
    </tr>
  </thead>
  <tbody>';

$pertanyaan = [
    'D1' => 'Saya merasa sulit untuk beristirahat',
    'A1' => 'Saya merasa bibir saya sering kering',
    'D2' => 'Saya sama sekali tidak dapat merasakan perasaan positif',
    'A2' => 'Saya mengalami kesulitan bernafas (misalnya: seringkali terengah-engah atau tidak dapat bernafas padahal tidak melakukan aktivitas fisik sebelumnya)',
    'D3' => 'Saya merasa sulit untuk meningkatkan inisiatif dalam melakukan sesuatu',
    'S1' => 'Saya cenderung bereaksi berlebihan terhadap suatu situasi',
    'A3' => 'Saya merasa gemetar (misalnya: pada tangan)',
    'A4' => 'Saya merasa telah menghabiskan banyak energi untuk merasa cemas',
    'A5' => 'Saya merasa khawatir dengan situasi dimana saya mungkin menjadi panik dan mempermalukan diri sendiri',
    'D4' => 'Saya merasa tidak ada hal yang dapat diharapkan di masa depan',
    'S2' => 'Saya menemukan diri saya mudah gelisah',
    'S3' => 'Saya merasa sulit untuk bersantai',
    'D5' => 'Saya merasa putus asa dan sedih',
    'S4' => 'Saya tidak dapat memaklumi hal apapun yang menghalangi saya untuk menyelesaikan hal yang sedang saya lakukan',
    'A6' => 'Saya merasa saya hampir panik',
    'D6' => 'Saya tidak merasa antusias dalam hal apapun',
    'D7' => 'Saya merasa bahwa saya tidak berharga sebagai seorang manusia',
    'S5' => 'Saya merasa bahwa saya mudah tersinggung',
    'A7' => 'Saya menyadari kegiatan jantung, walaupun saya tidak sehabis melakukan aktivitas fisik (misalnya: merasa detak jantung meningkat atau melemah)',
    'S6' => 'Saya merasa takut tanpa alasan yang jelas',
    'S7' => 'Saya merasa bahwa hidup tidak berarti'
];

$no = 1;
foreach ($pertanyaan as $kode => $teks) {
    $val = isset($t[$kode]) ? $t[$kode] : '';
    $col1 = ($val == 'Tidak Pernah') ? '☑' : '☐';
    $col2 = ($val == 'Kadang-kadang') ? '☑' : '☐';
    $col3 = ($val == 'Lumayan Sering') ? '☑' : '☐';
    $col4 = ($val == 'Sangat Sering') ? '☑' : '☐';
    $html .= "
    <tr>
      <td>{$no}</td>
      <td>{$teks}</td>
      <td>{$col1}</td>
      <td>{$col2}</td>
      <td>{$col3}</td>
      <td>{$col4}</td>
    </tr>";
    $no++;
}

$html .= '
  </tbody>
</table>';

$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("laporan_dass_21_" . $t['kd_pengujian'] . ".pdf", ["Attachment" => false]);
