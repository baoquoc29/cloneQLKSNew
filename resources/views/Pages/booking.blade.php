@extends('layouts.app')
<!-- Link tới Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Link tới jQuery và Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Link tới Bootstrap Slider CSS và JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.4/nouislider.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.4/nouislider.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.js"></script>

<style>
    .form-filter {
        margin-top: 20px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #f8f9fa;
    }

    .filter-item {
        margin-bottom: 20px;
    }

    .slider-container {
        margin-top: 10px;
    }

    .trip-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 10px;
        margin-bottom: 15px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .trip-info .info-item {
        margin-right: 20px;
    }

    .trip-info .info-item:last-child {
        margin-right: 0;
    }

    .btn-select {
        white-space: nowrap;
    }

    .slider-input {
        font-weight: bold;
    }
</style>

@section('content')
    <div class="container">
        <div class="row">
            <!-- Phần bên trái: Form bộ lọc tìm kiếm -->
            <div class="col-md-4 form-filter">
                <h4 class="mb-4 text-primary">Tìm Kiếm Chuyến Xe</h4>
                <form method="POST" action="{{ route('booking.search.advanced') }}">
                    @csrf
                    <div class="filter-item">
                        <label for="departure">Địa điểm đi</label>
                        <select class="form-control" id="departure" name="departure">
                            <!-- Option values should come from server-side or JavaScript -->
                            <option value="{{ $departure }}" selected>{{ $departure }}</option>
                            @foreach ($departures as $departure)
                                <option value="{{ $departure['departure'] }}">{{ $departure['departure'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="destination">Địa điểm đến</label>
                        <select class="form-control" id="destination" name="destination">
                            <!-- Option values should come from server-side or JavaScript -->
                            <option value="{{ $destination }}" selected>{{ $destination }}</option>
                            @foreach ($destinations as $destination)
                                <option value="{{ $destination['destination'] }}">{{ $destination['destination'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="departure-date">Ngày đi</label>
                        <input type="date" class="form-control" id="departure-date" name="departure-date"
                            value="{{ $departureDate }}">
                    </div>
                    <div class="filter-item">
                        <label for="car-type">Loại xe</label>
                        <select class="form-control" id="car-type" name="car-type">
                            <option value="All">Tất cả</option>
                            @foreach ($carTypes as $carType)
                                <option value="{{ $carType['name'] }}">{{ $carType['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="price-range">Giá tiền</label>
                        <div id="price-range" name="price-range" class="slider-container"></div>
                        <input type="text" class="form-control mt-2 slider-input" id="price-range-input"
                            name="price-range-input" readonly>
                        <!-- Hidden fields to store min and max values -->
                        <input type="hidden" id="price-min" name="price-min">
                        <input type="hidden" id="price-max" name="price-max">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Tìm Kiếm</button>
                </form>
            </div>

            <!-- Phần bên phải: Kết quả tìm kiếm -->
            <div class="col-md-8">
                <h4 class="mb-4 text-primary">Kết quả tìm kiếm</h4>
                @if ($filteredTripDetails && count($filteredTripDetails) > 0)
                    <h5>Tổng số chuyến: {{ count($filteredTripDetails) }}</h5>
                    @foreach ($filteredTripDetails as $filteredTripDetail)
                        <div class="trip-info">
                            <div class="info-item">
                                @php
                                    $percentageAvailableSeats =
                                        ($filteredTripDetail['availableSeats'] /
                                            $filteredTripDetail['tripDetail']['car']['numberOfSeats']) *
                                        100;

                                    // Giả sử bạn có thời gian dạng hh:mm:ss
                                    $departureTime = $filteredTripDetail['tripDetail']['departureTime']; // Ví dụ: '14:30:00'
                                    $destinationTime = $filteredTripDetail['tripDetail']['destinationTime']; // Ví dụ: '16:00:00'

                                    // Khởi tạo đối tượng DateTime cho thời gian đi
                                    $departureDateTime = new DateTime($departureTime);

                                    // Khởi tạo đối tượng DateTime cho thời gian đến
                                    // Thay đổi ngày để đảm bảo thời gian đến nằm sau thời gian đi
                                    $destinationDateTime = new DateTime($destinationTime);
                                    if ($destinationDateTime < $departureDateTime) {
                                        $destinationDateTime->modify('+1 day');
                                    }

                                    // Tính sự chênh lệch
                                    $interval = $departureDateTime->diff($destinationDateTime);

                                    // Format thời gian
                                    $formattedDepartureTime = $departureDateTime->format('H:i');
                                    $formattedDestinationTime = $destinationDateTime->format('H:i');

                                    // Xác định thông tin thời gian chạy
                                    $hours = $interval->h;
                                    $minutes = $interval->i;

                                    // Chọn định dạng hiển thị
                                    if ($minutes > 0) {
                                        $formattedDuration = $hours . ' giờ ' . $minutes . ' phút';
                                    } else {
                                        $formattedDuration = $hours . ' giờ';
                                    }
                                @endphp
                                <strong
                                    style="color: blue; font-weight: bold;">{{ substr($filteredTripDetail['tripDetail']['departureTime'], 0, 5) . ' - ' . substr($filteredTripDetail['tripDetail']['destinationTime'], 0, 5) }}</strong><br>
                                <small style="color: gray; font-weight: bold;">(Thời gian chạy:
                                    {{ $formattedDuration }})</small>
                            </div>
                            <div class="info-item" style="font-weight: bold;">
                                {{ $filteredTripDetail['tripDetail']['trip']['departure'] . ' - ' . $filteredTripDetail['tripDetail']['trip']['destination'] }}
                            </div>
                            <div class="info-item"
                                style="color: {{ $percentageAvailableSeats >= 50 ? 'green' : ($percentageAvailableSeats >= 20 ? 'yellow' : 'red') }}; font-weight: bold;">
                                {{ $filteredTripDetail['availableSeats'] . '/' . $filteredTripDetail['tripDetail']['car']['numberOfSeats'] }}
                                chỗ
                                trống<br>
                                <small
                                    style="color: gray; font-weight: bold;">{{ $filteredTripDetail['tripDetail']['car']['carType']['name'] }}</small>
                            </div>
                            <div class="info-item" style="color: red; font-weight: bold;">
                                {{ number_format($filteredTripDetail['tripDetail']['price'], 0, ',', '.') }}
                            </div>
                            <div class="info-item">
                                <a href="{{ route('booking.process', ['tripDetailId' => $filteredTripDetail['tripDetail']['tripDetailId'], 'departureDate' => $departureDate]) }}"
                                    class="btn btn-success btn-select">Đặt chỗ</a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>Không có chuyến nào</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function removeDuplicatesFromBottom(id) {
                const select = document.getElementById(id);
                const options = Array.from(select.options);
                const seenValues = new Set();

                // Duyệt qua các tùy chọn từ dưới lên
                for (let i = 0; i < options.length; ++i) {
                    const option = options[i];
                    if (seenValues.has(option.value)) {
                        select.remove(i); // Xóa tùy chọn nếu giá trị đã thấy
                    } else {
                        seenValues.add(option.value); // Thêm giá trị vào Set nếu chưa thấy
                    }
                }
            }

            // Loại bỏ các giá trị trùng lặp từ dưới lên cho các thẻ select
            removeDuplicatesFromBottom('departure');
            removeDuplicatesFromBottom('destination');

            // Thiết lập giá trị mặc định và ngăn chọn ngày trước ngày hôm nay
            var today = new Date().toISOString().split('T')[0];
            var departureDate = document.getElementById('departure-date');
            departureDate.setAttribute('min', today);

            // Khởi tạo slider giá tiền
            var priceSlider = document.getElementById('price-range');
            var priceInput = document.getElementById('price-range-input');

            noUiSlider.create(priceSlider, {
                start: [{{ $minPrice ?? 0 }}, {{ $maxPrice ?? 1000000 }}],
                connect: true,
                range: {
                    'min': 0,
                    'max': 1000000
                },
                step: 10000,
                format: {
                    to: function(value) {
                        return value.toLocaleString() + ' VNĐ';
                    },
                    from: function(value) {
                        return Number(value.replace(' VNĐ', '').replace(',', ''));
                    }
                }
            });

            // Cập nhật giá khi thay đổi slider
            priceSlider.noUiSlider.on('update', function(values) {
                priceInput.value = values.join(' - ');
                $("#price-min").val(values[0]);
                $("#price-max").val(values[1]);
            });
        });
    </script>
@endsection
