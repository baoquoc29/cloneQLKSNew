<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
    .navbar-nav .nav-link {
      font-weight: 700;
      /* Đặt trọng số chữ in đậm */
      font-size: 16px;
      /* Tăng kích thước chữ nếu cần */
      color: #000000;
      /* Màu chữ */
    }

    .navbar-nav .nav-link.active,
    .navbar-nav .nav-link:hover {
      color: #000000;
      /* Màu chữ khi hover hoặc active */
    }
  </style>
  
  <!-- Header -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Logo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="#">Trang chủ <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="{{ route('history-booking') }}">Vé của tôi<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Dịch vụ vận tải hành khách</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Dịch vụ chuyển phát nhanh</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Tin tức</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Tuyển dụng</a>
        </li>
      </ul>
    </div>
  </nav>