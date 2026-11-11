<?php

/**
 * 회원 작성 데이터 자동번역 (번역 메모리)
 *
 * - 원문 텍스트의 md5 를 키로 st_translation 에 번역문을 저장한다 (원본 테이블 수정 없음)
 * - enqueue(): 저장 시 원문을 번역 대기열(P)에 등록
 * - overlayRows(): 중국어 페이지에서 조회 결과의 해당 필드를 번역문으로 바꿔 끼움 (쿼리 1회)
 * - cron_proc::translate_pending() 이 대기열을 Google 번역으로 처리
 *
 * 마이그레이션: application/migrations/20261111_create_st_translation.sql
 * 보안: 원문/번역문(이력서 개인정보 포함 가능)은 로그에 남기지 않는다.
 */
class translationModel extends MY_Model
{
    // 번역 대상 언어 (st_translation.tr_lang)
    const LANG_ZH = 'zh';
    // 실패 재시도 상한
    const MAX_ERR_CNT = 3;

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 번역할 가치가 있는 문자열인지 (한글이 1자 이상 포함)
     * @param mixed $text
     * @return bool
     * 한글이 들어 있는 글인지 검사 (영문만 있으면 번역 안 함)
     */
    public function isTranslatable($text)
    {
        if (!is_string($text) || trim($text) === '') {
            return false;
        }
        return preg_match('/[\x{AC00}-\x{D7A3}]/u', $text) === 1;
    }

    /**
     * 번역 대기열 등록 (이미 있으면 무시)
     * @param array  $texts  원문 배열
     * @param string $format 'T'(text) | 'H'(html)
     * @return array 대상 원문의 md5 배열 (이미 등록돼 있던 것 포함 — translateNow() 에 넘긴다)
    * 원문을 md5로 지문 찍어 "대기(P)" 상태로 메모장에 적음 
    */
    public function enqueue($texts, $format = 'T')
    {
        $format = ($format === 'H') ? 'H' : 'T';
        $rows = array();
        foreach ((array)$texts as $text) {
            if (!$this->isTranslatable($text)) {
                continue;
            }
            $rows[md5($text)] = $text; // 같은 원문 중복 제거
        }
        if (empty($rows)) {
            return array();
        }

        $values = array();
        $now = $this->db->escape(date('Y-m-d H:i:s'));
        foreach ($rows as $hash => $text) {
            $values[] = '(' . $this->db->escape($hash) . ', ' . $this->db->escape(self::LANG_ZH) . ', '
                . $this->db->escape($format) . ', ' . $this->db->escape($text) . ", 'P', 0, " . $now . ')';
        }
        $sql = 'INSERT IGNORE INTO st_translation (tr_hash, tr_lang, tr_format, tr_src, tr_status, tr_err_cnt, tr_reg_dt) VALUES '
            . implode(', ', $values);
        $this->db->query($sql);
        return array_keys($rows);
    }

    /**
     * 지정한 원문들을 지금 바로 번역 (저장 직후 호출)
     * 아직 번역되지 않은(P/E) 것만 Google 에 보낸다. 실패·타임아웃이면 대기열에 그대로 남아 cron 이 다시 처리한다.
     * @param array $hashes          md5 배열 (enqueue() 반환값)
     * @param int   $connect_timeout 연결 타임아웃(초)
     * @param int   $timeout         전체 타임아웃(초)
     * @return array done(int), failed(int), skipped(bool — 설정 없음)
     * 방금 적은 글만 Google 번역에 바로 보내고 결과를 db저장
     */
    public function translateNow($hashes, $connect_timeout = 3, $timeout = 5)
    {
        $result = array('done' => 0, 'failed' => 0, 'skipped' => false);
        $hashes = array_values(array_unique((array)$hashes));
        if (empty($hashes)) {
            return $result;
        }

        require_once SHAREDPATH . 'libraries/GoogleTranslateClient.php';
        $client = new GoogleTranslateClient();
        if (!$client->isEnabled()) {
            $result['skipped'] = true; // 키 미설정: cron 도 건너뛰므로 대기열에만 남는다
            return $result;
        }
        $client->setTimeout($connect_timeout, $timeout);

        // 아직 번역 안 된 행만 조회 (uk_hash_lang 인덱스)
        $in = array();
        foreach ($hashes as $h) {
            $in[] = $this->db->escape($h);
        }
        $query = $this->db->query("SELECT tr_idx, tr_format, tr_src FROM st_translation
                WHERE tr_lang = " . $this->db->escape(self::LANG_ZH) . "
                  AND tr_hash IN (" . implode(',', $in) . ")
                  AND tr_status IN ('P', 'E')");
        if (!$query) {
            return $result;
        }

        $groups = array('T' => array(), 'H' => array());
        foreach ($query->result_array() as $row) {
            $groups[$row['tr_format'] === 'H' ? 'H' : 'T'][$row['tr_idx']] = $row['tr_src'];
        }

        foreach ($groups as $format => $texts) {
            if (empty($texts)) {
                continue;
            }
            $res = $client->translateBatch($texts, $format === 'H' ? 'html' : 'text');
            foreach ($res['translations'] as $tr_idx => $text) {
                $this->saveTranslation($tr_idx, $text);
                $result['done']++;
            }
            if (!$res['ok']) {
                // 재시도 횟수는 올리지 않는다 (타임아웃은 일시적일 수 있음) — 대기 상태로 두면 cron 이 처리
                $result['failed'] += count($texts) - count($res['translations']);
                error_log('[translateNow] ' . $res['error']);
            }
        }
        return $result;
    }

    /**
     * 완료된 번역문 조회 (uk_hash_lang 인덱스 사용)
     * @param array $hashes md5 배열
     * @return array hash => 번역문
     * 지문 목록을 받아 완료된(Y) 번역문만 찾아 줌(화면에 표시하기 위해)
     */
    public function getTranslations($hashes)
    {
        $hashes = array_values(array_unique((array)$hashes));
        if (empty($hashes)) {
            return array();
        }
        $in = array();
        foreach ($hashes as $h) {
            $in[] = $this->db->escape($h);
        }
        $sql = "SELECT tr_hash, tr_text FROM st_translation
                WHERE tr_lang = " . $this->db->escape(self::LANG_ZH) . "
                  AND tr_hash IN (" . implode(',', $in) . ")
                  AND tr_status = 'Y'";
        $map = array();
        $query = $this->db->query($sql);
        if (!$query) {
            return $map; // 테이블 미생성 등 조회 실패 시 원문 표시
        }
        foreach ($query->result_array() as $row) {
            $map[$row['tr_hash']] = $row['tr_text'];
        }
        return $map;
    }

    /**
     * 조회 결과의 지정 필드를 번역문으로 바꿔 끼운다 (중국어 페이지에서만 동작)
     * 번역이 아직 없으면 원문 유지 + 대기열 등록.
     * @param array $rows   행 배열 (참조로 수정)
     * @param array $fields 필드명 => 'T'|'H'  예: array('rc_name' => 'T', 'rc_desc' => 'H')
     * @return array 수정된 행 배열
     * 목록 데이터의 한국어를 번역문으로 바꿔 끼움 (여러 행 한꺼번에 처리)(화면에표시)
     */
    public function overlayRows(&$rows, $fields)
    {
        if (!is_array($rows) || empty($rows) || !function_exists('site_lang') || site_lang() !== 'zh') {
            return $rows;
        }

        // 1) 필요한 해시 수집
        $hashes = array();
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            foreach ($fields as $field => $format) {
                if (isset($row[$field]) && $this->isTranslatable($row[$field])) {
                    $hashes[] = md5($row[$field]);
                }
            }
        }
        if (empty($hashes)) {
            return $rows;
        }

        // 2) 한 번에 조회
        $map = $this->getTranslations($hashes);

        // 3) 치환 + 미번역 등록
        $missing = array('T' => array(), 'H' => array());
        foreach ($rows as $i => $row) {
            if (!is_array($row)) {
                continue;
            }
            foreach ($fields as $field => $format) {
                if (!isset($row[$field]) || !$this->isTranslatable($row[$field])) {
                    continue;
                }
                $hash = md5($row[$field]);
                if (isset($map[$hash])) {
                    $rows[$i][$field] = $map[$hash];
                } else {
                    $missing[$format === 'H' ? 'H' : 'T'][] = $row[$field];
                }
            }
        }
        if (!empty($missing['T'])) {
            $this->enqueue($missing['T'], 'T');
        }
        if (!empty($missing['H'])) {
            $this->enqueue($missing['H'], 'H');
        }
        return $rows;
    }

    /**
     * 단일 행 버전
     * @param array $row
     * @param array $fields
     * @return array
     * 행 1개짜리 버전 (내부에서 overlayRows를 호출)
     */
    public function overlayRow($row, $fields)
    {
        if (!is_array($row) || empty($row)) {
            return $row;
        }
        $rows = array($row);
        $this->overlayRows($rows, $fields);
        return $rows[0];
    }

    /**
     * cron: 번역 대기(P) + 재시도 가능한 실패(E) 조회
     * @param int $limit
     * @return array
     * 대기(P)와 재시도 가능한 실패(E)를 골라 줌
     */
    public function getPending($limit = 50)
    {
        $limit = max(1, min(200, (int)$limit));
        $sql = "SELECT tr_idx, tr_format, tr_src FROM st_translation
                WHERE tr_status IN ('P', 'E') AND tr_err_cnt < " . self::MAX_ERR_CNT . "
                ORDER BY tr_idx ASC
                LIMIT " . $limit;
        $query = $this->db->query($sql);
        return $query ? $query->result_array() : array();
    }

    /**
     * 번역 완료 저장
     * @param int    $tr_idx
     * @param string $text
     * 번역 완료된 문장을 tr_text에 저장하고 상태를 Y로 바꿈(크론용)
     */
    public function saveTranslation($tr_idx, $text)
    {
        $sql = "UPDATE st_translation SET tr_text = ?, tr_status = 'Y', tr_upd_dt = NOW() WHERE tr_idx = ?";
        $this->db->query($sql, array($text, (int)$tr_idx));
    }

    /**
     * 번역 실패 기록 (재시도 횟수 증가)
     * @param array $tr_idx_list
     * 실패한 문장을 E로 바꾸고 실패 횟수를 1 올림
     */
    public function markError($tr_idx_list)
    {
        $ids = array();
        foreach ((array)$tr_idx_list as $id) {
            $ids[] = (int)$id;
        }
        if (empty($ids)) {
            return;
        }
        $sql = "UPDATE st_translation SET tr_status = 'E', tr_err_cnt = tr_err_cnt + 1, tr_upd_dt = NOW()
                WHERE tr_idx IN (" . implode(',', $ids) . ")";
        $this->db->query($sql);
    }

    /**
     * 백필: 게재 중인 채용공고 원문 (PK 범위 조회 — 전체 스캔 금지)
     * @param int $after_idx 이 rc_idx 초과부터
     * @param int $limit
     * @return array
     * 게재 중인 공고 원문을 번호 순서대로 가져옴
     */
    public function getBackfillRecruits($after_idx, $limit)
    {
        $sql = "SELECT rc_idx, rc_name, rc_hash, rc_desc FROM st_recruit
                WHERE rc_idx > ?
                  AND rc_auto_yn = 'N' AND rc_show_yn = 'Y' AND rc_status = 'Y' AND rc_edate >= NOW()
                ORDER BY rc_idx ASC
                LIMIT " . max(1, min(500, (int)$limit));
        return $this->db->query($sql, array((int)$after_idx))->result_array();
    }

    /**
     * 백필: 공개 이력서 원문 (PK 범위 조회)
     * @param int $after_idx 이 ru_idx 초과부터
     * @param int $limit
     * @return array
     */
    public function getBackfillResumes($after_idx, $limit)
    {
        $sql = "SELECT ru_idx, ru_name, ru_desc FROM st_resume
                WHERE ru_idx > ?
                  AND ru_auto_yn = 'N' AND ru_status = 'Y' AND ru_show_yn = 'Y'
                ORDER BY ru_idx ASC
                LIMIT " . max(1, min(500, (int)$limit));
        return $this->db->query($sql, array((int)$after_idx))->result_array();
    }

    /**
     * 백필: 이력서들의 경력/자격증 이름
     * @param array $ru_idx_list
     * @return array 원문 배열
     * 이력서의 경력·자격증 이름을 가져옴
     */
    public function getResumeChildNames($ru_idx_list)
    {
        $ids = array();
        foreach ((array)$ru_idx_list as $id) {
            $ids[] = (int)$id;
        }
        if (empty($ids)) {
            return array();
        }
        $in = implode(',', $ids);
        $names = array();
        foreach (array('st_resume_career', 'st_resume_certi') as $table) {
            $rows = $this->db->query("SELECT rc_name FROM " . $table . " WHERE ru_idx IN (" . $in . ")")->result_array();
            foreach ($rows as $row) {
                $names[] = $row['rc_name'];
            }
        }
        return $names;
    }

    /**
     * 채용공고 1건의 번역 대상 원문 등록 (저장 시 호출)
     * @param array $data rc_name, rc_hash, rc_desc 키를 가진 배열
     * @return array 등록한 원문의 md5 배열
     * 공고의 제목·회사명·본문을 골라 enqueue에 넘김
     */
    public function enqueueRecruit($data)
    {
        if (!is_array($data)) {
            return array();
        }
        $hashes_t = $this->enqueue(array(
            isset($data['rc_name']) ? $data['rc_name'] : '',
            isset($data['rc_hash']) ? $data['rc_hash'] : '',
        ), 'T');
        $hashes_h = $this->enqueue(array(isset($data['rc_desc']) ? $data['rc_desc'] : ''), 'H');
        return array_merge($hashes_t, $hashes_h);
    }

    /**
     * 이력서 1건의 번역 대상 원문 등록 (저장 시 호출)
     * @param array $data       ru_name, ru_desc 키를 가진 배열
     * @param array $child_names 경력/자격증 이름 배열
     * @return array 등록한 원문의 md5 배열
     */
    public function enqueueResume($data, $child_names = array())
    {
        $texts = array(
            isset($data['ru_name']) ? $data['ru_name'] : '',
            isset($data['ru_desc']) ? $data['ru_desc'] : '',
        );
        return $this->enqueue(array_merge($texts, (array)$child_names), 'T');
    }
}
