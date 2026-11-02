<?php
class Chat_new extends CI_Controller
{
    # 전역변수 선언
    public $mm_idx;
    public $mm_id;

    function __construct()
    {
        parent::__construct();

        $this->load->model('membermodel', 'member_model');
        $this->load->model('settingmodel', 'setting_model');
        $this->load->model('sitemodel', 'site_model');
        $this->load->model('commonmodel', 'common_model');

        # 로그인확인후 이동처리
        if ($this->user->isLogin() == false) {
            // redirect(302): 301 영구 이동은 브라우저가 캐시하므로 일반 302로 보낸다
            redirect("/member/login");
        }

        $this->mm_id = $this->user->getUserId();
        $this->mm_idx = $this->user->getUserIdx();
    }

    # 제안보낸 인재 / 제안받은 인재 화면
    function com_sug()
    {
        $data = [];

        // 사용자 타입 설정
        $data["user_type"] = $this->user->getUserType();

        // 뷰 파일 로드
        $this->layout->shop('/chat/com_sug', $data);
    }
}
