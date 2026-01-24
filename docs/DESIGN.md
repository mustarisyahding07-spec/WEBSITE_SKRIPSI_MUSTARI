# 🎨 Dokumentasi Design & Arsitektur Sistem

> **Platform E-Commerce Ivo Karya** - Dokumentasi Arsitektur dan Diagram UML

---

## 📋 Daftar Isi

1. [Diagram Arsitektur](#1--diagram-arsitektur)
2. [Diagram Workflow](#2--diagram-workflow)
3. [Diagram Use Case](#3--diagram-use-case)
4. [Diagram Kelas (Class Diagram)](#4--diagram-kelas)
5. [Diagram Sequence](#5--diagram-sequence)
6. [Entity Relationship Diagram (ERD)](#6--entity-relationship-diagram-erd)

---

## 1. 🏗 Diagram Arsitektur

Sistem Ivo Karya mengikuti arsitektur **Monolithic MVC** dengan pemisahan jelas antara frontend publik dan admin panel.

```mermaid
graph TB
    subgraph "Client Layer"
        Browser["🌐 Browser<br/>(Chrome/Firefox/Safari)"]
        Mobile["📱 Mobile Browser"]
    end

    subgraph "Presentation Layer"
        Blade["📄 Blade Templates<br/>+ Tailwind CSS"]
        Alpine["⚡ Alpine.js<br/>(Interaktivitas)"]
        Livewire["🔄 Livewire<br/>(Components)"]
    end

    subgraph "Application Layer"
        Laravel["🔷 Laravel 11<br/>(MVC Framework)"]
        Filament["🟡 Filament v3<br/>(Admin Panel)"]
        Controllers["📦 Controllers"]
        Services["⚙️ Services<br/>(FonnteService)"]
    end

    subgraph "Data Layer"
        Eloquent["🔶 Eloquent ORM"]
        MySQL[("🗄️ MySQL<br/>Database")]
    end

    subgraph "External Services"
        Fonnte["📲 Fonnte API<br/>(WhatsApp Gateway)"]
    end

    Browser --> Blade
    Mobile --> Blade
    Blade --> Alpine
    Blade --> Livewire
    Alpine --> Laravel
    Livewire --> Laravel
    Laravel --> Filament
    Laravel --> Controllers
    Controllers --> Services
    Services --> Fonnte
    Controllers --> Eloquent
    Filament --> Eloquent
    Eloquent --> MySQL
```

### Penjelasan Komponen

| Layer | Komponen | Deskripsi |
|:------|:---------|:----------|
| **Client** | Browser/Mobile | Antarmuka pengguna mengakses sistem |
| **Presentation** | Blade + Tailwind + Alpine.js | Template engine dengan styling modern dan interaktivitas |
| **Application** | Laravel 11 + Filament v3 | Framework utama dengan admin panel |
| **Data** | Eloquent ORM + MySQL | Abstraksi database dan penyimpanan data |
| **External** | Fonnte API | Integrasi WhatsApp untuk notifikasi |

---

## 2. 🔄 Diagram Workflow

### A. Alur Pemesanan (Order Flow)

```mermaid
flowchart TD
    A[("👤 Pelanggan")] --> B["Kunjungi Website"]
    B --> C["Lihat Katalog Produk"]
    C --> D["Pilih Produk"]
    D --> E["Tambah ke Keranjang"]
    E --> F{"Lanjut Belanja?"}
    F -->|Ya| C
    F -->|Tidak| G["Buka Keranjang"]
    G --> H["Isi Data Pengiriman"]
    H --> I["Pilih Metode Pembayaran"]
    I --> J["Submit Pesanan"]
    J --> K[("💾 Simpan ke Database")]
    K --> L["Generate Invoice"]
    L --> M["📲 Kirim WA Invoice<br/>(Fonnte API)"]
    M --> N["Pelanggan Transfer"]
    N --> O[("👨‍💼 Admin")]
    O --> P["Konfirmasi Pembayaran"]
    P --> Q["Update Status: Processing"]
    Q --> R["Kirim Barang"]
    R --> S["Update Status: Shipped"]
    S --> T["📲 Kirim WA Resi<br/>(Fonnte API)"]
    T --> U["Pelanggan Terima"]
    U --> V["Konfirmasi Penerimaan"]
    V --> W["Status: Completed"]

    style A fill:#e1f5fe
    style O fill:#fff3e0
    style K fill:#e8f5e9
    style M fill:#c8e6c9
    style T fill:#c8e6c9
```

### B. Alur Admin Dashboard

```mermaid
flowchart LR
    Admin[("👨‍💼 Admin")] --> Login["Login Filament"]
    Login --> Dashboard["📊 Dashboard"]
    
    Dashboard --> Products["📦 Kelola Produk"]
    Dashboard --> Orders["📋 Kelola Pesanan"]
    Dashboard --> Articles["📝 Kelola Artikel"]
    Dashboard --> Reviews["⭐ Moderasi Ulasan"]
    Dashboard --> Settings["⚙️ Pengaturan"]
    
    Products --> CRUD1["Create/Read/Update/Delete"]
    Orders --> Status["Update Status Pesanan"]
    Status --> Notify["Kirim Notifikasi WA"]
    Articles --> CRUD2["Create/Read/Update/Delete"]
    Reviews --> Approve["Approve/Reject"]
    Settings --> Config["Konfigurasi Sistem"]
```

---

## 3. 👥 Diagram Use Case

Diagram ini menunjukkan interaksi antara aktor dan fitur sistem.

```mermaid
graph LR
    subgraph Aktor
        Guest(("🧑 Guest"))
        Customer(("👤 Customer"))
        Admin(("👨‍💼 Admin"))
    end

    subgraph "Fitur Publik"
        UC1["Lihat Landing Page"]
        UC2["Lihat Katalog"]
        UC3["Lihat Detail Produk"]
        UC4["Baca Artikel"]
        UC5["Lacak Pesanan"]
    end

    subgraph "Fitur Transaksi"
        UC6["Tambah ke Keranjang"]
        UC7["Checkout Pesanan"]
        UC8["Konfirmasi Penerimaan"]
        UC9["Tulis Ulasan"]
    end

    subgraph "Fitur Admin"
        UC10["Kelola Produk"]
        UC11["Kelola Pesanan"]
        UC12["Kelola Kategori"]
        UC13["Kelola Artikel"]
        UC14["Moderasi Ulasan"]
        UC15["Pengaturan Sistem"]
        UC16["Lihat Dashboard Analytics"]
    end

    Guest --> UC1
    Guest --> UC2
    Guest --> UC3
    Guest --> UC4
    Guest --> UC5
    Guest --> UC6
    Guest --> UC7

    Customer --> UC1
    Customer --> UC2
    Customer --> UC3
    Customer --> UC4
    Customer --> UC5
    Customer --> UC6
    Customer --> UC7
    Customer --> UC8
    Customer --> UC9

    Admin --> UC10
    Admin --> UC11
    Admin --> UC12
    Admin --> UC13
    Admin --> UC14
    Admin --> UC15
    Admin --> UC16
```

### Penjelasan Aktor

| Aktor | Deskripsi | Akses |
|:------|:----------|:------|
| **Guest** | Pengunjung tanpa akun | Lihat produk, checkout tanpa login |
| **Customer** | Pelanggan terdaftar | Semua fitur Guest + ulasan + profil |
| **Admin** | Administrator sistem | Full access ke Filament Dashboard |

---

## 4. 📦 Diagram Kelas

Struktur model utama dalam sistem.

```mermaid
classDiagram
    class User {
        +int id
        +string name
        +string email
        +string password
        +timestamp email_verified_at
        +orders() Order[]
        +reviews() Review[]
    }

    class Product {
        +int id
        +int category_id
        +string name
        +string slug
        +text description
        +decimal price
        +int stock
        +int weight
        +string image
        +boolean is_active
        +category() Category
        +reviews() Review[]
    }

    class Category {
        +int id
        +string name
        +string slug
        +string image
        +products() Product[]
    }

    class Order {
        +int id
        +int user_id
        +string customer_name
        +string customer_phone
        +string customer_address
        +json items
        +decimal total_price
        +int total_weight
        +string status
        +string tracking_number
        +string tracking_token
        +user() User
    }

    class Review {
        +int id
        +int product_id
        +int user_id
        +string customer_name
        +int rating
        +text comment
        +string image
        +boolean is_approved
        +product() Product
        +user() User
    }

    class Article {
        +int id
        +string title
        +string slug
        +text content
        +string image
        +boolean is_published
    }

    class Setting {
        +int id
        +string key
        +text value
    }

    User "1" --> "*" Order : places
    User "1" --> "*" Review : writes
    Category "1" --> "*" Product : contains
    Product "1" --> "*" Review : has
    Order "*" --> "*" Product : contains
```

---

## 5. 🔀 Diagram Sequence

### A. Sequence: Proses Checkout

```mermaid
sequenceDiagram
    autonumber
    actor Customer as 👤 Customer
    participant Cart as 🛒 CartController
    participant Order as 📋 Order Model
    participant DB as 🗄️ Database
    participant Fonnte as 📲 FonnteService
    participant WA as 📱 WhatsApp

    Customer->>Cart: POST /checkout (data pesanan)
    Cart->>Cart: Validasi input
    Cart->>Order: Create new Order
    Order->>DB: INSERT order data
    DB-->>Order: Order created (ID, token)
    Order-->>Cart: Order object
    Cart->>Cart: Generate invoice message
    Cart->>Fonnte: send(phone, invoice)
    Fonnte->>WA: API Request
    WA-->>Customer: 📩 Invoice WhatsApp
    Cart-->>Customer: Redirect ke halaman tracking
```

### B. Sequence: Update Status Pesanan

```mermaid
sequenceDiagram
    autonumber
    actor Admin as 👨‍💼 Admin
    participant Filament as 🟡 Filament Panel
    participant Resource as 📋 OrderResource
    participant Order as 📦 Order Model
    participant DB as 🗄️ Database
    participant Fonnte as 📲 FonnteService
    participant WA as 📱 WhatsApp
    actor Customer as 👤 Customer

    Admin->>Filament: Update status to "Shipped"
    Filament->>Resource: Handle status change
    Resource->>Order: Update status & tracking_number
    Order->>DB: UPDATE order
    DB-->>Order: Success
    
    alt status == "shipped"
        Resource->>Fonnte: send(phone, resi_message)
        Fonnte->>WA: API Request
        WA-->>Customer: 📩 Notifikasi Pengiriman
    end
    
    Resource-->>Admin: Success notification
```

---

## 6. 🗃 Entity Relationship Diagram (ERD)

```mermaid
erDiagram
    users {
        bigint id PK
        varchar name
        varchar email UK
        timestamp email_verified_at
        varchar password
        varchar remember_token
        timestamps created_at
        timestamps updated_at
    }

    categories {
        bigint id PK
        varchar name
        varchar slug UK
        varchar image
        timestamps created_at
        timestamps updated_at
    }

    products {
        bigint id PK
        bigint category_id FK
        varchar name
        varchar slug UK
        text description
        decimal price
        int stock
        int weight
        varchar image
        boolean is_active
        timestamps created_at
        timestamps updated_at
    }

    orders {
        bigint id PK
        bigint user_id FK
        varchar customer_name
        varchar customer_phone
        varchar customer_address
        json items
        decimal total_price
        int total_weight
        varchar status
        varchar tracking_number
        varchar tracking_token UK
        timestamps created_at
        timestamps updated_at
    }

    reviews {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        varchar customer_name
        int rating
        text comment
        varchar image
        boolean is_approved
        timestamps created_at
        timestamps updated_at
    }

    articles {
        bigint id PK
        varchar title
        varchar slug UK
        text content
        varchar image
        boolean is_published
        timestamps created_at
        timestamps updated_at
    }

    settings {
        bigint id PK
        varchar key UK
        text value
        timestamps created_at
        timestamps updated_at
    }

    users ||--o{ orders : "places"
    users ||--o{ reviews : "writes"
    categories ||--o{ products : "contains"
    products ||--o{ reviews : "has"
```

### Penjelasan Tabel

| Entity | Deskripsi | Relasi Utama |
|:-------|:----------|:-------------|
| **users** | Data pengguna/pelanggan terdaftar | Has many Orders, Reviews |
| **categories** | Kategori produk | Has many Products |
| **products** | Data produk yang dijual | Belongs to Category, Has many Reviews |
| **orders** | Data pesanan pelanggan | Belongs to User (nullable untuk guest) |
| **reviews** | Ulasan produk dari pelanggan | Belongs to Product, User |
| **articles** | Konten artikel/blog | Standalone |
| **settings** | Konfigurasi sistem (key-value) | Standalone |

---

## 📌 Catatan Teknis

1. **Guest Checkout**: Kolom `user_id` pada tabel `orders` bersifat nullable untuk mendukung pembelian tanpa login.
2. **Tracking Token**: Setiap pesanan memiliki `tracking_token` unik yang di-hash untuk keamanan pelacakan.
3. **Soft Status**: Status pesanan menggunakan enum: `pending`, `processing`, `shipped`, `completed`, `cancelled`.
4. **JSON Storage**: Kolom `items` pada orders menyimpan snapshot produk saat checkout dalam format JSON.

---

<p align="center">
  <em>Dokumentasi ini dibuat untuk keperluan akademis (Tugas Akhir/Skripsi)</em>
</p>
