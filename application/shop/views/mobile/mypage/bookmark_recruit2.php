<div class="page page-mypage-view">
  <div class="top-bar">
    <a href="javascript:void(0);" class="btn-back"><span class="sr-text">이전으로</span></a>
    <h1 class="title">관심공고 관리</h1>
  </div><!-- //top-bar -->

  <div class="page-content">
        <div class="tab-container">
            <ul class="tab-list">
                <li><a href="javascript:_replace('/mypage/bookmark_recruit1');" class="tab">즐겨찾기한 공고</a></li>
                <li class="on"><span class="tab">제안받은 공고</span></li>
            </ul>
        </div>

    <div class="tab-panel">
      <div class="section recruit-bookmark my-bookmark">
        <div class="container">
          <h2 class="sr-text">제안받은 공고</h3>
          <div class="result-wrap">
            <!-- 목록 -->
            <ol class="result-list result-list-card">
              <?php
              $card_options = ['show_bookmark' => false];
              foreach ($list as $row):
                include(APPPATH . 'views/mobile/_partial/recruit_card_row.php');
              endforeach;
              ?>
            </ol>
            <!-- //목록 -->
          </div>
          <div class="sticky-bottom">
            <button type="button" aria-haspopup="dialog" id="btnRequest" class="btn btn-primary ">지원하기</button>
          </div>     
        </div>
      </div>
    </div>
  </div><!-- //page-content -->
</div>


<input type="hidden" name="resume_yn" value="<?=$resume ==null ? "N":"Y"?>" />
<script>
  window.addEventListener('DOMContentLoaded', function() {
    $("#btnRequest").click(function(){
      //로그인 여부 확인
      if($("#is_login").val()!="Y"){
        commonLogin();
        return;
      }

      //이력서 여부 확인
      if($("[name=resume_yn]").val()=="N"){
        commonAlert("이력서 작성및 공개이력서 설정후 지원해주세요.",function(){});
        return;
      }

      var mq_idxs = [];

      $(".content-li.on").each(function () {
        var rc_idx = $(this).data("rc_idx");
        mq_idxs.push(rc_idx);
      });
      
      if(mq_idxs.length==0){
        commonAlert("지원하실 공고를 선택해주세요.",function(){});
        return;
      }

      openPopup('confirm', {
        content: '<em>선택한</em> 공고에<br> 지원하시겠습니까?',
        secondCallback: function () {
          var params = {};
          params["mq_idxs"] = mq_idxs;
          params["mq_type"] = "RECRUIT";
          comAjax("/common_proc/member_request",params,function(ret){
            if(ret.code=="00"){
              commonAlert("지원 신청 되었습니다.",function(){
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

    $(document).on('click', '.desc', function () {
      $(this).closest('.content-li').toggleClass('on');
    });
    
    $(document).on('click', '.img', function () {
      var rcIdx = $(this).closest('.content-li').data('rc_idx');
      if (rcIdx) {
        var url = '/recruit/job_post/' + rcIdx;
        location.href = url;
      }
    });
  });
</script>