<?php

include './../../includes/functions.php';

$kd_data = $_GET['kd_data'];

if (deleteData($kd_data) > 0) {
    echo "
        <script>
            alert('Data berhasil dihapus!');
            document.location.href = './data.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('Data gagal dihapus!');
            document.location.href = './data.php';
        </script>
    ";
}
