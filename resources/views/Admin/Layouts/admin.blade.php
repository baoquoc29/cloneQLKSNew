<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        /* Custom Styles */
        body {
            font-family: 'Public Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 20px;
            z-index: 1000;
            transition: margin-left 0.3s;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .sidebar .active {
            background-color: #495057;
            font-weight: bold;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            position: relative;
        }

        .navbar {
            background-color: #fff;
            color: #343a40;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            width: calc(100% - 250px);
            z-index: 1020;
            border-bottom: 1px solid #ddd;
        }

        .navbar .nav-link {
            color: #343a40;
            font-size: 0.9rem;
        }

        .navbar .navbar-nav {
            margin-top: -10px;
        }

        .navbar .navbar-toggler {
            padding: 0.25rem 0.75rem;
            font-size: 0.85rem;
        }

        .content-wrapper {
            margin-top: 60px;
            margin-left: 20px;
            margin-right: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                margin-bottom: 20px;
            }

            .main-content {
                margin-left: 0;
            }

            .navbar {
                left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-4">
            <h3>Admin Dashboard</h3>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#" data-link="dashboard"><i class="fas fa-home"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('car-type', ['page' => 1]) }}" data-link="car-type"><i class="fas fa-table"></i> Loại xe</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('car', ['page' => 1]) }}" data-link="car"><i class="fas fa-table"></i> Xe</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('trip', ['page' => 1]) }}" data-link="car-type"><i class="fas fa-table"></i>Chuyến</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('trip-detail', ['page' => 1]) }}" data-link="car-type"><i class="fas fa-table"></i>Chi tiết chuyến</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('promotion') }}" data-link="car-type"><i class="fas fa-table"></i>Quản lý khuyến mại</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('seats') }}" data-link="car-type"><i class="fas fa-table"></i>Sơ đồ chỗ ngồi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-link="charts"><i class="fas fa-chart-bar"></i> Charts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-link="users"><i class="fas fa-users"></i> Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-link="settings"><i class="fas fa-cogs"></i> Settings</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-bell"></i> Notifications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-envelope"></i> Messages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-user"></i> Profile</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Dashboard Content -->
        <!-- Content -->
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');

            sidebarLinks.forEach(link => {
                link.addEventListener('click', function () {
                    // Xóa lớp active khỏi tất cả các nút
                    sidebarLinks.forEach(link => link.classList.remove('active'));

                    // Thêm lớp active cho nút đang được nhấn
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>

</html>
