@extends('layouts.app')

<!-- Link tới Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Link tới jQuery và Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
    .cancel-ticket {
        margin-top: 20px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #f8f9fa;
    }

    .form-control.short {
        max-width: 200px;
        /* Điều chỉnh chiều dài ngắn hơn để phù hợp với yêu cầu */
    }

    .countdown-container {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .countdown {
        font-size: 1.1em;
        color: #ff0000;
        margin-left: 10px;
    }

    .countdown-text {
        font-size: 0.9em;
    }
</style>

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 cancel-ticket">
                <h4 class="mb-4 text-primary">Xác Nhận Hủy Vé</h4>
                <div class="ticket-info">
                    <p><strong>Mã vé:</strong> <span id="ticketCode" name="ticketCode">{{ $bookedTicket['bookingId'] }}</span>
                    </p>
                    <p><strong>Chuyến đi:</strong> <span id="trip" name="trip">{{ $bookedTicket['destination'] }} -
                            {{ $bookedTicket['departure'] }}</span></p>
                    <p><strong>Nơi đón:</strong> <span id="pickupPlace"
                            name="pickupPlace">{{ $bookedTicket['startDestination'] }}</span></p>
                    <p><strong>Nơi trả:</strong> <span id="dropoffPlace"
                            name="dropoffPlace">{{ $bookedTicket['endDestination'] }}</span></p>
                    <p><strong>Loại xe:</strong> <span id="carType" name="carType">{{ $bookedTicket['carType'] }}</span>
                    </p>
                    <p><strong>Biển số xe:</strong> <span id="carPlate"
                            name="carPlate">{{ $bookedTicket['licensePlate'] }}</span></p>
                    <p><strong>Thời gian khởi hành:</strong> <span id="departureTime"
                            name="departureTime">{{ $bookedTicket['departureDate'] }} -
                            {{ $bookedTicket['departureTime'] }}</span></p>

                    <p><strong>Tổng tiền:</strong> <span id="totalPrice" name="totalPrice">{{ $bookedTicket['totalPrice'] }}
                            VNĐ</span></p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('cancel-ticket', ['bookingId' => $bookedTicket['bookingId']]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="confirmationCode">Nhập mã xác nhận đã gửi vào email của bạn:</label>
                        <div class="input-group">
                            <input type="text" class="form-control short" id="confirmationCode" name="confirmationCode"
                                placeholder="Nhập mã xác nhận" required>
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" id="resendCode">Gửi lại mã</button>
                            </div>
                        </div>
                        <div class="countdown-container">
                            <p class="countdown-text">Mã xác nhận sẽ hết hạn sau:</p>
                            <p class="countdown" id="countdown">05:00</p>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger">Xác Nhận Hủy Vé</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Đồng hồ đếm ngược 5 phút -->
    <script>
        let countdownInterval;

        function startCountdown(duration, display) {
            let timer = duration,
                minutes, seconds;
            countdownInterval = setInterval(function() {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    timer = duration;
                }
            }, 1000);
        }

        function resetCountdown() {
            clearInterval(countdownInterval);
            let fiveMinutes = 60 * 5,
                display = document.querySelector('#countdown');
            startCountdown(fiveMinutes, display);
        }

        window.onload = function() {
            let fiveMinutes = 60 * 5,
                display = document.querySelector('#countdown');
            startCountdown(fiveMinutes, display);
        };

        // Xử lý nút gửi lại mã
        document.getElementById('resendCode').addEventListener('click', function() {
            alert('Mã xác nhận đã được gửi lại. Vui lòng kiểm tra email của bạn.');
            resetCountdown(); // Reset đồng hồ đếm ngược khi gửi lại mã
        });
    </script>
@endsection
