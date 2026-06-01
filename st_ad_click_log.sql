CREATE TABLE `st_ad_click_log` (
  `acl_idx`      int(11) NOT NULL AUTO_INCREMENT,
  `page_type`    varchar(20) NOT NULL COMMENT '페이지 유형 (recruit/hr)',
  `mm_idx`       int(11) DEFAULT NULL COMMENT '회원 idx (비회원은 NULL)',
  `mm_type`      varchar(10) DEFAULT NULL COMMENT 'MEM/COMP/비회원',
  `acl_ip`       varchar(50) DEFAULT NULL,
  `acl_session`  varchar(100) DEFAULT NULL,
  `reg_dt`       datetime NOT NULL,
  PRIMARY KEY (`acl_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
