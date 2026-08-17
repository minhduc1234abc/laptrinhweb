<?php

/*
|--------------------------------------------------------------------------
| BÀI TẬP CÁ NHÂN
| Chủ đề: Hệ thống quản lý phòng thực hành và thiết bị
|
| Yêu cầu:
| - PHP xử lý form
| - Mảng dữ liệu
| - Vòng lặp foreach
| - Hàm tự định nghĩa
| - Kiểm tra dữ liệu phía server
| - Hiển thị lỗi tại trường
| - Giữ lại dữ liệu khi form lỗi
| - Chuẩn hóa dữ liệu
| - htmlspecialchars chống XSS
| - Chưa lưu dữ liệu vào CSDL
|--------------------------------------------------------------------------
*/


/* =========================================================
   1. HÀM TỰ ĐỊNH NGHĨA
   ========================================================= */

/*
 * Hàm mã hóa dữ liệu trước khi hiển thị.
 * Giúp hạn chế XSS.
 */
function e($value)
{
    return htmlspecialchars(
        $value,
        ENT_QUOTES,
        'UTF-8'
    );
}


/*
 * Hàm kiểm tra và chuẩn hóa khoảng thời gian.
 */
function validTimeRange($start, $end)
{
    return $end > $start;
}


/* =========================================================
   2. DỮ LIỆU MẪU
   Sử dụng MẢNG và FOREACH
   ========================================================= */

$rooms = [

    [
        "id" => "P101",
        "name" => "Phòng máy 101",
        "capacity" => 40,
        "status" => "Trống"
    ],

    [
        "id" => "P102",
        "name" => "Phòng máy 102",
        "capacity" => 35,
        "status" => "Đang sử dụng"
    ],

    [
        "id" => "P103",
        "name" => "Phòng máy 103",
        "capacity" => 45,
        "status" => "Bảo trì"
    ],

    [
        "id" => "P104",
        "name" => "Phòng máy 104",
        "capacity" => 40,
        "status" => "Trống"
    ]

];


/* =========================================================
   3. BIẾN XỬ LÝ FORM
   ========================================================= */

$errors = [];

$success = "";


/*
 * Giá trị mặc định của form.
 * Việc này giúp giữ lại dữ liệu khi form có lỗi.
 */

$formData = [

    "name" => "",
    "email" => "",
    "room" => "",
    "date" => "",
    "start" => "",
    "end" => "",
    "purpose" => ""

];


/* =========================================================
   4. XỬ LÝ FORM
   ========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    /* -----------------------------------------------------
       Nhận dữ liệu từ FORM
       ----------------------------------------------------- */

    $formData["name"] = trim($_POST["name"] ?? "");

    $formData["email"] = trim($_POST["email"] ?? "");

    $formData["room"] = trim($_POST["room"] ?? "");

    $formData["date"] = trim($_POST["date"] ?? "");

    $formData["start"] = trim($_POST["start"] ?? "");

    $formData["end"] = trim($_POST["end"] ?? "");

    $formData["purpose"] = trim($_POST["purpose"] ?? "");


    /* -----------------------------------------------------
       KIỂM TRA HỌ TÊN
       ----------------------------------------------------- */

    if ($formData["name"] === "") {

        $errors["name"] = "Vui lòng nhập họ và tên.";

    } elseif (mb_strlen($formData["name"]) < 3) {

        $errors["name"] =
            "Họ và tên phải có ít nhất 3 ký tự.";

    } elseif (mb_strlen($formData["name"]) > 50) {

        $errors["name"] =
            "Họ và tên không được vượt quá 50 ký tự.";

    }


    /* -----------------------------------------------------
       KIỂM TRA EMAIL
       ----------------------------------------------------- */

    if ($formData["email"] === "") {

        $errors["email"] =
            "Vui lòng nhập email.";

    } elseif (!filter_var(
        $formData["email"],
        FILTER_VALIDATE_EMAIL
    )) {

        $errors["email"] =
            "Email không đúng định dạng.";

    }


    /* -----------------------------------------------------
       KIỂM TRA PHÒNG
       ----------------------------------------------------- */

    $validRooms = [];

    foreach ($rooms as $room) {

        $validRooms[] = $room["id"];

    }


    if ($formData["room"] === "") {

        $errors["room"] =
            "Vui lòng chọn phòng.";

    } elseif (!in_array(
        $formData["room"],
        $validRooms
    )) {

        $errors["room"] =
            "Phòng được chọn không hợp lệ.";

    }


    /* -----------------------------------------------------
       KIỂM TRA NGÀY
       ----------------------------------------------------- */

    if ($formData["date"] === "") {

        $errors["date"] =
            "Vui lòng chọn ngày đặt phòng.";

    } else {

        $dateObject = DateTime::createFromFormat(
            "Y-m-d",
            $formData["date"]
        );


        if (
            !$dateObject ||
            $dateObject->format("Y-m-d")
            !== $formData["date"]
        ) {

            $errors["date"] =
                "Ngày không đúng định dạng.";

        }

    }


    /* -----------------------------------------------------
       KIỂM TRA THỜI GIAN
       ----------------------------------------------------- */

    if ($formData["start"] === "") {

        $errors["start"] =
            "Vui lòng chọn thời gian bắt đầu.";

    }


    if ($formData["end"] === "") {

        $errors["end"] =
            "Vui lòng chọn thời gian kết thúc.";

    }


    /*
     * Kiểm tra thời gian kết thúc
     * phải lớn hơn thời gian bắt đầu.
     */

    if (
        $formData["start"] !== "" &&
        $formData["end"] !== ""
    ) {

        if (!validTimeRange(
            $formData["start"],
            $formData["end"]
        )) {

            $errors["end"] =
                "Thời gian kết thúc phải lớn hơn thời gian bắt đầu.";

        }

    }


    /* -----------------------------------------------------
       KIỂM TRA MỤC ĐÍCH
       ----------------------------------------------------- */

    if ($formData["purpose"] === "") {

        $errors["purpose"] =
            "Vui lòng nhập mục đích sử dụng.";

    } elseif (mb_strlen(
        $formData["purpose"]
    ) < 10) {

        $errors["purpose"] =
            "Mục đích sử dụng phải có ít nhất 10 ký tự.";

    } elseif (mb_strlen(
        $formData["purpose"]
    ) > 200) {

        $errors["purpose"] =
            "Mục đích sử dụng không được vượt quá 200 ký tự.";

    }


    /* =====================================================
       5. NẾU KHÔNG CÓ LỖI
       ===================================================== */

    if (empty($errors)) {

        $success =
            "Gửi yêu cầu đặt phòng thành công! " .
            "Dữ liệu đã được kiểm tra hợp lệ.";

        /*
         * Chưa lưu CSDL theo đúng yêu cầu bài tập.
         *
         * Có thể sau này INSERT dữ liệu vào MySQL.
         */

    }

}

?>


<!DOCTYPE html>

<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Student - Quản lý phòng thực hành
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family:
                Arial,
                sans-serif;

            background:
                #f4f7f9;

            color: #333;
        }


        :root {

            --blue: #003399;

            --blue-dark: #002266;

            --light-blue: #eef4ff;

            --border: #dee2e6;

            --red: #c62828;

            --green: #207a45;
        }


        /* =========================
           LAYOUT
        ========================= */

        .layout {

            min-height: 100vh;

            display: flex;
        }


        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {

            width: 240px;

            background: white;

            border-right:
                1px solid var(--border);

            padding: 25px 18px;

            position: fixed;

            top: 0;

            bottom: 0;

            left: 0;
        }


        .logo {

            color: var(--blue);

            font-size: 19px;

            font-weight: bold;

            margin-bottom: 40px;

            padding-left: 8px;
        }


        .menu-title {

            font-size: 12px;

            color: #999;

            margin-bottom: 10px;

            padding-left: 8px;
        }


        .menu a {

            display: block;

            text-decoration: none;

            color: #444;

            padding: 13px;

            margin-bottom: 5px;

            border-radius: 7px;
        }


        .menu a:hover,
        .menu a.active {

            background:
                var(--light-blue);

            color: var(--blue);

            font-weight: bold;
        }


        /* =========================
           MAIN
        ========================= */

        .main {

            margin-left: 240px;

            width:
                calc(100% - 240px);

            min-height: 100vh;
        }


        /* =========================
           TOPBAR
        ========================= */

        .topbar {

            height: 65px;

            background: white;

            border-bottom:
                1px solid var(--border);

            display: flex;

            justify-content:
                space-between;

            align-items: center;

            padding: 0 35px;
        }


        .user {

            font-weight: bold;
        }


        /* =========================
           CONTENT
        ========================= */

        .content {

            padding: 35px;
        }


        .welcome h1 {

            margin-top: 0;

            color: var(--blue);
        }


        .welcome p {

            color: #777;
        }


        /* =========================
           THỐNG KÊ
        ========================= */

        .stats {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;

            margin:
                30px 0;
        }


        .stat-card {

            background: white;

            border:
                1px solid var(--border);

            border-radius: 8px;

            padding: 20px;

            display: flex;

            align-items: center;

            gap: 15px;
        }


        .stat-icon {

            font-size: 30px;
        }


        .stat-card strong {

            color: var(--blue);

            font-size: 27px;
        }


        .stat-card p {

            margin: 4px 0 0;

            color: #777;
        }


        /* =========================
           CHỨC NĂNG
        ========================= */

        .function-grid {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;

            margin-top: 20px;
        }


        .function-card {

            background: white;

            border:
                1px solid var(--border);

            border-radius: 8px;

            padding: 25px;
        }


        .function-card:hover {

            box-shadow:
                0 4px 15px
                rgba(0,0,0,0.07);
        }


        .function-icon {

            font-size: 35px;
        }


        .function-card h3 {

            color: var(--blue);
        }


        .function-card p {

            color: #777;

            line-height: 1.5;
        }


        .function-card a {

            color: var(--blue);

            text-decoration: none;

            font-weight: bold;
        }


        /* =========================
           SECTION
        ========================= */

        .section {

            margin-top: 40px;
        }


        .section h2 {

            color: var(--blue);
        }


        /* =========================
           ROOM
        ========================= */

        .room-grid {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;
        }


        .room-card {

            background: white;

            border:
                1px solid var(--border);

            border-radius: 8px;

            padding: 20px;
        }


        .room-card h3 {

            color: var(--blue);
        }


        .status {

            display: inline-block;

            padding:
                5px 10px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;
        }


        .available {

            background: #e5f7eb;

            color: var(--green);
        }


        .busy {

            background: #ffe8e8;

            color: var(--red);
        }


        .maintenance {

            background: #fff3cd;

            color: #856404;
        }


        /* =========================
           FORM
        ========================= */

        .form-box {

            max-width: 700px;

            background: white;

            border:
                1px solid var(--border);

            border-radius: 8px;

            padding: 25px;
        }


        .form-group {

            margin-bottom: 18px;
        }


        label {

            display: block;

            font-weight: bold;

            margin-bottom: 7px;
        }


        input,
        select,
        textarea {

            width: 100%;

            padding: 11px;

            border:
                1px solid #ccc;

            border-radius: 6px;

            font-size: 14px;
        }


        textarea {

            min-height: 110px;

            resize: vertical;
        }


        input:focus,
        select:focus,
        textarea:focus {

            outline: none;

            border-color:
                var(--blue);

            box-shadow:
                0 0 0 3px
                rgba(0,51,153,0.1);
        }


        .error-input {

            border-color:
                var(--red);
        }


        .error-message {

            color:
                var(--red);

            font-size: 13px;

            margin-top: 5px;
        }


        .success {

            background:
                #e6f7ed;

            color:
                var(--green);

            padding: 13px;

            border-radius: 6px;

            margin-bottom: 20px;
        }


        button {

            background:
                var(--blue);

            color: white;

            border: none;

            border-radius: 6px;

            padding: 12px 20px;

            cursor: pointer;

            font-weight: bold;
        }


        button:hover {

            background:
                var(--blue-dark);
        }


        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 900px) {

            .sidebar {

                width: 190px;
            }


            .main {

                margin-left: 190px;

                width:
                    calc(100% - 190px);
            }


            .stats,
            .function-grid,
            .room-grid {

                grid-template-columns: 1fr;
            }

        }

    </style>

</head>


<body>


<div class="layout">


    <!-- =====================================================
         SIDEBAR
         ===================================================== -->

    <aside class="sidebar">

        <div class="logo">

            🏫 LAB MANAGEMENT

        </div>


        <div class="menu-title">

            MENU SINH VIÊN

        </div>


        <nav class="menu">

            <a
                href="#home"
                class="active"
            >
                🏠 Trang chủ
            </a>


            <a href="#rooms">

                🏫 Phòng thực hành

            </a>


            <a href="#booking">

                📅 Đặt phòng

            </a>


            <a href="#report">

                🔧 Báo hỏng thiết bị

            </a>

        </nav>

    </aside>



    <!-- =====================================================
         MAIN
         ===================================================== -->

    <main class="main">


        <!-- TOPBAR -->

        <header class="topbar">

            <span>
                Student Portal
            </span>


            <span class="user">

                👤 Nguyễn Văn A

            </span>

        </header>



        <!-- =================================================
             NỘI DUNG
             ================================================= -->

        <section class="content">


            <!-- =========================
                 TRANG CHỦ
            ========================= -->

            <section id="home">

                <div class="welcome">

                    <h1>
                        Xin chào, Nguyễn Văn A! 👋
                    </h1>

                    <p>
                        Chào mừng bạn đến với
                        hệ thống quản lý phòng thực hành.
                    </p>

                </div>


                <!-- THỐNG KÊ -->

                <div class="stats">


                    <div class="stat-card">

                        <span class="stat-icon">
                            🏫
                        </span>

                        <div>

                            <strong>
                                2
                            </strong>

                            <p>
                                Phòng đang trống
                            </p>

                        </div>

                    </div>


                    <div class="stat-card">

                        <span class="stat-icon">
                            📅
                        </span>

                        <div>

                            <strong>
                                3
                            </strong>

                            <p>
                                Yêu cầu đặt phòng
                            </p>

                        </div>

                    </div>


                    <div class="stat-card">

                        <span class="stat-icon">
                            🔧
                        </span>

                        <div>

                            <strong>
                                2
                            </strong>

                            <p>
                                Báo hỏng của tôi
                            </p>

                        </div>

                    </div>


                </div>


                <h2>
                    Chức năng chính
                </h2>


                <div class="function-grid">


                    <div class="function-card">

                        <div class="function-icon">
                            🏫
                        </div>

                        <h3>
                            Xem phòng thực hành
                        </h3>

                        <p>
                            Xem danh sách phòng,
                            sức chứa và trạng thái.
                        </p>

                        <a href="#rooms">
                            Xem phòng →
                        </a>

                    </div>


                    <div class="function-card">

                        <div class="function-icon">
                            📅
                        </div>

                        <h3>
                            Đặt phòng
                        </h3>

                        <p>
                            Gửi yêu cầu đặt phòng
                            theo ngày và thời gian.
                        </p>

                        <a href="#booking">
                            Đặt phòng →
                        </a>

                    </div>


                    <div class="function-card">

                        <div class="function-icon">
                            🔧
                        </div>

                        <h3>
                            Báo hỏng thiết bị
                        </h3>

                        <p>
                            Báo cho cán bộ khi
                            thiết bị gặp sự cố.
                        </p>

                        <a href="#report">
                            Báo hỏng →
                        </a>

                    </div>


                </div>

            </section>



            <!-- =================================================
                 CHỨC NĂNG 1: PHÒNG
            ================================================= -->

            <section
                id="rooms"
                class="section"
            >

                <h2>
                    🏫 Phòng thực hành
                </h2>


                <div class="room-grid">


                    <?php

                    /*
                     * FOREACH:
                     * Duyệt mảng phòng
                     * và hiển thị dữ liệu.
                     */

                    foreach ($rooms as $room):


                        if (
                            $room["status"]
                            === "Trống"
                        ) {

                            $statusClass =
                                "available";

                        } elseif (
                            $room["status"]
                            === "Đang sử dụng"
                        ) {

                            $statusClass =
                                "busy";

                        } else {

                            $statusClass =
                                "maintenance";

                        }

                    ?>


                        <div class="room-card">

                            <h3>

                                <?php
                                echo e(
                                    $room["id"]
                                );
                                ?>

                            </h3>


                            <p>

                                <?php
                                echo e(
                                    $room["name"]
                                );
                                ?>

                            </p>


                            <p>

                                Sức chứa:

                                <strong>

                                    <?php
                                    echo e(
                                        $room["capacity"]
                                    );
                                    ?>

                                    người

                                </strong>

                            </p>


                            <span
                                class="
                                    status
                                    <?php
                                    echo $statusClass;
                                    ?>
                                "
                            >

                                <?php
                                echo e(
                                    $room["status"]
                                );
                                ?>

                            </span>

                        </div>


                    <?php

                    endforeach;

                    ?>


                </div>

            </section>



            <!-- =================================================
                 CHỨC NĂNG 2: ĐẶT PHÒNG
            ================================================= -->

            <section
                id="booking"
                class="section"
            >

                <h2>
                    📅 Đặt phòng
                </h2>


                <?php if ($success !== ""): ?>

                    <div class="success">

                        <?php
                        echo e($success);
                        ?>

                    </div>

                <?php endif; ?>


                <div class="form-box">


                    <form
                        method="POST"
                        action="#booking"
                        novalidate
                    >


                        <!-- HỌ TÊN -->

                        <div class="form-group">

                            <label>
                                Họ và tên *
                            </label>


                            <input
                                type="text"
                                name="name"
                                value="<?php
                                    echo e(
                                        $formData["name"]
                                    );
                                ?>"
                                maxlength="50"
                                class="<?php
                                    echo isset(
                                        $errors["name"]
                                    )
                                        ? "error-input"
                                        : "";
                                ?>"
                            >


                            <?php if (
                                isset($errors["name"])
                            ): ?>

                                <div class="error-message">

                                    <?php
                                    echo e(
                                        $errors["name"]
                                    );
                                    ?>

                                </div>

                            <?php endif; ?>

                        </div>



                        <!-- EMAIL -->

                        <div class="form-group">

                            <label>
                                Email *
                            </label>


                            <input
                                type="text"
                                name="email"
                                value="<?php
                                    echo e(
                                        $formData["email"]
                                    );
                                ?>"
                                maxlength="100"
                                class="<?php
                                    echo isset(
                                        $errors["email"]
                                    )
                                        ? "error-input"
                                        : "";
                                ?>"
                            >


                            <?php if (
                                isset($errors["email"])
                            ): ?>

                                <div class="error-message">

                                    <?php
                                    echo e(
                                        $errors["email"]
                                    );
                                    ?>

                                </div>

                            <?php endif; ?>

                        </div>



                        <!-- PHÒNG -->

                        <div class="form-group">

                            <label>
                                Phòng *
                            </label>


                            <select
                                name="room"
                                class="<?php
                                    echo isset(
                                        $errors["room"]
                                    )
                                        ? "error-input"
                                        : "";
                                ?>"
                            >

                                <option value="">
                                    -- Chọn phòng --
                                </option>


                                <?php foreach (
                                    $rooms as $room
                                ): ?>

                                    <?php
                                    /*
                                     * Chỉ cho phép
                                     * chọn phòng trống.
                                     */
                                    if (
                                        $room["status"]
                                        !== "Trống"
                                    ) {
                                        continue;
                                    }
                                    ?>


                                    <option
                                        value="<?php
                                            echo e(
                                                $room["id"]
                                            );
                                        ?>"
                                        <?php

                                        if (
                                            $formData["room"]
                                            === $room["id"]
                                        ) {
                                            echo "selected";
                                        }

                                        ?>
                                    >

                                        <?php
                                        echo e(
                                            $room["id"]
                                        );
                                        ?>

                                        -

                                        <?php
                                        echo e(
                                            $room["name"]
                                        );
                                        ?>

                                    </option>


                                <?php endforeach; ?>


                            </select>


                            <?php if (
                                isset($errors["room"])
                            ): ?>

                                <div class="error-message">

                                    <?php
                                    echo e(
                                        $errors["room"]
                                    );
                                    ?>

                                </div>

                            <?php endif; ?>

                        </div>



                        <!-- NGÀY -->

                        <div class="form-group">

                            <label>
                                Ngày đặt phòng *
                            </label>


                            <input
                                type="date"
                                name="date"
                                value="<?php
                                    echo e(
                                        $formData["date"]
                                    );
                                ?>"
                                class="<?php
                                    echo isset(
                                        $errors["date"]
                                    )
                                        ? "error-input"
                                        : "";
                                ?>"
                            >


                            <?php if (
                                isset($errors["date"])
                            ): ?>

                                <div class="error-message">

                                    <?php
                                    echo e(
                                        $errors["date"]
                                    );
                                    ?>

                                </div>

                            <?php endif; ?>

                        </div>



                        <!-- THỜI GIAN -->

                        <div
                            style="
                                display:grid;
                                grid-template-columns:
                                1fr 1fr;
                                gap:15px;
                            "
                        >


                            <div class="form-group">

                                <label>
                                    Bắt đầu *
                                </label>


                                <input
                                    type="time"
                                    name="start"
                                    value="<?php
                                        echo e(
                                            $formData["start"]
                                        );
                                    ?>"
                                    class="<?php
                                        echo isset(
                                            $errors["start"]
                                        )
                                            ? "error-input"
                                            : "";
                                    ?>"
                                >


                                <?php if (
                                    isset(
                                        $errors["start"]
                                    )
                                ): ?>

                                    <div
                                        class="error-message"
                                    >

                                        <?php
                                        echo e(
                                            $errors["start"]
                                        );
                                        ?>

                                    </div>

                                <?php endif; ?>

                            </div>



                            <div class="form-group">

                                <label>
                                    Kết thúc *
                                </label>


                                <input
                                    type="time"
                                    name="end"
                                    value="<?php
                                        echo e(
                                            $formData["end"]
                                        );
                                    ?>"
                                    class="<?php
                                        echo isset(
                                            $errors["end"]
                                        )
                                            ? "error-input"
                                            : "";
                                    ?>"
                                >


                                <?php if (
                                    isset(
                                        $errors["end"]
                                    )
                                ): ?>

                                    <div
                                        class="error-message"
                                    >

                                        <?php
                                        echo e(
                                            $errors["end"]
                                        );
                                        ?>

                                    </div>

                                <?php endif; ?>

                            </div>


                        </div>



                        <!-- MỤC ĐÍCH -->

                        <div class="form-group">

                            <label>
                                Mục đích sử dụng *
                            </label>


                            <textarea
                                name="purpose"
                                maxlength="200"
                                placeholder="
Nhập mục đích sử dụng phòng...
"
                                class="<?php
                                    echo isset(
                                        $errors["purpose"]
                                    )
                                        ? "error-input"
                                        : "";
                                ?>"
                            ><?php

                                echo e(
                                    $formData["purpose"]
                                );

                            ?></textarea>


                            <?php if (
                                isset(
                                    $errors["purpose"]
                                )
                            ): ?>

                                <div class="error-message">

                                    <?php
                                    echo e(
                                        $errors["purpose"]
                                    );
                                    ?>

                                </div>

                            <?php endif; ?>

                        </div>



                        <!-- SUBMIT -->

                        <button
                            type="submit"
                        >

                            Gửi yêu cầu đặt phòng

                        </button>


                    </form>

                </div>

            </section>



            <!-- =================================================
                 CHỨC NĂNG 3: BÁO HỎNG
            ================================================= -->

            <section
                id="report"
                class="section"
            >

                <h2>
                    🔧 Báo hỏng thiết bị
                </h2>


                <div class="form-box">

                    <p>
                        Chức năng này cho phép sinh viên
                        báo cho cán bộ phòng lab khi phát hiện
                        thiết bị gặp sự cố.
                    </p>


                    <p>

                        <strong>
                            Thiết bị có thể báo:
                        </strong>

                    </p>


                    <ul>

                        <li>
                            Máy tính
                        </li>

                        <li>
                            Màn hình
                        </li>

                        <li>
                            Bàn phím
                        </li>

                        <li>
                            Chuột
                        </li>

                        <li>
                            Máy chiếu
                        </li>

                    </ul>


                    <button
                        type="button"
                        onclick="
                            alert(
                                'Chức năng báo hỏng đang được xây dựng.'
                            );
                        "
                    >

                        Tạo báo hỏng

                    </button>

                </div>

            </section>


        </section>

    </main>

</div>


</body>

</html>