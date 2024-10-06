@extends('layouts.app')

<style>
    body {
        background-image: url('{{ asset('images/background-bus.jpeg') }}');
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
        background: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 100%;
    }

    h2 {
        color: #ff6f61;
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    label {
        color: #333;
        font-weight: bold;
    }

    .form-control {
        border-radius: 25px;
        padding: 10px 15px;
        font-size: 16px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        border: 1px solid #ff6f61;
    }

    .form-control:focus {
        border-color: #ff6f61;
        box-shadow: 0 0 10px rgba(255, 111, 97, 0.3);
    }

    .btn-primary {
        background-color: #ff6f61;
        border-color: #ff6f61;
        font-size: 18px;
        border-radius: 25px;
        padding: 10px 20px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .btn-primary:hover {
        background-color: #ff8566;
        border-color: #ff8566;
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
            <h2>Đặt vé xe trực tuyến</h2>
            <form method="POST" action="{{ route('booking.search') }}">
                @csrf
                <div class="form-group">
                    <label for="departure">Điểm đi:</label>
                    <select class="form-control" id="departure" name="departure">
                        <option value="" disabled selected>Chọn điểm đi</option>
                        @foreach ($departures as $departure)
                            <option value="{{ $departure['departure'] }}">{{ $departure['departure'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="destination">Điểm đến:</label>
                    <select class="form-control" id="destination" name="destination">
                        <option value="" disabled selected>Chọn điểm đến</option>
                        @foreach ($destinations as $destination)
                            <option value="{{ $destination['destination'] }}">{{ $destination['destination'] }}</option>
                        @endforeach
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

            // Function to remove duplicate values
            function removeDuplicates(array) {
                return [...new Set(array)];
            }

            // Populate select dropdowns with unique options
            function populateSelect(id, options) {
                const select = document.getElementById(id);
                const uniqueOptions = removeDuplicates(options);

                uniqueOptions.forEach(option => {
                    const opt = document.createElement('option');
                    opt.value = option;
                    opt.textContent = option;
                    select.appendChild(opt);
                });
            }

            // Form validation before submit
            document.querySelector('form').addEventListener('submit', function(event) {
                const departure = document.getElementById('departure').value;
                const destination = document.getElementById('destination').value;

                if (!departure) {
                    alert('Vui lòng chọn điểm đi.');
                    event.preventDefault();
                    return;
                }

                if (!destination) {
                    alert('Vui lòng chọn điểm đến.');
                    event.preventDefault();
                    return;
                }
            });
        });

        function updateDestinations() {
            const departure = document.getElementById('departure').value;
            const destination = document.getElementById('destination').value;

            destination.innerHTML = '<option value="" disabled selected>Chọn điểm đến</option>';

            // Add new options to destination based on departure
        }

        document.addEventListener('DOMContentLoaded', updateDestinations);
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
