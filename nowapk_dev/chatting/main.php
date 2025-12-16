<?php include("./layout_header.php")?>
<header id="header">
   	<div class="title">친구 리스트</div>
   	
	<div class="icons">
		<div class="icon">
			<a id="btnSearch" href="javascript:void(0);" class="click tooltip_search">
				<i class="ri-search-line"></i>
			</a>
		</div>
		<div class="icon">
			<a id="btnAddChannel" href="javascript:void(0);" class="click tooltip_channel">
				<i class="ri-hashtag"></i>
			</a>
		</div>
		<div class="icon">
			<a id="btnChatUser" href="javascript:void(0);" class="click tooltip_add">
				<i class="ri-add-box-line"></i>
			</a>
		</div>
	</div>
</header>

<div id="wrap_main">
	<div class="user-wrap">
	    <div class="user-list">
	    	<div class="my-info">
	           <img id="user_img" class="icon" src="/chatting/img/icon.png" />
	           <div class="name"><span id="user_nick"></span> 님</div>
	           <div class="desc"><span id="user_desc"></span></div>
	        </div>
		    <span class="title user_count"></span>
		    <div id="user_body"></div>
	    </div>
	</div>
</div>
<div id="main_bottom">
    <div class="icons">
        <div class="icon">
            <a href="/chatting/main.php" class="tooltip_user active">
                <i class="ri-user-line"></i>
            </a>
        </div>
        <div class="icon">
            <a href="/chatting/room_list.php" class="tooltip_talk click">
                <i class="ri-chat-3-line"></i>
            </a>
        </div>
         <div class="icon">
            <a href="/chatting/channel_list.php" class="tooltip_channel_list click">
                <i class="ri-hashtag"></i>
            </a>
        </div>
         <div class="icon">
            <a href="javascript:alert('준비중입니다.');" class="tooltip_exp click">
                <i class="ri-flask-line"></i>
            </a>
        </div>
         <div class="icon">
            <a href="javascript:alert('준비중입니다.');" class="tooltip_exp click">
                <i class="ri-more-fill"></i>
            </a>
        </div>
    </div>
</div>
<script>
	
	//로그인 확인후 처리
	if(loginCheck()==false){
		alert("로그인후 이용해주세요.");
		$("body").html("");
		location.href="/chatting/start.php";
	}else{
		
		$("#user_nick").text(g_user_info.user_nick);
		$("#user_img").attr("src",g_user_info.user_img);
		$("#user_desc").text(g_user_info.user_desc);

		//회원리스트 가져오기
		socket.emit("user_list",{user_id : g_user_info.user_id});

	    //더블클릭 이벤트 처리
	    $(document).on("click",".user-chatting",function(){
	    	var target_id = $(this).data("target_id");
	    	var user_nick = $(this).data("user_nick");
	    	
	    	var chat_suer = [];
			chat_suer.push({user_id : g_user_info.user_id});
			chat_suer.push({user_id : target_id});
	    	
	    	var room_info = {
	    		user_id : g_user_info.user_id,
	    		user_list : chat_suer, 
	    		rm_type : "chat", 
	    		rm_img : "",
	    		rm_subject : user_nick,
	    		rm_desc : "",
	    	};
	    	
	    	socket.emit("room_insert",room_info);
	    });
	}
	
	/////////////////////////////////////////////////////////
	// 리턴 처리
	/////////////////////////////////////////////////////////

	//회원리스트 가져오기
    socket.on("user_list_return", function(ret) {
    	$("#user_body").html("");
       	if(ret!=null){
       		$(".user_count").html("친구 " + ret.length + "명");
       		for(var icnt=0;icnt < ret.length;icnt++){
       			var html = "";
       			html += "<div class='msg-item user-chatting' data-target_id='"+ret[icnt].user_id+"' data-user_nick='"+ret[icnt].user_nick+"'>";
       			html += "    <div class='icon'>";
       			html += "        <img src='"+ret[icnt].user_img+"' />";
       			html += "    </div>";
       			html += "    <div class='name'>"+ret[icnt].user_nick+"</div>";
       			html += "    <div class='desc'>"+ret[icnt].user_desc+"</div>";
       			html += "</div>";

       			//적용
       			$("#user_body").append(html);
       		}
       	}
    });
    
	//신규회원 추가시 다시 조회
    socket.on("user_insert_alarm", function(ret) {
    	socket.emit("user_list",{user_id : g_user_info.user_id});
    });

    //신규회원 추가시 다시 조회
    socket.on("user_login_alarm", function(ret) {
    	socket.emit("user_list",{user_id : g_user_info.user_id});
    });
</script>
<?php include("./layout_footer.php")?>
