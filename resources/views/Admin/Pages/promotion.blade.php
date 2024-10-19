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
            background-color: #f8f9fa;
        }

        .panel {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .panel-header {
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 15px;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }
    </style>

    <!-- Content -->
    <div class="container-fluid content-wrapper">
        <div class="panel">
            <div class="panel-header">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Quản Lý Khuyến Mãi</h4>
                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
                            <i class="fas fa-plus me-2"></i>Thêm Mới
                        </button>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Tìm Kiếm Nâng Cao</h5>
                    </div>
                    <div class="card-body">
                        <form id="searchPromotionForm" action="{{ route('promotion.search') }}" method="GET">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="searchPromotionCode" class="form-label">Mã Khuyến Mãi</label>
                                    <input type="text" class="form-control" id="searchPromotionCode" name="searchPromotionCode"
                                        value="{{ $searchPromotionCode ?? '' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="searchStartDate" class="form-label">Ngày Bắt Đầu</label>
                                    <input type="date" class="form-control" id="searchStartDate" name="searchStartDate">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="searchEndDate" class="form-label">Ngày Kết Thúc</label>
                                    <input type="date" class="form-control" id="searchEndDate" name="searchEndDate">
                                </div>
                            </div>
                            <div class="d-flex justify-content-start">
                                <button type="submit" class="btn btn-primary me-2">Tìm kiếm</button>
                                <button type="button" class="btn btn-secondary" id="resetSearchForm">Xóa bộ lọc</button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- <div class="d-flex justify-content-end mb-3">
                    <a href="#" class="btn btn-success btn-export me-2"><i class="fas fa-file-excel me-2"></i>Excel</a>
                    <a href="#" class="btn btn-danger btn-export me-2"><i class="fas fa-file-pdf me-2"></i>PDF</a>
                    <a href="#" class="btn btn-info btn-export"><i class="fas fa-print me-2"></i>In</a>
                </div> --}}
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã Khuyến Mãi</th>
                                <th>Mô Tả</th>
                                <th>Phần Trăm Giảm Giá</th>
                                <th>Ngày Bắt Đầu</th>
                                <th>Ngày Kết Thúc</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($promotions as $index => $promotion)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $promotion['code'] }}</td>
                                    <td>{{ $promotion['description'] }}</td>
                                    <td><span class="badge bg-success">{{ $promotion['discountPercentage'] }}%</span></td>
                                    <td>{{ $promotion['startDate'] }}</td>
                                    <td>{{ $promotion['endDate'] }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info action-icons" title="Xem"><i
                                                class="fas fa-eye"></i></a>
                                        <a href="#" class="btn btn-sm btn-warning action-icons" data-bs-toggle="modal"
                                            data-bs-target="#updatePromotionModal" data-code="{{ $promotion['code'] }}"
                                            data-description="{{ $promotion['description'] }}"
                                            data-discount="{{ $promotion['discountPercentage'] }}"
                                            data-start-date="{{ $promotion['startDate'] }}"
                                            data-end-date="{{ $promotion['endDate'] }}" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger action-icons" title="Xóa"
                                            onclick="confirmDelete('{{ route('promotion.delete', ['id' => $promotion['id']]) }}')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                    <h5 class="modal-title" id="addPromotionModalLabel">Thêm Khuyến Mãi Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPromotionForm" action="{{ route('promotion.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="promotionCode" class="form-label">Mã Khuyến Mãi</label>
                            <input type="text" class="form-control" id="promotionCode" name="promotionCode" required>
                        </div>
                        <div class="mb-3">
                            <label for="promotionDescription" class="form-label">Mô Tả</label>
                            <input type="text" class="form-control" id="promotionDescription" name="promotionDescription"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="promotionDiscount" class="form-label">Phần Trăm Giảm Giá</label>
                            <input type="number" class="form-control" id="promotionDiscount" name="promotionDiscount"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="promotionStartDate" class="form-label">Ngày Bắt Đầu</label>
                            <input type="date" class="form-control" id="promotionStartDate" name="promotionStartDate"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="promotionEndDate" class="form-label">Ngày Kết Thúc</label>
                            <input type="date" class="form-control" id="promotionEndDate" name="promotionEndDate"
                                required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
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
                    <h5 class="modal-title" id="updatePromotionModalLabel">Cập Nhật Khuyến Mãi</h5>
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
                            <input type="number" class="form-control" id="updatePromotionDiscount"
                                name="promotionDiscount" required>
                        </div>
                        <div class="mb-3">
                            <label for="updatePromotionStartDate" class="form-label">Ngày Bắt Đầu</label>
                            <input type="date" class="form-control" id="updatePromotionStartDate"
                                name="promotionStartDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="updatePromotionEndDate" class="form-label">Ngày Kết Thúc</label>
                            <input type="date" class="form-control" id="updatePromotionEndDate"
                                name="promotionEndDate" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Cập Nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];

            // Hàm để cập nhật các ràng buộc ngày
            function updateDateConstraints(startDateInput, endDateInput) {
                endDateInput.min = startDateInput.value;

                startDateInput.addEventListener('change', function() {
                    endDateInput.min = this.value;
                    if (endDateInput.value && endDateInput.value < this.value) {
                        endDateInput.value = this.value;
                    }
                });
            }

            // Cập nhật cho modal thêm mới
            const addStartDate = document.getElementById('promotionStartDate');
            const addEndDate = document.getElementById('promotionEndDate');
            addStartDate.value = today;
            addEndDate.value = today;
            updateDateConstraints(addStartDate, addEndDate);

            // Cập nhật cho modal sửa
            var updatePromotionModal = document.getElementById('updatePromotionModal');
            updatePromotionModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var code = button.getAttribute('data-code');
                var description = button.getAttribute('data-description');
                var discount = button.getAttribute('data-discount');
                var startDate = button.getAttribute('data-start-date');
                var endDate = button.getAttribute('data-end-date');

                var promotionCodeInput = updatePromotionModal.querySelector('#updatePromotionCode');
                var promotionDescriptionInput = updatePromotionModal.querySelector('#updatePromotionDescription');
                var promotionDiscountInput = updatePromotionModal.querySelector('#updatePromotionDiscount');
                var promotionStartDateInput = updatePromotionModal.querySelector('#updatePromotionStartDate');
                var promotionEndDateInput = updatePromotionModal.querySelector('#updatePromotionEndDate');

                promotionCodeInput.value = code;
                promotionDescriptionInput.value = description;
                promotionDiscountInput.value = discount;
                promotionStartDateInput.value = startDate;
                promotionEndDateInput.value = endDate;

                updateDateConstraints(promotionStartDateInput, promotionEndDateInput);
            });

            // Xử lý reset form tìm kiếm
            document.getElementById('resetSearchForm').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('searchPromotionCode').value = '';
                document.getElementById('searchStartDate').value = '';
                document.getElementById('searchEndDate').value = '';
                document.getElementById('searchPromotionForm').submit();
            });

            // Cập nhật cho form tìm kiếm
            const searchStartDate = document.getElementById('searchStartDate');
            const searchEndDate = document.getElementById('searchEndDate');
            
            // Chỉ áp dụng ràng buộc ngày, không đặt giá trị mặc định
            updateDateConstraints(searchStartDate, searchEndDate);
        });

        function confirmDelete(url) {
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa?',
                text: "Bạn không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }
    </script>
@endsection