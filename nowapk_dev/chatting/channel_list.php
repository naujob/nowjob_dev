<?php include("./layout_header.php")?>
<header id="header">
   	<div class="title">#공개 채널</div>
	<div class="icons">
		<div class="icon">
			<a id="btnSearch" href="javascript:void(0);" class="click">
				<i class="ri-search-line"></i>
			</a>
		</div>
		<div class="icon">
			<a id="btnAddChannel" href="javascript:void(0);" class="click">
				<i class="ri-hashtag"></i>
			</a>
		</div>
		<div class="icon">
			<a id="btnChatUser" href="javascript:void(0);" class="click">
				<i class="ri-add-box-line"></i>
			</a>
		</div>
	</div>
</header>
<div id="wrap_main">
	<div class="user-wrap">
	    <div class="user-list">
	    	<!--div class="adv_cont">
	    		광고 영역<br/>
	    		광고 영역
	    	</div-->
		    <div id="room_body"></div>
	    </div>
	</div>
</div>
<div id="main_bottom">
    <div class="icons">
       <div class="icon">
            <a href="/chatting/main.php" class="click">
                <i class="ri-user-line"></i>
            </a>
        </div>
        <div class="icon">
            <a href="/chatting/room_list.php" class="click">
                <i class="ri-chat-3-line"></i>
            </a>
        </div>
         <div class="icon">
            <a href="/chatting/channel_list.php" class="click active">
                <i class="ri-hashtag"></i>
            </a>
        </div>
         <div class="icon">
            <a href="javascript:alert('준비중입니다.');" class="click">
                <i class="ri-flask-line"></i>
            </a>
        </div>
         <div class="icon">
            <a href="javascript:alert('준비중입니다.');" class="click">
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
		//룸정보 가져오기
		socket.emit("room_list",{rm_type : "channel"});

	    //더블클릭 이벤트 처리
	    $(document).on("click",".room-item",function(){
	    	var target_id = $(this).data("target_id");
	    	location.href="/chatting/room.php?rm_idx=" + target_id;
	    });
	}
	
	/////////////////////////////////////////////////////////
	// 리턴 처리
	/////////////////////////////////////////////////////////

	//회원리스트 가져오기
    socket.on("room_list_return", function(ret) {
    	$("#room_body").html("");
       	if(ret!=null){
       		for(var icnt=0;icnt < ret.length;icnt++){
       			var img_html = getRoomImg(ret[icnt]);
       			var subject = getRoomSubject(ret[icnt]);
       			var desc = getRoomDesc(ret[icnt],"channel");
       			var html = "";
       			html += "<div class='msg-item room-item' data-target_id='"+ret[icnt].rm_idx+"'>";
       			html += "    <div class='icon'>";
       			html += img_html;
       			html += "    </div>";
       			html += "    <div class='name'>"+subject+"</div>";
       			html += "    <div class='desc'>"+desc+ "</div>";
       			html += "    <div class='time'>"+getFormatDate(new Date(ret[icnt].rm_last_dt),'chat') + "</div>";
       			html += "</div>";

       			//적용
       			$("#room_body").append(html);
       		}
       	}
    });
    
    
</script>
<?php include("./layout_footer.php")?>
