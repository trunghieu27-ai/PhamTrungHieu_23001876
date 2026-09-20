<?php

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

$totalScore = 0;
$numberOfStudents = count($students);

// Tự động chọn xuống dòng theo môi trường: Terminal (\n) hoặc Trình duyệt (<br>)
$nl = (php_sapi_name() === 'cli') ? "\n" : "<br>";

echo "Danh sach sinh vien:" . $nl;

foreach ($students as $student) {
    echo "Ho ten: " . $student["name"] . $nl;
    echo "Tuoi: " . $student["age"] . $nl;
    echo "Diem: " . $student["score"] . $nl;
    echo "-------------------------" . $nl;

    $totalScore += $student["score"];
}

$averageScore = $totalScore / $numberOfStudents;

echo "Diem trung binh: " . $averageScore . $nl;
?>