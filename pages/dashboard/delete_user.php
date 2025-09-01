<?php
include './../../includes/functions.php';

$kdUser = $_GET['kd_user'];

if (deleteUser($kdUser) > 0) {
    echo "
        <script>
            alert('Pengguna berhasil dihapus');
            document.location.href = './user_management.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('Pengguna gagal dihapus');
            document.location.href = './user_management.php';
        </script>
    ";
}
