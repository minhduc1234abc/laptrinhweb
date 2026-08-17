<?php

function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function chuanHoa($str) {
    $str = trim($str);
    $str = preg_replace('/\s+/', ' ', $str);
    return $str;
}

$phong_hop_le = ["P101", "P102", "P103"];

$errors = ["baohong" => [], "datphong" => [], "muonthietbi" => []];
$old = [
    "baohong" => ["ma_thiet_bi" => "", "ten_thiet_bi" => "", "phong" => "", "mo_ta_loi" => ""],
    "datphong" => ["ma_sv" => "", "ho_ten" => "", "phong" => "", "ngay_su_dung" => "", "gio_bat_dau" => "", "gio_ket_thuc" => "", "ly_do" => ""],
    "muonthietbi" => ["ma_sv" => "", "ho_ten" => "", "ten_thiet_bi" => "", "so_luong" => "", "ngay_muon" => "", "ngay_tra" => "", "ly_do" => ""]
];
$success = ["baohong" => false, "datphong" => false, "muonthietbi" => false];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST["action"] ?? "";

    if ($action == "baohong") {

        $ma_thiet_bi = chuanHoa($_POST["ma_thiet_bi"] ?? "");
        $ten_thiet_bi = chuanHoa($_POST["ten_thiet_bi"] ?? "");
        $phong = chuanHoa($_POST["phong"] ?? "");
        $mo_ta_loi = chuanHoa($_POST["mo_ta_loi"] ?? "");

        $old["baohong"] = compact("ma_thiet_bi", "ten_thiet_bi", "phong", "mo_ta_loi");

        if ($ma_thiet_bi === "") {
            $errors["baohong"]["ma_thiet_bi"] = "Vui lòng nhập mã thiết bị.";
        } elseif (!preg_match('/^[A-Za-z]{2}[0-9]{3}$/', $ma_thiet_bi)) {
            $errors["baohong"]["ma_thiet_bi"] = "Mã thiết bị không đúng định dạng (VD: TB001).";
        }

        if ($ten_thiet_bi === "") {
            $errors["baohong"]["ten_thiet_bi"] = "Vui lòng nhập tên thiết bị.";
        } elseif (mb_strlen($ten_thiet_bi) < 3 || mb_strlen($ten_thiet_bi) > 100) {
            $errors["baohong"]["ten_thiet_bi"] = "Tên thiết bị phải từ 3 đến 100 ký tự.";
        }

        if ($phong === "") {
            $errors["baohong"]["phong"] = "Vui lòng chọn phòng.";
        } elseif (!in_array($phong, $phong_hop_le)) {
            $errors["baohong"]["phong"] = "Phòng không hợp lệ.";
        }

        if ($mo_ta_loi === "") {
            $errors["baohong"]["mo_ta_loi"] = "Vui lòng mô tả lỗi.";
        } elseif (mb_strlen($mo_ta_loi) < 10 || mb_strlen($mo_ta_loi) > 300) {
            $errors["baohong"]["mo_ta_loi"] = "Mô tả lỗi phải từ 10 đến 300 ký tự.";
        }

        if (empty($errors["baohong"])) {
            $success["baohong"] = true;
            $old["baohong"] = ["ma_thiet_bi" => "", "ten_thiet_bi" => "", "phong" => "", "mo_ta_loi" => ""];
        }
    }

    if ($action == "datphong") {

        $ma_sv = chuanHoa($_POST["ma_sv"] ?? "");
        $ho_ten = chuanHoa($_POST["ho_ten"] ?? "");
        $phong = chuanHoa($_POST["phong"] ?? "");
        $ngay_su_dung = chuanHoa($_POST["ngay_su_dung"] ?? "");
        $gio_bat_dau = chuanHoa($_POST["gio_bat_dau"] ?? "");
        $gio_ket_thuc = chuanHoa($_POST["gio_ket_thuc"] ?? "");
        $ly_do = chuanHoa($_POST["ly_do"] ?? "");

        $old["datphong"] = compact("ma_sv", "ho_ten", "phong", "ngay_su_dung", "gio_bat_dau", "gio_ket_thuc", "ly_do");

        if ($ma_sv === "") {
            $errors["datphong"]["ma_sv"] = "Vui lòng nhập mã sinh viên.";
        } elseif (!preg_match('/^SV[0-9]{4,6}$/', $ma_sv)) {
            $errors["datphong"]["ma_sv"] = "Mã sinh viên không đúng định dạng (VD: SV2024).";
        }

        if ($ho_ten === "") {
            $errors["datphong"]["ho_ten"] = "Vui lòng nhập họ tên.";
        } elseif (!preg_match('/^[\p{L}\s]{3,100}$/u', $ho_ten)) {
            $errors["datphong"]["ho_ten"] = "Họ tên chỉ được chứa chữ cái, từ 3 đến 100 ký tự.";
        }

        if ($phong === "") {
            $errors["datphong"]["phong"] = "Vui lòng chọn phòng.";
        } elseif (!in_array($phong, $phong_hop_le)) {
            $errors["datphong"]["phong"] = "Phòng không hợp lệ.";
        }

        if ($ngay_su_dung === "") {
            $errors["datphong"]["ngay_su_dung"] = "Vui lòng chọn ngày sử dụng.";
        } elseif (strtotime($ngay_su_dung) === false || $ngay_su_dung < date("Y-m-d")) {
            $errors["datphong"]["ngay_su_dung"] = "Ngày sử dụng phải từ hôm nay trở đi.";
        }

        if ($gio_bat_dau === "" || $gio_ket_thuc === "") {
            $errors["datphong"]["gio"] = "Vui lòng chọn giờ bắt đầu và giờ kết thúc.";
        } elseif ($gio_ket_thuc <= $gio_bat_dau) {
            $errors["datphong"]["gio"] = "Giờ kết thúc phải sau giờ bắt đầu.";
        }

        if ($ly_do === "") {
            $errors["datphong"]["ly_do"] = "Vui lòng nhập lý do đặt phòng.";
        } elseif (mb_strlen($ly_do) < 10 || mb_strlen($ly_do) > 300) {
            $errors["datphong"]["ly_do"] = "Lý do phải từ 10 đến 300 ký tự.";
        }

        if (empty($errors["datphong"])) {
            $success["datphong"] = true;
            $old["datphong"] = ["ma_sv" => "", "ho_ten" => "", "phong" => "", "ngay_su_dung" => "", "gio_bat_dau" => "", "gio_ket_thuc" => "", "ly_do" => ""];
        }
    }

    if ($action == "muonthietbi") {

        $ma_sv = chuanHoa($_POST["ma_sv"] ?? "");
        $ho_ten = chuanHoa($_POST["ho_ten"] ?? "");
        $ten_thiet_bi = chuanHoa($_POST["ten_thiet_bi"] ?? "");
        $so_luong = chuanHoa($_POST["so_luong"] ?? "");
        $ngay_muon = chuanHoa($_POST["ngay_muon"] ?? "");
        $ngay_tra = chuanHoa($_POST["ngay_tra"] ?? "");
        $ly_do = chuanHoa($_POST["ly_do"] ?? "");

        $old["muonthietbi"] = compact("ma_sv", "ho_ten", "ten_thiet_bi", "so_luong", "ngay_muon", "ngay_tra", "ly_do");

        if ($ma_sv === "") {
            $errors["muonthietbi"]["ma_sv"] = "Vui lòng nhập mã sinh viên.";
        } elseif (!preg_match('/^SV[0-9]{4,6}$/', $ma_sv)) {
            $errors["muonthietbi"]["ma_sv"] = "Mã sinh viên không đúng định dạng (VD: SV2024).";
        }

        if ($ho_ten === "") {
            $errors["muonthietbi"]["ho_ten"] = "Vui lòng nhập họ tên.";
        } elseif (!preg_match('/^[\p{L}\s]{3,100}$/u', $ho_ten)) {
            $errors["muonthietbi"]["ho_ten"] = "Họ tên chỉ được chứa chữ cái, từ 3 đến 100 ký tự.";
        }

        if ($ten_thiet_bi === "") {
            $errors["muonthietbi"]["ten_thiet_bi"] = "Vui lòng nhập tên thiết bị.";
        } elseif (mb_strlen($ten_thiet_bi) < 3 || mb_strlen($ten_thiet_bi) > 100) {
            $errors["muonthietbi"]["ten_thiet_bi"] = "Tên thiết bị phải từ 3 đến 100 ký tự.";
        }

        if ($so_luong === "") {
            $errors["muonthietbi"]["so_luong"] = "Vui lòng nhập số lượng.";
        } elseif (!ctype_digit($so_luong) || (int)$so_luong < 1 || (int)$so_luong > 50) {
            $errors["muonthietbi"]["so_luong"] = "Số lượng phải là số nguyên từ 1 đến 50.";
        }

        if ($ngay_muon === "" || $ngay_tra === "") {
            $errors["muonthietbi"]["ngay"] = "Vui lòng chọn ngày mượn và ngày trả.";
        } elseif (strtotime($ngay_muon) === false || strtotime($ngay_tra) === false) {
            $errors["muonthietbi"]["ngay"] = "Ngày không hợp lệ.";
        } elseif ($ngay_muon < date("Y-m-d")) {
            $errors["muonthietbi"]["ngay"] = "Ngày mượn phải từ hôm nay trở đi.";
        } elseif ($ngay_tra <= $ngay_muon) {
            $errors["muonthietbi"]["ngay"] = "Ngày trả phải sau ngày mượn.";
        }

        if ($ly_do === "") {
            $errors["muonthietbi"]["ly_do"] = "Vui lòng nhập lý do mượn.";
        } elseif (mb_strlen($ly_do) < 10 || mb_strlen($ly_do) > 300) {
            $errors["muonthietbi"]["ly_do"] = "Lý do phải từ 10 đến 300 ký tự.";
        }

        if (empty($errors["muonthietbi"])) {
            $success["muonthietbi"] = true;
            $old["muonthietbi"] = ["ma_sv" => "", "ho_ten" => "", "ten_thiet_bi" => "", "so_luong" => "", "ngay_muon" => "", "ngay_tra" => "", "ly_do" => ""];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Yêu cầu sinh viên</title>

    <style>

        body {
            background-color: #f4f7f9;
            color: #333;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 30px;
        }

        .container {
            width: 900px;
            max-width: 95%;
            margin: auto;
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        h1 {
            text-align: center;
            color: #003399;
            margin-bottom: 10px;
        }

        h2 {
            color: #003399;
            margin-top: 30px;
        }

        .description {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }

        form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        label {
            font-weight: bold;
        }

        button {
            background-color: #003399;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #002266;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #003399;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .error-text {
            color: #cc0000;
            font-weight: normal;
            font-size: 13px;
            margin-top: -10px;
            margin-bottom: 15px;
        }

        .alert-success {
            background-color: #e6f4ea;
            color: #1e7e34;
            border: 1px solid #b7dfc0;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .input-error {
            border-color: #cc0000 !important;
            background-color: #fff5f5;
        }

    </style>

</head>


<body>


<div class="container">

    <h1>YÊU CẦU SINH VIÊN</h1>

    <p class="description">
        Báo hỏng thiết bị, gửi yêu cầu đặt phòng và mượn thiết bị thực hành
    </p>


    <h2>Báo hỏng thiết bị</h2>

    <?php if ($success["baohong"]): ?>
        <div class="alert-success">Đã gửi báo hỏng thiết bị thành công.</div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="action" value="baohong">

        <label>Mã thiết bị</label>
        <input type="text" name="ma_thiet_bi" placeholder="Ví dụ: TB001"
            value="<?php echo e($old["baohong"]["ma_thiet_bi"]); ?>"
            class="<?php echo isset($errors["baohong"]["ma_thiet_bi"]) ? "input-error" : ""; ?>">
        <?php if (isset($errors["baohong"]["ma_thiet_bi"])): ?>
            <p class="error-text"><?php echo e($errors["baohong"]["ma_thiet_bi"]); ?></p>
        <?php endif; ?>

        <label>Tên thiết bị</label>
        <input type="text" name="ten_thiet_bi" placeholder="Ví dụ: Máy chiếu phòng 101"
            value="<?php echo e($old["baohong"]["ten_thiet_bi"]); ?>"
            class="<?php echo isset($errors["baohong"]["ten_thiet_bi"]) ? "input-error" : ""; ?>">
        <?php if (isset($errors["baohong"]["ten_thiet_bi"])): ?>
            <p class="error-text"><?php echo e($errors["baohong"]["ten_thiet_bi"]); ?></p>
        <?php endif; ?>

        <label>Phòng</label>
        <select name="phong" class="<?php echo isset($errors["baohong"]["phong"]) ? "input-error" : ""; ?>">
            <option value="">-- Chọn phòng --</option>
            <?php foreach ($phong_hop_le as $p): ?>
                <option value="<?php echo e($p); ?>" <?php echo $old["baohong"]["phong"] === $p ? "selected" : ""; ?>><?php echo e($p); ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors["baohong"]["phong"])): ?>
            <p class="error-text"><?php echo e($errors["baohong"]["phong"]); ?></p>
        <?php endif; ?>

        <label>Mô tả lỗi</label>
        <textarea name="mo_ta_loi" rows="3" placeholder="Mô tả chi tiết lỗi thiết bị..."
            class="<?php echo isset($errors["baohong"]["mo_ta_loi"]) ? "input-error" : ""; ?>"><?php echo e($old["baohong"]["mo_ta_loi"]); ?></textarea>
        <?php if (isset($errors["baohong"]["mo_ta_loi"])): ?>
            <p class="error-text"><?php echo e($errors["baohong"]["mo_ta_loi"]); ?></p>
        <?php endif; ?>

        <button type="submit">Gửi báo hỏng</button>
    </form>


    <h2>Gửi yêu cầu đặt phòng</h2>

    <?php if ($success["datphong"]): ?>
        <div class="alert-success">Đã gửi yêu cầu đặt phòng thành công.</div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="action" value="datphong">

        <label>Mã sinh viên</label>
        <input type="text" name="ma_sv" placeholder="Ví dụ: SV2024"
            value="<?php echo e($old["datphong"]["ma_sv"]); ?>"
            class="<?php echo isset($errors["datphong"]["ma_sv"]) ? "input-error" : ""; ?>">
        <?php if (isset($errors["datphong"]["ma_sv"])): ?>
            <p class="error-text"><?php echo e($errors["datphong"]["ma_sv"]); ?></p>
        <?php endif; ?>

        <label>Họ tên</label>
        <input type="text" name="ho_ten" placeholder="Ví dụ: Lê Văn Hào"
            value="<?php echo e($old["datphong"]["ho_ten"]); ?>"
            class="<?php echo isset($errors["datphong"]["ho_ten"]) ? "input-error" : ""; ?>">
        <?php if (isset($errors["datphong"]["ho_ten"])): ?>
            <p class="error-text"><?php echo e($errors["datphong"]["ho_ten"]); ?></p>
        <?php endif; ?>

        <label>Phòng</label>
        <select name="phong" class="<?php echo isset($errors["datphong"]["phong"]) ? "input-error" : ""; ?>">
            <option value="">-- Chọn phòng --</option>
            <?php foreach ($phong_hop_le as $p): ?>
                <option value="<?php echo e($p); ?>" <?php echo $old["datphong"]["phong"] === $p ? "selected" : ""; ?>><?php echo e($p); ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors["datphong"]["phong"])): ?>
            <p class="error-text"><?php echo e($errors["datphong"]["phong"]); ?></p>
        <?php endif; ?>

        <label>Ngày sử dụng</label>
        <input type="date" name="ngay_su_dung"
            value="<?php echo e($old["datphong"]["ngay_su_dung"]); ?>"
            class="<?php echo isset($errors["datphong"]["ngay_su_dung"]) ? "input-error" : ""; ?>">
        <?php if (isset($errors["datphong"]["ngay_su_dung"])): ?>
            <p class="error-text"><?php echo e($errors["datphong"]["ngay_su_dung"]); ?></p>
        <?php endif; ?>

        <label>Giờ bắt đầu</label>
        <input type="time" name="gio_bat_dau"
            value="<?php echo e($old["datphong"]["gio_bat_dau"]); ?>"
            class="<?php echo isset($errors["datphong"]["gio"]) ? "input-error" : ""; ?>">

        <label>Giờ kết thúc</label>
        <input type="time" name="gio_ket_thuc"
            value="<?php echo e($old["datphong"]["gio_ket_thuc"]); ?>"
            class="<?php echo isset($errors["datphong"]["gio"]) ? "input-error" : ""; ?>">
        <?php if (isset($errors["datphong"]["gio"])): ?>
            <p class="error-text"><?php echo e($errors["datphong"]["gio"]); ?></p>
        <?php endif; ?>

        <label>Lý do đặt phòng</label>
        <textarea name="ly_do" rows="3" placeholder="Nhập lý do đặt phòng..."
            class="<?php echo isset($errors["datphong"]["ly_do"]) ? "input-error" : ""; ?>"><?php echo e($old["datphong"]["ly_do"]); ?></textarea>
        <?php if (isset($errors["datphong"]["ly_do"])): ?>
            <p class="error-text"><?php echo e($errors["datphong"]["ly_do"]); ?></p>
        <?php endif; ?>

        <button type="submit">Gửi yêu cầu đặt phòng</button>
    </form>


    <h2>Gửi yêu cầu mượn thiết bị</h2>

    <?php if ($success["muonthietbi"]): ?>
        <div class="alert-success">Đã gửi yêu cầu mượn thiết bị thành công.</div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="action" value="muonthietbi">

        <label>Mã sinh viên</label>
        <input type="text" name="ma_sv" placeholder="Ví dụ: SV2024"
            value="<?php echo e($old["muonthietbi"]["ma_sv"]); ?>"
            class="<?php echo isset($errors["muonthietbi"]["ma_sv"]) ? "input-error" : ""; ?>">
        <?php if (isset($errors["muonthietbi"]["ma_sv"])): ?>
            <p class="error-text"><?php echo e($errors["muonthietbi"]["ma_sv"]); ?></p>
        <?php endif; ?>

        <label>Họ tên</label>
        <input type="text" name="ho_ten" placeholder="Ví dụ: Lê Văn Hào"
            value="<?php echo e($old["muonthietbi"]["ho_ten"]); ?>"
            class="<?php echo isset($errors["muonthietbi"]["ho_ten"]) ? "input-error" : ""; ?>">
        <?php if (isset($errors["muonthietbi"]["ho_ten"])): ?>
            <p class="error-text"><?php echo e($errors["muonthietbi"]["ho_ten"]); ?></p>
        <?php endif; ?>

        <label>Tên thiết bị</label>
        <input type="text" name="ten_thiet_bi" placeholder="Ví dụ: Laptop Dell"
            value="<?php echo e($old["muonthietbi"]["ten_thiet_bi"]); ?>"
            class="<?php echo isset($errors["muonthietbi"]["ten_thiet_bi"]) ? "input-error" : ""; ?>">
        <?php if (isset($errors["muonthietbi"]["ten_thiet_bi"])): ?>
            <p class="error-text"><?php echo e($errors["muonthietbi"]["ten_thiet_bi"]); ?></p>
        <?php endif; ?>

        <label>Số lượng</label>
        <input type="number" name="so_luong" min="1" max="50" placeholder="Ví dụ: 1"
            value="<?php echo e($old["muonthietbi"]["so_luong"]); ?>"
            class="<?php echo isset($errors["muonthietbi"]["so_luong"]) ? "input-error" : ""; ?>">
        <?php if (isset($errors["muonthietbi"]["so_luong"])): ?>
            <p class="error-text"><?php echo e($errors["muonthietbi"]["so_luong"]); ?></p>
        <?php endif; ?>

        <label>Ngày mượn</label>
        <input type="date" name="ngay_muon"
            value="<?php echo e($old["muonthietbi"]["ngay_muon"]); ?>"
            class="<?php echo isset($errors["muonthietbi"]["ngay"]) ? "input-error" : ""; ?>">

        <label>Ngày trả</label>
        <input type="date" name="ngay_tra"
            value="<?php echo e($old["muonthietbi"]["ngay_tra"]); ?>"
            class="<?php echo isset($errors["muonthietbi"]["ngay"]) ? "input-error" : ""; ?>">
        <?php if (isset($errors["muonthietbi"]["ngay"])): ?>
            <p class="error-text"><?php echo e($errors["muonthietbi"]["ngay"]); ?></p>
        <?php endif; ?>

        <label>Lý do mượn</label>
        <textarea name="ly_do" rows="3" placeholder="Nhập lý do mượn thiết bị..."
            class="<?php echo isset($errors["muonthietbi"]["ly_do"]) ? "input-error" : ""; ?>"><?php echo e($old["muonthietbi"]["ly_do"]); ?></textarea>
        <?php if (isset($errors["muonthietbi"]["ly_do"])): ?>
            <p class="error-text"><?php echo e($errors["muonthietbi"]["ly_do"]); ?></p>
        <?php endif; ?>

        <button type="submit">Gửi yêu cầu mượn thiết bị</button>
    </form>

</div>


</body>

</html>