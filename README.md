# NCS Laboratory Website

Website resmi **Network & Cyber Security Laboratory** - Pusat Riset Keamanan Siber.

## 🛡️ Tentang

Website ini dirancang untuk menampilkan informasi, kegiatan, dan layanan laboratorium Network & Cyber Security mengikuti format ITS Cybersecurity Research Center.

## 🚀 Teknologi

- **Backend:** PHP Native
- **Database:** PostgreSQL
- **Frontend:** TailwindCSS (via CDN)
- **Icons:** Font Awesome
- **Fonts:** Orbitron, JetBrains Mono

## 📁 Struktur Folder

```
NCS/
├── admin/                  # Panel administrasi
│   ├── includes/          # Header, sidebar, footer admin
│   ├── pages/             # Halaman CRUD admin
│   └── index.php          # Entry point admin
├── config/                # Konfigurasi aplikasi
│   ├── app.php           # Pengaturan aplikasi
│   └── database.php      # Koneksi database
├── includes/              # Komponen yang dapat digunakan ulang
│   ├── header.php        # HTML head
│   ├── footer.php        # Footer dengan contact form
│   ├── navbar.php        # Navigasi utama
│   └── functions.php     # Helper functions
├── pages/                 # Halaman publik
│   ├── beranda.php       # Landing page
│   ├── visi-misi.php     # Visi & Misi
│   ├── logo.php          # Logo & Branding
│   ├── struktur.php      # Struktur Organisasi
│   ├── agenda.php        # Agenda kegiatan
│   ├── galeri.php        # Galeri foto/video
│   ├── penelitian.php    # Arsip penelitian (PDF)
│   ├── pengabdian.php    # Arsip pengabdian (PDF)
│   ├── sarana.php        # Sarana & Prasarana
│   ├── konsultatif.php   # Layanan konsultatif
│   └── link.php          # Link eksternal
├── public/               # Dokumen root web server
│   ├── index.php         # Entry point aplikasi
│   ├── uploads/          # File yang diupload
│   └── assets/           # CSS, JS, images
├── sql/                  # Database schema
│   └── schema.sql        # SQL untuk membuat tabel
├── .env                  # Environment variables
├── .env.example          # Contoh environment variables
└── README.md             # Dokumentasi
```

## ⚙️ Instalasi

### 1. Clone atau download repository

```bash
cd /path/to/webserver/htdocs
git clone <repository-url> NCS
```

### 2. Konfigurasi Environment

Copy file `.env.example` ke `.env` dan sesuaikan:

```bash
cp .env.example .env
```

Edit `.env` dengan kredensial database Anda:

```env
DB_HOST=localhost
DB_PORT=5432
DB_NAME=ncs_lab
DB_USER=postgres
DB_PASSWORD=your_password

APP_URL=http://localhost/ncs/public
```

### 3. Buat Database

Buat database PostgreSQL dan import schema:

```bash
# Buat database
createdb ncs_lab

# Import schema
psql -d ncs_lab -f sql/schema.sql
```

Atau melalui pgAdmin:
1. Buat database baru bernama `ncs_lab`
2. Jalankan query dari file `sql/schema.sql`

### 4. Konfigurasi Web Server

**Apache:** Pastikan `mod_rewrite` aktif dan document root mengarah ke folder `public/`.

**Nginx:** Konfigurasi location block untuk mengarahkan request ke `public/index.php`.

### 5. Set Permissions

```bash
chmod -R 755 public/uploads
```

## 🔐 Login Admin

Akses panel admin di: `http://your-domain/admin/`

**Default credentials:**
- Username: `admin`
- Password: `admin123`

⚠️ **PENTING:** Segera ganti password default setelah login pertama!

## 📱 Fitur

### Frontend (Public)
- ✅ Landing page dengan efek cyber/tech
- ✅ Profil: Visi Misi, Logo, Struktur Organisasi
- ✅ Galeri: Agenda & Dokumentasi Kegiatan
- ✅ Arsip: Penelitian & Pengabdian (download PDF)
- ✅ Layanan: Sarana Prasarana & Konsultatif
- ✅ Link Eksternal (Polinema, SINTA, dll)
- ✅ Form Kontak untuk Guest
- ✅ Footer dengan kredit tim pengembang
- ✅ Responsive design
- ✅ Dark mode dengan tema cyber

### Backend (Admin)
- ✅ Dashboard dengan statistik
- ✅ CRUD Agenda
- ✅ CRUD Galeri
- ✅ CRUD Dokumen (PDF upload)
- ✅ CRUD Layanan
- ✅ CRUD Struktur Organisasi
- ✅ CRUD Tim Pengembang
- ✅ CRUD Link Eksternal
- ✅ Manajemen Pesan/Komentar
- ✅ Pengaturan Website
- ✅ Manajemen User

## 🔧 Pengembangan

### Menambahkan Halaman Baru

1. Buat file baru di folder `pages/`
2. Tambahkan route di `public/index.php`
3. Update navigasi di `includes/navbar.php`

### Kustomisasi Tema

Edit konfigurasi Tailwind di `includes/header.php`:

```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: { ... },
                cyber: { ... }
            }
        }
    }
}
```

## 👥 Tim Pengembang

Data tim pengembang dapat dikelola melalui Admin Panel > Tim Pengembang.

## 📄 Lisensi

© 2025 NCS Laboratory - Politeknik Negeri Malang

---

**Dibuat dengan ❤️ menggunakan PHP Native, PostgreSQL, dan TailwindCSS**

