# LAPORAN PROYEK
# WEBSITE NCS LABORATORY
## Network & Cyber Security Laboratory
### Politeknik Negeri Malang

---

**Versi:** 1.0 | **Tanggal:** Desember 2025 | **Status:** Final Release

---

# BAB I - PENDAHULUAN

## 1.1 Latar Belakang

Laboratorium Network & Cyber Security (NCS) merupakan laboratorium unggulan di Jurusan Teknologi Informasi Politeknik Negeri Malang yang berfokus pada riset dan pengembangan keamanan jaringan dan siber. Laboratorium ini memiliki peran strategis dalam mendukung Tri Dharma Perguruan Tinggi melalui penelitian (184+ publikasi SINTA), pengabdian masyarakat, dan pendidikan.

Dalam era digital yang berkembang pesat, ancaman keamanan siber semakin kompleks. Laboratorium NCS perlu meningkatkan visibilitas dan aksesibilitas informasi kepada mahasiswa, dosen, peneliti, industri, dan masyarakat umum. Namun, keterbatasan media publikasi konvensional membuat informasi tentang kegiatan, layanan, dan hasil penelitian sulit diakses secara luas.

Untuk mengatasi hal tersebut, dikembangkan **Website NCS Laboratory** sebagai platform digital yang komprehensif. Website ini dilengkapi Content Management System (CMS) untuk memudahkan pengelolaan konten, menyediakan arsip digital penelitian dan pengabdian, serta memfasilitasi komunikasi dengan stakeholder. Dengan desain responsif dan tema modern "Pastel Cyber", website ini diharapkan dapat meningkatkan profesionalisme dan jangkauan laboratorium.

---

## 1.2 Rumusan Masalah

Permasalahan utama dalam proyek ini adalah bagaimana merancang dan membangun website profil Laboratorium NCS yang informatif, modern, dan mudah dikelola untuk meningkatkan visibilitas dan aksesibilitas informasi laboratorium kepada seluruh stakeholder.

Dari permasalahan utama tersebut, muncul beberapa pertanyaan spesifik yang perlu dijawab. Pertama, bagaimana merancang antarmuka website yang responsif dan user-friendly sehingga dapat diakses dengan optimal dari berbagai perangkat seperti desktop, tablet, dan smartphone. Kedua, bagaimana mengimplementasikan sistem manajemen konten (CMS) yang memudahkan administrator dalam mengelola informasi tanpa memerlukan keahlian teknis programming. Ketiga, bagaimana menerapkan standar keamanan web untuk melindungi website dari berbagai ancaman seperti SQL Injection, XSS, dan CSRF, serta bagaimana mengoptimalkan performa website agar dapat diakses dengan cepat.

---

## 1.3 Tujuan Proyek

### Tujuan Umum
Tujuan umum dari proyek ini adalah mengembangkan website profil Laboratorium Network & Cyber Security yang profesional, modern, dan mudah dikelola untuk meningkatkan visibilitas dan aksesibilitas informasi laboratorium kepada seluruh stakeholder.

### Tujuan Khusus

Untuk mencapai tujuan umum tersebut, ditetapkan beberapa tujuan khusus. Dari sisi pengembangan sistem, proyek ini bertujuan membangun website frontend yang responsif dengan tema "Pastel Cyber" yang modern dan menarik, serta mengembangkan panel administrasi dengan fitur CRUD lengkap yang memudahkan pengelolaan konten. Sistem akan dibangun menggunakan database PostgreSQL dengan struktur relasional yang efisien dan menerapkan standar keamanan web seperti CSRF protection, password hashing, dan input sanitization.

Dari sisi fungsionalitas, website akan menyediakan informasi lengkap tentang profil laboratorium termasuk visi-misi, struktur organisasi, dan roadmap pengembangan. Website juga akan memfasilitasi publikasi agenda kegiatan, galeri dokumentasi, serta menyediakan arsip digital penelitian dan pengabdian masyarakat yang dapat diunduh oleh pengunjung. Selain itu, tersedia form kontak untuk memfasilitasi komunikasi antara pengunjung dengan laboratorium.

Dari sisi kualitas, proyek ini menargetkan performa website dengan page load time di bawah 3 detik dan uptime minimal 99.5%. Website akan kompatibel dengan browser modern dan dibangun dengan kode yang clean, terstruktur, serta terdokumentasi dengan baik untuk memudahkan maintenance di masa depan.

---

## 1.4 Ruang Lingkup Proyek

### Dalam Ruang Lingkup

Ruang lingkup proyek ini mencakup pengembangan website lengkap dengan dua bagian utama yaitu frontend untuk publik dan backend untuk administrasi. Pada bagian frontend, akan dikembangkan 12 halaman informatif yang meliputi Beranda dengan hero section, halaman profil (Visi-Misi, Struktur Organisasi, Bidang Fokus, Roadmap), halaman kegiatan (Agenda dan Galeri), halaman arsip (Penelitian dan Pengabdian), serta halaman layanan (Sarana Prasarana dan Konsultatif). Semua halaman dirancang dengan desain responsif yang dapat diakses optimal dari berbagai perangkat, dilengkapi dengan fitur form kontak, download dokumen PDF, dan custom 404 page.

Pada bagian backend, akan dikembangkan panel administrasi lengkap dengan dashboard yang menampilkan statistik sistem. Panel admin dilengkapi dengan 14 modul CRUD untuk mengelola berbagai konten seperti pengaturan website, user management, agenda, galeri, dokumen, publikasi penelitian, layanan, bidang fokus, roadmap, struktur organisasi, tim pengembang, link eksternal, dan pesan pengunjung. Sistem backend juga menyediakan fitur upload file untuk gambar, video, dan dokumen PDF dengan validasi tipe dan ukuran file, serta sistem autentikasi dengan role management.

Dari sisi teknis, sistem akan dibangun menggunakan database PostgreSQL dengan 14 tabel yang saling berelasi untuk menyimpan berbagai jenis data. Aspek keamanan menjadi prioritas dengan implementasi CSRF protection, password hashing menggunakan bcrypt, prepared statements untuk mencegah SQL Injection, dan input sanitization untuk mencegah XSS. Proyek juga mencakup pembuatan dokumentasi lengkap berupa README dengan panduan instalasi, dokumentasi teknis, user manual, database schema, dan diagram sistem (Use Case, ERD, Arsitektur).

### Di Luar Ruang Lingkup

Beberapa fitur tidak termasuk dalam pengembangan proyek ini. Website ini bersifat informatif sehingga tidak memerlukan fitur e-commerce, payment gateway, atau sistem Learning Management System (LMS). Fitur social seperti forum diskusi, comment system antar user, dan real-time chat juga tidak dikembangkan karena form kontak sudah cukup untuk komunikasi. Native mobile application untuk Android dan iOS tidak termasuk dalam scope karena website sudah responsif dan dapat diakses melalui mobile browser. Fitur lain yang tidak dikembangkan meliputi multi-language support, advanced analytics dashboard (dapat menggunakan Google Analytics), RESTful API untuk integrasi eksternal, Two-Factor Authentication, dan real-time notifications.

### Batasan Teknis dan Deliverables

Sistem memiliki batasan teknis yang perlu diperhatikan. Dari sisi server, diperlukan PHP versi 8.0 atau lebih tinggi, PostgreSQL versi 12 atau lebih tinggi, dan web server Apache atau Nginx. Website mendukung browser modern seperti Chrome, Firefox, Safari, dan Edge versi terbaru. Untuk upload file, terdapat batasan ukuran maksimal 5MB untuk gambar, 100MB untuk video, dan 10MB untuk dokumen PDF. Target performa yang ditetapkan adalah page load time di bawah 3 detik dengan kemampuan menangani 100 concurrent users.

Deliverables yang akan diserahkan meliputi source code lengkap dengan dokumentasi inline, database schema beserta sample data, website yang sudah live di server production, user manual dan technical documentation, serta laporan proyek lengkap dengan diagram pendukung seperti Use Case Diagram, Entity Relationship Diagram (ERD), dan Arsitektur Sistem.

---

