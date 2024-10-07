@extends('Admin.Layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="mb-0" style="color: red">Thông tin sơ đồ</h5>
                    </div>
                    <div class="card-body">
                        <form id="seatForm" class="form-container">
                            <div class="mb-3">
                                <label for="rows" class="form-label">Số hàng (tối đa 10)</label>
                                <input type="number" class="form-control" id="rows" name="rows" min="1"
                                    max="10" value="{{ count($seatMap) > 1 ? count($seatMap) : '' }}"
                                    {{ count($seatMap) > 1 ? 'readonly' : '' }} required>
                            </div>
                            <div class="mb-3">
                                <label for="columns" class="form-label">Số cột (tối đa 10)</label>
                                <input type="number" class="form-control" id="columns" name="columns" min="1"
                                    max="10" value="{{ count($seatMap) > 1 ? count($seatMap[0]) : '' }}"
                                    {{ count($seatMap) > 1 ? 'readonly' : '' }} required>
                            </div>
                            <button type="button" class="btn btn-primary w-100" onclick="generateSeats()">Tạo sơ
                                đồ</button>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-gradient-info text-white">
                        <h5 class="mb-0" style="color: red">Thông tin xe</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="vehicleId" class="form-label">ID</label>
                            <input type="text" class="form-control bg-light" id="vehicleId" value="{{ $car['carId'] }}"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label for="licensePlate" class="form-label">Biển số xe</label>
                            <input type="text" class="form-control bg-light" id="licensePlate"
                                value="{{ $car['licensePlate'] }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="vehicleType" class="form-label">Loại xe</label>
                            <input type="text" class="form-control bg-light" id="vehicleType"
                                value="{{ $car['carType']['name'] }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="totalSeats" class="form-label">Số ghế</label>
                            <input type="text" class="form-control bg-light" id="totalSeats"
                                value="{{ $car['numberOfSeats'] }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-gradient-danger text-white">
                        <h5 class="mb-0 text-center" style="color: red">Sơ đồ chỗ ngồi</h5>
                    </div>
                    <div class="card-body">
                        <div id="seatMap" class="d-flex flex-column align-items-center mb-4">
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
                                <p class="text-muted">Chưa có sơ đồ chỗ ngồi.</p>
                            @endif
                        </div>

                        <form id="seatsForm" action="{{ route('seats.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="seats" id="seatData">
                            <input type="hidden" name="carId" value="{{ $car['carId'] }}">
                            <button type="submit" class="btn btn-success w-100">Lưu sơ đồ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2) !important;
        }

        .card-header {
            border-bottom: none;
            padding: 1.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .form-control:read-only {
            background-color: #ffffff;
            border-color: #4e73df;
            color: #4e73df;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
            transform: translateY(-1px);
        }

        .seat {
            width: 60px;
            height: 60px;
            margin: 8px;
            background-color: #f8f9fa;
            border: 3px solid #4e73df;
            border-radius: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            font-size: 1.2rem;
            color: #4e73df;
        }

        .seat.selected {
            background-color: #4e73df;
            color: white;
            border-color: #2e59d9;
            transform: scale(1.05);
        }

        .seat-row {
            display: flex;
            justify-content: center;
        }

        .form-label {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
    </style>

    <script>
        const totalSeats = parseInt(document.getElementById('totalSeats').value);

        function generateSeats() {
            const rows = Math.min(document.getElementById('rows').value, 10);
            const columns = Math.min(document.getElementById('columns').value, 10);
            const seatMap = document.getElementById('seatMap');
            seatMap.innerHTML = '';

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

        document.getElementById('seatsForm').addEventListener('submit', function(event) {
            const seatMatrix = getSeatMatrix();
            const numberOfSeats = seatMatrix.flat().filter(seat => seat !== '').length;

            if (numberOfSeats > totalSeats) {
                alert('Số ghế đã chọn vượt quá số ghế tối đa.');
                event.preventDefault();
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
                    rowArray.push(cols[j].textContent.trim() || '');
                }
                matrix.push(rowArray);
            }

            return matrix;
        }
    </script>
@endsection
