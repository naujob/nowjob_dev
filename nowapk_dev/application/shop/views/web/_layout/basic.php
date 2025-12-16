<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=1920">
    <title>지금 뜨는, 좋은 일자리 나우잡(NOWJOB)</title>
    <meta name="description" content="구인구직 정보 플랫폼">
    <!-- Google Fonts - Noto Sans KR -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="지금 뜨는, 좋은 일자리 NOW JOB">
    <meta property="og:url" content="https://nowjob.co.kr/">
    <meta property="og:description" content="구인구직 정보 플랫폼">
    <meta property="og:image" content="https://nowjob.co.kr/resource/shop/web/img/nowjob_info.png">
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://nowjob.co.kr/">
    <meta property="twitter:description" content="구인구직 정보 플랫폼">
    <meta property="twitter:image" content="https://nowjob.co.kr/resource/shop/web/img/nowjob_info.png">
    <!--<meta name="naver-site-verification" content="20459c5bd907c133c314dd88161a57f212fce831" />-->
    <meta name="naver-site-verification" content="2744b73772ebd4a579c885de8f8d22bb42035a89" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="https://nowjob.co.kr/resource/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/resource/favicon.ico" type="image/x-icon">


    <?= getCashJs(RES_PATH . "/js/lib/jquery-3.5.1.min.js") ?>
    <?= getCashJs(RES_PATH . "/js/lib/proj4.js") ?>
    <?= getCashJs(RES_PATH . "/js/lib/preview/jquery.magnific-popup.min.js") ?>
    <?= getCashJs(RES_PATH . "/js/add.js") ?>
    <script src="https://nowjob.co.kr:3030/socket.io/socket.io.js?v=0001"></script>
    <script>
        // Socket.IO 즉시 초기화 (DOM 로드 대기 없음)
        (function() {
            // Socket.IO 라이브러리 로드 확인
            if (typeof io === 'undefined') {
                console.error('Socket.IO 라이브러리를 로드할 수 없습니다.');
                // 대체 CDN에서 로드 시도
                var script = document.createElement('script');
                script.src = 'https://cdn.socket.io/4.5.4/socket.io.min.js';
                script.onload = function() {
                    console.log('Socket.IO 라이브러리 로드 성공 (CDN)');
                    createSocket();
                };
                script.onerror = function() {
                    console.error('Socket.IO 라이브러리 로드 실패');
                };
                document.head.appendChild(script);
            } else {
                createSocket();
            }
        })();

        function createSocket() {
            // Socket 연결 초기화
            const base_url = "https://nowjob.co.kr:3030";
            const socket = io(base_url, {
                secure: true,
                rejectUnauthorized: false, // SSL 인증서 검증 비활성화
                transports: ['websocket', 'polling']
            });

            // 전역 socket 객체 설정
            window.socket = socket;

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
    <script src="/chatting/js/common.js"></script>
    <script src="/chatting/js/chatting.js"></script>


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


    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "Organization",
            "name": "지금 뜨는, 좋은 일자리 나우잡(NOWJOB)",
            "url": "https://nowjob.co.kr/",
            "sameAs": [
                "https://play.google.com/store/apps/details?id=app.nowapk.com&pcampaignid=web_share",
                "https://apps.apple.com/kr/app/%EB%82%98%EC%9A%B0%EC%9E%A1/id6446226901",
                "https://blog.naver.com/nowjobofficial"
            ]
        }
    </script>


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


                        <ul class="account-menu">
                            <li>
                                <div class="dropdown-wrap">
                                    <a href="#memberPanel" role="button" aria-expanded="false" aria-controls="memberPanel" class="js-toggle-dropdown"><em class="user-name"><?= $this->user->getUserName() ?></em>님</a>
                                    <span style="display:none;"><?= $this->user->getUserIdx() ?></span>
                                    <div role="dialog" id="memberPanel" tabindex="-1" class="dropdown" hidden>
                                        <div class="member-card">
                                            <div class="card-body">
                                                <div class="thumb">
                                                    <img src="<?= $this->user->getUserImg() ?>" alt="">
                                                </div>
                                                <div class="profile-meta" style="cursor:pointer;" onclick="location.href='/compage/point_charge_step1';">
                                                    <p class="name"><em><?= $this->user->getUserName() ?></em>님</p>
                                                    <p class="email"><?= $this->user->getUserId() ?></p>
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
                                                <a href="/compage/bookmark_list" style="display:block; padding: 0 12px 12px 12px;">즐겨찾기 관리</a>
                                                <a href="/compage/coupon_list" style="display:block; padding: 0 12px 12px 12px;">쿠폰관리</a>
                                                <!-- a href="/compage/trade_list" style="display:block; padding: 0 12px 12px 12px;color:#ccc;" onclick="return false;">중고거래 관리</a -->
                                                <a href="/compage/interv_list" style="display:block; padding: 0 12px 12px 12px;">면접자관리</a>
                                                <a href="/compage/qna_list" style="display:block; padding: 0 12px 12px 12px;">고객센터</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li><span style="border: 1px solid ; border-radius: 5px; padding: 3px 5px;cursor: pointer; <?= ($_SERVER['REQUEST_URI'] != "/" ? "background-color: #57CCAF; color: #fff;" : "") ?>" onclick="fGoRecruit();">공고 등록</span></li>
                            <?php } ?>
                        </ul>
                        <div style="position: absolute; top: 44px;">
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


                                    <?php $count = $this->member_model->getRecruitTodayCnt($this->mm_idx);
                                    if ($count >= 5) { ?>
                                        openPopup('confirm', {
                                            content: '<p>채용공고등록은 <em>1일 5건 무료</em>입니다<br> 추가로 공고를 등록하시려면<br> 포인트에서 차감됩니다.</p><p class="required-point">채용공고등록: <span>500P</span>차감</p>',
                                            secondButton: '유료등록',
                                            secondCallback: function() {
                                                comAjax("/compage_proc/recruit_start_point", {}, function(ret2) {
                                                    if (ret2.code == "00") {
                                                        location.href = "/compage/recruit_write";
                                                    } else if (ret2.code == "01") {
                                                        commonAlert("보유하신 포인트가 부족합니다.(보유포인트 : " + ret2.data.mm_point.format() + "P)<br/>포인트 구매후 등록부탁드립니다.", function() {});
                                                    } else {
                                                        commonAlert(ret2.msg, function() {});
                                                        return;
                                                    }
                                                });
                                            }
                                        });
                                        return;
                                    <?php } else { ?>
                                        location.href = "/compage/recruit_write";
                                    <?php } ?>
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


                                    location.href = "/mypage/resume_list";
                                }


                                $(document).ready(function() {
                                    //console.log('ready');
                                    //미확인 메시지 카운트
                                    //socket.emit("message_user_read_count",{user_id : $("#user_id").val()});
                                    //console.log('message_user_read_count['+$("#user_id").val()+']');
                                    fSearchNoReadCountMainBottom();
                                });


                                function fSearchNoReadCountMainBottom() {


                                    var room_alert_check_url = '';
                                    <?php if ($this->user->getUserType() == "COMP") { ?>
                                        room_alert_check_url = '/chat/index_comp_ajax';
                                    <?php } else { ?>
                                        room_alert_check_url = '/chat_proc/get_chat_target_list_recruit_mem';
                                    <?php } ?>
                                    room_alert_check_url = '/chat_proc/getRoomListActaive';


                                    var rmIdxArray = [];
                                    $.ajax({
                                        url: room_alert_check_url,
                                        type: 'GET',
                                        dataType: 'json',
                                        success: function(response) {
                                            //console.log('index_comp');
                                            //console.log(response);


                                            //방목록 확인
                                            var roomCnt = 0;
                                            $.each(response.data.list, function(key, item) {
                                                //console.log(item);
                                                //console.log('1:'+item.rm_idx);
                                                //console.log('1:'+item.rm_idx_list);
                                                rmIdxArray.push(item.rm_idx);
                                                roomCnt++;


                                                if (item.rm_idx !== undefined) {
                                                    rmIdxArray.push(item.rm_idx);
                                                }


                                                // item.rm_idx_list가 undefined가 아니고 "(338, 344, 347)" 형태라면 처리
                                                if (item.rm_idx_list !== undefined && /^\(\d+(,\s*\d+)*\)$/.test(item.rm_idx_list)) {
                                                    // 괄호를 제거하고 콤마로 분리
                                                    var idxList = item.rm_idx_list.replace(/[()]/g, "").split(',');


                                                    // 각 숫자를 rmIdxArray에 추가
                                                    idxList.forEach(function(idx) {
                                                        rmIdxArray.push(idx.trim());
                                                    });
                                                }


                                            });
                                            //console.log('111:roomCnt:'+roomCnt);


                                            //node 방 확인 시작
                                            $.ajax({
                                                url: '/chat_proc/fSearchNoReadCount',
                                                type: 'GET',
                                                dataType: 'json',
                                                success: function(response) {
                                                    //console.log(response);
                                                    var myId = $("#user_id").val();


                                                    let result = response.data.result;


                                                    let countAll = 0;


                                                    //console.log(rmIdxArray);


                                                    $.each(result, function(key, room) {
                                                        //console.log(room);
                                                        //console.log('2:'+room.rm_idx);
                                                        //console.log($.inArray(''+room.rm_idx, rmIdxArray));


                                                        var exists = false;
                                                        if ($.inArray('' + room.rm_idx, rmIdxArray) > -1) {
                                                            exists = true;
                                                        } else {
                                                            exists = false;
                                                        }
                                                        //console.log(exists);


                                                        $.each(room.rm_messages, function(index, message) {
                                                            $.each(message.mg_reads, function(i, readEntry) {
                                                                if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
                                                                    //console.log(room);
                                                                    //console.log(message);
                                                                    if (exists) {
                                                                        countAll++;
                                                                        //console.log('bottom count up');
                                                                    }
                                                                }
                                                            });
                                                        });


                                                    });
                                                    if (countAll > 0) {
                                                        //$('#msgNoReadCountMainBottom').html(countAll);
                                                        //$('#msgNoReadCountMainBottom').show();
                                                        if (countAll > 0) {
                                                            if (countAll > 99) {
                                                                $('#noreadcountall').html('99+');
                                                                $('#noreadcountall').css('width', '40px');
                                                            } else {
                                                                $('#noreadcountall').html(countAll);
                                                            }
                                                            $('#noreadcountall').show();
                                                        } else {
                                                            $('#noreadcountall').hide();
                                                        }


                                                    }
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error('AJAX 호출 중 오류 발생:', error);
                                                }
                                            });
                                            //nopde 방 확인 종료


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
                    <p class="tel">고객센터 <em>032-833-0019</em></p>
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
            <div class="footer-info">
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
                // 초기 상태 설정 (접힌 상태)
                footerInfo.style.maxHeight = '0px';
                footerInfo.style.opacity = '0';
                footerInfo.style.transform = 'translateY(-10px)';
                footerInfo.style.margin = '0';
                iconRight.style.display = 'inline';
                iconDown.style.display = 'none';
                toggleBtn.setAttribute('aria-label', '회사 정보 열기');
                footer.style.paddingBottom = '15px';

                // 첫 번째 클릭 시 스크롤을 위해 초기 상태를 열린 상태로 설정
                let isFirstClick = true;

                toggleBtn.addEventListener('click', function() {
                    const isHidden = footerInfo.style.maxHeight === '0px' || footerInfo.style.maxHeight === '';

                    if (isHidden) {
                        // 열기 애니메이션
                        iconRight.style.display = 'none';
                        iconDown.style.display = 'inline';
                        footerInfo.style.maxHeight = '500px';
                        footerInfo.style.opacity = '1';
                        footerInfo.style.transform = 'translateY(0)';
                        footerInfo.style.margin = '10px 0 0';
                        toggleBtn.setAttribute('aria-label', '회사 정보 닫기');
                        footer.style.paddingBottom = '10px';

                        // footer 정보가 열릴 때마다 스크롤을 footer로 이동
                        setTimeout(() => {
                            // 스크롤을 footer의 맨 아래까지 이동
                            const footerBottom = footer.offsetTop + footer.offsetHeight;
                            window.scrollTo({
                                top: footerBottom - window.innerHeight,
                                behavior: 'smooth'
                            });
                        }, 300); // 애니메이션 완료 후 스크롤 실행
                    } else {
                        // 닫기 애니메이션
                        iconRight.style.display = 'inline';
                        iconDown.style.display = 'none';
                        footerInfo.style.maxHeight = '0px';
                        footerInfo.style.opacity = '0';
                        footerInfo.style.transform = 'translateY(-10px)';
                        footerInfo.style.margin = '0';
                        toggleBtn.setAttribute('aria-label', '회사 정보 열기');
                        footer.style.paddingBottom = '15px';
                    }
                });
            }
        });
    </script>


    <a href="#chat" id="bottomchatbtn" role="button" aria-haspopup="dialog" aria-controls="chatPopup" class="btn-chat js-open-chat"><span class="sr-text">채팅열기</span><span id="noreadcountall" style="display:none;right: 0; position: absolute; background-color: #F86048; color: #fff; padding: 5px; border-radius: 15px; width: 30px; text-align: center;"></span></a>


    <?php include_once(get_base_url() . '/application/shop/views/web/_layout/chatting_cont.php'); ?>




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