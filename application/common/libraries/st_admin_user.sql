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
-- 테이블 구조 `st_admin_user`
--

CREATE TABLE IF NOT EXISTS `st_admin_user` (
  `au_idx` int(11) NOT NULL COMMENT '기본키',
  `au_id` varchar(20) NOT NULL COMMENT '관리자 아이디',
  `mg_idx` int(11) NOT NULL COMMENT ' 권한 메뉴 아이디',
  `au_pwd` varchar(200) NOT NULL COMMENT '관리자 비밀번호',
  `au_name` varchar(20) NOT NULL COMMENT '관리자명',
  `au_hp` varchar(20) NOT NULL COMMENT '연락처',
  `au_email` varchar(50) NOT NULL COMMENT '이메일',
  `au_status` enum('Y','N') NOT NULL COMMENT '사용상태',
  `reg_id` varchar(20) NOT NULL COMMENT '등록 아이디',
  `up_id` varchar(20) NOT NULL COMMENT '수정 아이디',
  `reg_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '등록 일시',
  `up_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '수정 일시',
  `role_mem` char(1) DEFAULT NULL COMMENT '회원관리권한',
  `role_recresu` char(1) DEFAULT NULL COMMENT '채용공고권한',
  `role_trade` char(1) DEFAULT NULL COMMENT '중고거래권한',
  `role_ad` char(1) DEFAULT NULL COMMENT '유료광고권한',
  `role_pay` char(1) DEFAULT NULL COMMENT '결제권한',
  `role_site` char(1) DEFAULT NULL COMMENT '사이트권한'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='과리자 회원 테이블';

--
-- 테이블의 덤프 데이터 `st_admin_user`
--

INSERT INTO `st_admin_user` (`au_idx`, `au_id`, `mg_idx`, `au_pwd`, `au_name`, `au_hp`, `au_email`, `au_status`, `reg_id`, `up_id`, `reg_dt`, `up_dt`, `role_mem`, `role_recresu`, `role_trade`, `role_ad`, `role_pay`, `role_site`) VALUES
(9, 'nowjob', 6, '*BF18DFD3BB204A54558D455F444AD05F6E4E84B9', '나우인터내셔널', '', 'nau2023@naver.com', 'Y', '', '', '2024-01-02 00:29:07', '0000-00-00 00:00:00', 'Y', 'N', 'N', 'N', 'N', 'N'),
(10, 'nowjob001', 6, '*02ADF43CAA6396698FDDCE526F707D7F1D2F3DF1', '나우잡', '', '', 'Y', '', '', '2025-01-23 08:27:03', '0000-00-00 00:00:00', 'Y', 'Y', 'Y', 'N', 'N', 'N');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `st_admin_user`
--
ALTER TABLE `st_admin_user`
  ADD PRIMARY KEY (`au_idx`),
  ADD KEY `au_id` (`au_id`),
  ADD KEY `mg_idx` (`mg_idx`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `st_admin_user`
--
ALTER TABLE `st_admin_user`
  MODIFY `au_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '기본키',AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
