<?php
class Ppuriokatalksettingmodel extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    // 카카오 채널발송 알림 설정 조회
    function getSetting($type = 'usual') {
        return $this->db->query("SELECT * FROM st_ppurio_katalk_setting WHERE pks_type = ?", array($type))->row_array();
    }

    // 카카오 채널발송 알림 설정 업데이트
    function updateSetting($data, $type = 'usual') {
        $data['mod_dt'] = date('Y-m-d H:i:s'); // 수정일시 2026-06-30 14:11:22
      

        // 직접 SQL 실행 (더 명확한 로깅을 위해)
        $set_clause = '';
        $first = true;
        foreach ($data as $key => $value) {
            if (!$first) $set_clause .= ', ';
            $set_clause .= $key . ' = ?';
            $first = false;
        }
        $values = array_values($data);
        $values[] = $type;

        $sql = "UPDATE st_ppurio_katalk_setting SET " . $set_clause . " WHERE pks_type = ?";
     

        $this->db->query($sql, $values);

        $affected = $this->db->affected_rows();
        

        return $affected > 0 ? true : false;
    }
}
?>
