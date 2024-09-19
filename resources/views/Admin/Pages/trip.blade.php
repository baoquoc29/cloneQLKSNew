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
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTripModal">
                            <i class="fas fa-plus"></i> Thêm Mới
                        </button>
                    </div>

                    <div>
                        <a href="#" class="btn btn-primary btn-export"><i class="fas fa-file-excel"></i> Excel</a>
                        <a href="#" class="btn btn-danger btn-export"><i class="fas fa-file-pdf"></i> PDF</a>
                        <a href="#" class="btn btn-info btn-export"><i class="fas fa-print"></i> Print</a>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control search-input" placeholder="Tìm kiếm chuyến đi...">
                </div>
            </div>
            <div class="panel-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Danh Sách Chuyến Đi</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Điểm khởi hành</th>
                                    <th>Điểm đến</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày cập nhập</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $numberOrder = ($currentPage - 1) * $pageSize + 1;
                                @endphp
                                @foreach ($trips as $trip)
                                    <tr>
                                        <td>{{ $numberOrder++ }}</td>
                                        <td>{{ $trip['departure'] }}</td>
                                        <td>{{ $trip['destination'] }}</td>
                                        <td>{{ $trip['createdAt'] }}</td>
                                        <td>{{ $trip['updatedAt'] }}</td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm action-icons" title="Xem"><i
                                                    class="fas fa-eye"></i></a>

                                            <a href="#" class="btn btn-warning btn-sm action-icons"
                                                data-bs-toggle="modal" data-bs-target="#updateTripModal"
                                                data-id="{{ $trip['tripId'] }}" data-departure="{{ $trip['departure'] }}"
                                                data-destination="{{ $trip['destination'] }}" title="Sửa"><i
                                                    class="fas fa-edit"></i></a>

                                            <a href="#" class="btn btn-danger btn-sm action-icons" title="Xóa"
                                                onclick="confirmDelete('{{ route('trip.delete', ['tripId' => $trip['tripId']]) }}')">
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
                                        <a class="page-link" href="{{ route('trip', ['page' => $currentPage - 1]) }}"
                                            aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                @endif

                                @for ($i = 1; $i <= $totalPages; $i++)
                                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ route('trip', ['page' => $i]) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                @if ($currentPage < $totalPages)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ route('trip', ['page' => $currentPage + 1]) }}"
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

    <!-- Add Trip Modal -->
    <div class="modal fade" id="addTripModal" tabindex="-1" aria-labelledby="addTripModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTripModalLabel">Thêm Chuyến Đi Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTripForm" action="{{ route('trip.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="tripDeparture" class="form-label">Điểm khởi hành</label>
                            <input type="text" class="form-control" id="tripDeparture" name="tripDeparture" required>
                        </div>
                        <div class="mb-3">
                            <label for="tripDestination" class="form-label">Điểm đến</label>
                            <input type="text" class="form-control" id="tripDestination" name="tripDestination" required>
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

    <!-- Update Trip Modal -->
    <div class="modal fade" id="updateTripModal" tabindex="-1" aria-labelledby="updateTripModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateTripModalLabel">Cập Nhật Chuyến Đi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateTripForm" action="{{ route('trip.update', ['tripId' => $trip['tripId']]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="updateTripId" name="tripId">
                        <div class="mb-3">
                            <label for="updateTripDeparture" class="form-label">Điểm khởi hành</label>
                            <input type="text" class="form-control" id="updateTripDeparture" name="tripDeparture"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="updateTripDestination" class="form-label">Điểm đến</label>
                            <input type="text" class="form-control" id="updateTripDestination" name="tripDestination"
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
            var updateTripModal = document.getElementById('updateTripModal');
            updateTripModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var departure = button.getAttribute('data-departure');
                var destination = button.getAttribute('data-destination');

                var tripIdInput = updateTripModal.querySelector('#updateTripId');
                var tripDepartureInput = updateTripModal.querySelector('#updateTripDeparture');
                var tripDestinationInput = updateTripModal.querySelector('#updateTripDestination');

                tripIdInput.value = id;
                tripDepartureInput.value = departure;
                tripDestinationInput.value = destination;
            });
        });

        function confirmDelete(url) {
            // Hiển thị hộp thoại xác nhận
            if (confirm('Bạn có chắc chắn muốn xóa chuyến đi này không?')) {
                // Nếu người dùng chọn OK, điều hướng tới URL xóa
                window.location.href = url;
            }
        }


        @if ($message != null)
            Swal.fire({
                icon: 'success',
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
