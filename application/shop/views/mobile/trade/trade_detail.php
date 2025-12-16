<div class="page page-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">중고거래</h1>
        <span class="rt-group">
            <a href="/trade/trade_write" class="btn-action">상품등록</a>
            <a href="#sharePanel" aria-haspopup="dialog" aria-controls="sharePanel" class="btn-share js-toggle-panel"><span class="sr-text">공유하기</span></a>
        </span>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="view-visual-slider">
            <div class="slider-wrap keen-slider">
                <?php if($info["td_img1"]!="") : ?>
                <div class="keen-slider__slide">
                    <img data-src="<?=$info["td_img1"]?>" alt="" class="lozad">
                </div>
                <?php endif; ?>
                <?php if($info["td_img2"]!="") : ?>
                <div class="keen-slider__slide">
                    <img data-src="<?=$info["td_img2"]?>" alt="" class="lozad">
                </div>
                <?php endif; ?>
                <?php if($info["td_img3"]!="") : ?>
                <div class="keen-slider__slide">
                    <img data-src="<?=$info["td_img3"]?>" alt="" class="lozad">
                </div>
                <?php endif; ?>
                <?php if($info["td_img4"]!="") : ?>
                <div class="keen-slider__slide">
                    <img data-src="<?=$info["td_img4"]?>" alt="" class="lozad">
                </div>
                <?php endif; ?>
                <?php if($info["td_img5"]!="") : ?>
                <div class="keen-slider__slide">
                    <img data-src="<?=$info["td_img5"]?>" alt="" class="lozad">
                </div>
                <?php endif; ?>
            </div>
        </div>

        <script>
            window.addEventListener('DOMContentLoaded', function () {
                new KeenSlider(".view-visual-slider .slider-wrap", {
                    loop: true,
                }, [
                    sliderDots,
                    addActiveClass
                ]);
            });
        </script>

        <div class="section view-header">
            <!-- 즐겨찾기 -->
            <button type="button" class="btn-view-bookmark js-toggle-bookmark">
                <span class="sr-text">즐겨찾기 추가</span>
            </button>
            <div class="product-summary">
                <div class="container">
                    <div class="title-wrap">
                        <!-- 상품카테고리 -->
                        <p class="market-category"><?=$info["td_category"]?></p>
                        <!-- 제목 -->
                        <h2 class="view-title"><?=$info["td_name"]?></h2>
                        <div class="meta">
                            <!-- 작성일 -->
                            <p class="date"><?=get_yyyymmdd($info["reg_dt"])?></p>
                            <!-- 총 즐겨찾기수 -->
                            <p class="total-bookmark">
                                <span class="sr-text">총 즐겨찾기수</span>
                                <em>1</em>
                            </p>
                            <!-- 조회수 -->
                            <p class="total-view">
                                <span class="sr-text">조회수</span>
                                <em><?=$info["td_hit"]?></em>
                            </p>
                        </div>
                    </div>
                    <div class="price">
                        <p class="nego"><?=$info["td_price_req_yn"]=="Y" ? "가격제안불가":""?></p>
                        <p class="amount"><em><?=number_format($info["td_price"])?></em>원</p>
                    </div>
                    <div class="desc">
                        <?=str_replace("\n","<br/>",$info["td_desc"])?>
                    </div>
                    <!-- 신고하기 -->
                    <button type="button" id="btnReport" aria-haspopup="dialog" aria-controls="reportForm1" class="btn-report js-report"><span class="sr-text">신고하기</span></button>
                </div>
            </div>


            <div class="seller-profile">
                <div class="container">
                    <div class="item-info person">
                        <div class="thumb-wrap">
                            <div class="thumb">
                                <a href="#"><img data-src="<?=getMemberImg($mem_trade_info)?>" alt="" class="lozad"></a>
                            </div>
                        </div>
                        <div class="content-wrap">
                            <!-- 이름 -->
                            <p class="title"><a href="#"><?=getAstrik($mem_trade_info["mm_name"])?></a></p>
                            <!-- 추가사항 -->
                            <div class="meta">
                                <p class="loc">
                                	<?php foreach($addr_list as $key=>$row) : ?>
                                		<?=$key!=0 ? ",":""?><?=$row["ad_addr"]?>
                                	<?php endforeach; ?>
                                </p>
                                <!-- 해시태그 -->
                                <div class="hashtag">
                                    <?=getHashTag($mem_trade_info["td_hash"])?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section view-recomm">
            <div class="container">
                <h3 class="secondary-title">추천 중고상품</h3>
                <div class="recomm-list">
                    <div class="slider-wrap keen-slider">
						<?php foreach($reco_list as $row) : ?>
                        <div class="keen-slider__slide">
                            <div class="item-info sale column">
                                <div class="thumb-wrap">
                                    <!-- 추천라벨 -->
                                    <p class="recomm-badge"><span>추천</span></p>
                                    <!-- 즐겨찾기 추가된 목록: on -->
                                    <button type="button" data-bk_type="TRADE" data-bk_key="<?=$row["td_idx"]?>" class="btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>"><span class="sr-text">즐겨찾기 해제</span></button>
                                    <!-- 썸네일 -->
                                    <div class="thumb">
                                        <a href="/trade/trade_detail/<?=$row["td_idx"]?>"><img data-src="<?=getTradeImg($row)?>" alt="" class="lozad"></a>
                                    </div>
                                </div>
                                <div class="content-wrap">
                                    <p class="title"><strong><a href="/trade/trade_detail/<?=$row["td_idx"]?>"><?=$row["td_name"]?></a></strong></p>
                                    <div class="meta">
                                        <p class="area"><?=$row["ad_addr"]?></p>
                                    </div>
                                    <div class="price-wrap">
                                        <p class="amount"><em class="num"><?=number_format($row["td_price"])?></em> 원</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <script>
                    window.addEventListener('DOMContentLoaded', function () {
                        new KeenSlider(".view-recomm .slider-wrap", {
                            loop: false,
                            mode: 'free',
                            slides: { perView: "auto" },
                        });
                    });
                </script>
            </div>
        </div>
		<?php if($info["mm_idx"]==$this->user->getUserIdx()) : ?>
			<?php if($info["td_status"]=="Y") : ?>
			<div class="view-footer">
	            <button id="btnDelete" type="button" aria-haspopup="dialog" class="btn btn-secondary js-remove">판매완료</button>
	            <button id="btnEdit" type="button" class="btn btn-primary">수정</button>
	        </div>
	        <?php else : ?>
	        	<div class="view-footer">
		            <button type="button" aria-haspopup="dialog" class="btn btn-secondary js-remove">판매종료</button>
		        </div>
	        <?php endif; ?>
		<?php else : ?>
        <div class="view-footer">
            <a href="javascript:void(0);" id="btnChat"
            data-td_idx="<?=$info["td_idx"]?>"
            data-target_id="<?=$mem_trade_info["mm_id"]?>"
            data-user_id="<?=$mem_info["mm_id"]?>"
            data-target_img="<?=$mem_trade_info["mm_img1"]?>"
            data-user_img="<?=$mem_info["mm_img1"]?>"
            data-target_name="<?=$mem_trade_info["mm_name"]?>"
            data-user_name="<?=$mem_info["mm_name"]?>"
            data-subject="<?=$info["td_name"]?>" class="btn btn-primary">채팅으로 거래하기</a>
        </div>
        <?php endif; ?>
    </div><!-- //page-content -->
</div>

<!-- 공유하기 팝업 -->
<div id="sharePanel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="sr-text">공유하기</h2>
            <button type="button" aria-controls="sharePanel" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <div class="vendor-links">
                    <a href="#" onclick="shareMessage();return false;" class="share-link1">
                        <span class="ico"><img src="/resource/shop/mobile/img/share_vendor1.svg" alt=""></span>
                        <span>카카오톡</span>
                    </a>
                    <a href="#" onclick="CopyUrl();return false;" class="share-link2">
                        <span class="ico"><img src="/resource/shop/mobile/img/share_copy_url.svg" alt=""></span>
                        <span>URL복사</span>
                    </a>
                </div>

                <script defer src="https://developers.kakao.com/sdk/js/kakao.js"></script>
                <script>
                    /* 카카오톡 공유 */
                    function shareMessage() {
                        if (!Kakao.isInitialized()) {
                            Kakao.init("<?=$kakao_key["인증키"]?>");
                        }

                        var title = document.querySelector('.view-title').textContent.replace(/\s/g,''),
                            desc = document.querySelector('.product-summary .desc').textContent.replace(/\s/g,''),
                            imgUrl = document.querySelector('.view-visual-slider img').getAttribute('src');
                            url = location.href,
                            appUrl = 'scheme://host_path';

                        setTimeout(function () {
                            Kakao.Share.sendDefault({
                                objectType: 'feed',
                                content: {
                                    title: title,//글제목
                                    description: desc,//글내용
                                    imageUrl: imgUrl,//대표이미지 절대주소
                                    link: {
                                        mobileWebUrl: url,
                                    },
                                },
                                buttons: [
                                    {
                                        title: '웹으로 보기',
                                        link: {
                                            mobileWebUrl: url,
                                            webUrl: url
                                        },
                                    },
                                    {
                                        title: '앱으로 보기',
                                        link: {
                                            mobileWebUrl: appUrl,
                                            webUrl: url
                                        },
                                    },
                                ],
                            });
                        }, 100);
                    }

                    /* URL 복사 */
                    function CopyUrl(){
                        var dummy = document.createElement('input'),
                            url = location.href;

                        document.body.appendChild(dummy);
                        dummy.value = url;
                        dummy.select();
                        document.execCommand('copy');
                        document.body.removeChild(dummy);
                        openPopup('alert',{content:'URL을 복사했습니다.'});
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<!-- //공유하기 팝업 -->

<!-- 길찾기 팝업 -->
<div id="findLocation" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="sr-text">길찾기 지도선택</h2>
            <button type="button" aria-controls="findLocation" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <div class="vendor-links">
                    <a href="#" class="vendor-link1">
                        <span class="ico"><img src="/resource/shop/mobile/img/map_vendor1.jpg" alt=""></span>
                        <span>네이버</span>
                    </a>
                    <a href="#" class="vendor-link2">
                        <span class="ico"><img src="/resource/shop/mobile/img/map_vendor2.jpg" alt=""></span>
                        <span>카카오</span>
                    </a>
                    <a href="#" class="vendor-link3">
                        <span class="ico"><img src="/resource/shop/mobile/img/map_vendor3.jpg" alt=""></span>
                        <span>티맵</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- //길찾기 팝업 -->
 <!-- 신고하기 팝업 내용 -->
<template id="ReportTemp">
    <div class="alert-msg">
        <p>해당게시글을 신고하시겠습니까?<br> 신고사유를 작성해주세요</p>
        <div class="report-form">
            <label>
                <span class="sr-text">신고사유입력</span>
                <textarea name="br_desc" placeholder="신고사유를 작성해주세요" class="form-textarea js-autoheight"></textarea>
            </label>
        </div>
    </div>
</template>
<!-- //신고하기 팝업 내용 -->
<input type="hidden" name="td_idx" value="<?=$info["td_idx"]?>" />
<script>
	$(document).ready(function(){
		$("#btnEdit").click(function(){
			location.href="/<?=$mem_info["mm_type"]=="MEM" ? "mypage":"compage"?>/trade_edit/" + $("[name=td_idx]").val();
		});

		$("#btnDelete").click(function(){
			var content = "상품을 판매완료 처리 하시겠습니까?";
			openPopup('confirm', {
	            content:content,
	            firstButton:'취소',
	            secondButton:'확인',
	            firstCallback: function () {
	            },
	            secondCallback:  function () {
	                var params = {};
        			params["td_idx"] = $("[name=td_idx]").val();
        			comAjax("/trade_proc/trade_status_proc",params,function(ret){
        				if(ret.code=="00"){
                            location.reload();
        				}else{
                            commonAlert("처리시 오류가 발생하였습니다.관리자에게 문의 바랍니다.",function(){});
        				}
        			});
	            },
	        });
		});

		$("#btnReport").click(function(){
            var content = document.querySelector('#ReportTemp').innerHTML;
            var popup = openPopup('modal', {
                id:'ReportWriter',
                title:'신고하기',
                content: content,
                firstButton: '확인',
                removeClose : false,
                clickOnce : false,
                firstCallback: function () {
                    if($("[name=br_desc]").val()==""){
                        commonAlert("내용을 입력해주세요.",function(){
                            $("[name=br_desc]").focus();
                        });
                        return false;
                    }
                    var params = {};
        			params["bd_idx"] = $("[name=td_idx]").val();
        			params["br_type"] = "trade";
                    params["br_desc"] = $("[name=br_desc]").val();
        			comAjax("/setting_proc/board_report",params,function(ret){
        				if(ret.code=="00"){
                            removeElement("#ReportWriter1");
        					commonAlert("신고 처리 되었습니다.",function(){
                            });
        				}else{
                            commonAlert("신고 처리시 오류가 발생하였습니다.",function(){});
        				}
        			});
                }
            });
        });
	});
</script>

<?php if($this->common->getSession("os_type")=="android") : ?>
<script>
	//방생성 확인 및 방번호 확인
    function room_insert_return(data){
    	var ret = JSON.parse(data);
    	if(ret.code=="00"){
            if(first_yn=="Y"){
                APP_NOWAPK.message_insert(JSON.stringify(first_message),JSON.stringify(first_users));
            }
    		location.href="/chat/trade_list?view=true&rm_idx=" + ret.data;
    	}else{
    		alert(ret.data);
    		commonAlert(ret.msg,function(){});
    	}
    }
</script>

<script>
    var first_yn = "N";
    var first_message = {};
    var first_users = new Array();

	var g_rm_type = "trade";
	var g_rc_idx = 0;
	var g_ru_idx = 0;
	var g_td_idx = 0;

    //룸생성처리
    $(document).on("click","#btnChat",function(){
        var target_id = $(this).data("target_id");
        var user_id = $(this).data("user_id");
        var target_img = $(this).data("target_img");
        var user_img = $(this).data("user_img");
        var target_name = $(this).data("target_name");
        var user_name = $(this).data("user_name");
        var subject = $(this).data("subject");

        //키값 설정
        g_td_idx = $(this).data("td_idx");

        var params = {};
		params["rm_type"] = g_rm_type;
	    params["rc_idx"] = g_rc_idx;
	    params["ru_idx"] = g_ru_idx;
	    params["td_idx"] = g_td_idx;

	    comAjax("/chat_proc/insert_room",params,function(ret2){
	        if(ret2.code=="00"){
	        	var chat_suer = [];
                chat_suer.push({user_id : user_id,img : user_img,user_name : user_name});
                chat_suer.push({user_id : target_id,img : target_img,user_name : target_name});

		        var room_info = {
		            user_id : user_id,
		            user_list : chat_suer,
		            rm_idx : Number(ret2.data.rm_idx),
		            rm_type : "trade",
		            rm_img : "",
		            rm_subject : subject,
		            rm_desc : "",
		        };

                //기본 문구 전송
                first_yn = ret2.data.first_yn;
                first_message = {
                    rm_idx : Number(ret2.data.rm_idx),
                    user_id : user_id,
                    chat_type : "recruit",
                    mg_type : "text",
                    mg_text : "중고거래 채팅을 시작합니다!",
                    mg_req_dt : "",
                    mg_img : "",
                    mg_file : "",
                    mg_imoti : "",
                    mg_dt : getCurrentDate(),
                    mg_reads : new Array(),
                };
                first_message.mg_reads.push({read_yn : "Y",user_id : user_id});
                first_message.mg_reads.push({read_yn : "N",user_id : target_id});

                //회원정보
                first_users.push({user_id : user_id,user_nick : user_name});
                first_users.push({user_id : target_id,user_nick : target_name});

		        APP_NOWAPK.room_insert(JSON.stringify(room_info));
	        }else{
	            commonAlert(ret2.msg,function(){});
	            return;
	        }
	    });
    });
</script>
<?php else: ?>
<script>
	//방생성 확인 및 방번호 확인
    socket.on("room_insert_return", function(ret) {
    	if(ret.code=="00"){
            if(first_yn=="Y"){
                socket.emit("message_insert",first_message,first_users);
            }
    		location.href="/chat/trade_list?view=true&rm_idx=" + ret.data;
    	}else{
    		commonAlert(ret.msg,function(){});
    	}
    });
</script>

<script>
    var first_yn = "N";
    var first_message = {};
    var first_users = new Array();

	var g_rm_type = "trade";
	var g_rc_idx = 0;
	var g_ru_idx = 0;
	var g_td_idx = 0;

    //룸생성처리
    $(document).on("click","#btnChat",function(){
        var target_id = $(this).data("target_id");
        var user_id = $(this).data("user_id");
        var target_img = $(this).data("target_img");
        var user_img = $(this).data("user_img");
        var target_name = $(this).data("target_name");
        var user_name = $(this).data("user_name");
        var subject = $(this).data("subject");

        //키값 설정
        g_td_idx = $(this).data("td_idx");

        var params = {};
		params["rm_type"] = g_rm_type;
	    params["rc_idx"] = g_rc_idx;
	    params["ru_idx"] = g_ru_idx;
	    params["td_idx"] = g_td_idx;

	    comAjax("/chat_proc/insert_room",params,function(ret2){
	        if(ret2.code=="00"){
	        	var chat_suer = [];
                chat_suer.push({user_id : user_id,img : user_img,user_name : user_name});
                chat_suer.push({user_id : target_id,img : target_img,user_name : target_name});

		        var room_info = {
		            user_id : user_id,
		            user_list : chat_suer,
		            rm_idx : Number(ret2.data.rm_idx),
		            rm_type : "trade",
		            rm_img : "",
		            rm_subject : subject,
		            rm_desc : "",
		        };

                //기본 문구 전송
                first_yn = ret2.data.first_yn;
                first_message = {
                    rm_idx : Number(ret2.data.rm_idx),
                    user_id : user_id,
                    chat_type : "recruit",
                    mg_type : "text",
                    mg_text : "중고거래 채팅을 시작합니다!",
                    mg_req_dt : "",
                    mg_img : "",
                    mg_file : "",
                    mg_imoti : "",
                    mg_dt : getCurrentDate(),
                    mg_reads : new Array(),
                };
                first_message.mg_reads.push({read_yn : "Y",user_id : user_id});
                first_message.mg_reads.push({read_yn : "N",user_id : target_id});

                //회원정보
                first_users.push({user_id : user_id,user_nick : user_name});
                first_users.push({user_id : target_id,user_nick : target_name});

		        socket.emit("room_insert",room_info);
	        }else{
	            commonAlert(ret2.msg,function(){});
	            return;
	        }
	    });
    });
</script>
<?php endif; ?>
