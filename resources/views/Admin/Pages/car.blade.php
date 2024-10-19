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

        .swal2-horizontal-popup {
            width: auto;
            /* Đặt chiều rộng tự động để phù hợp với nội dung */
            max-width: 200px;
            /* Chiều rộng tối đa nhỏ hơn */
            padding: 5px 10px;
            /* Khoảng cách bên trong nhỏ hơn */
            font-size: 0.75rem;
            /* Kích thước chữ nhỏ hơn */
            text-align: center;
            /* Canh giữa nội dung */
            border-radius: 8px;
            /* Bo tròn góc */
        }

        .swal2-popup {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Đổ bóng nhẹ */
        }
    </style>

    <!-- Content -->
    <div class="container mt-4 content-wrapper">
        <div class="panel">
            <div class="panel-header">
                <!-- Form tìm kiếm nâng cao -->
                <div class="container mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tìm Kiếm Nâng Cao</h4>
                        </div>
                        <div class="card-body">
                            <form id="searchForm" action="{{ route('car.search', ['page' => 1]) }}" method="GET">
                                <div class="row">
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

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="numberOfSeats">Số ghế</label>
                                            <input type="number" class="form-control" id="numberOfSeats"
                                                name="numberOfSeats" placeholder="Nhập số ghế"
                                                value="{{ $numberOfSeats ?? '' }}" min="4" max="100">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">Trạng thái</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="All" @if ($status == 'All') selected @endif>Tất
                                                    cả</option>
                                                <option value="Hoạt động" @if ($status == 'Hoạt động') selected @endif>
                                                    Hoạt động</option>
                                                <option value="Bảo trì" @if ($status == 'Bảo trì') selected @endif>
                                                    Bảo trì
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Tìm kiếm
                                        </button>
                                        <!-- Thêm lớp ms-2 để tạo khoảng cách -->
                                        <button type="button" class="btn btn-secondary ms-2" id="resetButton">
                                            <i class="fas fa-times"></i> Xóa bộ lọc
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCarModal">
                            <i class="fas fa-plus"></i> Thêm Mới
                        </button>
                    </div>

                    {{-- <div>
                        <a href="#" class="btn btn-primary btn-export"><i class="fas fa-file-excel"></i> Excel</a>
                        <a href="#" class="btn btn-danger btn-export"><i class="fas fa-file-pdf"></i> PDF</a>
                        <a href="#" class="btn btn-info btn-export"><i class="fas fa-print"></i> Print</a>
                    </div> --}}
                </div>
            </div>
            <div class="panel-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Danh Sách Xe</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Biển số xe</th>
                                    <th>Loại xe</th>
                                    <th>Số ghế</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày cập nhật</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $numberOrder = ($currentPage - 1) * $pageSize + 1;
                                @endphp
                                @foreach ($cars as $car)
                                    <tr>
                                        <td>{{ $numberOrder++ }}</td>
                                        <td>{{ $car['licensePlate'] }}</td>
                                        <td>{{ $car['carType']['name'] }}</td>
                                        <td>{{ $car['numberOfSeats'] }}</td>
                                        <td>{{ $car['status'] }}</td>
                                        <td>{{ $car['createdAt'] }}</td>
                                        <td>{{ $car['updatedAt'] }}</td>
                                        <td>
                                            {{-- <a href="#" class="btn btn-info btn-sm action-icons" title="Xem"><i
                                                    class="fas fa-eye"></i></a> --}}
                                            <a href="#" class="btn btn-warning btn-sm action-icons"
                                                data-bs-toggle="modal" data-bs-target="#updateCarModal"
                                                data-id="{{ $car['carId'] }}"
                                                data-licenseplate="{{ $car['licensePlate'] }}"
                                                data-cartype="{{ $car['carType']['name'] }}"
                                                data-seats="{{ $car['numberOfSeats'] }}"
                                                data-status="{{ $car['status'] }}" title="Sửa"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-danger btn-sm action-icons btn-delete" 
                                               data-delete-url="{{ route('car.delete', ['carId' => $car['carId']]) }}" 
                                               title="Xóa">
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
                                                ? route('car', ['page' => $currentPage - 1])
                                                : route('car.search', [
                                                    'page' => $currentPage - 1,
                                                    'licensePlate' => $licensePlate,
                                                    'carTypeSearch' => $carTypeSearch,
                                                    'numberOfSeats' => $numberOfSeats,
                                                    'status' => $status,
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
                                                ? route('car', ['page' => $i])
                                                : route('car.search', [
                                                    'page' => $i,
                                                    'licensePlate' => $licensePlate,
                                                    'carTypeSearch' => $carTypeSearch,
                                                    'numberOfSeats' => $numberOfSeats,
                                                    'status' => $status,
                                                ]) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                @if ($currentPage < $totalPages)
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $search == false
                                                ? route('car', ['page' => $currentPage + 1])
                                                : route('car.search', [
                                                    'page' => $currentPage + 1,
                                                    'licensePlate' => $licensePlate,
                                                    'carTypeSearch' => $carTypeSearch,
                                                    'numberOfSeats' => $numberOfSeats,
                                                    'status' => $status,
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

    <!-- Add Car Modal -->
    <div class="modal fade" id="addCarModal" tabindex="-1" aria-labelledby="addCarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCarModalLabel">Thêm Xe Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addCarForm" action="{{ route('car.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="carLicensePlate" class="form-label">Biển số xe</label>
                            <input type="text" class="form-control" id="carLicensePlate" name="carLicensePlate"
                                required maxlength="10">
                            <div id="carLicensePlateError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="carType" class="form-label">Loại xe</label>
                            <select class="form-control" id="carType" name="carType" required>
                                <option value="" disabled selected>Chọn loại xe</option>
                                @foreach ($carTypes as $carType)
                                    <option value="{{ $carType['carTypeId'] }}">{{ $carType['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="seatNumber" class="form-label">Số ghế</label>
                            <input type="number" class="form-control" id="seatNumber" name="seatNumber" min="1"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="carStatus" class="form-label">Trạng thái</label>
                            <select class="form-control" id="carStatus" name="carStatus" required>
                                <option value="Hoạt động">Hoạt động</option>
                                <option value="Bảo trì">Bảo trì</option>
                            </select>
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

    <!-- Update Car Modal -->
    <div class="modal fade" id="updateCarModal" tabindex="-1" aria-labelledby="updateCarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateCarModalLabel">Cập Nhật Xe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateCarForm" action="{{ route('car.update', ['carId' => 0]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="updateCarId" name="carId">
                        <div class="mb-3">
                            <label for="updateCarLicensePlate" class="form-label">Biển số xe</label>
                            <input type="text" class="form-control" id="updateCarLicensePlate" name="carLicensePlate"
                                required maxlength="10">
                            <div id="updateCarLicensePlateError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="updateCarType" class="form-label">Loại xe</label>
                            <select class="form-control" id="updateCarType" name="carType" required>
                                @foreach ($carTypes as $carType)
                                    <option value="{{ $carType['carTypeId'] }}">{{ $carType['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="updateSeatNumber" class="form-label">Số ghế</label>
                            <input type="number" class="form-control" id="updateSeatNumber" name="seatNumber"
                                min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="updateCarStatus" class="form-label">Trạng thái</label>
                            <select class="form-control" id="updateCarStatus" name="carStatus" required>
                                <option value="Hoạt động">Hoạt động</option>
                                <option value="Bảo trì">Bảo trì</option>
                            </select>
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

    <!-- Thêm JavaScript reset form -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('resetButton').addEventListener('click', function(e) {
                e.preventDefault(); // Ngăn chặn hành vi mặc định của nút

                // Reset các trường input
                document.getElementById('licensePlate').value = '';
                document.getElementById('carTypeSearch').value = 'All';
                document.getElementById('numberOfSeats').value = '';
                document.getElementById('status').value = 'All';

                // Submit form
                document.getElementById('searchForm').submit();
            });
        });
    </script>

    <!-- JavaScript to Handle Modal Data -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var updateCarModal = document.getElementById('updateCarModal');
            updateCarModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var carId = button.getAttribute('data-id');
                var licensePlate = button.getAttribute('data-licenseplate');
                var carType = button.getAttribute('data-cartype');
                var seats = button.getAttribute('data-seats');
                var status = button.getAttribute('data-status');

                document.getElementById('updateCarId').value = carId;
                document.getElementById('updateCarLicensePlate').value = licensePlate;
                document.getElementById('updateSeatNumber').value = seats;
                document.getElementById('updateCarStatus').value = status;

                // Cập nhật các tùy chọn cho select
                var carTypeSelect = document.getElementById('updateCarType');

                // Đặt giá trị đã chọn cho select loại xe
                for (var i = 0; i < carTypeSelect.options.length; i++) {
                    if (carTypeSelect.options[i].text.trim() === carType.trim()) {
                        carTypeSelect.selectedIndex = i;
                        break;
                    }
                }

                var modal = bootstrap.Modal.getInstance(updateCarModal);
                modal.querySelector('#updateCarId').value = carId;
                modal.querySelector('#updateCarLicensePlate').value = licensePlate;
                modal.querySelector('#updateCarType').value = carType;
                modal.querySelector('#updateSeatNumber').value = seats;
                modal.querySelector('#updateCarStatus').value = status;
            });
        });

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
                icon: '{{ $message === 'Thêm xe thành công' || $message === 'Cập nhật xe thành công' ? 'success' : 'error' }}',
                title: 'Thông báo!',
                text: '{{ $message }}',
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
    </script>

    <script>
        function isValidLicensePlate(licensePlate) {
            // Biểu thức chính quy cho biển số xe Việt Nam
            const regex = /^(1[1-9]|[2-9][0-9])[A-Z](-|\s)?(\d{3}(\.\d{2}|\d{2})|\d{4,5})$/;
            return regex.test(licensePlate);
        }

        function isValidLicensePlateChar(char, position) {
            if (position < 2) {
                return /[1-9]/.test(char);
            } else if (position === 2) {
                return /[A-Z]/.test(char);
            } else if (position === 3) {
                return /[-\s\d]/.test(char);
            } else if (position === 7) {
                return /[\d.]/.test(char);
            } else {
                return /\d/.test(char);
            }
        }

        function validateAddCarForm() {
            const licensePlate = document.getElementById('carLicensePlate');
            const seatNumber = document.getElementById('seatNumber');
            const licensePlateError = document.getElementById('carLicensePlateError');
            const seatNumberError = document.getElementById('seatNumberError');
            let isValid = true;

            if (!isValidLicensePlate(licensePlate.value)) {
                licensePlateError.textContent = 'Biển số xe không hợp lệ. Vui lòng nhập theo định dạng: 51A-12345, 51A 123.45 hoặc 51A12345';
                licensePlate.classList.add('is-invalid');
                isValid = false;
            } else {
                licensePlateError.textContent = '';
                licensePlate.classList.remove('is-invalid');
            }

            const seats = parseInt(seatNumber.value);
            if (isNaN(seats) || seats < 4 || seats > 100) {
                seatNumberError.textContent = 'Số ghế phải từ 4 đến 100';
                seatNumber.classList.add('is-invalid');
                isValid = false;
            } else {
                seatNumberError.textContent = '';
                seatNumber.classList.remove('is-invalid');
            }

            return isValid;
        }

        function validateUpdateCarForm() {
            const licensePlate = document.getElementById('updateCarLicensePlate');
            const seatNumber = document.getElementById('updateSeatNumber');
            const licensePlateError = document.getElementById('updateCarLicensePlateError');
            const seatNumberError = document.getElementById('updateSeatNumberError');
            let isValid = true;

            if (!isValidLicensePlate(licensePlate.value)) {
                licensePlateError.textContent = 'Biển số xe không hợp lệ. Vui lòng nhập theo định dạng: 51A-12345, 51A 123.45 hoặc 51A12345';
                licensePlate.classList.add('is-invalid');
                isValid = false;
            } else {
                licensePlateError.textContent = '';
                licensePlate.classList.remove('is-invalid');
            }

            const seats = parseInt(seatNumber.value);
            if (isNaN(seats) || seats < 4 || seats > 100) {
                seatNumberError.textContent = 'Số ghế phải từ 4 đến 100';
                seatNumber.classList.add('is-invalid');
                isValid = false;
            } else {
                seatNumberError.textContent = '';
                seatNumber.classList.remove('is-invalid');
            }

            return isValid;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const licensePlateInputs = document.querySelectorAll('#carLicensePlate, #updateCarLicensePlate');
            
            licensePlateInputs.forEach(input => {
                input.addEventListener('input', function(e) {
                    let value = this.value.toUpperCase();
                    let newValue = '';
                    
                    for (let i = 0; i < value.length && i < 12; i++) {
                        if (isValidLicensePlateChar(value[i], i)) {
                            newValue += value[i];
                        }
                    }
                    
                    this.value = newValue;
                    
                    const errorElement = this.nextElementSibling;
                    if (newValue.length >= 7 && !isValidLicensePlate(newValue)) {
                        errorElement.textContent = 'Biển số xe không hợp lệ. Vui lòng nhập theo định dạng: 51A-12345, 51A 123.45 hoặc 51A12345';
                        this.classList.add('is-invalid');
                    } else {
                        errorElement.textContent = '';
                        this.classList.remove('is-invalid');
                    }
                });
            });
            
            // ... (giữ nguyên code khác)
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ... (giữ nguyên code khác)

            // Xử lý sự kiện click cho nút xóa
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('data-delete-url');
                    confirmDelete(url);
                });
            });
        });
    </script>
@endsection
