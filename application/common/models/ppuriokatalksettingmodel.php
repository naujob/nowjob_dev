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
        return $this->db->update('st_ppurio_katalk_setting', $data, array('pks_type' => $type));
    }
}
?>
