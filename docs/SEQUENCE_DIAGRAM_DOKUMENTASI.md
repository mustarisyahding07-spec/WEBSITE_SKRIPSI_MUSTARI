# Dokumentasi Sequence Diagram — WEBSITE IVO KARYA

> **Proyek**: Website Toko Online Ivo Karya  
> **Framework**: Laravel 10 + Filament v3 + Livewire 3  
> **Tanggal Dibuat**: 2 April 2026

---

## Daftar Halaman

| No | Halaman | Route |
|----|---------|-------|
| 1 | [Beranda](#1-beranda-homepage) | `GET /` |
| 2 | [Katalog Produk](#2-katalog-produk) | `GET /katalog` |
| 3 | [Detail Produk](#3-detail-produk) | `GET /product/{slug}` |
| 4 | [Keranjang Belanja](#4-keranjang-belanja) | `GET /cart` |
| 5 | [Checkout & Pembayaran](#5-checkout--pembayaran) | `POST /checkout` |
| 6 | [Lacak Pesanan](#6-lacak-pesanan-token) | `GET /order/track/{token}` |
| 7 | [Cari Pesanan](#7-cari-pesanan) | `GET /track` |
| 8 | [Daftar Artikel](#8-daftar-artikel) | `GET /articles` |
| 9 | [Detail Artikel](#9-detail-artikel) | `GET /articles/{slug}` |
| 10 | [Login](#10-login) | `GET /login` |
| 11 | [Register](#11-register) | `GET /register` |
| 12 | [Lupa / Reset Password](#12-lupa--reset-password) | `GET /forgot-password` |
| 13 | [Profil Pengguna](#13-profil-pengguna) | `GET /profile` |
| 14 | [Admin — Manajemen Produk](#14-admin--manajemen-produk) | `GET /admin/products` |
| 15 | [Admin — Manajemen Pesanan](#15-admin--manajemen-pesanan) | `GET /admin/orders` |
| 16 | [Admin — Manajemen Ulasan](#16-admin--manajemen-ulasan) | `GET /admin/reviews` |
| 17 | [API Shipping](#17-api-shipping) | `/api/shipping/*` |

---

## 1. Beranda (Homepage)

### Penjelasan

Halaman beranda merupakan antarmuka pertama yang ditampilkan kepada pengguna saat mengakses sistem website toko online Ivo Karya. Berdasarkan alur yang dirancang, ketika pengguna mengirimkan permintaan HTTP GET ke rute `/`, sistem akan meneruskan permintaan tersebut kepada `HomeController` melalui mekanisme routing Laravel. Controller kemudian melakukan kueri terhadap tabel produk menggunakan `Product Model`. Apabila pengguna menyertakan parameter `category` pada URL, sistem akan menerapkan kondisi filter menggunakan metode `whereHas` untuk menyaring produk berdasarkan slug kategori yang dimaksud.

Selain data produk, controller juga mengambil tiga artikel terbaru dari tabel artikel melalui `Article Model` dengan memanfaatkan metode `latest()->take(3)->get()`. Seluruh data yang diperoleh kemudian dikirimkan ke berkas tampilan `welcome.blade.php` sebagai variabel yang dapat diakses oleh templat. Hasil akhirnya adalah halaman beranda yang menampilkan daftar produk beserta cuplikan artikel terbaru secara dinamis kepada pengguna.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor User as User/Browser
    participant Router as Laravel Router
    participant HC as HomeController
    participant ProductM as Product Model
    participant ArticleM as Article Model
    participant DB as Database
    participant View as welcome.blade.php

    User->>Router: GET / (opsional: ?category=slug)
    Router->>HC: index(Request $request)
    HC->>ProductM: query()
    alt Ada filter kategori
        HC->>ProductM: whereHas('category', slug)
    end
    ProductM->>DB: SELECT products WHERE category.slug = ?
    DB-->>ProductM: Daftar produk
    ProductM-->>HC: $products collection
    HC->>ArticleM: latest()->take(3)->get()
    ArticleM->>DB: SELECT articles LIMIT 3 ORDER BY created_at DESC
    DB-->>ArticleM: 3 artikel terbaru
    ArticleM-->>HC: $articles collection
    HC->>View: view('welcome', compact('products','articles'))
    View-->>User: Render halaman beranda (HTML)
```

---

## 2. Katalog Produk

### Penjelasan

Halaman katalog produk berfungsi sebagai pusat penjelajahan seluruh produk yang tersedia di toko. Berbeda dengan halaman beranda, halaman ini dirancang secara khusus untuk menampilkan keseluruhan data produk disertai informasi kategorinya melalui mekanisme eager loading (`with('category')`). Ketika pengguna mengakses rute `/katalog`, permintaan diteruskan ke metode `catalog()` pada `HomeController`.

Controller selanjutnya membangun kueri produk dengan memuat relasi kategori secara bersamaan guna menghindari masalah N+1 query. Apabila pengguna menyertakan parameter `category` pada URL, sistem menerapkan filter berbasis slug kategori. Di samping data produk, sistem juga mengambil seluruh data kategori yang tersedia melalui `Category Model` untuk keperluan antarmuka filter yang ditampilkan kepada pengguna. Kedua kumpulan data tersebut kemudian diteruskan ke berkas tampilan `front/catalog.blade.php`, yang merender tampilan grid produk secara dinamis lengkap dengan navigasi filter kategori.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor User as User/Browser
    participant Router as Laravel Router
    participant HC as HomeController
    participant ProductM as Product Model
    participant CategoryM as Category Model
    participant DB as Database
    participant View as front/catalog.blade.php

    User->>Router: GET /katalog (opsional: ?category=slug)
    Router->>HC: catalog(Request $request)
    HC->>ProductM: with('category')
    alt Ada parameter category
        HC->>ProductM: whereHas('category', slug)
    end
    ProductM->>DB: SELECT products WITH category WHERE ...
    DB-->>ProductM: Produk beserta data kategori
    ProductM-->>HC: $products
    HC->>CategoryM: all()
    CategoryM->>DB: SELECT * FROM categories
    DB-->>CategoryM: Seluruh kategori
    CategoryM-->>HC: $categories
    HC->>View: view('front.catalog', compact('products','categories'))
    View-->>User: Grid produk + filter kategori
```

---

## 3. Detail Produk

### Penjelasan

Halaman detail produk menampilkan informasi lengkap mengenai suatu produk, meliputi nama, deskripsi, harga, ketersediaan stok, dan galeri gambar. Proses diawali saat pengguna mengakses rute `/product/{slug}`, di mana Laravel secara otomatis melakukan resolusi model melalui mekanisme Route Model Binding berdasarkan atribut `slug` pada tabel produk. Hasil resolusi diteruskan langsung ke metode `show()` pada `HomeController` tanpa memerlukan kueri tambahan secara eksplisit.

Keistimewaan halaman ini terletak pada integrasi komponen **Livewire** bernama `ProductReviews` yang memungkinkan pengelolaan ulasan produk secara reaktif tanpa pemuatan ulang halaman (_page reload_). Komponen tersebut melakukan pengambilan data ulasan yang telah disetujui (`is_approved = true`) saat pertama kali dimuat. Ketika pengguna mengirimkan formulir ulasan, sistem melakukan validasi terhadap atribut rating, komentar, serta nama pengguna (khusus bagi pengguna yang tidak terautentikasi). Apabila terdapat berkas gambar yang diunggah, sistem menyimpannya ke direktori penyimpanan publik. Setelah data ulasan berhasil disimpan ke basis data, komponen secara otomatis memperbarui tampilan daftar ulasan tanpa memuat ulang halaman secara keseluruhan.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor User as User/Browser
    participant Router as Laravel Router
    participant HC as HomeController
    participant DB as Database
    participant View as front/products/show.blade.php
    participant LW as Livewire ProductReviews

    User->>Router: GET /product/{slug}
    Router->>HC: show(Product $product)
    Note over HC: Route Model Binding — resolve by slug
    HC->>DB: SELECT products WHERE slug = ?
    DB-->>HC: Data produk
    HC->>View: view('front.products.show', compact('product'))
    View-->>User: Halaman produk + embed Livewire component

    User->>LW: mount(Product $product)
    LW->>DB: SELECT reviews WHERE product_id=? AND is_approved=1
    DB-->>LW: Daftar ulasan
    LW-->>View: Render ulasan + form

    User->>LW: Submit form ulasan
    LW->>LW: validate() rating, comment, name
    alt Ada upload foto
        LW->>LW: image->store('reviews','public')
    end
    LW->>DB: INSERT INTO reviews
    DB-->>LW: Review tersimpan
    LW->>DB: SELECT reviews (refresh)
    DB-->>LW: Ulasan terbaru
    LW-->>User: Re-render daftar ulasan (real-time)
```

---

## 4. Keranjang Belanja

### Penjelasan

Halaman keranjang belanja dirancang sebagai antarmuka pengelolaan produk yang akan dibeli oleh pengguna sebelum melanjutkan ke proses checkout. Sistem ini mengimplementasikan penyimpanan data keranjang berbasis **sesi PHP** (_session_), sehingga pengguna tidak diwajibkan untuk memiliki akun atau melakukan autentikasi terlebih dahulu. Pendekatan ini bertujuan untuk meminimalkan hambatan dalam pengalaman berbelanja.

Ketika halaman diakses, `CartController` mengambil data keranjang dari sesi dan melakukan proses pembersihan otomatis terhadap item-item yang tidak valid, yakni produk dengan harga nol atau tanpa nama (_ghost items_). Setelah proses validasi, sistem menghitung total harga keseluruhan item dalam keranjang. Pengguna juga dapat menambahkan produk ke keranjang dari halaman lain melalui rute `/cart/add/{product}`, yang mendukung tiga mode respons: respons JSON untuk permintaan berbasis AJAX, pengalihan ke halaman keranjang untuk mode _"Beli Sekarang"_, serta pengalihan kembali ke halaman sebelumnya untuk mode standar. Selain itu, tersedia fitur pembaruan kuantitas dan penghapusan item yang masing-masing menggunakan metode HTTP PATCH dan DELETE.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor User as User/Browser
    participant Router as Laravel Router
    participant CC as CartController
    participant Session as Laravel Session
    participant View as front/cart.blade.php

    User->>Router: GET /cart
    Router->>CC: index()
    CC->>Session: get('cart', [])
    Session-->>CC: Data keranjang (array)
    CC->>CC: Filter item tidak valid
    alt Ada item tidak valid
        CC->>Session: put('cart', $cleanCart)
    end
    CC->>CC: Hitung total harga
    CC->>View: view('front.cart', compact('cart','total'))
    View-->>User: Tampilan keranjang + total harga

    User->>Router: GET /cart/add/{product}
    Router->>CC: add(Request, Product $product)
    CC->>Session: get('cart', [])
    alt Produk sudah ada
        CC->>Session: Tambah quantity
    else Produk baru
        CC->>Session: Tambah item baru
    end
    CC->>Session: put('cart', $cart)
    alt Request AJAX
        CC-->>User: JSON {message, cart_count}
    else Buy Now
        CC-->>User: Redirect ke /cart
    else Normal
        CC-->>User: Redirect back + flash success
    end

    User->>Router: PATCH /cart/update/{id}
    Router->>CC: update(Request)
    CC->>Session: Update quantity item

    User->>Router: DELETE /cart/remove/{id}
    Router->>CC: remove(Request)
    CC->>Session: unset item
    CC-->>User: Redirect back + flash success
```

---

## 5. Checkout & Pembayaran

### Penjelasan

Proses checkout merupakan alur paling krusial dalam sistem toko online ini, karena melibatkan operasi basis data yang memerlukan konsistensi dan integritas data secara ketat. Pada tahap ini, pengguna diwajibkan mengisi formulir data pengiriman yang mencakup nama penerima, alamat, kode pos, identitas kota tujuan, pilihan kurir dan layanan pengiriman, serta metode pembayaran (transfer bank atau _cash on delivery_).

Setelah melewati validasi formulir, sistem tidak langsung membuat data pesanan, melainkan terlebih dahulu memulai transaksi basis data menggunakan `DB::beginTransaction()`. Di dalam transaksi tersebut, setiap produk dalam keranjang dikunci baris datanya menggunakan mekanisme `lockForUpdate()` untuk mencegah kondisi _race condition_ pada situasi pembelian bersamaan. Sistem kemudian memverifikasi ketersediaan stok secara satu per satu; apabila ditemukan ketidakcukupan stok atau produk yang tidak lagi tersedia, sistem segera melakukan rollback transaksi dan menampilkan pesan kesalahan kepada pengguna. Jika seluruh validasi stok berhasil, stok produk dikurangi secara atomik, data pesanan dibuat dengan token pelacak unik yang dihasilkan secara otomatis melalui fungsi `bin2hex(random_bytes(16))`, dan transaksi dikonfirmasi (_commit_). Setelah itu, data keranjang pada sesi dihapus dan pengguna dialihkan ke halaman pelacakan pesanan.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor User as User/Browser
    participant CC as CartController
    participant Session as Laravel Session
    participant DB as Database
    participant ProductM as Product Model
    participant OrderM as Order Model

    User->>CC: POST /checkout (form data)
    CC->>CC: validate() address, name, phone, courier, payment_method
    alt Validasi gagal
        CC-->>User: Redirect back + errors
    end
    CC->>Session: get('cart')
    alt Keranjang kosong
        CC-->>User: Redirect back: Keranjang kosong
    end
    CC->>DB: BEGIN TRANSACTION
    loop Setiap item di keranjang
        CC->>ProductM: lockForUpdate()->first()
        ProductM->>DB: SELECT product FOR UPDATE
        DB-->>ProductM: Data produk (terkunci)
        alt Produk tidak ada / stok kurang
            CC->>DB: ROLLBACK
            CC-->>User: Error pesan kesalahan
        end
        CC->>ProductM: decrement('stock', qty)
        ProductM->>DB: UPDATE products SET stock = stock - qty
    end
    CC->>OrderM: create([...data order, tracking_token])
    OrderM->>DB: INSERT INTO orders
    DB-->>OrderM: Order tersimpan
    CC->>DB: COMMIT
    CC->>Session: forget('cart')
    CC-->>User: Redirect ke /order/track/{tracking_token}
```

---

## 6. Lacak Pesanan (Token)

### Penjelasan

Halaman pelacakan pesanan berbasis token dirancang untuk memberikan akses kepada pelanggan dalam memantau status pesanan mereka tanpa memerlukan autentikasi akun. Sistem menggunakan token pelacak unik (`tracking_token`) sepanjang 32 karakter heksadesimal yang dihasilkan secara otomatis pada saat pembentukan pesanan. URL pelacakan kemudian disebarkan kepada pelanggan melalui pesan WhatsApp yang dikirim secara otomatis oleh sistem.

Ketika pengguna mengakses rute `/order/track/{token}`, `CartController` melakukan pencarian rekod pesanan berdasarkan nilai token tersebut menggunakan metode `firstOrFail()`. Apabila token tidak ditemukan dalam basis data, sistem secara otomatis menampilkan halaman kesalahan 404. Jika token valid, halaman akan menampilkan seluruh informasi pesanan secara komprehensif, termasuk status pesanan, daftar produk yang dipesan, informasi pengiriman, dan nomor resi apabila telah tersedia. Selain itu, terdapat aksi **konfirmasi penerimaan barang** yang dapat dilakukan oleh pelanggan ketika status pesanan berada pada tahap `shipped`. Konfirmasi tersebut akan mengubah status pesanan menjadi `completed` sebagai penanda bahwa transaksi telah selesai secara keseluruhan.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor User as User/Browser
    participant Router as Laravel Router
    participant CC as CartController
    participant OrderM as Order Model
    participant DB as Database
    participant View as front/track.blade.php

    User->>Router: GET /order/track/{token}
    Router->>CC: track($token)
    CC->>OrderM: where('tracking_token', token)->firstOrFail()
    OrderM->>DB: SELECT orders WHERE tracking_token = ?
    alt Token tidak ditemukan
        DB-->>OrderM: null → ModelNotFoundException
        CC-->>User: Halaman 404
    else Token valid
        DB-->>OrderM: Data order lengkap
        OrderM-->>CC: $order
        CC->>View: view('front.track', compact('order'))
        View-->>User: Status + detail + info pengiriman
    end
    alt Status == shipped
        User->>Router: POST /order/confirm/{token}
        Router->>CC: confirmReceive($token)
        CC->>OrderM: update(['status'=>'completed'])
        OrderM->>DB: UPDATE orders SET status=completed
        CC-->>User: Redirect back + flash sukses
    end
```

---

## 7. Cari Pesanan

### Penjelasan

Halaman pencarian pesanan menyediakan formulir sederhana yang memungkinkan pelanggan untuk menelusuri status pesanan mereka menggunakan nomor identitas pesanan (_Order ID_). Antarmuka ini berfungsi sebagai alternatif bagi pelanggan yang tidak memiliki tautan pelacakan berbasis token namun masih membutuhkan informasi status pesanan.

Ketika halaman pertama kali diakses tanpa parameter apapun, `TrackOrderController` merender formulir pencarian dengan variabel `$order` bernilai `null`. Setelah pengguna memasukkan nomor pesanan dan mengirimkan formulir melalui parameter URL `?order_id`, controller melakukan pencarian rekod pesanan menggunakan metode `find()` pada `Order Model`. Jika pesanan dengan identitas tersebut tidak ditemukan dalam basis data, sistem menampilkan pesan kesalahan kepada pengguna melalui mekanisme sesi _flash_. Sebaliknya, apabila pesanan berhasil ditemukan, tampilan akan diperbarui untuk menampilkan seluruh detail status pesanan secara langsung pada halaman yang sama.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor User as User/Browser
    participant Router as Laravel Router
    participant TOC as TrackOrderController
    participant OrderM as Order Model
    participant DB as Database
    participant View as front/track-order.blade.php

    User->>Router: GET /track
    Router->>TOC: index(Request)
    Note over TOC: Tidak ada order_id → $order = null
    TOC->>View: view('front.track-order', order=null)
    View-->>User: Form input Order ID

    User->>Router: GET /track?order_id=123
    Router->>TOC: index(Request dengan order_id)
    TOC->>OrderM: find($request->order_id)
    OrderM->>DB: SELECT orders WHERE id = ?
    alt Tidak ditemukan
        DB-->>OrderM: null
        TOC-->>User: Redirect back + error
    else Ditemukan
        DB-->>OrderM: Data order
        OrderM-->>TOC: $order
        TOC->>View: view dengan data order
        View-->>User: Detail status pesanan
    end
```

---

## 8. Daftar Artikel

### Penjelasan

Halaman daftar artikel merupakan bagian dari fitur konten pemasaran yang dirancang untuk mendukung strategi optimasi mesin pencari (_Search Engine Optimization_) website toko online Ivo Karya. Halaman ini menampilkan kumpulan artikel yang telah dipublikasikan oleh administrator dalam format grid yang terstruktur dengan navigasi pagination.

Ketika pengguna mengakses rute `/articles`, `ArticleController` melakukan pengambilan data artikel menggunakan metode `with('category')` untuk memuat relasi kategori secara efisien, dikombinasikan dengan pengurutan berdasarkan tanggal terbaru (`latest()`) dan pembatasan hasil menjadi sembilan artikel per halaman menggunakan metode `paginate(9)`. Laravel secara otomatis menangani logika pagination berdasarkan parameter `?page` yang terdapat pada URL. Hasil kueri berupa objek `LengthAwarePaginator` yang kemudian diteruskan ke berkas tampilan untuk dirender sebagai antarmuka yang dilengkapi dengan tautan navigasi antar halaman.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor User as User/Browser
    participant Router as Laravel Router
    participant AC as ArticleController
    participant ArticleM as Article Model
    participant DB as Database
    participant View as front/articles/index.blade.php

    User->>Router: GET /articles (opsional: ?page=2)
    Router->>AC: index()
    AC->>ArticleM: with('category')->latest()->paginate(9)
    ArticleM->>DB: SELECT articles WITH category ORDER BY created_at DESC LIMIT 9 OFFSET ?
    DB-->>ArticleM: Artikel + kategori + pagination meta
    ArticleM-->>AC: $articles (LengthAwarePaginator)
    AC->>View: view('front.articles.index', compact('articles'))
    View-->>User: Grid artikel + navigasi pagination
```

---

## 9. Detail Artikel

### Penjelasan

Halaman detail artikel menampilkan konten lengkap dari sebuah artikel yang dipilih oleh pengguna. Sistem mengimplementasikan mekanisme **Route Model Binding** berbasis atribut `slug` untuk menghasilkan URL yang ramah mesin pencari sekaligus meningkatkan kejelasan alamat halaman bagi pengguna. Dengan mekanisme ini, Laravel secara otomatis melakukan resolusi model `Article` berdasarkan nilai slug yang terdapat pada segmen URL tanpa memerlukan penulisan kueri secara eksplisit di dalam controller.

Ketika pengguna mengakses rute `/articles/{slug}`, router meneruskan permintaan ke metode `show()` pada `ArticleController` beserta objek artikel yang telah terselesaikan. Apabila slug yang dimaksud tidak ditemukan dalam basis data, Laravel secara otomatis merespons dengan kode status HTTP 404. Jika artikel berhasil ditemukan, controller meneruskan objek artikel tersebut ke berkas tampilan `front/articles/show.blade.php` untuk dirender sebagai halaman artikel yang memuat judul, kategori, waktu publikasi, dan konten lengkap artikel.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor User as User/Browser
    participant Router as Laravel Router
    participant AC as ArticleController
    participant DB as Database
    participant View as front/articles/show.blade.php

    User->>Router: GET /articles/{slug}
    Router->>AC: show(Article $article)
    Note over Router,AC: Route Model Binding — resolve by slug
    AC->>DB: SELECT articles WHERE slug = ?
    alt Tidak ditemukan
        DB-->>Router: null → 404
        Router-->>User: Halaman 404
    else Ditemukan
        DB-->>AC: Data artikel
        AC->>View: view('front.articles.show', compact('article'))
        View-->>User: Konten artikel lengkap
    end
```

---

## 10. Login

### Penjelasan

Halaman login merupakan gerbang autentikasi yang memungkinkan pengguna terdaftar untuk mengakses fitur-fitur yang memerlukan identitas terverifikasi. Sistem autentikasi pada aplikasi ini dibangun di atas fondasi Laravel Breeze yang menyediakan implementasi autentikasi berbasis sesi (_session-based authentication_) secara lengkap dan aman.

Halaman login hanya dapat diakses oleh pengguna yang belum terautentikasi berkat penerapan _middleware_ `guest`. Ketika pengguna mengirimkan formulir login, `AuthenticatedSessionController` melakukan validasi format masukan, kemudian mendelegasikan proses verifikasi kredensial kepada `Auth Facade` Laravel. Fasad tersebut memverifikasi kecocokan antara surel dan kata sandi yang dimasukkan dengan data yang tersimpan di basis data, di mana kata sandi dibandingkan menggunakan algoritma hashing `bcrypt`. Apabila kredensial tidak valid, sistem mengembalikan pesan kesalahan yang sesuai. Jika autentikasi berhasil, identitas sesi diregenerasi menggunakan metode `regenerate()` guna mencegah serangan _session fixation_. Pengguna selanjutnya dialihkan ke halaman dasbor yang secara otomatis meneruskan akses ke panel administrasi Filament.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor User as User/Browser
    participant Router as Laravel Router
    participant ASC as AuthenticatedSessionController
    participant AuthFacade as Auth Facade
    participant DB as Database
    participant Session as Laravel Session

    User->>Router: GET /login
    Note over Router: Middleware guest — redirect jika sudah login
    Router->>ASC: create()
    ASC-->>User: Form login

    User->>Router: POST /login (email, password, remember)
    Router->>ASC: store(LoginRequest)
    ASC->>ASC: validate email dan password
    ASC->>AuthFacade: attempt(email, password, remember)
    AuthFacade->>DB: SELECT user WHERE email=? AND verify password hash
    alt Kredensial salah
        DB-->>AuthFacade: Tidak ditemukan
        AuthFacade-->>ASC: false
        ASC-->>User: Redirect back + error
    else Kredensial benar
        DB-->>AuthFacade: Data user
        AuthFacade-->>ASC: true
        ASC->>Session: regenerate()
        ASC-->>User: Redirect ke /dashboard (→ /admin)
    end
```

---

## 11. Register

### Penjelasan

Halaman registrasi menyediakan mekanisme pendaftaran akun baru bagi pengguna yang ingin memanfaatkan fitur-fitur yang memerlukan identitas terverifikasi, seperti penulisan ulasan produk. Halaman ini hanya dapat diakses oleh pengguna yang belum terautentikasi, sebagaimana halaman login, melalui perlindungan _middleware_ `guest`.

Ketika pengguna mengirimkan formulir pendaftaran, `RegisteredUserController` melakukan serangkaian validasi terhadap masukan yang diterima, meliputi keunikan surel pada tabel pengguna, konfirmasi kesesuaian kata sandi, dan pemenuhan panjang minimum kata sandi sebesar delapan karakter. Apabila validasi berhasil, controller membuat rekod pengguna baru di basis data dengan kata sandi yang telah melalui proses hashing menggunakan fungsi `Hash::make()` dari Laravel. Setelah rekod berhasil disimpan, pengguna secara otomatis diautentikasi menggunakan metode `Auth::login()` tanpa perlu melalui proses login terpisah. Sistem kemudian mengirimkan notifikasi verifikasi surel kepada alamat yang didaftarkan dan mengalihkan pengguna ke halaman dasbor.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor User as User/Browser
    participant Router as Laravel Router
    participant RUC as RegisteredUserController
    participant UserM as User Model
    participant AuthFacade as Auth Facade
    participant DB as Database

    User->>Router: GET /register
    Router->>RUC: create()
    RUC-->>User: Form pendaftaran

    User->>Router: POST /register (name, email, password, confirmation)
    Router->>RUC: store(Request)
    RUC->>RUC: validate name, email unique, password confirmed min:8
    alt Validasi gagal
        RUC-->>User: Redirect back + validation errors
    else Validasi berhasil
        RUC->>UserM: create([name, email, password => Hash::make])
        UserM->>DB: INSERT INTO users
        DB-->>UserM: User baru + ID
        UserM-->>RUC: $user
        RUC->>AuthFacade: login($user)
        RUC-->>User: Redirect ke /dashboard + kirim email verifikasi
    end
```

---

## 12. Lupa / Reset Password

### Penjelasan

Fitur pemulihan kata sandi diimplementasikan melalui alur dua tahap yang terstruktur untuk menjamin keamanan proses penggantian kata sandi. Pada tahap pertama, pengguna memasukkan alamat surel pada formulir yang tersedia di rute `/forgot-password`. `PasswordResetLinkController` kemudian memverifikasi keberadaan surel tersebut dalam basis data dan membuat token reset yang unik untuk disimpan pada tabel `password_reset_tokens`.

Sebagai pertimbangan keamanan, sistem selalu menampilkan pesan yang mengindikasikan keberhasilan pengiriman tautan, terlepas dari apakah surel tersebut terdaftar dalam sistem atau tidak. Pendekatan ini bertujuan untuk mencegah enumerasi akun oleh pihak yang tidak berwenang. Tautan reset yang memuat token unik kemudian dikirimkan ke alamat surel pengguna melalui layanan pengiriman surat elektronik. Pada tahap kedua, pengguna mengakses tautan tersebut dan mengisi formulir kata sandi baru. `NewPasswordController` memverifikasi validitas dan masa berlaku token, kemudian memperbarui kata sandi pengguna di basis data setelah token dikonfirmasi sah. Token yang telah digunakan kemudian dihapus untuk mencegah penggunaan ulang.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor User as User/Browser
    participant PRLC as PasswordResetLinkController
    participant NPC as NewPasswordController
    participant DB as Database
    participant Email as Mail Service

    User->>PRLC: GET /forgot-password
    PRLC-->>User: Form input surel

    User->>PRLC: POST /forgot-password (email)
    PRLC->>DB: Verifikasi surel di tabel users
    alt Surel tidak terdaftar
        PRLC-->>User: Pesan sukses netral (keamanan)
    else Surel valid
        PRLC->>DB: INSERT INTO password_reset_tokens
        PRLC->>Email: Kirim tautan reset berisi token
        Email-->>User: Surel berisi /reset-password/{token}
        PRLC-->>User: Konfirmasi pengiriman tautan
    end

    User->>NPC: GET /reset-password/{token}
    NPC-->>User: Form kata sandi baru

    User->>NPC: POST /reset-password (token, email, password)
    NPC->>DB: Verifikasi token valid dan belum kedaluwarsa
    alt Token tidak valid
        NPC-->>User: Error token kedaluwarsa
    else Token valid
        NPC->>DB: UPDATE users SET password = Hash::make(baru)
        NPC->>DB: DELETE FROM password_reset_tokens
        NPC-->>User: Redirect /login + notifikasi berhasil
    end
```

---

## 13. Profil Pengguna

### Penjelasan

Halaman profil pengguna menyediakan antarmuka bagi pengguna yang telah terautentikasi untuk mengelola data akun mereka secara mandiri. Akses ke halaman ini dijaga oleh _middleware_ `auth`, yang secara otomatis mengalihkan pengguna yang belum terautentikasi ke halaman login. Halaman ini mengintegrasikan tiga fungsi utama dalam satu tampilan terpadu.

Fungsi pertama adalah pemutakhiran informasi profil, di mana pengguna dapat mengubah nama dan alamat surel. Apabila surel diubah, atribut `email_verified_at` secara otomatis dikosongkan untuk mengharuskan pengguna melakukan verifikasi ulang terhadap surel baru. Fungsi kedua adalah penggantian kata sandi yang memerlukan verifikasi kata sandi lama sebelum kata sandi baru dapat disimpan. Fungsi ketiga adalah penghapusan akun secara permanen, yang mengharuskan pengguna mengonfirmasi kata sandi mereka sebelum operasi dieksekusi. Proses penghapusan akun mencakup _logout_ dari sesi aktif, penghapusan rekod pengguna dari basis data, serta invalidasi sesi dan pembaruan token CSRF untuk menjaga keamanan sistem.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor User as User (Terautentikasi)
    participant Router as Laravel Router
    participant Middleware as Middleware auth
    participant PC as ProfileController
    participant UserM as User Model
    participant AuthFacade as Auth Facade
    participant DB as Database

    User->>Router: GET /profile
    Router->>Middleware: Verifikasi sesi auth
    alt Belum login
        Middleware-->>User: Redirect ke /login
    end
    Router->>PC: edit(Request)
    PC-->>User: Form edit profil

    User->>Router: PATCH /profile (name, email)
    Router->>PC: update(ProfileUpdateRequest)
    PC->>PC: validate()
    alt Surel berubah
        PC->>UserM: email_verified_at = null
    end
    PC->>UserM: save()
    UserM->>DB: UPDATE users SET name, email WHERE id
    PC-->>User: Redirect /profile + status profile-updated

    User->>Router: DELETE /profile (konfirmasi password)
    Router->>PC: destroy(Request)
    PC->>PC: validateWithBag current_password
    PC->>AuthFacade: logout()
    PC->>UserM: delete()
    UserM->>DB: DELETE FROM users WHERE id
    PC->>PC: session invalidate + regenerateToken
    PC-->>User: Redirect ke /
```

---

## 14. Admin — Manajemen Produk

### Penjelasan

Halaman manajemen produk pada panel administrasi dibangun menggunakan **Filament v3**, sebuah _framework_ antarmuka administrasi berbasis Laravel yang menyediakan komponen CRUD secara deklaratif. Halaman ini hanya dapat diakses oleh pengguna dengan hak akses administrator, dan menampilkan seluruh rekod produk dalam format tabel yang dilengkapi dengan fitur pencarian, pengurutan, dan filter.

Formulir penambahan produk baru diorganisasikan ke dalam tiga tab: tab *General* untuk informasi dasar produk (nama, slug otomatis, kategori, gambar, dan deskripsi), tab *Pricing & Stock* untuk data harga, harga diskon, stok, dan berat produk, serta tab *SEO* untuk pengisian meta judul dan meta deskripsi guna keperluan optimasi mesin pencari. Slug produk dibangkitkan secara otomatis dari nama produk menggunakan fungsi `Str::slug()` pada saat operasi pembuatan. Berkas gambar yang diunggah disimpan ke direktori `products` pada sistem penyimpanan lokal. Tersedia pula aksi khusus _Flash Sale_ yang dapat diaktifkan oleh administrator untuk menandai produk tertentu sebagai produk promosi. Operasi pembaruan data produk mengikuti alur yang serupa dengan pembuatan, namun menggunakan metode HTTP PATCH untuk memperbarui rekod yang telah ada.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor Admin as Administrator
    participant Filament as Filament Panel
    participant PR as ProductResource
    participant ProductM as Product Model
    participant DB as Database
    participant Storage as File Storage

    Admin->>Filament: GET /admin/products
    Filament->>PR: table()
    PR->>DB: SELECT products WITH category
    DB-->>Filament: Daftar produk
    Filament-->>Admin: Tabel produk (nama, kategori, harga, stok)

    Admin->>Filament: GET /admin/products/create
    Filament->>PR: form() — tabs General, Pricing, SEO
    Filament-->>Admin: Formulir tambah produk

    Admin->>Filament: POST (data produk lengkap)
    Filament->>Filament: Validasi field wajib
    alt Ada upload gambar
        Filament->>Storage: Simpan ke direktori products
        Storage-->>Filament: Path berkas gambar
    end
    Filament->>ProductM: create(data)
    ProductM->>DB: INSERT INTO products
    DB-->>ProductM: Produk baru + ID
    Filament-->>Admin: Redirect + notifikasi berhasil

    Admin->>Filament: GET /admin/products/{id}/edit
    Filament->>DB: SELECT product WHERE id=?
    DB-->>Filament: Data produk
    Filament-->>Admin: Formulir edit terisi

    Admin->>Filament: PATCH (data yang diubah)
    Filament->>ProductM: update(data)
    ProductM->>DB: UPDATE products SET ... WHERE id
    Filament-->>Admin: Redirect + notifikasi tersimpan
```

---

## 15. Admin — Manajemen Pesanan

### Penjelasan

Halaman manajemen pesanan merupakan komponen paling vital dalam operasional bisnis toko online Ivo Karya. Halaman ini memungkinkan administrator untuk memantau seluruh pesanan yang masuk dan mengelola perpindahan status pesanan melalui alur yang telah ditetapkan: `pending` → `processing` → `shipped` → `completed`. Setiap transisi status yang signifikan diiringi dengan pengiriman notifikasi otomatis melalui layanan WhatsApp kepada pelanggan menggunakan **FonnteService**.

Pada saat administrator mengonfirmasi pesanan dari status `pending` ke `processing`, sistem secara bersamaan mengambil informasi rekening bank dari tabel pengaturan (_settings_) dan menyusun pesan WhatsApp yang berisi detail pesanan beserta instruksi pembayaran, kemudian mengirimkannya ke nomor telepon pelanggan. Pada tahap berikutnya, ketika administrator memasukkan nomor resi pengiriman, status pesanan diperbarui menjadi `shipped` dan sistem secara otomatis mengirimkan pesan WhatsApp yang memuat nomor resi serta tautan pelacakan pesanan yang unik kepada pelanggan. Mekanisme notifikasi otomatis ini bertujuan untuk meningkatkan transparansi proses pengiriman dan kepuasan pelanggan.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor Admin as Administrator
    participant Filament as Filament Panel
    participant OR as OrderResource
    participant OrderM as Order Model
    participant DB as Database
    participant Setting as Setting Model
    participant Fonnte as FonnteService (WhatsApp)

    Admin->>Filament: GET /admin/orders
    Filament->>OR: table()
    OR->>DB: SELECT orders ORDER BY created_at DESC
    DB-->>Filament: Daftar pesanan + badge status
    Filament-->>Admin: Tabel pesanan (ID, Customer, Total, Status)

    Admin->>Filament: Klik Konfirmasi (status: pending)
    OR->>OrderM: update(['status'=>'processing'])
    OrderM->>DB: UPDATE orders SET status=processing
    OR->>Setting: get('bank_account_info')
    Setting->>DB: SELECT value FROM settings WHERE key=bank_account_info
    DB-->>Setting: Info rekening bank
    OR->>Fonnte: send(phone, pesan konfirmasi + info bayar)
    Fonnte-->>OR: Response API
    Filament-->>Admin: Notifikasi WA terkirim

    Admin->>Filament: Klik Kirim Resi (status: processing)
    Filament-->>Admin: Formulir input nomor resi
    Admin->>Filament: Submit nomor resi
    OR->>OrderM: update(['status'=>'shipped', 'tracking_number'=>resi])
    OrderM->>DB: UPDATE orders SET status=shipped, tracking_number=?
    OR->>OR: Buat URL pelacakan via route('order.track', token)
    OR->>Fonnte: send(phone, pesan resi + URL pelacakan)
    Fonnte-->>OR: Response API
    Filament-->>Admin: Notifikasi resi WA terkirim
```

---

## 16. Admin — Manajemen Ulasan

### Penjelasan

Halaman manajemen ulasan pada panel administrasi berfungsi sebagai antarmuka moderasi konten yang memungkinkan administrator untuk meninjau, menyetujui, atau menghapus ulasan produk yang dikirimkan oleh pelanggan. Sistem ulasan pada aplikasi ini menerapkan mekanisme persetujuan (_approval_) sebelum ulasan ditampilkan kepada publik, yang dikendalikan melalui atribut `is_approved` pada tabel ulasan.

Seluruh ulasan, baik yang telah maupun belum mendapat persetujuan, ditampilkan dalam tabel administrasi beserta informasi produk yang diulas, nama pelanggan, nilai rating bintang, isi komentar, dan status persetujuan. Administrator dapat mengubah status persetujuan ulasan secara individual melalui aksi toggle, yang akan memperbarui nilai atribut `is_approved` di basis data. Hanya ulasan dengan nilai `is_approved = true` yang akan ditampilkan pada halaman detail produk yang dapat diakses oleh publik. Selain itu, administrator juga memiliki kewenangan untuk menghapus ulasan secara permanen apabila konten ulasan tersebut dinilai tidak sesuai dengan kebijakan toko.

### Sequence Diagram

```mermaid
sequenceDiagram
    actor Admin as Administrator
    participant Filament as Filament Panel
    participant RR as ReviewResource
    participant ReviewM as Review Model
    participant DB as Database

    Admin->>Filament: GET /admin/reviews
    Filament->>RR: table()
    RR->>DB: SELECT reviews WITH product, user ORDER BY created_at DESC
    DB-->>Filament: Seluruh ulasan (disetujui & belum)
    Filament-->>Admin: Tabel ulasan (nama, produk, rating, komentar, status)

    Admin->>Filament: Toggle is_approved
    Filament->>ReviewM: update(['is_approved' => true/false])
    ReviewM->>DB: UPDATE reviews SET is_approved=? WHERE id
    DB-->>Filament: Updated
    Filament-->>Admin: Status ulasan diperbarui

    Admin->>Filament: Klik Hapus
    Filament->>ReviewM: delete()
    ReviewM->>DB: DELETE FROM reviews WHERE id
    DB-->>Filament: Deleted
    Filament-->>Admin: Ulasan dihapus dari tabel
```

---

## 17. API Shipping

### Penjelasan

Modul API pengiriman merupakan lapisan integrasi antara sistem toko online dengan layanan pihak ketiga **RajaOngkir** yang menyediakan data wilayah administratif Indonesia dan kalkulasi tarif ongkos kirim secara _real-time_. API ini beroperasi pada rute internal dengan prefiks `/api/shipping` dan diakses secara langsung oleh kode JavaScript pada sisi klien melalui permintaan asinkronus (`fetch` / `axios`) dari halaman keranjang belanja.

Sistem menyediakan lima _endpoint_ yang saling berkaitan. Pertama, _endpoint_ pengambilan daftar provinsi yang menginterogasi API RajaOngkir untuk mendapatkan seluruh data provinsi di Indonesia. Kedua, _endpoint_ pengambilan kota berdasarkan identitas provinsi. Ketiga, _endpoint_ pencarian kota berdasarkan kode pos yang mempermudah pengguna dalam mengidentifikasi kota tujuan pengiriman. Keempat, _endpoint_ kalkulasi biaya pengiriman yang menerima parameter asal, tujuan, berat total paket, dan nama kurir untuk menghasilkan daftar layanan beserta tarifnya. Kelima, _endpoint_ _reverse geocoding_ yang mengonversi koordinat GPS (lintang dan bujur) yang diperoleh dari perangkat pengguna menjadi informasi alamat yang dapat dibaca manusia, guna membantu pengisian alamat pengiriman secara otomatis.

### Sequence Diagram

```mermaid
sequenceDiagram
    participant JS as Browser/JavaScript
    participant Router as Laravel Router
    participant SC as ShippingController
    participant RO as RajaOngkir API
    participant Geo as Geocoding Service

    JS->>Router: GET /api/shipping/provinces
    Router->>SC: getProvinces()
    SC->>RO: GET rajaongkir.com/starter/province
    RO-->>SC: JSON daftar provinsi
    SC-->>JS: Response provinsi

    JS->>Router: GET /api/shipping/cities?province_id=?
    Router->>SC: getCities(Request)
    SC->>RO: GET /starter/city?province={id}
    RO-->>SC: JSON daftar kota
    SC-->>JS: Response kota

    JS->>Router: POST /api/shipping/find-city (postal_code)
    Router->>SC: findCityByPostalCode(Request)
    SC->>RO: Query kota by kode pos
    RO-->>SC: Data kota cocok
    SC-->>JS: JSON {city_id, city_name, province}

    JS->>Router: POST /api/shipping/cost (origin, dest, weight, courier)
    Router->>SC: calculateCost(Request)
    SC->>RO: POST /starter/cost
    RO-->>SC: JSON tarif per layanan
    SC-->>JS: Daftar kurir + biaya ongkir

    JS->>Router: POST /api/shipping/geocode (latitude, longitude)
    Router->>SC: reverseGeocode(Request)
    SC->>Geo: Konversi koordinat ke alamat
    Geo-->>SC: Nama jalan, kecamatan, kota
    SC-->>JS: JSON informasi lokasi
```

---

## Catatan Teknis

| Aspek | Keterangan |
|-------|-----------|
| **Framework** | Laravel 10 + Filament v3 + Livewire 3 |
| **Autentikasi** | Laravel Breeze (session-based) |
| **Penyimpanan Keranjang** | PHP Session — tidak memerlukan autentikasi |
| **Transaksi Basis Data** | `DB::beginTransaction()` pada proses checkout |
| **Notifikasi WhatsApp** | Fonnte API — dipicu saat konfirmasi & pengiriman resi |
| **Integrasi Ongkir** | RajaOngkir Starter Plan |
| **Komponen Real-time** | Livewire 3 — `ProductReviews` component |
| **Panel Admin** | Filament v3 Resources (Product, Order, Category, Article, Review, Setting) |
| **Penyimpanan Berkas** | Laravel Storage `storage/app/public` |
| **Token Pelacakan** | `bin2hex(random_bytes(16))` — 32 karakter heksadesimal unik |
| **URL Produk & Artikel** | Route Model Binding berbasis `slug` (SEO-friendly) |

---

*Dokumentasi ini disusun berdasarkan hasil analisis mendalam terhadap kode sumber proyek WEBSITE-IVO-KARYA.*  
*Dibuat oleh: Sistem Dokumentasi Antigravity AI — 2 April 2026*
