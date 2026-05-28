<?php
class Chat_proc extends CI_Controller
{
	// MongoDB 연결 설정 (중앙화)
	private $mongo_uri = "mongodb://localhost:27017";
	private $mongo_db = "dev_chat";
	private $environment = "unknown";

	function __construct()
	{
		parent::__construct();

		$this->load->model('sitemodel', 'site_model');
		$this->load->model('membermodel', 'member_model');
		$this->load->model('commonmodel', 'common_model');
		$this->load->model('settingmodel', 'setting_model');

		// 환경에 따라 MongoDB 설정 결정 (도메인 기반)
		$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		$is_dev_domain = strpos($http_host, 'dev.') !== false || strpos($http_host, 'dev_') !== false;

		if ($is_dev_domain) {
			// 개발 환경 (dev.nowjob.co.kr 등)
			$this->environment = 'development';
			$this->mongo_uri = "mongodb://localhost:27017";
			$this->mongo_db = "dev_chat";
		} else {
			// 운영 환경 (nowjob.co.kr 등)
			$this->environment = 'production';
			$this->mongo_uri = "mongodb://localhost:27017";  // 운영 서버 주소로 변경 필요
			$this->mongo_db = "chat";
		}

	}

	// 환경 확인용 함수 (디버깅/모니터링)
	function get_environment_info()
	{
		$info = [
			'environment' => $this->environment,
			'mongo_uri' => $this->mongo_uri,
			'mongo_db' => $this->mongo_db,
			'document_root' => isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : 'NOT SET',
			'http_host' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'NOT SET',
			'defined_environment' => defined('ENVIRONMENT') ? ENVIRONMENT : 'NOT DEFINED',
		];

		// MongoDB 연결 테스트
		try {
			@$mongo = new Mongo($this->mongo_uri);
			$database = $mongo->{$this->mongo_db};
			$info['mongodb_connection'] = 'SUCCESS';
			$info['mongodb_databases'] = $mongo->listDBs();
		} catch (Exception $e) {
			$info['mongodb_connection'] = 'FAILED';
			$info['mongodb_error'] = $e->getMessage();
		}

		json_return("00", "환경 정보 조회 성공", $info);
	}
	//기업 회원의 제안받은 인재
	function get_chat_target_list_recruit()
	{
		//지원자목록-공고리스트
		$data = [];

		//데이터 설정
		$mm_idx = $this->user->getUserIdx();
		$user_id = $this->user->getUserId();
		$user_phone = $this->user->getUserPhone(); // 전화번호도 가져오기 (채팅방 생성 시 mm_phone 사용)
		$result = $this->member_model->getRecruitActaiveList($mm_idx);

		$temp_list = $result; //["list"];

		// 채팅 미리보기 바이트 수 (변경 가능)
		$preview_byte_length = 20;

		// MongoDB 연결하여 마지막 메시지, 시간, 미확인 카운트 조회
		$uri = $this->mongo_uri;
		try {
			@$mongo = new Mongo($uri);
			$database = $mongo->{$this->mongo_db};
			$collection = $database->room;

			$rtn = "";
			foreach ($temp_list as $key => $row) {
				//$temp_list[$key]["ru_resume_img"] = getResumeImg($row);
				//$temp_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
				//$temp_list[$key]["ru_work_type"] = getWorkType($row["ru_work_type"],"span",0);
				//$temp_list[$key]["ru_price_type"] = getPriceType($row["ru_price_type"],0);
				//$temp_list[$key]["ru_my_type"] = getMyType($row["ru_my_type"],1);
				//$temp_list[$key]["ru_week"] = getWeekYoil($row["ru_week"]);
				//$temp_list[$key]["rc_name"] = $row["rc_name"];
				// rc_thumb가 null이거나 빈 값일 경우 getRecruitImg 함수로 대체 이미지 처리
				if (empty($row["rc_thumb"])) {
					$temp_list[$key]["rc_thumb"] = getRecruitImg($row);
				}

				// 회사명 조회 및 추가
				if (!empty($row["mm_idx"])) {
					$company_info = $this->member_model->getMember(["mm_idx" => $row["mm_idx"]]);
					if ($company_info) {
						$temp_list[$key]["mm_comp_name"] = $company_info["mm_comp_name"] ? $company_info["mm_comp_name"] : $company_info["mm_name"];
					} else {
						$temp_list[$key]["mm_comp_name"] = "";
					}
				} else {
					$temp_list[$key]["mm_comp_name"] = "";
				}

				// 채팅방 인덱스 리스트 파싱
				$rm_idx_list = [];
				if (!empty($row["rm_idx_list"])) {
					$rm_idx_str = preg_replace('/[()]/', '', $row["rm_idx_list"]);
					$rm_idx_list = array_filter(array_map('trim', explode(',', $rm_idx_str)));
				}

				// 나간 채팅방을 제외한 rm_idx_list 생성 (room_count 계산용)
				$filtered_rm_idx_list = [];

				// 마지막 메시지, 시간, 미확인 카운트 조회
				$last_msg = "";
				$last_msg_time = "";
				$unread_count = 0;

				if (!empty($rm_idx_list)) {
					// 가장 최근 채팅방의 마지막 메시지 조회
					$latest_rm_idx = 0;
					$latest_time = "";
					foreach ($rm_idx_list as $rm_idx) {
						$rm_idx = (int)trim($rm_idx);
						$query = ['rm_idx' => $rm_idx];
						$room = $collection->findOne($query);

						if ($room) {
							// 채팅방에서 나간 사용자 확인 - rm_users 배열에서 exit_yn='Y'인 사용자는 제외
							$rm_users = isset($room["rm_users"]) ? $room["rm_users"] : [];
							$user_in_room = false;
							$user_exited = false;
							foreach ($rm_users as $room_user) {
								// user_id는 mm_id 또는 mm_phone일 수 있으므로 둘 다 체크
								$room_user_id = isset($room_user["user_id"]) ? $room_user["user_id"] : "";
								if ($room_user_id == $user_id || $room_user_id == $user_phone) {
									// 사용자가 rm_users에 있지만 exit_yn='Y'이면 나간 것으로 간주
									if (isset($room_user["exit_yn"]) && $room_user["exit_yn"] == "Y") {
										$user_exited = true;
									} else {
										$user_in_room = true;
									}
									break;
								}
							}
							// 사용자가 채팅방에 없거나 나간 경우 이 채팅방은 건너뛰기
							if (!$user_in_room || $user_exited) {
								continue;
							}

							// 나가지 않은 채팅방만 filtered_rm_idx_list에 추가
							$filtered_rm_idx_list[] = $rm_idx;

							// 마지막 메시지 조회
							if (isset($room["rm_messages"]) && is_array($room["rm_messages"]) && count($room["rm_messages"]) > 0) {
								$last_message = end($room["rm_messages"]);
								if (isset($last_message["mg_text"])) {
									$last_msg = $last_message["mg_text"];
									// HTML 태그 제거 후 순수 텍스트만 추출
									$last_msg = strip_tags($last_msg);
									// 첫 시작부터 20바이트 제한
									$temp_list[$key]["last_msg_preview"] = mb_substr($last_msg, 0, $preview_byte_length, "UTF-8");
									if (mb_strlen($last_msg, "UTF-8") > $preview_byte_length) {
										$temp_list[$key]["last_msg_preview"] .= "...";
									}
								}
								if (isset($last_message["mg_dt"])) {
									$last_msg_time = $last_message["mg_dt"];
									// formatChatTime 함수에서 직접 MongoDate 객체를 처리하도록 수정
									if (method_exists($this, 'formatChatTime')) {
										$temp_list[$key]["last_msg_time"] = $this->formatChatTime($last_msg_time);
									} else {
										// formatChatTime이 없는 경우에만 변환
										if (is_object($last_msg_time) && method_exists($last_msg_time, 'sec')) {
											$temp_list[$key]["last_msg_time"] = date("Y-m-d H:i:s", $last_msg_time->sec);
										} else {
											$temp_list[$key]["last_msg_time"] = $last_msg_time;
										}
									}

									if (empty($latest_time) || $last_msg_time > $latest_time) {
										$latest_time = $last_msg_time;
										$latest_rm_idx = $rm_idx;
									}
								}
							}

							// 미확인 메시지 카운트
							if (isset($room["rm_messages"]) && is_array($room["rm_messages"])) {
								foreach ($room["rm_messages"] as $message) {
									if (isset($message["mg_reads"]) && is_array($message["mg_reads"])) {
										foreach ($message["mg_reads"] as $read) {
											if (
												isset($read["user_id"]) && $read["user_id"] == $user_id &&
												isset($read["read_yn"]) && $read["read_yn"] == "N"
											) {
												$unread_count++;
											}
										}
									}
								}
							}
						}
					}

					// 가장 최근 채팅방 기준으로 시간 설정
					if ($latest_rm_idx > 0) {
						$query = ['rm_idx' => $latest_rm_idx];
						$latest_room = $collection->findOne($query);
						if ($latest_room && isset($latest_room["rm_messages"]) && is_array($latest_room["rm_messages"]) && count($latest_room["rm_messages"]) > 0) {
							$latest_msg = end($latest_room["rm_messages"]);
							if (isset($latest_msg["mg_dt"])) {
								$latest_time = $latest_msg["mg_dt"];
								// formatChatTime 함수에서 직접 MongoDate 객체를 처리하도록 수정
								if (method_exists($this, 'formatChatTime')) {
									$temp_list[$key]["last_msg_time"] = $this->formatChatTime($latest_time);
								} else {
									// formatChatTime이 없는 경우에만 변환
									if (is_object($latest_time) && method_exists($latest_time, 'sec')) {
										$temp_list[$key]["last_msg_time"] = date("Y-m-d H:i:s", $latest_time->sec);
									} else {
										$temp_list[$key]["last_msg_time"] = $latest_time;
									}
								}
								if (isset($latest_msg["mg_text"])) {
									$last_msg = $latest_msg["mg_text"];
									// HTML 태그 제거 후 순수 텍스트만 추출
									$last_msg = strip_tags($last_msg);
									// 첫 시작부터 20바이트 제한
									$temp_list[$key]["last_msg_preview"] = mb_substr($last_msg, 0, $preview_byte_length, "UTF-8");
									if (mb_strlen($last_msg, "UTF-8") > $preview_byte_length) {
										$temp_list[$key]["last_msg_preview"] .= "...";
									}
								}
							}
						}
					}
				}

				// 채팅방이 없는 공고는 제외하여 공고 등록 직후 노출 방지
				// 단, 제안받은 인재가 있는 경우(reqcnt > 0)에는 채팅방이 없어도 표시
				if (empty($filtered_rm_idx_list)) {
					// 제안받은 인재가 있는지 확인 (개인이 지원한 경우 포함)
					$reqcnt = isset($row["reqcnt"]) ? (int)$row["reqcnt"] : 0;
					if ($reqcnt == 0) {
						unset($temp_list[$key]);
						continue;
					}
				}

				// 필터링된 rm_idx_list를 문자열로 변환하여 반환 (room_count 계산용)
				if (!empty($filtered_rm_idx_list)) {
					$temp_list[$key]["rm_idx_list"] = "(" . implode(",", $filtered_rm_idx_list) . ")";
				} else {
					// 필터링된 리스트가 비어있으면 원본 rm_idx_list 사용 (모델에서 반환된 값)
					// MongoDB 연결 실패나 모든 채팅방에서 나간 경우에도 원본 값 사용
					if (!empty($row["rm_idx_list"]) && $row["rm_idx_list"] !== "()") {
						$temp_list[$key]["rm_idx_list"] = $row["rm_idx_list"];
					} else {
						$temp_list[$key]["rm_idx_list"] = "()";
					}
				}

				// 기본값 설정
				if (empty($temp_list[$key]["last_msg_preview"])) {
					$temp_list[$key]["last_msg_preview"] = "";
				}
				if (empty($temp_list[$key]["last_msg_time"])) {
					$temp_list[$key]["last_msg_time"] = "";
				}
				$temp_list[$key]["unread_count"] = $unread_count;

				$rtn .= "1";
			}
		} catch (Exception $e) {
			// MongoDB 연결 실패 시 기본값 설정
			$rtn = "";
			foreach ($temp_list as $key => $row) {
				// rc_thumb가 null이거나 빈 값일 경우 getRecruitImg 함수로 대체 이미지 처리
				if (empty($row["rc_thumb"])) {
					$temp_list[$key]["rc_thumb"] = getRecruitImg($row);
				}

				// 회사명 조회 및 추가
				if (!empty($row["mm_idx"])) {
					$company_info = $this->member_model->getMember(["mm_idx" => $row["mm_idx"]]);
					if ($company_info) {
						$temp_list[$key]["mm_comp_name"] = $company_info["mm_comp_name"] ? $company_info["mm_comp_name"] : $company_info["mm_name"];
					} else {
						$temp_list[$key]["mm_comp_name"] = "";
					}
				} else {
					$temp_list[$key]["mm_comp_name"] = "";
				}
				
				// rm_idx_list 설정 (모델에서 반환된 원본 값 사용)
				if (empty($temp_list[$key]["rm_idx_list"])) {
					$temp_list[$key]["rm_idx_list"] = isset($row["rm_idx_list"]) ? $row["rm_idx_list"] : "()";
				}
				
				$temp_list[$key]["last_msg_preview"] = "";
				$temp_list[$key]["last_msg_time"] = "";
				$temp_list[$key]["unread_count"] = 0;
				$rtn .= "1";
			}
		}

		$list = $temp_list;

		json_return("00", "조회성공" . $rtn, ["list" => $list]);
	}
	// 기업회원 제안보낸 인재 탭의 기업이 등록한 공고 목록
	function get_chat_target_list_recruit2()
	{
		//지원자목록-공고리스트
		$data = [];

		//데이터 설정
		$mm_idx = $this->user->getUserIdx();
		$user_id = $this->user->getUserId();
		$user_phone = $this->user->getUserPhone(); // 전화번호도 가져오기 (채팅방 생성 시 mm_phone 사용)

		$result = $this->member_model->getRecruitActaiveList2($mm_idx);

		$temp_list = $result; //["list"];

		// 채팅 미리보기 바이트 수 (변경 가능)
		$preview_byte_length = 20;

		// MongoDB 연결하여 마지막 메시지, 시간, 미확인 카운트 조회
		$uri = $this->mongo_uri;
		try {
			@$mongo = new Mongo($uri);
			$database = $mongo->{$this->mongo_db};
			$collection = $database->room;

			$rtn = "";
			foreach ($temp_list as $key => $row) {
				//$temp_list[$key]["ru_resume_img"] = getResumeImg($row);
				//$temp_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
				//$temp_list[$key]["ru_work_type"] = getWorkType($row["ru_work_type"],"span",0);
				//$temp_list[$key]["ru_price_type"] = getPriceType($row["ru_price_type"],0);
				//$temp_list[$key]["ru_my_type"] = getMyType($row["ru_my_type"],1);
				//$temp_list[$key]["ru_week"] = getWeekYoil($row["ru_week"]);
				//$temp_list[$key]["rc_name"] = $row["rc_name"];
				// rc_thumb가 null이거나 빈 값일 경우 getRecruitImg 함수로 대체 이미지 처리
				if (empty($row["rc_thumb"])) {
					$temp_list[$key]["rc_thumb"] = getRecruitImg($row);
				}

				// 회사명 조회 및 추가
				if (!empty($row["mm_idx"])) {
					$company_info = $this->member_model->getMember(["mm_idx" => $row["mm_idx"]]);
					if ($company_info) {
						$temp_list[$key]["mm_comp_name"] = $company_info["mm_comp_name"] ? $company_info["mm_comp_name"] : $company_info["mm_name"];
					} else {
						$temp_list[$key]["mm_comp_name"] = "";
					}
				} else {
					$temp_list[$key]["mm_comp_name"] = "";
				}

				// 채팅방 인덱스 리스트 파싱
				$rm_idx_list = [];
				if (!empty($row["rm_idx_list"])) {
					$rm_idx_str = preg_replace('/[()]/', '', $row["rm_idx_list"]);
					$rm_idx_list = array_filter(array_map('trim', explode(',', $rm_idx_str)));
				}

				// 나간 채팅방을 제외한 rm_idx_list 생성 (room_count 계산용)
				$filtered_rm_idx_list = [];

				// 마지막 메시지, 시간, 미확인 카운트 조회
				$last_msg = "";
				$last_msg_time = "";
				$unread_count = 0;

				if (!empty($rm_idx_list)) {
					// 가장 최근 채팅방의 마지막 메시지 조회
					$latest_rm_idx = 0;
					$latest_time = "";
					foreach ($rm_idx_list as $rm_idx) {
						$rm_idx = (int)trim($rm_idx);
						$query = ['rm_idx' => $rm_idx];
						$room = $collection->findOne($query);

						if ($room) {
							// 채팅방에서 나간 사용자 확인 - rm_users 배열에서 exit_yn='Y'인 사용자는 제외
							$rm_users = isset($room["rm_users"]) ? $room["rm_users"] : [];
							$user_in_room = false;
							$user_exited = false;
							foreach ($rm_users as $room_user) {
								// user_id는 mm_id 또는 mm_phone일 수 있으므로 둘 다 체크
								$room_user_id = isset($room_user["user_id"]) ? $room_user["user_id"] : "";
								if ($room_user_id == $user_id || $room_user_id == $user_phone) {
									// 사용자가 rm_users에 있지만 exit_yn='Y'이면 나간 것으로 간주
									if (isset($room_user["exit_yn"]) && $room_user["exit_yn"] == "Y") {
										$user_exited = true;
									} else {
										$user_in_room = true;
									}
									break;
								}
							}
							// 사용자가 채팅방에 없거나 나간 경우 이 채팅방은 건너뛰기
							if (!$user_in_room || $user_exited) {
								continue;
							}

							// 나가지 않은 채팅방만 filtered_rm_idx_list에 추가
							$filtered_rm_idx_list[] = $rm_idx;

							// 마지막 메시지 조회
							if (isset($room["rm_messages"]) && is_array($room["rm_messages"]) && count($room["rm_messages"]) > 0) {
								$last_message = end($room["rm_messages"]);
								if (isset($last_message["mg_text"])) {
									$last_msg = $last_message["mg_text"];
									// HTML 태그 제거 후 순수 텍스트만 추출
									$last_msg = strip_tags($last_msg);
									// 첫 시작부터 20바이트 제한
									$temp_list[$key]["last_msg_preview"] = mb_substr($last_msg, 0, $preview_byte_length, "UTF-8");
									if (mb_strlen($last_msg, "UTF-8") > $preview_byte_length) {
										$temp_list[$key]["last_msg_preview"] .= "...";
									}
								}
								if (isset($last_message["mg_dt"])) {
									$last_msg_time = $last_message["mg_dt"];
									// formatChatTime 함수에서 직접 MongoDate 객체를 처리하도록 수정
									if (method_exists($this, 'formatChatTime')) {
										$temp_list[$key]["last_msg_time"] = $this->formatChatTime($last_msg_time);
									} else {
										// formatChatTime이 없는 경우에만 변환
										if (is_object($last_msg_time) && method_exists($last_msg_time, 'sec')) {
											$temp_list[$key]["last_msg_time"] = date("Y-m-d H:i:s", $last_msg_time->sec);
										} else {
											$temp_list[$key]["last_msg_time"] = $last_msg_time;
										}
									}

									if (empty($latest_time) || $last_msg_time > $latest_time) {
										$latest_time = $last_msg_time;
										$latest_rm_idx = $rm_idx;
									}
								}
							}

							// 미확인 메시지 카운트
							if (isset($room["rm_messages"]) && is_array($room["rm_messages"])) {
								foreach ($room["rm_messages"] as $message) {
									if (isset($message["mg_reads"]) && is_array($message["mg_reads"])) {
										foreach ($message["mg_reads"] as $read) {
											if (
												isset($read["user_id"]) && $read["user_id"] == $user_id &&
												isset($read["read_yn"]) && $read["read_yn"] == "N"
											) {
												$unread_count++;
											}
										}
									}
								}
							}
						}
					}

					// 가장 최근 채팅방 기준으로 시간 설정
					if ($latest_rm_idx > 0) {
						$query = ['rm_idx' => $latest_rm_idx];
						$latest_room = $collection->findOne($query);
						if ($latest_room && isset($latest_room["rm_messages"]) && is_array($latest_room["rm_messages"]) && count($latest_room["rm_messages"]) > 0) {
							$latest_msg = end($latest_room["rm_messages"]);
							if (isset($latest_msg["mg_dt"])) {
								$latest_time = $latest_msg["mg_dt"];
								// formatChatTime 함수에서 직접 MongoDate 객체를 처리하도록 수정
								if (method_exists($this, 'formatChatTime')) {
									$temp_list[$key]["last_msg_time"] = $this->formatChatTime($latest_time);
								} else {
									// formatChatTime이 없는 경우에만 변환
									if (is_object($latest_time) && method_exists($latest_time, 'sec')) {
										$temp_list[$key]["last_msg_time"] = date("Y-m-d H:i:s", $latest_time->sec);
									} else {
										$temp_list[$key]["last_msg_time"] = $latest_time;
									}
								}
								if (isset($latest_msg["mg_text"])) {
									$last_msg = $latest_msg["mg_text"];
									// HTML 태그 제거 후 순수 텍스트만 추출
									$last_msg = strip_tags($last_msg);
									// 첫 시작부터 20바이트 제한
									$temp_list[$key]["last_msg_preview"] = mb_substr($last_msg, 0, $preview_byte_length, "UTF-8");
									if (mb_strlen($last_msg, "UTF-8") > $preview_byte_length) {
										$temp_list[$key]["last_msg_preview"] .= "...";
									}
								}
							}
						}
					}
				}
				// 필터링된 rm_idx_list를 문자열로 변환하여 반환 (room_count 계산용)
				if (!empty($filtered_rm_idx_list)) {
					$temp_list[$key]["rm_idx_list"] = "(" . implode(",", $filtered_rm_idx_list) . ")";
				} else {
					$temp_list[$key]["rm_idx_list"] = "()";
				}
				// 기본값 설정
				if (empty($temp_list[$key]["last_msg_preview"])) {
					$temp_list[$key]["last_msg_preview"] = "";
				}
				if (empty($temp_list[$key]["last_msg_time"])) {
					$temp_list[$key]["last_msg_time"] = "";
				}
				$temp_list[$key]["unread_count"] = $unread_count;

				$rtn .= "1";
			}
		} catch (Exception $e) {
			// MongoDB 연결 실패 시 기본값 설정
			$rtn = "";
			foreach ($temp_list as $key => $row) {
				// 회사명 조회 및 추가
				if (!empty($row["mm_idx"])) {
					$company_info = $this->member_model->getMember(["mm_idx" => $row["mm_idx"]]);
					if ($company_info) {
						$temp_list[$key]["mm_comp_name"] = $company_info["mm_comp_name"] ? $company_info["mm_comp_name"] : $company_info["mm_name"];
					} else {
						$temp_list[$key]["mm_comp_name"] = "";
					}
				} else {
					$temp_list[$key]["mm_comp_name"] = "";
				}
				$temp_list[$key]["last_msg_preview"] = "";
				$temp_list[$key]["last_msg_time"] = "";
				$temp_list[$key]["unread_count"] = 0;
				$rtn .= "1";
			}
		}

		$list = $temp_list;

		json_return("00", "조회성공" . $rtn, ["list" => $list]);
	}

	function get_chat_target_list_recruit_mem()
	{
		//지원한-공고리스트
		$data = [];

		//데이터 설정
		$mm_idx = $this->user->getUserIdx();
		$user_id = $this->user->getUserId();
		$user_phone = $this->user->getUserPhone(); // 전화번호도 가져오기 (채팅방 생성 시 mm_phone 사용)
		$result = $this->member_model->getRecruitActaiveListMem($mm_idx);

		$temp_list = $result;
		$preview_byte_length = 20;

		// MongoDB 연결하여 마지막 메시지 조회
		$uri = $this->mongo_uri;
		try {
			@$mongo = new Mongo($uri);
			$database = $mongo->{$this->mongo_db};
			$collection = $database->room;

			$rtn = "";
			foreach ($temp_list as $key => $row) {
				// rc_thumb가 null이거나 빈 값일 경우 getRecruitImg 함수로 대체 이미지 처리
				if (empty($row["rc_thumb"])) {
					$temp_list[$key]["rc_thumb"] = getRecruitImg($row);
				}

				// 회사명 조회 및 추가 (공고를 등록한 기업의 정보)
				if (!empty($row["mm_idx"])) {
					$company_info = $this->member_model->getMember(["mm_idx" => $row["mm_idx"]]);
					if ($company_info) {
						// 탈퇴한 기업 회원의 채팅방은 목록에서 제외
						if ($company_info["mm_status"] != 'Y') {
							unset($temp_list[$key]);
							continue;
						}
						$temp_list[$key]["user_name"] = $company_info["mm_comp_name"] ? $company_info["mm_comp_name"] : $company_info["mm_name"];
						$temp_list[$key]["mm_comp_name"] = $company_info["mm_comp_name"] ? $company_info["mm_comp_name"] : $company_info["mm_name"];
					} else {
						// 회원 정보 없으면 탈퇴로 간주하여 제외
						unset($temp_list[$key]);
						continue;
					}
				} else {
					$temp_list[$key]["user_name"] = "";
					$temp_list[$key]["mm_comp_name"] = "";
				}

				// 채팅방 인덱스 조회 (단일 rm_idx 사용)
				$rm_idx = isset($row["rm_idx"]) ? (int)$row["rm_idx"] : 0;

				// 마지막 메시지, 시간 조회
				if ($rm_idx > 0) {
					$query = ['rm_idx' => $rm_idx];
					$room = $collection->findOne($query);

					if ($room) {
						// 채팅방에서 나간 사용자 확인 - rm_users 배열에서 exit_yn='Y'인 사용자는 제외
						$rm_users = isset($room["rm_users"]) ? $room["rm_users"] : [];
						$user_in_room = false;
						$user_exited = false;
						foreach ($rm_users as $room_user) {
							// user_id는 mm_id 또는 mm_phone일 수 있으므로 둘 다 체크
							$room_user_id = isset($room_user["user_id"]) ? $room_user["user_id"] : "";
							if ($room_user_id == $user_id || $room_user_id == $user_phone) {
								// 사용자가 rm_users에 있지만 exit_yn='Y'이면 나간 것으로 간주
								if (isset($room_user["exit_yn"]) && $room_user["exit_yn"] == "Y") {
									$user_exited = true;
								} else {
									$user_in_room = true;
								}
								break;
							}
						}

						// 나간 채팅방은 목록에서 제외
						if ($user_exited) {
							unset($temp_list[$key]);
							continue;
						}

						if ($user_in_room && !$user_exited && isset($room["rm_messages"]) && is_array($room["rm_messages"]) && count($room["rm_messages"]) > 0) {
							$last_message = end($room["rm_messages"]);
							if (isset($last_message["mg_text"])) {
								$last_msg = $last_message["mg_text"];
								// HTML 태그 제거 후 순수 텍스트만 추출
								$last_msg = strip_tags($last_msg);
								// 첫 시작부터 20바이트 제한
								$temp_list[$key]["last_msg_preview"] = mb_substr($last_msg, 0, $preview_byte_length, "UTF-8");
								if (mb_strlen($last_msg, "UTF-8") > $preview_byte_length) {
									$temp_list[$key]["last_msg_preview"] .= "...";
								}
							}
							if (isset($last_message["mg_dt"])) {
								$last_msg_time = $last_message["mg_dt"];
								// formatChatTime 함수에서 직접 MongoDate 객체를 처리하도록 수정
								if (method_exists($this, 'formatChatTime')) {
									$temp_list[$key]["last_msg_time"] = $this->formatChatTime($last_msg_time);
								} else {
									// formatChatTime이 없는 경우에만 변환
									if (is_object($last_msg_time) && method_exists($last_msg_time, 'sec')) {
										$temp_list[$key]["last_msg_time"] = date("Y-m-d H:i:s", $last_msg_time->sec);
									} else {
										$temp_list[$key]["last_msg_time"] = $last_msg_time;
									}
								}
							}
						}
					}
				}

				// 기본값 설정
				if (empty($temp_list[$key]["last_msg_preview"])) {
					$temp_list[$key]["last_msg_preview"] = "";
				}
				if (empty($temp_list[$key]["last_msg_time"])) {
					$temp_list[$key]["last_msg_time"] = "";
				}

				$rtn .= "1";
			}

			// unset된 항목 제거 후 배열 재정렬
			$temp_list = array_values($temp_list);
		} catch (Exception $e) {
			// MongoDB 연결 실패 시 기본값 설정
			$rtn = "";
			foreach ($temp_list as $key => $row) {
				// 회사명 조회 및 추가 (MongoDB 실패 시에도 회사명은 조회)
				if (!empty($row["mm_idx"])) {
					$company_info = $this->member_model->getMember(["mm_idx" => $row["mm_idx"]]);
					if ($company_info) {
						$temp_list[$key]["user_name"] = $company_info["mm_comp_name"] ? $company_info["mm_comp_name"] : $company_info["mm_name"];
						$temp_list[$key]["mm_comp_name"] = $company_info["mm_comp_name"] ? $company_info["mm_comp_name"] : $company_info["mm_name"];
					} else {
						$temp_list[$key]["user_name"] = "";
						$temp_list[$key]["mm_comp_name"] = "";
					}
				} else {
					$temp_list[$key]["user_name"] = "";
					$temp_list[$key]["mm_comp_name"] = "";
				}
				if (empty($temp_list[$key]["last_msg_preview"])) {
					$temp_list[$key]["last_msg_preview"] = "";
				}
				if (empty($temp_list[$key]["last_msg_time"])) {
					$temp_list[$key]["last_msg_time"] = "";
				}
				$rtn .= "1";
			}
		}

		$list = $temp_list;

		json_return("00", "조회성공" . $rtn, ["list" => $list]);
	}

	function get_chat_target_list()
	{
		//지원자 리스트 org

		$data = [];
		$ru_idx = input_post("ru_idx", "");
		$chatgbn = input_post("chatgbn", "");

		//데이터 설정
		$mm_idx = $this->user->getUserIdx();
		$user_id = $this->user->getUserId();
		$user_phone = $this->user->getUserPhone(); // 전화번호도 가져오기 (채팅방 생성 시 mm_phone 사용)
		$params = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $mm_idx,
			"ru_idx" => $ru_idx,
		];

		if ($chatgbn == "chatPanel1") {
			$result = $this->member_model->getRequestRecruitList($params);
		} else if ($chatgbn == "chatPanel2") {
			$result = $this->member_model->getRequestRecruitListSended($params);
		}

		$temp_list = $result["list"];
		$preview_byte_length = 20;

		// MongoDB 연결하여 나간 채팅방 필터링
		$uri = $this->mongo_uri;
		$filtered_list = [];

		try {
			@$mongo = new Mongo($uri);
			$database = $mongo->{$this->mongo_db};
			$collection = $database->room;

			foreach ($temp_list as $key => $row) {
				$user_in_room = true; // 기본값

				// 채팅방이 있는 경우만 MongoDB에서 나간 사용자인지 확인
				if (isset($row["rm_idx"]) && !empty($row["rm_idx"])) {
					$rm_idx = (int)$row["rm_idx"];
					$query = ['rm_idx' => $rm_idx];
					$room = $collection->findOne($query);

					if ($room) {
						$user_in_room = false;
						$user_exited = false;
						// rm_users 배열에서 현재 사용자가 있는지 확인 (exit_yn='Y' 체크 포함)
						$rm_users = isset($room["rm_users"]) ? $room["rm_users"] : [];

						foreach ($rm_users as $room_user) {
							// user_id는 mm_id 또는 mm_phone일 수 있으므로 둘 다 체크
							$room_user_id = isset($room_user["user_id"]) ? $room_user["user_id"] : "";
							if ($room_user_id == $user_id || $room_user_id == $user_phone) {
								// 사용자가 rm_users에 있지만 exit_yn='Y'이면 나간 것으로 간주
								if (isset($room_user["exit_yn"]) && $room_user["exit_yn"] == "Y") {
									$user_exited = true;
								} else {
									$user_in_room = true;
								}
								break;
							}
						}

						// 나간 채팅방은 목록에서 제외
						if ($user_exited) {
							continue;
						}

						// 마지막 메시지 조회 (나가지 않은 사용자만)
						if ($user_in_room && !$user_exited && isset($room["rm_messages"]) && is_array($room["rm_messages"]) && count($room["rm_messages"]) > 0) {
							$last_message = end($room["rm_messages"]);
							if (isset($last_message["mg_text"])) {
								$last_msg = $last_message["mg_text"];
								// HTML 태그 제거 후 순수 텍스트만 추출
								$last_msg = strip_tags($last_msg);
								// 첫 시작부터 20바이트 제한
								$temp_list[$key]["last_msg_preview"] = mb_substr($last_msg, 0, $preview_byte_length, "UTF-8");
								if (mb_strlen($last_msg, "UTF-8") > $preview_byte_length) {
									$temp_list[$key]["last_msg_preview"] .= "...";
								}
							}
							if (isset($last_message["mg_dt"])) {
								$last_msg_time = $last_message["mg_dt"];
								// formatChatTime 함수에서 직접 MongoDate 객체를 처리하도록 수정
								if (method_exists($this, 'formatChatTime')) {
									$temp_list[$key]["last_msg_time"] = $this->formatChatTime($last_msg_time);
								} else {
									// formatChatTime이 없는 경우에만 변환
									if (is_object($last_msg_time) && method_exists($last_msg_time, 'sec')) {
										$temp_list[$key]["last_msg_time"] = date("Y-m-d H:i:s", $last_msg_time->sec);
									} else {
										$temp_list[$key]["last_msg_time"] = $last_msg_time;
									}
								}
							}
						}
					}
				}

				// 표시용 상태 저장
				$temp_list[$key]["user_in_room"] = $user_in_room ? "Y" : "N";

				// 데이터 스펙 채우기
				$temp_list[$key]["ru_resume_img"] = getResumeImg($row);
				$temp_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
				$temp_list[$key]["ru_work_type"] = getWorkType($row["ru_work_type"], "span", 0);
				$temp_list[$key]["ru_price_type"] = getPriceType($row["ru_price_type"], 0);
				$temp_list[$key]["ru_my_type"] = getMyType($row["ru_my_type"], 1);
				$temp_list[$key]["ru_week"] = getWeekYoil($row["ru_week"]);
				// 인재정보 필드 명시적으로 전달 (인재정보 표시용)
				if (isset($row["ru_name"])) {
					$temp_list[$key]["ru_name"] = $row["ru_name"];
				}
				if (isset($row["ru_idx"])) {
					$temp_list[$key]["ru_idx"] = $row["ru_idx"];
				}
				if (isset($row["mm_name"])) {
					$temp_list[$key]["mm_name"] = $row["mm_name"];
				}
				if (isset($row["mm_nation"])) {
					$temp_list[$key]["mm_nation"] = $row["mm_nation"];
				}
				if (isset($row["ma_age"])) {
					$temp_list[$key]["ma_age"] = $row["ma_age"];
				}
				if (isset($row["ad_addr"])) {
					$temp_list[$key]["ad_addr"] = $row["ad_addr"];
				}
				if (isset($row["rc_idx"])) {
					$temp_list[$key]["rc_idx"] = $row["rc_idx"];
				}
				if (isset($row["rc_name"])) {
					$temp_list[$key]["rc_name"] = $row["rc_name"];
				}
				if (isset($row["target_id"])) {
					$temp_list[$key]["target_id"] = $row["target_id"];
				}
				if (isset($row["user_id"])) {
					$temp_list[$key]["user_id"] = $row["user_id"];
				}
				if (isset($row["target_name"])) {
					$temp_list[$key]["target_name"] = $row["target_name"];
				}
				if (isset($row["user_name"])) {
					$temp_list[$key]["user_name"] = $row["user_name"];
				}
				if (isset($row["up_dt"])) {
					$temp_list[$key]["up_dt"] = $row["up_dt"];
				}
				// mm_sex 정보 명시적으로 전달 (JavaScript에서 기본 아바타 결정에 사용)
				if (isset($row["mm_sex"])) {
					$temp_list[$key]["mm_sex"] = $row["mm_sex"];
				}
				// rc_thumb가 null/빈 값이면 대체 이미지 적용
				if (empty($row["rc_thumb"])) {
					$temp_list[$key]["rc_thumb"] = getRecruitImg($row);
				}

				// 기본값 설정
				if (empty($temp_list[$key]["last_msg_preview"])) {
					$temp_list[$key]["last_msg_preview"] = "";
				}
				if (empty($temp_list[$key]["last_msg_time"])) {
					$temp_list[$key]["last_msg_time"] = "";
				}

				$filtered_list[] = $temp_list[$key];
			}
		} catch (Exception $e) {
			// error_log("[GET_CHAT_TARGET_LIST] MongoDB 오류 - error: " . $e->getMessage());
			// MongoDB 오류 시 원본 리스트 사용
			foreach ($temp_list as $key => $row) {
				$temp_list[$key]["user_in_room"] = "Y";
				$temp_list[$key]["ru_resume_img"] = getResumeImg($row);
				$temp_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
				$temp_list[$key]["ru_work_type"] = getWorkType($row["ru_work_type"], "span", 0);
				$temp_list[$key]["ru_price_type"] = getPriceType($row["ru_price_type"], 0);
				$temp_list[$key]["ru_my_type"] = getMyType($row["ru_my_type"], 1);
				$temp_list[$key]["ru_week"] = getWeekYoil($row["ru_week"]);
				// 인재정보 필드 명시적으로 전달 (인재정보 표시용)
				if (isset($row["ru_name"])) {
					$temp_list[$key]["ru_name"] = $row["ru_name"];
				}
				if (isset($row["ru_idx"])) {
					$temp_list[$key]["ru_idx"] = $row["ru_idx"];
				}
				if (isset($row["mm_name"])) {
					$temp_list[$key]["mm_name"] = $row["mm_name"];
				}
				if (isset($row["mm_nation"])) {
					$temp_list[$key]["mm_nation"] = $row["mm_nation"];
				}
				if (isset($row["ma_age"])) {
					$temp_list[$key]["ma_age"] = $row["ma_age"];
				}
				if (isset($row["ad_addr"])) {
					$temp_list[$key]["ad_addr"] = $row["ad_addr"];
				}
				if (isset($row["rc_idx"])) {
					$temp_list[$key]["rc_idx"] = $row["rc_idx"];
				}
				if (isset($row["rc_name"])) {
					$temp_list[$key]["rc_name"] = $row["rc_name"];
				}
				if (isset($row["target_id"])) {
					$temp_list[$key]["target_id"] = $row["target_id"];
				}
				if (isset($row["user_id"])) {
					$temp_list[$key]["user_id"] = $row["user_id"];
				}
				if (isset($row["target_name"])) {
					$temp_list[$key]["target_name"] = $row["target_name"];
				}
				if (isset($row["user_name"])) {
					$temp_list[$key]["user_name"] = $row["user_name"];
				}
				if (isset($row["up_dt"])) {
					$temp_list[$key]["up_dt"] = $row["up_dt"];
				}
				// mm_sex 정보 명시적으로 전달 (JavaScript에서 기본 아바타 결정에 사용)
				if (isset($row["mm_sex"])) {
					$temp_list[$key]["mm_sex"] = $row["mm_sex"];
				}
				// rc_thumb가 null/빈 값이면 대체 이미지 적용
				if (empty($row["rc_thumb"])) {
					$temp_list[$key]["rc_thumb"] = getRecruitImg($row);
				}
				// 기본값 설정
				if (empty($temp_list[$key]["last_msg_preview"])) {
					$temp_list[$key]["last_msg_preview"] = "";
				}
				if (empty($temp_list[$key]["last_msg_time"])) {
					$temp_list[$key]["last_msg_time"] = "";
				}
			}
			$filtered_list = $temp_list;
		}

		$list = $filtered_list;

		json_return("00", "조회성공", ["list" => $list]);
	}

	//채팅 - 제안보낸인재 리스트
	function get_chat_target_list2()
	{
		//제안보낸 인재

		$data = [];
		$ru_idx = input_post("ru_idx", "");

		//데이터 설정
		$mm_idx = $this->user->getUserIdx();
		$user_id = $this->user->getUserId();
		$user_phone = $this->user->getUserPhone(); // 전화번호도 가져오기 (채팅방 생성 시 mm_phone 사용)
		$params = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $mm_idx,
			"ru_idx" => $ru_idx,
		];
		$result = $this->member_model->getRequestRecruitList2($params);

		$temp_list = $result["list"];
		$preview_byte_length = 20;

		// MongoDB 연결하여 마지막 메시지 조회
		$uri = $this->mongo_uri;
		try {
			@$mongo = new Mongo($uri);
			$database = $mongo->{$this->mongo_db};
			$collection = $database->room;

			foreach ($temp_list as $key => $row) {
				$temp_list[$key]["ru_resume_img"] = getResumeImg($row);
				$temp_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
				$temp_list[$key]["ru_work_type"] = getWorkType($row["ru_work_type"], "span", 0);
				$temp_list[$key]["ru_price_type"] = getPriceType($row["ru_price_type"], 0);
				$temp_list[$key]["ru_my_type"] = getMyType($row["ru_my_type"], 1);
				$temp_list[$key]["ru_week"] = getWeekYoil($row["ru_week"]);
				// rc_thumb가 null/빈 값이면 대체 이미지 적용
				if (empty($row["rc_thumb"])) {
					$temp_list[$key]["rc_thumb"] = getRecruitImg($row);
				}

				// 마지막 메시지 조회
				$rm_idx = isset($row["rm_idx"]) ? (int)$row["rm_idx"] : 0;
				if ($rm_idx > 0) {
					$query = ['rm_idx' => $rm_idx];
					$room = $collection->findOne($query);

					if ($room) {
						// 채팅방에서 나간 사용자 확인 - rm_users 배열에서 exit_yn='Y'인 사용자는 제외
						$rm_users = isset($room["rm_users"]) ? $room["rm_users"] : [];
						$user_in_room = false;
						$user_exited = false;
						foreach ($rm_users as $room_user) {
							// user_id는 mm_id 또는 mm_phone일 수 있으므로 둘 다 체크
							$room_user_id = isset($room_user["user_id"]) ? $room_user["user_id"] : "";
							if ($room_user_id == $user_id || $room_user_id == $user_phone) {
								// 사용자가 rm_users에 있지만 exit_yn='Y'이면 나간 것으로 간주
								if (isset($room_user["exit_yn"]) && $room_user["exit_yn"] == "Y") {
									$user_exited = true;
								} else {
									$user_in_room = true;
								}
								break;
							}
						}

						// 나간 채팅방은 목록에서 제외
						if ($user_exited) {
							unset($temp_list[$key]);
							continue;
						}

						if ($user_in_room && !$user_exited && isset($room["rm_messages"]) && is_array($room["rm_messages"]) && count($room["rm_messages"]) > 0) {
							$last_message = end($room["rm_messages"]);
							if (isset($last_message["mg_text"])) {
								$last_msg = $last_message["mg_text"];
								// HTML 태그 제거 후 순수 텍스트만 추출
								$last_msg = strip_tags($last_msg);
								// 첫 시작부터 20바이트 제한
								$temp_list[$key]["last_msg_preview"] = mb_substr($last_msg, 0, $preview_byte_length, "UTF-8");
								if (mb_strlen($last_msg, "UTF-8") > $preview_byte_length) {
									$temp_list[$key]["last_msg_preview"] .= "...";
								}
							}
							if (isset($last_message["mg_dt"])) {
								$last_msg_time = $last_message["mg_dt"];
								// formatChatTime 함수에서 직접 MongoDate 객체를 처리하도록 수정
								if (method_exists($this, 'formatChatTime')) {
									$temp_list[$key]["last_msg_time"] = $this->formatChatTime($last_msg_time);
								} else {
									// formatChatTime이 없는 경우에만 변환
									if (is_object($last_msg_time) && method_exists($last_msg_time, 'sec')) {
										$temp_list[$key]["last_msg_time"] = date("Y-m-d H:i:s", $last_msg_time->sec);
									} else {
										$temp_list[$key]["last_msg_time"] = $last_msg_time;
									}
								}
							}
						}
					}
				}

				// 기본값 설정
				if (empty($temp_list[$key]["last_msg_preview"])) {
					$temp_list[$key]["last_msg_preview"] = "";
				}
				if (empty($temp_list[$key]["last_msg_time"])) {
					$temp_list[$key]["last_msg_time"] = "";
				}
			}

			// unset된 항목 제거 후 배열 재정렬
			$temp_list = array_values($temp_list);
		} catch (Exception $e) {
			// MongoDB 연결 실패 시 기본값 설정
			foreach ($temp_list as $key => $row) {
				if (empty($temp_list[$key]["last_msg_preview"])) {
					$temp_list[$key]["last_msg_preview"] = "";
				}
				if (empty($temp_list[$key]["last_msg_time"])) {
					$temp_list[$key]["last_msg_time"] = "";
				}
			}
		}

		$list = $temp_list;

		json_return("00", "조회성공", ["list" => $list]);
	}

	function get_chat_target_list2_mem()
	{
		//제안받은공고

		$data = [];
		$ru_idx = input_post("ru_idx", "");

		//데이터 설정
		$mm_idx = $this->user->getUserIdx();
		$user_id = $this->user->getUserId();
		$user_phone = $this->user->getUserPhone(); // 전화번호도 가져오기 (채팅방 생성 시 mm_phone 사용)
		$params = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $mm_idx,
			"ru_idx" => $ru_idx,
		];
		$result = $this->member_model->getRequestRecruitList2Mem($params);

		$temp_list = $result["list"];
		$preview_byte_length = 20;

		// MongoDB 연결하여 마지막 메시지 조회
		$uri = $this->mongo_uri;
		try {
			@$mongo = new Mongo($uri);
			$database = $mongo->{$this->mongo_db};
			$collection = $database->room;

			foreach ($temp_list as $key => $row) {
				$temp_list[$key]["ru_resume_img"] = getResumeImg($row);
				$temp_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
				$temp_list[$key]["ru_work_type"] = getWorkType($row["ru_work_type"], "span", 0);
				$temp_list[$key]["ru_price_type"] = getPriceType($row["ru_price_type"], 0);
				$temp_list[$key]["ru_my_type"] = getMyType($row["ru_my_type"], 1);
				$temp_list[$key]["ru_week"] = getWeekYoil($row["ru_week"]);

				// 회사명 설정 (user_name을 mm_comp_name으로도 복사)
				$temp_list[$key]["mm_comp_name"] = isset($row["user_name"]) ? $row["user_name"] : "";

				// 마지막 메시지 조회
				$rm_idx = isset($row["rm_idx"]) ? (int)$row["rm_idx"] : 0;
				if ($rm_idx > 0) {
					$query = ['rm_idx' => $rm_idx];
					$room = $collection->findOne($query);

					if ($room) {
						// 채팅방에서 나간 사용자 확인 - rm_users 배열에서 exit_yn='Y'인 사용자는 제외
						$rm_users = isset($room["rm_users"]) ? $room["rm_users"] : [];
						$user_in_room = false;
						$user_exited = false;
						foreach ($rm_users as $room_user) {
							// user_id는 mm_id 또는 mm_phone일 수 있으므로 둘 다 체크
							$room_user_id = isset($room_user["user_id"]) ? $room_user["user_id"] : "";
							if ($room_user_id == $user_id || $room_user_id == $user_phone) {
								// 사용자가 rm_users에 있지만 exit_yn='Y'이면 나간 것으로 간주
								if (isset($room_user["exit_yn"]) && $room_user["exit_yn"] == "Y") {
									$user_exited = true;
								} else {
									$user_in_room = true;
								}
								break;
							}
						}

						// 나간 채팅방은 목록에서 제외
						if ($user_exited) {
							unset($temp_list[$key]);
							continue;
						}

						if ($user_in_room && !$user_exited && isset($room["rm_messages"]) && is_array($room["rm_messages"]) && count($room["rm_messages"]) > 0) {
							$last_message = end($room["rm_messages"]);
							if (isset($last_message["mg_text"])) {
								$last_msg = $last_message["mg_text"];
								// HTML 태그 제거 후 순수 텍스트만 추출
								$last_msg = strip_tags($last_msg);
								// 첫 시작부터 20바이트 제한
								$temp_list[$key]["last_msg_preview"] = mb_substr($last_msg, 0, $preview_byte_length, "UTF-8");
								if (mb_strlen($last_msg, "UTF-8") > $preview_byte_length) {
									$temp_list[$key]["last_msg_preview"] .= "...";
								}
							}
							if (isset($last_message["mg_dt"])) {
								$last_msg_time = $last_message["mg_dt"];
								// formatChatTime 함수에서 직접 MongoDate 객체를 처리하도록 수정
								if (method_exists($this, 'formatChatTime')) {
									$temp_list[$key]["last_msg_time"] = $this->formatChatTime($last_msg_time);
								} else {
									// formatChatTime이 없는 경우에만 변환
									if (is_object($last_msg_time) && method_exists($last_msg_time, 'sec')) {
										$temp_list[$key]["last_msg_time"] = date("Y-m-d H:i:s", $last_msg_time->sec);
									} else {
										$temp_list[$key]["last_msg_time"] = $last_msg_time;
									}
								}
							}
						}
					}
				}

				// 기본값 설정
				if (empty($temp_list[$key]["last_msg_preview"])) {
					$temp_list[$key]["last_msg_preview"] = "";
				}
				if (empty($temp_list[$key]["last_msg_time"])) {
					$temp_list[$key]["last_msg_time"] = "";
				}
			}

			// unset된 항목 제거 후 배열 재정렬
			$temp_list = array_values($temp_list);
		} catch (Exception $e) {
			// MongoDB 연결 실패 시 기본값 설정
			foreach ($temp_list as $key => $row) {
				// 회사명 설정 (user_name을 mm_comp_name으로도 복사)
				$temp_list[$key]["mm_comp_name"] = isset($row["user_name"]) ? $row["user_name"] : "";
				
				if (empty($temp_list[$key]["last_msg_preview"])) {
					$temp_list[$key]["last_msg_preview"] = "";
				}
				if (empty($temp_list[$key]["last_msg_time"])) {
					$temp_list[$key]["last_msg_time"] = "";
				}
			}
		}

		$list = $temp_list;

		json_return("00", "조회성공", ["list" => $list]);
	}

	function get_chat_target_list_by_recruite()
	{

		$data = [];
		$ru_idx = input_post("ru_idx", "");

		//데이터 설정
		$mm_idx = $this->user->getUserIdx();
		$params = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $mm_idx,
			"ru_idx" => $ru_idx
		];
		$result = $this->member_model->getRequestRecruitList($params);

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["ru_resume_img"] = getResumeImg($row);
			$temp_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
			$temp_list[$key]["ru_work_type"] = getWorkType($row["ru_work_type"], "span", 0);
			$temp_list[$key]["ru_price_type"] = getPriceType($row["ru_price_type"], 0);
			$temp_list[$key]["ru_my_type"] = getMyType($row["ru_my_type"], 1);
			$temp_list[$key]["ru_week"] = getWeekYoil($row["ru_week"]);
		}
		$list = $temp_list;

		json_return("00", "조회성공", ["list" => $list]);
	}

	# 즐겨찾기 처리
	function insert_room_check()
	{
		$rm_type = input_post("rm_type", "");
		$rm_idx = input_post("rm_idx", "");
		$rc_idx = input_post("rc_idx", "");
		$ru_idx = input_post("ru_idx", "");
		$td_idx = input_post("td_idx", "");
		$mm_idx = $this->user->getUserIdx();

		if ($this->user->isLogin() == false) {
			json_return("10", "로그인후 이용해주세요.");
		}

		$info = [
			"rm_type" => $rm_type,
			"rm_idx" => $rm_idx,
			"rc_idx" => $rc_idx,
			"ru_idx" => $ru_idx,
			"td_idx" => $td_idx,
			"mm_idx" => $mm_idx,
		];

		if ($rm_type == "recruit") {
			$chk = $this->common_model->getRoom(["rm_type" => $rm_type, "ru_idx" => $ru_idx, "rc_idx" => $rc_idx]);
			if ($chk != null) {
				// 기존 채팅방이 있으면 항상 first_yn: "N" (이미 만들어진 채팅방이므로)
				json_return("00", "이미있음", ["rm_idx" => $chk["rm_idx"], "first_yn" => "N"]);
			}
		} else if ($rm_type == "trade") {
			$chk = $this->common_model->getRoom(["rm_type" => $rm_type, "td_idx" => $td_idx, "mm_idx" => $mm_idx]);
			if ($chk != null) {
				// 🔥 기존 채팅방이 있어도 메시지가 없으면 first_yn: "Y"로 반환
				$message_count = 0;
				$uri = $this->mongo_uri;
				try {
					@$mongo = new Mongo($uri);
					$database = $mongo->{$this->mongo_db};
					$collection = $database->room;
					
					$query = ['rm_idx' => (int)$chk["rm_idx"]];
					$mongo_room = $collection->findOne($query);
					
					if ($mongo_room && isset($mongo_room["rm_messages"]) && is_array($mongo_room["rm_messages"])) {
						$message_count = count($mongo_room["rm_messages"]);
					}
				} catch (Exception $e) {
					// MongoDB 연결 실패 시 기본값 유지
				}
				
				// 메시지가 없으면 first_yn: "Y" (첫 메시지 전송 필요)
				$first_yn = ($message_count === 0) ? "Y" : "N";
				json_return("00", "이미있음", ["rm_idx" => $chk["rm_idx"], "first_yn" => $first_yn]);
			}
		}

		# 리쿨트일때만 실행한다.
		if ($rm_type == "recruit") {
			# 포인트 정보
			$point_info = $this->setting_model->getCodeInfo(["cd_grp" => "채팅포인트", "cd_key" => "CHAT_POINT"]);

			# 회원 포인트 정보
			$mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);

			if ($mem_info["mm_point"] < $point_info["cd_val3"]) {
				json_return("90", "포인트가 부족하여 채팅을 진행 할 수 없습니다. 포인트를 충전해주세요.<br/>보유포인트 : " . $mem_info["mm_point"] . "<br/>사용포인트 : " . $point_info["cd_val3"]);
			}
		}

		// 새로운 채팅방은 항상 first_yn: "Y" (첫 메시지를 보낼 예정)
		json_return("00", "채팅가능", ["first_yn" => "Y"]);
	}

	# 즐겨찾기 처리
	function insert_room()
	{
		$rm_type = input_post("rm_type", "");
		$rm_idx = input_post("rm_idx", "");
		$rc_idx = input_post("rc_idx", "");
		$ru_idx = input_post("ru_idx", "");
		$td_idx = input_post("td_idx", "");
		$mm_idx = $this->user->getUserIdx();

		if ($this->user->isLogin() == false) {
			json_return("10", "로그인후 이용해주세요.");
		}

		$info = [
			"rm_type" => $rm_type,
			"rm_idx" => $rm_idx,
			"rc_idx" => $rc_idx,
			"ru_idx" => $ru_idx,
			"td_idx" => $td_idx,
			"mm_idx" => $mm_idx,
		];

		if ($rm_type == "recruit") {
			$chk = $this->common_model->getRoom(["rm_type" => $rm_type, "ru_idx" => $ru_idx, "rc_idx" => $rc_idx]);
			if ($chk != null) {
				// 🔥 기존 채팅방이 있어도 메시지가 없으면 first_yn: "Y"로 반환
				$message_count = 0;
				$uri = $this->mongo_uri;
				try {
					@$mongo = new Mongo($uri);
					$database = $mongo->{$this->mongo_db};
					$collection = $database->room;
					
					$query = ['rm_idx' => (int)$chk["rm_idx"]];
					$mongo_room = $collection->findOne($query);
					
					if ($mongo_room && isset($mongo_room["rm_messages"]) && is_array($mongo_room["rm_messages"])) {
						$message_count = count($mongo_room["rm_messages"]);
					}
				} catch (Exception $e) {
					// MongoDB 연결 실패 시 기본값 유지
				}
				
				// 메시지가 없으면 first_yn: "Y" (첫 메시지 전송 필요)
				$first_yn = ($message_count === 0) ? "Y" : "N";
				json_return("00", "생성완료", ["rm_idx" => $chk["rm_idx"], "first_yn" => $first_yn]);
			}
		} else if ($rm_type == "trade") {
			$chk = $this->common_model->getRoom(["rm_type" => $rm_type, "td_idx" => $td_idx, "mm_idx" => $mm_idx]);
			if ($chk != null) {
				// 🔥 기존 채팅방이 있어도 메시지가 없으면 first_yn: "Y"로 반환
				$message_count = 0;
				$uri = $this->mongo_uri;
				try {
					@$mongo = new Mongo($uri);
					$database = $mongo->{$this->mongo_db};
					$collection = $database->room;
					
					$query = ['rm_idx' => (int)$chk["rm_idx"]];
					$mongo_room = $collection->findOne($query);
					
					if ($mongo_room && isset($mongo_room["rm_messages"]) && is_array($mongo_room["rm_messages"])) {
						$message_count = count($mongo_room["rm_messages"]);
					}
				} catch (Exception $e) {
					// MongoDB 연결 실패 시 기본값 유지
				}
				
				// 메시지가 없으면 first_yn: "Y" (첫 메시지 전송 필요)
				$first_yn = ($message_count === 0) ? "Y" : "N";
				json_return("00", "생성완료", ["rm_idx" => $chk["rm_idx"], "first_yn" => $first_yn]);
			}
		}

		# 리쿨트일때만 실행한다.
		if ($rm_type == "recruit") {
			# 포인트 정보
			$point_info = $this->setting_model->getCodeInfo(["cd_grp" => "채팅포인트", "cd_key" => "CHAT_POINT"]);

			# 회원 포인트 정보
			$mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);

			if ($mem_info["mm_point"] < $point_info["cd_val3"]) {
				json_return("90", "포인트가 부족하여 채팅을 진행 할 수 없습니다. 포인트를 충전해주세요.<br/>보유포인트 : " . $mem_info["mm_point"] . "<br/>사용포인트 : " . $point_info["cd_val3"]);
			}

			# 포인트 차감
			$last_point = $mem_info["mm_point"] - $point_info["cd_val3"];
			$insert = [
				"mm_idx" => $mm_idx,
				"o_num" => "",
				"pm_idx" => $rm_idx,
				"pt_type" => "CHAT",
				"pl_type" => "minus",
				"pl_point" => $point_info["cd_val3"],
				"pl_last_point" => $last_point,
				"pl_desc" => "채팅시작(" . $point_info["cd_val3"] . "P차감)",
				"reg_id" => "system",
			];
			$result_point = $this->site_model->insertPointList($insert);

			if ($result_point["code"] != "00") {
				$this->db->trans_rollback();
				json_return("92", "포인트 차감시 문제가 발생하였습니다. 관리자에 문의 바랍니다.");
			} else {
				$this->member_model->updateMember(["mm_point" => $last_point], ["mm_idx" => $mm_idx]);
			}
		}

		$info["point_yn"] = "Y";
		$result = $this->common_model->insertRoom($info);
		if ($result["code"] == "00") {
			json_return("00", "생성완료", ["rm_idx" => $result["key"], "first_yn" => "Y"]);
		} else {
			json_return("90", "채팅방 생성시 오류가 발생하였습니다.다시 시도해주세요.");
		}
	}

	function set_status()
	{
		$rm_idx = input_post("rm_idx", "");
		$rm_status = input_post("rm_status", "");
		$rm_dt = input_post("rm_dt", "");
		$mm_idx = $this->user->getUserIdx();

		# 상태 업데이트
		$this->common_model->updateRoom(["rm_status" => $rm_status], ["rm_idx" => $rm_idx]);

		if ($rm_status == "면접일정확인") {
			$this->common_model->updateRoom(["rm_itv_dt" => $rm_dt], ["rm_idx" => $rm_idx]);
		} else if ($rm_status == "채용일정확인") {
			$this->common_model->updateRoom(["rm_rec_dt" => $rm_dt], ["rm_idx" => $rm_idx]);
		} else if ($rm_status == "개인면접수락") {
			# 룸정보 획득
			$room = $this->common_model->getRoom(["rm_idx" => $rm_idx]);

			# 인터뷰 등록자 여부 확인후 처리한다.
			$info = $this->common_model->getInterview(["ru_idx" => $room["ru_idx"], "rc_idx" => $room["rc_idx"]]);

			//추가정보 조회
			if ($info == null) {
				$recruit = $this->member_model->getRecruit(["rc_idx" => $room["rc_idx"]]);
				$resume = $this->member_model->getResume(["ru_idx" => $room["ru_idx"]]);

				//면접일 확정되면 인터뷰 등록처리
				$iv_data = [
					"rc_idx" => $recruit["rc_idx"],
					"rc_mm_idx" => $recruit["mm_idx"],
					"ru_idx" => $resume["ru_idx"],
					"ru_mm_idx" => $resume["mm_idx"],
					"iv_status" => "면접확정",
					"iv_req_dt" => $room["rm_itv_dt"],
				];
				$this->common_model->insertInterview($iv_data);
			} else {

				//면접일 확정되면 인터뷰 등록처리
				$iv_data = [
					"iv_status" => "면접확정",
					"iv_req_dt" => $room["rm_itv_dt"],
				];
				$this->common_model->updateInterview($iv_data, ["iv_idx" => $info["iv_idx"], "iv_status" => "면접확정"]);
			}
		} else if ($rm_status == "면접취소") {
			# 룸정보 획득
			$room = $this->common_model->getRoom(["rm_idx" => $rm_idx]);

			# 인터뷰 등록자 여부 확인후 처리한다.
			$info = $this->common_model->getInterview(["ru_idx" => $room["ru_idx"], "rc_idx" => $room["rc_idx"]]);

			if ($info != null) {
				$iv_data = [
					"iv_status" => "면접취소",
				];
				$this->common_model->updateInterview($iv_data, ["iv_idx" => $info["iv_idx"], "iv_status" => "면접확정"]);
			}
		} else if ($rm_status == "채용취소") {
			# 룸정보 획득
			$room = $this->common_model->getRoom(["rm_idx" => $rm_idx]);

			# 인터뷰 등록자 여부 확인후 처리한다.
			$info = $this->common_model->getInterview(["ru_idx" => $room["ru_idx"], "rc_idx" => $room["rc_idx"]]);

			if ($info != null) {
				$iv_data = [
					"iv_status" => "채용취소",
				];
				$this->common_model->updateInterview($iv_data, ["iv_idx" => $info["iv_idx"], "iv_status" => "면접확정"]);
			}
		}

		# 히스토리 등록
		$this->common_model->insertRoomHistory(["rm_idx" => $rm_idx, "mm_idx" => $mm_idx, "rh_status" => $rm_status]);
	}

	# 지원 상태 확인 (개인회원이 지원취소했는지 확인)
	function check_request_status()
	{
		$mq_idx = input_post("mq_idx", "");
		$ru_idx = input_post("ru_idx", "");
		$rc_idx = input_post("rc_idx", "");

		// mq_idx가 있으면 mq_idx로 확인하고, ru_idx와 rc_idx도 일치하는지 확인
		if (!empty($mq_idx)) {
			$sql = "
				SELECT mq_idx, mq_type, ru_idx, rc_idx, mq_status
				FROM st_member_request 
				WHERE mq_idx = '" . $this->db->escape_str($mq_idx) . "'
				LIMIT 1
			";
			$query = $this->db->query($sql);
			$result = $query->row_array();

			if ($result == null || empty($result)) {
				// 지원 정보가 없으면 지원취소된 것으로 판단
				json_return("91", "지원취소");
			} else {
				// mq_status가 'cancel'이면 지원취소된 것으로 판단
				if (isset($result["mq_status"]) && $result["mq_status"] == "cancel") {
					json_return("91", "지원취소");
				}
				
				// ru_idx와 rc_idx가 제공된 경우 일치하는지 확인
				if (!empty($ru_idx) && !empty($rc_idx)) {
					// mq_type이 RECRUIT인지 확인하고, ru_idx와 rc_idx가 일치하는지 확인
					if ($result["mq_type"] == "RECRUIT" && 
						$result["ru_idx"] == $ru_idx && 
						$result["rc_idx"] == $rc_idx) {
						// 모든 조건이 일치하면 정상
						json_return("00", "정상");
					} else {
						// 조건이 일치하지 않으면 지원취소된 것으로 판단
						json_return("91", "지원취소");
					}
				} else {
					// ru_idx와 rc_idx가 없으면 mq_idx만으로 확인
					json_return("00", "정상");
				}
			}
		} 
		// mq_idx가 없으면 ru_idx와 rc_idx로 확인 (하위 호환성)
		else if (!empty($ru_idx) && !empty($rc_idx)) {
			$sql = "
				SELECT mq_idx, mq_status
				FROM st_member_request 
				WHERE mq_type = 'RECRUIT' 
				AND ru_idx = '" . $this->db->escape_str($ru_idx) . "' 
				AND rc_idx = '" . $this->db->escape_str($rc_idx) . "'
				LIMIT 1
			";
			$query = $this->db->query($sql);
			$result = $query->row_array();

			if ($result == null || empty($result)) {
				// 지원 정보가 없으면 지원취소된 것으로 판단
				json_return("91", "지원취소");
			} else {
				// mq_status가 'cancel'이면 지원취소된 것으로 판단
				if (isset($result["mq_status"]) && $result["mq_status"] == "cancel") {
					json_return("91", "지원취소");
				}
				// 지원 정보가 있고 취소 상태가 아니면 정상
				json_return("00", "정상");
			}
		} else {
			json_return("90", "필수 파라미터가 누락되었습니다.");
		}
	}

	# 중고상품 채팅상세
	function get_chat_info()
	{
		$data = [];

		$mm_idx = $this->user->getUserIdx();
		$rm_idx = input_post("rm_idx");
		$mm_type = input_post("mm_type");

		# 룸정보 가져오기
		$room = $this->common_model->getRoom(["rm_idx" => $rm_idx]);
		if ($room["rm_type"] == "recruit") {
			if ($mm_type == "MEM") {
				$info = $this->member_model->getRecruitDetail($room["rc_idx"], $mm_idx);

				$info["type"] = "recruit";
				$info["rt_recruit_img"] = getRecruitImg($info);
				$info["rt_work_img"] = getWorkingImg($info["rc_work"]);
				$info["rt_work_type"] = getWorkType($info["rc_work_type"]);
				$info["rt_price_type"] = getPriceType($info["rc_price_type"]);
				$info["rt_price_info"] = getPriceType($info["rc_price_type"], "amount");
				$info["rt_price"] = getRecuitPrice($info);
				$info["rt_week"] = getWeekYoil($info["rc_week"]);
				$info["rt_date"] = substr($info["rc_sdate"], 0, 10) . " ~ " . substr($info["rc_edate"], 0, 10);
				$info["recruit_phone"] = $info["mm_phone"];
				$info["recruit_name"] = $info["mm_comp_name"];

				if (strpos($info["rc_work_type"], "2교대") !== false) {
					$info["rt_time"] = $info["rc_am_stime"] . "~" . $info["rc_am_etime"] . "(주간)";
					$info["rt_time"] .= $info["rc_pm_stime"] . "~" . $info["rc_pm_etime"] . "(야간)";
				} else if (strpos($info["rc_work_type"], "주간") !== false && strpos($info["rc_work_type"], "2교대") === false) {
					$info["rt_time"] = $info["rc_am_stime"] . "~" . $info["rc_am_etime"] . "(주간)";
				} else if (strpos($info["rc_work_type"], "야간") !== false && strpos($info["rc_work_type"], "2교대") === false) {
					$info["rt_time"] = $info["rc_pm_stime"] . "~" . $info["rc_pm_etime"] . "(야간)";
				}
			} else {
				$recruit = $this->member_model->getRecruitDetail($room["rc_idx"], $mm_idx);

				$info = $this->member_model->getResumeDetail($room["ru_idx"], $mm_idx);
				$info["career_list"] = $this->member_model->getResumeCareerList(["ru_idx" => $room["ru_idx"]]);
				$info["certi_list"] = $this->member_model->getResumeCertiList(["ru_idx" => $room["ru_idx"]]);

				$info["type"] = "recruit";
				$info["rc_idx"] = $room["rc_idx"];
				$info["ru_resume_img"] = getResumeImg($info);
				$info["ru_work_img"] = getWorkingImg($info["ru_work"]);
				// chat_view.php와 동일하게 기본값 사용 (기본값: $tag = "span", $max = 10)
				// 클라이언트 측에서 <span>을 <em>으로 변환하는 로직이 있으므로 서버에서는 기본값 사용
				$info["ru_work_type"] = getWorkType($info["ru_work_type"]);
				// chat_view.php와 동일하게 기본값($max = 3) 사용하여 모든 급여 타입 표시
				$info["ru_price_type"] = getPriceType($info["ru_price_type"]);
				$info["ru_my_type"] = getMyType($info["ru_my_type"], 1);
				$info["ru_week"] = getWeekYoil($info["ru_week"]);

				$info["recruit_phone"] = $recruit["mm_phone"];
				$info["recruit_name"] = $recruit["mm_comp_name"];
			}
		} else {
			$info = $this->site_model->getTrade(["td_idx" => $room["td_idx"]]);
			$mem_info = $this->member_model->getMember(["mm_idx" => $info["mm_idx"]]);
			$info["mm_name"] = $mem_info["mm_name"];

			$addr_list = $this->member_model->getAddressList(["ad_type" => "TRADE", "ad_key" => $room["td_idx"]]);

			$area = "";
			foreach ($addr_list as $key => $row) {
				$area .= ($key == 0 ? "" : ",") . $row["ad_addr"];
			}

			$info["type"] = "trade";
			$info["td_area"] = $area;
			$info["reg_dt"] = substr($info["reg_dt"], 0, 10);
		}
		json_return("00", "조회완료", $info);
	}


	//채팅 미확인 조회
	function fSearchNoReadCount()
	{

		// MongoDB 연결 정보
		$uri = $this->mongo_uri;
		

		try {
			// MongoDB 클라이언트 생성 및 연결
			//$client = new MongoDB\Client($uri);
			//MongoClient 를 쓰라는 오류 메시지 안나오도록 "@" 추가
			@$mongo = new Mongo($uri);

			// 데이터베이스 선택
			$database = $mongo->{$this->mongo_db}; //$client->$dbName;

			// 컬렉션 선택 (room 컬렉션)
			$collection = $database->room;

			// 한 건만 조회 (findOne)
			//$document = $collection->findOne(); // 조건 없이 첫 번째 문서 조회

			//$query = array('rm_last_msg' => "22"); //마지막 메시지가 "22"인 채팅 조회
			//$query = array('rm_messages.mg_reads.read_yn' => 'N'); //메시지 read_yn 이 N인 채팅 조회
			$query = array(
				//'rm_messages.mg_reads.read_yn' => 'N',
				//'rm_messages.user_id' => $this->user->getUserId(),
				'rm_messages.mg_reads.user_id' => $this->user->getUserId()
			);

			// 필요한 필드만 조회하여 응답 최적화
			// 레거시 MongoDB 드라이버에서는 두 번째 파라미터로 fields 배열 전달
			$fields = array(
				'rm_idx' => 1,
				'rm_messages' => 1
			);

			//$document = $collection->findOne($query);//한건조회
			$cursor = $collection->find($query, $fields);

			// 조회 결과를 배열로 변환
			$results = iterator_to_array($cursor);

			// if ($document) {
			//      $result = json_encode($document) . "\n";
			// } else {
			//      $result = "조회된 문서가 없습니다.\n";
			// }

		} catch (MongoDB\Driver\Exception\Exception $e) {
			$result = "MongoDB 연결 오류: {$e->getMessage()}\n";
			//exit;
		}

		// MongoDB 조회 결과에서 탈퇴 회원 메시지 제외
		// mg_reads에 저장된 mm_status를 확인하여 필터링
		$my_user_id = $this->user->getUserId();
		$filtered_results = [];

		foreach ($results as $room) {
			if (!isset($room["rm_messages"])) {
				$filtered_results[] = $room;
				continue;
			}

			// 각 room의 메시지를 순회하면서 탈퇴 회원 읽음 상태 제외
			$filtered_messages = [];
			foreach ($room["rm_messages"] as $message) {
				if (!isset($message["mg_reads"])) {
					$filtered_messages[] = $message;
					continue;
				}

				// mg_reads에서 탈퇴 회원(mm_status != 'Y') 제외
				$filtered_reads = [];
				foreach ($message["mg_reads"] as $read) {
					// mm_status가 'Y'인 경우만 유지
					// (mm_status가 없거나 'N'인 경우는 탈퇴 회원으로 간주하여 제외)
					if (isset($read["mm_status"]) && $read["mm_status"] === 'Y') {
						$filtered_reads[] = $read;
					} elseif (!isset($read["mm_status"])) {
						// 레거시 데이터(mm_status 없음)는 유지
						$filtered_reads[] = $read;
					}
				}
				$message["mg_reads"] = $filtered_reads;
				$filtered_messages[] = $message;
			}
			$room["rm_messages"] = $filtered_messages;
			$filtered_results[] = $room;
		}

		$results = $filtered_results;

		// 응답 최적화: 필요한 필드만 추출
		// 필수 필드: rm_idx, mg_reads[read_yn, user_id, mm_status]
		$optimized_results = [];
		foreach ($results as $room) {
			$optimized_room = array(
				'rm_idx' => isset($room['rm_idx']) ? $room['rm_idx'] : null
			);

			if (isset($room['rm_messages']) && is_array($room['rm_messages'])) {
				$optimized_messages = [];
				foreach ($room['rm_messages'] as $message) {
					if (isset($message['mg_reads']) && is_array($message['mg_reads']) && count($message['mg_reads']) > 0) {
						// mg_reads에서 필요한 필드만 추출
						$optimized_reads = [];
						foreach ($message['mg_reads'] as $read) {
							$optimized_reads[] = array(
								'read_yn' => isset($read['read_yn']) ? $read['read_yn'] : 'Y',
								'user_id' => isset($read['user_id']) ? $read['user_id'] : null,
								'mm_status' => isset($read['mm_status']) ? $read['mm_status'] : null
							);
						}
						$optimized_messages[] = array('mg_reads' => $optimized_reads);
					}
				}
				$optimized_room['rm_messages'] = $optimized_messages;
			}

			$optimized_results[] = $optimized_room;
		}

		$data = [];
		$data["reg_dt"] = date("Y-m-d H:i:s");
		//$data["result"] = $document;
		$data["result"] = $optimized_results;
		json_return("00", "조회완료", $data);

		/*
$data = ['user_id' => $this->user->getUserId()]; // 사용자 ID 설정

// MongoDB 집계 파이프라인 정의
$pipeline = [
    ['$match' => ['rm_users.user_id' => $data['user_id']]],
    ['$unwind' => '$rm_messages'],
    ['$project' => [
        'rm_idx' => 1,
        'rm_messages' => 1
    ]],
    ['$unwind' => '$rm_messages.mg_reads'],
    ['$match' => [
        'rm_messages.mg_reads.read_yn' => 'N',
        'rm_messages.mg_reads.user_id' => $data['user_id']
    ]],
    ['$group' => [
        '_id' => '$rm_idx',
        'count' => ['$sum' => 1]
    ]]
];

// 집계 쿼리 실행
$command = [
    'aggregate' => 'room',
    'pipeline' => $pipeline,
    'cursor' => new stdClass()
];

$cursor = $database->command($command);
$results = iterator_to_array($cursor['cursor']['firstBatch']);

// 결과 처리
$return_data = [];
foreach ($results as $result) {
    $return_data[] = [
        'rm_idx' => $result['_id'],
        'unread_count' => $result['count']
    ];
}

// $data 배열에 결과 추가
$data = [];
$data["reg_dt"] = date("Y-m-d H:i:s");
$data["result"] = $return_data;
json_return("00", "조회완료", $data);
*/
	}

	//채팅방 생성
	// 현재 사용하지 않음
	function newroommake()
	{


		// MongoDB 연결 정보
		$uri = $this->mongo_uri;
		$dbName = "chat"; // 사용할 데이터베이스 이름

		try {
			// MongoDB 클라이언트 생성 및 연결
			//$client = new MongoDB\Client($uri);
			//MongoClient 를 쓰라는 오류 메시지 안나오도록 "@" 추가
			@$mongo = new Mongo($uri);

			// 데이터베이스 선택
			$database = $mongo->{$this->mongo_db}; //$client->$dbName;

			// 컬렉션 선택 (room 컬렉션)
			$collection = $database->room;

			//            "_id" => new MongoId("66202416cb33c7aefec48e12"),
			$rm_idx = (int) filter_input(INPUT_POST, 'rm_idx', FILTER_SANITIZE_NUMBER_INT);
			$document = [
				"rm_idx" => $rm_idx,
				"rm_type" => "chat",
				"rm_img" => "",
				"rm_subject" => "",
				"rm_desc" => "",
				"rm_last_msg" => "",
				"rm_last_dt" => new MongoDate(),
				"rm_users" => [
					[
						"user_id" => input_post("user_id1"),
						"img" => "",
						"user_name" => input_post("user_name1")
					],
					[
						"user_id" => input_post("user_id2"),
						"img" => "",
						"user_name" => input_post("user_name2")
					]
				],
				"user_info" => [
					[
						"user_id" => input_post("user_id1"),
						"img" => "",
						"user_name" => input_post("user_name1")
					],
					[
						"user_id" => input_post("user_id2"),
						"img" => "",
						"user_name" => input_post("user_name2")
					]
				],
				"rm_status" => "N"
			];

			// 문서 삽입
			$collection->insert($document);
		} catch (MongoDB\Driver\Exception\Exception $e) {
			$result = "MongoDB 연결 오류: {$e->getMessage()}\n";
			//exit;
		}

		$data = [];
		$data["reg_dt"] = date("Y-m-d H:i:s");
		$data["result"] = $document;

		//update room 최근 대화시간
		$sql = "update st_room set up_dt = now() where rm_idx = '" . $rm_idx . "' ";
		$query = $this->db->query($sql);

		json_return("00", "조회완료", $data);
	}

	function getRoomListActaive()
	{
		//지원한-공고리스트
		$data = [];

		//데이터 설정
		$mm_idx = $this->user->getUserIdx();
		$result = $this->member_model->getRoomListActaive($mm_idx);

		$temp_list = $result;

		$list = $temp_list;

		json_return("00", "조회성공", ["list" => $list]);
	}

	// 제안보낸 인재 목록 조회 (새 UI)
	function get_chat_target_list_new_sent()
	{
		// 개인회원이 이력서에 제안보낸 채팅방 목록
		$data = [];

		// 데이터 설정
		$mm_idx = $this->user->getUserIdx();
		$user_id = $this->user->getUserId();
		$result = $this->member_model->getRecruitActaiveList2($mm_idx);

		$temp_list = $result;

		// MongoDB 연결하여 마지막 메시지, 시간, 미확인 카운트 조회
		$uri = $this->mongo_uri;
		try {
			@$mongo = new Mongo($uri);
			$database = $mongo->{$this->mongo_db};
			$collection = $database->room;

			foreach ($temp_list as $key => $row) {
				// 회사 프로필 이미지 처리
				$company_info = $this->member_model->getMember(["mm_idx" => $row["mm_idx"]]);
				if (!empty($company_info)) {
					$company_img = getCompImg($company_info);
					// NOW JOB 로고로 대체 (이미지가 없을 경우)
					if (empty($company_img) || $company_img == "/resource/shop/mobile/img/common/noimage4.png") {
						$company_img = "/resource/shop/mobile/img/common/noimage4.png"; // NOW JOB 로고 경로로 변경 필요
					}
					$temp_list[$key]["company_img"] = $company_img;
					$temp_list[$key]["company_name"] = $company_info["mm_comp_name"] ? $company_info["mm_comp_name"] : $company_info["mm_name"];
				} else {
					$temp_list[$key]["company_img"] = "/resource/shop/mobile/img/common/noimage4.png";
					$temp_list[$key]["company_name"] = "";
				}

				// rc_thumb가 null이거나 빈 값일 경우 getRecruitImg 함수로 대체 이미지 처리
				if (empty($row["rc_thumb"])) {
					$temp_list[$key]["rc_thumb"] = getRecruitImg($row);
				}

				// 채팅방 인덱스 리스트 파싱
				$rm_idx_list = [];
				if (!empty($row["rm_idx_list"])) {
					$rm_idx_str = preg_replace('/[()]/', '', $row["rm_idx_list"]);
					$rm_idx_list = array_filter(array_map('trim', explode(',', $rm_idx_str)));
				}
				$temp_list[$key]["rm_idx_array"] = $rm_idx_list;

				// 마지막 메시지, 시간, 미확인 카운트 조회
				$last_msg = "";
				$last_msg_time = "";
				$unread_count = 0;
				$chat_participants = 0;

				if (!empty($rm_idx_list)) {
					// 가장 최근 채팅방의 마지막 메시지 조회
					$latest_rm_idx = 0;
					$latest_time = "";
					foreach ($rm_idx_list as $rm_idx) {
						$rm_idx = (int)trim($rm_idx);
						$query = ['rm_idx' => $rm_idx];
						$room = $collection->findOne($query);

						if ($room) {
							// 채팅 참여자 수
							if (isset($room["rm_users"]) && is_array($room["rm_users"])) {
								$chat_participants = count($room["rm_users"]);
							}

							// 마지막 메시지 조회
							if (isset($room["rm_messages"]) && is_array($room["rm_messages"]) && count($room["rm_messages"]) > 0) {
								$last_message = end($room["rm_messages"]);
								if (isset($last_message["mg_text"])) {
									$last_msg = $last_message["mg_text"];
									// HTML 태그 제거 후 순수 텍스트만 추출
									$last_msg = strip_tags($last_msg);
									// 첫 시작부터 20바이트 제한
									$temp_list[$key]["last_msg_preview"] = mb_substr($last_msg, 0, 20, "UTF-8");
									if (mb_strlen($last_msg, "UTF-8") > 20) {
										$temp_list[$key]["last_msg_preview"] .= "...";
									}
								}
								if (isset($last_message["mg_dt"])) {
									$last_msg_time = $last_message["mg_dt"];
									// formatChatTime 함수에서 직접 MongoDate 객체를 처리하도록 수정
									$temp_list[$key]["last_msg_time"] = $this->formatChatTime($last_msg_time);

									if (empty($latest_time) || $last_msg_time > $latest_time) {
										$latest_time = $last_msg_time;
										$latest_rm_idx = $rm_idx;
									}
								}
							}

							// 미확인 메시지 카운트
							if (isset($room["rm_messages"]) && is_array($room["rm_messages"])) {
								foreach ($room["rm_messages"] as $message) {
									if (isset($message["mg_reads"]) && is_array($message["mg_reads"])) {
										foreach ($message["mg_reads"] as $read) {
											if (
												isset($read["user_id"]) && $read["user_id"] == $user_id &&
												isset($read["read_yn"]) && $read["read_yn"] == "N"
											) {
												$unread_count++;
											}
										}
									}
								}
							}
						}
					}

					// 가장 최근 채팅방 기준으로 시간 설정
					if ($latest_rm_idx > 0) {
						$query = ['rm_idx' => $latest_rm_idx];
						$latest_room = $collection->findOne($query);
						if ($latest_room && isset($latest_room["rm_messages"]) && is_array($latest_room["rm_messages"]) && count($latest_room["rm_messages"]) > 0) {
							$latest_msg = end($latest_room["rm_messages"]);
							if (isset($latest_msg["mg_dt"])) {
								$latest_time = $latest_msg["mg_dt"];
								if (is_object($latest_time) && method_exists($latest_time, 'sec')) {
									$latest_time = date("Y-m-d H:i:s", $latest_time->sec);
								}
								$temp_list[$key]["last_msg_time"] = $this->formatChatTime($latest_time);
								if (isset($latest_msg["mg_text"])) {
									$last_msg = $latest_msg["mg_text"];
									// HTML 태그 제거 후 순수 텍스트만 추출
									$last_msg = strip_tags($last_msg);
									// 첫 시작부터 20바이트 제한
									$temp_list[$key]["last_msg_preview"] = mb_substr($last_msg, 0, 20, "UTF-8");
									if (mb_strlen($last_msg, "UTF-8") > 20) {
										$temp_list[$key]["last_msg_preview"] .= "...";
									}
								}
							}
						}
					}
				}

				$temp_list[$key]["unread_count"] = $unread_count;
				$temp_list[$key]["chat_participants"] = $chat_participants;
			}
		} catch (Exception $e) {
			// MongoDB 연결 실패 시 기본값 설정
			foreach ($temp_list as $key => $row) {
				$temp_list[$key]["last_msg_preview"] = "";
				$temp_list[$key]["last_msg_time"] = "";
				$temp_list[$key]["unread_count"] = 0;
				$temp_list[$key]["chat_participants"] = 0;
			}
		}

		// 인덱스 정리(중간 unset된 항목 제거)
		$list = array_values($temp_list);
		json_return("00", "조회성공", ["list" => $list]);
	}

	// 제안받은 인재 목록 조회 (새 UI)
	function get_chat_target_list_new_received()
	{
		// 개인회원이 채용공고에 지원한 채팅방 목록
		$data = [];

		// 데이터 설정
		$mm_idx = $this->user->getUserIdx();
		$user_id = $this->user->getUserId();
		$result = $this->member_model->getRecruitActaiveListMem($mm_idx);

		$temp_list = $result;

		// MongoDB 연결하여 마지막 메시지, 시간, 미확인 카운트 조회
		$uri = $this->mongo_uri;
		try {
			@$mongo = new Mongo($uri);
			$database = $mongo->{$this->mongo_db};
			$collection = $database->room;

			foreach ($temp_list as $key => $row) {
				// 회사 프로필 이미지 처리
				$company_info = $this->member_model->getMember(["mm_idx" => $row["mm_idx"]]);
				if (!empty($company_info)) {
					$company_img = getCompImg($company_info);
					// NOW JOB 로고로 대체 (이미지가 없을 경우)
					if (empty($company_img) || $company_img == "/resource/shop/mobile/img/common/noimage4.png") {
						$company_img = "/resource/shop/mobile/img/common/noimage4.png"; // NOW JOB 로고 경로로 변경 필요
					}
					$temp_list[$key]["company_img"] = $company_img;
					$temp_list[$key]["company_name"] = $company_info["mm_comp_name"] ? $company_info["mm_comp_name"] : $company_info["mm_name"];
				} else {
					$temp_list[$key]["company_img"] = "/resource/shop/mobile/img/common/noimage4.png";
					$temp_list[$key]["company_name"] = "";
				}

				// rc_thumb가 null이거나 빈 값일 경우 getRecruitImg 함수로 대체 이미지 처리
				if (empty($row["rc_thumb"])) {
					$temp_list[$key]["rc_thumb"] = getRecruitImg($row);
				}

				// 인재 정보 처리
				$resume_info = $this->member_model->getResume(["ru_idx" => $row["ru_idx"]]);
				if (!empty($resume_info)) {
					$temp_list[$key]["ru_resume_img"] = getResumeImg($resume_info);
					$temp_list[$key]["ru_work_img"] = getWorkingImg($resume_info["ru_work"]);
					$temp_list[$key]["ru_work_type"] = getWorkType($resume_info["ru_work_type"], "span", 0);
					$temp_list[$key]["mm_nation"] = $resume_info["mm_nation"] ? $resume_info["mm_nation"] : "";
					$temp_list[$key]["ma_age"] = $resume_info["ma_age"] ? $resume_info["ma_age"] : "";
					$temp_list[$key]["mm_name"] = $resume_info["mm_name"] ? $resume_info["mm_name"] : "";
				}

				// 채팅방 인덱스 조회
				$rm_idx = isset($row["rm_idx"]) ? (int)$row["rm_idx"] : 0;
				$temp_list[$key]["rm_idx"] = $rm_idx;

				// 마지막 메시지, 시간, 미확인 카운트 조회
				$last_msg = "";
				$last_msg_time = "";
				$unread_count = 0;

				if ($rm_idx > 0) {
					$query = ['rm_idx' => $rm_idx];
					$room = $collection->findOne($query);

					if ($room) {
						// 마지막 메시지 조회
						if (isset($room["rm_messages"]) && is_array($room["rm_messages"]) && count($room["rm_messages"]) > 0) {
							$last_message = end($room["rm_messages"]);
							if (isset($last_message["mg_text"])) {
								$last_msg = $last_message["mg_text"];
								// 20바이트 미리보기
								$temp_list[$key]["last_msg_preview"] = mb_substr($last_msg, 0, 20, "UTF-8");
								if (mb_strlen($last_msg, "UTF-8") > 20) {
									$temp_list[$key]["last_msg_preview"] .= "...";
								}
							}
							if (isset($last_message["mg_dt"])) {
								$last_msg_time = $last_message["mg_dt"];
								// formatChatTime 함수에서 직접 MongoDate 객체를 처리하도록 수정
								$temp_list[$key]["last_msg_time"] = $this->formatChatTime($last_msg_time);
							}
						}

						// 미확인 메시지 카운트
						if (isset($room["rm_messages"]) && is_array($room["rm_messages"])) {
							foreach ($room["rm_messages"] as $message) {
								if (isset($message["mg_reads"]) && is_array($message["mg_reads"])) {
									foreach ($message["mg_reads"] as $read) {
										if (
											isset($read["user_id"]) && $read["user_id"] == $user_id &&
											isset($read["read_yn"]) && $read["read_yn"] == "N"
										) {
											$unread_count++;
										}
									}
								}
							}
						}
					}
				}

				$temp_list[$key]["unread_count"] = $unread_count;
				if (empty($temp_list[$key]["last_msg_preview"])) {
					$temp_list[$key]["last_msg_preview"] = "";
				}
				if (empty($temp_list[$key]["last_msg_time"])) {
					$temp_list[$key]["last_msg_time"] = "";
				}
			}
		} catch (Exception $e) {
			// MongoDB 연결 실패 시 기본값 설정
			foreach ($temp_list as $key => $row) {
				$temp_list[$key]["last_msg_preview"] = "";
				$temp_list[$key]["last_msg_time"] = "";
				$temp_list[$key]["unread_count"] = 0;
			}
		}

		$list = $temp_list;
		json_return("00", "조회성공", ["list" => $list]);
	}

	// 채팅 시간 포맷팅 헬퍼 함수
	private function formatChatTime($datetime)
	{
		// null이나 false 체크
		if ($datetime === null || $datetime === false) {
			return "";
		}

		$timestamp = null;

		// MongoDB MongoDate 객체인 경우 처리
		if (is_object($datetime)) {
			// MongoDate 객체인지 확인
			if (method_exists($datetime, 'sec')) {
				$timestamp = $datetime->sec;
			} else if (isset($datetime->sec)) {
				$timestamp = $datetime->sec;
			} else if (get_class($datetime) === 'MongoDate') {
				$timestamp = $datetime->sec;
			}
		}

		// 문자열인 경우
		if ($timestamp === null && is_string($datetime) && !empty($datetime)) {
			$timestamp = strtotime($datetime);
			if ($timestamp === false) {
				return "";
			}
		}

		// 숫자인 경우 (타임스탬프)
		if ($timestamp === null && is_numeric($datetime)) {
			$timestamp = (int)$datetime;
		}

		// 타임스탬프가 없으면 빈 문자열 반환
		if ($timestamp === null || $timestamp <= 0) {
			return "";
		}

		// 항상 시간 형식으로 표시 (오전/오후 시:분)
		$hour = (int)date("H", $timestamp);
		$minute = date("i", $timestamp);
		$ampm = $hour >= 12 ? "오후" : "오전";
		$hour_12 = $hour > 12 ? $hour - 12 : ($hour == 0 ? 12 : $hour);
		return $ampm . " " . $hour_12 . ":" . $minute;
	}

	// 기업 채팅방별 인재 목록 조회 (0225-11-10 추가)
	function get_chat_target_list_by_recruit_new()
	{
		// 특정 채용공고의 개인회원 채팅방 리스트
		$data = [];
		$rc_idx = input_post("rc_idx", "");
		$chatgbn = input_post("chatgbn", ""); // chatgbn 파라미터 추가
		$user_id = $this->user->getUserId();

		if (empty($rc_idx)) {
			json_return("10", "채용공고 번호가 필요합니다.");
		}

		// 데이터 설정
		$mm_idx = $this->user->getUserIdx();
		$params = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $mm_idx,
			"ru_idx" => $rc_idx, // getRequestRecruitListSended는 ru_idx 파라미터에 실제로 rc_idx 값을 받음
		];

		// chatgbn에 따라 지원한 인재 또는 제안 보낸 인재 조회
		if ($chatgbn == "chat_list2" || $chatgbn == "chatPanel2") {
			// 제안보낸 인재 탭: 제안 보낸 인재 목록 조회
			$result = $this->member_model->getRequestRecruitListSended($params);
		} else {
			// 채용공고 탭: 지원한 인재 목록 조회
			$params["ru_idx"] = 0; // 지원한 인재 조회는 ru_idx를 0으로
			$result = $this->member_model->getRequestRecruitList($params);
		}

		$temp_list = $result["list"];
		$filtered_list = [];

		foreach ($temp_list as $key => $row) {
			// getRequestRecruitListSended는 이미 rc_idx로 필터링된 데이터를 반환하므로
			// 제안보낸 인재 탭인 경우 필터링 생략, 채용공고 탭인 경우만 필터링
			if ($chatgbn == "chat_list2" || $chatgbn == "chatPanel2") {
				// 제안보낸 인재 탭: 이미 필터링된 데이터이므로 바로 처리
			} else {
				// 채용공고 탭: 해당 채용공고와 일치하는 경우만 필터링
				if ($row["rc_idx"] != $rc_idx) {
					continue;
				}
			}

			// 데이터 처리
			$temp_list[$key]["ru_resume_img"] = getResumeImg($row);
			$temp_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
			$temp_list[$key]["ru_work_type"] = getWorkType($row["ru_work_type"], "span", 0);
			$temp_list[$key]["mm_nation"] = $row["mm_nation"] ? $row["mm_nation"] : "";
			$temp_list[$key]["ma_age"] = $row["ma_age"] ? $row["ma_age"] : "";
			$temp_list[$key]["mm_name"] = $row["mm_name"] ? $row["mm_name"] : "";

			// 채팅방 정보 조회
			$room = $this->common_model->getRoom(["rc_idx" => $rc_idx, "ru_idx" => $row["ru_idx"]]);
			if (!empty($room)) {
				$temp_list[$key]["rm_idx"] = $room["rm_idx"];

				// MongoDB에서 마지막 메시지, 시간 조회
				$uri = $this->mongo_uri;
				try {
					@$mongo = new Mongo($uri);
					$database = $mongo->{$this->mongo_db};
					$collection = $database->room;

					$query = ['rm_idx' => (int)$room["rm_idx"]];
					$mongo_room = $collection->findOne($query);

					if ($mongo_room) {
						// 마지막 메시지 조회
						if (isset($mongo_room["rm_messages"]) && is_array($mongo_room["rm_messages"]) && count($mongo_room["rm_messages"]) > 0) {
							$last_message = end($mongo_room["rm_messages"]);
							if (isset($last_message["mg_text"])) {
								$last_msg = $last_message["mg_text"];
								$temp_list[$key]["last_msg_preview"] = mb_substr($last_msg, 0, 20, "UTF-8");
								if (mb_strlen($last_msg, "UTF-8") > 20) {
									$temp_list[$key]["last_msg_preview"] .= "...";
								}
							}
							if (isset($last_message["mg_dt"])) {
								$last_msg_time = $last_message["mg_dt"];
								if (is_object($last_msg_time) && method_exists($last_msg_time, 'sec')) {
									$last_msg_time = date("Y-m-d H:i:s", $last_msg_time->sec);
								}
								$temp_list[$key]["last_msg_time"] = $this->formatChatTime($last_msg_time);
							}
						}

						// 미확인 메시지 카운트
						$unread_count = 0;
						if (isset($mongo_room["rm_messages"]) && is_array($mongo_room["rm_messages"])) {
							foreach ($mongo_room["rm_messages"] as $message) {
								if (isset($message["mg_reads"]) && is_array($message["mg_reads"])) {
									foreach ($message["mg_reads"] as $read) {
										if (
											isset($read["user_id"]) && $read["user_id"] == $user_id &&
											isset($read["read_yn"]) && $read["read_yn"] == "N"
										) {
											$unread_count++;
										}
									}
								}
							}
						}
						$temp_list[$key]["unread_count"] = $unread_count;
					}
				} catch (Exception $e) {
					$temp_list[$key]["last_msg_preview"] = "";
					$temp_list[$key]["last_msg_time"] = "";
					$temp_list[$key]["unread_count"] = 0;
				}
			} else {
				$temp_list[$key]["rm_idx"] = 0;
				$temp_list[$key]["last_msg_preview"] = "";
				$temp_list[$key]["last_msg_time"] = "";
				$temp_list[$key]["unread_count"] = 0;
			}

			$filtered_list[] = $temp_list[$key];
		}

		$list = $filtered_list;
		json_return("00", "조회성공", ["list" => $list]);
	}

	// 채팅방 나가기 처리
	function exit_room()
	{
		$rm_idx = input_post("rm_idx", "");
		$user_id = $this->user->getUserId();
		$mm_idx = $this->user->getUserIdx();

		if (empty($rm_idx) || empty($user_id)) {
			json_return("90", "필수 파라미터가 없습니다.", []);
			return;
		}

		// MongoDB 연결
		$uri = $this->mongo_uri;
		try {
			@$mongo = new Mongo($uri);
			$database = $mongo->{$this->mongo_db};
			$collection = $database->room;

			// 채팅방 조회
			$query = ['rm_idx' => (int)$rm_idx];
			$room = $collection->findOne($query);

			if (!$room) {
				json_return("90", "채팅방을 찾을 수 없습니다.", []);
				return;
			}

			// 사용자 정보 조회 (닉네임/이름 가져오기)
			$user_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);
			$user_name = !empty($user_info["mm_name"]) ? $user_info["mm_name"] : (!empty($user_info["mm_comp_name"]) ? $user_info["mm_comp_name"] : $user_id);

			// rm_users 배열에서 해당 사용자 찾아서 exit_yn, exit_dt 업데이트
			$rm_users = isset($room["rm_users"]) ? $room["rm_users"] : [];
			$user_found = false;
			$all_exited = true;

			foreach ($rm_users as $key => $room_user) {
				if (isset($room_user["user_id"]) && $room_user["user_id"] == $user_id) {
					// 사용자를 찾았음
					$user_found = true;

					// 이미 나간 사용자인 경우 (exit_yn='Y')
					if (isset($room_user["exit_yn"]) && $room_user["exit_yn"] == "Y") {
						// 이미 나간 상태로 간주하고 성공 응답
						json_return("00", "이미 나간 채팅방입니다.", [
							"rm_idx" => $rm_idx,
							"already_exited" => true
						]);
						return;
					}

					// 나가는 사용자: exit_yn='Y', exit_dt 설정
					$rm_users[$key]["exit_yn"] = "Y";
					$rm_users[$key]["exit_dt"] = new MongoDate();
				} else {
					// 다른 사용자: exit_yn이 없거나 'N'이면 all_exited = false
					if (!isset($room_user["exit_yn"]) || $room_user["exit_yn"] != "Y") {
						$all_exited = false;
					}
				}
			}

			// 사용자가 rm_users에 없거나 이미 나간 경우 처리
			if (!$user_found) {
				// rm_users가 비어있거나 사용자가 없는 경우, 이미 나간 것으로 간주하고 성공 처리
				if (empty($rm_users)) {
					// 이미 나간 상태로 간주하고 성공 응답
					json_return("00", "이미 나간 채팅방입니다.", [
						"rm_idx" => $rm_idx,
						"already_exited" => true
					]);
					return;
				}

				// rm_users에 사용자가 있지만 현재 사용자가 아닌 경우
				json_return("90", "채팅방에 참여한 사용자가 아닙니다.", []);
				return;
			}

			// MongoDB 업데이트: rm_users 배열 업데이트
			$update_data = ['$set' => ['rm_users' => $rm_users]];

			// 모든 사용자가 나갔으면 rm_status를 'CLOSED'로 설정
			if ($all_exited) {
				$update_data['$set']['rm_status'] = 'CLOSED';
			}

			$update_result = $collection->update($query, $update_data);

			// 시스템 메시지 생성 및 전송
			// 소켓을 통해 시스템 메시지 전송 (실시간 알림)
			// 소켓이 없으면 AJAX로 처리하거나 소켓 서버에 직접 요청
			$system_message = [
				"rm_idx" => (int)$rm_idx,
				"user_id" => $user_id,
				"chat_type" => "recruit",
				"mg_type" => "system_exit",
				"mg_text" => $user_name . "님이 채팅방을 나갔습니다.",
				"mg_req_dt" => "",
				"mg_img" => "",
				"mg_file" => "",
				"mg_imoti" => "",
				"mg_dt" => date("Y-m-d H:i:s"),
				"mg_reads" => []
			];

			// 나머지 사용자들에게 읽음 상태 설정
			foreach ($rm_users as $room_user) {
				if (isset($room_user["user_id"]) && $room_user["user_id"] != $user_id) {
					$system_message["mg_reads"][] = [
						"user_id" => $room_user["user_id"],
						"read_yn" => "N"
					];
				}
			}

			json_return("00", "채팅방을 나갔습니다.", [
				"rm_idx" => $rm_idx,
				"system_message" => $system_message,
				"all_exited" => $all_exited
			]);
		} catch (Exception $e) {
			json_return("90", "처리 중 오류가 발생했습니다: " . $e->getMessage(), []);
		}
	}

	// 채팅방 나가기 디버깅 로그 함수
	private function log_exit_room_debug($step, $message, $data = [])
	{
		// 기존 파일 로깅을 비활성화하여 불필요한 로그 기록을 중단
		// (기능 영향 없이 바로 반환)
		return;
	}

	// 배열 내 MongoDB 객체를 로그용으로 변환
	private function convert_array_for_log($array)
	{
		$result = [];
		foreach ($array as $key => $value) {
			if (is_object($value)) {
				if (method_exists($value, 'sec')) {
					$result[$key] = date("Y-m-d H:i:s", $value->sec);
				} else {
					$result[$key] = (string)$value;
				}
			} elseif (is_array($value)) {
				$result[$key] = $this->convert_array_for_log($value);
			} else {
				$result[$key] = $value;
			}
		}
		return $result;
	}
} //class
