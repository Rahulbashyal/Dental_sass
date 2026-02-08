# 🦷 Dental Care SaaS Platform

**A Modern, Multi-Tenant Clinic Management System**

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4.svg)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.4-38B2AC.svg)](https://tailwindcss.com)

## 📖 Overview

The **Dental Care SaaS Platform** is a comprehensive, cloud-native solution designed to streamline the operations of dental clinics. Built on a multi-tenant architecture, it empowers multiple clinics to manage their practice securely and independently within a single hosted environment. From patient scheduling and electronic medical records (EMR) to complex financial accounting and automated marketing, this platform provides a 360-degree ecosystem for modern dental care.

Key benefits include:
- **Centralized Management:** Superadmins can oversee entire networks of clinics.
- **Role-Based Access:** Dedicated dashboards for Dentists, Receptionists, Accountants, and Admins.
- **Localization:** Integrated support for local calendars (Nepali Date Picker) and currency formatting.
- **Scalability:** Built to scale from single practitioners to large dental chains.

---

## 🏗 System Architecture & Outline

The system is structured around key functional modules, each serving a specific aspect of clinic management.

### 1. Multi-Tenancy & User Management
*   **Clinic Isolation:** Each clinic has its own database scope, ensuring data privacy and security.
*   **Role-Based Access Control (RBAC):**
    *   **superadmin:** Full system control, clinic onboarding, subscription management.
    *   **clinic_admin:** Manages specific clinic settings, staff, and services.
    *   **dentist:** Patient care, treatment plans, prescriptions, and appointment management.
    *   **receptionist:** Front-desk operations, patient registration, check-in/out, billing.
    *   **accountant:** Financial tracking, expense management, and reporting.

### 2. Patient Care Module
*   **Electronic Health Records (EHR):** Comprehensive patient profiles including medical history, allergies, and past treatments.
*   **Prescription Management:** Digital prescription writing with an integrated medication database.
*   **Treatment Plans:** Create detailed treatment proposals with cost estimations and phase tracking.
*   **Notes & Documents:** Secure storage for X-rays, consent forms, and clinical notes.

### 3. Appointment & Scheduling
*   **Smart Calendar:** Drag-and-drop appointment scheduling with real-time availability checks.
*   **Recurring Appointments:** Support for follow-up visits and ongoing treatment schedules.
*   **Waitlist Management:** Efficiently manage patient queues and cancellations.
*   **Notifications:** Automated SMS/Email reminders for upcoming appointments.
*   **Nepali Date Integration:** Specialized support for local date formats alongside standard Gregorian dates.

### 4. Financial & Accounting Module
*   **Invoicing & Billing:** Generate compliant invoices, track payments, and manage due balances.
*   **Double-Entry Accounting:** Full general ledger, chart of accounts, and journal entry capabilities standard.
*   **Financial Reporting:** Real-time generation of Profit & Loss statements, revenue reports, and expense breakdowns.
*   **Multi-Currency Support:** Handles varied pricing structures and currency formats.

### 5. CRM & Marketing Suite
*   **Lead Management:** Track prospective patients from inquiry to first visit.
*   **Campaign Management:** Create and track marketing campaigns (Email/SMS) to engage patients.
*   **Content Management System (CMS):**
    *   **Landing Page Builder:** customize clinic public-facing websites.
    *   **Service & Team Management:** showcase clinic services and staff profiles.
    *   **Blog & Testimonials:** publish health tips and patient success stories.

### 6. Inventory & Staff Management
*   **Staff Profiles:** Manage employment details, schedules, and performance.
*   **Inventory Tracking:** (Feature Roadmap) Monitor stock levels for dental supplies and medications.

---

## 🛠 Technology Stack

*   **Backend:** PHP 8.2, Laravel 11 Framework
*   **Frontend:** Blade Templates, Tailwind CSS v3, Alpine.js
*   **Database:** MySQL / MariaDB
*   **Build Tool:** Vite
*   **PDF Generation:** `barryvdh/laravel-dompdf`
*   **Media Management:** `spatie/laravel-medialibrary`
*   **Security:** `pragmarx/google2fa-laravel` (2FA), Custom Security Middleware

---

## 🚀 Getting Started

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL Database

### Installation

1.  **Clone the repository**
    ```bash
    git clone https://github.com/Rahulbashyal/Dental_sass.git
    cd Dental_sass
    ```

2.  **Install PHP dependencies**
    ```bash
    composer install
    ```

3.  **Install Frontend dependencies**
    ```bash
    npm install
    ```

4.  **Environment Setup**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Edit `.env` to configure your database connection.*

5.  **Database Migration & Seeding**
    ```bash
    php artisan migrate
    php artisan db:seed
    ```
    *This will set up the database structure and populate it with initial roles and permissions.*

6.  **Build Assets**
    ```bash
    npm run build
    ```

7.  **Run Local Server**
    ```bash
    php artisan serve
    ```

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
