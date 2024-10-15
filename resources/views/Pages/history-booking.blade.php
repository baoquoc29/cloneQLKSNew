@extends('layouts.app')

@section('content')
    <div class="container-fluid my-5">
        <div class="row">
            <!-- Panel bên trái: Form tìm kiếm vé -->
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title text-primary mb-4">Tìm Kiếm Lịch Sử Đặt Vé</h4>
                        <form method="POST" action="{{ route('history-booking.search') }}" id="searchForm">
                            @csrf
                            <div class="form-group">
                                <label for="fullName">Họ tên</label>
                                <input type="text" class="form-control" id="fullName" name="fullName"
                                    placeholder="Nhập họ tên" value="{{ $name ?? '' }}" required>
                                <div class="invalid-feedback" id="fullNameError"></div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Nhập email" value="{{ $email ?? '' }}" required>
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="Nhập số điện thoại" value="{{ $phone ?? '' }}" required>
                                <div class="invalid-feedback" id="phoneError"></div>
                            </div>
                            <div class="form-group">
                                <label for="ticketCode">Mã vé (không bắt buộc)</label>
                                <input type="text" class="form-control" id="ticketCode" name="ticketCode"
                                    placeholder="Nhập mã vé" value="{{ $bookingId ?? "" }}">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-4" id="searchButton" disabled>Tìm
                                Kiếm</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Panel bên phải: Kết quả tìm kiếm -->
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title text-primary mb-4">Danh Sách Lịch Sử Đặt Vé</h4>
                        @if ($bookedTickets != null && count($bookedTickets) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Mã vé</th>
                                            <th>Chuyến đi</th>
                                            <th>Nơi đón - trả</th>
                                            <th>Loại xe</th>
                                            <th>Thời gian</th>
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
                                                <td>{{ $bookedTicket['departure'] }} - {{ $bookedTicket['destination'] }}
                                                </td>
                                                <td>{{ $bookedTicket['startDestination'] }} -
                                                    {{ $bookedTicket['endDestination'] }}</td>
                                                <td>{{ $bookedTicket['carType'] }} ({{ $bookedTicket['licensePlate'] }})
                                                </td>
                                                <td>{{ $bookedTicket['departureTime'] }} -
                                                    {{ $bookedTicket['destinationTime'] }}</td>
                                                <td class="text-center">{{ $bookedTicket['numberSeats'] }}</td>
                                                <td>{{ number_format($bookedTicket['totalPrice'], 0, ',', '.') }} VNĐ</td>
                                                <td class="text-center">
                                                    @php
                                                        $statusText = '';
                                                        $badgeClass = '';
                                                        switch ($bookedTicket['status']) {
                                                            case 'Cancelled':
                                                                $statusText = 'Đã Hủy';
                                                                $badgeClass = 'badge-danger';
                                                                break;
                                                            case 'Confirmed':
                                                                $statusText = 'Đã Đặt';
                                                                $badgeClass = 'badge-success';
                                                                break;
                                                            case 'Completed':
                                                                $statusText = 'Đã Hoàn Thành';
                                                                $badgeClass = 'badge-info';
                                                                break;
                                                            default:
                                                                $statusText = $bookedTicket['status'];
                                                                $badgeClass = 'badge-secondary';
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($bookedTicket['status'] == 'Confirmed')
                                                        <form method="GET"
                                                            action="{{ route('confirm-cancel', ['bookingId' => $bookedTicket['bookingId']]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Hủy
                                                                vé</button>
                                                        </form>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Không có lịch sử đặt vé nào.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container-fluid {
            max-width: 1600px;
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .card-title {
            font-weight: bold;
        }

        .table {
            margin-bottom: 0;
        }

        .table th,
        .table td {
            vertical-align: middle;
            padding: 0.75rem;
        }

        .table th {
            background-color: #007bff;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }

        .badge {
            font-size: 0.9em;
            padding: 0.4em 0.6em;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let nameEntered = false;
            let emailEntered = false;
            let phoneEntered = false;

            function validateForm() {
                let isValid = true;
                const fullName = $('#fullName').val().trim();
                const email = $('#email').val().trim();
                const phone = $('#phone').val().trim();

                // Validate Full Name
                if (nameEntered && fullName === '') {
                    $('#fullName').addClass('is-invalid');
                    $('#fullNameError').text('Họ tên không được để trống');
                    isValid = false;
                } else {
                    $('#fullName').removeClass('is-invalid');
                    $('#fullNameError').text('');
                }

                // Validate Email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailEntered && !emailRegex.test(email)) {
                    $('#email').addClass('is-invalid');
                    $('#emailError').text('Email không hợp lệ');
                    isValid = false;
                } else {
                    $('#email').removeClass('is-invalid');
                    $('#emailError').text('');
                }

                // Validate Phone
                const phoneRegex = /^(0[3|5|7|8|9])+([0-9]{8})$/;
                if (phoneEntered && !phoneRegex.test(phone)) {
                    $('#phone').addClass('is-invalid');
                    $('#phoneError').text('Số điện thoại không hợp lệ');
                    isValid = false;
                } else {
                    $('#phone').removeClass('is-invalid');
                    $('#phoneError').text('');
                }

                $('#searchButton').prop('disabled', !isValid || fullName === '' || email === '' || phone === '');
            }

            $('#fullName').on('input', function() {
                nameEntered = true;
                validateForm();
            });

            $('#email').on('input', function() {
                emailEntered = true;
                validateForm();
            });

            $('#phone').on('input', function() {
                phoneEntered = true;
                validateForm();
            });

            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                if ($('#searchButton').prop('disabled') === false) {
                    this.submit();
                }
            });

            validateForm();
        });
    </script>
@endsection
