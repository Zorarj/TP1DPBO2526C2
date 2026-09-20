# Dokumentasi Desain & Alur Sistem Manajemen Film (Multi-Language)

Dokumen ini berisi penjelasan mengenai **desain arsitektur berbasis Object-Oriented Programming (OOP)** serta **alur kerja (flow) program** pada implementasi Sistem Manajemen Data Film di empat bahasa pemrograman: **Java**, **C++**, **Python**, dan **PHP (Web OOP)**.

---

## 🏗️ 1. Desain Arsitektur & Konsep OOP

Sistem ini dirancang menggunakan paradigma **Pemrograman Berorientasi Objek (OOP)** untuk mengelola data film secara terstruktur tanpa menggunakan database relasional eksternal.

### A. Struktur Komponen Utama

1. **Model Data (`Film`)**
   - Bertindak sebagai entitas data tunggal.
   - **Atribut:** `id`, `name`, `genre`, `price`, dan `image` (khusus versi PHP Web)[cite: 4, 6].
   - **Pada Java, C++, dan Python:** Kelas `Film` bertindak ganda sebagai representasi objek sekaligus pengelola *list* internal[cite: 2, 5, 6].
   - **Pada PHP Web:** Menerapkan **Enkapsulasi Murni** dengan atribut bertipe `private` yang diakses menggunakan *Getter* (`getId()`, `getName()`, dll.) dan diubah melalui *Setter* (`setName()`, `setPrice()`, dll.)[cite: 4].

2. **Pengelola Data (`FilmManager` / Container)**
   - **Java (CLI):** Menggunakan array statis berukuran tetap (`Film[100]`) yang dilacak menggunakan variabel indeks `num`[cite: 6].
   - **C++ (CLI):** Menggunakan `std::vector<Film>` dinamis dari C++ Standard Library[cite: 2].
   - **Python (CLI):** Menggunakan struktur data *List* dinamis bawaan Python (`self.list_film = []`)[cite: 5].
   - **PHP (Web):** Menggunakan kelas terpisah `FilmManager`[cite: 4]. State objek disimpan secara persisten antar *request* HTTP di dalam `$_SESSION` menggunakan teknik **Serialization** (`serialize()` dan `unserialize()`)[cite: 4].

---

## 🔄 2. Penjelasan Alur Kode (Code Flow)

### A. Alur Kerja Umum (Operasi CRUD)

1. **Inisialisasi & Memuat State:**
   - Program pertama kali berjalan dengan menginisialisasi objek pengelola (*manager*).
   - Pada aplikasi web, program membaca data yang tersimpan di dalam sesi (`$_SESSION`) untuk merekonstruksi kembali daftar film[cite: 4].

2. **Penerimaan & Validasi Input:**
   - Menerima parameter input data (ID, Nama, Genre, Harga, serta File Gambar pada web)[cite: 4].
   - Memvalidasi apakah ID atau Harga bernilai negatif (< 0)[cite: 4, 6]. Jika ya, sistem menolak proses dan memberikan pesan peringatan[cite: 4, 6].
   - Mengecek keberadaan ID untuk mencegah duplikasi data saat penambahan[cite: 4].

3. **Eksekusi Operasi CRUD:**
   - **Create (Tambah):** Membua instansi objek `Film` baru dan memasukkannya ke dalam kontainer (Array/Vector/List/Session)[cite: 2, 4, 5, 6]. Pada versi web, proses ini mencakup penyimpanan file gambar poster ke direktori lokal `uploads/`[cite: 4].
   - **Read (Tampil):** Melakukan perulangan (*looping*) pada koleksi data untuk menampilkan seluruh informasi film ke konsol CLI atau tabel HTML[cite: 2, 4, 5, 6].
   - **Update (Ubah):** Mencari film berdasarkan ID target[cite: 2, 4, 5, 6]. Jika ditemukan, memperbarui nilai atributnya (Nama, Genre, Harga, atau Gambar)[cite: 2, 4, 5, 6].
   - **Delete (Hapus):** Mencari film berdasarkan ID target[cite: 2, 4, 5, 6]. Jika ditemukan, menghapus objek dari koleksi data dan merapikan urutan indeks[cite: 2, 4, 5, 6]. Pada versi web, file gambar fisik juga dihapus dari disk[cite: 4].

4. **Menyimpan State & Output:**
   - Menyimpan pembaruan data ke memori atau sesi (`$_SESSION`)[cite: 4].
   - Menampilkan umpan balik (pesan sukses/gagal) dan memperbarui tampilan interface[cite: 2, 4, 5, 6].

---

### B. Detail Alur Per Bahasa Program

#### 1. Java (`Film.java` & `Main.java`)
- **Penambahan Data (`addFilm`):** Memeriksa apakah `id` atau `price` bernilai minus (< 0)[cite: 6]. Jika valid, buat instansiasi `new Film(id, name, genre, price)` lalu masukkan ke array `listFilm[num]` dan naikkan nilai `num++`[cite: 6].
- **Pencarian & Update (`showFilm` / `updateFilm`):** Melakukan iterasi berbasis indeks `for(i = 0; i < num)`[cite: 6]. Jika `id` cocok, lakukan pembaruan nilai/penampilan lalu keluar method dengan `return`[cite: 6].
- **Penghapusan (`removeFilm`):** Menemukan indeks elemen target, lalu melakukan **pergeseran elemen ke kiri** secara manual pada array untuk menutup celah kosong, serta menurunkan nilai `num--`[cite: 6].

#### 2. C++ (`Film.cpp`)
- **Penambahan Data (`addFilm`):** Memvalidasi nilai input[cite: 2]. Membuat objek baru dan memasukkannya ke dalam `std::vector` menggunakan method `.push_back()`[cite: 2].
- **Pencarian & Update:** Menggunakan *range-based for loop* (`for (auto& film : listFilm)`) untuk mereferensikan objek secara langsung di memori[cite: 2].
- **Penghapusan (`removeFilm`):** Mencari indeks posisi elemen, kemudian memanggil `listFilm.erase()`[cite: 2]. Elemen setelahnya akan dirapikan otomatis oleh `std::vector`[cite: 2].

#### 3. Python (`film.py`)
- **Inisialisasi:** Method `__init__` menyiapkan atribut instance beserta *list* kosongan `self.list_film`[cite: 5].
- **Penambahan Data (`add_film`):** Memvalidasi parameter[cite: 5]. Jika valid, menginstansiasi objek `Film` baru dan menambahkannya ke list menggunakan `.append()`[cite: 5].
- **Penghapusan (`remove_film`):** Memanfaatkan fungsi `enumerate()` untuk mendapatkan indeks dan objek sekaligus[cite: 5]. Saat ID ditemukan, perintah `del self.list_film[i]` mengeksekusi penghapusan dari memori[cite: 5].

#### 4. PHP Web OOP (`index.php`)
- **Inisialisasi State:** Setiap halaman dimuat, `FilmManager::__construct()` memeriksa `$_SESSION['film_manager']`[cite: 4]. Jika data ada, dilakukan `unserialize()` untuk merekonstruksi kembali objek-objek `Film`[cite: 4].
- **Handling Form POST:** Membaca parameter aksi (`add`, `update`, `delete`) dari pengiriman form[cite: 4]:
  - **Tambah & Edit:** Mengelola *file upload* gambar lokal[cite: 4]. File dipindahkan ke direktori `./uploads/` dengan penamaan unik berbasis `time() + uniqid()`, lalu path file disimpan pada properti objek[cite: 4].
  - **Penghapusan:** Menghapus file gambar fisik dari disk menggunakan `unlink()` (jika bukan gambar default), menghapus elemen dari array dengan `unset()`, dan mereorganisasi indeks array dengan `array_values()`[cite: 4].
- **Persistensi & Rendering:** Setiap perubahan diakhiri dengan pemanggilan method internal `saveToSession()` yang melakukan `serialize()` pada daftar objek[cite: 4]. Tampilan daftar film dirender ke dalam tabel HTML dengan mengakses method *getter* masing-masing objek[cite: 4].

![alt](<Dokumentasi/Screenshot 2026-09-20 202040.png>)
![alt](<Dokumentasi/Screenshot 2026-09-20 203514.png>)
![alt](<Dokumentasi/Screenshot 2026-09-20 203525.png>)
![alt](<Dokumentasi/Screenshot 2026-09-20 203553.png>)
![alt](<Dokumentasi/Screenshot 2026-09-20 203627.png>)
![alt](<Dokumentasi/Screenshot 2026-09-20 203722.png>)
![alt](<Dokumentasi/Screenshot 2026-09-20 203733.png>)