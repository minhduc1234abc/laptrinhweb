<?php

// ========================================
// 1. MẢNG DỮ LIỆU PHÒNG THỰC HÀNH
// ========================================

$rooms = [
    [
        "ma_phong" => "P101",
        "ten_phong" => "Phòng máy 101",
        "suc_chua" => 40,
        "trang_thai" => "Trống"
    ],
    [
        "ma_phong" => "P102",
        "ten_phong" => "Phòng máy 102",
        "suc_chua" => 35,
        "trang_thai" => "Đang sử dụng"
    ],
    [
        "ma_phong" => "P103",
        "ten_phong" => "Phòng máy 103",
        "suc_chua" => 45,
        "trang_thai" => "Bảo trì"
    ]
];


// ========================================
// 2. HÀM KIỂM TRA TRẠNG THÁI PHÒNG
// ========================================

function checkRoomStatus($status)
{
    if ($status == "Trống") {
        return "Có thể đặt";
    } elseif ($status == "Đang sử dụng") {
        return "Không thể đặt";
    } else {
        return "Đang bảo trì";
    }
}


// ========================================
// 3. NHẬN DỮ LIỆU TỪ FORM
// ========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ma_phong = $_POST["ma_phong"];
    $ten_phong = $_POST["ten_phong"];
    $suc_chua = (int) $_POST["suc_chua"];
    $trang_thai = $_POST["trang_thai"];

    // Thêm phòng mới vào mảng
    $rooms[] = [
        "ma_phong" => $ma_phong,
        "ten_phong" => $ten_phong,
        "suc_chua" => $suc_chua,
        "trang_thai" => $trang_thai
    ];
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Quản lý phòng thực hành</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <!-- ==================================
         TIÊU ĐỀ
    =================================== -->

    <h1>QUẢN LÝ PHÒNG THỰC HÀNH</h1>

    <p class="description">
        Quản lý thông tin và trạng thái các phòng thực hành
    </p>


    <!-- ==================================
         FORM NHẬP PHÒNG
    =================================== -->

    <div class="form-box">

        <h2>Thêm phòng thực hành</h2>

        <form method="POST">

            <div class="form-group">

                <label>Mã phòng</label>

                <input
                    type="text"
                    name="ma_phong"
                    placeholder="Ví dụ: P104"
                    required
                >

            </div>


            <div class="form-group">

                <label>Tên phòng</label>

                <input
                    type="text"
                    name="ten_phong"
                    placeholder="Ví dụ: Phòng máy 104"
                    required
                >

            </div>


            <div class="form-group">

                <label>Sức chứa</label>

                <input
                    type="number"
                    name="suc_chua"
                    min="1"
                    placeholder="Số người"
                    required
                >

            </div>


            <div class="form-group">

                <label>Trạng thái</label>

                <select name="trang_thai" required>

                    <option value="">
                        -- Chọn trạng thái --
                    </option>

                    <option value="Trống">
                        Trống
                    </option>

                    <option value="Đang sử dụng">
                        Đang sử dụng
                    </option>

                    <option value="Bảo trì">
                        Bảo trì
                    </option>

                </select>

            </div>


            <button type="submit">
                Thêm phòng
            </button>

        </form>

    </div>


    <!-- ==================================
         DANH SÁCH PHÒNG
    =================================== -->

    <h2>Danh sách phòng thực hành</h2>

    <table>

        <thead>

            <tr>

                <th>STT</th>

                <th>Mã phòng</th>

                <th>Tên phòng</th>

                <th>Sức chứa</th>

                <th>Trạng thái</th>

                <th>Khả năng đặt</th>

            </tr>

        </thead>


        <tbody>

        <?php

        // ==================================
        // 4. VÒNG LẶP DUYỆT MẢNG
        // ==================================

        $stt = 1;

        foreach ($rooms as $room):

        ?>

            <tr>

                <td>
                    <?php echo $stt; ?>
                </td>

                <td>
                    <?php echo $room["ma_phong"]; ?>
                </td>

                <td>
                    <?php echo $room["ten_phong"]; ?>
                </td>

                <td>
                    <?php echo $room["suc_chua"]; ?> người
                </td>

                <td>

                    <?php echo $room["trang_thai"]; ?>

                </td>

                <td>

                    <?php

                    // Gọi hàm kiểm tra trạng thái
                    echo checkRoomStatus(
                        $room["trang_thai"]
                    );

                    ?>

                </td>

            </tr>

        <?php

            $stt++;

        endforeach;

        ?>

        </tbody>

    </table>

</div>

</body>

</html>