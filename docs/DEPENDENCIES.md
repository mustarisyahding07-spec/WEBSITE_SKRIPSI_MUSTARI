# 📦 Dokumentasi Dependencies Ivo Karya

> **Daftar Lengkap Library dan Package yang Digunakan**

---

## 📋 Daftar Isi

1. [Ringkasan Dependencies](#ringkasan-dependencies)
2. [Backend Dependencies](#backend-dependencies-php)
3. [Frontend Dependencies](#frontend-dependencies-npm)
4. [Penjelasan Detail](#penjelasan-detail)
5. [Security Considerations](#security-considerations)
6. [Catatan Kompatibilitas](#catatan-kompatibilitas)

---

## Ringkasan Dependencies

| Kategori | Backend (Composer) | Frontend (NPM) |
|:---------|:------------------:|:--------------:|
| **Core Framework** | 3 | 2 |
| **Admin Panel** | 1 | - |
| **Styling & UI** | - | 3 |
| **Development Tools** | 6 | 4 |
| **Total Packages** | **10** | **9** |

---

## Backend Dependencies (PHP)

### 🏗️ Core Framework

| Package | Versi | Fungsi Utama |
|:--------|:------|:-------------|
| `php` | ^8.2 | Runtime PHP minimum |
| `laravel/framework` | ^11.0 | Framework utama aplikasi |
| `laravel/tinker` | ^2.9 | REPL untuk debugging |

### 🔧 Admin Panel

| Package | Versi | Fungsi Utama |
|:--------|:------|:-------------|
| `filament/filament` | ^3.2 | Admin panel dengan TALL stack |

### 🛠️ Development Dependencies

| Package | Versi | Kategori | Fungsi Utama |
|:--------|:------|:---------|:-------------|
| `fakerphp/faker` | ^1.23 | Testing | Generate fake data untuk seeding |
| `laravel/breeze` | ^2.0 | Auth | Starter kit autentikasi |
| `laravel/pail` | ^1.1 | Logging | Real-time log viewer |
| `laravel/pint` | ^1.13 | Linting | PHP code style fixer |
| `laravel/sail` | ^1.26 | Docker | Development environment |
| `mockery/mockery` | ^1.6 | Testing | Mocking framework |
| `nunomaduro/collision` | ^8.1 | CLI | Beautiful error reporting |
| `phpunit/phpunit` | ^11.0.1 | Testing | Unit testing framework |

---

## Frontend Dependencies (NPM)

### ⚛️ Core

| Package | Versi | Fungsi Utama |
|:--------|:------|:-------------|
| `alpinejs` | ^3.4.2 | JavaScript reaktif ringan |
| `axios` | ^1.11.0 | HTTP client untuk AJAX |

### 🎨 Styling & UI

| Package | Versi | Fungsi Utama |
|:--------|:------|:-------------|
| `tailwindcss` | ^3.1.0 | Utility-first CSS framework |
| `@tailwindcss/forms` | ^0.5.2 | Form styling plugin |
| `@tailwindcss/vite` | ^4.0.0 | Tailwind Vite integration |
| `autoprefixer` | ^10.4.2 | CSS vendor prefixes |
| `postcss` | ^8.4.31 | CSS processing |

### 🛠️ Build Tools

| Package | Versi | Fungsi Utama |
|:--------|:------|:-------------|
| `vite` | ^7.0.7 | Frontend build tool |
| `laravel-vite-plugin` | ^2.0.0 | Laravel Vite integration |
| `concurrently` | ^9.0.1 | Run multiple processes |

---

## Penjelasan Detail

### Laravel Framework (`laravel/framework`)

**Fungsi:**
Framework PHP full-stack yang menyediakan:
- Routing & middleware
- Eloquent ORM untuk database
- Blade templating engine
- Authentication & authorization
- Queue & job processing
- Event & broadcasting

**Mengapa Dipilih:**
- Ekosistem mature dan dokumentasi lengkap
- Active community dan long-term support
- Convention over configuration
- Built-in security features

**Penggunaan dalam Project:**
```php
// Routing
Route::get('/katalog', [HomeController::class, 'catalog']);

// Eloquent ORM
$products = Product::with('category')->where('stock', '>', 0)->get();

// Blade templating
@foreach($products as $product)
    <div>{{ $product->name }}</div>
@endforeach
```

---

### Filament (`filament/filament`)

**Fungsi:**
Admin panel builder dengan komponen TALL stack (Tailwind, Alpine, Laravel, Livewire).

**Mengapa Dipilih:**
- Rapid development admin panel
- Built-in CRUD resources
- Extensible widgets & charts
- Modern UI out of the box

**Penggunaan dalam Project:**
- `ProductResource` - Manajemen produk
- `OrderResource` - Manajemen pesanan
- `CategoryResource` - Manajemen kategori
- Dashboard widgets untuk statistik

---

### Alpine.js (`alpinejs`)

**Fungsi:**
Micro JavaScript framework untuk interaktivitas frontend.

**Mengapa Dipilih:**
- Ringan (< 15KB)
- Declarative syntax seperti Vue
- Tidak perlu build step
- Perfect complement untuk Blade

**Penggunaan dalam Project:**
```html
<!-- Cart modal -->
<div x-data="{ open: false }">
    <button @click="open = true">Open Cart</button>
    <div x-show="open" x-transition>
        <!-- Cart content -->
    </div>
</div>

<!-- Location picker -->
<div x-data="locationPicker()">
    <input x-model="postalCode" @change="searchCity()">
</div>
```

---

### Tailwind CSS (`tailwindcss`)

**Fungsi:**
Utility-first CSS framework untuk styling.

**Mengapa Dipilih:**
- Rapid UI development
- Highly customizable
- Small production bundle (purged CSS)
- Responsive by default

**Penggunaan dalam Project:**
```html
<!-- Responsive product card -->
<div class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition">
    <img class="w-full h-48 object-cover rounded-lg" src="...">
    <h3 class="text-lg font-bold mt-2">Product Name</h3>
    <p class="text-brand-600 font-bold">Rp 75.000</p>
</div>
```

---

### Vite (`vite`)

**Fungsi:**
Build tool modern untuk frontend assets.

**Mengapa Dipilih:**
- Instant server start (no bundling in dev)
- Hot Module Replacement (HMR)
- Optimized production build
- Native ES modules support

**Penggunaan dalam Project:**
```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

---

### Laravel Breeze (`laravel/breeze`)

**Fungsi:**
Starter kit untuk authentication.

**Mengapa Dipilih:**
- Simple, minimal implementation
- Easy to customize
- Includes login, register, password reset
- Blade views out of the box

**Fitur yang Digunakan:**
- Login & Register pages
- Password reset flow
- Email verification
- Remember me functionality

---

## Security Considerations

### Package dengan Akses Sensitif

| Package | Akses | Rekomendasi |
|:--------|:------|:------------|
| `laravel/framework` | Database, Files, Network | Keep updated |
| `filament/filament` | Admin data, Auth | Restrict admin access |
| `axios` | HTTP requests | Validate CORS |

### Update Strategy

```bash
# Check outdated packages
composer outdated
npm outdated

# Update dengan minor versions
composer update --with-dependencies
npm update

# Security audit
composer audit
npm audit
```

### Vulnerability Monitoring

| Tool | Command | Frequency |
|:-----|:--------|:----------|
| **Composer Audit** | `composer audit` | Weekly |
| **NPM Audit** | `npm audit` | Weekly |
| **Dependabot** | GitHub integration | Auto |

---

## Catatan Kompatibilitas

### Minimum Requirements

| Requirement | Version | Notes |
|:------------|:--------|:------|
| **PHP** | 8.2+ | Required by Laravel 11 |
| **Node.js** | 18+ | Required by Vite 5+ |
| **NPM** | 8+ | Comes with Node.js |
| **MySQL** | 8.0+ | Recommended |
| **Composer** | 2.0+ | Package manager |

### Known Issues

<details>
<summary><strong>⚠️ Laravel Pail on Windows</strong></summary>

**Issue:** `laravel/pail` memerlukan `pcntl` extension yang tidak tersedia di Windows.

**Solution:** Hapus dari scripts di `composer.json` atau gunakan WSL.

```json
// composer.json - Removed pail from dev script
"dev": [
    "Composer\\Config::disableProcessTimeout",
    "npx concurrently \"php artisan serve\" \"npm run dev\""
]
```
</details>

<details>
<summary><strong>⚠️ Tailwind v4 Breaking Changes</strong></summary>

**Issue:** `@tailwindcss/vite` v4.0.0 memiliki breaking changes.

**Solution:** Pastikan menggunakan konfigurasi yang sesuai dengan v4.

```javascript
// tailwind.config.js untuk v4
// Configuration might differ from v3
```
</details>

<details>
<summary><strong>⚠️ Vite HMR Port Conflict</strong></summary>

**Issue:** Vite HMR port (5173) mungkin konflik dengan aplikasi lain.

**Solution:** Ubah port di vite.config.js

```javascript
export default defineConfig({
    server: {
        port: 5174,
        hmr: {
            port: 5174
        }
    }
});
```
</details>

---

## 📊 Dependency Tree Overview

```
website-ivo-karya/
├── composer.json (PHP Dependencies)
│   ├── require
│   │   ├── php ^8.2
│   │   ├── laravel/framework ^11.0
│   │   ├── laravel/tinker ^2.9
│   │   └── filament/filament ^3.2
│   │
│   └── require-dev
│       ├── fakerphp/faker ^1.23
│       ├── laravel/breeze ^2.0
│       ├── laravel/pail ^1.1
│       ├── laravel/pint ^1.13
│       ├── laravel/sail ^1.26
│       ├── mockery/mockery ^1.6
│       ├── nunomaduro/collision ^8.1
│       └── phpunit/phpunit ^11.0.1
│
└── package.json (NPM Dependencies)
    └── devDependencies
        ├── @tailwindcss/forms ^0.5.2
        ├── @tailwindcss/vite ^4.0.0
        ├── alpinejs ^3.4.2
        ├── autoprefixer ^10.4.2
        ├── axios ^1.11.0
        ├── concurrently ^9.0.1
        ├── laravel-vite-plugin ^2.0.0
        ├── postcss ^8.4.31
        ├── tailwindcss ^3.1.0
        └── vite ^7.0.7
```

---

*Dokumentasi ini dibuat untuk keperluan Tugas Akhir/Skripsi*  
**Universitas Ichsan Sidenreng Rappang** © 2026
