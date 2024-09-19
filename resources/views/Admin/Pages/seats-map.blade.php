@extends('Admin.Layouts.admin')

@section('content')
    <!-- CSS and JS for Modal -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .seat {
            width: 40px;
            height: 40px;
            margin: 5px;
            background-color: white;
            border: 1px solid #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .seat.selected {
            background-color: #007bff;
            color: white;
        }

        .seat-row {
            display: flex;
            justify-content: center;
        }

        .panel {
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 10px;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .form-container {
            margin-bottom: 20px;
        }

        .info-panel {
            margin-bottom: 20px;
        }

        .header-title {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .readonly-input {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            color: #495057;
            cursor: not-allowed;
        }
    </style>

    <div class="container mt-5">
        <div class="row">
            <!-- Form nhập số hàng và số cột -->
            <div class="col-md-4">
                <div class="panel">
                    <h4 class="mb-4">Nhập thông tin</h4>
                    <form id="seatForm" class="form-container">
                        @if (count($seatMap) > 1)
                            @php
                                $rows = count($seatMap);
                                $columns = count($seatMap[0]);
                            @endphp
                            <div class="mb-3">
                                <label for="rows" class="form-label">Số hàng</label>
                                <input type="number" class="form-control" id="rows" name="rows" min="1"
                                    value="{{ $rows }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="columns" class="form-label">Số cột</label>
                                <input type="number" class="form-control" id="columns" name="columns" min="1"
                                    value="{{ $columns }}" readonly>
                            </div>
                        @else
                            <div class="mb-3">
                                <label for="rows" class="form-label">Số hàng</label>
                                <input type="number" class="form-control" id="rows" name="rows" min="1"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="columns" class="form-label">Số cột</label>
                                <input type="number" class="form-control" id="columns" name="columns" min="1"
                                    required>
                            </div>
                        @endif
                        <button type="button" class="btn-custom" onclick="generateSeats()">Tạo sơ đồ</button>
                    </form>
                </div>

                <!-- Thông tin về xe -->
                <div class="info-panel panel">
                    <h4 class="mb-4">Thông tin xe</h4>
                    <form>
                        <div class="mb-3">
                            <label for="vehicleId" class="form-label">ID</label>
                            <input type="text" class="form-control readonly-input" id="vehicleId"
                                value="{{ $car['carId'] }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="licensePlate" class="form-label">Biển số xe</label>
                            <input type="text" class="form-control readonly-input" id="licensePlate"
                                value="{{ $car['licensePlate'] }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="vehicleType" class="form-label">Loại xe</label>
                            <input type="text" class="form-control readonly-input" id="vehicleType"
                                value="{{ $car['carType']['name'] }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="totalSeats" class="form-label">Số ghế</label>
                            <input type="text" class="form-control readonly-input" id="totalSeats"
                                value="{{ $car['numberOfSeats'] }}" readonly>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sơ đồ chỗ ngồi -->
            <div class="col-md-8">
                <div class="header-title">Sơ đồ chỗ ngồi</div>
                <div class="panel">
                    <h4 class="mb-4">Seat Preview</h4>
                    <div id="seatMap" class="d-flex flex-column align-items-center">
                        @if (count($seatMap) > 0)
                            @foreach ($seatMap as $row)
                                <div class="seat-row">
                                    @foreach ($row as $seat)
                                        <div class="seat @if ($seat !== '') selected @endif">
                                            {{ $seat }}
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @else
                            <p>Chưa có sơ đồ chỗ ngồi.</p>
                        @endif
                    </div>

                    <!-- Form gửi dữ liệu -->
                    <form id="seatsForm" action="{{ route('seats.create') }}" method="POST">
                        @csrf <!-- Bảo vệ CSRF -->
                        <input type="hidden" name="seats" id="seatData">
                        <input type="hidden" name="carId" value="{{ $car['carId'] }}">
                        <button type="submit" class="btn-custom mt-4">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Lưu tổng số ghế từ thông tin xe
        const totalSeats = parseInt(document.getElementById('totalSeats').value);

        function generateSeats() {
            const rows = document.getElementById('rows').value;
            const columns = document.getElementById('columns').value;
            const seatMap = document.getElementById('seatMap');
            seatMap.innerHTML = ''; // Xóa sơ đồ trước đó

            for (let i = 0; i < rows; i++) {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'seat-row';

                for (let j = 0; j < columns; j++) {
                    const seatDiv = document.createElement('div');
                    seatDiv.className = 'seat';
                    seatDiv.contentEditable = true;

                    seatDiv.oninput = function() {
                        const seatNumber = seatDiv.textContent.trim();
                        if (seatNumber) {
                            seatDiv.classList.add('selected');
                        } else {
                            seatDiv.classList.remove('selected');
                        }
                    };

                    rowDiv.appendChild(seatDiv);
                }
                seatMap.appendChild(rowDiv);
            }
        }

        // Trước khi form được submit, chuyển sơ đồ chỗ ngồi thành JSON
        document.getElementById('seatsForm').addEventListener('submit', function(event) {
            const seatMatrix = getSeatMatrix();
            const numberOfSeats = seatMatrix.flat().filter(seat => seat !== '').length;

            console.log(numberOfSeats + " " + totalSeats);
            if (numberOfSeats > totalSeats) {
                alert('Số ghế đã chọn vượt quá số ghế tối đa.');
                event.preventDefault(); // Ngăn chặn gửi form
                return;
            }

            document.getElementById('seatData').value = JSON.stringify(seatMatrix);
        });

        function getSeatMatrix() {
            const seatMap = document.getElementById('seatMap');
            const rows = seatMap.children;
            const matrix = [];

            for (let i = 0; i < rows.length; i++) {
                const cols = rows[i].children;
                const rowArray = [];

                for (let j = 0; j < cols.length; j++) {
                    rowArray.push(cols[j].textContent.trim() || ''); // Lấy mã ghế hoặc để trống
                }
                matrix.push(rowArray);
            }

            return matrix;
        }
    </script>
@endsection
