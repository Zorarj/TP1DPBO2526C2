<?php
session_start();

// ==========================================
// 1. CLASS FILM (Representasi Objek Film)
// ==========================================
class Film {
    private $id;
    private $name;
    private $genre;
    private $price;
    private $image;

    public function __construct($id, $name, $genre, $price, $image = 'uploads/default.jpg') {
        $this->id = $id;
        $this->name = $name;
        $this->genre = $genre;
        $this->price = $price;
        $this->image = $image;
    }

    // Getter Methods (Enkapsulasi)
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getGenre() { return $this->genre; }
    public function getPrice() { return $this->price; }
    public function getImage() { return $this->image; }

    // Setter Methods
    public function setName($name) { $this->name = $name; }
    public function setGenre($genre) { $this->genre = $genre; }
    public function setPrice($price) { $this->price = $price; }
    public function setImage($image) { $this->image = $image; }
}

// ==========================================
// 2. CLASS FILM MANAGER (Pengelola Koleksi Film)
// ==========================================
class FilmManager {
    private $listFilm = [];

    public function __construct() {
        // Ambil data dari SESSION jika ada
        if (isset($_SESSION['film_manager'])) {
            $this->listFilm = unserialize($_SESSION['film_manager']);
        }
    }

    // Simpan status objek ke SESSION
    private function saveToSession() {
        $_SESSION['film_manager'] = serialize($this->listFilm);
    }

    public function getListFilm() {
        return $this->listFilm;
    }

    public function addFilm($id, $name, $genre, $price, $fileImage) {
        if ($id < 0 || $price < 0) {
            return "Error: ID dan Price tidak boleh minus!";
        }

        // Cek duplicate ID
        foreach ($this->listFilm as $film) {
            if ($film->getId() === $id) {
                return "Error: Film dengan ID $id sudah ada!";
            }
        }

        $imagePath = $this->uploadImage($fileImage);
        $newFilm = new Film($id, $name, $genre, $price, $imagePath);
        $this->listFilm[] = $newFilm;

        $this->saveToSession();
        return "Film berhasil ditambahkan!";
    }

    public function updateFilm($id, $name, $genre, $price, $fileImage) {
        foreach ($this->listFilm as $film) {
            if ($film->getId() === $id) {
                $film->setName($name);
                $film->setGenre($genre);
                $film->setPrice($price);

                // Jika ada upload gambar baru
                if (isset($fileImage) && $fileImage['error'] === UPLOAD_ERR_OK) {
                    $newPath = $this->uploadImage($fileImage);
                    $film->setImage($newPath);
                }

                $this->saveToSession();
                return "Data film dengan ID $id berhasil diperbarui!";
            }
        }
        return "Data film dengan ID $id tidak ditemukan..";
    }

    public function removeFilm($id) {
        foreach ($this->listFilm as $key => $film) {
            if ($film->getId() === $id) {
                // Hapus file fisik gambar jika ada
                if (file_exists($film->getImage()) && strpos($film->getImage(), 'default.jpg') === false) {
                    unlink($film->getImage());
                }

                unset($this->listFilm[$key]);
                $this->listFilm = array_values($this->listFilm); // Re-index array

                $this->saveToSession();
                return "Data film dengan ID $id berhasil dihapus!!";
            }
        }
        return "Data film dengan ID $id tidak ditemukan..";
    }

    // Helper Method untuk Handling File Upload
    private function uploadImage($fileImage) {
        $uploadDir = './uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (isset($fileImage) && $fileImage['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileImage['name'], PATHINFO_EXTENSION));
            $newName = time() . '_' . uniqid() . '.' . $ext;
            $destPath = $uploadDir . $newName;

            if (move_uploaded_file($fileImage['tmp_name'], $destPath)) {
                return $destPath;
            }
        }
        return 'uploads/default.jpg';
    }
}

// ==========================================
// 3. INSIALISASI & HANDLING REQUEST
// ==========================================
$manager = new FilmManager();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name   = trim($_POST['name'] ?? '');
    $genre  = trim($_POST['genre'] ?? '');
    $price  = isset($_POST['price']) ? (int)$_POST['price'] : 0;
    $image  = $_FILES['image'] ?? null;

    if ($action === 'add') {
        $message = $manager->addFilm($id, $name, $genre, $price, $image);
    } elseif ($action === 'update') {
        $message = $manager->updateFilm($id, $name, $genre, $price, $image);
    } elseif ($action === 'delete') {
        $message = $manager->removeFilm($id);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Film (OOP)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
        .alert { padding: 10px; background-color: #e7f3fe; border-left: 6px solid #2196F3; margin-bottom: 15px; }
        .form-container { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { padding: 8px 15px; color: white; border: none; cursor: pointer; border-radius: 3px; }
        .btn-add { background-color: #4CAF50; }
        .btn-update { background-color: #ff9800; }
        .btn-delete { background-color: #f44336; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; vertical-align: middle; }
        th { background-color: #f2f2f2; }
        img.poster { width: 80px; height: auto; border-radius: 4px; }
    </style>
</head>
<body>

    <h2>Manajemen Data Film (OOP Version)</h2>

    <?php if (!empty($message)): ?>
        <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- FORM INPUT / UPDATE DATA FILM -->
    <div class="form-container">
        <h3>Form Input / Edit Film</h3>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label>ID Film:</label>
                <input type="number" name="id" required min="0">
            </div>
            <div class="form-group">
                <label>Nama Film:</label>
                <input type="text" name="name">
            </div>
            <div class="form-group">
                <label>Genre:</label>
                <input type="text" name="genre">
            </div>
            <div class="form-group">
                <label>Harga (Rp):</label>
                <input type="number" name="price" min="0">
            </div>
            <div class="form-group">
                <label>Gambar Poster (Lokal):</label>
                <input type="file" name="image" accept="image/*">
            </div>
            
            <button type="submit" name="action" value="add" class="btn btn-add">Tambah Film</button>
            <button type="submit" name="action" value="update" class="btn btn-update">Update Film (by ID)</button>
        </form>
    </div>

    <!-- TABEL MENAMPILKAN DATA FILM -->
    <h3>Daftar Film</h3>
    <table>
        <thead>
            <tr>
                <th>Poster</th>
                <th>ID</th>
                <th>Nama Film</th>
                <th>Genre</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $films = $manager->getListFilm(); 
            if (empty($films)): 
            ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada data film.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($films as $film): ?>
                    <tr>
                        <td>
                            <?php if (file_exists($film->getImage())): ?>
                                <img src="<?= htmlspecialchars($film->getImage()) ?>" alt="Poster" class="poster">
                            <?php else: ?>
                                <span>No Image</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($film->getId()) ?></td>
                        <td><?= htmlspecialchars($film->getName()) ?></td>
                        <td><?= htmlspecialchars($film->getGenre()) ?></td>
                        <td>Rp<?= number_format($film->getPrice(), 0, ',', '.') ?></td>
                        <td>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $film->getId() ?>">
                                <button type="submit" name="action" value="delete" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus film ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>