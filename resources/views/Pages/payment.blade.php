@extends('layouts.app')

<link href="{{ asset('assets/bootstrap.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/jumbotron-narrow.css') }}" rel="stylesheet">  
<script src="{{ asset('assets/jquery-1.11.3.min.js') }}"></script>

@section('content')

<div class="container">
    <div class="header clearfix">
        <h3 class="text-muted">VNPAY DEMO</h3>
    </div>
    <div class="form-group">
        <label for="customer_name">Tên khách hàng:</label>
        <input type="text" class="form-control" id="customer_name" value="{{ $customer_name ?? '' }}" readonly>
    </div>
    <div class="form-group">
        <label for="trip_info">Thông tin chuyến:</label>
        <input type="text" class="form-control" id="trip_info" value="{{ $trip_info ?? '' }}" readonly>
    </div>
    <div class="form-group">
        <button onclick="pay()">Giao dịch thanh toán</button><br>
    </div>
    <div class="form-group">
        <button onclick="querydr()">API truy vấn kết quả thanh toán</button><br>
    </div>
    <div class="form-group">
        <button onclick="refund()">API hoàn tiền giao dịch</button><br>
    </div>
    <p>
        &nbsp;
    </p>
    <footer class="footer">
        <p>&copy; VNPAY {{ date('Y') }}</p>
    </footer>
</div> 
<script>
    function pay() {
        window.location.href = "{{ route('vnpay.pay') }}";
    }
    function querydr() {
        window.location.href = "{{ route('vnpay.querydr') }}";
    }
    function refund() {
        window.location.href = "{{ route('vnpay.refund') }}";
    }
</script>
   
@endsection
