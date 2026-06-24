-- phpMyAdmin SQL Dump
-- version 4.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- 생성 시간: 26-06-24 04:47
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
-- 테이블 구조 `st_admin_grant`
--

CREATE TABLE IF NOT EXISTS `st_admin_grant` (
  `mg_idx` int(11) NOT NULL COMMENT '기본키',
  `mg_name` varchar(30) NOT NULL COMMENT '메뉴권한 관리명',
  `mg_master_yn` enum('Y','N') NOT NULL DEFAULT 'N',
  `reg_id` varchar(30) NOT NULL COMMENT '등록 아이디',
  `up_id` varchar(30) NOT NULL COMMENT '수정 아이디',
  `reg_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
  `up_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '수정일시'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `st_admin_grant`
--

INSERT INTO `st_admin_grant` (`mg_idx`, `mg_name`, `mg_master_yn`, `reg_id`, `up_id`, `reg_dt`, `up_dt`) VALUES
(1, '마스터 메뉴권한', 'Y', '0', '0', '2021-02-20 14:15:57', '0000-00-00 00:00:00'),
(5, '관리자 권한', 'N', '0', '0', '2021-02-20 14:23:59', '0000-00-00 00:00:00'),
(6, '운영자 권한', 'N', '0', '0', '2021-02-20 14:26:17', '0000-00-00 00:00:00');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `st_admin_grant`
--
ALTER TABLE `st_admin_grant`
  ADD PRIMARY KEY (`mg_idx`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `st_admin_grant`
--
ALTER TABLE `st_admin_grant`
  MODIFY `mg_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '기본키',AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
