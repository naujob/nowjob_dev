
<div id="popChatUser" class="no-overlay">
	<div class="popup-cont">
		<div class="popup">
			<div class="pop-cont">
				<h5 class="pop-title">채팅 친구 선택(<span class="pop_user_count">0</span>)</h5>
				<div class="pop-list user_cont" id="pop_user_body"></div>
			</div>
			<div class="pop-btn">
				<a class="btn-chat" href="javascript:void(0)">채팅하기</a>
			</div>
			<a class="pop-close" href="javascript:void(0);"><i class="ri-close-line"></i></a>
		</div>
	</div>
</div>

<div id="popChatChannel" class="no-overlay">
	<div class="popup-cont">
		<div class="popup">
			<div class="pop-cont">
				<h5 class="pop-title">채널생성(오픈채팅)</h5>
				<div class="channel_cont">
					<form id="img_frm" class="" method="post">
					<div class="input">
						<div class="title">채널명</div>
						<div class="cont"><input type="text" name="cn_name" maxlength="20" placeholder="채널명 2자이상" /></div>
					</div>
					<div class="input">
						<div class="title">채널설명</div>
						<div class="cont"><input type="text" name="cn_desc" maxlength="50" placeholder="채널명 0-50자 사이" /></div>
					</div>
					<div class="input">
						<div class="title">채널 이미지</div>
						<div class="prev_img">
							<img id="out_img" src="/chatting/img/channel/ex1.svg"   />
						</div>
						<div class="desc_img">
							<div class="img active"> 
								<img src="/chatting/img/channel/ex1.svg" />
							</div>
							<div class="img">
								<img src="/chatting/img/channel/ex2.svg" />
							</div>
							<div class="img">
								<img src="/chatting/img/channel/ex3.svg" />
							</div>
							<div class="img">
								<img src="/chatting/img/channel/ex4.svg" />
							</div>
							<div class="img">
								<img src="/chatting/img/channel/ex5.svg" />
							</div>
							<div class="img">
								<img src="/chatting/img/channel/ex6.svg" />
							</div>
							<div class="img">
								<img src="/chatting/img/channel/ex7.svg" />
							</div>
							<div class="img">
								<img src="/chatting/img/channel/ex8.svg" />
							</div>
							<div class="img">
								<img src="/chatting/img/channel/ex9.svg" />
							</div>
						</div>
						<div class="choose_img">
							<label for="cn_img" class="input-file-button">
							 이미지 업로드
							</label>
							<input type="file" name="cn_img" id="cn_img" onchange="preview_image(event,'out_img')"  accept="image/gif, image/jpeg, image/png" style="display:none;" />
						</div>
					</div>
					<input type="hidden" name="file_name" value="cn_img" />
					</form>
				</div>
			</div>
			<div class="pop-btn">
				<a class="btn-channel" href="javascript:void(0)">채널 생성하기</a>
			</div>
			<a class="pop-close" href="javascript:void(0);"><i class="ri-close-line"></i></a>
		</div>
	</div>
</div>


<!-- 공통 리턴 모듈 -->
<script>
	//방생성 확인 및 방번호 확인
    socket.on("room_insert_return", function(ret) {
    	if(ret.code=="00"){
    		location.href="/chatting/room.php?rm_idx=" + ret.data;	
    	}else{
    		alert(ret.msg);
    	}
    });
</script>
<script>
	/*
	tippy('.tooltip_search', {
		content: '검색',
	});
	*/
</script>
<script>
	var arr_user_target = [];
	//유저 추가
	$("#btnChatUser").click(function(){
		$("#popChatUser").show();
		
		//회원리스트 가져오기
		socket.emit("pop_user_list",{user_id : g_user_info.user_id});
		
		//회원리스트 가져오기
	    socket.on("pop_user_list_return", function(ret) {
	    	$("#pop_user_body").html("");
	       	if(ret!=null){
	       		for(var icnt=0;icnt < ret.length;icnt++){
	       			var html = "";
	       			html += "<div class='msg-item pop-item' data-target_id='"+ret[icnt].user_id+"' data-user_nick='"+ret[icnt].user_nick+"'>";
	       			html += "    <div class='check'><i class=\"checkbox ri-checkbox-blank-circle-line\"></i></div>";
	       			html += "    <div class='icon'>";
	       			html += "        <img src='"+ret[icnt].user_img+"' />";
	       			html += "    </div>";
	       			html += "    <div class='name'>"+ret[icnt].user_nick+"</div>";
	       			html += "    <div class='desc'>"+ret[icnt].user_desc+"</div>";
	       			html += "</div>";
	
	       			//적용
	       			$("#pop_user_body").append(html);
	       		}
	       	}
	    });
	});
	
	//유저 선택
	$(document).on("click",".pop-item",function(){
		var target_id = $(this).attr("data-target_id");
		var user_nick = $(this).attr("data-user_nick");
		if($(this).hasClass("checked")){
			$(this).removeClass("checked");
			$(this).find(".checkbox").removeClass("ri-checkbox-circle-line").addClass("ri-checkbox-blank-circle-line");
			
			for(var icnt=0;icnt < arr_user_target.length;icnt++){
				if(target_id==arr_user_target[icnt].user_id){
					arr_user_target.splice(icnt,1);
				}
			}
		}else{
			$(this).addClass("checked");
			$(this).find(".checkbox").removeClass("ri-checkbox-blank-circle-line").addClass("ri-checkbox-circle-line");
			
			arr_user_target.push({user_id : target_id,user_nick : user_nick});
		}
	});
	
	$(".btn-chat").click(function(){
		var chat_suer = [];
		var chat_subject = "";
		chat_suer.push({user_id : g_user_info.user_id});
		
		for(var icnt=0;icnt < arr_user_target.length;icnt++){
			chat_suer.push({user_id:arr_user_target[icnt].user_id});
		}
		
		if(arr_user_target.length==1){
			chat_subject = arr_user_target[0].user_nick;
		}else{
			chat_subject = "그룹채팅";
		}
		
    	var room_info = {
    		user_id : g_user_info.user_id,
    		user_list : chat_suer, 
    		rm_type : "chat", 
    		rm_img : "",
    		rm_subject : chat_subject,
    		rm_desc : ""
    	};
    	
    	socket.emit("room_insert",room_info);
	});
	
	var sel_url = "/chatting/img/channel/ex1.svg";
	$(".btn-channel").click(function(){
		if($("[name=cn_name]").val()==""){
			alert("채널명을 입력해주세요.");
			$("[name=cn_name]").focus();
			return;
		}
		
		if($("[name=cn_name]").val().length < 2){
			alert("채널명은 2자 이상 입력해주세요.");
			$("[name=cn_name]").focus();
			return;
		}
		
		var chat_suer = [];
		chat_suer.push({user_id : g_user_info.user_id});
		
		if($("[name=cn_img]").val()!=""){
			var form = $('#img_frm'); 
			var params = new FormData(form[0]);
			fileAjax("/chatting/image_upload.php",params,function(result){
				if(result.code == "00"){
					//데이터 전송처리
					var room_info = {
			    		user_id : g_user_info.user_id,
			    		user_list : chat_suer, 
			    		rm_type : "channel",
			    		rm_img : result.url,  
			    		rm_subject : $("[name=cn_name]").val(),
			    		rm_desc : $("[name=cn_desc]").val()
			    	};
			    	
			    	socket.emit("room_insert",room_info);
					
				}else{
					alert(result.msg);
				}
			});
		}else{
			//데이터 전송처리
			var room_info = {
	    		user_id : g_user_info.user_id,
	    		user_list : chat_suer, 
	    		rm_type : "channel",
	    		rm_img : sel_url,  
	    		rm_subject : $("[name=cn_name]").val(),
	    		rm_desc : $("[name=cn_desc]").val()
	    	};
	    	
	    	socket.emit("room_insert",room_info);
		}
	});
	
	$("#cn_img").change(function(){
		var cw = $('.prev_img').width();
		$('.prev_img').css({'line-height':(cw)+'px'});
	})
	
	$(".desc_img img").click(function(){
		var src = $(this).attr("src");
		$("#out_img").attr("src",src);
		$(".desc_img .img").removeClass("active");
		$(this).closest(".img").addClass("active");
		sel_url = src;
	});
	
	$("#btnAddChannel").click(function(){
		//초기화
		$("[name=cn_name]").val("");
		$("[name=cn_img]").val("");
		sel_url = "/chatting/img/channel/ex1.svg";
		$("#out_img").attr("src",sel_url);
		$(".desc_img .img").removeClass("active");
		$(".desc_img .img").eq(0).addClass("active");
		
		//팝업 오픈
		$("#popChatChannel").show();
	});
	
	//팝업 닫기
	$(".pop-close").click(function(){
		$(".no-overlay").hide();
	});
</script>
</body>
</html>
