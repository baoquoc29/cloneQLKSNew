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

<style>
    body {
        background-color: #f7f9fc;
        font-family: 'Arial', sans-serif;
    }

    .container {
        padding: 30px;
    }

    .form-filter {
        padding: 30px;
        border-radius: 10px;
        background-color: #ffffff;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .form-filter h4 {
        color: #007bff;
        font-weight: bold;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
        margin-bottom: 20px;
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
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 15px;
        background-color: #ffffff;
        transition: box-shadow 0.3s ease;
    }

    .trip-info:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
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
        transition: background-color 0.3s, border-color 0.3s;
        background-color: #28a745;
        border: none;
        color: white;
    }

    .btn-select:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .slider-input {
        font-weight: bold;
        text-align: center;
        border: none;
        background-color: #e9ecef;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .form-filter {
            padding: 15px;
        }

        .trip-info {
            flex-direction: column;
            align-items: flex-start;
        }

        .trip-info .info-item {
            margin-bottom: 10px;
        }
    }
</style>

@section('content')
    <div class="container">
        <div class="row">
            <!-- Phần bên trái: Form bộ lọc tìm kiếm -->
            <div class="col-md-4 form-filter">
                <h4 class="mb-4">Tìm Kiếm Chuyến Xe</h4>
                <form method="POST" action="{{ route('booking.search.advanced') }}">
                    @csrf
                    <div class="filter-item">
                        <label for="departure">Địa điểm đi</label>
                        <select class="form-control" id="departure" name="departure">
                            <option value="{{ $departure }}" selected>{{ $departure }}</option>
                            @foreach ($departures as $departure)
                                <option value="{{ $departure['departure'] }}">{{ $departure['departure'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="destination">Địa điểm đến</label>
                        <select class="form-control" id="destination" name="destination">
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
                                chỗ trống<br>
                                <small
                                    style="color: gray; font-weight: bold;">{{ $filteredTripDetail['tripDetail']['car']['carType']['name'] }}</small>
                            </div>
                            <div class="info-item" style="color: red; font-weight: bold;">
                                {{ number_format($filteredTripDetail['tripDetail']['price'], 0, ',', '.') }} VNĐ
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
