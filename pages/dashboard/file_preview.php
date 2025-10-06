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

if ($_SESSION['role'] == 'Admin') {
  $tests = query("SELECT * FROM tb_pengujian");
} else {
  $kd_user = $_SESSION['kd_user'];
  $tests = query("SELECT * FROM tb_pengujian WHERE kd_user = '$kd_user'");
}

$html = '
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Preview File</title>
  </head>
  <body>
    <div class="wrapper">
      <h1 style="font-size: 22px; text-align: center; margin-bottom: 32px;">Laporan Hasil Pengujian Tingkat Kesehatan Mental</h1>
      <table style="border-collapse: collapse; width: 100%;">
        <thead>
          <tr>
            <th style="border: 1px solid #000; padding: 8px; text-align:center; width:40px;">No</th>
            <th style="border: 1px solid #000; padding: 8px; text-align:center;">Tanggal Pengujian</th>
            <th style="border: 1px solid #000; padding: 8px; text-align:center;">Parameter Depresi</th>
            <th style="border: 1px solid #000; padding: 8px; text-align:center;">Parameter Kecemasan</th>
            <th style="border: 1px solid #000; padding: 8px; text-align:center;">Parameter Stres</th>
            <th style="border: 1px solid #000; padding: 8px; text-align:center;">Hasil Klasifikasi</th>
          </tr>
        </thead>
        <tbody>';

$no = 1;
foreach ($tests as $test) {
  $html .= "
          <tr>
            <td style='border:1px solid #000; padding:8px; text-align:center;'>{$no}</td>
            <td style='border:1px solid #000; padding:8px; text-align:center;'>{$test['tanggal_pengujian']}</td>
            <td style='border:1px solid #000; padding:8px; text-align:center;'>{$test['P1']}</td>
            <td style='border:1px solid #000; padding:8px; text-align:center;'>{$test['P2']}</td>
            <td style='border:1px solid #000; padding:8px; text-align:center;'>{$test['P3']}</td>
            <td style='border:1px solid #000; padding:8px; text-align:center;'>{$test['hasil_klasifikasi']}</td>
          </tr>";
  $no++;
}

$html .= '
        </tbody>
      </table>
    </div>
  </body>
</html>';

// Load ke Dompdf
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("tingkat_kesehatan_mental.pdf", ["Attachment" => false]);
