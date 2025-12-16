<div id="content" class="page page-mypage">
    <main class="page-content">

        <div class="container">
            <?php $tab_on = "tab3";?>
            <?php $tab_on2 = "menu2";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
            <div class="tab-panel on">
                <div class="recruit-bookmark">
                    <h3 class="sr-text">제안받은 지원공고</h3>
                    <div class="result-header">
                        <p class="total">검색결과 <em><?=$count?></em>건</p>
                    </div>
                    <div class="result-wrap">

                        <!-- 목록 -->
                        <ol class="result-list">
                            <?php foreach($list as $row) : ?>
                            <li class="list-item">
                                <div class="item-info person">
                                    <div class="thumb-wrap">
                                        <!-- 추천라벨 -->
                                        <!--p class="recomm-badge"><span>추천</span></p-->
                                        <button type="button" data-bk_type="RESUME" data-bk_key="<?=$row["ru_idx"]?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>"><span class="sr-text">즐겨찾기 추가</span></button>
                                        <!-- 썸네일 -->
                                        <div class="thumb">
                                            <a href="/resume/hr_post/<?=$row["ru_idx"]?>"><img data-src="<?=getResumeImg($row)?>" alt="" class="lozad"></a>
                                        </div>
                                        <!-- 평점 -->
                                        <p class="star-rating">
                                            <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                                            <span class="star" style="width:100%;"></span>
                                            <span class="sr-text">평점</span>
                                            <span class="sr-text">5</span>
                                        </p>
                                    </div>

                                    <div class="content-wrap">
                                        <div class="inline-group">
                                            <div class="col">
                                                <div class="meta top">
                                                    <?=getWorkingImg($row["ru_work"])?>
                                                </div>
                                                <div class="title-wrap">
                                                    <!-- 이름 -->
		                                            <p class="title"><strong><a href="/resume/hr_post/<?=$row["ru_idx"]?>"><?=$row["mm_name"]?></a></strong></p>
		                                            <!-- 추가사항 -->
		                                            <div class="meta">
		                                                <p><?=$row["mm_nation"]?></p>
		                                                <span>/</span>
		                                                <p><?=$row["ma_age"]?>세</p>
		                                                <span>/</span>
		                                                <p><?=$row["ma_sex"]?></p>
		                                            </div>
                                                </div>
                                                <!-- 지역 -->
                                                <div class="meta">
                                                    <p class="loc"><?=$row["ad_addr"]?></p>
                                                </div>
                                                <!-- 평점 -->
                                                <p class="star-rating">
                                                    <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                                                    <span class="star" style="width:100%;"></span>
                                                    <span class="sr-text">평점</span>
                                                    <span class="sr-text">5</span>
                                                </p>
                                            </div>
                                            <div class="rt-group">
                                                <!-- 조건 -->
                                                <p class="condition"><em class="period"><?=$row["ru_work_type"]?></em>,<span>야간</span></p>
                                                <ul class="working-day">
                                                    <?=getWeekYoil($row["ru_week"])?>
                                                </ul>
                                                <!-- 해시태그 -->
                                                <div class="hashtag">
                                                    <?=getMyType($row["ru_my_type"])?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 목록선택 -->
                                    <!--div class="selection">
                                        <label class="form-checkbox">
                                            <input type="checkbox" name="chk[]" value="<?=$row["ru_idx"]?>"  class="sr-text">
                                            <span class="label-red"></span>
                                            <span class="sr-text">지원하기 선택</span>
                                        </label>
                                    </div-->
                                    <div class="form-action-wrap align-right" style="margin:20px;">
                                        <button type="button" aria-haspopup="dialog" class="btn-round btn-tertiary btnChat"
                                        data-ru_idx="<?=$row["ru_idx"]?>"
	                                	data-rc_idx="<?=$row["rc_idx"]?>"
	                                	data-target_id="<?=$row["target_id"]?>"
	                                	data-user_id="<?=$row["user_id"]?>"
	                                	data-target_img="<?=$row["target_img"]?>"
	                                	data-user_img="<?=$row["user_img"]?>"
	                                    data-target_name="<?=$row["target_name"]?>"
	                                	data-user_name="<?=$row["user_name"]?>"
	                                	data-subject="<?=$row["rc_name"]?>"
                                        >1:1채팅하기</button>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ol>
                        <!-- //목록 -->
                    </div>
                    <!--div class="form-action-wrap align-right">
                        <button type="button" aria-haspopup="dialog" id="btnReqeust" class="btn-round btn-tertiary">제안보내기</button>
                    </div-->
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->

<script>
    var first_yn = "N";
    var first_message = {};
    var first_users = new Array();

	var g_rm_type = "recruit";
	var g_rc_idx = 0;
	var g_ru_idx = 0;
	var g_td_idx = 0;

    //룸생성처리
    $(document).on("click",".btnChat",function(){
        first_yn = "N";
        var target_id = $(this).data("target_id");
        var user_id = $(this).data("user_id");
        var target_img = $(this).data("target_img");
        var user_img = $(this).data("user_img");
        var target_name = $(this).data("target_name");
        var user_name = $(this).data("user_name");
        var subject = $(this).data("subject");

        //키값 설정
        g_rc_idx = $(this).data("rc_idx");
        g_ru_idx = $(this).data("ru_idx");

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
		            rm_type : "chat",
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
                    mg_text : "1:1 채팅을 시작합니다!",
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

                //방생성
                click_type="chat";
                //alert(1);
		            socket.emit("room_insert",room_info);
                //alert(2);

                $('#bottomchatbtn').trigger('click');

                setTimeout(function() {
                    $('.list-item a').each(function() {
                        // a 태그의 onclick 속성 체크
                        if ($(this).attr('onclick') === "fShowChatListByRecruite('"+g_rc_idx+"',this); return false;") {
                            $(this).trigger('click'); // 해당 a 태그를 클릭
                            return false; // 찾으면 each 문 종료
                        }
                    });
                },  500);

                // 또 1초 후에 data-ru_idx="158"인 a 태그 클릭
                setTimeout(function() {
                    // $('.list-item a').each(function() {
                    //     // a 태그의 onclick 속성 체크
                    //     if ($(this).attr('onclick') === "fShowChatListByRecruite('"+g_rc_idx+"',this); return false;") {
                    //
                    //         console.log($(this).parent().attr('class'));
                    //
                    //         setTimeout(function() {
                    //           console.log('click');
                    //           //$(this).parent().trigger('click');
                    //           $(this).trigger('click');
                    //         }, 3000);
                    //         //$(this).trigger('click'); // 해당 a 태그를 클릭
                    //         //$(this).parent().trigger('click'); // 해당 a 태그를 클릭
                    //         //$(this).parent().find('a[data-ru_idx="'+g_ru_idx+'"]').eq(0).trigger('click'); // 해당 a 태그를 클릭
                    //         //$(this).parent().trigger('click'); // 해당 a 태그를 클릭
                    //         return false; // 찾으면 each 문 종료
                    //     }
                    // });
                    //$('.list-item a[data-ru_idx="158"]').trigger('click'); // 해당 a 태그를 클릭

                    $('.list-item a').each(function() {
                    //     //console.log($(this).data('ru_idx') + ' : '+ $(this).data('rc_idx'));
                        if($(this).data('ru_idx')==g_ru_idx && $(this).data('rc_idx')==g_rc_idx){
                    //       console.log($(this).html());
                    //       console.log($(this).parent().html());
                          $(this).parent().trigger('click');
                    //
                    //       //click start
                    //       $("#chat_view").html("");
                    //       var rm_idx = $(this).parent().data("target_id");
                    //       alert(1);
                  	// 	    //socket.emit("room_join",{rm_idx : Number(rm_idx),con_user_id : $("#user_id").val()});
                  	// 	    //socket.emit("room_info",{rm_idx : Number(rm_idx),con_user_id : $("#user_id").val()});
                  	// 			// var params = {
                  	// 			// 		rm_idx : Number(rm_idx),
                  	// 			// 		skip:0,
                  	// 			// 		limit : 1000 //rm_length
                  	// 			// };
                  	// 			// socket.emit("message_list",params);
                  	 			//$('.chat-view').css('display','flex');
                          //$('.chat-view').css('display','block');
                          $('.chat-view').removeAttr('hidden');
                    //       //click end
                    //
                    //       return false;
                        }
                    });

                    //$('.list-item a[data-ru_idx="'+g_ru_idx+'"][data-rc_idx="'+g_rc_idx+'"]').trigger('click');
                }, 1000); // 첫 클릭 후 1초 지연이므로 총 2초 후 실행

                // setTimeout(function() {
                //   alert(2);
                //   socket.emit("room_join",{rm_idx : Number(rm_idx),con_user_id : $("#user_id").val()});
                // }, 3000); // 첫 클릭 후 1초 지연이므로 총 2초 후 실행
                // setTimeout(function() {
                //   alert(3);
                //   socket.emit("room_info",{rm_idx : Number(rm_idx),con_user_id : $("#user_id").val()});
                // }, 4000); // 첫 클릭 후 1초 지연이므로 총 2초 후 실행
                // setTimeout(function() {
                //   alert(4);
                //   var params = {
                //       rm_idx : Number(rm_idx),
                //       skip:0,
                //       limit : 1000 //rm_length
                //   };
                //   socket.emit("message_list",params);
                // }, 5000); // 첫 클릭 후 1초 지연이므로 총 2초 후 실행
                // setTimeout(function() {
                //   alert(5);
                //   $('.chat-view').css('display','flex');
                // }, 6000); // 첫 클릭 후 1초 지연이므로 총 2초 후 실행

	        }else{
	            commonAlert(ret2.msg,function(){});
	            return;
	        }
	    });
    });
</script>
