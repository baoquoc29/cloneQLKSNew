@extends('layouts.app')

<!-- Link tới Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Link tới jQuery và Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success mt-5" role="alert">
                    <h4 class="alert-heading">Hủy Vé Thành Công!</h4>
                    <p>Vé của bạn đã được hủy thành công. Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.</p>
                    <hr>
                    <p class="mb-0">Nếu bạn cần hỗ trợ thêm, vui lòng liên hệ với chúng tôi.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Quay lại Trang Chủ</a>
                </div>
            </div>
        </div>
    </div>
@endsection
