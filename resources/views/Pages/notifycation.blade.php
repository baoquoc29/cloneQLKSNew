@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white text-center">
                <h3>Thanh toán thành công</h3>
            </div>
            <div class="card-body">
                <div class="text-center my-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center font-weight-bold">
                    Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi. Giao dịch của bạn đã được thanh toán thành công.
                </p>
                <p class="text-center">
                    Vui lòng kiểm tra email và tin nhắn SMS để biết thêm chi tiết về thông tin chuyến đi.
                </p>
                <div class="text-center mt-4">
                    <a href="{{ url('/home') }}" class="btn btn-primary btn-lg">Quay lại trang chủ</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .container {
            max-width: 600px;
        }
        .card {
            border-radius: 15px;
        }
        .card-header {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 50px;
            padding: 10px 30px;
            font-size: 1.2rem;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
@endsection