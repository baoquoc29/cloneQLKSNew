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
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCarTypeModal">
                            <i class="fas fa-plus"></i> Thêm Mới
                        </button>
                    </div>

                    {{-- <div>
                        <a href="#" class="btn btn-primary btn-export"><i class="fas fa-file-excel"></i> Excel</a>
                        <a href="#" class="btn btn-danger btn-export"><i class="fas fa-file-pdf"></i> PDF</a>
                        <a href="#" class="btn btn-info btn-export"><i class="fas fa-print"></i> Print</a>
                    </div> --}}
                </div>
                <div class="mb-3">
                    <form id="searchCarTypeForm" action="{{ route('car-type.search', ['page' => 1]) }}" method="GET"
                        class="d-flex mb-3">

                        <input type="text" class="form-control search-input" id="searchCarTypeName"
                            name="searchCarTypeName" placeholder="Tìm kiếm tên loại xe..."
                            value = "{{ $search ? $searchCarTypeName : '' }}">
                        <button type="submit" class="btn btn-primary ms-2">Tìm kiếm</button>
                    </form>
                </div>
            </div>
            <div class="panel-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Danh Sách Loại Xe</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tên Loại Xe</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày cập nhật</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $numberOrder = ($currentPage - 1) * $pageSize + 1;
                                @endphp
                                @foreach ($carTypes as $carType)
                                    <tr>
                                        <td>{{ $numberOrder++ }}</td>
                                        <td>{{ $carType['name'] }}</td>
                                        <td>{{ $carType['createdAt'] }}</td>
                                        <td>{{ $carType['updatedAt'] }}</td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm action-icons" title="Xem"><i
                                                    class="fas fa-eye"></i></a>
                                            <a href="#" class="btn btn-warning btn-sm action-icons"
                                                data-bs-toggle="modal" data-bs-target="#updateCarTypeModal"
                                                data-id="{{ $carType['carTypeId'] }}" data-name="{{ $carType['name'] }}"
                                                title="Sửa"><i class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-danger btn-sm action-icons" title="Xóa"
                                                onclick="confirmDelete('{{ route('car-type.delete', ['carTypeId' => $carType['carTypeId']]) }}')"><i
                                                    class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <!-- Previous Page Link -->
                                @if ($currentPage > 1)
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $search == false
                                                ? route('car-type', ['page' => $currentPage - 1])
                                                : route('car-type.search', ['page' => $currentPage - 1, 'searchCarTypeName' => $searchCarTypeName]) }}"
                                            aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                @endif

                                <!-- Page Numbers -->
                                @for ($i = 1; $i <= $totalPages; $i++)
                                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ $search == false
                                                ? route('car-type', ['page' => $i])
                                                : route('car-type.search', ['page' => $i, 'searchCarTypeName' => $searchCarTypeName]) }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor

                                <!-- Next Page Link -->
                                @if ($currentPage < $totalPages)
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $search == false
                                                ? route('car-type', ['page' => $currentPage + 1])
                                                : route('car-type.search', ['page' => $currentPage + 1, 'searchCarTypeName' => $searchCarTypeName]) }}"
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

    <!-- Add Car Type Modal -->
    <div class="modal fade" id="addCarTypeModal" tabindex="-1" aria-labelledby="addCarTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCarTypeModalLabel">Thêm Loại Xe Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addCarTypeForm" action="{{ route('car-type.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="carTypeName" class="form-label">Tên Loại Xe</label>
                            <input type="text" class="form-control" id="carTypeName" name="carTypeName" required>
                            <div id="carTypeNameError" class="invalid-feedback"></div>
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

    <!-- Update Car Type Modal -->
    <div class="modal fade" id="updateCarTypeModal" tabindex="-1" aria-labelledby="updateCarTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateCarTypeModalLabel">Cập Nhật Loại Xe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateCarTypeForm" action="{{ route('car-type.update', ['carTypeId' => 0]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="updateCarTypeId" name="carTypeId">
                        <div class="mb-3">
                            <label for="updateCarTypeName" class="form-label">Tên Loại Xe</label>
                            <input type="text" class="form-control" id="updateCarTypeName" name="carTypeName"
                                required>
                            <div id="updateCarTypeNameError" class="invalid-feedback"></div>
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
        function isValidCarTypeName(name) {
            // Chỉ cho phép chữ cái, số, dấu cách và dấu gạch ngang
            return /^[a-zA-Z0-9\s-]+$/.test(name);
        }

        function validateCarTypeInput(inputElement, errorElement) {
            inputElement.addEventListener('input', function() {
                if (!isValidCarTypeName(this.value)) {
                    errorElement.textContent = 'Tên loại xe không được chứa ký tự đặc biệt';
                    this.classList.add('is-invalid');
                } else {
                    errorElement.textContent = '';
                    this.classList.remove('is-invalid');
                }
            });

            inputElement.addEventListener('keypress', function(e) {
                if (!isValidCarTypeName(e.key)) {
                    e.preventDefault();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý form thêm mới
            const addCarTypeNameInput = document.getElementById('carTypeName');
            const addCarTypeNameError = document.getElementById('carTypeNameError');
            validateCarTypeInput(addCarTypeNameInput, addCarTypeNameError);

            // Xử lý form cập nhật
            const updateCarTypeNameInput = document.getElementById('updateCarTypeName');
            const updateCarTypeNameError = document.getElementById('updateCarTypeNameError');
            validateCarTypeInput(updateCarTypeNameInput, updateCarTypeNameError);

            // Thêm xử lý submit form
            document.getElementById('addCarTypeForm').addEventListener('submit', function(e) {
                if (!isValidCarTypeName(addCarTypeNameInput.value)) {
                    e.preventDefault();
                    addCarTypeNameError.textContent = 'Tên loại xe không hợp lệ';
                }
            });

            document.getElementById('updateCarTypeForm').addEventListener('submit', function(e) {
                if (!isValidCarTypeName(updateCarTypeNameInput.value)) {
                    e.preventDefault();
                    updateCarTypeNameError.textContent = 'Tên loại xe không hợp lệ';
                }
            });

            // Giữ lại code xử lý modal hiện có
            var updateCarTypeModal = document.getElementById('updateCarTypeModal');
            updateCarTypeModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');

                var carTypeNameInput = updateCarTypeModal.querySelector('#updateCarTypeName');
                var carTypeIdInput = updateCarTypeModal.querySelector('#updateCarTypeId');

                carTypeNameInput.value = name;
                carTypeIdInput.value = id;

                // Update form action dynamically
                var formAction = document.getElementById('updateCarTypeForm').action;
                document.getElementById('updateCarTypeForm').action = formAction.replace(/\/\d+$/, '/' +
                    id);
            });
        });

        function confirmDelete(url) {
            if (confirm('Bạn có chắc chắn muốn xóa loại xe này không?')) {
                window.location.href = url;
            }
        }

        @if ($message != null)
            Swal.fire({
                icon: '{{ $message === 'Thêm loại xe thành công' || $message === 'Cập nhật loại xe thành công' ? 'success' : 'error' }}',
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
@endsection