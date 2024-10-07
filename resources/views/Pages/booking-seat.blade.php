@extends('layouts.app')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

@section('content')
    <div class="container py-5">
        <div class="row">
            <!-- Phần trên: Thông tin chuyến xe -->
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title text-primary mb-4">Thông tin chuyến xe</h2>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <p><strong><i class="fas fa-map-marker-alt text-primary mr-2"></i>Điểm đi:</strong>
                                    {{ $departure }}</p>
                                <p><strong><i class="fas fa-map-pin text-primary mr-2"></i>Điểm đến:</strong>
                                    {{ $destination }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <p><strong><i class="far fa-clock text-primary mr-2"></i>Thời gian đi:</strong>
                                    {{ $departureTime }}</p>
                                <p><strong><i class="far fa-clock text-primary mr-2"></i>Thời gian đến:</strong>
                                    {{ $destinationTime }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <p><strong><i class="fas fa-tag text-primary mr-2"></i>Giá:</strong>
                                    {{ number_format($price, 0, ',', '.') . ' VNĐ' }}</p>
                                <p><strong><i class="far fa-calendar-alt text-primary mr-2"></i>Ngày đi:</strong>
                                    {{ $departureDate }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phần dưới: Sơ đồ chỗ ngồi và form điền thông tin -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="card-title text-primary mb-4">Sơ đồ chỗ ngồi</h3>
                        <div class="seat-map">
                            @foreach ($seatMaps as $rowIndex => $row)
                                <div class="seat-row">
                                    @foreach ($row as $colIndex => $seat)
                                        @if ($seat !== null)
                                            @php
                                                $seatId = $seat['seat']['seatId'];
                                                $seatCode = $seat['seat']['seatNumber'];
                                                $seatStatus = $seat['status'];
                                                $seatClass =
                                                    $seatStatus === 0
                                                        ? 'available'
                                                        : ($seatStatus === 1
                                                            ? 'booked'
                                                            : 'held');
                                            @endphp
                                            <div class="seat {{ $seatClass }}" data-seat-code="{{ $seatCode }}"
                                                data-seat-id="{{ $seatId }}" data-seat-status="{{ $seatStatus }}"
                                                @if ($seatStatus === 0) onclick="toggleSeat(this)" @endif>
                                                {{ $seatCode }}
                                            </div>
                                        @else
                                            <div class="seat-spacer"></div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="seat-legend mt-4">
                            <h4 class="text-primary">Ghi chú</h4>
                            <div class="d-flex justify-content-between">
                                <div><span class="seat-legend-box available"></span> Ghế trống</div>
                                <div><span class="seat-legend-box booked"></span> Ghế đã đặt</div>
                                <div><span class="seat-legend-box held"></span> Ghế đang giữ chỗ</div>
                            </div>
                        </div>

                        <div class="seat-summary mt-4">
                            <h4 class="text-primary">Danh sách ghế đã chọn</h4>
                            <ul id="selected-seats-list" class="list-unstyled"></ul>
                            <p><strong>Tổng tiền:</strong> <span id="total-price" class="text-primary">0 VNĐ</span></p>
                            <p><strong>Giảm giá:</strong> <span id="discount-amount" class="text-danger">0 VNĐ</span></p>
                            <p><strong>Tổng tiền phải trả:</strong> <span id="total-after-discount" class="text-success">0
                                    VNĐ</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phần bên phải: Form điền thông tin -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-primary mb-4">Thông tin liên hệ</h3>
                        <form action="{{ route('booking.confirm', ['tripDetailId' => $tripDetail['tripDetailId']]) }}"
                            method="POST" id="bookingForm">
                            @csrf
                            <!-- Hidden inputs -->
                            <input type="hidden" id="hidden-departure" name="departure" value="{{ $departure }}">
                            <input type="hidden" id="hidden-destination" name="destination" value="{{ $destination }}">
                            <input type="hidden" id="hidden-departureTime" name="departureTime"
                                value="{{ $departureTime }}">
                            <input type="hidden" id="hidden-destinationTime" name="destinationTime"
                                value="{{ $destinationTime }}">
                            <input type="hidden" id="hidden-price" name="price" value="{{ $price }}">
                            <input type="hidden" id="hidden-departureDate" name="departureDate"
                                value="{{ $departureDate }}">
                            <input type="hidden" id="hidden-selectedSeats" name="selectedSeats" value="">
                            <input type="hidden" id="hidden-selectedSeatIds" name="selectedSeatIds" value="">
                            <input type="hidden" id="hidden-name" name="name" value="">
                            <input type="hidden" id="hidden-phone" name="phone" value="">
                            <input type="hidden" id="hidden-email" name="email" value="">
                            <input type="hidden" id="hidden-pickup_location" name="pickup_location" value="">
                            <input type="hidden" id="hidden-dropoff_location" name="dropoff_location" value="">

                            <div class="form-group">
                                <label for="name" class="form-label"><i
                                        class="fas fa-user text-primary mr-2"></i>Tên:</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                                <span class="error text-danger"></span>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label"><i class="fas fa-phone text-primary mr-2"></i>Số
                                    điện thoại:</label>
                                <input type="tel" id="phone" name="phone" class="form-control" required>
                                <span class="error text-danger" id="phone-error"></span>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label"><i
                                        class="fas fa-envelope text-primary mr-2"></i>Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                                <span class="error text-danger" id="email-error"></span>
                            </div>

                            <div class="form-group">
                                <label for="pickup-location" class="form-label"><i
                                        class="fas fa-map-marker-alt text-primary mr-2"></i>Địa điểm đón:</label>
                                <input type="text" id="pickup-location" name="pickup_location" class="form-control"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="dropoff-location" class="form-label"><i
                                        class="fas fa-map-pin text-primary mr-2"></i>Địa điểm trả:</label>
                                <input type="text" id="dropoff-location" name="dropoff_location" class="form-control"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="promotion_code" class="form-label"><i
                                        class="fas fa-tag text-primary mr-2"></i>Mã khuyến mại:</label>
                                <div class="input-group">
                                    <input type="text" id="promotion_code" name="promotion_code"
                                        class="form-control">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary" id="apply-promo-btn">Áp
                                            dụng</button>
                                    </div>
                                </div>
                                <span class="error text-danger" id="promo-error"></span>
                                <span class="discount-info text-success" id="promo-success"></span>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block" id="submit-button" disabled>
                                <i class="fas fa-check-circle mr-2"></i>Thanh toán
                            </button>
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

        .card-title {
            font-weight: bold;
        }

        .seat-map {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .seat-row {
            display: flex;
            gap: 10px;
        }

        .seat,
        .seat-spacer {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .seat {
            border: 2px solid #007bff;
        }

        .seat.available {
            background-color: #e9ecef;
            color: #007bff;
        }

        .seat.booked {
            background-color: #dc3545;
            color: white;
            cursor: not-allowed;
        }

        .seat.held {
            background-color: #ffc107;
            color: black;
        }

        .seat:hover:not(.booked) {
            transform: scale(1.1);
        }

        .seat-legend-box {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            border-radius: 3px;
        }

        .seat-legend-box.available {
            background-color: #e9ecef;
            border: 2px solid #007bff;
        }

        .seat-legend-box.booked {
            background-color: #dc3545;
        }

        .seat-legend-box.held {
            background-color: #ffc107;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sockjs-client/1.5.1/sockjs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/stomp.js/2.3.3/stomp.min.js"></script>

    <script>
        $(document).ready(function() {
            let selectedSeatsCount = 0;
            const maxSeats = 4; // Số ghế tối đa có thể chọn
            let stompClient = null;
            let stompClient1 = null;
            const sessionId = Math.random().toString(36).substring(2, 15);
            const tripDetailId = {{ $tripDetail['tripDetailId'] }};
            const departureDate = '{{ $departureDate }}';
            const heldSeats = {};

            // Kết nối WebSocket
            connectWebSocket();

            function connectWebSocket() {
                const socket = new SockJS('http://localhost:8080/ws');
                stompClient = Stomp.over(socket);

                stompClient.connect({}, function() {
                    console.log('WebSocket is connected');

                    stompClient.subscribe('/topic/seat-status', function(message) {
                        const seatHoldDTO = JSON.parse(message.body);
                        updateSeatStatus(seatHoldDTO);
                    });
                });

                const socket1 = new SockJS('http://localhost:8080/cancel');
                stompClient1 = Stomp.over(socket1);

                stompClient1.connect({}, function() {
                    console.log('Cancel WebSocket is connected');

                    stompClient1.subscribe('/topic/cancel', function(message) {
                        const seatHoldDTO = JSON.parse(message.body);
                        updateSeatStatus(seatHoldDTO);
                    });
                });
            }

            // Cập nhật trạng thái ghế
            function updateSeatStatus(seatHoldDTO) {
                const seatElement = document.querySelector(`[data-seat-id="${seatHoldDTO.seatId}"]`);
                if (seatElement) {
                    const newStatus = seatHoldDTO.status;
                    seatElement.setAttribute('data-seat-status', newStatus);
                    updateSeatAppearance(seatElement, newStatus);
                }
            }

            // Cập nhật giao diện ghế
            function updateSeatAppearance(seatElement, status) {
                seatElement.classList.remove('available', 'booked', 'held');
                switch (parseInt(status)) {
                    case 0:
                        seatElement.classList.add('available');
                        seatElement.style.backgroundColor = '#e9ecef';
                        seatElement.style.color = '#007bff';
                        seatElement.onclick = function() {
                            toggleSeat(this);
                        };
                        break;
                    case 1:
                        seatElement.classList.add('booked');
                        seatElement.style.backgroundColor = '#dc3545';
                        seatElement.style.color = 'white';
                        seatElement.onclick = null;
                        break;
                    case 2:
                        seatElement.classList.add('held');
                        seatElement.style.backgroundColor = '#ffc107';
                        seatElement.style.color = 'black';
                        break;
                }
            }

            // Xử lý khi click vào ghế
            window.toggleSeat = function(element) {
                const seatId = element.getAttribute('data-seat-id');
                const seatCode = element.getAttribute('data-seat-code');
                let seatStatus = parseInt(element.getAttribute('data-seat-status'));

                console.log(`Toggling seat ${seatId} with current status ${seatStatus}`);

                if (seatStatus === 0) { // Nếu ghế đang trống
                    if (selectedSeatsCount >= maxSeats) {
                        alert(`Bạn chỉ có thể chọn tối đa ${maxSeats} ghế.`);
                        return;
                    }
                    seatStatus = 2; // Đổi trạng thái thành giữ chỗ
                    selectedSeatsCount++;
                    holdSeat(seatId);
                } else if (seatStatus === 2) { // Nếu ghế đang được giữ
                    seatStatus = 0; // Đổi trạng thái thành trống
                    selectedSeatsCount--;
                    cancelSeat(seatId);
                }

                element.setAttribute('data-seat-status', seatStatus);
                updateSeatAppearance(element, seatStatus);
                updateSeatSummary(seatId, seatCode, seatStatus === 2 ? 'add' : 'remove');

                console.log(`Seat ${seatId} updated to status ${seatStatus}`);
                validateForm();
            }

            // Hàm gửi yêu cầu giữ ghế
            function holdSeat(seatId) {
                try {
                    stompClient.send('/app/hold', {}, JSON.stringify({
                        sessionId: sessionId,
                        seatId: parseInt(seatId),
                        tripDetailId: tripDetailId,
                        departureDate: departureDate,
                        status: 2
                    }));
                } catch (error) {
                    console.error('Lỗi khi gửi yêu cầu giữ ghế:', error);
                }
            }

            // Hàm gửi yêu cầu hủy ghế
            function cancelSeat(seatId) {
                try {
                    stompClient1.send('/app/cancel', {}, JSON.stringify({
                        sessionId: sessionId,
                        seatId: parseInt(seatId),
                        tripDetailId: tripDetailId,
                        departureDate: departureDate,
                        status: 0
                    }));
                } catch (error) {
                    console.error('Lỗi khi gửi yêu cầu hủy ghế:', error);
                }
            }

            // Cập nhật thông tin ghế đã chọn
            function updateSeatSummary(seatId, seatCode, action) {
                const seatList = $('#selected-seats-list');
                const totalPriceElement = $('#total-price');
                const discountAmountElement = $('#discount-amount');
                const totalAfterDiscountElement = $('#total-after-discount');
                const hiddenSelectedSeats = $('#hidden-selectedSeats');
                const hiddenSelectedSeatIds = $('#hidden-selectedSeatIds');

                let totalPrice = parseInt(totalPriceElement.text().replace(' VNĐ', '')) || 0;
                const price = {{ $price }};

                if (action === 'add') {
                    seatList.append(`<li data-seat-id="${seatId}">${seatCode}</li>`);
                    totalPrice += price;

                    const currentSeats = hiddenSelectedSeats.val() ? hiddenSelectedSeats.val().split(',') : [];
                    currentSeats.push(seatCode);
                    hiddenSelectedSeats.val(currentSeats.join(','));

                    const currentSeatIds = hiddenSelectedSeatIds.val() ? hiddenSelectedSeatIds.val().split(',') :
                [];
                    currentSeatIds.push(seatId);
                    hiddenSelectedSeatIds.val(currentSeatIds.join(','));
                } else if (action === 'remove') {
                    seatList.find(`li[data-seat-id="${seatId}"]`).remove();
                    totalPrice -= price;

                    const currentSeats = hiddenSelectedSeats.val().split(',').filter(seat => seat !== seatCode);
                    hiddenSelectedSeats.val(currentSeats.join(','));

                    const currentSeatIds = hiddenSelectedSeatIds.val().split(',').filter(id => id !== seatId);
                    hiddenSelectedSeatIds.val(currentSeatIds.join(','));
                }

                totalPriceElement.text(totalPrice + ' VNĐ');

                // Cập nhật số tiền giảm giá và tổng tiền sau khi giảm giá
                const discountValue = parseInt(discountAmountElement.text().replace(' VNĐ', '')) || 0;
                const totalAfterDiscountValue = totalPrice - discountValue;
                totalAfterDiscountElement.text(totalAfterDiscountValue + ' VNĐ');
            }

            // Hàm kiểm tra form
            function validateForm() {
                const name = $('#name').val().trim();
                const phone = $('#phone').val().trim();
                const email = $('#email').val().trim();
                const pickupLocation = $('#pickup-location').val().trim();
                const dropoffLocation = $('#dropoff-location').val().trim();

                let isValid = true;

              

                // Kiểm tra số điện thoại
                const phoneRegex = /^(0|\+84)([3|5|7|8|9])([0-9]{8})$/;
                if (!phoneRegex.test(phone)) {
                    $('#phone-error').text('Số điện thoại không hợp lệ');
                    isValid = false;
                } else {
                    $('#phone-error').text('');
                }

                // Kiểm tra email
                const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                if (!emailRegex.test(email)) {
                    $('#email-error').text('Email không hợp lệ');
                    isValid = false;
                } else {
                    $('#email-error').text('');
                }

                // Kiểm tra địa điểm đón và trả
                if (pickupLocation === '') {
                    $('#pickup-location').next('.error').text('Vui lòng nhập địa điểm đón');
                    isValid = false;
                } else {
                    $('#pickup-location').next('.error').text('');
                }

                if (dropoffLocation === '') {
                    $('#dropoff-location').next('.error').text('Vui lòng nhập địa điểm trả');
                    isValid = false;
                } else {
                    $('#dropoff-location').next('.error').text('');
                }

                // Kiểm tra số ghế đã chọn
                if (selectedSeatsCount === 0) {
                    $('#seat-error').text('Vui lòng chọn ít nhất 1 ghế');
                    isValid = false;
                } else {
                    $('#seat-error').text('');
                }

                // Kích hoạt hoặc vô hiệu hóa nút thanh toán
                $('#submit-button').prop('disabled', !isValid);

                return isValid;
            }

            // Gọi hàm kiểm tra form khi có thay đổi trong các trường input
            $('#name, #phone, #email, #pickup-location, #dropoff-location').on('input', validateForm);

            // Xử lý áp dụng mã giảm giá
            $('#apply-promo-btn').click(function() {
                const promoCode = $('#promotion_code').val();
                // Gửi yêu cầu kiểm tra mã giảm giá đến server
                $.ajax({
                    url: '/check-promo-code',
                    method: 'POST',
                    data: {
                        promoCode: promoCode,
                        totalPrice: parseInt($('#total-price').text().replace(' VNĐ', ''))
                    },
                    success: function(response) {
                        if (response.valid) {
                            $('#discount-amount').text('-' + response.discountAmount + ' VNĐ');
                            $('#total-after-discount').text(response.totalAfterDiscount +
                                ' VNĐ');
                            $('#promo-success').text('Mã giảm giá đã được áp dụng thành công!');
                            $('#promo-error').text('');
                        } else {
                            $('#promo-error').text('Mã giảm giá không hợp lệ hoặc đã hết hạn.');
                            $('#promo-success').text('');
                        }
                    },
                    error: function() {
                        $('#promo-error').text('Có lỗi xảy ra khi kiểm tra mã giảm giá.');
                        $('#promo-success').text('');
                    }
                });
            });

            // Xác thực form trước khi submit
            $('#bookingForm').submit(function(e) {
                e.preventDefault();
                if (validateForm()) {
                    this.submit();
                }
            });

            // Cập nhật hidden inputs khi người dùng nhập thông tin
            $('#name, #phone, #email, #pickup-location, #dropoff-location').on('input', function() {
                const id = $(this).attr('id');
                $(`#hidden-${id}`).val($(this).val());
            });

            // Khởi tạo: vô hiệu hóa nút thanh toán
            $('#submit-button').prop('disabled', true);
        });
    </script>
@endsection
