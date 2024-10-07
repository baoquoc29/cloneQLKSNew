<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

<style>
    .navbar {
        box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 24px;
        color: #007bff;
    }

    .navbar-nav .nav-item {
        margin-right: 10px;
    }

    .navbar-nav .nav-link {
        font-weight: 600;
        font-size: 16px;
        color: #333;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
        color: #007bff;
        background-color: rgba(0, 123, 255, .1);
        border-radius: 5px;
    }

    .navbar-toggler {
        border: none;
    }

    .navbar-toggler:focus {
        outline: none;
        box-shadow: none;
    }

    @media (max-width: 991.98px) {
        .navbar-nav {
            padding-top: 10px;
        }

        .navbar-nav .nav-item {
            margin-right: 0;
        }
    }
</style>

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/home') }}">
            <i class="fas fa-bus-alt mr-2"></i>Logo
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/home') ? 'active' : '' }}" href="{{ url('/home') }}">
                        <i class="fas fa-home mr-1"></i>Trang chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('history-booking*') ? 'active' : '' }}"
                        href="{{ route('history-booking') }}">
                        <i class="fas fa-ticket-alt mr-1"></i>Vé của tôi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-bus mr-1"></i>Dịch vụ vận tải hành khách
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-shipping-fast mr-1"></i>Dịch vụ chuyển phát nhanh
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-newspaper mr-1"></i>Tin tức
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-user-tie mr-1"></i>Tuyển dụng
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
