<?php

// 매칭 로직 공유 모델
class Matchmodel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 활성 이력서 조회 (주소 포함)
     */
    public function getActiveResumes() {
        $sql = "
            SELECT r.ru_idx, r.mm_idx, r.ru_name, r.ru_work, r.ru_work_type, r.ru_price_type, r.reg_dt,
                   r.ru_status, r.ru_show_yn, r.ru_work_dt,
                   m.mm_name, m.mm_phone, m.mm_sex, m.mm_birth, m.mm_nation,
                   a.ad_addr
            FROM st_resume r
            INNER JOIN st_member m ON r.mm_idx = m.mm_idx
            INNER JOIN st_address a ON r.mm_idx = a.mm_idx
                AND a.ad_type = 'RESUME' AND a.ad_key = r.ru_idx
            WHERE r.ru_show_yn = 'Y'
              AND r.ru_status = 'Y'
              AND m.mm_type = 'MEM'
              AND m.mm_status = 'Y'
              AND m.mm_agree_ad = 'Y'
              AND m.alarm_resume = 'Y'
              AND m.mm_phone != ''
            ORDER BY r.reg_dt DESC
        ";
        return $this->db->query($sql)->result_array();
    }

    /**
     * 게재 중인 채용공고 조회 (주소 포함)
     */
    public function getActiveRecruits() {
        $sql = "
            SELECT rc.rc_idx, rc.mm_idx, rc.rc_name, rc.rc_work, rc.rc_work_type, rc.rc_price_type,
                   rc.rc_sex, rc.rc_sage, rc.rc_eage, rc.rc_nation, rc.reg_dt,
                   rc.rc_status, rc.rc_show_yn, rc.rc_sdate, rc.rc_edate,
                   m.mm_comp_name, m.mm_phone,
                   a.ad_addr
            FROM st_recruit rc
            INNER JOIN st_member m ON rc.mm_idx = m.mm_idx
            INNER JOIN st_address a ON rc.mm_idx = a.mm_idx
                AND a.ad_type IN ('RECRUIT', 'RECRUIT_EXPOSE_1', 'RECRUIT_EXPOSE_2')
                AND a.ad_key = rc.rc_idx
            WHERE rc.rc_status = 'Y'
              AND rc.rc_show_yn = 'Y'
              AND rc.rc_sdate <= NOW()
              AND rc.rc_edate >= NOW()
              AND m.mm_type = 'COMP'
              AND m.mm_status = 'Y'
              AND m.mm_agree_ad = 'Y'
              AND m.alarm_resume = 'Y'
              AND m.mm_phone != ''
            ORDER BY rc.reg_dt DESC
        ";
        return $this->db->query($sql)->result_array();
    }

    /**
     * ad_addr 필드만 사용 - 주소를 "시/도 구/군" 단위로 그룹핑합니다.

        *예: "서울특별시 강남구 역삼동" → "서울특별시 강남구"로 압축

     * 
     */
    public function groupByArea($items) { // items: 이력서 또는 채용공고 데이터의 배열
        $grouped = array();
        foreach ($items as $item) {
            $addr = isset($item['ad_addr']) ? trim($item['ad_addr']) : ''; // 'ad_addr' => '서울특별시 강남구 테헤란로'
            if ($addr === '') continue;
            // item:인천광역시 남동구 고잔동
            $parts = explode(' ', $addr); // parts[0]: 시/도, parts[1]: 구/군, parts[2]: 동/읍/면 ["인천광역시", "남동구", "고잔동"]
            // 시/도 + 구/군 (처음 2단어)
            $area = (count($parts) >= 2) ? $parts[0] . ' ' . $parts[1] : $parts[0];
            // area 인천광역시 남동구
            if (!isset($grouped[$area])) { // 그룹이 없으면 새로 생성
                $grouped[$area] = array();
            }
            $grouped[$area][] = $item; //같은 지역($area)의 항목들을 배열에 추가
        }
        return $grouped;
    }

    /**
     * 이력서와 채용공고의 세부 조건 매칭 확인
     * 모든 조건이 일치해야 매칭됨
     * $conditions: null이면 전체 검사, 배열이면 해당 조건만 검사 (예: ['area', 'work', 'sex'])
     */
    public function isMatched($resume, $recruit, $conditions = null) {
        // 1. 직종 매칭 (1개 이상 겹침)
        if ($conditions === null || in_array('work', $conditions)) {
            $ru_works = array_map('trim', explode(',', $resume['ru_work']));
            $rc_works = array_map('trim', explode(',', $recruit['rc_work']));
            $work_intersect = array_intersect($ru_works, $rc_works);
            if (empty($work_intersect)) return false;
        }

        // 2. 성별 매칭
        if ($conditions === null || in_array('sex', $conditions)) {
            if ($resume['mm_sex'] !== '') {
                $sex_map = array('남' => '남자', '여' => '여자');
                $member_sex = isset($sex_map[$resume['mm_sex']]) ? $sex_map[$resume['mm_sex']] : '';
                if ($member_sex !== '') {
                    $rc_sex_arr = array_map('trim', explode(',', $recruit['rc_sex']));
                    if (!in_array($member_sex, $rc_sex_arr)) return false;
                }
            }
        }

        // 3. 나이 매칭 (mm_birth가 0 이상일 때만 검사)
        if ($conditions === null || in_array('age', $conditions)) {
            if (((int)$resume['mm_birth']) > 0 && ((int)$recruit['rc_sage']) > 0 && ((int)$recruit['rc_eage']) > 0) {
                // mm_birth는 YYYYMMDD 형식이므로 처음 4자리(연도)만 추출
                $birth_year = (int)substr((string)$resume['mm_birth'], 0, 4);
                $korean_age = (int)date('Y') - $birth_year + 1;
                $normalized = round($korean_age / 10) * 10;
                if ($normalized < 20) $normalized = 20;
                if ($normalized > 70) $normalized = 70;
                if ($normalized < (int)$recruit['rc_sage'] || $normalized > (int)$recruit['rc_eage']) return false;
            }
        }

        // 4. 국적 매칭
        if ($conditions === null || in_array('nation', $conditions)) {
            if ($resume['mm_nation'] != '') {
                $rc_nations = array_map('trim', explode(',', $recruit['rc_nation']));
                if (in_array($resume['mm_nation'], array('내국인', '외국인'))) {
                    if (!in_array($resume['mm_nation'], $rc_nations)) return false;
                }
            }
        }

        // 5. 근무형태 매칭 (1개 이상 겹침)
        if ($conditions === null || in_array('work_type', $conditions)) {
            $ru_types = array_map('trim', explode(',', $resume['ru_work_type']));
            $rc_types = array_map('trim', explode(',', $recruit['rc_work_type']));
            $type_intersect = array_intersect($ru_types, $rc_types); // 공통 데이터 찾기 (교집합)
            if (empty($type_intersect)) return false;
        }

        // 6. 급여타입 매칭 (1개 이상 겹침, 또는 "시급" ↔ "월급" 매핑, 또는 "급여협의")
        if ($conditions === null || in_array('price', $conditions)) {
            $ru_price_type = isset($resume['ru_price_type']) ? trim($resume['ru_price_type']) : '';
            $rc_price_type = isset($recruit['rc_price_type']) ? trim($recruit['rc_price_type']) : '';

            // 급여 타입이 없거나 "급여협의"면 스킵 (매칭 조건으로 제약하지 않음)
            if ($ru_price_type === '' || $rc_price_type === '' || $rc_price_type === '급여협의') {
                // 급여 타입 정보 없거나 협의 가능하면 통과
            } else {
                $ru_prices = array_map('trim', explode(',', $ru_price_type));
                $rc_prices = array_map('trim', explode(',', $rc_price_type));

                $price_intersect = array_intersect($ru_prices, $rc_prices);
                if (!empty($price_intersect)) {
                    // 직접 일치
                } else if (in_array('시급', $rc_prices) && in_array('월급', $ru_prices)) {
                    // 매핑: 채용공고의 "시급"이 이력서의 "월급"과 일치
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 지역 조건 없이 모든 이력서와 모든 공고를 매칭 (전체 이중 루프)
     * $conditions: 검사할 조건 배열 (예: ['nation', 'sex', 'work'])
     */
    public function matchAllPairs($resumes, $recruits, $conditions = null) {
        $matches = array();
        foreach ($resumes as $resume) {
            foreach ($recruits as $recruit) {
                if ($this->isMatched($resume, $recruit, $conditions)) {
                    $matches[] = array(
                        'resume' => $resume,
                        'recruit' => $recruit
                    );
                }
            }
        }
        return $matches;
    }
}
?>
