BÀI TẬP CÁ NHÂN – QUẢN LÝ PHÒNG THỰC HÀNH

1. Thông tin bài tập

Đề tài: Quản lý phòng thực hành

Môn học: Lập trình Web

Hình thức: Bài tập cá nhân

Chủ đề liên quan: Hệ thống quản lý phòng thực hành và thiết bị của bài tập lớn nhóm

Bài cá nhân được thực hiện đơn giản, tập trung vào PHP, HTML, CSS, mảng, hàm, điều kiện và vòng lặp.

2. Mục đích

Xây dựng chương trình đơn giản cho phép:

Nhập thông tin phòng thực hành.

Lưu thông tin phòng bằng mảng PHP.

Kiểm tra trạng thái phòng.

Hiển thị danh sách phòng dưới dạng bảng.

3. Đối tượng dữ liệu

Đối tượng được chọn là Phòng thực hành.

Mỗi phòng có 4 trường:

ma_phong: Mã phòng, ví dụ P101.

ten_phong: Tên phòng, ví dụ Phòng máy 101.

suc_chua: Số người tối đa, ví dụ 40.

trang_thai: Trạng thái phòng, ví dụ Trống.

4. Chức năng chính

4.1. Nhập thông tin phòng

Người dùng nhập mã phòng, tên phòng, sức chứa và trạng thái, sau đó nhấn Thêm phòng.

4.2. Lưu dữ liệu bằng mảng

Dữ liệu được lưu trong mảng PHP:

$rooms = [];

4.3. Kiểm tra trạng thái phòng

Chương trình sử dụng hàm tự định nghĩa:

kiemTraPhong()

Quy tắc:

Trống → Có thể đặt.

Đang sử dụng → Không thể đặt.

Bảo trì → Không thể đặt.

4.4. Hiển thị danh sách

Sử dụng vòng lặp foreach để duyệt mảng và hiển thị dữ liệu bằng bảng HTML.

5. Các yêu cầu kỹ thuật

Mảng: sử dụng $rooms để lưu danh sách phòng.

Hàm tự định nghĩa: sử dụng kiemTraPhong($trang_thai).

Điều kiện: sử dụng if, elseif, else.

Vòng lặp: sử dụng foreach.

Form HTML: sử dụng phương thức POST để tiếp nhận dữ liệu.

CSS: viết trực tiếp trong index.php để giữ project đơn giản.

6. Công nghệ sử dụng

PHP

HTML

CSS

XAMPP

Git

GitHub

GitHub Desktop

7. Cấu trúc project

bai-ca-nhan/
└── index.php

Toàn bộ PHP, HTML và CSS được viết trong index.php.

8. Cách chạy chương trình

Bước 1: Mở XAMPP

Mở XAMPP và nhấn Start Apache.

Bước 2: Đặt project

Đặt thư mục project vào:

C:\xampp\htdocs\bai-ca-nhan

Trong thư mục phải có:

C:\xampp\htdocs\bai-ca-nhan\index.php

Bước 3: Mở website

Truy cập:

http://localhost/bai-ca-nhan/

9. GitHub và GitHub Desktop

Quy trình làm việc:

Sửa code
   ↓
Mở GitHub Desktop
   ↓
Kiểm tra Changes
   ↓
Viết nội dung Commit
   ↓
Commit to main
   ↓
Push origin
   ↓
Kiểm tra code trên GitHub

Clone repository

Mở GitHub Desktop.

Chọn File → Clone repository.

Chọn tab URL.

Dán link repository GitHub.

Chọn thư mục lưu project.

Nhấn Clone.

Nếu muốn chạy bằng XAMPP, có thể chọn thư mục:

C:\xampp\htdocs\

10. Lưu ý

Dữ liệu trong bài được lưu bằng mảng PHP, không sử dụng MySQL.

Vì vậy, dữ liệu phòng mới thêm chỉ tồn tại trong lần chạy hiện tại và sẽ trở lại dữ liệu ban đầu khi tải lại trang.

Điều này giúp bài tập giữ đúng phạm vi đơn giản và tập trung vào yêu cầu của bài cá nhân.

11. Kiểm tra yêu cầu

Có repository cá nhân trên GitHub.

Đối tượng dữ liệu liên quan đến bài tập lớn nhóm.

Có ít nhất 3 trường dữ liệu.

Có form tiếp nhận dữ liệu.

Có xử lý dữ liệu nhập.

Tổ chức dữ liệu bằng mảng.

Có ít nhất một hàm tự định nghĩa.

Có câu lệnh điều kiện.

Có vòng lặp foreach.

Hiển thị dữ liệu dưới dạng bảng.

Sử dụng GitHub và GitHub Desktop.