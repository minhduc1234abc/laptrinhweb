<?php
$hoTen      = "Vũ Minh Đức";
$ngheNghiep = "Sinh viên";
$gioiThieu  = "Mình là sinh viên đam mê lập trình web, đang trong quá trình học và thực hành các kỹ năng HTML, CSS, JavaScript và PHP thông qua các bài tập và dự án thực tế trên lớp.";
$email      = "vuduc04082006@gmail.com";
$github     = "https://github.com/minhduc1234abc";
$duAns = [
    [
        "ten" => "Bài tập buổi 9",
        "moTa" => "Tổng hợp các bài tập thực hành HTML/CSS được làm trong buổi học số 9, gồm nhiều ví dụ (VD1, VD3, VD4...) và các bài tập nâng cao (vd5 - vd14) rèn luyện cấu trúc trang và bố cục web.",
        "congNghe" => ["HTML", "CSS"],
        "link" => "https://github.com/minhduc1234abc/baitapbuoi9",
        "loai" => "Bài tập lập trình web"
    ],
    [
        "ten" => "Bài tập lớn",
        "moTa" => "Dự án tổng hợp nhiều bài tập từ các buổi học (buổi 1 đến buổi 9), bao gồm các bài thực hành về giao diện, hình ảnh và bố cục trang web hoàn chỉnh.",
        "congNghe" => ["HTML", "CSS"],
        "link" => "https://github.com/minhduc1234abc/baitaplon",
        "loai" => "Bài tập lập trình web"
    ],
    [
        "ten" => "Bài tập tổng",
        "moTa" => "Bài tập tổng hợp kiến thức đã học trong khóa học lập trình web, ôn tập và củng cố các kỹ năng dựng trang web tĩnh.",
        "congNghe" => ["HTML", "CSS"],
        "link" => "https://github.com/minhduc1234abc/baitaptong",
        "loai" => "Bài tập lập trình web"
    ],
    [
        "ten" => "Thiết kế Web (tkeweb)",
        "moTa" => "Dự án thực hành thiết kế giao diện web, tập trung vào bố cục, màu sắc và trải nghiệm người dùng cho các trang web tĩnh.",
        "congNghe" => ["HTML", "CSS"],
        "link" => "https://github.com/minhduc1234abc/tkeweb",
        "loai" => "Thiết kế web"
    ],
];

$namHienTai = date("Y");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Giới thiệu bản thân - <?php echo htmlspecialchars($hoTen); ?></title>
<style>
    :root{
        --primary:#4f46e5;
        --primary-dark:#3730a3;
        --bg:#f4f5fb;
        --card-bg:#ffffff;
        --text:#1f2333;
        --muted:#6b7280;
    }
    *{box-sizing:border-box;}
    body{
        margin:0;
        font-family:'Segoe UI', Tahoma, Arial, sans-serif;
        background:var(--bg);
        color:var(--text);
        line-height:1.6;
    }
    header.hero{
        background:linear-gradient(135deg,var(--primary),var(--primary-dark));
        color:#fff;
        padding:60px 20px 50px;
        text-align:center;
    }
    header.hero .avatar{
        width:120px;height:120px;border-radius:50%;
        background:#fff;
        color:var(--primary-dark);
        display:flex;align-items:center;justify-content:center;
        font-size:42px;font-weight:bold;
        margin:0 auto 20px;
        border:4px solid rgba(255,255,255,0.6);
    }
    header.hero h1{margin:0 0 6px;font-size:2rem;}
    header.hero p.role{margin:0 0 16px;opacity:.9;font-size:1.05rem;}
    header.hero p.bio{max-width:640px;margin:0 auto;opacity:.95;}
    header.hero .contact-links{margin-top:20px;}
    header.hero .contact-links a{
        color:#fff;
        text-decoration:none;
        border:1px solid rgba(255,255,255,0.6);
        padding:8px 16px;
        border-radius:20px;
        margin:0 6px;
        display:inline-block;
        transition:.2s;
        font-size:.9rem;
    }
    header.hero .contact-links a:hover{background:rgba(255,255,255,0.15);}

    main{max-width:1000px;margin:0 auto;padding:40px 20px;}
    section{margin-bottom:50px;}
    section h2{
        font-size:1.5rem;
        margin-bottom:20px;
        border-left:5px solid var(--primary);
        padding-left:12px;
    }

    .project-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
        gap:22px;
    }
    .project-card{
        background:var(--card-bg);
        border-radius:12px;
        padding:22px;
        box-shadow:0 2px 10px rgba(0,0,0,.06);
        display:flex;
        flex-direction:column;
        transition:transform .2s, box-shadow .2s;
    }
    .project-card:hover{
        transform:translateY(-4px);
        box-shadow:0 8px 20px rgba(0,0,0,.1);
    }
    .project-card .badge{
        align-self:flex-start;
        background:#eef2ff;
        color:var(--primary-dark);
        font-size:.75rem;
        padding:4px 10px;
        border-radius:12px;
        margin-bottom:10px;
        font-weight:600;
    }
    .project-card h3{margin:0 0 8px;font-size:1.15rem;}
    .project-card p{color:var(--muted);font-size:.92rem;flex-grow:1;}
    .tech-tags{margin:12px 0;}
    .tech-tags span{
        display:inline-block;
        background:var(--bg);
        color:var(--text);
        font-size:.75rem;
        padding:4px 9px;
        border-radius:6px;
        margin:0 6px 6px 0;
        border:1px solid #e5e7eb;
    }
    .project-card a.btn{
        margin-top:10px;
        text-align:center;
        background:var(--primary);
        color:#fff;
        text-decoration:none;
        padding:10px;
        border-radius:8px;
        font-size:.9rem;
        transition:background .2s;
    }
    .project-card a.btn:hover{background:var(--primary-dark);}

    .skills-list{display:flex;flex-wrap:wrap;gap:10px;}
    .skills-list span{
        background:var(--card-bg);
        border:1px solid #e5e7eb;
        padding:8px 16px;
        border-radius:20px;
        font-size:.9rem;
        box-shadow:0 1px 4px rgba(0,0,0,.04);
    }

    footer{
        text-align:center;
        padding:24px;
        color:var(--muted);
        font-size:.85rem;
    }
</style>
</head>
<body>

<header class="hero">
    <div class="avatar"><?php echo htmlspecialchars(mb_substr($hoTen, 0, 1)); ?></div>
    <h1><?php echo htmlspecialchars($hoTen); ?></h1>
    <p class="role"><?php echo htmlspecialchars($ngheNghiep); ?></p>
    <p class="bio"><?php echo htmlspecialchars($gioiThieu); ?></p>
    <div class="contact-links">
        <a href="mailto:<?php echo htmlspecialchars($email); ?>">✉ Email</a>
        <a href="<?php echo htmlspecialchars($github); ?>" target="_blank" rel="noopener">💻 GitHub</a>
    </div>
</header>

<main>

    <section id="ky-nang">
        <h2>Kỹ năng</h2>
        <div class="skills-list">
            <span>HTML5</span>
            <span>CSS3</span>
            <span>JavaScript</span>
            <span>PHP</span>
            <span>Responsive Design</span>
            <span>Git &amp; GitHub</span>
        </div>
    </section>

    <section id="du-an">
        <h2>Các dự án đã làm</h2>
        <div class="project-grid">
            <?php foreach ($duAns as $duAn): ?>
            <div class="project-card">
                <span class="badge"><?php echo htmlspecialchars($duAn['loai']); ?></span>
                <h3><?php echo htmlspecialchars($duAn['ten']); ?></h3>
                <p><?php echo htmlspecialchars($duAn['moTa']); ?></p>
                <div class="tech-tags">
                    <?php foreach ($duAn['congNghe'] as $tech): ?>
                        <span><?php echo htmlspecialchars($tech); ?></span>
                    <?php endforeach; ?>
                </div>
                <a class="btn" href="<?php echo htmlspecialchars($duAn['link']); ?>" target="_blank" rel="noopener">
                    Xem trên GitHub &rarr;
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<footer>
    &copy; <?php echo htmlspecialchars($namHienTai); ?> - <?php echo htmlspecialchars($hoTen); ?>. All rights reserved.
</footer>

</body>
</html>