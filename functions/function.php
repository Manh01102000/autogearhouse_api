<?php
// gọi để lấy email
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
// Hàm mã hóa và giải mã sử dụng thuật toán đối xứng AES-256-CBC (AES 256 byte)
// 🔒 Hàm mã hóa dữ liệu
function encryptData($data, $key)
{
    // Tạo IV ngẫu nhiên (16 byte)
    $iv = random_bytes(16);
    // Mã hóa dữ liệu
    $encrypted = openssl_encrypt($data, "AES-256-CBC", $key, 0, $iv);
    // Gộp IV + dữ liệu mã hóa rồi mã hóa tiếp bằng Base64
    return base64_encode($iv . $encrypted);
}
// 🔓 Hàm giải mã dữ liệu
function decryptData($data, $key)
{
    // Giải mã Base64 để lấy lại dữ liệu gốc
    $data = base64_decode($data);
    // Lấy IV từ 16 byte đầu tiên
    $iv = substr($data, 0, 16);
    // Lấy phần dữ liệu mã hóa sau IV
    $encrypted = substr($data, 16);
    // Giải mã dữ liệu
    return openssl_decrypt($encrypted, "AES-256-CBC", $key, 0, $iv);
}
// Hàm Lấy link ảnh avatar
if (!function_exists('geturlimageAvatar')) {
    function geturlimageAvatar($time_stamp)
    {
        $month = date('m', $time_stamp);
        $year = date('Y', $time_stamp);
        $day = date('d', $time_stamp);
        $dir = "pictures/" . $year . "/" . $month . "/" . $day . "/"; // Full Path
        is_dir($dir) || @mkdir($dir, 0777, true) || die("Can't Create folder");
        return $dir;
    }
}
// Hàm Lấy link ảnh avatar admin
if (!function_exists('geturlimageAvatarAdmin')) {
    function geturlimageAvatarAdmin($time_stamp)
    {
        $month = date('m', $time_stamp);
        $year = date('Y', $time_stamp);
        $day = date('d', $time_stamp);
        $dir = "pictures/admin/" . $year . "/" . $month . "/" . $day . "/"; // Full Path
        is_dir($dir) || @mkdir($dir, 0777, true) || die("Can't Create folder");
        return $dir;
    }
}
// Hàm xóa dấu
if (!function_exists('remove_accent')) {
    function remove_accent($mystring)
    {
        $marTViet = array(
            "à",
            "á",
            "ạ",
            "ả",
            "ã",
            "â",
            "ầ",
            "ấ",
            "ậ",
            "ẩ",
            "ẫ",
            "ă",
            "ằ",
            "ắ",
            "ặ",
            "ẳ",
            "ẵ",
            "è",
            "é",
            "ẹ",
            "ẻ",
            "ẽ",
            "ê",
            "ề",
            "ế",
            "ệ",
            "ể",
            "ễ",
            "ì",
            "í",
            "ị",
            "ỉ",
            "ĩ",
            "ò",
            "ó",
            "ọ",
            "ỏ",
            "õ",
            "ô",
            "ồ",
            "ố",
            "ộ",
            "ổ",
            "ỗ",
            "ơ",
            "ờ",
            "ớ",
            "ợ",
            "ở",
            "ỡ",
            "ù",
            "ú",
            "ụ",
            "ủ",
            "ũ",
            "ư",
            "ừ",
            "ứ",
            "ự",
            "ử",
            "ữ",
            "ỳ",
            "ý",
            "ỵ",
            "ỷ",
            "ỹ",
            "đ",
            "À",
            "Á",
            "Ạ",
            "Ả",
            "Ã",
            "Â",
            "Ầ",
            "Ấ",
            "Ậ",
            "Ẩ",
            "Ẫ",
            "Ă",
            "Ằ",
            "Ắ",
            "Ặ",
            "Ẳ",
            "Ẵ",
            "È",
            "É",
            "Ẹ",
            "Ẻ",
            "Ẽ",
            "Ê",
            "Ề",
            "Ế",
            "Ệ",
            "Ể",
            "Ễ",
            "Ì",
            "Í",
            "Ị",
            "Ỉ",
            "Ĩ",
            "Ò",
            "Ó",
            "Ọ",
            "Ỏ",
            "Õ",
            "Ô",
            "Ồ",
            "Ố",
            "Ộ",
            "Ổ",
            "Ỗ",
            "Ơ",
            "Ờ",
            "Ớ",
            "Ợ",
            "Ở",
            "Ỡ",
            "Ù",
            "Ú",
            "Ụ",
            "Ủ",
            "Ũ",
            "Ư",
            "Ừ",
            "Ứ",
            "Ự",
            "Ử",
            "Ữ",
            "Ỳ",
            "Ý",
            "Ỵ",
            "Ỷ",
            "Ỹ",
            "Đ",
            "'"
        );

        $marKoDau = array(
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "a",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "e",
            "i",
            "i",
            "i",
            "i",
            "i",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "o",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "u",
            "y",
            "y",
            "y",
            "y",
            "y",
            "d",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "A",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "E",
            "I",
            "I",
            "I",
            "I",
            "I",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "O",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "U",
            "Y",
            "Y",
            "Y",
            "Y",
            "Y",
            "D",
            ""
        );

        return str_replace($marTViet, $marKoDau, $mystring);
    }
}
// Hàm lấy client_ip
if (!function_exists('client_ip')) {
    function client_ip()
    {
        $array = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];
        foreach ($array as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }
}
// Hàm chuyển title sang dạng slug, alias
if (!function_exists('replaceTitle')) {
    function replaceTitle($title)
    {
        $title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');
        $title = remove_accent($title);
        $title = str_replace('/', '', $title);
        $title = preg_replace('/[^\00-\255]+/u', '', $title);

        if (preg_match("/[\p{Han}]/simu", $title)) {
            $title = str_replace(' ', '-', $title);
        } else {
            $arr_str = array("&lt;", "&gt;", "/", " / ", "\\", "&apos;", "&quot;", "&amp;", "lt;", "gt;", "apos;", "quot;", "amp;", "&lt", "&gt", "&apos", "&quot", "&amp", "&#34;", "&#39;", "&#38;", "&#60;", "&#62;");

            $title = str_replace($arr_str, " ", $title);
            $title = preg_replace('/\p{P}|\p{S}/u', ' ', $title);
            $title = preg_replace('/[^0-9a-zA-Z\s]+/', ' ', $title);

            //Remove double space
            $array = array(
                '    ' => ' ',
                '   ' => ' ',
                '  ' => ' ',
            );
            $title = trim(strtr($title, $array));
            $title = str_replace(" ", "-", $title);
            $title = urlencode($title);
            // remove cac ky tu dac biet sau khi urlencode
            $array_apter = array("%0D%0A", "%", "&", "---");
            $title = str_replace($array_apter, "-", $title);
            $title = strtolower($title);
        }
        return $title;
    }
}
// Hàm giới hạn text hiển thị
if (!function_exists('limitText')) {
    function limitText($text, $limit, $suffix = '...')
    {
        return mb_strimwidth($text, 0, $limit, $suffix, 'UTF-8');
    }
}
//Hàm lấy thời gian
if (!function_exists('lay_tgian')) {
    function lay_tgian($tgian)
    {
        // Lấy chênh lệch thời gian tính bằng giây
        $tg = time() - $tgian; // Get the difference in seconds
        $thoi_gian = '';

        if ($tg > 0) {
            if ($tg < 60) {
                $thoi_gian = $tg . ' giây';
            } else if ($tg >= 60 && $tg < 3600) {
                $thoi_gian = floor($tg / 60) . ' phút';
            } else if ($tg >= 3600 && $tg < 86400) {
                $thoi_gian = floor($tg / 3600) . ' giờ';
            } else if ($tg >= 86400 && $tg < 2592000) {
                $thoi_gian = floor($tg / 86400) . ' ngày';
            } else if ($tg >= 2592000 && $tg < 77760000) {
                $thoi_gian = floor($tg / 2592000) . ' tháng';
            } else if ($tg >= 77760000 && $tg < 933120000) {
                $thoi_gian = floor($tg / 77760000) . ' năm';
            }
        } else {
            $thoi_gian = '1 giây';
        }

        return $thoi_gian;
    }
}
//Hàm lấy thời gian
if (!function_exists('timeAgo')) {
    function timeAgo($timestamp)
    {
        $now = time(); // Lấy thời gian hiện tại dưới dạng timestamp (giây)
        $secondsAgo = $now - $timestamp;

        if ($secondsAgo < 60) {
            return "$secondsAgo giây trước";
        } else if ($secondsAgo < 3600) {
            $minutesAgo = floor($secondsAgo / 60);
            return "$minutesAgo phút trước";
        } else if ($secondsAgo < 86400) {
            $hoursAgo = floor($secondsAgo / 3600);
            return "$hoursAgo giờ trước";
        } else if ($secondsAgo < 604800) {
            $daysAgo = floor($secondsAgo / 86400);
            return "$daysAgo ngày trước";
        } else if ($secondsAgo < 2592000) {
            $weeksAgo = floor($secondsAgo / 604800);
            return "$weeksAgo tuần trước";
        } else if ($secondsAgo < 31536000) {
            $monthsAgo = floor($secondsAgo / 2592000);
            return "$monthsAgo tháng trước";
        } else {
            $yearsAgo = floor($secondsAgo / 31536000);
            return "$yearsAgo năm trước";
        }
    }
}
// Hàm render breadcrumb
if (!function_exists('renderBreadcrumb')) {
    function renderBreadcrumb($breadcrumbItems)
    {
        // Render breadcrumb HTML
        $html = '<section class="bread-crumb"><div class="breadcrumb-container"><ul class="breadcrumb dp_fl_fd_r">';

        foreach ($breadcrumbItems as $item) {
            if ($item['url']) {
                $html .= '<li><a href="' . $item['url'] . '" target="_blank" class="' . $item['class'] . '">' . $item['title'] . '</a></li>';
            } else {
                $html .= '<li class="' . $item['class'] . ' dp_fl_fd_r">' . $item['title'] . '</li>';
            }
        }

        $html .= '</ul></div></section>';

        return $html;
    }
}
// link chi tiet ung vien
function rewriteUV($id, $name)
{
    $alias = replaceTitle($name);
    if ($alias == '') {
        $alias = 'nguoi-ngoai-quoc';
    }
    return "/" . $alias . "-us" . $id;
}
// link chi tiet sản phẩm
function rewriteProduct($id, $alias, $text)
{
    // Đảm bảo ID là số
    $id = intval($id);
    if ($id <= 0) {
        return null; // ID không hợp lệ
    }

    // Nếu alias rỗng, tạo alias từ text
    if (empty($alias)) {
        $alias = !empty($text) ? replaceTitle($text) : "san-pham";
    }

    // Loại bỏ khoảng trắng thừa
    $alias = trim($alias);

    return "/san-pham/" . $alias . "-" . $id;
}
// link chi tiet tin tin
function rewriteNews($id, $alias, $text)
{
    // Đảm bảo ID là số
    $id = intval($id);
    if ($id <= 0) {
        return null; // ID không hợp lệ
    }

    // Nếu alias rỗng, tạo alias từ text
    if (empty($alias)) {
        $alias = !empty($text) ? replaceTitle($text) : "bai-viet";
    }

    // Loại bỏ khoảng trắng thừa
    $alias = trim($alias);

    return "/bai-viet/" . $alias . "-" . $id;
}

// Hàm lấy chuỗi cuối cùng
if (!function_exists('getLastWord')) {

    function getLastWord($fullName)
    {
        $fullName = trim($fullName); // Loại bỏ khoảng trắng thừa
        $lastSpace = strrpos($fullName, ' '); // Tìm vị trí dấu cách cuối cùng

        if ($lastSpace !== false) {
            return substr($fullName, $lastSpace + 1); // Lấy phần sau dấu cách cuối cùng
        }

        return $fullName; // Nếu chỉ có 1 từ, trả về nguyên chuỗi
    }
}

// Làm upload avatar
if (!function_exists('UploadAvatar')) {
    function UploadAvatar($img_temp, $name, $time, $type)
    {
        $path = "pictures/";
        $year = date('Y', $time);
        $month = date('m', $time);
        $day = date('d', $time);
        $folderPath = "$path$year/$month/$day";
        $img = '';
        // Tạo thư mục nếu chưa tồn tại, kiểm tra lỗi khi tạo
        if (!is_dir($folderPath) && !mkdir($folderPath, 0777, true) && !is_dir($folderPath)) {
            return $img; // Trả về false nếu không thể tạo thư mục
        }

        // Kiểm tra file tạm có tồn tại không
        if (!file_exists($img_temp)) {
            return $img;
        }

        // Xử lý tên file an toàn hơn
        $image = replaceTitle($name) . '-' . time();
        $path_to = "$folderPath/$image.$type";

        if (move_uploaded_file($img_temp, $path_to)) {
            return "$image.$type";
        }

        return $img;
    }
}

// Làm UploadImageVideoComment
if (!function_exists('UploadImageVideoComment')) {
    function UploadImageVideoComment($img_temp, $name, $time, $extension, $type = 'product')
    {
        // Định nghĩa thư mục lưu file
        $basePath = "upload/comment/$type";
        $year = date('Y', $time);
        $month = date('m', $time);
        $day = date('d', $time);
        $folderPath = "$basePath/$year/$month/$day";

        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Kiểm tra file tạm có tồn tại không
        if (!file_exists($img_temp)) {
            return false;
        }

        // Xử lý tên file an toàn hơn
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($name, PATHINFO_FILENAME)); // Loại bỏ ký tự đặc biệt
        $image = $safeName . '-' . time();
        $pathTo = "$folderPath/$image.$extension";

        // Lưu file vào thư mục
        return move_uploaded_file($img_temp, $pathTo) ? $pathTo : false;
    }
}

// Làm lấy link video, ảnh sản phẩm
if (!function_exists('getUrlImageVideoProduct')) {
    function getUrlImageVideoProduct($time, $type = 1)
    {
        try {
            if (!is_numeric($time) || $time <= 0) {
                throw new InvalidArgumentException("Invalid timestamp provided.");
            }
            $dir = "";
            if ($type == 1) {
                // Định dạng đường dẫn thư mục
                $dir = sprintf(
                    "upload/product/images/%s/%s/%s/",
                    date('Y', $time),
                    date('m', $time),
                    date('d', $time)
                );
            } else if ($type == 2) {
                // Định dạng đường dẫn thư mục
                $dir = sprintf(
                    "upload/product/videos/%s/%s/%s/",
                    date('Y', $time),
                    date('m', $time),
                    date('d', $time)
                );
            }
            if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new RuntimeException("Failed to create directory: $dir");
            }

            return $dir;
        } catch (Exception $e) {
            Log::error("Error in getUrlImageVideoProduct: " . $e->getMessage());
            return false;
        }
    }
}

// Hàm trả về kết quả, mã lỗi của api
// Hàm này có thể dùng cho cả trả về thành công & lỗi bằng cách:
// $status: "success" hoặc "error"
// $message: Nội dung phản hồi
// $data: Dữ liệu cần trả về (mặc định là [])
// $httpCode: Mã HTTP (mặc định là 200 OK)
function apiResponse($status, $message, $data = [], $result = true, $httpCode = 200)
{
    return response()->json([
        'status' => $status,
        'message' => $message,
        'data' => $data,
        'result' => $result
    ], $httpCode);
}
function apiResponseWithCookie($status, $message, $data = [], $namecookie = '', $cookie = '', $timecookie = 0, $result = true, $httpCode = 200)
{
    return response()->json([
        'status' => $status,
        'message' => $message,
        'data' => $data,
        'result' => $result
    ], $httpCode)
        // Sau khi lên serve thì mở bảo mật XSS không lấy được token từ js
        // ->withCookie(cookie($namecookie, $cookie, $timecookie, '/', null, true, true));
        ->withCookie(cookie($namecookie, $cookie, $timecookie, '/', null, false, true));
}

//==============Hàm gửi email====================
//khi xác thực tài khoản
function sendOTPEmail($name, $email, $subject = "Email xác thực tài khoản", $otp)
{
    //Khai báo đối tượng
    $CustomEmail = new OtpMail($name, $subject, $otp);
    Mail::to($email)->queue($CustomEmail);
    // trả về kết quả
    return back()->with('success', 'Email xác nhận đã được gửi!');
}