# 🤖 PC Master Builder
### > High-Performance Hardware Configurator & AI Compatibility Assistant

![System Status](https://img.shields.io/badge/SYSTEM-STABLE-39FF14?style=for-the-badge&logo=laravel&logoColor=white)
![Build Version](https://img.shields.io/badge/VERSION-1.0.42--BETA-00F0FF?style=for-the-badge)
![AI Core](https://img.shields.io/badge/AI_CORE-GEMINI--1.5--FLASH-BD00FF?style=for-the-badge)

**PC Master Builder** is a cutting-edge, full-stack hardware assembly platform. It moves away from generic configurators by integrating a deep **Cyberpunk / Tactical** interface with the power of **Google Gemini AI** to validate component compatibility in real-time.

---

## ⚡ Core Features

### 🛠️ Advanced Constructor
Build your dream rig with a highly interactive interface. Manage quantities, track total wattage, and see the subtotal update as you assemble.

### 🧠 Gemini AI Compatibility Core
Don't guess if your CPU fits your Motherboard. Our integrated AI analyzes:
*   **Socket Matching:** (LGA1700, AM5, etc.)
*   **TDP & Power:** Real-time analysis of your PSU capacity.
*   **RAM Architecture:** Speed and type (DDR4/DDR5) validation.
*   **Form Factor:** Gabinete vs. GPU dimensions and Motherboard size.

### 📊 Tactical Dashboard
*   **Admin:** Monitor sales performance, inventory levels, and popular components through customized neon charts.
*   **User:** Manage your collection of saved blueprints and re-scan compatibility anytime.

### 📄 Professional Blueprints (PDF)
Generate high-fidelity, blueprint-style PDF quotes with full pricing breakdowns, ready for printing or digital sharing.

---

## 🛠️ Technical Stack

*   **Framework:** Laravel 11 (Architecture MVC)
*   **Frontend:** Blade + Tailwind CSS v4 (Custom "Neon Rig" Design System)
*   **Database:** MySQL 8 (Hierarchical Categories + JSON Specs)
*   **Real-time:** Alpine.js
*   **AI Integration:** Google Gemini API (gemini-1.5-flash)
*   **PDF Core:** DomPDF
*   **Auth:** Laravel Breeze + Spatie Roles & Permissions

---

## 📂 Database Architecture

The system utilizes a specialized schema designed for hardware management:
*   **Hierarchical Categories:** Allows for complex nesting (e.g., Almacenamiento > SSD NVMe).
*   **JSON Specification Engine:** Stores variable hardware attributes (TDP, Sockets, Speeds) without rigid schema constraints.
*   **Quote-Component Pivot:** Captures historical pricing and quantities at the moment of assembly.

---

## 🚀 Installation & Deployment

### 1. Clone the repository
```bash
git clone https://github.com/your-user/MasterBuilder.git
cd MasterBuilder
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```
> **IMPORTANT:** Ensure you add your `GEMINI_API_KEY` to the `.env` file for AI features.

### 4. Database Setup
```bash
php artisan migrate:fresh --seed
```

### 5. Launch System
```bash
npm run dev
# In another terminal
php artisan serve
```

---

## 🦾 Design System: "The Neon Rig"
The UI is built on a custom design system defined in `app.css`:
*   **Background:** Deep Abyss (#050505) with technical grid patterns.
*   **Accents:** Cyber Cyan (#00F0FF) & Neon Magenta (#BD00FF).
*   **Typography:** Rajdhani (Headers) & JetBrains Mono (Data).
*   **Shapes:** Custom chamfered corners using CSS clip-paths.

---

> **Status:** Developed for *Aplicaciones Web Interactivas* // Ciclo 25/26-II
