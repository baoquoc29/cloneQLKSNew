<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http; // Thư viện HTTP client của Laravel
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;


class TripController extends Controller
{
    private const PAGE_SIZE = 5;
    private $search = false;

    public function home()
    {
        $data = $this->getAllDepartureAndDestination();

        return view('index', [
            'departures' => $data['departures'],
            'destinations' => $data['destinations']
        ]);
    }

    public function getAllDepartureAndDestination()
    {
        $departures = $this->getAllDeparture();
        $destinations = $this->getAllDestination();

        return [
            'departures' => $departures,
            'destinations' => $destinations
        ];
    }

    public static function getAllDeparture()
    {
        $departures = ApiController::getData(ApiEndpoints::API_DEPARTURE_LIST);
        return $departures;
    }

    public static function getAllDestination()
    {

        $destinations = ApiController::getData(ApiEndpoints::API_DESTINATION_LIST);
        return $destinations;
    }

    public function tripManagement($page = 1)
    {
        $response = Http::get("http://localhost:8080/api/trip/page", [
            'pageSize' => self::PAGE_SIZE,
            'pageNo' => $page - 1,
        ]);

        $trips = $response['data']['items'];
        
        $totalPages = $response['data']['totalPage'];
        $totalElements = $response['data']['totalElements'];

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        // Lưu trữ thông tin phân trang vào session
        session()->put('currentPage', $page);
        session()->put('pageSize', self::PAGE_SIZE);
        session()->put('totalPages', $totalPages);
        session()->put('totalElements', $totalElements);

        // Lấy thông báo từ session và xóa nó
        $message = session()->get('message');
        session()->forget('message');

        $departures = TripController::getAllDeparture();
        $destinations = TripController::getAllDestination();
        $departure = $destination = 'All';

        $search = false;
        return view('Admin.Pages.trip', [
            'trips' => $trips,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'pageSize' => self::PAGE_SIZE,
            'message' => $message,
            'search' => $search,
            'departures' => $departures,
            'destinations' => $destinations,
            'destination' => $destination,
            'departure' => $departure
        ]);
    }

    public static function getAllTrip()
    {
        $trips = ApiController::getData(ApiEndpoints::API_TRIP_LIST);
        return $trips;
    }

    public static function addTrip(Request $request)
    {
        $destination = $request->input('tripDestination');
        $departure = $request->input('tripDeparture');

        $tripRequest = [
            'departure' => $departure,
            'destination' => $destination

        ];
        $apiResponse = ApiController::postData(ApiEndpoints::API_TRIP_ADD, $tripRequest);
        // Kiểm tra xem $apiResponse có phải là một đối tượng JsonResponse không
        if ($apiResponse instanceof \Illuminate\Http\JsonResponse) {
            $responseData = $apiResponse->getData(true); // Chuyển đổi JsonResponse thành mảng

            // Xử lý phản hồi từ API
            if (isset($responseData['success']) && $responseData['success'] == true && $responseData['code'] == 200) {
                // Nếu thành công, lưu thông báo thành công vào session và điều hướng đến trang mới
                $message = "Thêm chuyến thành công";
                session()->put('message', $message);

                // Cập nhật các giá trị phân trang từ session
                $totalPages = session()->get('totalPages', 1);
                $totalElements = session()->get('totalElements', 0);
                $pageSize = session()->get('pageSize', 5);

                // Tính số trang mới sau khi thêm loại xe
                $totalPages = ($totalElements % $pageSize == 0) ? $totalPages + 1 : $totalPages;

                // Điều hướng đến trang mới
                return redirect()->route('trip', ['page' => $totalPages]);
            } else {
                // Nếu không thành công, lưu thông báo lỗi vào session và trả về phản hồi lỗi
                $message = $responseData['message'] ?? 'Có lỗi xảy ra khi thêm chuyến';
                session()->put('message', $message);

                // Trả về phản hồi lỗi (có thể điều chỉnh tùy theo yêu cầu)
                return redirect()->back()->with('error', $message);
            }
        } else {
            // Nếu không phải JsonResponse, xử lý trường hợp lỗi hoặc thông báo không chính xác
            $message = 'Có lỗi xảy ra khi thêm chuyến';
            session()->put('message', $message);
            return redirect()->back()->with('error', $message);
        }
    }


    public static function updateTrip(Request $request, $tripId)
    {
        $tripId = $request->input('tripId');
        $destination = $request->input('tripDestination');
        $departure = $request->input('tripDeparture');

        $tripRequest = [
            'departure' => $departure,
            'destination' => $destination

        ];
        $apiResponse = ApiController::putData(ApiEndpoints::API_TRIP_UPDATE . $tripId, $tripRequest);
        // Kiểm tra xem $apiResponse có phải là một đối tượng JsonResponse không
        if ($apiResponse instanceof \Illuminate\Http\JsonResponse) {
            $responseData = $apiResponse->getData(true); // Chuyển đổi JsonResponse thành mảng

            // Xử lý phản hồi từ API
            if (isset($responseData['success']) && $responseData['success'] == true && $responseData['code'] == 200) {
                // Nếu thành công, lưu thông báo thành công vào session và điều hướng đến trang mới
                $message = "Cập nhật chuyến thành công";
                session()->put('message', $message);

                // Cập nhật các giá trị phân trang từ session
                $currentPage = session()->get('currentPage', 1);
                $totalPages = session()->get('totalPages', 1);

                // Điều hướng đến trang hiện tại
                return redirect()->route('trip', ['page' => $currentPage]);
            } else {
                // Nếu không thành công, lưu thông báo lỗi vào session và trả về phản hồi lỗi
                $message = $responseData['message'] ?? 'Có lỗi xảy ra khi cập nhật chuyến';
                session()->put('message', $message);

                // Trả về phản hồi lỗi (có thể điều chỉnh tùy theo yêu cầu)
                return redirect()->back()->with('error', $message);
            }
        } else {
            // Nếu không phải JsonResponse, xử lý trường hợp lỗi hoặc thông báo không chính xác
            $message = 'Có lỗi xảy ra khi cập nhật chuyến';
            session()->put('message', $message);
            return redirect()->back()->with('error', $message);
        }
    }

    public static function deleteTrip($tripId)
    {
        $tripResponse = ApiController::deleteData(ApiEndpoints::API_TRIP_DELETE . $tripId);

        $currentPage = session()->get('currentPage', 1);
        $totalPages = session()->get('totalPages', 1);
        $totalElements = session()->get('totalElements', 0);
        $pageSize = session()->get('pageSize', 5);

        $currentPage = ($totalElements % $pageSize == 1 && $currentPage == $totalPages) ? $currentPage - 1 : $currentPage;

        return redirect()->route('trip', ['page' => $currentPage]);
    }

    public function search(Request $request, $page = 1)
    {
        // Lấy giá trị từ request
        $departure = $request->input('departure');
        $destination = $request->input('destination');

        // Nếu cả departure và destination đều không khác "All", redirect về trang đầu tiên
        if ($departure == 'All' && $destination == 'All') {
            return redirect()->route('trip', ['page' => 1]);
        }

        // Tạo đường dẫn API dựa trên các tham số
        $apiUrl = 'http://localhost:8080/api/trip/search?';
        $pageSize = 5;

        // Thêm các tham số vào URL nếu có giá trị
        if ($departure != 'All') {
            $apiUrl .= 'departure=' . urlencode($departure) . '&';
        }

        if ($destination != 'All') {
            $apiUrl .= 'destination=' . urlencode($destination) . '&';
        }

        // Bỏ dấu '&' cuối cùng (nếu có)
        $apiUrl = rtrim($apiUrl, '&');

     

        // Gọi API để lấy dữ liệu chuyến đi
        $apiResponse = ApiController::getData($apiUrl);
  

        // Giả định rằng API trả về các chuyến đi

        $trips = $apiResponse['trips'] ?? []; // Cập nhật tên trường tương ứng với phản hồi API
     
        // $response = Http::get("http://localhost:8080/api/trip/page", [
        //     'pageSize' => self::PAGE_SIZE,
        //     'pageNo' => $page - 1,
        // ]);

        // $trips = $response['data']['items'];
        $totalPages = $apiResponse['totalPages'] ?? 1; // Cập nhật tên trường tương ứng với phản hồi API

        // Lấy thông báo từ session và xóa nó
        $message = session()->get('message');
        session()->forget('message'); // Xóa thông báo khỏi session

        $departures = TripController::getAllDeparture();
        $destinations = TripController::getAllDestination();
        $search = true;
        // Truyền các giá trị về view
        return view('Admin.Pages.trip', [
            'pageSize' => $pageSize,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'message' => $message,
            'departure' => $departure,
            'destination' => $destination,
            'trips' => $trips,
            'search' => $search,
            'departures' => $departures,
            'destinations' => $destinations
        ]);
    }
}
