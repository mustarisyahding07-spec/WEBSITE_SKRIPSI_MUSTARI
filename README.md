# Ivo Karya E-Commerce Platform 🚀
> *Bridging the gap between production capacity and digital sales performance.*

![Project Banner](https://img.shields.io/badge/Status-Active_Development-success?style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament_v3-F2C14E?style=for-the-badge&logo=laravel&logoColor=black)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

## 📖 Introduction

**Ivo Karya E-Commerce** is a premium digital platform designed to solve a critical business challenge for UMKM in Sidenreng Rappang. Specifically, it addresses the disparity between the **50kg production capacity** of Abon Ikan/Sapi and the current **37kg sales volume**.

By transitioning from conventional sales to a high-end, automated digital storefront, this project aims to maximize market reach, streamline operations, and elevate the *Ivo Karya* brand image to a premium level.

**Developer**: Mustari Syahding (KS1122016)  
**Institution**: Universitas Ichsan Sidenreng Rappang

---

## 🛠 Tech Stack Detail

Built on the robust **TALL Stack** ecosystem, ensuring performance, scalability, and developer happiness.

| Component | Technology | Description |
| :--- | :--- | :--- |
| **Backend Framework** | **Laravel 11** (PHP 8.2+) | The foundation. Robust, secure, and modern MVC architecture. |
| **Admin Panel** | **Filament PHP v3** | A beautiful, full-featured admin panel for managing orders, products, and settings. |
| **Frontend** | **Blade + Tailwind CSS + Alpine.js** | A "Scroll-driven" immersive experience inspired by Apple's product landing pages. |
| **Database** | **MySQL** | Reliable relational database for structured order and product data. |
| **Automation** | **Fonnte API** | WhatsApp Gateway integration for true automation of billing and notifications. |

---

## ✅ Core Features

### 🍎 Apple-Style UX & Storytelling
The public-facing website deviates from traditional flat e-commerce layouts. It employs **scroll-driven animations**, glassmorphism (glass cards), and high-resolution imagery to narrate the quality of "Abon Ikan Ivo Karya."

### 🤖 True Automation (Fonnte Integration)
Manual message sending is a thing of the past.
- **Auto-Billing**: When an order is placed, a WhatsApp message with payment details is instantly sent to the customer.
- **Shipping Updates**: When status changes to `Shipped`, the receipt number (Resi) and tracking link are auto-sent.

### 📊 Filament Analytics Dashboard
Data-driven decision making.
- **Real vs Target Charts**: Visual comparison of actual sales against the 50kg production target.
- **Revenue Trends**: Monthly performance visualization.
- **Order Status Distribution**: Quick insight into pending vs completed orders.
- **Customer Growth**: Tracking new user acquisitions over time.

### 🔒 Privacy & Security features
- **Hidden Admin Entry**: No unsightly "Admin Login" buttons on the header. The access point is discreetly placed in the footer to maintain brand exclusivity.
- **Dynamic Settings**: Admin can update WhatsApp numbers and Bank Account info directly from the dashboard without touching code.
- **Secure Tracking**: Orders are tracked via a unique, hashed `tracking_token`, ensuring customers can only see their own orders.

---

## 🛠 Future Roadmap (In Development)

| Feature | Status | Description |
| :--- | :--- | :--- |
| **RajaOngkir Integration** | 📝 Planned | Automatic shipping cost calculation based on weight and location. |
| **Export Reports** | 📝 Planned | One-click generation of PDF/Excel monthly sales reports for business accounting. |
| **Inventory Prediction** | 🧠 AI Concept | AI-driven alerts predicting stock depletion based on sales velocity. |
| **PWA Support** | 📱 Planned | Enabling "Add to Home Screen" functionality for a native app-like experience. |

---

## ⚙️ Installation Guide

Follow these steps to set up the project locally.

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL Server

### Steps

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/username/website-ivo-karya.git
    cd website-ivo-karya
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup**
    Copy the `.env.example` file to `.env` and configure your database and Fonnte credentials.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Note: Ensure you set `FONNTE_TOKEN` in your `.env` or via the Admin Dashboard Settings.*

4.  **Database Migration & Seeding**
    ```bash
    php artisan migrate --seed
    ```

5.  **Run Development Servers**
    Open two terminal tabs:
    ```bash
    # Terminal 1 (Backend)
    php artisan serve

    # Terminal 2 (Frontend Assets)
    npm run dev
    ```

---

## 🏗 Project Architecture

The system follows the standard **MVC (Model-View-Controller)** pattern enforced by Laravel.

- **Services**: `FonnteService` abstracts the WhatsApp API logic, keeping Controllers clean.
- **Filament Resources**: Admin logic is encapsulated within `app/Filament/`, separating back-office operations from the public frontend.
- **Livewire Components**: Used for dynamic frontend elements like the **Product Review System** and **Cart Modal**.

---

<p align="center">
  <br>
  Built with ❤️ by <strong>Mustari Syahding</strong> for <strong>UMKM Indonesia</strong>.
</p>
