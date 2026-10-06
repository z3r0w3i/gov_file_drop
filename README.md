# Cổng Tiếp nhận Tài liệu Điện tử (Gov File Drop) - Nextcloud Plugin

Ứng dụng mở rộng (App/Plugin) chuyên dụng dành cho Nextcloud, giúp biến các liên kết chia sẻ thư mục công khai thành **Cổng tiếp nhận tệp tin và hồ sơ điện tử** chuẩn mực, chuyên nghiệp và tuân thủ pháp luật.

- **Tác giả:** Nguyễn Hùng Anh
- **Đơn vị công tác:** Sở Khoa học và Công nghệ tỉnh Gia Lai
- **Phiên bản:** 1.0.0
- **Giấy phép:** AGPL-3.0-or-later

---

## 🌟 Tính năng nổi bật

1. **Tuân thủ Luật Bảo vệ dữ liệu cá nhân số 91/2025/QH15:**
   - Tích hợp khung điều khoản cam kết pháp lý trực tiếp tại giao diện tiếp nhận.
   - Nút xác nhận tải lên tự động kích hoạt khi người nộp chọn tệp và tích đồng ý điều khoản.

2. **Hàng đợi kiểm tra tệp tin (File Review Queue):**
   - Người nộp xem được danh sách tên tệp, dung lượng và có thể xóa từng tệp trước khi gửi.
   - Hỗ trợ thêm nhiều tệp và kéo thả (Drag & Drop) mượt mà.

3. **Tải lên Native WebDAV XHR:**
   - Tải lên trực tiếp qua WebDAV API kèm thanh tiến trình phần trăm (0% -> 100%) theo thời gian thực.
   - Báo trạng thái thành công hoặc chẩn đoán mã lỗi cụ thể nếu có sự cố máy chủ.

4. **Bảo mật thông tin người nộp:**
   - Ẩn toàn bộ danh sách tệp tin đã nộp của những người khác trong thư mục chia sẻ, đảm bảo tính riêng tư tuyệt đối cho người nộp.

5. **Độc lập hoàn toàn (Standalone Plugin):**
   - Tích hợp qua cơ chế `IPublicShareTemplateProviderWithPriority` của Nextcloud.
   - **Không chỉnh sửa trực tiếp mã nguồn lõi** (`files_sharing`), không lo bị ghi đè hay mất tính năng khi nâng cấp phiên bản Nextcloud.

---

## 📁 Cấu trúc thư mục Plugin

```text
gov_file_drop/
├── appinfo/
│   ├── info.xml            # Khai báo thông tin App, phiên bản, tương thích Nextcloud 27 - 36
│   └── routes.php          # Định tuyến rỗng (sử dụng Provider của Nextcloud Core)
├── css/
│   └── gov_public.css      # Giao diện hành chính công hiện đại, responsive mobile
├── js/
│   └── gov_files_drop.js   # Script xử lý hàng đợi, kéo thả, WebDAV XHR upload
├── lib/
│   ├── AppInfo/
│   │   └── Application.php # Đăng ký Service & Provider vào DI Container của Nextcloud
│   └── Provider/
│       └── GovPublicShareTemplateProvider.php # Intercept public shares có quyền upload
├── templates/
│   └── public.php          # Giao diện Cổng tiếp nhận hồ sơ HTML + CSP Nonce an toàn
└── README.md               # Tài liệu hướng dẫn sử dụng
```

---

## 🚀 Hướng dẫn Cài đặt & Kích hoạt trên Server

### Bước 1: Copy thư mục plugin lên máy chủ
Sao chép thư mục `gov_file_drop` vào thư mục `apps/` của Nextcloud (ví dụ `/var/www/html/apps/gov_file_drop`):

```bash
chown -R www-data:www-data /var/www/html/apps/gov_file_drop
chmod -R 755 /var/www/html/apps/gov_file_drop
```

### Bước 2: Kích hoạt ứng dụng qua lệnh `occ`
Chạy lệnh sau trên terminal của server Nextcloud:

```bash
sudo -u www-data php /var/www/html/occ app:enable gov_file_drop
```

*(Sau khi kích hoạt, bất kỳ liên kết chia sẻ công khai nào của một Thư mục có cấp quyền Tải lên sẽ tự động hiển thị giao diện Cổng Tiếp nhận này).*

---

## ⚠️ Lưu ý Quan trọng về Cấu hình Server

### 1. Khắc phục lỗi HTTP 503 (`Encryption not ready: No default encryption module defined`)
Nếu máy chủ Nextcloud bật cấu hình mã hóa nhưng chưa chỉ định module mã hóa mặc định, WebDAV PUT sẽ trả về HTTP 503.

**Cách xử lý (chọn 1 trong 2 cách):**
- **Cách A (Khuyên dùng nếu không dùng mã hóa nội bộ):** Tắt mã hóa:
  ```bash
  sudo -u www-data php /var/www/html/occ encryption:disable
  ```
  *Hoặc kiểm tra trong file `config/config.php` đảm bảo `'encryption.enabled' => false`.*

- **Cách B (Nếu muốn dùng mã hóa máy chủ):** Kích hoạt module mã hóa mặc định:
  ```bash
  sudo -u www-data php /var/www/html/occ app:enable encryption
  sudo -u www-data php /var/www/html/occ encryption:set-default-module OC_DEFAULT_MODULE
  sudo -u www-data php /var/www/html/occ encryption:enable
  ```

### 2. Cấu hình Quyền thư mục chia sẻ
Khi tạo liên kết chia sẻ cho thư mục tiếp nhận hồ sơ:
- Chọn quyền: **"Cho phép tải lên và chỉnh sửa"** (*Allow upload and editing*).
- Plugin `gov_file_drop` đã cấu hình `$hideFileList = true`, do đó công chúng/người nộp **hoàn toàn KHÔNG nhìn thấy** các tệp tin trong thư mục, đảm bảo an toàn bí mật hồ sơ theo quy định.
