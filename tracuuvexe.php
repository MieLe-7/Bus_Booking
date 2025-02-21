<?php
    session_start();
?>
<?php 
$message ="" ; // Biến để lưu kết quả tìm kiếm

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
// Lấy thông tin từ form POST

    // Lấy dữ liệu từ form
    $MaPhieuDat = $_POST['MaPhieuDat'] ?? '';
    $SoDienThoai = $_POST['SoDienThoai'] ?? '';
    

    if (!empty($MaPhieuDat) || !empty($SoDienThoai) ) {
        // Truy vấn MySQL
        
        $sql = "SELECT pdx.MaPhieuDat, pdx.MaKhachHang, kh.HoTen, kh.SoDienThoai, pdx.ThoiGianDat, cd.ThoiGianKhoiHanh, pdx.SoGhe, pdx.TongSoTien, pdx.HinhThucThanhToan
                 FROM PhieuDatXe AS pdx JOIN KhachHang AS kh ON pdx.MaKhachHang = kh.MaKhachHang
                                JOIN ChuyenDi AS cd ON pdx.MaChuyenDi = cd.MaChuyenDi         
                WHERE pdx.MaPhieuDat = '$MaPhieuDat' AND kh.SoDienThoai = '$SoDienThoai' ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $message .= "Mã phiếu đặt: " . $row["MaPhieuDat"] . "<br>";
                $message .= "Tên khách hàng: " . $row["HoTen"] . "<br>";
                $message .= "Thời gian khởi hành: " . $row["ThoiGianKhoiHanh"] . "<br>";
                $message .= "Số ghế: " . $row["SoGhe"] . "<br>";
                $message .= "Tổng số tiền: " . $row["TongSoTien"] . "<br><br>";
            }
        } else {
            $message = "Không tìm thấy thông tin đặt vé!";
        }
    } else {
        $message = "Vui lòng nhập đủ thông tin để tra cứu.";
    }
$conn->close();
}

?>               
<!DOCTYPE html>
<html lang="vi">
 <head>
  <title>
   Tra cứu vé xe
  </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css">
  <style>
   body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .navbar {
            background-color: #000;
            padding: 10px 0;         
            justify-content: space-between;
            }
        .navbar a {
            color: #FFD700;
            text-decoration: none;
            margin: 0 25px;
            font-weight: bold;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        .container h2 {
            font-size: .5rem;
            color: #333;
        }
        .form-group {     
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .form-group label {
            margin-right: 10px;
            align-self: center;
            font-weight: bold;
            font-size: 16px;
        }
        .form-group input {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;           
        }
        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }
        .form-group button {
            padding: 10px 20px;
            background-color: #FFD700;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .form-group button:hover {
            background-color: #FFC107;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
        .steps {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .step {
            margin: 0 20px;
            text-align: center;           
        }
        .step img {
            max-width: 100%;
            height: auto;
            gap: 40;
        }
        .step h5 {
            font-size: 1rem;
            color: #333;
            margin-top: 10px;
        }
        .footer {
            background-color: #000;
            padding: 20px;
            text-align: center;
            margin-top: 40px;
            color: #FFD700;
        }
        .footer a {
            color: #FFD700;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .footer .text-muted {
            color: #FFD700;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .form-group {
                flex-direction: column;
            }
            .form-group input {
                margin-bottom: 10px;
                width: 100%;
            }
            .steps {
                flex-direction: column;
            }
            .step {
                margin-bottom: 20px;
            }
        }
  </style>
 </head>
 <body>
 <div class="menu">
        <div style="text-align:center;padding:10px;margin: 0 auto;text-decoration: none;">
            <a href="index.php">Trang chủ</a>
            <a href="lichtrinh.php">Lịch trình</a>
            <a href="process_search.php">Tìm Kiếm Chuyến Xe</a>
            <a href="tracuuvexe.php">Tra cứu vé</a>
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
  <main class="container">
        <h1>TRA CỨU THÔNG TIN ĐẶT VÉ</h1>
        <form  id="searchForm" method="POST"action="">
            <div class="form-group">
                <label for="MaPhieuDat">Mã vé</label>
                <input id="MaPhieuDat" name="MaPhieuDat" placeholder="Vui lòng nhập mã vé" type="text"required/>
                <label for="SoDienThoai">Số điện thoại</label>
                <input id="SoDienThoai" name="SoDienThoai" placeholder="Vui lòng nhập số điện thoại" type="text"required/>
            <button type="submit" name="tracuu" id="search-btn" onclick="searchTickets()">Tra cứu</button>
            </div> 
        </form> 
   </main>   
   <div class="steps">
        <div class="step1">
            <img alt="Hình ảnh minh họa nhập thông tin vé" height="200" src="./images/nhapttvx.png" width="200"/>
        <h5>Bước 1. Nhập thông tin vé</h5>
        </div>
        <div class="step2">
            <img alt="Hình ảnh minh họa kiểm tra vé" height="200" src="./images/tracuuvx.png" width="200"/>
        <h5>Bước 2. Kiểm tra vé</h5>       
        </div>
    </div>           
    <!-- Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultModalLabel">Kết Quả Tra Cứu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($message)) echo $message; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hiển thị modal nếu có thông báo
        <?php if (!empty($message)) : ?>
            var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
            resultModal.show();
        <?php endif; ?>
    </script>            
<!-- Thêm Bootstrap và JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <div class="footer">
   <span>Bản quyền © bởi <a href="">Bảo Toàn Company</a>-<script>document.write(new Date().getFullYear());</script>.</span>
   <span class="text-muted">Đặt vé xe khách trực tuyến</span>
   <p class="float-right">
        <a href="#">Về đầu trang</a>
   </p>
   <p><a href="#" id="privacy-policy-link">Chính sách bảo mật</a> | <a href="#" id="terms-of-service-link">Điều khoản sử dụng</a></p>
  </div>
  <div class="modal" id="privacy-policy-modal">
        <div class="modal-content">
            <span class="close" id="close-privacy-policy">
                 &times;
            </span>
        <h2>Chính sách bảo mật</h2>
        <p>
        Nội dung chính sách bảo mật...
        </p>
        </div>
  </div>
  <div class="modal" id="terms-of-service-modal">
        <div class="modal-content">
            <span class="close" id="close-terms-of-service">
             &times;
            </span>
            <h2>
             Điều khoản sử dụng
            </h2>
            <p>
             Nội dung điều khoản sử dụng...
            </p>
        </div>
  </div> 
  <script>
   document.getElementById('privacy-policy-link').onclick = function() {
            document.getElementById('privacy-policy-modal').style.display = 'block';
        }
        document.getElementById('terms-of-service-link').onclick = function() {
            document.getElementById('terms-of-service-modal').style.display = 'block';
        }
       
        document.getElementById('close-privacy-policy').onclick = function() {
            document.getElementById('privacy-policy-modal').style.display = 'none';
        }
        document.getElementById('close-terms-of-service').onclick = function() {
            document.getElementById('terms-of-service-modal').style.display = 'none';
        }
       
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
  </script>
 </body>
</html> 