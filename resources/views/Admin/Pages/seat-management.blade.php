@extends('Admin.Layouts.admin')

@section('content')
    <!-- CSS and JS for Modal -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .action-icons {
            font-size: 0.9rem;
            /* Kích thước biểu tượng nhỏ hơn */
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            /* Padding nhỏ hơn */
            font-size: 0.75rem;
            /* Kích thước chữ nhỏ hơn */
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

        .seat-map {
            display: grid;
            grid-template-columns: repeat(4, 50px);
            grid-template-rows: auto;
            gap: 10px;
        }

        .seat {
            width: 50px;
            height: 50px;
            background-color: lightblue;
            border: 1px solid black;
            text-align: center;
            line-height: 50px;
            cursor: pointer;
        }

        .seat.selected {
            background-color: gray;
        }

        .seat.empty {
            background-color: white;
            border: 1px solid white;
            cursor: not-allowed;
        }
    </style>

    <!-- Content -->
    <div class="container mt-4 content-wrapper">
        <div class="panel">
            <div class="panel-header">
                <div class="d-flex justify-content-between mb-3">
                    {{-- <div>
                        <input type="text" class="form-control search-input" placeholder="Tìm kiếm xe...">
                    </div> --}}
                </div>
            </div>
            <div class="panel-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Danh Sách Sơ Đồ Chỗ Ngồi</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Biển số xe</th>
                                    <th>Loại xe</th>
                                    <th>Số ghế</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $numberOrder = 1;
                                    $index = 0;
                                @endphp
                                @foreach ($cars as $car)
                                    <tr>
                                        <td>{{ $numberOrder++ }}</td>
                                        <td>{{ $car['licensePlate'] }}</td>
                                        <td>{{ $car['carType']['name'] }}</td>
                                        <td>{{ $car['numberOfSeats'] }}</td>
                                        <td>
                                            @if (count($seatsMap[$index++]) > 1)
                                                <a href="{{ route('seats-map', ['carId' => $car['carId']]) }}"
                                                    class="btn btn-info btn-sm action-icons" title="Xem">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('seats-map', ['carId' => $car['carId']]) }}"
                                                    class="btn btn-primary btn-sm action-icons" title="Tạo Sơ Đồ Chỗ Ngồi">
                                                    <i class="fas fa-plus"></i>>
                                                </a>
                                            @endif
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

    <!-- JavaScript for Modal -->
    <script>
        function confirmDelete(url) {
            if (confirm('Bạn có chắc chắn muốn xóa sơ đồ chỗ ngồi này?')) {
                window.location.href = url;
            }
        }
    </script>
@endsection
