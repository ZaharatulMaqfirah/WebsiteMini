<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";  // Sesuaikan dengan username MySQL kamu
$password = "";       // Sesuaikan dengan password MySQL kamu
$database = "marathon_db";  // Nama database

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data dari form
$nama = $_POST['nama'];
$email = $_POST['email'];
$nomor = $_POST['nomor'];
$kategori = $_POST['kategori'];

// Validasi sederhana
if (empty($nama) || empty($email) || empty($nomor) || empty($kategori)) {
    die("Semua kolom wajib diisi.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Format email tidak valid.");
}

// Menggunakan prepared statement
$stmt = $conn->prepare("INSERT INTO peserta (nama, email, nomor, kategori) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nama, $email, $nomor, $kategori);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pendaftaran</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="confirmation-container">
        <?php if ($stmt->execute()): ?>
            <div class="success-icon">✔</div>
            <h3>Pendaftaran Berhasil!</h3>
            <p>Terima kasih telah mendaftar, <strong><?php echo htmlspecialchars($nama); ?></strong>.</p>
            <p>Kami akan segera menghubungi Anda melalui email: <strong><?php echo htmlspecialchars($email); ?></strong>.</p>
            <a href="index.html">Kembali ke Beranda</a>
        <?php else: ?>
            <div class="error-icon">✖</div>
            <h3>Pendaftaran Gagal</h3>
            <p>Maaf, terjadi kesalahan: <?php echo htmlspecialchars($stmt->error); ?></p>
            <a href="register.html">Coba Lagi</a>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Menutup koneksi
$stmt->close();
$conn->close();
?>
