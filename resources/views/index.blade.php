@extends('layouts.app')
<style>
    body {
        background-image: url('background.jpg');
        /* Thay 'background.jpg' bằng đường dẫn đến hình nền của bạn */
        background-size: cover;
        background-position: center;
        height: 100vh;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    .container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .form-container {
        background: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 100%;
    }

    .footer {
        background-color: #f8f9fa;
        padding: 10px;
        text-align: center;
    }
</style>
@section('content')
    <!-- Content -->
    <div class="container">
        <div class="form-container">
            <h2 class="text-center">Đặt vé xe trực tuyến</h2>
            <form method="POST" action="{{ route('booking.search') }}">
                @csrf
                <div class="form-group">
                    <label for="departure">Điểm đi:</label>
                    <select class="form-control" id="departure" name="departure">
                        <!-- Option values should come from server-side or JavaScript -->
                        <option value="" disabled selected>Chọn điểm đi</option>
                        <!-- Replace the following options with dynamic values from server-side data -->
                        @foreach ($departures as $departure)
                            <option value="{{ $departure['departure'] }}">{{ $departure['departure'] }}</option>
                        @endforeach
                        <!-- Add more options as needed -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="destination">Điểm đến:</label>
                    <select class="form-control" id="destination" name="destination">
                        <!-- Option values should come from server-side or JavaScript -->
                        <option value="" disabled selected>Chọn điểm đến</option>
                        @foreach ($destinations as $destination)
                            <option value="{{ $destination['destination'] }}">{{ $destination['destination'] }}</option>
                        @endforeach
                        <!-- Options will be updated dynamically -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="departure-date">Thời gian đi:</label>
                    <input type="date" class="form-control" id="departure-date" name="departure-date">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Tìm chuyến xe</button>
            </form>
        </div>
    </div>

    <script>
        // Set default date to today and disable past dates
        document.addEventListener('DOMContentLoaded', function() {
            var today = new Date().toISOString().split('T')[0];
            var dateInput = document.getElementById('departure-date');
            dateInput.value = today;
            dateInput.setAttribute('min', today);

            // Hàm để loại bỏ giá trị trùng lặp
            function removeDuplicates(array) {
                return [...new Set(array)];
            }

            // Hàm thêm tùy chọn vào thẻ select
            function populateSelect(id, options) {
                const select = document.getElementById(id);
                const uniqueOptions = removeDuplicates(options); // Loại bỏ giá trị trùng lặp

                uniqueOptions.forEach(option => {
                    const opt = document.createElement('option');
                    opt.value = option;
                    opt.textContent = option;
                    select.appendChild(opt);
                });
            }

            // Kiểm tra form trước khi submit
            document.querySelector('form').addEventListener('submit', function(event) {
                const departure = document.getElementById('departure').value;
                const destination = document.getElementById('destination').value;

                if (!departure) {
                    alert('Vui lòng chọn điểm đi.');
                    event.preventDefault(); // Ngăn không cho form submit
                    return;
                }

                if (!destination) {
                    alert('Vui lòng chọn điểm đến.');
                    event.preventDefault(); // Ngăn không cho form submit
                    return;
                }
            });
        });

        // const departureOptions = [
        //     'Hà Nội',
        //     'Hồ Chí Minh',
        //     'Đà Nẵng'
        //     // Add more options as needed
        // ];

        function updateDestinations() {
            const departure = document.getElementById('departure').value;
            const destination = document.getElementById('destination').value;

            // Clear existing options
            destination.innerHTML = '<option value="" disabled selected>Chọn điểm đến</option>';

            // Add options based on departure
            // departureOptions.forEach(place => {
            //     if (place !== departure) {
            //         const option = document.createElement('option');
            //         option.value = place;
            //         option.textContent = place;
            //         destination.appendChild(option);
            //     }
            // });
        }

        // Initialize destinations on page load
        document.addEventListener('DOMContentLoaded', updateDestinations);
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection