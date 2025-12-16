<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

//배너 리스트
function showBannerList($bg_code,$limit = 50){
	$db = DB('default', true);
	$bg_code = inject($bg_code);
	$sql = "SELECT bn.*
		FROM st_banner_group bg
			inner join st_banner bn
		    	on bg.bg_idx = bn.bg_idx and bg.bg_type = bn.bg_type
		where bg.bg_status = 'Y'
		and bn.bn_status = 'Y'
		and bg.bg_code = '$bg_code'
		and (bn_period_type = 'A' OR ((date_format(bn_sdate,'%Y%m%d') <= date_format(now(),'%Y%m%d') and date_format(bn_edate,'%Y%m%d') >= date_format(now(),'%Y%m%d'))))
		order by bn.bn_sort asc,bn_idx desc
		limit 0,$limit
	";

	$qry = $db->query($sql);
	$list = $qry->result_array();

	$result = "";
	$device_type = device_type();
	foreach($list as $row){
		$bg_idx = $row["bg_idx"];
		$bn_name = $row["bn_name"];
		$bn_link_type = ($row["bn_link_type"]=="C" ? "_self":"_blank");
		$bn_link = $row["bn_link"];
		$bn_bg_color = $row["bn_bg_color"];
		$bg_img = $device_type=="M" ? $row["bn_m_img"]:$row["bn_pc_img"];

		if($bg_idx=="7"){
			$bn_link = "/goods/brand?chk_brand=" . $row["bn_link"];
		}

		$style = "";
		if($bn_bg_color!=""){
			$style .= "background:#$bn_bg_color;";
		}

		if($bn_link_type!="N" && $row["bn_link"]!=""){
			$html = "
				<div class='item swiper-slide' style='$style'>
					<a href='$bn_link' target='$bn_link_type'>
	                	<img src='$bg_img'  alt='$bn_name' title='$bn_name' />
	                </a>
	            </div>
			";
			$result .= $html;
		}else{
			$html = "
				<div class='item swiper-slide' style='$style'>
	                <img src='$bg_img'  alt='$bn_name' title='$bn_name' />
	            </div>
			";
			$result .= $html;
		}
	}

	return $result;
}

//배너 리스트
function showBannerListData($list,$limit = 50){
	$result = "";
	foreach($list as $row){

		$bn_name = $row["bn_name"];
		$bn_link_type = $row["bn_link_type"];
		$bn_link = $row["bn_link"];
		$bn_bg_color = $row["bn_bg_color"];
		$bg_img = $row["bg_img"];

		$style = "";
		if($bn_bg_color!=""){
			$style .= "background:#$bn_bg_color;";
		}

		if($bn_link_type!="N" && $row["bn_link"]!="javascript:void(0);" && $row["bn_link"]!="javascript:void(0);"){
			$html = "
				<div class='item swiper-slide' style='$style'>
					<a href='$bn_link' target='$bn_link_type'>
	                	<img src='$bg_img'  alt='$bn_name' title='$bn_name' />
	                </a>
	            </div>
			";
			$result .= $html;
		}else{
			$html = "
				<div class='item swiper-slide' style='$style'>
	                <img src='$bg_img'  alt='$bn_name' title='$bn_name' />
	            </div>
			";
			$result .= $html;
		}
	}

	return $result;
}

//배너한게 HTML 생성
function showOneBanner($bg_img,$bn_name,$bn_link_type,$bn_link){
	$result = "";
	if($bn_link_type=="N" || $bn_link==""){
		$html = "
			<div class='item' style=''>
                <img src='$bg_img'  alt='$bn_name' title='$bn_name' />
            </div>
		";
		$result .= $html;
	}else{
		$html = "
			<div class='item' style=''>
				<a href='$bn_link' target='$bn_link_type'>
                	<img src='$bg_img'  alt='$bn_name' title='$bn_name' />
                </a>
            </div>
		";
		$result .= $html;
	}
	return $result;
}

//팝업 HTML 생성
function showPopup(){
	$db = DB('default', true);
	$sql = "SELECT pp.*
		FROM st_popup pp

		where pp.pp_status = 'Y'
		and (((date_format(pp_sdate,'%Y%m%d') <= date_format(now(),'%Y%m%d') and date_format(pp_edate,'%Y%m%d') >= date_format(now(),'%Y%m%d'))))
		order by pp.pp_idx desc
		limit 0,5
	";

	$qry = $db->query($sql);
	$list = $qry->result_array();

	$result = "";
	$device_type = device_type();
	foreach($list as $row){
		$pp_img = $device_type=="M" ? $row["pp_m_img"]:$row["pp_pc_img"];
		$pp_idx = $row["pp_idx"];
		$pp_link_type = $row["pp_link_type"];
		$pp_link = $row["pp_link"];
		$pp_top = $row["pp_top"];
		$pp_left = $row["pp_left"];

		$pp_img = $device_type=="M" ? $row["pp_m_img"]:$row["pp_pc_img"];

		$html = "
			<div class='popup-container'>
				<div class='popup-layer' id='popup-layer-$pp_idx'>
					<div class='popup-layer-content' id='popupLik-$pp_idx'>
						<img src='$pp_img' style='' />
					</div>
					<div class='popup-foot'>
						<input type='checkbox' id='closeTime-$pp_idx' value='0' /> <label for='closeTime-$pp_idx'>오늘 하루 보지 않기</label>
						<a href='javascript:void(0);' class='button' onclick='$(\"#popup-layer-$pp_idx\").parent().remove();'  >닫기</a>
					</div>
				</div>
			</div>
			<script type='text/javascript'>
			$(function(){
				if(getCookie('it_ck_pop_$pp_idx')=='done'){
					$('#popup-layer-$pp_idx').hide();
					$('#popup-layer-$pp_idx').parent().hide();
				}else{
					$('#popup-layer-$pp_idx').show();
					$('#popup-layer-$pp_idx').parent().show();
				}

				$('#popup-layer-$pp_idx').css({left:'$pp_left',top:'$pp_top'});
				$('#popup-layer-$pp_idx input.button').click(function(){
					$('#popup-layer-$pp_idx').parent().remove();
					return false;
				});
				// 팝업 쿠키
				$('#closeTime-$pp_idx').bind('click', function() {
					if (this.checked) {
						setCookie('it_ck_pop_$pp_idx', 'done',1);
						$('#popup-layer-$pp_idx').parent().remove();
					}
		        });

		        $('#popupLik-$pp_idx').bind('click', function() {
		            var link_type = '$pp_link_type';
		            var link_url = '$pp_link';

		            if (link_url == 'N') {
		                return;
		            }

		            if (link_type == 'B') {
		                window.open(link_url);
		            } else if (link_type == 'C') {
		                location.href = link_url;
		            }
		        });
			});
			</script>
		";
		$result .= $html;
	}
	return $result;
}


//팝업 HTML 생성
function showPopupMobile(){
	$db = DB('default', true);
	$sql = "SELECT pp.*
		FROM st_popup pp

		where pp.pp_status = 'Y'
		and (((date_format(pp_sdate,'%Y%m%d') <= date_format(now(),'%Y%m%d') and date_format(pp_edate,'%Y%m%d') >= date_format(now(),'%Y%m%d'))))
		order by pp.pp_idx desc
	";

	$qry = $db->query($sql);
	$row = $qry->row_array();

	$result = "";
	$pp_img = $row["pp_m_img"];
	$pp_idx = $row["pp_idx"];
	$pp_link_type = $row["pp_link_type"];
	$pp_link = $row["pp_link"];
	$pp_top = $row["pp_top"];
	$pp_left = $row["pp_left"];

	$pp_img = $device_type=="M" ? $row["pp_m_img"]:$row["pp_pc_img"];

	$html = "
        <div id='notice1Panel' class='layer-panel card-type notify' tabindex='-1'>
            <div class='panel-container'>
                <div class='panel-header'>
                    <h2 class='sr-text'>이벤트 알림</h2>
                </div>
                <div class='panel-body'>
                    <div class='panel-content'>

                        <div class='event-banner'>
                            <img src='$pp_img' width='828' height='861' alt='나우잡 앱출시, 기념이벤트'>
                        </div>

                    </div>
                </div>
                <div class='panel-footer'>
                    <div class='sticky-bottom'>
                        <!-- 쿠키 추가 버튼 -->
                        <!-- 20221122 수정 -->
                        <button type='button' aria-controls='notice1Panel' onclick='setCookie('notice1Panel', 'hide', 365);return false;' class='btn btn-secondary js-toggle-panel'>다시 보지 않기</button>
                        <button type='button' aria-controls='notice1Panel' class='btn btn-primary js-toggle-panel'>확인</button>
                    </div>
                </div>
            </div>
        </div>
        <script>showNotice('#notice1Panel', 'notice1Panel');</script>
	";
	$result .= $html;
	return $result;
}

//상품 리스트 생성
function showGoodsListCode($ds_code,$type="basic",$margin_type = null,$sale_per = null,$is_login = null,$guest_yn = null){
	$ci =& get_instance();
	$db = DB('default', true);
	$in_code = "";
	if(is_array($ds_code)){
		foreach($ds_code as $code){
			if($in_code==""){
				$in_code = "'" . inject($code) . "'";
			}else{
				$in_code .=",'" . inject($code) . "'";
			}
		}
	}else{
		$in_code =  "'" . inject($ds_code) . "'";
	}

	$mm_idx = $ci->user->getUserIdx();
	$sql = "
		SELECT gd.gd_idx
			,gd.gd_code
			,gd.gd_name
			,gd.gd_list_name
			,gd.gd_sub_name
			,gd.gd_s_price
			,gd.gd_p_price
			,gd.bd_idx
			,gd.gd_img_type
			,gd.gd_img_main
			,gd.gd_img_list
			,gd.gd_img_main_url
			,gd.gd_img_list_url
			,gd.show_yn
			,dp.ds_code
			,(case when gw.gd_idx is null then 'N' else 'Y' end) as wish_yn
			,cp.gc_name
			,cp.gc_won
			,cp.gc_won_type
			,(case when gd_stock_yn='Y' and ((gd_stock = 0 and gd_option_yn = 'N') or (gd_option_yn = 'Y' and gg_stock = 0)) then 'Y' else 'N' end) as sold_out
		FROM st_display dp
			inner join st_display_goods ds
		    	on dp.ds_idx = ds.ds_idx
		    inner join vw_goods gd
		    	on ds.gd_idx = gd.gd_idx
		    left join st_goods_wish gw
		    	on gd.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
		    left join (
				 select aa.gc_idx
				        ,aa.gc_name
				        ,aa.gc_won
				        ,aa.gc_won_type
				        ,bb.gd_idx
				 FROM (
					  select aa.gc_idx
					        ,aa.gc_name
					        ,aa.gc_won
					        ,aa.gc_won_type
					  FROM
					  st_goods_coupon aa
					    left join st_goods_coupon_member bb
					        on bb.gc_idx = bb.gc_idx
					  where aa.gc_sdate <=now() and gc_edate >= now()
					  and (aa.gc_mem_type in ('','$margin_type') or (aa.gc_mem_type = 'SELECT' and aa.gc_idx = bb.gc_idx and bb.mm_idx = '$mm_idx'))
					  group by aa.gc_idx
				) aa inner join st_goods_coupon_list bb
				  on aa.gc_idx = bb.gc_idx
			) cp on gd.gd_idx = cp.gd_idx
			left join (
					SELECT gd_idx,sum(gg_stock) as gg_stock
					FROM `st_goods_option_list`
					group by gd_idx
				) dd on gd.gd_idx = dd.gd_idx

		where dp.ds_code in($in_code)
		and dp.ds_status = 'Y'
		and gd.gd_status = 'Y'
		group by gd.gd_idx
		order by ds.pg_sort asc,ds.pg_idx desc
	";

	$qry = $db->query($sql);
	$list = $qry->result_array();

	//필요정보 설정
	$CI = &get_instance();
	$device_type = device_type();

	//결과 처리
	$result = "";
	foreach($list as $row){
		if($type=="basic"){
			$result .= showGoodsType1($row,$margin_type,$sale_per,$is_login,$guest_yn);
		}else if($type=="main"){
			$result .= showGoodsMain($row,$margin_type,$sale_per,$is_login,$guest_yn);
		}
	}

	return $result;
}

function showGoodsListCodeMobile($ds_code,$margin_type,$sale_per,$is_login){
	$ci =& get_instance();
	$db = DB('default', true);
	$in_code = "";
	if(is_array($ds_code)){
		foreach($ds_code as $code){
			if($in_code==""){
				$in_code = "'" . inject($code) . "'";
			}else{
				$in_code .=",'" . inject($code) . "'";
			}
		}
	}else{
		$in_code =  "'" . inject($ds_code) . "'";
	}

	$mm_idx = $ci->user->getUserIdx();
	$sql = "
		SELECT gd.gd_idx
			,gd.gd_code
			,gd.gd_name
			,gd.gd_list_name
			,gd.gd_sub_name
			,gd.gd_s_price
			,gd.gd_p_price
			,gd.bd_idx
			,gd.gd_img_type
			,gd.gd_img_main
			,gd.gd_img_list
			,gd.gd_img_main_url
			,gd.gd_img_list_url
			,gd.show_yn
			,dp.ds_code
			,(case when gw.gd_idx is null then 'N' else 'Y' end) as wish_yn
			,cp.gc_name
			,cp.gc_won
			,cp.gc_won_type
			,(case when gd_stock_yn='Y' and ((gd_stock = 0 and gd_option_yn = 'N') or (gd_option_yn = 'Y' and gg_stock = 0)) then 'Y' else 'N' end) as sold_out
		FROM st_display dp
			inner join st_display_goods ds
		    	on dp.ds_idx = ds.ds_idx
		    inner join vw_goods gd
		    	on ds.gd_idx = gd.gd_idx
		    left join st_goods_wish gw
		    	on gd.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
		    left join (
				select
				     aa.gc_name
				    ,aa.gc_won
				    ,aa.gc_won_type
				    ,bb.gd_idx
				FROM st_goods_coupon aa
				    inner join st_goods_coupon_list bb
				        on aa.gc_idx = bb.gc_idx
				    inner join (
				        SELECT gd_idx,max(aa.gc_idx) as gc_idx
				        FROM st_goods_coupon aa
				            inner join st_goods_coupon_list bb
				                on aa.gc_idx = bb.gc_idx
				        where aa.gc_sdate <=now() and gc_edate >= now()
				        group by gd_idx
				    ) cc
				        on bb.gc_idx = bb.gc_idx and bb.gd_idx = cc.gd_idx and aa.gc_idx = cc.gc_idx
				   where aa.gc_mem_type in ('','$margin_type')
				) cp on gd.gd_idx = cp.gd_idx
			left join (
					SELECT gd_idx,sum(gg_stock) as gg_stock
					FROM `st_goods_option_list`
					group by gd_idx
				) dd on gd.gd_idx = dd.gd_idx
		where dp.ds_code in($in_code)
		and dp.ds_status = 'Y'
		and gd.gd_status = 'Y'
		group by gd.gd_idx
		order by ds.pg_sort asc,ds.pg_idx desc
	";

	$qry = $db->query($sql);
	$list = $qry->result_array();

	$result = "";
	$device_type = device_type();
	foreach($list as $row){
		$result .= showGoodsTypeM($row,$margin_type,$sale_per,$is_login,$guest_yn);
	}

	return $result;
}

//데이터 기준 상품 리슽 생성
function showGoodsListData($list,$type="basic",$margin_type = null,$sale_per = null,$is_login = null,$guest_yn = null){
	$result = "";
	foreach($list as $row){
		if($type=="basic"){
			$result .= showGoodsType1($row,$margin_type,$sale_per,$is_login,$guest_yn);
		}else if($type=="main"){
			$result .= showGoodsMain($row,$margin_type,$sale_per,$is_login,$guest_yn);
		}
	}
	return $result;
}

function showDeliveryInfo($delivery_info,$add_yn = "Y",$delivery_type =""){
	$html = "";
	switch($delivery_info["dv_type"]){
		case "00":
			$html = "<span class='delivery'>무료배송</span>";
			break;
		case "90":
			$html = "<span class='delivery'>부피별 배송비</span>";
			break;
		case "10":
			$html = "<span class='delivery'>유료배송비<em>".number_format($delivery_info["dv_price"])."</em>원</span>";
			break;
		case "20":
			$html = "<span class='delivery'>조건부 무료배송비<em>".number_format($delivery_info["dv_price"])."(".number_format($delivery_info["dv_limit_price"])."이상구매시 배송비 0)</em>원</span>";
			break;
		case "30":
			$html = "<span class='delivery'>수량별 배송비<em>(수량 ".number_format($delivery_info["dv_limit_price"]) . " 개당 ".number_format($delivery_info["dv_price"])."원)</em></span>";
			break;
		case "40":
			$html = "<span class='delivery'>금액별 차등배송비</span>";
			if($add_yn=="Y"){
				$html .= "<div class='delivery_add'>";
				foreach($delivery_info["list"] as $row){
					$html .= "<li>총 상품금액이 ".number_format($row["dt_start"])."원 - ".number_format($row["dt_end"])."원 배송비 " .number_format($row["dt_price"]). "원</li>";
				}
				$html .= "</div>";
			}
			break;
		case "50":
			$html = "<span class='delivery'>무게별 차등배송비</span>";
			if($add_yn=="Y"){
				$html .= "<div class='delivery_add'>";
				foreach($delivery_info["list"] as $row){
					$html .= "<li>총 상품무게 ".number_format($row["dt_start"])."kg - ".number_format($row["dt_end"])."kg 의 배송비 " .number_format($row["dt_price"]). "원</li>";
				}
				$html .= "</div>";
			}
			break;
		case "60":
			$html = "<span class='delivery'>수량별 차등배송비</span>";
			if($add_yn=="Y"){
				$html .= "<div class='delivery_add'>";
				foreach($delivery_info["list"] as $row){
					$html .= "<li>총 상품수량 ".number_format($row["dt_start"])."개 - ".number_format($row["dt_end"])."개 의 배송비 " .number_format($row["dt_price"]). "원</li>";
				}
				$html .= "</div>";
			}
			break;
	}

	return $html;
}

# 상품 HTML type1
function showGoodsType1($info,$margin_type,$sale_per,$is_login,$guest_yn){
	//견적가
	$CI = &get_instance();

	$sale_price = number_format(getOriginPrice($margin_type,$sale_per,$info["gd_p_price"],$info["gd_s_price"],$info["gd_i_price"]));
	$coupon_html = "";
	$coupon_name = "";
	$coupon_price = 0;
	$coupon_type = "원";

	//정보 못가져왔을때 비노출처리
	if($margin_type==null){
		$is_login = false;
	}

	if($info["gc_won"]!=null && $info["gc_name"]!="" && $info["gc_won"] > 0){
		$coupon_name = "할인";
		$coupon_price = $info["gc_won"];
		if($info["gc_won_type"]=="WON"){
			$coupon_type = "원";
			$coupon_price = number_format($coupon_price);
		}else{
			$coupon_type = "%";
		}
	}

	if($coupon_name!=""){
		$coupon_html = "
            <div class='coupon'>
				<div class='name'>".$coupon_name."</span><span class='won'>".$coupon_price . "" . $coupon_type . "</span></div>
            </div>
		";
	}

	$sold_out = "";
	if($info["sold_out"]=="Y"){
		$sold_out = "
	        <div class='soldout'>
				<div class='icon'>품절</div>
	        </div>
		";
	}

	$html = "
		<li>
	        <div class='goods_box_1'>
	            <div class='thumb thumbnail-wrappper'>
					<div class='thumbnail'>
						<a class='inner' href='/goods/detail/".$info["gd_idx"]."'><img src='" . getGoodsListImg($info) ."' onerror='this.style.display = \"none\"' /></a>
					</div>
					<!-- 퀵메뉴 -->
	                <div class='item_quick'>
	                    <a href='javascript:void(0);' class='btn cart btnCart' data-gd_idx='".$info["gd_idx"]."' title='장바구니 담기'>
	                    	<img src='/resource/shop/basic/web/img/icon/iquick_cart.png' alt='' /></a>
	                    <a href='javascript:void(0);' class='btn wish js_wish btnWish' data-gd_idx='".$info["gd_idx"]."' title='찜하기'>
	                    	<img src='/resource/shop/basic/web/img/icon/iquick_wish.png' alt='' class='wish_off ".($info["wish_yn"]=="Y" ? "on":"off")."'/><img src='/resource/shop/basic/web/img/icon/iquick_wish_on.png' alt='' class='wish_on  ".($info["wish_yn"]!="Y" ? "on":"off")."' />
	                    </a>
	                    <a href='/goods/detail/".$info["gd_idx"]."' class='btn blank' title='새창보기' target='_blank'>
	                    	<img src='/resource/shop/basic/web/img/icon/iquick_blank.png' alt='' />
	                    </a>
	                </div>
	                 $sold_out
				</div>
	            <!-- 상품정보 -->
	            <div class='info'>
	                <div class='name_box'>
	                    <div class='item_name'>".($info["gd_list_name"]!="" ? $info["gd_list_name"] : $info["gd_name"])."</div>
	                    <div class='sub_name ellipsis'>".$info["gd_sub_name"]."</div>
	                </div>
	";

	if($info["show_yn"]=="N"){
		$html .= "
                <div class='price'>
					<div class='after'><span class='won'>[비매품]</span></div>
                </div>
		";
	}else if($is_login==true || ($guest_yn == "Y")){
		$html .= "
		                <div class='price'>
							<div class='after'><span class='won'>".$sale_price."</span>원</div>
		                </div>
		";
	}else{
		$html .= "
		                <!--div class='price'>
							<div class='after'><span class='won'>로그인후 확인가능</div>
		                </div-->
		";
	}
	$html .= "
	            </div>
	            $coupon_html

	        </div>
	    </li>
	";

	return $html;
}

# 상품 HTML Main type
function showGoodsMain($info,$margin_type,$sale_per,$is_login,$guest_yn){
	//견적가
	$CI = &get_instance();

	$sale_price = number_format(getOriginPrice($margin_type,$sale_per,$info["gd_p_price"],$info["gd_s_price"],$info["gd_i_price"]));
	$coupon_html = "";
	$coupon_name = "";
	$coupon_price = 0;
	$coupon_type = "원";

	//정보 못가져왔을때 비노출처리
	if($margin_type==null){
		$is_login = false;
	}

	if($info["gc_won"]!=null && $info["gc_name"]!="" && $info["gc_won"] > 0){
		$coupon_name = "할인";
		$coupon_price = $info["gc_won"];
		if($info["gc_won_type"]=="WON"){
			$coupon_type = "원";
			$coupon_price = number_format($coupon_price);
		}else{
			$coupon_type = "%";
		}
	}

	if($coupon_name!=""){
		$coupon_html = "
            <div class='coupon'>
				<div class='name'>".$coupon_name."</span><span class='won'>".$coupon_price . "" . $coupon_type . "</span></div>
            </div>
		";
	}

	$sold_out = "";
	if($info["sold_out"]=="Y"){
		$sold_out = "
	        <div class='soldout'>
				<div class='icon'>품절</div>
	        </div>
		";
	}

	$html = "
		<li class='swiper-slide'>
	        <div class='goods_box_main'>
	            <div class='thumb thumbnail-wrappper'>
					<div class='thumbnail'>
						<a class='inner' href='/goods/detail/".$info["gd_idx"]."'><img src='" . getGoodsListImg($info) ."' onerror='this.style.display = \"none\"' /></a>
					</div>
					<!-- 퀵메뉴 -->
	                <div class='item_quick'>
	                    <a href='javascript:void(0);' class='btn cart btnCart' data-gd_idx='".$info["gd_idx"]."' title='장바구니 담기'>
	                    	<img src='/resource/shop/basic/web/img/icon/iquick_cart.png' alt='' /></a>
	                    <a href='javascript:void(0);' class='btn wish js_wish btnWish' data-gd_idx='".$info["gd_idx"]."' title='찜하기'>
	                    	<img src='/resource/shop/basic/web/img/icon/iquick_wish.png' alt='' class='wish_off ".($info["wish_yn"]=="Y" ? "on":"off")."'/><img src='/resource/shop/basic/web/img/icon/iquick_wish_on.png' alt='' class='wish_on  ".($info["wish_yn"]!="Y" ? "on":"off")."' />
	                    </a>
	                    <a href='/goods/detail/".$info["gd_idx"]."' class='btn blank' title='새창보기' target='_blank'>
	                    	<img src='/resource/shop/basic/web/img/icon/iquick_blank.png' alt='' />
	                    </a>
	                </div>
	                $sold_out
				</div>
	            <!-- 상품정보 -->
	            <div class='info'>
	                <div class='name_box'>
	                    <div class='item_name'>".mb_substr($info["gd_list_name"]!="" ? $info["gd_list_name"] : $info["gd_name"],0,30,"UTF-8")."</div>
	                    <!--div class='sub_name ellipsis'>".$info["gd_sub_name"]."</div-->
	                </div>
	";
	if($info["show_yn"]=="N"){
		$html .= "
                <div class='price'>
					<div class='after'><span class='won'>[비매품]</span></div>
                </div>
		";
	}else if($is_login==true || ($guest_yn == "Y")){
		$html .= "
		                <div class='price'>
							<div class='after'><span class='won'>".$sale_price."</span>원</div>
		                </div>
		";
	}else{
		$html .= "
		                <!--div class='price'>
							<div class='after'><span class='won'>로그인후 확인가능</div>
		                </div-->
		";
	}
	$html .= "
	            </div>

	            $coupon_html
	        </div>
	    </li>
	";

	return $html;
}


# 상품 HTML type1
function showGoodsBest($info,$num,$margin_type,$sale_per,$is_login,$guest_yn){
	$CI = &get_instance();
	$sale_price = number_format(getOriginPrice($margin_type,$sale_per,$info["gd_p_price"],$info["gd_s_price"],$info["gd_i_price"]));
	$coupon_html = "";
	$coupon_name = "";
	$coupon_price = 0;
	$coupon_type = "원";

	if($info["gc_won"]!=null && $info["gc_name"]!="" && $info["gc_won"] > 0){
		$coupon_name = "할인";
		$coupon_price = $info["gc_won"];
		if($info["gc_won_type"]=="WON"){
			$coupon_type = "원";
			$coupon_price = number_format($coupon_price);
		}else{
			$coupon_type = "%";
		}
	}

	if($coupon_name!=""){
		$coupon_html = "
            <div class='coupon'>
				<div class='name'>".$coupon_name."</span><span class='won'>".$coupon_price . "" . $coupon_type . "</span></div>
            </div>
		";
	}

	$html = "
	    <li>
	        <div class='goods_box_1'>

	            <div class='thumb thumbnail-wrappper'>
					<div class='thumbnail'>
						<a class='inner' href='/goods/detail/".$info["gd_idx"]."'><img src='" . getGoodsListImg($info) ."' /></a>
					</div>
					<!-- 퀵메뉴 -->
	                <div class='item_quick'>
	                    <a class='btn cart btnCart' data-gd_idx='".$info["gd_idx"]."' title='장바구니 담기'>
	                    	<img src='/resource/shop/basic/web/img/icon/iquick_cart.png' alt='' /></a>
	                    <a href='javascript:void(0);' class='btn wish js_wish btnWish' data-gd_idx='".$info["gd_idx"]."' title='찜하기'>
	                    	<img src='/resource/shop/basic/web/img/icon/icon_iquick_wish.png' alt='' class='off'/><img src='/resource/shop/basic/web/img/icon/icon_iquick_wish_on.png' alt='' class='on' />
	                    </a>
	                    <a href='/goods/detail/".$info["gd_idx"]."' class='btn blank' title='새창보기' target='_blank'>
	                    	<img src='/resource/shop/basic/web/img/icon/iquick_blank.png' alt='' />
	                    </a>
	                </div>
				</div>
	            <!-- 상품정보 -->
	            <div class='info'>
	                <div class='name_box'>
	                    <div class='item_name'>".($info["gd_list_name"]!="" ? $info["gd_list_name"] : $info["gd_name"])."</div>
	                    <div class='sub_name ellipsis'>".$info["gd_sub_name"]."</div>
	                </div>
		";

		if($is_login==true || ($guest_yn == "Y")){
			$html .= "
			                <div class='price'>
								<div class='after'><span class='won'>".$sale_price."</span>원</div>
			                </div>
			";
		}else{
			$html .= "
			                <!--div class='price'>
								<div class='after'><span class='won'>로그인후 확인가능</div>
			                </div-->
			";
		}

	    $html .= "
	            </div>
	            <span class='best_num'>$num</span>
	            $coupon_html
	        </div>
	    </li>
	";
	return $html;
}

#위시리스트
function showGoodsWish($info,$margin_type,$sale_per,$is_login,$guest_yn){
	$CI = &get_instance();
	$sale_price = number_format(getOriginPrice($margin_type,$sale_per,$info["gd_p_price"],$info["gd_s_price"],$info["gd_i_price"]));
	$coupon_html = "";
	$coupon_name = "";
	$coupon_price = 0;
	$coupon_type = "원";

	if($info["gc_won"]!=null && $info["gc_name"]!="" && $info["gc_won"] > 0){
		$coupon_name = "할인";
		$coupon_price = $info["gc_won"];
		if($info["gc_won_type"]=="WON"){
			$coupon_type = "원";
			$coupon_price = number_format($coupon_price);
		}else{
			$coupon_type = "%";
		}
	}

	if($coupon_name!=""){
		$coupon_html = "
            <div class='coupon'>
				<div class='name'>".$coupon_name."</span><span class='won'>".$coupon_price . "" . $coupon_type . "</span></div>
            </div>
		";
	}

	$html = "
		<li>
		    <div class='wish_box'>
		        <div class='wish_select'>
		            <label><input type='checkbox' name='chk[]' class='check _chk_class' value='" . $info["gd_idx"] . "' /></label>
		            <a href='javascript:void(0);' class='c_btn h22 black line btnDelete' data-gd_idx='" . $info["gd_idx"] . "'>찜삭제</a>
		        </div>
		        <div class='item thumbnail-wrappper'>
					<div class='thumbnail'>
						<a class='inner' href='/goods/detail/".$info["gd_idx"]."'><img src='" . getGoodsListImg($info) ."' /></a>
					</div>

		            <div class='info'>
		                <div class='item_name'>".($info["gd_list_name"]!="" ? $info["gd_list_name"] : $info["gd_name"])."</div>
	";
	if($is_login==true || ($guest_yn == "Y")){
		$html .= "
		                <div class='price'>
							<div class='after'><span class='won'>".$sale_price."</span>원</div>
		                </div>
		";
	}else{
		$html .= "
		                <!--div class='price'>
							<div class='after'><span class='won'>로그인후 확인가능</div>
		                </div-->
		";
	}

	$html .= "
		            </div>
		        </div>
		        $coupon_html
		    </div>
		</li>
	";
	return $html;
}

#위시리스트
function showGoodsWishMobile($info,$margin_type,$sale_per,$is_login,$guest_yn){
	$CI = &get_instance();
	$sale_price = number_format(getOriginPrice($margin_type,$sale_per,$info["gd_p_price"],$info["gd_s_price"],$info["gd_i_price"]));
	$coupon_html = "";
	$coupon_name = "";
	$coupon_price = 0;
	$coupon_type = "원";

	if($info["gc_won"]!=null && $info["gc_name"]!="" && $info["gc_won"] > 0){
		$coupon_name = "할인";
		$coupon_price = $info["gc_won"];
		if($info["gc_won_type"]=="WON"){
			$coupon_type = "원";
			$coupon_price = number_format($coupon_price);
		}else{
			$coupon_type = "%";
		}
	}

	if($coupon_name!=""){
		$coupon_html = "
            <div class='coupon'>
				<div class='name'>".$coupon_name."</span><span class='won'>".$coupon_price . "" . $coupon_type . "</span></div>
            </div>
		";
	}

	$html = "
		<li>
		    <div class='wish_box'>
		        <div class='item'>
		            <label class='check'><input type='checkbox' name='chk[]' class='_chk_class' value='" . $info["gd_idx"] . "'></label>
		            <a href='/goods/detail/" . $info["gd_idx"] . "' class='upper_link' title=''></a>

		            <div class='thumb'>
		                <div class='fake_img'>
		                    <a href='/goods/detail/" . $info["gd_idx"] . "'  title='상세보기'>
		                        <img src='" . getGoodsListImg($info) ."' />
		                    </a>
		                </div>
		                <div class='real_img'>
		                        <a href='/goods/detail/" . $info["gd_idx"] . "'  title='상세보기'>
		                        <img src='" . getGoodsListImg($info) ."' />
		                    </a>
		                </div>
		            </div>
		            <!-- 상품정보 -->
		            <div class='info'>
		                <div class='item_name'>".($info["gd_list_name"]!="" ? $info["gd_list_name"] : $info["gd_name"])."</div>
		";
	if($is_login==true || ($guest_yn == "Y")){
		$html .= "
		                <div class='price'>
							<div class='after'><span class='won'>".$sale_price."</span>원</div>
		                </div>
		";
	}else{
		$html .= "
		                <!--div class='price'>
							<div class='after'><span class='won'>로그인후 확인가능</div>
		                </div-->
		";
	}

	$html .= "
		            </div>
		        </div>
		    </div>
		</li>
	";
	return $html;
}

#위시리스트
function showGoodsOften($info,$margin_type,$sale_per,$is_login,$guest_yn){
	$CI = &get_instance();
	$sale_price = number_format(getOriginPrice($margin_type,$sale_per,$info["gd_p_price"],$info["gd_s_price"],$info["gd_i_price"]));
	$coupon_html = "";
	$coupon_name = "";
	$coupon_price = 0;
	$coupon_type = "원";

	if($info["gc_won"]!=null && $info["gc_name"]!="" && $info["gc_won"] > 0){
		$coupon_name = "할인";
		$coupon_price = $info["gc_won"];
		if($info["gc_won_type"]=="WON"){
			$coupon_type = "원";
			$coupon_price = number_format($coupon_price);
		}else{
			$coupon_type = "%";
		}
	}

	if($coupon_name!=""){
		$coupon_html = "
            <div class='coupon'>
				<div class='name'>".$coupon_name."</span><span class='won'>".$coupon_price . "" . $coupon_type . "</span></div>
            </div>
		";
	}

	$html = "
		<li>
		    <div class='wish_box'>

		        <div class='item thumbnail-wrappper'>
					<div class='thumbnail'>
						<a class='inner' href='/goods/detail/".$info["gd_idx"]."'><img src='" . getGoodsListImg($info) ."' /></a>
					</div>

		            <div class='info'>
		                <div class='item_name'>".($info["gd_list_name"]!="" ? $info["gd_list_name"] : $info["gd_name"])."</div>
	";
	if($is_login==true || ($guest_yn == "Y")){
		$html .= "
		                <div class='price'>
							<div class='after'><span class='won'>".$sale_price."</span>원</div>
		                </div>
		";
	}else{
		$html .= "
		                <!--div class='price'>
							<div class='after'><span class='won'>로그인후 확인가능</div>
		                </div-->
		";
	}

	$html .= "
		            </div>
		        </div>
		    </div>
		</li>
	";
	return $html;
}


# 상품 HTML type1
function showGoodsTypeM($info,$margin_type,$sale_per,$is_login,$guest_yn){
	$CI = &get_instance();

	$sale_price = number_format(getOriginPrice($margin_type,$sale_per,$info["gd_p_price"],$info["gd_s_price"],$info["gd_i_price"]));
	$coupon_html = "";
	$coupon_name = "";
	$coupon_price = 0;
	$coupon_type = "원";

	if($info["gc_won"]!=null && $info["gc_name"]!="" && $info["gc_won"] > 0){
		$coupon_name = "할인";
		$coupon_price = $info["gc_won"];
		if($info["gc_won_type"]=="WON"){
			$coupon_type = "원";
			$coupon_price = number_format($coupon_price);
		}else{
			$coupon_type = "%";
		}
	}

	if($coupon_name!=""){
		$coupon_html = "
            <div class='coupon'>
				<div class='name'>".$coupon_name."</span><span class='won'>".$coupon_price . "" . $coupon_type . "</span></div>
            </div>
		";
	}

	$sold_out = "";
	if($info["sold_out"]=="Y"){
		$sold_out = "
	        <div class='soldout'>
				<div class='icon'>품절</div>
	        </div>
		";
	}

	$html = "
		<li>
	        <div class='goods_box_1'>
	        	<div class='thumbnail-wrappper'>
		            <div class='thumbnail'>
		                <a class='inner' href='/goods/detail/".$info["gd_idx"]."'><img src='" . getGoodsListImg($info) ."' onerror='this.style.display = \"none\"'  /></a>
		                $sold_out
		            </div>

	            </div>
	            <!-- 상품정보 -->
	            <div class='info'>
	                <div class='name_box'>
	                    <div class='item_name'>".($info["gd_list_name"]!="" ? $info["gd_list_name"] : $info["gd_name"])."</div>
	                    <div class='sub_name ellipsis'>".$info["gd_sub_name"]."</div>
	                </div>
	";

	if($is_login==true || ($guest_yn == "Y")){
		$html .= "
		                <div class='price'>
							<div class='after'><span class='won'>".$sale_price."</span>원</div>
		                </div>
		";
	}else{
		$html .= "
		                <!--div class='price'>
							<div class='after'><span class='won'>로그인후 확인가능</div>
		                </div-->
		";
	}

	$html .= "
	            </div>
	            $coupon_html

	        </div>
	    </li>
	";
	return $html;
}
