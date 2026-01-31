# 📊 Activity Diagrams - Platform E-Commerce Ivo Karya

> **Kumpulan Diagram Aktivitas untuk Setiap Halaman Sistem**

---

## 📋 Daftar Isi

### Halaman Publik
1. [Landing Page](#1-landing-page)
2. [Katalog Produk](#2-katalog-produk)
3. [Detail Produk](#3-detail-produk)
4. [Keranjang Belanja](#4-keranjang-belanja)
5. [Checkout](#5-checkout)
6. [Pelacakan Pesanan](#6-pelacakan-pesanan)
7. [Daftar Artikel](#7-daftar-artikel)
8. [Detail Artikel](#8-detail-artikel)
9. [Login](#9-login)
10. [Register](#10-register)

### Halaman Admin
11. [Dashboard Admin](#11-dashboard-admin)
12. [Manajemen Produk](#12-manajemen-produk)
13. [Manajemen Kategori](#13-manajemen-kategori)
14. [Manajemen Pesanan](#14-manajemen-pesanan)
15. [Manajemen Artikel](#15-manajemen-artikel)
16. [Moderasi Review](#16-moderasi-review)
17. [Pengaturan Sistem](#17-pengaturan-sistem)

---

## Halaman Publik

---

### 1. Landing Page

**URL:** `/`

```mermaid
flowchart TD
    Start([👤 Pengunjung Mengakses Website]) --> LoadPage[Muat Halaman Landing]
    LoadPage --> DisplayHero[Tampilkan Hero Section]
    DisplayHero --> DisplayFeatures[Tampilkan Keunggulan Produk]
    DisplayFeatures --> LoadProducts[Ambil Produk Unggulan dari Database]
    LoadProducts --> DisplayProducts[Tampilkan Grid Produk Unggulan]
    DisplayProducts --> DisplayProcess[Tampilkan Proses Pembuatan]
    DisplayProcess --> LoadReviews[Ambil Review Terbaru]
    LoadReviews --> DisplayTestimonial[Tampilkan Testimoni Pelanggan]
    DisplayTestimonial --> DisplayCTA[Tampilkan Call-to-Action]
    DisplayCTA --> UserAction{Aksi Pengguna?}
    
    UserAction -->|Klik Produk| ProductDetail[Ke Detail Produk]
    UserAction -->|Klik Katalog| Catalog[Ke Halaman Katalog]
    UserAction -->|Klik Artikel| Articles[Ke Halaman Artikel]
    UserAction -->|Scroll Terus| DisplayCTA
    UserAction -->|Klik Chatbot| OpenChatbot[Buka Widget Chatbot]
    
    ProductDetail --> End([Selesai])
    Catalog --> End
    Articles --> End
    OpenChatbot --> ChatInteraction[Interaksi dengan Chatbot]
    ChatInteraction --> End
```

---

### 2. Katalog Produk

**URL:** `/katalog`

```mermaid
flowchart TD
    Start([👤 Buka Halaman Katalog]) --> LoadPage[Muat Halaman Katalog]
    LoadPage --> FetchCategories[Ambil Daftar Kategori]
    FetchCategories --> FetchProducts[Ambil Semua Produk Aktif]
    FetchProducts --> DisplayFilters[Tampilkan Filter & Sort Options]
    DisplayFilters --> DisplayGrid[Tampilkan Grid Produk]
    DisplayGrid --> UserAction{Aksi Pengguna?}
    
    UserAction -->|Pilih Kategori| FilterCategory[Filter Berdasarkan Kategori]
    FilterCategory --> FetchFiltered[Ambil Produk Sesuai Filter]
    FetchFiltered --> DisplayGrid
    
    UserAction -->|Ubah Sorting| ApplySort[Terapkan Sorting]
    ApplySort --> SortProducts[Urutkan Produk]
    SortProducts --> DisplayGrid
    
    UserAction -->|Klik Produk| GoToDetail[Navigasi ke Detail Produk]
    GoToDetail --> End([Selesai])
    
    UserAction -->|Klik Tambah Keranjang| AddToCart[Tambah ke Keranjang]
    AddToCart --> ShowNotification[Tampilkan Notifikasi Sukses]
    ShowNotification --> DisplayGrid
    
    UserAction -->|Klik Pagination| ChangePage[Pindah Halaman]
    ChangePage --> FetchProducts
```

---

### 3. Detail Produk

**URL:** `/product/{slug}`

```mermaid
flowchart TD
    Start([👤 Buka Detail Produk]) --> FetchProduct[Ambil Data Produk dari Database]
    FetchProduct --> ProductFound{Produk Ditemukan?}
    
    ProductFound -->|Tidak| Show404[Tampilkan Halaman 404]
    Show404 --> End([Selesai])
    
    ProductFound -->|Ya| DisplayImage[Tampilkan Gambar Produk]
    DisplayImage --> DisplayInfo[Tampilkan Info Produk]
    DisplayInfo --> DisplayPrice[Tampilkan Harga & Diskon]
    DisplayPrice --> CheckStock{Stok Tersedia?}
    
    CheckStock -->|Tidak| ShowOutOfStock[Tampilkan: Stok Habis]
    CheckStock -->|Ya| ShowAddButton[Tampilkan Tombol Tambah]
    
    ShowOutOfStock --> DisplayDescription
    ShowAddButton --> DisplayDescription[Tampilkan Deskripsi]
    DisplayDescription --> FetchReviews[Ambil Review Produk]
    FetchReviews --> DisplayReviews[Tampilkan Daftar Review]
    DisplayReviews --> DisplayRelated[Tampilkan Produk Terkait]
    DisplayRelated --> UserAction{Aksi Pengguna?}
    
    UserAction -->|Klik Tambah| SelectQty[Pilih Jumlah]
    SelectQty --> ValidateQty{Qty <= Stok?}
    ValidateQty -->|Ya| AddToCart[Tambah ke Keranjang]
    ValidateQty -->|Tidak| ShowError[Tampilkan Error Qty]
    ShowError --> SelectQty
    AddToCart --> ShowSuccess[Notifikasi Sukses]
    ShowSuccess --> UserAction
    
    UserAction -->|Tulis Review| OpenReviewForm[Buka Form Review]
    OpenReviewForm --> InputReview[Input Rating & Komentar]
    InputReview --> SubmitReview[Submit Review]
    SubmitReview --> SaveReview[(Simpan ke Database)]
    SaveReview --> ShowPending[Tampilkan: Menunggu Moderasi]
    ShowPending --> UserAction
    
    UserAction -->|Klik Produk Lain| GoToOther[Navigasi ke Produk Lain]
    GoToOther --> End
```

---

### 4. Keranjang Belanja

**URL:** `/cart`

```mermaid
flowchart TD
    Start([👤 Buka Keranjang]) --> LoadCart[Ambil Data Keranjang dari Session]
    LoadCart --> HasItems{Ada Item?}
    
    HasItems -->|Tidak| ShowEmpty[Tampilkan: Keranjang Kosong]
    ShowEmpty --> ShowCatalogLink[Tampilkan Link ke Katalog]
    ShowCatalogLink --> End([Selesai])
    
    HasItems -->|Ya| DisplayItems[Tampilkan Daftar Item]
    DisplayItems --> CalculateSubtotal[Hitung Subtotal]
    CalculateSubtotal --> DisplaySummary[Tampilkan Ringkasan Belanja]
    DisplaySummary --> UserAction{Aksi Pengguna?}
    
    UserAction -->|Ubah Quantity| UpdateQty[Update Jumlah Item]
    UpdateQty --> ValidateStock{Qty <= Stok?}
    ValidateStock -->|Ya| SaveQty[Simpan Perubahan]
    ValidateStock -->|Tidak| ShowMaxError[Error: Melebihi Stok]
    ShowMaxError --> DisplayItems
    SaveQty --> RecalculateCart[Hitung Ulang Total]
    RecalculateCart --> DisplayItems
    
    UserAction -->|Klik Hapus| ConfirmDelete{Konfirmasi Hapus?}
    ConfirmDelete -->|Ya| RemoveItem[Hapus Item dari Session]
    ConfirmDelete -->|Tidak| DisplayItems
    RemoveItem --> CheckRemaining{Masih Ada Item?}
    CheckRemaining -->|Ya| DisplayItems
    CheckRemaining -->|Tidak| ShowEmpty
    
    UserAction -->|Lanjut Checkout| GoToCheckout[Scroll ke Form Checkout]
    GoToCheckout --> End
```

---

### 5. Checkout

**URL:** `/cart` (bagian checkout form)

```mermaid
flowchart TD
    Start([👤 Mulai Checkout]) --> DisplayForm[Tampilkan Form Checkout]
    DisplayForm --> InputName[Input Nama Lengkap]
    InputName --> InputPhone[Input No. WhatsApp]
    InputPhone --> InputAddress[Input Alamat Lengkap]
    InputAddress --> OpenLocationPicker[Buka Location Picker]
    
    OpenLocationPicker --> LocationMethod{Metode Lokasi?}
    LocationMethod -->|GPS| RequestGPS[Minta Izin GPS]
    RequestGPS --> GetCoordinates[Dapatkan Koordinat]
    GetCoordinates --> ReverseGeocode[Reverse Geocode ke Alamat]
    ReverseGeocode --> DisplayLocation[Tampilkan Lokasi di Peta]
    
    LocationMethod -->|Manual| InputPostalCode[Input Kode Pos]
    InputPostalCode --> SearchCity[Cari Kota dari API]
    SearchCity --> CityFound{Kota Ditemukan?}
    CityFound -->|Tidak| ShowLocationError[Error: Lokasi Tidak Ditemukan]
    ShowLocationError --> InputPostalCode
    CityFound -->|Ya| DisplayLocation
    
    DisplayLocation --> FetchShipping[Ambil Opsi Pengiriman dari API]
    FetchShipping --> ShippingAvailable{Ada Layanan?}
    ShippingAvailable -->|Tidak| ShowNoService[Error: Tidak Ada Layanan]
    ShowNoService --> InputPostalCode
    ShippingAvailable -->|Ya| DisplayShippingOptions[Tampilkan Pilihan Kurir]
    
    DisplayShippingOptions --> SelectCourier[Pilih Kurir & Service]
    SelectCourier --> CalculateTotal[Hitung Total + Ongkir]
    CalculateTotal --> SelectPayment{Pilih Metode Bayar}
    
    SelectPayment -->|Transfer Bank| ShowBankInfo[Tampilkan Info Rekening]
    SelectPayment -->|COD| ShowCODInfo[Tampilkan Info Bayar di Tempat]
    
    ShowBankInfo --> EnableSubmit[Aktifkan Tombol Checkout]
    ShowCODInfo --> EnableSubmit
    
    EnableSubmit --> ClickCheckout[Klik Checkout]
    ClickCheckout --> ValidateForm{Form Valid?}
    ValidateForm -->|Tidak| ShowValidationError[Tampilkan Error Validasi]
    ShowValidationError --> DisplayForm
    
    ValidateForm -->|Ya| SubmitToServer[Kirim ke Server]
    SubmitToServer --> BeginTransaction[Mulai Database Transaction]
    BeginTransaction --> CheckStock{Cek Stok Produk}
    
    CheckStock -->|Tidak Cukup| RollbackTransaction[Rollback Transaction]
    RollbackTransaction --> ShowStockError[Error: Stok Tidak Cukup]
    ShowStockError --> DisplayForm
    
    CheckStock -->|Cukup| DecrementStock[Kurangi Stok Produk]
    DecrementStock --> CreateOrder[(Simpan Order ke Database)]
    CreateOrder --> CommitTransaction[Commit Transaction]
    CommitTransaction --> ClearCart[Hapus Session Keranjang]
    ClearCart --> SendWhatsApp[Kirim Notifikasi WhatsApp]
    SendWhatsApp --> RedirectTracking[Redirect ke Halaman Tracking]
    RedirectTracking --> End([Selesai])
```

---

### 6. Pelacakan Pesanan

**URL:** `/track` dan `/order/track/{token}`

```mermaid
flowchart TD
    Start([👤 Buka Halaman Lacak]) --> DisplaySearchForm[Tampilkan Form Pencarian]
    DisplaySearchForm --> InputSearch[Input No. Pesanan / No. WA]
    InputSearch --> ClickSearch[Klik Cari]
    ClickSearch --> SearchDatabase[(Cari di Database)]
    
    SearchDatabase --> OrderFound{Pesanan Ditemukan?}
    OrderFound -->|Tidak| ShowNotFound[Tampilkan: Pesanan Tidak Ditemukan]
    ShowNotFound --> DisplaySearchForm
    
    OrderFound -->|Ya| RedirectToDetail[Redirect ke Detail Tracking]
    RedirectToDetail --> LoadOrderDetail[Muat Detail Pesanan]
    LoadOrderDetail --> DisplayOrderHeader[Tampilkan Header Pesanan]
    DisplayOrderHeader --> DisplayTimeline[Tampilkan Timeline Status]
    DisplayTimeline --> DisplayPaymentInfo{Metode Pembayaran?}
    
    DisplayPaymentInfo -->|Transfer| ShowBankDetails[Tampilkan Detail Rekening]
    DisplayPaymentInfo -->|COD| ShowCODDetails[Tampilkan Info Bayar di Tempat]
    
    ShowBankDetails --> DisplayOrderItems
    ShowCODDetails --> DisplayOrderItems[Tampilkan Daftar Item]
    DisplayOrderItems --> DisplayShippingInfo[Tampilkan Info Pengiriman]
    DisplayShippingInfo --> CheckStatus{Status Pesanan?}
    
    CheckStatus -->|Shipped| ShowConfirmButton[Tampilkan Tombol Konfirmasi]
    CheckStatus -->|Lainnya| HideConfirmButton[Sembunyikan Tombol]
    
    ShowConfirmButton --> UserAction{Aksi Pengguna?}
    HideConfirmButton --> UserAction
    
    UserAction -->|Klik Konfirmasi| ConfirmReceive[Konfirmasi Pesanan Diterima]
    ConfirmReceive --> UpdateStatus[(Update Status: Completed)]
    UpdateStatus --> ShowSuccess[Tampilkan Pesan Sukses]
    ShowSuccess --> RefreshPage[Refresh Halaman]
    RefreshPage --> LoadOrderDetail
    
    UserAction -->|Kembali| GoHome[Kembali ke Beranda]
    GoHome --> End([Selesai])
```

---

### 7. Daftar Artikel

**URL:** `/articles`

```mermaid
flowchart TD
    Start([👤 Buka Halaman Artikel]) --> LoadPage[Muat Halaman Artikel]
    LoadPage --> FetchArticles[(Ambil Artikel Published)]
    FetchArticles --> HasArticles{Ada Artikel?}
    
    HasArticles -->|Tidak| ShowEmpty[Tampilkan: Belum Ada Artikel]
    ShowEmpty --> End([Selesai])
    
    HasArticles -->|Ya| DisplayGrid[Tampilkan Grid Artikel]
    DisplayGrid --> UserAction{Aksi Pengguna?}
    
    UserAction -->|Klik Artikel| GoToDetail[Navigasi ke Detail Artikel]
    GoToDetail --> End
    
    UserAction -->|Klik Pagination| ChangePage[Pindah Halaman]
    ChangePage --> FetchArticles
    
    UserAction -->|Scroll| DisplayGrid
```

---

### 8. Detail Artikel

**URL:** `/articles/{slug}`

```mermaid
flowchart TD
    Start([👤 Buka Detail Artikel]) --> FetchArticle[(Ambil Artikel dari Database)]
    FetchArticle --> ArticleFound{Artikel Ditemukan?}
    
    ArticleFound -->|Tidak| Show404[Tampilkan Halaman 404]
    Show404 --> End([Selesai])
    
    ArticleFound -->|Ya| IsPublished{Status Published?}
    IsPublished -->|Tidak| Show404
    
    IsPublished -->|Ya| DisplayHeader[Tampilkan Judul & Tanggal]
    DisplayHeader --> DisplayImage[Tampilkan Featured Image]
    DisplayImage --> DisplayContent[Tampilkan Konten Artikel]
    DisplayContent --> DisplayShareButtons[Tampilkan Tombol Share]
    DisplayShareButtons --> FetchRelated[(Ambil Artikel Terkait)]
    FetchRelated --> DisplayRelated[Tampilkan Artikel Terkait]
    DisplayRelated --> UserAction{Aksi Pengguna?}
    
    UserAction -->|Klik Share| OpenShareDialog[Buka Dialog Share]
    OpenShareDialog --> UserAction
    
    UserAction -->|Klik Artikel Lain| GoToOther[Navigasi ke Artikel Lain]
    GoToOther --> End
    
    UserAction -->|Klik Kembali| GoToList[Kembali ke Daftar Artikel]
    GoToList --> End
```

---

### 9. Login

**URL:** `/login`

```mermaid
flowchart TD
    Start([👤 Buka Halaman Login]) --> CheckSession{Sudah Login?}
    CheckSession -->|Ya| RedirectDashboard[Redirect ke Dashboard]
    RedirectDashboard --> End([Selesai])
    
    CheckSession -->|Tidak| DisplayForm[Tampilkan Form Login]
    DisplayForm --> InputEmail[Input Email]
    InputEmail --> InputPassword[Input Password]
    InputPassword --> CheckRemember{Centang Remember Me?}
    CheckRemember -->|Ya| SetRemember[Set Remember Flag]
    CheckRemember -->|Tidak| SkipRemember[Skip Remember]
    SetRemember --> ClickLogin
    SkipRemember --> ClickLogin[Klik Login]
    
    ClickLogin --> ValidateForm{Form Valid?}
    ValidateForm -->|Tidak| ShowValidationError[Tampilkan Error]
    ShowValidationError --> DisplayForm
    
    ValidateForm -->|Ya| SendCredentials[Kirim ke Server]
    SendCredentials --> VerifyCredentials[(Verifikasi di Database)]
    VerifyCredentials --> CredentialsValid{Kredensial Valid?}
    
    CredentialsValid -->|Tidak| ShowLoginError[Error: Email/Password Salah]
    ShowLoginError --> IncrementAttempt[Tambah Hitungan Gagal]
    IncrementAttempt --> CheckThrottle{Melebihi Batas?}
    CheckThrottle -->|Ya| ShowThrottleError[Error: Terlalu Banyak Percobaan]
    ShowThrottleError --> DisplayForm
    CheckThrottle -->|Tidak| DisplayForm
    
    CredentialsValid -->|Ya| CreateSession[Buat Session]
    CreateSession --> RegenerateToken[Regenerate CSRF Token]
    RegenerateToken --> RedirectIntended[Redirect ke Halaman Tujuan]
    RedirectIntended --> End
```

---

### 10. Register

**URL:** `/register`

```mermaid
flowchart TD
    Start([👤 Buka Halaman Register]) --> CheckSession{Sudah Login?}
    CheckSession -->|Ya| RedirectDashboard[Redirect ke Dashboard]
    RedirectDashboard --> End([Selesai])
    
    CheckSession -->|Tidak| DisplayForm[Tampilkan Form Register]
    DisplayForm --> InputName[Input Nama Lengkap]
    InputName --> InputEmail[Input Email]
    InputEmail --> InputPassword[Input Password]
    InputPassword --> InputConfirm[Input Konfirmasi Password]
    InputConfirm --> ClickRegister[Klik Register]
    
    ClickRegister --> ValidateName{Nama Valid?}
    ValidateName -->|Tidak| ShowNameError[Error: Nama tidak valid]
    ShowNameError --> DisplayForm
    
    ValidateName -->|Ya| ValidateEmail{Email Valid & Unik?}
    ValidateEmail -->|Tidak| ShowEmailError[Error: Email sudah terdaftar]
    ShowEmailError --> DisplayForm
    
    ValidateEmail -->|Ya| ValidatePassword{Password >= 8 Karakter?}
    ValidatePassword -->|Tidak| ShowPasswordError[Error: Password terlalu pendek]
    ShowPasswordError --> DisplayForm
    
    ValidatePassword -->|Ya| ValidateConfirm{Password Cocok?}
    ValidateConfirm -->|Tidak| ShowConfirmError[Error: Password tidak cocok]
    ShowConfirmError --> DisplayForm
    
    ValidateConfirm -->|Ya| HashPassword[Hash Password dengan Bcrypt]
    HashPassword --> CreateUser[(Simpan User ke Database)]
    CreateUser --> SendVerificationEmail[Kirim Email Verifikasi]
    SendVerificationEmail --> AutoLogin[Login Otomatis]
    AutoLogin --> RedirectDashboard2[Redirect ke Dashboard]
    RedirectDashboard2 --> End
```

---

## Halaman Admin

---

### 11. Dashboard Admin

**URL:** `/admin`

```mermaid
flowchart TD
    Start([👨‍💻 Akses Dashboard Admin]) --> CheckAuth{Sudah Login sebagai Admin?}
    CheckAuth -->|Tidak| RedirectLogin[Redirect ke Login]
    RedirectLogin --> End([Selesai])
    
    CheckAuth -->|Ya| LoadDashboard[Muat Dashboard]
    LoadDashboard --> FetchStats[(Ambil Data Statistik)]
    FetchStats --> DisplayStatsWidget[Tampilkan Widget Statistik]
    DisplayStatsWidget --> FetchChartData[(Ambil Data Grafik)]
    FetchChartData --> DisplayRevenueChart[Tampilkan Grafik Pendapatan]
    DisplayRevenueChart --> DisplayOrderChart[Tampilkan Grafik Status Pesanan]
    DisplayOrderChart --> FetchRecentOrders[(Ambil Pesanan Terbaru)]
    FetchRecentOrders --> DisplayRecentTable[Tampilkan Tabel Pesanan Terbaru]
    DisplayRecentTable --> CheckAlerts{Ada Alert?}
    
    CheckAlerts -->|Ya, Stok Rendah| ShowLowStockAlert[Tampilkan Alert Stok Rendah]
    CheckAlerts -->|Ya, Pending Orders| ShowPendingAlert[Tampilkan Alert Pesanan Pending]
    CheckAlerts -->|Tidak| UserAction
    
    ShowLowStockAlert --> UserAction{Aksi Admin?}
    ShowPendingAlert --> UserAction
    
    UserAction -->|Klik Statistik| DrillDown[Buka Detail Statistik]
    DrillDown --> End
    
    UserAction -->|Klik Pesanan| GoToOrder[Navigasi ke Detail Pesanan]
    GoToOrder --> End
    
    UserAction -->|Klik Menu Lain| NavigateMenu[Navigasi ke Menu Lain]
    NavigateMenu --> End
```

---

### 12. Manajemen Produk

**URL:** `/admin/products`

```mermaid
flowchart TD
    Start([👨‍💻 Buka Manajemen Produk]) --> LoadPage[Muat Halaman Products]
    LoadPage --> FetchProducts[(Ambil Semua Produk)]
    FetchProducts --> DisplayTable[Tampilkan Tabel Produk]
    DisplayTable --> UserAction{Aksi Admin?}
    
    UserAction -->|Klik Tambah| OpenCreateForm[Buka Form Tambah]
    OpenCreateForm --> FillProductForm[Isi Data Produk]
    FillProductForm --> UploadImage[Upload Gambar]
    UploadImage --> SelectCategory[Pilih Kategori]
    SelectCategory --> SetPricing[Set Harga & Diskon]
    SetPricing --> SetStock[Set Stok & Berat]
    SetStock --> FillSEO[Isi Meta SEO]
    FillSEO --> ClickSave[Klik Simpan]
    ClickSave --> ValidateProduct{Data Valid?}
    ValidateProduct -->|Tidak| ShowError[Tampilkan Error]
    ShowError --> FillProductForm
    ValidateProduct -->|Ya| SaveProduct[(Simpan ke Database)]
    SaveProduct --> ShowSuccess[Tampilkan Notifikasi Sukses]
    ShowSuccess --> DisplayTable
    
    UserAction -->|Klik Edit| LoadProduct[(Ambil Data Produk)]
    LoadProduct --> OpenEditForm[Buka Form Edit]
    OpenEditForm --> FillProductForm
    
    UserAction -->|Klik Hapus| ConfirmDelete{Konfirmasi Hapus?}
    ConfirmDelete -->|Tidak| DisplayTable
    ConfirmDelete -->|Ya| DeleteProduct[(Hapus dari Database)]
    DeleteProduct --> ShowDeleteSuccess[Tampilkan Notifikasi Sukses]
    ShowDeleteSuccess --> DisplayTable
    
    UserAction -->|Filter/Search| ApplyFilter[Terapkan Filter]
    ApplyFilter --> FetchProducts
    
    UserAction -->|Bulk Action| SelectMultiple[Pilih Beberapa Produk]
    SelectMultiple --> BulkDelete[Hapus Massal]
    BulkDelete --> DisplayTable
```

---

### 13. Manajemen Kategori

**URL:** `/admin/categories`

```mermaid
flowchart TD
    Start([👨‍💻 Buka Manajemen Kategori]) --> LoadPage[Muat Halaman Categories]
    LoadPage --> FetchCategories[(Ambil Semua Kategori)]
    FetchCategories --> DisplayTable[Tampilkan Tabel Kategori]
    DisplayTable --> UserAction{Aksi Admin?}
    
    UserAction -->|Klik Tambah| OpenForm[Buka Form Kategori]
    OpenForm --> InputName[Input Nama Kategori]
    InputName --> AutoGenerateSlug[Generate Slug Otomatis]
    AutoGenerateSlug --> InputDescription[Input Deskripsi]
    InputDescription --> ClickSave[Klik Simpan]
    ClickSave --> ValidateCategory{Data Valid?}
    ValidateCategory -->|Tidak| ShowError[Tampilkan Error]
    ShowError --> InputName
    ValidateCategory -->|Ya| SaveCategory[(Simpan ke Database)]
    SaveCategory --> ShowSuccess[Notifikasi Sukses]
    ShowSuccess --> DisplayTable
    
    UserAction -->|Klik Edit| LoadCategory[(Ambil Data Kategori)]
    LoadCategory --> OpenForm
    
    UserAction -->|Klik Hapus| CheckProducts{Ada Produk Terkait?}
    CheckProducts -->|Ya| ShowCannotDelete[Error: Tidak Bisa Hapus]
    ShowCannotDelete --> DisplayTable
    CheckProducts -->|Tidak| ConfirmDelete{Konfirmasi?}
    ConfirmDelete -->|Tidak| DisplayTable
    ConfirmDelete -->|Ya| DeleteCategory[(Hapus dari Database)]
    DeleteCategory --> DisplayTable
```

---

### 14. Manajemen Pesanan

**URL:** `/admin/orders`

```mermaid
flowchart TD
    Start([👨‍💻 Buka Manajemen Pesanan]) --> LoadPage[Muat Halaman Orders]
    LoadPage --> FetchOrders[(Ambil Semua Pesanan)]
    FetchOrders --> DisplayTable[Tampilkan Tabel Pesanan]
    DisplayTable --> UserAction{Aksi Admin?}
    
    UserAction -->|Klik View| LoadOrder[(Ambil Detail Pesanan)]
    LoadOrder --> DisplayOrderDetail[Tampilkan Detail Pesanan]
    DisplayOrderDetail --> DisplayItems[Tampilkan Item Pesanan]
    DisplayItems --> DisplayShipping[Tampilkan Info Pengiriman]
    DisplayShipping --> DisplayTimeline[Tampilkan Timeline Status]
    DisplayTimeline --> AdminAction{Aksi pada Pesanan?}
    
    AdminAction -->|Update Status| SelectNewStatus[Pilih Status Baru]
    SelectNewStatus --> SaveStatus[(Update Status di Database)]
    SaveStatus --> TriggerNotification{Kirim Notifikasi?}
    TriggerNotification -->|Ya| SendWhatsApp[Kirim Notifikasi WhatsApp]
    TriggerNotification -->|Tidak| RefreshPage
    SendWhatsApp --> RefreshPage[Refresh Halaman]
    RefreshPage --> DisplayTable
    
    AdminAction -->|Input Resi| OpenResiForm[Buka Form Input Resi]
    OpenResiForm --> InputResi[Input Nomor Resi]
    InputResi --> SaveResi[(Simpan Resi)]
    SaveResi --> SetShipped[Set Status: Shipped]
    SetShipped --> SendResiNotif[Kirim Notifikasi + Resi ke WA]
    SendResiNotif --> DisplayTable
    
    AdminAction -->|Cancel Order| ConfirmCancel{Konfirmasi Cancel?}
    ConfirmCancel -->|Tidak| DisplayOrderDetail
    ConfirmCancel -->|Ya| RestoreStock[Kembalikan Stok Produk]
    RestoreStock --> SetCancelled[(Set Status: Cancelled)]
    SetCancelled --> DisplayTable
    
    UserAction -->|Filter Status| SelectStatus[Pilih Filter Status]
    SelectStatus --> FetchOrders
```

---

### 15. Manajemen Artikel

**URL:** `/admin/articles`

```mermaid
flowchart TD
    Start([👨‍💻 Buka Manajemen Artikel]) --> LoadPage[Muat Halaman Articles]
    LoadPage --> FetchArticles[(Ambil Semua Artikel)]
    FetchArticles --> DisplayTable[Tampilkan Tabel Artikel]
    DisplayTable --> UserAction{Aksi Admin?}
    
    UserAction -->|Klik Tambah| OpenEditor[Buka Editor Artikel]
    OpenEditor --> InputTitle[Input Judul]
    InputTitle --> AutoSlug[Generate Slug Otomatis]
    AutoSlug --> UploadFeaturedImage[Upload Featured Image]
    UploadFeaturedImage --> WriteContent[Tulis Konten dengan Rich Editor]
    WriteContent --> SetPublished{Langsung Publish?}
    SetPublished -->|Ya| SetPublishTrue[Set is_published = true]
    SetPublished -->|Tidak| SetPublishFalse[Set is_published = false]
    SetPublishTrue --> ClickSave
    SetPublishFalse --> ClickSave[Klik Simpan]
    ClickSave --> ValidateArticle{Data Valid?}
    ValidateArticle -->|Tidak| ShowError[Tampilkan Error]
    ShowError --> OpenEditor
    ValidateArticle -->|Ya| SaveArticle[(Simpan ke Database)]
    SaveArticle --> ShowSuccess[Notifikasi Sukses]
    ShowSuccess --> DisplayTable
    
    UserAction -->|Klik Edit| LoadArticle[(Ambil Data Artikel)]
    LoadArticle --> OpenEditor
    
    UserAction -->|Toggle Publish| ToggleStatus[Toggle Status Publish]
    ToggleStatus --> UpdatePublish[(Update is_published)]
    UpdatePublish --> DisplayTable
    
    UserAction -->|Klik Hapus| ConfirmDelete{Konfirmasi?}
    ConfirmDelete -->|Tidak| DisplayTable
    ConfirmDelete -->|Ya| DeleteArticle[(Hapus dari Database)]
    DeleteArticle --> DisplayTable
    
    UserAction -->|Preview| OpenPreview[Buka Preview Halaman]
    OpenPreview --> DisplayTable
```

---

### 16. Moderasi Review

**URL:** `/admin/reviews`

```mermaid
flowchart TD
    Start([👨‍💻 Buka Moderasi Review]) --> LoadPage[Muat Halaman Reviews]
    LoadPage --> FetchReviews[(Ambil Semua Review)]
    FetchReviews --> DisplayTable[Tampilkan Tabel Review]
    DisplayTable --> UserAction{Aksi Admin?}
    
    UserAction -->|Filter Pending| FilterPending[Filter: is_approved = false]
    FilterPending --> FetchReviews
    
    UserAction -->|Lihat Detail| ShowReviewDetail[Tampilkan Detail Review]
    ShowReviewDetail --> DisplayRating[Tampilkan Rating]
    DisplayRating --> DisplayComment[Tampilkan Komentar]
    DisplayComment --> DisplayProduct[Tampilkan Produk Terkait]
    DisplayProduct --> ModerateAction{Aksi Moderasi?}
    
    ModerateAction -->|Approve| SetApproved[(Set is_approved = true)]
    SetApproved --> ShowSuccess[Notifikasi: Review Disetujui]
    ShowSuccess --> DisplayTable
    
    ModerateAction -->|Reject| SetRejected[(Set is_approved = false)]
    SetRejected --> ShowRejectNotif[Notifikasi: Review Ditolak]
    ShowRejectNotif --> DisplayTable
    
    ModerateAction -->|Hapus| ConfirmDelete{Konfirmasi Hapus?}
    ConfirmDelete -->|Tidak| DisplayTable
    ConfirmDelete -->|Ya| DeleteReview[(Hapus dari Database)]
    DeleteReview --> DisplayTable
    
    UserAction -->|Bulk Approve| SelectMultiple[Pilih Beberapa Review]
    SelectMultiple --> BulkApprove[(Approve Semua yang Dipilih)]
    BulkApprove --> DisplayTable
```

---

### 17. Pengaturan Sistem

**URL:** `/admin/settings`

```mermaid
flowchart TD
    Start([👨‍💻 Buka Pengaturan]) --> LoadPage[Muat Halaman Settings]
    LoadPage --> FetchSettings[(Ambil Semua Setting)]
    FetchSettings --> DisplayForm[Tampilkan Form Settings]
    DisplayForm --> GroupedSettings[Kelompokkan berdasarkan Kategori]
    GroupedSettings --> DisplayStoreInfo[Tampilkan: Info Toko]
    DisplayStoreInfo --> DisplayWhatsApp[Tampilkan: Pengaturan WhatsApp]
    DisplayWhatsApp --> DisplayPayment[Tampilkan: Info Pembayaran]
    DisplayPayment --> DisplayShippingConfig[Tampilkan: Konfigurasi Pengiriman]
    DisplayShippingConfig --> UserAction{Aksi Admin?}
    
    UserAction -->|Edit Nilai| InputNewValue[Input Nilai Baru]
    InputNewValue --> ValidateValue{Nilai Valid?}
    ValidateValue -->|Tidak| ShowError[Tampilkan Error]
    ShowError --> DisplayForm
    ValidateValue -->|Ya| SaveSetting[(Update di Database)]
    SaveSetting --> ClearCache[Clear Config Cache]
    ClearCache --> ShowSuccess[Notifikasi: Setting Tersimpan]
    ShowSuccess --> DisplayForm
    
    UserAction -->|Tambah Setting| OpenAddForm[Buka Form Tambah]
    OpenAddForm --> InputKey[Input Key]
    InputKey --> InputValue[Input Value]
    InputValue --> SaveNewSetting[(Simpan Setting Baru)]
    SaveNewSetting --> DisplayForm
```

---

## 📝 Catatan

### Legenda Simbol

| Simbol | Makna |
|:-------|:------|
| `([...])` | Start/End node |
| `[...]` | Process/Action |
| `{...}` | Decision/Condition |
| `[(...)` | Database operation |
| `-->` | Alur normal |
| `-->|label|` | Alur dengan kondisi |

### Teknologi yang Digunakan

- **Diagram Tool**: Mermaid.js
- **Flowchart Type**: flowchart TD (Top-Down)
- **Compatible with**: GitHub, GitLab, Notion, Obsidian

---

*Dokumentasi ini dibuat untuk keperluan Tugas Akhir/Skripsi*  
**Universitas Ichsan Sidenreng Rappang** © 2026
