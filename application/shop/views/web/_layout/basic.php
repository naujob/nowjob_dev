<?php
// app_base_url: 최상위 도메인 정보를 담고 있음. ex) https://nowjob.co.kr
$app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/');
$socket_base_url = rtrim($this->config->item('socket_base_url') ?: $app_base_url, '/');
$socket_io_script_url = $this->config->item('socket_io_script_url');
if (!$socket_io_script_url) {
    $socket_io_script_url = rtrim($socket_base_url, '/') . '/socket.io/socket.io.js?v=0001';
}
$og_image_url = $app_base_url . '/resource/shop/web/img/nowjob_info_logo.png';
$favicon_url = $app_base_url . '/resource/favicon.ico';
$is_prod_env = defined('ENVIRONMENT') && ENVIRONMENT === 'production';
$app_base_host = parse_url($app_base_url, PHP_URL_HOST);
$is_nowjob_domain = ($app_base_host === 'nowjob.co.kr');
$is_dev_domain = ($app_base_host === 'dev.nowjob.co.kr');
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=1920">
    <title>나우잡 | 내 주변 일자리. 맞춤형 구인구직 플랫폼</title>
    <!-- Google Fonts - Noto Sans KR -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
<?php if ($is_prod_env && ($is_nowjob_domain || $is_dev_domain)) : ?>
    <meta name="description" content="내 주변 지금 뜨는, 좋은 일자리 나우잡(NOWJOB)! 나에게 딱맞는 채용 정보와 전국 직종별 공고를 지금 바로 확인해보세요.">
    <meta property="og:description" content="내 주변 지금 뜨는, 좋은 일자리 나우잡(NOWJOB)! 나에게 딱맞는 채용 정보와 전국 직종별 공고를 지금 바로 확인해보세요.">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="나우잡 | 내 주변 일자리. 맞춤형 구인구직 플랫폼">
    <meta property="og:url" content="<?= $app_base_url ?>/">
    
    <meta property="og:image" content="<?= $og_image_url ?>">
    
    <!--<meta name="naver-site-verification" content="20459c5bd907c133c314dd88161a57f212fce831" />-->
    <!-- <meta name="naver-site-verification" content="2744b73772ebd4a579c885de8f8d22bb42035a89" /> -->
    <meta name="naver-site-verification" content="20459c5bd907c133c314dd88161a57f212fce831" />
    <meta name="keywords" content="나우잡, NOWJOB,구인구직, 외국인채용, 알바, 급구, 일자리, 채용, 일당">
    <script type="application/ld+json">
        [
        {
            "@context": "http://schema.org",
            "@type": "Organization",
            "name": "나우잡",
            "url": "https://nowjob.co.kr",
            "sameAs": [
            "https://www.youtube.com/@NOWJOB_NOU",
            "https://www.instagram.com/nowjob_nou",
            "https://blog.naver.com/nowjobofficial"
            ]
        },
        {
        "@context": "https://schema.org",
        "@type": "ItemList",
        "itemListElement": [
            {
            "@type": "ListItem",
            "position": 1,
            "url": "https://nowjob.co.kr/recruit/job_list", 
            "name": "추천공고",
            "image": "https://nowjob.co.kr/resource/uploads/meta/meta_recruit.png" 
            },
            {
            "@type": "ListItem",
            "position": 2,
            "url": "https://nowjob.co.kr/resume/hr_list",
            "name": "추천인재",
            "image": "https://nowjob.co.kr/resource/uploads/meta/meta_resume.png"
            },
            {
            "@type": "ListItem",
            "position": 3,
            "url": "https://nowjob.co.kr/member/login",
            "name": "간편가입",
            "image": "https://nowjob.co.kr/resource/uploads/meta/meta_join.png"
            }
        ]
        }
        ]
</script>
    <?php else : ?>
    <meta name="robots" content="noindex, nofollow, noarchive">
    <?php endif; ?>
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= $favicon_url ?>" type="image/x-icon">
    <link rel="icon" href="/resource/favicon.ico" type="image/x-icon">


    <?= getCashJs(RES_PATH . "/js/lib/jquery-3.5.1.min.js") ?>
    <?= getCashJs(RES_PATH . "/js/lib/proj4.js") ?>
    <?= getCashJs(RES_PATH . "/js/lib/preview/jquery.magnific-popup.min.js") ?>
    <?= getCashJs(RES_PATH . "/js/add.js") ?>
    <script>
        window.__CHAT_BASE_URL = "<?= $socket_base_url ?>";
    </script>
    <script>
        // 전역 socket 참조를 항상 보장(초기 로드 시점 undefined 방지)
        // + 실제 소켓 연결 전 등록된 on/off/emit을 대기 후 연결 시점에 반영
        window.socket = window.socket || (function() {
            var real = null;
            var onQueue = []; // 이벤트 핸들러 큐
            var onceQueue = []; // 한 번만 실행되는 이벤트 핸들러 큐
            var offQueue = []; // 이벤트 핸들러 제거 큐
            var emitQueue = []; // 이벤트 발생 큐

            function flush() { // 실제 소켓 연결 후 큐에 등록된 이벤트 핸들러를 실행
                if (!real) return;
                offQueue.forEach(function(item) { real.off(item[0], item[1]); });
                onQueue.forEach(function(item) { real.on(item[0], item[1]); });
                onceQueue.forEach(function(item) { real.once(item[0], item[1]); });
                emitQueue.forEach(function(item) { real.emit.apply(real, item); });
                onQueue = [];
                onceQueue = [];
                offQueue = [];
                emitQueue = [];
            }

            return {
                get connected() {
                    return real ? !!real.connected : false;
                },
                get id() {
                    return real ? (real.id || null) : null;
                },
                on: function(evt, handler) {
                    if (real) return real.on(evt, handler);
                    onQueue.push([evt, handler]);
                },
                once: function(evt, handler) {
                    if (real) return real.once(evt, handler);
                    onceQueue.push([evt, handler]);
                },
                off: function(evt, handler) {
                    if (real) return real.off(evt, handler);
                    offQueue.push([evt, handler]);
                },
                emit: function() {
                    var args = Array.prototype.slice.call(arguments);
                    if (real) return real.emit.apply(real, args);
                    emitQueue.push(args);
                },
                __bindRealSocket: function(sock) {
                    real = sock;
                    flush();
                }
            };
        })();
        var socket = window.socket;

        // Socket.IO 즉시 초기화 (CDN 우선, 실패 시 내부 경로 fallback)
        (function() {
            function loadScript(src, onload, onerror) {
                var script = document.createElement('script');
                script.src = src;
                script.onload = onload;
                script.onerror = onerror;
                document.head.appendChild(script);
            }

            if (typeof io !== 'undefined') {
                createSocket();
                return;
            }

            loadScript(
                'https://cdn.socket.io/4.5.4/socket.io.min.js',
                function() {
                    console.log('Socket.IO 라이브러리 로드 성공 (CDN)');
                    createSocket();
                },
                function() {
                    console.error('Socket.IO CDN 로드 실패, 내부 경로로 재시도합니다.');
                    loadScript(
                        '<?= $socket_io_script_url ?>',
                        function() {
                            console.log('Socket.IO 라이브러리 로드 성공 (LOCAL)');
                            createSocket();
                        },
                        function() {
                            console.error('Socket.IO 라이브러리를 로드할 수 없습니다.');
                        }
                    );
                }
            );
        })();

        function createSocket() {
            // Socket 연결 초기화
            // base_url 변수는 최상위 도메인 정보를 담고 있음. ex) https://nowjob.co.kr
            const base_url = "<?= $socket_base_url ?>";
            const realSocket = io(base_url, {
                secure: true,
                rejectUnauthorized: false, // SSL 인증서 검증 비활성화
                transports: ['websocket', 'polling']
            });

            // 전역 proxy에 실제 소켓 바인딩
            if (window.socket && typeof window.socket.__bindRealSocket === 'function') {
                window.socket.__bindRealSocket(realSocket);
                socket = window.socket;
            } else {
                window.socket = realSocket;
                socket = realSocket;
            }

            // 연결 상태 모니터링
            socket.on('connect', function() {
                console.log('Socket.IO 연결 성공:', socket.id);
            });

            socket.on('disconnect', function() {
                console.log('Socket.IO 연결 끊김');
            });

            socket.on('connect_error', function(error) {
                console.error('Socket.IO 연결 오류:', error);
            });

            // 연결 성공 후 사용자 상태 업데이트
            socket.on('connection_return', function(ret) {
                console.log('서버 연결 확인:', ret);
                var user_id = getUserId();
                var location = getLocation();
                if (user_id != "") {
                    socket.emit("update_status", {
                        user_id: user_id,
                        last_location: location
                    });
                    // Socket 연결 확인 후 미확인 메시지 카운트 조회
                    fSearchNoReadCountMainBottom();
                } else {
                    socket.emit("buyguest_status", {});
                }
            });
        }

        // 전역 함수들
        function getUserId() {
            if ($("#is_login").val() == "Y") {
                return $("#user_id").val();
            } else {
                return "";
            }
        }

        function getLocation() {
            var href = window.location.href;
            if (href.indexOf("/chat/chat_list") > -1 || href.indexOf("/chat/trade_list") > -1) {
                return "chat_list";
            } else if (href.indexOf("/chat/chat_view") > -1 || href.indexOf("/chat/trade_view") > -1) {
                return "chat_room";
            } else {
                return "site";
            }
        }
    </script>
    <script src="/chatting/js/common.js?vs=<?= getFileVersion('/chatting/js/common.js') ?>"></script>
    <script src="/chatting/js/chatting.js?vs=<?= getFileVersion('/chatting/js/chatting.js') ?>"></script>


    <!-- Style -->
    <?= getCachCss(RES_PATH . "/css/keen-slider.min.css") ?>
    <?= getCachCss(RES_PATH . "/css/main.css") ?>
    <?= getCachCss(RES_PATH . "/js/lib/preview/magnific-popup.css") ?>
    <?= getCachCss(RES_PATH . "/css/add.css") ?>


    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-PXR9Q1W94K"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-PXR9Q1W94K');
    </script>


    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-TQC25Z8B');
    </script>
    <!-- End Google Tag Manager -->


    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '869026064607445');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=869026064607445&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->

    <!-- Google AdSense - 광고 수익화를 위한 AdSense 스크립트 -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7573726661605583"
        crossorigin="anonymous"></script>

    <script>
        $(document).on('keydown', function(event) {
            if (event.key === 'Escape') { // ESC 키 확인
                //alert('escape');
                event.preventDefault(); // 기본 동작 방지
                event.stopPropagation(); // 이벤트 전파 중지
                //location.reload();
                $('.btn-close-chat').trigger('click');


            }
        });
    </script>

    <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') : ?>
       
    <?php endif; ?>


</head>
<?php
/* 오류 수정함 */
//this->user->getUserPoint()
// 위 함수에서 실시간으로 가져오게 변경완료
?>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TQC25Z8B"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <a href="#content" class="skip">메뉴 건너띄기</a>
    <header id="header" class="<?= $header_class != null ? $header_class : "" ?>">
        <div class="container">
            <h1 class="top-logo"><a href="/"><span class="sr-text">지금 뜨는, 좋은 일자리 나우잡</span></a></h1>
            <nav class="gnb">
                <ul class="top-menu">
                    <li><a href="/recruit/job_list">채용정보</a></li>
                    <li><a href="/resume/hr_list">인재정보</a></li>
                    <!-- li><a href="/trade/wlist">중고거래</a></li -->
                    <!--<li><a href="/community/community_list">커뮤니티</a></li>-->
                    <li><a href="/community/event_list">이벤트</a></li>
                </ul>
            </nav>
            <?php if ($this->user->isLogin()) : ?>
                <?php /*
                <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
                    <span class="sr-text">미확인 알림메세지</span>
                    <span class="cnt"><?= isset($g_alarm_cnt) ? $g_alarm_cnt : '0' ?></span>
                </a>
                */ ?>
            <?php endif; ?>
            <?php if ($this->user->isLogin() == false) : ?>
                <div class="rt">
                    <ul class="account-menu">
                        <li><a href="/member/login">로그인</a></li>
                        <!-- <li><a href="/member/join_start">회원가입</a></li> -->
                        <li><a href="/mypage">마이페이지</a></li>
                    </ul>
                <?php else : ?>
                    <div class="rt" style="margin-top: -24px; /* -36px; */">
                        <?php if ($this->user->getUserType() == 'MEM') { ?>
                            <input type="hidden" id="logingbn" value="MEM">
                        <?php } else if ($this->user->getUserType() == 'COMP') { ?>
                            <input type="hidden" id="logingbn" value="COMP">
                        <?php } else { ?>
                            <input type="hidden" id="logingbn" value="">
                        <?php } ?>


                        <?php
                        // 기업회원 표기: 미인증 시 "기업회원", 인증 후 기업명 표시 (top_line.php와 동일한 로직)
                        // 최신 사용자 정보 가져오기
                        $current_user_info = $this->user->isLogin() ? $this->user->getCurrentUserInfo() : [];
                        $user_info_source = (isset($current_user_info) && is_array($current_user_info)) ? $current_user_info : [];
                        $is_comp_user = ($this->user->getUserType() == 'COMP') || (!empty($user_info_source['mm_comp_num']) || !empty($user_info_source['mm_comp_name']));
                        $comp_certified = $is_comp_user && !empty($user_info_source['mm_comp_num']);
                        $comp_name = ($is_comp_user && isset($user_info_source['mm_comp_name'])) ? $user_info_source['mm_comp_name'] : '';
                        $display_comp_name = $is_comp_user
                            ? (($comp_certified && $comp_name) ? $comp_name : '기업회원')
                            : '개인회원';
                        ?>
                        <ul class="account-menu">
                            <li>
                                <div class="dropdown-wrap">
                                    <a href="#memberPanel" role="button" aria-expanded="false" aria-controls="memberPanel" class="js-toggle-dropdown"><em class="user-name"><?= $display_comp_name ?></em>님</a>
                                    <span style="display:none;"><?= $this->user->getUserIdx() ?></span>
                                    <div role="dialog" id="memberPanel" tabindex="-1" class="dropdown" hidden>
                                        <div class="member-card">
                                            <div class="card-body">
                                                <div class="thumb">
                                                    <img src="<?= getResumeImg($this->user->getUser()) ?>" alt="">
                                                </div>
                                                <div class="profile-meta" style="cursor:pointer;" onclick="location.href='/compage/point_charge_step1';">
                                                    <p class="name"><em><?= $display_comp_name ?></em>님</p>
                                                    <p class="email"><?= $this->user->getUserPhone() ?></p>
                                                    <p class="point txt-color1"><em><?= number_format($this->user->getUserPoint()) ?><?php /* =number_format($this->user->getUserPoint()) */ ?></em>P</p>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <!-- <a href="/mypage/">포인트 충전관리</a> -->
                                                <?php if ($this->user->getJoinType() == "WEB") : ?>
                                                    <a href="/compage/mypage">회원정보 확인·수정</a>
                                                    <!-- <a href="javascript:void(0);" id="chgPwd">비밀번호 변경</a> -->
                                                <?php endif; ?>
                                                <?php if ($this->user->getJoinType() != "WEB") : ?>
                                                    <a href="/compage/mypage">회원정보 확인·수정</a>
                                                    <!-- <a href="javascript:void(0);" id="chgPwd">비밀번호 변경</a> -->
                                                <?php endif; ?>
                                                <a href="javascript:void(0);" id="btnLogout">로그아웃</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php if ($this->user->getUserType() == "MEM") { ?>
                                <li><!-- <a href="/mypage/">마이페이지</a> -->
                                    <div class="dropdown-wrap" style="margin-top: -18px; margin-left: 23px;">
                                        <a href="#memberPanel" role="button" aria-expanded="false" aria-controls="memberPanel" class="js-toggle-dropdown">마이페이지</a>
                                        <div role="dialog" id="memberPanel" tabindex="-1" class="dropdown" hidden>
                                            <div class="member-card" style="font-size: 14px; width: max-content;">
                                                <a href="/mypage/point_charge_step1" style="display:block; padding: 12px;">포인트 충전 · 광고 구매 / 관리</a>
                                                <a href="/mypage/resume_list" style="display:block; padding: 0 12px 12px 12px;">이력서 등록 및 관리</a>
                                                <a href="/mypage/bookmark_recruit1" style="display:block; padding: 0 12px 12px 12px;">관심공고 관리</a>
                                                <a href="/mypage/coupon_list" style="display:block; padding: 0 12px 12px 12px;">쿠폰관리</a>
                                                <!-- a href="/mypage/trade_list" style="display:block; padding: 0 12px 12px 12px;color:#ccc;" onclick="return false;">중고거래 관리</a -->
                                                <a href="/mypage/qna_list" style="display:block; padding: 0 12px 12px 12px;">고객센터</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li><span style="border: 1px solid ; border-radius: 5px; padding: 3px 5px;cursor: pointer;margin-left: 10px;  <?= ($_SERVER['REQUEST_URI'] != "/" ? "background-color: #57CCAF; color: #fff;" : "") ?>" onclick="fGoResume();">이력서 등록</span>
                                </li>
                            <?php } else { ?>
                                <li><!-- <a href="/mypage/">마이페이지</a> -->
                                    <div class="dropdown-wrap" style="margin-top: -18px; margin-left: 23px;">
                                        <a href="#memberPanel" role="button" aria-expanded="false" aria-controls="memberPanel" class="js-toggle-dropdown">마이페이지</a>
                                        <div role="dialog" id="memberPanel" tabindex="-1" class="dropdown" hidden>
                                            <div class="member-card" style="font-size: 14px; width: max-content;">
                                                <a href="/compage/point_charge_step1" style="display:block; padding: 12px;">포인트 충전 · 광고 구매 / 관리</a>
                                                <a href="/compage/recruit_list" style="display:block; padding: 0 12px 12px 12px;">채용공고 등록 및 관리</a>
                                                <a href="/compage/bookmark_person1" style="display:block; padding: 0 12px 12px 12px;">관심인재 관리</a>
                                                <!-- <a href="/compage/bookmark_list" style="display:block; padding: 0 12px 12px 12px;">즐겨찾기 관리</a> -->
                                                <a href="/compage/coupon_list" style="display:block; padding: 0 12px 12px 12px;">쿠폰관리</a>
                                                <!-- a href="/compage/trade_list" style="display:block; padding: 0 12px 12px 12px;color:#ccc;" onclick="return false;">중고거래 관리</a -->
                                                <!-- <a href="/compage/interv_list" style="display:block; padding: 0 12px 12px 12px;">면접자관리</a> -->
                                                <a href="/compage/qna_list" style="display:block; padding: 0 12px 12px 12px;">고객센터</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li><span style="border: 1px solid ; border-radius: 5px; padding: 3px 5px;cursor: pointer; <?= ($_SERVER['REQUEST_URI'] != "/" ? "background-color: #57CCAF; color: #fff;" : "") ?>" onclick="fGoRecruit();">채용공고 등록</span></li>
                            <?php } ?>
                        </ul>
                        <div style="position: absolute; top: 44px;">
                            <?php $current_user_info = $this->user->isLogin() ? $this->user->getCurrentUserInfo() : []; ?>
                            <script>
                                function fGoRecruit() {
                                    //로그인 여부 확인
                                    if ($("#is_login").val() != "Y") {
                                        commonLogin();
                                        return;
                                    }


                                    <?php if ($this->user->getUserType() != 'COMP') { ?>
                                        commonAlert("기업회원만 채용공고 작성이 가능합니다.", function() {});
                                        return;
                                    <?php } ?>

                                    if (!requireCompCertWithGuide()) {
                                        return;
                                    }

                                    // 기업회원일 때 채용공고 개수 확인 후 이동
                                    $.ajax({
                                        url: "/compage_proc/getRecruitCount",
                                        type: "post",
                                        dataType: "json",
                                        success: function(ret) {
                                            if (ret.code == "00") {
                                                var recruitCount = ret.data.count || 0;

                                                // 채용공고가 0개이면 등록 페이지로, 1개 이상이면 목록 페이지로 이동
                                                if (recruitCount == 0) {
                                                    location.href = "/compage/recruit_write";
                                                } else {
                                                    location.href = "/compage/recruit_list";
                                                }
                                            } else {
                                                commonAlert(ret.msg || "채용공고 개수를 확인할 수 없습니다.", function() {});
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.error("채용공고 개수 확인 오류:", error);
                                            commonAlert("채용공고 개수를 확인할 수 없습니다.", function() {});
                                        }
                                    });
                                }

                                /**
                                 * 기업정보 입력 팝업 호출 (미인증 기업회원용)
                                 */
                                function openCompInfoPopup() {
                                    var $dimwrap = $('#dimwrap');
                                    var $compPopup = $('#dimcont');

                                    if (!$dimwrap.length || !$compPopup.length) {
                                        commonAlert("기업정보 입력 팝업을 불러올 수 없습니다.<br>페이지를 새로고침 후 다시 시도해주세요.", function() {});
                                        return;
                                    }

                                    // if (typeof fMemUpdateType === 'function') {
                                    //     fMemUpdateType('all');
                                    // }
                                    $dimwrap.show();
                                    $compPopup.show();
                                }

                                function ensureCompInfoPopup() {
                                    if (!window.NOWJOB_COMP_INFO) return true;
                                    if (!NOWJOB_COMP_INFO.isLogin) return true;
                                    if (NOWJOB_COMP_INFO.userType !== 'COMP') return true;
                                    if (NOWJOB_COMP_INFO.hasCompCert) return true;
                                    openCompInfoPopup();
                                    return false;
                                }

                                function requireCompCertWithGuide() {
                                    if (!window.NOWJOB_COMP_INFO || !NOWJOB_COMP_INFO.isLogin) {
                                        if (typeof commonLogin === 'function') {
                                            commonLogin();
                                        } else {
                                            location.href = "/member/login";
                                        }
                                        return false;
                                    }

                                    if (NOWJOB_COMP_INFO.userType !== 'COMP') {
                                        commonAlert("기업회원만 채용공고 작성이 가능합니다.", function() {});
                                        return false;
                                    }

                                    if (!NOWJOB_COMP_INFO.hasCompCert) {
                                        // 안내 팝업 없이 바로 사업자등록 바텀시트 오픈
                                        openCompInfoPopup();
                                        return false;
                                    }

                                    return true;
                                }

                                function fGoResume() {
                                    //로그인 여부 확인
                                    if ($("#is_login").val() != "Y") {
                                        commonLogin();
                                        return;
                                    }


                                    <?php if ($this->user->getUserType() != 'MEM') { ?>
                                        commonAlert("일반회원만 이력서 작성이 가능합니다.", function() {});
                                        return;
                                    <?php } ?>

                                    // 개인회원일 때 이력서 개수 확인 후 이동
                                    $.ajax({
                                        url: "/mypage_proc/getResumeCount",
                                        type: "post",
                                        dataType: "json",
                                        success: function(ret) {
                                            if (ret.code == "00") {
                                                var resumeCount = ret.data.count || 0;

                                                // 이력서가 0개이면 등록 페이지로, 1개 이상이면 목록 페이지로 이동
                                                if (resumeCount == 0) {
                                                    location.href = "/mypage/resume_write";
                                                } else {
                                                    location.href = "/mypage/resume_list";
                                                }
                                            } else {
                                                commonAlert(ret.msg || "이력서 개수를 확인할 수 없습니다.", function() {});
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.error("이력서 개수 확인 오류:", error);
                                            commonAlert("이력서 개수를 확인할 수 없습니다.", function() {});
                                        }
                                    });
                                }


                                $(document).ready(function() {
                                    //console.log('ready');
                                    //미확인 메시지 카운트
                                    //socket.emit("message_user_read_count",{user_id : $("#user_id").val()});
                                    //console.log('message_user_read_count['+$("#user_id").val()+']');
                                    
                                    // DOM이 완전히 로드된 후 실행 (chatting_cont.php include 후)
                                    setTimeout(function() {
                                        fSearchNoReadCountMainBottom();
                                    }, 100);
                                });


                                function fSearchNoReadCountMainBottom() {
                                    // 로그인 체크
                                    if ($("#is_login").val() != "Y") {
                                        return;
                                    }

                                    // chatting_cont.php의 fSearchNoReadCount()와 동일한 로직 사용
                                    // 방 목록 필터링 없이 모든 안읽은 메시지 카운트
                                    $.ajax({
                                        url: '/chat_proc/fSearchNoReadCount',
                                        type: 'GET',
                                        dataType: 'json',
                                        success: function(ret) {
                                            if (ret.code != "00" || !ret.data || !ret.data.result) {
                                                return;
                                            }

                                            // Extract the result object
                                            const results = ret.data.result;

                                            // 전체 안읽은 메시지 카운트 계산
                                            var countAll = 0;
                                            for (const key in results) {
                                                if (results.hasOwnProperty(key)) {
                                                    const result = results[key];
                                                    const { rm_messages } = result;
                                                    
                                                    if (rm_messages) {
                                                        rm_messages.forEach(message => {
                                                            if (message.mg_reads) {
                                                                message.mg_reads.forEach(read => {
                                                                    if (read.read_yn === 'N' && read.user_id === $("#user_id").val()) {
                                                                        countAll++;
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    }
                                                }
                                            }

                                            // noreadcountall 업데이트
                                            var $noreadcountall = $('#noreadcountall');
                                            if ($noreadcountall.length > 0) {
                                                if (countAll > 0) {
                                                    if (countAll > 99) {
                                                        $noreadcountall.html('99+');
                                                        $noreadcountall.css('width', '40px');
                                                    } else {
                                                        $noreadcountall.html(countAll);
                                                        $noreadcountall.css('width', '30px');
                                                    }
                                                    $noreadcountall.show();
                                                } else {
                                                    $noreadcountall.hide();
                                                }
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('미확인 메시지 카운트 조회 오류:', error);
                                        }
                                    });
                                }


                                //미확인 메시지 카운트 리턴
                                socket.on("realtime_message", function(ret) {
                                    /*
                                      console.log(ret);
                                      console.log(ret.not_read);
                                      if(ret.not_read > 0){
                                          if(ret.not_read > 99){
                                              $('#noreadcountall').html('99+');
                                              $('#noreadcountall').css('width','40px');
                                          }else{
                                              $('#noreadcountall').html(ret.not_read);
                                          }
                                          $('#noreadcountall').show();
                                      }else{
                                          $('#noreadcountall').hide();
                                      }
                                    */
                                });
                            </script>


                        </div>


                    <?php endif; ?>
                    <?php include_once(get_base_url() . '/application/shop/views/web/_layout/search_cont.php'); ?>
                    </div>
                </div>
    </header>
    <?= $contents ?>
    <?php
    if ($this->user->isLogin() && $this->user->getUserType() == 'COMP') {
        $CI = &get_instance();
        $CI->load->helper('comp_popup_helper');
        $popup_payload = get_comp_popup_payload($this->user->getUserIdx());
        if (!isset($comp_popup_info)) {
            $comp_popup_info = $popup_payload['info'];
        }
        if (!isset($comp_popup_enc_data)) {
            $comp_popup_enc_data = $popup_payload['enc'];
        }
        if (!isset($comp_popup_reqgbn) && isset($reqgbn)) {
            $comp_popup_reqgbn = $reqgbn;
        }
        include_once(get_base_url() . '/application/shop/views/web/_partial/comp_info_popup.php');
    }
    ?>
    <script>
        window.NOWJOB_COMP_INFO = {
            isLogin: <?= $this->user->isLogin() ? 'true' : 'false' ?>,
            userType: '<?= $this->user->isLogin() ? $this->user->getUserType() : '' ?>',
            hasCompCert: <?= ($this->user->isLogin() && $this->user->getUserType() == 'COMP' && !empty($current_user_info["mm_comp_num"])) ? 'true' : 'false' ?>
        };
    </script>
    <footer id="footer">
        <div class="container">
            <div class="footer-top">
                <div class="company-name"><span class="now-text"><span class="n-letter">N</span><span class="o-letter">O</span><span class="w-letter">W</span></span> D&I Co., Ltd (주)나우디앤아이 <button class="toggle-btn" aria-label="회사 정보 열기/닫기">
                        <span class="icon-right" style="display:inline;vertical-align:middle;">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <polyline points="7,5 13,10 7,15" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="icon-down" style="display:none;vertical-align:middle;">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <polyline points="5,7 10,13 15,7" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </button></div>
                <div class="footer-contact">
                    <ul class="social">
                        <li><a href="https://blog.naver.com/nowjobofficial" target="_blank" class="social4"><span class="sr-text">블로그 바로가기</span></a></li>
                        <li><a href="https://www.instagram.com/nowjob_nou" target="_blank" class="social1"><span class="sr-text">인스타 바로가기</span></a></li>
                        <li><a href="https://pf.kakao.com/_xbgvUn" target="_blank" class="social3"><span class="sr-text">카카오채널 바로가기</span></a></li>
                    </ul>
                    <p class="title">지금 뜨는, 좋은 일자리 <em>나우잡</em></p>
                    <p class="tel">고객센터 <em>032-833-0018</em></p>
                </div>
            </div>
            <ul class="footer-menu">
                <li>
                    <a href="/home/about">ABOUT US</a>
                    <small>회사소개</small>
                </li>
                <li>
                    <a href="/home/site_policy">TERMS OF USE</a>
                    <small>이용약관</small>
                </li>
                <li>
                    <a href="/home/site_privacy">PERSONAL PROTECTION POLICY</a>
                    <small>개인정보처리방침</small>
                </li>
                <li>
                    <a href="/home/site_terms3">Location Services Terms of Use</a>
                    <small>위치서비스 이용약관</small>
                </li>
                <li>
                    <a href="/community/faq_list">FAQ</a>
                    <small>자주 묻는 질문</small>
                </li>
                <li>
                    <a href="/community/notice_list">NOTICE</a>
                    <small>공지사항</small>
                </li>
            </ul>
            <div class="footer-info hidden">
                <p>
                    Representative name : HAN OG CHOI │ Business registration number : 634-88-03093 │ Mail order business : 2025-인천연수구-1886 <br />
                    Address : 14, Songdogukje-daero 286beon-gil, Yeonsu-gu, Incheon</br>
                    Business of dissemination job information : J15000202540002
                </p>
                <p class="copyright">Copyright NOW D&I Co.,Ltd all right reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.querySelector('.toggle-btn');
            const footerInfo = document.querySelector('.footer-info');
            const footer = document.getElementById('footer');
            const iconRight = document.querySelector('.icon-right');
            const iconDown = document.querySelector('.icon-down');

            if (toggleBtn && footerInfo) {
                // 초기 상태: hidden 클래스로 숨김
                iconRight.style.display = 'inline';
                iconDown.style.display = 'none';
                toggleBtn.setAttribute('aria-label', '회사 정보 열기');

                // 클릭 이벤트: 클릭 시에만 작동
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const isHidden = footerInfo.classList.contains('hidden');

                    if (isHidden) {
                        // 열기: hidden 클래스 제거
                        footerInfo.classList.remove('hidden');
                        iconRight.style.display = 'none';
                        iconDown.style.display = 'inline';
                        toggleBtn.setAttribute('aria-label', '회사 정보 닫기');

                        // footer 정보가 열릴 때마다 스크롤을 footer로 이동
                        setTimeout(() => {
                            const footerBottom = footer.offsetTop + footer.offsetHeight;
                            window.scrollTo({
                                top: footerBottom - window.innerHeight,
                                behavior: 'smooth'
                            });
                        }, 50); // 애니메이션 없으므로 짧은 지연
                    } else {
                        // 닫기: hidden 클래스 추가
                        footerInfo.classList.add('hidden');
                        iconRight.style.display = 'inline';
                        iconDown.style.display = 'none';
                        toggleBtn.setAttribute('aria-label', '회사 정보 열기');
                    }
                });
            }
        });
    </script>


    <a href="#chat" id="bottomchatbtn" role="button" aria-haspopup="dialog" aria-controls="chatPopup" class="btn-chat js-open-chat"><span class="sr-text">채팅열기</span><span id="noreadcountall" style="display:none;right: 0; position: absolute; background-color: #F86048; color: #fff; padding: 5px; border-radius: 15px; width: 30px; text-align: center;"></span></a>


    <?php include_once(get_base_url() . '/application/shop/views/web/_layout/chatting_cont.php'); ?>

    <!-- 알림목록 -->
    <?php include_once(get_base_url() . '/application/shop/views/web/_layout/popup_alarm.php'); ?>




    <!-- //채팅팝업 -->
    <a href="#header" role="button" class="btn-top js-goto-top"><span class="sr-text">맨위로</span></a>




    <!-- 현재위치 변경 레이어팝업 -->
    <div id="chgPwdCont" class="layer-panel card-type" tabindex="-1" style="position:fixed;">
        <div class="panel-container" style="max-width:500px;margin:0 auto;top:50%;margin-top:-270px;height:540px;">
            <div class="panel-header">
                <h2 class="sr-text">비밀번호 변경</h2>
                <button type="button" aria-controls="chgPwdCont" class="btn-close-popup js-toggle-panel btnClosePwd"><span class="sr-text">닫기</span></button>
            </div>
            <div class="panel-body">
                <div class="panel-content">
                    <div class="page-join">
                        <h2 class="primary-title align-center">비밀번호 변경<h2>
                                <div class="find-loc-form">
                                    <form id="frm_pwd" onsubmit="return false">
                                        <div class="form-row">
                                            <label>
                                                <span class="form-label" style="font-size:18px;">기존 비밀번호</span>
                                                <input type="password" id="mm_pwd" name="mm_pwd" autocomplete="off" aria-required="true" required class="form-text">
                                            </label>
                                        </div>


                                        <div class="form-row">
                                            <label>
                                                <span class="form-label" style="font-size:18px;">새 비밀번호</span>
                                                <input type="password" id="change_pwd" name="change_pwd" autocomplete="off" aria-required="true" required class="form-text">
                                            </label>
                                            <p class="help-msg rule" style="padding-left:0px;">비밀번호는 8~20자 이내, 숫자/특수문자/영문대소문자를 사용해 주세요.</p>
                                        </div>


                                        <div class="form-row">
                                            <label>
                                                <span class="form-label" style="font-size:18px;">새 비밀번호 확인</span>
                                                <input type="password" id="change_pwd_c" name="change_pwd_c" autocomplete="off" aria-required="true" required class="form-text">
                                            </label>
                                        </div>


                                        <div class="sticky-bottom" style="margin-top:20px;">
                                            <button type="button" id="btnChgPwd" class="btn btn-primary" style="width:100%;padding:16px;">변경하기</button>
                                        </div>


                                    </form>
                                </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <!-- Script -->
    <?= getCashJs(RES_PATH . "/js/lib/lozad.min.js") ?>
    <?= getCashJs(RES_PATH . "/js/lib/keen-slider.js") ?>
    <?= getCashJs(RES_PATH . "/js/lib/flatpickr.js") ?>
    <?= getCashJs(RES_PATH . "/js/lib/l10n/ko.js") ?>
    <?= getCashJs(RES_PATH . "/js/lib/plugins/monthSelect/index.js") ?>
    <?= getCashJs(RES_PATH . "/js/main.js") ?>


    <input type="hidden" id="is_login" name="is_login" value="<?= $this->user->isLogin() == true ? "Y" : "N" ?>" />
    <input type="hidden" id="user_id" name="user_id" value="<?= $this->user->isLogin() == true ? $this->user->getUserId() : "" ?>" />
    <input type="hidden" id="os_type" name="os_type" value="web" />

</body>
<script>
    function fGoEvent(gbn, arg) {


        if ($('#logingbn').val() == gbn) {
            //location.href=arg;
            window.open(arg);
        } else {
            return false;
        }
    }
</script>

</html>
