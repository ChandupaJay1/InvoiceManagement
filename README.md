<p align="center">
  <a href="https://nirmanamask-sri-lanka.free.nf" target="_blank">
    <img src="public/icon.png" width="100" alt="Invoice Manager Logo" />
  </a>
</p>

# Invoice Manager

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12" />
    <img src="https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
    <img src="https://img.shields.io/badge/Alpine.js-3.0-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js" />
    <img src="https://img.shields.io/badge/PWA-Supported-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white" alt="PWA Ready" />
</p>

## 🚀 About The Project

**Invoice Manager** is a robust, web-based invoice management system built with Laravel. It streamlines the billing process for businesses, offering role-based access for Administrators and Staff.

The application is fully optimized as a **Progressive Web App (PWA)**, allowing users to install it on their devices and access core features even with limited connectivity.

### ✨ Key Features

*   **Role-Based Authentication**: Secure login for Admins and regular Users.
*   **Intuitive Dashboard**:
    *   **Admin**: View total users, total invoices, stole utilization capacity, and total collection.
    *   **User**: Track personal daily and monthly sales.
*   **Invoice Management**:
    *   Create professional invoices with dynamic item selection.
    *   Automatic "Stole" (Item) tracking to prevent double-booking.
    *   Print-ready invoice views optimized for thermal printers (80mm).
*   **Reports & Collections**:
    *   Daily, Monthly, and Lifetime collection reports per user.
    *   Monthly aggregate reports for administrative insight.
*   **Data Safety**:
    *   Admin capability to backup invoices to CSV.
    *   Restore functionality from backups.
*   **PWA Ready**: Installable on mobile and desktop with offline caching capabilities.

## 🛠️ Technology Stack

*   **Backend**: Laravel 12.0 (PHP 8.2+)
*   **Frontend**: Blade Templates, Tailwind CSS 4.0, Alpine.js
*   **Database**: MySQL
*   **Tools**: Vite, Composer, NPM

## 📸 Screenshots

<!-- Add your screenshots here -->
| Admin Dashboard | Create Invoice |
|:---:|:---:|
| <img src="https://via.placeholder.com/600x400?text=Admin+Dashboard" width="400" /> | <img src="https://via.placeholder.com/600x400?text=Create+Invoice" width="400" /> |

| Invoice Print View | Mobile PWA |
|:---:|:---:|
| <img src="https://via.placeholder.com/600x400?text=Print+View" width="400" /> | <img src="https://via.placeholder.com/600x400?text=Mobile+View" width="400" /> |

## ⚙️ Installation

1.  **Clone the repository**
    ```bash
    git clone https://github.com/ChandupaJay1/InvoiceManagement.git
    cd InvoiceManagement
    ```

2.  **Install PHP dependencies**
    ```bash
    composer install
    ```

3.  **Install Node.js dependencies**
    ```bash
    npm install
    ```

4.  **Environment Configuration**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Update `.env` with your database credentials.*

5.  **Run Migrations**
    ```bash
    php artisan migrate
    ```

6.  **Build Assets**
    ```bash
    npm run build
    ```

7.  **Serve Application**
    ```bash
    php artisan serve
    ```

## 📱 PWA Setup (For Production)

To enable PWA features on a production server:

1.  Ensure your site is served over **HTTPS**.
2.  The `manifest.json` and `sw.js` are located in the `public/` directory.
3.  Browser caching is handled automatically by the service worker.

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

## 👨‍💻 Author

**NerdTech Labs**
<br />
<a href="https://www.facebook.com/nerdtechlabs/">Facebook</a>
<br />
&copy; 2026 All Rights Reserved.
