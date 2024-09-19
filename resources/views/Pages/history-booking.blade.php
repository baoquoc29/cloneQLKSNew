@extends('layouts.app')

<!-- Link tới Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Link tới jQuery và Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
    /* CSS như đã được định nghĩa ở trên */
</style>

@section('content')
    <div class="container">
        <div class="row">
            <!-- Panel bên trái: Form tìm kiếm vé -->
            <div class="col-md-3 form-filter"> <!-- Thay đổi kích thước của cột -->
                <h4 class="mb-4 text-primary">Tìm Kiếm Lịch Sử Đặt Vé</h4>
                <form method="POST" action="{{ route('history-booking.search') }}">
                    @csrf
                    <div class="filter-item">
                        <label for="fullName">Họ tên</label>
                        <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Nhập họ tên"
                            value="{{ $name ?? '' }}" required>
                        <span class="error text-danger"></span> <!-- Hiển thị lỗi -->
                    </div>
                    <div class="filter-item">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email"
                            required value="{{ $email ?? '' }}">
                        <span class="error text-danger"></span> <!-- Hiển thị lỗi -->
                    </div>
                    <div class="filter-item">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            placeholder="Nhập số điện thoại" required value="{{ $phone ?? '' }}">
                        <span class="error text-danger"></span> <!-- Hiển thị lỗi -->
                    </div>
                    <div class="filter-item">
                        <label for="ticketCode">Mã vé</label>
                        <input type="text" class="form-control" id="ticketCode" name="ticketCode"
                            placeholder="Nhập mã vé">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3" disabled>Tìm Kiếm</button>
                </form>
            </div>

            <!-- Panel bên phải: Kết quả tìm kiếm -->
            <div class="col-md-9 ticket-history"> <!-- Thay đổi kích thước của cột -->
                <h4 class="mb-4 text-primary">Danh Sách Lịch Sử Đặt Vé</h4>
                @if ($bookedTickets != null && count($bookedTickets) > 0)
                    <div class="table-container">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Mã vé</th>
                                    <th>Chuyến đi</th>
                                    <th>Nơi đón</th>
                                    <th>Nơi trả</th>
                                    <th>Loại xe</th>
                                    <th>Biển số xe</th>
                                    <th>Thời gian khởi hành</th>
                                    <th>Số ghế</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookedTickets as $bookedTicket)
                                    <tr>
                                        <td>{{ $bookedTicket['bookingId'] }}</td>
                                        <td>{{ $bookedTicket['destination'] }} - {{ $bookedTicket['departure'] }}</td>
                                        <td>{{ $bookedTicket['startDestination'] }}</td>
                                        <td>{{ $bookedTicket['endDestination'] }}</td>
                                        <td>{{ $bookedTicket['carType'] }}</td>
                                        <td>{{ $bookedTicket['licensePlate'] }}</td>
                                        <td>{{ $bookedTicket['departureTime'] }} - {{ $bookedTicket['destinationTime'] }}
                                        </td>
                                        <td>{{ count($bookedTicket['bookedSeats']) }}</td>
                                        <td>{{ $bookedTicket['totalPrice'] }}</td>
                                        <td>{{ $bookedTicket['status'] }}</td>
                                        <td>
                                            @if ($bookedTicket['status'] == 'Confirmed')
                                                <!-- Nút hủy vé -->
                                                <form method="GET"
                                                    action="{{ route('confirm-cancel', ['bookingId' => $bookedTicket['bookingId']]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hủy vé</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>Không có lịch sử đặt vé nào.</p>
                @endif
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khi người dùng nhập số điện thoại
            $('#phone').on('input', function() {
                var phone = $(this).val();
                var isValidPhone = validatePhone(phone);

                if (!isValidPhone) {
                    $('#phone').next('.error').text('Số điện thoại không hợp lệ');
                } else {
                    $('#phone').next('.error').text(''); // Xóa lỗi nếu hợp lệ
                }
            });

            // Khi người dùng nhập email
            $('#email').on('input', function() {
                var email = $(this).val();
                var isValidEmail = validateEmail(email);

                if (!isValidEmail) {
                    $('#email').next('.error').text('Email không hợp lệ');
                } else {
                    $('#email').next('.error').text(''); // Xóa lỗi nếu hợp lệ
                }
            });

            // Hàm kiểm tra định dạng số điện thoại Việt Nam
            function validatePhone(phone) {
                // Regex cho số điện thoại Việt Nam (10 số, bắt đầu bằng 03, 05, 07, 08, 09)
                var phoneRegex = /^(0[3|5|7|8|9])+([0-9]{8})$/;
                return phoneRegex.test(phone);
            }

            // Hàm kiểm tra định dạng email
            function validateEmail(email) {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
        });
    </script>
@endsection
