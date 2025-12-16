<?php

//https://directsend.co.kr/index.php/address/api
$config['admin_menu'] = [
	//환경설정
    [
        'name'      => '환경설정',
        'action'    => '/admin/setting/basic/index',
        'children'  => 
        [
            [
                'name'      => '기본설정',
                'action'    => '/admin/setting/basic/index',
                'children'  => 
                [
                	[
		                'name'      => '싸이트 기본정보',
		                'action'    => '/admin/setting/basic/index',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '메타테그/파비콘 설정',
		                'action'    => '/admin/setting/basic/icon',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '약관/정책 설정',
		                'action'    =>  '/admin/setting/basic/policy',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '이용안내/회원탈퇴 정보 설정',
		                'action'    => '/admin/setting/basic/info',
		                'pages'     => [],
		            ],
                ],
            ],
            [
                'name'      => '운영관리 설정',
                'action'    => '/admin/setting/manage/menu',
                'children'  => 
                [
                	[
		                'name'      => '관리자 메뉴관리',
		                'action'    => '/admin/setting/manage/menu',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '운영자 계정관리',
		                'action'    => '/admin/setting/manage/user',
		                'pages'     => [
		                	[
				                'name'      => '운영자 계정상세',
				                'action'    => '/admin/setting/manage/user_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '권한별 메뉴관리',
		                'action'    => '/admin/setting/manage/grant_menu',
		                'pages'     => [
		                	[
				                'name'      => '권한별 메뉴리스트',
				                'action'    => '/admin/setting/manage/grant_menu_edit',
			            	]
		            ],
		            ],
		            [
		                'name'      => 'SNS연동 설정 ',
		                'action'    => '/admin/setting/manage/sns_api',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '자주쓰는 메뉴 설정',
		                'action'    => '/admin/setting/manage/fav_menu',
		                'pages'     => [],
		            ],
                ],
            ],
            [
                'name'      => 'SMS/이메일/알림톡 관리',
                'action'    => '/admin/setting/message/setting',
                'children'  => 
                [
               	 	[
		                'name'      => '발송 계정 관리',
		                'action'    => '/admin/setting/message/setting',
		                'pages'     => [],
		            ],
                	[
		                'name'      => 'SMS 정보 설정',
		                'action'    => '/admin/setting/message/setting_sms',
		                'pages'     => [
		                	[
				                'name'      => 'SMS 정보 수정',
				                'action'    => '/admin/setting/message/setting_sms_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '알림톡 정보 설정',
		                'action'    => '/admin/setting/message/setting_talk',
		                'pages'     => [
		                	[
				                'name'      => '알림톡 정보 수정',
				                'action'    => '/admin/setting/message/setting_talk_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '이메일 정보 설정',
		                'action'    => '/admin/setting/message/setting_email',
		                'pages'     => [],
		            ],
		            [
		                'name'      => 'SMS 발송내역',
		                'action'    => '/admin/setting/message/sms_history',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '알림톡 발송내역',
		                'action'    => '/admin/setting/message/talk_history',
		                'pages'     => [],
		            ],
		             [
		                'name'      => '이메일 발송내역',
		                'action'    => '/admin/setting/message/email_history',
		                'pages'     => [],
		            ],
                ],
            ],
            [
                'name'      => '결제 관련 설정',
                'action'    => '/admin/setting/pay/setting',
                'children'  => 
                [
                	[
		                'name'      => '결제 수단 노출설정',
		                'action'    =>  '/admin/setting/pay/setting',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '무통장 입금 은행 관리',
		                'action'    =>  '/admin/setting/pay/bank',
		                'pages'     => [],
		            ],
		            [
		                'name'      => 'PG 연동 관리',
		                'action'    => '/admin/setting/pay/setting_pg',
		                'pages'     => [
		                	[
				                'name'      => 'PG 연동 정보 수정',
				                'action'    => '/admin/setting/pay/setting_pg_detail',
			            	]
		                ],
		            ],
		            [
		                'name'      => '결제대기 자동주문취소 설정',
		                'action'    => '/admin/setting/pay/auto_cancel',
		                'pages'     => [],
		            ],
		            
                ],
            ],
            [
                'name'      => '상품/배송 설정',
                'action'    => '/admin/setting/goods/goods_setting',
                'children'  => 
                [
                	[
		                'name'      => '상품 설정',
		                'action'    => '/admin/setting/goods/goods_setting',
		                'pages'     => [],
		            ],
		           
		            [
		                'name'      => '도서산간 추가배송비 설정',
		                'action'    => '/admin/setting/goods/dlv_add_setting',
		                'pages'     => [
		                	[
				                'name'      => '도서산간 추가배송비 등록/수정',
				                'action'    => '/admin/setting/goods/dlv_add_setting_edit',
			            	]
		                ],
		            ],
                ],
            ],
            [
                'name'      => '키워드 관리',
                'action'    =>'/admin/setting/keyword/setting',
                'children'  => 
                [
                	[
		                'name'      => '검색 키워드',
		                'action'    => '/admin/setting/keyword/setting',
		                'pages'     => [],
		            ],
                ],
            ],
            [
                'name'      => '디자인 관리',
                'action'    =>  '/admin/setting/design/setting',
                'children'  => 
                [
                	[
		                'name'      => '스킨 관리',
		                'action'    => '/admin/setting/design/setting',
		                'pages'     => [],
		            ]
                ],
            ],
        ]
    ],
    
	//회원관리
    [
        'name'      => '회원관리',
        'action'    => '/admin/member/member_list',
        'children'  => 
        [
            [
                'name'      => '회원관리',
                'action'    => '/admin/member/member_list',
                'children'  => 
                [
                	[
		                'name'      => '회원관리',
		                'action'    => '/admin/member/member_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 정보 등록/수정',
				                'action'    => '/admin/member/member_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '회원가입 정책/항목 설정',
		                'action'    => '/admin/member/regist_info',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '회원 등급 관리',
		                'action'    => '/admin/member/grade_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 등급 등록/수정',
				                'action'    => '/admin/member/grade_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '회원 등급 정책/실행',
		                'action'    => '/admin/member/policy',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '휴면 회원 정책',
		                'action'    => '/admin/member/policy_dormant',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '휴면 회원 관리',
		                'action'    => '/admin/member/dormant_list',
		                'pages'     => [],
		            ],
                ],
            ],
        ]
    ],
    
	//업체관리 : 업체가 있는경우만 활성화
    [
        'name'      => '업체관리',
        'action'    => '/admin/vendor/vendor_list',
        'children'  => 
        [
            [
                'name'      => '판매자관리',
                'action'    => '/admin/vendor/vendor_list',
                'children'  => 
                [
                	[
		                'name'      => '판매자 리스트',
		                'action'    => '/admin/vendor/vendor_list',
		                'pages'     => [
		                	[
				                'name'      => '판매자 등록/수정',
				                'action'    => '/admin/vendor/vendor_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '입점 요청 리스트',
		                'action'    => '/admin/vendor/vendor_req_list',
		                'pages'     => [
		                	[
				                'name'      => '입점 요청 승인/반려',
				                'action'    => '/admin/vendor/vendor_req_edit',
			            	]
		                ],
		            ],
                ],
            ],
             [
                'name'      => '판매자 상품관리',
                'action'    => '/admin/vendor/vendor_goods_list',
                'children'  => 
                [
		            [
		                'name'      => '업체 상품관리',
		                'action'    => '/admin/vendor/vendor_goods_list',
		                'pages'     => [
		                	[
				                'name'      => '업체 상품 수정',
				                'action'    => '/admin/vendor/vendor_goods_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '업체 상품 승인 리스트',
		                'action'    => '/admin/vendor/vendor_goods_req_list',
		                'pages'     => [
		                	[
				                'name'      => '업체 상품 승인/반려',
				                'action'    => '/admin/vendor/vendor_goods_req_edit',
			            	]
		                ],
		            ],
                ],
            ],
            [
                'name'      => '문의/팝업/공지/FAQ',
                'action'    => '/admin/vendor/inquiry_list',
                'children'  => 
                [
		            [
		                'name'      => '업체 문의 관리',
		                'action'    => '/admin/vendor/inquiry_list',
		                'pages'     => [
		                	[
				                'name'      => '업체 문의 등록/수정',
				                'action'    => '/admin/vendor/inquiry_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '팝업관리',
		                'action'    => '/admin/vendor/popup_list',
		                'pages'     => [
		                	[
				                'name'      => '업체 팝업 등록/수정',
				                'action'    => '/admin/vendor/popup_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '업체 공지사항',
		                'action'    => '/admin/vendor/notice_list',
		                'pages'     => [
		                	[
				                'name'      => '업체 공지사항 등록/수정',
				                'action'    => '/admin/vendor/notice_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '업체 QnA',
		                'action'    => '/admin/vendor/qna_list',
		                'pages'     => [
		                	[
				                'name'      => '업체 QnA 등록/수정',
				                'action'    => '/admin/vendor/qna_edit',
			            	]
		                ],
		            ],
                ],
            ],
        ]
    ],
    
	//상품
    [
        'name'      => '상품',
        'action'    => '/admin/goods/goods_list',
        'children'  => 
        [
            [
                'name'      => '상품 관리',
                'action'    => '/admin/goods/goods_list',
                'children'  => 
                [
                	[
		                'name'      => '상품 목록',
		                'action'    => '/admin/goods/goods_list',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '상품등록',
		                'action'    => '/admin/goods/goods_edit',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '상품 아이콘 관리',
		                'action'    => '/admin/goods/icon_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 등급 등록/수정',
				                'action'    => '/admin/goods/icon_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '자주쓰는 옵션 관리',
		                'action'    => '/admin/goods/option_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 등급 등록/수정',
				                'action'    => '/admin/goods/option_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '배송관리',
		                'action'    => '/admin/goods/delivery_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 등급 등록/수정',
				                'action'    => '/admin/goods/delivery_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '이용안내 설정',
		                'action'    => '/admin/goods/detail_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 등급 등록/수정',
				                'action'    => '/admin/goods/detail_edit',
			            	]
		                ],
		            ],
                ],
            ],
            [
                'name'      => '브랜드/카테고리 관리',
                'action'    => '/admin/goods/brand_list',
                'children'  => 
                [
                	[
		                'name'      => '브랜드 관리',
		                'action'    => '/admin/goods/brand_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 등급 등록/수정',
				                'action'    => '/admin/goods/brand_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '카테고리 관리',
		                'action'    => '/admin/goods/category',
		                'pages'     => [],
		            ],
                ],
            ],
        ]
    ],
    
	//주문/배송
    [
        'name'      => '주문/배송',
        'action'    => '/admin/order/order_list',
        'children'  => 
        [
            [
                'name'      => '주문 관리',
                'action'    => '/admin/order/order_list',
                'children'  => 
                [
                	[
		                'name'      => '전체주문 목록',
		                'action'    => '/admin/order/order_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 등급 등록/수정',
				                'action'    => '/admin/order/order_edit',
			            	]
		                ],
		            ],
		             [
		                'name'      => '입금 대기 주문 목록',
		                'action'    => '/admin/order/wait_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 등급 등록/수정',
				                'action'    => '/admin/order/wait_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '배송 관리',
		                'action'    => '/admin/order/delivery_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 등급 등록/수정',
				                'action'    => '/admin/order/delivery_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '상품별 배송 관리',
		                'action'    => '/admin/order/prd_delivery_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 등급 등록/수정',
				                'action'    => '/admin/order/prd_delivery_edit',
			            	]
		                ],
		            ],
		             [
		                'name'      => '취소/교환/반품',
		                'action'    => '/admin/order/claim_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 등급 등록/수정',
				                'action'    => '/admin/order/claim_edit',
			            	]
		                ],
		            ],
		           
                ],
            ],
        ]
    ],
    
    //게시판
    [
        'name'      => '게시판',
        'action'    => '/admin/board/board_list',
        'children'  => 
        [
            [
                'name'      => '게시판 관리',
                'action'    => '/admin/board/board_list',
                'children'  => 
                [
                	
		            [
		                'name'      => '게시판 목록',
		                'action'    => '/admin/board/board_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 등급 등록/수정',
				                'action'    => '/admin/board/board_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '게시글 목록',
		                'action'    => '/admin/board/data_list',
		                'pages'     => [
		                	[
				                'name'      => '회원 등급 등록/수정',
				                'action'    => '/admin/board/data_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '게시판 금지어 관리',
		                'action'    => '/admin/board/keyword_setting',
		                'pages'     => [],
		            ]
                ],
            ],
        ]
    ],
    //싸이트관리
    [
        'name'      => '싸이트 운영',
        'action'    => '/admin/site/display_list',
        'children'  => 
        [
        	[
                'name'      => '상품 진열 관리',
                'action'    => '/admin/site/display_list',
                'children'  => 
                [
                	[
		                'name'      => '상품 진열 관리',
		                'action'    => '/admin/site/display_list',
		               	'pages'     => [
		                	[
				                'name'      => '상품 진열 관리 등록/수정',
				                'action'    => '/admin/site/display_edit',
			            	]
		                ],
		            ],
		            [
		                'name'      => '기획전 관리',
		                'action'    => '/admin/site/plan_list',
		                'pages'     => [
		                	[
				                'name'      => '기획전 등록/수정',
				                'action'    => '/admin/site/plan_edit',
			            	]
		                ],
		            ],
                ],
            ],
            [
                'name'      => '배너/팝업/일반 관리',
                'action'    => '/admin/site/banner_group_list',
                'children'  => 
                [
                	
                	[
		                'name'      => '배너 관리',
		                'action'    => '/admin/site/banner_group_list',
		                'pages'     => [
		                	[
				                'name'      => '배너그룹 등록/수정',
				                'action'    => '/admin/site/banner_group_edit',
			            	],
			            	[
				                'name'      => '배너 리스트',
				                'action'    => '/admin/site/banner_list',
			            	],
			            	[
				                'name'      => '배너 등록/수정',
				                'action'    => '/admin/site/banner_edit',
			            	],
		                ],
		            ],
		            [
		                'name'      => '팝업 관리',
		                'action'    => '/admin/site/popup_list',
		                'pages'     => [
		                	[
				                'name'      => '팝업 등록/수정',
				                'action'    => '/admin/site/popup_edit',
			            	],
		                ],
		            ],
		            [
		                'name'      => '일반 페이지 관리',
		                'action'    => '/admin/site/page_list',
		                'pages'     => [
		                	[
				                'name'      => '일반 페이지 등록/수정',
				                'action'    => '/admin/site/page_edit',
			            	],
		                ],
		            ]
                ],
            ],
            [
                'name'      => '게시글/문의/후기',
                'action'    => '/admin/site/review_list',
                'children'  => 
                [
                	[
		                'name'      => '상품후기 관리',
		                'action'    => '/admin/site/review_list',
		                'pages'     => [
		                	[
				                'name'      => '상품후기 등록/수정',
				                'action'    => '/admin/site/review_edit',
			            	],
		                ],
		            ],
		            [
		                'name'      => '상품문의 관리',
		                'action'    => '/admin/site/goods_inquiry_list',
		                'pages'     => [
		                	[
				                'name'      => '상품문의 등록/수정',
				                'action'    => '/admin/site/goods_inquiry_edit',
			            	],
		                ],
		            ],
		            [
		                'name'      => '이벤트',
		                'action'    => '/admin/site/event_list',
		                'pages'     => [
		                	[
				                'name'      => '이벤트 등록/수정',
				                'action'    => '/admin/site/event_edit',
			            	],
		                ],
		            ],
		            
		            [
		                'name'      => '공지사항',
		                'action'    => '/admin/site/notice_list',
		                'pages'     => [
		                	[
				                'name'      => '공지사항 등록/수정',
				                'action'    => '/admin/site/notice_edit',
			            	],
		                ],
		            ],
		            [
		                'name'      => 'QnA',
		                'action'    => '/admin/site/qna_list',
		                'pages'     => [
		                	[
				                'name'      => 'QnA 등록/수정',
				                'action'    => '/admin/site/qna_edit',
			            	],
		                ],
		            ],
		            [
		                'name'      => '1:1문의',
		                'action'    => '/admin/site/inquiry_list',
		                'pages'     => [
		                	[
				                'name'      => '1:1문의 등록/수정',
				                'action'    => '/admin/site/inquiry_edit',
			            	],
		                ],
		            ],
		            [
		                'name'      => '제휴문의',
		                'action'    => '/admin/site/ali_list',
		                'pages'     => [
		                	[
				                'name'      => '제휴문의등록/수정',
				                'action'    => '/admin/site/ali_edit',
			            	],
		                ],
		            ],
                ],
            ],
            [
                'name'      => '적립금/쿠폰 관리',
                'action'    => '/admin/site/setting_deposit',
                'children'  => 
                [
                	[
		                'name'      => '적립금 발급 설정',
		                'action'    => '/admin/site/setting_deposit',
		                'pages'     => [
		                	[
				                'name'      => '적립금 설정  등록/수정',
				                'action'    => '/admin/site/setting_deposit_edit',
			            	],
		                ],
		            ],
		            [
		                'name'      => '적립금 발급 리스트',
		                'action'    => '/admin/site/deposit_list',
		                'pages'     => [
		                	[
				                'name'      => '적립금  등록',
				                'action'    => '/admin/site/deposit_edit',
			            	],
		                ],
		            ],
		             [
		                'name'      => '쿠폰 발급 설정',
		                'action'    => '/admin/site/setting_coupon',
		                'pages'     => [
		                	[
				                'name'      => '쿠폰 발급  등록/수정',
				                'action'    => '/admin/site/setting_coupon_edit',
			            	],
			            	[
				                'name'      => '쿠폰 발급  등록/수정',
				                'action'    => '/admin/site/setting_coupon_list',
			            	],
		                ],
		            ],
		             [
		                'name'      => '쿠폰 발급 리스트',
		                'action'    => '/admin/site/coupon_list',
		                'pages'     => [
		                	[
				                'name'      => '쿠폰  등록/수정',
				                'action'    => '/admin/site/coupon_edit',
			            	],
		                ],
		            ],
                ],
            ],
        ]
    ],
    //로그 분석
    [
        'name'      => '로그분석',
        'action'    => '/admin/log/visit',
        'children'  => 
        [
        	[
                'name'      => '방문자 분석',
                'action'    => '/admin/log/visit',
                'children'  => 
                [
                	[
		                'name'      => '방문자 분석',
		                'action'    => '/admin/log/visit',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '방문자 접속분석',
		                'action'    => '/admin/log/visit_connect',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '방문자 환경분석',
		                'action'    => '/admin/log/visit_envi',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '방문자 상세분석',
		                'action'    => '/admin/log/visit_list',
		                'pages'     => [],
		            ],
                ],
            ],
        	[
                'name'      => '회원분석',
                'action'    => '/admin/log/member_join',
                'children'  => 
                [
                	[
		                'name'      => '회원  가입 분석',
		                'action'    => '/admin/log/member_join',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '회원 적립금 분석',
		                'action'    => '/admin/log/member_deposit',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '회원 쿠폰 분석',
		                'action'    => '/admin/log/member_coupon',
		                'pages'     => [],
		            ],
                ],
            ],
            [
                'name'      => '상품분석',
                'action'    => '/admin/log/goods_category',
                'children'  => 
                [
                	[
		                'name'      => '카테고리별 판매순위',
		                'action'    => '/admin/log/goods_category',
		                'pages'     => [],
		            ],
                	[
		                'name'      => '상품 판매상품 순위',
		                'action'    => '/admin/log/goods_order',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '장바구니 상품 순위',
		                'action'    => '/admin/log/goods_cart',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '찜 상품 순위',
		                'action'    => '/admin/log/goods_fav',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '접속 상품 순위',
		                'action'    => '/admin/log/goods_detail',
		                'pages'     => [],
		            ]
                ],
            ],
            [
                'name'      => '매출분석',
                'action'    => '/admin/log/order_all',
                'children'  => 
                [
                	[
		                'name'      => '매출분석',
		                'action'    => '/admin/log/order_all',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '카테고리별 매출',
		                'action'    => '/admin/log/order_paymethod',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '결제 수단별 매출',
		                'action'    => '/admin/log/order_paymethod',
		                'pages'     => [],
		            ]
                ],
            ],
            [
                'name'      => '업체분석',
                'action'    => '/admin/log/vendor_goods_ranking',
                'children'  => 
                [
                	[
		                'name'      => '업체별 상품 순위',
		                'action'    => '/admin/log/vendor_goods_ranking',
		                'pages'     => [],
		            ],
		            [
		                'name'      => '업체별 매출 순위',
		                'action'    => '/admin/log/vendor_order_ranking',
		                'pages'     => [],
		            ]
                ],
            ],
        ]
    ]
];

//메뉴