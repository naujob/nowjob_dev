-- 전화하기 클릭 로그 테이블
CREATE TABLE IF NOT EXISTS `st_call_log` (
  `cl_idx`        INT(11) NOT NULL AUTO_INCREMENT,
  `rc_idx`        INT(11) NOT NULL COMMENT '채용공고 idx',
  `mm_idx`        INT(11) DEFAULT NULL COMMENT '회원 idx (비회원은 NULL)',
  `mm_type`       VARCHAR(10) DEFAULT NULL COMMENT '회원유형 (MEM/COMP/비회원)',
  `cl_ip`         VARCHAR(50) DEFAULT NULL COMMENT '접속 IP',
  `cl_session_id` VARCHAR(100) DEFAULT NULL COMMENT '세션 ID (비회원 식별용)',
  `reg_dt`        DATETIME NOT NULL COMMENT '클릭 일시',
  PRIMARY KEY (`cl_idx`),
  KEY `idx_rc_idx` (`rc_idx`),
  KEY `idx_mm_idx` (`mm_idx`),
  KEY `idx_reg_dt` (`reg_dt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='전화하기 클릭 로그';
