@extends('layouts.app')

<style>
    body {
        background-image: url('{{ asset('images/background-bus.jpeg') }}');
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
        max-width: 700px; /* Tăng kích thước form */
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

    .card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .card-header {
        background-color: #0056b3;
        padding: 20px;
    }

    .form-label {
        font-weight: bold;
        color: #495057;
    }

    .form-select,
    .form-control {
        border: 2px solid #ced4da;
        border-radius: 10px;
        padding: 10px;
        transition: all 0.3s ease;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #0056b3;
        box-shadow: 0 0 0 0.25rem rgba(0, 86, 179, 0.25);
    }

    .btn-primary {
        background-color: #0056b3;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #003d82;
        transform: translateY(-2px);
    }

    .fas,
    .far {
        color: #0056b3;
    }
</style>

@section('content')
    <!-- Content -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h2 class="text-center mb-0">Đặt Vé Xe Trực Tuyến</h2>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('booking.search') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="departure" class="form-label"><i class="fas fa-map-marker-alt me-2"></i>Điểm đi:</label>
                                    <select class="form-select form-select-lg" id="departure" name="departure">
                                        <option value="" disabled selected>Chọn điểm đi</option>
                                        @foreach ($departures as $departure)
                                            <option value="{{ $departure['departure'] }}">{{ $departure['departure'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="destination" class="form-label"><i class="fas fa-map-pin me-2"></i>Điểm đến:</label>
                                    <select class="form-select form-select-lg" id="destination" name="destination">
                                        <option value="" disabled selected>Chọn điểm đến</option>
                                        @foreach ($destinations as $destination)
                                            <option value="{{ $destination['destination'] }}">{{ $destination['destination'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="departure-date" class="form-label"><i class="far fa-calendar-alt me-2"></i>Thời gian đi:</label>
                                    <input type="date" class="form-control form-control-lg" id="departure-date" name="departure-date">
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg py-3">Tìm chuyến xe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
        }
        .card-header {
            background-color: #0056b3;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        .form-select, .form-control {
            border: 2px solid #ced4da;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-select:focus, .form-control:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 0.25rem rgba(0, 86, 179, 0.25);
        }
        .btn-primary {
            background-color: #0056b3;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #003d82;
            transform: translateY(-2px);
        }
        .fas, .far {
            color: #0056b3;
        }
    </style>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

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
