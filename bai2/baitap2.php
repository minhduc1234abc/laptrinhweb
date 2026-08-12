<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tính tiền tài liệu</title>
</head>
<body>

<h2>THÔNG TIN TÀI LIỆU</h2>

<form method="post">
    <label>Tên tài liệu:</label>
    <input type="text" name="ten" required>
    <br><br>

    <label>Số lượng:</label>
    <input type="number" name="soluong" min="1" required>
    <br><br>

    <label>Đơn giá:</label>
    <input type="number" name="dongia" min="0" required>
    <br><br>

    <input type="submit" name="tinh" value="Tính">
</form>

<?php
if (isset($_POST['tinh'])) {
    $ten = $_POST['ten'];
    $soluong = $_POST['soluong'];
    $dongia = $_POST['dongia'];

    $thanhtien = $soluong * $dongia;

    echo "<h3>KẾT QUẢ</h3>";
    echo "Tên tài liệu: " . $ten . "<br>";
    echo "Số tiền: " . number_format($thanhtien) . " VNĐ";
}
?>

</body>
</html>