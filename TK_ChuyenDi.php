<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê chuyến đi</title>
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
                <h3 style = "text-align: center;"><a href ="index.php">BẢO TOÀN</a></h3>
                <button class="btn btn-secondary d-md-none mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMenu" aria-expanded="false" aria-controls="collapseMenu">
                    <i class="fas fa-bars">Danh mục quản lý</i> 
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
                    <p><i class="fas fa-home"></i> > Thống Kê Chuyến Đi</p>
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

                <h2>Bảng Thống Kê Chuyến Đi</h2>
                <form method="GET" action=""> 
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="tuNgay" class="form-label">Từ</label>
                            <input type="date" class="form-control" id="tuNgay" name="tuNgay">
                        </div>
                        <div class="col-md-3">
                            <label for="denNgay" class="form-label">Đến</label>
                            <input type="date" class="form-control" id="denNgay" name="denNgay">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button class="btn btn-primary me-2" type="submit" name="tim"><i class="fas fa-search"></i> Tìm</button> <input type="hidden" name="tim" value="1">
                            <button class="btn btn-secondary me-2">Dashboard</button>
                            <button class="btn btn-success">Xuất Excel</button>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã chuyển đi</th>
                            <th>Biển số xe</th>
                            <th>Tài xế 1</th>
                            <th>Tài xế 2</th>
                            <th>Phụ xe</th>
                            <th>Tên lộ trình</th>
                            <th>Thời gian khởi hành</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

// Thiết lập thông tin kết nối
$servername = "localhost"; //Địa chỉ máy chủ
$username = "root"; //Tên đăng nhập
$password = ""; //Mật khẩu
$dbname = "baotoan"; //Tên cơ sở dữ liệu cần kết nối
// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);
// Kiểm tra kết nối
if ($conn->connect_error) {
die("Kết nối thất bại: " . $conn->connect_error);
}
// Sử dụng bộ mã UTF8 khi thực hiện các thao tác dữ liệu
$conn->set_charset("utf8");

// Xử lý tìm kiếm (nếu có)
if (isset($_GET['tim']) && !($_GET['tuNgay'] == "" && $_GET['denNgay'] == "")) {
    $tuNgay = $_GET['tuNgay'];
    $denNgay = $_GET['denNgay'];
    // Truy vấn dữ liệu với điều kiện tìm kiếm
    if ($tuNgay !="" && $denNgay ==""){
        $sql = "SELECT 
                cd.MaChuyenDi,
                x.BienSoXe,
                nv1.TenNhanVien AS TenTaiXe1,
                nv2.TenNhanVien AS TenTaiXe2,
                nvp.TenNhanVien AS TenPhuXe,
                lt.TenLoTrinh,
                cd.ThoiGianKhoiHanh
            FROM 
                ChuyenDi AS cd
            JOIN 
                LoTrinh AS lt ON cd.MaLoTrinh = lt.MaLoTrinh
            JOIN 
                NhanVien AS nv1 ON cd.MaNhanVienTaiXe1 = nv1.MaNhanVien
            LEFT JOIN 
                NhanVien AS nv2 ON cd.MaNhanVienTaiXe2 = nv2.MaNhanVien
            LEFT JOIN 
                NhanVien AS nvp ON cd.MaNhanVienPhuXe = nvp.MaNhanVien
            JOIN
                BangGia AS bg ON cd.MaGia = bg.MaGia
            JOIN
                LoaiXe AS lx ON bg.MaLoaiXe = lx.MaLoaiXe
            JOIN
                Xe AS x ON lx.MaLoaiXe = x.MaLoaiXe
            WHERE cd.ThoiGianKhoiHanh > '$tuNgay' ";
    }
    elseif($tuNgay =="" && $denNgay !=""){
        $sql = "SELECT 
        cd.MaChuyenDi,
        x.BienSoXe,
        nv1.TenNhanVien AS TenTaiXe1,
        nv2.TenNhanVien AS TenTaiXe2,
        nvp.TenNhanVien AS TenPhuXe,
        lt.TenLoTrinh,
        cd.ThoiGianKhoiHanh
    FROM 
        ChuyenDi AS cd
    JOIN 
        LoTrinh AS lt ON cd.MaLoTrinh = lt.MaLoTrinh
    JOIN 
        NhanVien AS nv1 ON cd.MaNhanVienTaiXe1 = nv1.MaNhanVien
    LEFT JOIN 
        NhanVien AS nv2 ON cd.MaNhanVienTaiXe2 = nv2.MaNhanVien
    LEFT JOIN 
        NhanVien AS nvp ON cd.MaNhanVienPhuXe = nvp.MaNhanVien
    JOIN
        BangGia AS bg ON cd.MaGia = bg.MaGia
    JOIN
        LoaiXe AS lx ON bg.MaLoaiXe = lx.MaLoaiXe
    JOIN
        Xe AS x ON lx.MaLoaiXe = x.MaLoaiXe
    WHERE cd.ThoiGianKhoiHanh < '$denNgay' ";
    }
    else
    $sql = "SELECT 
                cd.MaChuyenDi,
                x.BienSoXe,
                nv1.TenNhanVien AS TenTaiXe1,
                nv2.TenNhanVien AS TenTaiXe2,
                nvp.TenNhanVien AS TenPhuXe,
                lt.TenLoTrinh,
                cd.ThoiGianKhoiHanh
            FROM 
                ChuyenDi AS cd
            JOIN 
                LoTrinh AS lt ON cd.MaLoTrinh = lt.MaLoTrinh
            JOIN 
                NhanVien AS nv1 ON cd.MaNhanVienTaiXe1 = nv1.MaNhanVien
            LEFT JOIN 
                NhanVien AS nv2 ON cd.MaNhanVienTaiXe2 = nv2.MaNhanVien
            LEFT JOIN 
                NhanVien AS nvp ON cd.MaNhanVienPhuXe = nvp.MaNhanVien
            JOIN
                BangGia AS bg ON cd.MaGia = bg.MaGia
            JOIN
                LoaiXe AS lx ON bg.MaLoaiXe = lx.MaLoaiXe
            JOIN
                Xe AS x ON lx.MaLoaiXe = x.MaLoaiXe
            WHERE cd.ThoiGianKhoiHanh BETWEEN '$tuNgay' AND '$denNgay' ";
 } else {
    // Truy vấn dữ liệu mặc định (ví dụ: 10 chuyến đi gần nhất)
    $sql = "SELECT 
                cd.MaChuyenDi,
                x.BienSoXe,
                nv1.TenNhanVien AS TenTaiXe1,
                nv2.TenNhanVien AS TenTaiXe2,
                nvp.TenNhanVien AS TenPhuXe,
                lt.TenLoTrinh,
                cd.ThoiGianKhoiHanh
            FROM 
                ChuyenDi AS cd
            JOIN 
                LoTrinh AS lt ON cd.MaLoTrinh = lt.MaLoTrinh
            JOIN 
                NhanVien AS nv1 ON cd.MaNhanVienTaiXe1 = nv1.MaNhanVien
            LEFT JOIN 
                NhanVien AS nv2 ON cd.MaNhanVienTaiXe2 = nv2.MaNhanVien
            LEFT JOIN 
                NhanVien AS nvp ON cd.MaNhanVienPhuXe = nvp.MaNhanVien
            JOIN
                BangGia AS bg ON cd.MaGia = bg.MaGia
            JOIN
                LoaiXe AS lx ON bg.MaLoaiXe = lx.MaLoaiXe
            JOIN
                Xe AS x ON lx.MaLoaiXe = x.MaLoaiXe";
            }
            $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $stt = 1;

                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $stt++ . "</td>";
                                echo "<td>" . $row["MaChuyenDi"] . "</td>";
                                echo "<td>" . $row["BienSoXe"] . "</td>";
                                echo "<td>" . $row["TenTaiXe1"] . "</td>";
                                echo "<td>" . $row["TenTaiXe2"] . "</td>";
                                echo "<td>" . $row["TenPhuXe"] . "</td>";
                                echo "<td>" . $row["TenLoTrinh"] . "</td>";
                                echo "<td>" . $row["ThoiGianKhoiHanh"] . "</td>";
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
