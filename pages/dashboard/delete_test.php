<?php

include './../../includes/functions.php';

$kd_pengujian = $_GET['kd_pengujian'];

if (deleteTest($kd_pengujian) > 0) {
    echo "
        <script>
            alert('Satu pengujian berhasil dihapus!');
            document.location.href = './history.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('Pengujian gagal dihapus!');
            document.location.href = './history.php';
        </script>
    ";
}
