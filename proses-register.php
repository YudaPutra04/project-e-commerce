<?php
//dua baris ini untuk menampilkan error jika ada, sangat berguna saat development
ini_set('display_errors', 1);
error_reporting(E_ALL);

//konfigurasi koneksi database
$servername ="localhost";
$db_username = "root";
$db_password = "";
$dbname = "project_e_commerce_db";
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

//buat koneksi
if ($conn->connect_error) {
    die("koneksi gagal: " .$conn->connect_error);
}

//ambil data dari form register.html
$username = $_POST['username'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$password = $_POST['password'];

//validasi sederhana, pastikan input yang wajib tidak kosong
if (empty($username) || empty($email) || empty ($password)) {
    die("error: Username, Email, dan Password tidak boleh kosong.");
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, email, phone_number, password) VALUES (?,?,?,?)");

$stmt->bind_param("ssss", $username, $email, $phone_number,$hashed_password);

//eksekusi statment dan beri pesan
if ($stmt->execute()) {
    echo "<h1>REGISTRASI BERHASIL!</h1>";
    echo "<p>Anda sekarang bisa kembali ke halaman login.</p>";
    echo "<a href='halaman-login/login.html'>Kembali ke Login</a>";
} else {
    //cek jika ada error karena duplikat (username/email sudah ada)
    if ($conn->errno == 1062) {
        echo "Error: Registrasi gagal. Username atau Email sudah terdaftar.";
    } else {
        //error lainnya
        echo "error: " .$stmt->error;
    }
}

//tutup statment dan koneksi
$stmt->close();
$conn->close();
?>