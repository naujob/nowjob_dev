<?php

$config = get_config();

// system/core/CodeIgniter.php에서 정의되어 있지만 여기서 미리 선언해서 해당 파일에 대한 수정을 하지 않도록 한다

require_once BASEPATH.'core/Controller.php';    // 뒤에서 한번 더 선언되지만 여기서 선언해서 사용하면 뒤에서 정의된 것은 무시

// 같은 디렉토리의 사용자 정의 Controller가 우선순위를 갖는다
if (file_exists(APPPATH.'core/'.$config['subclass_prefix'].'Controller.php'))
{
    require_once APPPATH.'core/'.$config['subclass_prefix'].'Controller.php';
} else if (file_exists(SHAREDPATH.'core/'.$config['subclass_prefix'].'Controller.php'))
{
    // 같은 환경에서 정의가 없으면 common에서 사용자 정의 Controller를 찾는다
    require_once SHAREDPATH.'core/'.$config['subclass_prefix'].'Controller.php';
}

unset($config);