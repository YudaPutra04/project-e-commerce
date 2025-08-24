<?php
session_start(); // mulai session

// Konfigurasi database (sama seperti sebelumnya)
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "project_e_commerce_db";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("koneksi gagal: " . $conn->connect_error);
}

// ambil data dari form
$username = $_POST['username'];
$password = $_POST['password'];

// cari user berdasarkan username
$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    // verifikasi password
    if (password_verify($password, $hashed_password)) {
        // jika password cocok, buat session
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $id;
        $_SESSION['username'] = $username;

        // arahkan ke halaman dashboard atau halaman utama setelah login
        header("location: halaman-utama/contoh.html"); 
        exit(); //selalu gunakan exit() setelah header redirect
    } else {
        // jika password salah
        echo "Password yang Anda masukkan salah.";
    }
} else {
    // jika username tidak ditemukan
    echo "Username tidak ditemukan.";
}

$stmt->close();
$conn->close();
?>