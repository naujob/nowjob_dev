-- MATERIALIZED VIEW: 채용공고 목록 캐시 테이블
-- 목적: 복잡한 JOIN/GROUP BY 쿼리를 미리 계산해서 저장
-- 효과: 조회 시간 907ms → 200ms로 단축

-- 1. 캐시 테이블 생성
CREATE TABLE IF NOT EXISTS vw_recruit_list_cache (
  rc_idx INT PRIMARY KEY,
  mm_id VARCHAR(100),
  mm_idx INT,
  mm_status CHAR(1),
  mm_comp_name VARCHAR(255),
  rc_name VARCHAR(65) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  rc_work VARCHAR(20),
  rc_work_type VARCHAR(30),
  rc_price_type VARCHAR(30),
  rc_price_rep VARCHAR(50),
  rc_price_pay INT,
  rc_img1 VARCHAR(255),
  rc_show_yn ENUM('Y','N'),
  rc_status ENUM('Y','N','H'),
  rc_sdate TIMESTAMP DEFAULT '0000-00-00 00:00:00',
  rc_edate TIMESTAMP DEFAULT '0000-00-00 00:00:00',
  rc_sage INT,
  rc_eage INT,
  rc_nation VARCHAR(10),
  rc_sex VARCHAR(10),
  rc_week VARCHAR(20),
  rc_hash VARCHAR(100),
  ad_addr VARCHAR(20),
  s_addr VARCHAR(100),
  ad_gps_x DECIMAL(14, 10),
  ad_gps_y DECIMAL(14, 10),
  book_cnt INT DEFAULT 0,
  pm_idx INT,
  pm_code VARCHAR(20),
  up_dt TIMESTAMP NULL,
  reg_dt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  cached_at TIMESTAMP DEFAULT '0000-00-00 00:00:00',

  -- 인덱스 추가 (조회 성능 향상)
  INDEX idx_status_dates (rc_show_yn, rc_status, rc_sdate, rc_edate),
  INDEX idx_work (rc_work),
  INDEX idx_work_type (rc_work_type),
  INDEX idx_week (rc_week),
  INDEX idx_price_type (rc_price_type),
  INDEX idx_sex (rc_sex),
  INDEX idx_addr (ad_addr),
  INDEX idx_s_addr (s_addr(50)),
  INDEX idx_dates (rc_sdate, rc_edate),
  INDEX idx_up_dt (up_dt),
  INDEX idx_mm_idx (mm_idx),
  INDEX idx_pm_idx (pm_idx),
  INDEX idx_cached_at (cached_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='채용공고 캐시 테이블';

-- 참고: 캐시 갱신은 Cron + HTTP 요청(recruit_proc.php의 refreshRecruitCache)으로 처리
-- MySQL PROCEDURE와 EVENT는 사용하지 않음 (PHP 컨트롤러 기반 갱신)
