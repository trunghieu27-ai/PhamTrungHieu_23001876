<?php

/**
 * Class Student quản lý thông tin và hành vi của sinh viên
 */
class Student {
    public $name;
    public $age;
    public $score;

    /**
     * Constructor khởi tạo thông tin sinh viên
     */
    public function __construct($name, $age, $score) {
        $this->name = $name;
        $this->age = $age;
        $this->score = $score;
    }

    /**
     * Method trả về xếp loại theo thang điểm
     */
    public function getRank() {
        if ($this->score >= 8.0) {
            return "Giỏi";
        } elseif ($this->score >= 6.5) {
            return "Khá";
        } elseif ($this->score >= 5.0) {
            return "Trung bình";
        } else {
            return "Yếu";
        }
    }

    /**
     * Method kiểm tra sinh viên có đạt (qua môn) hay không (điểm >= 5)
     */
    public function isPassed() {
        return $this->score >= 5.0;
    }

    /**
     * Method hiển thị thông tin sinh viên
     */
    public function display() {
        $nl = (php_sapi_name() === 'cli') ? "\n" : "<br>";
        echo "Ho ten: " . $this->name . $nl;
        echo "Tuoi: " . $this->age . $nl;
        echo "Diem: " . $this->score . $nl;
        echo "Xep loai: " . $this->getRank() . $nl;
        echo "Ket qua: " . ($this->isPassed() ? "Dat" : "Khong dat") . $nl;
        echo "-------------------------" . $nl;
    }
}

// =======================================================
// CÁC FUNCTION XỬ LÝ DANH SÁCH SINH VIÊN (TÁCH BIỆT LOGIC)
// =======================================================

/**
 * Tìm và trả về sinh viên có điểm cao nhất
 */
function findBestStudent($students) {
    if (empty($students)) {
        return null;
    }

    $best = $students[0];
    foreach ($students as $student) {
        if ($student->score > $best->score) {
            $best = $student;
        }
    }
    return $best;
}

/**
 * Đếm số sinh viên đạt (sử dụng method isPassed của Student)
 */
function countPassedStudents($students) {
    $count = 0;
    foreach ($students as $student) {
        if ($student->isPassed()) {
            $count++;
        }
    }
    return $count;
}

/**
 * Tính điểm trung bình của cả lớp
 */
function calculateAverageScore($students) {
    if (empty($students)) {
        return 0;
    }

    $totalScore = 0;
    foreach ($students as $student) {
        $totalScore += $student->score;
    }
    return $totalScore / count($students);
}

// =======================================================
// PHẦN CODE CHÍNH (MAIN)
// =======================================================
$nl = (php_sapi_name() === 'cli') ? "\n" : "<br>";

// 1. Tạo các object Student theo yêu cầu
$student1 = new Student("Nguyen Van An", 20, 8.5);
$student2 = new Student("Tran Thi Binh", 21, 6.5);
$student3 = new Student("Le Van Cuong", 19, 4.5);
$student4 = new Student("Pham Thi Dung", 20, 7.5);

// Tạo danh sách các object Student
$students = [$student1, $student2, $student3, $student4];

// 2. Duyệt danh sách và gọi display() để hiển thị thông tin
echo "=== DANH SACH SINH VIEN (OOP) ===" . $nl;
foreach ($students as $student) {
    $student->display();
}

// 3. Tìm sinh viên có điểm cao nhất
$bestStudent = findBestStudent($students);
echo $nl . "=== THONG KE LOP HOC ===" . $nl;
if ($bestStudent !== null) {
    echo "Sinh vien co diem cao nhat: " . $bestStudent->name . " (" . $bestStudent->score . " diem)" . $nl;
}

// 4. Đếm số sinh viên đạt
$passedCount = countPassedStudents($students);
echo "So sinh vien dat (diem >= 5): " . $passedCount . "/" . count($students) . " sinh vien" . $nl;

// 5. Tính điểm trung bình của lớp
$avgScore = calculateAverageScore($students);
echo "Diem trung binh ca lop: " . $avgScore . $nl;
?>
