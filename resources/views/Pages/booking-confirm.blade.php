@extends('layouts.app')

@section('content')
    <div class="container my-5 position-relative">
        <!-- Countdown timer -->
        <div id="countdown" class="position-absolute" style="top: 0; right: 15px;">
            <p class="mb-0"><strong>Thời gian còn lại:</strong></p>
            <p id="timer" class="text-danger font-weight-bold mb-0">20:00</p>
        </div>

        <h2 class="text-center mb-5 text-primary">Xác nhận thông tin đặt vé</h2>

        <div class="row mb-5">
            <!-- Thông tin chuyến xe -->
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-bus mr-2"></i>Thông tin chuyến xe</h5>
                    </div>
                    <div class="card-body">
                        <p><i class="fas fa-map-marker-alt text-primary mr-2"></i><strong>Điểm đi:</strong>
                            {{ $departure }}</p>
                        <p><i class="fas fa-map-pin text-primary mr-2"></i><strong>Điểm đến:</strong> {{ $destination }}
                        </p>
                        <p><i class="far fa-clock text-primary mr-2"></i><strong>Thời gian đi:</strong> {{ $departureTime }}
                        </p>
                        <p><i class="far fa-clock text-primary mr-2"></i><strong>Thời gian đến:</strong>
                            {{ $destinationTime }}</p>
                        <p><i class="fas fa-calendar-alt text-primary mr-2"></i><strong>Ngày đi:</strong>
                            {{ $departureDate }}</p>
                        <p><i class="fas fa-chair text-primary mr-2"></i><strong>Ghế đã chọn:</strong> {{ $selectedSeats }}
                        </p>
                        <p class="h4 mt-3">
                            <i class="fas fa-money-bill-wave text-success mr-2"></i>
                            <strong>Tổng tiền:</strong>
                            <span class="text-success">
                                {{ number_format($totalPrice, 0, ',', '.') . ' VNĐ' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Thông tin liên hệ -->
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-user mr-2"></i>Thông tin liên hệ</h5>
                    </div>
                    <div class="card-body">
                        <p><i class="fas fa-user-circle text-success mr-2"></i><strong>Tên:</strong> {{ $name }}</p>
                        <p><i class="fas fa-phone text-success mr-2"></i><strong>Số điện thoại:</strong> {{ $phone }}
                        </p>
                        <p><i class="fas fa-envelope text-success mr-2"></i><strong>Email:</strong> {{ $email }}</p>
                        <p><i class="fas fa-map-marker-alt text-success mr-2"></i><strong>Địa điểm đón:</strong>
                            {{ $startDestination }}</p>
                        <p><i class="fas fa-map-pin text-success mr-2"></i><strong>Địa điểm trả:</strong>
                            {{ $endDestination }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form xác nhận -->
        <form action="{{ route('booking.payment') }}" method="POST" id="confirmationForm" class="mb-5">
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
            <input type="hidden" id="hidden-promotionCode" name="promotionCode" value="{{ $promotionCode }}">

            <button type="submit" class="btn btn-primary btn-lg btn-block" id="paymentButton">
                <i class="fas fa-check-circle mr-2"></i>Xác nhận thanh toán
            </button>
        </form>
    </div>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
        }

        .card {
            transition: transform 0.3s;
            border-radius: 15px;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            font-size: 1.25rem;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 12px 24px;
            font-size: 1.2rem;
            border-radius: 30px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #timer {
            font-size: 1.5rem;
        }

        #countdown {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 10px 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .text-primary {
            color: #007bff !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .bg-primary {
            background-color: #007bff !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .shadow-lg {
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
        }
    </style>

    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <script>
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
