<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">관심인재 관리</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="tab-container">
            <ul class="tab-list">
                <li><a href="/compage/bookmark_person1" class="tab">즐겨찾기한 인재</a></li>
                <li class="on"><span class="tab">채용공고 지원받은 인재</span></li>
            </ul>
        </div>
        <div class="tab-panel">
            <div class="section my-bookmark">
                <div class="container">
                    <h2 class="sr-text">채용공고 지원받은 인재</h3>
                    <p class="result-summary">
                        검색결과 <em><?=$count?></em>건
                    </p>
                    <div class="result-wrap">
                        <!-- 목록 -->
                        <ol class="result-list">
                            <?php foreach($list as $row) : ?>
                            <li class="list-item">
                                <div class="item-info person">
                                    <div class="thumb-wrap">
                                        <!-- 추천라벨 -->
                                        <p class="recomm-badge"><span>추천</span></p>
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
                                        <!-- 공고보기 -->
                                        <a href="/recruit/job_post/<?=$row["rc_idx"]?>" class="btn-line">지원한 공고</a>
                                    </div>

                                    <div class="content-wrap">
                                        <div class="meta top">
                                            <!-- 직종라벨 -->
                                            <p class="category">
                                                <?=getWorkingImg($row["ru_work"])?>
                                            </p>
                                            <!-- 조건 -->
                                            <p class="condition"><em class="period"><?=($row["ru_price_type"])?></em>,<span class="working-time"><?=($row["ru_work_type"])?></span></p>
                                            <label class="form-checkbox">
                                                <input type="checkbox" name="chk[]" value="<?=$row["ru_idx"]?>" data-mq_idx="<?=$row["mq_idx"]?>" class="sr-text">
                                                <span class="label-color1"></span>
                                                <span class="sr-text">항목 선택</span>
                                            </label>
                                        </div>
                                        <div class="title-wrap">
                                            <!-- 이름 -->
                                            <p class="title"><strong><a href="/resume/hr_post/<?=$row["ru_idx"]?>"><?=getAstrik($row["mm_name"])?></a></strong></p>
                                            <!-- 추가사항 -->
                                            <div class="meta">
                                                <p><?=$row["mm_nation"]?></p>
                                                <span>/</span>
                                                <p><?=$row["ma_age"]?>세</p>
                                            </div>
                                        </div>
                                        <ul class="working-day">
                                            <?=getWeekYoil($row["ru_week"])?>
                                        </ul>
                                        <!-- 해시태그 -->
                                        <div class="hashtag">
                                            <?=getMyType($row["ru_my_type"])?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ol>
                        <!-- //목록 -->
                    </div>
                    <div class="sticky-bottom">
                        <button type="button" aria-haspopup="dialog" id="btnReqeust" class="btn btn-primary">제안보내기</button>
                        <button id="btnCancel" type="button" class="btn btn-secondary js-remove">취소하기</button>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- //page-content -->
</div>


<script>
	$("#btnReqeust").click(function(){
        //로그인 여부 확인
        if($("#is_login").val()!="Y"){
            commonLogin();
            return;
        }

		var mq_idxs = new Array();

		for(var icnt =0;icnt < $("[name='chk[]']:checked").length;icnt++){
			mq_idxs.push($("[name='chk[]']:checked").eq(icnt).val());
		}

		if(mq_idxs.length==0){
			commonAlert("제안하실 공고를 선택해주세요.",function(){});
			return;
		}

		openPopup('confirm', {
            content: '<em>선택한 </em>이력서에<br> 제안하시겠습니까?',
            secondCallback: function () {
              	var params = {};
			    params["mq_idxs"] = mq_idxs;
			    params["mq_type"] = "RESUME";
			    comAjax("/common_proc/member_request",params,function(ret){
			        if(ret.code=="00"){
			        	commonAlert("제안 신청 되었습니다.",function(){
			        		$("[name='chk[]']").prop("checked",false);
			        	});
			        }else{
			            commonAlert(ret.msg,function(){});
			            return;
			        }
			    });
            }
        });
	});


	$("#btnCancel").click(function(){
		var mq_idxs = new Array();
		for(var icnt =0;icnt < $("[name='chk[]']:checked").length;icnt++){
			mq_idxs.push($("[name='chk[]']:checked").eq(icnt).attr("data-mq_idx"));
		}

		if(mq_idxs.length==0){
			commonAlert("취소하실 제안받은 인재를 선택해주세요.",function(){});
			return;
		}

		openPopup('confirm', {
            content: '<em>선택한</em> 지원받은 인재를<br> 취소하시겠습니까?',
            secondCallback: function () {
              	var params = {};
              	params["proc_type"] = "member_request";
			    params["mq_idxs"] = mq_idxs;
			    comAjax("/common_proc/request_cancel",params,function(ret){
			        if(ret.code=="00"){
			        	commonAlert("취소 되었습니다.",function(){
			        		location.reload();
			        	});
			        }else{
			            commonAlert(ret.msg,function(){});
			            return;
			        }
			    });
            }
        });
	});
</script>
