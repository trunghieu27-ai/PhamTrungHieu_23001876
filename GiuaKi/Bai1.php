
<?php
$name = "Nguyen Van A";
$score = 7.5;

if ($score < 0 || $score > 10) {
    $rank = "Điểm không hợp lệ (phải nằm trong khoảng 0-10)";
} elseif ($score >= 8) {
    $rank = "Giỏi";
} elseif ($score >= 6.5) {
    $rank = "Khá";
} elseif ($score >= 5) {
    $rank = "Trung bình";
} else {
    $rank = "Yếu";
}

echo "Tên học sinh: " . $name . "\n";
echo "Điểm: " . $score . "\n";
echo "Xếp loại: " . $rank;
?>


