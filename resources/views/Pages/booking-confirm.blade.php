@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <h2 class="mb-4">Xác nhận thông tin đặt vé</h2>

        <div class="row mb-4">
            <!-- Thông tin chuyến xe -->
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Thông tin chuyến xe</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Điểm đi:</strong> {{ $departure }}</p>
                        <p><strong>Điểm đến:</strong> {{ $destination }}</p>
                        <p><strong>Thời gian đi:</strong> {{ $departureTime }}</p>
                        <p><strong>Thời gian đến:</strong> {{ $destinationTime }}</p>
                        <p><strong>Tổng tiền:</strong> <span class="text-danger font-weight-bold">{{ $totalPrice }}</span>
                        </p>
                        <p><strong>Ngày đi:</strong> {{ $departureDate }}</p>
                        <p><strong>Danh sách ghế đã chọn:</strong> {{ $selectedSeats }}</p>
                    </div>
                </div>
            </div>

            <!-- Thông tin liên hệ -->
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Thông tin liên hệ</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Tên:</strong> {{ $name }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $phone }}</p>
                        <p><strong>Email:</strong> {{ $email }}</p>
                        <p><strong>Địa điểm đón:</strong> {{ $startDestination }}</p>
                        <p><strong>Địa điểm trả:</strong> {{ $endDestination }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form xác nhận -->
        <form action="{{ route('booking.payment') }}" method="POST" id="confirmationForm">
            @csrf
            <input type="hidden" name="trip-detail-id" value="{{ $tripDetail['tripDetailId'] }}">
            <input type="hidden" name="departure" value="{{ $departure }}">
            <input type="hidden" name="destination" value="{{ $destination }}">
            <input type="hidden" name="departureTime" value="{{ $departureTime }}">
            <input type="hidden" name="destinationTime" value="{{ $destinationTime }}">
            <input type="hidden" name="totalPrice" value="{{ $totalPrice }}">
            <input type="hidden" name="departureDate" value="{{ $departureDate }}">
            <input type="hidden" name="selectedSeats" value="{{ $selectedSeats }}">
            <input type="hidden" name="selectedSeatIds" value="{{ $selectedSeatIds }}">
            <input type="hidden" name="name" value="{{ $name }}">
            <input type="hidden" name="phone" value="{{ $phone }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="pickup_location" value="{{ $startDestination }}">
            <input type="hidden" name="dropoff_location" value="{{ $endDestination }}">

            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" id="terms" required>
                <label class="form-check-label" for="terms">Tôi chấp nhận điều khoản</label>
            </div>

            <button type="submit" class="btn btn-primary w-100" id="paymentButton" disabled>Thanh toán</button>
        </form>

        <!-- Countdown timer -->
        <div id="countdown" class="mt-4">
            <p class="h5">Thời gian còn lại: <span id="timer" class="font-weight-bold">20:00</span></p>
        </div>
    </div>

    <style>
        .card-header {
            font-size: 1.25rem;
        }

        .form-check-label {
            font-size: 1rem;
        }

        #timer {
            color: #dc3545;
            /* Đỏ nhạt cho đồng hồ đếm ngược */
        }
    </style>

    <script>
        document.getElementById('terms').addEventListener('change', function() {
            document.getElementById('paymentButton').disabled = !this.checked;
        });

        // Countdown timer
        let timeLeft = 20 * 60; // 20 minutes in seconds
        const countdownTimer = setInterval(function() {
            if (timeLeft <= 0) {
                clearInterval(countdownTimer);
                alert('Hết thời gian! Bạn sẽ được chuyển về trang ban đầu.');
                window.location.href =
                    "{{ route('booking.process', ['tripDetailId' => $tripDetail['tripDetailId'], 'departureDate' => $departureDate]) }}";
            } else {
                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                document.getElementById('timer').textContent =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
        }, 1000);
    </script>
@endsection