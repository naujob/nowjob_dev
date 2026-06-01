-- phpMyAdmin SQL Dump
-- version 4.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- 생성 시간: 26-06-01 01:42
-- 서버 버전: 5.5.68-MariaDB
-- PHP 버전: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 데이터베이스: `dev_nowapk`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `st_call_log`
--

CREATE TABLE IF NOT EXISTS `st_call_log` (
  `cl_idx` int(11) NOT NULL,
  `rc_idx` int(11) NOT NULL COMMENT '채용공고 idx',
  `mm_idx` int(11) DEFAULT NULL COMMENT '회원 idx (비회원은 NULL)',
  `mm_type` varchar(10) DEFAULT NULL COMMENT '회원유형 (MEM/COMP/비회원)',
  `cl_ip` varchar(50) DEFAULT NULL COMMENT '접속 IP',
  `cl_session_id` varchar(100) DEFAULT NULL COMMENT '세션 ID (비회원 식별용)',
  `reg_dt` datetime NOT NULL COMMENT '클릭 일시'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='전화하기 클릭 로그';

--
-- 테이블의 덤프 데이터 `st_call_log`
--

INSERT INTO `st_call_log` (`cl_idx`, `rc_idx`, `mm_idx`, `mm_type`, `cl_ip`, `cl_session_id`, `reg_dt`) VALUES
(1, 52, 1, 'MEM', '112.170.223.212', 'ld2e2g47aeqc83i3aba4kt43m1', '2026-05-28 16:37:59');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `st_call_log`
--
ALTER TABLE `st_call_log`
  ADD PRIMARY KEY (`cl_idx`),
  ADD KEY `idx_rc_idx` (`rc_idx`),
  ADD KEY `idx_mm_idx` (`mm_idx`),
  ADD KEY `idx_reg_dt` (`reg_dt`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `st_call_log`
--
ALTER TABLE `st_call_log`
  MODIFY `cl_idx` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
