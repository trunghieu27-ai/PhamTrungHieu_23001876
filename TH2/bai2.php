<?php

$nl = (php_sapi_name() === 'cli') ? "\n" : "<br>";

class Movie {
    private $id;
    private $title;
    private $price;
    private $totalSeats;
    private $availableSeats;

    public function __construct($id, $title, $price, $totalSeats) {
        $this->id = $id;
        $this->title = $title;
        $this->price = max(0, $price);
        $this->totalSeats = max(0, $totalSeats);
        $this->availableSeats = $this->totalSeats;
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getTotalSeats() {
        return $this->totalSeats;
    }

    public function getAvailableSeats() {
        return $this->availableSeats;
    }

    public function bookTicket($quantity) {
        global $nl;

        if ($quantity <= 0) {
            echo "[LỖI ĐẶT VÉ] Số lượng vé đặt phải lớn hơn 0 (Bạn đã yêu cầu: $quantity vé cho phim '{$this->title}')!$nl";
            return false;
        }

        if ($quantity > $this->availableSeats) {
            echo "[LỖI ĐẶT VÉ] Không đủ ghế! Phim '{$this->title}' chỉ còn {$this->availableSeats} ghế trống, không thể đặt $quantity vé!$nl";
            return false;
        }

        $this->availableSeats -= $quantity;
        echo "=> Đặt thành công $quantity vé cho phim '{$this->title}'. (Ghế còn lại: {$this->availableSeats}/{$this->totalSeats})$nl";
        return true;
    }

    public function cancelTicket($quantity) {
        global $nl;

        if ($quantity <= 0) {
            echo "[LỖI HỦY VÉ] Số lượng vé hủy phải lớn hơn 0 (Bạn đã yêu cầu: $quantity vé cho phim '{$this->title}')!$nl";
            return false;
        }

        $soldSeats = $this->getSoldSeats();
        if ($quantity > $soldSeats) {
            echo "[LỖI HỦY VÉ] Không thể hủy $quantity vé! Phim '{$this->title}' hiện tại mới chỉ bán được $soldSeats vé!$nl";
            return false;
        }

        $this->availableSeats += $quantity;
        echo "=> Hủy thành công $quantity vé của phim '{$this->title}'. (Ghế còn lại: {$this->availableSeats}/{$this->totalSeats})$nl";
        return true;
    }

    public function getSoldSeats() {
        return $this->totalSeats - $this->availableSeats;
    }

    public function getRevenue() {
        return $this->getSoldSeats() * $this->price;
    }

    public function displayInfo() {
        global $nl;
        echo "---------------------------------------------------------$nl";
        echo "Mã phim        : " . $this->id . $nl;
        echo "Tên phim       : " . $this->title . $nl;
        echo "Giá vé         : " . number_format($this->price, 0, ',', '.') . " VNĐ" . $nl;
        echo "Tổng số ghế    : " . $this->totalSeats . " ghế" . $nl;
        echo "Số ghế còn lại : " . $this->availableSeats . " ghế" . $nl;
        echo "Số vé đã bán   : " . $this->getSoldSeats() . " vé" . $nl;
        echo "Doanh thu      : " . number_format($this->getRevenue(), 0, ',', '.') . " VNĐ" . $nl;
        echo "---------------------------------------------------------$nl";
    }
}

function findMovieById($movies, $id) {
    if (empty($movies) || !is_array($movies)) {
        return null;
    }

    foreach ($movies as $movie) {
        if ($movie instanceof Movie && $movie->getId() == $id) {
            return $movie;
        }
    }

    return null;
}

function getTotalRevenue($movies) {
    if (empty($movies) || !is_array($movies)) {
        return 0;
    }

    $totalRevenue = 0;
    foreach ($movies as $movie) {
        if ($movie instanceof Movie) {
            $totalRevenue += $movie->getRevenue();
        }
    }

    return $totalRevenue;
}

function getBestSellingMovie($movies) {
    if (empty($movies) || !is_array($movies)) {
        return null;
    }

    $bestSelling = null;
    $maxSold = -1;

    foreach ($movies as $movie) {
        if ($movie instanceof Movie) {
            $sold = $movie->getSoldSeats();
            if ($sold > $maxSold) {
                $maxSold = $sold;
                $bestSelling = $movie;
            }
        }
    }

    return $bestSelling;
}

echo "=========================================================$nl";
echo "              BÀI 2: QUẢN LÝ VÉ XEM PHIM                $nl";
echo "=========================================================$nl$nl";

echo "--- BƯỚC 1: Khởi tạo danh sách các đối tượng Movie ---$nl";
$movies = [
    new Movie(1, "Avengers", 100000, 100),
    new Movie(2, "Avatar", 120000, 80),
    new Movie(3, "Batman", 90000, 120)
];
echo "Đã khởi tạo thành công 03 bộ phim trong danh sách.$nl$nl";

echo "--- BƯỚC 2: Đặt vé cho phim Avengers ---$nl";
$avengers = findMovieById($movies, 1);
if ($avengers !== null) {
    $avengers->bookTicket(30);
}
echo "$nl";

echo "--- BƯỚC 3: Đặt vé cho phim Avatar ---$nl";
$avatar = findMovieById($movies, 2);
if ($avatar !== null) {
    $avatar->bookTicket(50);
}
echo "$nl";

echo "--- BƯỚC 4: Hủy vé đã đặt của phim Avengers ---$nl";
if ($avengers !== null) {
    $avengers->cancelTicket(10);
}
echo "$nl";

echo "--- BƯỚC 5: Hiển thị thông tin tất cả các phim ---$nl";
echo "DANH SÁCH CHI TIẾT TẤT CẢ CÁC BỘ PHIM:$nl";
foreach ($movies as $movie) {
    $movie->displayInfo();
}
echo "$nl";

echo "--- BƯỚC 6: Tính tổng doanh thu của tất cả các phim ---$nl";
$totalRevenue = getTotalRevenue($movies);
echo "TỔNG DOANH THU TẤT CẢ CÁC BỘ PHIM: " . number_format($totalRevenue, 0, ',', '.') . " VNĐ$nl$nl";

echo "--- BƯỚC 7: Tìm và hiển thị phim có số vé bán ra nhiều nhất ---$nl";
$bestMovie = getBestSellingMovie($movies);
if ($bestMovie !== null) {
    echo "PHIM BÁN CHẠY NHẤT LÀ: '{$bestMovie->getTitle()}' với {$bestMovie->getSoldSeats()} vé đã bán!$nl";
    echo "Chi tiết phim bán chạy nhất:$nl";
    $bestMovie->displayInfo();
} else {
    echo "Không tìm thấy phim bán chạy nhất.$nl";
}
echo "$nl";

echo "=========================================================$nl";
echo "        KIỂM TRA CÁC TRƯỜNG HỢP BẮT BUỘC PHẢI XỬ LÝ      $nl";
echo "=========================================================$nl";

echo "1. Thử đặt số vé <= 0 (ví dụ: -5 vé và 0 vé):$nl";
$avengers->bookTicket(-5);
$avengers->bookTicket(0);
echo "$nl";

echo "2. Thử đặt số vé lớn hơn số ghế còn lại:$nl";
echo "(Phim Avatar hiện còn {$avatar->getAvailableSeats()} ghế, thử đặt 100 vé):$nl";
$avatar->bookTicket(100);
echo "$nl";

echo "3. Thử hủy số vé <= 0 (ví dụ: 0 vé):$nl";
$avengers->cancelTicket(0);
echo "$nl";

echo "4. Thử hủy số vé lớn hơn số vé đã bán:$nl";
$batman = findMovieById($movies, 3);
echo "(Phim Batman đã bán {$batman->getSoldSeats()} vé, thử hủy 5 vé):$nl";
$batman->cancelTicket(5);
echo "$nl";

echo "5. Thử tìm phim với ID không tồn tại (ID: 999):$nl";
$notFoundMovie = findMovieById($movies, 999);
if ($notFoundMovie === null) {
    echo "[THÔNG BÁO] Không tìm thấy bộ phim có ID = 999 trong danh sách!$nl";
} else {
    $notFoundMovie->displayInfo();
}
echo "$nl";

echo "6. Thử gọi các function với danh sách phim rỗng ([]):$nl";
$emptyMoviesList = [];

$foundInEmpty = findMovieById($emptyMoviesList, 1);
echo "a. findMovieById với danh sách rỗng: " . ($foundInEmpty === null ? "null (Chính xác, không phát sinh lỗi)" : "Có kết quả") . $nl;

$revInEmpty = getTotalRevenue($emptyMoviesList);
echo "b. getTotalRevenue với danh sách rỗng: " . number_format($revInEmpty, 0, ',', '.') . " VNĐ (Chính xác, không phát sinh lỗi)" . $nl;

$bestInEmpty = getBestSellingMovie($emptyMoviesList);
echo "c. getBestSellingMovie với danh sách rỗng: " . ($bestInEmpty === null ? "null (Chính xác, không phát sinh lỗi)" : "Có kết quả") . $nl;

echo "=========================================================$nl";
?>
