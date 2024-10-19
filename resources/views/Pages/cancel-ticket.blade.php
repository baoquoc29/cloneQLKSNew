@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Xác Nhận Hủy Vé</h4>
                    </div>
                    <div class="card-body">
                        <div class="ticket-info mb-4">
                            <h5 class="text-primary mb-3">Thông tin vé</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Mã vé:</strong> {{ $bookedTicket['bookingId'] }}</p>
                                    <p><strong>Chuyến đi:</strong> {{ $bookedTicket['departure'] }} -
                                        {{ $bookedTicket['destination'] }}</p>
                                    <p><strong>Nơi đón:</strong> {{ $bookedTicket['startDestination'] }}</p>
                                    <p><strong>Nơi trả:</strong> {{ $bookedTicket['endDestination'] }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Loại xe:</strong> {{ $bookedTicket['carType'] }}</p>
                                    <p><strong>Biển số xe:</strong> {{ $bookedTicket['licensePlate'] }}</p>
                                    <p><strong>Thời gian khởi hành:</strong> {{ $bookedTicket['departureDate'] }} -
                                        {{ $bookedTicket['departureTime'] }}</p>
                                    <p><strong>Tổng tiền:</strong>
                                        {{ number_format($bookedTicket['totalPrice'], 0, ',', '.') }} VNĐ</p>
                                </div>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST"
                            action="{{ route('cancel-ticket', ['bookingId' => $bookedTicket['bookingId']]) }}">
                            @csrf
                            <div class="form-group">
                                <label for="confirmationCode">Nhập mã xác nhận đã gửi vào email của bạn:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="confirmationCode" name="confirmationCode"
                                        placeholder="Nhập mã xác nhận" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit" id="resendCode">Gửi lại
                                            mã</button>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Mã xác nhận sẽ hết hạn sau: <span id="countdown"
                                            class="font-weight-bold text-danger">05:00</span></small>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger btn-block">Xác Nhận Hủy Vé</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .card-header {
            border-bottom: none;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
                    clearInterval(countdownInterval);
                    display.textContent = "Hết hạn";
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

        document.getElementById('resendCode').addEventListener('click', function() {
            alert('Mã xác nhận đã được gửi lại. Vui lòng kiểm tra email của bạn.');
            resetCountdown();
        });
    </script>
@endsection
