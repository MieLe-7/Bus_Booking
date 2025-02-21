<?php 
    session_start();
?>
<?php
    // Kết nối cơ sở dữ liệu
    require_once "config.php";

    
    // Lấy mã chuyến từ URL
    if (isset($_GET['MaChuyenDi'])) {
        $maChuyenDi = $_GET['MaChuyenDi'];

        // Lấy thông tin chuyến xe
        $sql = "SELECT cd.MaChuyenDi, lt.TenLoTrinh, cd.ThoiGianKhoiHanh, lx.TenLoaiXe, bg.GiaNgayThuong 
                FROM chuyendi AS cd
                JOIN lotrinh AS lt ON cd.MaLoTrinh = lt.MaLoTrinh
                JOIN banggia AS bg ON cd.MaGia = bg.MaGia
                JOIN loaixe AS lx ON bg.MaLoaiXe = lx.MaLoaiXe
                WHERE cd.MaChuyenDi = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error;
            exit();
          }
        $stmt->bind_param("i", $maChuyenDi);
        $stmt->execute();
        $result = $stmt->get_result();

        // Kiểm tra xem có dữ liệu không
        if ($result->num_rows > 0) {
            $chuyen = $result->fetch_assoc();
        } else {
            echo "Không tìm thấy thông tin chuyến xe.";
            exit();
        }
    } else {
        echo "Không có mã chuyến xe.";
        exit();
    }

    // Lấy mã loại xe cho chuyến đi
    $sqlLoaiXe = "SELECT MaLoaiXe FROM chuyendi WHERE MaChuyenDi = ?";
    $stmtLoaiXe = $conn->prepare($sqlLoaiXe);
    $stmtLoaiXe->bind_param("i", $maChuyenDi);
    $stmtLoaiXe->execute();
    $resultLoaiXe = $stmtLoaiXe->get_result();
    $loaiXe = $resultLoaiXe->fetch_assoc();
    $stmtLoaiXe->close();

    // Lấy danh sách xe thuộc loại xe này
    $sqlXe = "SELECT MaXe, BienSoXe FROM xe WHERE MaLoaiXe = ?";
    $stmtXe = $conn->prepare($sqlXe);
    $stmtXe->bind_param("i", $loaiXe['MaLoaiXe']);
    $stmtXe->execute();
    $resultXe = $stmtXe->get_result();
    $xeList = [];
    while ($row = $resultXe->fetch_assoc()) {
        $xeList[] = $row;
    }
    $stmtXe->close();

    // Lấy danh sách ghế theo mã xe
    $lowerDeckSeats = [];
    $upperDeckSeats = [];
    foreach ($xeList as $xe) {
    $sql = " SELECT tg.MaGhe, tg.LoaiGhe, xg.TrangThai 
            FROM xeghe xg
            JOIN maughetieuchuan tg ON xg.MaMauGheTieuChuan = tg.MaMauGheTieuChuan
            WHERE xg.MaXe = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $xe['MaXe']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        if ($row['LoaiGhe'] === 'Tầng dưới') {
            $lowerDeckSeats[] = $row;
        } elseif ($row['LoaiGhe'] === 'Tầng trên') {
            $upperDeckSeats[] = $row;
        }
    }
    $stmt->close();
    }

    // Lấy thông tin lộ trình từ bảng ChuyenDi
    $sqlLoTrinh = " SELECT lt.TenLoTrinh, lt.ThoiGianDiChuyen FROM chuyendi cd
                    JOIN lotrinh lt ON cd.MaLoTrinh = lt.MaLoTrinh
                    WHERE cd.MaChuyenDi = ?";
    $stmtLoTrinh = $conn->prepare($sqlLoTrinh);
    $stmtLoTrinh->bind_param("i", $maChuyenDi);
    $stmtLoTrinh->execute();
    $resultLoTrinh = $stmtLoTrinh->get_result();

    // Khởi tạo biến lưu dữ liệu lộ trình
    $loTrinhInfo = null;
    if ($resultLoTrinh->num_rows > 0) {
    $loTrinhInfo = $resultLoTrinh->fetch_assoc();
    }
    $stmtLoTrinh->close();

    // Truy vấn tất cả giá vé cho MaLoaiXe
    $sqlPrice = "SELECT b.GiaNgayThuong, b.GiaNgayDacBiet 
    FROM banggia b
    JOIN chuyendi c ON b.MaGia = c.MaGia
    WHERE c.MaChuyenDi = ?";

    $stmtPrice = $conn->prepare($sqlPrice);
    $stmtPrice->bind_param("i", $maChuyenDi);
    $stmtPrice->execute();
    $resultPrice = $stmtPrice->get_result();

    if ($row = $resultPrice->fetch_assoc()) {
    $giaNgayThuong = $row['GiaNgayThuong'];
    $giaNgayDacBiet = $row['GiaNgayDacBiet'];

    } else {
    echo "Không tìm thấy giá vé cho chuyến đi này!";
    }

    $stmtPrice->close();
    // Lay thong tin diem don
    $sqlPickup = "SELECT dt.MaDiemDonTra, dt.TenDiem FROM diemdontra dt
                JOIN chuyendi c ON dt.MaLoTrinh = c.MaLoTrinh
                WHERE MaChuyenDi = ?";
    $stmtPickup = $conn->prepare($sqlPickup);
    $stmtPickup->bind_param("i", $maChuyenDi);
    $stmtPickup->execute();
    $resultPickup = $stmtPickup->get_result();
    $stmtPickup->close();

    // Lay thong tin diem tra
    $sqlDropoff = "SELECT dt.MaDiemDonTra, dt.TenDiem FROM diemdontra dt
                JOIN chuyendi c ON dt.MaLoTrinh = c.MaLoTrinh
                WHERE MaChuyenDi = ?";
    $stmtDropoff = $conn->prepare($sqlDropoff);
    $stmtDropoff->bind_param("i", $maChuyenDi);
    $stmtDropoff->execute();
    $resultDropoff = $stmtDropoff->get_result();
    $stmtDropoff->close();
    $conn->close();
    ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm chuyến xe - Đặt vé</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Thanh menu -->
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
    <header>
        <h1>Bao Toan Car</h1>
        <p>Website và hotline đặt vé duy nhất của Công ty Bao Toan.</p>
    </header>
    <div class="container">
        <h3>CHỌN GHẾ NGỒI</h3>
        <!-- GHE TANG TREN -->
        <div class="seating-area">
            <h4>Ghế tầng dưới</h4>
            <div class="seats" id="lowerDeck">
                <?php foreach ($lowerDeckSeats as $seat): ?>
                    <div class="seat <?= $seat['TrangThai'] === 'da_ban' ? 'taken' : '' ?>" 
                    data-seat="<?= $seat['MaGhe'] ?>"
                    onclick="selectSeat('<?= $seat['MaGhe'] ?>')">
                    <?= $seat['MaGhe'] ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- GHE TANG DUOI -->
        <div class="seating-area">
            <h4>Ghế tầng trên</h4>
            <div class="seats" id="upperDeck">
                <?php foreach ($upperDeckSeats as $seat): ?>
                    <div class="seat <?= $seat['TrangThai'] === 'da_ban' ? 'taken' : '' ?>" 
                    data-seat="<?= $seat['MaGhe'] ?>" 
                    onclick="selectSeat('<?= $seat['MaGhe'] ?>')">
                    <?= $seat['MaGhe'] ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
            <!-- THEM CHU THICH -->
            <div class="legend">
            <div class="legend-item">
                <div class="legend-box taken"></div>
                <span>Ghế đã bán</span>
            </div>
            <div class="legend-item">
                <div class="legend-box selected"></div>
                <span>Ghế đang chọn</span>
            </div>
            <div class="legend-item">
                <div class="legend-box empty"></div>
                <span>Ghế trống</span>
            </div>
        </div>

        <input type="hidden" name="selectedSeats" id="selectedSeats" value="">
        </div>


        <div class="bottom-section">
            <!-- THONG TIN KHACH HANG -->
            <div class="left-section">
                <div class="section">
                    <h2>THÔNG TIN KHÁCH HÀNG</h2>
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input type="text" id="name" name="name" placeholder="Nhập họ và tên" required>
                        <span id="fullNameError" class="error-message" style="color: red; display: none;">Vui lòng nhập Họ và Tên.</span>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
                        <span id="phoneError" class="error-message" style="color:red; display:none;">Số điện thoại không hợp lệ!</span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Nhập email" required>
                        <span id="emailError" class="error-message" style="color:red; display:none;">Email không hợp lệ!</span>
                    </div>
                    <input type="checkbox"> Chấp nhận <a href="#">Điều khoản</a> và <a href="#">Chính sách bảo mật</a>
                </div>
                <!-- TAO PHAN THONG TIN DON TRA-->
                <div class="section">
                <h2>THÔNG TIN ĐÓN TRẢ</h2>
                    <div class="form-group">
                        <label for="pickup">ĐIỂM ĐÓN KHÁCH</label>
                        <div class="radio-group">
                            <input type="radio" id="pickup-point" name="pickup_type" value="point" checked onclick="togglePickupInput()">
                            <label for="pickup-point">Điểm đón</label>

                            <input type="radio" id="pickup-transfer" name="pickup_type" value="transfer" onclick="togglePickupInput()">
                            <label for="pickup-transfer">Trung chuyển</label>
                        </div>
                        <select id="pickup" name="pickup" required>
                            <option value="">-- Chọn điểm đón --</option>
                            <?php while ($row = $resultPickup->fetch_assoc()) { ?>
                                <option value="<?= $row['MaDiemDonTra'] ?>"><?= $row['TenDiem'] ?></option>
                            <?php } ?>
                        </select>
                        <!-- Input cho trường hợp chọn Trung chuyển -->
                        <div id="pickup-transfer-container" style="display: none;">
                            <label for="pickup-transfer-input">Nhập điểm đón trung chuyển</label>
                            <input type="text" id="pickup-transfer-input" name="pickup_transfer" placeholder="Nhập điểm đón trung chuyển" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dropoff">ĐIỂM TRẢ KHÁCH</label>
                        <div class="radio-group">
                            <input type="radio" id="dropoff-point" name="dropoff_type" value="point" checked onclick="toggleDropoffInput()">
                            <label for="dropoff-point">Điểm trả</label>

                            <input type="radio" id="dropoff-transfer" name="dropoff_type" value="transfer" onclick="toggleDropoffInput()">
                            <label for="dropoff-transfer">Trung chuyển</label>
                        </div>
                        <select id="dropoff" name="dropoff" required>
                            <option value="">-- Chọn điểm trả --</option>
                            <?php while ($row = $resultDropoff->fetch_assoc()) { ?>
                                <option value="<?= $row['MaDiemDonTra'] ?>"><?= $row['TenDiem'] ?></option>
                            <?php } ?>
                        </select>
                        <!-- Input cho trường hợp chọn Trung chuyển -->
                        <div id="dropoff-transfer-container" style="display: none;">
                            <label for="dropoff-transfer-input">Nhập điểm trả trung chuyển</label>
                            <input type="text" id="dropoff-transfer-input" name="dropoff_transfer" placeholder="Nhập điểm trả trung chuyển" />
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // Hàm để hiển thị/ẩn trường nhập điểm đón khi chọn Trung chuyển
                function togglePickupInput() {
                    var pickupType = document.querySelector('input[name="pickup_type"]:checked').value;
                    var pickupSelect = document.getElementById('pickup');
                    var pickupTransferContainer = document.getElementById('pickup-transfer-container');
                    
                    if (pickupType === 'transfer') {
                        pickupSelect.style.display = 'none';
                        pickupTransferContainer.style.display = 'block';
                    } else {
                        pickupSelect.style.display = 'block';
                        pickupTransferContainer.style.display = 'none';
                    }
                }

                // Hàm để hiển thị/ẩn trường nhập điểm trả khi chọn Trung chuyển
                function toggleDropoffInput() {
                    var dropoffType = document.querySelector('input[name="dropoff_type"]:checked').value;
                    var dropoffSelect = document.getElementById('dropoff');
                    var dropoffTransferContainer = document.getElementById('dropoff-transfer-container');
                    
                    if (dropoffType === 'transfer') {
                        dropoffSelect.style.display = 'none';
                        dropoffTransferContainer.style.display = 'block';
                    } else {
                        dropoffSelect.style.display = 'block';
                        dropoffTransferContainer.style.display = 'none';
                    }
                }

                // Gọi hàm khi trang tải để đảm bảo trạng thái ban đầu đúng
                window.onload = function() {
                    togglePickupInput();
                    toggleDropoffInput();
                }
            </script>
            <!-- THONG TIN LO TRINH -->
            <div class="right-section">
                <div class="section">
                    <h2>THÔNG TIN LỘ TRÌNH</h2>
                    <div class="info-row">
                        <span class="info-label">Tên lộ trình: </span>
                        <span class="info-content">
                            <?= $loTrinhInfo ? htmlspecialchars($loTrinhInfo['TenLoTrinh']) : 'Không tìm thấy thông tin lộ trình.' ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Thời gian khởi hành: </span>
                        <span class="info-content">
                            <?= $loTrinhInfo ? htmlspecialchars($loTrinhInfo['ThoiGianDiChuyen']) : '---' ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label" >Vị trí:</span>
                        <span class="info-content" id="selectedSeatsList">Chưa có ghế nào được chọn</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label" >Số vé đã chọn:</span>
                        <span class="info-content count">0</span>
                    </div>
                </div>
                <!--CHI TIET GIA-->
                <div class="section">
                    <h2>CHI TIẾT GIÁ</h2>
                    <p class="total">
                        <strong>Giá ngày thường: </strong>
                        <span id="regularPrice">
                            <?= number_format($giaNgayThuong, 0, ',', '.') ?> VNĐ (Đang được áp dụng)
                        </span>
                    </p>
                    <p class="total">
                        <strong>Giá ngày đặc biệt: </strong>
                        <span id="specialPrice">
                            <?= number_format($giaNgayDacBiet, 0, ',', '.') ?> VNĐ
                        </span>
                    </p>
                    <p class="total"><strong>Tổng tiền: </strong><span id="totalPrice">0 VNĐ</span></p> 
                    <form action="process_payment.php" method="POST" id="paymentForm" onsubmit="return validateForm()">
                        <!-- CAC TRUONG AN CHUA TONG TIEN -->
                        <input type="hidden" name="seatList" id="seatList">
                        <input type="hidden" name="seatCount" id="seatCount">
                        <input type="hidden" name="totalAmount" id="totalAmount">
                        <!-- THEM CAC TRUONG THONG TIN KHACH HANG VAO FROM -->
                        <input type="hidden" name="name" id="nameInput">
                        <input type="hidden" name="phone" id="phoneInput">
                        <input type="hidden" name="email" id="emailInput">
                        <input type="hidden" name="pickupDetails" id="pickupDetails">
                        <input type="hidden" name="dropoffDetails" id="dropoffDetails">
                        <button type="submit">Thanh toán</button>
                        <button type="button" id="cancelBtn" onclick="cancelSelection()">Hủy</button>
                    </form>
                </div>
            </div>
        </div>
        </div>
        <footer>
        <p>© 2025 Bao Toan Car. All rights reserved.</p>
        <p>Phát triển bởi Đội ngũ IT Bao Toan.</p>
        </footer>
    <script>
        let selectedSeats = [];
        let totalPrice = 0;

        // HAM CHON GHE
        function selectSeat(seatId) {
            const seatElement = document.querySelector(`[data-seat='${seatId}']`);

            if (seatElement.classList.contains('taken')) {
                alert('Ghế này đã được bán!');
                return;
            }

            // Thay doi trang thai lop CSS
            seatElement.classList.toggle('selected');

            // Cap nhat danh sach ghe da chon
            if (seatElement.classList.contains('selected')) {
                selectedSeats.push(seatId);
            } else {
                selectedSeats = selectedSeats.filter(seat => seat !== seatId);
            }

            updateUI();
        }

        // HAM CAP NHAT GIAO DIEN
        function updateUI() {
            let seatCount = selectedSeats.length;
            document.getElementById('seatCount').value = seatCount;
            document.getElementById('selectedSeatsList').innerText = selectedSeats.length > 0
                ? selectedSeats.join(', ')
                : 'Chưa có ghế nào được chọn';

            document.querySelector('.count').innerText = selectedSeats.length;
            document.getElementById('selectedSeats').value = selectedSeats.join(',');
            document.getElementById('seatList').value = selectedSeats.join(',');

            // Lấy giá ngày thường
            const regularPrice = parseFloat(
                document.getElementById('regularPrice').innerText
                    .replace('VNĐ', '')
                    .replace(/\./g, '')
                    .trim()
            ) || 0;

            // Tính tổng tiền
            const total = seatCount * regularPrice;

            // Cập nhật tổng tiền hiển thị
            document.getElementById('totalPrice').innerText = total.toLocaleString('vi-VN') + ' VNĐ';

            // Gán tổng tiền vào hidden input để gửi lên server
            document.getElementById('totalAmount').value = total;
        }

        // HAM NUT HUY CHON GHE
        function cancelSelection() {
            selectedSeats.forEach(seatId => {
                const seatElement = document.querySelector(`[data-seat='${seatId}']`);
                seatElement.classList.remove('selected');
            });

            selectedSeats = [];
            updateUI();
        }

        // HAM THONG BAO NEU CHUA DU DIEU KIEN NHAN NUT "THANH TOAN"
        function validateForm() {
            const selectedSeats = document.getElementById('selectedSeats').value.trim();
            const termsAccepted = document.querySelector('input[type="checkbox"]').checked;

            // Kiểm tra nếu không có ghế nào được chọn
            if (!selectedSeats) {
                alert('Vui lòng chọn ít nhất một ghế trước khi thanh toán.');
                return false; // Ngăn form gửi đi
            }

            // Kiểm tra nếu chưa chấp nhận điều khoản
            if (!termsAccepted) {
                alert('Vui lòng chấp nhận điều khoản và chính sách bảo mật trước khi thanh toán.');
                return false; // Ngăn form gửi đi
            }

            // Lấy giá trị các trường nhập liệu
            const fullNameInput = document.getElementById('name').value.trim();
            const phoneInput = document.getElementById('phone').value;
            const emailInput = document.getElementById('email').value;

            // Lấy các phần tử thông báo lỗi
            const fullNameError = document.getElementById('fullNameError');
            const phoneError = document.getElementById('phoneError');
            const emailError = document.getElementById('emailError');
            
            let isValid = true; // Biến flag kiểm tra tính hợp lệ

            // Kiểm tra Họ và Tên không được để trống
            if (!fullNameInput) {
                fullNameError.style.display = 'inline';  // Hiển thị thông báo lỗi
                isValid = false;
            } else {
                fullNameError.style.display = 'none';  // Ẩn thông báo lỗi nếu hợp lệ
            }
            // Kiểm tra số điện thoại
            const phonePattern = /^[0-9]{10}$/; // Pattern cho số điện thoại Việt Nam
            if (!phonePattern.test(phoneInput)) {
                phoneError.style.display = 'inline';  // Hiển thị thông báo lỗi
                isValid = false; // Đánh dấu là không hợp lệ
            } else {
                phoneError.style.display = 'none';  // Ẩn thông báo lỗi nếu hợp lệ
            }

            // Kiểm tra email
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/; // Pattern cho email
            if (!emailPattern.test(emailInput)) {
                emailError.style.display = 'inline';  // Hiển thị thông báo lỗi
                isValid = false; // Đánh dấu là không hợp lệ
            } else {
                emailError.style.display = 'none';  // Ẩn thông báo lỗi nếu hợp lệ
            }

            // Nếu tất cả đều hợp lệ, cho phép gửi form
            return isValid;
        }
        // HAM CAP NHAT THONG TIN DIEM DON TRA
        function updatePickupDetails() {
            const pickupType = document.querySelector('input[name="pickup_type"]:checked').value;
            let pickupDetails = '';

            if (pickupType === 'transfer') {
                const transferInput = document.getElementById('pickup-transfer-input').value.trim();
                pickupDetails = transferInput ? `Trung chuyển: ${transferInput}` : 'Chưa nhập địa điểm trung chuyển';
            } else {
                const pickupSelect = document.getElementById('pickup');
                pickupDetails = pickupSelect.options[pickupSelect.selectedIndex].text;
            }

            document.getElementById('pickupDetails').value = pickupDetails;
            document.querySelector('.pickup-info').innerText = pickupDetails;
        }
        // HAM CAP NHAT THONG TIN TRUNG CHUYEN
        function updateDropoffDetails() {
            const dropoffType = document.querySelector('input[name="dropoff_type"]:checked').value;
            let dropoffDetails = '';

            if (dropoffType === 'transfer') {
                const transferInput = document.getElementById('dropoff-transfer-input').value.trim();
                dropoffDetails = transferInput ? `Trung chuyển: ${transferInput}` : 'Chưa nhập địa điểm trung chuyển';
            } else {
                const dropoffSelect = document.getElementById('dropoff');
                dropoffDetails = dropoffSelect.options[dropoffSelect.selectedIndex].text;
            }

            document.getElementById('dropoffDetails').value = dropoffDetails;
            document.querySelector('.dropoff-info').innerText = dropoffDetails;
        }

        
        document.getElementById('pickup').addEventListener('change', updatePickupDetails);
        document.getElementById('pickup-transfer-input').addEventListener('input', updatePickupDetails);

        document.getElementById('dropoff').addEventListener('change', updateDropoffDetails);
        document.getElementById('dropoff-transfer-input').addEventListener('input', updateDropoffDetails);


        document.addEventListener('DOMContentLoaded', function(){
            document.getElementById('paymentForm').addEventListener('submit', function(event) {
            
            // Kiểm tra điều kiện validateForm trước khi submit
                if (!validateForm()) {
                    event.preventDefault();  // Ngăn submit nếu không hợp lệ
                } else {
                    // //CAP NHAT THONG TIN KHACH HANG VA GIA GHE VAO TRUONG AN
                    document.getElementById('nameInput').value = document.getElementById('name').value;
                    document.getElementById('phoneInput').value = document.getElementById('phone').value;
                    document.getElementById('emailInput').value = document.getElementById('email').value;
                    document.getElementById('pickupDetails').value = document.querySelector('.pickup-info').innerText;
                    document.getElementById('dropoffDetails').value = document.querySelector('.dropoff-info').innerText;
                    document.getElementById('seatList').value = selectedSeats.join(',');
            
                    const totalAmountValue = selectedSeats.length * price;
                    const formattedTotalAmount = totalAmountValue.toLocaleString();  // Dinh dang
                    document.getElementById('totalAmount').value = formattedTotalAmount;
                }
            });
        });
    </script>
</body>
</html>