# 🚀 Muhammad Ali — Laravel Portfolio

A full-featured professional portfolio built with **Laravel**, dark premium design, admin panel, and full CMS.

---

## 📁 Project Structure

```
portfolio/
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php
│   │   ├── ProjectController.php
│   │   ├── BlogController.php
│   │   ├── ContactController.php
│   │   └── AdminController.php
│   └── Models/
│       ├── Project.php
│       ├── BlogPost.php
│       ├── ContactMessage.php
│       ├── Testimonial.php
│       └── Setting.php
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php         ← Public layout
│   │   └── admin.blade.php       ← Admin layout
│   ├── home/
│   │   └── index.blade.php       ← Homepage (all sections)
│   ├── projects/
│   │   ├── index.blade.php       ← All projects page
│   │   └── show.blade.php        ← Single project detail
│   ├── blog/
│   │   ├── index.blade.php       ← Blog listing
│   │   └── show.blade.php        ← Single post
│   └── admin/
│       ├── dashboard.blade.php
│       ├── settings.blade.php    ← Profile photo + settings
│       ├── messages.blade.php
│       ├── testimonials.blade.php
│       ├── projects/
│       │   ├── index.blade.php
│       │   └── form.blade.php    ← Create/Edit with image upload
│       └── blog/
│           ├── index.blade.php
│           └── form.blade.php
├── public/
│   ├── css/app.css               ← All styles
│   └── js/app.js                 ← All JavaScript
├── routes/web.php
└── database/migrations/
```

---

## ⚙️ Installation

### 1. Create a new Laravel project and copy these files

```bash
composer create-project laravel/laravel amar-portfolio
cd amar-portfolio
# Copy all files from this zip into the project
```

### 2. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```
DB_DATABASE=portfolio
DB_USERNAME=root
DB_PASSWORD=yourpassword

APP_NAME="Muhammad Ali Portfolio"
APP_URL=http://localhost
```

### 3. Create database & run migrations

```bash
mysql -u root -p -e "CREATE DATABASE portfolio;"
php artisan migrate
```

### 4. Create storage link (for uploaded images)

```bash
php artisan storage:link
```

### 5. Create admin user

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name'     => 'Muhammad Ali',
    'email'    => 'amarjafri1472@gmail.com',
    'password' => bcrypt('your-secure-password'),
]);
```

### 6. Seed with demo projects (optional)

```bash
php artisan db:seed --class=PortfolioSeeder
```

### 7. Run the development server

```bash
php artisan serve
```

Visit: http://localhost:8000
Admin: http://localhost:8000/admin/login

---

## 🎛️ Admin Panel Features

| Feature           | Description                                    |
| ----------------- | ---------------------------------------------- |
| **Profile Photo** | Upload your photo — appears on hero section    |
| **Settings**      | Name, tagline, bio, email, phone, social links |
| **Projects**      | Full CRUD with main image + gallery upload     |
| **Blog**          | Create/edit posts with featured image          |
| **Testimonials**  | Add client reviews with avatar + rating        |
| **Messages**      | View all contact form submissions              |

---

## 🌟 Pages & Features

- **Homepage** — Hero with photo, About, Experience Timeline, Featured Projects, Skills, Testimonials, Blog Preview, Contact Form
- **All Projects** — Filterable by category with pagination
- **Project Detail** — Full description, gallery lightbox, tech stack, related projects
- **Blog** — Featured post, filterable grid, pagination
- **Contact Form** — Saved to DB, sends email notification

---

## 🚀 Deployment (cPanel / VPS)

1. Upload files to `public_html` or subdirectory
2. Point document root to `public/`
3. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
4. Run: `composer install --no-dev && php artisan migrate --force`
5. Run: `php artisan storage:link`
6. Set folder permissions: `storage/` and `bootstrap/cache/` → `755`

---

## 📧 Contact Form Email Setup

In `.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=amarjafri1472@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=amarjafri1472@gmail.com
MAIL_FROM_NAME="Muhammad Ali Portfolio"
```

---

Built with ❤️ using Laravel · Lahore, Pakistan
