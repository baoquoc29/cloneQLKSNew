<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http; // Thư viện HTTP client của Laravel
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{
    private const PAGE_SIZE = 3;
    private $search = false;
    private $data;

    public function reportManagement($page = 1)
    {
        $response = Http::get("http://localhost:8080/api/report", [
            'pageSize' => self::PAGE_SIZE,
            'pageNo' => $page - 1,
        ]);

        $trips =  Http::get("http://localhost:8080/api/trip/all-trips")['data'];

        $data = $response->json();
        $bookings = $data['data']['items'];

        $totalPages = $data['data']['totalPage'];
        $totalElements = $data['data']['totalElements'];

        if ($page > $totalPages)
            $page = $totalPages;

        // Khởi tạo biến cần thiết
        $totalPrice = 0;
        $currentPage = 1; // Bắt đầu từ trang đầu tiên
        $cancelled = 0;
        $nonCancelled = 0;
        $allBookingItems = []; // Danh sách để lưu trữ tất cả booking items

        do {
            // Gọi API và lấy dữ liệu cho trang hiện tại
            $response = Http::get("http://localhost:8080/api/report", [
                'pageSize' => self::PAGE_SIZE,
                'pageNo' => $currentPage - 1,
            ]);

            // Giả sử API trả về mảng `items` và tổng số trang
            $bookingItems = $response['data']['items']; // Lấy danh sách booking items

            // Nếu không còn booking items, thoát khỏi vòng lặp
            if (empty($bookingItems)) {
                break;
            }

            // Lưu tất cả booking items vào danh sách
            $allBookingItems = array_merge($allBookingItems, $bookingItems);

            // Cập nhật tổng giá và số lượng vé
            foreach ($bookingItems as $item) {
                if ($item['status'] != 'Cancelled') {
                    $totalPrice += $item['totalAmount'];
                    $nonCancelled += $item['ticketCount'];
                } else {
                    $cancelled += $item['ticketCount'];
                }
            }

            // Chuyển sang trang tiếp theo
            $currentPage++;
        } while (true); // Tiếp tục cho đến khi không còn dữ liệu
        $totalRevenue = $totalPrice;
    
        $this->data = $allBookingItems;

      //  session()->put('data',  $this->data);

        // session()->put('currentPage', $page);
        // session()->put('pageSize', self::PAGE_SIZE);
        // session()->put('totalPages', $totalPages);
        // session()->put('totalElements', $totalElements);

        // Lấy thông báo từ session và xóa nó
        // $message = session()->get('message');
        // session()->forget('message'); // Xóa thông báo khỏi session

        $search = false;
        return view('Admin.Pages.report', [
            'bookings' => $bookings,
            'trips' => $trips,
            'totalPages' => $totalPages,
            'totalRevenue' => $totalRevenue,
            'totalPrice' => $totalPrice,
            'nonCancelled' => $nonCancelled,
            'cancelled' => $cancelled,
            'search' => $search,
            'currentPage' => $page,
            'pageSize' => self::PAGE_SIZE,
            'allBookingItems' => $allBookingItems
        ]);
    }

    public function search(Request $request, $page = 1)
    {
        $pageNo = max(0, $page - 1); // Đảm bảo pageNo không nhỏ hơn 0

        // Lấy các giá trị tìm kiếm từ yêu cầu
        $customerName = $request->input('customerName', '');
        $tripSelected = $request->input('trip', '');
        $startDate = $request->input('startDate', date('Y-m-d')); // Ngày bắt đầu mặc định là hôm nay
        $endDate = $request->input('endDate', date('Y-m-d')); // Ngày kết thúc mặc định là hôm nay
        $transactionStatus = $request->input('transactionStatus', '');

        $trips =  Http::get("http://localhost:8080/api/trip/all-trips")['data'];

        // Khởi tạo đường dẫn API với các tham số tìm kiếm và phân trang
        $apiUrl = ApiEndpoints::API_REPORT_SEARCH . '?pageSize=' . self::PAGE_SIZE . '&pageNo=' . ($page - 1);

        // Thêm tham số customerName nếu có giá trị
        if (!empty($customerName)) {
            $apiUrl .= '&customerName=' . $customerName;
        }

        // Thêm tham số trip nếu có giá trị
        if (!empty($tripSelected) && $tripSelected !== 'Tất cả') {
            $apiUrl .= '&trip=' . $tripSelected;
        }


        // Thêm tham số startDate nếu có giá trị
        if (!empty($startDate)) {
            $apiUrl .= '&startDate=' . $startDate;
        }

        // Thêm tham số endDate nếu có giá trị
        if (!empty($endDate)) {
            $apiUrl .= '&endDate=' . $endDate;
        }

        // Thêm tham số status nếu không phải là "Tất cả"
        if (!empty($transactionStatus) && $transactionStatus !== 'Tất cả') { // Kiểm tra nếu status không phải là "Tất cả"
            $apiUrl .= '&status=' . $transactionStatus;
        }

        // Gọi API để lấy dữ liệu theo các tiêu chí tìm kiếm với phân trang
        $apiResponse = ApiController::getData($apiUrl);

        $bookings = $apiResponse['items'];

        $totalPages = $apiResponse['totalPage'];

        $totalPrice = 0;
        $currentPage = 1; // Bắt đầu từ trang đầu tiên
        $allBookingItems = []; // Danh sách để lưu trữ tất cả booking items
        do {
            // Gọi API và lấy dữ liệu cho trang hiện tại
            $response =  ApiEndpoints::API_REPORT_SEARCH . '?pageSize=' . self::PAGE_SIZE . '&pageNo=' . ($currentPage - 1);

            // Thêm tham số customerName nếu có giá trị
            if (!empty($customerName)) {
                $response .= '&customerName=' . $customerName;
            }

            // Thêm tham số trip nếu có giá trị
            if (!empty($tripSelected) && $tripSelected !== 'Tất cả') {
                $response .= '&trip=' . $tripSelected;
            }


            // Thêm tham số startDate nếu có giá trị
            if (!empty($startDate)) {
                $response .= '&startDate=' . $startDate;
            }

            // Thêm tham số endDate nếu có giá trị
            if (!empty($endDate)) {
                $response .= '&endDate=' . $endDate;
            }

            // Thêm tham số status nếu không phải là "Tất cả"
            if (!empty($transactionStatus) && $transactionStatus !== 'Tất cả') { // Kiểm tra nếu status không phải là "Tất cả"
                $response .= '&status=' . $transactionStatus;
            }

            // Giả sử API trả về mảng `items` và tổng số trang
            $bookingItems = ApiController::getData($response)['items']; // Đổi tên từ `$bookings` thành `$bookingItems`
            $allBookingItems = array_merge($allBookingItems, $bookingItems);

            foreach ($bookingItems as $item) { // Đổi tên từ `$booking` thành `$item`
                if ($item['status'] != 'Cancelled') {
                    $totalPrice += $item['totalAmount'];
                }
            }

            // Chuyển sang trang tiếp theo
            $currentPage++;
        } while ($currentPage <= $totalPages); // Tiếp tục đến khi hết trang


        $totalRevenue = 0;
        $currentPage = 1; // Bắt đầu từ trang đầu tiên
        $cancelled = 0;
        $nonCancelled = 0;
     
        do {
            // Gọi API và lấy dữ liệu cho trang hiện tại
            $responseApi = Http::get("http://localhost:8080/api/report", [
                'pageSize' => self::PAGE_SIZE,
                'pageNo' => $currentPage - 1,
            ]);

            // Giả sử API trả về mảng `items` và tổng số trang
            $bookItems = $responseApi['data']['items']; // Đổi tên từ `$bookings` thành `$bookingItems`
            // Lưu tất cả booking items vào danh sách
       

            foreach ($bookItems as $item) {
                array_push($allBookingItems, $item);
                 // Đổi tên từ `$booking` thành `$item`
                if ($item['status'] != 'Cancelled') {
                    $totalRevenue += $item['totalAmount'];
                    $nonCancelled += $item['ticketCount'];
                } else {
                    $cancelled += $item['ticketCount'];
                }
            }

            // Chuyển sang trang tiếp theo
            $currentPage++;
        } while ($currentPage <= $totalPages); // Tiếp tục đến khi hết trang
        $totalRevenue = $totalPrice;
        $this->data = $allBookingItems;
     
        session()->put('data',  $this->data);

        $search = true;
        return view('Admin.Pages.report', [
            'bookings' => $bookings,
            'trips' => $trips,
            'totalPages' => $totalPages,
            'totalRevenue' => $totalRevenue,
            'totalPrice' => $totalPrice,
            'currentPage' => $page,
            'pageSize' => self::PAGE_SIZE,
            'customerName' => $customerName,
            'tripSelected' => $tripSelected,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'transactionStatus' => $transactionStatus,
            'search' => $search,
            'nonCancelled' => $nonCancelled,
            'cancelled' => $cancelled,
            'allBookingItems' => $allBookingItems
        ]);
    }

    public function reportExcel()
    {
        // Lấy dữ liệu từ session (được lưu trước đó)
        $data = session()->get('data');
    
        // Gọi API để export file Excel
        $response = Http::post("http://localhost:8080/api/report/exportBookings", $data);
     
        // Kiểm tra xem API có phản hồi thành công không
        if ($response->ok()) {
            // Tạo tên file và lấy nội dung file từ API response
            $fileName = 'Bookings.xlsx';
            $fileContent = $response->body(); // Lấy nội dung của file Excel
    
            // Trả về phản hồi cho trình duyệt tải xuống file
            return response($fileContent)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') // Loại file Excel
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"'); // Đặt tên file khi tải về
        }
    
        // Nếu có lỗi, có thể redirect hoặc hiển thị thông báo lỗi
        return redirect()->back()->with('error', 'Có lỗi xảy ra khi xuất file Excel.');
    }    
}
