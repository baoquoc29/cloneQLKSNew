@extends('Admin.Layouts.admin')

@section('content')
    <!-- CSS and JS for Modal -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .action-icons {
            font-size: 1.2rem;
        }

        .btn-export {
            margin-right: 5px;
        }

        .search-input {
            max-width: 300px;
        }

        .content-wrapper {
            padding: 20px;
            background-color: #e9ecef;
        }

        .panel {
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .panel-header {
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }

        .panel-body {
            /* Có thể thêm các thuộc tính khác nếu cần */
        }
    </style>

    <!-- Content -->
    <div class="container mt-4 content-wrapper">

        <div class="panel">
            <div class="panel-header">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTripDetailModal">
                            <i class="fas fa-plus"></i> Thêm Mới
                        </button>
                    </div>

                    {{-- <div>
                        <a href="#" class="btn btn-primary btn-export"><i class="fas fa-file-excel"></i> Excel</a>
                        <a href="#" class="btn btn-danger btn-export"><i class="fas fa-file-pdf"></i> PDF</a>
                        <a href="#" class="btn btn-info btn-export"><i class="fas fa-print"></i> Print</a>
                    </div> --}}
                </div>

                <div class="container mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tìm Kiếm Nâng Cao</h4>
                        </div>
                        <div class="card-body">
                            <form id="searchForm" action="{{ route('trip-detail.search', ['page' => 1]) }}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="departure">Địa điểm đi</label>
                                            <select class="form-control" id="departure" name="departure">
                                                <option value="All" @if ($departure == 'All') selected @endif>
                                                    Tất cả
                                                </option>
                                                <!-- Thêm các địa điểm khác ở đây -->
                                                @foreach ($departures as $dep)
                                                    <option value="{{ $dep['departure'] }}"
                                                        @if ($departure == $dep['departure']) selected @endif>
                                                        {{ $dep['departure'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="destination">Địa điểm đến</label>
                                            <select class="form-control" id="destination" name="destination">
                                                <option value="All" @if ($destination == 'All') selected @endif>
                                                    Tất cả</option>
                                                <!-- Thêm các địa điểm khác ở đây -->
                                                @foreach ($destinations as $des)
                                                    <option value="{{ $des['destination'] }}"
                                                        @if ($destination == $des['destination']) selected @endif>
                                                        {{ $des['destination'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="licensePlate">Biển số xe</label>
                                            <input type="text" class="form-control" id="licensePlate" name="licensePlate"
                                                placeholder="Nhập biển số xe" value="{{ $licensePlate ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="carType">Loại xe</label>
                                            <select class="form-control" id="carTypeSearch" name="carTypeSearch">
                                                <option value="All" @if ($carTypeSearch == 'All') selected @endif>
                                                    Tất cả</option>
                                                @foreach ($carTypes as $type)
                                                    <option value="{{ $type['name'] }}"
                                                        @if ($carTypeSearch == $type['name']) selected @endif>
                                                        {{ $type['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="priceFrom">Giá từ</label>
                                            <input type="number" class="form-control" id="priceFrom" name="priceFrom"
                                                placeholder="Giá từ" value="{{ $priceFrom ?? 0 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="priceTo">Giá đến</label>
                                            <input type="number" class="form-control" id="priceTo" name="priceTo"
                                                placeholder="Giá đến" value="{{ $priceTo ?? 1000000 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="departureTimeFrom">Thời gian đi từ</label>
                                            <input type="time" class="form-control" id="departureTimeFrom"
                                                name="departureTimeFrom" value="{{ $departureTimeFrom ?? '00:00' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="departureTimeTo">Thời gian đi đến</label>
                                            <input type="time" class="form-control" id="departureTimeTo"
                                                name="departureTimeTo" value="{{ $departureTimeTo ?? '23:59' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Tìm kiếm
                                        </button>
                                        <button type="button" class="btn btn-secondary ms-2" id="resetButton">
                                            <i class="fas fa-times"></i> Xóa bộ lọc
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- <div class="mb-3">
                    <input type="text" class="form-control search-input" placeholder="Tìm kiếm chuyến đi...">
                </div> --}}
            </div>
            <div class="panel-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Danh Sách Chi Tiết Chuyến Đi</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Địa điểm đi</th>
                                    <th>Địa điểm đến</th>
                                    <th>Xe</th>
                                    <th>Loại xe</th>
                                    <th>Giá</th>
                                    <th>Thời gian khởi hành</th>
                                    <th>Thời gian đến</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $numberOrder = ($currentPage - 1) * $pageSize + 1;
                                @endphp
                                @foreach ($tripDetails as $tripDetail)
                                    <tr>
                                        <td>{{ $numberOrder++ }}</td>
                                        <td>{{ $tripDetail['trip']['departure'] }}</td>
                                        <td>{{ $tripDetail['trip']['destination'] }}</td>
                                        <td>{{ $tripDetail['car']['licensePlate'] }}</td>
                                        <td>{{ $tripDetail['car']['carType']['name'] }}</td>
                                        <td>{{ number_format($tripDetail['price'], 0, ',', '.') }}</td>
                                        <td>{{ $tripDetail['departureTime'] }}</td>
                                        <td>{{ $tripDetail['destinationTime'] }}</td>
                                        {{-- <td>{{ $tripDetail['createdAt'] }}</td>
                                        <td>{{ $tripDetail['updatedAt'] }}</td> --}}
                                        <td>
                                            {{-- <a href="#" class="btn btn-info btn-sm action-icons" title="Xem"><i
                                                    class="fas fa-eye"></i></a> --}}

                                            <a href="#" class="btn btn-warning btn-sm action-icons"
                                                data-bs-toggle="modal" data-bs-target="#updateTripDetailModal"
                                                data-id="{{ $tripDetail['tripDetailId'] }}"
                                                data-trip=" {{ $tripDetail['trip']['departure'] . ' - ' . $tripDetail['trip']['destination'] }}"
                                                data-car="{{ $tripDetail['car']['licensePlate'] }}"
                                                data-price="{{ $tripDetail['price'] }}"
                                                data-departureTime="{{ $tripDetail['departureTime'] }}"
                                                data-destinationTime="{{ $tripDetail['destinationTime'] }}"
                                                title="Sửa"><i class="fas fa-edit"></i></a>

                                            <a href="#" class="btn btn-danger btn-sm action-icons" title="Xóa"
                                                onclick="confirmDelete('{{ route('trip-detail.delete', ['tripDetailId' => $tripDetail['tripDetailId']]) }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                @if ($currentPage > 1)
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $search == false
                                                ? route('trip-detail', ['page' => $currentPage - 1])
                                                : route('trip-detail.search', [
                                                    'page' => $currentPage - 1,
                                                    'departure' => $departure,
                                                    'destination' => $destination,
                                                    'licensePlate' => $licensePlate,
                                                    'carTypeSearch' => $carTypeSearch,
                                                    'priceFrom' => $priceFrom,
                                                    'priceTo' => $priceTo,
                                                    'departureTimeFrom' => $departureTimeFrom,
                                                    'departureTimeTo' => $departureTimeTo,
                                                ]) }}"
                                            aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                @endif

                                @for ($i = 1; $i <= $totalPages; $i++)
                                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ $search == false
                                                ? route('trip-detail', ['page' => $i])
                                                : route('trip-detail.search', [
                                                    'page' => $i,
                                                    'departure' => $departure,
                                                    'destination' => $destination,
                                                    'licensePlate' => $licensePlate,
                                                    'carTypeSearch' => $carTypeSearch,
                                                    'priceFrom' => $priceFrom,
                                                    'priceTo' => $priceTo,
                                                    'departureTimeFrom' => $departureTimeFrom,
                                                    'departureTimeTo' => $departureTimeTo,
                                                ]) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                @if ($currentPage < $totalPages)
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $search == false
                                                ? route('trip-detail', ['page' => $currentPage + 1])
                                                : route('trip-detail.search', [
                                                    'page' => $currentPage + 1,
                                                    'departure' => $departure,
                                                    'destination' => $destination,
                                                    'licensePlate' => $licensePlate,
                                                    'carTypeSearch' => $carTypeSearch,
                                                    'priceFrom' => $priceFrom,
                                                    'priceTo' => $priceTo,
                                                    'departureTimeFrom' => $departureTimeFrom,
                                                    'departureTimeTo' => $departureTimeTo,
                                                ]) }}"
                                            aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Trip Detail Modal -->
    <div class="modal fade" id="addTripDetailModal" tabindex="-1" aria-labelledby="addTripDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTripDetailModalLabel">Thêm Chi Tiết Chuyến Đi Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTripDetailForm" action="{{ route('trip-detail.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="trip" class="form-label">Chuyến đi</label>
                            <select class="form-control" id="trip" name="trip" required>
                                <option value="" disabled selected>Chọn chuyến đi</option>
                                @foreach ($trips as $trip)
                                    <option value="{{ $trip['tripId'] }}">
                                        {{ $trip['departure'] . ' - ' . $trip['destination'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="car" class="form-label">Xe</label>
                            <select class="form-control" id="car" name="car" required>
                                <option value="" disabled selected>Chọn xe</option>
                                @foreach ($cars as $car)
                                    <option value="{{ $car['carId'] }}">{{ $car['licensePlate'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Giá</label>
                            <input type="number" class="form-control" id="price" name="price" min="0"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="departureTime" class="form-label">Thời gian khởi hành</label>
                            <input type="time" class="form-control" id="departureTime" name="departureTime"
                                min="00:00:00" max="23:59:59" required>
                        </div>
                        <div class="mb-3">
                            <label for="destinationTime" class="form-label">Thời gian đến</label>
                            <input type="time" class="form-control" id="destinationTime" name="destinationTime"
                                min="00:00" max="23:59" required>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Trip Detail Modal -->
    <div class="modal fade" id="updateTripDetailModal" tabindex="-1" aria-labelledby="updateTripDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateTripDetailModalLabel">Cập Nhật Chi Tiết Chuyến Đi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateTripDetailForm" action = "{{ route('trip-detail.update', ['tripDetailId' => 0]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="updateTripDetailId" name="tripDetailId">
                        <div class="mb-3">
                            <label for="updateTrip" class="form-label">Chuyến đi</label>
                            <select class="form-control" id="updateTrip" name="trip" required>
                                <option value="" disabled selected>Chọn chuyến đi</option>
                                @foreach ($trips as $trip)
                                    <option value="{{ $trip['tripId'] }}">
                                        {{ $trip['departure'] . ' - ' . $trip['destination'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="updateCar" class="form-label">Xe</label>
                            <select class="form-control" id="updateCar" name="car" required>
                                <option value="" disabled selected>Chọn xe</option>
                                @foreach ($cars as $car)
                                    <option value="{{ $car['carId'] }}">{{ $car['licensePlate'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="updatePrice" class="form-label">Giá</label>
                            <input type="number" class="form-control" id="updatePrice" name="price" min="0"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="updateDepartureTime" class="form-label">Thời gian khởi hành</label>
                            <input type="time" class="form-control" id="updateDepartureTime" name="departureTime"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="updateDestinationTime" class="form-label">Thời gian đến</label>
                            <input type="time" class="form-control" id="updateDestinationTime" name="destinationTime"
                                required>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Cập Nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var updateTripDetailModal = document.getElementById('updateTripDetailModal');

            updateTripDetailModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var trip = button.getAttribute('data-trip');

                var car = button.getAttribute('data-car');
                var price = button.getAttribute('data-price');
                var departureTime = button.getAttribute('data-departureTime');
                var destinationTime = button.getAttribute('data-destinationTime');

                // Cập nhật các giá trị của form
                document.getElementById('updateTripDetailId').value = id;
                document.getElementById('updatePrice').value = price;
                document.getElementById('updateDepartureTime').value = departureTime;
                document.getElementById('updateDestinationTime').value = destinationTime;

                // Cập nhật các tùy chọn cho select
                var tripSelect = document.getElementById('updateTrip');
                var carSelect = document.getElementById('updateCar');

                // Đặt giá trị đã chọn cho select chuyến đi
                for (var i = 0; i < tripSelect.options.length; i++) {
                    if (tripSelect.options[i].text.trim() === trip.trim()) {
                        tripSelect.selectedIndex = i;
                        break;
                    }
                }

                // Đặt giá trị đã chọn cho select xe
                for (var i = 0; i < carSelect.options.length; i++) {
                    if (carSelect.options[i].text.trim() === car.trim()) {
                        carSelect.selectedIndex = i;
                        break;
                    }
                }
            });
        });

        // Hàm để định dạng datetime cho input type="datetime-local"
        function formatDateTime(dateTime) {
            if (!dateTime) return '';
            let [date, time] = dateTime.split(' ');
            return `${date}T${time}`;
        }

        function confirmDelete(url) {
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa?',
                text: "Bạn sẽ không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý, xóa!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        @if ($message != null)
            Swal.fire({
                icon: '{{ $message === 'Thêm thành công' || $message === 'Cập nhật thành công' ? 'success' : 'error' }}',
                title: 'Thông báo!',
                text: '{{ $message == 'An error occurred' ? "Trùng lịch chạy" :   $message}}',
                toast: false,
                position: 'top',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    container: 'swal2-container-middle',
                    popup: 'swal2-small-popup'
                }
            });
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('resetButton').addEventListener('click', function(e) {
                e.preventDefault(); // Ngăn chặn hành vi mặc định của nút

                // Reset các trường input
                document.getElementById('departure').value = 'All';
                document.getElementById('destination').value = 'All';
                document.getElementById('licensePlate').value = '';
                document.getElementById('carTypeSearch').value = 'All';
                document.getElementById('priceFrom').value = '';
                document.getElementById('priceTo').value = '';
                document.getElementById('departureTimeFrom').value = '';
                document.getElementById('departureTimeTo').value = '';

                // Submit form
                document.getElementById('searchForm').submit();
            });
        });
    </script>
@endsection