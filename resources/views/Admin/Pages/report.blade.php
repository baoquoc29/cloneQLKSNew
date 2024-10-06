@extends('Admin.Layouts.admin')

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4 text-primary text-center">Báo Cáo Thống Kê</h2>

        <!-- Thống kê nhanh -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-body text-center">
                        <h5 class="card-title">Số Vé Đã Đặt Hôm Nay</h5>
                        <p class="card-text">{{ $nonCancelled }} Vé</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-body text-center">
                        <h5 class="card-title">Số Vé Đã Hủy Hôm Nay</h5>
                        <p class="card-text">{{ $cancelled }} vé</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body text-center">
                        <h5 class="card-title">Tổng Doanh Thu Hôm Nay</h5>
                        <p class="card-text">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ doanh thu -->
        {{-- <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Doanh Thu Theo Tháng</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div> --}}

        <!-- Tìm kiếm nâng cao và thống kê doanh thu -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Thống Kê Doanh Thu</h5>
                <div>
                    <form action="#" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">Xuất File PDF</button>
                    </form>

                    <form action="{{ route('report.excel') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Xuất File Excel</button>
                    </form>
    
                    <form action="#" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">Xuất File Word</button>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <!-- Form tìm kiếm nâng cao -->
                <form action="{{ route('report.search', ['page' => 1]) }}" method="GET" class="form-inline mb-4">
                    <div class="form-row w-100 align-items-end">
                        <div class="form-group col-md-2 mb-2">
                            <label for="startDate" class="mr-2">Từ Ngày:</label>
                            <input type="date" class="form-control" id="startDate" name="startDate"
                                value="{{ $search ? $startDate : date('Y-m-d') }}">
                        </div>

                        <div class="form-group col-md-2 mb-2">
                            <label for="endDate" class="mr-2">Đến Ngày:</label>
                            <input type="date" class="form-control" id="endDate" name="endDate"
                                value="{{ $search ? $endDate : date('Y-m-d') }}">
                        </div>

                        <div class="form-group col-md-3 mb-2">
                            <label for="transactionStatus" class="mr-2">Trạng Thái Giao Dịch:</label>
                            <select class="form-control" id="transactionStatus" name="transactionStatus">
                                <option value="Tất cả">Tất cả</option>
                                <option value="Completed">Hoàn Tất</option>
                                <option value="Cancelled">Đã Hủy</option>
                                <option value="Confirmed">Đã đặt</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3 mb-2">
                            <label for="customerName" class="mr-2">Khách Hàng:</label>
                            <input type="text" class="form-control" id="customerName" name="customerName"
                                placeholder="Nhập tên khách hàng" value="{{ $search ? $customerName : '' }}">
                        </div>

                        <div class="form-group col-md-2 mb-2">
                            <label for="trip" class="mr-2">Chuyến Đi:</label>
                            <select class="form-control" id="trip" name="trip">
                                <option value="Tất cả">Tất cả</option>
                                @foreach ($trips as $trip)
                                    <option value="{{ $trip }}">{{ $trip }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-1 mb-2">
                            <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bảng thống kê doanh thu -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Bảng Thống Kê Doanh Thu</h5>
                <h6>Tổng Tiền: {{ number_format($totalPrice, 0, ',', '.') }} VNĐ</h6>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
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
                                        Đã đặt
                                    @elseif (strtolower($booking['status']) == 'cancelled')
                                        Đã hủy
                                    @elseif (strtolower($booking['status']) == 'completed')
                                        Đã hoàn thành
                                    @else
                                        Trạng thái không xác định
                                    @endif
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
                                        ? route('report', ['page' => $currentPage - 1])
                                        : route('report.search', [
                                            'page' => $currentPage - 1,
                                            'startDate' => $startDate,
                                            'endDate' => $endDate,
                                            'transactionStatus' => $transactionStatus,
                                            'customerName' => $customerName,
                                            'trip' => $tripSelected,
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
                                        ? route('report', ['page' => $i])
                                        : route('report.search', [
                                            'page' => $i,
                                            'startDate' => $startDate,
                                            'endDate' => $endDate,
                                            'transactionStatus' => $transactionStatus,
                                            'customerName' => $customerName,
                                            'trip' => $tripSelected,
                                        ]) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($currentPage < $totalPages)
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ $search == false
                                        ? route('report', ['page' => $currentPage + 1])
                                        : route('report.search', [
                                            'page' => $currentPage + 1,
                                            'startDate' => $startDate,
                                            'endDate' => $endDate,
                                            'transactionStatus' => $transactionStatus,
                                            'customerName' => $customerName,
                                            'trip' => $tripSelected,
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
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy giá trị của transactionStatus từ server-side (Blade PHP)
            const transactionStatus = "{{ $transactionStatus ?? 'Tất cả' }}";

            // Lấy phần tử select từ DOM
            const transactionStatusSelect = document.getElementById('transactionStatus');

            // Duyệt qua tất cả các option trong select
            for (let i = 0; i < transactionStatusSelect.options.length; i++) {
                // Kiểm tra nếu option nào có giá trị bằng với $transactionStatus
                if (transactionStatusSelect.options[i].value === transactionStatus) {
                    // Đặt option đó là selected
                    transactionStatusSelect.selectedIndex = i;
                    break;
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Lấy giá trị của tripSelected từ server-side (Blade PHP)
            const tripSelected = "{{ $tripSelected ?? 'Tất cả' }}";

            // Lấy phần tử select từ DOM
            const tripSelect = document.getElementById('trip');

            // Duyệt qua tất cả các option trong select
            for (let i = 0; i < tripSelect.options.length; i++) {
                // Kiểm tra nếu option nào có giá trị bằng với tripSelected
                if (tripSelect.options[i].value === tripSelected) {
                    // Đặt option đó là selected
                    tripSelect.selectedIndex = i;
                    break;
                }
            }
        });
    </script>
@endsection
