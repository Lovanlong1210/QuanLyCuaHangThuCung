
<h2 align="center">
    <a href="https://dainam.edu.vn/vi/khoa-cong-nghe-thong-tin">
    🎓 Faculty of Information Technology (DaiNam University)
    </a>
</h2>
<h2 align="center">
    Pet Store Manager
</h2>
<div align="center">
    <p align="center">
        <img src="docs/logo/aiotlab_logo.png" alt="AIoTLab Logo" width="170"/>
        <img src="docs/logo/fitdnu_logo.png" alt="AIoTLab Logo" width="180"/>
        <img src="docs/logo/dnu_logo.png" alt="DaiNam University Logo" width="200"/>
    </p>

[![AIoTLab](https://img.shields.io/badge/AIoTLab-green?style=for-the-badge)](https://www.facebook.com/DNUAIoTLab)
[![Faculty of Information Technology](https://img.shields.io/badge/Faculty%20of%20Information%20Technology-blue?style=for-the-badge)](https://dainam.edu.vn/vi/khoa-cong-nghe-thong-tin)
[![DaiNam University](https://img.shields.io/badge/DaiNam%20University-orange?style=for-the-badge)](https://dainam.edu.vn)

</div>
 
## 📖 1. Giới thiệu
Hệ thống Website Quản lý Dịch vụ Chăm sóc Thú cưng được xây dựng nhằm hỗ trợ công tác quản lý lịch hẹn, theo dõi hồ sơ sức khỏe thú cưng và vận hành các dịch vụ tại cửa hàng một cách hiệu quả. Thay vì quản lý thủ công bằng sổ sách ghi chép hay các tệp Excel rời rạc dễ gây nhầm lẫn, hệ thống mang đến một giải pháp số hóa tập trung, hiện đại và thân thiện với người dùng.

## 🔧 2. Các công nghệ được sử dụng
<div align="center">

### Hệ điều hành
![macOS](https://img.shields.io/badge/macOS-000000?style=for-the-badge&logo=macos&logoColor=F0F0F0)
[![Windows](https://img.shields.io/badge/Windows-0078D6?style=for-the-badge&logo=windows&logoColor=white)](https://www.microsoft.com/en-us/windows/)
[![Ubuntu](https://img.shields.io/badge/Ubuntu-E95420?style=for-the-badge&logo=ubuntu&logoColor=white)](https://ubuntu.com/)

### Công nghệ chính
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](#)
[![CSS](https://img.shields.io/badge/CSS-1572B6?style=for-the-badge&logo=css3&logoColor=white)](#)
[![SCSS](https://img.shields.io/badge/SCSS-CC6699?style=for-the-badge&logo=sass&logoColor=white)](#)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](#)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)

### Web Server & Database
[![Apache](https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=apache&logoColor=white)](https://httpd.apache.org/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/) 
[![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)](https://www.apachefriends.org/)

### Database Management Tools
[![MySQL Workbench](https://img.shields.io/badge/MySQL_Workbench-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://dev.mysql.com/downloads/workbench/)
</div>

---

## 🖼️ 3. Hình ảnh các chức năng
## Trang đăng nhập
<img width="1919" height="910" alt="image" src="https://github.com/user-attachments/assets/0d5fc1a0-420b-4ff6-9796-d4e277139f07" />

## Trang đăng ký
<img width="1919" height="852" alt="image" src="https://github.com/user-attachments/assets/49e22961-7cf5-4e9f-b53f-5eac067f1876" />

## Bảng điều khiển ADMIN
<img width="1919" height="857" alt="image" src="https://github.com/user-attachments/assets/456791d7-b4c4-4040-bb50-9abe4b632458" />

## Đặt lịch mới
<img width="1919" height="855" alt="image" src="https://github.com/user-attachments/assets/efceb7c2-3eb3-4e8d-9272-6d2f41f859e0" />




## 4. Hướng dẫn cài đặt

### 4.1 Cài đặt công cụ, môi trường và các thư viện cần thiết

Mục này hướng dẫn cách chuẩn bị môi trường để chạy dự án trên máy Windows (XAMPP) hoặc Linux/WSL.

  - PHP 7.4+ (hoặc PHP 8.x)
  - MySQL (MariaDB)
  - Apache (XAMPP trên Windows tiện dụng)
  - PDO MySQL extension (thường có sẵn trong XAMPP)

  1. Tải XAMPP từ https://www.apachefriends.org/ và cài đặt.
  2. Mở `XAMPP Control Panel` và Start `Apache` và `MySQL`.

  Nếu dùng WSL/Ubuntu, cài Apache + PHP + MySQL bằng `apt` hoặc dùng Docker.

  - Giả sử XAMPP được cài ở `C:\xampp`, đặt project ở `C:\xampp\htdocs\BTL`.

  - Tạo database mới (ví dụ tên `pet_care_db`) bằng phpMyAdmin (http://localhost/phpmyadmin) hoặc dòng lệnh.

  - Ví dụ import bằng mysql CLI (WSL / PowerShell):

```bash
# Tạo database (nếu cần)
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS pet_care_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import file dump SQL (ví dụ file có đường dẫn C:/xampp/htdocs/BTL/db/dump.sql)
# Trên WSL đường dẫn Windows sẽ mount ở /mnt/c/...
mysql -u root -p pet_care_db < /mnt/c/xampp/htdocs/BTL/db/dump.sql
```

  - Hoặc trong `phpMyAdmin`:
    1. Vào http://localhost/phpmyadmin
    2. Tạo database `pet_care_db` (nếu chưa có)
    3. Chọn database -> Import -> chọn file SQL -> Go.

  - Mở file `functions/db.php` và chỉnh thông số kết nối (`$host`, `$db`, `$user`, `$pass`) cho phù hợp với môi trường của bạn.

```php
// ví dụ (Windows/XAMPP mặc định):
$host = '127.0.0.1';
$db   = 'pet_care_db';
$user = 'root';
$pass = ''; // XAMPP mặc định trống
```

  - Lưu file.

  - Bật hiển thị lỗi khi đang phát triển: trong `php.ini` (hoặc `xampp\php\php.ini`) bật `display_errors = On` và `error_reporting = E_ALL`.
  - Khởi động lại Apache sau khi chỉnh `php.ini`.

  - Mở trình duyệt: `http://localhost/BTL/views/login.php` (hoặc `http://localhost/BTL/` tùy cấu hình).

  - Không bật `display_errors` trên môi trường production.
  - Đừng lưu mật khẩu DB ở trạng thái mặc định nếu máy có kết nối mạng công cộng.
  - Cân nhắc sử dụng file cấu hình ngoài (ví dụ `.env`) để lưu thông tin nhạy cảm.
  - 
### 4.2 Tải project

Bạn có thể tải mã nguồn bằng `git` hoặc tải file zip rồi giải nén vào thư mục web root.

- Với `git` (nếu repository có trên GitHub/GitLab):

```bash
# ví dụ (trên Windows PowerShell)
cd C:\xampp\htdocs
git clone https://github.com/your-repo/your-project.git BTL
```

- Hoặc tải file ZIP từ giao diện GitHub và giải nén vào `C:\xampp\htdocs\BTL`.

### 4.3 Setup database

1. Tạo database mới (ví dụ `pet_care_db`).

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS pet_care_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

2. Import schema/dump (nếu repo có file `db/dump.sql`).

```bash
# Trên Windows/PowerShell (điều chỉnh đường dẫn nếu cần)
mysql -u root -p pet_care_db < C:\xampp\htdocs\BTL\db\dump.sql

# Trên WSL: /mnt/c/xampp/htdocs/BTL/db/dump.sql
mysql -u root -p pet_care_db < /mnt/c/xampp/htdocs/BTL/db/dump.sql
```

3. Nếu không có `dump.sql`, bạn có thể tạo bảng thủ công trong `phpMyAdmin` hoặc dùng các tập tin SQL mà bạn đang giữ.

### 4.4 Setup tham số kết nối

- Mở file `functions/db.php` và chỉnh các tham số sau cho phù hợp môi trường:

```php
// ví dụ (Windows/XAMPP mặc định):
$host = '127.0.0.1';
$db   = 'pet_care_db';
$user = 'root';
$pass = ''; // XAMPP mặc định trống
```

- Lưu file. Nếu dùng môi trường khác (Docker, remote DB), hãy cập nhật host và thông tin xác thực tương ứng.

### 4.5 Chạy hệ thống

1. Bật `Apache` và `MySQL` (qua XAMPP Control Panel hoặc lệnh tương đương trên Linux).
2. Mở trình duyệt và truy cập:

```
http://localhost/BTL/views/login.php
```

Hoặc nếu đã cấu hình VirtualHost, dùng host bạn đã cấu hình.

### 4.6 Đăng nhập lần đầu

Có hai cách để có tài khoản admin ban đầu:

- Cách A — Tự tạo tài khoản admin qua SQL (an toàn cho môi trường dev):

1. Tạo password hash bằng PHP CLI:

```bash
# Chạy lệnh này để in ra hash (thay 'admin123' bằng mật khẩu mong muốn)
php -r "echo password_hash('admin123', PASSWORD_DEFAULT) . PHP_EOL;"
```

2. Dùng hash vừa tạo để chèn vào bảng `customers`:

```sql
INSERT INTO customers (name, email, password, phone, role)
VALUES ('Admin', 'admin@example.com', '<PASTE_HASH_HERE>', '0123456789', 'admin');
```

3. Sau đó truy cập `http://localhost/BTL/views/login.php` và đăng nhập bằng `admin@example.com` / `admin123`.

- Cách B — Đăng ký tài khoản và cập nhật role bằng database

1. Mở `http://localhost/BTL/views/register.php` và đăng ký một tài khoản.
2. Đăng nhập vào `phpMyAdmin`, tìm bản ghi trong `customers` và thay đổi trường `role` thành `admin` cho tài khoản đó.
