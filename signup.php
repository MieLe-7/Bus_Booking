<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
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
        .signup-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .signup-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .signup-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .signup-container button {
            width: 100%;
            padding: 10px;
            background-color: #ffc107;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            color: #fff;
            cursor: pointer;
        }
        .signup-container button:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Đăng Ký</h2>
        <form action="signup.php" method="POST">
            Số điện thoại: <input type="text" name="username" placeholder="Số điện thoại" required>
            Mật Khẩu: <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" name="signup">Đăng Ký</button>
        </form>

        <?php
        if (isset($_POST["signup"])) {
            require_once "config.php"; // Kết nối cơ sở dữ liệu

            // Lấy dữ liệu từ form và loại bỏ khoảng trắng thừa
            $soDienThoai = trim($_POST["username"]);
            $matKhau = trim($_POST["password"]);
            
            // Kiểm tra nếu số điện thoại và mật khẩu không trống
            if (!empty($soDienThoai) && !empty($matKhau)) {
                // Mã hóa mật khẩu bằng password_hash() (an toàn hơn md5)
                $hashed_pass = password_hash($matKhau, PASSWORD_BCRYPT);
                
                // Chuẩn bị câu lệnh SQL để kiểm tra xem số điện thoại đã tồn tại chưa
                $sql_check = "SELECT * FROM taikhoan WHERE SoDienThoai = ?";
                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->bind_param("s", $soDienThoai); // Gắn giá trị vào câu lệnh SQL
                $stmt_check->execute();
                $result_check = $stmt_check->get_result();
                
                if ($result_check->num_rows > 0) {
                    echo "<div style='color: red;'>Số điện thoại này đã được đăng ký!</div>";
                } else {
                    // Chuẩn bị câu lệnh SQL để thêm tài khoản mới
                    $sql_insert = "INSERT INTO taikhoan (SoDienThoai, MatKhau) VALUES (?, ?)";
                    $stmt_insert = $conn->prepare($sql_insert);
                    $stmt_insert->bind_param("ss", $soDienThoai, $hashed_pass);
                    if ($stmt_insert->execute()) {
                        // Chuyển hướng về giaodien.php sau khi đăng ký thành công
                        header("Location: index.php?signup=success");
                        exit;
                    } else {
                        echo "<div style='color: red;'>Lỗi khi đăng ký: " . $stmt_insert->error . "</div>";
                    }
                    $stmt_insert->close();
                }
                
                $stmt_check->close();
            } else {
                echo "<div style='color: red;'>Vui lòng nhập đầy đủ dữ liệu!</div>";
            }

            $conn->close(); // Đóng kết nối cơ sở dữ liệu
        }
        ?>
    </div>
</body>
</html>
