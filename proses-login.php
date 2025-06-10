<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query_username = $conn->query("SELECT * FROM user WHERE username = '$username'");
    if ($query_username->num_rows > 0) {
        $data = $query_username->fetch_assoc();
        if ($password == $data['password']) {
            $_SESSION['username'] = $data['username'];
            $_SESSION['name'] = $data['nama'];
            if ($data['level'] == 'admin') {
                header('location: admin/index.php');
                exit();
            } else {
                header('location: index.php');
                exit();
            }
        } else {
            echo "<script>alert('Password Salah atau belum diisi');</script>";
            echo "<script>window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Username tidak terdaftar');</script>";
    }
}
?>
