<div class="bottom-nav">
    <!-- 활성화된 메뉴 : active 클래스 -->
    <a href="/" class="btn menu-home <?=$select_menu=="home" ? "active":""?>">메인</a>
    <button type="button" aria-controls="menu" aria-expanded="false" class="btn-toggle-menu js-toggle-menu"><span class="sr-text">메뉴열기</span></button>
    <nav id="menu" class="menu">
        <!-- 개인 사용자 -->
        <ul>
            <li><a href="/" class="sub-menu1">HOME</a></li>
            <li><a href="/recruit" class="sub-menu2">채용정보</a></li>
            <li><a href="/trade" class="sub-menu3">중고거래</a></li>
            <li><a href="/chat" class="sub-menu4">채팅</a></li>
            <?php if($this->user->isLogin()) : ?>
            	<li><a href="/<?=($this->user->getUserType()=="COMP") ? "compage":"mypage" ?>/index" class="sub-menu5">마이페이지</a></li>	
            <?php else : ?>
            	<li><a href="/member/login" class="sub-menu5">로그인</a></li>
            <?php endif; ?>
            
        </ul>
        <div class="dimmed"></div>
    </nav>
    <a href="/chat" class="btn menu-chat <?=$select_menu=="chat" ? "active":""?>">채팅방</a>
</div>