<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
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
            .button button:hover {
            background-color: #e64a19;  
        }   
            .select-btn {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            position: relative;
        }
        .select-btn:hover {
            background-color: #218838;
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
            <a class="navbar-brand" href="index.php">BẢO TOÀN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="process_search.php">Tìm Kiếm Chuyến Xe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tracuuvexe.php">Tra Cứu Vé</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Lịch Trình</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="LienHe.php">Liên Hệ</a>
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
</div>

<center><div class = "result-container">
            <div class = "left-section">
                <h2>LIÊN HỆ VỚI CHÚNG TÔI</h2><br>
                <div class = "text-lg font-medium"><h2>XE KHÁCH BẢO TOÀN</h2></div><br>
                    <span class ="text-gray">Địa chỉ:
                        <span class ="text-black">56 Hoàng Diệu 2, Linh Chiểu, Thành phố Thủ Đức, Thành phố Hồ Chí Minh, Việt Nam</span><br>
                    </span>
                    <span class = "text-gray">Website: 
                        <a class = "text-black" href = "https://baotoan.com/"
                        target ="_blank" rel = "noreferrer">https://baotoan.com/
                        </a>
                    </span><br>
                    <span class = "text-gray">Điện thoại:
                        <a class = "text-black" href = "tel:0889200200">0889200200</a>
                    </span><br>
                    <span class = "text-gray">Email:
                        <a class = "text-black" href = "mailto:busbaotoan@.vn">busbaotoan@gmail.com</a>
                    </span><br>
            </div>
            <div class = "right-section">
                <h2>BẢN ĐỒ</h2>
                <iframe
                src = "https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d125388.64649063538!2d106.68110440810061!3d10.857516038179496!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x317527f7713754db%3A0x4a61b2c52f7a23c!2zNTYgxJAuIEhvw6BuZyBEaeG7h3UgMiwgTGluaCBDaGnhu4N1LCBUaOG7pyDEkOG7qWMsIEjhu5MgQ2jDrSBNaW5o!3m2!1d10.857527!2d106.76350629999999!5e0!3m2!1svi!2s!4v1736310120060!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </center>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>