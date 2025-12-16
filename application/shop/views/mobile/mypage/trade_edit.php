<div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">중고거래 상품등록</h1>
    </div><!-- //top-bar -->

    <div class="page-content">
        <form id="frm" onsubmit="return false" autocomplete="false">
        	<input type="hidden" id="td_idx" name="td_idx" value="<?=getData($info,"td_idx","")?>" />
        	<input type="hidden" id="proc_mode" name="proc_mode" value="<?=getProcMode($info)?>" />
        	
            <div class="section">
                <div class="container">
                    
                    <div class="form-row">
                        <p class="form-label">상품사진 업로드</p>
                        <div class="form-pic-group">
                            <label class="form-pic first">
                                <input type="file" name="td_img1" accept="image/*" class="js-file-preview">
                                <span class="label">대표사진을<br> 첨부해주세요</span>
                                <span class="preview">
	                            	<?php if(getData($info,"td_img1")!="") : ?>
	                            		<img src="<?=getData($info,"td_img1")?>" />
	                            		<input type='hidden' name='td_img1_ori' value='<?=getData($info,"td_img1")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="td_img2" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"td_img2")!="") : ?>
	                            		<img src="<?=getData($info,"td_img2")?>" />
	                            		<input type='hidden' name='td_img2_ori' value='<?=getData($info,"td_img2")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="td_img3" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"td_img3")!="") : ?>
	                            		<img src="<?=getData($info,"td_img3")?>" />
	                            		<input type='hidden' name='td_img3_ori' value='<?=getData($info,"td_img3")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="td_img4" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"td_img4")!="") : ?>
	                            		<img src="<?=getData($info,"td_img4")?>" />
	                            		<input type='hidden' name='td_img4_ori' value='<?=getData($info,"td_img4")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="td_img5" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"td_img5")!="") : ?>
	                            		<img src="<?=getData($info,"td_img5")?>" />
	                            		<input type='hidden' name='td_img5_ori' value='<?=getData($info,"td_img5")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>
                            <span class="form-label">상품명 <em>최대 20자</em></span>
                            <input type="text" name="td_name" value="<?=$info["td_name"]?>" maxlength="20" placeholder="상품명을 입력해 주세요" aria-required="true" required class="form-text">
                        </label>
                    </div>
                    <div class="form-row">
                        <p class="form-label">카테고리 <em>다중선택가능</em></p>
                        <!-- 20221107 수정 -->
                        <div class="form-toggle-group col-3">
                            <label class="form-toggle-btn2 sm">
                                <input type="checkbox" id="td_category_all" >
                                <span class="btn">전체</span>
                            </label>
                          
                            <?php foreach($td_category_list as $key=> $row) : ?>
                            <label class="form-toggle-btn2 sm">
                                <input type="checkbox" id="td_category_<?=$key + 1?>" name="td_category[]" value="<?=$row["cd_val"]?>" data-img="<?=$row["cd_val2"]?>" <?=in_array_return($row["cd_val"],explode(",",$info["td_category"]),"check")?>>
                                <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label">판매가
                            <span class="option">
                                <label class="form-checkbox">
                                    <input type="checkbox" name="td_price_req_yn" <?=$info["td_price_req_yn"]=="N" ? "checked":""?> value="N" class="sr-text">
                                    <span class="label">가격제안불가</span>
                                </label>
                            </span>
                        </p>
                        <label class="inline-group">
                            <span class="sr-text">판매가입력</span>
                            <input type="number" name="td_price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="<?=$info["td_price"]?>" placeholder="상품가격을 입력해주세요" aria-required="true" required class="form-text is-currency">
                            <span class="unit">원</span>
                        </label>
                    </div>

                    <div class="form-row">
                        <p class="form-label">거래가능위치<em>거래가능위치는 최대3개까지 가능합니다</em></p>
                        <div class="inline-group">
                            <ul class="added-location" id="addr_location">
                            	<?php foreach($addr_list as $row) : ?>
                                <li>
                                	<input type="hidden" name="ad_zipcode[]" value="<?=$row["ad_zip_code"]?>" />
                                    <input type="hidden" name="ad_jibun[]" value="<?=$row["ad_jibun"]?>" />
                                    <input type="hidden" name="ad_doro[]" value="<?=$row["ad_doro"]?>" />
                                    <input type="hidden" name="ad_addr[]" value="<?=$row["ad_addr"]?>" />
                                    <input type="hidden" name="ad_gps_x[]" value="<?=$row["ad_gps_x"]?>" />
                                    <input type="hidden" name="ad_gps_y[]" value="<?=$row["ad_gps_y"]?>" />
                                    <p><?=$row["ad_addr"]?></p>
                                    <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <!-- 20220929 수정 -->
                            <a href="javascript:void(0);" onclick="showAddrInfo(3);" role="button" aria-haspopup="dialog"  class="btn-add-more js-toggle-panel"><span class="sr-text">추가작성</span></a>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label">상품소개 <em>최대1500자</em></p>
                        <label>
                            <span class="sr-text">상품소개 입력</span>
                            <textarea name="td_desc" maxlength="1500" placeholder="상품설명을 입력해 주세요.&#13;&#10;사이즈, 색상, 사용감, 사용기간, 보증기간 등 상세한 상품정보를 입력하시면 더욱 수월하게 거래할 수 있습니다." class="form-textarea js-autoheight"><?=$info["td_desc"]?></textarea>
                        </label>
                        <p class="help-msg">카카오톡 아이디 첨부 시 게시물 삭제 및 이용제제 처리될 수 있습니다.</p>
                    </div>

                    <div class="form-row">
                        <p class="form-label">해시태그 <em>다중선택가능</em></p>
                        <div class="form-toggle-group col-3">
                            
                            <?php foreach($td_hash_list as $row) : ?>
                            <label class="form-toggle-btn2 sm tag">
                                <input type="checkbox" name="td_hash[]" data-select-limit="6" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],explode(",",$info["td_hash"]),"check")?>>
                               	<span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>
                            
                            <!-- 추가 태그 -->
                            <?php foreach($info["td_hash"] as $row) : ?>
	                            <?php
	                            	$is_arr = false;
	                            	foreach($td_hash_list as $temp){
	                            		if($temp["cd_val"] == $row){
	                            			$is_arr = true;
	                            		}
	                            	} 
									if($is_arr==false) : 
	                            ?>
	                            <label class="form-toggle-btn2 sm tag">
	                                <input type="checkbox" name="td_hash[]" data-select-limit="6" value="<?=$row?>" <?=in_array_return($row,$info["td_hash"],"check")?>>
	                               <span class="btn"><?=$row?></span>
	                            </label> 	
	                            <?php endif; ?>
                            <?php endforeach; ?>
                            
                            <button type="button" aria-haspopup="dialog" class="btn-add-more js-add-hashtag"><span class="sr-text">추가작성</span></button>
                        </div>

                        <!-- 추가작성 양식 -->
                        <template id="addTemp">
                            <div class="additional-form">
                                <form action="/">
                                    <p class="popup-title">해시태그 작성</p>
                                    <div class="fom-container">
                                        <label class="form-row">
                                            <span class="sr-text">해시태그 작성</span>
                                            <textarea name="advantage" placeholder="예) #직거래가능, #에누리가능 #빠른대답" aria-required="true" maxlength="50" required class="form-textarea js-autoheight"></textarea>
                                            <span class="form-limit-label">200자이내</span>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </template>
                        
                        <script>
                            //추가작성 팝업 표시
                            window.addEventListener('DOMContentLoaded', function () {
                                document.querySelector('.js-add-hashtag').addEventListener('click', function (e) {
                                    var content = document.querySelector('#addTemp').innerHTML;
                                    this.classList.add('is-trigger');
                                    openPopup('modal', {
                                        content: content,
                                        firstButton: '작성',
                                        firstCallback: function () {
                                            var btn = document.querySelector('.js-add-hashtag');
                                            var values = document.querySelector('.additional-form .form-textarea').value.trim();
                                            var docfrag =  document.createDocumentFragment();

                                            if (!values) return;

                                            values.split(',').forEach(function (value) {
                                                var label = document.createElement('LABEL');
                                                var txt = value.trim().replace(/[\{\}\[\]\/?.,;:|\)*~`!^\-+<>@\#$%&\\\=\(\'\"]/g,'');

                                                label.setAttribute('class', 'form-toggle-btn2 sm tag');
                                                label.innerHTML = '<input type="checkbox" name="hashtag" value="'+ txt +'"> <span class="btn">'+ txt +'</span>';

                                                docfrag.appendChild(label);
                                            });

                                            btn.parentNode.insertBefore(docfrag, btn);
                                        },
                                    });
                                });
                            });
                        </script>
                    </div>

                </div>
            </div>

            <div class="section">
                <div class="container">
                    <ul class="important-list">
                        <li><a href="#terms1Panel" aria-haspopup="dialog" aria-controls="terms1Panel"  class="js-toggle-panel">상품등록완료 전에 읽어주세요</a></li>
                        <li><a href="#terms2Panel" aria-haspopup="dialog" aria-controls="terms2Panel"  class="js-toggle-panel">개인정보 제공 안내를 확인해주세요</a></li>
                    </ul>
                </div>
            </div>

            <div class="sticky-bottom">
                <button id="btnSave" type="button" class="btn btn-primary">수정완료</button>
            </div>

        </form>
    </div><!-- //page-content -->
</div>
 <!-- 서비스 이용약관 -->
    <div id="terms1Panel" class="layer-panel card-type" tabindex="-1">
        <div class="panel-container">
            <div class="panel-header">
                <!-- 약관제목 -->
                <h2 class="panel-title">상품등록 필수사항</h2>
            </div>
            <div class="panel-body">
                <div class="panel-content">
                    
                    <!-- 약관내용 -->
                    <div class="terms-wrap">
                      	내용 입력해야합니다.
                    </div>
                    <!-- //약관내용 -->

                </div>
            </div>
            <div class="panel-footer">
                <div class="sticky-bottom">
                    <button type="button" aria-controls="terms1Panel" class="btn btn-primary js-toggle-panel">확인</button>
                </div>
            </div>
        </div>
    </div>
    <!-- //서비스 이용약관 -->
    
    <!-- 개인정보 처리방침 -->
    <div id="terms2Panel" class="layer-panel card-type" tabindex="-1">
        <div class="panel-container">
            <div class="panel-header">
                <!-- 약관제목 -->
                <h2 class="panel-title">개인정보 제공 안내</h2>
            </div>
            <div class="panel-body">
                <div class="panel-content">
                    
                    <!-- 약관내용 -->
                    <div class="terms-wrap">
						내용 입력해야합니다.
                    </div>
                    <!-- //약관내용 -->

                </div>
            </div>
            <div class="panel-footer">
                <div class="sticky-bottom">
                    <button type="button" aria-controls="terms2Panel" class="btn btn-primary js-toggle-panel">확인</button>
                </div>
            </div>
        </div>
    </div>
    <!-- //개인정보 처리방침 -->

<script>

	// 검색 완료 선택 처리
	function selectAddrInfo(zipcode,doro,jibun,addr,x,y){
		var html = "";
		html +='<li>';
		html +='<input type="hidden" name="ad_zipcode[]" value="'+zipcode+'" />';
		html +='<input type="hidden" name="ad_jibun[]" value="'+jibun+'" />';
		html +='<input type="hidden" name="ad_doro[]" value="'+doro+'" />';
		html +='<input type="hidden" name="ad_addr[]" value="'+addr+'" />';
		html +='<input type="hidden" name="ad_gps_x[]" value="'+x+'" />';
		html +='<input type="hidden" name="ad_gps_y[]" value="'+y+'" />';
		html +='<p>'+addr+'</p>';
		html +='<button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>';
		html +='</li>';

		$("#addr_location").append(html);
		
		//팝업 종료
		$("#changeArea").removeClass("on");
	}
	
	function showAddrInfo(maxlen){
		if($("#addr_location").find("li").length >= maxlen){
			commonAlert("희망지역은 " + maxlen + "개까지 등록 가능합니다.",function(){});
            return;
		}
		$("#changeArea").addClass("on");
	}
	
	$(document).ready(function(){
		//처리
		$("#td_category_all").click(function(){
			$("[name='td_category[]']").prop("checked",$(this).is(":checked"));
		});
		
		$("#btnSave").click(function(){
			if(($("[name=td_img1]").val()==""  && $("[name=td_img1_ori]").val()==undefined) || ($("[name=td_img1_ori]").val()=="" && $("[name=td_img1_ori]").val()=="")){
            	commonAlert("상품사진을 1개 이상 선택해주세요.",function(){});
                return;	
            }
            
            if($("[name='td_name']").val()==""){
                commonAlert("상품명을 입력해주세요.",function(){});
                return;
            }
            
            if($("[name='td_category[]']:checked").length==0){
                commonAlert("카테고리를 1개 이상 선택해주세요.",function(){});
                return;
            }
            
            if($("[name='td_price']").val()==""){
                commonAlert("판매가를 입력해주세요.",function(){});
                return;
            }
            
            if($("#addr_location").find("li").length==0){
                commonAlert("거래가능위치를 선택해주세요.",function(){});
                return;
            }
            
           	if($("[name='td_desc']").val()==""){
                commonAlert("상품 소개를 입력해주세요.",function(){});
                return;
            }
            
            if($("[name='td_hash[]']:checked").length==0){
                commonAlert("해시태그를 선택해주세요.",function(){});
                return;
            }
            
            var form = $('#frm');
            var params = new FormData(form[0]);
	       	fileAjaxLoading("/trade_proc/trade_write",params,function(ret){
	            if(ret.code=="00"){
	            	commonAlert("상품이 "+($("[name=proc_mode]").val()=="update" ? "수정":"등록")+"되었습니다.",function(){
	            		location.replace("/mypage/trade_list");
	            	});
	            }else{
	                commonAlert(ret.msg,function(){});
	                return;
	            }
	        });
		});
		
		// 뒤로가기
		$("#btnPrev").click(function(){
			history.go(-1);
		});
	});
</script>

<?php include_once (get_base_url() . '/application/shop/views/mobile/_layout/addr_popup.php'); ?>
