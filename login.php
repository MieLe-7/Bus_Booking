<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #ffc107;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            color: #fff;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng Nhập</h2>
        <form action="login.php" method="POST">
            Số điện thoại:<input type="text" name="username" placeholder="Số điện thoại" required>
            Mật khẩu:<input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" name="login">Đăng Nhập</button>
        </form>
        
        <?php
        if (isset($_POST["login"])) {
            require_once "config.php"; // Kết nối cơ sở dữ liệu

            $soDienThoai = trim($_POST["username"]); // Sử dụng SoDienThoai thay vì username
            $matKhau = trim($_POST["password"]);
            
            if (!empty($soDienThoai) && !empty($matKhau)) {
                $sql = "SELECT * FROM taikhoan WHERE SoDienThoai = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $soDienThoai); // Gắn giá trị vào câu lệnh SQL
                $stmt->execute(); // Thực thi câu lệnh SQL
                
                $result = $stmt->get_result(); // Lấy kết quả từ câu lệnh SQL
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    // Kiểm tra mật khẩu
                    if (password_verify($matKhau, $row['MatKhau'])) {
                        session_start();
                        $_SESSION['user'] = $soDienThoai;
                        // Kiểm tra quyền của người dùng
                if ($row['role'] === 'admin') {
                    header("Location: TK_DoanhThu.php"); // Chuyển đến trang admin
                } else {
                    header("Location: index.php"); // Chuyển đến trang chủ cho user
                }
                exit;
            } else {
                echo "Đăng nhập không thành công! Mật khẩu không đúng.";
            }
        } else {
            echo "Đăng nhập không thành công! Số điện thoại không tồn tại.";
        }
        
        $stmt->close(); // Đóng câu lệnh SQL
    } else {
        echo "Vui lòng nhập đầy đủ dữ liệu!";
    }

    $conn->close(); // Đóng kết nối cơ sở dữ liệu
}
?>
    </div>
</body>
</html>
