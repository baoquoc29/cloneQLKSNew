@extends('layouts.app')

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
{{-- <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-database.js"></script> --}}
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

@section('content')
    <div class="container">
        <!-- Phần trên: Thông tin chuyến xe -->
        <div class="trip-details">
            <h2>Thông tin chuyến xe</h2>
            <p><strong>Điểm đi:</strong> {{ $departure }}</p>
            <p><strong>Điểm đến:</strong> {{ $destination }}</p>
            <p><strong>Thời gian đi:</strong> {{ $departureTime }}</p>
            <p><strong>Thời gian đến:</strong> {{ $destinationTime }}</p>
            <p><strong>Giá:</strong> {{ number_format($price, 0, ',', '.') . ' VNĐ' }}</p>
            <p><strong>Ngày đi:</strong> {{ $departureDate }}</p>
        </div>

        <!-- Phần dưới: Sơ đồ chỗ ngồi và form điền thông tin -->
        <div class="row">
            <!-- Phần bên trái: Sơ đồ chỗ ngồi -->
            <div class="col-md-8">
                <div class="seat-map">
                    <h3>Sơ đồ chỗ ngồi</h3>
                    <div class="seat-map">
                        @foreach ($seatMaps as $rowIndex => $row)
                            <div class="seat-row">
                                @foreach ($row as $colIndex => $seat)
                                    @php
                                        if ($seat !== null) {
                                            $seatId = $seat['seat']['seatId'];
                                            $seatCode = $seat['seat']['seatNumber'];
                                            $seatStatus = $seat['status'];
                                            $fillColor =
                                                $seatStatus === 0 ? 'lightblue' : ($seatStatus === 1 ? 'red' : 'gray');
                                            $cursor = $seatStatus === 0 ? 'pointer' : 'not-allowed';
                                            $borderColor = $seatStatus === 0 ? 'black' : 'black';
                                        } else {
                                            $seatId = -1;
                                            $seatCode = '';
                                            $seatStatus = -1;
                                            $fillColor = 'white';
                                            $borderColor = 'white';
                                            $cursor = 'not-allowed';
                                        }
                                    @endphp
                                    <svg class="seat" data-seat-code="{{ $seatCode }}"
                                        data-seat-id="{{ $seatId }}" data-seat-status="{{ $seatStatus }}"
                                        x="{{ $colIndex * 60 }}" y="{{ $rowIndex * 60 }}" width="50" height="50"
                                        style="cursor: {{ $cursor }};"
                                        @if ($seatStatus === 0) onclick="toggleSeat(this)" @endif>
                                        <rect width="50" height="50" fill="{{ $fillColor }}"
                                            stroke="{{ $borderColor }}" />
                                        <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"
                                            fill="black">{{ $seatCode }}</text>
                                    </svg>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <div class="seat-legend">
                        <h4>Ghi chú</h4>
                        <p><span class="seat-legend-box" style="background-color: lightblue;"></span> Ghế trống</p>
                        <p><span class="seat-legend-box" style="background-color: red;"></span> Ghế đã đặt</p>
                        <p><span class="seat-legend-box" style="background-color: gray;"></span> Ghế đang giữ chỗ</p>
                    </div>

                    <div class="seat-summary">
                        <h4>Danh sách ghế đã chọn</h4>
                        <ul id="selected-seats-list"></ul>
                        <p><strong>Tổng tiền:</strong> <span id="total-price">0 VNĐ</span></p>
                        <p><strong>Giảm giá</strong> <span id="discount-amount" class="text-danger">0 VNĐ</span>
                        </p>
                        <p><strong>Tổng tiền phải trả:</strong> <span id="total-after-discount">0 VNĐ</span></p>
                    </div>
                </div>
            </div>

            <!-- Phần bên phải: Form điền thông tin -->
            <div class="col-md-4">
                <div class="booking-form">
                    <h3>Thông tin liên hệ</h3>
                    <form action="{{ route('booking.confirm', ['tripDetailId' => $tripDetail['tripDetailId']]) }}"
                        method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" id="hidden-departure" name="departure" value="{{ $departure }}">
                        <input type="hidden" id="hidden-destination" name="destination" value="{{ $destination }}">
                        <input type="hidden" id="hidden-departureTime" name="departureTime" value="{{ $departureTime }}">
                        <input type="hidden" id="hidden-destinationTime" name="destinationTime"
                            value="{{ $destinationTime }}">
                        <input type="hidden" id="hidden-price" name="price" value="{{ $price }}">
                        <input type="hidden" id="hidden-departureDate" name="departureDate" value="{{ $departureDate }}">
                        <input type="hidden" id="hidden-selectedSeats" name="selectedSeats" value="">
                        <input type="hidden" id="hidden-selectedSeatIds" name="selectedSeatIds" value="">
                        <input type="hidden" id="hidden-name" name="name" value="">
                        <input type="hidden" id="hidden-phone" name="phone" value="">
                        <input type="hidden" id="hidden-email" name="email" value="">
                        <input type="hidden" id="hidden-pickup_location" name="pickup_location" value="">
                        <input type="hidden" id="hidden-dropoff_location" name="dropoff_location" value="">

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Tên:</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                            <span class="error text-danger"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Số điện thoại:</label>
                            <input type="tel" id="phone" name="phone" class="form-control" required>
                            <span class="error text-danger" id="phone-error"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                            <span class="error text-danger" id="email-error"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="pickup-location" class="form-label">Địa điểm đón:</label>
                            <input type="text" id="pickup-location" name="pickup_location" class="form-control"
                                required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="dropoff-location" class="form-label">Địa điểm trả:</label>
                            <input type="text" id="dropoff-location" name="dropoff_location" class="form-control"
                                required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="promotion_code" class="form-label">Mã khuyến mại:</label>
                            <div class="input-group">
                                <input type="text" id="promotion_code" name="promotion_code" class="form-control">
                                <button type="button" class="btn btn-secondary" id="apply-promo-btn">Áp dụng</button>
                            </div>
                            <span class="error text-danger" id="promo-error"></span>
                            <span class="discount-info text-success" id="promo-success"></span>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="submit-button" disabled>Thanh
                            toán</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* CSS styles */
        .trip-details {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .seat-map {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
        }

        .seat {
            cursor: pointer;
        }

        .seat-legend {
            margin-top: 20px;
        }

        .seat-legend-box {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-right: 10px;
            border: 1px solid #ddd;
        }

        .seat-summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .booking-form {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sockjs-client/1.5.1/sockjs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/stomp.js/2.3.3/stomp.min.js"></script>

    <script>
        $(document).ready(function() {
            let promoApplied = false;
            let discountAmount = 0;

            $('#apply-promo-btn').click(function() {
                if (promoApplied) {
                    // Nếu mã khuyến mại đã được áp dụng, người dùng đang hủy
                    resetPromo();
                } else {
                    applyPromoOrDiscount(); // Gọi phương thức mới
                }
            });

            function applyPromoOrDiscount() {
                const promoCode = $('#promotion_code').val();
                const discountAmountElement = $('#discount-amount');
                let discountValue = 0;

                if (promoCode === '') {
                    $('#promo-error').text('Vui lòng nhập mã khuyến mại.');
                    return;
                }

                // Kiểm tra mã khuyến mại
                if (promoCode === 'PROMO123') {
                    discountValue = 50000; // Giảm giá 50,000 VNĐ
                } else if (promoCode === "MAUDEAL") {
                    discountValue = 10000; // Giảm giá 10,000 VNĐ
                } else {
                    $('#promo-error').text('Mã khuyến mại không hợp lệ hoặc đã hết hạn.');
                    return;
                }

                discountAmount = discountValue; // Gán giá trị giảm giá
                $('#promo-error').text('');
                $('#promo-success').text(`Mã khuyến mại hợp lệ. Giảm giá: ${discountAmount.toLocaleString()} VNĐ`);
                $('#apply-promo-btn').text('Hủy mã khuyến mại');
                promoApplied = true;

                // Cập nhật số tiền giảm giá và tổng tiền sau khi giảm giá
                updateDiscountAndTotalPrice();
            }

            function resetPromo() {
                $('#promo-success').text('');
                $('#promo-error').text('');
                $('#promotion_code').val('');
                discountAmount = 0;
                $('#apply-promo-btn').text('Áp dụng');
                promoApplied = false;
                updateDiscountAndTotalPrice(); // Cập nhật lại tổng tiền sau khi hủy mã
            }

            function updateDiscountAndTotalPrice() {
                const totalSeatsPrice = selectedSeatsCount * {{ $price }};
                const finalPrice = totalSeatsPrice - discountAmount;

                $('#discount-amount').text(`-${discountAmount.toLocaleString()} VNĐ`);
                $('#total-price').text(`${finalPrice.toLocaleString()} VNĐ`);
                $('#total-after-discount').text(`${finalPrice.toLocaleString()} VNĐ`);
            }

            // Form validation and seat selection logic
            $('input').on('input', validateForm);

            function validateForm() {
                const name = $('#name').val();
                const phone = $('#phone').val();
                const email = $('#email').val();
                const pickupLocation = $('#pickup-location').val();
                const dropoffLocation = $('#dropoff-location').val();
                const isFormValid = name && phone && email && pickupLocation && dropoffLocation &&
                    selectedSeatsCount > 0;

                if (isFormValid && validatePhone() && validateEmail()) {
                    $('#submit-button').prop('disabled', false);
                } else {
                    $('#submit-button').prop('disabled', true);
                }
            }

            function validatePhone() {
                const phone = $('#phone').val();
                const phonePattern = /^(0|\+84)[1-9][0-9]{8}$/;
                if (!phonePattern.test(phone)) {
                    $('#phone-error').text('Số điện thoại không hợp lệ!');
                    return false;
                } else {
                    $('#phone-error').text('');
                    return true;
                }
            }

            function validateEmail() {
                const email = $('#email').val();
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    $('#email-error').text('Email không hợp lệ!');
                    return false;
                } else {
                    $('#email-error').text('');
                    return true;
                }
            }

            // Call validateForm whenever seats are selected/deselected
            function toggleSeat(element) {
                const seatId = element.getAttribute('data-seat-id');
                const seatCode = element.getAttribute('data-seat-code');
                let seatStatus = parseInt(element.getAttribute('data-seat-status'));

                if (seatStatus === 0) {
                    if (selectedSeatsCount >= maxSeats) {
                        alert(`Bạn chỉ có thể chọn tối đa ${maxSeats} ghế.`);
                        return;
                    }
                    seatStatus = 2;
                    selectedSeatsCount++;
                    holdSeat(seatId);
                } else if (seatStatus === 2) {
                    seatStatus = 0;
                    selectedSeatsCount--;
                    cancelSeat(seatId);
                }

                updateSeatColor(element, seatStatus);
                updateSeatSummary(seatId, seatCode, seatStatus === 2 ? 'add' : 'remove');
                validateForm(); // Update form validation when seat selection changes
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Kiểm tra số điện thoại
            $('#phone').on('input', function() {
                const phone = $(this).val();
                const phonePattern = /^(0|\+84)[1-9][0-9]{8}$/; // Kiểu mẫu cho số điện thoại Việt Nam
                const errorMessage = $('#phone-error');

                if (!phonePattern.test(phone)) {
                    errorMessage.text('Số điện thoại không hợp lệ!');
                } else {
                    errorMessage.text(''); // Xóa thông báo lỗi
                }
            });

            // Kiểm tra email
            $('#email').on('input', function() {
                const email = $(this).val();
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Kiểu mẫu cho email
                const errorMessage = $('#email-error');

                if (!emailPattern.test(email)) {
                    errorMessage.text('Email không hợp lệ!');
                } else {
                    errorMessage.text(''); // Xóa thông báo lỗi
                }
            });
        });


        const maxSeats = 4; // Số ghế tối đa có thể chọn
        let selectedSeatsCount = 0;
        let stompClient = null;
        let stompClient1 = null;
        const sessionId = sessionStorage.getItem('sessionId') || generateSessionId();
        sessionStorage.setItem('sessionId', sessionId);

        function generateSessionId() {
            return 'session_' + Math.random().toString(36).substr(2, 9);
        }

        // Lấy các giá trị từ PHP (Blade Template)
        const tripDetailId = '{{ $tripDetail['tripDetailId'] }}';
        const departureDate = '{{ $departureDate }}';

        $(document).ready(function() {
            connectWebSocket(); // Kết nối WebSocket
        });

        function connectWebSocket() {
            const socket = new SockJS('http://localhost:8080/hold'); // WebSocket endpoint
            stompClient = Stomp.over(socket);

            stompClient.connect({}, function() {
                console.log('WebSocket is connected');

                // Đăng ký nhận cập nhật ghế từ server qua WebSocket
                stompClient.subscribe('/topic/hold', function(message) {
                    const seatHoldDTO = JSON.parse(message.body);
                    console.log("Seat: " + seatHoldDTO.holdStart);
                    updateSeatStatus(seatHoldDTO); // Cập nhật trạng thái ghế
                });

                // stompClient1.subscribe('/topic/cancel', function(message) {
                //     const seatHoldDTO = JSON.parse(message.body);
                //     updateSeatStatus(seatHoldDTO); // Cập nhật trạng thái ghế
                // });
            });

            const socket1 = new SockJS('http://localhost:8080/cancel'); // WebSocket endpoint
            stompClient1 = Stomp.over(socket1);

            stompClient1.connect({}, function() {
                console.log('WebSocket is connected');

                stompClient1.subscribe('/topic/cancel', function(message) {
                    const seatHoldDTO = JSON.parse(message.body);
                    updateSeatStatus(seatHoldDTO); // Cập nhật trạng thái ghế
                });
            });
        }

        // Cập nhật trạng thái ghế
        function updateSeatStatus(seatHoldDTO) {
            const seatElement = document.querySelector(`[data-seat-id="${seatHoldDTO.seatId}"]`);
            if (seatElement) {
                const newStatus = seatHoldDTO.status;
                const newColor = newStatus === 0 ? 'lightblue' : 'gray'; // Trạng thái 0: trống, 2: giữ chỗ
                seatElement.querySelector('rect').setAttribute('fill', newColor);
                seatElement.setAttribute('data-seat-status', newStatus);
            }
        }

        // Xử lý khi click vào ghế
        function toggleSeat(element) {
            const seatId = element.getAttribute('data-seat-id');
            const seatCode = element.getAttribute('data-seat-code');
            let seatStatus = parseInt(element.getAttribute('data-seat-status'));

            console.log(`Hiện tại ghế ${seatId} có trạng thái ${seatStatus}`);

            if (seatStatus === 0) { // Nếu ghế đang trống
                if (selectedSeatsCount >= maxSeats) {
                    alert(`Bạn chỉ có thể chọn tối đa ${maxSeats} ghế.`);
                    return;
                }
                seatStatus = 2; // Đổi trạng thái thành giữ chỗ
                element.classList.add('selected');
                selectedSeatsCount++;

                // Gửi yêu cầu giữ ghế qua WebSocket
                holdSeat(seatId);


            } else if (seatStatus === 2) { // Nếu ghế đang được giữ
                seatStatus = 0; // Đổi trạng thái thành trống
                element.classList.remove('selected');
                selectedSeatsCount--;
                console.log("Hủy giữ chỗ");

                // Gửi yêu cầu hủy ghế qua WebSocket
                cancelSeat(seatId);
            }


            // Cập nhật màu sắc ghế và trạng thái
            updateSeatColor(element, seatStatus);
            updateSeatSummary(seatId, seatCode, seatStatus === 2 ? 'add' : 'remove');
        }

        // Cập nhật màu sắc ghế
        function updateSeatColor(element, seatStatus) {
            const newColor = seatStatus === 0 ? 'lightblue' : 'gray';
            console.log(`Cập nhật màu sắc ghế: ${newColor} cho ghế ${element.getAttribute('data-seat-id')}`);
            element.querySelector('rect').setAttribute('fill', newColor);
            element.setAttribute('data-seat-status', seatStatus);
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


        // Giải phóng ghế (hủy giữ chỗ)
        function releaseSeat(seatId) {
            const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
            if (seatElement) {
                const seatStatus = 0; // Đặt trạng thái ghế về trống
                seatElement.classList.remove('selected');
                selectedSeatsCount--;

                // Cập nhật trạng thái ghế
                const newColor = 'lightblue'; // Màu cho ghế trống
                seatElement.querySelector('rect').setAttribute('fill', newColor);
                seatElement.setAttribute('data-seat-status', seatStatus);

                // Cập nhật thông tin ghế đã chọn
                updateSeatSummary(seatId, seatElement.getAttribute('data-seat-code'), 'remove');

                // Xóa bộ đếm thời gian khi ghế bị giải phóng
                if (heldSeats[seatId]) {
                    clearTimeout(heldSeats[seatId].timer); // Xóa bộ đếm thời gian
                    delete heldSeats[seatId]; // Xóa thông tin ghế đã giữ
                }
            }
        }

        // Cập nhật thông tin ghế đã chọn
        function updateSeatSummary(seatId, seatCode, action) {
            const seatList = document.getElementById('selected-seats-list');
            const seatSummary = document.getElementById('total-price');
            const discountAmount = document.getElementById('discount-amount');
            const totalAfterDiscount = document.getElementById('total-after-discount');
            const hiddenSelectedSeats = document.getElementById('hidden-selectedSeats');
            const hiddenSelectedSeatIds = document.getElementById('hidden-selectedSeatIds');

            let totalPrice = parseInt(seatSummary.textContent.replace(' VNĐ', '')) || 0;
            const price = {{ $price }};

            if (action === 'add') {
                const listItem = document.createElement('li');
                listItem.textContent = seatCode;
                seatList.appendChild(listItem);

                const currentSeats = hiddenSelectedSeats.value ? hiddenSelectedSeats.value.split(',') : [];
                currentSeats.push(seatCode);
                hiddenSelectedSeats.value = currentSeats.join(',');

                const currentSeatIds = hiddenSelectedSeatIds.value ? hiddenSelectedSeatIds.value.split(',') : [];
                currentSeatIds.push(seatId);
                hiddenSelectedSeatIds.value = currentSeatIds.join(',');

                // Cập nhật tổng tiền
                totalPrice += price;
                seatSummary.textContent = totalPrice + ' VNĐ';

            } else if (action === 'remove') {
                const listItems = seatList.querySelectorAll('li');
                listItems.forEach(item => {
                    if (item.textContent === seatCode) {
                        seatList.removeChild(item);
                    }
                });

                const currentSeats = hiddenSelectedSeats.value ? hiddenSelectedSeats.value.split(',') : [];
                hiddenSelectedSeats.value = currentSeats.filter(seat => seat !== seatCode).join(',');

                const currentSeatIds = hiddenSelectedSeatIds.value ? hiddenSelectedSeatIds.value.split(',') : [];
                hiddenSelectedSeatIds.value = currentSeatIds.filter(id => id !== seatId).join(',');

                // Cập nhật tổng tiền
                totalPrice -= price;
                seatSummary.textContent = totalPrice + ' VNĐ';
            }

            // Cập nhật số tiền giảm giá và tổng tiền sau khi giảm giá
            const discountValue = parseInt(discountAmount.textContent.replace(' VNĐ', '')) || 0;
            const totalAfterDiscountValue = totalPrice - discountValue;
            totalAfterDiscount.textContent = totalAfterDiscountValue + ' VNĐ';

            // Kích hoạt nút submit nếu có ghế được chọn
            document.getElementById('submit-button').disabled = selectedSeatsCount === 0;
        }

        // Thêm hàm xử lý mã giảm giá
    //     function applyDiscount() {
    //         const promoCodeInput = document.getElementById('promotion_code').value;
    //         const discountAmountElement = document.getElementById('discount-amount');
    //         let discountValue = 0;

    //         // Giả sử bạn kiểm tra mã giảm giá ở đây
    //         if (promoCodeInput === "MAUDEAL") {
    //             discountValue = 10000; // Ví dụ: giảm giá 10,000 VNĐ
    //         }

    //         discountAmountElement.textContent = `-${discountValue} VNĐ`;
    //         const totalPrice = parseInt(document.getElementById('total-price').textContent.replace(' VNĐ', '')) || 0;
    //         const totalAfterDiscount = totalPrice - discountValue;
    //         document.getElementById('total-after-discount').textContent = totalAfterDiscount + ' VNĐ';
    //     }
    // </script>
{{-- 
    <script>
        document.getElementById('apply-promo-btn').addEventListener('click', applyDiscount);
    </script> --}}
@endsection
