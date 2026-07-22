<?php
class Member_proc extends CI_Controller
{
    // 심사용 테스트 전화번호: SMS 인증 생략, 즉시 회원가입/로그인 처리 (추후 추가 가능)
    private $test_phone_numbers = ["01011112222", "01011111111","01022222222"];

    function __construct()
    {
        parent::__construct();

        # 모델 로드
        $this->load->model('sitemodel', 'site_model');
        $this->load->model('membermodel', 'member_model');
        $this->load->model('mongomodel', 'mongo_model');
    }

    /**
     * 비즈니스 로직 오류를 파일에 기록하는 함수
     */
    private function log_business_error($function_name, $error_message, $data = [])
    {
        try {
            $log_data = [
                'timestamp' => date('Y-m-d H:i:s'),
                'function' => $function_name,
                'error' => $error_message,
                'data' => $data,
                'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown',
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown'
            ];

            $log_file = '/website/nowapk/logs/business_error_' . date('Y-m-d') . '.log';
            $log_line = json_encode($log_data, JSON_UNESCAPED_UNICODE) . "\n";

            file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            // 로그 기록 실패 시 무시
        }
    }

    /**
     * 휴대폰 회원 로그인 처리 (세션 설정)
     */
    private function loginPhoneMember($mem_info)
    {
        $addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mem_info["mm_idx"]]);
        $this->user->setUser([
            "mm_idx" => $mem_info["mm_idx"],
            "mm_id" => $mem_info["mm_id"],
            "mm_point" => $mem_info["mm_point"],
            "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
            "mm_type" => $mem_info["mm_type"],
            "mm_join_type" => $mem_info["mm_join_type"],
            "mm_img" => $mem_info["mm_thumb"],
            "ad_addr" => isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : "",
            "ad_gps_x" => isset($addr_info["ad_gps_x"]) ? $addr_info["ad_gps_x"] : "",
            "ad_gps_y" => isset($addr_info["ad_gps_y"]) ? $addr_info["ad_gps_y"] : "",
            "mm_sex" => $mem_info["mm_sex"],
        ]);
    }

    /**
     * 휴대폰 회원 생성 및 로그인 처리
     */
    private function createAndLoginPhoneMember($normalized_phone)
    {
        $mm_id = "phone_" . $normalized_phone . "_" . time();
        $mm_pwd = password_hash("phone_signup_" . time(), PASSWORD_DEFAULT);

        $insert_data = [
            "mm_id" => $mm_id,
            "mm_pwd" => $mm_pwd,
            "mm_phone" => $normalized_phone,
            "mm_join_type" => "WEB",
            "mm_status" => "Y",
            "last_login_dt" => date("Y-m-d H:i:s"),
        ];

        $this->db->insert("st_member", $insert_data);
        $mm_idx = $this->db->insert_id();

        if (empty($mm_idx)) {
            return null;
        }

        $mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);
        if ($mem_info == null) {
            return null;
        }

        $addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mem_info["mm_idx"]]);
        if (!is_array($addr_info)) {
            $addr_info = [];
        }

        $this->user->setUser([
            "mm_idx" => $mem_info["mm_idx"],
            "mm_id" => $mem_info["mm_id"],
            "mm_point" => $mem_info["mm_point"],
            "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
            "mm_type" => $mem_info["mm_type"],
            "mm_join_type" => $mem_info["mm_join_type"],
            "mm_img" => $mem_info["mm_thumb"],
            "ad_addr" => isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : "",
            "ad_gps_x" => isset($addr_info["ad_gps_x"]) ? $addr_info["ad_gps_x"] : "",
            "ad_gps_y" => isset($addr_info["ad_gps_y"]) ? $addr_info["ad_gps_y"] : "",
            "mm_sex" => $mem_info["mm_sex"],
        ]);

        if (function_exists('session_write_close')) {
            session_write_close();
        }

        return $mem_info;
    }

    /**
     * Google ID Token 검증
     */
    private function verify_google_id_token($id_token, $allowed_client_ids = [])
    {
        if ($id_token == "") {
            return ["ok" => false, "error" => "id_token 없음"];
        }

        $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($id_token);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($response === false || $http_code != 200) {
            return ["ok" => false, "error" => "토큰 검증 실패: " . $curl_error];
        }

        $data = json_decode($response, true);
        if (!is_array($data) || empty($data["sub"])) {
            return ["ok" => false, "error" => "토큰 정보 파싱 실패"];
        }

        if (!empty($allowed_client_ids)) {
            $aud = isset($data["aud"]) ? $data["aud"] : "";
            if ($aud == "" || !in_array($aud, $allowed_client_ids, true)) {
                return ["ok" => false, "error" => "클라이언트 ID 불일치"];
            }
        }

        if (isset($data["exp"]) && (int)$data["exp"] < time()) {
            return ["ok" => false, "error" => "토큰 만료"];
        }

        return ["ok" => true, "data" => $data];
    }

    # 로그인 처리
    function login()
    {
        $mm_id = input_post("mm_id", "");
        $mm_pwd = input_post("mm_pwd", "");
        $mm_join_type = input_post("mm_join_type", "web");
        $mm_join_key = input_post("mm_join_key", "");
        $mem_info = null;
        $idsave = input_post("idsave", "");

        // 세션 및 쿠키 정보 로그 기록 (디버깅용)
        // $debug_log = "========== [LOGIN_ATTEMPT] time: " . date("Y-m-d H:i:s") . " ==========\n";
        // $debug_log .= "SESSION: \n" . print_r($_SESSION, true) . "\n";
        // $debug_log .= "COOKIE: \n" . print_r($_COOKIE, true) . "\n";
        // $log_dir = dirname(dirname(dirname(__DIR__))) . "/logs"; // /home/nowapk/dev_nowapk/logs
        // @file_put_contents($log_dir . "/login_debug_" . date('Y-m-d') . ".log", $debug_log, FILE_APPEND | LOCK_EX);

        if ($mm_join_type == "web") {
            # 로그인처리
            if ($mm_id == "") {
                json_return("90", "아이디를 입력해주세요.");
                exit;
            }

            if ($mm_pwd == "") {
                json_return("90", "비밀번호를 입력해주세요.");
                exit;
            }

            # 정보조회후 로그인처리
            $mem_info = $this->member_model->getMember(["mm_join_type" => "WEB", "mm_id" => $mm_id, "mm_pwd" => $this->common->getSqlPass($mm_pwd)]);

            if ($mem_info == null) {
                //json_return("90","회원정보를 찾을수 없습니다.");
                json_return("90", "아이디 또는 비밀번호가 일치하지 않습니다.<br>다시 확인 후 입력해주시기 바랍니다.");
                exit;
            }

            if ($mem_info["mm_status"] != "Y") {
                json_return("90", "탈퇴한 회원입니다.");
                exit;
            }
        } else if ($mm_join_type == "KAKAO" || $mm_join_type == "APPLE" || $mm_join_type == "NAVER") {

            //json_return("99","테스트1 정상적인 접근이 아닙니다.(".$join_type."key:".$join_key.")");
            //exit;

            # 로그인처리
            if ($mm_join_key == "") {
                json_return("99", "정상적인 접근이 아닙니다.");
                exit;
            }

            // 카카오 로그인 시 7개 파라미터 받기 (로깅용)
            $sns_phone = "";
            if ($mm_join_type == "KAKAO") {
                $kakaoid = input_post("kakaoid", "");
                $kakaoname = input_post("kakaoname", "");
                $kakaophone = input_post("kakaophone", "");
                $kakaogender = input_post("kakaogender", "");
                $kakaobirthyear = input_post("kakaobirthyear", "");
                $kakaobirthday = input_post("kakaobirthday", "");
                $kakaoemail = input_post("kakaoemail", "");
                $sns_phone = $kakaophone;

                // 로그 기록 (선택사항)
                // error_log("카카오 로그인 파라미터: kakaoid=" . $kakaoid . ", kakaoname=" . $kakaoname);
            } else if ($mm_join_type == "NAVER") {
                // 네이버 로그인 시 파라미터 받기
                $navername = input_post("navername", "");
                $naveremail = input_post("naveremail", "");
                $navergender = input_post("navergender", "");
                $naverbirthyear = input_post("naverbirthyear", "");
                $naverbirthday = input_post("naverbirthday", "");
                $sns_phone = input_post("naverphone", "");
            } else if ($mm_join_type == "APPLE") {
                // 애플 로그인 시 전화번호 파라미터 받기
                $sns_phone = input_post("applephone", "");
            }

            $mem_info = $this->member_model->getMember(["mm_join_type" => $mm_join_type, "mm_join_key" => $mm_join_key, "mm_status" => "Y"]);
            if ($mem_info != null && $mem_info["mm_status"] == "Y") {
                //로그인 성공 하도록
            } else {
                //회원정보 없으면 탈퇴 여부 체크

                # 정보조회후 로그인처리
                //$mem_info = $this->member_model->getMember(["mm_join_type"=>$mm_join_type,"mm_join_key"=>$mm_join_key,"mm_status"=>"Y"]);
                $query  = "select * from st_member ";
                $query .= "where mm_join_type = '" . $mm_join_type . "' ";
                $query .= "and   mm_join_key = '" . $mm_join_key . "'  ";
                $query .= "and   mm_status = 'N'  ";
                $query .= "order by mm_idx desc limit 1 ";

                $result = $this->db->query($query);
                $mem_info = $result->row_array();
                if ($mem_info == null) {
                    // 회원정보가 없을 때 전화번호 중복 체크
                    if (!empty($sns_phone)) {
                        $duplicate_check = $this->checkDuplicatePhoneForSnsLogin($sns_phone);
                        if ($duplicate_check["is_duplicate"]) {
                            // 전화번호가 이미 가입되어 있으면 특별한 코드로 반환
                            json_return("91", $duplicate_check["message"], [
                                "join_type" => $duplicate_check["join_type"],
                                "mm_phone" => isset($duplicate_check["mm_phone"]) ? $duplicate_check["mm_phone"] : ""
                            ]);
                            exit;
                        }
                    }

                    // 회원이 없을 때 SNS 정보를 저장 (앱에서 전달받은 정보 포함)
                    if ($mm_join_type == "KAKAO") {
                        // 성별 변환: MALE/FEMALE -> 남/여
                        $gender_converted = "";
                        if ($kakaogender == "MALE") {
                            $gender_converted = "남";
                        } else if ($kakaogender == "FEMALE") {
                            $gender_converted = "여";
                        }

                        $sns_info = [
                            "join_type" => "KAKAO",
                            "join_key" => $mm_join_key,
                            "username" => !empty($kakaoname) ? $kakaoname : "kakao_" . $mm_join_key,
                            "email" => !empty($kakaoemail) ? $kakaoemail : "",
                            "gender" => $gender_converted,
                            "phonenum" => $sns_phone,
                            "birthyear" => !empty($kakaobirthyear) ? $kakaobirthyear : "",
                            "birthday" => !empty($kakaobirthday) ? $kakaobirthday : "",
                        ];

                        // 기존 로그 삭제
                        $this->site_model->deleteSiteLog(["session_id" => session_id(), "sl_status" => "Y"]);

                        // SNS 정보 로그 저장
                        $this->site_model->setSiteLog(
                            [
                                // 현재 사용자의 세션 ID를 저장 (소셜 로그 기록, 추후 회원가입시 정보 연동 목적)
                                // session_id() 함수는 현재 세션의 고유 식별자인 세션 ID 문자열을 반환합니다. 이 값은 사용자의 세션을 구분하는 데 사용됩니다.
                                "session_id" => session_id(),
                                "sl_type" => "JOIN",
                                "sl_step" => "SNS",
                                "sl_txt" => json_encode($sns_info, JSON_UNESCAPED_UNICODE)
                            ],
                            [
                                "session_id" => session_id(),
                                "sl_type" => "JOIN",
                                "sl_step" => "SNS",
                                "sl_status" => "Y"
                            ]
                        );
                    } else if ($mm_join_type == "NAVER") {
                        // 성별 변환: M/F 또는 남/여 -> 남/여
                        $gender_converted = "";
                        if ($navergender == "M" || $navergender == "남") {
                            $gender_converted = "남";
                        } else if ($navergender == "F" || $navergender == "여") {
                            $gender_converted = "여";
                        }

                        $sns_info = [
                            "join_type" => "NAVER",
                            "join_key" => $mm_join_key,
                            "username" => !empty($navername) ? $navername : "naver_" . $mm_join_key,
                            "email" => !empty($naveremail) ? $naveremail : "",
                            "gender" => $gender_converted,
                            "phonenum" => $sns_phone,
                            "birthyear" => !empty($naverbirthyear) ? $naverbirthyear : "",
                            "birthday" => !empty($naverbirthday) ? $naverbirthday : "",
                        ];

                        // 기존 로그 삭제
                        $this->site_model->deleteSiteLog(["session_id" => session_id(), "sl_status" => "Y"]);

                        // SNS 정보 로그 저장
                        $this->site_model->setSiteLog(
                            [
                                "session_id" => session_id(),
                                "sl_type" => "JOIN",
                                "sl_step" => "SNS",
                                "sl_txt" => json_encode($sns_info, JSON_UNESCAPED_UNICODE)
                            ],
                            [
                                "session_id" => session_id(),
                                "sl_type" => "JOIN",
                                "sl_step" => "SNS",
                                "sl_status" => "Y"
                            ]
                        );
                    }

                    json_return("90", "회원정보를 찾을수 없습니다.");
                    exit;
                }

                if ($mem_info["mm_status"] != "Y") {

                    $fireDate = new DateTime($mem_info["fire_dt"]); // 탈퇴일
                    $currentDate = new DateTime(); // 현재 시간
                    $interval = $fireDate->diff($currentDate)->days; // 두 날짜 차이 계산 (일 단위)

                    if ($interval < 15) {
                        json_return("91", "탈퇴한 회원입니다.");
                    } else {
                        json_return("90", "회원정보를 찾을수 없습니다.");
                    }

                    //json_return("91","탈퇴한 회원입니다.");
                    exit;
                }
            }
        }


        //di가 있는 경우 같은 di가 있는 회원으로 로그인 전환 처리
        if ($mem_info["mm_di"] != "" && $mem_info["stop_dt"] != "0000-00-00 00:00:00" && $mem_info["mm_status"] != "Y") {
            $sql = "select * FROM st_member
                      where mm_di = '" . $mem_info["mm_di"] . "'
                      and   stop_dt = '0000-00-00 00:00:00'
                      limit 1 ";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            $mem_info = $this->member_model->getMember(["mm_idx" => $result[0]["mm_idx"]]);
        }

        $addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mem_info["mm_idx"]]);
        $this->user->setUser([
            "mm_idx" => $mem_info["mm_idx"],
            "mm_id" => $mem_info["mm_id"],
            "mm_point" => $mem_info["mm_point"],
            "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
            "mm_type" => $mem_info["mm_type"],
            "mm_join_type" => $mem_info["mm_join_type"],
            "mm_img" => $mem_info["mm_thumb"],
            "ad_addr" => $addr_info["ad_addr"],
            "ad_gps_x" => $addr_info["ad_gps_x"],
            "ad_gps_y" => $addr_info["ad_gps_y"],
            "mm_sex" => $mem_info["mm_sex"],
        ]);


        # 접속 처리
        $this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $mm_id]);

        # SNS 로그인인 경우, 최근 로그인 SNS 추적 저장 (세션 + 쿠키)
        if ($mm_join_type != "web" && in_array($mm_join_type, ["KAKAO", "APPLE", "NAVER"])) {
            $this->common->setSession("last_login_type", $mm_join_type);
            setcookie("last_login_type", $mm_join_type, time() + (86400 * 365), "/");
        }

        # 헤더 정보 업데이트 : UUID로 디바이스를 찾아 현재 로그인한 회원과 연결한다.
        $headers = apache_request_headers();
        if (isset($headers["uuid"])) {
            // UUID로 디바이스 정보를 조회한다 (mm_idx 조건 없이)
            $mem_device = $this->member_model->getMemberDevice(["uuid" => $headers["uuid"]]);
            # 빈값이 아니라면
            if ($mem_device != null) {
                // 해당 디바이스를 로그인한 회원(mm_idx)과 연결 (기존 연결 관계와 무관하게 재할당)
                $this->member_model->setMemberDevice(
                    ["mm_idx" => $mem_info["mm_idx"]],
                    ["uuid" => $headers["uuid"]]
                );
            }
        }

        // iOS/Android일 때 쿠키의 UUID로 디바이스 연결 (헤더에 uuid가 없는 경우)
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $is_ios = preg_match('/nowjobswift|CFNetwork|Darwin/i', $user_agent) || preg_match('/\b(iPhone|iPad|iPod)\b/i', $user_agent);
        $is_android = preg_match('/NowJobApp_Android|Android/i', $user_agent);

        // 헤더에 UUID가 없고, iOS 또는 Android인 경우 쿠키의 UUID 사용
        if (($is_ios || $is_android) && (!isset($headers["uuid"]) || empty($headers["uuid"]))) {
            $uuid_cookie = isset($_COOKIE['uuid']) ? $_COOKIE['uuid'] : '';
            
            // OS 타입에 따라 토큰이 있는 디바이스를 우선적으로 찾기 (푸시 알림을 위해 토큰이 있는 디바이스가 중요)
            $os_type_for_query = $is_ios ? 'ios' : 'android';
            $sql = "SELECT * FROM st_member_device 
                    WHERE token IS NOT NULL 
                    AND token != '' 
                    AND os_type = ?
                    ORDER BY reg_dt DESC 
                    LIMIT 1";
            $query = $this->db->query($sql, [$os_type_for_query]);
            $token_device = $query->row_array();
            
            $target_uuid = null;
            
            if ($token_device && !empty($token_device["token"])) {
                // 토큰이 있는 디바이스가 있으면 이것을 우선적으로 연결
                $target_uuid = $token_device["uuid"];
            } else if ($uuid_cookie != "") {
                // 토큰이 있는 디바이스가 없으면 쿠키의 UUID 사용 (mm_idx 조건 없이)
                $cookie_device = $this->member_model->getMemberDevice(["uuid" => $uuid_cookie]);
                if ($cookie_device != null) {
                    $target_uuid = $uuid_cookie;
                } else {
                    // 쿠키의 UUID로 디바이스가 없으면 새로 생성 (안드로이드에서 디바이스 정보가 없는 경우 대비)
                    $new_device_data = [
                        "uuid" => $uuid_cookie,
                        "mm_idx" => $mem_info["mm_idx"],
                        "os_type" => $os_type_for_query,
                        "push_yn" => "N",
                        "reg_dt" => date('Y-m-d H:i:s'),
                        "up_dt" => date('Y-m-d H:i:s')
                    ];
                    $insert_result = $this->member_model->insertMemberDevice($new_device_data);
                    if ($insert_result) {
                        $target_uuid = $uuid_cookie;
                    }
                }
            }
            
            // 하나의 디바이스만 연결 (중복 방지)
            if ($target_uuid != null) {
                // 기존에 같은 mm_idx로 연결된 다른 디바이스가 있으면 mm_idx를 0으로 되돌림
                $sql = "UPDATE st_member_device 
                        SET mm_idx = 0, up_dt = NOW() 
                        WHERE mm_idx = ? AND uuid != ?";
                $this->db->query($sql, [$mem_info["mm_idx"], $target_uuid]);
                
                // 선택한 디바이스 연결
                $this->member_model->setMemberDevice(
                    ["mm_idx" => $mem_info["mm_idx"]],
                    ["uuid" => $target_uuid]
                );
            }
        }

        # 로그인 성공 시 해당 사용자의 모든 디바이스 중 토큰이 있는 경우 push_yn을 'Y'로 활성화
        // 토큰이 있는 디바이스만 푸시 알림 활성화 (토큰이 없으면 활성화하지 않음)
        $sql = "UPDATE st_member_device 
                SET push_yn = 'Y', up_dt = NOW() 
                WHERE mm_idx = ? AND token IS NOT NULL AND token != ''";
        $this->db->query($sql, [$mem_info["mm_idx"]]);

        # 몽고 회원정보 DB업데이트
        $mem_device = $this->member_model->getMemberDevice(["mm_idx" => $mem_info["mm_idx"]]);
        $this->mongo_model->insertUserInfo($mem_info, $mem_device != null ? $mem_device["token"] : "");

        if ($idsave == "Y") {
            setcookie("saved_id", $mm_id, time() + (86400 * 365), "/");
        } else {
            setcookie("saved_id", "", time() + (86400 * 365), "/");
        }

        json_return("00", "로그인 완료");
    }

    // 휴대폰 번호로 자동 로그인 (중복 SNS 계정 발견 시 사용)
    // mm_join_type POST 파라미터 추가: SNS 타입도 로그인 가능 (KAKAO, NAVER, WECHAT 등)
    function phone_auto_login()
    {
        $mm_phone = input_post("mm_phone", "");
        $mm_join_type = input_post("mm_join_type", "");

        if (empty($mm_phone)) {
            json_return("90", "전화번호 정보가 없습니다.");
            exit;
        }

        // 전화번호로 기존 회원 조회 (활성 회원만)
        $normalized_phone = preg_replace('/[^0-9]/', '', $mm_phone);
        $query = "SELECT * FROM st_member
                  WHERE REPLACE(REPLACE(REPLACE(mm_phone, '-', ''), ' ', ''), '.', '') = '" . $this->db->escape_str($normalized_phone) . "'
                  AND mm_status = 'Y'";

        // mm_join_type 지정 시에는 해당 타입만, 미지정 시에는 WEB 타입만
        if (!empty($mm_join_type)) {
            $query .= " AND mm_join_type = '" . $this->db->escape_str($mm_join_type) . "'";
        } else {
            $query .= " AND mm_join_type = 'WEB'";
        }

        $query .= " LIMIT 1";
        $result = $this->db->query($query);
        $mem_info = $result->row_array();

        if ($mem_info == null) {
            json_return("90", "회원정보를 찾을 수 없습니다.");
            exit;
        }

        // 주소정보 조회
        $addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mem_info["mm_idx"]]);

        // 세션 설정 (다른 로그인 경로와 동일한 배열 형태 사용)
        $this->user->setUser([
            "mm_idx" => $mem_info["mm_idx"],
            "mm_id" => $mem_info["mm_id"],
            "mm_point" => $mem_info["mm_point"],
            "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
            "mm_type" => $mem_info["mm_type"],
            "mm_join_type" => $mem_info["mm_join_type"],
            "mm_img" => $mem_info["mm_thumb"],
            "ad_addr" => isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : "",
            "ad_gps_x" => isset($addr_info["ad_gps_x"]) ? $addr_info["ad_gps_x"] : "",
            "ad_gps_y" => isset($addr_info["ad_gps_y"]) ? $addr_info["ad_gps_y"] : "",
            "mm_sex" => $mem_info["mm_sex"],
        ]);

        json_return("00", "로그인 완료");
    }

    # 구글 로그인 처리
    function google_login()
    {
        $id_token = input_post("id_token", "");
        $email = input_post("email", "");
        $name = input_post("name", "");

        if ($id_token == "") {
            json_return("99", "정상적인 접근이 아닙니다.");
            exit;
        }

        $google_setting = getSettingInfo(["st_group" => "구글"]);
        $client_ids = [];
        if (is_array($google_setting) && !empty($google_setting["WEB_CLIENT_ID"])) {
            $client_ids[] = $google_setting["WEB_CLIENT_ID"];
        }

        if (empty($client_ids)) {
            json_return("99", "구글 로그인 설정이 없습니다. 관리자에게 문의해주세요.");
            exit;
        }

        $verify = $this->verify_google_id_token($id_token, $client_ids);
        if (!$verify["ok"]) {
            $this->log_business_error("google_login", "구글 토큰 검증 실패", ["error" => $verify["error"]]);
            json_return("99", "구글 로그인에 실패했습니다.");
            exit;
        }

        $token_data = $verify["data"];
        $google_sub = $token_data["sub"];
        if ($email == "" && !empty($token_data["email"])) {
            $email = $token_data["email"];
        }
        if ($name == "" && !empty($token_data["name"])) {
            $name = $token_data["name"];
        }

        // 1) 구글 가입 회원인지 조회 (정상 회원만)
        $mem_info = $this->member_model->getMember([
            "mm_join_type" => "GOOGLE",
            "mm_join_key" => $google_sub,
            "mm_status" => "Y"
        ]);

        // 2) GOOGLE이 없으면 이메일로 기존 회원 조회는 하지 않음
        // 이유: 이메일만으로 기존 회원을 판단하면 다른 SNS로 가입된 회원이 로그인되는 문제 발생
        // 대신 신규 가입 플로우로 보내서 휴대폰 입력 단계에서 중복 감지하도록 통일

        if ($mem_info == null) {
            // 3) 탈퇴/비활성 회원 여부 확인 (mm_status = 'N')
            $query  = "select * from st_member ";
            $query .= "where mm_join_type = 'GOOGLE' ";
            $query .= "and   mm_join_key = '" . $google_sub . "'  ";
            $query .= "and   mm_status = 'N'  ";
            $query .= "order by mm_idx desc limit 1 ";

            $result = $this->db->query($query);
            $mem_info = $result->row_array();
            if ($mem_info == null) {
                // 4) 회원이 없으면 SNS 정보 임시 저장 (회원가입으로 이어질 수 있도록)
                $sns_info = [
                    "join_type" => "GOOGLE",
                    "join_key" => $google_sub,
                    "username" => !empty($name) ? $name : "google_" . $google_sub,
                    "email" => !empty($email) ? $email : "",
                ];

                // **"회원가입을 하러 온 손님의 정보를 장부에 임시로 적어두는 과정"**
                // 사용자가 구글 로그인을 성공한 직후, 실제 회원가입 버튼을 누르기 전까지 그 정보를 잃어버리지 않게 서버 데이터베이스(DB)라는 창고에 **'임시 저장'**
                $this->site_model->deleteSiteLog(["session_id" => session_id(), "sl_status" => "Y"]);
                $this->site_model->setSiteLog(
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN",
                        "sl_step" => "SNS",
                        "sl_txt" => json_encode($sns_info, JSON_UNESCAPED_UNICODE)
                    ],
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN",
                        "sl_step" => "SNS",
                        "sl_status" => "Y"
                    ]
                );
                // 회원 없음 응답 + 가입용 키/이름/이메일 전달
                json_return("90", "회원정보를 찾을수 없습니다.", [
                    "join_key"  => $google_sub,
                    "join_type" => "GOOGLE",
                    "name"      => !empty($name) ? $name : "",
                    "email"     => !empty($email) ? $email : ""
                ]);
                exit;
            }

            if ($mem_info["mm_status"] != "Y") {
                // 5) 탈퇴 회원이면 탈퇴 후 15일 이내 여부에 따라 메시지 분기
                $fireDate = new DateTime($mem_info["fire_dt"]); // 탈퇴일
                $currentDate = new DateTime(); // 현재 시간
                $interval = $fireDate->diff($currentDate)->days; // 두 날짜 차이 계산 (일 단위)

                if ($interval < 15) {
                    json_return("91", "탈퇴한 회원입니다.");
                } else {
                    json_return("90", "회원정보를 찾을수 없습니다.");
                }
                exit;
            }
        }

        // 5) 로그인 성공: 세션에 사용자 정보 저장
        $addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mem_info["mm_idx"]]);
        $this->user->setUser([
            "mm_idx" => $mem_info["mm_idx"],
            "mm_id" => $mem_info["mm_id"],
            "mm_point" => $mem_info["mm_point"],
            "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
            "mm_type" => $mem_info["mm_type"],
            "mm_join_type" => $mem_info["mm_join_type"],
            "mm_img" => $mem_info["mm_thumb"],
            "ad_addr" => $addr_info["ad_addr"],
            "ad_gps_x" => $addr_info["ad_gps_x"],
            "ad_gps_y" => $addr_info["ad_gps_y"],
            "mm_sex" => $mem_info["mm_sex"],
        ]);

        // 6) 최근 로그인 시각 갱신
        $this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $mem_info["mm_id"]]);

        // 6-1) 최근 로그인 SNS 추적 저장 (세션 + 쿠키)
        $this->common->setSession("last_login_type", "GOOGLE");
        setcookie("last_login_type", "GOOGLE", time() + (86400 * 365), "/");

        // 7) 헤더의 uuid로 디바이스 연결(푸시/디바이스 관리용)
        $headers = apache_request_headers();
        if (isset($headers["uuid"])) {
            $mem_device = $this->member_model->getMemberDevice(["uuid" => $headers["uuid"]]);
            if ($mem_device != null) {
                $this->member_model->setMemberDevice(
                    ["mm_idx" => $mem_info["mm_idx"]],
                    ["uuid" => $headers["uuid"]]
                );
            }
        }

        // 8) 앱/모바일 UA이면서 헤더 uuid가 없으면 쿠키 uuid로 디바이스 연결
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $is_ios = preg_match('/nowjobswift|CFNetwork|Darwin/i', $user_agent) || preg_match('/\b(iPhone|iPad|iPod)\b/i', $user_agent);
        $is_android = preg_match('/NowJobApp_Android|Android/i', $user_agent);

        if (($is_ios || $is_android) && (!isset($headers["uuid"]) || empty($headers["uuid"]))) {
            $uuid_cookie = isset($_COOKIE['uuid']) ? $_COOKIE['uuid'] : '';
            $os_type_for_query = $is_ios ? 'ios' : 'android';
            // 토큰이 있는 디바이스를 우선 연결 (푸시를 위해)
            $sql = "SELECT * FROM st_member_device 
                    WHERE token IS NOT NULL 
                    AND token != '' 
                    AND os_type = ?
                    ORDER BY reg_dt DESC 
                    LIMIT 1";
            $query = $this->db->query($sql, [$os_type_for_query]);
            $token_device = $query->row_array();

            $target_uuid = null;

            if ($token_device && !empty($token_device["token"])) {
                // 토큰 보유 디바이스 우선 연결
                $target_uuid = $token_device["uuid"];
            } else if ($uuid_cookie != "") {
                // 토큰 디바이스가 없으면 쿠키의 uuid 사용
                $cookie_device = $this->member_model->getMemberDevice(["uuid" => $uuid_cookie]);
                if ($cookie_device != null) {
                    $target_uuid = $uuid_cookie;
                } else {
                    // 쿠키 uuid로 디바이스가 없으면 생성
                    $new_device_data = [
                        "uuid" => $uuid_cookie,
                        "mm_idx" => $mem_info["mm_idx"],
                        "os_type" => $os_type_for_query,
                        "push_yn" => "N",
                        "reg_dt" => date('Y-m-d H:i:s'),
                        "up_dt" => date('Y-m-d H:i:s')
                    ];
                    $insert_result = $this->member_model->insertMemberDevice($new_device_data);
                    if ($insert_result) {
                        $target_uuid = $uuid_cookie;
                    }
                }
            }

            if ($target_uuid != null) {
                // 동일 사용자에 연결된 다른 디바이스는 해제 (중복 방지)
                $sql = "UPDATE st_member_device 
                        SET mm_idx = 0, up_dt = NOW() 
                        WHERE mm_idx = ? AND uuid != ?";
                $this->db->query($sql, [$mem_info["mm_idx"], $target_uuid]);

                // 선택된 디바이스만 연결
                $this->member_model->setMemberDevice(
                    ["mm_idx" => $mem_info["mm_idx"]],
                    ["uuid" => $target_uuid]
                );
            }
        }

        // 9) 토큰 보유 디바이스의 푸시 알림 활성화
        $sql = "UPDATE st_member_device 
                SET push_yn = 'Y', up_dt = NOW() 
                WHERE mm_idx = ? AND token IS NOT NULL AND token != ''";
        $this->db->query($sql, [$mem_info["mm_idx"]]);

        // 10) 몽고 DB에 사용자 정보/토큰 동기화
        $mem_device = $this->member_model->getMemberDevice(["mm_idx" => $mem_info["mm_idx"]]);
        $this->mongo_model->insertUserInfo($mem_info, $mem_device != null ? $mem_device["token"] : "");

        // 11) 로그인 완료 응답
        json_return("00", "로그인 완료");
    }

    /**
     * 위챗 임시 code → access_token + openid 교환
     * @param string $code      위챗 임시 인증 코드
     * @param string $app_id    위챗 AppID
     * @param string $app_secret  위챗 AppSecret
     * @return array|false  성공 시 access_token·openid·unionid 포함 배열, 실패 시 false
     */
    private function verify_wechat_code($code, $app_id, $app_secret)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token"
             . "?appid=" . $app_id
             . "&secret=" . $app_secret
             . "&code=" . $code
             . "&grant_type=authorization_code";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            return false;
        }
        $data = json_decode($response, true);
        if (!is_array($data) || isset($data["errcode"])) {
            return false;
        }
        return $data;
    }

    /**
     * 위챗 사용자 정보 조회 (openid, sex, unionid)
     * @param string $access_token  위챗 access_token
     * @param string $openid        위챗 openid
     * @return array|false  성공 시 응답 배열, 실패 시 false
     */
    private function get_wechat_user_info($access_token, $openid)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo"
             . "?access_token=" . $access_token
             . "&openid=" . $openid
             . "&lang=zh_CN";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            return false;
        }
        $data = json_decode($response, true);
        if (!is_array($data) || isset($data["errcode"])) {
            return false;
        }
        return $data;
    }

    /**
     * 카카오 로그인 처리 (AJAX)
     * POST 파라미터: access_token (클라이언트에서 Kakao SDK로 취득한 토큰)
     */
    function kakao_login()
    {
        $access_token = input_post("access_token", "");

        if ($access_token == "") {
            json_return("99", "정상적인 접근이 아닙니다.");
            exit;
        }

        // KakaoLogin 라이브러리로 사용자 정보 조회
        $this->load->library('KakaoLogin');
        $kakao_setting = getSettingInfo(["st_group" => "카카오"]);
        $kakao_client_id = isset($kakao_setting["REST_API_KEY"]) ? $kakao_setting["REST_API_KEY"] : "";

        if (empty($kakao_client_id)) {
            json_return("99", "카카오 로그인 설정이 없습니다.");
            exit;
        }

        $kakaoLogin = new KakaoLogin($kakao_client_id);
        $result = $kakaoLogin->getUserInfo($access_token);

        if ($result["code"] != 200) {
            json_return("99", "카카오 로그인에 실패했습니다.");
            exit;
        }

        $kakao_user = $result["data"];
        if (empty($kakao_user["id"])) {
            json_return("99", "카카오 로그인에 실패했습니다.");
            exit;
        }

        $kakao_id = $kakao_user["id"];
        $kakao_account = isset($kakao_user["kakao_account"]) ? $kakao_user["kakao_account"] : [];
        $email = isset($kakao_account["email"]) ? $kakao_account["email"] : "";
        $name = isset($kakao_account["name"]) ? $kakao_account["name"] : "";
        $gender = isset($kakao_account["gender"]) ? ($kakao_account["gender"] == "male" ? "남" : ($kakao_account["gender"] == "female" ? "여" : "")) : "";
        $phonenum = isset($kakao_account["phone_number"]) ? $kakao_account["phone_number"] : "";

        // 전화번호 정규화: 모든 비숫자 제거 (check_sns_phone과 동일한 방식)
        if ($phonenum != "") {
            $phonenum = preg_replace('/[^0-9]/', '', $phonenum);
        }

        // 1) 카카오 가입 회원인지 조회 (정상 회원만)
        $mem_info = $this->member_model->getMember([
            "mm_join_type" => "KAKAO",
            "mm_join_key" => $kakao_id,
            "mm_status" => "Y"
        ]);

        // 2) 카카오 회원이 없으면 이메일로 기존 회원 조회는 하지 않음
        // 이유: 이메일만으로 기존 회원을 판단하면 다른 SNS로 가입된 회원이 로그인되는 문제 발생
        // 대신 신규 가입 플로우로 보내서 휴대폰 입력 단계에서 중복 감지하도록 통일

        // 3) 회원이 없으면 탈퇴 회원 확인 또는 휴대폰 중복 체크
        if ($mem_info == null) {
            // 탈퇴 회원 확인
            $query = "select * from st_member ";
            $query .= "where mm_join_type = 'KAKAO' ";
            $query .= "and   mm_join_key = '" . $kakao_id . "' ";
            $query .= "and   mm_status = 'N' ";
            $query .= "order by mm_idx desc limit 1 ";

            $result = $this->db->query($query);
            $mem_info = $result->row_array();

            if ($mem_info == null) {
                // 휴대폰번호 중복 체크
                if (!empty($phonenum)) {
                    $duplicate_check = $this->checkDuplicatePhoneForSnsLogin($phonenum);
                    if ($duplicate_check["is_duplicate"]) {
                        // 중복 회원이 있으면 code 91로 반환
                        json_return("91", $duplicate_check["message"], [
                            "join_type" => $duplicate_check["join_type"],
                            "mm_phone" => isset($duplicate_check["mm_phone"]) ? $duplicate_check["mm_phone"] : ""
                        ]);
                        exit;
                    }
                }

                // 회원이 없을 때 SNS 정보 임시 저장 (회원가입으로 진행될 수 있도록)
                $sns_info = [
                    "join_type" => "KAKAO",
                    "join_key" => $kakao_id,
                    "username" => !empty($name) ? $name : "kakao_" . $kakao_id,
                    "email" => !empty($email) ? $email : "",
                    "gender" => $gender,
                    "phonenum" => $phonenum
                ];

                $this->site_model->deleteSiteLog(["session_id" => session_id(), "sl_status" => "Y"]);
                $this->site_model->setSiteLog(
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN",
                        "sl_step" => "SNS",
                        "sl_txt" => json_encode($sns_info, JSON_UNESCAPED_UNICODE)
                    ],
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN",
                        "sl_step" => "SNS",
                        "sl_status" => "Y"
                    ]
                );

                // 신규 회원 → 회원가입 유도
                json_return("90", "회원정보를 찾을 수 없습니다.", [
                    "join_key" => $kakao_id,
                    "join_type" => "KAKAO",
                    "name" => !empty($name) ? $name : "",
                    "email" => !empty($email) ? $email : ""
                ]);
                exit;
            }

            // 탈퇴 회원 처리
            if ($mem_info["mm_status"] != "Y") {
                $fireDate = new DateTime($mem_info["fire_dt"]);
                $currentDate = new DateTime();
                $interval = $fireDate->diff($currentDate)->days;

                if ($interval < 15) {
                    json_return("91", "탈퇴한 회원입니다.");
                } else {
                    json_return("90", "회원정보를 찾을수 없습니다.");
                }
                exit;
            }
        }

        // 4) 로그인 성공: 세션에 사용자 정보 저장
        $addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mem_info["mm_idx"]]);
        $this->user->setUser([
            "mm_idx" => $mem_info["mm_idx"],
            "mm_id" => $mem_info["mm_id"],
            "mm_point" => $mem_info["mm_point"],
            "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
            "mm_type" => $mem_info["mm_type"],
            "mm_join_type" => $mem_info["mm_join_type"],
            "mm_img" => $mem_info["mm_thumb"],
            "ad_addr" => $addr_info["ad_addr"],
            "ad_gps_x" => $addr_info["ad_gps_x"],
            "ad_gps_y" => $addr_info["ad_gps_y"],
            "mm_sex" => $mem_info["mm_sex"],
        ]);

        // 5) 최근 로그인 시각 갱신
        $this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $mem_info["mm_id"]]);

        // 5-1) 최근 로그인 SNS 추적 저장 (세션 + 쿠키)
        $this->common->setSession("last_login_type", "KAKAO");
        setcookie("last_login_type", "KAKAO", time() + (86400 * 365), "/");

        // 6) 헤더의 uuid로 디바이스 연결
        $headers = apache_request_headers();
        if (isset($headers["uuid"])) {
            $mem_device = $this->member_model->getMemberDevice(["uuid" => $headers["uuid"]]);
            if ($mem_device != null) {
                $this->member_model->setMemberDevice(
                    ["mm_idx" => $mem_info["mm_idx"]],
                    ["uuid" => $headers["uuid"]]
                );
            }
        }

        // 7) 몽고 회원정보 DB 업데이트
        $mem_device = $this->member_model->getMemberDevice(["mm_idx" => $mem_info["mm_idx"]]);
        $this->mongo_model->insertUserInfo($mem_info, $mem_device != null ? $mem_device["token"] : "");

        json_return("00", "로그인 완료");
    }

    /**
     * 위챗 로그인 처리
     * POST 파라미터: code (위챗 임시 인증 코드)
     * 사용 데이터: openid, sex, unionid
     */
    function wechat_login()
    {
        $code = input_post("code", "");

        if ($code == "") {
            json_return("99", "정상적인 접근이 아닙니다.");
            exit;
        }

        // st_setting 테이블에서 위챗 AppID, AppSecret 조회
        $wechat_setting = getSettingInfo(["st_group" => "위챗"]);
        $app_id     = (isset($wechat_setting["APP_ID"]) && $wechat_setting["APP_ID"] != "")
                        ? $wechat_setting["APP_ID"] : "";
        $app_secret = (isset($wechat_setting["APP_SECRET"]) && $wechat_setting["APP_SECRET"] != "")
                        ? $wechat_setting["APP_SECRET"] : "";

        if ($app_id == "" || $app_secret == "") {
            json_return("99", "위챗 로그인 설정이 없습니다. 관리자에게 문의해주세요.");
            exit;
        }

        // 1) code → access_token + openid 교환
        $token_data = $this->verify_wechat_code($code, $app_id, $app_secret);
        if (!$token_data) {
            json_return("99", "위챗 인증에 실패했습니다.");
            exit;
        }

        $access_token = $token_data["access_token"];
        $openid       = $token_data["openid"];

        // 2) 위챗 사용자 정보 조회 (openid, sex, unionid 사용)
        $user_info = $this->get_wechat_user_info($access_token, $openid);
        $sex_code  = ($user_info && isset($user_info["sex"])) ? intval($user_info["sex"]) : 0;
        $unionid   = ($user_info && isset($user_info["unionid"])) ? $user_info["unionid"] : "";

        // 위챗 sex: 1=남, 2=여, 0=미지정 → 내부 표준: MALE/FEMALE/""
        if ($sex_code == 1) {
            $gender = "MALE";
        } elseif ($sex_code == 2) {
            $gender = "FEMALE";
        } else {
            $gender = "";
        }

        // 3) 위챗 가입 회원인지 조회 (정상 회원만) - unionid 우선, openid 폴백
        // 우선순위: unionid 먼저 시도 → 없으면 openid로 시도 (기존 회원 호환성)
        $mm_join_key_for_query = ($unionid != "") ? $unionid : $openid;

        $mem_info = $this->member_model->getMember([
            "mm_join_type" => "WECHAT",
            "mm_join_key"  => $mm_join_key_for_query,
            "mm_status"    => "Y"
        ]);

        // unionid로 못 찾고 openid가 다르다면, openid로 재시도 (기존 openid 회원 호환)
        if ($mem_info == null && $unionid != "" && $unionid != $openid) {
            $mem_info = $this->member_model->getMember([
                "mm_join_type" => "WECHAT",
                "mm_join_key"  => $openid,
                "mm_status"    => "Y"
            ]);
        }

        if ($mem_info == null) {
            // 4) 탈퇴/비활성 회원 여부 확인 (mm_status = 'N')
            $mm_join_key_for_inactive = ($unionid != "") ? $unionid : $openid;

            $query  = "select * from st_member ";
            $query .= "where mm_join_type = 'WECHAT' ";
            $query .= "and   mm_join_key = '" . $mm_join_key_for_inactive . "'  ";
            $query .= "and   mm_status = 'N'  ";
            $query .= "order by mm_idx desc limit 1 ";

            $result   = $this->db->query($query);
            $mem_info = $result->row_array();

            // 탈퇴/비활성도 못 찾으면 openid로 재시도
            if ($mem_info == null && $unionid != "" && $unionid != $openid) {
                $query  = "select * from st_member ";
                $query .= "where mm_join_type = 'WECHAT' ";
                $query .= "and   mm_join_key = '" . $openid . "'  ";
                $query .= "and   mm_status = 'N'  ";
                $query .= "order by mm_idx desc limit 1 ";

                $result   = $this->db->query($query);
                $mem_info = $result->row_array();
            }

            if ($mem_info == null) {
                // 5) 회원이 없으면 SNS 정보 임시 저장 (회원가입으로 이어질 수 있도록)
                $sns_info = [
                    "join_type" => "WECHAT",
                    "join_key"  => $unionid,
                    "gender"    => $gender,
                    "unionid"   => $unionid,
                ];

                $this->site_model->deleteSiteLog(["session_id" => session_id(), "sl_status" => "Y"]);
                $this->site_model->setSiteLog(
                    [
                        "session_id" => session_id(),
                        "sl_type"    => "JOIN",
                        "sl_step"    => "SNS",
                        "sl_txt"     => json_encode($sns_info, JSON_UNESCAPED_UNICODE)
                    ],
                    [
                        "session_id" => session_id(),
                        "sl_type"    => "JOIN",
                        "sl_step"    => "SNS",
                        "sl_status"  => "Y"
                    ]
                );
                // 신규 회원 응답: 전화번호 인증 후 회원가입 진행
                json_return("90", "회원정보를 찾을수 없습니다.", [
                    "join_key"  => $unionid,
                    "join_type" => "WECHAT",
                ]);
                exit;
            }

            if ($mem_info["mm_status"] != "Y") {
                // 6) 탈퇴 회원이면 탈퇴 후 15일 이내 여부에 따라 메시지 분기
                $fireDate    = new DateTime($mem_info["fire_dt"]);
                $currentDate = new DateTime();
                $interval    = $fireDate->diff($currentDate)->days;

                if ($interval < 15) {
                    json_return("91", "탈퇴한 회원입니다.");
                } else {
                    json_return("90", "회원정보를 찾을수 없습니다.");
                }
                exit;
            }
        }

        // 6-1) openid로 조회된 기존 회원이면, unionid로 자동 마이그레이션
        // (PC에서 openid로 저장된 회원이 앱에서 접속할 때 자동 통합)
        if ($unionid != "" && $mem_info["mm_join_key"] != $unionid) {
            $this->member_model->updatemember(
                ["mm_join_key" => $unionid],
                ["mm_idx" => $mem_info["mm_idx"]]
            );
            // 조회 결과 객체도 업데이트
            $mem_info["mm_join_key"] = $unionid;
        }

        // 7) 로그인 성공: 세션에 사용자 정보 저장
        $addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mem_info["mm_idx"]]);
        $this->user->setUser([
            "mm_idx"       => $mem_info["mm_idx"],
            "mm_id"        => $mem_info["mm_id"],
            "mm_point"     => $mem_info["mm_point"],
            "mm_name"      => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
            "mm_type"      => $mem_info["mm_type"],
            "mm_join_type" => $mem_info["mm_join_type"],
            "mm_img"       => $mem_info["mm_thumb"],
            "ad_addr"      => $addr_info["ad_addr"],
            "ad_gps_x"     => $addr_info["ad_gps_x"],
            "ad_gps_y"     => $addr_info["ad_gps_y"],
            "mm_sex"       => $mem_info["mm_sex"],
        ]);

        // 8) 최근 로그인 시각 갱신
        $this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $mem_info["mm_id"]]);

        // 9) 헤더의 uuid로 디바이스 연결 (푸시/디바이스 관리용)
        $headers = apache_request_headers();
        if (isset($headers["uuid"])) {
            $mem_device = $this->member_model->getMemberDevice(["uuid" => $headers["uuid"]]);
            if ($mem_device != null) {
                $this->member_model->setMemberDevice(
                    ["mm_idx" => $mem_info["mm_idx"]],
                    ["uuid"   => $headers["uuid"]]
                );
            }
        }

        // 10) 앱/모바일 UA이면서 헤더 uuid가 없으면 쿠키 uuid로 디바이스 연결
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $is_ios     = preg_match('/nowjobswift|CFNetwork|Darwin/i', $user_agent) || preg_match('/\b(iPhone|iPad|iPod)\b/i', $user_agent);
        $is_android = preg_match('/NowJobApp_Android|Android/i', $user_agent);

        if (($is_ios || $is_android) && (!isset($headers["uuid"]) || empty($headers["uuid"]))) {
            $uuid_cookie       = isset($_COOKIE['uuid']) ? $_COOKIE['uuid'] : '';
            $os_type_for_query = $is_ios ? 'ios' : 'android';

            // 토큰이 있는 디바이스를 우선 연결 (푸시를 위해)
            $sql = "SELECT * FROM st_member_device
                    WHERE token IS NOT NULL
                    AND token != ''
                    AND os_type = ?
                    ORDER BY reg_dt DESC
                    LIMIT 1";
            $query        = $this->db->query($sql, [$os_type_for_query]);
            $token_device = $query->row_array();

            $target_uuid = null;

            if ($token_device && !empty($token_device["token"])) {
                // 토큰 보유 디바이스 우선 연결
                $target_uuid = $token_device["uuid"];
            } else if ($uuid_cookie != "") {
                // 토큰 디바이스가 없으면 쿠키의 uuid 사용
                $cookie_device = $this->member_model->getMemberDevice(["uuid" => $uuid_cookie]);
                if ($cookie_device != null) {
                    $target_uuid = $uuid_cookie;
                } else {
                    // 쿠키 uuid로 디바이스가 없으면 생성
                    $new_device_data = [
                        "uuid"    => $uuid_cookie,
                        "mm_idx"  => $mem_info["mm_idx"],
                        "os_type" => $os_type_for_query,
                        "push_yn" => "N",
                        "reg_dt"  => date('Y-m-d H:i:s'),
                        "up_dt"   => date('Y-m-d H:i:s')
                    ];
                    $insert_result = $this->member_model->insertMemberDevice($new_device_data);
                    if ($insert_result) {
                        $target_uuid = $uuid_cookie;
                    }
                }
            }

            if ($target_uuid != null) {
                // 동일 사용자에 연결된 다른 디바이스는 해제 (중복 방지)
                $sql = "UPDATE st_member_device
                        SET mm_idx = 0, up_dt = NOW()
                        WHERE mm_idx = ? AND uuid != ?";
                $this->db->query($sql, [$mem_info["mm_idx"], $target_uuid]);

                // 선택된 디바이스만 연결
                $this->member_model->setMemberDevice(
                    ["mm_idx" => $mem_info["mm_idx"]],
                    ["uuid"   => $target_uuid]
                );
            }
        }

        // 11) 토큰 보유 디바이스의 푸시 알림 활성화
        $sql = "UPDATE st_member_device
                SET push_yn = 'Y', up_dt = NOW()
                WHERE mm_idx = ? AND token IS NOT NULL AND token != ''";
        $this->db->query($sql, [$mem_info["mm_idx"]]);

        // 12) 몽고 DB에 사용자 정보/토큰 동기화
        $mem_device = $this->member_model->getMemberDevice(["mm_idx" => $mem_info["mm_idx"]]);
        $this->mongo_model->insertUserInfo($mem_info, $mem_device != null ? $mem_device["token"] : "");

        // 13) 로그인 완료 응답
        json_return("00", "로그인 완료");
    }

    # 자동 로그인
    function auto_login()
    {
        $params = input_get_all();

        $params["mm_pwd"] = sys_decrypt($params["mm_pwd"], 'njob', '20231007');

        $mem_info = $this->member_model->getMember(["mm_id" => $params["mm_id"], "mm_pwd" => $params["mm_pwd"]]);

        //print_r($mem_info);
        //exit;

        if ($mem_info["mm_status"] != "Y") {
            //print_r($mem_info);
            echo "<script>alert('로그인 가능한 회원이 아닙니다.');self.close();</script>";
            //echo "<script>alert('로그인 가능한 회원이 아닙니다.');</script>";
            exit;
        }

        $addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mem_info["mm_idx"]]);
        $this->user->setUser([
            "mm_idx" => $mem_info["mm_idx"],
            "mm_id" => $mem_info["mm_id"],
            "mm_point" => $mem_info["mm_point"],
            "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
            "mm_type" => $mem_info["mm_type"],
            "mm_join_type" => $mem_info["mm_join_type"],
            "mm_img" => $mem_info["mm_thumb"],
            "ad_addr" => $addr_info["ad_addr"],
            "ad_gps_x" => $addr_info["ad_gps_x"],
            "ad_gps_y" => $addr_info["ad_gps_y"],
            "mm_sex" => $mem_info["mm_sex"],
        ]);

        # 접속 처리
        $this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $mm_id]);

        # 헤더 정보 업데이트 : 매칭안되어 있으면 업데이트 처리한다.
        $headers = apache_request_headers();
        if (isset($headers["uuid"])) {
            $mem_device = $this->member_model->getMemberDevice(["uuid" => $headers["uuid"], "mm_idx" => 0]);
            # 빈값이 아니라면
            if ($mem_device != null) {
                $this->member_model->setMemberDevice(["mm_idx" => $mem_info["mm_idx"]], ["uuid" => $headers["uuid"]]);
            }
        }

        # 로그인 성공 시 해당 사용자의 모든 디바이스 중 토큰이 있는 경우 push_yn을 'Y'로 활성화
        $sql = "UPDATE st_member_device 
                SET push_yn = 'Y', up_dt = NOW() 
                WHERE mm_idx = ? AND token IS NOT NULL AND token != ''";
        $this->db->query($sql, [$mem_info["mm_idx"]]);

        # 몽고 회원정보 DB업데이트
        $mem_device = $this->member_model->getMemberDevice(["mm_idx" => $mem_info["mm_idx"]]);
        $this->mongo_model->insertUserInfo($mem_info, $mem_device != null ? $mem_device["token"] : "");

        echo "<script>location.href='/';</script>";
    }

    # 휴대폰번호 확인
    function check_phone()
    {
        $phone = input_post("mm_phone");
        $mem_info = $this->member_model->getMember(["mm_phone" => $phone]);

        if ($mem_info == null) {
            json_return("90", "조회 안됨");
        } else {
            json_return("00", "조회 성공", ["email" => $mem_info["mm_id"]]);
        }
    }

    # 휴대폰번호 확인
    function check_pwd()
    {
        $phone = input_post("mm_phone");
        //$email = input_post("mm_email");

        // 전화번호 정규화 (하이픈, 공백, 점 제거)
        $normalized_phone = preg_replace('/[^0-9]/', '', $phone);

        // 먼저 mm_id로 조회 시도 (일반 회원)
        $mem_info = $this->member_model->getMember(["mm_id" => $phone, "mm_status" => "Y"]);

        // mm_id로 조회되지 않으면 mm_phone으로 조회 시도 (간편가입 회원)
        if ($mem_info == null) {
            // SQL로 전화번호 조회 (하이픈 제거 후 비교)
            $query = "SELECT * FROM st_member 
                      WHERE REPLACE(REPLACE(REPLACE(mm_phone, '-', ''), ' ', ''), '.', '') = '" . $this->db->escape_str($normalized_phone) . "' 
                      AND mm_status = 'Y' 
                      LIMIT 1";
            $result = $this->db->query($query);
            $mem_info = $result->row_array();
        }

        if ($mem_info == null) {
            json_return("91", "조회 안됨");
        } else {
            // 간편가입 회원인지 확인 (NAVER, KAKAO)
            $join_type = isset($mem_info["mm_join_type"]) ? strtoupper($mem_info["mm_join_type"]) : "";
            if ($join_type == "NAVER" || $join_type == "KAKAO") {
                json_return("93", "간편가입 회원");
            } else {
                // 전화번호 일치 확인
                $mem_phone_normalized = preg_replace('/[^0-9]/', '', $mem_info["mm_phone"]);
                if ($mem_phone_normalized == $normalized_phone) {
                    //발송건수 확인 : 하루 10회이상 발송불가.
                    $count_result = $this->site_model->checkSmsSendCount($phone, "비밀번호인증");

                    if ($count_result["code"] != "00") {
                        json_return("98", "발송횟수 오버");
                    }

                    $authNum = rand(100000, 999999);
                    $result = $this->common->sendSms($phone, "나우잡 인증 번호 발송 [" . $authNum . "]", $authNum);

                    if ($result["code"] == "00") {
                        json_return("00", "조회 성공");
                    } else {
                        json_return("99", "처리 오류" . $result["text"]);
                    }
                } else {
                    json_return("92", "조회 안됨");
                }
            }
        }
    }

    # 휴대폰번호 인증번호 확인
    function check_pwd_auth()
    {
        $phone = input_post("mm_phone");
        $auth = input_post("auth");

        $check = $this->site_model->checkSmsAuth($phone, $auth);

        json_return($check["code"], $check["msg"]);
    }

    # 임시 비밀번호 발송 처리
    function send_temp_pwd()
    {
        $phone = input_post("mm_phone");
        //$email = input_post("mm_email");

        //$mem_info = $this->member_model->getMember(["mm_id"=>$email]);
        $mem_info = $this->member_model->getMember(["mm_id" => $phone]);

        if ($mem_info == null) {
            json_return("91", "회원정보가 조회되지 않습니다. 다시 진행부탁드립니다.");
        } else {
            $authNum = rand(100000, 999999);
            $authNum = "pwd" . $authNum;
            $result = $this->common->sendSms($phone, "나우잡 임시 비밀번호 발송 [" . $authNum . "]", $authNum);

            if ($result["code"] == "00") {
                $result = $this->member_model->updateMember(["mm_pwd" => $this->common->getSqlPass($authNum)], ["mm_idx" => $mem_info["mm_idx"]]);

                if ($result["code"] == "00") {
                    json_return("00", "처리완료");
                } else {
                    json_return("92", "비밀번호 업데이트시 오류가 발생하였습니다. 관리자에 문의 해주세요.");
                }
            } else {
                json_return("99", "SMS발송시 오류가 발생하였습니다. 관리자에 문의 해주세요.");
            }
        }
    }

    # 구글 회원가입용 전화번호 확인 및 SMS 인증번호 발송
    function check_google_phone()
    {
        $phone = input_post("mm_phone");

        // 전화번호 정규화 (하이픈, 공백, 점 제거)
        $normalized_phone = preg_replace('/[^0-9]/', '', $phone);

        if ($normalized_phone == '') {
            json_return("91", "전화번호를 입력해주세요.");
            return;
        }

        // 전화번호로 회원 조회 (가입 타입 무관하게 모두 조회)
        $query = "SELECT * FROM st_member
                  WHERE REPLACE(REPLACE(REPLACE(mm_phone, '-', ''), ' ', ''), '.', '') = '" . $this->db->escape_str($normalized_phone) . "'
                  AND mm_status = 'Y'
                  LIMIT 1";
        $result = $this->db->query($query);
        $mem_info = $result->row_array();

        if ($mem_info != null) {
            // 이미 가입된 회원 — 가입 경로별 메시지 반환
            $join_type = isset($mem_info["mm_join_type"]) ? strtoupper($mem_info["mm_join_type"]) : "WEB";

            if ($join_type === "GOOGLE") {
                $provider_label = "구글";
            } elseif ($join_type === "KAKAO") {
                $provider_label = "카카오";
            } elseif ($join_type === "NAVER") {
                $provider_label = "네이버";
            } elseif ($join_type === "APPLE") {
                $provider_label = "애플";
            } elseif ($join_type === "WECHAT") {
                $provider_label = "위쳇";
            }else {
                $provider_label = "휴대폰";
            }

            // 중복 계정 팝업용 join_type 정보 함께 반환
            json_return("99", $provider_label . "으로 가입된 회원입니다.", [
                "join_type" => $join_type,
                "mm_phone" => $mem_info["mm_phone"]
            ]);
        } else {
            // 미가입 — 발송 횟수 확인 후 SMS 인증번호 발송
            $count_result = $this->site_model->checkSmsSendCount($phone, "구글가입인증");

            if ($count_result["code"] != "00") {
                json_return("98", "하루 SMS 발송 횟수를 초과하였습니다. 내일 다시 진행해주세요.");
                return;
            }

            $authNum = rand(100000, 999999);
            $result = $this->common->sendSms($phone, "나우잡 인증 번호 발송 [" . $authNum . "]", $authNum);

            if ($result["code"] == "00") {
                json_return("00", "인증번호를 발송하였습니다.");
            } else {
                json_return("99", "SMS 발송 오류: " . $result["text"]);
            }
        }
    }

    # 구글 회원가입용 전화번호 인증번호 확인
    function check_google_phone_auth()
    {
        $phone = input_post("mm_phone");
        $auth  = input_post("auth");

        $check = $this->site_model->checkSmsAuth($phone, $auth);

        json_return($check["code"], $check["msg"]);
    }

    # 휴대폰 OTP 로그인용 전화번호 확인 및 SMS 인증번호 발송
    function check_phone_login()
    {
        $phone = input_post("mm_phone");

        // 전화번호 정규화 (하이픈, 공백, 점 제거)
        $normalized_phone = preg_replace('/[^0-9]/', '', $phone);

        if ($normalized_phone == '') {
            json_return("91", "전화번호를 입력해주세요.");
            return;
        }

        // 심사용 테스트 전화번호: 가입 여부와 무관하게 SMS 인증 생략하고 즉시 로그인/가입 처리
        if (in_array($normalized_phone, $this->test_phone_numbers, true)) {
            $query = "SELECT * FROM st_member
                      WHERE REPLACE(REPLACE(REPLACE(mm_phone, '-', ''), ' ', ''), '.', '') = '" . $this->db->escape_str($normalized_phone) . "'
                      AND mm_status = 'Y'
                      LIMIT 1";
            $result = $this->db->query($query);
            $existing_member = $result->row_array();

            if ($existing_member != null) {
                // 이미 가입된 테스트 번호 → 인증번호 입력 없이 바로 로그인
                $this->loginPhoneMember($existing_member);
                $this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $existing_member["mm_id"]]);
                json_return("00", "테스트 계정으로 즉시 로그인되었습니다.", ["skip_auth" => true]);
                return;
            }

            // 미가입 테스트 번호 → 즉시 회원가입 + 로그인
            $mem_info = $this->createAndLoginPhoneMember($normalized_phone);
            if ($mem_info != null) {
                json_return("00", "테스트 계정으로 즉시 회원가입되었습니다.", ["skip_auth" => true]);
                return;
            }
            json_return("99", "테스트 계정 처리 중 오류가 발생했습니다.");
            return;
        }

        // 가입 여부 확인 (미가입 회원은 SMS 발송 전에 회원가입 안내)
        $query = "SELECT mm_idx FROM st_member
                  WHERE REPLACE(REPLACE(REPLACE(mm_phone, '-', ''), ' ', ''), '.', '') = '" . $this->db->escape_str($normalized_phone) . "'
                  AND mm_status = 'Y'
                  LIMIT 1";
        $result = $this->db->query($query);
        $existing_member = $result->row_array();

        if ($existing_member == null) {
            json_return("90", "회원가입이 필요합니다.");
            return;
        }

        // SMS 발송 (가입된 회원만 발송)
        $count_result = $this->site_model->checkSmsSendCount($phone, "휴대폰로그인인증");

        if ($count_result["code"] != "00") {
            json_return("98", "하루 SMS 발송 횟수를 초과하였습니다. 내일 다시 진행해주세요.");
            return;
        }

        $authNum = rand(100000, 999999);
        $result = $this->common->sendSms($phone, "나우잡 인증 번호 발송 [" . $authNum . "]", $authNum);

        if ($result["code"] == "00") {
            json_return("00", "인증번호를 발송하였습니다.");
        } else {
            json_return("99", "SMS 발송 오류: " . $result["text"]);
        }
    }

    # 휴대폰번호로 회원 생성 및 로그인 (비밀번호 없음)
    function create_phone_member()
    {
        $phone = input_post("mm_phone");

        // 전화번호 정규화
        $normalized_phone = preg_replace('/[^0-9]/', '', $phone);

        if (empty($normalized_phone) || strlen($normalized_phone) < 10) {
            json_return("91", "전화번호를 정확히 입력해주세요.");
            return;
        }

        // 이미 존재하는지 확인
        $query = "SELECT * FROM st_member
                  WHERE REPLACE(REPLACE(REPLACE(mm_phone, '-', ''), ' ', ''), '.', '') = '" . $this->db->escape_str($normalized_phone) . "'
                  AND mm_status = 'Y'
                  LIMIT 1";
        $result = $this->db->query($query);
        $mem_info = $result->row_array();

        if ($mem_info != null) {
            // 이미 가입된 회원
            json_return("91", "이미 가입된 휴대폰번호입니다.");
            return;
        }

        // 새 회원 생성 (휴대폰번호 기반)
        $mm_id = "phone_" . $normalized_phone . "_" . time(); // 고유 아이디 자동 생성
        $mm_pwd = password_hash("phone_signup_" . time(), PASSWORD_DEFAULT); // 더미 비밀번호

        $insert_data = [
            "mm_id" => $mm_id,
            "mm_pwd" => $mm_pwd,
            "mm_phone" => $normalized_phone,
            "mm_join_type" => "WEB",
            "mm_status" => "Y",
            "last_login_dt" => date("Y-m-d H:i:s"),
        ];

        $this->db->insert("st_member", $insert_data);
        $mm_idx = $this->db->insert_id();

        if (empty($mm_idx)) {
            json_return("99", "회원 생성에 실패했습니다.");
            return;
        }

        // 생성된 회원 정보 조회
        $mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);

        if ($mem_info == null) {
            json_return("99", "회원 정보를 찾을 수 없습니다.");
            return;
        }

        // 주소 정보 조회
        $addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mem_info["mm_idx"]]);
        if (!is_array($addr_info)) {
            $addr_info = [];
        }

        // 로그인 처리
        $this->user->setUser([
            "mm_idx" => $mem_info["mm_idx"],
            "mm_id" => $mem_info["mm_id"],
            "mm_point" => $mem_info["mm_point"],
            "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
            "mm_type" => $mem_info["mm_type"],
            "mm_join_type" => $mem_info["mm_join_type"],
            "mm_img" => $mem_info["mm_thumb"],
            "ad_addr" => isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : "",
            "ad_gps_x" => isset($addr_info["ad_gps_x"]) ? $addr_info["ad_gps_x"] : "",
            "ad_gps_y" => isset($addr_info["ad_gps_y"]) ? $addr_info["ad_gps_y"] : "",
            "mm_sex" => $mem_info["mm_sex"],
        ]);

        // 세션 명시적 저장 (이후 요청에서 세션 데이터 보장)
        if (function_exists('session_write_close')) {
            session_write_close();
        }

        json_return("00", "회원가입 완료", ["mm_id" => $mem_info["mm_id"]]);
    }

    # 휴대폰 OTP 로그인용 인증번호 확인 및 로그인 처리
    function check_phone_login_auth()
    {
        $phone = input_post("mm_phone");
        $auth  = input_post("auth");

        // 전화번호 정규화
        $normalized_phone = preg_replace('/[^0-9]/', '', $phone);

        // 인증번호 필수 확인
        if (empty($auth)) {
            json_return("99", "인증번호를 입력해주세요.");
            return;
        }

        $check = $this->site_model->checkSmsAuth($phone, $auth);

        if ($check["code"] != "00") {
            json_return($check["code"], $check["msg"]);
            return;
        }

        // 전화번호로 회원 재조회
        $query = "SELECT * FROM st_member
                  WHERE REPLACE(REPLACE(REPLACE(mm_phone, '-', ''), ' ', ''), '.', '') = '" . $this->db->escape_str($normalized_phone) . "'
                  AND mm_status = 'Y'
                  LIMIT 1";
        $result = $this->db->query($query);
        $mem_info = $result->row_array();

        if ($mem_info == null) {
            // 미가입 회원 → 회원가입 페이지로 안내
            json_return("90", "회원가입이 필요합니다.", ["phone" => $normalized_phone]);
            return;
        }

        // 주소 정보 조회
        $addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mem_info["mm_idx"]]);

        // 로그인 처리 (setUser 배열 형식으로 호출)
        $this->user->setUser([
            "mm_idx" => $mem_info["mm_idx"],
            "mm_id" => $mem_info["mm_id"],
            "mm_point" => $mem_info["mm_point"],
            "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
            "mm_type" => $mem_info["mm_type"],
            "mm_join_type" => $mem_info["mm_join_type"],
            "mm_img" => $mem_info["mm_thumb"],
            "ad_addr" => $addr_info["ad_addr"],
            "ad_gps_x" => $addr_info["ad_gps_x"],
            "ad_gps_y" => $addr_info["ad_gps_y"],
            "mm_sex" => $mem_info["mm_sex"],
        ]);

        // 접속 처리 (last_login_dt 갱신)
        $this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $mem_info["mm_id"]]);

        json_return("00", "로그인 완료");
    }

    # SNS 회원가입용 전화번호 확인 및 SMS 인증번호 발송 (위챗/애플 등)
    function check_sns_phone()
    {
        $phone = input_post("mm_phone");

        // 전화번호 정규화 (하이픈, 공백, 점 제거)
        $normalized_phone = preg_replace('/[^0-9]/', '', $phone);

        if ($normalized_phone == '') {
            json_return("91", "전화번호를 입력해주세요.");
            return;
        }

        // 공용 헬퍼로 중복 체크
        $duplicate_check = checkDuplicatePhoneForSns($normalized_phone);
        if ($duplicate_check["is_duplicate"]) {
            // 중복 계정 감지 → code 91 + 구조화 데이터 반환
            json_return("91", $duplicate_check["message"], [
                "join_type" => $duplicate_check["join_type"],
                "mm_phone" => isset($duplicate_check["mm_phone"]) ? $duplicate_check["mm_phone"] : ""
            ]);
            return;
        }

        // 미가입 — 발송 횟수 확인 후 SMS 인증번호 발송
        $count_result = $this->site_model->checkSmsSendCount($phone, "SNS가입인증");

        if ($count_result["code"] != "00") {
            json_return("98", "하루 SMS 발송 횟수를 초과하였습니다. 내일 다시 진행해주세요.");
            return;
        }

        $authNum = rand(100000, 999999);
        $result = $this->common->sendSms($phone, "나우잡 인증 번호 발송 [" . $authNum . "]", $authNum);

        if ($result["code"] == "00") {
            json_return("00", "인증번호를 발송하였습니다.");
        } else {
            json_return("99", "SMS 발송 오류: " . $result["text"]);
        }
    }

    # SNS 회원가입용 전화번호 인증번호 확인
    function check_sns_phone_auth()
    {
        $phone = input_post("mm_phone");
        $auth  = input_post("auth");

        $check = $this->site_model->checkSmsAuth($phone, $auth);

        json_return($check["code"], $check["msg"]);
    }

    # 가입 인증 문자 발송 처리
    function sendSmsAuth()
    {
        $phone = input_post("hpno");

        $mem_info = $this->member_model->getMember(["mm_phone" => $phone]);

        if ($mem_info == null) {
            //json_return("90","조회 안됨");
        } else {
            // 가입 경로별 메시지 생성
            $join_type = isset($mem_info["mm_join_type"]) ? strtoupper($mem_info["mm_join_type"]) : "";
            if ($join_type === "WEB") {
                $provider_label = "핸드폰번호";
                $postposition = "로";
            } elseif ($join_type === "NAVER") {
                $provider_label = "네이버";
                $postposition = "로";
            } elseif ($join_type === "KAKAO") {
                $provider_label = "카카오톡";
                $postposition = "으로";
            } elseif ($join_type === "WECHAT") {
                $provider_label = "위쳇";
                $postposition = "으로";
            }  elseif ($join_type === "GOOGLE") {
                $provider_label = "구글";
                $postposition = "에서";
            }else {
                $provider_label = "NOWJOB";
                $postposition = "에서";
            }

            $alert_msg = $provider_label . $postposition . " 가입된 회원 정보 입니다.";

            json_return("99", $alert_msg, [""]);
            return;
        }


        $authNum = rand(100000, 999999);
        $result = $this->common->sendSms($phone, "나우잡 인증문자 발송 [" . $authNum . "]", $authNum);

        if ($result["code"] == "00") {
            json_return("00", "처리완료");
        } else {
            json_return("99", "인증문자 발송에 실패하였습니다.");
        }
    }

    # 가입 인증 문자 확인
    function confSmsAuth()
    {
        $phone = input_post("hpno");
        $confno = input_post("confno");

        $authNum = rand(100000, 999999);
        $result = $this->common->sendSms($phone, "나우잡 인증문자 발송 [" . $authNum . "]", $authNum);

        if ($result["code"] == "00") {
            json_return("00", "처리완료");
        } else {
            json_return("99", "인증문자 발송에 실패하였습니다.");
        }
    }

    # 본인인증 타입 처리
    function join_auth()
    {
        // POST로 전달받은 회원 타입: "MEM" (개인회원) 또는 "COMP" (기업회원)
        $type = input_post("type", "");

        // 세션에 본인인증 타입 저장 (나중에 nice_success()에서 사용)
        // 값: "MEM" (개인회원) 또는 "COMP" (기업회원)
        $this->common->setSession("AUTH_TYPE", $type);

        if ($type == "MEM") {
            # 로그 남기기
            $this->site_model->setSiteLog(
                [
                    // 현재 세션 ID (세션별 로그 추적용)
                    "session_id" => session_id(),
                    // 로그 타입: "JOIN" (회원가입 관련 로그)
                    "sl_type" => "JOIN",
                    // 로그 단계: "개인_인증진행" (개인회원 본인인증 진행 중)
                    "sl_step" => "개인_인증진행",
                    // 로그 텍스트: JSON 형식의 추가 데이터 (현재 $return 변수가 정의되지 않아 빈 값 저장됨 - 버그 가능성)
                    "sl_txt" => json_encode($return, JSON_UNESCAPED_UNICODE)
                ]
            );
        } else {
            # 로그 남기기
            $this->site_model->setSiteLog(
                [
                    // 현재 세션 ID (세션별 로그 추적용)
                    "session_id" => session_id(),
                    // 로그 타입: "JOIN" (회원가입 관련 로그)
                    "sl_type" => "JOIN",
                    // 로그 단계: "기업_인증진행" (기업회원 본인인증 진행 중)
                    "sl_step" => "기업_인증진행",
                    // 로그 텍스트: JSON 형식의 추가 데이터 (현재 $return 변수가 정의되지 않아 빈 값 저장됨 - 버그 가능성)
                    "sl_txt" => json_encode($return, JSON_UNESCAPED_UNICODE)
                ]
            );
        }

        // 세션에 기업회원 본인인증 요청 시간 저장 (5분 이내 인증 완료 체크용)
        // 값: "Y-m-d H:i:s" 형식의 현재 시간 (예: "2025-01-20 14:30:00")
        $this->common->setSession("COM_MAN_REQ", date("Y-m-d H:i:s"));

        // 세션에 본인인증 요청 경로 저장 (회원가입 플로우인지 구분용) 어떤 상황에서 인증을 하는지
        // 값: "JOIN" (회원가입 경로임을 표시)
        $this->common->setSession("PASS_REQ_PATH", "JOIN");

        json_return("00", "처리완료");
    }

    function com_man_update()
    {
        $this->common->setSession("COM_MAN_REQ", date("Y-m-d H:i:s"));
        json_return("00", "처리완료");
    }

    function com_man_retire()
    {
        $this->common->setSession("COM_MAN_RETIRE", date("Y-m-d H:i:s"));
        json_return("00", "처리완료");
    }

    # 본인인증 성공
    function nice_success()
    {

        //echo "<script>alert('nice_success');</script>"; //test

        $headers = apache_request_headers();
        $os_type = isset($headers["os_type"]) ? $headers["os_type"] : "";
        if ($os_type === '') {
            $ua = $_SERVER['HTTP_USER_AGENT'] ? $_SERVER['HTTP_USER_AGENT'] : '';
            $isIOS = preg_match('/\b(iPhone|iPad|iPod)\b/i', $ua) || (stripos($ua, 'Macintosh') !== false && stripos($ua, 'Mobile') !== false);
            $isAndroid = preg_match('/NowJobApp_Android|Android/i', $ua);

            if ($isIOS) {
                $os_type = 'ios';
            } else if ($isAndroid) {
                $os_type = 'android';
            }
        }

        //echo "<script>alert('".$os_type."');</script>";//test

        $return = $this->member_model->getNiceReturnData("success");

        $return["name"] = urldecode($return["name"]);

        if ($return["code"] != "00") {
            # 로그 남기기
            $this->site_model->setSiteLog(
                [
                    "session_id" => session_id(),
                    "sl_type" => "JOIN",
                    "sl_step" => "본인인증_오류",
                    "sl_txt" => json_encode($return, JSON_UNESCAPED_UNICODE)
                ]
            );

            if ($os_type == "ios") {
                echo "<script>alert('인증 오류가 발생했습니다.다시 진행해주세요.');location.href='" . "/member/login" . "';</script>";
            } else if ($os_type == "android") {
                // 2025-01-17: 안드로이드 앱 전용 처리 - opener 없이 직접 이동 (WebView 보안 정책 문제 해결)
                echo "<script>alert('인증 오류가 발생했습니다.다시 진행해주세요.');location.href='" . "/member/login" . "';</script>";
            } else {
                echo "<script>alert('인증 오류가 발생했습니다.다시 진행해주세요.');opener.location.href='" . "/member/login" . "';self.close();</script>";
            }
            exit;
        }

        // SNS 회원가입인지 확인 (SNS 회원가입의 경우 탈퇴 신청 체크 건너뛰기)
        $check_sns = $this->site_model->getSiteLog(
            ["session_id" => session_id(), "sl_type" => "JOIN", "sl_step" => "SNS", "sl_status" => "Y"]
        );
        $is_sns_join = ($check_sns != null);

        // 회원가입 플로우인지 확인 (회원가입의 경우 탈퇴 신청 체크 건너뛰기)
        $PASS_REQ_PATH = $this->common->getSession("PASS_REQ_PATH");
        $is_join_flow = ($PASS_REQ_PATH == "JOIN");

        $COM_MAN_RETIRE = $this->common->getSession("COM_MAN_RETIRE");
        // 회원가입 플로우가 아니고 SNS 회원가입이 아닐 때만 탈퇴 신청 체크
        if ($COM_MAN_RETIRE != null && !$is_sns_join && !$is_join_flow) {
            $now = new DateTime(); // 현재 시간
            $comManReqDateTime = new DateTime($COM_MAN_RETIRE); // 비교할 시간
            $interval = $now->getTimestamp() - $comManReqDateTime->getTimestamp();
            if ($interval > 5 * 60) {
                //5분이 경과했으면 오류처리
                if ($os_type == "ios") {
                    echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>인증 오류</title>
</head>
<body>
    <script>
        alert('5분이내에 인증 해주세요.(탈퇴신청)');
        location.href='/mypage/mypage';
    </script>
</body>
</html>";
                } else if ($os_type == "android") {
                    // 2025-01-17: 안드로이드 앱 전용 처리 - opener 없이 직접 이동 (WebView 보안 정책 문제 해결)
                    echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>인증 오류</title>
</head>
<body>
    <script>
        alert('5분이내에 인증 해주세요.(탈퇴신청)');
        location.href='/mypage/mypage';
    </script>
</body>
</html>";
                } else {
                    echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>인증 오류</title>
</head>
<body>
    <script>
        alert('5분이내에 인증 해주세요.(탈퇴신청)');
        window.close();
    </script>
</body>
</html>";
                }
                exit;
            } else {
                //5분이내 정상 - 팝업 창에서 직접 탈퇴 처리
                echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>인증 완료</title>
    <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
</head>
<body>
    <div style='text-align: center; padding: 50px;'>
        <h2>인증이 완료되었습니다.</h2>
        <p>탈퇴 처리를 진행합니다...</p>
    </div>
    <script>
        // 탈퇴 처리 API 호출
        var params = {
            fire_reason: 'PASS인증 탈퇴',
            fire_reason_desc: 'PASS 인증을 통한 탈퇴'
        };
        
        $.ajax({
            type: 'POST',
            url: '/setting_proc/member_fire',
            data: params,
            success: function(ret) {
                if (ret.code == '00') {
                    alert('탈퇴가 완료되었습니다.');
                    // 부모 창으로 리다이렉트
                    if (window.opener) {
                        try {
                            window.opener.location.href = '/member/login';
                        } catch (e) {
                            // 부모 창 리다이렉트 실패 시 무시
                        }
                    }
                    setTimeout(function() {
                        window.close();
                    }, 1000);
                } else {
                    alert('탈퇴 처리 중 오류가 발생했습니다: ' + ret.msg);
                    if (window.opener) {
                        window.opener.location.reload();
                    }
                    setTimeout(function() {
                        window.close();
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                alert('서버 통신 오류가 발생했습니다. 다시 시도해주세요.');
                if (window.opener) {
                    window.opener.location.reload();
                }
                setTimeout(function() {
                    window.close();
                }, 2000);
            }
        });
    </script>
</body>
</html>";
                exit;
            }
        }


        //echo "<script>alert('test');</script>";//test
        //print_r($return); //test
        //Array ( [code] => 00 [msg] => 처리 성공 [errcode] => [requestnumber] =>  [responsenumber] =>  [authtype] => M [name] => 홍길동 [birthdate] => 19990101 [gender] => 1 [nationalinfo] => 0 [dupinfo] =>  [conninfo] => [mobileno] => 01012345678 [mobileco] => )

        // 회원가입 플로우일 때 기 가입 확인 (전화번호로 확인)
        if ($is_join_flow) {
            // 본인인증 로그 기록
            $sl_txt = [
                "mm_name" => $return["name"],
                "mm_sex" => ($return["gender"] == "1" ? "남" : "여"),
                "mm_nation" => ($return["nationalinfo"] == "0" ? "내국인" : "외국인"),
                "mm_birth" => $return["birthdate"],
                "mm_phone" => $return["mobileno"],
                "mm_ci" => "",
                "mm_di" => $return["dupinfo"],
            ];

            # 로그 남기기
            $type = $this->common->getSession("AUTH_TYPE");
            $this->site_model->setSiteLog(
                [
                    "session_id" => session_id(),
                    "sl_type" => "JOIN_" . $type,
                    "sl_step" => "본인인증",
                    "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
                ],
                [
                    "session_id" => session_id(),
                    "sl_type" => "JOIN_" . $type,
                    "sl_step" => "본인인증",
                    "sl_status" => "Y"
                ]
            );

            // 전화번호 형식 정규화 (하이픈 제거)
            $normalized_phone = preg_replace('/[^0-9]/', '', $return["mobileno"]);

            // SQL로 전화번호 조회 (하이픈 제거 후 비교)
            // DB에는 '010-9002-5528' 형식으로 저장되어 있으므로 REPLACE로 하이픈 제거 후 비교
            $query = "SELECT * FROM st_member
                      WHERE REPLACE(REPLACE(REPLACE(mm_phone, '-', ''), ' ', ''), '.', '') = '" . $this->db->escape_str($normalized_phone) . "'
                      AND mm_status = 'Y'
                      LIMIT 1";
            $result = $this->db->query($query);
            $mem_info = $result->row_array();

            if ($mem_info != null && !empty($mem_info)) {
                // 가입 경로별 알림 문구 생성 (WEB: NOWJOB에서, NAVER: 네이버로, KAKAO: 카카오로)
                $join_type = isset($mem_info["mm_join_type"]) ? strtoupper($mem_info["mm_join_type"]) : "";
                if ($join_type === "WEB") {
                    $provider_label = "NOWJOB";
                    $postposition = "에서";
                } elseif ($join_type === "NAVER") {
                    $provider_label = "네이버";
                    $postposition = "로";
                } elseif ($join_type === "KAKAO") {
                    $provider_label = "카카오";
                    $postposition = "로";
                } elseif ($join_type === "GOOGLE") {
                    $provider_label = "구글";
                    $postposition = "에서";
                }elseif ($join_type === "WECHAT") {
                    $provider_label = "위쳇";
                    $postposition = "에서";
                }else {
                    $provider_label = "NOWJOB";
                    $postposition = "에서";
                }

                $alert_msg = $provider_label . $postposition . " 이미 가입된 전화번호 입니다.";

                if ($os_type == "ios") {
                    echo "<script>alert('" . $alert_msg . "');location.href='/member/login';</script>";
                } else if ($os_type == "android") {
                    echo "<script>alert('" . $alert_msg . "');location.href='/member/login';</script>";
                } else {
                    // 웹 브라우저: 팝업창이므로 부모 창을 로그인 페이지로 이동 후 팝업 닫기
                    echo "<script>
                        alert('" . $alert_msg . "');
                        if (window.opener) {
                            try {
                                window.opener.location.href = '/member/login';
                            } catch (e) {
                                // 부모 창 접근 실패 시 현재 창에서 이동
                                location.href = '/member/login';
                            }
                        } else {
                            location.href = '/member/login';
                        }
                        setTimeout(function() {
                            window.close();
                        }, 100);
                    </script>";
                }
                exit;
            }
        }

        $COM_MAN_REQ = $this->common->getSession("COM_MAN_REQ");
        // 회원가입 플로우가 아니고 SNS 회원가입이 아닐 때만 기업회원 본인인증 체크
        if ($COM_MAN_REQ != null && !$is_sns_join && !$is_join_flow) {
            $now = new DateTime(); // 현재 시간
            $comManReqDateTime = new DateTime($COM_MAN_REQ); // 비교할 시간
            $interval = $now->getTimestamp() - $comManReqDateTime->getTimestamp();
            if ($interval > 5 * 60) {
                //5분이 경과했으면 오류처리
                if ($os_type == "ios") {
                    echo "<script>alert('5분이내에 인증 해주세요.');location.href='" . "/compage/mypage" . "';</script>";
                } else if ($os_type == "android") {
                    // 2025-01-17: 안드로이드 앱 전용 처리 - opener 없이 직접 이동 (WebView 보안 정책 문제 해결)
                    echo "<script>alert('5분이내에 인증 해주세요.');location.href='" . "/compage/mypage" . "';</script>";
                } else {
                    echo "<script>alert('5분이내에 인증 해주세요.');self.close();</script>";
                }
                exit;
            } else {

                // 본인인증 로그 기록 start 25.02.26
                $sl_txt = [
                    "mm_name" => $return["name"],
                    "mm_sex" => ($return["gender"] == "1" ? "남" : "여"),
                    "mm_nation" => ($return["nationalinfo"] == "0" ? "내국인" : "외국인"),
                    "mm_birth" => $return["birthdate"],
                    "mm_phone" => $return["mobileno"],
                    "mm_ci" => "",
                    "mm_di" => $return["dupinfo"],
                ];

                # 로그 남기기
                $type = $this->common->getSession("AUTH_TYPE");

                $this->site_model->setSiteLog(
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN_" . $type,
                        "sl_step" => "본인인증",
                        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
                    ],
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN_" . $type,
                        "sl_step" => "본인인증",
                        "sl_status" => "Y"
                    ]
                );
                // 본인인증 로그 기록 end 25.02.26

                //echo "<script>alert('ok');</script>"; //test

                //5분이내 정상
                $gender = "";
                if ($return["gender"] == "1") $gender = "남";
                if ($return["gender"] == "2") $gender = "여";

                $nationalinfo = "";
                if ($return["nationalinfo"] == "0") $nationalinfo = "내국인";
                else $nationalinfo = "외국인";

                // 기 가입 확인 시작 25.02.26
                $mem_info = $this->member_model->getMember(["mm_phone" => $return["mobileno"]]);
                if ($mem_info == null) {
                    //json_return("90","조회 안됨");
                } else {
                    // 가입 경로별 알림 문구 생성 (WEB: NOWJOB에서, NAVER: 네이버로, KAKAO: 카카오로)
                    $join_type = isset($mem_info["mm_join_type"]) ? strtoupper($mem_info["mm_join_type"]) : "";
                    if ($join_type === "WEB") {
                        $provider_label = "NOWJOB";
                        $postposition = "에서"; // NOWJOB에서
                    } elseif ($join_type === "NAVER") {
                        $provider_label = "네이버";
                        $postposition = "로"; // 네이버로
                    } elseif ($join_type === "KAKAO") {
                        $provider_label = "카카오";
                        $postposition = "로"; // 카카오로
                    } elseif ($join_type === "GOOGLE") {
                        $provider_label = "구글";
                        $postposition = "에서";
                    }elseif ($join_type === "WECHAT") {
                        $provider_label = "위쳇";
                        $postposition = "에서";
                    }else {
                        // 알 수 없는 타입일 경우 NOWJOB으로 처리
                        $provider_label = "NOWJOB";
                        $postposition = "에서";
                    }

                    $alert_msg = $provider_label . $postposition . " 이미 가입된 전화번호 입니다.";

                    if ($os_type == "ios") {
                        echo "<script>alert('" . $alert_msg . "');location.replace('/member/join_start');</script>";
                        exit;
                    } else if ($os_type == "android") {
                        // 2025-01-17: 안드로이드 앱 전용 처리 - opener 없이 직접 이동 (WebView 보안 정책 문제 해결)
                        echo "<script>alert('" . $alert_msg . "');location.replace('/member/join_start');</script>";
                        exit;
                    } else {
                        echo "<script>alert('" . $alert_msg . "');self.close();</script>";
                        exit;
                    }
                }
                // 기 가입 확인 종료 25.02.26

                if ($os_type == "ios") {

                    //echo "<script>alert('userIdx:".$this->user->getUserIdx()."');</script>";//test

                    //echo '//인증 저장 후 화면 이동';
                    // echo '  <input id="mm_name"  name="mm_name"  value="'.$return["name"].'">';
                    // echo '  <input id="mm_phone" name="mm_phone" value="'.$return["mobileno"].'">';
                    // echo '  <input id="mm_di"    name="mm_di"    value="'.$return["dupinfo"].'">';
                    // echo '  <input id="mm_sex"   name="mm_sex"   value="'.$gender.'">';
                    // echo '</form>';
                    // echo '<script>';
                    // echo 'var form = $(\'#frm2\');';
                    // echo 'var params = new FormData(form[0]);';
                    // echo 'console.log(params);';

                    //   echo 'fileAjax("/compage_proc/com_biz_update2",params,function(ret){';
                    //   echo '    //alert(ret);';
                    //   echo '    console.log(ret);';
                    //   echo '    if(ret.code=="00"){';
                    //   echo '      location.href=\'"."/compage/index?gbn=setting"."\';';
                    //   echo '    }else{';
                    //   echo '      commonAlert(ret.msg,function(){});';
                    //   echo '      history.back();';
                    //   echo '    }';
                    //   echo '});';
                    //   echo '</script>';
                    // echo '</div>';

                    $result = $this->member_model->updateMember(["mm_name" => $return["name"]], ["mm_idx" => $this->user->getUserIdx()]);
                    $result = $this->member_model->updateMember(["mm_phone" => $return["mobileno"]], ["mm_idx" => $this->user->getUserIdx()]);
                    $result = $this->member_model->updateMember(["mm_di" => $return["dupinfo"]], ["mm_idx" => $this->user->getUserIdx()]);
                    $result = $this->member_model->updateMember(["mm_sex" => $gender], ["mm_idx" => $this->user->getUserIdx()]);

                    $mem_info = $this->member_model->getMember(["mm_idx" => $this->user->getUserIdx()]);

                    //echo "<script>alert('update');</script>";//test
                    //echo "<script>alert('".$type."');</script>";//test

                    if ($type == "COMP") { //$mem_info["mm_comp_type"]=="COMP"
                        //echo "<script> location.href='/compage/index?gbn=setting'; </script>";
                        echo "<script> location.href='/member/join_comp_toturial'; </script>";
                    } else {
                        //echo "<script>alert('go mem');</script>";//test
                        //echo "<script> location.href='/mypage/index?gbn=setting'; </script>";
                        echo "<script> location.href='/member/join_mem_toturial'; </script>";
                    }
                    exit;
                } else {
                    // 안드로이드 앱인 경우 별도 처리
                    if ($os_type == "android") {
                        echo "<script>
                            // PASS 인증 콜백 페이지가 히스토리를 생성하지 못하게 강제 제거
                            window.history.replaceState(null, '', '/member/join_start');
                            // 튜토리얼 페이지로 이동 (히스토리에 추가하지 않음)
                            location.replace('/member/join_mem_toturial');
                        </script>";
                    } else {
                        // 웹 브라우저용 기존 로직 (opener 사용)
                        echo "<script>
                            // 전역 오류 처리 추가
                            window.onerror = function() { return true; };
                            
                            // opener 안전성 검사
                            if (typeof opener === 'undefined' || opener === null) {
                                location.href='/member/join_mem_toturial';
                                return;
                            }
                            
                            try {
                                // opener.document 접근 시도 전 안전성 검사
                                if (!opener.document) {
                                    location.href='/member/join_mem_toturial';
                                    return;
                                }
                                
                                //alert(opener.document.getElementsByName('bizPhone')[0].value);
                                //alert(opener.document.getElementsByName('bizName' )[0].value);
                                //alert(opener.document.getElementsByName('mm_name')[0].value);
                                //alert(opener.document.getElementsByName('mm_phone')[0].value);
                                //alert(opener.document.getElementsByName('mm_di')[0].value);
                                //alert(opener.document.getElementsByName('mm_sex').length);
                                //alert('test0');//test
                                
                                var bizPhoneElements = opener.document.getElementsByName('bizPhone');
                                if (bizPhoneElements.length > 0 && bizPhoneElements[0].value.indexOf('010') > -1){
                                    var bizNameElements = opener.document.getElementsByName('bizName');
                                    if (true ||
                                       (bizPhoneElements.length > 0 && bizPhoneElements[0].value == '" . $return["mobileno"] . "') ||
                                       (bizNameElements.length > 0 && bizNameElements[0].value == '" . $return["mobileno"] . "')) {
                                      
                                      // 안전하게 요소에 값 설정
                                      var mmNameElements = opener.document.getElementsByName('mm_name');
                                      var mmPhoneElements = opener.document.getElementsByName('mm_phone');
                                      var mmDiElements = opener.document.getElementsByName('mm_di');
                                      
                                      if (mmNameElements.length > 0) mmNameElements[0].value = '" . $return["name"] . "';
                                      if (mmPhoneElements.length > 0) mmPhoneElements[0].value = '" . $return["mobileno"] . "';
                                      if (mmDiElements.length > 0) mmDiElements[0].value = '" . $return["dupinfo"] . "';

                                      //alert('test1');//test
                                      var mmSexElements = opener.document.getElementsByName('mm_sex');
                                      if (mmSexElements.length > 0) {
                                          // 첫 번째 요소에 값 설정
                                          mmSexElements[0].value = '" . $gender . "';
                                      } else {
                                          console.log('mm_sex 요소가 존재하지 않습니다.');
                                      }
                                      //alert('test2');//test

                                      // 부모창의 URL 가져오기
                                      try {
                                          let parentUrl = opener.location.href;
                                          //alert(parentUrl);
                                          if(parentUrl.indexOf('member/join_start') > -1){
                                              self.close();
                                              if (typeof opener.fsubmit === 'function') {
                                                  opener.fsubmit();
                                              }
                                          }else{
                                              self.close();
                                          }
                                      } catch(e) {
                                          self.close();
                                      }

                                    }else{
                                      try {
                                          var dimcont6 = opener.document.getElementById('dimcont6');
                                          if (dimcont6) {
                                              dimcont6.style.display = 'block';
                                          }
                                      } catch(e) {
                                          console.log('dimcont6 요소 접근 오류');
                                      }
                                      self.close();
                                    }
                                } else {
                                    //전화번호가 아니면 (카카오톡, 아이폰가입이면)
                                    //전화번호를 update
                                    var mmNameElements = opener.document.getElementsByName('mm_name');
                                    var mmPhoneElements = opener.document.getElementsByName('mm_phone');
                                    var mmDiElements = opener.document.getElementsByName('mm_di');
                                    
                                    if (mmNameElements.length > 0) mmNameElements[0].value = '" . $return["name"] . "';
                                    if (mmPhoneElements.length > 0) mmPhoneElements[0].value = '" . $return["mobileno"] . "';
                                    if (mmDiElements.length > 0) mmDiElements[0].value = '" . $return["dupinfo"] . "';

                                    var mmSexElements = opener.document.getElementsByName('mm_sex');
                                    if (mmSexElements.length > 0) {
                                        // 첫 번째 요소에 값 설정
                                        mmSexElements[0].value = '" . $gender . "';
                                    } else {
                                        console.log('mm_sex 요소가 존재하지 않습니다.');
                                    }

                                    self.close();
                                }
                            } catch(e) {
                                // opener 관련 오류 발생 시 대체 처리
                                console.log('opener 접근 오류:', e);
                                location.href='/member/join_mem_toturial';
                            }
                        </script>";
                    }

                    exit; // 하단에서 '본인인증' 등록하도록 주석처리 25.02.26

                }
            }
        }

        # 중복확인
        $mem_info = $this->member_model->getMember(["mm_di" => $return["dupinfo"]]);
        if ($mem_info != null) {
            $is_fire = false;
            if ($mem_info["mm_status"] == "N") {
                $fire_dt =  substr($mem_info["fire_dt"], 0, 10);
                $diff_dt = date('Y-m-d', strtotime(date("Y-m-d H:i:s") . " -30 days"));

                if ($fire_dt > $diff_dt) {
                    $is_fire = true;
                }
            }

            if ($mem_info["mm_status"] != "H" && $is_fire == false) {
                $this->common->setSession("AUTH_DI", $return["dupinfo"]);
                if ($os_type == "ios") {
                    echo "<script>location.href='" . "/member/join_error" . "';</script>";
                } else if ($os_type == "android") {
                    // 2025-01-17: 안드로이드 앱 전용 처리 - opener 없이 직접 이동 (WebView 보안 정책 문제 해결)
                    echo "<script>location.href='" . "/member/join_error" . "';</script>";
                } else {
                    echo "<script>opener.location.href='" . "/member/join_error" . "';self.close();</script>";
                }
                exit;
            }
        }

        $sl_txt = [
            "mm_name" => $return["name"],
            "mm_sex" => ($return["gender"] == "1" ? "남" : "여"),
            "mm_nation" => ($return["nationalinfo"] == "0" ? "내국인" : "외국인"),
            "mm_birth" => $return["birthdate"],
            "mm_phone" => $return["mobileno"],
            "mm_ci" => "",
            "mm_di" => $return["dupinfo"],
        ];

        # 로그 남기기
        $type = $this->common->getSession("AUTH_TYPE");

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_" . $type,
                "sl_step" => "본인인증",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_" . $type,
                "sl_step" => "본인인증",
                "sl_status" => "Y"
            ]
        );

        if ($type == "COMP") {
            # 기업회원 가입 이동
            // 주석처리: 기업회원 SNS는 NICE 본인인증을 하지 않으므로 이 코드는 실행되지 않음
            /*
            // SNS 회원가입인 경우 본인인증 완료 후 동의 팝업 표시
            if ($is_sns_join) {
                // 세션에 동의 팝업 표시 플래그 저장
                $this->common->setSession("SHOW_AGREEMENT_POPUP", "Y");

                if (getDeviceType() == "web") {
                    echo "<script>alert('본인인증이 완료 되었습니다.');opener.location.href='" . "/member/join_start?join_type=sns&show_agreement=comp" . "';self.close();</script>";
                } else {
                    if ($os_type == "ios") {
                        echo "<script>alert('본인인증이 완료 되었습니다.');location.href='" . "/member/join_start?join_type=sns&show_agreement=comp" . "';</script>";
                    } else if ($os_type == "android") {
                        // 2025-01-17: 안드로이드 앱 전용 처리 - opener 없이 직접 이동 (WebView 보안 정책 문제 해결)
                        echo "<script>alert('본인인증이 완료 되었습니다.');location.href='" . "/member/join_start?join_type=sns&show_agreement=comp" . "';</script>";
                    } else {
                        echo "<script>alert('본인인증이 완료 되었습니다.');opener.location.href='" . "/member/join_start?join_type=sns&show_agreement=comp" . "';self.close();</script>";
                    }
                }
                exit;
            }
            */

            // 일반 회원가입 경로
            if (getDeviceType() == "web") {
                echo "<script>alert('본인인증이 완료 되었습니다.');opener.location.href='" . "/member/join_wcomp_step1" . "';self.close();</script>";
            } else {
                if ($os_type == "ios") {
                    echo "<script>location.href='" . "/member/join_comp_toturial" . "';</script>";
                } else if ($os_type == "android") {
                    // 2025-01-17: 안드로이드 앱 전용 처리 - opener 없이 직접 이동 (WebView 보안 정책 문제 해결)
                    // PASS 인증 콜백 페이지가 히스토리를 생성하지 못하게 강제 제거
                    echo "<script>
                        window.history.replaceState(null, '', '/member/join_start');
                        location.replace('/member/join_comp_toturial');
                    </script>";
                } else {
                    echo "<script>alert('본인인증이 완료 되었습니다.');opener.location.href='" . "/member/join_comp_toturial" . "';self.close();</script>";
                }
            }
            exit;
        } else {
            # 일반 회원가입 화면 이동 - PC/모바일 통일: 비밀번호 자동생성 경로(join_mem_toturial)로
            if (getDeviceType() == "web") {
                echo "<script>alert('본인인증이 완료 되었습니다.');opener.location.href='" . "/member/join_mem_toturial" . "';self.close();</script>";
            } else {
                if ($os_type == "ios") {
                    echo "<script>location.href='" . "/member/join_mem_toturial" . "';</script>";
                } else if ($os_type == "android") {
                    // 2025-01-17: 안드로이드 앱 전용 처리 - opener 없이 직접 이동 (WebView 보안 정책 문제 해결)
                    // PASS 인증 콜백 페이지가 히스토리를 생성하지 못하게 강제 제거
                    echo "<script>
                        window.history.replaceState(null, '', '/member/join_start');
                        location.replace('/member/join_mem_toturial');
                    </script>";
                } else {
                    echo "<script>alert('본인인증이 완료 되었습니다.');opener.location.href='" . "/member/join_mem_toturial" . "';self.close();</script>";
                }
            }
            exit;
        }
    }

    function nice_fail()
    {
        $headers = apache_request_headers();
        $os_type = isset($headers["os_type"]) ? $headers["os_type"] : "";

        $return = $this->member_model->getNiceReturnData("fail");

        # 로그 남기기
        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN",
                "sl_step" => "본인인증_오류",
                "sl_txt" => json_encode($return, JSON_UNESCAPED_UNICODE)
            ]
        );

        if ($os_type == "ios") {
            echo "<script>alert('본인인증시 오류가 발생하였습니다. 다시 시도해주세요.');location.href='" . "/member/login" . "';self.close();</script>";
        } else if ($os_type == "android") {
            // 2025-01-17: 안드로이드 앱 전용 처리 - opener 없이 직접 이동 (WebView 보안 정책 문제 해결)
            echo "<script>alert('본인인증시 오류가 발생하였습니다. 다시 시도해주세요.');location.href='" . "/member/login" . "';</script>";
        } else {
            echo "<script>alert('본인인증시 오류가 발생하였습니다. 다시 시도해주세요.');opener.location.href='" . "/member/login" . "';self.close();</script>";
        }
        exit;
    }

    function nice_pass()
    {
        $sl_txt = [
            "mm_name" => "게스트",
            "mm_sex" => ("남"),
            "mm_nation" => ("내국인"),
            "mm_birth" => "2000-01-01",
            "mm_phone" => "010-0000-0000",
            "mm_ci" => "",
            "mm_di" => "pass_" . uniqid(),
        ];

        # 로그 남기기
        $type = $this->common->getSession("AUTH_TYPE");

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_" . $type,
                "sl_step" => "본인인증",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_" . $type,
                "sl_step" => "본인인증",
                "sl_status" => "Y"
            ]
        );

        if ($type == "COMP") {
            # 기업회원 가입 이동
            if (device_type() == "W") {
                echo "<script>location.href='" . "/member/join_wcomp_step1" . "';self.close();</script>";
            } else {
                if ($os_type == "ios") {
                    echo "<script>location.href='" . "/member/join_comp_toturial" . "';</script>";
                } else {
                    echo "<script>location.href='" . "/member/join_comp_toturial" . "';self.close();</script>";
                }
            }
            exit;
        } else {
            # 일반 회원가입 화면 이동
            if (device_type() == "W") {
                echo "<script>location.href='" . "/member/join_wmem_step1" . "';self.close();</script>";
            } else {
                if ($os_type == "ios") {
                    echo "<script>location.href='" . "/member/join_mem_toturial" . "';</script>";
                } else {
                    echo "<script>location.href='" . "/member/join_mem_toturial" . "';self.close();</script>";
                }
            }
            exit;
        }
    }


    function join_error_proc()
    {
        $session_di = $this->common->getSession("AUTH_DI");
        $mem_info = $this->member_model->getMember(["mm_di" => $session_di, "mm_status" => "Y"]);

        if ($mem_info == null) {
            json_return("90", "회원정보를 찾을수 없습니다.");
        }

        $addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mem_info["mm_idx"]]);
        $this->user->setUser([
            "mm_idx" => $mem_info["mm_idx"],
            "mm_id" => $mem_info["mm_id"],
            "mm_point" => $mem_info["mm_point"],
            "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
            "mm_type" => $mem_info["mm_type"],
            "mm_join_type" => $mem_info["mm_join_type"],
            "ad_addr" => $addr_info["ad_addr"],
            "ad_gps_x" => $addr_info["ad_gps_x"],
            "ad_gps_y" => $addr_info["ad_gps_y"],
            "mm_sex" => $mem_info["mm_sex"],
        ]);

        # 접속 처리
        $this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $mm_id]);

        # 로그인 성공 시 해당 사용자의 모든 디바이스 중 토큰이 있는 경우 push_yn을 'Y'로 활성화
        $sql = "UPDATE st_member_device 
                SET push_yn = 'Y', up_dt = NOW() 
                WHERE mm_idx = ? AND token IS NOT NULL AND token != ''";
        $this->db->query($sql, [$mem_info["mm_idx"]]);

        json_return("00", "로그인 완료");
    }

    function join_mem_step1()
    {
        # 데이터 추가.
        $mm_id = input_post("mm_id", "");
        $mm_pwd = input_post("mm_pwd", "");

        # 데이터 검증
        if ($mm_id == "" || $mm_pwd == "") {
            json_return("99", "데이터가 비어 있습니다.");
        }


        # 아이디 중복체크
        $info = $this->member_model->getMember(["mm_id" => $mm_id]);

        //if($info!=null){
        //	json_return("11","이미 가입된 이메일 주소가 있습니다.");
        //}

        #로그 생성
        $sl_txt = [
            "mm_id" => $mm_id,
            "mm_pwd" => $mm_pwd
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝1",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝1",
                "sl_status" => "Y"
            ]
        );

        json_return("00", "처리완료");
    }

    function join_mem_step2()
    {
        # 데이터 추가.
        $ad_zipcode = input_post("ad_zipcode", "");
        $ad_jibun = input_post("ad_jibun", "");
        $ad_doro = input_post("ad_doro", "");
        $ad_addr = input_post("ad_addr", "");
        $ad_detail = input_post("ad_detail", "");
        $ad_gps_x = input_post("ad_gps_x", "");
        $ad_gps_y = input_post("ad_gps_y", "");

        $agree1 = input_post("agree1", "");
        $agree2 = input_post("agree2", "");
        $agree3 = input_post("agree3", "");
        $agree4 = input_post("agree4", "");
        $agree5 = input_post("agree5", "");

        # 데이터 검증
        //if($ad_gps_x=="" || $ad_gps_y==""){
        //    json_return("99","GPS정보가 확인되지 않습니다.");
        //}

        //if($ad_addr==""){
        //    json_return("99","선택하신 주소가 확인되지 않습니다.");
        //}

        #로그 생성
        $sl_txt = [
            "ad_zipcode" => $ad_zipcode,
            "ad_jibun" => $ad_jibun,
            "ad_doro" => $ad_doro,
            "ad_addr" => $ad_addr,
            "ad_detail" => $ad_detail,
            "ad_gps_x" => $ad_gps_x,
            "ad_gps_y" => $ad_gps_y,
            "agree1" => $agree1,
            "agree2" => $agree2,
            "agree3" => $agree3,
            "agree4" => $agree4,
            "agree5" => $agree5
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝2",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝2",
                "sl_status" => "Y"
            ]
        );
        json_return("00", "처리완료");
    }

    function join_mem_step3()
    {

        $agree1 = input_post("agree1", "");
        $agree2 = input_post("agree2", "");
        $agree3 = input_post("agree3", "");
        $agree4 = input_post("agree4", "");
        $agree5 = input_post("agree5", "");

        $sl_txt = [
            "agree1" => $agree1,
            "agree2" => $agree2,
            "agree3" => $agree3,
            "agree4" => $agree4,
            "agree5" => $agree5
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝3",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝3",
                "sl_status" => "Y"
            ]
        );
        json_return("00", "처리완료");
    }

    function join_mem_step4_1()
    {
        # 데이터 추가.
        $ma_work = input_post("ma_work", "");
        $ma_price_type = input_post("ma_price_type", "");
        $ma_work_type = input_post("ma_work_type", "");
        $ma_week_yn = input_post("ma_week_yn", "N");
        $ma_week_yn = ($ma_week_yn == "" ? "N" : $ma_week_yn);
        $ma_week = input_post("ma_week", "");

        # 데이터 검증
        if ($ma_work == "") {
            json_return("99", "희망업무 선택이 확인되지 않습니다.");
        }

        if ($ma_price_type == "") {
            json_return("99", "희망급여 선택이 확인되지 않습니다.");
        }

        if ($ma_work_type == "") {
            json_return("99", "근무형태 선택이 확인되지 않습니다.");
        }

        if ($ma_week_yn == "N" && $ma_week == "") {
            json_return("99", "근무요일 선택이 확인되지 않습니다.");
        }


        #로그 생성
        $sl_txt = [
            "ma_work" => $ma_work,
            "ma_price_type" => $ma_price_type,
            "ma_work_type" => $ma_work_type,
            "ma_week_yn" => $ma_week_yn,
            "ma_week" => $ma_week
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_1",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_1",
                "sl_status" => "Y"
            ]
        );
        json_return("00", "처리완료");
    }


    function join_mem_step4_2()
    {
        # 데이터 추가.
        $ma_edu = input_post("ma_edu", "");
        $ma_work_dt = input_post("ma_work_dt", "");

        if ($ma_edu == "") {
            json_return("99", "최종학력 선택이 확인되지 않습니다.");
        }

        if ($ma_work_dt == "") {
            json_return("99", "최종학력 선택이 확인되지 않습니다.");
        }

        #로그 생성
        $sl_txt = [
            "ma_edu" => $ma_edu,
            "ma_work_dt" => $ma_work_dt
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_2",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_2",
                "sl_status" => "Y"
            ]
        );
        json_return("00", "처리완료");
    }

    function join_mem_step4_3()
    {
        # 데이터 추가.
        $arr_career = input_post("arr_career", "");
        $ma_my_type = input_post("ma_my_type", "");

        #로그 생성
        $sl_txt = [
            "arr_career" => $arr_career,
            "ma_my_type" => $ma_my_type
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_3",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_3",
                "sl_status" => "Y"
            ]
        );
        json_return("00", "처리완료");
    }

    function join_mem_step4_4()
    {
        # 데이터 추가.
        $arr_certi = input_post("arr_certi", "");
        $ma_desc = input_post("ma_desc", "");

        # 기존 등록 이미지 있으면 삭제
        $log_info = $this->site_model->getSiteLog(
            ["session_id" => session_id(), "sl_type" => "JOIN_MEM", "sl_step" => "스텝4_4"]
        );

        if ($log_info != null) {
            try {
                $json_info = json_decode($log_info["sl_txt"], true);
                file_remove($json_info["mm_img1"]);
                file_remove($json_info["mm_img2"]);
                file_remove($json_info["mm_img3"]);
                file_remove($json_info["mm_img4"]);
                file_remove($json_info["mm_img5"]);
            } catch (Exception $ex) {
            }
        }

        # 이미지 처리.
        $mm_img1 = $this->common->img_upload_resize_url("member", "mm_img1", 1200);
        $mm_img2 = $this->common->img_upload_resize_url("member", "mm_img2", 1200);
        $mm_img3 = $this->common->img_upload_resize_url("member", "mm_img3", 1200);
        $mm_img4 = $this->common->img_upload_resize_url("member", "mm_img4", 1200);
        $mm_img5 = $this->common->img_upload_resize_url("member", "mm_img5", 1200);

        $mm_thumb = $this->common->img_upload_resize_url("member", "mm_img1", 600);

        #로그 생성
        $sl_txt = [
            "arr_certi" => $arr_certi,
            "ma_desc" => $ma_desc,
            "mm_img1" => $mm_img1,
            "mm_img2" => $mm_img2,
            "mm_img3" => $mm_img3,
            "mm_img4" => $mm_img4,
            "mm_img5" => $mm_img5,
            "mm_thumb" => $mm_thumb,
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_4",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_4",
                "sl_status" => "Y"
            ]
        );


        # 전체 등록 파일 조회 후 리턴
        $return_data = [];
        $list = $this->site_model->getSiteLogList(
            ["session_id" => session_id(), "sl_type" => "JOIN_MEM", "sl_status" => "Y"]
        );

        if ($list == null) {
            json_return("10", "등록 데이터가 존재하지 않습니다.", $list);
        }

        $step_sns = $this->site_model->getStepTempData("JOIN", "SNS");
        $step_auth = $this->site_model->getStepData($list, "본인인증");
        $step1_info = $this->site_model->getStepData($list, "스텝1");
        $step2_info = $this->site_model->getStepData($list, "스텝2");
        $step4_1_info = $this->site_model->getStepData($list, "스텝4_1");
        $step4_2_info = $this->site_model->getStepData($list, "스텝4_2");
        $step4_3_info = $this->site_model->getStepData($list, "스텝4_3");
        $step4_4_info = $this->site_model->getStepData($list, "스텝4_4");

        // SNS 회원가입인지 일반 회원가입인지에 따라 데이터 분기 처리
        if (!empty($step_sns)) {
            // SNS 회원가입: SNS 정보 사용
            $mm_name = !empty($step_sns["username"]) ? $step_sns["username"] : "";
            $mm_phone = !empty($step_sns["phonenum"]) ? $step_sns["phonenum"] : "";
            // 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
            $mm_sex = "";
            if (!empty($step_sns["gender"])) {
                if ($step_sns["gender"] == "MALE") {
                    $mm_sex = "남";
                } else if ($step_sns["gender"] == "FEMALE") {
                    $mm_sex = "여";
                } else if ($step_sns["gender"] == "남" || $step_sns["gender"] == "여") {
                    $mm_sex = $step_sns["gender"];
                }
            }
            // SNS의 birthyear와 birthday를 YYYYMMDD 형식으로 변환
            // birthday는 "MMDD" 또는 "MM-DD" 형식일 수 있으므로 하이픈 제거
            // 카카오: birthyear와 birthday 모두 제공 가능
            // 네이버: birthyear는 제공함, birthday는 제공하지 않음 (null)
            // 네이버의 경우 birthyear만 있으면 YYYY0000 형식으로 저장됨
            $mm_birth = "";
            if (!empty($step_sns["birthyear"])) {
                if (!empty($step_sns["birthday"])) {
                    // birthyear와 birthday 모두 있는 경우 (카카오)
                    $birthday_clean = str_replace("-", "", $step_sns["birthday"]); // 하이픈 제거
                    $mm_birth = $step_sns["birthyear"] . $birthday_clean;
                } else {
                    // birthyear만 있는 경우
                    // 생년만 저장 (YYYY0000 형식)
                    $mm_birth = $step_sns["birthyear"] . "0000";
                }
            } else {
                // birthyear가 없는 경우
                // birthday만 있어도 YYYYMMDD 형식을 만들 수 없으므로 빈 문자열로 저장
            }
            $mm_nation = "";
        } else {
            // 일반 회원가입: 본인인증 정보 사용
            $mm_name = isset($step_auth["mm_name"]) ? $step_auth["mm_name"] : "";
            $mm_phone = isset($step_auth["mm_phone"]) ? $step_auth["mm_phone"] : "";
            $mm_sex = isset($step_auth["mm_sex"]) ? $step_auth["mm_sex"] : "";
            $mm_birth = isset($step_auth["mm_birth"]) ? $step_auth["mm_birth"] : "";
            $mm_nation = isset($step_auth["mm_nation"]) ? $step_auth["mm_nation"] : "";
        }

        $arr_mem = [
            "mm_name" => $mm_name,
            "mm_sex" => $mm_sex,
            "mm_nation" => $mm_nation,
            "mm_birth" => $mm_birth,
            "mm_phone" => $mm_phone,
            //"mm_ci"=>$step_auth["mm_ci"],
            //"mm_di"=>$step_auth["mm_di"],
            "mm_id" => $step1_info["mm_id"],
            //"mm_pwd"=>$step1_info["mm_pwd"],
            //"mm_gps_x"=>$step2_info["mm_gps_x"],
            //"mm_gps_y"=>$step2_info["mm_gps_y"],
            "mm_zipcode" => $step2_info["mm_zipcode"],
            "mm_doro" => $step2_info["mm_doro"],
            "mm_jibun" => $step2_info["mm_jibun"],
            "ad_addr" => $step2_info["ad_addr"],
            "ma_work" => $step4_1_info["ma_work"],
            "ma_work_img" => getWorkingImg(arr_implode(",", $step4_1_info["ma_work"])),
            "ma_price_type" => $step4_1_info["ma_price_type"],
            "ma_work_type" => $step4_1_info["ma_work_type"],
            "ma_week_yn" => $step4_1_info["ma_week_yn"],
            "ma_week" => $step4_1_info["ma_week"],
            "ma_edu" => $step4_2_info["ma_edu"],
            "ma_work_dt" => substr($step4_2_info["ma_work_dt"], 0, 10),
            "ma_work_dt_yoil" => get_yoil($step4_2_info["ma_work_dt"]),
            "arr_career" => $step4_3_info["arr_career"],
            "ma_my_type" => $step4_3_info["ma_my_type"],
            "arr_certi" => $step4_4_info["arr_certi"],
            "ma_desc" => $step4_4_info["ma_desc"],
            "mm_img1" => $step4_4_info["mm_img1"],
            "mm_img2" => $step4_4_info["mm_img2"],
            "mm_img3" => $step4_4_info["mm_img3"],
            "mm_img4" => $step4_4_info["mm_img4"],
            "mm_img5" => $step4_4_info["mm_img5"],
        ];

        json_return("00", "처리완료", $arr_mem);
    }


    function join_comp_step1()
    {
        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝1",
                "sl_txt" => ""
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝1",
                "sl_status" => "Y"
            ]
        );
        json_return("00", "처리완료");
    }

    function join_comp_step2()
    {
        # 데이터 추가.
        $mm_comp_type = input_post("mm_comp_type", "");
        $mm_comp_name = input_post("mm_comp_name", "");
        $mm_comp_num = input_post("mm_comp_num", "");

        $mm_comp_auth = $this->common->img_upload_url("member", "mm_comp_auth");

        if ($mm_comp_type == "") {
            json_return("99", "사업자 유형 선택이 확인되지 않습니다.");
        }

        if ($mm_comp_name == "") {
            json_return("99", "사업자명이 확인되지 않습니다.");
        }

        if ($mm_comp_type == "COMP" || $mm_comp_type == "OUT") {
            if ($mm_comp_num == "") {
                json_return("99", "사업자번호가 확인되지 않습니다.");
            }
        }

        #로그 생성
        $sl_txt = [
            "mm_comp_type" => $mm_comp_type,
            "mm_comp_name" => $mm_comp_name,
            "mm_comp_num" => $mm_comp_num,
            "mm_comp_auth" => $mm_comp_auth,
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝2",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝2",
                "sl_status" => "Y"
            ]
        );
        json_return("00", "처리완료");
    }

    function join_comp_step3()
    {
        # 데이터 추가.
        $mm_id = input_post("mm_id", "");
        $mm_pwd = input_post("mm_pwd", "");

        # 데이터 검증
        if ($mm_id == "" || $mm_pwd == "") {
            json_return("99", "데이터가 비어 있습니다.");
        }

        # 아이디 중복체크
        $info = $this->member_model->getMember(["mm_id" => $mm_id, "mm_status" => "Y"]);

        if ($info != null) {
            //json_return("11","이미 가입된 이메일 주소가 있습니다.");
            json_return("11", "이미 가입된 아이디가 있습니다.");
        }

        #로그 생성
        $sl_txt = [
            "mm_id" => $mm_id,
            "mm_pwd" => $mm_pwd
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝3",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝3",
                "sl_status" => "Y"
            ]
        );

        json_return("00", "처리완료");
    }

    function join_comp_step4()
    {
        # 데이터 추가.
        $ad_zipcode = input_post("ad_zipcode", "");
        $ad_jibun = input_post("ad_jibun", "");
        $ad_doro = input_post("ad_doro", "");
        $ad_addr = input_post("ad_addr", "");
        $ad_detail = input_post("ad_detail", "");
        $ad_gps_x = input_post("ad_gps_x", "");
        $ad_gps_y = input_post("ad_gps_y", "");

        $agree1 = input_post("agree1", "");
        $agree2 = input_post("agree2", "");
        $agree3 = input_post("agree3", "");
        $agree4 = input_post("agree4", "");
        $agree5 = input_post("agree5", "");

        //# 데이터 검증
        //if($ad_gps_x=="" || $ad_gps_y==""){
        //    json_return("99","GPS정보가 확인되지 않습니다.");
        //}

        //if($ad_addr==""){
        //    json_return("99","선택하신 주소가 확인되지 않습니다.");
        //}

        #로그 생성
        $sl_txt = [
            "ad_zipcode" => $ad_zipcode,
            "ad_jibun" => $ad_jibun,
            "ad_doro" => $ad_doro,
            "ad_addr" => $ad_addr,
            "ad_detail" => $ad_detail,
            "ad_gps_x" => $ad_gps_x,
            "ad_gps_y" => $ad_gps_y,
            "agree1" => $agree1,
            "agree2" => $agree2,
            "agree3" => $agree3,
            "agree4" => $agree4,
            "agree5" => $agree5
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝4",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝4",
                "sl_status" => "Y"
            ]
        );
        json_return("00", "처리완료");
    }

    function join_comp_step5()
    {

        $agree1 = input_post("agree1", "");
        $agree2 = input_post("agree2", "");
        $agree3 = input_post("agree3", "");
        $agree4 = input_post("agree4", "");
        $agree5 = input_post("agree5", "");

        $sl_txt = [
            "agree1" => $agree1,
            "agree2" => $agree2,
            "agree3" => $agree3,
            "agree4" => $agree4,
            "agree5" => $agree5
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝5",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝5",
                "sl_status" => "Y"
            ]
        );
        json_return("00", "처리완료");
    }

    function join_comp_step6()
    {
        $ma_work = input_post("ma_work", "");
        $ma_price_type = input_post("ma_price_type", "");
        $ma_work_type = input_post("ma_work_type", "");
        $ma_week_yn = input_post("ma_week_yn", "N");
        $ma_week_yn = ($ma_week_yn == "" ? "N" : $ma_week_yn);
        $ma_week = input_post("ma_week", "");
        $ma_sex = input_post("ma_sex", "");
        $ma_sage = input_post("ma_sage", "");
        $ma_eage = input_post("ma_eage", "");
        $ma_life_type = input_post("ma_life_type", "");
        $ma_nation = input_post("ma_nation", "");
        $ma_edu = input_post("ma_edu", "");

        # 데이터 검증
        if ($ma_work == "") {
            json_return("99", "희망업무 선택이 확인되지 않습니다.");
        }

        if ($ma_price_type == "") {
            json_return("99", "희망급여 선택이 확인되지 않습니다.");
        }

        if ($ma_work_type == "") {
            json_return("99", "근무형태 선택이 확인되지 않습니다.");
        }

        if ($ma_week_yn == "N" && $ma_week == "") {
            json_return("99", "근무요일 선택이 확인되지 않습니다.");
        }

        if ($ma_sex == "") {
            json_return("99", "희망성별이 선택이 확인되지 않습니다.");
        }

        # 기존 등록 이미지 있으면 삭제
        $log_info = $this->site_model->getSiteLog(
            ["session_id" => session_id(), "sl_type" => "JOIN_COMP", "sl_step" => "스텝6"]
        );

        if ($log_info != null) {
            try {
                $json_info = json_decode($log_info["sl_txt"], true);
                file_remove($json_info["mm_img1"]);
            } catch (Exception $ex) {
            }
        }

        # 이미지 처리.
        $mm_img1 = $this->common->img_upload_resize_url("member", "mm_img1", 1200);
        $mm_thumb = $this->common->img_upload_resize_url("member", "mm_img1", 600);

        #로그 생성
        $sl_txt = [
            "ma_work" => $ma_work,
            "ma_price_type" => $ma_price_type,
            "ma_work_type" => $ma_work_type,
            "ma_week_yn" => $ma_week_yn,
            "ma_week" => $ma_week,
            "ma_sex" => $ma_sex,
            "ma_sage" => $ma_sage,
            "ma_eage" => $ma_eage,
            "ma_life_type" => $ma_life_type,
            "ma_nation" => $ma_nation,
            "ma_edu" => $ma_edu,
            "mm_img1" => $mm_img1,
            "mm_thumb" => $mm_thumb
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝6",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝6",
                "sl_status" => "Y"
            ]
        );

        # 전체 등록 파일 조회 후 리턴
        $return_data = [];
        $list = $this->site_model->getSiteLogList(
            ["session_id" => session_id(), "sl_type" => "JOIN_COMP", "sl_status" => "Y"]
        );

        if ($list == null) {
            json_return("10", "등록 데이터가 존재하지 않습니다.", $list);
        }

        $step_sns = $this->site_model->getStepTempData("JOIN", "SNS");
        $step_auth = $this->site_model->getStepData($list, "본인인증");
        $step1_info = $this->site_model->getStepData($list, "스텝1");
        $step2_info = $this->site_model->getStepData($list, "스텝2");
        $step3_info = $this->site_model->getStepData($list, "스텝3");
        $step4_info = $this->site_model->getStepData($list, "스텝4");
        $step5_info = $this->site_model->getStepData($list, "스텝5");
        $step6_info = $this->site_model->getStepData($list, "스텝6");

        // SNS 회원가입인지 일반 회원가입인지에 따라 데이터 분기 처리
        if (!empty($step_sns)) {
            // SNS 회원가입: SNS 정보 사용
            $mm_name = !empty($step_sns["username"]) ? $step_sns["username"] : "";
            $mm_phone = !empty($step_sns["phonenum"]) ? $step_sns["phonenum"] : "";
            // 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
            $mm_sex = "";
            if (!empty($step_sns["gender"])) {
                if ($step_sns["gender"] == "MALE") {
                    $mm_sex = "남";
                } else if ($step_sns["gender"] == "FEMALE") {
                    $mm_sex = "여";
                } else if ($step_sns["gender"] == "남" || $step_sns["gender"] == "여") {
                    $mm_sex = $step_sns["gender"];
                }
            }
            // SNS의 birthyear와 birthday를 YYYYMMDD 형식으로 변환
            // birthday는 "MMDD" 또는 "MM-DD" 형식일 수 있으므로 하이픈 제거
            // 카카오: birthyear와 birthday 모두 제공 가능
            // 네이버: birthyear는 제공함, birthday는 제공하지 않음 (null)
            // 네이버의 경우 birthyear만 있으면 YYYY0000 형식으로 저장됨
            $mm_birth = "";
            if (!empty($step_sns["birthyear"])) {
                if (!empty($step_sns["birthday"])) {
                    // birthyear와 birthday 모두 있는 경우 (카카오)
                    $birthday_clean = str_replace("-", "", $step_sns["birthday"]); // 하이픈 제거
                    $mm_birth = $step_sns["birthyear"] . $birthday_clean;
                } else {
                    // birthyear만 있는 경우
                    // 생년만 저장 (YYYY0000 형식)
                    $mm_birth = $step_sns["birthyear"] . "0000";
                }
            } else {
                // birthyear가 없는 경우
                // birthday만 있어도 YYYYMMDD 형식을 만들 수 없으므로 빈 문자열로 저장
            }
            $mm_nation = "";
        } else {
            // 일반 회원가입: 본인인증 정보 사용
            $mm_name = isset($step_auth["mm_name"]) ? $step_auth["mm_name"] : "";
            $mm_phone = isset($step_auth["mm_phone"]) ? $step_auth["mm_phone"] : "";
            $mm_sex = isset($step_auth["mm_sex"]) ? $step_auth["mm_sex"] : "";
            $mm_birth = isset($step_auth["mm_birth"]) ? $step_auth["mm_birth"] : "";
            $mm_nation = isset($step_auth["mm_nation"]) ? $step_auth["mm_nation"] : "";
        }

        $arr_mem = [
            "mm_name" => $mm_name,
            "mm_sex" => $mm_sex,
            "mm_nation" => $mm_nation,
            "mm_birth" => $mm_birth,
            "mm_phone" => $mm_phone,
            "mm_id" => $step3_info["mm_id"],
            "mm_comp_type" => $step2_info["mm_comp_type"],
            "mm_comp_name" => $step2_info["mm_comp_name"],
            "mm_comp_num" => $step2_info["mm_comp_num"],
            "mm_zipcode" => $step4_info["mm_zipcode"],
            "mm_doro" => $step4_info["mm_doro"],
            "mm_jibun" => $step4_info["mm_jibun"],
            "ma_work" => $step6_info["ma_work"],
            "ma_work_img" => getWorkingImg(arr_implode(",", $step6_info["ma_work"])),
            "ma_price_type" => $step6_info["ma_price_type"],
            "ma_work_type" => $step6_info["ma_work_type"],
            "ma_week_yn" => $step6_info["ma_week_yn"],
            "ma_week" => $step6_info["ma_week"],
            "ma_sage" => $step6_info["ma_sage"],
            "ma_eage" => $step6_info["ma_eage"],
            "ma_sex" => $step6_info["ma_sex"],
            "ma_life_type" => $step6_info["ma_life_type"],
            "ma_nation" => $step6_info["ma_nation"],
            "ma_edu" => $step6_info["ma_edu"],
            "mm_img1" => $step6_info["mm_img1"],
        ];

        json_return("00", "처리완료", $arr_mem);
    }

    # 회원가입 완료 처리
    function join_complete_proc()
    {
        $proc_type = input_post("proc_type", "");
        $ma_type = input_post("ma_type", "");
        $uuid = input_post("uuid", "");
        $join_type = input_post("join_type", "");
        $join_key = input_post("join_key", "");
        if ($join_type == "" && isset($_COOKIE["join_sns_type"])) {
            $join_type = $_COOKIE["join_sns_type"];
        }
        if ($join_key == "" && isset($_COOKIE["join_sns_key"])) {
            $join_key = $_COOKIE["join_sns_key"];
        }
        $alarm = [
            "uuid" => "",
            "alarm_chat" => "Y",
            "alarm_resume" => "Y",
            "alarm_fav" => "Y",
            "alarm_adv" => "Y",
            "alarm_deny_yn" => "N",
            "token" => "",
        ];

        if ($uuid != "") {
            $mem_device = $this->member_model->getMemberDevice(["uuid" => $uuid]);
            # 빈값이 아니라면
            if ($mem_device != null) {
                $alarm = [
                    "uuid" => $mem_device["uuid"],
                    "alarm_chat" => $mem_device["push_yn"],
                    "alarm_resume" => $mem_device["push_yn"],
                    "alarm_fav" => $mem_device["push_yn"],
                    "alarm_adv" => $mem_device["marketing_yn"],
                    "alarm_deny_yn" => "N",
                    "token" => $mem_device["token"],
                ];
            }
        }

        # 회원및 회사 정보 처리
        $arr_mem = [];

        if ($ma_type == "MEM") {
            $list = $this->site_model->getSiteLogList(
                ["session_id" => session_id(), "sl_type" => "JOIN_MEM", "sl_status" => "Y"]
            );

            if ($list == null) {
                json_return("10", "등록 데이터가 존재하지 않습니다.", $list);
            }

            $step_sns = $this->site_model->getStepTempData("JOIN", "SNS");
            $step_auth = $this->site_model->getStepData($list, "본인인증");
            $step1_info = $this->site_model->getStepData($list, "스텝1");
            $step2_info = $this->site_model->getStepData($list, "스텝2");
            $step3_info = $this->site_model->getStepData($list, "스텝3");
            $step4_1_info = $this->site_model->getStepData($list, "스텝4_1");
            $step4_2_info = $this->site_model->getStepData($list, "스텝4_2");
            $step4_3_info = $this->site_model->getStepData($list, "스텝4_3");
            $step4_4_info = $this->site_model->getStepData($list, "스텝4_4");

            // 방어 처리: 누락된 단계 데이터 기본값 세팅 (iOS 500 방지)
            if (!is_array($step_auth)) $step_auth = [];
            if (!is_array($step1_info)) $step1_info = [];
            if (!is_array($step2_info)) $step2_info = [];
            if (!is_array($step3_info)) $step3_info = [];
            if (!is_array($step4_1_info)) $step4_1_info = [];
            if (!is_array($step4_2_info)) $step4_2_info = [];
            if (!is_array($step4_3_info)) $step4_3_info = [];
            if (!is_array($step4_4_info)) $step4_4_info = [];

            // 클라이언트에서 전달된 join_type/join_key가 있고 SNS 로그가 없으면 보완
            if ((!is_array($step_sns) || empty($step_sns)) && ($join_type !== "" || $join_key !== "")) {
                $step_sns = [
                    "join_type" => $join_type,
                    "join_key" => $join_key,
                    "username" => "",
                    "email" => "",
                    "gender" => "",
                    "birthyear" => "",
                    "birthday" => "",
                    "phonenum" => ""
                ];
            }

            // 디버깅 로그: SNS 데이터 확인
            if ($proc_type == "ALL") {
                # 트랜젝션 시작
                $this->db->trans_begin();

                # 데이터 처리
                // SNS 회원가입인지 일반 회원가입인지에 따라 초기값 분기 처리
                if (!empty($step_sns)) {
                    // SNS 회원가입: SNS 정보만 사용 (본인인증 없음)
                    $mm_name_init = !empty($step_sns["username"]) ? $step_sns["username"] : "";
                    $mm_phone_init = !empty($step_sns["phonenum"]) ? $step_sns["phonenum"] : "";
                    // 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
                    $mm_sex_init = "X";
                    if (!empty($step_sns["gender"])) {
                        if ($step_sns["gender"] == "MALE") {
                            $mm_sex_init = "남";
                        } else if ($step_sns["gender"] == "FEMALE") {
                            $mm_sex_init = "여";
                        } else if ($step_sns["gender"] == "남" || $step_sns["gender"] == "여") {
                            $mm_sex_init = $step_sns["gender"];
                        } else {
                            $mm_sex_init = $step_sns["gender"]; // 기타 값은 그대로 사용
                        }
                    }
                    // SNS의 birthyear와 birthday를 YYYYMMDD 형식으로 변환
                    // birthday는 "MMDD" 또는 "MM-DD" 형식일 수 있으므로 하이픈 제거
                    // 네이버의 경우 birthday가 없을 수 있으므로 birthyear만 있어도 처리
                    $mm_birth_init = "";
                    if (!empty($step_sns["birthyear"])) {
                        if (!empty($step_sns["birthday"])) {
                            // birthyear와 birthday 모두 있는 경우
                            $birthday_clean = str_replace("-", "", $step_sns["birthday"]); // 하이픈 제거
                            $mm_birth_init = $step_sns["birthyear"] . $birthday_clean;
                        } else {
                            // birthyear만 있는 경우 (네이버 API에서 birthday가 제공되지 않을 수 있음)
                            // 생년만 저장 (YYYY0000 형식)
                            $mm_birth_init = $step_sns["birthyear"] . "0000";
                        }
                    } else {
                    }
                    $mm_nation_init = "";
                    $mm_ci_init = "";
                    $mm_di_init = "";
                } else {
                    // 일반 회원가입: 본인인증 정보 사용
                    $mm_name_init = isset($step_auth["mm_name"]) ? $step_auth["mm_name"] : "";
                    $mm_phone_init = isset($step_auth["mm_phone"]) ? $step_auth["mm_phone"] : "";
                    $mm_sex_init = isset($step_auth["mm_sex"]) ? $step_auth["mm_sex"] : "";
                    $mm_birth_init = isset($step_auth["mm_birth"]) ? $step_auth["mm_birth"] : "";
                    $mm_nation_init = isset($step_auth["mm_nation"]) ? $step_auth["mm_nation"] : "";
                    $mm_ci_init = isset($step_auth["mm_ci"]) ? $step_auth["mm_ci"] : "";
                    $mm_di_init = isset($step_auth["mm_di"]) ? $step_auth["mm_di"] : "";
                }

                // agree4/agree5: step2_info 우선, 없으면 step3_info에서 가져옴
                $agree4 = !empty($step2_info["agree4"]) ? $step2_info["agree4"] : (!empty($step3_info["agree4"]) ? $step3_info["agree4"] : "");
                $agree5 = !empty($step2_info["agree5"]) ? $step2_info["agree5"] : (!empty($step3_info["agree5"]) ? $step3_info["agree5"] : "");

                $arr_mem = [
                    "mm_type" => "MEM",
                    "mm_id" => isset($step1_info["mm_id"]) ? $step1_info["mm_id"] : "",
                    "mm_pwd" => $this->common->getSqlPass(isset($step1_info["mm_pwd"]) ? $step1_info["mm_pwd"] : ""),
                    "mm_name" => $mm_name_init,
                    "mm_status" => "Y",
                    "mm_phone" => $mm_phone_init,
                    "mm_sex" => $mm_sex_init,
                    "mm_birth" => $mm_birth_init,
                    "mm_nation" => $mm_nation_init,
                    "mm_ci" => $mm_ci_init,
                    "mm_di" => $mm_di_init,
                    "mm_thumb" => isset($step4_4_info["mm_thumb"]) ? $step4_4_info["mm_thumb"] : "",
                    "mm_img1" => isset($step4_4_info["mm_img1"]) ? $step4_4_info["mm_img1"] : "",
                    "mm_img2" => isset($step4_4_info["mm_img2"]) ? $step4_4_info["mm_img2"] : "",
                    "mm_img3" => isset($step4_4_info["mm_img3"]) ? $step4_4_info["mm_img3"] : "",
                    "mm_img4" => isset($step4_4_info["mm_img4"]) ? $step4_4_info["mm_img4"] : "",
                    "mm_img5" => isset($step4_4_info["mm_img5"]) ? $step4_4_info["mm_img5"] : "",
                    "mm_join_ip" => $this->common->getIpAddr(),
                    "alarm_chat" => $alarm["alarm_chat"],
                    "alarm_resume" => $alarm["alarm_resume"],
                    "alarm_fav" => $alarm["alarm_fav"],
                    "alarm_adv" => $alarm["alarm_adv"],
                    "alarm_deny_yn" => $alarm["alarm_deny_yn"],
                    "alarm_deny_yoil" => "",
                    "alarm_deny_stime" => "",
                    "alarm_deny_etime" => "",
                    "mm_agree_privget" => $agree4,
                    "mm_agree_ad" => $agree5,
                ];

                if (!empty($step_sns)) {
                    $arr_mem["mm_join_type"] = $step_sns["join_type"];
                    $arr_mem["mm_join_key"] = $step_sns["join_key"];
                    $arr_mem["mm_id"] = strtolower($step_sns["join_type"]) . "_" . $step_sns["join_key"];
                    $arr_mem["mm_ci"] = $arr_mem["mm_id"];

                    // SNS 정보로 덮어쓰기 (이미 초기값에서 설정했지만, 재확인)
                    if (!empty($step_sns["username"])) {
                        $arr_mem["mm_name"] = $step_sns["username"];
                    } else if (empty($arr_mem["mm_name"])) {
                        $arr_mem["mm_name"] = $arr_mem["mm_id"];
                    }

                    if (!empty($step_sns["phonenum"])) {
                        $arr_mem["mm_phone"] = $step_sns["phonenum"];
                    } else if (empty($arr_mem["mm_phone"])) {
                        $arr_mem["mm_phone"] = $arr_mem["mm_id"];
                    }

                    // 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
                    if (!empty($step_sns["gender"])) {
                        if ($step_sns["gender"] == "MALE") {
                            $arr_mem["mm_sex"] = "남";
                        } else if ($step_sns["gender"] == "FEMALE") {
                            $arr_mem["mm_sex"] = "여";
                        } else if ($step_sns["gender"] == "남" || $step_sns["gender"] == "여") {
                            $arr_mem["mm_sex"] = $step_sns["gender"];
                        } else {
                            $arr_mem["mm_sex"] = $step_sns["gender"]; // 기타 값은 그대로 사용
                        }
                    } else if (empty($arr_mem["mm_sex"])) {
                        $arr_mem["mm_sex"] = "X";
                    }

                    // mm_birth: SNS의 birthyear와 birthday 합치기 (YYYYMMDD 형식)
                    // birthday는 "MMDD" 또는 "MM-DD" 형식일 수 있으므로 하이픈 제거
                    // 네이버의 경우 birthday가 없을 수 있으므로 birthyear만 있어도 처리
                    if (!empty($step_sns["birthyear"])) {
                        if (!empty($step_sns["birthday"])) {
                            // birthyear와 birthday 모두 있는 경우
                            $birthday_clean = str_replace("-", "", $step_sns["birthday"]); // 하이픈 제거
                            $arr_mem["mm_birth"] = $step_sns["birthyear"] . $birthday_clean;
                        } else {
                            // birthyear만 있는 경우 (네이버 API에서 birthday가 제공되지 않을 수 있음)
                            // 생년만 저장 (YYYY0000 형식)
                            $arr_mem["mm_birth"] = $step_sns["birthyear"] . "0000";
                        }
                    } else {
                        // SNS에서 생년월일 정보가 없으면 빈 문자열로 명시적 설정
                        $arr_mem["mm_birth"] = "";
                    }

                    // 디버깅 로그: mm_birth 설정 후 확인
                    if (empty($arr_mem["mm_di"])) {
                        $arr_mem["mm_di"] = $arr_mem["mm_id"];
                    }

                    if (empty($arr_mem["mm_nation"])) {
                        $arr_mem["mm_nation"] = "";
                    }
                } else {
                    $arr_mem["mm_join_type"] = "WEB";
                    $arr_mem["mm_join_key"] = "";
                }

                // 디버깅 로그: mm_id 누락 시 원인 추적 (iOS 500 대응)
                // mm_id 누락 시 500 방지
                if (empty($arr_mem["mm_id"])) {
                    json_return("91", "가입 정보가 누락되었습니다. 다시 로그인해주세요.");
                    exit;
                }

                // DB 저장
                $result_join = $this->member_model->insertMember($arr_mem);

                // 회원 INSERT 결과 검증
                if (!is_array($result_join) || $result_join["code"] != "00") {
                    $this->db->trans_rollback();
                    json_return("91", "[01]회원가입시 오류가 발생하였습니다.");
                    exit;
                }

                // mm_idx NULL 체크 (INSERT ID 생성 실패 시)
                if (empty($result_join["key"])) {
                    $this->db->trans_rollback();
                    json_return("91", "[01]회원 ID 생성에 실패했습니다. 다시 시도해주세요.");
                    exit;
                }

                $mm_idx = $result_join["key"];

                # 토큰정보 연동
                if ($alarm["uuid"] != "") {
                    $this->member_model->setMemberDevice(["mm_idx" => $mm_idx], ["uuid" => $alarm["uuid"]]);
                }

                $arr_addr = [
                    "mm_idx" => $mm_idx,
                    "ad_type" => "MEMBER",
                    "ad_key" => $mm_idx,
                    "ad_zipcode" => isset($step2_info["ad_zipcode"]) ? $step2_info["ad_zipcode"] : "",
                    "ad_jibun" => isset($step2_info["ad_jibun"]) ? $step2_info["ad_jibun"] : "",
                    "ad_doro" => isset($step2_info["ad_doro"]) ? $step2_info["ad_doro"] : "",
                    "ad_detail" => isset($step2_info["ad_detail"]) ? $step2_info["ad_detail"] : "",
                    "ad_addr" => isset($step2_info["ad_addr"]) ? $step2_info["ad_addr"] : "",
                    "ad_gps_x" => isset($step2_info["ad_gps_x"]) ? $step2_info["ad_gps_x"] : "",
                    "ad_gps_y" => isset($step2_info["ad_gps_y"]) ? $step2_info["ad_gps_y"] : "",
                ];

                $result_addr = $this->member_model->insertAddress($arr_addr);
                if ($result_addr["code"] != "00") {
                    $this->db->trans_rollback();
                    json_return("92", "[02]회원가입시 오류가 발생하였습니다.");
                    exit;
                }


                // SNS 가입 시 $arr_mem["mm_birth"] 사용, 일반 가입 시 $step_auth["mm_birth"] 사용
                $birth_for_age = !empty($step_sns) ? $arr_mem["mm_birth"] : (isset($step_auth["mm_birth"]) ? $step_auth["mm_birth"] : "");
                $ma_age = getKoreanAge($birth_for_age);
                $arr_mem_info = [
                    "mm_idx" => $mm_idx,
                    "ma_work" => arr_implode(",", isset($step4_1_info["ma_work"]) ? $step4_1_info["ma_work"] : []),
                    "ma_price_type" => arr_implode(",", isset($step4_1_info["ma_price_type"]) ? $step4_1_info["ma_price_type"] : []),
                    "ma_age" => $ma_age,
                    "ma_work_type" => arr_implode(",", isset($step4_1_info["ma_work_type"]) ? $step4_1_info["ma_work_type"] : []),
                    "ma_week_yn" => isset($step4_1_info["ma_week_yn"]) ? $step4_1_info["ma_week_yn"] : "",
                    "ma_week" => arr_implode(",", isset($step4_1_info["ma_week"]) ? $step4_1_info["ma_week"] : []),
                    "ma_edu" => isset($step4_2_info["ma_edu"]) ? $step4_2_info["ma_edu"] : "",
                    "ma_work_dt" => isset($step4_2_info["ma_work_dt"]) ? $step4_2_info["ma_work_dt"] : "",
                    "ma_my_type" => arr_implode(",", isset($step4_3_info["ma_my_type"]) ? $step4_3_info["ma_my_type"] : []),
                    "ma_desc" => isset($step4_4_info["ma_desc"]) ? $step4_4_info["ma_desc"] : "",
                ];

                $result_info = $this->member_model->insertMemberInfo($arr_mem_info);

                if ($result_info["code"] != "00") {
                    $this->db->trans_rollback();
                    json_return("93", "[03]회원가입시 오류가 발생하였습니다.");
                }

                if (isset($step4_3_info["arr_career"]) && $step4_3_info["arr_career"] != "") {
                    $arr_temp = explode("&&&", $step4_3_info["arr_career"]);
                    foreach ($arr_temp as $row) {
                        $arr_data = explode("|||", $row);

                        $info = [
                            "mm_idx" => $mm_idx,
                            "mc_type" => isset($arr_data[0]) ? $arr_data[0] : "",
                            "mc_name" => isset($arr_data[1]) ? $arr_data[1] : "",
                            "mc_date" => isset($arr_data[2]) ? $arr_data[2] : "",
                        ];

                        $this->member_model->insertMemberCareer($info);
                    }
                }

                if (isset($step4_4_info["arr_certi"]) && $step4_4_info["arr_certi"] != "") {
                    $arr_temp = explode("&&&", $step4_4_info["arr_certi"]);
                    foreach ($arr_temp as $row) {
                        $arr_data = explode("|||", $row);

                        $info = [
                            "mm_idx" => $mm_idx,
                            "mc_name" => isset($arr_data[0]) ? $arr_data[0] : "",
                            "mc_dt" => isset($arr_data[1]) ? $arr_data[1] : "",
                        ];

                        $this->member_model->insertMemberCerti($info);
                    }
                }

                # 포인트 추가
                $pay_point = 5000;
                $insert = [
                    "mm_idx" => $mm_idx,
                    "o_num" => "",
                    "pm_idx" => 0,
                    "pt_type" => "JOIN",
                    "pl_type" => "plus",
                    "pl_point" => $pay_point,
                    "pl_last_point" => $pay_point,
                    "pl_desc" => "회원가입 포인트 지급(5,000P)",
                    "reg_id" => "system",
                ];
                $result_point = $this->site_model->insertPointList($insert);

                #회원 정보 수정
                $this->member_model->updateMember(["mm_point" => $pay_point], ["mm_idx" => $mm_idx]);

                #  정보조회후 로그인처리
                $mem_info = $this->member_model->getMember(["mm_id" => $arr_mem["mm_id"]]);

                $this->user->setUser([
                    "mm_idx" => $mem_info["mm_idx"],
                    "mm_id" => $mem_info["mm_id"],
                    "mm_point" => $mem_info["mm_point"],
                    "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
                    "mm_type" => $mem_info["mm_type"],
                    "mm_join_type" => $mem_info["mm_join_type"],
                    "ad_addr" => $step2_info["ad_addr"],
                    "ad_gps_x" => $step2_info["ad_gps_x"],
                    "ad_gps_y" => $step2_info["ad_gps_y"],
                    "mm_sex" => $mem_info["mm_sex"],
                ]);

                # 접속 처리
                $this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $arr_mem["mm_id"]]);

                # 로그인 성공 시 해당 사용자의 모든 디바이스 중 토큰이 있는 경우 push_yn을 'Y'로 활성화
                $sql = "UPDATE st_member_device 
                        SET push_yn = 'Y', up_dt = NOW() 
                        WHERE mm_idx = ? AND token IS NOT NULL AND token != ''";
                $this->db->query($sql, [$mem_info["mm_idx"]]);

                # 완료 로그
                $this->site_model->setSiteLog(
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN_MEM",
                        "sl_step" => "가입완료",
                        "sl_txt" => ""
                    ],
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN_MEM",
                        "sl_step" => "가입완료",
                        "sl_status" => "Y"
                    ]
                );

                # 로그 업데이트
                $this->site_model->updateSiteLog(["sl_status" => "N", "mm_idx" => $mem_info["mm_idx"]], ["session_id" => session_id()]);

                # 몽고 디비 추가
                $this->setMongoUser($arr_mem, $alarm["token"]);

                $this->db->trans_commit();

                // 로그인 세션 설정 (join_mem_finish에서 isLogin = true가 되도록)
                $addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mm_idx]);
                if (!is_array($addr_info)) {
                    $addr_info = [];
                }
                $this->user->setUser([
                    "mm_idx" => $mm_idx,
                    "mm_id" => $arr_mem["mm_id"],
                    "mm_point" => $arr_mem["mm_point"],
                    "mm_name" => $arr_mem["mm_name"],
                    "mm_type" => $arr_mem["mm_type"],
                    "mm_join_type" => $arr_mem["mm_join_type"],
                    "mm_img" => $arr_mem["mm_thumb"],
                    "ad_addr" => isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : "",
                    "ad_gps_x" => isset($addr_info["ad_gps_x"]) ? $addr_info["ad_gps_x"] : "",
                    "ad_gps_y" => isset($addr_info["ad_gps_y"]) ? $addr_info["ad_gps_y"] : "",
                    "mm_sex" => $arr_mem["mm_sex"],
                ]);

                return json_return("00", "회원가입 완료", ["mm_id" => $arr_mem["mm_id"]]);
            } else {
                //PART

                # 트랜젝션 시작
                $this->db->trans_begin();

                /*
                    $arr_mem = [
						"mm_type"=>"MEM",
						"mm_id"=>$step1_info["mm_id"],
	                    "mm_pwd"=>$this->common->getSqlPass($step1_info["mm_pwd"]),
		                //"mm_name"=>$step_auth["mm_name"],
						"mm_name"=>$step1_info["mm_id"],
		                "mm_status"=>"Y",
		                //"mm_phone"=>$step_auth["mm_phone"],
						"mm_phone"=>$step1_info["mm_id"],
		                //"mm_sex"=>$step_auth["mm_sex"],
						"mm_sex"=>"X",
		                "mm_birth"=>$step_auth["mm_birth"],
		                "mm_nation"=>$step_auth["mm_nation"],
	                    "mm_ci"=>$step_auth["mm_ci"],
	                    "mm_di"=>$step_auth["mm_di"],
	                    "mm_join_ip"=>$this->common->getIpAddr($arr_mem),
	                    "alarm_chat"=>$alarm["alarm_chat"],
	                    "alarm_resume"=>$alarm["alarm_resume"],
	                    "alarm_fav"=>$alarm["alarm_fav"],
	                    "alarm_adv"=>$alarm["alarm_adv"],
	                    "alarm_deny_yn"=>$alarm["alarm_deny_yn"],
	                    "alarm_deny_yoil"=>"",
	                    "alarm_deny_stime"=>"",
	                    "alarm_deny_etime"=>"",
	                ]; */

                $agree1 = !empty($step2_info["agree1"]) ? $step2_info["agree1"] : (!empty($step3_info["agree1"]) ? $step3_info["agree1"] : "");
                $agree2 = !empty($step2_info["agree2"]) ? $step2_info["agree2"] : (!empty($step3_info["agree2"]) ? $step3_info["agree2"] : "");
                $agree3 = !empty($step2_info["agree3"]) ? $step2_info["agree3"] : (!empty($step3_info["agree3"]) ? $step3_info["agree3"] : "");
                $agree4 = !empty($step2_info["agree4"]) ? $step2_info["agree4"] : (!empty($step3_info["agree4"]) ? $step3_info["agree4"] : "");
                $agree5 = !empty($step2_info["agree5"]) ? $step2_info["agree5"] : (!empty($step3_info["agree5"]) ? $step3_info["agree5"] : "");

                //print_r($step_auth);
                //exit; //test
                //test

                // SNS 회원가입인지 일반 회원가입인지에 따라 초기값 분기 처리
                if (!empty($step_sns)) {
                    // SNS 회원가입: SNS 정보만 사용 (본인인증 없음)
                    $mm_name_init = !empty($step_sns["username"]) ? $step_sns["username"] : "";
                    $mm_phone_init = !empty($step_sns["phonenum"]) ? $step_sns["phonenum"] : "";
                    // 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
                    $mm_sex_init = "X";
                    if (!empty($step_sns["gender"])) {
                        if ($step_sns["gender"] == "MALE") {
                            $mm_sex_init = "남";
                        } else if ($step_sns["gender"] == "FEMALE") {
                            $mm_sex_init = "여";
                        } else if ($step_sns["gender"] == "남" || $step_sns["gender"] == "여") {
                            $mm_sex_init = $step_sns["gender"];
                        } else {
                            $mm_sex_init = $step_sns["gender"]; // 기타 값은 그대로 사용
                        }
                    }
                    // SNS의 birthyear와 birthday를 YYYYMMDD 형식으로 변환
                    // birthday는 "MMDD" 또는 "MM-DD" 형식일 수 있으므로 하이픈 제거
                    // 네이버의 경우 birthday가 없을 수 있으므로 birthyear만 있어도 처리
                    $mm_birth_init = "";
                    if (!empty($step_sns["birthyear"])) {
                        if (!empty($step_sns["birthday"])) {
                            // birthyear와 birthday 모두 있는 경우
                            $birthday_clean = str_replace("-", "", $step_sns["birthday"]); // 하이픈 제거
                            $mm_birth_init = $step_sns["birthyear"] . $birthday_clean;
                        } else {
                            // birthyear만 있는 경우 (네이버 API에서 birthday가 제공되지 않을 수 있음)
                            // 생년만 저장 (YYYY0000 형식)
                            $mm_birth_init = $step_sns["birthyear"] . "0000";
                        }
                    }
                    $mm_nation_init = "";
                    $mm_di_init = "";
                } else {
                    // 일반 회원가입: 본인인증 정보 사용
                    $mm_name_init = isset($step_auth["mm_name"]) ? $step_auth["mm_name"] : "";
                    $mm_phone_init = isset($step_auth["mm_phone"]) ? $step_auth["mm_phone"] : "";
                    $mm_sex_init = isset($step_auth["mm_sex"]) ? $step_auth["mm_sex"] : "";
                    $mm_birth_init = isset($step_auth["mm_birth"]) ? $step_auth["mm_birth"] : "";
                    $mm_nation_init = isset($step_auth["mm_nation"]) ? $step_auth["mm_nation"] : "";
                    $mm_di_init = isset($step_auth["mm_di"]) ? $step_auth["mm_di"] : "";
                }

                $arr_mem = [
                    "mm_type" => "MEM",
                    "mm_id" => $step1_info["mm_id"],
                    "mm_pwd" => $this->common->getSqlPass($step1_info["mm_pwd"]),
                    "mm_name" => $mm_name_init,
                    "mm_status" => "Y",
                    "mm_phone" => $mm_phone_init,
                    "mm_sex" => $mm_sex_init,
                    "mm_birth" => $mm_birth_init,
                    "mm_nation" => $mm_nation_init,
                    "mm_ci" => $step1_info["mm_id"],
                    "mm_di" => $mm_di_init,
                    "mm_join_ip" => $this->common->getIpAddr($arr_mem),
                    "alarm_chat" => $alarm["alarm_chat"],
                    "alarm_resume" => $alarm["alarm_resume"],
                    "alarm_fav" => $alarm["alarm_fav"],
                    "alarm_adv" => $alarm["alarm_adv"],
                    "alarm_deny_yn" => $alarm["alarm_deny_yn"],
                    "alarm_deny_yoil" => "",
                    "alarm_deny_stime" => "",
                    "alarm_deny_etime" => "",
                    "mm_agree_service" => $agree1,
                    "mm_agree_privacy" => $agree2,
                    "mm_agree_location" => $agree3,
                    "mm_agree_privget" => $agree4,
                    "mm_agree_ad" => $agree5
                ];

                if (!empty($step_sns)) {
                    $arr_mem["mm_join_type"] = $step_sns["join_type"];
                    $arr_mem["mm_join_key"] = $step_sns["join_key"];
                    $arr_mem["mm_id"] = strtolower($step_sns["join_type"]) . "_" . $step_sns["join_key"];
                    $arr_mem["mm_ci"] = $arr_mem["mm_id"];

                    // SNS 정보로 덮어쓰기 (이미 초기값에서 설정했지만, 재확인)
                    if (!empty($step_sns["username"])) {
                        $arr_mem["mm_name"] = $step_sns["username"];
                    } else if (empty($arr_mem["mm_name"])) {
                        $arr_mem["mm_name"] = $arr_mem["mm_id"];
                    }

                    if (!empty($step_sns["phonenum"])) {
                        $arr_mem["mm_phone"] = $step_sns["phonenum"];
                    } else if (empty($arr_mem["mm_phone"])) {
                        $arr_mem["mm_phone"] = $arr_mem["mm_id"];
                    }

                    // 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
                    if (!empty($step_sns["gender"])) {
                        if ($step_sns["gender"] == "MALE") {
                            $arr_mem["mm_sex"] = "남";
                        } else if ($step_sns["gender"] == "FEMALE") {
                            $arr_mem["mm_sex"] = "여";
                        } else if ($step_sns["gender"] == "남" || $step_sns["gender"] == "여") {
                            $arr_mem["mm_sex"] = $step_sns["gender"];
                        } else {
                            $arr_mem["mm_sex"] = $step_sns["gender"]; // 기타 값은 그대로 사용
                        }
                    } else if (empty($arr_mem["mm_sex"])) {
                        $arr_mem["mm_sex"] = "X";
                    }

                    // mm_birth: SNS의 birthyear와 birthday 합치기 (YYYYMMDD 형식)
                    // birthday는 "MMDD" 또는 "MM-DD" 형식일 수 있으므로 하이픈 제거
                    // 카카오: birthyear와 birthday 모두 제공 가능
                    // 네이버: birthyear는 제공함, birthday는 제공하지 않음 (null)
                    // 네이버의 경우 birthyear만 있으면 YYYY0000 형식으로 저장됨
                    if (!empty($step_sns["birthyear"])) {
                        if (!empty($step_sns["birthday"])) {
                            // birthyear와 birthday 모두 있는 경우 (카카오)
                            $birthday_clean = str_replace("-", "", $step_sns["birthday"]); // 하이픈 제거
                            $arr_mem["mm_birth"] = $step_sns["birthyear"] . $birthday_clean;
                        } else {
                            // birthyear만 있는 경우
                            // 생년만 저장 (YYYY0000 형식)
                            $arr_mem["mm_birth"] = $step_sns["birthyear"] . "0000";
                        }
                    } else {
                        // birthyear가 없는 경우 (네이버: 출생연도 제공 안 함)
                        // birthday만 있어도 YYYYMMDD 형식을 만들 수 없으므로 빈 문자열로 저장
                        $arr_mem["mm_birth"] = "";
                    }

                    if (empty($arr_mem["mm_di"])) {
                        $arr_mem["mm_di"] = $arr_mem["mm_id"];
                    }

                    if (empty($arr_mem["mm_nation"])) {
                        $arr_mem["mm_nation"] = "";
                    }

                    // 성별 정보는 SNS에서 가져온 값으로 덮어쓰기
                    // 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
                    if ($arr_mem["mm_sex"] == "X" && $step_sns["gender"] != "") {
                        if ($step_sns["gender"] == "MALE") {
                            $arr_mem["mm_sex"] = "남";
                        } else if ($step_sns["gender"] == "FEMALE") {
                            $arr_mem["mm_sex"] = "여";
                        } else if ($step_sns["gender"] == "남" || $step_sns["gender"] == "여") {
                            $arr_mem["mm_sex"] = $step_sns["gender"];
                        } else {
                            $arr_mem["mm_sex"] = $step_sns["gender"]; // 기타 값은 그대로 사용
                        }
                    }
                } else {
                    $arr_mem["mm_join_type"] = "WEB";
                    $arr_mem["mm_join_key"] = "";
                }

                //json_return("99","TEST mm_name ".$arr_mem["mm_name"]);//test
                //json_return("99","TEST mm_phone ".$arr_mem["mm_phone"]);//test
                //json_return("99","TEST mm_di ".$arr_mem["mm_di"]);//test
                //json_return("99","TEST mm_ci ".$arr_mem["mm_ci"]);//test
                //json_return("99","TEST mm_sex ".$arr_mem["mm_sex"]);//test
                //json_return("99","TEST mm_birth [".$arr_mem["mm_birth"]."]");//test
                //json_return("99","TEST mm_nation [".$arr_mem["mm_nation"]."]");//test
                //exit;

                //$string = implode(", ", $list[2]);
                //$string = implode(", ", $arr_mem);
                //json_return("99","TEST MEM8 ".$string);//test

                //exit;
                $result_join = $this->member_model->insertMember($arr_mem);
                //json_return("99","TEST : ".implode(", ", $result_join));//test
                //json_return("99","TEST result: ".$result_join["code"]);//test
                //exit;

                if ($result_join["code"] != "00") {
                    $this->db->trans_rollback();
                    json_return("91", "[01]회원가입시 오류가 발생하였습니다.");
                }

                // mm_idx NULL 체크 (INSERT ID 생성 실패 시)
                if (empty($result_join["key"])) {
                    $this->db->trans_rollback();
                    json_return("91", "[01]회원 ID 생성에 실패했습니다. 다시 시도해주세요.");
                    exit;
                }

                $mm_idx = $result_join["key"];

                //json_return("91",$ma_type."오류가1".$proc_type);
                //exit;

                # 토큰정보 연동
                if ($alarm["uuid"] != "") {
                    $this->member_model->setMemberDevice(["mm_idx" => $result_join["key"]], ["uuid" => $alarm["uuid"]]);
                }

                //json_return("91",$ma_type."오류가2".$proc_type);
                //exit;

                $arr_addr = [
                    "mm_idx" => $result_join["key"],
                    "ad_type" => "MEMBER",
                    "ad_key" => $result_join["key"],
                    "ad_zipcode" => $step2_info["ad_zipcode"],
                    "ad_jibun" => $step2_info["ad_jibun"],
                    "ad_doro" => $step2_info["ad_doro"],
                    "ad_detail" => $step2_info["ad_detail"],
                    "ad_addr" => $step2_info["ad_addr"],
                    "ad_gps_x" => $step2_info["ad_gps_x"],
                    "ad_gps_y" => $step2_info["ad_gps_y"],
                ];

                //json_return("91",$ma_type."오류가3".$proc_type);
                //exit;
                //return json_return("99","3TEST".$step2_info["ad_gps_x"]);
                if ($step2_info["ad_gps_x"] != "") {

                    $result_addr = $this->member_model->insertAddress($arr_addr);
                    if ($result_addr["code"] != "00") {
                        $this->db->trans_rollback();
                        json_return("92", "[02]회원가입시 오류가 발생하였습니다.");
                    }
                }
                // 신규 가입시 GPS 없으면 주소 저장 안 함 (빈값으로 저장되지 않도록)

                // SNS 가입 시 $arr_mem["mm_birth"] 사용, 일반 가입 시 $step_auth["mm_birth"] 사용
                $birth_for_age = !empty($step_sns) ? $arr_mem["mm_birth"] : (isset($step_auth["mm_birth"]) ? $step_auth["mm_birth"] : "");
                $ma_age = getKoreanAge($birth_for_age);
                $arr_mem_info = [
                    "mm_idx" => $mm_idx,
                    "ma_age" => $ma_age,
                ];

                $result_info = $this->member_model->insertMemberInfo($arr_mem_info);
                if ($result_info["code"] != "00") {
                    $this->db->trans_rollback();
                    return json_return("93", "[03]회원가입시 오류가 발생하였습니다.");
                }

                # 포인트 추가
                $pay_point = 5000;
                $insert = [
                    "mm_idx" => $mm_idx,
                    "o_num" => "",
                    "pm_idx" => 0,
                    "pt_type" => "JOIN",
                    "pl_type" => "plus",
                    "pl_point" => $pay_point,
                    "pl_last_point" => $pay_point,
                    "pl_desc" => "회원가입 포인트 지급(5,000P)",
                    "reg_id" => "system",
                ];
                $result_point = $this->site_model->insertPointList($insert);

                #회원 정보 수정
                $this->member_model->updateMember(["mm_point" => $pay_point], ["mm_idx" => $mm_idx]);

                # 회원정보 조회
                $mem_info = $this->member_model->getMember(["mm_id" => $arr_mem["mm_id"]]);

                $this->user->setUser([
                    "mm_idx" => $mem_info["mm_idx"],
                    "mm_id" => $mem_info["mm_id"],
                    "mm_point" => $mem_info["mm_point"],
                    "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
                    "mm_type" => $mem_info["mm_type"],
                    "mm_join_type" => $mem_info["mm_join_type"],
                    "ad_addr" => $step2_info["ad_addr"],
                    "ad_gps_x" => $step2_info["ad_gps_x"],
                    "ad_gps_y" => $step2_info["ad_gps_y"],
                    "mm_sex" => $mem_info["mm_sex"],
                ]);

                # 접속 처리
                $this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $arr_mem["mm_id"]]);

                # 로그인 성공 시 해당 사용자의 모든 디바이스 중 토큰이 있는 경우 push_yn을 'Y'로 활성화
                $sql = "UPDATE st_member_device 
                        SET push_yn = 'Y', up_dt = NOW() 
                        WHERE mm_idx = ? AND token IS NOT NULL AND token != ''";
                $this->db->query($sql, [$mem_info["mm_idx"]]);

                # 완료 로그
                $this->site_model->setSiteLog(
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN_COMP",
                        "sl_step" => "가입완료",
                        "sl_txt" => ""
                    ],
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN_COMP",
                        "sl_step" => "가입완료",
                        "sl_status" => "Y"
                    ]
                );

                # 로그 업데이트
                $this->site_model->updateSiteLog(["sl_status" => "N", "mm_idx" => $mem_info["mm_idx"]], ["session_id" => session_id()]);

                # 몽고 디비 추가
                $this->setMongoUser($arr_mem, $alarm["token"]);

                $this->db->trans_commit();
                error_log("[join_complete_proc] MEM - PART 응답 - mm_id: " . $arr_mem["mm_id"]);
                json_return("00", "회원가입 완료", ["mm_id" => $arr_mem["mm_id"]]);
            }
        } else if ($ma_type == "COMP") {


            $list = $this->site_model->getSiteLogList(
                ["session_id" => session_id(), "sl_type" => "JOIN_COMP", "sl_status" => "Y"]
            );

            if ($list == null) {
                json_return("10", "등록 데이터가 존재하지 않습니다.", $list);
            }

            $step_sns = $this->site_model->getStepTempData("JOIN", "SNS");
            $step_auth = $this->site_model->getStepData($list, "본인인증");
            $step1_info = $this->site_model->getStepData($list, "스텝1");
            $step2_info = $this->site_model->getStepData($list, "스텝2");
            $step3_info = $this->site_model->getStepData($list, "스텝3");
            $step4_info = $this->site_model->getStepData($list, "스텝4");
            $step5_info = $this->site_model->getStepData($list, "스텝5");
            $step6_info = $this->site_model->getStepData($list, "스텝6");

            // 방어 처리: 누락된 단계 데이터 기본값 세팅 (iOS 500 방지)
            if (!is_array($step_auth)) $step_auth = [];
            if (!is_array($step1_info)) $step1_info = [];
            if (!is_array($step2_info)) $step2_info = [];
            if (!is_array($step3_info)) $step3_info = [];
            if (!is_array($step4_info)) $step4_info = [];
            if (!is_array($step5_info)) $step5_info = [];
            if (!is_array($step6_info)) $step6_info = [];

            if ($proc_type == "ALL") {
                # 트랜젝션 시작
                $this->db->trans_begin();

                # 데이터 수집
                // SNS 회원가입인지 일반 회원가입인지에 따라 초기값 분기 처리
                if (!empty($step_sns)) {
                    // SNS 회원가입: SNS 정보만 사용 (본인인증 없음)
                    $mm_name_init = !empty($step_sns["username"]) ? $step_sns["username"] : "";
                    $mm_phone_init = !empty($step_sns["phonenum"]) ? $step_sns["phonenum"] : "";
                    // 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
                    $mm_sex_init = "X";
                    if (!empty($step_sns["gender"])) {
                        if ($step_sns["gender"] == "MALE") {
                            $mm_sex_init = "남";
                        } else if ($step_sns["gender"] == "FEMALE") {
                            $mm_sex_init = "여";
                        } else if ($step_sns["gender"] == "남" || $step_sns["gender"] == "여") {
                            $mm_sex_init = $step_sns["gender"];
                        } else {
                            $mm_sex_init = $step_sns["gender"]; // 기타 값은 그대로 사용
                        }
                    }
                    // SNS의 birthyear와 birthday를 YYYYMMDD 형식으로 변환
                    // birthday는 "MMDD" 또는 "MM-DD" 형식일 수 있으므로 하이픈 제거
                    $mm_birth_init = "";
                    if (!empty($step_sns["birthyear"]) && !empty($step_sns["birthday"])) {
                        $birthday_clean = str_replace("-", "", $step_sns["birthday"]); // 하이픈 제거
                        $mm_birth_init = $step_sns["birthyear"] . $birthday_clean;
                    }
                    $mm_nation_init = "";
                    $mm_ci_init = "";
                    $mm_di_init = "";
                } else {
                    // 일반 회원가입: 본인인증 정보 사용
                    $mm_name_init = isset($step_auth["mm_name"]) ? $step_auth["mm_name"] : "";
                    $mm_phone_init = isset($step_auth["mm_phone"]) ? $step_auth["mm_phone"] : "";
                    $mm_sex_init = isset($step_auth["mm_sex"]) ? $step_auth["mm_sex"] : "";
                    $mm_birth_init = isset($step_auth["mm_birth"]) ? $step_auth["mm_birth"] : "";
                    $mm_nation_init = isset($step_auth["mm_nation"]) ? $step_auth["mm_nation"] : "";
                    $mm_ci_init = isset($step_auth["mm_ci"]) ? $step_auth["mm_ci"] : "";
                    $mm_di_init = isset($step_auth["mm_di"]) ? $step_auth["mm_di"] : "";
                }

                $arr_mem = [
                    "mm_type" => "COMP",
                    "mm_status" => "Y",
                    "mm_id" => isset($step3_info["mm_id"]) ? $step3_info["mm_id"] : "",
                    "mm_img1" => isset($step6_info["mm_img1"]) ? $step6_info["mm_img1"] : "",
                    "mm_thumb" => isset($step6_info["mm_thumb"]) ? $step6_info["mm_thumb"] : "",
                    "mm_pwd" => $this->common->getSqlPass(isset($step3_info["mm_pwd"]) ? $step3_info["mm_pwd"] : ""),
                    "mm_name" => $mm_name_init,
                    "mm_sex" => $mm_sex_init,
                    "mm_nation" => $mm_nation_init,
                    "mm_birth" => $mm_birth_init,
                    "mm_phone" => $mm_phone_init,
                    "mm_comp_type" => isset($step2_info["mm_comp_type"]) ? $step2_info["mm_comp_type"] : "",
                    "mm_comp_name" => isset($step2_info["mm_comp_name"]) ? $step2_info["mm_comp_name"] : "",
                    "mm_comp_num" => isset($step2_info["mm_comp_num"]) ? $step2_info["mm_comp_num"] : "",
                    "mm_comp_auth" => isset($step2_info["mm_comp_auth"]) ? $step2_info["mm_comp_auth"] : "",
                    "mm_ci" => $mm_ci_init,
                    "mm_di" => $mm_di_init,
                    "mm_join_ip" => $this->common->getIpAddr(),
                    "alarm_chat" => $alarm["alarm_chat"],
                    "alarm_resume" => $alarm["alarm_resume"],
                    "alarm_fav" => $alarm["alarm_fav"],
                    "alarm_adv" => $alarm["alarm_adv"],
                    "alarm_deny_yn" => $alarm["alarm_deny_yn"],
                    "alarm_deny_yoil" => "",
                    "alarm_deny_stime" => "",
                    "alarm_deny_etime" => "",
                ];

                if (!empty($step_sns)) {
                    $arr_mem["mm_join_type"] = $step_sns["join_type"];
                    $arr_mem["mm_join_key"] = $step_sns["join_key"];
                    $arr_mem["mm_id"] = strtolower($step_sns["join_type"]) . "_" . $step_sns["join_key"];
                    $arr_mem["mm_ci"] = $arr_mem["mm_id"];

                    // SNS 정보로 덮어쓰기 (이미 초기값에서 설정했지만, 재확인)
                    if (!empty($step_sns["username"])) {
                        $arr_mem["mm_name"] = $step_sns["username"];
                    } else if (empty($arr_mem["mm_name"])) {
                        $arr_mem["mm_name"] = $arr_mem["mm_id"];
                    }

                    if (!empty($step_sns["phonenum"])) {
                        $arr_mem["mm_phone"] = $step_sns["phonenum"];
                    } else if (empty($arr_mem["mm_phone"])) {
                        $arr_mem["mm_phone"] = $arr_mem["mm_id"];
                    }

                    // 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
                    if (!empty($step_sns["gender"])) {
                        if ($step_sns["gender"] == "MALE") {
                            $arr_mem["mm_sex"] = "남";
                        } else if ($step_sns["gender"] == "FEMALE") {
                            $arr_mem["mm_sex"] = "여";
                        } else if ($step_sns["gender"] == "남" || $step_sns["gender"] == "여") {
                            $arr_mem["mm_sex"] = $step_sns["gender"];
                        } else {
                            $arr_mem["mm_sex"] = $step_sns["gender"]; // 기타 값은 그대로 사용
                        }
                    } else if (empty($arr_mem["mm_sex"])) {
                        $arr_mem["mm_sex"] = "X";
                    }

                    // mm_birth: SNS의 birthyear와 birthday 합치기 (YYYYMMDD 형식)
                    // birthday는 "MMDD" 또는 "MM-DD" 형식일 수 있으므로 하이픈 제거
                    // 카카오: birthyear와 birthday 모두 제공 가능
                    // 네이버: birthyear는 제공함, birthday는 제공하지 않음 (null)
                    // 네이버의 경우 birthyear만 있으면 YYYY0000 형식으로 저장됨
                    if (!empty($step_sns["birthyear"])) {
                        if (!empty($step_sns["birthday"])) {
                            // birthyear와 birthday 모두 있는 경우 (카카오)
                            $birthday_clean = str_replace("-", "", $step_sns["birthday"]); // 하이픈 제거
                            $arr_mem["mm_birth"] = $step_sns["birthyear"] . $birthday_clean;
                        } else {
                            // birthyear만 있는 경우
                            // 생년만 저장 (YYYY0000 형식)
                            $arr_mem["mm_birth"] = $step_sns["birthyear"] . "0000";
                        }
                    } else {
                        // birthyear가 없는 경우 (네이버: 출생연도 제공 안 함)
                        // birthday만 있어도 YYYYMMDD 형식을 만들 수 없으므로 빈 문자열로 저장
                        $arr_mem["mm_birth"] = "";
                    }

                    if (empty($arr_mem["mm_di"])) {
                        $arr_mem["mm_di"] = $arr_mem["mm_id"];
                    }

                    if (empty($arr_mem["mm_nation"])) {
                        $arr_mem["mm_nation"] = "";
                    }
                } else {
                    $arr_mem["mm_join_type"] = "WEB";
                    $arr_mem["mm_join_key"] = "";
                }

                $result_join = $this->member_model->insertMember($arr_mem);

                // 회원 INSERT 결과 검증
                if (!is_array($result_join) || $result_join["code"] != "00") {
                    $this->db->trans_rollback();
                    json_return("91", "[01]회원가입시 오류가 발생하였습니다.");
                    exit;
                }

                // mm_idx NULL 체크 (INSERT ID 생성 실패 시)
                if (empty($result_join["key"])) {
                    $this->db->trans_rollback();
                    json_return("91", "[01]회원 ID 생성에 실패했습니다. 다시 시도해주세요.");
                    exit;
                }

                $mm_idx = $result_join["key"];

                # 토큰정보 연동
                if ($alarm["uuid"] != "") {
                    $this->member_model->setMemberDevice(["mm_idx" => $mm_idx], ["uuid" => $alarm["uuid"]]);
                }

                $arr_addr = [
                    "mm_idx" => $mm_idx,
                    "ad_type" => "MEMBER",
                    "ad_key" => $mm_idx,
                    "ad_zipcode" => isset($step4_info["ad_zipcode"]) ? $step4_info["ad_zipcode"] : "",
                    "ad_jibun" => isset($step4_info["ad_jibun"]) ? $step4_info["ad_jibun"] : "",
                    "ad_doro" => isset($step4_info["ad_doro"]) ? $step4_info["ad_doro"] : "",
                    "ad_detail" => isset($step4_info["ad_detail"]) ? $step4_info["ad_detail"] : "",
                    "ad_addr" => isset($step4_info["ad_addr"]) ? $step4_info["ad_addr"] : "",
                    "ad_gps_x" => isset($step4_info["ad_gps_x"]) ? $step4_info["ad_gps_x"] : "",
                    "ad_gps_y" => isset($step4_info["ad_gps_y"]) ? $step4_info["ad_gps_y"] : "",
                ];

                $result_addr = $this->member_model->insertAddress($arr_addr);
                if ($result_addr["code"] != "00") {
                    $this->db->trans_rollback();
                    json_return("92", "[02]회원가입시 오류가 발생하였습니다.");
                    exit;
                }

                // SNS 가입 시 $arr_mem["mm_birth"] 사용, 일반 가입 시 $step_auth["mm_birth"] 사용
                $birth_for_age = !empty($step_sns) ? $arr_mem["mm_birth"] : (isset($step_auth["mm_birth"]) ? $step_auth["mm_birth"] : "");
                $ma_age = getKoreanAge($birth_for_age);
                $arr_mem_info = [
                    "mm_idx" => $mm_idx,
                    "ma_work" => arr_implode(",", isset($step6_info["ma_work"]) ? $step6_info["ma_work"] : []),
                    "ma_price_type" => arr_implode(",", isset($step6_info["ma_price_type"]) ? $step6_info["ma_price_type"] : []),
                    "ma_age" => $ma_age,
                    "ma_work_type" => arr_implode(",", isset($step6_info["ma_work_type"]) ? $step6_info["ma_work_type"] : []),
                    "ma_week_yn" => isset($step6_info["ma_week_yn"]) ? $step6_info["ma_week_yn"] : "",
                    "ma_week" => arr_implode(",", isset($step6_info["ma_week"]) ? $step6_info["ma_week"] : []),
                    "ma_sage" => isset($step6_info["ma_sage"]) ? $step6_info["ma_sage"] : "",
                    "ma_eage" => isset($step6_info["ma_eage"]) ? $step6_info["ma_eage"] : "",
                    "ma_sex" => arr_implode(",", isset($step6_info["ma_sex"]) ? $step6_info["ma_sex"] : []),
                    "ma_nation" => arr_implode(",", isset($step6_info["ma_nation"]) ? $step6_info["ma_nation"] : []),
                    "ma_edu" => isset($step6_info["ma_edu"]) ? $step6_info["ma_edu"] : "",
                    "ma_life_type" => arr_implode(",", isset($step6_info["ma_life_type"]) ? $step6_info["ma_life_type"] : []),
                    "ma_desc" => "",
                ];

                $result_info = $this->member_model->insertMemberInfo($arr_mem_info);

                if ($result_info["code"] != "00") {
                    $this->db->trans_rollback();
                    return json_return("93", "[03]회원가입시 오류가 발생하였습니다.");
                }

                # 포인트 추가
                $pay_point = 10000;
                $insert = [
                    "mm_idx" => $mm_idx,
                    "o_num" => "",
                    "pm_idx" => 0,
                    "pt_type" => "JOIN",
                    "pl_type" => "plus",
                    "pl_point" => $pay_point,
                    "pl_last_point" => $pay_point,
                    "pl_desc" => "회원가입 포인트 지급(10,000P)",
                    "reg_id" => "system",
                ];
                $result_point = $this->site_model->insertPointList($insert);

                #회원 정보 수정
                $this->member_model->updateMember(["mm_point" => $pay_point], ["mm_idx" => $mm_idx]);


                # 정보조회후 로그인처리
                $mem_info = $this->member_model->getMember(["mm_id" => $arr_mem["mm_id"]]);

                $this->user->setUser([
                    "mm_idx" => $mem_info["mm_idx"],
                    "mm_id" => $mem_info["mm_id"],
                    "mm_point" => $mem_info["mm_point"],
                    "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
                    "mm_type" => $mem_info["mm_type"],
                    "mm_join_type" => $mem_info["mm_join_type"],
                    "ad_addr" => isset($step4_info["ad_addr"]) ? $step4_info["ad_addr"] : "",
                    "ad_gps_x" => isset($step4_info["ad_gps_x"]) ? $step4_info["ad_gps_x"] : "",
                    "ad_gps_y" => isset($step4_info["ad_gps_y"]) ? $step4_info["ad_gps_y"] : "",
                    "mm_sex" => $mem_info["mm_sex"],
                ]);

                # 접속 처리
                $this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $arr_mem["mm_id"]]);

                # 로그인 성공 시 해당 사용자의 모든 디바이스 중 토큰이 있는 경우 push_yn을 'Y'로 활성화
                $sql = "UPDATE st_member_device
                        SET push_yn = 'Y', up_dt = NOW()
                        WHERE mm_idx = ? AND token IS NOT NULL AND token != ''";
                $this->db->query($sql, [$mem_info["mm_idx"]]);

                # 완료 로그
                $this->site_model->setSiteLog(
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN_COMP",
                        "sl_step" => "가입완료",
                        "sl_txt" => ""
                    ],
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN_COMP",
                        "sl_step" => "가입완료",
                        "sl_status" => "Y"
                    ]
                );

                # 로그 업데이트
                $this->site_model->updateSiteLog(["sl_status" => "N", "mm_idx" => $mem_info["mm_idx"]], ["session_id" => session_id()]);

                # 몽고 디비 추가
                $this->setMongoUser($arr_mem, $alarm["token"]);

                $this->db->trans_commit();

                // 로그인 세션 설정 (join_comp_finish에서 isLogin = true가 되도록)
                $addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $arr_mem["mm_idx"]]);
                if (!is_array($addr_info)) {
                    $addr_info = [];
                }
                $this->user->setUser([
                    "mm_idx" => $mm_idx,
                    "mm_id" => $arr_mem["mm_id"],
                    "mm_point" => $arr_mem["mm_point"],
                    "mm_name" => isset($arr_mem["mm_comp_name"]) ? $arr_mem["mm_comp_name"] : $arr_mem["mm_name"],
                    "mm_type" => $arr_mem["mm_type"],
                    "mm_join_type" => $arr_mem["mm_join_type"],
                    "mm_img" => $arr_mem["mm_thumb"],
                    "ad_addr" => isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : "",
                    "ad_gps_x" => isset($addr_info["ad_gps_x"]) ? $addr_info["ad_gps_x"] : "",
                    "ad_gps_y" => isset($addr_info["ad_gps_y"]) ? $addr_info["ad_gps_y"] : "",
                    "mm_sex" => $arr_mem["mm_sex"],
                ]);

                error_log("[join_complete_proc] COMP - NICE 응답 - mm_id: " . $arr_mem["mm_id"]);
                return json_return("00", "회원가입 완료", ["mm_id" => $arr_mem["mm_id"]]);
            } else {
                //PART

                //json_return("99","TEST2","");

                # 트랜젝션 시작
                $this->db->trans_begin();
                /*
                    # 데이터 수집
                    $arr_mem = [
                        "mm_type"=>"COMP",
                        "mm_status"=>"Y",
                        "mm_id"=>$step3_info["mm_id"],
                        "mm_pwd"=>$this->common->getSqlPass($step3_info["mm_pwd"]),
                        "mm_name"=>$step_auth["mm_name"],
                        "mm_sex"=>$step_auth["mm_sex"],
                        "mm_nation"=>$step_auth["mm_nation"],
                        "mm_birth"=>$step_auth["mm_birth"],
                        "mm_phone"=>$step_auth["mm_phone"],
                        "mm_comp_type"=>$step2_info["mm_comp_type"],
                        "mm_comp_name"=>$step2_info["mm_comp_name"],
                        "mm_comp_num"=>$step2_info["mm_comp_num"],
                        "mm_comp_auth"=>$step2_info["mm_comp_auth"],
                        "mm_ci"=>$step_auth["mm_ci"],
                        "mm_di"=>$step_auth["mm_di"],
	                    "mm_join_ip"=>$this->common->getIpAddr(),
	                    "alarm_chat"=>$alarm["alarm_chat"],
	                    "alarm_resume"=>$alarm["alarm_resume"],
	                    "alarm_fav"=>$alarm["alarm_fav"],
	                    "alarm_adv"=>$alarm["alarm_adv"],
	                    "alarm_deny_yn"=>$alarm["alarm_deny_yn"],
	                    "alarm_deny_yoil"=>"",
	                    "alarm_deny_stime"=>"",
	                    "alarm_deny_etime"=>"",
	                ];
					*/

                $agree1 = !empty($step4_info["agree1"]) ? $step4_info["agree1"] : (!empty($step5_info["agree1"]) ? $step5_info["agree1"] : "");
                $agree2 = !empty($step4_info["agree2"]) ? $step4_info["agree2"] : (!empty($step5_info["agree2"]) ? $step5_info["agree2"] : "");
                $agree3 = !empty($step4_info["agree3"]) ? $step4_info["agree3"] : (!empty($step5_info["agree3"]) ? $step5_info["agree3"] : "");
                $agree4 = !empty($step4_info["agree4"]) ? $step4_info["agree4"] : (!empty($step5_info["agree4"]) ? $step5_info["agree4"] : "");
                $agree5 = !empty($step4_info["agree5"]) ? $step4_info["agree5"] : (!empty($step5_info["agree5"]) ? $step5_info["agree5"] : "");

                # 데이터 수집
                // SNS 회원가입인지 일반 회원가입인지에 따라 초기값 분기 처리
                if (!empty($step_sns)) {
                    // SNS 회원가입: SNS 정보만 사용 (본인인증 없음)
                    $mm_name_init = !empty($step_sns["username"]) ? $step_sns["username"] : "";
                    $mm_phone_init = !empty($step_sns["phonenum"]) ? $step_sns["phonenum"] : "";
                    // 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
                    $mm_sex_init = "X";
                    if (!empty($step_sns["gender"])) {
                        if ($step_sns["gender"] == "MALE") {
                            $mm_sex_init = "남";
                        } else if ($step_sns["gender"] == "FEMALE") {
                            $mm_sex_init = "여";
                        } else if ($step_sns["gender"] == "남" || $step_sns["gender"] == "여") {
                            $mm_sex_init = $step_sns["gender"];
                        } else {
                            $mm_sex_init = $step_sns["gender"]; // 기타 값은 그대로 사용
                        }
                    }
                    // SNS의 birthyear와 birthday를 YYYYMMDD 형식으로 변환
                    // birthday는 "MMDD" 또는 "MM-DD" 형식일 수 있으므로 하이픈 제거
                    $mm_birth_init = "";
                    if (!empty($step_sns["birthyear"]) && !empty($step_sns["birthday"])) {
                        $birthday_clean = str_replace("-", "", $step_sns["birthday"]); // 하이픈 제거
                        $mm_birth_init = $step_sns["birthyear"] . $birthday_clean;
                    }
                    $mm_nation_init = "";
                    $mm_di_init = "";
                } else {
                    // 일반 회원가입: 본인인증 정보 사용
                    $mm_name_init = isset($step_auth["mm_name"]) ? $step_auth["mm_name"] : "";
                    $mm_phone_init = isset($step_auth["mm_phone"]) ? $step_auth["mm_phone"] : "";
                    $mm_sex_init = isset($step_auth["mm_sex"]) ? $step_auth["mm_sex"] : "";
                    $mm_birth_init = isset($step_auth["mm_birth"]) ? $step_auth["mm_birth"] : "";
                    $mm_nation_init = isset($step_auth["mm_nation"]) ? $step_auth["mm_nation"] : "";
                    $mm_di_init = isset($step_auth["mm_di"]) ? $step_auth["mm_di"] : "";
                }

                $arr_mem = [
                    "mm_type" => "COMP",
                    "mm_status" => "Y",
                    "mm_id" => $step3_info["mm_id"],
                    "mm_pwd" => $this->common->getSqlPass($step3_info["mm_pwd"]),
                    "mm_name" => $mm_name_init,
                    "mm_sex" => $mm_sex_init,
                    "mm_nation" => $mm_nation_init,
                    "mm_birth" => $mm_birth_init,
                    "mm_phone" => $mm_phone_init,
                    "mm_comp_type" => "COMP",
                    "mm_comp_name" => $step3_info["mm_id"],
                    "mm_comp_num" => "",
                    "mm_comp_auth" => "",
                    "mm_ci" => $step3_info["mm_id"],
                    "mm_di" => $mm_di_init,
                    "mm_join_ip" => $this->common->getIpAddr(),
                    "alarm_chat" => $alarm["alarm_chat"],
                    "alarm_resume" => $alarm["alarm_resume"],
                    "alarm_fav" => $alarm["alarm_fav"],
                    "alarm_adv" => $alarm["alarm_adv"],
                    "alarm_deny_yn" => $alarm["alarm_deny_yn"],
                    "alarm_deny_yoil" => "",
                    "alarm_deny_stime" => "",
                    "alarm_deny_etime" => "",
                    "mm_agree_service" => $agree1,
                    "mm_agree_privacy" => $agree2,
                    "mm_agree_location" => $agree3,
                    "mm_agree_privget" => $agree4,
                    "mm_agree_ad" => $agree5

                ];

                if (!empty($step_sns)) {
                    $arr_mem["mm_join_type"] = $step_sns["join_type"];
                    $arr_mem["mm_join_key"] = $step_sns["join_key"];
                    $arr_mem["mm_id"] = strtolower($step_sns["join_type"]) . "_" . $step_sns["join_key"];
                    $arr_mem["mm_ci"] = $arr_mem["mm_id"];

                    // SNS 정보로 덮어쓰기 (이미 초기값에서 설정했지만, 재확인)
                    if (!empty($step_sns["username"])) {
                        $arr_mem["mm_name"] = $step_sns["username"];
                    } else if (empty($arr_mem["mm_name"])) {
                        $arr_mem["mm_name"] = $arr_mem["mm_id"];
                    }

                    if (!empty($step_sns["phonenum"])) {
                        $arr_mem["mm_phone"] = $step_sns["phonenum"];
                    } else if (empty($arr_mem["mm_phone"])) {
                        $arr_mem["mm_phone"] = $arr_mem["mm_id"];
                    }

                    // 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
                    if (!empty($step_sns["gender"])) {
                        if ($step_sns["gender"] == "MALE") {
                            $arr_mem["mm_sex"] = "남";
                        } else if ($step_sns["gender"] == "FEMALE") {
                            $arr_mem["mm_sex"] = "여";
                        } else if ($step_sns["gender"] == "남" || $step_sns["gender"] == "여") {
                            $arr_mem["mm_sex"] = $step_sns["gender"];
                        } else {
                            $arr_mem["mm_sex"] = $step_sns["gender"]; // 기타 값은 그대로 사용
                        }
                    } else if (empty($arr_mem["mm_sex"])) {
                        $arr_mem["mm_sex"] = "X";
                    }

                    // mm_birth: SNS의 birthyear와 birthday 합치기 (YYYYMMDD 형식)
                    // birthday는 "MMDD" 또는 "MM-DD" 형식일 수 있으므로 하이픈 제거
                    // 카카오: birthyear와 birthday 모두 제공 가능
                    // 네이버: birthyear는 제공함, birthday는 제공하지 않음 (null)
                    // 네이버의 경우 birthyear만 있으면 YYYY0000 형식으로 저장됨
                    if (!empty($step_sns["birthyear"])) {
                        if (!empty($step_sns["birthday"])) {
                            // birthyear와 birthday 모두 있는 경우 (카카오)
                            $birthday_clean = str_replace("-", "", $step_sns["birthday"]); // 하이픈 제거
                            $arr_mem["mm_birth"] = $step_sns["birthyear"] . $birthday_clean;
                        } else {
                            // birthyear만 있는 경우
                            // 생년만 저장 (YYYY0000 형식)
                            $arr_mem["mm_birth"] = $step_sns["birthyear"] . "0000";
                        }
                    } else {
                        // birthyear가 없는 경우 (네이버: 출생연도 제공 안 함)
                        // birthday만 있어도 YYYYMMDD 형식을 만들 수 없으므로 빈 문자열로 저장
                        $arr_mem["mm_birth"] = "";
                    }

                    if (empty($arr_mem["mm_di"])) {
                        $arr_mem["mm_di"] = $arr_mem["mm_id"];
                    }

                    if (empty($arr_mem["mm_nation"])) {
                        $arr_mem["mm_nation"] = "";
                    }

                    // 성별 정보는 SNS에서 가져온 값으로 덮어쓰기
                    // 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
                    if ($arr_mem["mm_sex"] == "X" && $step_sns["gender"] != "") {
                        if ($step_sns["gender"] == "MALE") {
                            $arr_mem["mm_sex"] = "남";
                        } else if ($step_sns["gender"] == "FEMALE") {
                            $arr_mem["mm_sex"] = "여";
                        } else if ($step_sns["gender"] == "남" || $step_sns["gender"] == "여") {
                            $arr_mem["mm_sex"] = $step_sns["gender"];
                        } else {
                            $arr_mem["mm_sex"] = $step_sns["gender"]; // 기타 값은 그대로 사용
                        }
                    }
                } else {
                    $arr_mem["mm_join_type"] = "WEB";
                    $arr_mem["mm_join_key"] = "";
                }

                $result_join = $this->member_model->insertMember($arr_mem);

                if ($result_join["code"] != "00") {
                    $this->db->trans_rollback();
                    return json_return("91", "[01]회원가입시 오류가 발생하였습니다.");
                }

                // mm_idx NULL 체크 (INSERT ID 생성 실패 시)
                if (empty($result_join["key"])) {
                    $this->db->trans_rollback();
                    return json_return("91", "[01]회원 ID 생성에 실패했습니다. 다시 시도해주세요.");
                }

                $mm_idx = $result_join["key"];

                $arr_addr = [
                    "mm_idx" => $result_join["key"],
                    "ad_type" => "MEMBER",
                    "ad_key" => $result_join["key"],
                    "ad_zipcode" => $step4_info["ad_zipcode"],
                    "ad_jibun" => $step4_info["ad_jibun"],
                    "ad_doro" => $step4_info["ad_doro"],
                    "ad_detail" => $step4_info["ad_detail"],
                    "ad_addr" => $step4_info["ad_addr"],
                    "ad_gps_x" => $step4_info["ad_gps_x"],
                    "ad_gps_y" => $step4_info["ad_gps_y"],
                ];

                //return json_return("99","TEST2");

                // GPS 좌표가 있을 때만 주소 저장 (빈값으로 저장 안 함)


                # 토큰정보 연동
                if ($alarm["uuid"] != "") {
                    $this->member_model->setMemberDevice(["mm_idx" => $result_join["key"]], ["uuid" => $alarm["uuid"]]);
                }

                // SNS 가입 시 $arr_mem["mm_birth"] 사용, 일반 가입 시 $step_auth["mm_birth"] 사용
                $birth_for_age = !empty($step_sns) ? $arr_mem["mm_birth"] : (isset($step_auth["mm_birth"]) ? $step_auth["mm_birth"] : "");
                $ma_age = getKoreanAge($birth_for_age);
                $arr_mem_info = [
                    "mm_idx" => $mm_idx,
                    "ma_age" => $ma_age,
                ];

                $result_info = $this->member_model->insertMemberInfo($arr_mem_info);
                if ($result_info["code"] != "00") {
                    $this->db->trans_rollback();
                    return json_return("93", "[03]회원가입시 오류가 발생하였습니다.");
                }

                # 포인트 추가
                $pay_point = 10000;
                $insert = [
                    "mm_idx" => $mm_idx,
                    "o_num" => "",
                    "pm_idx" => 0,
                    "pt_type" => "JOIN",
                    "pl_type" => "plus",
                    "pl_point" => $pay_point,
                    "pl_last_point" => $pay_point,
                    "pl_desc" => "회원가입 포인트 지급(10,000P)",
                    "reg_id" => "system",
                ];
                $result_point = $this->site_model->insertPointList($insert);

                #회원 정보 수정
                $this->member_model->updateMember(["mm_point" => $pay_point], ["mm_idx" => $mm_idx]);

                # 회원정보 조회
                $mem_info = $this->member_model->getMember(["mm_id" => $arr_mem["mm_id"]]);

                $this->user->setUser([
                    "mm_idx" => $mem_info["mm_idx"],
                    "mm_id" => $mem_info["mm_id"],
                    "mm_point" => $mem_info["mm_point"],
                    "mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
                    "mm_type" => $mem_info["mm_type"],
                    "mm_join_type" => $mem_info["mm_join_type"],
                    "ad_addr" => $step4_info["ad_addr"],
                    "ad_gps_x" => $step4_info["ad_gps_x"],
                    "ad_gps_y" => $step4_info["ad_gps_y"],
                    "mm_sex" => $mem_info["mm_sex"],
                ]);

                # 접속 처리
                $this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $arr_mem["mm_id"]]);

                # 로그인 성공 시 해당 사용자의 모든 디바이스 중 토큰이 있는 경우 push_yn을 'Y'로 활성화
                $sql = "UPDATE st_member_device 
                        SET push_yn = 'Y', up_dt = NOW() 
                        WHERE mm_idx = ? AND token IS NOT NULL AND token != ''";
                $this->db->query($sql, [$mem_info["mm_idx"]]);

                # 완료 로그
                $this->site_model->setSiteLog(
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN_COMP",
                        "sl_step" => "가입완료",
                        "sl_txt" => ""
                    ],
                    [
                        "session_id" => session_id(),
                        "sl_type" => "JOIN_COMP",
                        "sl_step" => "가입완료",
                        "sl_status" => "Y"
                    ]
                );

                # 로그 업데이트
                $this->site_model->updateSiteLog(["sl_status" => "N", "mm_idx" => $mem_info["mm_idx"]], ["session_id" => session_id()]);

                # 몽고 디비 추가
                $this->setMongoUser($arr_mem, $alarm["token"]);

                $this->db->trans_commit();
                error_log("[join_complete_proc] COMP - PART 응답 - mm_id: " . $arr_mem["mm_id"]);
                return json_return("00", "회원가입 완료", ["mm_id" => $arr_mem["mm_id"]]);
            }
        }
    }

    # 웹 회원가입
    function join_wmem_step3()
    {
        # 데이터 추가.
        $ma_work = input_post("ma_work", "");
        $ma_price_type = input_post("ma_price_type", "");
        $ma_work_type = input_post("ma_work_type", "");
        $ma_week = input_post("ma_week", "");
        $ma_week_yn = input_post("ma_week_yn", "");
        $ma_work_dt = input_post("ma_work_dt", "");
        $ma_edu = input_post("ma_edu", "");
        $ma_my_type = input_post("ma_my_type", "");
        $ma_desc = input_post("ma_desc", "");
        $arr_certi = input_post("arr_certi", "");
        $arr_career = input_post("arr_career", "");


        # 기존 등록 이미지 있으면 삭제
        $log_info = $this->site_model->getSiteLog(
            ["session_id" => session_id(), "sl_type" => "JOIN_MEM", "sl_step" => "스텝4_4"]
        );

        if ($log_info != null) {
            try {
                $json_info = json_decode($log_info["sl_txt"], true);
                file_remove($json_info["mm_img1"]);
                file_remove($json_info["mm_img2"]);
                file_remove($json_info["mm_img3"]);
                file_remove($json_info["mm_img4"]);
                file_remove($json_info["mm_img5"]);
            } catch (Exception $ex) {
            }
        }

        # 이미지 처리.
        $mm_img1 = $this->common->img_upload_resize_url("member", "mm_img1", 1200);
        $mm_img2 = $this->common->img_upload_resize_url("member", "mm_img2", 1200);
        $mm_img3 = $this->common->img_upload_resize_url("member", "mm_img3", 1200);
        $mm_img4 = $this->common->img_upload_resize_url("member", "mm_img4", 1200);
        $mm_img5 = $this->common->img_upload_resize_url("member", "mm_img5", 1200);
        $mm_thumb = $this->common->img_upload_resize_url("member", "mm_img1", 600);

        #로그 생성
        $sl_txt = [
            "ma_work" => $ma_work,
            "ma_price_type" => $ma_price_type,
            "ma_work_type" => $ma_work_type,
            "ma_week_yn" => $ma_week_yn,
            "ma_week" => $ma_week
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_1",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_1",
                "sl_status" => "Y"
            ]
        );

        #로그 생성
        $sl_txt = [
            "ma_edu" => $ma_edu,
            "ma_work_dt" => $ma_work_dt
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_2",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_2",
                "sl_status" => "Y"
            ]
        );

        #로그 생성
        $sl_txt = [
            "arr_career" => $arr_career,
            "ma_my_type" => $ma_my_type
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_3",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_3",
                "sl_status" => "Y"
            ]
        );

        #로그 생성
        $sl_txt = [
            "arr_certi" => $arr_certi,
            "ma_desc" => $ma_desc,
            "mm_img1" => $mm_img1,
            "mm_img2" => $mm_img2,
            "mm_img3" => $mm_img3,
            "mm_img4" => $mm_img4,
            "mm_img5" => $mm_img5,
            "mm_thumb" => $mm_thumb,
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_4",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_MEM",
                "sl_step" => "스텝4_4",
                "sl_status" => "Y"
            ]
        );

        # 전체 등록 파일 조회 후 리턴
        $return_data = [];
        $list = $this->site_model->getSiteLogList(
            ["session_id" => session_id(), "sl_type" => "JOIN_MEM", "sl_status" => "Y"]
        );

        if ($list == null) {
            json_return("10", "등록 데이터가 존재하지 않습니다.", $list);
        }

        $step_auth = $this->site_model->getStepData($list, "본인인증");
        $step1_info = $this->site_model->getStepData($list, "스텝1");
        $step2_info = $this->site_model->getStepData($list, "스텝2");
        $step4_1_info = $this->site_model->getStepData($list, "스텝4_1");
        $step4_2_info = $this->site_model->getStepData($list, "스텝4_2");
        $step4_3_info = $this->site_model->getStepData($list, "스텝4_3");
        $step4_4_info = $this->site_model->getStepData($list, "스텝4_4");

        $arr_mem = [
            "mm_name" => $step_auth["mm_name"],
            "mm_sex" => $step_auth["mm_sex"],
            "mm_nation" => $step_auth["mm_nation"],
            "mm_birth" => $step_auth["mm_birth"],
            "mm_phone" => $step_auth["mm_phone"],
            //"mm_ci"=>$step_auth["mm_ci"],
            //"mm_di"=>$step_auth["mm_di"],
            "mm_id" => $step1_info["mm_id"],
            //"mm_pwd"=>$step1_info["mm_pwd"],
            //"mm_gps_x"=>$step2_info["mm_gps_x"],
            //"mm_gps_y"=>$step2_info["mm_gps_y"],
            "mm_zipcode" => $step2_info["mm_zipcode"],
            "mm_doro" => $step2_info["mm_doro"],
            "mm_jibun" => $step2_info["mm_jibun"],
            "ad_addr" => $step2_info["ad_addr"],
            "ma_work" => $step4_1_info["ma_work"],
            "ma_work_img" => getWorkingImg(arr_implode(",", $step4_1_info["ma_work"])),
            "ma_price_type" => $step4_1_info["ma_price_type"],
            "ma_work_type" => $step4_1_info["ma_work_type"],
            "ma_week_yn" => $step4_1_info["ma_week_yn"],
            "ma_week" => $step4_1_info["ma_week"],
            "ma_edu" => $step4_2_info["ma_edu"],
            "ma_work_dt" => substr($step4_2_info["ma_work_dt"], 0, 10),
            "ma_work_dt_yoil" => get_yoil($step4_2_info["ma_work_dt"]),
            "arr_career" => $step4_3_info["arr_career"],
            "ma_my_type" => $step4_3_info["ma_my_type"],
            "arr_certi" => $step4_4_info["arr_certi"],
            "ma_desc" => $step4_4_info["ma_desc"],
            "mm_img1" => $step4_4_info["mm_img1"],
            "mm_img2" => $step4_4_info["mm_img2"],
            "mm_img3" => $step4_4_info["mm_img3"],
            "mm_img4" => $step4_4_info["mm_img4"],
            "mm_img5" => $step4_4_info["mm_img5"],
        ];

        json_return("00", "처리완료", $arr_mem);
    }

    function join_wcomp_step4()
    {
        $ma_work = input_post("ma_work", "");
        $ma_price_type = input_post("ma_price_type", "");
        $ma_work_type = input_post("ma_work_type", "");
        $ma_week_yn = input_post("ma_week_yn", "N");
        $ma_week_yn = ($ma_week_yn == "" ? "N" : $ma_week_yn);
        $ma_week = input_post("ma_week", "");
        $ma_sex = input_post("ma_sex", "");
        $ma_sage = input_post("ma_sage", "");
        $ma_eage = input_post("ma_eage", "");
        $ma_life_type = input_post("ma_life_type", "");
        $ma_nation = input_post("ma_nation", "");
        $ma_edu = input_post("ma_edu", "");

        # 데이터 검증
        if ($ma_work == "") {
            json_return("99", "희망업무 선택이 확인되지 않습니다.");
        }

        if ($ma_price_type == "") {
            json_return("99", "희망급여 선택이 확인되지 않습니다.");
        }

        if ($ma_work_type == "") {
            json_return("99", "근무형태 선택이 확인되지 않습니다.");
        }

        if ($ma_week_yn == "N" && $ma_week == "") {
            json_return("99", "근무요일 선택이 확인되지 않습니다.");
        }

        if ($ma_sex == "") {
            json_return("99", "희망성별이 선택이 확인되지 않습니다.");
        }

        # 기존 등록 이미지 있으면 삭제
        $log_info = $this->site_model->getSiteLog(
            ["session_id" => session_id(), "sl_type" => "JOIN_COMP", "sl_step" => "스텝6"]
        );

        if ($log_info != null) {
            try {
                $json_info = json_decode($log_info["sl_txt"], true);
                file_remove($json_info["mm_img1"]);
            } catch (Exception $ex) {
            }
        }

        # 이미지 처리.
        $mm_img1 = $this->common->img_upload_resize_url("member", "mm_img1", 1200);
        $mm_thumb = $this->common->img_upload_resize_url("member", "mm_img1", 600);

        #로그 생성
        $sl_txt = [
            "ma_work" => $ma_work,
            "ma_price_type" => $ma_price_type,
            "ma_work_type" => $ma_work_type,
            "ma_week_yn" => $ma_week_yn,
            "ma_week" => $ma_week,
            "ma_sex" => $ma_sex,
            "ma_sage" => $ma_sage,
            "ma_eage" => $ma_eage,
            "ma_life_type" => $ma_life_type,
            "ma_nation" => $ma_nation,
            "ma_edu" => $ma_edu,
            "mm_img1" => $mm_img1,
            "mm_thumb" => $mm_thumb
        ];

        $this->site_model->setSiteLog(
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝6",
                "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
            ],
            [
                "session_id" => session_id(),
                "sl_type" => "JOIN_COMP",
                "sl_step" => "스텝6",
                "sl_status" => "Y"
            ]
        );

        # 전체 등록 파일 조회 후 리턴
        $return_data = [];
        $list = $this->site_model->getSiteLogList(
            ["session_id" => session_id(), "sl_type" => "JOIN_COMP", "sl_status" => "Y"]
        );

        if ($list == null) {
            json_return("10", "등록 데이터가 존재하지 않습니다.", $list);
        }

        $step_sns = $this->site_model->getStepTempData("JOIN", "SNS");
        $step_auth = $this->site_model->getStepData($list, "본인인증");
        $step1_info = $this->site_model->getStepData($list, "스텝1");
        $step2_info = $this->site_model->getStepData($list, "스텝2");
        $step3_info = $this->site_model->getStepData($list, "스텝3");
        $step4_info = $this->site_model->getStepData($list, "스텝4");
        $step5_info = $this->site_model->getStepData($list, "스텝5");
        $step6_info = $this->site_model->getStepData($list, "스텝6");

        // SNS 회원가입인지 일반 회원가입인지에 따라 데이터 분기 처리
        if (!empty($step_sns)) {
            // SNS 회원가입: SNS 정보 사용
            $mm_name = !empty($step_sns["username"]) ? $step_sns["username"] : "";
            $mm_phone = !empty($step_sns["phonenum"]) ? $step_sns["phonenum"] : "";
            // 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
            $mm_sex = "";
            if (!empty($step_sns["gender"])) {
                if ($step_sns["gender"] == "MALE") {
                    $mm_sex = "남";
                } else if ($step_sns["gender"] == "FEMALE") {
                    $mm_sex = "여";
                } else if ($step_sns["gender"] == "남" || $step_sns["gender"] == "여") {
                    $mm_sex = $step_sns["gender"];
                }
            }
            // SNS의 birthyear와 birthday를 YYYYMMDD 형식으로 변환
            // birthday는 "MMDD" 또는 "MM-DD" 형식일 수 있으므로 하이픈 제거
            // 카카오: birthyear와 birthday 모두 제공 가능
            // 네이버: birthyear는 제공함, birthday는 제공하지 않음 (null)
            // 네이버의 경우 birthyear만 있으면 YYYY0000 형식으로 저장됨
            $mm_birth = "";
            if (!empty($step_sns["birthyear"])) {
                if (!empty($step_sns["birthday"])) {
                    // birthyear와 birthday 모두 있는 경우 (카카오)
                    $birthday_clean = str_replace("-", "", $step_sns["birthday"]); // 하이픈 제거
                    $mm_birth = $step_sns["birthyear"] . $birthday_clean;
                } else {
                    // birthyear만 있는 경우
                    // 생년만 저장 (YYYY0000 형식)
                    $mm_birth = $step_sns["birthyear"] . "0000";
                }
            } else {
                // birthyear가 없는 경우
                // birthday만 있어도 YYYYMMDD 형식을 만들 수 없으므로 빈 문자열로 저장
            }
            $mm_nation = "";
        } else {
            // 일반 회원가입: 본인인증 정보 사용
            $mm_name = isset($step_auth["mm_name"]) ? $step_auth["mm_name"] : "";
            $mm_phone = isset($step_auth["mm_phone"]) ? $step_auth["mm_phone"] : "";
            $mm_sex = isset($step_auth["mm_sex"]) ? $step_auth["mm_sex"] : "";
            $mm_birth = isset($step_auth["mm_birth"]) ? $step_auth["mm_birth"] : "";
            $mm_nation = isset($step_auth["mm_nation"]) ? $step_auth["mm_nation"] : "";
        }

        $arr_mem = [
            "mm_name" => $mm_name,
            "mm_sex" => $mm_sex,
            "mm_nation" => $mm_nation,
            "mm_birth" => $mm_birth,
            "mm_phone" => $mm_phone,
            "mm_id" => $step3_info["mm_id"],
            "mm_comp_type" => $step2_info["mm_comp_type"],
            "mm_comp_name" => $step2_info["mm_comp_name"],
            "mm_comp_num" => $step2_info["mm_comp_num"],
            "mm_zipcode" => $step4_info["mm_zipcode"],
            "mm_doro" => $step4_info["mm_doro"],
            "mm_jibun" => $step4_info["mm_jibun"],
            "ma_work" => $step6_info["ma_work"],
            "ma_work_img" => getWorkingImg(arr_implode(",", $step6_info["ma_work"])),
            "ma_price_type" => $step6_info["ma_price_type"],
            "ma_work_type" => $step6_info["ma_work_type"],
            "ma_week_yn" => $step6_info["ma_week_yn"],
            "ma_week" => $step6_info["ma_week"],
            "ma_sage" => $step6_info["ma_sage"],
            "ma_eage" => $step6_info["ma_eage"],
            "ma_sex" => $step6_info["ma_sex"],
            "ma_life_type" => $step6_info["ma_life_type"],
            "ma_nation" => $step6_info["ma_nation"],
            "ma_edu" => $step6_info["ma_edu"],
            "mm_img1" => $step6_info["mm_img1"],
        ];

        json_return("00", "처리완료", $arr_mem);
    }

    function setMongoUser($mem_info, $token)
    {
        $this->mongo_model->insertUserInfo($mem_info, $token);
    }

    /**
     * 간편로그인 로그인 시 전화번호로 기존 회원 존재 여부 확인 (로그인용)
     * @param string $phone 전화번호
     * @return array ["is_duplicate" => bool, "message" => string]
     */
    private function checkDuplicatePhoneForSnsLogin($phone)
    {
        // 공통 함수 사용
        return checkDuplicatePhoneForSns($phone);
    }

    /**
     * 간편로그인 회원가입 시 전화번호로 기존 회원 존재 여부 확인
     * @param string $phone 전화번호
     * @return bool 중복이 있으면 true 반환하고 에러 메시지 반환, 없으면 false
     */
    private function checkDuplicatePhoneForSns($phone)
    {
        // 공통 함수 사용
        $result = checkDuplicatePhoneForSns($phone);

        if ($result["is_duplicate"]) {
            $this->db->trans_rollback();
            json_return("90", $result["message"]);
            return true;
        }

        return false;
    }

    /**
     * 최근 로그인 타입을 세션에 저장
     */
    /**
     * 최근 로그인 타입을 세션에 저장 (쿠키로도 저장해 로그아웃 후에도 유지)
     */
    public function set_last_login_type()
    {
        $login_type = $this->input->post('login_type');
        $allowed_types = array('KAKAO', 'NAVER', 'GOOGLE', 'WECHAT', 'WEB', 'APPLE');

        if (!empty($login_type) && in_array($login_type, $allowed_types)) {
            $this->common->setSession("last_login_type", $login_type);
            setcookie("last_login_type", $login_type, time() + (86400 * 365), "/");
            session_write_close();
            json_return("00", "success");
        } else {
            json_return("99", "invalid login type");
        }
    }
}
