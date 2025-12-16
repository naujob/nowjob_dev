<div id="content" class="page page-mypage">
    <main class="page-content">

        <div class="container">
            <?php $tab_on = "tab3";?>
            <?php $tab_on2 = "menu2";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
            <div class="tab-panel on">
                <div class="recruit-bookmark">
                    <h3 class="sr-text">제안보낸 지원공고</h3>
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
                                        <button type="button" aria-haspopup="dialog" class="btn-round btn-tertiary btnCancle"
                                            data-ru_idx="<?=$row["ru_idx"]?>"
        	                                	data-rc_idx="<?=$row["rc_idx"]?>"
        	                                	data-target_id="<?=$row["target_id"]?>"
        	                                	data-user_id="<?=$row["user_id"]?>"
        	                                	data-target_img="<?=$row["target_img"]?>"
        	                                	data-user_img="<?=$row["user_img"]?>"
      	                                    data-target_name="<?=$row["target_name"]?>"
        	                                	data-user_name="<?=$row["user_name"]?>"
        	                                	data-subject="<?=$row["rc_name"]?>"
                                        >제안 취소</button>
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

    //제안을 취소 처리
    $(document).on("click",".btnCancle",function(){
      var ru_idx = $(this).data('ru_idx');

        openPopup('confirm', {
            content:'<em>제안</em>을 취소하시겠습니까?',
            secondCallback: function () {

                var params = {};
    				    params["ru_idx"] = ru_idx;
        			  comAjax("/compage_proc/resume_propose_cancle",params,function(ret){
        				    if(ret.code=="00"){
        					    commonAlert("제안 취소 되었습니다.",function(){
        						    location.reload();
                      });
        				    }else{
                      console.log(ret);
                      commonAlert("삭제처리시 오류가 발생하였습니다.",function(){});
        				    }
        			  });
            }
        });

    });
</script>
