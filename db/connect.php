<?php

$conn = mysqli_connect("localhost", "root", "", "db_kesehatan_mental");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
