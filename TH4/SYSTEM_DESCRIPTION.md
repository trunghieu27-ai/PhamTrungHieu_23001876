# HỆ THỐNG QUẢN LÝ SẢN PHẨM – SHOPPING CART

## 1. Tổng quan hệ thống

### 1.1. Tên hệ thống

**Shopping Cart – Product Management System**

### 1.2. Mục đích

Hệ thống được xây dựng nhằm cung cấp các chức năng cơ bản để quản lý danh sách sản phẩm cho một hệ thống giỏ hàng.

Hệ thống cho phép người dùng:

- Xem danh sách sản phẩm.
- Thêm sản phẩm mới.
- Xem thông tin sản phẩm.
- Chỉnh sửa thông tin sản phẩm.
- Xóa sản phẩm.
- Kiểm tra tính hợp lệ của dữ liệu trước khi lưu vào cơ sở dữ liệu.

Dự án được xây dựng bằng:

- **PHP**: xử lý logic phía máy chủ.
- **MySQL**: lưu trữ dữ liệu sản phẩm.
- **HTML**: xây dựng giao diện.
- **XAMPP/Apache**: môi trường chạy PHP và MySQL.
- **DBeaver hoặc phpMyAdmin**: công cụ quản lý cơ sở dữ liệu.

---

# 2. Kiến trúc hệ thống

Hệ thống sử dụng cấu trúc đơn giản theo hướng tách riêng:

- Kết nối cơ sở dữ liệu.
- Logic truy vấn dữ liệu.
- Giao diện người dùng.
- Các trang xử lý chức năng.

Cấu trúc project:

```text
app/
├── common/
│   └── dbConnect.php
│
├── model/
│   └── product.php
│
├── view/
│   ├── header.php
│   └── footer.php
│
├── assets/
│   └── ...
│
├── index.php
├── product_list.php
├── product_add.php
├── product_edit.php
└── product_delete.php
```

---

# 3. Mô tả các thư mục và file

## 3.1. Thư mục `common/`

Thư mục này chứa những thành phần dùng chung cho nhiều trang trong hệ thống.

### `common/dbConnect.php`

Chịu trách nhiệm tạo kết nối giữa PHP và MySQL.

Nhiệm vụ:

- Kết nối tới database `shopping_cart`.
- Khai báo thông tin:
  - Host.
  - Username.
  - Password.
  - Database name.

- Kiểm tra lỗi kết nối.
- Cung cấp connection để các file khác sử dụng.

Các file xử lý dữ liệu không cần tự tạo kết nối MySQL riêng mà sử dụng kết nối từ file này.

---

# 4. Model

## 4.1. `model/product.php`

Đây là thành phần chịu trách nhiệm xử lý dữ liệu liên quan đến sản phẩm.

Các truy vấn SQL được tập trung trong file này thay vì viết trực tiếp trong các trang giao diện.

Model cung cấp các function:

```php
getAllProducts()
```

Lấy toàn bộ sản phẩm từ database.

---

```php
getProductById($id)
```

Lấy thông tin của một sản phẩm dựa trên `id`.

---

```php
addProduct($name, $price, $quantity)
```

Thêm một sản phẩm mới vào database.

---

```php
updateProduct($id, $name, $price, $quantity)
```

Cập nhật thông tin của sản phẩm.

---

```php
deleteProduct($id)
```

Xóa sản phẩm khỏi database.

---

## 4.2. Vai trò của Model

Việc tách các câu lệnh SQL vào `model/product.php` giúp:

- Code dễ tổ chức.
- Tránh lặp lại câu lệnh SQL.
- Dễ bảo trì.
- Dễ sửa đổi database logic.
- Các trang PHP tập trung vào xử lý request và giao diện.

Mô hình xử lý cơ bản:

```text
PHP Page
   │
   ▼
Product Model
   │
   ▼
MySQL Database
```

---

# 5. Database

## 5.1. Database

Tên database:

```text
shopping_cart
```

Database này lưu trữ thông tin các sản phẩm trong hệ thống.

---

## 5.2. Bảng `products`

Bảng `products` có cấu trúc:

| Trường   | Kiểu dữ liệu  | Ràng buộc                   | Mô tả             |
| -------- | ------------- | --------------------------- | ----------------- |
| id       | INT           | PRIMARY KEY, AUTO_INCREMENT | Mã sản phẩm       |
| name     | VARCHAR(100)  | NOT NULL                    | Tên sản phẩm      |
| price    | DECIMAL(10,2) | NOT NULL                    | Giá sản phẩm      |
| quantity | INT           | NOT NULL                    | Số lượng sản phẩm |

---

## 5.3. Ý nghĩa các trường

### `id`

Là mã định danh duy nhất của sản phẩm.

Ví dụ:

```text
1
2
3
4
5
```

Giá trị được tự động tăng khi thêm sản phẩm mới.

---

### `name`

Lưu tên sản phẩm.

Ví dụ:

```text
Laptop Dell
Chuột Logitech
Bàn phím cơ
Tai nghe Bluetooth
Màn hình Samsung
```

Tên sản phẩm không được để trống.

---

### `price`

Lưu giá sản phẩm.

Kiểu:

```text
DECIMAL(10,2)
```

Cho phép lưu giá có phần thập phân.

Ví dụ:

```text
15000000.00
250000.50
```

Giá phải lớn hơn `0`.

---

### `quantity`

Lưu số lượng sản phẩm hiện có.

Ví dụ:

```text
10
25
100
```

Số lượng phải lớn hơn hoặc bằng `0`.

---

# 6. Dữ liệu mẫu

Database cần có tối thiểu 5 sản phẩm.

Ví dụ:

|  ID | Tên sản phẩm       |         Giá | Số lượng |
| --: | ------------------ | ----------: | -------: |
|   1 | Laptop Dell        | 15000000.00 |       10 |
|   2 | Chuột Logitech     |   500000.00 |       25 |
|   3 | Bàn phím cơ        |  1200000.00 |       15 |
|   4 | Tai nghe Bluetooth |   850000.00 |       20 |
|   5 | Màn hình Samsung   |  4500000.00 |        8 |

---

# 7. Các trang của hệ thống

## 7.1. `index.php`

Đây là trang bắt đầu của hệ thống.

Trang có thể đóng vai trò trang chủ và cung cấp đường dẫn đến chức năng quản lý sản phẩm.

Ví dụ:

```text
Shopping Cart
│
└── Quản lý sản phẩm
```

Người dùng có thể chọn:

```text
[Xem danh sách sản phẩm]
```

để chuyển đến:

```text
product_list.php
```

---

# 8. Trang danh sách sản phẩm

## `product_list.php`

Đây là trang chính của chức năng quản lý sản phẩm.

Trang thực hiện:

1. Gọi `getAllProducts()`.
2. Lấy danh sách sản phẩm từ database.
3. Hiển thị dữ liệu bằng HTML table.

Giao diện:

```text
---------------------------------------------------------------
| ID | Tên sản phẩm | Giá | Số lượng | Chức năng              |
---------------------------------------------------------------
| 1  | Laptop Dell  | ... | 10       | [Sửa] [Xóa]          |
| 2  | Chuột        | ... | 25       | [Sửa] [Xóa]          |
| 3  | Bàn phím     | ... | 15       | [Sửa] [Xóa]          |
---------------------------------------------------------------

[Thêm sản phẩm]
```

Mỗi sản phẩm có hai chức năng:

```text
[Sửa]
[Xóa]
```

Ngoài ra trang có nút:

```text
[Thêm sản phẩm]
```

để chuyển đến `product_add.php`.

---

# 9. Chức năng thêm sản phẩm

## `product_add.php`

Trang này cho phép người dùng tạo sản phẩm mới.

Form bao gồm:

```text
Tên sản phẩm: [________________]

Giá:          [________________]

Số lượng:     [________________]

              [Thêm sản phẩm]
```

---

## 9.1. Kiểm tra dữ liệu

Trước khi thêm sản phẩm vào database, hệ thống phải kiểm tra:

### Tên sản phẩm

Không được để trống.

Ví dụ không hợp lệ:

```text
name = ""
```

Thông báo:

```text
Tên sản phẩm không được để trống.
```

---

### Giá

Giá phải lớn hơn `0`.

Ví dụ:

```text
price = 0
```

hoặc:

```text
price = -10000
```

là không hợp lệ.

Thông báo:

```text
Giá sản phẩm phải lớn hơn 0.
```

---

### Số lượng

Số lượng phải lớn hơn hoặc bằng `0`.

Ví dụ:

```text
quantity = -5
```

là không hợp lệ.

Thông báo:

```text
Số lượng phải lớn hơn hoặc bằng 0.
```

---

## 9.2. Quy trình thêm sản phẩm

```text
Người dùng nhập dữ liệu
          │
          ▼
     Submit Form
          │
          ▼
   Kiểm tra dữ liệu
          │
     ┌────┴────┐
     │         │
   Sai       Đúng
     │         │
     ▼         ▼
Hiển thị lỗi  addProduct()
                 │
                 ▼
              MySQL
                 │
                 ▼
      Thêm thành công
                 │
                 ▼
        product_list.php
```

---

# 10. Chức năng sửa sản phẩm

## `product_edit.php`

Trang này cho phép người dùng cập nhật thông tin sản phẩm.

Khi người dùng chọn:

```text
[Sửa]
```

hệ thống truyền `id` của sản phẩm sang trang sửa.

Ví dụ:

```text
product_edit.php?id=3
```

---

## 10.1. Lấy thông tin sản phẩm

Trang gọi:

```php
getProductById($id)
```

để lấy thông tin hiện tại.

Ví dụ:

```text
Tên sản phẩm: Bàn phím cơ
Giá:          1200000
Số lượng:     15
```

Các giá trị này được hiển thị sẵn trong form.

---

## 10.2. Form sửa

```text
Tên sản phẩm: [Bàn phím cơ________]

Giá:          [1200000____________]

Số lượng:     [15_________________]

              [Cập nhật]
```

---

## 10.3. Kiểm tra dữ liệu

Dữ liệu khi cập nhật phải tuân thủ các điều kiện:

```text
Tên sản phẩm != rỗng
Giá > 0
Số lượng >= 0
```

Nếu hợp lệ, hệ thống gọi:

```php
updateProduct($id, $name, $price, $quantity)
```

Sau khi cập nhật thành công, người dùng được chuyển về:

```text
product_list.php
```

---

# 11. Chức năng xóa sản phẩm

## `product_delete.php`

Trang này xử lý việc xóa sản phẩm.

Người dùng chọn:

```text
[Xóa]
```

hệ thống nhận `id` của sản phẩm cần xóa.

Ví dụ:

```text
product_delete.php?id=5
```

---

# 12. Xác nhận trước khi xóa

Trước khi thực hiện thao tác xóa, hệ thống phải yêu cầu người dùng xác nhận.

Ví dụ:

```text
Bạn có chắc chắn muốn xóa sản phẩm này?

[Hủy]    [Xóa]
```

Mục đích là tránh trường hợp người dùng vô tình xóa sản phẩm.

---

# 13. Kiểm tra sản phẩm trước khi xóa

Trước khi xóa, hệ thống cần kiểm tra sản phẩm có tồn tại hay không.

Có thể sử dụng:

```php
getProductById($id)
```

Nếu sản phẩm tồn tại:

```text
→ Thực hiện deleteProduct($id)
```

Nếu sản phẩm không tồn tại:

```text
Sản phẩm không tồn tại.
```

---

# 14. Quy trình xóa

```text
Người dùng chọn Xóa
        │
        ▼
Nhận product ID
        │
        ▼
Kiểm tra sản phẩm
        │
   ┌────┴─────┐
   │          │
Không tồn tại  Tồn tại
   │          │
   ▼          ▼
Thông báo    Xác nhận
                │
          ┌─────┴─────┐
          │           │
        Hủy          Xóa
          │           │
          ▼           ▼
      Quay lại   deleteProduct()
                      │
                      ▼
                   MySQL
                      │
                      ▼
              Danh sách sản phẩm
```

---

# 15. Header và Footer

## `view/header.php`

Chứa phần HTML dùng chung ở đầu các trang.

Ví dụ:

```html
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Shopping Cart</title>
  </head>
  <body>
    <h1>Shopping Cart</h1>
  </body>
</html>
```

Có thể đặt navigation:

```text
Trang chủ | Danh sách sản phẩm | Thêm sản phẩm
```

---

## `view/footer.php`

Chứa phần HTML dùng chung ở cuối các trang.

Ví dụ:

```html
<footer>
    <p>Shopping Cart Management System</p>
</footer>

</body>
</html>
```

---

# 16. Tái sử dụng Header và Footer

Các trang:

```text
index.php
product_list.php
product_add.php
product_edit.php
product_delete.php
```

sử dụng:

```php
include 'view/header.php';
```

và:

```php
include 'view/footer.php';
```

Điều này giúp tránh việc viết lại HTML giống nhau ở nhiều file.

---

# 17. Assets

Thư mục:

```text
assets/
```

dùng để chứa các tài nguyên giao diện nếu cần.

Có thể bao gồm:

```text
assets/
├── css/
│   └── style.css
├── js/
│   └── script.js
└── images/
```

Tuy nhiên bài thực hành yêu cầu HTML cơ bản nên không bắt buộc phải sử dụng CSS hoặc JavaScript phức tạp.

---

# 18. Luồng hoạt động tổng thể

Toàn bộ hệ thống hoạt động theo mô hình:

```text
                    ┌───────────────┐
                    │   index.php   │
                    └───────┬───────┘
                            │
                            ▼
                  ┌───────────────────┐
                  │ product_list.php  │
                  └───────┬───────────┘
                          /|\
                         / | \
                        /  |  \
                       ▼   ▼   ▼
                     Thêm Sửa Xóa
                      │    │    │
                      ▼    ▼    ▼
                   add   edit delete
                      \    |    /
                       \   |   /
                        ▼  ▼  ▼
                  ┌─────────────────┐
                  │ model/product.php│
                  └────────┬────────┘
                           │
                           ▼
                  ┌─────────────────┐
                  │ common/dbConnect│
                  └────────┬────────┘
                           │
                           ▼
                  ┌─────────────────┐
                  │ MySQL Database  │
                  │ shopping_cart   │
                  │    products     │
                  └─────────────────┘
```

---

# 19. Mối quan hệ giữa các thành phần

```text
┌──────────────────────┐
│      User/Browser    │
└──────────┬───────────┘
           │ HTTP Request
           ▼
┌──────────────────────┐
│     PHP Pages        │
│                      │
│ product_list.php     │
│ product_add.php      │
│ product_edit.php     │
│ product_delete.php   │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│   Product Model      │
│   model/product.php  │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ Database Connection  │
│ common/dbConnect.php │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│       MySQL          │
│   shopping_cart      │
│      products        │
└──────────────────────┘
```

---

# 20. Các chức năng chính

| Chức năng      | File xử lý             | Model sử dụng                         |
| -------------- | ---------------------- | ------------------------------------- |
| Trang chủ      | `index.php`            | Không bắt buộc                        |
| Xem sản phẩm   | `product_list.php`     | `getAllProducts()`                    |
| Thêm sản phẩm  | `product_add.php`      | `addProduct()`                        |
| Sửa sản phẩm   | `product_edit.php`     | `getProductById()`, `updateProduct()` |
| Xóa sản phẩm   | `product_delete.php`   | `getProductById()`, `deleteProduct()` |
| Kết nối DB     | `common/dbConnect.php` | MySQL connection                      |
| Xử lý sản phẩm | `model/product.php`    | SQL queries                           |
| Header         | `view/header.php`      | Giao diện                             |
| Footer         | `view/footer.php`      | Giao diện                             |

---

# 21. Yêu cầu kiểm tra dữ liệu

Hệ thống cần kiểm tra dữ liệu ở phía PHP trước khi thực hiện thao tác database.

## Thêm sản phẩm

| Dữ liệu  | Điều kiện       |
| -------- | --------------- |
| Tên      | Không được rỗng |
| Giá      | > 0             |
| Số lượng | >= 0            |

## Sửa sản phẩm

| Dữ liệu  | Điều kiện       |
| -------- | --------------- |
| ID       | Phải tồn tại    |
| Tên      | Không được rỗng |
| Giá      | > 0             |
| Số lượng | >= 0            |

## Xóa sản phẩm

| Dữ liệu  | Điều kiện                              |
| -------- | -------------------------------------- |
| ID       | Phải tồn tại                           |
| Xác nhận | Người dùng phải xác nhận trước khi xóa |

---

# 22. Xử lý lỗi

Hệ thống cần xử lý một số trường hợp lỗi cơ bản.

### Lỗi kết nối database

Ví dụ:

```text
Không thể kết nối đến database.
```

---

### Sản phẩm không tồn tại

Ví dụ:

```text
Không tìm thấy sản phẩm.
```

---

### Dữ liệu không hợp lệ

Ví dụ:

```text
Tên sản phẩm không được để trống.
Giá phải lớn hơn 0.
Số lượng phải lớn hơn hoặc bằng 0.
```

---

### Xóa thành công

Ví dụ:

```text
Xóa sản phẩm thành công.
```

---

### Thêm thành công

Ví dụ:

```text
Thêm sản phẩm thành công.
```

---

### Cập nhật thành công

Ví dụ:

```text
Cập nhật sản phẩm thành công.
```

---

# 23. Yêu cầu giao diện

Giao diện sử dụng HTML cơ bản.

Các thành phần chính:

### Table

Dùng để hiển thị danh sách sản phẩm.

```html
<table>
  ...
</table>
```

### Form

Dùng cho chức năng thêm và sửa.

```html
<form>...</form>
```

### Input

Nhập dữ liệu:

```html
<input type="text" /> <input type="number" />
```

### Button

Thực hiện submit:

```html
<button type="submit">Lưu</button>
```

### Link

Điều hướng giữa các trang:

```html
<a href="product_list.php">Danh sách sản phẩm</a>
```

---

# 24. Bảo mật và xử lý dữ liệu

Mặc dù đây là bài thực hành cơ bản, hệ thống nên tuân thủ một số nguyên tắc:

- Kiểm tra dữ liệu người dùng gửi lên.
- Không tin tưởng trực tiếp dữ liệu từ URL.
- Kiểm tra `id` trước khi truy vấn.
- Sử dụng prepared statement khi thực hiện SQL để hạn chế SQL Injection.
- Escape dữ liệu khi hiển thị ra HTML để hạn chế XSS.
- Không đặt thông tin mật khẩu database trực tiếp ở nhiều file khác nhau.

---

# 25. Công nghệ sử dụng

| Công nghệ          | Vai trò                |
| ------------------ | ---------------------- |
| PHP                | Backend và xử lý logic |
| MySQL              | Database               |
| HTML               | Giao diện              |
| Apache             | Web server             |
| XAMPP              | Môi trường phát triển  |
| DBeaver/phpMyAdmin | Quản lý database       |

---

# 26. Cách chạy hệ thống

## Bước 1: Khởi động XAMPP

Mở XAMPP Control Panel và bật:

```text
Apache
MySQL
```

---

## Bước 2: Đặt project

Đặt thư mục project vào:

```text
D:\xampp\htdocs\
```

Ví dụ:

```text
D:\xampp\htdocs\shopping_cart\
```

---

## Bước 3: Tạo database

Tạo database:

```text
shopping_cart
```

Sau đó tạo bảng:

```text
products
```

với các trường:

```text
id
name
price
quantity
```

---

## Bước 4: Thêm dữ liệu mẫu

Thêm ít nhất 5 sản phẩm vào bảng `products`.

---

## Bước 5: Kiểm tra kết nối

Kiểm tra thông tin trong:

```text
common/dbConnect.php
```

Ví dụ:

```text
Host: localhost
Database: shopping_cart
User: root
Password: tùy cấu hình MySQL
```

---

## Bước 6: Truy cập website

Mở trình duyệt:

```text
http://localhost/shopping_cart/
```

Nếu project được đặt với tên thư mục khác thì thay `shopping_cart` bằng tên thư mục tương ứng.

---

# 27. Quy trình sử dụng hệ thống

### Bước 1 – Truy cập trang chủ

```text
index.php
```

↓

### Bước 2 – Xem danh sách

```text
product_list.php
```

↓

### Bước 3 – Thêm sản phẩm

```text
product_add.php
```

↓

### Bước 4 – Sửa sản phẩm

```text
product_edit.php?id=...
```

↓

### Bước 5 – Xóa sản phẩm

```text
product_delete.php?id=...
```

↓

### Bước 6 – Database được cập nhật

```text
shopping_cart.products
```

---

# 28. Kết quả cần đạt

Sau khi hoàn thành, hệ thống phải đáp ứng đầy đủ các yêu cầu:

- [x] Tạo database `shopping_cart`.
- [x] Tạo bảng `products`.
- [x] Có tối thiểu 5 sản phẩm.
- [x] Kết nối PHP với MySQL.
- [x] Có file `common/dbConnect.php`.
- [x] Có model `model/product.php`.
- [x] Có function `getAllProducts()`.
- [x] Có function `getProductById($id)`.
- [x] Có function `addProduct(...)`.
- [x] Có function `updateProduct(...)`.
- [x] Có function `deleteProduct($id)`.
- [x] Hiển thị danh sách sản phẩm.
- [x] Thêm sản phẩm.
- [x] Sửa sản phẩm.
- [x] Xóa sản phẩm.
- [x] Kiểm tra dữ liệu đầu vào.
- [x] Kiểm tra sản phẩm tồn tại.
- [x] Có xác nhận trước khi xóa.
- [x] Sử dụng `header.php`.
- [x] Sử dụng `footer.php`.
- [x] Sử dụng HTML cơ bản.
- [x] Tách logic truy vấn database vào Model.

---

# 29. Tổng kết

Hệ thống Shopping Cart trong bài thực hành là một ứng dụng web PHP đơn giản tập trung vào chức năng **CRUD sản phẩm**.

CRUD bao gồm:

```text
C – Create  → Thêm sản phẩm
R – Read    → Xem sản phẩm
U – Update  → Sửa sản phẩm
D – Delete  → Xóa sản phẩm
```

Kiến trúc của hệ thống được tổ chức thành các thành phần:

```text
View
 │
 ├── header.php
 ├── footer.php
 ├── product_list.php
 ├── product_add.php
 ├── product_edit.php
 └── product_delete.php
        │
        ▼
Model
 │
 └── product.php
        │
        ▼
Database Connection
 │
 └── dbConnect.php
        │
        ▼
MySQL
 │
 └── shopping_cart
       └── products
```

Việc phân tách các thành phần giúp hệ thống dễ hiểu, dễ kiểm tra và dễ mở rộng trong các bài thực hành hoặc các dự án PHP lớn hơn.

---

# 30. Chức năng Đăng nhập & Đăng xuất (Authentication & Access Control)

## 30.1. Cơ sở dữ liệu (Bảng `users`)
- `id`: Khóa chính (INT, AUTO_INCREMENT).
- `username`: Tên đăng nhập duy nhất (VARCHAR(50), NOT NULL, UNIQUE).
- `password`: Mật khẩu đã được mã hóa an toàn (VARCHAR(255), NOT NULL) bằng thuật toán `password_hash()` với thuật toán mặc định `PASSWORD_DEFAULT` (Bcrypt).
- Tài khoản quản trị viên mẫu:
  - **Tên đăng nhập:** `admin`
  - **Mật khẩu:** `123456`

## 30.2. Kiến trúc & Các thành phần bổ sung
```text
Controllers & Views:
├── register.php       → Đăng ký tài khoản mới, kiểm tra trùng lặp và băm mật khẩu
├── login.php          → Đăng nhập, kiểm tra tài khoản với password_verify() và tạo session
└── logout.php         → Đăng xuất, hủy session và chuyển hướng an toàn

Middleware & Helpers:
└── common/auth.php    → isLoggedIn(), requireLogin(), redirectIfLoggedIn(), getCurrentUser()

Models:
└── model/user.php     → findUserByUsername(), createUser(), checkUsernameExists(), getUserById()
```

## 30.3. Cơ chế kiểm soát truy cập (Access Control)
- Tất cả các trang quản lý sản phẩm gồm `product_list.php`, `product_add.php`, `product_edit.php` và `product_delete.php` đều được tích hợp hàm `requireLogin()`.
- Khi người dùng chưa đăng nhập cố gắng truy cập vào các trang này, hệ thống tự động lưu thông báo lỗi Flash message vào `$_SESSION` và chuyển hướng (HTTP 302) về trang `login.php`.
- Sau khi đăng nhập thành công, hệ thống lưu thông tin phiên làm việc vào `$_SESSION['user']`, hiển thị huy hiệu tên tài khoản `👤 {username}` cùng nút `[Đăng xuất]` trên thanh điều hướng `view/header.php`.

