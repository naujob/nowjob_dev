<?php
class Member_proc extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        # 모델 로드
        $this->load->model('sitemodel', 'site_model');
        $this->load->model('membermodel', 'member_model');
        $this->load->model('mongomodel', 'mongo_model');
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

        # 헤더 정보 업데이트 : 매칭안되어 있으면 업데이트 처리한다.
        $headers = apache_request_headers();
        if (isset($headers["uuid"])) {
            $mem_device = $this->member_model->getMemberDevice(["uuid" => $headers["uuid"], "mm_idx" => 0]);
            # 빈값이 아니라면
            if ($mem_device != null) {
                $this->member_model->setMemberDevice(["mm_idx" => $mem_info["mm_idx"]], ["uuid" => $headers["uuid"]]);
            }
        }

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

        //$mem_info = $this->member_model->getMember(["mm_id"=>$email]);
        $mem_info = $this->member_model->getMember(["mm_id" => $phone, "mm_status" => "Y"]);

        if ($mem_info == null) {
            json_return("91", "조회 안됨");
        } else {
            if ($mem_info["mm_phone"] == $phone) {
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
                $provider_label = "NOWJOB";
                $postposition = "에서";
            } elseif ($join_type === "NAVER") {
                $provider_label = "네이버";
                $postposition = "로";
            } elseif ($join_type === "KAKAO") {
                $provider_label = "카카오";
                $postposition = "로";
            } else {
                $provider_label = "NOWJOB";
                $postposition = "에서";
            }

            $alert_msg = $provider_label . $postposition . " 이미 가입된 전화번호 입니다.";

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
        $type = input_post("type", "");

        $this->common->setSession("AUTH_TYPE", $type);
        if ($type == "MEM") {
            # 로그 남기기
            $this->site_model->setSiteLog(
                [
                    "session_id" => session_id(),
                    "sl_type" => "JOIN",
                    "sl_step" => "개인_인증진행",
                    "sl_txt" => json_encode($return, JSON_UNESCAPED_UNICODE)
                ]
            );
        } else {
            # 로그 남기기
            $this->site_model->setSiteLog(
                [
                    "session_id" => session_id(),
                    "sl_type" => "JOIN",
                    "sl_step" => "기업_인증진행",
                    "sl_txt" => json_encode($return, JSON_UNESCAPED_UNICODE)
                ]
            );
        }

        $this->common->setSession("COM_MAN_REQ", date("Y-m-d H:i:s"));
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

        $COM_MAN_RETIRE = $this->common->getSession("COM_MAN_RETIRE");
        if ($COM_MAN_RETIRE != null) {
            $now = new DateTime(); // 현재 시간
            $comManReqDateTime = new DateTime($COM_MAN_RETIRE); // 비교할 시간
            $interval = $now->getTimestamp() - $comManReqDateTime->getTimestamp();
            if ($interval > 5 * 60) {
                //5분이 경과했으면 오류처리
                if ($os_type == "ios") {
                    echo "<script>alert('5분이내에 인증 해주세요.(탈퇴신청)');location.href='" . "/compage/mypage" . "';</script>";
                } else if ($os_type == "android") {
                    // 2025-01-17: 안드로이드 앱 전용 처리 - opener 없이 직접 이동 (WebView 보안 정책 문제 해결)
                    echo "<script>alert('5분이내에 인증 해주세요.(탈퇴신청)');location.href='" . "/compage/mypage" . "';</script>";
                } else {
                    echo "<script>alert('5분이내에 인증 해주세요.(탈퇴신청)');self.close();</script>";
                }
                exit;
            } else {
                //5분이내 정상
                echo "<script>
              opener.fRetire();
              self.close();
            </script>";
                exit;
            }
        }


        //echo "<script>alert('test');</script>";//test
        //print_r($return); //test
        //Array ( [code] => 00 [msg] => 처리 성공 [errcode] => [requestnumber] =>  [responsenumber] =>  [authtype] => M [name] => 홍길동 [birthdate] => 19990101 [gender] => 1 [nationalinfo] => 0 [dupinfo] =>  [conninfo] => [mobileno] => 01012345678 [mobileco] => )

        $COM_MAN_REQ = $this->common->getSession("COM_MAN_REQ");
        if ($COM_MAN_REQ != null) {
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
                    } else {
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
                            // 안드로이드 앱 전용 처리 - opener 없이 직접 이동
                            alert('본인인증이 완료 되었습니다.');
                            location.href='/member/join_mem_toturial';
                        </script>";
                    } else {
                        // 웹 브라우저용 기존 로직 (opener 사용)
                        echo "<script>
                            // 전역 오류 처리 추가
                            window.onerror = function() { return true; };
                            
                            // opener 안전성 검사
                            if (typeof opener === 'undefined' || opener === null) {
                                alert('본인인증이 완료 되었습니다.');
                                location.href='/member/join_mem_toturial';
                                return;
                            }
                            
                            try {
                                // opener.document 접근 시도 전 안전성 검사
                                if (!opener.document) {
                                    alert('본인인증이 완료 되었습니다.');
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
                                alert('본인인증이 완료 되었습니다.');
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
            if (getDeviceType() == "web") {
                echo "<script>alert('본인인증이 완료 되었습니다.');opener.location.href='" . "/member/join_wcomp_step1" . "';self.close();</script>";
            } else {
                if ($os_type == "ios") {
                    echo "<script>alert('본인인증이 완료 되었습니다.');location.href='" . "/member/join_comp_toturial" . "';</script>";
                } else if ($os_type == "android") {
                    // 2025-01-17: 안드로이드 앱 전용 처리 - opener 없이 직접 이동 (WebView 보안 정책 문제 해결)
                    echo "<script>alert('본인인증이 완료 되었습니다.');location.href='" . "/member/join_comp_toturial" . "';</script>";
                } else {
                    echo "<script>alert('본인인증이 완료 되었습니다.');opener.location.href='" . "/member/join_comp_toturial" . "';self.close();</script>";
                }
            }
            exit;
        } else {
            # 일반 회원가입 화면 이동
            if (getDeviceType() == "web") {
                echo "<script>alert('본인인증이 완료 되었습니다.');opener.location.href='" . "/member/join_wmem_step1" . "';self.close();</script>";
            } else {
                if ($os_type == "ios") {
                    echo "<script>alert('본인인증이 완료 되었습니다.');location.href='" . "/member/join_mem_toturial" . "';</script>";
                } else if ($os_type == "android") {
                    // 2025-01-17: 안드로이드 앱 전용 처리 - opener 없이 직접 이동 (WebView 보안 정책 문제 해결)
                    echo "<script>alert('본인인증이 완료 되었습니다.');location.href='" . "/member/join_mem_toturial" . "';</script>";
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

        $step_auth = $this->site_model->getStepData($list, "본인인증");
        $step1_info = $this->site_model->getStepData($list, "스텝1");
        $step2_info = $this->site_model->getStepData($list, "스텝2");
        $step3_info = $this->site_model->getStepData($list, "스텝3");
        $step4_info = $this->site_model->getStepData($list, "스텝4");
        $step5_info = $this->site_model->getStepData($list, "스텝5");
        $step6_info = $this->site_model->getStepData($list, "스텝6");

        $arr_mem = [
            "mm_name" => $step_auth["mm_name"],
            "mm_sex" => $step_auth["mm_sex"],
            "mm_nation" => $step_auth["mm_nation"],
            "mm_birth" => $step_auth["mm_birth"],
            "mm_phone" => $step_auth["mm_phone"],
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

            if ($proc_type == "ALL") {
                # 트랜젝션 시작
                $this->db->trans_begin();

                # 데이터 처리
                $arr_mem = [
                    "mm_type" => "MEM",
                    "mm_id" => $step1_info["mm_id"],
                    "mm_pwd" => $this->common->getSqlPass($step1_info["mm_pwd"]),
                    "mm_name" => $step_auth["mm_name"],
                    "mm_status" => "Y",
                    "mm_phone" => $step_auth["mm_phone"],
                    "mm_sex" => $step_auth["mm_sex"],
                    "mm_birth" => $step_auth["mm_birth"],
                    "mm_nation" => $step_auth["mm_nation"],
                    "mm_ci" => $step_auth["mm_ci"],
                    "mm_di" => $step_auth["mm_di"],
                    "mm_thumb" => $step4_4_info["mm_thumb"],
                    "mm_img1" => $step4_4_info["mm_img1"],
                    "mm_img2" => $step4_4_info["mm_img2"],
                    "mm_img3" => $step4_4_info["mm_img3"],
                    "mm_img4" => $step4_4_info["mm_img4"],
                    "mm_img5" => $step4_4_info["mm_img5"],
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
                } else {
                    $arr_mem["mm_join_type"] = "WEB";
                    $arr_mem["mm_join_key"] = "";
                }

                $result_join = $this->member_model->insertMember($arr_mem);

                if ($result_join["code"] != "00") {
                    $this->db->trans_rollback();
                    json_return("91", "[01]회원가입시 오류가 발생하였습니다.");
                }

                # 토큰정보 연동
                if ($alarm["uuid"] != "") {
                    $this->member_model->setMemberDevice(["mm_idx" => $result_join["key"]], ["uuid" => $alarm["uuid"]]);
                }

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

                $result_addr = $this->member_model->insertAddress($arr_addr);
                if ($result_addr["code"] != "00") {
                    $this->db->trans_rollback();
                    json_return("92", "[02]회원가입시 오류가 발생하였습니다.");
                }


                $ma_age = getKoreanAge($step_auth["mm_birth"]);
                $arr_mem_info = [
                    "mm_idx" => $result_join["key"],
                    "ma_work" => arr_implode(",", $step4_1_info["ma_work"]),
                    "ma_price_type" => arr_implode(",", $step4_1_info["ma_price_type"]),
                    "ma_age" => $ma_age,
                    "ma_work_type" => arr_implode(",", $step4_1_info["ma_work_type"]),
                    "ma_week_yn" => $step4_1_info["ma_week_yn"],
                    "ma_week" => arr_implode(",", $step4_1_info["ma_week"]),
                    "ma_edu" => $step4_2_info["ma_edu"],
                    "ma_work_dt" => $step4_2_info["ma_work_dt"],
                    "ma_my_type" => arr_implode(",", $step4_3_info["ma_my_type"]),
                    "ma_desc" => $step4_4_info["ma_desc"],
                ];

                $result_info = $this->member_model->insertMemberInfo($arr_mem_info);

                if ($result_info["code"] != "00") {
                    $this->db->trans_rollback();
                    json_return("93", "[03]회원가입시 오류가 발생하였습니다.");
                }

                if ($step4_3_info["arr_career"] != "") {
                    $arr_temp = explode("&&&", $step4_3_info["arr_career"]);
                    foreach ($arr_temp as $row) {
                        $arr_data = explode("|||", $row);

                        $info = [
                            "mm_idx" => $result_join["key"],
                            "mc_type" => $arr_data[0],
                            "mc_name" => $arr_data[1],
                            "mc_date" => $arr_data[2],
                        ];

                        $this->member_model->insertMemberCareer($info);
                    }
                }

                if ($step4_4_info["arr_certi"] != "") {
                    $arr_temp = explode("&&&", $step4_4_info["arr_certi"]);
                    foreach ($arr_temp as $row) {
                        $arr_data = explode("|||", $row);

                        $info = [
                            "mm_idx" => $result_join["key"],
                            "mc_name" => $arr_data[0],
                            "mc_dt" => $arr_data[1],
                        ];

                        $this->member_model->insertMemberCerti($info);
                    }
                }

                # 포인트 추가
                $pay_point = 5000;
                $insert = [
                    "mm_idx" => $result_join["key"],
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
                $this->member_model->updateMember(["mm_point" => $pay_point], ["mm_idx" => $result_join["key"]]);

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
                return json_return("00", "회원가입 완료");
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

                $arr_mem = [
                    "mm_type" => "MEM",
                    "mm_id" => $step1_info["mm_id"],
                    "mm_pwd" => $this->common->getSqlPass($step1_info["mm_pwd"]),
                    "mm_name" => $step_auth["mm_name"], //"mm_name"=>$step1_info["mm_id"],
                    "mm_status" => "Y",
                    "mm_phone" => $step_auth["mm_phone"], //"mm_phone"=>$step1_info["mm_id"],
                    "mm_sex" => $step_auth["mm_sex"], //"mm_sex"=>"X",
                    "mm_birth" => $step_auth["mm_birth"], // "mm_birth"=>" ",
                    "mm_nation" => $step_auth["mm_nation"], //"mm_nation"=>" ",
                    "mm_ci" => $step1_info["mm_id"],
                    "mm_di" => $step_auth["mm_di"], //"mm_di"=>$step1_info["mm_id"],
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

                    if ($arr_mem["mm_name"] == "") $arr_mem["mm_name"] = $arr_mem["mm_id"];
                    if ($arr_mem["mm_phone"] == "") $arr_mem["mm_phone"] = $arr_mem["mm_id"];
                    if ($arr_mem["mm_di"] == "") $arr_mem["mm_di"] = $arr_mem["mm_id"];
                    if ($arr_mem["mm_sex"] == "") $arr_mem["mm_sex"] = "X";
                    if ($arr_mem["mm_birth"] == "") $arr_mem["mm_birth"] = " ";
                    if ($arr_mem["mm_nation"] == "") $arr_mem["mm_nation"] = " ";

                    if ($arr_mem["mm_name"] == $arr_mem["mm_id"] && $step_sns["username"] != "") $arr_mem["mm_name"] = $step_sns["username"];
                    if ($arr_mem["mm_sex"] == "X" && $step_sns["gender"] != "") $arr_mem["mm_sex"] = $step_sns["gender"];
                    if ($arr_mem["mm_phone"] == $arr_mem["mm_id"] && $step_sns["phonenum"] != "") $arr_mem["mm_phone"] = $step_sns["phonenum"];
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
                } else {

                    // 신규 가입시 주소 없으므로 없도록 처리 25.02.26
                    // $arr_addr = [
                    //       		"mm_idx"=>$result_join["key"],
                    //       		"ad_type"=>"MEMBER",
                    //       		"ad_key"=>$result_join["key"],
                    //       		"ad_zipcode"=>"15052",
                    //       		"ad_jibun"=>"경기도 시흥시 군서마을로 26-2 (정왕동)",
                    //       		"ad_doro"=>"경기도 시흥시 정왕동 1213-16 은진빌딩",
                    //       		"ad_detail"=>"",
                    //       		"ad_addr"=>"경기 시흥시 정왕동",
                    //       		"ad_gps_x"=>"126.7486744464",
                    //       		"ad_gps_y"=>"37.3447914686",
                    //       	];
                    $arr_addr = [
                        "mm_idx" => $result_join["key"],
                        "ad_type" => "MEMBER",
                        "ad_key" => $result_join["key"],
                        "ad_zipcode" => "",
                        "ad_jibun" => "",
                        "ad_doro" => "",
                        "ad_detail" => "",
                        "ad_addr" => "",
                        "ad_gps_x" => "",
                        "ad_gps_y" => "",
                    ];

                    $result_addr = $this->member_model->insertAddress($arr_addr);

                    if ($result_addr["code"] != "00") {
                        $this->db->trans_rollback();
                        json_return("92", "[02]회원가입시 오류가 발생하였습니다.");
                    }
                }

                $ma_age = getKoreanAge($step_auth["mm_birth"]);
                $arr_mem_info = [
                    "mm_idx" => $result_join["key"],
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
                    "mm_idx" => $result_join["key"],
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
                $this->member_model->updateMember(["mm_point" => $pay_point], ["mm_idx" => $result_join["key"]]);

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
                json_return("00", "회원가입 완료");
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

            if ($proc_type == "ALL") {
                # 트랜젝션 시작
                $this->db->trans_begin();

                # 데이터 수집
                $arr_mem = [
                    "mm_type" => "COMP",
                    "mm_status" => "Y",
                    "mm_id" => $step3_info["mm_id"],
                    "mm_img1" => $step6_info["mm_img1"],
                    "mm_thumb" => $step6_info["mm_thumb"],
                    "mm_pwd" => $this->common->getSqlPass($step3_info["mm_pwd"]),
                    "mm_name" => $step_auth["mm_name"],
                    "mm_sex" => $step_auth["mm_sex"],
                    "mm_nation" => $step_auth["mm_nation"],
                    "mm_birth" => $step_auth["mm_birth"],
                    "mm_phone" => $step_auth["mm_phone"],
                    "mm_comp_type" => $step2_info["mm_comp_type"],
                    "mm_comp_name" => $step2_info["mm_comp_name"],
                    "mm_comp_num" => $step2_info["mm_comp_num"],
                    "mm_comp_auth" => $step2_info["mm_comp_auth"],
                    "mm_ci" => $step_auth["mm_ci"],
                    "mm_di" => $step_auth["mm_di"],
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

                /*
                    # 데이터 수집
					$arr_mem = [
                        "mm_type"=>"COMP",
                        "mm_status"=>"Y",
                        "mm_id"=>$step3_info["mm_id"],
                        "mm_img1"=>$step6_info["mm_img1"],
                        "mm_thumb"=>$step6_info["mm_thumb"],
                        "mm_pwd"=>$this->common->getSqlPass($step3_info["mm_pwd"]),
                        "mm_name"=>$step3_info["mm_id"],
                        "mm_sex"=>"X",
                        "mm_nation"=>" ",
                        "mm_birth"=>" ",
                        "mm_phone"=>$step3_info["mm_id"],
                        "mm_comp_type"=>"PERSON",
                        "mm_comp_name"=>" ",
                        "mm_comp_num"=>" ",
                        "mm_comp_auth"=>" ",
                        "mm_ci"=>$step3_info["mm_id"],
                        "mm_di"=>$step3_info["mm_id"],
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

                if (!empty($step_sns)) {
                    $arr_mem["mm_join_type"] = $step_sns["join_type"];
                    $arr_mem["mm_join_key"] = $step_sns["join_key"];
                } else {
                    $arr_mem["mm_join_type"] = "WEB";
                    $arr_mem["mm_join_key"] = "";
                }

                $result_join = $this->member_model->insertMember($arr_mem);

                if ($result_join["code"] != "00") {
                    $this->db->trans_rollback();
                    json_return("91", "[01]회원가입시 오류가 발생하였습니다.");
                }

                # 토큰정보 연동
                if ($alarm["uuid"] != "") {
                    $this->member_model->setMemberDevice(["mm_idx" => $result_join["key"]], ["uuid" => $alarm["uuid"]]);
                }

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

                $result_addr = $this->member_model->insertAddress($arr_addr);
                if ($result_addr["code"] != "00") {
                    $this->db->trans_rollback();
                    json_return("92", "[02]회원가입시 오류가 발생하였습니다.");
                }

                $ma_age = getKoreanAge($step_auth["mm_birth"]);
                $arr_mem_info = [
                    "mm_idx" => $result_join["key"],
                    "ma_work" => arr_implode(",", $step6_info["ma_work"]),
                    "ma_price_type" => arr_implode(",", $step6_info["ma_price_type"]),
                    "ma_age" => $ma_age,
                    "ma_work_type" => arr_implode(",", $step6_info["ma_work_type"]),
                    "ma_week_yn" => $step6_info["ma_week_yn"],
                    "ma_week" => arr_implode(",", $step6_info["ma_week"]),
                    "ma_sage" => $step6_info["ma_sage"],
                    "ma_eage" => $step6_info["ma_eage"],
                    "ma_sex" => arr_implode(",", $step6_info["ma_sex"]),
                    "ma_nation" => arr_implode(",", $step6_info["ma_nation"]),
                    "ma_edu" => $step6_info["ma_edu"],
                    "ma_life_type" => arr_implode(",", $step6_info["ma_life_type"]),
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
                    "mm_idx" => $result_join["key"],
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
                $this->member_model->updateMember(["mm_point" => $pay_point], ["mm_idx" => $result_join["key"]]);


                # 정보조회후 로그인처리
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
                return json_return("00", "회원가입 완료");
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
                $arr_mem = [
                    "mm_type" => "COMP",
                    "mm_status" => "Y",
                    "mm_id" => $step3_info["mm_id"],
                    "mm_pwd" => $this->common->getSqlPass($step3_info["mm_pwd"]),
                    "mm_name" => $step_auth["mm_name"], //$step3_info["mm_id"],
                    "mm_sex" => $step_auth["mm_sex"], //"X",
                    "mm_nation" => $step_auth["mm_nation"], //" ",
                    "mm_birth" => $step_auth["mm_birth"], //" ",
                    "mm_phone" => $step_auth["mm_phone"], //$step3_info["mm_id"],
                    "mm_comp_type" => "COMP",
                    "mm_comp_name" => $step3_info["mm_id"],
                    "mm_comp_num" => "",
                    "mm_comp_auth" => "",
                    "mm_ci" => $step3_info["mm_id"],
                    "mm_di" => $step_auth["mm_di"], //$step3_info["mm_id"],
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

                    if ($arr_mem["mm_name"] == "") $arr_mem["mm_name"] = $arr_mem["mm_id"];
                    if ($arr_mem["mm_phone"] == "") $arr_mem["mm_phone"] = $arr_mem["mm_id"];
                    if ($arr_mem["mm_di"] == "") $arr_mem["mm_di"] = $arr_mem["mm_id"];
                    if ($arr_mem["mm_sex"] == "") $arr_mem["mm_sex"] = "X";
                    if ($arr_mem["mm_birth"] == "") $arr_mem["mm_birth"] = " ";
                    if ($arr_mem["mm_nation"] == "") $arr_mem["mm_nation"] = " ";

                    if ($arr_mem["mm_name"] == $arr_mem["mm_id"] && $step_sns["username"] != "") $arr_mem["mm_name"] = $step_sns["username"];
                    if ($arr_mem["mm_sex"] == "X" && $step_sns["gender"] != "") $arr_mem["mm_sex"] = $step_sns["gender"];
                    if ($arr_mem["mm_phone"] == $arr_mem["mm_id"] && $step_sns["phonenum"] != "") $arr_mem["mm_phone"] = $step_sns["phonenum"];
                } else {
                    $arr_mem["mm_join_type"] = "WEB";
                    $arr_mem["mm_join_key"] = "";
                }

                $result_join = $this->member_model->insertMember($arr_mem);

                if ($result_join["code"] != "00") {
                    $this->db->trans_rollback();
                    return json_return("91", "[01]회원가입시 오류가 발생하였습니다.");
                }

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

                if ($step4_info["ad_gps_x"] != "") {
                } else {
                    $arr_addr = [
                        "mm_idx" => $result_join["key"],
                        "ad_type" => "MEMBER",
                        "ad_key" => $result_join["key"],
                        "ad_zipcode" => "", //"15052",
                        "ad_jibun" => "", //"경기도 시흥시 군서마을로 26-2 (정왕동)",
                        "ad_doro" => "", //"경기도 시흥시 정왕동 1213-16 은진빌딩",
                        "ad_detail" => "",
                        "ad_addr" => "", //"경기 시흥시 정왕동",
                        "ad_gps_x" => "", //"126.7486744464",
                        "ad_gps_y" => "", //"37.3447914686",
                    ];
                }
                $result_addr = $this->member_model->insertAddress($arr_addr);
                if ($result_addr["code"] != "00") {
                    $this->db->trans_rollback();
                    json_return("92", "[02]회원가입시 오류가 발생하였습니다.");
                }


                # 토큰정보 연동
                if ($alarm["uuid"] != "") {
                    $this->member_model->setMemberDevice(["mm_idx" => $result_join["key"]], ["uuid" => $alarm["uuid"]]);
                }

                $ma_age = getKoreanAge($step_auth["mm_birth"]);
                $arr_mem_info = [
                    "mm_idx" => $result_join["key"],
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
                    "mm_idx" => $result_join["key"],
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
                $this->member_model->updateMember(["mm_point" => $pay_point], ["mm_idx" => $result_join["key"]]);

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
                return json_return("00", "회원가입 완료");
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

        $step_auth = $this->site_model->getStepData($list, "본인인증");
        $step1_info = $this->site_model->getStepData($list, "스텝1");
        $step2_info = $this->site_model->getStepData($list, "스텝2");
        $step3_info = $this->site_model->getStepData($list, "스텝3");
        $step4_info = $this->site_model->getStepData($list, "스텝4");
        $step5_info = $this->site_model->getStepData($list, "스텝5");
        $step6_info = $this->site_model->getStepData($list, "스텝6");

        $arr_mem = [
            "mm_name" => $step_auth["mm_name"],
            "mm_sex" => $step_auth["mm_sex"],
            "mm_nation" => $step_auth["mm_nation"],
            "mm_birth" => $step_auth["mm_birth"],
            "mm_phone" => $step_auth["mm_phone"],
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
}
