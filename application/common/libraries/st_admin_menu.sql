-- phpMyAdmin SQL Dump
-- version 4.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- 생성 시간: 26-06-24 04:48
-- 서버 버전: 5.5.68-MariaDB
-- PHP 버전: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 데이터베이스: `nowapk`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `st_admin_menu`
--

CREATE TABLE IF NOT EXISTS `st_admin_menu` (
  `am_type` enum('admin','store') NOT NULL DEFAULT 'admin' COMMENT '싸이트유형',
  `am_idx` int(11) NOT NULL COMMENT '기본키',
  `am_parent` int(11) NOT NULL COMMENT '부모키값',
  `am_name` varchar(30) NOT NULL COMMENT '메뉴명',
  `am_url` varchar(100) NOT NULL COMMENT '메뉴URL',
  `am_depth` int(11) NOT NULL COMMENT '차수',
  `am_status` enum('Y','N') NOT NULL COMMENT '메뉴상태',
  `am_sort` int(11) NOT NULL COMMENT '정렬순서',
  `am_child_list` varchar(2000) NOT NULL COMMENT '서뷰메뉴리스트',
  `am_hidden` enum('Y','N') NOT NULL DEFAULT 'N',
  `reg_id` varchar(30) NOT NULL COMMENT '등록 아이디',
  `up_id` varchar(30) NOT NULL COMMENT '수정 아이디',
  `reg_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
  `up_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '수정일시'
) ENGINE=InnoDB AUTO_INCREMENT=512 DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `st_admin_menu`
--

INSERT INTO `st_admin_menu` (`am_type`, `am_idx`, `am_parent`, `am_name`, `am_url`, `am_depth`, `am_status`, `am_sort`, `am_child_list`, `am_hidden`, `reg_id`, `up_id`, `reg_dt`, `up_dt`) VALUES
('admin', 44, 0, '환경설정', '/admin/setting/manage/user', 1, 'Y', 0, '', 'N', '0', '0', '2021-02-16 16:34:40', '2021-02-16 16:34:40'),
('admin', 45, 0, '회원관리', '/admin/member/member_list', 1, 'Y', 44, '', 'N', '0', '0', '2021-02-16 16:34:57', '2021-02-16 16:34:57'),
('admin', 46, 0, '업체관리', '/admin/vendor/vendor_list', 1, 'N', 46, '', 'Y', '0', '0', '2021-02-16 16:35:08', '2021-02-16 16:35:08'),
('admin', 47, 0, '중고거래 관리', '/admin/trade/trade_list', 1, 'Y', 48, '', 'N', '0', '0', '2021-02-16 16:35:19', '2021-02-16 16:35:19'),
('admin', 50, 0, '결제/포인트 관리', '/admin/order/order_list', 1, 'Y', 434, '', 'N', '0', '0', '2021-02-16 16:35:50', '2021-02-16 16:35:50'),
('admin', 52, 44, '기본설정', '/admin/setting/manage/user', 2, 'Y', 52, '', 'N', '0', '0', '2021-02-16 16:36:33', '2021-02-16 16:36:33'),
('admin', 55, 466, '업무구분 설정', '/admin/setting/basic/config', 3, 'Y', 495, '업무구분 등록/수정:/admin/setting/basic/config_edit', 'N', '0', '0', '2021-02-16 16:38:19', '2021-02-16 16:38:19'),
('admin', 64, 63, '발송 계정 관리', '/admin/setting/message/setting', 3, 'Y', 64, '', 'N', '0', '0', '2021-02-16 16:43:22', '2021-02-16 16:43:22'),
('admin', 65, 63, 'SMS 정보 설정', '/admin/setting/message/setting_sms', 3, 'Y', 65, 'SMS 정보 수정:/admin/setting/message/setting_sms_edit', 'N', '0', '0', '2021-02-16 16:43:36', '2021-02-16 16:43:36'),
('admin', 66, 63, '알림톡 정보 설정', '/admin/setting/message/setting_talk', 3, 'Y', 66, '알림톡 정보 수정:/admin/setting/message/setting_talk_edit', 'N', '0', '0', '2021-02-16 16:43:52', '2021-02-16 16:43:52'),
('admin', 67, 63, '이메일 정보 설정', '/admin/setting/message/setting_email', 3, 'Y', 67, '이메일 정보 수정:/admin/setting/message/setting_email_edit', 'N', '0', '0', '2021-02-16 16:44:04', '2021-02-16 16:44:04'),
('admin', 68, 63, 'SMS 발송내역', '/admin/setting/message/sms_history', 3, 'Y', 68, 'SMS 발송내역 내용:/admin/setting/message/sms_history_view', 'N', '0', '0', '2021-02-16 16:44:12', '2021-02-16 16:44:12'),
('admin', 69, 63, '알림톡 발송내역', '/admin/setting/message/talk_history', 3, 'Y', 69, '알림톡 발송내역 내용:/admin/setting/message/talk_history_view', 'N', '0', '0', '2021-02-16 16:44:23', '2021-02-16 16:44:23'),
('admin', 70, 63, '이메일 발송내역', '/admin/setting/message/email_history', 3, 'Y', 70, '이메일 발송내역 내용:/admin/setting/message/email_history_view', 'N', '0', '0', '2021-02-16 16:44:34', '2021-02-16 16:44:34'),
('admin', 72, 71, '결제 수단 노출설정', '/admin/setting/pay/setting', 3, 'Y', 72, '', 'Y', '0', '0', '2021-02-16 16:45:00', '2021-02-16 16:45:00'),
('admin', 73, 71, '무통장 입금 은행 관리', '/admin/setting/pay/bank', 3, 'Y', 73, '', 'N', '0', '0', '2021-02-16 16:45:09', '2021-02-16 16:45:09'),
('admin', 74, 71, 'PG 연동 관리', '/admin/setting/pay/setting_pg', 3, 'Y', 74, 'PG 연동 정보 수정:/admin/setting/pay/setting_pg_detail', 'N', '0', '0', '2021-02-16 16:45:24', '2021-02-21 14:59:32'),
('admin', 75, 71, '결제대기 자동주문취소 설정', '/admin/setting/pay/auto_cancel', 3, 'Y', 75, '', 'N', '0', '0', '2021-02-16 16:45:34', '2021-02-16 16:45:34'),
('admin', 77, 76, '상품 설정', '/admin/setting/goods/goods_setting', 3, 'Y', 77, '', 'N', '0', '0', '2021-02-16 16:45:55', '2021-02-16 16:45:55'),
('admin', 78, 76, '도서산간 추가배송비 설정', '/admin/setting/goods/dlv_add_setting', 3, 'Y', 78, '도서산간 추가배송비 등록/수정:/admin/setting/goods/dlv_add_setting_edit', 'N', '0', '0', '2021-02-16 16:46:10', '2021-02-16 16:46:10'),
('admin', 79, 44, '키워드 관리', '/admin/setting/keyword/setting', 2, 'Y', 79, '', 'Y', '0', '0', '2021-02-16 16:46:41', '2021-02-16 16:46:41'),
('admin', 80, 79, '검색 키워드', '/admin/setting/keyword/setting', 3, 'Y', 80, '', 'Y', '0', '0', '2021-02-16 16:47:00', '2021-02-16 16:47:00'),
('admin', 82, 81, '스킨 관리', '/admin/setting/design/setting', 3, 'Y', 82, '', 'N', '0', '0', '2021-02-16 16:47:22', '2021-02-16 16:47:22'),
('admin', 83, 45, '회원관리', '/admin/member/member_list', 2, 'Y', 83, '', 'N', '0', '0', '2021-02-16 16:47:34', '2021-02-16 16:47:34'),
('admin', 84, 83, '개인회원 리스트', '/admin/member/member_list', 3, 'Y', 84, '개인회원 정보 등록/수정:/admin/member/member_edit', 'N', '0', '0', '2021-02-16 16:47:58', '2021-02-16 16:47:58'),
('admin', 87, 83, '회원 등급 정책/실행', '/admin/member/policy', 3, 'Y', 87, '', 'Y', '0', '0', '2021-02-16 16:48:34', '2021-02-16 16:48:34'),
('admin', 91, 46, '판매자관리', '/admin/vendor/vendor_list', 2, 'Y', 91, '', 'N', '0', '0', '2021-02-16 16:50:59', '2021-02-16 16:50:59'),
('admin', 92, 91, '판매자 리스트', '/admin/vendor/vendor_list', 3, 'Y', 92, '판매자 등록/수정:/admin/vendor/vendor_edit', 'N', '0', '0', '2021-02-16 16:51:18', '2021-02-16 16:51:18'),
('admin', 93, 91, '입점 요청 리스트', '/admin/vendor/vendor_req_list', 3, 'Y', 93, '입점 요청 승인/반려:/admin/vendor/vendor_req_edit', 'N', '0', '0', '2021-02-16 16:51:33', '2021-02-16 16:51:33'),
('admin', 95, 46, '판매자 상품관리', '/admin/vendor/vendor_goods_list', 2, 'Y', 95, '', 'N', '0', '0', '2021-02-16 16:55:20', '2021-02-16 16:55:20'),
('admin', 96, 95, '업체 상품관리', '/admin/vendor/vendor_goods_list', 3, 'Y', 96, '업체 상품 수정:/admin/vendor/vendor_goods_edit', 'N', '0', '0', '2021-02-16 16:55:36', '2021-02-16 16:55:36'),
('admin', 97, 95, '업체 상품 승인 리스트', '/admin/vendor/vendor_goods_req_list', 3, 'Y', 97, '업체 상품 승인/반려:/admin/vendor/vendor_goods_req_edit', 'N', '0', '0', '2021-02-16 16:55:50', '2021-02-16 16:55:50'),
('admin', 98, 46, '문의/팝업/공지/FAQ', '/admin/vendor/inquiry_list', 2, 'Y', 98, '', 'N', '0', '0', '2021-02-16 16:55:59', '2021-02-16 16:55:59'),
('admin', 99, 98, '업체 문의 관리', '/admin/vendor/inquiry_list', 3, 'Y', 99, '업체 문의 등록/수정:/admin/vendor/inquiry_edit', 'N', '0', '0', '2021-02-16 16:56:18', '2021-02-16 16:56:18'),
('admin', 100, 98, '팝업관리', '/admin/vendor/popup_list', 3, 'Y', 100, '업체 팝업 등록/수정:/admin/vendor/popup_edit', 'N', '0', '0', '2021-02-16 16:56:37', '2021-02-16 16:56:37'),
('admin', 101, 98, '업체 공지사항', '/admin/vendor/notice_list', 3, 'Y', 101, '업체 공지사항 등록/수정:/admin/vendor/notice_edit', 'N', '0', '0', '2021-02-16 16:56:52', '2021-02-16 16:56:52'),
('admin', 102, 98, '업체 QnA', '/admin/vendor/qna_list', 3, 'Y', 102, '업체 QnA 등록/수정:/admin/vendor/qna_edit', 'N', '0', '0', '2021-02-16 16:57:11', '2021-02-16 16:57:11'),
('admin', 103, 47, '중고거래 관리', '/admin/trade/trade_list', 2, 'Y', 103, '', 'N', '0', '0', '2021-02-16 16:57:24', '2021-02-16 16:57:24'),
('admin', 104, 103, '중고거래 리스트', '/admin/trade/trade_list', 3, 'Y', 104, '중고거래 상세정보:/admin/trade/trade_edit', 'N', '0', '0', '2021-02-16 16:57:46', '2021-02-16 16:57:46'),
('admin', 106, 103, '상품 아이콘 관리', '/admin/goods/icon_list', 3, 'Y', 106, '상품 아이콘 등록/수정:/admin/goods/icon_edit', 'Y', '0', '0', '2021-02-16 16:58:31', '2021-02-16 16:58:31'),
('admin', 111, 110, '브랜드 관리', '/admin/goods/brand_list', 3, 'Y', 111, '브랜드 등록/수정:/admin/goods/brand_edit', 'N', '0', '0', '2021-02-16 17:00:04', '2021-02-16 17:00:04'),
('admin', 112, 110, '카테고리 관리', '/admin/goods/category', 3, 'Y', 112, '', 'N', '0', '0', '2021-02-16 17:00:14', '2021-02-16 17:00:14'),
('admin', 113, 48, '주문 관리', '/admin/order/order_list', 2, 'Y', 113, '', 'N', '0', '0', '2021-02-16 17:00:27', '2021-02-16 17:00:27'),
('admin', 114, 113, '주문처리총괄현황', '/admin/order/order_list', 3, 'Y', 116, '주문 등록/수정:/admin/order/order_edit', 'N', '0', '0', '2021-02-16 17:00:58', '2021-02-16 17:00:58'),
('admin', 115, 113, '주문관리', '/admin/order/wait_list', 3, 'Y', 114, '주문 등록/수정:/admin/order/wait_edit', 'N', '0', '0', '2021-02-16 17:01:22', '2021-02-16 17:01:22'),
('admin', 116, 113, '배송 관리', '/admin/order/delivery_list', 3, 'Y', 115, '주문 등록/수정:/admin/order/delivery_edit', 'N', '0', '0', '2021-02-16 17:01:42', '2021-02-16 17:01:42'),
('admin', 117, 113, '상품별 배송 관리', '/admin/order/prd_delivery_list', 3, 'Y', 117, '주문 등록/수정:/admin/order/prd_delivery_edit', 'N', '0', '0', '2021-02-16 17:02:02', '2021-02-16 17:02:02'),
('admin', 118, 113, '취소/교환/반품', '/admin/order/claim_list', 3, 'Y', 118, '주문 등록/수정:/admin/order/claim_edit', 'N', '0', '0', '2021-02-16 17:02:22', '2021-02-16 17:02:22'),
('admin', 119, 49, '게시판 관리', '/admin/board/table_list', 2, 'Y', 119, '', 'N', '0', '0', '2021-02-16 17:02:43', '2021-02-16 17:02:43'),
('admin', 120, 119, '게시판 리스트', '/admin/board/table_list', 3, 'Y', 120, '게시판 등록/수정:/admin/board/table_edit', 'N', '0', '0', '2021-02-16 17:03:19', '2021-02-16 17:03:19'),
('admin', 121, 119, '게시글 리스트', '/admin/board/board_list', 3, 'Y', 121, '게시글 등록/수정:/admin/board/board_edit', 'N', '0', '0', '2021-02-16 17:03:40', '2021-02-16 17:03:40'),
('admin', 122, 119, '게시판 금지어 관리', '/admin/board/keyword_setting', 3, 'Y', 122, '', 'N', '0', '0', '2021-02-16 17:03:49', '2021-02-16 17:03:49'),
('admin', 124, 123, '상품 진열 관리', '/admin/site/display_list', 3, 'Y', 124, '상품 진열 관리 등록/수정:/admin/site/display_edit', 'N', '0', '0', '2021-02-16 17:04:48', '2021-02-16 17:04:48'),
('admin', 125, 123, '이벤트 전시 관리', '/admin/site/plan_list', 3, 'Y', 125, '이벤트 전시 등록/수정:/admin/site/plan_edit#이벤트 전시 카테고리 등록/수정:/admin/site/plan_category', 'N', '0', '0', '2021-02-16 17:05:04', '2021-02-16 17:05:04'),
('admin', 126, 50, '결제/포인트 관리', '/admin/order/order_list', 2, 'Y', 126, '', 'N', '0', '0', '2021-02-16 17:05:23', '2021-02-16 17:05:23'),
('admin', 127, 126, '결제 리스트', '/admin/order/order_list', 3, 'Y', 127, '결제 상세정보:/admin/order/order_edit', 'N', '0', '0', '2021-02-16 17:05:56', '2021-02-16 17:05:56'),
('admin', 131, 130, '상품후기 관리', '/admin/site/review_list', 3, 'Y', 131, '상품후기 등록/수정:/admin/site/review_edit', 'Y', '0', '0', '2021-02-16 17:07:06', '2021-02-16 17:07:06'),
('admin', 132, 130, '커뮤니티', '/admin/site/community_list', 3, 'Y', 133, '커뮤니티 등록/수정:/admin/site/community_edit', 'N', '0', '0', '2021-02-16 17:07:20', '2021-02-16 17:07:20'),
('admin', 133, 130, '이벤트', '/admin/site/event_list', 3, 'Y', 134, '이벤트 등록/수정:/admin/site/event_edit', 'N', '0', '0', '2021-02-16 17:07:36', '2021-02-16 17:07:36'),
('admin', 134, 130, '공지사항', '/admin/site/notice_list', 3, 'Y', 132, '공지사항 등록/수정:/admin/site/notice_edit', 'N', '0', '0', '2021-02-16 17:07:50', '2021-02-16 17:07:50'),
('admin', 135, 130, 'FAQ', '/admin/site/qna_list', 3, 'Y', 135, 'QnA 등록/수정:/admin/site/qna_edit', 'N', '0', '0', '2021-02-16 17:08:05', '2021-02-16 17:08:05'),
('admin', 136, 130, '나의문의', '/admin/site/inquiry_list', 3, 'Y', 136, '문의 등록/수정:/admin/site/inquiry_edit', 'N', '0', '0', '2021-02-16 17:08:20', '2021-02-16 17:08:20'),
('admin', 137, 130, '제휴문의', '/admin/site/ali_list', 3, 'Y', 137, '제휴문의등록/수정:/admin/site/ali_edit', 'Y', '0', '0', '2021-02-16 17:08:34', '2021-02-16 17:08:34'),
('admin', 139, 138, '쿠폰 발급 설정', '/admin/site/setting_deposit', 3, 'Y', 139, '쿠폰 설정  등록/수정:/admin/site/setting_deposit_edit', 'Y', '0', '0', '2021-02-16 17:09:08', '2021-02-16 17:09:08'),
('admin', 140, 138, '인센티브', '/admin/site/deposit_list', 3, 'Y', 140, '쿠폰 등록:/admin/site/deposit_edit', 'N', '0', '0', '2021-02-16 17:09:24', '2021-02-16 17:09:24'),
('admin', 141, 138, '쿠폰 발급 설정', '/admin/site/setting_coupon', 3, 'Y', 141, '쿠폰 발급  등록/수정:/admin/site/setting_coupon_edit#쿠폰 발급  리스트:/admin/site/setting_coupon_list', 'Y', '0', '0', '2021-02-16 17:09:49', '2021-02-16 17:10:25'),
('admin', 142, 138, '쿠폰 발급 리스트', '/admin/site/coupon_list', 3, 'Y', 142, '쿠폰  등록/수정:/admin/site/coupon_edit', 'Y', '0', '0', '2021-02-16 17:10:08', '2021-02-16 17:10:08'),
('admin', 149, 148, '회원  가입 분석', '/admin/log/member_join', 3, 'Y', 149, '', 'N', '0', '0', '2021-02-16 17:11:42', '2021-02-16 17:11:42'),
('admin', 150, 148, '회원 적립금 분석', '/admin/log/member_deposit', 3, 'Y', 150, '', 'N', '0', '0', '2021-02-16 17:11:50', '2021-02-16 17:11:50'),
('admin', 151, 148, '회원 쿠폰 분석', '/admin/log/member_coupon', 3, 'Y', 151, '', 'N', '0', '0', '2021-02-16 17:11:57', '2021-02-16 17:11:57'),
('admin', 153, 152, '카테고리별 판매순위', '/admin/log/goods_category', 3, 'Y', 153, '', 'N', '0', '0', '2021-02-16 17:12:22', '2021-02-16 17:12:22'),
('admin', 154, 152, '상품 판매상품 순위', '/admin/log/goods_order', 3, 'Y', 156, '', 'N', '0', '0', '2021-02-16 17:12:30', '2021-02-16 17:12:30'),
('admin', 156, 152, '찜 상품 순위', '/admin/log/goods_fav', 3, 'Y', 165, '', 'N', '0', '0', '2021-02-16 17:12:46', '2021-02-16 17:12:46'),
('admin', 159, 158, '매출분석', '/admin/log/order_all', 3, 'Y', 159, '', 'N', '0', '0', '2021-02-16 17:13:15', '2021-02-16 17:13:15'),
('admin', 161, 158, '결제 수단별 매출', '/admin/log/order_paymethod', 3, 'Y', 161, '', 'N', '0', '0', '2021-02-16 17:13:36', '2021-02-16 17:13:36'),
('admin', 162, 51, '업체분석', '/admin/log/vendor_goods_ranking', 2, 'N', 162, '', 'Y', '0', '0', '2021-02-16 17:13:55', '2021-02-16 17:13:55'),
('admin', 163, 162, '업체별 상품 순위', '/admin/log/vendor_goods_ranking', 3, 'N', 163, '', 'Y', '0', '0', '2021-02-16 17:14:04', '2021-02-16 17:14:04'),
('admin', 164, 162, '업체별 매출 순위', '/admin/log/vendor_order_ranking', 3, 'N', 164, '', 'Y', '0', '0', '2021-02-16 17:14:12', '2021-02-16 17:14:12'),
('admin', 165, 152, '브랜드별 판매순위', '/admin/log/goods_brand', 3, 'Y', 154, '', 'N', '', '', '2021-07-28 15:00:03', '0000-00-00 00:00:00'),
('admin', 166, 83, '기업회원 리스트', '/admin/member/comp_list', 3, 'Y', 85, '기업회원 정보 등록/수정:/admin/member/comp_edit', 'N', '', '', '2021-08-03 15:28:17', '0000-00-00 00:00:00'),
('admin', 168, 113, '견적관리', '/admin/order/estimate_list', 3, 'Y', 452, '견적관리 등록/수정:/admin/order/estimate_edit', 'N', '', '', '2021-08-10 07:51:14', '0000-00-00 00:00:00'),
('admin', 170, 169, '통계관리', '/admin/sales/sales_list', 2, 'Y', 170, '', 'N', '', '', '2021-08-11 08:15:02', '0000-00-00 00:00:00'),
('admin', 171, 170, '기간별 거래현황', '/admin/sales/sales_list', 3, 'Y', 171, '', 'N', '', '', '2021-08-12 05:13:54', '0000-00-00 00:00:00'),
('admin', 172, 170, '일일주문현황', '/admin/sales/daily_list', 3, 'Y', 172, '', 'N', '', '', '2021-08-12 05:14:12', '0000-00-00 00:00:00'),
('admin', 173, 170, '거래처별 주문현황', '/admin/sales/seller_list', 3, 'Y', 173, '거래처별 주문현황  상세:/admin/sales/seller_detail_list', 'N', '', '', '2021-08-12 05:14:34', '0000-00-00 00:00:00'),
('admin', 174, 130, '나의신고내역', '/admin/site/report_list', 3, 'Y', 174, '견적문의 답변:/admin/site/report_edit', 'N', '', '', '2021-08-19 05:58:58', '0000-00-00 00:00:00'),
('admin', 175, 130, '인증서 다운로드', '/admin/site/certi_list', 3, 'Y', 175, '인증서 다운로드:/admin/site/certi_edit', 'Y', '', '', '2021-08-19 05:59:26', '0000-00-00 00:00:00'),
('admin', 176, 138, '상품할인쿠폰 관리', '/admin/site/prd_coupon', 3, 'Y', 176, '상품할인쿠폰 등록/수정:/admin/site/prd_coupon_edit', 'N', '', '', '2021-08-24 05:26:05', '0000-00-00 00:00:00'),
('admin', 435, 434, '상점 관리', '/admin/setting/store/admin_store_list', 2, 'Y', 435, '', 'N', '', '', '2021-08-30 14:39:38', '0000-00-00 00:00:00'),
('admin', 436, 435, '상점관리', '/admin/setting/store/admin_store_list', 3, 'Y', 436, '상점관리 상세:/admin/setting/store/admin_store_edit', 'N', '', '', '2021-08-30 14:40:04', '0000-00-00 00:00:00'),
('admin', 437, 435, '결제관리', '/admin/setting/store/admin_store_pay_list', 3, 'Y', 438, '결제관리 상세:/admin/setting/store/admin_store_pay_edit', 'N', '', '', '2021-08-30 14:42:26', '0000-00-00 00:00:00'),
('admin', 438, 435, '충전내역 관리', '/admin/setting/store/admin_store_charge_list', 3, 'Y', 439, '충전금 관리 상세:/admin/setting/store/admin_store_charge_edit', 'N', '', '', '2021-08-30 14:44:32', '0000-00-00 00:00:00'),
('admin', 439, 435, '충전금 사용내역', '/admin/setting/store/admin_store_charge_history', 3, 'Y', 458, '충전금 사용 내역 상세:/admin/setting/store/admin_store_charge_history_edit', 'N', '', '', '2021-08-30 14:45:26', '0000-00-00 00:00:00'),
('admin', 442, 441, '메인 배너 관리', '/admin/setting/store/auto_main_banner', 3, 'Y', 442, '메인 배너 관리 등록/수정:/admin/setting/store/auto_main_banner_edit', 'N', '', '', '2021-10-13 07:46:31', '0000-00-00 00:00:00'),
('admin', 443, 434, '상점운영', '/admin/setting/store/banner_list', 2, 'Y', 443, '', 'N', '', '', '2021-10-13 13:45:43', '0000-00-00 00:00:00'),
('admin', 444, 443, '메인배너 관리', '/admin/setting/store/banner_list', 3, 'Y', 444, '메인배너 관리 등록/수정:/admin/setting/store/banner_edit', 'N', '', '', '2021-10-13 13:46:16', '0000-00-00 00:00:00'),
('admin', 445, 443, '인증서 다운로드', '/admin/setting/store/certi_list', 3, 'Y', 445, '인증서 다운로드 등록/수정:/admin/setting/store/certi_edit', 'Y', '', '', '2021-10-13 13:47:09', '0000-00-00 00:00:00'),
('admin', 446, 443, '공지사항', '/admin/setting/store/notice_list', 3, 'Y', 446, '공지사항 등록/수정:/admin/setting/store/notice_edit', 'N', '', '', '2021-10-13 13:47:43', '0000-00-00 00:00:00'),
('admin', 451, 71, '포인트 설정', '/admin/setting/pay/setting_point', 3, 'Y', 451, '', 'N', '', '', '2021-11-17 01:53:24', '0000-00-00 00:00:00'),
('admin', 452, 113, '작업복로고/건설안전인쇄', '/admin/order/file_list', 3, 'Y', 168, '', 'N', '', '', '2021-11-17 08:21:07', '0000-00-00 00:00:00'),
('admin', 454, 138, '쿠폰관리', '/admin/site/setting_coupon', 3, 'Y', 454, '쿠폰관리 등록/수정:/admin/site/setting_coupon_edit', 'N', '', '', '2021-11-22 07:46:29', '0000-00-00 00:00:00'),
('admin', 457, 138, '쿠폰 발급 리스트', '/admin/site/coupon_list', 3, 'Y', 457, '쿠폰 발급 등록/수정:/admin/site/coupon_edit', 'N', '', '', '2021-11-22 14:22:35', '0000-00-00 00:00:00'),
('admin', 458, 435, '권한&금액 설정', '/admin/setting/store/admin_store_setting_list', 3, 'Y', 437, '결제금액설정 추사/수정:/admin/setting/store/admin_store_setting_edit', 'N', '', '', '2022-01-26 05:29:15', '0000-00-00 00:00:00'),
('admin', 462, 461, '즐겨찾기 관리', '/admin/fav/fav_list', 2, 'Y', 0, '', 'N', '', '', '2022-11-04 09:52:31', '0000-00-00 00:00:00'),
('admin', 463, 462, '결제관리', '/admin/order/order_list', 3, 'Y', 0, '', 'N', '', '', '2022-11-04 09:54:43', '0000-00-00 00:00:00'),
('admin', 464, 462, '환불관리', 'admin/order/refund_list', 3, 'Y', 464, '', 'N', '', '', '2022-11-04 09:55:35', '0000-00-00 00:00:00'),
('admin', 465, 0, '사이트 관리', '/admin/site/banner_group_list', 1, 'Y', 472, '', 'N', '', '', '2023-02-09 07:09:06', '0000-00-00 00:00:00'),
('admin', 466, 465, '운영 관리', '/admin/site/banner_group_list', 2, 'Y', 466, '', 'N', '', '', '2023-02-09 07:09:21', '0000-00-00 00:00:00'),
('admin', 472, 0, '채용공고/이력서 관리', '/admin/member/recruit_list', 1, 'Y', 47, '', 'N', '', '', '2023-02-11 05:25:01', '0000-00-00 00:00:00'),
('admin', 473, 472, '채용공고/이력서 관리', '/admin/member/recruit_list', 2, 'Y', 473, '', 'N', '', '', '2023-02-11 05:25:30', '0000-00-00 00:00:00'),
('admin', 474, 473, '채용공고 리스트', '/admin/member/recruit_list', 3, 'Y', 474, '채용광고 상세:/admin/member/recruit_edit', 'N', '', '', '2023-02-11 05:25:55', '0000-00-00 00:00:00'),
('admin', 475, 473, '이력서 리스트', '/admin/member/resume_list', 3, 'Y', 475, '이력서 상세정보:/admin/member/resume_edit', 'N', '', '', '2023-02-11 05:26:22', '0000-00-00 00:00:00'),
('admin', 476, 83, '채용공고 즐겨찾기 리스트', '/admin/member/fav_recruit_list', 3, 'Y', 476, '', 'N', '', '', '2023-02-11 08:17:10', '0000-00-00 00:00:00'),
('admin', 477, 83, '이력서 즐겨찾기 리스트', '/admin/member/fav_resume_list', 3, 'Y', 477, '', 'N', '', '', '2023-02-11 08:17:32', '0000-00-00 00:00:00'),
('admin', 478, 466, '배너 리스트', '/admin/site/banner_group_list', 3, 'Y', 467, '배너 설정 리스트:/admin/site/banner_list#배너 설정 등록/수정:/admin/site/banner_edit', 'N', '', '', '2023-02-11 08:59:26', '0000-00-00 00:00:00'),
('admin', 479, 126, '포인트 이력 리스트', '/admin/order/point_list', 3, 'Y', 479, '', 'N', '', '', '2023-02-11 09:01:03', '0000-00-00 00:00:00'),
('admin', 480, 465, '커뮤니티 관리', '/admin/site/notice_list', 2, 'Y', 480, '', 'N', '', '', '2023-02-12 08:30:39', '0000-00-00 00:00:00'),
('admin', 481, 466, '쿠폰 관리', '/admin/site/setting_coupon', 3, 'Y', 468, '쿠폰 등록/수정:/admin/site/setting_coupon_edit', 'N', '', '', '2023-02-12 08:31:17', '0000-00-00 00:00:00'),
('admin', 482, 466, '쿠폰발급 리스트', '/admin/site/coupon_list', 3, 'Y', 469, '쿠폰발급 등록/수정:/admin/site/coupon_edit', 'N', '', '', '2023-02-12 08:33:25', '0000-00-00 00:00:00'),
('admin', 483, 466, '포인트 관리', '/admin/site/deposit_list', 3, 'Y', 470, '포인트 발행:/admin/site/deposit_edit', 'N', '', '', '2023-02-12 08:42:46', '0000-00-00 00:00:00'),
('admin', 484, 480, '공지사항 관리', '/admin/site/notice_list', 3, 'Y', 484, '공지사항 등록/수정:/admin/site/notice_edit', 'N', '', '', '2023-02-12 09:03:05', '0000-00-00 00:00:00'),
('admin', 485, 480, '커뮤니티 관리', '/admin/site/community_list', 3, 'Y', 485, '커뮤니티 등록/수정:/admin/site/community_edit', 'N', '', '', '2023-02-12 09:16:53', '0000-00-00 00:00:00'),
('admin', 486, 480, '이벤트 관리', '/admin/site/event_list', 3, 'Y', 486, '이벤트 등록/수정:/admin/site/event_edit', 'N', '', '', '2023-02-12 09:23:03', '0000-00-00 00:00:00'),
('admin', 487, 480, 'FAQ 관리', '/admin/site/qna_list', 3, 'Y', 487, ' FAQ 등록/수정:/admin/site/qna_edit', 'N', '', '', '2023-02-12 09:26:36', '0000-00-00 00:00:00'),
('admin', 488, 480, '문의하기 관리', '/admin/site/inquiry_list', 3, 'Y', 488, '문의하기 등록/수정:/admin/site/inquiry_edit', 'N', '', '', '2023-02-12 09:30:40', '0000-00-00 00:00:00'),
('admin', 490, 52, '운영자 계정관리', '/admin/setting/manage/user', 3, 'Y', 55, '운영자 등록/수정:/admin/setting/manage/user_edit', 'N', '', '', '2023-02-12 13:11:17', '0000-00-00 00:00:00'),
('admin', 491, 52, '구매포인트 설정', '/admin/setting/basic/config_point', 3, 'Y', 491, '구매포인트 등록/수정:/admin/setting/basic/config_point_edit', 'N', '', '', '2023-02-12 13:14:08', '0000-00-00 00:00:00'),
('admin', 492, 466, '푸시발송 관리', '/admin/site/push_list', 3, 'Y', 493, '푸시발송 등록/수정:/admin/site/push_edit', 'N', '', '', '2023-03-01 06:47:17', '0000-00-00 00:00:00'),
('admin', 493, 466, '팝업 관리', '/admin/site/popup_list', 3, 'Y', 492, '팝업 등록/수정:/admin/site/popup_edit', 'N', '', '', '2023-03-01 06:48:34', '0000-00-00 00:00:00'),
('admin', 494, 466, '태그관리', '/admin/site/setting_tag', 3, 'Y', 494, '', 'N', '', '', '2023-05-25 12:46:25', '0000-00-00 00:00:00'),
('admin', 495, 52, '이용약관 관리', '/admin/setting/basic/policy', 3, 'Y', 498, '', 'N', '', '', '2023-06-06 08:31:44', '0000-00-00 00:00:00'),
('admin', 496, 480, '신고관리', '/admin/site/report_list', 3, 'Y', 496, '', 'N', '', '', '2023-06-13 14:20:23', '0000-00-00 00:00:00'),
('admin', 497, 103, '중고거래 카테고리 설정', '/admin/setting/basic/config_trade', 3, 'Y', 105, '중고거래 카테고리 등록/수정:/admin/setting/basic/config_trade_edit', 'N', '', '', '2023-07-12 19:09:32', '0000-00-00 00:00:00'),
('admin', 498, 52, '사용포인트 설정', '/admin/setting/basic/config_usepoint', 3, 'Y', 495, '사용포인트 수정:/admin/setting/basic/config_usepoint_edit', 'N', '', '', '2023-10-26 11:35:19', '0000-00-00 00:00:00'),
('admin', 499, 0, '유료광고관리', '/admin/member/memad_list', 1, 'Y', 400, '', 'N', '0', '0', '2024-05-27 05:58:15', '0000-00-00 00:00:00'),
('admin', 500, 499, '유료광고관리', '/admin/member/memad_list', 2, 'Y', 400, '', 'N', '0', '0', '2024-05-27 11:28:43', '0000-00-00 00:00:00'),
('admin', 501, 500, '개인회원 유료광고 리스트', '/admin/member/memad_list', 3, 'Y', 1, '', 'N', '0', '0', '2024-05-27 11:32:07', '0000-00-00 00:00:00'),
('admin', 502, 500, '기업회원 유료광고 리스트', '/admin/member/memco_list', 3, 'Y', 2, '', 'N', '0', '0', '2024-05-27 11:32:43', '0000-00-00 00:00:00'),
('admin', 503, 466, '전화걸기/지원하기 관리', '/admin/call/call_log_list', 2, 'Y', 999, '', 'N', '', '', '2026-05-29 00:25:47', '0000-00-00 00:00:00'),
('admin', 504, 466, 'PC배너광고 클릭수 관리', '/admin/ad/pc_banner_click_list', 2, 'Y', 504, '', 'N', '', '', '2026-06-01 02:56:59', '0000-00-00 00:00:00'),
('admin', 508, 0, '뿌리오 발송 관리', '/admin/ppurio/buy_recom', 1, 'Y', 999, 'N', 'N', '', '', '2026-06-24 02:14:58', '0000-00-00 00:00:00'),
('admin', 510, 508, '뿌리오 발송 관리', '/admin/ppurio/buy_recom', 2, 'Y', 1, '', 'N', '', '', '2026-06-24 02:24:21', '0000-00-00 00:00:00'),
('admin', 511, 510, '추천인재 구매', '/admin/ppurio/buy_recom', 3, 'Y', 1, '', 'N', '', '', '2026-06-24 02:24:30', '0000-00-00 00:00:00');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `st_admin_menu`
--
ALTER TABLE `st_admin_menu`
  ADD PRIMARY KEY (`am_idx`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `st_admin_menu`
--
ALTER TABLE `st_admin_menu`
  MODIFY `am_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '기본키',AUTO_INCREMENT=512;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
