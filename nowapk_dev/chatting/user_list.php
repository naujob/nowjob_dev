<?php include("./layout_header.php")?>
<header id="header">
    <div class="top">
        <div class="user_info">
           <div class="icon"><img id="user_img"  src="/bot/img/icon.png" /></div>
           <div class="name"><span id="user_nick"></span> 님</div>
           <div class="msg"><span id="user_desc"></span></div>
        </div>
         <div class="icons">
            <div class="quick">
                <a href="javascript:alert('준비중입니다.');" class="click">
                     <i class="ri-menu-line"></i>
                </a>
            </div>
        </div>
    </div>
</header>
<div id="wrap_list">
	<div class="user-wrap">
	    <div class="user-list">
	    	<div class="user-tabs">
		        <a href="" class="on">회원리스트</a>
				<a href="" class="">채팅리스트</a>
		    </div>
		    <span class="title user_count"></span>
		    <div id="user_body">

		    </div>
	    </div>
	</div>
</div>

<script>
	//로그인 확인후 처리
	if(loginCheck()==false){
		alert("로그인후 이용해주세요.");
		$("body").html("");
		location.href="/bot/start.php";
	}else{
		$("#user_nick").text(g_user_info.user_nick);
		$("#user_img").attr("src",g_user_info.user_img);
		$("#user_desc").text(g_user_info.user_desc);

		//회원리스트 가져오기
		socket.emit("user_list",{user_id : g_user_info.user_id});

		//호원리스트 가져오기
	    socket.on("user_list_return", function(ret) {
	    	$("#user_body").html("");
	       	if(ret!=null){
	       		$(".user_count").html(ret.length + "명");
	       		for(var icnt=0;icnt < ret.length;icnt++){
	       			var html = "";
	       			html += "<div class='msg-item user-chatting' data-target_id='"+ret[icnt].user_id+"'>";
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
	    //더블클릭 이벤트 처리
	    $(document).on("click",".user-chatting",function(){
	    	var target_id = $(this).data("target_id");
	    	socket.emit("room_insert",{user_id : g_user_info.user_id,target_id : target_id});
	    });
	}

	/////////////////////////////////////////////////////////
	// 리턴 처리
	/////////////////////////////////////////////////////////

	//신규회원 추가시 다시 조회
    socket.on("user_insert_alarm", function(ret) {
    	socket.emit("user_list",{user_id : g_user_info.user_id});
    });

    //신규회원 추가시 다시 조회
    socket.on("user_login_alarm", function(ret) {
    	socket.emit("user_list",{user_id : g_user_info.user_id});
    });

    //방생성 확인 및 방번호 확인
    socket.on("room_insert_return", function(ret) {
    	location.href="/bot/room.php?rm_idx=" + ret.data;
    });
</script>
<?php include("./layout_footer.php")?>
