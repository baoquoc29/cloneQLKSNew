<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public static function getData($url)
    {
        // Tạo một client Guzzle
        $client = new Client();

        // Gửi một yêu cầu GET tới API
        $response = $client->request('GET', $url);

        try {
            // Gửi một yêu cầu GET tới API
            $response = $client->request('GET', $url);

            // Lấy nội dung của phản hồi
            $responseData = json_decode($response->getBody(), true);

            // Kiểm tra nếu dữ liệu JSON không hợp lệ
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Lỗi giải mã JSON: ' . json_last_error_msg());
            }

            if ($responseData['success'] && $responseData['code'] === 200) {
                $data = $responseData['data'];
                return $data;
            }

            $messageError = $responseData['message'];
            // Trả về dữ liệu hoặc xử lý nó theo cách bạn muốn
            return $messageError;
        } catch (RequestException $e) {
            // Xử lý lỗi khi gửi yêu cầu tới API
            Log::error('Lỗi khi gọi API: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi gọi API.'], 500);
        } catch (\Exception $e) {
            // Xử lý các lỗi khác, chẳng hạn như lỗi giải mã JSON
            Log::error('Lỗi khi xử lý dữ liệu JSON: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi xử lý dữ liệu JSON.'], 500);
        }
    }

    public static function getDataWithParams($url, $params)
    {
        // Tạo một client Guzzle
        $client = new Client();
    
        try {
            // Mã hóa các tham số JSON thành query string
            $queryString = http_build_query($params);
    
            // Kết hợp URL với query string
            $fullUrl = $url . '?' . $queryString;
    
            // Gửi yêu cầu GET tới API với query string
            $response = $client->request('GET', $fullUrl);
    
            // Lấy nội dung của phản hồi
            $responseData = json_decode($response->getBody(), true);
    
            // Kiểm tra nếu dữ liệu JSON không hợp lệ
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Lỗi giải mã JSON: ' . json_last_error_msg());
            }
    
            if ($responseData['success'] && $responseData['code'] === 200) {
                return $responseData['data'];
            }
    
            // Nếu có lỗi trong phản hồi
            return $responseData['message'];
        } catch (RequestException $e) {
            // Xử lý lỗi khi gửi yêu cầu tới API
            Log::error('Lỗi khi gọi API: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi gọi API.'], 500);
        } catch (\Exception $e) {
            // Xử lý các lỗi khác
            Log::error('Lỗi khi xử lý dữ liệu JSON: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi xử lý dữ liệu JSON.'], 500);
        }
    }
    
    public static function postData($url, $data)
    {
        $client = new Client();

        try {
            // Gửi yêu cầu POST đến API
            $response = $client->post($url, [
                'json' => $data,
            ]);

            // Lấy mã trạng thái và nội dung phản hồi
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();

            // Kiểm tra loại nội dung phản hồi
            if (stripos($response->getHeaderLine('Content-Type'), 'xml') !== false) {
                // Chuyển đổi XML thành JSON nếu phản hồi là XML
                $responseArray = self::xmlToArray($responseBody);
                $responseBody = json_encode($responseArray);
            }

            $responseData = json_decode($responseBody, true);

            // Xử lý phản hồi dựa trên dữ liệu nhận được
            if (isset($responseData['success']) && !$responseData['success']) {
                // Nếu phản hồi báo lỗi
                return response()->json([
                    'success' => $responseData['success'],
                    'code' => $responseData['code'],
                    'message' => $responseData['message'],
                ], $responseData['code']);
            }

            // Nếu phản hồi thành công
            $data = isset($responseData['data']) ? $responseData['data'] : [];
            $code = isset($responseData['code']) ? $responseData['code'] : 200;

            return response()->json([
                'success' => true,
                'code' => $code,
                'data' => $data,
            ]);
        } catch (RequestException $e) {
            // Xử lý lỗi từ yêu cầu
            $responseBody = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body';

            // Kiểm tra nếu phản hồi lỗi là XML và chuyển đổi nếu cần
            if ($e->hasResponse() && stripos($e->getResponse()->getHeaderLine('Content-Type'), 'xml') !== false) {
                $responseArray = self::xmlToArray($responseBody);
                $responseBody = json_encode($responseArray);
            }

            $responseData = json_decode($responseBody, true);

            // Trả về kết quả dưới dạng JSON theo định dạng yêu cầu
            return response()->json([
                'success' => isset($responseData['success']) ? $responseData['success'] : false,
                'code' => isset($responseData['code']) ? $responseData['code'] : 500,
                'message' => isset($responseData['message']) ? $responseData['message'] : 'An error occurred',
            ], isset($responseData['code']) ? $responseData['code'] : 500);
        }
    }


    // Chuyển đổi XML thành mảng PHP
    private static function xmlToArray($xmlString)
    {
        $xml = simplexml_load_string($xmlString);
        $json = json_encode($xml);
        return json_decode($json, true);
    }

    public static function putData($url, $data)
    {
        $client = new Client();

        try {
            // Gửi yêu cầu PUT đến API
            $response = $client->request('PUT', $url, [
                'json' => $data,
            ]);

            // Lấy mã trạng thái và nội dung phản hồi
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();

            // Kiểm tra loại nội dung phản hồi
            if (stripos($response->getHeaderLine('Content-Type'), 'xml') !== false) {
                // Chuyển đổi XML thành JSON nếu phản hồi là XML
                $responseArray = self::xmlToArray($responseBody);
                $responseBody = json_encode($responseArray);
            }

            $responseData = json_decode($responseBody, true);

            // Xử lý phản hồi dựa trên dữ liệu nhận được
            if (isset($responseData['success']) && !$responseData['success']) {
                // Nếu phản hồi báo lỗi
                return response()->json([
                    'success' => $responseData['success'],
                    'code' => $responseData['code'],
                    'message' => $responseData['message'],
                ], $responseData['code']);
            }

            // Nếu phản hồi thành công
            $data = isset($responseData['data']) ? $responseData['data'] : [];
            $code = isset($responseData['code']) ? $responseData['code'] : 200;

            return response()->json([
                'success' => true,
                'code' => $code,
                'data' => $data,
            ]);
        } catch (RequestException $e) {
            // Xử lý lỗi từ yêu cầu
            $responseBody = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body';

            // Kiểm tra nếu phản hồi lỗi là XML và chuyển đổi nếu cần
            if ($e->hasResponse() && stripos($e->getResponse()->getHeaderLine('Content-Type'), 'xml') !== false) {
                $responseArray = self::xmlToArray($responseBody);
                $responseBody = json_encode($responseArray);
            }

            $responseData = json_decode($responseBody, true);

            // Trả về kết quả dưới dạng JSON theo định dạng yêu cầu
            return response()->json([
                'success' => isset($responseData['success']) ? $responseData['success'] : false,
                'code' => isset($responseData['code']) ? $responseData['code'] : 500,
                'message' => isset($responseData['message']) ? $responseData['message'] : 'An error occurred',
            ], isset($responseData['code']) ? $responseData['code'] : 500);
        } catch (\Exception $e) {
            // Xử lý các lỗi khác, ví dụ lỗi giải mã JSON
            Log::error('Lỗi khi xử lý dữ liệu JSON: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi xử lý dữ liệu JSON.'], 500);
        }
    }

    public static function updateData($url, $data)
{
    $client = new Client();

    try {
        // Gửi yêu cầu PUT đến API
        $response = $client->request('PUT', $url, [
            'json' => $data,
        ]);

        // Lấy mã trạng thái và nội dung phản hồi
        $statusCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        // Kiểm tra loại nội dung phản hồi
        if (stripos($response->getHeaderLine('Content-Type'), 'xml') !== false) {
            // Chuyển đổi XML thành JSON nếu phản hồi là XML
            $responseArray = self::xmlToArray($responseBody);
            $responseBody = json_encode($responseArray);
        }

        $responseData = json_decode($responseBody, true);

        // Xử lý phản hồi dựa trên dữ liệu nhận được
        if (isset($responseData['success']) && !$responseData['success']) {
            // Nếu phản hồi báo lỗi
            return response()->json([
                'success' => $responseData['success'],
                'code' => $responseData['code'],
                'message' => $responseData['message'],
            ], $responseData['code']);
        }

        // Nếu phản hồi thành công
        $data = isset($responseData['data']) ? $responseData['data'] : [];
        $code = isset($responseData['code']) ? $responseData['code'] : 200;

        return response()->json([
            'success' => true,
            'code' => $code,
            'data' => $data,
        ]);

    } catch (RequestException $e) {
        // Xử lý lỗi từ yêu cầu
        $responseBody = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body';

        // Kiểm tra nếu phản hồi lỗi là XML và chuyển đổi nếu cần
        if ($e->hasResponse() && stripos($e->getResponse()->getHeaderLine('Content-Type'), 'xml') !== false) {
            $responseArray = self::xmlToArray($responseBody);
            $responseBody = json_encode($responseArray);
        }

        $responseData = json_decode($responseBody, true);

        // Trả về kết quả dưới dạng JSON theo định dạng yêu cầu
        return response()->json([
            'success' => isset($responseData['success']) ? $responseData['success'] : false,
            'code' => isset($responseData['code']) ? $responseData['code'] : 500,
            'message' => isset($responseData['message']) ? $responseData['message'] : 'An error occurred',
        ], isset($responseData['code']) ? $responseData['code'] : 500);
    } catch (\Exception $e) {
        // Xử lý các lỗi khác, ví dụ lỗi giải mã JSON
        Log::error('Lỗi khi xử lý dữ liệu JSON: ' . $e->getMessage());
        return response()->json(['error' => 'Có lỗi xảy ra khi xử lý dữ liệu JSON.'], 500);
    }
}

    public static function deleteData($url)
    {
        $client = new Client();

        try {
            $response = $client->request('DELETE', $url);
            $responseData = json_decode($response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Lỗi giải mã JSON: ' . json_last_error_msg());
            }

            if ($responseData['success'] && $responseData['code'] === 200) {
                return $responseData['data'];
            }

            return $responseData['message'];
        } catch (RequestException $e) {
            Log::error('Lỗi khi gọi API: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi gọi API.'], 500);
        } catch (\Exception $e) {
            Log::error('Lỗi khi xử lý dữ liệu JSON: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi xử lý dữ liệu JSON.'], 500);
        }
    }
}
