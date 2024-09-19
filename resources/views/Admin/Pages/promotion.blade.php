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
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
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
                    <form id="searchPromotionForm" action="#" method="GET" class="d-flex mb-3">
                        <input type="text" class="form-control search-input" id="searchPromotionDescription"
                            name="searchPromotionDescription" placeholder="Tìm kiếm khuyến mại...">
                        <button type="submit" class="btn btn-primary ms-2">Tìm kiếm</button>
                    </form>
                </div>
            </div>
            <div class="panel-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Danh Sách Khuyến Mại</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mã Khuyến Mại</th>
                                    <th>Mô Tả</th>
                                    <th>Phần Trăm Giảm Giá</th>
                                    <th>Ngày Bắt Đầu Áp Dụng</th>
                                    <th>Ngày Hết Hạn</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $numberOrder = 1;
                                @endphp
                                @foreach ($promotions as $promotion)
                                    <tr>
                                        <td>{{ $numberOrder++ }}</td>
                                        <td>{{ $promotion['code'] }}</td>
                                        <td>{{ $promotion['description'] }}</td>
                                        <td>{{ $promotion['discountPercentage'] }}</td>
                                        <td>{{ $promotion['startDate'] }}</td>
                                        <td>{{ $promotion['endDate'] }}</td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm action-icons" title="Xem"><i
                                                    class="fas fa-eye"></i></a>
                                            <a href="#" class="btn btn-warning btn-sm action-icons"
                                                data-bs-toggle="modal" data-bs-target="#updatePromotionModal"
                                                data-code="{{ $promotion['code'] }}"
                                                data-description="{{ $promotion['description'] }}"
                                                data-discount="{{ $promotion['discountPercentage'] }}"
                                                data-start-date="{{ $promotion['startDate'] }}"
                                                data-end-date="{{ $promotion['endDate'] }}" title="Sửa"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-danger btn-sm action-icons" title="Xóa"
                                                onclick="confirmDelete('{{ route('promotion.delete', ['id' => $promotion['id'] ]) }}')"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Promotion Modal -->
    <div class="modal fade" id="addPromotionModal" tabindex="-1" aria-labelledby="addPromotionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPromotionModalLabel">Thêm Khuyến Mại Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPromotionForm" action="{{ route('promotion.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="promotionCode" class="form-label">Mã Khuyến Mại</label>
                            <input type="text" class="form-control" id="promotionCode" name="promotionCode" required>
                        </div>
                        <div class="mb-3">
                            <label for="promotionDescription" class="form-label">Mô Tả</label>
                            <input type="text" class="form-control" id="promotionDescription" name="promotionDescription"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="promotionDiscount" class="form-label">Phần Trăm Giảm Giá</label>
                            <input type="text" class="form-control" id="promotionDiscount" name="promotionDiscount"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="promotionStartDate" class="form-label">Ngày Bắt Đầu Áp Dụng</label>
                            <input type="date" class="form-control" id="promotionStartDate" name="promotionStartDate"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="promotionEndDate" class="form-label">Ngày Hết Hạn</label>
                            <input type="date" class="form-control" id="promotionEndDate" name="promotionEndDate"
                                required>
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

    <!-- Update Promotion Modal -->
    <div class="modal fade" id="updatePromotionModal" tabindex="-1" aria-labelledby="updatePromotionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePromotionModalLabel">Cập Nhật Khuyến Mại</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updatePromotionForm" action="{{ route('promotion.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="updatePromotionCode" name="promotionCode">
                        <div class="mb-3">
                            <label for="updatePromotionDescription" class="form-label">Mô Tả</label>
                            <input type="text" class="form-control" id="updatePromotionDescription"
                                name="promotionDescription" required>
                        </div>
                        <div class="mb-3">
                            <label for="updatePromotionDiscount" class="form-label">Phần Trăm Giảm Giá</label>
                            <input type="text" class="form-control" id="updatePromotionDiscount"
                                name="promotionDiscount" required>
                        </div>
                        <div class="mb-3">
                            <label for="updatePromotionStartDate" class="form-label">Ngày Bắt Đầu Áp Dụng</label>
                            <input type="date" class="form-control" id="updatePromotionStartDate"
                                name="promotionStartDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="updatePromotionEndDate" class="form-label">Ngày Hết Hạn</label>
                            <input type="date" class="form-control" id="updatePromotionEndDate"
                                name="promotionEndDate" required>
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
            var updatePromotionModal = document.getElementById('updatePromotionModal');
            updatePromotionModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var code = button.getAttribute('data-code');
                var description = button.getAttribute('data-description');
                var discount = button.getAttribute('data-discount');
                var startDate = button.getAttribute('data-start-date');
                var endDate = button.getAttribute('data-end-date');

                var promotionCodeInput = updatePromotionModal.querySelector('#updatePromotionCode');
                var promotionDescriptionInput = updatePromotionModal.querySelector(
                    '#updatePromotionDescription');
                var promotionDiscountInput = updatePromotionModal.querySelector('#updatePromotionDiscount');
                var promotionStartDateInput = updatePromotionModal.querySelector(
                    '#updatePromotionStartDate');
                var promotionEndDateInput = updatePromotionModal.querySelector('#updatePromotionEndDate');

                promotionCodeInput.value = code;
                promotionDescriptionInput.value = description;
                promotionDiscountInput.value = discount;
                promotionStartDateInput.value = startDate;
                promotionEndDateInput.value = endDate;

                //Update form action dynamically
                var formAction = document.getElementById('updatePromotionForm').action;
            });
        });

        function confirmDelete(url) {
            if (confirm('Bạn có chắc chắn muốn xóa khuyến mại này không?')) {
                window.location.href = url;
            }
        }
    </script>
@endsection
