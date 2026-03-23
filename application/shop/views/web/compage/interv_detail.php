<input type="hidden" name="iv_idx" value="<?=$info["iv_idx"]?>" />
<div class="page page-rating">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">인재별점</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="section">
            <div class="container">

                <!-- 인재정보 -->
                <div class="item-info person">
                    <div class="thumb-wrap">
                        <!-- 썸네일 -->
                        <div class="thumb">
                            <a href="/resume/resume_detail/<?=$resume["ru_idx"]?>"><img data-src="<?=getResumeImg($resume)?>" alt="" class="lozad"></a>
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
                        <div class="meta top">
                            <!-- 직종라벨 -->
                            <p class="category"><?=getWorkingImg($resume["ru_work"])?></p>
                            <!-- 조건 -->
                            <p class="condition"><?=($resume["ru_work_type"])?></p>
                        </div>
                        <div class="title-wrap">
                            <!-- 이름 -->
                            <p class="title"><strong><a href="/resume/resume_detail/<?=$resume["ru_idx"]?>"><?=$resume["mm_name"]?></a></strong></p>
                            <!-- 추가사항 -->
                            <div class="meta">
                                <p><?=$resume["mm_nation"]?></p>
                                <span>/</span>
                                <p><?=$resume["ma_age"]?></p>
                            </div>
                        </div>
                        <ul class="working-day">
                            <?=getWeekYoil($resume["ru_week"])?>
                        </ul>
                        <!-- 해시태그 -->
                        <div class="hashtag">
                            <?=$resume["ru_my_type"]?>
                        </div>
                    </div>
                </div>

                <div class="rating-form">
                    <form action="/">

                        <!-- 20220929 수정 -->
                        <div class="form-row">
                            <div class="align-center">
                                <p class="form-label">홍길동 인재의 <em>근무태도</em>는 어떤가요?<small>(5점 만점)</small></p>
                            </div>
                            <div class="form-star-rating">
                                <input type="radio" name="rating1" id="rating1-5" value="5">
                                <label for="rating1-5" class="active-star"></label>
                                <input type="radio" name="rating1" id="rating1-4" value="4">
                                <label for="rating1-4" class="active-star"></label>
                                <input type="radio" name="rating1" id="rating1-3" value="3">
                                <label for="rating1-3" class="active-star"></label>
                                <input type="radio" name="rating1" id="rating1-2" value="2">
                                <label for="rating1-2" class="active-star"></label>
                                <input type="radio" name="rating1" id="rating1-1" value="1">
                                <label for="rating1-1" class="active-star"></label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="align-center">
                                <p class="form-label">홍길동 인재의 <em>업무능력</em>은 어떤가요?<small>(5점 만점)</small></p>
                            </div>
                            <div class="form-star-rating">
                                <input type="radio" name="rating2" id="rating2-5" value="5">
                                <label for="rating2-5" class="active-star"></label>
                                <input type="radio" name="rating2" id="rating2-4" value="4">
                                <label for="rating2-4" class="active-star"></label>
                                <input type="radio" name="rating2" id="rating2-3" value="3">
                                <label for="rating2-3" class="active-star"></label>
                                <input type="radio" name="rating2" id="rating2-2" value="2">
                                <label for="rating2-2" class="active-star"></label>
                                <input type="radio" name="rating2" id="rating2-1" value="1">
                                <label for="rating2-1" class="active-star"></label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="align-center">
                                <p class="form-label">홍길동 인재의 <em>성향</em>은 어떤가요?<small>(5점 만점)</small></p>
                            </div>
                            <div class="form-star-rating">
                                <input type="radio" name="rating3" id="rating3-5" value="5">
                                <label for="rating3-5" class="active-star"></label>
                                <input type="radio" name="rating3" id="rating3-4" value="4">
                                <label for="rating3-4" class="active-star"></label>
                                <input type="radio" name="rating3" id="rating3-3" value="3">
                                <label for="rating3-3" class="active-star"></label>
                                <input type="radio" name="rating3" id="rating3-2" value="2">
                                <label for="rating3-2" class="active-star"></label>
                                <input type="radio" name="rating3" id="rating3-1" value="1">
                                <label for="rating3-1" class="active-star"></label>
                            </div>
                        </div>

                        <div class="sticky-bottom view-footer">
                            <button type="button" id="btnSave" class="btn btn-primary">별점확인</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div><!-- //page-content -->
</div>

<script>
$("#btnSave").click(function(){
    if($("[name=rating1]").is(":checked")==false){
        commonAlert("근무태도 별점을 선택해주세요.",function(){});
        return;
    }

    if($("[name=rating2]").is(":checked")==false){
        commonAlert("업무능력 별점을 선택해주세요.",function(){});
        return;
    }

    if($("[name=rating3]").is(":checked")==false){
        commonAlert("성향 별점을 선택해주세요.",function(){});
        return;
    }

    var params = {};
    params["iv_idx"] = $("[name='iv_idx']").val();
    params["iv_eval_num1"] = $("[name=rating1]:checked").val();
    params["iv_eval_num2"] = $("[name=rating2]:checked").val();
    params["iv_eval_num3"] = $("[name=rating3]:checked").val();
    params["iv_status"] = "평가완료";
    comAjax("/compage_proc/chage_interview_proc",params,function(ret){
        if(ret.code=="00"){
            commonAlert("평가 완료 되었습니다.",function(){
                history.go(-1);
            });
        }else{
            commonAlert(ret.msg,function(){});
        }
    });
});
</script>
