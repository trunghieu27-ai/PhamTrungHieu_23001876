<?php

// Dữ liệu danh sách sinh viên
$students = [
    [
        "name" => "Nguyen Van An",
        "age" => 20,
        "score" => 8.5
    ],
    [
        "name" => "Tran Thi Binh",
        "age" => 21,
        "score" => 6.5
    ],
    [
        "name" => "Le Van Cuong",
        "age" => 19,
        "score" => 4.5
    ],
    [
        "name" => "Pham Thi Dung",
        "age" => 20,
        "score" => 7.5
    ]
];

/**
 * 1. Tìm và trả về sinh viên có điểm cao nhất
 */
function findBestStudent($students) {
    if (empty($students)) {
        return null;
    }

    $best = $students[0];
    foreach ($students as $student) {
        if ($student["score"] > $best["score"]) {
            $best = $student;
        }
    }
    return $best;
}

/**
 * 2. Tìm và trả về sinh viên có điểm thấp nhất
 */
function findWorstStudent($students) {
    if (empty($students)) {
        return null;
    }

    $worst = $students[0];
    foreach ($students as $student) {
        if ($student["score"] < $worst["score"]) {
            $worst = $student;
        }
    }
    return $worst;
}

/**
 * 3. Đếm số sinh viên đạt (điểm >= 5)
 */
function countPassedStudents($students) {
    $count = 0;
    foreach ($students as $student) {
        if ($student["score"] >= 5.0) {
            $count++;
        }
    }
    return $count;
}

/**
 * 4. Tìm sinh viên theo tên (không phân biệt chữ hoa/thường)
 */
function findStudentByName($students, $name) {
    foreach ($students as $student) {
        // Tìm kiếm chứa từ khóa tên (ví dụ tìm "An" hoặc "Nguyen Van An")
        if (stripos($student["name"], trim($name)) !== false) {
            return $student;
        }
    }
    return null;
}

/**
 * Hàm hỗ trợ in nhanh thông tin sinh viên
 */
function printStudentInfo($student) {
    $nl = (php_sapi_name() === 'cli') ? "\n" : "<br>";
    if ($student === null) {
        echo "Không tìm thấy sinh viên!" . $nl;
    } else {
        echo "Họ tên: " . $student["name"] . " | Tuổi: " . $student["age"] . " | Điểm: " . $student["score"] . $nl;
    }
}

// ==========================================
// THỬ GỌI CÁC FUNCTION VÀ IN KẾT QUẢ
// ==========================================
$nl = (php_sapi_name() === 'cli') ? "\n" : "<br>";

echo "=== KET QUA XU LY DANH SACH SINH VIEN ===" . $nl . $nl;

// 1. Sinh viên có điểm cao nhất
echo "1. Sinh vien co diem cao nhat:" . $nl;
$bestStudent = findBestStudent($students);
printStudentInfo($bestStudent);
echo $nl;

// 2. Sinh viên có điểm thấp nhất
echo "2. Sinh vien co diem thap nhat:" . $nl;
$worstStudent = findWorstStudent($students);
printStudentInfo($worstStudent);
echo $nl;

// 3. Số sinh viên đạt (điểm >= 5)
$passedCount = countPassedStudents($students);
echo "3. So sinh vien dat (diem >= 5): " . $passedCount . " sinh vien" . $nl . $nl;

// 4. Tìm sinh viên theo tên
$searchName = "Tran Thi Binh";
echo "4. Tim kiem sinh vien voi tu khoa '$searchName':" . $nl;
$foundStudent = findStudentByName($students, $searchName);
printStudentInfo($foundStudent);

// Thử tìm kiếm 1 sinh viên không tồn tại
$searchNotFound = "Hoang Van Em";
echo $nl . "Tim kiem sinh vien '$searchNotFound':" . $nl;
$notFoundStudent = findStudentByName($students, $searchNotFound);
printStudentInfo($notFoundStudent);
?>
