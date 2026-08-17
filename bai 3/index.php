<?php
function e($v) {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

function checkTime($start, $end) {
    return $end > $start;
}

$rooms = [
    ["id"=>"P101","name"=>"Phòng máy 101","capacity"=>40,"status"=>"Trống"],
    ["id"=>"P102","name"=>"Phòng máy 102","capacity"=>35,"status"=>"Đang sử dụng"],
    ["id"=>"P103","name"=>"Phòng máy 103","capacity"=>45,"status"=>"Bảo trì"],
    ["id"=>"P104","name"=>"Phòng máy 104","capacity"=>40,"status"=>"Trống"]
];

$data = [
    "name"=>"",
    "email"=>"",
    "room"=>"",
    "date"=>"",
    "start"=>"",
    "end"=>"",
    "purpose"=>""
];

$errors = [];
$success = "";
$submitted = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $submitted = true;

    foreach ($data as $key => $value) {
        $data[$key] = trim($_POST[$key] ?? "");
    }

    if ($data["name"] === "")
        $errors["name"] = "Vui lòng nhập họ và tên.";
    elseif (mb_strlen($data["name"]) < 3)
        $errors["name"] = "Họ tên phải có ít nhất 3 ký tự.";
    elseif (mb_strlen($data["name"]) > 50)
        $errors["name"] = "Họ tên tối đa 50 ký tự.";

    if ($data["email"] === "")
        $errors["email"] = "Vui lòng nhập email.";
    elseif (!filter_var($data["email"], FILTER_VALIDATE_EMAIL))
        $errors["email"] = "Email không đúng định dạng.";

    $validRooms = [];
    foreach ($rooms as $room) {
        $validRooms[] = $room["id"];
    }

    if ($data["room"] === "")
        $errors["room"] = "Vui lòng chọn phòng.";
    elseif (!in_array($data["room"], $validRooms))
        $errors["room"] = "Phòng không hợp lệ.";

    if ($data["date"] === "")
        $errors["date"] = "Vui lòng chọn ngày.";

    if ($data["start"] === "")
        $errors["start"] = "Vui lòng chọn giờ bắt đầu.";

    if ($data["end"] === "")
        $errors["end"] = "Vui lòng chọn giờ kết thúc.";
    elseif ($data["start"] !== "" && !checkTime($data["start"], $data["end"]))
        $errors["end"] = "Giờ kết thúc phải lớn hơn giờ bắt đầu.";

    if ($data["purpose"] === "")
        $errors["purpose"] = "Vui lòng nhập mục đích.";
    elseif (mb_strlen($data["purpose"]) < 10)
        $errors["purpose"] = "Mục đích phải có ít nhất 10 ký tự.";
    elseif (mb_strlen($data["purpose"]) > 200)
        $errors["purpose"] = "Mục đích tối đa 200 ký tự.";

    if (empty($errors)) {
        $success = "Gửi yêu cầu đặt phòng thành công!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Portal</title>

<style>
*{box-sizing:border-box}
body{
    margin:0;
    font-family:Arial,sans-serif;
    background:#f4f7f9;
    color:#333
}
:root{
    --blue:#003399;
    --border:#ddd;
    --red:#d32f2f;
    --green:#207a45
}
.sidebar{
    position:fixed;
    width:220px;
    height:100vh;
    background:white;
    border-right:1px solid var(--border);
    padding:25px 15px
}
.logo{
    color:var(--blue);
    font-weight:bold;
    font-size:18px;
    margin-bottom:35px
}
.menu-title{
    font-size:12px;
    color:#999;
    margin-bottom:10px
}
.menu a{
    display:block;
    padding:12px;
    margin-bottom:5px;
    border-radius:6px;
    text-decoration:none;
    color:#444
}
.menu a:hover{
    background:#eef4ff;
    color:var(--blue)
}
.main{
    margin-left:220px;
    min-height:100vh
}
.topbar{
    height:60px;
    background:white;
    border-bottom:1px solid var(--border);
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 30px
}
.content{padding:30px}
h1,h2,h3{color:var(--blue)}
.welcome p{color:#777}
.stats{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin:25px 0
}
.stat,.card,.room,.form{
    background:white;
    border:1px solid var(--border);
    border-radius:8px;
    padding:20px
}
.stat strong{
    font-size:25px;
    color:var(--blue)
}
.stat p{color:#777}
.cards,.rooms{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px
}
.card p{color:#777;line-height:1.5}
.card a{
    color:var(--blue);
    text-decoration:none;
    font-weight:bold
}
.section{margin-top:45px}
.room h3{margin-top:0}
.status{
    display:inline-block;
    padding:5px 10px;
    border-radius:15px;
    font-size:12px
}
.available{background:#e5f7eb;color:var(--green)}
.busy{background:#ffe5e5;color:var(--red)}
.maintenance{background:#fff3cd;color:#856404}
.form{max-width:700px}
.group{margin-bottom:17px}
label{
    display:block;
    font-weight:bold;
    margin-bottom:6px
}
input,select,textarea{
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:5px;
    font-family:Arial
}
textarea{
    min-height:100px;
    resize:vertical
}
.error-input{
    border-color:var(--red)!important;
    background:#fffafa
}
.error{
    color:var(--red);
    font-size:13px;
    margin-top:5px
}
.success{
    max-width:700px;
    background:#e6f7ed;
    color:var(--green);
    padding:12px;
    border-radius:5px;
    margin-bottom:15px
}
button{
    background:var(--blue);
    color:white;
    border:0;
    padding:11px 18px;
    border-radius:5px;
    cursor:pointer
}
button:hover{background:#002266}

@media(max-width:800px){
    .sidebar{width:180px}
    .main{margin-left:180px}
    .stats,.cards,.rooms{grid-template-columns:1fr}
}
</style>
</head>

<body>

<aside class="sidebar">
    <div class="logo">🏫 LAB MANAGEMENT</div>

    <div class="menu-title">MENU SINH VIÊN</div>

    <nav class="menu">
        <a href="#home">🏠 Trang chủ</a>
        <a href="#rooms">🏫 Phòng thực hành</a>
        <a href="#booking">📅 Đặt phòng</a>
        <a href="#report">🔧 Báo hỏng</a>
    </nav>
</aside>

<main class="main">

<header class="topbar">
    <span>Student Portal</span>
    <strong>👤 Nguyễn Văn A</strong>
</header>

<div class="content">

<section id="home">

    <div class="welcome">
        <h1>Xin chào, Nguyễn Văn A! 👋</h1>
        <p>Chào mừng bạn đến với hệ thống quản lý phòng thực hành.</p>
    </div>

    <div class="stats">

        <div class="stat">
            <strong>2</strong>
            <p>Phòng đang trống</p>
        </div>

        <div class="stat">
            <strong>3</strong>
            <p>Yêu cầu đặt phòng</p>
        </div>

        <div class="stat">
            <strong>2</strong>
            <p>Báo hỏng của tôi</p>
        </div>

    </div>

    <h2>Chức năng chính</h2>

    <div class="cards">

        <div class="card">
            <h3>🏫 Xem phòng</h3>
            <p>Xem danh sách phòng, sức chứa và trạng thái.</p>
            <a href="#rooms">Xem phòng →</a>
        </div>

        <div class="card">
            <h3>📅 Đặt phòng</h3>
            <p>Gửi yêu cầu đặt phòng theo ngày và thời gian.</p>
            <a href="#booking">Đặt phòng →</a>
        </div>

        <div class="card">
            <h3>🔧 Báo hỏng</h3>
            <p>Báo cho cán bộ khi phát hiện thiết bị gặp sự cố.</p>
            <a href="#report">Báo hỏng →</a>
        </div>

    </div>

</section>


<section id="rooms" class="section">

    <h2>🏫 Phòng thực hành</h2>

    <div class="rooms">

        <?php foreach ($rooms as $room): ?>

            <?php
            $class = $room["status"] === "Trống"
                ? "available"
                : ($room["status"] === "Đang sử dụng"
                    ? "busy"
                    : "maintenance");
            ?>

            <div class="room">

                <h3><?= e($room["id"]) ?></h3>

                <p><?= e($room["name"]) ?></p>

                <p>
                    Sức chứa:
                    <strong><?= e($room["capacity"]) ?> người</strong>
                </p>

                <span class="status <?= $class ?>">
                    <?= e($room["status"]) ?>
                </span>

            </div>

        <?php endforeach; ?>

    </div>

</section>


<section id="booking" class="section">

    <h2>📅 Đặt phòng</h2>

    <?php if ($submitted && $success): ?>
        <div class="success"><?= e($success) ?></div>
    <?php endif; ?>

    <div class="form">

        <form method="POST" action="#booking">

            <div class="group">

                <label>Họ và tên *</label>

                <input
                    type="text"
                    name="name"
                    maxlength="50"
                    value="<?= e($data["name"]) ?>"
                    class="<?= $submitted && isset($errors["name"]) ? 'error-input' : '' ?>"
                >

                <?php if ($submitted && isset($errors["name"])): ?>
                    <div class="error"><?= e($errors["name"]) ?></div>
                <?php endif; ?>

            </div>


            <div class="group">

                <label>Email *</label>

                <input
                    type="text"
                    name="email"
                    value="<?= e($data["email"]) ?>"
                    class="<?= $submitted && isset($errors["email"]) ? 'error-input' : '' ?>"
                >

                <?php if ($submitted && isset($errors["email"])): ?>
                    <div class="error"><?= e($errors["email"]) ?></div>
                <?php endif; ?>

            </div>


            <div class="group">

                <label>Phòng *</label>

                <select
                    name="room"
                    class="<?= $submitted && isset($errors["room"]) ? 'error-input' : '' ?>"
                >

                    <option value="">-- Chọn phòng --</option>

                    <?php foreach ($rooms as $room): ?>

                        <?php if ($room["status"] === "Trống"): ?>

                            <option
                                value="<?= e($room["id"]) ?>"
                                <?= $data["room"] === $room["id"] ? "selected" : "" ?>
                            >
                                <?= e($room["id"]) ?> - <?= e($room["name"]) ?>
                            </option>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </select>

                <?php if ($submitted && isset($errors["room"])): ?>
                    <div class="error"><?= e($errors["room"]) ?></div>
                <?php endif; ?>

            </div>


            <div class="group">

                <label>Ngày đặt phòng *</label>

                <input
                    type="date"
                    name="date"
                    value="<?= e($data["date"]) ?>"
                    class="<?= $submitted && isset($errors["date"]) ? 'error-input' : '' ?>"
                >

                <?php if ($submitted && isset($errors["date"])): ?>
                    <div class="error"><?= e($errors["date"]) ?></div>
                <?php endif; ?>

            </div>


            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">

                <div class="group">

                    <label>Bắt đầu *</label>

                    <input
                        type="time"
                        name="start"
                        value="<?= e($data["start"]) ?>"
                        class="<?= $submitted && isset($errors["start"]) ? 'error-input' : '' ?>"
                    >

                    <?php if ($submitted && isset($errors["start"])): ?>
                        <div class="error"><?= e($errors["start"]) ?></div>
                    <?php endif; ?>

                </div>


                <div class="group">

                    <label>Kết thúc *</label>

                    <input
                        type="time"
                        name="end"
                        value="<?= e($data["end"]) ?>"
                        class="<?= $submitted && isset($errors["end"]) ? 'error-input' : '' ?>"
                    >

                    <?php if ($submitted && isset($errors["end"])): ?>
                        <div class="error"><?= e($errors["end"]) ?></div>
                    <?php endif; ?>

                </div>

            </div>


            <div class="group">

                <label>Mục đích sử dụng *</label>

                <textarea
                    name="purpose"
                    maxlength="200"
                    class="<?= $submitted && isset($errors["purpose"]) ? 'error-input' : '' ?>"
                ><?= e($data["purpose"]) ?></textarea>

                <?php if ($submitted && isset($errors["purpose"])): ?>
                    <div class="error"><?= e($errors["purpose"]) ?></div>
                <?php endif; ?>

            </div>


            <button type="submit">
                Gửi yêu cầu đặt phòng
            </button>

        </form>

    </div>

</section>


<section id="report" class="section">

    <h2>🔧 Báo hỏng thiết bị</h2>

    <div class="form">

        <p>
            Sinh viên có thể báo cho cán bộ phòng lab
            khi phát hiện thiết bị gặp sự cố.
        </p>

        <ul>
            <li>Máy tính</li>
            <li>Màn hình</li>
            <li>Bàn phím</li>
            <li>Chuột</li>
            <li>Máy chiếu</li>
        </ul>

        <button onclick="alert('Chức năng báo hỏng đang được xây dựng.')">
            Tạo báo hỏng
        </button>

    </div>

</section>

</div>

</main>

</body>
</html>