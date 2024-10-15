@extends('Admin.Layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center mb-4">Quản lý Xe</h1>
        <div class="d-flex justify-content-between mb-3">
            <button id="addCarBtn" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm Xe Mới
            </button>
            <div>
                <button id="exportExcelBtn" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Xuất Excel
                </button>
                <button id="exportPdfBtn" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Xuất PDF
                </button>
                <button id="printBtn" class="btn btn-info">
                    <i class="bi bi-printer"></i> In
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="carTable" class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>STT</th>
                        <th>Biển số xe</th>
                        <th>Loại xe</th>
                        <th>Số ghế</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Ngày cập nhật</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="carTableBody"></tbody>
            </table>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item" id="prevPageItem">
                    <a class="page-link" href="#" id="prevPage">Trang trước</a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link" href="#" id="currentPage"></a>
                </li>
                <li class="page-item" id="nextPageItem">
                    <a class="page-link" href="#" id="nextPage">Trang sau</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Modal for Add/Edit Car -->
    <div class="modal fade" id="carModal" tabindex="-1" aria-labelledby="carModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="carModalLabel">Thêm Xe Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="carForm">
                        <input type="hidden" id="carId">
                        <div class="mb-3">
                            <label for="licensePlate" class="form-label">Biển số xe:</label>
                            <input type="text" class="form-control" id="licensePlate" required>
                        </div>
                        <div class="mb-3">
                            <label for="carType" class="form-label">Loại xe:</label>
                            <select class="form-select" id="carType" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="numberOfSeats" class="form-label">Số ghế:</label>
                            <input type="number" class="form-control" id="numberOfSeats" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái:</label>
                            <select class="form-select" id="status" required>
                                <option value="Hoạt động">Hoạt động</option>
                                <option value="Bảo trì">Bảo trì</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="saveCarBtn">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
@endsection
