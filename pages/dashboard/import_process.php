<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../index.php");
    exit;
}

include './../../includes/functions.php';

// Cek apakah file sudah diupload
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $tmpName = $_FILES['file']['tmp_name'];

    // Panggil fungsi importDataExcel
    $affectedRows = importDataExcel($tmpName);

    if ($affectedRows > 0) {
        echo "
            <script>
                alert('Import data berhasil!');
                window.location.href = 'data.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Import data gagal!');
                window.location.href = 'data.php';
            </script>
        ";
    }
} else {
    echo "
        <script>
            alert('Tidak ada file yang diupload!');
            window.location.href = 'data.php';
        </script>
    ";
}
