@extends('Admin.Layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <h2 class="text-center mb-4">Báo Cáo Thống Kê</h2>

        <!-- Thống kê nhanh -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card bg-primary text-white shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Số Vé Đã Đặt Hôm Nay</div>
                                <div class="h3 mb-0 font-weight-bold">{{ $nonCancelled }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ticket-alt fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card bg-danger text-white shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Số Vé Đã Hủy Hôm Nay</div>
                                <div class="h3 mb-0 font-weight-bold">{{ $cancelled }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ban fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card bg-warning text-white shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Tổng Doanh Thu Hôm Nay</div>
                                <div class="h3 mb-0 font-weight-bold">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <!-- Biểu đồ doanh thu -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Doanh Thu Theo Tháng</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div> --}}

        <!-- Tìm kiếm nâng cao và thống kê doanh thu -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Thống Kê Doanh Thu</h5>
                <div>
                    <button class="btn btn-primary btn-sm" onclick="exportPDF()">Xuất PDF</button>
                    <button class="btn btn-success btn-sm" onclick="exportExcel()">Xuất Excel</button>
                    <button class="btn btn-warning btn-sm" onclick="exportWord()">Xuất Word</button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('report.search', ['page' => 1]) }}" method="GET">
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-md-2">
                            <label for="startDate" class="form-label">Từ Ngày:</label>
                            <input type="date" class="form-control" id="startDate" name="startDate"
                                value="{{ $search ? $startDate : date('Y-m-d') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="endDate" class="form-label">Đến Ngày:</label>
                            <input type="date" class="form-control" id="endDate" name="endDate"
                                value="{{ $search ? $endDate : date('Y-m-d') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="transactionStatus" class="form-label">Trạng Thái:</label>
                            <select class="form-select" id="transactionStatus" name="transactionStatus">
                                <option value="Tất cả">Tất cả</option>
                                <option value="Completed">Hoàn Tất</option>
                                <option value="Cancelled">Đã Hủy</option>
                                <option value="Confirmed">Đã đặt</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="customerName" class="form-label">Khách Hàng:</label>
                            <input type="text" class="form-control" id="customerName" name="customerName"
                                placeholder="Tên khách hàng" value="{{ $search ? $customerName : '' }}">
                        </div>
                        <div class="col-md-2">
                            <label for="trip" class="form-label">Chuyến Đi:</label>
                            <select class="form-select" id="trip" name="trip">
                                <option value="Tất cả">Tất cả</option>
                                @foreach ($trips as $trip)
                                    <option value="{{ $trip }}">{{ $trip }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-primary w-100">Tìm Kiếm</button>
                        </div>
                    </div>
                </form>

                <!-- Bảng thống kê doanh thu -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>STT</th>
                                <th>Khách hàng</th>
                                <th>Chuyến</th>
                                <th>Số vé đã đặt</th>
                                <th>Ngày đặt</th>
                                <th>Ngày đi</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $numberOrder = ($currentPage - 1) * $pageSize + 1;
                            @endphp
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td>{{ $numberOrder++ }}</td>
                                    <td>{{ $booking['customerName'] }}</td>
                                    <td>{{ $booking['trip'] }}</td>
                                    <td>{{ $booking['ticketCount'] == 0 ? ' - ' : $booking['ticketCount'] }}</td>
                                    <td>{{ $booking['bookingDate'] }}</td>
                                    <td>{{ $booking['departureDate'] }}</td>
                                    <td>{{ $booking['totalAmount'] == 0 ? '-' : number_format($booking['totalAmount'], 0, ',', '.') . ' VNĐ' }}
                                    </td>
                                    <td>
                                        @if (strtolower($booking['status']) == 'confirmed')
                                            <span class="badge bg-success">Đã đặt</span>
                                        @elseif (strtolower($booking['status']) == 'cancelled')
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @elseif (strtolower($booking['status']) == 'completed')
                                            <span class="badge bg-info">Hoàn tất</span>
                                        @else
                                            <span class="badge bg-secondary">Không xác định</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        @if ($currentPage > 1)
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ $search == false ? route('report', ['page' => $currentPage - 1]) : route('report.search', ['page' => $currentPage - 1, 'startDate' => $startDate, 'endDate' => $endDate, 'transactionStatus' => $transactionStatus, 'customerName' => $customerName, 'trip' => $tripSelected]) }}">Trước</a>
                            </li>
                        @endif

                        @for ($i = 1; $i <= $totalPages; $i++)
                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ $search == false ? route('report', ['page' => $i]) : route('report.search', ['page' => $i, 'startDate' => $startDate, 'endDate' => $endDate, 'transactionStatus' => $transactionStatus, 'customerName' => $customerName, 'trip' => $tripSelected]) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($currentPage < $totalPages)
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ $search == false ? route('report', ['page' => $currentPage + 1]) : route('report.search', ['page' => $currentPage + 1, 'startDate' => $startDate, 'endDate' => $endDate, 'transactionStatus' => $transactionStatus, 'customerName' => $customerName, 'trip' => $tripSelected]) }}">Sau</a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Dữ liệu cho biểu đồ
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4'],
                datasets: [{
                    label: 'Doanh Thu (VNĐ)',
                    data: [15000000, 20000000, 25000000, 30000000],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Các hàm xuất báo cáo
        function exportPDF() {
            alert('Chức năng xuất PDF đang được phát triển');
        }

        function exportExcel() {
            alert('Chức năng xuất Excel đang được phát triển');
        }

        function exportWord() {
            alert('Chức năng xuất Word đang được phát triển');
        }

        // Đặt giá trị cho các select box
        document.addEventListener('DOMContentLoaded', function() {
            const transactionStatus = "{{ $transactionStatus ?? 'Tất cả' }}";
            const tripSelected = "{{ $tripSelected ?? 'Tất cả' }}";

            document.getElementById('transactionStatus').value = transactionStatus;
            document.getElementById('trip').value = tripSelected;
        });
    </script>
@endsection
