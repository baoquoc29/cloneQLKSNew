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
                                <label for="rows" class="form-label">Số hàng (tối đa 8)</label>
                                <input type="number" class="form-control" id="rows" name="rows" min="1"
                                    max="8" value="{{ count($seatMap) > 1 ? count($seatMap) : '' }}"
                                    {{ count($seatMap) > 1 ? 'readonly' : '' }} required>
                            </div>
                            <div class="mb-3">
                                <label for="columns" class="form-label">Số cột (tối đa 8)</label>
                                <input type="number" class="form-control" id="columns" name="columns" min="1"
                                    max="8" value="{{ count($seatMap) > 1 ? count($seatMap[0]) : '' }}"
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

        .seat.duplicate {
            border-color: red;
        }
    </style>

    <script>
        const totalSeats = parseInt(document.getElementById('totalSeats').value);

        function generateSeats() {
            const rows = Math.min(document.getElementById('rows').value, 8);
            const columns = Math.min(document.getElementById('columns').value, 8);
            const seatMap = document.getElementById('seatMap');
            seatMap.innerHTML = '';

            for (let i = 0; i < rows; i++) {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'seat-row';

                for (let j = 0; j < columns; j++) {
                    const seatDiv = document.createElement('div');
                    seatDiv.className = 'seat';
                    seatDiv.contentEditable = true;

                    seatDiv.addEventListener('input', function(e) {
                        let seatNumber = this.textContent.trim().toUpperCase();
                        if (seatNumber.length > 3) {
                            seatNumber = seatNumber.slice(0, 3);
                        }
                        this.textContent = seatNumber;
                        
                        if (seatNumber) {
                            this.classList.add('selected');
                            if (isDuplicateSeat(seatNumber, this)) {
                                this.classList.add('duplicate');
                                alert('Mã ghế này đã tồn tại. Vui lòng nhập mã khác.');
                                disableOtherSeats(this);
                            } else {
                                this.classList.remove('duplicate');
                                enableAllSeats();
                            }
                        } else {
                            this.classList.remove('selected', 'duplicate');
                            enableAllSeats();
                        }

                        // Đặt con trỏ ở cuối nội dung
                        const range = document.createRange();
                        const sel = window.getSelection();
                        range.setStart(this.childNodes[0] || this, this.textContent.length);
                        range.collapse(true);
                        sel.removeAllRanges();
                        sel.addRange(range);
                    });

                    seatDiv.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            this.blur();
                        }
                    });

                    rowDiv.appendChild(seatDiv);
                }
                seatMap.appendChild(rowDiv);
            }
        }

        function isDuplicateSeat(seatNumber, currentSeat) {
            const seats = document.querySelectorAll('.seat');
            for (let seat of seats) {
                if (seat !== currentSeat && seat.textContent.trim().toUpperCase() === seatNumber) {
                    return true;
                }
            }
            return false;
        }

        function checkDuplicateSeats() {
            const seats = document.querySelectorAll('.seat');
            const seatNumbers = new Set();
            seats.forEach(seat => {
                const seatNumber = seat.textContent.trim().toUpperCase();
                if (seatNumber && seatNumbers.has(seatNumber)) {
                    seat.classList.add('duplicate');
                } else {
                    seat.classList.remove('duplicate');
                    if (seatNumber) {
                        seatNumbers.add(seatNumber);
                    }
                }
            });
        }

        document.getElementById('seatsForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const seatMatrix = getSeatMatrix();
            const filledSeats = seatMatrix.flat().filter(seat => seat !== '');

            if (filledSeats.length > totalSeats) {
                alert(`Bạn đã nhập quá số ghế cho phép. Số ghế tối đa là ${totalSeats}.`);
                return;
            }

            if (filledSeats.length < totalSeats) {
                alert(`Bạn chưa nhập đủ số ghế. Cần nhập ${totalSeats} ghế.`);
                return;
            }

            const uniqueSeats = new Set(filledSeats.map(seat => seat.toUpperCase()));
            if (filledSeats.length !== uniqueSeats.size) {
                alert('Có mã ghế bị trùng. Vui lòng kiểm tra lại.');
                return;
            }

            document.getElementById('seatData').value = JSON.stringify(seatMatrix);
            this.submit();
        });

        function getSeatMatrix() {
            const seatMap = document.getElementById('seatMap');
            const rows = seatMap.children;
            const matrix = [];

            for (let i = 0; i < rows.length; i++) {
                const cols = rows[i].children;
                const rowArray = [];

                for (let j = 0; j < cols.length; j++) {
                    rowArray.push(cols[j].textContent.trim().toUpperCase() || '');
                }
                matrix.push(rowArray);
            }

            return matrix;
        }

        function disableOtherSeats(currentSeat) {
            const seats = document.querySelectorAll('.seat');
            seats.forEach(seat => {
                if (seat !== currentSeat) {
                    seat.contentEditable = false;
                    seat.classList.add('disabled');
                }
            });
        }

        function enableAllSeats() {
            const seats = document.querySelectorAll('.seat');
            seats.forEach(seat => {
                seat.contentEditable = true;
                seat.classList.remove('disabled');
            });
        }
    </script>
@endsection
