<?php

namespace App\Http\Controllers;

use App\Constants\ApiEndpoints;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Http; // Thư viện HTTP client của Laravel
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class TripDetailController extends Controller
{
    private const BOOKING_SEARCH = 'booking/search';
    private const BOOKING_ADVANCED_SEARCH = 'booking/search/advanced';

    private const PAGE_SIZE = 5;

    public function store(Request $request)
    {
        // Lấy URL hiện tại
        $url = $request->path();
        // Xử lý theo URL sử dụng switch-case
        switch ($url) {
            case self::BOOKING_SEARCH:
                // Xử lý cho route /booking/search
                return $this->storeSearch($request);
                break;

            case self::BOOKING_ADVANCED_SEARCH:
                // Xử lý cho route /booking/search/advanced
                return $this->storeSearchAdvanced($request);
                break;

            default:
                // Nếu không khớp với các case trên, tiếp tục xử lý yêu cầu
                return view('index');
        }
    }

    public function storeSearch(Request $request)
    {
        // Lấy tất cả dữ liệu từ form
        $data = $request->all();

        // Hoặc lấy từng trường cụ thể như cách truyền thống $_POST['data']
        $departure = $request->input('departure');
        $destination = $request->input('destination');
        $departureDate = $request->input('departure-date');

        //Lay ra danh sach departures va destinations
        $departures = TripController::getAllDeparture();
        $destinations = TripController::getAllDestination();

        //Lay ra danh sach car-type
        $carTypes = CarTypeController::getAllCarType();

        //Lay ra danh sach trip-detail
        $filteredTripDetails = $this->searchTripDetail($departure, $destination, $departureDate);

        // Trả về một phản hồi hoặc chuyển hướng
        return view('Pages.booking', compact(
            "departure",
            "destination",
            "departureDate",
            "departures",
            "destinations",
            "carTypes",
            "filteredTripDetails"
        ));
    }

    public function storeSearchAdvanced(Request $request)
    {
        // Lấy tất cả dữ liệu từ form
        $data = $request->all();

        // Hoặc lấy từng trường cụ thể như cách truyền thống $_POST['data']
        $departure = $request->input('departure');
        $destination = $request->input('destination');
        $departureDate = $request->input('departure-date');
        $carType = $request->input('car-type');
        $priceRange = $request->input('price-range-input');

        // Tách chuỗi thành hai phần
        list($minPrice, $maxPrice) = $this->getPriceRange($priceRange);

        //Lay ra danh sach departures va destinations
        $departures = TripController::getAllDeparture();
        $destinations = TripController::getAllDestination();

        //Lay ra danh sach car-type
        $carTypes = CarTypeController::getAllCarType();

        //Lay ra danh sach trip-detail
        $filteredTripDetails = $this->searchAdvancedTripDetail($departure, $destination, $departureDate, $carType, $minPrice, $maxPrice);

        //Trả về một phản hồi hoặc chuyển hướng
        return view('Pages.booking', compact(
            "departure",
            "destination",
            "departureDate",
            "departures",
            "destinations",
            "carType",
            "minPrice",
            "maxPrice",
            "carTypes",
            "filteredTripDetails"
        ));
    }

    private function getPriceRange($priceRange)
    {
        // Tách chuỗi thành hai phần
        list($priceMin, $priceMax) = explode(' - ', $priceRange);

        // Loại bỏ ký tự không phải số và dấu phẩy
        $priceMin = preg_replace('/[^\d]/', '', $priceMin);
        $priceMax = preg_replace('/[^\d]/', '', $priceMax);

        // Chuyển đổi các giá trị về số nguyên lớn (long)
        $priceMin = intval($priceMin);
        $priceMax = intval($priceMax);

        return [$priceMin, $priceMax];
    }

    public static function getSeatMaps($tripDetailId, $departureDate)
    {
        $apiUrl = ApiEndpoints::API_SEAT_MAP_GET . $tripDetailId . '/' . $departureDate;
        $seatMaps = ApiController::getData($apiUrl);
        return $seatMaps;
    }

    public static function getAllTripDetail()
    {
        $tripDetails = ApiController::getData(ApiEndpoints::API_TRIP_DETAIL_LIST);
        return $tripDetails;
    }

    public static function getTripDetailById($tripDetailId)
    {
        $apiUrl = ApiEndpoints::API_TRIP_DETAIL_SEARCH_BY_ID . $tripDetailId;
        $tripDetail = ApiController::getData($apiUrl);
        return $tripDetail;
    }

    public static function searchTripDetail($departure, $destination, $departureDate)
    {
        $queryParams = http_build_query([
            'departure' => $departure,
            'destination' => $destination,
            'departure-date' => $departureDate
        ]);

        $apiUrl = ApiEndpoints::API_TRIP_DETAIL_SEARCH . $queryParams;
        $filteredTripDetails = ApiController::getData($apiUrl);
        return $filteredTripDetails;
    }

    public static function searchAdvancedTripDetail($departure, $destination, $departureDate, $carType, $minPrice, $maxPrice)
    {
        $queryParams = http_build_query([
            'departure' => $departure,
            'destination' => $destination,
            'departure-date' => $departureDate,
            'car-type' => $carType,
            'min-price' => $minPrice,
            'max-price' => $maxPrice
        ]);

        $apiUrl = ApiEndpoints::API_TRIP_DETAIL_SEARCH_ADVANCED . $queryParams;
        $filteredTripDetails = ApiController::getData($apiUrl);
        return $filteredTripDetails;
    }

    public static function tripDetailManagement($page = 1)
    {
        $response = Http::get("http://localhost:8080/api/trip-detail/page", [
            'pageSize' => self::PAGE_SIZE,
            'pageNo' => $page - 1,
        ]);

        $tripDetails = $response['data']['items'];
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

        $trips = TripController::getAllTrip();
        $cars = CarController::getAllCar();
        return view('Admin.Pages.trip-detail', [
            'tripDetails' => $tripDetails,
            'trips' => $trips,
            'cars' => $cars,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'pageSize' => self::PAGE_SIZE,
            'message' => $message
        ]);
    }

    public static function addTripDetail(Request $request)
    {
        $tripId = $request->input('trip');
        $carId = $request->input('car');
        $price = intval($request->input('price'));
        $departureTime = $request->input('departureTime') . ':00';
        $destinationTime = $request->input('destinationTime') . ':00';

        // return $departureTime;

        $tripDetailRequest = [
            "trip" => [
                "tripId" => $tripId
            ],
            "car" => [
                "carId" => $carId
            ],
            "price" => $price,
            "departureTime" => $departureTime,
            "destinationTime" => $destinationTime
        ];

        $apiResponse = ApiController::postData(ApiEndpoints::API_TRIP_DETAIL_ADD, $tripDetailRequest);
        // Kiểm tra xem $apiResponse có phải là một đối tượng JsonResponse không
        if ($apiResponse instanceof \Illuminate\Http\JsonResponse) {
            $responseData = $apiResponse->getData(true); // Chuyển đổi JsonResponse thành mảng

            // Xử lý phản hồi từ API
            if (isset($responseData['success']) && $responseData['success'] == true && $responseData['code'] == 200) {
                // Nếu thành công, lưu thông báo thành công vào session và điều hướng đến trang mới
                $message = "Thêm thành công";
                session()->put('message', $message);

                // Cập nhật các giá trị phân trang từ session
                $totalPages = session()->get('totalPages', 1);
                $totalElements = session()->get('totalElements', 0);
                $pageSize = session()->get('pageSize', 5);

                // Tính số trang mới sau khi thêm loại xe
                $totalPages = ($totalElements % $pageSize == 0) ? $totalPages + 1 : $totalPages;

                // Điều hướng đến trang mới
                return redirect()->route('trip-detail', ['page' => $totalPages]);
            } else {
                // Nếu không thành công, lưu thông báo lỗi vào session và trả về phản hồi lỗi
                $message = $responseData['message'] ?? 'Có lỗi xảy ra khi thêm';
                session()->put('message', $message);

                // Trả về phản hồi lỗi (có thể điều chỉnh tùy theo yêu cầu)
                return redirect()->back()->with('error', $message);
            }
        } else {
            // Nếu không phải JsonResponse, xử lý trường hợp lỗi hoặc thông báo không chính xác
            $message = 'Có lỗi xảy ra khi thêm';
            session()->put('message', $message);
            return redirect()->back()->with('error', $message);
        }
    }

    public static function updateTripDetail(Request $request, $tripDetailId)
    {
        $tripDetailId = $request->input('tripDetailId');
        $tripId = $request->input('trip');
        $carId = $request->input('car');
        $price = intval($request->input('price'));
        $departureTime = $request->input('departureTime');
        $destinationTime = $request->input('destinationTime');

        $tripDetailRequest = [
            "trip" => [
                "tripId" => $tripId
            ],
            "car" => [
                "carId" => $carId
            ],
            "price" => $price,
            "departureTime" => $departureTime,
            "destinationTime" => $destinationTime
        ];

        $apiResponse = ApiController::putData(ApiEndpoints::API_TRIP_DETAIL_UPDATE . $tripDetailId, $tripDetailRequest);
        // Kiểm tra xem $apiResponse có phải là một đối tượng JsonResponse không
        if ($apiResponse instanceof \Illuminate\Http\JsonResponse) {
            $responseData = $apiResponse->getData(true); // Chuyển đổi JsonResponse thành mảng

            // Xử lý phản hồi từ API
            if (isset($responseData['success']) && $responseData['success'] == true && $responseData['code'] == 200) {
                // Nếu thành công, lưu thông báo thành công vào session và điều hướng đến trang mới
                $message = "Cập nhật thành công";
                session()->put('message', $message);

                // Cập nhật các giá trị phân trang từ session
                $currentPage = session()->get('currentPage', 1);
                $totalPages = session()->get('totalPages', 1);

                // Điều hướng đến trang hiện tại
                return redirect()->route('trip-detail', ['page' => $currentPage]);
            } else {
                // Nếu không thành công, lưu thông báo lỗi vào session và trả về phản hồi lỗi
                $message = $responseData['message'] ?? 'Có lỗi xảy ra khi cập nhật';
                session()->put('message', $message);

                // Trả về phản hồi lỗi (có thể điều chỉnh tùy theo yêu cầu)
                return redirect()->back()->with('error', $message);
            }
        } else {
            // Nếu không phải JsonResponse, xử lý trường hợp lỗi hoặc thông báo không chính xác
            $message = 'Có lỗi xảy ra khi cập nhật';
            session()->put('message', $message);
            return redirect()->back()->with('error', $message);
        }
    }

    public static function deleteTripDetail($tripDetailId)
    {
        $tripDetailResponse = ApiController::deleteData(ApiEndpoints::API_TRIP_DETAIL_DELETE . $tripDetailId);

        $currentPage = session()->get('currentPage', 1);
        $totalPages = session()->get('totalPages', 1);
        $totalElements = session()->get('totalElements', 0);
        $pageSize = session()->get('pageSize', 5);

        $currentPage = ($totalElements % $pageSize == 1 && $currentPage == $totalPages) ? $currentPage - 1 : $currentPage;

        return redirect()->route('trip-detail', ['page' => $currentPage]);
    }
}
