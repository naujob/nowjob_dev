<form id="frm" class="controls" method="post">
    <input type="hidden" name="gd_idx" value="<?=getData($info,"gd_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />

    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">상품 노출 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr style="display:none;">
                    <th scope="col">판매자<em class="ess"></em></th>
                    <td class="inline-wrap">
					    <?=inputSelect("sr_idx","sr_idx",$select_seller,"","style='width:150px;'")?>
					</td>
                </tr>
                <tr>
                    <th scope="col">상품 노출 여부<em class="ess"></em></th>
                    <td class="inline-wrap">
					    <?=inputRadio("gd_status","gd_status",getOptionList("goods_status"),getData($info,"gd_status",$setting["st_goods_use_yn"]))?>
					</td>
                </tr>
               <tr style="display:none;">
                    <th scope="col">상품 아이콘</th>
                     <td>
				        <div class="input-text">
				            <label class="inline-wrap">
				                <?=inputCheck("gd_icons","gd_icons[]",$select_icon,$reg_icons)?>
				            </label>
				        </div>
				    </td>
                </tr>
                </tbody>
            </table>
        </div>
		<div class="section">
            <h3 class="sub-title mb-10">카테고리 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">카테고리 추가</th>
                    <td class="inline-wrap">
                       	<?=inputSelect("gd_cate1","gd_cate1",[],"","style='width:150px;'")?>
                       	<?=inputSelect("gd_cate2","gd_cate2",[""=>"-- 2차 카테고리선택 --"],"","style='width:150px;'")?>
                       	<?=inputSelect("gd_cate3","gd_cate3",[""=>"-- 3차 카테고리선택 --"],"","style='width:150px;'")?>
                       	<a href="javascript:void(0);" class="btn-xs btn-type3 btnAddCategory" style="width:auto;">카테고리 추가</a>
					</td>
                </tr>
               <tr>
                    <th scope="col">선택 카테고리 리스트<em class="ess"></em></th>
                     <td>
				        <table class="table-small">
				            <colgroup>
				            	<col width="80px" />
				                <col width="*" />
				                <col width="100" />
				            </colgroup>
				            <thead>
				            <tr>
				                <th scope="col" class="tc pl-0">번호</th>
				                <th scope="col" class="tc pl-0">카테고리명</th>
				                <th scope="col" class="tc pl-0">관리</th>
							</tr>
				            </thead>
				            <tbody id="body_category">
				            	<?php foreach($reg_categorys as $key=> $ct) : ?>
				            		<tr>
										<td class='tc'><?=$key + 1?></td>
										<td><?=$ct["cate_name"]?></td>
										<td class='tc'>
											<a href='javascript:void(0);' class='btn-xxs btn-type4 btnDelCategory'   style='width:auto;'>삭제</a>
											<input type='hidden' name='ct_idx[]' value='<?=$ct["ct_idx"]?>' />
										</td>
									</tr>
				            	<?php endforeach;?>

				            	<?php if(count($reg_categorys)==0) : ?>
				            	<tr class="empty_category">
				            		<td colspan="3" class="tc" style="background:#f9f9f9;">카테고리를 추가해주세요.</td>
				            	</tr>
				            	<?php endif;?>
							</tbody>
				        </table>
				    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">상품/배송 정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">상품코드(자동발행)</th>
                    <td class="inline-wrap" colspan="3">
                        <div class="input-text ">
                            <label>
                                <?=inputText("gd_code","gd_code",getData($info,"gd_code"),"등록후 생성","readonly",200)?>
                            </label>
                        </div>
                        <a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit"  style="width:auto;">복사</a>
                    </td>
                </tr>
                <tr>
                    <th scope="col">ERP코드</th>
                    <td class="inline-wrap">
                        <div class="input-text ">
                            <label>
                                <?=inputText("gd_erp_code","gd_erp_code",getData($info,"gd_erp_code"),"ERP-0000-0000","",200)?>
                            </label>
                        </div>

                    </td>
                     <th scope="col">관리 코드</th>
                    <td>
                        <div class="input-text ">
                            <?=inputText("gd_adm_code","gd_adm_code",getData($info,"gd_adm_code"),"PXC-0000-0000","style='width:200px;'",200)?>
                        </div>
                    </td>
                </tr>
                <tr>
             		<th scope="col">브랜드<?=$setting["st_goods_brand_yn"]=="Y" ? "<em class='ess'></em>":""?></th>
                    <td  colspan="3">
                       	<?=inputSelect("bd_idx","bd_idx",$select_brand,getData($info,"bd_idx"),"style='width:180px;'")?>
					</td>
                </tr>
                <tr>
                    <th scope="col">상품명</th>
                    <td colspan="3">
                        <table class="table-form">
				            <colgroup>
				            	<col width="150px" />
				                <col width="*" />
				            </colgroup>

				            <tbody>
								<tr>
					                <th>상품명<em class="ess"></em></td>
					                <td>
					                	<div class="input-text ">
				                        	<?=inputText("gd_name","gd_name",getData($info,"gd_name"),"상품명을 입력해주세요.","",200)?>
				                        </div>
					                </td>
					            </tr>
					            <tr>
					                <th>리스트 상품명(옵션)</th>
					                <td>
					                	<div class="input-text ">
				                            <?=inputText("gd_list_name","gd_list_name",getData($info,"gd_list_name"),"부가설명","",200)?>
				                        </div>
					                </td>
					            </tr>
					            <tr>
					                <th>부가 설명(옵션)</th>
					                <td>
					                	<div class="input-text ">
				                            <?=inputText("gd_sub_name","gd_sub_name",getData($info,"gd_sub_name"),"부가설명","",200)?>
				                        </div>
					                </td>
					            </tr>
					            <tr>
					                <th>검색 키위드(옵션)</th>
					                <td>
					                	<div class="input-text">
				                            <?=inputText("gd_keyword","gd_keyword",getData($info,"gd_keyword"),"  (,) 또는  ENTER"," data-role='tagsinput' style='display:none;'  ",200)?>
				                        </div>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>
                <tr>
                	<th scope="col">가격</th>
                    <td colspan="3">
                        <table class="table-form">
				            <colgroup>
				            	<col width="150px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>입고설정가<em class="ess"></em></td>
					                <td class="inline-wrap">
					                	<div class="input-text w120">
				                            <label>
				                                <?=inputNumber("gd_p_price","gd_p_price",getData($info,"gd_p_price"),"","",10)?>
				                            </label>
				                        </div>
				                        <span class="ml-5">원</span>
					                </td>
					            </tr>
					            <tr>
					                <th>출고설정가<em class="ess"></em></th>
					                <td class="inline-wrap">
					                	<div class="input-text w120">
				                            <label>
				                                <?=inputNumber("gd_s_price","gd_s_price",getData($info,"gd_s_price"),"","",10)?>
				                            </label>
				                        </div>
				                        <span class="ml-5">원</span>
					                </td>
					            </tr>
 								<tr>
					                <th>소매기준가</th>
					                <td class="inline-wrap">
					                	<div class="input-text w120">
				                            <label>
				                                <?=inputNumber("gd_i_price","gd_i_price",getData($info,"gd_i_price"),"","",10)?>
				                            </label>
				                        </div>
				                        <span class="ml-5">원</span>
				                        <p class="exc1 ml-5 mt-0">0이상 입력시 소매 마진율 계산 기준가로 설정됩니다.</p>
					                </td>
					            </tr>
					           <tr style="display:none;">
					                <th>과세여부<em class="ess"></em></th>
					                <td class="inline-wrap">
					                	 <?=inputRadio("gd_tax_yn","gd_tax_yn",getOptionList("tax_yn"),getData($info,"gd_tax_yn",$setting["st_goods_tax_yn"]))?>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>

                <tr>
                    <th scope="col">배송유형 선택<em class="ess"></em></th>
                    <td colspan="3">
						<select id="dv_idx" name="dv_idx" class="w180">
							<option value="">-- 배송유형 선택 --</option>
                        <?php foreach($delivery_list as $dlv) : ?>
                        	<option value="<?=$dlv["dv_idx"]?>" <?=$dlv["dv_idx"]==getData($info,"dv_idx","0") ? "selected":""?> data-dv_type="<?=$dlv["dv_type"]?>" data-dv_price="<?=$dlv["dv_price"]?>" data-dv_price_v2="<?=$dlv["dv_price_v2"]?>" data-dv_limit_price="<?=$dlv["dv_limit_price"]?>"><?=$dlv["dv_name"]?></option>
                        <?php endforeach;?>
                        </select>
                        <!--a href="/admin/goods/delivery_list" target="_blank" class="btn-xs btn-type7  ml-10" style="width:auto;">배송비 관리창 열기</a-->
                        <p id="delivery_info" style="margin-top:10px;display:none;">

                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">배송정보 <em class="ess"></em></th>

					 <td class="inline-wrap">
	                	<div >
                   			<?=inputSelect("gd_adddlv_type","gd_adddlv_type",[""=>"없음","today"=>"당일배송","etc"=>"기타"],getData($info,"gd_adddlv_type",""),"style='width:150px;'")?>
                   		</div>
                   		<div class="input-text w300 ml-5 cont_adddlv" style="<?=getData($info,"gd_adddlv_type","")!="" ? "":"display:none;"?>">
							<?=inputText("gd_adddlv_txt","gd_adddlv_txt",getData($info,"gd_adddlv_txt"),"필요한 경우 추가 정보를 기입해주세요.","",100)?>
                        </div>
	                </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">추가 정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">상품 순서</th>
                    <td>
                        <div class="input-text">
                            <?=inputNumber("gd_sort","gd_sort",getData($info,"gd_sort","0"),"0","style='width:120px;'",200)?>

                        </div>
                        <p class="exc1">설정시 오름차순으로 정렬됩니다.</p>
                    </td>

	                <th>상품 추가정보</th>
	                <td class="inline-wrap">
	                	<span> 부피 : </span>
	                	<div class="input-text w100">
                            <label>
                               	<?=inputTextNumber("gd_cm3","gd_cm3",getData($info,"gd_cm3"),"0.5545","",200)?>
                            </label>
                        </div>

	                	<span class="ml-10" style="display:none;"> 무게 : </span>
	                	<div class="input-text w100" style="display:none;">
                            <label>
                               	<?=inputNumber("gd_kg","gd_kg",getData($info,"gd_kg"),"20","",200)?>
                            </label>
                        </div>
                        <span style="display:none;">kg</span>
                        <p class="exc1 d-block" style="display:none;">부피별 배송지 사용하는경우 필수 입력입니다.</p>
	                </td>
                </tr>
                <tr>
                    <th scope="col" style="display:none;">제조사</th>
                    <td class="inline-wrap" style="display:none;">
                   		<div class="input-text w200">
                            <label>
                                <?=inputText("gd_make","gd_make",getData($info,"gd_make",$setting["st_goods_make_name"]),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <th scope="col">원산지<em class="ess"></em></th>
                    <td class="inline-wrap" colspan="3">
                   		<div class="input-text w200">
                            <label>
                                <?=inputText("gd_origin","gd_origin",getData($info,"gd_origin",$setting["st_goods_origin_name"]),"","",200)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">재고관리</th>
                    <td colspan="3">
                    	<table class="table-form">
				            <colgroup>
				            	<col width="150px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>재고 사용여부</td>
					                <td class="inline-wrap">
					                	<div class="inline-wrap">
				                   			<?=inputRadio("gd_stock_yn","gd_stock_yn",["N"=>"재고에 상관없이 판매","Y"=>"재고 사용"],getData($info,"gd_stock_yn",$setting["st_goods_stock_yn"]),"")?>
				                   		</div>
					                </td>
					            </tr>
					            <tr class="cont_stock <?=getData($info,"gd_stock_yn",$setting["st_goods_stock_yn"])=="Y" ? "":"hidden"?>">
					                <th>재고수량</th>
					                <td class="inline-wrap">
					                	<div class="input-text w100">
				                            <label>
				                                <?=inputText("gd_stock","gd_stock",getData($info,"gd_stock"),"0","",200)?>
				                            </label>
				                        </div>
				                        <p class="exc1 d-block">옵션이 있는경우 옵션 재고 수량으로 재고가 관리됩니다.</p>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="col">옵션 설정</th>
                    <td colspan="3">
                    	<table class="table-form">
				            <colgroup>
				            	<col width="150px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>옵션 사용여부</td>
					                <td class="inline-wrap">
					                	<div class="inline-wrap">
				                   			<?=inputRadio("gd_option_yn","gd_option_yn",getOptionList("use_yn"),getData($info,"gd_option_yn",$setting["st_goods_option_yn"]))?>
				                   		</div>
					                </td>
					            </tr>
					            <tr>
					                <th>옵션 템플릿</td>
					                <td class="inline-wrap">
					                	<div class="inline-wrap">
				                   			<?=inputSelect("sel_option_group","sel_option_group",$select_option_group,"","style='width:180px;'")?>
				                   		</div>
					                </td>
					            </tr>
					            <tr class="cont_option <?=getData($info,"gd_option_yn",$setting["st_goods_option_yn"]) == "Y" ? "":"hidden"?>">
					                <th>옵션 설정</th>
					                <td class="">
					                	<table class="table-small" style="width:100%;">
							                <colgroup>
							                    <col width="200px"/>
							                    <col width="*"/>
							                    <col width="100px"/>
							                </colgroup>
							                <thead>
							                	<th class="tc">옵션명</th>
							                	<th class="tc">옵션값(최대 3개까지 입력가능)</th>
							                	<th class="tc" style="padding:4px;"><a href="javascript:void(0);" class="btn-type3 btn-xxs btnOptionAdd" style="">추가</a></th>
							                </thead>
							                <tbody id="option_body">
							                	<?php foreach($reg_option_groups as $key=>$opt) : ?>
								               	<tr class='option_tr'>
													<td style="vertical-align:center;text-align:center;">
														<div class="input-text ">
								                            <label>
								                                <?=inputText("ol_name_" . ($key + 1),"ol_name[]",getData($opt,"gg_name"),"예시) 색상"," ",20)?>
								                            </label>
								                        </div>
													</td>
								                    <td>
								                       	<div class="input-text">
								                            <label style="width:100%;">
								                            	<?=inputText("ol_values_" . ($key + 1),"ol_values[]",getData($opt,"gg_values"),"  (,) 또는 ENTER"," style='text-align:left;display:none;'  data-addclass='' ",20000)?>
								                            </label>
								                            <!--label style="width:100%;margin-top:4px;">
								                            	<?=inputText("ol_prices_" . ($key + 1),"ol_prices[]",getData($opt,"gg_prices"),"  (,) 또는 ENTER"," style='text-align:left;display:none;'  data-addclass='' ",20000)?>
								                            </label-->
								                        </div>
								                    </td>
								                    <td class="tc">
								                    	<?=inputHidden("ol_idx_" . ($key + 1),"ol_idx[]",getData($opt,"gg_idx"),"","")?>
								                    	<?=inputHidden("ol_val_" . ($key + 1),"ol_val[]","","","")?>
								                    	<!--<?=inputHidden("ol_price_" . ($key + 1),"ol_price[]","","","")?>-->
								                       	<?php if($key!=0) : ?>
								                       		<a href="javascript:void(0);" class="btn-type4 btn-xxs btnOptionDel" style="">삭제</a>
								                       	<?php endif; ?>
								                    </td>
								                </tr>
								                <?php endforeach;?>
							                </tbody>
							            </table>
							            <div class="mt-10 tc">
						            		<a href="javascript:void(0);" class="btn-xs btn-type3 btnCompleteOption" style="width:auto;">옵션 목록 생성</a>
							            </div>
							            <div class="cont_option_list <?=count($reg_options) > 0 ? "":"hidden"?>">
							            	<div>
							            		<table class="table-small" style="width:100%;margin-top:10px;border:solid 2px #5c6c87 !important;">
									                <colgroup>
									                	<col width="70px"/>
									                    <col width="110px"/>
									                    <col style="display:none;" width="70px"/>
									                    <col style="display:none;" width="110px"/>
									                    <col style="display:none;" width="70px"/>
									                    <col style="display:none;" width="110px"/>
									                    <col width="70px"/>
									                    <col width="110px"/>
									                    <col width="70px"/>
									                    <col width="110px"/>
									                </colgroup>
									                <tbody>
									                	<tr>
									                		<th colspan="10" class="tc">
									                			옵션 일괄 설정(체크박스 선택한 대상자에 일괄 적용)
									                		</th>
									                	</tr>
									                	<tr>
									                		<th class="tc">선택삭제</th>
									                		<td class="tc vb">
									                			<a href="javascript:void(0);" class="btn-xs btn-type2 btnOpAll" data-field="remove" style="width:100%;margin-top:4px;">삭제하기</a>
									                		</td>
									                		<th style="display:none;" class="tc">입고설정가</th>
									                		<td style="display:none;" class="tc">
									                			<div class="input-text"><input type='number' name='op_set'  placeholder='0' value='0' /></div>
									                			<a href="javascript:void(0);" class="btn-xs btn-type2 btnOpAll" data-field="op_p_price[]" style="width:100%;margin-top:4px;">적용하기</a>
									                		</td>
									                		<th style="display:none;" class="tc">출고설정가</th>
									                		<td style="display:none;" class="tc">
									                			<div class="input-text"><input type='number' name='op_set'  placeholder='0' value='0' /></div>
									                			<a href="javascript:void(0);" class="btn-xs btn-type2 btnOpAll" data-field="op_add_price[]" style="width:100%;margin-top:4px;">적용하기</a>
									                		</td>
									                		<th class="tc">재고수량</th>
									                		<td class="tc">
									                			<div class="input-text"><input type='number' name='op_set'  placeholder='0' value='0' /></div>
									                			<a href="javascript:void(0);" class="btn-xs btn-type2 btnOpAll" data-field="op_stock[]" style="width:100%;margin-top:4px;">적용하기</a>
									                		</td>
									                		<th class="tc">옵션 사용여부</th>
									                		<td class="tc">
									                			<select name='op_set' style='width:100% !important;min-width:100%;'>
																	<option value='Y'>사용</option>
																	<option value='N'>미사용</option>
																</select>
																<a href="javascript:void(0);" class="btn-xs btn-type2 btnOpAll" data-field="op_use_yn[]" style="width:100%;margin-top:4px;">적용하기</a>
									                		</td>
									                	</tr>
									                </tbody>
									            </table>
							            	</div>
							            	<table class="table-small" style="width:100%;margin-top:10px;">
								                <colgroup>
								                    <col width="50px"/>
								                    <col width="*"/>
								                    <col width="110px"/>
								                    <col style="display:none;" width="110px"/>
								                    <col style="display:none;" width="110px"/>
								                    <col width="120px"/>
								                </colgroup>
								                <thead>
								                	<th class="tc">
							                			<div class='input-checkbox'>
												            <label>
												                <input type='checkbox' id='chk_option_all' name='chk_option_all'   />
												                <span style="padding-left:5px;"><strong></strong></span>
												            </label>
												        </div>
								                	</th>
								                	<th class="tc">옵션</th>
								                	<th style="display:none;" class="tc">입고설정가(원)</th>
								                	<th style="display:none;" class="tc">출고설정가(원)</th>
								                	<th class="tc">재고수량</th>
								                	<th class="tc">옵션 사용여부</th>
								                </thead>
								                <tbody id="option_list_body">
								                	<?php foreach($reg_options as $key=>$opt) : ?>
								                		<tr>
														    <td class='tc'>
														        <div class='input-checkbox'>
														            <label>
														                <input type='checkbox' name='chk_opt' checked  />
														                <span style='padding-left:5px;'><strong></strong></span>
														            </label>
														        </div>
														    </td>
														    <td><?=$opt["gg_name"]?></td>
														    <td style="display:none;" class='tc'><div class='input-text'><input type='number' name='op_p_price[]'  placeholder='0' value='<?=$opt["gg_p_price"]?>' /></div></td>
														    <td style="display:none;" class='tc'><div class='input-text'><input type='number' name='op_add_price[]'  placeholder='0' value='<?=$opt["gg_add_price"]?>' /></div></td>
														    <td class='tc'><div class='input-text'><input type='number' name='op_stock[]'  placeholder='0' value='<?=$opt["gg_stock"]?>' /></div></td>
														    <td class='tc'>
																<input type='hidden' name='op_name[]' value='<?=$opt["gg_name"]?>'  value='0' />
														        <select  name='op_use_yn[]' style='width:100% !important;min-width:auto !important;'>
														            <option value='Y' <?=$opt["gg_use_yn"]=="Y" ? "selected":""?>>사용</option>
														            <option value='N' <?=$opt["gg_use_yn"]=="N" ? "selected":""?>>미사용</option>
														        </select>
														    </td>
														</tr>
								                	<?php endforeach;?>
								                </tbody>
								            </table>
							            </div>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>
                <tr style="display:none;">
                    <th scope="col">추가 옵션 설정</th>
                    <td colspan="3">
                    	<table class="table-form">
				            <colgroup>
				            	<col width="150px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>추가 옵션 사용여부 </td>
					                <td class="inline-wrap">
					                	<div class="inline-wrap">
				                   			<?=inputRadio("gd_option_add_yn","gd_option_add_yn",getOptionList("use_yn"),getData($info,"gd_option_add_yn",$setting["st_goods_option_add_yn"]))?>
				                   		</div>
					                </td>
					            </tr>
					            <tr class="cont_option_add <?=getData($info,"gd_add_option_yn",$setting["st_goods_option_add_yn"]) == "Y" ? "":"hidden"?>">
					                <th>옵션 설정</th>
					                <td class="">
					                	<table class="table-small" style="width:100%;">
							                <colgroup>
							                    <col width="200px"/>
							                    <col width="*"/>
							                    <col width="100px"/>
							                </colgroup>
							                <thead>
							                	<th class="tc">추가 옵션명</th>
							                	<th class="tc">추가 옵션값(최대 20가까지 입력가능)</th>
							                	<th class="tc" style="padding:4px;"><a href="javascript:void(0);" class="btn-type3 btn-xxs btnOptionAddAdd" style="">추가</a></th>
							                </thead>
							                <tbody id="option_add_body">
							                	<?php foreach($reg_add_option_groups as $key=>$opt) : ?>
								               	<tr class='option_add_tr'>
													<td style="vertical-align:center;text-align:center;">
														<div class="input-text ">
								                            <label>
								                                <?=inputText("ol_add_name_" . ($key + 1),"ol_add_name[]",getData($opt,"gg_name"),"예시) 색상","' ",20)?>
								                            </label>
								                        </div>
													</td>
								                    <td>
								                       	<div class="input-text">
								                            <label style="width:100%;">
								                            	<?=inputText("ol_add_values_" . ($key + 1),"ol_add_values[]",getData($opt,"gg_values"),"  (,) 또는 ENTER"," style='text-align:left;display:none;'  data-addclass='' ",20000)?>
								                            </label>
								                        </div>
								                    </td>

								                    <td class="tc">
								                    	<?=inputHidden("ol_add_idx_" . ($key + 1),"ol_add_idx[]",getData($opt,"gg_idx"),"","")?>
								                    	<?=inputHidden("ol_add_val_" . ($key + 1),"ol_add_val[]","","","")?>
								                       	<?php if($key!=0) : ?>
								                       		<a href="javascript:void(0);" class="btn-type4 btn-xxs btnOptionAddDel" style="">삭제</a>
								                       	<?php endif; ?>
								                    </td>
								                </tr>
								                <?php endforeach;?>
							                </tbody>
							            </table>
							            <div class="mt-10 tc">
						            		<a href="javascript:void(0);" class="btn-xs btn-type3 btnCompleteOptionAdd" style="width:auto;">추가 옵션 목록 생성</a>
							            </div>
							            <div class="cont_option_add_list  <?=count($reg_add_options) > 0 ? "":"hidden"?>">
							            	<div>
							            		<table class="table-small" style="width:100%;margin-top:10px;border:solid 2px #5c6c87 !important;">
									                <colgroup>
									                	<col width="70px"/>
									                    <col width="110px"/>
									                    <col width="70px"/>
									                    <col width="110px"/>
									                    <col width="70px"/>
									                    <col width="110px"/>
									                    <col width="70px"/>
									                    <col width="110px"/>
									                    <col width="70px"/>
									                    <col width="110px"/>
									                </colgroup>
									                <tbody>
									                	<tr>
									                		<th colspan="10" class="tc">
									                			추가 옵션 일괄 설정(체크박스 선택한 대상자에 일괄 적용)
									                		</th>
									                	</tr>
									                	<tr>
									                		<th class="tc">선택삭제</th>
									                		<td class="tc vb">
									                			<a href="javascript:void(0);" class="btn-xs btn-type2 btnOpAddAll" data-field="remove" style="width:100%;margin-top:4px;">삭제하기</a>
									                		</td>
									                		<th class="tc">입고설정가</th>
									                		<td class="tc">
									                			<div class="input-text"><input type='number' name='op_set'  placeholder='0' value='0' /></div>
									                			<a href="javascript:void(0);" class="btn-xs btn-type2 btnOpAddAll" data-field="op_add_p_price[]" style="width:100%;margin-top:4px;">적용하기</a>
									                		</td>
									                		<th class="tc">출고설정가</th>
									                		<td class="tc">
									                			<div class="input-text"><input type='number' name='op_set'  placeholder='0' value='0' /></div>
									                			<a href="javascript:void(0);" class="btn-xs btn-type2 btnOpAddAll" data-field="op_add_add_price[]" style="width:100%;margin-top:4px;">적용하기</a>
									                		</td>
									                		<th class="tc">재고수량</th>
									                		<td class="tc">
									                			<div class="input-text"><input type='number' name='op_set'  placeholder='0' value='0' /></div>
									                			<a href="javascript:void(0);" class="btn-xs btn-type2 btnOpAddAll" data-field="op_add_stock[]" style="width:100%;margin-top:4px;">적용하기</a>
									                		</td>
									                		<th class="tc">사용여부</th>
									                		<td class="tc">
									                			<select name='op_set' style='width:100% !important;min-width:100%;'>
																	<option value='Y'>사용</option>
																	<option value='N'>미사용</option>
																</select>
																<a href="javascript:void(0);" class="btn-xs btn-type2 btnOpAddAll" data-field="op_add_use_yn[]" style="width:100%;margin-top:4px;">적용하기</a>
									                		</td>
									                	</tr>
									                </tbody>
									            </table>
							            	</div>
							            	<table class="table-small" style="width:100%;margin-top:10px;">
								                <colgroup>
								                    <col width="50px"/>
								                    <col width="*"/>
								                    <col width="110px"/>
								                    <col width="110px"/>
								                    <col width="110px"/>
								                    <col width="120px"/>
								                </colgroup>
								                <thead>
								                	<th class="tc">
							                			<div class='input-checkbox'>
												            <label>
												                <input type='checkbox' id='chk_add_all' name='chk_add_all'   />
												                <span style="padding-left:5px;"><strong></strong></span>
												            </label>
												        </div>
								                	</th>
								                	<th class="tc">옵션</th>
								                	<th class="tc">입고설정가(원)</th>
								                	<th class="tc">출고설정가(원)</th>
								                	<th class="tc">재고수량</th>
								                	<th class="tc">사용여부</th>
								                </thead>
								                <tbody id="option_add_list_body">
								                	<?php foreach($reg_add_options as $key=>$opt) : ?>
								                		<tr>
														    <td class='tc'>
														        <div class='input-checkbox'>
														            <label>
														                <input type='checkbox' name='chk_add_opt' checked  />
														                <span style='padding-left:5px;'><strong></strong></span>
														            </label>
														        </div>
														    </td>
														    <td><?=$opt["gg_name"]?></td>
														    <td class='tc' style="display:none;"><div class='input-text'><input type='number' name='op_add_p_price[]'  placeholder='0' value='<?=$opt["gg_p_price"]?>' /></div></td>
														    <td class='tc' style="display:none;"><div class='input-text'><input type='number' name='op_add_add_price[]'  placeholder='0' value='<?=$opt["gg_add_price"]?>' /></div></td>
														    <td class='tc'><div class='input-text'><input type='number' name='op_add_stock[]'  placeholder='0' value='<?=$opt["gg_stock"]?>' /></div></td>
														    <td class='tc'>
																<input type='hidden' name='op_add_name[]' value='<?=$opt["gg_name"]?>'  value='0' />
														        <select  name='op_add_use_yn[]' style='width:100% !important;min-width:auto !important;'>
														            <option value='Y' <?=$opt["gg_use_yn"]=="Y" ? "selected":""?>>사용</option>
														            <option value='N' <?=$opt["gg_use_yn"]=="N" ? "selected":""?>>미사용</option>
														        </select>
														    </td>
														</tr>
								                	<?php endforeach;?>
								                </tbody>
								            </table>
							            </div>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="col">추가옵션  설정</th>
                    <td colspan="3" class="inline-wrap">
                    	<?=inputRadio("gd_option_add_type","gd_option_add_type",[""=>"없음","작업복로고"=>"작업복로고","건설안전인쇄"=>"건설안전인쇄"],getData($info,"gd_option_add_type"))?>
                    </td>
                </tr>
                <tr>
                    <th scope="col">상품정보제공고시</th>
                    <td colspan="3">
                    	<table class="table-form">
                    		<colgroup>
				            	<col width="150px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>품목</td>
					                <td class="inline-wrap">
					                	<?=inputSelect("sel_notice","sel_notice",$select_notice,"","style='width:180px;'")?>
					                </td>
					            </tr>
					            <tr>
					                <th>상세 정보</td>
					                <td >
					                	<div class="w100p tr">
					                		<a href="javascript:void(0);" class="btn-xs btn-type1 btnAllNoticeDefault " style="width:auto;">전체 항목 '상세설명참조'로 표기</a>
					                	</div>
					                	<table class="table-small" style="clear:both;margin-top:6px;">
								            <colgroup>
								            	<col width="50px" />
								                <col width="200" />
								                <col width="200" />
								                <col width="60" />
								            </colgroup>
								            <thead>
								            <tr>
								                <th scope="col" class="tc pl-0">번호</th>
								                <th scope="col" class="tc pl-0">항목</th>
								                <th scope="col" class="tc pl-0">정보</th>
								                <th scope="col" class="tc pl-0">
								                	<a href="javascript:void(0);" class="btn-xxs btn-type3 btnAddNotice " style="width:auto;">추가</a>
								                </th>
											</tr>
								            </thead>
								            <tbody id="body_notice">
								            	<?php foreach($reg_details as $key=> $dt) : ?>
									            	<tr>
														<td class='tc'><?=$key + 1?></td>
														<td><div class='input-text'><input type='text' name='gd_notice_title[]' value='<?=$dt["notice_title"]?>' /></div></td>
														<td><div class='input-text'><input type='text' name='gd_notice_value[]' value='<?=$dt["notice_value"]?>' style='color:#aaa;' /></div></td>
														<td class='tc'>
															<a href='javascript:void(0);' class='btn-xxs btn-type4 btnDelNotice' style='width:auto;'>삭제</a>
														</td>
													</tr>
								            	<?php endforeach;?>
								            	<?php if(count($reg_details) == 0) : ?>
								            		<tr class="empty_notice">
									            		<td colspan="4" class="tc" style="background:#f9f9f9;">정보재공 고시를 추가해주세요.</td>
									            	</tr>
								            	<?php endif;?>
											</tbody>
								        </table>
					                </td>
					            </tr>

							</tbody>
				        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="col">관련 상품 설정</th>
                    <td colspan="3">
                   		<table class="table-form">
				            <colgroup>
				            	<col width="150px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>관련상품 설정</td>
					                <td class="inline-wrap">
					                	<div class="inline-wrap">
				                   			<?=inputRadio("gd_rel_type","gd_rel_type",getOptionList("goods_rel_product"),getData($info,"gd_rel_type",$setting["st_goods_rel_product_type"]))?>
				                   		</div>
					                </td>
					            </tr>
					            <tr class="cont_rel_none <?=getData($info,"gd_rel_type",$setting["st_goods_rel_product_type"]) =="none" ? "":"hidden"?>">
					                <th>사용안함</th>
					                <td class="inline-wrap">
					                	관련상품은 비노출 처리됩니다.
					                </td>
					            </tr>
					            <tr class="cont_rel_auto <?=getData($info,"gd_rel_type",$setting["st_goods_rel_product_type"]) =="auto" ? "":"hidden"?>">
					                <th>자동설정</th>
					                <td class="inline-wrap">
					                	자동 설정 조건에 의해 노출 처리 됩니다.
					                </td>
					            </tr>
					            <tr class="cont_rel_manual <?=getData($info,"gd_rel_type",$setting["st_goods_rel_product_type"]) =="manual" ? "":"hidden"?>">
					                <th>수동상품 선택<br/>(최대 10개)</th>
					                <td class="inline-wrap">
					                	<a href="javascript:void(0);" class="btn-xs btn-type7 btnPopGoods"  style="width:auto;">관련상품 선택창 열기</a>
					                	<div class="mt-5" style="width:100%;">
				                			<?php $select_max_length = 10;?>
					                		<?php $this->load->view('/admin/module/select_goods_list');?>
					                	</div>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">상품 이미지</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">인증서 이미지</th>
                    <td>
                        <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputFile("gd_certi_file","gd_certi_file",getData($info,"gd_certi_file",""),"")?>
                        </div>
                        <p class="exc1">권장 포멧 : png,pdf</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">이미지 유형</th>
                    <td>
                        <div class="inline-wrap">
						    <?=inputRadio("gd_img_type","gd_img_type",getOptionList("goods_regist_image_type"),getData($info,"gd_img_type",$setting["st_goods_image_type"]))?>
						</div>
                    </td>
                </tr>

                <tr class="cont_img_img <?=getData($info,"gd_img_type",$setting["st_goods_image_type"]) =="img" ? "":"hidden"?>">
                    <th scope="col">대표 이미지<em class="ess"></em></th>
                    <td>
                        <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("gd_img_main","gd_img_main",getData($info,"gd_img_main",""),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png, 권장 사이즈 : 600px x 600px, 크기 : 200kb~500kb</p>
                        <p class="exc1">리스트 이미지 미설정시 대표 이미지로 대체 됩니다.</p>
                        <p class="exc1">상세 이미지 미설정시 대표 이미지로 대체 됩니다.</p>
                    </td>
                </tr>

                <tr class="cont_img_img <?=getData($info,"gd_img_type",$setting["st_goods_image_type"]) =="img" ? "":"hidden"?>">
                    <th scope="col">개별 이미지</th>
                    <td>
                       <table class="table-form">
				            <colgroup>
				            	<col width="150px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
				            	<tr>
					                <th>리스트 이미지(옵션)</td>
					                <td class="inline-wrap">
					                	<div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
				                        	<?=inputImage("gd_img_list","gd_img_list",getData($info,"gd_img_list",""),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
				                        </div>
				                        <p class="exc1 ml-10">권장 포멧 : png , 사이즈 400px x 400px, 크기 : 100kb~200kb </p>
					                </td>
					            </tr>
				            	<?php for($icnt=1;$icnt <= $max_goods_length;$icnt++) : ?>
								<tr>
					                <th><?=$icnt?>.상세 이미지(옵션)</td>
					                <td class="inline-wrap">
					                	<div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
				                        	<?=inputImage("gd_img_dt" . $icnt,"gd_img_dt" . $icnt,getData($info,"gd_img_dt"  . $icnt,""),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
				                        </div>
				                        <p class="exc1 ml-10">권장 포멧 : png , 사이즈 400px x 400px, 크기 : 100kb~200kb </p>
					                </td>
					            </tr>
					            <?php endfor;?>
							</tbody>
				        </table>
                    </td>
                </tr>
                <tr class="cont_img_url  <?=getData($info,"gd_img_type",$setting["st_goods_image_type"]) =="img" ? "hidden":""?>">
                    <th scope="col">대표 이미지<em class="ess"></em></th>
                    <td class="inline-wrap">
                       	<div class="input-text w400">
                            <?=inputText("gd_img_main_url","gd_img_main_url",getData($info,"gd_img_main_url"),"http://","",200)?>
                        </div>
                        <a href="javascript:void(0);" class="btn-xs btn-type2 btnPreview" style="width:auto;">미리보기</a>
                        <p class="exc1 d-block">권장 포멧 : png, 권장 사이즈 : 600px x 600px, 크기 : 200kb~500kb</p>
                         <p class="exc1">리스트 이미지 미설정시 대표 이미지로 대체 됩니다.</p>
                        <p class="exc1">상세 이미지 미설정시 대표 이미지로 대체 됩니다.</p>
                    </td>
                </tr>
                <tr class="cont_img_url  <?=getData($info,"gd_img_type",$setting["st_goods_image_type"]) =="img" ? "hidden":""?>">
                    <th scope="col">개별 이미지</th>
                    <td>
                       <table class="table-form">
				            <colgroup>
				            	<col width="150px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>리스트 이미지(옵션)</td>
					                <td class="inline-wrap">
					                	<div class="input-text w400">
				                            <?=inputText("gd_img_list_url","gd_img_list_url",getData($info,"gd_img_list_url"),"http://","",200)?>
				                        </div>
				                        <a href="javascript:void(0);" class="btn-xs btn-type2 btnPreview"  style="width:auto;">미리보기</a>
				                         <p class="exc1 ml-10">권장 포멧 : png , 사이즈 400px x 400px, 크기 : 100kb~200kb </p>
					                </td>
					            </tr>
					            <?php for($icnt=1;$icnt <= $max_goods_length;$icnt++) : ?>
								<tr>
					                <th><?=$icnt?>.상세 이미지(옵션)</td>
					                <td class="inline-wrap">
					                	<div class="input-text w400">
				                        	<?=inputText("gd_img_dt".$icnt."_url" ,"gd_img_dt".$icnt."_url",getData($info,"gd_img_dt".$icnt."_url"),"http://","",300)?>
				                        </div>
				                        <a href="javascript:void(0);" class="btn-xs btn-type2 btnPreview"  style="width:auto;">미리보기</a>
				                        <p class="exc1 ml-10">권장 포멧 : png , 사이즈 400px x 400px, 크기 : 100kb~200kb </p>
					                </td>
					            </tr>
					            <?php endfor;?>
							</tbody>
				        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="section">
        	<h3 class="sub-title mb-10">상품 상세 정보</h3>
        	<table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">PC,모바일 상세 함께 사용</th>
                    <td>
                        <div class="input-text">
				            <label class="inline-wrap">
				                <?=inputRadio("gd_desc_type","gd_desc_type",['Y'=>'함께사용','N'=>'개별등록'],getData($info,"gd_desc_type",$setting["st_goods_desc_type"]))?>
				            </label>
				        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">상세정보<em class="ess"></em></th>
                    <td>
                        <div class="tab policy js-tab cont_desc_type" style="<?=getData($info,"gd_desc_type",$setting["st_goods_desc_type"]) == "Y" ? "display:none;":""?>">
				            <a data-tab_id="#tab_1" href="javascript:void(0);" class="on">제품상세(PC)</a>
				            <a data-tab_id="#tab_2" href="javascript:void(0);">제품상세(모바일)</a>
				        </div>
				        <div class="tab-content-wrap">
				            <div id="tab_1" class="tab-article on">
				                <div class="input-text">
			                    	<?=inputTextArea("gd_pc_desc","gd_pc_desc",getData($info,"gd_pc_desc"),"","")?>
			                    </div>
				            </div>
				            <div id="tab_2" class="tab-article">
				                <div class="input-text">
			                    	<?=inputTextArea("gd_m_desc","gd_m_desc",getData($info,"gd_m_desc"),"","")?>
			                    </div>
				            </div>
				        </div>
                    </td>
                </tr>
                </tbody>
            </table>
	    </div>
	    <div class="section">
        	<h3 class="sub-title mb-10">이용안내</h3>
        	<table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">이용안내 상세</th>
                    <td>
                    	<table class="table-form">
				            <colgroup>
				            	<col width="150px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>템플릿 로드</td>
					                <td class="">
                       					<?=inputSelect("sel_detail","sel_detail",$select_detail,"","style='width:180px;'")?>
				                        <a href="javascript:void(0);" class="btn-xs btn-type3 btnLoadDetail"  style="width:auto;">가져오기</a>
				                        <p class="exc1 d-block">가져오기 클릭시 템플릿에 등록된 정보를 자공으로 가져옵니다.</p>
				                        <p class="exc1 d-block">입력하지 않은 이용안내는 자동으로 비노출 처리됩니다.</p>
					                </td>
					            </tr>
					            <tr>
					            	<th>결제안내</td>
					                <td>
					                	<div class="input-text">
				                            <?=inputTextArea("gd_detail_pay","gd_detail_pay",getData($info,"gd_detail_pay"),"","","")?>
				                        </div>
					                </td>
					            </tr>
					            <tr>
					            	<th>배송안내</td>
					                <td>
					                	<div class="input-text">
				                            <?=inputTextArea("gd_detail_delivery","gd_detail_delivery",getData($info,"gd_detail_delivery"),"","","")?>
				                        </div>
					                </td>
					            </tr>
					            <tr>
					            	<th>교환/반품/환불안내</td>
					                <td>
					                	<div class="input-text">
				                            <?=inputTextArea("gd_detail_claim","gd_detail_claim",getData($info,"gd_detail_claim"),"","","")?>
				                        </div>
					                </td>
					            </tr>
					            <tr>
					            	<th>교환/반품 제한사항</td>
					                <td>
					                	<div class="input-text">
				                            <?=inputTextArea("gd_detail_claim_deny","gd_detail_claim_deny",getData($info,"gd_detail_claim_deny"),"","","")?>
				                        </div>
					                </td>
					            </tr>
					            <tr>
					            	<th>서비스문의 안내</td>
					                <td>
					                	<div class="input-text">
				                            <?=inputTextArea("gd_detail_service","gd_detail_service",getData($info,"gd_detail_service"),"","","")?>
				                        </div>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>
                </tbody>
            </table>
	    </div>
        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button>
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
        </div>
    </div>
</form>

<script>
	$(document).ready(function(){
		loadEditor("#gd_pc_desc",500);
		loadEditor("#gd_m_desc",400);
		loadEditor("#gd_detail_pay",200);
		loadEditor("#gd_detail_delivery",200);
		loadEditor("#gd_detail_claim",200);
		loadEditor("#gd_detail_claim_deny",200);
		loadEditor("#gd_detail_service",200);

		//1차 카테고리 로드
		getCategoryList(1,0);

		//배송유형 로드
		changeDlvInfo();
		$("#dv_idx option").not(":selected").attr("disabled", "disabled");
		$("#dv_idx option").not(":selected").attr("disabled", "")


		//추가배송정보
		$("#gd_adddlv_type").change(function(){
			if($(this).val()!=""){
				$(".cont_adddlv").show();
			}else{
				$(".cont_adddlv").hide();
			}
		});

		//소수점 자리수 입력제한
		$("#gd_cm3").keydown(function(){
			$(this).val($(this).val().replace(/(\.\d{4})\d+/g, '$1'));
		}).keyup(function(){
			$(this).val($(this).val().replace(/(\.\d{4})\d+/g, '$1'));
		}).keypress(function(){
			$(this).val($(this).val().replace(/(\.\d{4})\d+/g, '$1'));
		});

		//마진방식에 따른 금액처리
		var margin_type = "<?=$setting["st_goods_margin_type"]?>";
		$("[name=gd_prev_price]").click(function(){
			if($(this).val()=="N"){
				$(".cont_prev_price").hide();
			}else{
				$(".cont_prev_price").show();
			}
		});

		$("#gd_p_price,#gd_s_price").blur(function(){
			if(margin_type=="SAME"){
				if($("#gd_p_price").val()=="0" || $("#gd_p_price").val()==""){
					for(var icnt=0;icnt < $(".mg_sale_price").length;icnt++){
						$(".mg_sale_price").text("0원");
					}
					return;
				}
			}else if(margin_type=="DIFF"){
				if($("#gd_p_price").val()=="0" || $("#gd_p_price").val()=="" || $("#gd_s_price").val()=="0" || $("#gd_s_price").val()==""){
					for(var icnt=0;icnt < $(".mg_sale_price").length;icnt++){
						$(".mg_sale_price").text("0원");
					}
					return;
				}
			}

			for(var icnt=0;icnt < $(".mg_sale_price").length;icnt++){
				var per = $(".mg_sale_price").eq(icnt).data("per");
				var price = getSalePrice(margin_type,per,$("#gd_p_price").val(),$("#gd_s_price").val());
				$(".mg_sale_price").eq(icnt).text(price.format() + "원");
			}
		});

		if(margin_type=="SAME"){
			$("#gd_s_price").prop("readonly",true);
			$("#gd_p_price").keyup(function(){
				$("#gd_s_price").val($("#gd_p_price").val());
			});
		}

		//재고사용여부
		$("[name=gd_stock_yn]").change(function(){
			if($("[name=gd_stock_yn]:checked").val()=="Y"){
				$(".cont_stock").removeClass("hidden");
			}else{
				$(".cont_stock").addClass("hidden");
			}
		});

		//옵션사용여부
		$("[name=gd_option_yn]").change(function(){
			if($("[name=gd_option_yn]:checked").val()=="Y"){
				$(".cont_option").removeClass("hidden");
			}else{
				$(".cont_option").addClass("hidden");
			}
		});
		$("[name=gd_option_add_yn]").change(function(){
			if($("[name=gd_option_add_yn]:checked").val()=="Y"){
				$(".cont_option_add").removeClass("hidden");
			}else{
				$(".cont_option_add").addClass("hidden");
			}
		});

		//2차 카테고리 로드
		$("#gd_cate1").change(function(){
			var idx = $(this).val();
			getCategoryList(2,idx);
		});

		//3차 카테고리 로드
		$("#gd_cate2").change(function(){
			var idx = $(this).val();
			getCategoryList(3,idx);
		});

		//카테고리 추가
		$(".btnAddCategory").click(function(){
			if($("#gd_cate1").val()==""){
				COMMON.alert("1차 카테고리를 선택해주세요.");
				return;
			}

			var gd_cate1_nm = $("#gd_cate1").val()=="" ? "" : $("#gd_cate1 option:selected").text();
			var gd_cate2_nm = $("#gd_cate2").val()=="" ? "" : $("#gd_cate2 option:selected").text();
			var gd_cate3_nm = $("#gd_cate3").val()=="" ? "" : $("#gd_cate3 option:selected").text();

			var gd_cate1_idx = $("#gd_cate1").val();
			var gd_cate2_idx = $("#gd_cate2").val();
			var gd_cate3_idx = $("#gd_cate3").val();

			var num = $("[name='ct_idx[]']").length + 1;
			var cate_name = gd_cate1_nm + (gd_cate2_nm !="" ? " <span style='font-size:10px;'>〉</span> " + gd_cate2_nm:"") + (gd_cate3_nm !="" ? " <span style='font-size:10px;'>〉</span> " + gd_cate3_nm:"");
			var cate_idx = (gd_cate3_idx !="" ? gd_cate3_idx : (gd_cate2_idx!="" ? gd_cate2_idx:gd_cate1_idx));

			for(var icnt=0;icnt < $("[name='ct_idx[]']").length;icnt++){
				if($("[name='ct_idx[]']").eq(icnt).val()==cate_idx){
					return;
				}
			}

			var html = "";
			html += "<tr>";
			html += "    <td class='tc'>"+num+"</td>";
			html += "    <td>"+cate_name+"</td>";
			html += "    <td class='tc'>";
			html += "    	<a href='javascript:void(0);' class='btn-xxs btn-type4 btnDelCategory'   style='width:auto;'>삭제</a>";
			html += "    	<input type='hidden' name='ct_idx[]' value='"+cate_idx+"' />";
			html += "    </td>";
			html += "</tr>";

			//빈카테고리 삭제
			if($("#body_category tr").hasClass("empty_category")){
				$(".empty_category").remove();
			}

			$("#body_category").append(html);
		});

		//카테고리 삭제
		$(document).on("click",".btnDelCategory",function(){
			$(this).closest("tr").remove();
		});

		//선택 추가정보 처리
		$("#dv_idx").change(function(){
			changeDlvInfo();
		});

		$("[name=gd_desc_type]").change(function(){
			if($("[name=gd_desc_type]:checked").val()=="Y"){
				$(".cont_desc_type").hide();
			}else{
				$(".cont_desc_type").show();
			}
		});

		//옵션 템플릿 가져오기
		$("#sel_option_group").change(function(){
			var params = {};
			params["og_idx"] = $("#sel_option_group").val();
			comAjax("/admin/goods/goods_option_group_lilst",params,function(result){
				if(result.code == "00"){
					//기존꺼 삭제 처리
					$(".option_tr").remove();

					for(var icnt=0;icnt < result.data.length;icnt++){
						var option_idx = $(".option_tr").length + 1;
						if($(".option_tr").length  >= 3){
							COMMON.alert("옵션은 3개까지 추가 가능합니다.");
							return;
						}
						var html = "";
						html +="<tr class='option_tr'>";
						html +="    <td style='vertical-align:center;text-align:center;'>";
						html +="        <div class='input-text '>";
						html +="            <label>";
						html +="                <input type='text' id='ol_name_"+option_idx+"' name='ol_name[]' placeholder='예시) 색상' maxlength='20' value='"+result.data[icnt].ol_name+"'  />				                            </label>";
						html +="        </div>";
						html +="    </td>";
						html +="    <td>";
						html +="        <div class='input-text'>";
						html +="            <label style='width:100%;'>";
						html +="                <input type='text' id='ol_values_"+option_idx+"' name='ol_values[]'  maxlength='20000' value='"+result.data[icnt].ol_values+"' placeholder='  (,) 또는 ENTER' data-role='tagsinput' style='text-align:left;display:none;' />				                            </label>";
						//html +="            <label style='width:100%;margin-top:4px;'>";
						//html +="                <input type='text' id='ol_prices_"+option_idx+"' name='ol_prices[]'  maxlength='20000' value='"+result.data[icnt].ol_prices+"' placeholder='  (,) 또는 ENTER' data-role='tagsinput' style='text-align:left;display:none;' />				                            </label>";
						html +="        </div>";
						html +="    </td>";
						html +="    <td class='tc'>";
						html +="        <input type='hidden' id='ol_idx_"+option_idx+"' name='ol_idx[]' placeholder='' maxlength='10000' value=''  />";
						html +="        <input type='hidden' id='ol_val_"+option_idx+"' name='ol_val[]' placeholder='' maxlength='10000' value=''  />";
						//html +="        <input type='hidden' id='ol_price_"+option_idx+"' name='ol_price[]' placeholder='' maxlength='10000' value=''  />";
						html +="        <a href='javascript:void(0);' class='btn-type4 btn-xxs btnOptionDel' >삭제</a>";
						html +="    </td>";
						html +="</tr>";

						$("#option_body").append(html);
						$("[name='ol_values[]']").eq($("[name='ol_values[]']").length-1).tagsinput({});
						//$("[name='ol_prices[]']").eq($("[name='ol_prices[]']").length-1).tagsinput({allowDuplicates : true});

						if($(".option_tr").length  > 1){
							$(".btnOptionDel").show();
						}
					}

					$(".btnCompleteOption").click();
				}else{
					COMMON.alert(result.msg);
				}
			});
		});

		//상품정보제공고시 고시정보 가져오기
		$("#sel_notice").change(function(){
			if($(this).val()==""){
				var html = "";
				html += "<tr class='empty_notice'>";
            	html += "	<td colspan='4' class='tc' style='background:#f9f9f9;'>정보재공 고시를 추가해주세요.</td>";
            	html += "</tr>";
				$("#body_notice").html(html);
			}else{
				var params = {};
				params["group"] = $(this).val();
		        comAjax("/admin/goods/goods_edit_notice_list",params,function(result){
		            if(result.code == "00"){
		                $("#body_notice").html(result.data);
		            }else{
		                COMMON.alert(result.msg);
		            }
		        });
			}
		});

		//상품정보제공고시 삭제
		$(document).on("click",".btnDelNotice",function(){
			$(this).closest("tr").remove();

			if($("[name='gd_notice_value[]']").length==0){
				var html = "";
				html += "<tr class='empty_notice'>";
	        	html += "	<td colspan='4' class='tc' style='background:#f9f9f9;'>정보재공 고시를 추가해주세요.</td>";
	        	html += "</tr>";
				$("#body_notice").html(html);
			}
		});

		//상품정보제공고시 상세설명참조로 변경
		$(".btnAllNoticeDefault").click(function(){
			$("[name='gd_notice_value[]']").val("상세설명참조");
		});

		//상품정보제공고시 추가
		$(".btnAddNotice").click(function(){
			var num = $("[name='gd_notice_value[]']").length +1;
			var html = "";
        	html +="<tr>";
			html +="	<td class='tc'>" + (num) + "</td>";
			html +="	<td><div class='input-text'><input type='text' name='gd_notice_title[]' value='' /></div></td>";
			html +="	<td><div class='input-text'><input type='text' name='gd_notice_value[]' value='' style='color:#aaa;' /></div></td>";
			html +="	<td class='tc'>";
			html +="		<a href='javascript:void(0);' class='btn-xxs btn-type4 btnDelNotice'   style='width:auto;'>삭제</a>";
			html +="	</td>";
			html +="</tr>";

			if($("#body_category tr").hasClass("empty_category")){
				$(".empty_notice").remove();
			}

			$("#body_notice").append(html);
		});

		//관련상품 설정처리
		$("[name=gd_rel_type]").change(function(){
			$("[class^=cont_rel_]").addClass("hidden");
			$(".cont_rel_" + $(this).val()).removeClass("hidden");
		});

		//이미지 선택
		$("[name=gd_img_type]").change(function(){
			changeImageType();
		});

		//리스트
		$("#btnList").click(function(){
	        history.go(-1);
	    });

	    //템플릿 로드
	    $(".btnLoadDetail").click(function(){
			if($("#sel_detail").val()==""){
				return;
			}

			var params = {};
			params["idx"] = $("#sel_detail").val();
			comAjax("/admin/goods/goods_edit_detail_info",params,function(result){

				if(result.code == "00"){

					$('#gd_detail_pay').summernote('pasteHTML', result.data.tg_pay_desc);
					$('#gd_detail_delivery').summernote('pasteHTML', result.data.tg_dlv_desc);
					$('#gd_detail_claim').summernote('pasteHTML', result.data.tg_claim_desc);
					$('#gd_detail_claim_deny').summernote('pasteHTML', result.data.tg_claim_deny_desc);
					$('#gd_detail_service').summernote('pasteHTML', result.data.tg_service_desc);

					$("#gd_detail_pay").val(result.data.tg_pay_desc);
					$("#gd_detail_delivery").val(result.data.tg_dlv_desc);
					$("#gd_detail_claim").val(result.data.tg_claim_desc);
					$("#gd_detail_claim_deny").val(result.data.tg_claim_deny_desc);
					$("#gd_detail_service").val(result.data.tg_service_desc);
				}else{
					COMMON.alert(result.msg);
				}
			});
	    });

	    //저장
	    var is_proc = false;
	    $("#btnSave").click(function(){
	    	var brand_yn = "<?=$setting["st_goods_brand_yn"]?>";

	    	if($("[name='ct_idx[]']").length==0){
	    		COMMON.alert("카테고리는 선택해 입력해주세요.",function(){});
	            return;
	    	}


	        if($("#gd_name").val()==""){
	            COMMON.alert("상품명을 입력해주세요.",function(){$("#gd_name").focus();});
	            return;
	        }

	        if($("#gd_p_price").val()==""){
	            COMMON.alert("입고설정가를 입력해주세요.",function(){$("#gd_p_price").focus();});
	            return;
	        }

	        if($("#gd_s_price").val()==""){
	            COMMON.alert("출고설정가를 입력해주세요.",function(){$("#gd_s_price").focus();});
	            return;
	        }

	        if(brand_yn=="Y" && $("#bd_idx").val()==""){
	            COMMON.alert("브랜드를 선택해주세요.",function(){$("#bd_idx").focus();});
	            return;
	        }

	        if($("#dv_idx").val()==""){
	            COMMON.alert("배송유형을 선택해주세요.",function(){$("#dv_idx").focus();});
	            return;
	        }

	        if($("#dv_idx").val()=="50" && $("#gd_kg").val()==""){
	            COMMON.alert("무개별 배송비를 선택하신 경우에는 무게를 반드시 입력해주세요.",function(){$("#dv_idx").focus();});
	            return;
	        }

	        if($("#gd_cm3").val()=="" || $("#gd_cm3").val()=="0"){
	            COMMON.alert("부피별 배송비를 선택하신 경우에는 부피를 반드시 입력해주세요.",function(){$("#gd_cm3").focus();});
	            return;
	        }

	        if($("#gd_origin").val()==""){
	            COMMON.alert("제조사를 입력해주세요.",function(){$("#gd_origin").focus();});
	            return;
	        }

	        if($("[name=gd_img_type]:checked").val()=="img"){
	        	if($("#gd_img_main").val()=="" && $("#gd_img_main_ori").val()==""){
	        		COMMON.alert("대표이미지를 선택해주세요.",function(){$("#gd_img_main").focus();});
	            	return;
	        	}
	        }else{
	        	if($("#gd_img_main_url").val()==""){
	        		COMMON.alert("대표이미지URL을 입력해주세요.",function(){$("#gd_img_main_url").focus();});
	            	return;
	        	}
	        }

	        if($("[name=gd_desc_type]:checked").val()=="Y"){
	        	if($("#gd_pc_desc").val()==""){
	        		COMMON.alert("제품상세정보를 입력해주세요.",function(){$("#gd_pc_desc").focus();});
	            	return;
	        	}
	        }else{
	        	if($("#gd_pc_desc").val()==""){
	        		COMMON.alert("제품상세(PC)정보 를 입력해주세요.",function(){$("#gd_pc_desc").focus();});
	            	return;
	        	}

	        	if($("#gd_m_desc").val()==""){
	        		COMMON.alert("제품상세(모바일)정보를 입력해주세요.",function(){$("#gd_m_desc").focus();});
	            	return;
	        	}
	        }

	        //옵션값 다른곳에 담는다.
	        for(var icnt =1; icnt <= $("[name='ol_idx[]']").length;icnt++){
	        	$("[name='ol_val[]']").eq(icnt-1).val($("#ol_values_" + icnt).val());
	        }

	        //for(var icnt =1; icnt <= $("[name='ol_idx[]']").length;icnt++){
	        	//$("[name='ol_price[]']").eq(icnt-1).val($("#ol_prices_" + icnt).val());
	        //}

	        //옵션값 다른곳에 담는다.
	        for(var icnt =1; icnt <= $("[name='ol_add_idx[]']").length;icnt++){
	        	$("[name='ol_add_val[]']").eq(icnt-1).val($("#ol_add_values_" + icnt).val());
	        }

	        for(var icnt =1; icnt <= $("[name='ol_add_idx[]']").length;icnt++){
	        	$("[name='ol_add_price[]']").eq(icnt-1).val($("#ol_add_prices_" + icnt).val());
	        }

	        if(is_proc==true){
	        	COMMON.alert("처리중입니다.",function(){});
	        	return;
	        }

	        var form = $('#frm');
			var params = new FormData(form[0]);
			is_proc = true;
			fileAjaxLoading("/admin/goods/goods_edit_proc",params,function(result){
				if(result.code == "00"){
					COMMON.alert(result.msg,function(){
						is_proc = false;
						location.reload();
					});
				}else{
					COMMON.alert(result.msg);
					is_proc = false;
				}
			});
	    });
	});

	function changeDlvInfo(){
		/*
		if($("#dv_idx").val()!=""){
			//$("#delivery_info").show();
			var price = $("#dv_idx option:selected").data("dv_price");
			var price_v2 = $("#dv_idx option:selected").data("dv_price_v2");
			var limit_price = $("#dv_idx option:selected").data("dv_limit_price");;
			var dv_type = $("#dv_idx option:selected").data("dv_type");
			if(dv_type=="40" || dv_type=="50" || dv_type=="60" || dv_type=="70"){
				$("#delivery_info").html("조건에 따른 차등 배송비는 <strong class='fc1'>[상품관리 > 배송관리]</strong> 에서 확인바랍니다.");
			}else if(dv_type=="90"){
				$("#delivery_info").html("1.03 부피 당 택배배송비 <strong class='fc1'>" + price.format() + "원</strong> 1.03 부피 당 화물배송비 <strong class='fc1'>" + price_v2.format() + "원</strong> ");
			}else{
				if(dv_type=="00"){
					$("#delivery_info").html("구매 금액에 상관없이 무조건 배송비 없음(<strong class='fc1'>0</strong>원)");
				}else if(dv_type=="10"){
					$("#delivery_info").html("구매 금액에 상관없이 배송비 <strong class='fc1'>"+price.format()+"</strong>원을 부과합니다.");
				}else if(dv_type=="20"){
					$("#delivery_info").html("<strong class='fc1'>" + limit_price.format() + "원</strong> 미만일때 <strong class='fc1'>"+price.format()+"</strong>원을 부과합니다.");
				}else if(dv_type=="30"){
					$("#delivery_info").html("주문 금액에 상관없이 상품 수량마다 배송비 <strong class='fc1'>"+price.format()+"</strong>원을 부과합니다.");
				}
			}
		}else{
			$("#delivery_info").html("");
			$("#delivery_info").hide();
		}
		*/
	}

	function getCategoryList(depth,parent_id){
		var params = {};
		params["depth"] = depth;
		params["parent_id"] = parent_id;

		comAjax("/admin/goods/goods_category",params,function(result){
			if(result.code == "00"){
				$('#gd_cate' + depth).find('option').remove().end().append(result.data);
			}else{
				COMMON.alert(result.msg);
			}
		});
	}

	function changeImageType(){
		if($("[name=gd_img_type]:checked").val()=="img"){
			$(".cont_img_img").removeClass("hidden");
			$(".cont_img_url").addClass("hidden");
		}else{
			$(".cont_img_img").addClass("hidden");
			$(".cont_img_url").removeClass("hidden");
		}
	}
</script>
<script>
	/////////////////////////////////////////////////////////////////
	//옵션처리 시작
	/////////////////////////////////////////////////////////////////
	$(document).ready(function(){
		//옵션처리
		var $tags = $("[name='ol_values[]']").tagsinput();
		//var $tags = $("[name='ol_prices[]']").tagsinput({allowDuplicates : true});
		var $tags = $("[name='ol_add_values[]']").tagsinput();
		var $tags = $("[name='ol_add_prices[]']").tagsinput({allowDuplicates : true});
	});

	$(document).on("click",".btnOptionAdd",function(){
		var option_idx = $(".option_tr").length + 1;
		if($(".option_tr").length  >= 3){
			COMMON.alert("옵션은 3개까지 추가 가능합니다.");
			return;
		}
		var html = "";
		html +="<tr class='option_tr'>";
		html +="    <td style='vertical-align:center;text-align:center;'>";
		html +="        <div class='input-text '>";
		html +="            <label>";
		html +="                <input type='text' id='ol_name_"+option_idx+"' name='ol_name[]' placeholder='예시) 색상' maxlength='20' value=''  />				                            </label>";
		html +="        </div>";
		html +="    </td>";
		html +="    <td>";
		html +="        <div class='input-text'>";
		html +="            <label style='width:100%;'>";
		html +="                <input type='text' id='ol_values_"+option_idx+"' name='ol_values[]'  maxlength='20000' value='' placeholder='  (,) 또는 ENTER' data-role='tagsinput' style='text-align:left;display:none;' />				                            </label>";
		//html +="            <label style='width:100%;margin-top:4px;'>";
		//html +="                <input type='text' id='ol_prices_"+option_idx+"' name='ol_prices[]'  maxlength='20000' value='' placeholder='  (,) 또는 ENTER' data-role='tagsinput' style='text-align:left;display:none;' />				                            </label>";
		html +="        </div>";
		html +="    </td>";
		html +="    <td class='tc'>";
		html +="        <input type='hidden' id='ol_idx_"+option_idx+"' name='ol_idx[]' placeholder='' maxlength='10000' value=''  />";
		html +="        <input type='hidden' id='ol_val_"+option_idx+"' name='ol_val[]' placeholder='' maxlength='10000' value=''  />";
		//html +="        <input type='hidden' id='ol_price_"+option_idx+"' name='ol_price[]' placeholder='' maxlength='10000' value=''  />";
		html +="        <a href='javascript:void(0);' class='btn-type4 btn-xxs btnOptionDel' >삭제</a>";
		html +="    </td>";
		html +="</tr>";

		$("#option_body").append(html);
		$("[name='ol_values[]']").eq($("[name='ol_values[]']").length-1).tagsinput({});
		//$("[name='ol_prices[]']").eq($("[name='ol_prices[]']").length-1).tagsinput({allowDuplicates : true});

		if($(".option_tr").length  > 1){
			$(".btnOptionDel").show();
		}
	});

	$(document).on("click",".btnOptionDel",function(){
		$(this).closest("tr").remove();
	});

	$(".btnCompleteOption").click(function(){
		if($("#gd_p_price").val()==""){
			COMMON.alert("입고설정가를 먼저 입력해주세요.",function(){$("#gd_p_price").focus();});
			return;
		}

		if($("#gd_s_price").val()==""){
			COMMON.alert("출고설정가를 먼저 입력해주세요.",function(){$("#gd_s_price").focus();});
			return;
		}

		var s_price = parseInt($("#gd_s_price").val());
		var p_price = parseInt($("#gd_p_price").val());

		for(var icnt =1; icnt <= $("[name='ol_idx[]']").length;icnt++){
        	var ol_value = $("#ol_values_" + icnt).val();
        	//var ol_price = $("#ol_prices_" + icnt).val();

        	//for(var ii=0;ii < ol_price.split(",").length;ii++){
        	//	if(isNaN(ol_price.split(",")[ii])==true){
        	//		COMMON.alert("옵션금액은 숫자로만 입력해주세요.");
        	//		return;
        	//	}
        	//}

        	//if(ol_value.split(",").length !=ol_price.split(",").length){
        	//	COMMON.alert("옵션명과 옵션금액은 동일하게 설정해주세요.");
        	//	return;
        	//}
        }

		var option_array = new Array();
		var arr_length = $("[name='ol_idx[]']").length;
		for(var icnt =1; icnt <= $("[name='ol_idx[]']").length;icnt++){
			var ol_name = $("#ol_name_" + icnt).val();
        	var ol_value = $("#ol_values_" + icnt).val();
        	//var ol_price = $("#ol_prices_" + icnt).val();

        	if(ol_name==""){
        		COMMON.alert("옵션명을 입력해주세요.");
        		return;
        	}

        	//if(ol_value==""){
        	//	COMMON.alert("옵션값을 입력해주세요.");
        	//	return;
        	//}

        	option_array.push({ol_name:ol_name,ol_value:ol_value.split(","),ol_price:0})
        }


        //리스트 만들기
        var item ="";
    	item +="<tr>";
		item +="    <td class='tc'>";
		item +="        <div class='input-checkbox'>";
		item +="            <label>";
		item +="                <input type='checkbox' name='chk_opt' checked  />";
		item +="                <span style='padding-left:5px;'><strong></strong></span>";
		item +="            </label>";
		item +="        </div>";
		item +="    </td>";
		item +="    <td>{{op_name}}</td>";
		item +="    <td style='display:none;' class='tc'><div class='input-text'><input type='number' name='op_p_price[]'  placeholder='0' value='{{op_p_price}}' /></div></td>";
		item +="    <td style='display:none;' class='tc'><div class='input-text'><input type='number' name='op_add_price[]'  placeholder='0' value='{{op_add_price}}' /></div></td>";
		item +="    <td class='tc'><div class='input-text'><input type='number' name='op_stock[]'  placeholder='0' value='0' /></div></td>";
		item +="    <td class='tc'>";
		item +="		<input type='hidden' name='op_name[]' value='{{op_name}}'  value='0' />";
		item +="        <select  name='op_use_yn[]' style='width:100% !important;min-width:auto !important;'>";
		item +="            <option value='Y'>사용</option>";
		item +="            <option value='N'>미사용</option>";
		item +="        </select>";
		item +="    </td>";
		item +="</tr>";

		$("#option_list_body").html("");
        if(arr_length==1){
        	for(var icnt_1 = 0;icnt_1 < option_array[0].ol_value.length;icnt_1++){
        		var copy = item;
        		var op_val_1 = option_array[0].ol_value[icnt_1];
        		var op_price_1 = 0;

        		copy = copy.replaceAll("{{op_name}}",op_val_1);
        		copy = copy.replaceAll("{{op_p_price}}",(p_price + parseInt(op_price_1)));
        		copy = copy.replaceAll("{{op_add_price}}",(s_price + parseInt(op_price_1)));
        		$("#option_list_body").append(copy);
        	}
        }else if(arr_length==2){
        	for(var icnt_1 = 0;icnt_1 < option_array[0].ol_value.length;icnt_1++){
        		for(var icnt_2 = 0;icnt_2 < option_array[1].ol_value.length;icnt_2++){
	        		var copy = item;
	        		var op_val_1 = option_array[0].ol_value[icnt_1];
	        		var op_val_2 = option_array[1].ol_value[icnt_2];

	        		var op_price_1 = 0;
	        		var op_price_2 = 0;

	        		copy = copy.replaceAll("{{op_name}}",op_val_1 + "," + op_val_2);
	        		copy = copy.replaceAll("{{op_p_price}}",(p_price + parseInt(op_price_1) + parseInt(op_price_2)));
        			copy = copy.replaceAll("{{op_add_price}}",(s_price + parseInt(op_price_1) + parseInt(op_price_2)));
	        		$("#option_list_body").append(copy);
	        	}
        	}
        }else if(arr_length==3){
        	for(var icnt_1 = 0;icnt_1 < option_array[0].ol_value.length;icnt_1++){
        		for(var icnt_2 = 0;icnt_2 < option_array[1].ol_value.length;icnt_2++){
        			for(var icnt_3 = 0;icnt_3 < option_array[2].ol_value.length;icnt_3++){
		        		var copy = item;
		        		var op_val_1 = option_array[0].ol_value[icnt_1];
		        		var op_val_2 = option_array[1].ol_value[icnt_2];
		        		var op_val_3 = option_array[2].ol_value[icnt_3];

		        		var op_price_1 = 0;
	        			var op_price_2 = 0;
	        			var op_price_3 = 0;

		        		copy = copy.replaceAll("{{op_name}}",op_val_1 + "," + op_val_2 + "," + op_val_3);
		        		copy = copy.replaceAll("{{op_p_price}}",(p_price + parseInt(op_price_1) + parseInt(op_price_2) + parseInt(op_price_3)));
        				copy = copy.replaceAll("{{op_add_price}}",(s_price + parseInt(op_price_1) + parseInt(op_price_2) + parseInt(op_price_3)));
		        		$("#option_list_body").append(copy);
		        	}
	        	}
        	}
        }

        $(".cont_option_list").removeClass("hidden");
	});

	$(".btnOpAll").click(function(){
		var field = $(this).data("field");
		if(field=="remove"){
			var arr_del = new Array();
			var length = $("[name=chk_opt]").length;
			for(var icnt=$("[name=chk_opt]").length;icnt >=0;icnt--){
				if($("[name=chk_opt]").eq(icnt).is(":checked")){
					$("[name=chk_opt]").eq(icnt).closest("tr").remove();
				}
			}
		}else{
			var value = $(this).closest("td").find("[name=op_set]").val();
			for(var icnt=$("[name=chk_opt]").length;icnt >=0;icnt--){
				if($("[name=chk_opt]").eq(icnt).is(":checked")){
					$("[name='"+field+"']").eq(icnt).val(value);
				}
			}
		}
	});

	$(document).on("click",".btnOptionAddAdd",function(){
		var option_idx = $(".option_add_tr").length + 1;
		if($(".option_add_tr").length  >= 20){
			COMMON.alert("옵션은 20개까지 추가 가능합니다.");
			return;
		}
		var html = "";
		html +="<tr class='option_add_tr'>";
		html +="    <td style='vertical-align:center;text-align:center;'>";
		html +="        <div class='input-text '>";
		html +="            <label>";
		html +="                <input type='text' id='ol_add_name_"+option_idx+"' name='ol_add_name[]' placeholder='예시) 색상' maxlength='20' value=''  />				                            </label>";
		html +="        </div>";
		html +="    </td>";
		html +="    <td>";
		html +="        <div class='input-text'>";
		html +="            <label style='width:100%;'>";
		html +="                <input type='text' id='ol_add_values_"+option_idx+"' name='ol_add_values[]' maxlength='20000' value='' placeholder='  (,) 또는 ENTER' data-role='tagsinput' style='text-align:left;display:none;' />				                            </label>";
		html +="        </div>";
		html +="    </td>";
		html +="    <td class='tc'>";
		html +="        <input type='hidden' id='ol_add_idx_"+option_idx+"' name='ol_add_idx[]' placeholder='' maxlength='10000' value=''  />";
		html +="        <input type='hidden' id='ol_add_val_"+option_idx+"' name='ol_add_val[]' placeholder='' maxlength='10000' value=''  />";
		html +="        <a href='javascript:void(0);' class='btn-type4 btn-xxs btnOptionAddDel' >삭제</a>";
		html +="    </td>";
		html +="</tr>";

		$("#option_add_body").append(html);
		$("[name='ol_add_values[]']").eq($("[name='ol_add_values[]']").length-1).tagsinput({});

		if($(".option_tr").length  > 1){
			$(".btnOptionAddDel").show();
		}

	});

	$(document).on("click",".btnOptionAddDel",function(){
		$(this).closest("tr").remove();

		if($(".option_add_tr").length  == 1){
			$(".btnOptionAddDel").hide();
		}else{
			$(".btnOptionAddDel").show();
		}
		$(".btnOptionAddAdd").eq($(".btnOptionAddAdd").length -1).show();
	});


	$(".btnCompleteOptionAdd").click(function(){
		var option_array = new Array();
		var arr_length = $("[name='ol_add_idx[]']").length;
		for(var icnt =1; icnt <= $("[name='ol_add_idx[]']").length;icnt++){
			var ol_name = $("#ol_add_name_" + icnt).val();
        	var ol_value = $("#ol_add_values_" + icnt).val();

        	if(ol_name==""){
        		COMMON.alert("옵션명을 입력해주세요.");
        		return;
        	}

        	if(ol_value==""){
        		COMMON.alert("옵션값을 입력해주세요.");
        		return;
        	}

        	option_array.push({ol_name:ol_name,ol_value:ol_value.split(",")})
        }


        //리스트 만들기
        var item ="";
    	item +="<tr>";
		item +="    <td class='tc'>";
		item +="        <div class='input-checkbox'>";
		item +="            <label>";
		item +="                <input type='checkbox' name='chk_add_opt'  />";
		item +="                <span style='padding-left:5px;'><strong></strong></span>";
		item +="            </label>";
		item +="        </div>";
		item +="    </td>";
		item +="    <td>{{op_name}}</td>";
		item +="    <td class='tc'><div class='input-text'><input type='number' name='op_add_p_price[]'  placeholder='0' value='0' /></div></td>";
		item +="    <td class='tc'><div class='input-text'><input type='number' name='op_add_add_price[]'  placeholder='0' value='0' /></div></td>";
		item +="    <td class='tc'><div class='input-text'><input type='number' name='op_add_stock[]'  placeholder='0' value='0' /></div></td>";
		item +="    <td class='tc'>";
		item +="		<input type='hidden' name='op_add_name[]' value='{{op_name}}'  value='0' />";
		item +="        <select  name='op_add_use_yn[]' style='width:100% !important;min-width:auto !important;'>";
		item +="            <option value='Y'>사용</option>";
		item +="            <option value='N'>미사용</option>";
		item +="        </select>";
		item +="    </td>";
		item +="</tr>";

		$("#option_add_list_body").html("");
        for(var icnt_1 = 0;icnt_1 < option_array.length;icnt_1++){
        	for(var icnt_2=0;icnt_2 < option_array[icnt_1].ol_value.length;icnt_2++){
        		var copy = item;
	    		var op_val_1 = option_array[icnt_1].ol_value[icnt_2];
	    		var ol_name_1 = option_array[icnt_1].ol_name;

	    		copy = copy.replaceAll("{{op_name}}",ol_name_1 + " : " + op_val_1);
	    		$("#option_add_list_body").append(copy);
        	}
    	}

    	$(".cont_option_add_list").removeClass("hidden");
	});

	$(".btnOpAddAll").click(function(){
		var field = $(this).data("field");
		if(field=="remove"){
			var arr_del = new Array();
			var length = $("[name=chk_add_opt]").length;
			for(var icnt=$("[name=chk_add_opt]").length;icnt >=0;icnt--){
				if($("[name=chk_add_opt]").eq(icnt).is(":checked")){
					$("[name=chk_add_opt]").eq(icnt).closest("tr").remove();
				}
			}
		}else{
			var value = $(this).closest("td").find("[name=op_set]").val();
			for(var icnt=$("[name=chk_add_opt]").length;icnt >=0;icnt--){
				if($("[name=chk_add_opt]").eq(icnt).is(":checked")){
					$("[name='"+field+"']").eq(icnt).val(value);
				}
			}
		}
	});

    $(".btnPreview").click(function(){
        var url = $(this).closest("td").find("[type=text]").val();
        if(url !=""){
            $.magnificPopup.open({
              items: {
                src: url
              },
              type: 'image'
            });
        }
    })
</script>
