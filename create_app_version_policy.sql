-- Table: app_version_policy
CREATE TABLE IF NOT EXISTS app_version_policy (
    platform        VARCHAR(20) PRIMARY KEY COMMENT 'android 또는 ios',
    min_version     VARCHAR(32) NOT NULL COMMENT '강제 업데이트 기준 버전',
    latest_version  VARCHAR(32) NOT NULL COMMENT '권장 업데이트 기준 버전',
    message_force   VARCHAR(255) DEFAULT NULL COMMENT '강제 업데이트 안내 문구',
    message_optional VARCHAR(255) DEFAULT NULL COMMENT '선택 업데이트 안내 문구',
    store_url       VARCHAR(255) DEFAULT NULL COMMENT 'PlayStore/AppStore URL',
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 샘플 데이터 (운영에 맞게 수정)
INSERT INTO app_version_policy (platform, min_version, latest_version, message_force, message_optional, store_url)
VALUES
    ('android', '3.0.0', '3.0.0', '최신 버전으로 업데이트해야 이용할 수 있습니다.', '새 버전이 출시되었습니다.', 'https://play.google.com/store/apps/details?id=app.nowapk.com'),
    ('ios', '6.0.1', '6.0.1', '업데이트 후 이용하실 수 있습니다.', '새 버전을 확인해 주세요.', 'https://apps.apple.com/kr/app/id6446226901');
