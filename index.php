<?php

$errors = [];
$success = false;

$full_name = $_POST['full_name'] ?? '';
$email     = $_POST['email'] ?? '';
$subject   = $_POST['subject'] ?? '';
$message   = $_POST['message'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (trim($full_name) === '') {
        $errors[] = 'Họ tên không được để trống.';
    }
    if (trim($message) === '') {
        $errors[] = 'Nội dung không được để trống.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không đúng định dạng.';
    }

    $avatar = $_FILES['avatar'] ?? null;
    if (!$avatar || $avatar['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = 'Vui lòng chọn ảnh đại diện.';
    } else {
        $ext = strtolower(pathinfo($avatar['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $errors[] = 'Ảnh đại diện phải là JPG hoặc PNG.';
        } elseif ($avatar['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Ảnh đại diện không được vượt quá 2MB.';
        } else {
            if (!is_dir('uploads')) mkdir('uploads');
            move_uploaded_file($avatar['tmp_name'], 'uploads/' . uniqid() . '.' . $ext);
        }
    }

    if (empty($errors)) {
        $success = true;
        $full_name = $email = $subject = $message = '';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Liên hệ</title>
<style>
    body { font-family: Arial, sans-serif; background: #f0f5f3; display: flex; justify-content: center; padding: 40px; }
    form { background: #fff; padding: 24px; border-radius: 10px; width: 350px; box-shadow: 0 4px 12px rgba(0,0,0,.1); }
    h2 { text-align: center; color: #1b6e57; }
    label { display: block; margin-top: 12px; font-weight: bold; }
    input, select, textarea { width: 100%; padding: 8px; margin-top: 4px; border: 1px solid #ccc; border-radius: 5px; }
    button { width: 100%; margin-top: 16px; padding: 10px; background: #1b6e57; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
    .error { color: red; font-size: 13px; }
    .success { color: green; font-weight: bold; text-align: center; }
</style>
</head>
<body>

<form method="POST" enctype="multipart/form-data">
    <h2>Liên hệ</h2>

    <?php if ($success): ?>
        <p class="success">Gửi liên hệ thành công!</p>
    <?php endif; ?>

    <?php foreach ($errors as $e): ?>
        <p class="error">• <?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>

    <label>Họ tên</label>
    <input type="text" name="full_name" value="<?= htmlspecialchars($full_name) ?>">

    <label>Email</label>
    <input type="text" name="email" value="<?= htmlspecialchars($email) ?>">

    <label>Chủ đề</label>
    <select name="subject">
        <option value="Hỗ trợ kỹ thuật" <?= $subject === 'Hỗ trợ kỹ thuật' ? 'selected' : '' ?>>Hỗ trợ kỹ thuật</option>
        <option value="Góp ý" <?= $subject === 'Góp ý' ? 'selected' : '' ?>>Góp ý</option>
        <option value="Khác" <?= $subject === 'Khác' ? 'selected' : '' ?>>Khác</option>
    </select>

    <label>Nội dung</label>
    <textarea name="message" rows="4"><?= htmlspecialchars($message) ?></textarea>

    <label>Ảnh đại diện</label>
    <input type="file" name="avatar">

    <button type="submit">Gửi liên hệ</button>
</form>

</body>
</html>