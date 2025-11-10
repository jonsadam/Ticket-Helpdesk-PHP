<p align="center">
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" width="80"/>
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg" width="80"/>
  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Javascript-shield.svg/397px-Javascript-shield.svg.png?20180912181046" width="80"/>
  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d5/Tailwind_CSS_Logo.svg/512px-Tailwind_CSS_Logo.svg.png?20230715030042" width="80"/>
</p>

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

## Dokumentasi

Tampilan Login
![Tampilan Dashboard](https://raw.githubusercontent.com/jonsadam/Ticket-Helpdesk-PHP/refs/heads/main/img/WhatsApp%20Image%202025-11-10%20at%2013.48.26.jpeg)

Dashboard Ticket Helpdesk
![Tampilan Dashboard](https://raw.githubusercontent.com/jonsadam/Ticket-Helpdesk-PHP/refs/heads/main/img/WhatsApp%20Image%202025-11-10%20at%2013.49.18.jpeg)

Create Ticket
![Tampilan Dashboard](https://raw.githubusercontent.com/jonsadam/Ticket-Helpdesk-PHP/refs/heads/main/img/WhatsApp%20Image%202025-11-10%20at%2013.49.43.jpeg)

Detail Ticket, Chat dan View Attachment
![Tampilan Dashboard](https://raw.githubusercontent.com/jonsadam/Ticket-Helpdesk-PHP/refs/heads/main/img/WhatsApp%20Image%202025-11-10%20at%2013.50.35.jpeg)


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







