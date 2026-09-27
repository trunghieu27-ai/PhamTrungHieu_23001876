<?php

$nl = (php_sapi_name() === 'cli') ? "\n" : "<br>";

class CartItem {
    private $name;
    private $price;
    private $quantity;

    public function __construct($name, $price, $quantity) {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function getTotal() {
        return $this->price * $this->quantity;
    }
}

class ShoppingCart {
    private $items = [];

    public function addItem($item) {
        global $nl;

        if (!($item instanceof CartItem)) {
            echo "[LỖI THÊM] Dữ liệu truyền vào không phải là một CartItem hợp lệ!$nl";
            return false;
        }

        if ($item->getPrice() <= 0) {
            echo "[LỖI THÊM] Sản phẩm '{$item->getName()}' có đơn giá không hợp lệ (" . number_format($item->getPrice(), 0, ',', '.') . " VNĐ). Giá phải lớn hơn 0!$nl";
            return false;
        }

        if ($item->getQuantity() <= 0) {
            echo "[LỖI THÊM] Sản phẩm '{$item->getName()}' có số lượng không hợp lệ ({$item->getQuantity()}). Số lượng phải lớn hơn 0!$nl";
            return false;
        }

        foreach ($this->items as $existingItem) {
            if (strcasecmp($existingItem->getName(), $item->getName()) === 0) {
                $newQuantity = $existingItem->getQuantity() + $item->getQuantity();
                $existingItem->setQuantity($newQuantity);
                echo "=> Sản phẩm '{$existingItem->getName()}' đã có trong giỏ hàng. Đã cộng thêm {$item->getQuantity()} (Tổng số lượng mới: $newQuantity).$nl";
                return true;
            }
        }

        $this->items[] = $item;
        echo "=> Đã thêm sản phẩm '{$item->getName()}' vào giỏ hàng thành công (Số lượng: {$item->getQuantity()}, Giá: " . number_format($item->getPrice(), 0, ',', '.') . " VNĐ).$nl";
        return true;
    }

    public function removeItem($name) {
        global $nl;
        $found = false;

        foreach ($this->items as $index => $item) {
            if (strcasecmp($item->getName(), $name) === 0) {
                unset($this->items[$index]);
                $this->items = array_values($this->items);
                $found = true;
                echo "=> Đã xóa sản phẩm '$name' khỏi giỏ hàng thành công.$nl";
                break;
            }
        }

        if (!$found) {
            echo "[LỖI XÓA] Không tìm thấy sản phẩm '$name' trong giỏ hàng để xóa!$nl";
            return false;
        }

        return true;
    }

    public function calculateTotal() {
        $total = 0;
        if (empty($this->items)) {
            return 0;
        }

        foreach ($this->items as $item) {
            $total += $item->getTotal();
        }

        return $total;
    }

    public function displayCart() {
        global $nl;
        echo "=========================================================$nl";
        echo "                   DANH SÁCH GIỎ HÀNG                    $nl";
        echo "=========================================================$nl";

        if (empty($this->items)) {
            echo "Giỏ hàng hiện đang trống.$nl";
            echo "Tổng tiền: 0 VNĐ$nl";
            echo "=========================================================$nl";
            return;
        }

        $index = 1;
        foreach ($this->items as $item) {
            echo "$index. Tên sản phẩm: " . $item->getName() . $nl;
            echo "   Đơn giá    : " . number_format($item->getPrice(), 0, ',', '.') . " VNĐ" . $nl;
            echo "   Số lượng   : " . $item->getQuantity() . $nl;
            echo "   Thành tiền : " . number_format($item->getTotal(), 0, ',', '.') . " VNĐ" . $nl;
            echo "---------------------------------------------------------$nl";
            $index++;
        }

        echo "TỔNG TIỀN THANH TOÁN: " . number_format($this->calculateTotal(), 0, ',', '.') . " VNĐ$nl";
        echo "=========================================================$nl";
    }

    public function getItems() {
        return $this->items;
    }
}

echo "=========================================================$nl";
echo "       BÀI 1: QUẢN LÝ GIỎ HÀNG BẰNG LẬP TRÌNH HƯỚNG ĐỐI TƯỢNG$nl";
echo "=========================================================$nl$nl";

echo "--- BƯỚC 1: Khởi tạo đối tượng ShoppingCart ---$nl";
$cart = new ShoppingCart();
echo "Khởi tạo giỏ hàng thành công!$nl$nl";

echo "--- BƯỚC 2: Khởi tạo ít nhất 04 CartItem hợp lệ ---$nl";
$item1 = new CartItem("Điện thoại iPhone 15 Pro", 27990000, 1);
$item2 = new CartItem("Laptop Asus Zenbook", 23500000, 1);
$item3 = new CartItem("Bàn phím cơ không dây", 1850000, 2);
$item4 = new CartItem("Tai nghe Bluetooth Sony", 4200000, 1);
echo "Đã tạo 04 sản phẩm thành công.$nl$nl";

echo "--- BƯỚC 3: Thêm sản phẩm vào giỏ hàng ---$nl";
$cart->addItem($item1);
$cart->addItem($item2);
$cart->addItem($item3);
$cart->addItem($item4);
echo "$nl";

echo "--- BƯỚC 4: Hiển thị giỏ hàng ban đầu ---$nl";
$cart->displayCart();
echo "$nl";

echo "--- BƯỚC 5: Tính và hiển thị tổng tiền của giỏ hàng ---$nl";
$total = $cart->calculateTotal();
echo "Tổng tiền thanh toán của giỏ hàng là: " . number_format($total, 0, ',', '.') . " VNĐ$nl$nl";

echo "--- BƯỚC 6: Xóa sản phẩm theo tên khỏi giỏ hàng ---$nl";
$cart->removeItem("Bàn phím cơ không dây");
echo "$nl";

echo "--- BƯỚC 7: Hiển thị lại giỏ hàng sau khi xóa ---$nl";
$cart->displayCart();
echo "$nl";

echo "=========================================================$nl";
echo "        KIỂM TRA CÁC TRƯỜNG HỢP ĐẶC BIỆT & KHÔNG HỢP LỆ     $nl";
echo "=========================================================$nl";

echo "1. Thử thêm sản phẩm có giá <= 0:$nl";
$invalidPriceItem = new CartItem("Ốp lưng khuyến mãi", 0, 2);
$cart->addItem($invalidPriceItem);
echo "$nl";

echo "2. Thử thêm sản phẩm có số lượng <= 0:$nl";
$invalidQtyItem = new CartItem("Cáp sạc Type-C", 150000, -3);
$cart->addItem($invalidQtyItem);
echo "$nl";

echo "3. Thử xóa sản phẩm không tồn tại trong giỏ hàng:$nl";
$cart->removeItem("Chuột Gaming Razer");
echo "$nl";

echo "4. Thử giỏ hàng rỗng (Empty Cart):$nl";
$emptyCart = new ShoppingCart();
echo "Tổng tiền của giỏ hàng rỗng: " . $emptyCart->calculateTotal() . " VNĐ$nl";
$emptyCart->displayCart();
?>
