# CMS Platform (Modular Page & Service Management System)

## 1. Overview

This CMS is a **modular, database-driven content and service management platform** designed to power modern, scalable websites.

It enables:
- Dynamic page creation using reusable sections
- Structured content management (instead of static pages/PDFs)
- Integration of interactive components (Vue-powered)
- Extension into full digital service delivery (forms, workflows, user interaction)

The system is built to support **content-heavy and service-oriented platforms**, such as government, regulatory, and enterprise websites.

---

## 2. Technology Stack

### Backend
- **Laravel 10+**
- RESTful API architecture
- Eloquent ORM
- MySQL / MariaDB

### Frontend (Admin Dashboard)
- **Vue 3**
- **Inertia.js**
- Component-based UI architecture

### Frontend (Public Website)
- **Blade (Laravel templating engine)** for rendering pages
- **Vue 3 (progressive enhancement)** for interactive components:
    - Search
    - Forms
    - Filters
    - Dynamic UI blocks

### Build Tools
- **Vite**
- ES Modules

---

## 3. Core Features

### Page & Section Builder
- Create pages dynamically
- Add multiple sections per page
- Supported section types:
    - Hero
    - Services
    - Content blocks
    - FAQs
    - Lists / grids

---

### Content Management
- Structured content (not static HTML or PDFs)
- Reusable components
- Centralized content editing

---

### Modular Architecture
Easily extendable modules:
- Documents
- Posts (News, Notices)
- Services (Licensing, Complaints)
- Forms & workflows
- Events
- Tenders

---

### Vue-Powered Interactive Components
- Embedded inside Blade templates
- Supports:
    - Live search
    - Dynamic forms
    - Filters and pagination

---

## 4. Installation

### Requirements
- PHP >= 8.1
- Composer
- Node.js >= 18
- MySQL / MariaDB

---

### Steps

#### 1. Clone Repository
```bash
git clone <repository-url>
cd <project-folder>
