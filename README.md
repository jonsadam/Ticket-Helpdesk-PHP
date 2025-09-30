## Helpdesk Ticketing System

Aplikasi Helpdesk Web Simple berbasis **PHP, MySQL, HTML5, dan TailwindCSS** untuk mengelola tiket support antara **user** dan **admin**.

---

## Fitur

- Login & Register User  
- Session terpisah (Admin & User)  
- CRUD Tiket   
- Upload gambar opsional saat membuat tiket  
- Balasan tiket (reply) antara user dan admin  
- Edit profil 
---

## Database

- Buat database **tiket**
- Tabel :
- **users** – menyimpan data akun (admin & user)  
- **tickets** – menyimpan data tiket  
- **replies** – menyimpan balasan tiket  

---

## Install Auto
1. Akses ke browser http://localhost/skuytiket/
2. Ikuti step by step
3. Kalau sudah selesai install, silahkan hapus file install.php nya yang berada di root folder.

## Install Manual

1. Salin folder ke `htdocs/`  
2. Import database ke phpMyAdmin  
3. Ubah konfigurasi koneksi di `config/database.php`  
4. Akses melalui browser: http://localhost/skuytiket/ (Jika ingin akses di lokal)


Lisensi: MIT
Dibuat oleh: Lensajon Corp (Adam Abdillah Januar) – 2025