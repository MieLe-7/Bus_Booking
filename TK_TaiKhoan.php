<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê tài khoản</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: sans-serif;
        }
        .sidebar {
            background-color: #6c757d;
            color: white;
            padding: 20px;
            min-height: 100vh;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #5a6268;
        }
        .sidebar .active{
            background-color: #343a40;
        }
        .content {
            padding: 20px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #e0f2f7; /* Light blue for odd rows */
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 sidebar">
                <h3 style = "text-align:center;"><a href = "index.php">BẢO TOÀN</a></h3>
                <button class="btn btn-secondary d-md-none mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMenu" aria-expanded="false" aria-controls="collapseMenu">
                    <i class="fas fa-bars"></i> Danh mục quản lý
                </button>

                <div class="collapse d-md-block" id="collapseMenu">
                    <a href="TK_BanVe.php" class="active d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> THỐNG KÊ BÁN VÉ</a>
                    <a href="TK_Xe.php" class="active d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> THỐNG KÊ XE</a>
                    <a href="TK_ChuyenDi.php" class="active d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> THỐNG KÊ CHUYẾN ĐI</a>
                    <a href="TK_DoanhThu.php" class="active d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> THỐNG KÊ DOANH THU</a>
                    <a href="TK_TaiKhoan.php" class="active d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> THỐNG KÊ TÀI KHOẢN</a>
                </div>
            </div>
            <div class="col-md-9 content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p><i class="fas fa-home"></i> > Thống Kê Tài Khoản</p>
                    <div class="auth-buttons">
                        <?php
                        if (isset($_SESSION['user'])) {
                            echo "<span style='color:black;'>Xin chào, " . $_SESSION['user'] . "!</span>";
                            echo '<a href="logout.php" class="btn btn-success btn-sm">Đăng xuất</a>';
                        } else {
                            echo '<a href="signup.php" class="btn btn-success btn-sm">Đăng ký</a>';
                            echo '<a href="login.php" class="btn btn-success btn-sm">Đăng nhập</a>';
                        }
                        ?>
                    </div>
                </div>

                <h2>Bảng Thống Kê Tài Khoản</h2>
                <form method="GET" action=""> 
                    <div class="row mb-3">
                        <div class="col-md-6 d-flex align-items-end">
                            <button class="btn btn-success">Xuất Excel</button>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã tài khoản</th>
                            <th>Mã nhân viên</th>
                            <th>Mã khách hàng</th>
                            <th>Số điện thoại</th>
                            <th>Mật khẩu</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

        require_once "config.php";

        // Truy vấn dữ liệu mặc định (ví dụ: các tài khoản)
        $sql = "SELECT tk.MaTaiKhoan, 
                        tk.MaNhanVien,
                        tk.MaKhachHang,
                        tk.SoDienThoai,
                        tk.MatKhau
                    FROM TaiKhoan tk";
            $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $stt = 1;

                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $stt++ . "</td>";
                                echo "<td>" . $row["MaTaiKhoan"] . "</td>";
                                echo "<td>" . $row["MaNhanVien"] . "</td>";
                                echo "<td>" . $row["MaKhachHang"] . "</td>";
                                echo "<td>" . $row["SoDienThoai"] . "</td>";
                                echo "<td>" . $row["MatKhau"] . "</td>";
                                echo "</tr>";
                            }


                        } else {
                            echo "<tr><td colspan='7'>Không có dữ liệu.</td></tr>";
                        }
                        $conn->close();
                    ?>
                </tbody>
                  
                </table>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
