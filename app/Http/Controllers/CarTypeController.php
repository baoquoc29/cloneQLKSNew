<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\ApiEndpoints;
use Illuminate\Support\Facades\Http; // Thư viện HTTP client của Laravel
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class CarTypeController extends Controller
{
    private $search = false;
    private const PAGE_SIZE = 5;

    public static function carTypeManagement($page = 1)
    {
        $response = Http::get("http://localhost:8080/api/car-type/page", [
            'pageSize' => self::PAGE_SIZE,
            'pageNo' => $page - 1,
        ]);

        $data = $response->json();
        $carTypes = $data['data']['items'];
        $totalPages = $data['data']['totalPage'];
        $totalElements = $data['data']['totalElements'];

        if ($page > $totalPages)
            $page = $totalPages;

        session()->put('currentPage', $page);
        session()->put('pageSize', self::PAGE_SIZE);
        session()->put('totalPages', $totalPages);
        session()->put('totalElements', $totalElements);

        // Lấy thông báo từ session và xóa nó
        $message = session()->get('message');
        session()->forget('message'); // Xóa thông báo khỏi session

        $search = false;
        return view('Admin.Pages.car-type', [
            'carTypes' => $carTypes,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'pageSize' => self::PAGE_SIZE,
            'message' => $message,
            'search' => $search
        ]);
    }

    public static function getAllCarType()
    {
        $carTypes = ApiController::getData(ApiEndpoints::API_CAR_TYPE_LIST);
        return $carTypes;
    }

    public static function addCarType(Request $request)
    {
        $name = $request->input('carTypeName');
        $carTypeRequest = ["name" => $name];
        $apiResponse = ApiController::postData(ApiEndpoints::API_CAR_TYPE_ADD, $carTypeRequest);

        if ($apiResponse instanceof \Illuminate\Http\JsonResponse) {
            $responseData = $apiResponse->getData(true);

            if (isset($responseData['success']) && $responseData['success'] == true && $responseData['code'] == 200) {
                $message = "Thêm loại xe thành công";
                $success = true;
            } else {
                $message = 'Loại xe đã tồn tại';
                $success = false;
            }
        } else {
            $message = 'Có lỗi xảy ra khi thêm loại xe';
            $success = false;
        }

        session()->put('message', $message);
        session()->put('success', $success);

        if ($success) {
            $totalPages = session()->get('totalPages', 1);
            $totalElements = session()->get('totalElements', 0);
            $pageSize = session()->get('pageSize', 5);
            $totalPages = ($totalElements % $pageSize == 0) ? $totalPages + 1 : $totalPages;
            return redirect()->route('car-type', ['page' => $totalPages]);
        } else {
            return redirect()->back()->with('error', $message);
        }
    }

    public static function updateCarType(Request $request, $carTypeId)
    {
        $name = $request->input('carTypeName');
        $carTypeId = $request->input('carTypeId');
        $carTypeRequest = [
            "name" => $name
        ];

        // Gọi phương thức putData để gửi yêu cầu cập nhật loại xe
        $apiResponse = ApiController::putData(ApiEndpoints::API_CAR_TYPE_UPDATE . $carTypeId, $carTypeRequest);

        // Kiểm tra xem $apiResponse có phải là một đối tượng JsonResponse không
        if ($apiResponse instanceof \Illuminate\Http\JsonResponse) {
            $responseData = $apiResponse->getData(true); // Chuyển đổi JsonResponse thành mảng

            // Xử lý phản hồi từ API
            if (isset($responseData['success']) && $responseData['success'] == true && $responseData['code'] == 200) {
                // Nếu thành công, lưu thông báo thành công vào session và điều hướng đến trang mới
                $message = "Cập nhật loại xe thành công";
                session()->put('message', $message);

                // Cập nhật các giá trị phân trang từ session
                $currentPage = session()->get('currentPage', 1);
                $totalPages = session()->get('totalPages', 1);

                // Điều hướng đến trang hiện tại
                return redirect()->route('car-type', ['page' => $currentPage]);
            } else {
                // Nếu không thành công, lưu thông báo lỗi vào session và trả về phản hồi lỗi
                $message = $responseData['message'] ?? 'Loại xe đã tồn tại';
                session()->put('message', $message);

                // Trả về phản hồi lỗi (có thể điều chỉnh tùy theo yêu cầu)
                return redirect()->back()->with('error', $message);
            }
        } else {
            // Nếu không phải JsonResponse, xử lý trường hợp lỗi hoặc thông báo không chính xác
            $message = 'Có lỗi xảy ra khi cập nhật loại xe';
            session()->put('message', $message);
            return redirect()->back()->with('error', $message);
        }
    }

    public static function deleteCarType($carTypeId)
    {
        $carTypeResponse = ApiController::deleteData(ApiEndpoints::API_CAR_TYPE_DELETE . $carTypeId);

        $currentPage = session()->get('currentPage', 1);
        $totalPages = session()->get('totalPages', 1);
        $totalElements = session()->get('totalElements', 0);
        $pageSize = session()->get('pageSize', 5);

        $currentPage = ($totalElements % $pageSize == 1 && $currentPage == $totalPages) ? $currentPage - 1 : $currentPage;

        return redirect()->route('car-type', ['page' => $currentPage]);
    }

    public static function findCarTypeById($carTypeId)
    {
        $carType = ApiController::getData(ApiEndpoints::API_CAR_TYPE_SEARCH_BY_ID . $carTypeId);
        return $carType;
    }

    public function search(Request $request, $page = 1)
    {
        // Lấy tên loại xe từ yêu cầu
        $searchCarTypeName = $request->input('searchCarTypeName', '');
        if ($searchCarTypeName == '')
            return redirect()->route('car-type', $page = 1);

        // Tạo đường dẫn API với tham số tìm kiếm, kích thước trang, và số trang
        $apiUrl = ApiEndpoints::API_CAR_TYPE_SEARCH_BY_NAME . urlencode($searchCarTypeName) .
            '?pageSize=' . self::PAGE_SIZE . '&pageNo=' . $page - 1;

        // Gọi API để lấy dữ liệu loại xe theo tên với phân trang
        $apiResponse = ApiController::getData($apiUrl);

        $carTypes = $apiResponse['carTypes'];
        $totalPages = $apiResponse['totalPages'];

        // Lấy thông báo từ session và xóa nó
        $message = session()->get('message');
        session()->forget('message'); // Xóa thông báo khỏi session

        $search = true;
        return view('Admin.Pages.car-type', [
            'carTypes' => $carTypes,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'pageSize' => self::PAGE_SIZE,
            'message' => $message,
            'searchCarTypeName' => $searchCarTypeName,
            'search' => $search
        ]);
    }
}
