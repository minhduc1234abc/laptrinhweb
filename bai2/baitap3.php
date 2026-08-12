<?php

// Tạo mảng gồm 3 sinh viên
$studen = [
    [
        "name" => "Nguyễn Văn An",
        "midteram" => 6,
        "final" => 8
    ],
    [
        "name" => "Trần Thị Bình",
        "midteram" => 7,
        "final" => 9
    ],
    [
        "name" => "Lê Văn Cường",
        "midteram" => 4,
        "final" => 5
    ]
];

// Hàm tính điểm trung bình
function calculateAverage($midteram, $final) {
    return ($midteram + $final) / 2;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Danh sách sinh viên</title>
</head>
<body>

<h2>Danh sách sinh viên</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>Tên sinh viên</th>
        <th>Midterm</th>
        <th>Final</th>
        <th>Điểm trung bình</th>
        <th>Kết quả</th>
    </tr>

    <?php foreach ($studen as $student): ?>

        <?php
        $average = calculateAverage(
            $student["midteram"],
            $student["final"]
        );

        $result = ($average >= 5) ? "Đạt" : "Chưa đạt";

        $safeName = htmlspecialchars($student["name"]);
        ?>

        <tr>
            <td><?= $safeName ?></td>
            <td><?= $student["midteram"] ?></td>
            <td><?= $student["final"] ?></td>
            <td><?= number_format($average, 2) ?></td>
            <td><?= $result ?></td>
        </tr>

    <?php endforeach; ?>

</table>

</body>
</html>