<?php

// Dữ liệu danh sách sinh viên từ Bài 1
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
 * 1. Hàm tính điểm trung bình của danh sách sinh viên
 */
function calculateAverageScore($students) {
    $totalStudents = count($students);
    if ($totalStudents === 0) {
        return 0;
    }

    $totalScore = 0;
    foreach ($students as $student) {
        $totalScore += $student["score"];
    }

    return $totalScore / $totalStudents;
}

/**
 * 2. Hàm trả về xếp loại dựa vào điểm số
 */
function getRank($score) {
    if ($score >= 8.0) {
        return "Giỏi";
    } elseif ($score >= 6.5) {
        return "Khá";
    } elseif ($score >= 5.0) {
        return "Trung bình";
    } else {
        return "Yếu";
    }
}

/**
 * 3. Hàm hiển thị thông tin của một sinh viên (gồm cả xếp loại)
 */
function displayStudent($student) {
    $nl = (php_sapi_name() === 'cli') ? "\n" : "<br>";
    $rank = getRank($student["score"]);

    echo "Ho ten: " . $student["name"] . $nl;
    echo "Tuoi: " . $student["age"] . $nl;
    echo "Diem: " . $student["score"] . $nl;
    echo "Xep loai: " . $rank . $nl;
    echo "-------------------------" . $nl;
}

// ==========================================
// PHẦN CODE CHÍNH: GỌI CÁC FUNCTION ĐÃ TÁCH
// ==========================================
$nl = (php_sapi_name() === 'cli') ? "\n" : "<br>";

echo "=== THONG TIN SINH VIEN ===" . $nl;

// Hiển thị thông tin 1 sinh viên (ví dụ sinh viên đầu tiên trong danh sách)
displayStudent($students[0]);


