<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm Kiếm Chuyến Xe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
             font-family: 'Roboto', sans-serif;
             background-color:rgba(244, 244, 244, 0.97);
             margin: 0;
             padding: 0;
         }
        
         .navbar {
             background-color: #000;
             padding: 10px 20px;
             display: flex;
             justify-content: space-between;
             align-items: center;
             color: #FFD700;
             position: relative;        
         }       
         .navbar a {
             color:  #FFD700;
             text-decoration: none;
             margin: 0 12px;
             transition: text-decoration 0.3s ease;
             display: inline-block;
             position: relative;        
         }
        .navbar a:hover {
             text-decoration: none;
             background-color: #ffc107; 
         }         
        .navbar .active
        {
            background-color: #ffc107e3 ;
        }
        .h3 {
             text-align: center;
             margin-top: 15px;           
             font-weight: 500;
         }
         .search-container {
             background: #f9f9f9;
             padding: 20px;
             margin-bottom: 20px; /* Tạo khoảng cách giữa box form và box kết quả */
             border-radius: 8px;
             box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
         }   
        .search-bar {
            display: flex;
            flex-wrap: wrap; /* Cho phép các phần tử xuống dòng */
            gap: 15px; /* Tạo khoảng cách giữa các phần tử */
            justify-content: space-between; /* Phân phối đều giữa các phần tử */
            width: 100%; /* Đảm bảo chiều rộng không vượt quá container */
            align-items: flex-start;
        } 
        .form-group {
            display: flex;
            flex-direction: column; /* Xếp label trên input */    
            max-width: 180px; /* Giới hạn chiều rộng tối đa */ 
            flex: 1;
            min-width: 100px;
          }
        label {
            margin-bottom: 5px; /* Khoảng cách giữa label và input */
            font-weight: bold;
          }     
         
         .form-group label {
             display: block;
             margin-bottom: 5px; 
             font-weight: bold;
         }
        input, select {           
            padding: 5px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
 
          }
          .search-button {
            display: flex;  
            justify-content: center; /* Căn giữa nút */
            margin-top: 20px; /* Tạo khoảng cách giữa nút và các ô nhập */
         }    
         .search-button button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #ff5722;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            display: inline-block;           
        }  
        .search-button button:hover {
            background-color: #e64a19;  
        }        
        .footer {
             background-color:  #000;
             color: #FFD700;
             padding: 20px;
             text-align: center;
             margin-top: 20px;
         }
         .footer a {
             color: #FFD700;
             text-decoration: none;
             margin: 0 10px;
         }
         .footer a:hover {
             text-decoration: underline;
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
             padding-top: 60px;
         }
         .modal-content {
             background-color: #fefefe;
             margin: 5% auto;
             padding: 20px;
             border: 1px solid #888;
             width: 80%;
             max-width: 600px;
             border-radius: 8px;
         }
         .close {
             color: #aaa;
             float: right;
             font-size: 28px;
             font-weight: bold;
         }
         .close:hover, .close:focus {
             color: black;
             text-decoration: none;
             cursor: pointer;
         }
          /* Box chứa kết quả tìm kiếm */
        .result-container {
            background: #f9f9f9;
            padding: 20px;  
            margin-top: 20px; /* Tạo khoảng cách giữa form và kết quả */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
          }
        .result-container h3 {
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
          }
      .result-content {
            padding: 10px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
          }
         .table-striped {
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
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
             <!-- Thanh điều hướng ngang (navbar) -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
            <a class="navbar-brand" href="#">BẢO TOÀN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="process_search.php">Tìm Kiếm Chuyến Xe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tracuuvexe.php">Tra Cứu Vé</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Lịch Trình</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="LienHe.php">Liên Hệ</a>
                    </li>       
                </ul>
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
        </nav>  
    </div>  
       <!--------- Nội dung trang -------------->
    <form name="frmTimKiem" id="frmTimKiem" method="GET" action="">    
       <div class="col-md-12 text-center">
       <h3>Tìm kiếm chuyến xe|BaoToan</h3>
    </div>
    <div class="search-container">
    <div class="search-bar">
          <!-- Điểm đi -->
            <div class="form-group">
                <label for="diemdi">Điểm đi:</label>
                <input type="text" class="form-control" id="diemdi" name="DiemDi" value ="<?php echo isset($_GET['DiemDi']) ? htmlspecialchars($_GET['DiemDi']) : '';?> "placeholder="Nhập điểm đi">
            </div>  
            <!-- Điểm đến -->
            <div class="form-group">
                <label for="diemden">Điểm đến</label>                           
                <input type="text" class="form-control" id="diemden" name="DiemDen" value=" <?php echo isset($_GET['DiemDen']) ? htmlspecialchars($_GET['DiemDen']) : '';?>"placeholder="Nhập điểm đến">
            </div>  
            <!-- Ngày đi -->
            <div class="form-group">
                 <label for="ngaydi">Ngày đi</label>
                 <input id="ngaydi" name="ThoiGianKhoiHanh" type="date" class="form-control"value ="<?php echo isset($_GET['ThoiGianKhoiHanh']) ? htmlspecialchars($_GET['ThoiGianKhoiHanh']) : ''; ?>">
            </div> 
              <!-- Ngày về -->
              <div class="form-group " id="oNgayVe" style="display: none;">
                <label for="ngayve">Ngày về</label>
                <input id="ngayve" name="ngayve" type="date" class="form-control">
            </div>
            <!-- Loại vé -->
            <div class="form-group">
                    <label for="loaive">Loại vé</label>
                        <select id="loaive"name="loaive">
                            <option value="motchieu" id="motchieu"selected>Một chiều</option>
                            <option value="khuhoi"id="khuhoi">Khứ hồi</option>
                        </select>
            </div>           
            <div class="form-group">
                        <label for="sapxep">Sắp xếp theo:</label>
                        <select id="sapxep" name="sapxep">
                            <option value="gia_asc" selected>Giá tăng dần</option>
                            <option value="gia_desc">Giá giảm dần</option>
                            <option value="thoi_gian_asc">Giờ đi sớm nhất</option>
                            <option value="thoi_gian_desc">Giờ đi muộn nhất</option>
                        </select>
            </div>  
    </div>            
    <!-- Nút Tìm chuyến -->
    <div class="search-button">
        <button type="submit"name="tim" id="search-btn" >Tìm chuyến</button>
    </div>    
    </div> 
     <!-- Kết quả tìm kiếm -->   
    <div class="result-container">
    <h5 class="text-center">Kết quả tìm kiếm</h5>
         <div class="result-content">
            <table class="table table-bordered table-striped">
                 <thead>
                     <tr> 
                          <th>Mã chuyến đi</th>                   
                          <th>Tên lộ trình</th>
                          <th>Ngày đi</th>
                          <th>Ngày về</th>                  
                          <th>Loại xe</th>
                          <th>Số ghế</th>                          
                          <th>Giá vé</th>
                          <th>Đặt vé</th>
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
    // Lấy thông tin từ biểu mẫu
     $diemdi = isset($_GET['DiemDi']) ? trim($_GET['DiemDi']) : '';
     $diemden = isset($_GET['DiemDen']) ? trim($_GET['DiemDen']) : '';
     $ngaydi = isset($_GET['ThoiGianKhoiHanh']) ? $_GET['ThoiGianKhoiHanh'] : ''; 
     $ngayve = isset($_GET['ngayve']) ? $_GET['ngayve'] : '';
     $loaive = isset($_GET['loaive']) ? $_GET['loaive'] : ''; 

   // Xác định phần ORDER BY dựa trên lựa chọn sắp xếp
     $sapxep = isset($_GET['sapxep']) ? $_GET['sapxep'] : 'gia_asc'; // Mặc định sắp xếp theo giá tăng dần
         switch ($sapxep) {
             case 'gia_asc':
                 $order_by = "ORDER BY GiaNgayThuong ASC";
             break;
             case 'gia_desc':
                 $order_by = "ORDER BY GiaNgayThuong DESC";
             break;
             case 'thoi_gian_asc':
                 $order_by = "ORDER BY ThoiGianKhoiHanh ASC";
             break;
             case 'thoi_gian_desc':
                 $order_by = "ORDER BY ThoiGianKhoiHanh DESC";
             break;
             default:
                 $order_by = "ORDER BY GiaNgayThuong ASC";
             break;
           }
     
    if (!empty($ngaydi) && !empty($diemdi) && !empty($diemden)) {
         // Kiểm tra nếu điểm đi và điểm đến giống nhau
        if ($diemdi === $diemden) {
            die("Điểm đi và điểm đến không thể giống nhau. Vui lòng chọn lại.");
            }      
        if ($loaive == 'motchieu') {
            // One-way query
            $sql = "WITH DiemDauCuoi AS (SELECT MaLoTrinh, MIN(TenDiaDiem) AS DiemDi, MAX(TenDiaDiem) AS DiemDen FROM diemdiqua GROUP BY MaLoTrinh),
                         SoGheTrong AS  (SELECT COUNT(xg.MaXeGhe) AS SoGheTrong, x.MaLoaiXe FROM xeghe xg 
                                    JOIN xe x ON xg.MaXe = x.MaXe 
                                    JOIN maughetieuchuan mgtc ON mgtc.MaLoaiXe = x.MaLoaiXe AND mgtc.MaMauGheTieuChuan = xg.MaMauGheTieuChuan
                                    WHERE xg.TrangThai = 'trống' GROUP BY x.MaLoaiXe )
                    SELECT  MaChuyenDi, dd.DiemDi, dd.DiemDen, lt.TenLoTrinh, cd.ThoiGianKhoiHanh, lx.TenLoaiXe, cd.loaive, bg.GiaNgayThuong, sg.SoGheTrong, cd.MaChuyenDi
                    FROM chuyendi AS cd
                    JOIN lotrinh AS lt ON cd.MaLoTrinh = lt.MaLoTrinh
                    JOIN DiemDauCuoi AS dd ON lt.MaLoTrinh = dd.MaLoTrinh
                    JOIN banggia AS bg ON cd.MaGia = bg.MaGia
                    JOIN loaixe AS lx ON bg.MaLoaiXe = lx.MaLoaiXe
                    LEFT JOIN SoGheTrong AS sg ON sg.MaLoaiXe = lx.MaLoaiXe
                    WHERE DATE(cd.ThoiGianKhoiHanh) = ? AND dd.DiemDi = ? AND dd.DiemDen = ? AND cd.loaive = 'Một chiều' $order_by ;";
            // Chuẩn bị câu truy vấn
            $stmt = $conn->prepare($sql);
            // Liên kết các tham số với câu truy vấn
            $stmt->bind_param("sss", $ngaydi, $diemdi, $diemden); // "ssss" nghĩa là 4 tham số kiểu string      
             } 
            elseif ($loaive == 'khuhoi' && !empty($ngayve)) {
            // Round-trip query
                    $sql = "WITH DiemDauCuoi AS (SELECT MaLoTrinh, MIN(TenDiaDiem) AS DiemDi, MAX(TenDiaDiem) AS DiemDen FROM diemdiqua GROUP BY MaLoTrinh),
                                 SoGheTrong AS (SELECT COUNT(xg.MaXeGhe) AS SoGheTrong, x.MaLoaiXe FROM xeghe xg 
                                            JOIN xe x ON xg.MaXe = x.MaXe 
                                            JOIN maughetieuchuan mgtc ON mgtc.MaLoaiXe = x.MaLoaiXe AND mgtc.MaMauGheTieuChuan = xg.MaMauGheTieuChuan
                                            WHERE xg.TrangThai = 'trống' GROUP BY x.MaLoaiXe)
                            SELECT MaChuyenDi, dd.DiemDi, dd.DiemDen, lt.TenLoTrinh, cd.ThoiGianKhoiHanh, cd.ngayve, lx.TenLoaiXe, cd.loaive, bg.GiaNgayThuong, sg.SoGheTrong, cd.MaChuyenDi
                            FROM chuyendi AS cd
                            JOIN lotrinh AS lt ON cd.MaLoTrinh = lt.MaLoTrinh
                            JOIN DiemDauCuoi AS dd ON lt.MaLoTrinh = dd.MaLoTrinh
                            JOIN banggia AS bg ON cd.MaGia = bg.MaGia
                            JOIN loaixe AS lx ON bg.MaLoaiXe = lx.MaLoaiXe
                            LEFT JOIN SoGheTrong AS sg ON sg.MaLoaiXe = lx.MaLoaiXe
                            WHERE DATE(cd.ThoiGianKhoiHanh) = ? AND DATE (cd.ngayve) = ? AND dd.DiemDi = ? AND dd.DiemDen = ? AND cd.loaive = 'Khứ hồi' $order_by;";        
            // Chuẩn bị câu truy vấn
                    $stmt = $conn->prepare($sql);
            // Liên kết các tham số với câu truy vấn
                    $stmt->bind_param("ssss", $ngaydi, $ngayve, $diemdi, $diemden); // "ssss" nghĩa là 4 tham số kiểu string
                 }        
            // Thực thi truy vấn           
        $stmt->execute();
        $result = $stmt->get_result(); 
            // Lưu kết quả 
        $chuyenDiData = [];
        while ($row = $result->fetch_assoc()) {
                $chuyenDiData[] = $row;              
                }
            // Hiển thị kết quả
        if (!empty($chuyenDiData)) {    
                foreach ( $chuyenDiData as $row) {  
                    echo "<tr>";
                    $maChuyenDi = isset($row["MaChuyenDi"]) ? $row["MaChuyenDi"] : "Không xác định";
                    echo "<td>" . $maChuyenDi . "</td>";
                    echo "<td>". $row["TenLoTrinh"] . "</td>";
                    echo "<td>".($row["ThoiGianKhoiHanh"]) . "</td>";
                    echo "<td>". (!empty($row['ngayve']) ? $row['ngayve'] :"Không có")."</td>";       
                    echo "<td>". $row['TenLoaiXe']. "</td>";
                    echo "<td>". $row['SoGheTrong']."</td>";                   
                    echo "<td>". number_format($row['GiaNgayThuong'], 0, ',', '.') . " VND</td>";
                    echo "<td><a href='bookticket.php?MaChuyenDi=" . $row["MaChuyenDi"] . "' class='btn btn-success'>Chọn chuyến</a></td>";
                    echo "</tr>";
                 }
            }  
            else {
                    echo "<div class='container mt-5'><p>Không tìm thấy chuyến đi phù hợp.</p></div>";
                }
        $stmt->close();
            } 
        else {
                echo "<p>Vui lòng nhập đầy đủ thông tin để tìm kiếm chuyến đi.</p>";
            }  
            // Đóng kết nối                     
        $conn->close();
        ?>
      </tbody>
     </table>
    </div>
   </div>
  </form>  
</div>
<!---------------footer---------------->
<div class="footer">
    <span>Bản quyền © bởi<a href="">Bảo Toàn Company</a>- <script>document.write(new Date().getFullYear());</script>.</span>
    <span>Đặt vé xe khách trực tuyến</span>
    <p class="float-right"><a href="#">Về đầu trang</a></p>    
    <p><a href="#" id="privacy-policy-link">Chính sách bảo mật</a>|<a href="#" id="terms-of-service-link">Điều khoản sử dụng</a>|<a href="#" id="contact-link">Liên hệ</a></p>
</div>
<div class="modal" id="privacy-policy-modal">
    <div class="modal-content">
     <span class="close" id="close-privacy-policy">&times;</span>
     <h2>Chính sách bảo mật</h2>
     <p>Nội dung chính sách bảo mật...</p>
    </div>
</div>
<div class="modal" id="terms-of-service-modal">
    <div class="modal-content">
     <span class="close" id="close-terms-of-service">&times;</span>
     <h2>Điều khoản sử dụng</h2>
     <p>Nội dung điều khoản sử dụng...</p>
    </div>
</div>
<div class="modal" id="contact-modal">
    <div class="modal-content">
     <span class="close" id="close-contact">&times;</span>
     <h2>Liên hệ</h2>
     <p> Điện thoại: 034.214.6187. Email: baotoan@baotoan.com</p>
     <p> Địa chỉ: 56 Đ.Hoàng Diệu 2,Linh Chiểu, Thủ Đức, Hồ Chí Minh.</p>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
// Hiện/ẩn trường "Ngày về"
document.getElementById('loaive').addEventListener('change', function() {
    const ngayVe = document.getElementById('oNgayVe');
    if (this.value === 'khuhoi') {
        ngayVe.style.display = 'flex'; // Hiển thị ô "Ngày về"
    } else {
        ngayVe.style.display = 'none'; // Ẩn ô "Ngày về"
    }
});
         // Modal logic
    const privacyPolicyModal = document.getElementById('privacy-policy-modal');
    const termsOfServiceModal = document.getElementById('terms-of-service-modal');
    const contactModal = document.getElementById('contact-modal');
    const privacyPolicyLink = document.getElementById('privacy-policy-link');
    const termsOfServiceLink = document.getElementById('terms-of-service-link');
    const contactLink = document.getElementById('contact-link');
    const closePrivacyPolicy = document.getElementById('close-privacy-policy');
    const closeTermsOfService = document.getElementById('close-terms-of-service');
    const closeContact = document.getElementById('close-contact'); 
 
    privacyPolicyLink.onclick = function() {
    privacyPolicyModal.style.display = 'block';
    } 
    termsOfServiceLink.onclick = function() {
    termsOfServiceModal.style.display = 'block';
    } 
    contactLink.onclick = function() {
    contactModal.style.display = 'block';
    } 
    closePrivacyPolicy.onclick = function() {
    privacyPolicyModal.style.display = 'none';
    } 
    closeTermsOfService.onclick = function() {
    termsOfServiceModal.style.display = 'none';
    } 
    closeContact.onclick = function() {
    contactModal.style.display = 'none';
    } 
    window.onclick = function(event) {
      if (event.target == privacyPolicyModal) {
        privacyPolicyModal.style.display = 'none';
       }
      if (event.target == termsOfServiceModal) {
       termsOfServiceModal.style.display = 'none';
       }
       if (event.target == contactModal) {
       contactModal.style.display = 'none';
       }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>