# 🦷 Dental Care SaaS Platform

> **Modernize Your Dental Practice.**  
> A powerful, multi-tenant cloud solution for clinics of all sizes.

[![License](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Vite](https://img.shields.io/badge/Vite-5.0-646CFF?style=flat-square&logo=vite)](https://vitejs.dev)
[![Tailwind](https://img.shields.io/badge/Tailwind-3.4-38B2AC?style=flat-square&logo=tailwind-css)](https://tailwindcss.com)

---

## ⚡ Features at a Glance

| 🏢 Multi-Tenancy | 🩺 Patient Care | 💰 Smart Finance |
| :--- | :--- | :--- |
| • **Clinic Isolation** (Data Privacy)<br>• **RBAC** (Granular Roles)<br>• **Scalable Architecture** | • **Digital EHR** & History<br>• **Treatment Plans**<br>• **E-Prescriptions** | • **Invoicing & Billing**<br>• **Double-Entry Accounting**<br>• **Profit/Loss Reports** |

| 📅 Smart Scheduling | 📣 Growth & CRM | 🔒 Enterprise Security |
| :--- | :--- | :--- |
| • **Drag-n-Drop Calendar**<br>• **Waitlist Management**<br>• **SMS/Email Reminders** | • **Lead Tracking Pipeline**<br>• **Marketing Campaigns**<br>• **Patient Portal** | • **2FA Authentication**<br>• **Audit Logs**<br>• **Secure API Headers** |

---

## 👥 Roles & Access

Our system is built around **5 Key Personas**, giving everyone the exact tools they need:

- 👮 **Superadmin** – The "God Mode". Manage subscriptions, onboard clinics, and oversee the entire SaaS network.
- 🏥 **Clinic Admin** – The Boss of the Branch. Manage staff, services, and high-level clinic settings.
- 👨‍⚕️ **Dentist** – Critical Care. Manage appointments, write prescriptions, and update patient medical records.
- 👩‍💼 **Receptionist** – Front Desk Hero. Handle check-ins, billing, and the daily appointment flow.
- 📊 **Accountant** – The Money Keeper. Track expenses, manage the ledger, and generate financial health reports.

---

## 🏗 Tech Stack

We use the best-in-class open source technologies for speed, security, and developer happiness.

- **Backend:** PHP 8.2 • Laravel 11.x
- **Frontend:** Blade • Alpine.js • Tailwind CSS • Livewire
- **Database:** MySQL 8.0 / MariaDB
- **Tools:** Vite (Build) • Pest (Testing) • Pint (Style)

---

## 🚀 Quick Start Guide

**1. Clone & Install**
```bash
git clone https://github.com/Rahulbashyal/Dental_sass.git
cd Dental_sass
composer install && npm install
```

**2. Configure Environment**
```bash
cp .env.example .env
php artisan key:generate
# (Don't forget to set your DB credentials in .env!)
```

**3. Ignite! 🔥**
```bash
php artisan migrate --seed  # Sets up DB & Fake Data
npm run build               # Compiles Assets
php artisan serve           # Launches Server
```

Visit `http://localhost:8000` and login with the default credentials found in `database/seeders/UserSeeder.php` (or check the console output after seeding).

---

## 🤝 Contributing

We welcome contributions! Please see our [CONTRIBUTING.md](CONTRIBUTING.md) for details.

1.  Fork the Project
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

---

<p align="center">
  <sub>Built with ❤️ by the Dental Care Team. Licensed under MIT.</sub>
</p>
