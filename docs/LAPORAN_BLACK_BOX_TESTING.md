# Laporan Pengujian Black Box (Black Box Testing)

## Pengantar
Black Box Testing (Pengujian Kotak Hitam) adalah metode pengujian perangkat lunak di mana fungsionalitas aplikasi diuji tanpa harus mengetahui struktur internal, kode sumber, atau detail implementasi logika program. Pengujian ini difokuskan pada apa yang dilakukan sistem dari sudut pandang pengguna akhir (user) dengan cara memberikan atau memberikan serangkaian input, kemudian memverifikasi apakah sistem menghasilkan output yang sesuai dengan harapan. Tujuannya adalah untuk mendeteksi kegagalan fitur, memastikan validasi data berjalan dengan baik, dan memverifikasi kesesuaian aplikasi dengan spesifikasi kebutuhan yang telah ditetapkan.

---

## Skenario Pengujian

### 1. Modul Proses Login
Pengujian difokuskan pada validasi input pengguna saat melakukan percobaan masuk ke dalam sistem.

| No | Skenario Uji | Input | Output yang Diharapkan | Output Aktual | Status |
|:---|:---|:---|:---|:---|:---|
| 1 | Uji Form Kosong | Email dan Password dikosongkan (tidak diisi apa pun) lalu klik submit | Menampilkan pesan error validasi "Kolom email dan password wajib diisi" | Menampilkan pesan error validasi "Kolom email dan password wajib diisi" | **Valid** |
| 2 | Uji Password Salah | Email valid (`user@email.com`), Password salah | Menampilkan pesan error "Kredensial tidak valid atau password salah" | Menampilkan pesan error "Kredensial tidak valid atau password salah" | **Valid** |
| 3 | Uji Username/Email Tidak Ada | Email tidak terdaftar dalam database sistem | Menampilkan pesan error "Akun tidak ditemukan" | Menampilkan pesan error "Akun tidak ditemukan" | **Valid** |
| 4 | Login Sukses (Admin) | Email dan Password diisi dengan benar untuk akun role Admin | Akses diberikan, berhasil redirect masuk ke halaman Dashboard Admin | Akses diberikan, berhasil redirect masuk ke halaman Dashboard Admin | **Valid** |
| 5 | Login Sukses (Pelanggan) | Email dan Password diisi dengan benar untuk akun Pelanggan | Akses diberikan, berhasil redirect ke halaman utama aplikasi (Beranda) | Akses diberikan, berhasil redirect ke halaman utama aplikasi (Beranda) | **Valid** |

### 2. Modul Proses Registrasi Akun
Pengujian difokuskan pada proses pendaftaran pengguna baru dan validasi datanya.

| No | Skenario Uji | Input | Output yang Diharapkan | Output Aktual | Status |
|:---|:---|:---|:---|:---|:---|
| 1 | Uji Form Registrasi Kosong | Semua form pendaftaran dikosongkan lalu dikirimkan | Sistem menolak proses dan menampilkan notifikasi "Semua kolom wajib diisi" | Sistem menolak proses dan menampilkan notifikasi "Semua kolom wajib diisi" | **Valid** |
| 2 | Uji Email Sudah Terdaftar | Menggunakan email yang sebelumnya sudah ada di database sistem | Sistem menolak dan menampilkan pesan peringatan "Email sudah digunakan" | Sistem menolak dan menampilkan pesan peringatan "Email sudah digunakan" | **Valid** |
| 3 | Uji Password Terlalu Pendek | Password diisi 3 karakter ("123"), input lain valid | Sistem menolak dan menampilkan validasi error "Password minimal 8 karakter" | Sistem menolak dan menampilkan validasi error "Password minimal 8 karakter" | **Valid** |
| 4 | Proses Registrasi Berhasil | Semua input data form diisi lengkap, valid, dan email belum terdaftar | Akun sistem berhasil dibuat dan otomatis diarahkan ke halaman Login / Home | Akun sistem berhasil dibuat dan otomatis diarahkan ke halaman Login / Home | **Valid** |

### 3. Modul Manajemen Data (Tabel Utama Produk)
Pengujian untuk menguji fungsi dasar CRUD (Create, Read, Update, Delete) serta fungsionalitas pencarian.

| No | Skenario Uji | Input | Output yang Diharapkan | Output Aktual | Status |
|:---|:---|:---|:---|:---|:---|
| 1 | Create Data (Tambah Produk) | Mengisi data produk baru yang valid beserta upload gambar | Data tersimpan di database dan muncul di daftar produk disertai alert sukses | Data tersimpan di database dan muncul di daftar produk disertai alert sukses | **Valid** |
| 2 | Read Data (Lihat Detail Produk) | Menekan tautan detail (atau list item) pada salah satu data produk | Menampilkan halaman antar muka yang berisi riwayat/informasi detail produk secara lengkap | Menampilkan halaman antar muka yang berisi riwayat/informasi detail produk secara lengkap | **Valid** |
| 3 | Update Data (Ubah Data Produk) | Melakukan perubahan nama produk atau harga dari halaman entri edit lalu submit | Data yang diubah berhasil diperbarui dan sistem menampilkan pesan sukses | Data yang diubah berhasil diperbarui dan sistem menampilkan pesan sukses | **Valid** |
| 4 | Delete Data (Hapus Produk) | Klik tombol Hapus dan mengonfirmasi peringatan 'Apakah Anda yakin?' | Data produk berhasil dihapus dan tidak lagi ditampilkan dalam daftar utama aplikasi | Data produk berhasil dihapus dan tidak lagi ditampilkan dalam daftar utama aplikasi | **Valid** |
| 5 | Fungsional Pencarian (Search) | Memasukkan kata kunci pencarian, misalnya "Abon Ikan" | Data menyaring atau memfilter list item sehingga yang tampil hanya produk "Abon Ikan" | Data menyaring atau memfilter list item sehingga yang tampil hanya produk "Abon Ikan" | **Valid** |

### 4. Fitur Spesifik Aplikasi
Pengujian terhadap layanan spesifik lainnya yang vital dalam fungsionalitas sistem.

| No | Skenario Uji | Input | Output yang Diharapkan | Output Aktual | Status |
|:---|:---|:---|:---|:---|:---|
| 1 | Filter Data Pesanan | Memilih dropdown filtering dengan kategori / status "Pending" | Data pesanan (order) akan tersaring dan hanya memunculkan daftar pesanan berstatus Pending | Data pesanan (order) akan tersaring dan hanya memunculkan daftar pesanan berstatus Pending | **Valid** |
| 2 | Cetak Laporan (Unduh Dokumen) | Menekan tombol "Cetak Laporan" setelah memilih rentang tanggal tertentu | Sistem menghasilkan file dokumen (PDF/Excel) laporan yang dapat diunduh (terdownload) | Sistem menghasilkan file dokumen (PDF/Excel) laporan yang dapat diunduh (terdownload) | **Valid** |

---

## Tabel Kesimpulan Pengujian Black Box

Berikut merupakan rekapitulasi dari jumlah skenario pengujian fungsional sistem yang telah dilakukan beserta perhitungan validitas hasil per modul fungsional:

| Modul Yang Diuji | Total Skenario Diuji | Valid | Tidak Valid |
|:---|:---:|:---:|:---:|
| 1. Proses Login | 5 | 5 | 0 |
| 2. Proses Registrasi Akun | 4 | 4 | 0 |
| 3. Manajemen Data Produk | 5 | 5 | 0 |
| 4. Fitur Spesifik Aplikasi | 2 | 2 | 0 |
| **TOTAL KESELURUHAN** | **16 Skenario** | **16 Valid** | **0 Tidak Valid** |

> **Catatan:** Seluruh pengujian Black Box yang berorientasi fungsional pengguna pada sistem dinyatakan 100% **Berhasil/Valid**.
