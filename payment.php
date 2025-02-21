<?php
    session_start();
?>
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $SoDienThoai = $_POST['SoDienThoai'];
        $email = $_POST['email'];
        $pickupDetails = $_POST['pickupDetails'];
        $dropoffDetails = $_POST['dropoffDetails'];
        $seatCount = $_POST['seatCount'];
        $ThoiGianDat = date('Y-m-d H:i:s');
        // Lấy danh sách ghế từ POST
        $seatList = isset($_POST['seatList']) ? $_POST['seatList'] : '';

        // Chuyển danh sách ghế thành mảng nếu cần
        $seats = explode(',', $seatList);
        $totalAmount = $_POST['totalAmount'];

// Kiểm tra xem khách hàng đã tồn tại hay chưa
$sql_check = "SELECT MaKhachHang FROM KhachHang WHERE SoDienThoai = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("s", $SoDienThoai);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Nếu khách hàng đã tồn tại, lấy mã khách hàng
    $row = $result->fetch_assoc();
    $MaKhachHang = $row['MaKhachHang'];
} else {
    // Nếu khách hàng chưa tồn tại, tạo mã khách hàng mới
    $MaKhachHang = uniqid('KH');  // Tạo mã khách hàng duy nhất
    $sql_insert = "INSERT INTO KhachHang (MaKhachHang, HoTen, SoDienThoai) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("ssss", $MaKhachHang, $name, $phone);
    $stmt->execute();
}

// Thêm phiếu đặt xe vào cơ sở dữ liệu
$sql_insert_phieu = "INSERT INTO PhieuDatXe (MaKhachHang, ThoiGianDat, MaChuyenDi, SoGhe, TongSoTien) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql_insert_phieu);
$stmt->bind_param("sssss", $MaKhachHang, $ThoiGianDat, $maChuyenDi, $seats, $totalAmount);

if ($stmt->execute()) {
    echo "Đặt vé thành công!";
} else {
    echo "Lỗi: " . $stmt->error;
}

$stmt->close();

// Đóng kết nối
$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm chuyến xe - Thanh toán</title>
    <link rel="stylesheet" href="style.css">
</head>
<body> 
    <!-- Thanh menu -->
    <div class="menu">
        <div style="text-align:center;padding:10px;margin: 0 auto;text-decoration: none;">
            <a href="index.php">Trang chủ</a>
            <a href="lichtrinh.php">Lịch trình</a>
            <a href="process_search.php">Tìm Kiếm Chuyến Xe</a>
            <a href="tracuuve.php">Tra cứu vé</a>
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
    <div class="payment-container">
        <div class="payment-section">
            <!-- Hiển thị thông tin khách hàng -->
            <h2>CHI TIẾT GIAO DỊCH</h2>
            <p><strong>Họ và tên:</strong> <span><?php echo htmlspecialchars($name); ?></span></p>
            <p><strong>Số điện thoại:</strong> <span><?php echo htmlspecialchars($phone); ?></span></p>
            <p><strong>Email:</strong> <span><?php echo htmlspecialchars($email); ?></span></p>
            <p><strong>Điểm đón:</strong> <span><?php echo htmlspecialchars($pickupDetails); ?></span></p>
            <p><strong>Điểm trả:</strong> <span><?php echo htmlspecialchars($dropoffDetails); ?></span></p>
            <p><strong>Số ghế đã chọn:</strong> <span><?php echo htmlspecialchars($seatCount); ?></span></p>
            <p>
                <strong>Vị trí ghế đã chọn:</strong>
                <span>
                    <?php if (!empty($seats)): ?>
                        <?= htmlspecialchars(implode(', ', $seats)) ?>
                    <?php else: ?>
                        Chưa có ghế nào được chọn.
                    <?php endif; ?>
                </span>
            </p>
            <p><strong>Tổng tiền:</strong> <span><?php echo number_format($totalAmount, 0, ',', '.'); ?> VNĐ</span></p>
        </div>
        <div class="payment-section">
            <h2>CHỌN PHƯƠNG THỨC THANH TOÁN</h2>
            <h5><i>Sau khi chọn phương thức thanh toán phù hợp quý khách hàng vui lòng quét mã QR Code phía bên dưới để tiến hành thanh toán!</i></h5>
            <form id="paymentForm">
                <div class="payment-option">
                    <input type="radio" id="zalopay" name="payment" value="ZaloPay" checked>
                    <label for="zalopay">
                        <img src="./images/zalopay-logo.png" alt="ZaloPay" class="payment-logo"> ZaloPay
                    </label>
                </div>

                <div class="payment-option">
                    <input type="radio" id="momo" name="payment" value="MoMo">
                    <label for="momo">
                        <img src="./images/momo-logo.png" alt="MoMo" class="payment-logo"> MoMo
                    </label>
                </div>

                <div class="payment-option">
                    <input type="radio" id="vnpay" name="payment" value="VNPay">
                    <label for="vnpay">
                        <img src="./images/vnpay-logo.jpg" alt="VNPay" class="payment-logo"> VNPay
                    </label>
                </div>

                <div class="payment-option">
                    <input type="radio" id="airpay" name="payment" value="VietComBank">
                    <label for="airpay">
                        <img src="./images/vietcombank.jpg" alt="VietComBank" class="payment-logo"> VietComBank
                    </label>
                </div>
            </form>
        </div>

        <div class="payment-section">
            <div class="countdown">
                <h2>VUI LÒNG QUÉT MÃ QR CODE ĐỂ TIẾN HÀNH THANH TOÁN</h2>
                <form>
                    <!-- Hiển thị mã QR -->
                    <div class="qr-code">
                        <img id="qrCode" src="./images/qrcode_zalopay.jpg" alt="QR Code"> <!-- ID cho hình ảnh QR -->
                        <div id="loadingStatus" class="loading-status">
                            <div id="spinner" class="spinner"></div> <!-- Vòng xoay -->
                            Đang chờ thanh toán...
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
        <center><button onclick="window.history.back()">Quay lại</button></center>
    <script>
        // Hàm thay đổi mã QR và trạng thái khi chọn phương thức thanh toán
        document.getElementById('paymentForm').addEventListener('change', function(event) {
            let qrCodeSrc = '';
            let loadingStatus = document.getElementById('loadingStatus');
            
            // Kiểm tra xem người dùng chọn phương thức thanh toán nào
            if (event.target && event.target.name === 'payment') {
                // Đổi mã QR tương ứng
                switch (event.target.value) {
                    case 'ZaloPay':
                        qrCodeSrc = './images/qrcode_zalopay.jpg'; // Mã QR ZaloPay
                        break;
                    case 'MoMo':
                        qrCodeSrc = './images/qrcode_momo.jpg'; // Mã QR MoMo
                        break;
                    case 'VNPay':
                        qrCodeSrc = './images/qrcode_vnpay.jpg'; // Mã QR VNPay
                        break;
                    case 'AirPay':
                        qrCodeSrc = './images/qrcode_vietcombank.jpg'; // Mã QR AirPay
                        break;
                    default:
                        qrCodeSrc = './images/qrcode_zalopay.jpg'; // Mã QR mặc định (ZaloPay)
                        break;
                }

                // Thay đổi mã QR và trạng thái
                document.getElementById('qrCode').src = qrCodeSrc;
                loadingStatus.textContent = 'Đang chờ thanh toán...'; // Trạng thái chờ thanh toán
                spinner.style.display = 'inline-block';
            }
            setTimeout(() => {
                loadingStatus.textContent = 'Đang xử lý...'; // Thay đổi trạng thái
                spinner.style.display = 'none'; // Ẩn vòng xoay sau khi xử lý xong
            }, 3000); // Thay đổi thời gian nếu cần
        });
    </script>
    <?php
    }
    ?>
    <footer>
        <p>© 2025 Bao Toan Car. All rights reserved.</p>
        <p>Phát triển bởi Đội ngũ IT Bao Toan.</p>
    </footer>
</body>
</html>