 <div class="page page-join member-type1">
     <div class="top-bar">
         <!--a href="#" class="btn-back"><span class="sr-text">이전으로</span></a-->
         <h1 class="title">개인회원 회원가입</h1>
     </div><!-- //top-bar -->
     <div class="page-content">

         <div class="join-welcome">
             <div class="container">
                 <h2 class="primary-title">가입을 축하드립니다</h2>
                 <p>나우잡이 제공하는 채용정보 및 다양한 혜택을<br> 이용하실 수 있습니다.</p>
             </div>
         </div>
         <div class="form-action-wrap" id="non_mem_last_cont" style="text-align: center;display:none;">
             <!-- <a href="/mypage/resume_list" class="btn-round btn-tertiary" style="">이력서 등록하러 가기</a> -->
         </div>
         <div class="sticky-bottom">
             <a href="/" class="btn btn-primary">메인으로 바로가기</a>
         </div>
     </div><!-- //page-content -->
</div>
 <script>
     if (getCookie("non_mem_last_cont") != '') {
         var msg = '';
         //	msg = '<a href="'+getCookie("non_mem_last_cont")+'" class="btn-round btn-tertiary" style="margin-bottom: 20px; padding: 0.625rem 1.125rem;">채용공고 계속보기</a><br />';
         $('#non_mem_last_cont').prepend(msg);
         $('#non_mem_last_cont').show();
         setCookie('non_mem_last_cont', '', 1);
     }

 </script>
