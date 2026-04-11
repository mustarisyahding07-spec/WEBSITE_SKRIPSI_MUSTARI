# BAB IV: HASIL DAN PEMBAHASAN

---

## 4.1 Hasil Pengumpulan Data

Dalam tahapan pengembangan sistem informasi e-commerce untuk UMKM Ivo Karya, proses pengumpulan data primer merupakan langkah esensial untuk memastikan bahwa sistem yang dibangun merepresentasikan kondisi operasional bisnis secara faktual dan akurat. Pengumpulan data difokuskan pada spesifikasi inventaris produk utama yang diproduksi dan didistribusikan oleh entitas bisnis yang menunjang aktivitas perekonomian dasar, yakni produk olahan abon ikan.

Berdasarkan hasil observasi lapangan dan wawancara terstruktur yang dilakukan dengan pihak manajemen UMKM Ivo Karya, diperoleh matriks penetapan harga jual produk (*pricing structure*) yang diklasifikasikan secara hierarkis berdasarkan satuan massa (berat) kemasan produk. Data profil kemasan produk abon ikan yang berhasil dihimpun diuraikan secara komprehensif pada Tabel 4.1 berikut.

**Tabel 4.1** Struktur Harga Produk Abon Ikan UMKM Ivo Karya

| No | Varian Kemasan (Berat) | Ekuivalensi Massa | Harga Jual (IDR) |
|:--:|:-----------------------|:-----------------:|:-----------------|
| 1  | Kemasan 100 gram       | 100 gr            | Rp 32.000        |
| 2  | Kemasan 160 gram       | 160 gr            | Rp 50.000        |
| 3  | Kemasan 500 gram       | 500 gr            | Rp 160.000       |
| 4  | Kemasan 1 Kilogram     | 1000 gr           | Rp 320.000       |

### 4.1.1 Implikasi Data Terhadap Perancangan Sistem

Data kuantitatif yang dipaparkan pada Tabel 4.1 memiliki validitas dan urgensi tinggi serta memberikan implikasi langsung terhadap perancangan basis data maupun logika operasional (*business logic*) pada platform digital yang dikembangkan:

1. **Populasi Entitas Basis Data**: Parameter berat dan harga yang dikumpulkan berfungsi sebagai atribut konkret (data *dummy* terlegitimasi) untuk proses populasi awal (*database seeding*) ke dalam entitas tabel `products`. Atribut massa (dalam ekuivalensi gram) diintegrasikan langsung ke dalam spesifikasi kolom `weight`, yang menjadi variabel pembatas matematis krusial untuk otomatisasi kalkulasi biaya logistik (*shipping cost*) melalui komunikasi dengan antarmuka pemrograman aplikasi pihak ketiga (seperti API Ekspedisi Kurir).
2. **Proporsionalitas Nilai Ekonomis dan Skalabilitas Harga**: Analisis komparatif terhadap struktur harga mengindikasikan adanya linearitas harga yang menjaga rasio nilai keekonomian yang stabil di setiap peningkatan gramasi. Rasionalitas ini akan divisualisasikan dengan pendekatan transparan pada antarmuka klien (*frontend UX*), sehingga memberikan referensi rasional bagi calon konsumen dalam mengeksekusi konversi pembelian.
3. **Standardisasi Varian Produk (*Product SKUs*)**: Setiap kemasan yang berbeda direpresentasikan secara independen sebagai identitas *Stock Keeping Unit* (SKU) yang deterministik. Pendekatan diskrit ini memastikan mekanisme pelacakan inventaris (*inventory tracking*) pada modul administrasi Filament beroperasi dengan tingkat otonomi dan resolusi pelacakan stok yang tinggi tanpa tumpang tindih data.

Data primer ini dijamin keabsahan empirisnya selaras dengan konfirmasi faktual dari pemilik usaha dan menjadi referensi empiris (*ground truth*) dalam mendemonstrasikan integritas serta keakuratan proses kalkulasi finansial sistem saat memasuki siklus pengujian akhir aplikasi (fase asersi validasi).
