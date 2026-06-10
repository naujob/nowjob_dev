-- MATERIALIZED VIEW: 인재정보 목록 캐시 테이블
-- 목적: 복잡한 JOIN 쿼리를 미리 계산해서 저장
-- 효과: 초기 로드 시간 단축 (AJAX 없이 서버 렌더링)

-- 1. 캐시 테이블 생성
CREATE TABLE IF NOT EXISTS vw_resume_list_cache (
  ru_idx INT PRIMARY KEY COMMENT '이력서 기본키',
  mm_idx INT COMMENT '회원키',
  mm_id VARCHAR(100) COMMENT '회원아이디',
  mm_status CHAR(1) COMMENT '회원상태',
  ru_name VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '이력서명',
  ru_work VARCHAR(20) COMMENT '업종',
  ru_work_type VARCHAR(10) COMMENT '근무형태',
  ru_price_type VARCHAR(30) COMMENT '급여형태',
  ru_work_img VARCHAR(200) COMMENT '근무형태이미지',
  ru_resume_img VARCHAR(255) COMMENT '프로필이미지',
  ru_show_yn ENUM('Y','N') COMMENT '공개여부',
  ru_status ENUM('Y','N','H','HY','HN') COMMENT '이력서상태',
  ru_week VARCHAR(20) COMMENT '근무요일',
  ru_my_type VARCHAR(60) COMMENT '나의강점',
  ad_addr VARCHAR(100) COMMENT '지역주소',
  mm_age VARCHAR(10) COMMENT '나이',
  mm_nation VARCHAR(20) COMMENT '국적',
  book_cnt INT DEFAULT 0 COMMENT '북마크수',
  pm_idx INT COMMENT '프로모션인덱스',
  pm_code VARCHAR(20) COMMENT '프로모션코드',
  up_dt TIMESTAMP NULL COMMENT '수정일시',
  reg_dt TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
  cached_at TIMESTAMP DEFAULT '0000-00-00 00:00:00' COMMENT '캐시갱신일시',

  -- 인덱스 추가 (조회 성능 향상)
  INDEX idx_status_show (ru_show_yn, ru_status),
  INDEX idx_work_type (ru_work_type),
  INDEX idx_week (ru_week),
  INDEX idx_price_type (ru_price_type),
  INDEX idx_addr (ad_addr),
  INDEX idx_up_dt (up_dt),
  INDEX idx_mm_idx (mm_idx),
  INDEX idx_pm_idx (pm_idx),
  INDEX idx_cached_at (cached_at),
  INDEX idx_reg_dt (reg_dt)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='인재정보 캐시 테이블';

-- 참고: 캐시 갱신은 Cron + HTTP 요청(resume_proc.php의 refreshResumeCache)으로 처리
-- MySQL PROCEDURE와 EVENT는 사용하지 않음 (PHP 컨트롤러 기반 갱신)
