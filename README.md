# ShoeStore - Web bán giày

## Giới thiệu

ShoeStore là một ứng dụng web bán giày cơ bản được xây dựng bằng PHP và MySQL. Dự án này giúp người dùng duyệt qua các sản phẩm giày, thêm chúng vào giỏ hàng và thực hiện thanh toán.

## Yêu cầu hệ thống

- **XAMPP** (hoặc bất kỳ máy chủ web hỗ trợ PHP và MySQL nào)
- **PHP 7.0 trở lên**
- **MySQL** hoặc MariaDB
- Trình duyệt web hiện đại

## Cài đặt và cấu hình

### 1. Tải và cài đặt XAMPP
- Tải [XAMPP](https://www.apachefriends.org/index.html) và cài đặt trên máy tính của bạn.

### 2. Tải dự án từ GitHub
Clone repository từ GitHub:
```bash
git clone https://github.com/gk182/ShoeStore.git
   ```

Dưới đây là phần README.md cho các bước Cấu hình cơ sở dữ liệu và Cấu hình XAMPP như bạn yêu cầu:

markdown
Sao chép mã
### 3. Cấu hình cơ sở dữ liệu

1. Mở **phpMyAdmin** qua URL `http://localhost/phpmyadmin` trong trình duyệt.

2. **Tạo cơ sở dữ liệu mới**:
   - Trong phpMyAdmin, tạo một cơ sở dữ liệu mới, ví dụ: `shoestore`.

3. **Import file SQL vào cơ sở dữ liệu**:
   - Mở cơ sở dữ liệu `shoestore` đã tạo.
   - Chọn tab **Import**.
   - Chọn file `shoestore.sql` từ thư mục dự án của bạn và nhấn **Go**.
   - File SQL này sẽ tạo các bảng cần thiết cho dự án và chèn dữ liệu mẫu.
### 4. Cấu hình XAMPP

1. **Di chuyển thư mục ShoeStore** vào thư mục `htdocs` trong thư mục cài đặt XAMPP (thường là `C:\xampp\htdocs`).

2. **Khởi động Apache và MySQL**:
   - Mở **XAMPP Control Panel** và khởi động các dịch vụ **Apache** và **MySQL**.

3. **Truy cập vào website**:
   - Mở trình duyệt và nhập URL:
   ```bash
   http://localhost/ShoeStore/
      ```
