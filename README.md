# VIMICO

## Setup project

### Cấu hình ImageMagick
Cài đặt ghostscript
```
sudo apt-get install ghostscript
```
Tìm kiếm file policy.xml ImageMagick trên hệ thống, để có cho phép chuyển đổi pdf thành image:
Xác định vị trí file policy.xml:
```angular2html
sudo find / -name "policy.xml"
```
Chỉnh sửa file policy.xml:
Mở file policy.xml để chỉnh sửa:

```angular2html
nano /etc/ImageMagick-6/policy.xml
```
Tìm và sửa dòng liên quan đến PDF:
Tìm dòng cấu hình liên quan đến định dạng PDF. Nó thường trông giống như sau:

```angular2html
<policy domain="coder" rights="none" pattern="PDF" />
```
Bạn cần thay đổi rights="none" thành rights="read|write" để cho phép ImageMagick đọc và ghi file PDF:
```angular2html
<policy domain="coder" rights="read|write" pattern="PDF" />
```
