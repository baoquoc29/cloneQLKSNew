@extends('layouts.app')

<!-- Link tới Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Link tới jQuery và Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Link tới Bootstrap Slider CSS và JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.4/nouislider.min.js"></script>
<!-- Link tới Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Roboto', sans-serif;
    }

    .container {
        padding: 40px 20px;
    }

    .form-filter {
        padding: 30px;
        border-radius: 15px;
        background-color: #ffffff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }

    .form-filter:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .form-filter h4 {
        color: #4e54c8;
        font-weight: bold;
        border-bottom: 2px solid #4e54c8;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }

    .filter-item {
        margin-bottom: 25px;
    }

    .form-control,
    .btn {
        border-radius: 10px;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(78, 84, 200, 0.25);
        border-color: #4e54c8;
    }

    .btn-primary {
        background-color: #4e54c8;
        border: none;
        padding: 10px 20px;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #3f45b6;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 84, 200, 0.4);
    }

    .slider-container {
        margin-top: 15px;
    }

    .trip-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        border: none;
        border-radius: 15px;
        margin-bottom: 20px;
        background-color: #ffffff;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .trip-info:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .trip-info .info-item {
        margin-right: 20px;
        flex: 1;
    }

    .trip-info .info-item:last-child {
        margin-right: 0;
    }

    .btn-select {
        white-space: nowrap;
        transition: all 0.3s ease;
        background-color: #28a745;
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: bold;
    }

    .btn-select:hover {
        background-color: #218838;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    }

    .slider-input {
        font-weight: bold;
        text-align: center;
        border: none;
        background-color: #e9ecef;
        border-radius: 10px;
        padding: 10px;
        margin-top: 10px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .form-filter {
            padding: 20px;
        }

        .trip-info {
            flex-direction: column;
            align-items: flex-start;
        }

        .trip-info .info-item {
            margin-bottom: 15px;
            width: 100%;
        }
    }

    .btn-select.btn-secondary {
        background-color: #6c757d;
        cursor: not-allowed;
    }

    .btn-select.btn-secondary:hover {
        background-color: #5a6268;
        transform: none;
        box-shadow: none;
    }

    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
    }

    .pagination li {
        margin: 0 5px;
    }

    .pagination li a, .pagination li span {
        display: block;
        padding: 5px 10px;
        border: 1px solid #4e54c8;
        color: #4e54c8;
        text-decoration: none;
        border-radius: 5px;
    }

    .pagination li.active span {
        background-color: #4e54c8;
        color: white;
    }

    .pagination li a:hover {
        background-color: #4e54c8;
        color: white;
    }
</style>

@section('content')
    <div class="container">
        <div class="row">
            <!-- Phần bên trái: Form bộ lọc tìm kiếm -->
            <div class="col-md-4 form-filter">
                <h4 class="mb-4"><i class="fas fa-search mr-2"></i>Tìm Kiếm Chuyến Xe</h4>
                <form method="POST" action="{{ route('booking.search.advanced') }}">
                    @csrf
                    <div class="filter-item">
                        <label for="departure"><i class="fas fa-map-marker-alt mr-2"></i>Địa điểm đi</label>
                        <select class="form-control" id="departure" name="departure">
                            <option value="{{ $departure }}" selected>{{ $departure }}</option>
                            @foreach ($departures as $departure)
                                <option value="{{ $departure['departure'] }}">{{ $departure['departure'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="destination"><i class="fas fa-map-pin mr-2"></i>Địa điểm đến</label>
                        <select class="form-control" id="destination" name="destination">
                            <option value="{{ $destination }}" selected>{{ $destination }}</option>
                            @foreach ($destinations as $destination)
                                <option value="{{ $destination['destination'] }}">{{ $destination['destination'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="departure-date"><i class="far fa-calendar-alt mr-2"></i>Ngày đi</label>
                        <input type="date" class="form-control" id="departure-date" name="departure-date"
                            value="{{ $departureDate }}">
                    </div>
                    <div class="filter-item">
                        <label for="time-range"><i class="far fa-clock mr-2"></i>Thời gian xuất phát</label>
                        <select class="form-control" id="time-range" name="time-range">
                            <option value="All" @if ($timeRange == 'All') selected @endif>Tất cả</option>
                            <option value="morning" @if ($timeRange == 'morning') selected @endif>Sáng (5:00 - 11:59)
                            </option>
                            <option value="afternoon" @if ($timeRange == 'afternoon') selected @endif>Chiều (12:00 -
                                17:59)</option>
                            <option value="evening" @if ($timeRange == 'evening') selected @endif>Tối (18:00 - 4:59)
                            </option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="car-type"><i class="fas fa-bus mr-2"></i>Loại xe</label>
                        <select class="form-control" id="car-type" name="car-type">
                            <option value="All" @if ($carTypeRequest == 'All') selected @endif>Tất cả</option>
                            @foreach ($carTypes as $carType)
                                <option value="{{ $carType['name'] }}" @if ($carTypeRequest == $carType['name']) selected @endif>
                                    {{ $carType['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="price-range"><i class="fas fa-dollar-sign mr-2"></i>Giá tiền</label>
                        <div id="price-range" name="price-range" class="slider-container"></div>
                        <input type="text" class="form-control mt-2 slider-input" id="price-range-input"
                            name="price-range-input" readonly>
                        <input type="hidden" id="price-min" name="price-min">
                        <input type="hidden" id="price-max" name="price-max">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3 btn-block"><i class="fas fa-search mr-2"></i>Tìm
                        Kiếm</button>
                </form>
            </div>

            <!-- Phần bên phải: Kết quả tìm kiếm -->
            <div class="col-md-8">
                <h4 class="mb-4 text-primary"><i class="fas fa-list mr-2"></i>Kết quả tìm kiếm</h4>
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
                                    $departureTime = $filteredTripDetail['tripDetail']['departureTime'];
                                    $destinationTime = $filteredTripDetail['tripDetail']['destinationTime'];
                                    $departureDateTime = new DateTime($departureTime);
                                    $destinationDateTime = new DateTime($destinationTime);
                                    if ($destinationDateTime < $departureDateTime) {
                                        $destinationDateTime->modify('+1 day');
                                    }
                                    $interval = $departureDateTime->diff($destinationDateTime);
                                    $formattedDepartureTime = $departureDateTime->format('H:i');
                                    $formattedDestinationTime = $destinationDateTime->format('H:i');
                                    $hours = $interval->h;
                                    $minutes = $interval->i;
                                    $formattedDuration =
                                        $minutes > 0 ? $hours . ' giờ ' . $minutes . ' phút' : $hours . ' giờ';
                                @endphp
                                <strong style="color: #4e54c8; font-weight: bold;">
                                    <i
                                        class="far fa-clock mr-2"></i>{{ substr($filteredTripDetail['tripDetail']['departureTime'], 0, 5) . ' - ' . substr($filteredTripDetail['tripDetail']['destinationTime'], 0, 5) }}
                                </strong><br>
                                <small style="color: #6c757d; font-weight: bold;">
                                    <i class="fas fa-hourglass-half mr-2"></i>{{ $formattedDuration }}
                                </small>
                            </div>
                            <div class="info-item" style="font-weight: bold;">
                                <i
                                    class="fas fa-route mr-2"></i>{{ $filteredTripDetail['tripDetail']['trip']['departure'] . ' - ' . $filteredTripDetail['tripDetail']['trip']['destination'] }}
                            </div>
                            <div class="info-item"
                                style="color: {{ $percentageAvailableSeats >= 50 ? '#28a745' : ($percentageAvailableSeats >= 20 ? '#ffc107' : '#dc3545') }}; font-weight: bold;">
                                <i
                                    class="fas fa-users mr-2"></i>{{ $filteredTripDetail['availableSeats'] . '/' . $filteredTripDetail['tripDetail']['car']['numberOfSeats'] }}
                                chỗ trống<br>
                                <small style="color: #6c757d; font-weight: bold;">
                                    <i
                                        class="fas fa-bus mr-2"></i>{{ $filteredTripDetail['tripDetail']['car']['carType']['name'] }}
                                </small>
                            </div>
                            <div class="info-item" style="color: #dc3545; font-weight: bold;">
                                <i
                                    class="fas fa-tag mr-2"></i>{{ number_format($filteredTripDetail['tripDetail']['price'], 0, ',', '.') }}
                                VNĐ
                            </div>
                            <div class="info-item">
                                @if ($filteredTripDetail['availableSeats'] > 0)
                                    <a href="{{ route('booking.process', ['tripDetailId' => $filteredTripDetail['tripDetail']['tripDetailId'], 'departureDate' => $departureDate]) }}"
                                        class="btn btn-success btn-select">
                                        <i class="fas fa-ticket-alt mr-2"></i>Đặt chỗ
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-select" disabled>
                                        <i class="fas fa-ban mr-2"></i>Hết vé
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="alert alert-info"><i class="fas fa-info-circle mr-2"></i>Không có chuyến nào</p>
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

                for (let i = 0; i < options.length; ++i) {
                    const option = options[i];
                    if (seenValues.has(option.value)) {
                        select.remove(i);
                    } else {
                        seenValues.add(option.value);
                    }
                }
            }

            removeDuplicatesFromBottom('departure');
            removeDuplicatesFromBottom('destination');
            removeDuplicatesFromBottom('car-type');

            var today = new Date().toISOString().split('T')[0];
            var departureDate = document.getElementById('departure-date');
            departureDate.setAttribute('min', today);

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

            priceSlider.noUiSlider.on('update', function(values) {
                priceInput.value = values.join(' - ');
                $("#price-min").val(values[0]);
                $("#price-max").val(values[1]);
            });
        });
    </script>
@endsection