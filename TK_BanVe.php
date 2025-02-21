<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê bán vé</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body
        {
            font-family: sans-serif;
        }
        .sidebar
        {
            background-color: #6c757d;
            color: white;
            padding: 20px;
            min-height: 100vh;
        }
        .sidebar a 
        {
            color: white;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        .sidebar a:hover 
        {
            background-color: #5a6268;
        }
        .sidebar .active
        {
            background-color: #343a40;
        }
        .content 
        {
            padding: 20px;
        }
        .table-striped tbody tr:nth-of-type(odd)
        {
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
                    <p><i class="fas fa-home"></i> > Thống Kê Bán Vé</p>
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
                
                <h2>Bảng Thống Kê Bán Vé</h2>
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
                            <th>Mã phiếu đặt</th>
                            <th>Mã khách hàng</th>
                            <th>Tên khách hàng</th>
                            <th>Ngày đặt vé</th>
                            <th>Ngày khởi hành</th>
                            <th>Số ghế</th>
                            <th>Tổng số tiền</th>
                            <th>Hình thức thanh toán</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $servername = "localhost"; //Địa chỉ máy chủ
                        $username = "root"; //Tên đăng nhập
                        $password = ""; //Mật khẩu
                        $dbname = "baotoan"; //Tên cơ sở dữ liệu cần kết nối
                        
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        // Kiểm tra kết nối
                        if ($conn->connect_error) {
                        die("Kết nối thất bại: " . $conn->connect_error);
                        }
                        // Sử dụng bộ mã UTF8 khi thực hiện các thao tác dữ liệu
                        $conn->set_charset("utf8");

                    // Xử lý tìm kiếm (nếu có)
                    if (isset($_GET['tim']) && !($_GET['tuNgay'] == "" && $_GET['denNgay'] == ""))
                    {
                        $tuNgay = $_GET['tuNgay'];
                        $denNgay = $_GET['denNgay'];
                        // Truy vấn dữ liệu với điều kiện tìm kiếm
                        if ($tuNgay !="" && $denNgay =="")
                        {
                            $sql = "SELECT 
                                    pdx.MaPhieuDat,
                                    kh.MaKhachHang,
                                    kh.HoTen,
                                    pdx.ThoiGianDat,
                                    cd.ThoiGianKhoiHanh,
                                    pdx.SoGhe,
                                    pdx.TongSoTien,
                                    pdx.HinhThucThanhToan
                                FROM 
                                    PhieuDatXe AS pdx
                                JOIN 
                                    KhachHang AS kh ON pdx.MaKhachHang = kh.MaKhachHang
                                JOIN 
                                    ChuyenDi AS cd ON pdx.MaChuyenDi = cd.MaChuyenDi
                                WHERE cd.ThoiGianKhoiHanh > '$tuNgay'
                                ORDER BY pdx.ThoiGianDat";
                        }
                        elseif($tuNgay =="" && $denNgay !="")
                        {
                            $sql = "SELECT 
                                    pdx.MaPhieuDat,
                                    pdx.MaKhachHang,
                                    kh.HoTen,
                                    pdx.ThoiGianDat,
                                    cd.ThoiGianKhoiHanh,
                                    pdx.SoGhe,
                                    pdx.TongSoTien,
                                    pdx.HinhThucThanhToan
                                FROM 
                                    PhieuDatXe AS pdx
                                JOIN 
                                    KhachHang AS kh ON pdx.MaKhachHang = kh.MaKhachHang
                                JOIN 
                                    ChuyenDi AS cd ON pdx.MaChuyenDi = cd.MaChuyenDi
                                WHERE cd.ThoiGianKhoiHanh < '$denNgay' 
                                ORDER BY pdx.ThoiGianDat";
                        } else
                        $sql = "SELECT 
                                    pdx.MaPhieuDat,
                                    pdx.MaKhachHang,
                                    kh.HoTen,
                                    pdx.ThoiGianDat,
                                    cd.ThoiGianKhoiHanh,
                                    pdx.SoGhe,
                                    pdx.TongSoTien,
                                    pdx.HinhThucThanhToan
                                FROM 
                                    PhieuDatXe AS pdx
                                JOIN 
                                    KhachHang AS kh ON pdx.MaKhachHang = kh.MaKhachHang
                                JOIN 
                                    ChuyenDi AS cd ON pdx.MaChuyenDi = cd.MaChuyenDi
                                WHERE cd.ThoiGianKhoiHanh BETWEEN '$tuNgay' AND '$denNgay'
                                ORDER BY pdx.ThoiGianDat";
                        } else {
                    // Truy vấn dữ liệu mặc định (ví dụ: phiếu đặt xe gần nhất)
                        $sql = "SELECT 
                                    pdx.MaPhieuDat,
                                    pdx.MaKhachHang,
                                    kh.HoTen,
                                    pdx.ThoiGianDat,
                                    cd.ThoiGianKhoiHanh,
                                    pdx.SoGhe,
                                    pdx.TongSoTien,
                                    pdx.HinhThucThanhToan
                                FROM 
                                    PhieuDatXe AS pdx
                                JOIN 
                                    KhachHang AS kh ON pdx.MaKhachHang = kh.MaKhachHang
                                JOIN 
                                    ChuyenDi AS cd ON pdx.MaChuyenDi = cd.MaChuyenDi
                                ORDER BY pdx.ThoiGianDat";
                        }
                                $result = $conn->query($sql);

                                            if ($result->num_rows > 0)
                                            {
                                                $stt = 1;

                                                while($row = $result->fetch_assoc())
                                                {
                                                    echo "<tr>";
                                                    echo "<td>" . $stt++ . "</td>";
                                                    echo "<td>" . $row["MaPhieuDat"] . "</td>";
                                                    echo "<td>" . $row["MaKhachHang"] . "</td>";
                                                    echo "<td>" . $row["HoTen"] . "</td>";
                                                    echo "<td>" . $row["ThoiGianDat"] . "</td>";
                                                    echo "<td>" . $row["ThoiGianKhoiHanh"] . "</td>";
                                                    echo "<td>" . $row["SoGhe"] . "</td>";
                                                    echo "<td>" . $row["TongSoTien"] . "</td>";
                                                    echo "<td>" . $row["HinhThucThanhToan"] . "</td>";
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