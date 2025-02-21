<?php
// Start session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['signup']) && $_GET['signup'] == 'success') {
    echo "<div style='color: green;'>Đăng ký thành công! Hãy đăng nhập để tiếp tục.</div>";
}

if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    echo "<div style='color: green;'>Đăng xuất thành công!</div>";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảo Toàn - Đặt Vé</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #2c2c2c;
            color: #fff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            text-align: center;
            padding: 20px;
            background-color: #ffc107;
            color: #000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        header p {
            font-size: 16px;
        }

        /* Thanh menu */
        .menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .menu a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .menu a:hover {
            color: #ffc107;
            transform: scale(1.1);
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
        }
        
        .auth-buttons a {
            background-color: #ffc107;
            color: #000;
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .auth-buttons a:hover {
            background-color: #e0a800;
            transform: scale(1.05);
        }

        .hotline {
            text-align: center;
            margin: 20px 0;
        }

        .hotline a {
            font-size: 20px;
            text-decoration: none;
            color: #000;
            background-color: #ffc107;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .hotline a:hover {
            background-color: #e0a800;
            transform: scale(1.05);
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            text-align: center;
        }

        .main-content img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .form-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .form-container input, .form-container button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container input:focus {
            outline: none;
            border-color: #ffc107;
        }

        .form-container input {
            width: 200px;
        }

        .form-container button {
            background-color: #ffc107;
            color: #000;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .form-container button:hover {
            background-color: #e0a800;
            transform: scale(1.05);
        }
        .main-content {
            text-align: center;
        }

        img {
            width: 100%;
            height: 50vh;
            object-fit: cover; 
        }

        .form-container {
            margin-top: 20px;
        }

        select, input[type="date"], button {
            padding: 10px;
            margin: 10px;
            font-size: 16px;
            width: 200px;
            box-sizing: border-box;
        }

        button {
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background-color: #e0a800;
            transform: scale(1.05);  /* Tăng kích thước khi hover */
        }

        footer {
            text-align: center;
            padding: 20px 30px;
            background-color: #222;
            color: #ccc;
            font-size: 14px;
        }

        footer p {
            margin-top: 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-container input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Thanh menu -->
    <div class="menu">
        <div style="text-align:center;padding:10px;margin: 0 auto;text-decoration: none;">
            <a href="index.php">Trang chủ</a>
            <a href="tracuuvexe.php">Tra cứu vé</a>
            <a href="#">Lịch trình</a>
            <a href="LienHe.php">Liên hệ</a>
        </div>
        <div class="auth-buttons">
            <?php
            if (isset($_SESSION['user'])) {
                echo "<span style='color:white;'>Xin chào, " . $_SESSION['user'] . "!</span>";
                echo '<a href="logout.php" class="btn btn-success btn-sm">Đăng xuất</a>';
            } else {
                echo '<a href="signup.php" class="btn btn-success btn-sm">Đăng ký</a>';
                echo '<a href="login.php" class="btn btn-success btn-sm">Đăng nhập</a>';
            }
            ?>
        </div>
        <br style="clear:both">
    </div>

    <header>
        <h1>Bao Toan Car</h1>
        <p>Website và hotline đặt vé duy nhất của Công ty Bao Toan.</p>
    </header>

    <div class="hotline">
        <p>Hotline đặt vé: <a href="tel:0889200200">0889 200 200</a></p>
    </div>

    <div class="main-content">
    <img src="./images/giaodien.jpg" alt="Bao Toan" style="width: 100%; height: 300px;">
    <div class="form-container">
    
    <form action="process_search.php" method="GET">
                <!-- Dropdown cho Nơi đi -->
        <select name="departure" id="departure">
            <option value="Hanoi">Hà Nội</option>
            <option value="SaiGon">Sài Gòn</option>
            <option value="DaNang">Đà Nẵng</option>
            <option value="Hue">Huế</option>
        </select>
        
        <select name="destination" id="destination">
            <option value="Hanoi">Hà Nội</option>
            <option value="SaiGon">Sài Gòn</option>
            <option value="DaNang">Đà Nẵng</option>
            <option value="Hue">Huế</option>
        </select>
        
        <input type="date" name="date" id="date">
        
        <button type="submit">Tìm chuyến</button>
        </div>
</div>
    <footer>
        <p>© 2025 Bao Toan Car. All rights reserved.</p>
        <p>Phát triển bởi Đội ngũ IT Bao Toan.</p>
    </footer>

</body>
</html>