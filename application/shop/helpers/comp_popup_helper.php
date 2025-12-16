<?php
if (!function_exists('get_comp_popup_payload')) {
  /**
   * 기업정보 팝업에 필요한 데이터 반환
   *
   * @param int $member_idx
   * @return array
   */
  function get_comp_popup_payload($member_idx)
  {
    $CI = &get_instance();
    $CI->load->model('member_model');
    return [
      'info' => $CI->member_model->getMember(["mm_idx" => $member_idx]),
      'enc' => $CI->member_model->getNiceEncData(),
    ];
  }
}

