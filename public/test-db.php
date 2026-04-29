<?php
$mysqli = new mysqli('localhost', 'root', '', 'kost_regar', 3306);
if ($mysqli->connect_error) {
    die('Gagal: ' . $mysqli->connect_error);
} else {
    echo 'Koneksi sukses';
}