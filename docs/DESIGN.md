# 🎨 Dokumentasi Design & Arsitektur Sistem

> **Platform E-Commerce Ivo Karya** - Dokumentasi Teknis Lengkap

---

## 📋 Daftar Isi

1. [Diagram Arsitektur](#1-diagram-arsitektur)
2. [Diagram Alur Kerja](#2-diagram-alur-kerja)
3. [Diagram Use Case](#3-diagram-use-case)
4. [Diagram Kelas](#4-diagram-kelas)
5. [Diagram Sequence](#5-diagram-sequence)
6. [Entity Relationship Diagram](#6-entity-relationship-diagram)
7. [Diagram Komponen](#7-diagram-komponen)

---

## 1. Diagram Arsitektur

### 1.1 Deskripsi
Sistem Ivo Karya menggunakan arsitektur **Monolitik Termodulasi** dengan pemisahan yang jelas antara:
- **Frontend Layer**: Blade Templates + Alpine.js + Tailwind CSS
- **Backend Layer**: Laravel 11 dengan Filament Admin Panel
- **Data Layer**: MySQL Database
- **External Services**: WhatsApp Gateway (Fonnte), Shipping API (RajaOngkir/Komerce)

### 1.2 Diagram

```mermaid
graph TB
    subgraph "🌐 Client Layer"
        Browser[🖥️ Web Browser]
        Mobile[📱 Mobile Browser]
    end
    
    subgraph "🎨 Presentation Layer"
        Blade[Blade Templates]
        Alpine[Alpine.js]
        Tailwind[Tailwind CSS]
        Livewire[Livewire Components]
    end
    
    subgraph "⚙️ Application Layer"
        Routes[Routes]
        Controllers[Controllers]
        Middleware[Middleware]
        Services[Services]
        Models[Eloquent Models]
    end
    
    subgraph "🔧 Admin Layer"
        Filament[Filament v3]
        Resources[Admin Resources]
        Widgets[Dashboard Widgets]
    end
    
    subgraph "💾 Data Layer"
        MySQL[(MySQL Database)]
        Sessions[(Session Storage)]
        Cache[(Cache Storage)]
    end
    
    subgraph "🌍 External Services"
        Fonnte[📱 Fonnte WhatsApp API]
        RajaOngkir[🚚 RajaOngkir API]
        Komerce[📦 Komerce API]
    end
    
    Browser --> Blade
    Mobile --> Blade
    Blade --> Alpine
    Blade --> Tailwind
    Blade --> Livewire
    
    Blade --> Routes
    Routes --> Middleware
    Middleware --> Controllers
    Controllers --> Services
    Services --> Models
    Models --> MySQL
    
    Filament --> Resources
    Resources --> Models
    Widgets --> Models
    
    Services --> Fonnte
    Services --> RajaOngkir
    Services --> Komerce
    
    Controllers --> Sessions
    Controllers --> Cache
```

### 1.3 Penjelasan Komponen

| Layer | Komponen | Teknologi | Fungsi |
|:------|:---------|:----------|:-------|
| **Client** | Browser | Chrome/Firefox/Safari | Akses web oleh pengguna |
| **Presentation** | Blade | Laravel Blade | Server-side templating |
| **Presentation** | Alpine.js | JavaScript | Interaktivitas frontend |
| **Presentation** | Tailwind | CSS Framework | Styling responsif |
| **Application** | Controllers | PHP | Handle HTTP requests |
| **Application** | Services | PHP | Business logic |
| **Application** | Models | Eloquent ORM | Database abstraction |
| **Admin** | Filament | PHP | Admin panel framework |
| **Data** | MySQL | RDBMS | Penyimpanan data |
| **External** | Fonnte | REST API | WhatsApp notifications |
| **External** | RajaOngkir | REST API | Ongkos kirim |

---

## 2. Diagram Alur Kerja

### 2.1 Deskripsi
Alur kerja sistem menggambarkan perjalanan data dari input pengguna hingga output yang dihasilkan. Berikut adalah alur utama pemesanan produk.

### 2.2 Diagram Alur Pemesanan

```mermaid
flowchart TD
    Start([🏠 Pelanggan Masuk Website]) --> Browse[📦 Lihat Katalog Produk]
    Browse --> Detail[👀 Lihat Detail Produk]
    Detail --> AddCart{🛒 Tambah ke Keranjang?}
    
    AddCart -->|Ya| Cart[🛒 Lihat Keranjang]
    AddCart -->|Tidak| Browse
    
    Cart --> FillForm[📝 Isi Data Penerima]
    FillForm --> SelectLoc[📍 Pilih Lokasi Pengiriman]
    SelectLoc --> CalcShip[🚚 Hitung Ongkir]
    
    CalcShip --> SelectCourier[📦 Pilih Kurir]
    SelectCourier --> SelectPayment{💳 Pilih Metode Bayar}
    
    SelectPayment -->|Transfer Bank| TransferInfo[📄 Tampilkan Info Rekening]
    SelectPayment -->|COD| CODInfo[💰 Instruksi Bayar di Tempat]
    
    TransferInfo --> CheckStock{📊 Cek Stok Tersedia?}
    CODInfo --> CheckStock
    
    CheckStock -->|Ya| CreateOrder[✅ Buat Pesanan]
    CheckStock -->|Tidak| StockError[❌ Error: Stok Tidak Cukup]
    StockError --> Cart
    
    CreateOrder --> DecrementStock[📉 Kurangi Stok Produk]
    DecrementStock --> SaveDB[(💾 Simpan ke Database)]
    SaveDB --> SendWA[📱 Kirim Notifikasi WhatsApp]
    SendWA --> TrackPage[📋 Halaman Pelacakan Pesanan]
    
    TrackPage --> End([✅ Selesai])
```

### 2.3 Penjelasan Step-by-Step

| Step | Aksi | Komponen Terlibat |
|:-----|:-----|:------------------|
| 1 | Pelanggan mengakses website | `HomeController`, `welcome.blade.php` |
| 2 | Melihat katalog produk | `HomeController@catalog`, `catalog.blade.php` |
| 3 | Tambah ke keranjang | `CartController@add`, Session Storage |
| 4 | Isi form checkout | `cart.blade.php`, Alpine.js validation |
| 5 | Pilih lokasi pengiriman | `LocationPicker` component, Komerce API |
| 6 | Hitung ongkos kirim | `ShippingController`, RajaOngkir/Komerce API |
| 7 | Proses checkout | `CartController@checkout`, DB Transaction |
| 8 | Simpan pesanan | `Order` model, MySQL |
| 9 | Kirim notifikasi | `FonnteService`, WhatsApp API |
| 10 | Tampilkan tracking | `CartController@track`, `track.blade.php` |

---

## 3. Diagram Use Case

### 3.1 Deskripsi
Diagram Use Case menggambarkan interaksi antara aktor (pengguna) dengan fitur-fitur yang tersedia dalam sistem.

### 3.2 Diagram

```mermaid
graph LR
    subgraph "👤 Aktor"
        Guest((🧑 Pengunjung))
        Customer((👨‍💼 Pelanggan))
        Admin((👨‍💻 Admin))
    end
    
    subgraph "🌐 Fitur Publik"
        UC1[Lihat Katalog Produk]
        UC2[Lihat Detail Produk]
        UC3[Baca Artikel]
        UC4[Tambah ke Keranjang]
        UC5[Checkout Pesanan]
        UC6[Lacak Pesanan]
        UC7[Tulis Review]
        UC8[Chat dengan Chatbot]
    end
    
    subgraph "🔐 Fitur Admin"
        UC9[Kelola Produk]
        UC10[Kelola Kategori]
        UC11[Kelola Pesanan]
        UC12[Kelola Artikel]
        UC13[Moderasi Review]
        UC14[Lihat Dashboard Analitik]
        UC15[Kelola Pengaturan]
    end
    
    Guest --> UC1
    Guest --> UC2
    Guest --> UC3
    Guest --> UC4
    Guest --> UC8
    
    Customer --> UC1
    Customer --> UC2
    Customer --> UC3
    Customer --> UC4
    Customer --> UC5
    Customer --> UC6
    Customer --> UC7
    Customer --> UC8
    
    Admin --> UC9
    Admin --> UC10
    Admin --> UC11
    Admin --> UC12
    Admin --> UC13
    Admin --> UC14
    Admin --> UC15
```

### 3.3 Penjelasan Aktor

| Aktor | Deskripsi | Hak Akses |
|:------|:----------|:----------|
| **Pengunjung (Guest)** | Pengguna yang belum login | Lihat produk, baca artikel, tambah keranjang, chat |
| **Pelanggan (Customer)** | Pengguna yang melakukan pembelian | Semua fitur publik + checkout + tracking + review |
| **Admin** | Pengelola toko | Full akses admin panel Filament |

---

## 4. Diagram Kelas

### 4.1 Deskripsi
Diagram kelas menggambarkan struktur model/entity dalam sistem beserta relasinya.

### 4.2 Diagram

```mermaid
classDiagram
    class User {
        +bigint id
        +string name
        +string email
        +string password
        +timestamp email_verified_at
        +hasMany() orders
        +hasMany() reviews
    }
    
    class Category {
        +bigint id
        +string name
        +string slug
        +string description
        +hasMany() products
    }
    
    class Product {
        +bigint id
        +bigint category_id
        +string name
        +string slug
        +text description
        +decimal price
        +decimal discount_price
        +int stock
        +int weight
        +string image
        +belongsTo() category
        +hasMany() reviews
        +getDiscountPercentageAttribute()
    }
    
    class Order {
        +bigint id
        +bigint user_id
        +string customer_name
        +string customer_phone
        +string customer_address
        +json items_json
        +decimal total_amount
        +decimal total_weight
        +string status
        +string payment_method
        +string tracking_token
        +string tracking_number
        +decimal shipping_cost
        +belongsTo() user
    }
    
    class Review {
        +bigint id
        +bigint product_id
        +bigint user_id
        +string customer_name
        +int rating
        +text comment
        +boolean is_approved
        +belongsTo() product
        +belongsTo() user
    }
    
    class Article {
        +bigint id
        +string title
        +string slug
        +text content
        +string image
        +boolean is_published
    }
    
    class Setting {
        +bigint id
        +string key
        +text value
        +static get()
        +static set()
    }
    
    User "1" --> "*" Order : places
    User "1" --> "*" Review : writes
    Category "1" --> "*" Product : contains
    Product "1" --> "*" Review : has
```

### 4.3 Penjelasan Kelas Utama

| Kelas | Tanggung Jawab | Atribut Penting |
|:------|:---------------|:----------------|
| **User** | Menyimpan data pengguna | name, email, password |
| **Product** | Menyimpan data produk | name, price, stock, weight |
| **Order** | Menyimpan data pesanan | items_json, total_amount, status, payment_method |
| **Category** | Mengelompokkan produk | name, slug |
| **Review** | Menyimpan ulasan produk | rating, comment, is_approved |
| **Article** | Menyimpan artikel blog | title, content, is_published |
| **Setting** | Konfigurasi dinamis | key, value |

---

## 5. Diagram Sequence

### 5.1 Deskripsi
Diagram sequence menggambarkan interaksi antar objek untuk skenario spesifik dalam urutan waktu.

### 5.2 Sequence: Proses Checkout

```mermaid
sequenceDiagram
    actor Customer as 👤 Pelanggan
    participant UI as 🖥️ Frontend
    participant Controller as ⚙️ CartController
    participant Service as 🔧 ShippingService
    participant Model as 💾 Order Model
    participant DB as 🗄️ MySQL
    participant WA as 📱 Fonnte API
    
    Customer->>UI: Klik "Checkout"
    UI->>Controller: POST /checkout (form data)
    
    Controller->>Controller: Validate request
    Controller->>DB: BEGIN TRANSACTION
    
    loop Untuk setiap item di keranjang
        Controller->>Model: lockForUpdate()
        Model->>DB: SELECT ... FOR UPDATE
        DB-->>Model: Product data
        
        alt Stok cukup
            Controller->>Model: decrement('stock', qty)
            Model->>DB: UPDATE products SET stock = stock - qty
        else Stok tidak cukup
            Controller->>DB: ROLLBACK
            Controller-->>UI: Error: Stok tidak mencukupi
            UI-->>Customer: Tampilkan error
        end
    end
    
    Controller->>Model: Order::create()
    Model->>DB: INSERT INTO orders
    DB-->>Model: Order ID
    
    Controller->>DB: COMMIT
    
    Controller->>WA: sendMessage(customer_phone, order_details)
    WA-->>Controller: Message sent
    
    Controller-->>UI: Redirect to tracking page
    UI-->>Customer: Tampilkan halaman pelacakan
```

### 5.3 Sequence: Kalkulasi Ongkos Kirim

```mermaid
sequenceDiagram
    actor User as 👤 Pelanggan
    participant Frontend as 🖥️ LocationPicker
    participant API as ⚙️ ShippingController
    participant Service as 🔧 KomerceService
    participant External as 🌍 Komerce API
    
    User->>Frontend: Input kode pos "91611"
    Frontend->>API: POST /api/shipping/find-city
    API->>Service: searchByPostalCode("91611")
    Service->>External: GET /destination/domestic-destination?search=91611
    External-->>Service: {city_id: 398, city_name: "Sidenreng Rappang"}
    Service-->>API: City data
    API-->>Frontend: {success: true, data: cityInfo}
    
    Frontend->>Frontend: Update peta & tampilkan lokasi
    
    Frontend->>API: POST /api/shipping/cost
    API->>Service: getAllCourierCosts(destination_id, weight)
    
    loop Untuk setiap kurir (JNE, SiCepat, J&T, IDExpress)
        Service->>External: POST /calculate/domestic-cost
        External-->>Service: {cost, etd, service}
    end
    
    Service-->>API: Array of shipping options
    API-->>Frontend: {success: true, data: shippingOptions}
    Frontend-->>User: Tampilkan pilihan kurir & harga
```

---

## 6. Entity Relationship Diagram

### 6.1 Deskripsi
ERD menggambarkan struktur tabel database dan hubungan antar entitas.

### 6.2 Diagram

```mermaid
erDiagram
    users ||--o{ orders : "places"
    users ||--o{ reviews : "writes"
    categories ||--o{ products : "contains"
    products ||--o{ reviews : "has"
    
    users {
        bigint id PK
        string name
        string email UK
        string password
        timestamp email_verified_at
        timestamp created_at
        timestamp updated_at
    }
    
    categories {
        bigint id PK
        string name
        string slug UK
        text description
        timestamp created_at
        timestamp updated_at
    }
    
    products {
        bigint id PK
        bigint category_id FK
        string name
        string slug UK
        text description
        decimal price
        decimal discount_price
        int stock
        int weight
        string image
        string meta_title
        string meta_description
        timestamp created_at
        timestamp updated_at
    }
    
    orders {
        bigint id PK
        bigint user_id FK
        string customer_name
        string customer_phone
        string customer_address
        json items_json
        decimal total_amount
        decimal total_weight
        decimal shipping_cost
        string status
        string payment_method
        string tracking_token UK
        string tracking_number
        string courier
        string courier_service
        string postal_code
        string destination_city_id
        decimal latitude
        decimal longitude
        timestamp created_at
        timestamp updated_at
    }
    
    reviews {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        string customer_name
        int rating
        text comment
        boolean is_approved
        timestamp created_at
        timestamp updated_at
    }
    
    articles {
        bigint id PK
        string title
        string slug UK
        text content
        string image
        boolean is_published
        timestamp created_at
        timestamp updated_at
    }
    
    settings {
        bigint id PK
        string key UK
        text value
        timestamp created_at
        timestamp updated_at
    }
```

### 6.3 Penjelasan Relasi

| Relasi | Tipe | Deskripsi |
|:-------|:-----|:----------|
| `users` → `orders` | One-to-Many | Satu user bisa memiliki banyak pesanan |
| `users` → `reviews` | One-to-Many | Satu user bisa menulis banyak review |
| `categories` → `products` | One-to-Many | Satu kategori memiliki banyak produk |
| `products` → `reviews` | One-to-Many | Satu produk memiliki banyak review |

---

## 7. Diagram Komponen

### 7.1 Deskripsi
Diagram komponen menggambarkan organisasi dan dependensi antar modul dalam sistem.

### 7.2 Diagram

```mermaid
graph TB
    subgraph "📦 Frontend Components"
        NavBar[Public Navbar]
        LocationPicker[Location Picker]
        ChatBot[Chatbot Widget]
        CartModal[Cart Notification]
    end
    
    subgraph "🎮 Controllers"
        HomeCtrl[HomeController]
        CartCtrl[CartController]
        ShipCtrl[ShippingController]
        ArticleCtrl[ArticleController]
        TrackCtrl[TrackOrderController]
    end
    
    subgraph "🔧 Services"
        FonnteService[FonnteService]
        KomerceService[KomerceService]
        RajaOngkirService[RajaOngkirService]
    end
    
    subgraph "📊 Filament Admin"
        ProductRes[ProductResource]
        OrderRes[OrderResource]
        CategoryRes[CategoryResource]
        ArticleRes[ArticleResource]
        ReviewRes[ReviewResource]
        SettingRes[SettingResource]
    end
    
    subgraph "💾 Models"
        User[User Model]
        Product[Product Model]
        Order[Order Model]
        Category[Category Model]
        Review[Review Model]
        Article[Article Model]
    end
    
    NavBar --> HomeCtrl
    LocationPicker --> ShipCtrl
    ChatBot --> HomeCtrl
    
    HomeCtrl --> Product
    CartCtrl --> Order
    CartCtrl --> Product
    ShipCtrl --> KomerceService
    ShipCtrl --> RajaOngkirService
    
    CartCtrl --> FonnteService
    
    ProductRes --> Product
    OrderRes --> Order
    CategoryRes --> Category
    ArticleRes --> Article
    ReviewRes --> Review
```

---

## 📝 Catatan Teknis

### Best Practices yang Diterapkan

1. **Separation of Concerns**: Controller, Service, dan Model terpisah jelas
2. **Database Transactions**: Checkout menggunakan transaction untuk konsistensi data
3. **Row Locking**: Mencegah race condition saat update stok
4. **API Caching**: Response API eksternal di-cache untuk performa
5. **Lazy Loading Prevention**: Eager loading untuk relasi database

### Keputusan Arsitektur

| Keputusan | Alasan |
|:----------|:-------|
| **Monolith vs Microservices** | Monolith dipilih untuk kesederhanaan deployment dan maintenance UMKM |
| **Filament vs Custom Admin** | Filament mempercepat development dengan UI yang sudah jadi |
| **Session-based Cart** | Guest checkout tanpa perlu login |
| **External WhatsApp API** | Fonnte dipilih karena harga terjangkau dan dokumentasi lengkap |

---

*Dokumentasi ini dibuat untuk keperluan Tugas Akhir/Skripsi*  
**Universitas Ichsan Sidenreng Rappang** © 2026
