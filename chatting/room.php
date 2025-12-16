<?php include("./layout_header.php")?>
<?php
	$rm_idx = $_GET["rm_idx"];
?>
<!-- wrap -->
<header id="header">
	<a href="javascript:history.go(-1);">
		<i class="arrow ri-arrow-left-line"></i>
	</a>
   	<div class="title chat" id="rm_subject"></div>
	<div class="icons">
		<div class="icon">
			<a href="javascript:alert('준비중입니다.');" class="click">
				<i class="ri-search-line"></i>
			</a>
		</div>
		<div class="icon">
			<a href="javascript:alert('준비중입니다.');" class="click">
				<i class="ri-hashtag"></i>
			</a>
		</div>

	</div>
</header>
<div id="wrap">
    <div id="chat_body">
        <!--div class="msg-left">
            <div class="icon">
                <img src="/bot/img/bot2.png" />
            </div>
            <div class="name">식봇</div>
            <div class="msg_cont">
                <div class="msg">
                    식봇은 처음이시죠? 안녕하세요. 저희는 식자재를 고객에게 편리하게 재공해주는
                    식봇입니다. 고객님의 <strong style="color:#f22;">'성함'</strong>을 알려주시겠어요?
                </div>
           </div>
            <div class="regdt">12:40:11</div>
        </div>
        <div class="msg-right">
            <div class="msg_cont">
                <div class="msg">
                    박현종
                </div>
            </div>
            <div class="regdt">12:41:11</div>
        </div>
        <div class="msg-left">
            <div class="icon">
                <img src="/bot/img/bot2.png" />
            </div>
            <div class="name">식봇</div>
            <div class="msg_cont">
                <div class="msg">
                    <strong style="color:#333;">'박현종'</strong>님 식봇에 오신걸 환영합니다.
                </div>
           </div>
            <div class="regdt">12:40:11</div>
        </div>
        <div class="msg-left">
            <div class="icon">
                <img src="/bot/img/bot2.png" />
            </div>
            <div class="name">식봇</div>
            <div class="msg_cont">
                <div class="msg">
                    식당 사장님 이신가요?
                      <a href="javascript:void(0);" style="margin-top:10px;background:rgb(250,227,76);color:#444;border-radius:5px;padding:10px 20px;width:100%;display:inline-block;text-align:center;">네</a>
                      <a href="javascript:void(0);" style="margin-top:10px;background:#eee;color:#444;border-radius:5px;padding:10px 20px;width:100%;display:inline-block;text-align:center;">아니오</a>
                </div>
           </div>
            <div class="regdt">12:40:11</div>
        </div>
        <div class="msg-right">
            <div class="msg_cont">
                <div class="msg">

                </div>
            </div>
            <div class="regdt">12:41:11</div>
        </div>
        <div class="msg-left">
            <div class="icon">
                <img src="/bot/img/bot2.png" />
            </div>
            <div class="name">식봇</div>
            <div class="msg_cont">
                <div class="msg">
                    식당 위치가 어디신가? 주소를 알려주시면 가까운 식재자 마트를 추천해 드리겠습니다.<br/>
                    <a href="javascript:void(0);" style="margin-top:10px;background:rgb(250,227,76);color:#444;border-radius:5px;padding:10px 20px;width:100%;display:inline-block;text-align:center;">주소 선택</a>
                </div>
           </div>
            <div class="regdt">12:40:11</div>
        </div>
        <div class="msg-right">
            <div class="msg_cont">
                <div class="msg">
                    경기도 성남시 수정구 태평1동 4992-1번지
                </div>
            </div>
            <div class="regdt">12:41:11</div>
        </div>
        <div class="msg-left">
            <div class="icon">
                <img src="/bot/img/bot2.png" />
            </div>
            <div class="name">식봇</div>
            <div class="msg_cont">
                <div class="msg">
                    사장님 식당 상호명을 알려주실수 있으실까요?
                    상호명으로 사장님에게 맞는 식자재를 추천해드리겠습니다.

                </div>
           </div>
            <div class="regdt">12:40:11</div>
        </div>
        <div class="msg-right">
            <div class="msg_cont">
                <div class="msg">
                    낙지보감
                </div>
            </div>
            <div class="regdt">12:41:11</div>
        </div>
        <div class="msg-left">
            <div class="icon">
                <img src="/bot/img/bot2.png" />
            </div>
            <div class="name">식봇</div>
            <div class="msg_cont">
                <div class="msg">
                    좋은정보 감사합니다. 식봇에 원하는 정보를 입력하시면 거기에 맞게 답변해드리겠습니다.
                </div>
           </div>
            <div class="regdt">12:40:11</div>
        </div>
        <div class="msg-right">
            <div class="msg_cont">
                <div class="msg">
                    감자 찾아줘
                </div>
            </div>
            <div class="regdt">12:41:11</div>
        </div>
         <div class="msg-left">
            <div class="icon">
                <img src="/bot/img/bot2.png" />
            </div>
            <div class="name">식봇</div>
            <div class="msg_cont">
                <div class="msg">
                    죄송합니다. 잘 못알아들였습니다.
                </div>
           </div>
            <div class="regdt">12:40:11</div>
        </div>
        <div class="msg-right">
            <div class="msg_cont">
                <div class="msg">
                    떡뽁이 오뎅 소스
                </div>
            </div>
            <div class="regdt">12:41:11</div>
        </div>
         <div class="msg-left">
            <div class="icon">
                <img src="/bot/img/bot2.png" />
            </div>
            <div class="name">식봇</div>
            <div class="msg_cont">
                <div class="msg">
                    죄송합니다. 잘 못알아들였습니다.
                </div>
           </div>
            <div class="regdt">12:40:11</div>
        </div>
        <div class="msg-right">
            <div class="msg_cont">
                <div class="msg">
                    낙지보감 소스 재료
                </div>
            </div>
            <div class="regdt">12:41:11</div>
        </div>
         <div class="msg-left">
            <div class="icon">
                <img src="/bot/img/bot2.png" />
            </div>
            <div class="name">식봇</div>
            <div class="msg_cont">
                <div class="msg">
                    죄송합니다. 잘 못알아들였습니다.
                </div>
           </div>
            <div class="regdt">12:40:11</div>
        </div>
        <div class="msg-right">
            <div class="msg_cont">
                <div class="msg">
                    감자
                </div>
            </div>
            <div class="regdt">12:41:11</div>
        </div>
        <div class="msg-left big">
            <div class="icon">
                <img src="/bot/img/bot2.png" />
            </div>
            <div class="name">식봇</div>
            <div class="msg_cont">
                <div class="msg">
                    식봇이 '감자' 를 검색했습니다.<br/>
                   <div class="prd-list">
                       <div class="prd">
                           <img src="https://content.foodspring.co.kr/vendor/560/images/36_1825939214_r.jpg" />
                           <div class="name">햇감자</div>
                           <div class="price"><span>7%</span><strong>45,000</strong></div>
                       </div>
                       <div class="prd">
                           <img src="https://content.foodspring.co.kr/vendor/560/images/36_1825939214_r.jpg" />
                           <div class="name">햇감자</div>
                           <div class="price"><span>7%</span><strong>45,000</strong></div>
                       </div>
                       <div class="prd">
                           <img src="https://content.foodspring.co.kr/vendor/560/images/36_1825939214_r.jpg" />
                           <div class="name">햇감자</div>
                           <div class="price"><span>7%</span><strong>45,000</strong></div>
                       </div>
                       <div class="prd">
                           <img src="https://content.foodspring.co.kr/vendor/560/images/36_1825939214_r.jpg" />
                           <div class="name">햇감자</div>
                           <div class="price"><span>7%</span><strong>45,000</strong></div>
                       </div>
                       <a href="javascript:void(0);" style="margin-top:10px;background:rgb(250,227,76);color:#444;border-radius:5px;padding:10px 20px;width:100%;display:inline-block;text-align:center;">더보기</a>
                   </div>
                </div>
           </div>
            <div class="regdt">12:40:11</div>
        </div>

        <div class="msg-left big">
            <div class="icon">
                <img src="/bot/img/bot2.png" />
            </div>
            <div class="name">식봇</div>
            <div class="msg_cont">
                <div class="msg">
                   고객님 정기적으로 계란을 구매하셨습니다. 오늘은 필요 없으신가요?<br/>
                   <div class="prd-list">
                       <div class="prd">
                           <img src="https://content.foodspring.co.kr/vendor/560/images/103_1543142951_r.png" />
                           <div class="name">계란 10개</div>
                           <div class="price"><span>7%</span><strong>45,000</strong></div>
                       </div>
                       <div class="prd">
                           <img src="https://content.foodspring.co.kr/vendor/560/images/97_2032817804_r.jpg" />
                           <div class="name">계란 10개</div>
                           <div class="price"><span>7%</span><strong>45,000</strong></div>
                       </div>
                       <div class="prd">
                           <img src="https://content.foodspring.co.kr/vendor/560/images/103_1543142951_r.png" />
                           <div class="name">계란 10개</div>
                           <div class="price"><span>7%</span><strong>45,000</strong></div>
                       </div>
                       <div class="prd">
                           <img src="https://content.foodspring.co.kr/vendor/560/images/97_2032817804_r.jpg" />
                           <div class="name">계란 10개</div>
                           <div class="price"><span>7%</span><strong>45,000</strong></div>
                       </div>
                       <a href="javascript:void(0);" style="margin-top:10px;background:rgb(250,227,76);color:#444;border-radius:5px;padding:10px 20px;width:100%;display:inline-block;text-align:center;">구매하로 가기</a>
                   </div>
                </div>
           </div>
            <div class="regdt">12:40:11</div>
        </div>

        <div class="msg-left big">
            <div class="icon">
                <img src="/bot/img/bot2.png" />
            </div>
            <div class="name">식봇</div>
            <div class="msg_cont">
                <div class="msg">
                   고객님이 확인하지 못하신 기획전입니다. 요런 기획전은 어떠신가요?<br/>
                   <div class="plan-list">
                       <div class="prd">
                           <img src="https://content.foodspring.co.kr/admin/images/66_259157901166_2591579011.png" />

                       </div>
                       <div class="prd">
                           <img src="https://content.foodspring.co.kr/admin/images/64_886952745464_8869527454.jpg" />

                       </div>
                       <div class="prd">
                           <img src="https://content.foodspring.co.kr/admin/images/79_338982045279_3389820452.png" />

                       </div>
                       <div class="prd">
                           <img src="https://content.foodspring.co.kr/admin/images/74_199724888474_1997248884.png" />
                       </div>
                       <a href="javascript:void(0);" style="margin-top:10px;background:rgb(250,227,76);color:#444;border-radius:5px;padding:10px 20px;width:100%;display:inline-block;text-align:center;">구매하로 가기</a>
                   </div>
                </div>
           </div>
            <div class="regdt">12:40:11</div>
        </div-->
    </div>

</div>
<div id="room_bottom">
    <div class="send" id="text_cont">
        <textarea class="text" id="chatTxt"></textarea>
        <button class="btn off" id="btnSend"><i class="ri-send-plane-2-fill"></i></button>
    </div>

	<div class="icon_left">
		<a href="javascript:alert('준비중입니다.');" class="click">
			<i class="ri-add-box-line"></i>
		</a>
	</div>
	<div class="icon_left2">
		<a href="javascript:alert('준비중입니다.');" class="click">
			<i class="ri-emotion-laugh-line"></i>
		</a>
	</div>
</div>
<script>
	var last_send_dt = "";
	var rm_idx = "<?=$rm_idx?>";
	var rm_info = {};
	var rm_start = 0;
    var rm_length = 20;
    var rm_count = 0;

	if(rm_idx==null || rm_idx==""){
		alert("잘못된 접근입니다.");
		$("body").html("");
		location.href="/bot/start.php";
	}
</script>

<script>
	//로그인 확인후 처리
	if(loginCheck()==false){
		alert("로그인후 이용해주세요.");
		$("body").html("");
		location.href="/bot/start.php";
	}else{
		//룸정보 가져오기.
		socket.emit("room_join",{rm_idx : Number(rm_idx),con_user_id : g_user_info.user_id});
		socket.emit("room_info",{rm_idx : Number(rm_idx),con_user_id : g_user_info.user_id});

		//방생성 확인 및 방번호 확인
	    socket.on("room_info_return", function(ret) {
	    	if(ret.code !="00"){
	    		alert("데이터 조회시 문제가 발생하였습니다. 관리자에게 문의 해주세요.");
	    	}else{
	    		//채팅방 정보
	    		rm_info = ret.data;

	    		$("#rm_subject").html(getRoomSubject(rm_info));


	    		//메시지 리스트 조회
	    		var params = {
	    			rm_idx : Number(rm_idx),
	    			skip:rm_start,
	    			limit : rm_length
	    		};
	    		socket.emit("message_list",params);
	    	}
	    });

		var pagging_yn = false;
		$( "#wrap" ).scroll(function() {
			var scroll_top = $(this).scrollTop();
		  	if($(this).scrollTop() < 300 && pagging_yn==false){
				pagging_yn = true;

				//메시지 리스트 조회
	    		var params = {
	    			rm_idx : Number(rm_idx),
	    			skip:(++rm_start * rm_length),
	    			limit : rm_length
	    		};
	    		socket.emit("message_list",params);
			}
		});
		//텍스트 자동 사이즈스크롤 버텀
		//$("#chatTxt").keyup(function(key){
			//var str_arr = $(this).val().split("\n");  // 줄바꿈 기준으로 나눔
			//var row = str_arr.length;

			//4줄까지만 키워준다.
			//var last_height = (((row-1 > 4 ? 4 : row-1)) * 20) + 20;
			//$("#text_cont").css("height",last_height + "pt");
			//console.log("height::" + last_height);
		//});

	    $("#btnSend").click(function(){
	       	var txt =  $("#chatTxt").val();
	       	if(txt==""){
	       		return;
	       	}

			var message = {
				rm_idx : Number(rm_idx),
				user_id : g_user_info.user_id,
				mg_type : "text",
		    	mg_text : txt,
		    	mg_img : "",
		    	mg_file : "",
		    	mg_imoti : "",
				mg_dt : getCurrentDate(),
				mg_reads : new Array(),
	       	};

	       	//리드 카운터 초기화 한다.
			for(var icnt=0;icnt < rm_info.rm_users.length;icnt++){
				if(g_user_info.user_id==rm_info.rm_users[icnt].user_id){
					message.mg_reads.push({read_yn : "Y",user_id : rm_info.rm_users[icnt].user_id});
				}else{
					message.mg_reads.push({read_yn : "N",user_id : rm_info.rm_users[icnt].user_id});
				}
			}

	       	//데이터 가져오기
	       	var last_info = $(".msg-item").last();

	       	//타임라인 추가하기
	       	var add_timeline = false;
	       	if(last_info==null || last_info.length == 0){
	       		add_timeline = true;
	       	}else{
	       		var item_info = last_info.data("info");
	       		var user_id = item_info.split("^")[0];
	       		var mg_dt = item_info.split("^")[1];
	       		var mg_hhmm = item_info.split("^")[2];

	       		if(mg_dt!=getFormatDate(new Date(message.mg_dt),'ymd')){
	       			add_timeline = true;
	       		}
	       	}

	       	//타임라인 추가 : 로컬만
	       	if(add_timeline==true){
	       		var message_timeline = {
					rm_idx : Number(rm_idx),
					mg_type : "timeline",
					mg_dt : getCurrentDate()
		       	};
		       	//자기 자신 추가하기
				showMessage(message_timeline,false);
	       	}

			//자기 자신 추가하기
			showMessage(message,false);

	       	//채팅방 실시간 쏘기
			socket.emit("message_insert",message);

			//채팅창 클리어
			$("#chatTxt").val("").focus();

			//스크롤 이동처리
	       	scrollBottom();
	    });
	}

	/////////////////////////////////////////////////////////
	// 리턴 처리
	/////////////////////////////////////////////////////////

	//메시지 정보 리턴
    socket.on("message_insert_return", function(ret) {
		var message_list = ret.data;
    	for(var icnt=0;icnt < message_list.length;icnt++){
    		showMessage(message_list[icnt],false);
			if(message_list[icnt].mg_type =="text" || message_list[icnt].mg_type =="img" || message_list[icnt].mg_type =="file"){
				//메시지 리스트 조회
				var params = {
					rm_idx : Number(rm_idx),
					mg_idx : Number(message_list[icnt].mg_idx),
					user_id : g_user_info.user_id
				};
				socket.emit("message_read_return",params);
			}
    	}
    	scrollBottom();
    });

	//메시지 정보 리턴
    socket.on("message_read_count_return", function(ret) {
		//리턴받은 카운트 메시지 정보 업데이트
		if(ret.data.count==0){
			$("[id^=mg_idx_]").find(".readcount").html("");
		}else{

			$("[id^=mg_idx_]").find(".readcount").html(ret.data.count);
		}
    });

	//방생성 확인 및 방번호 확인
    socket.on("message_list_return", function(ret) {
    	//메시지 리스트 가져오기
    	var message_list = ret.data;
    	for(var icnt=0;icnt < message_list.length;icnt++){
    		showMessage(message_list[icnt].rm_messages,true);
    	}
		if(pagging_yn==false){
			scrollBottom();
		}
		pagging_yn = false;
    });

	//방 조인
    socket.on("room_join_return", function(ret) {
    	//alert("방에 접속하셨습니다.");
    });

	//접속 끊길때 방 정리
    socket.on("room_leave_alarm", function(ret) {
		alert("접속이 끊겼습니다.");
    	socket.emit("room_leave",{rm_idx : Number(rm_idx)});
    });

	//방해제 처리
	socket.on("room_leave_return", function(ret) {
    	alert("방에 접속하셨습니다.");
    });

    //메시지 출력 처리
    function showMessage(info,is_prepend){
    	//유형에 따른 처리
    	if(info.mg_type =="timeline"){
			var content = "<div class='timeline'>";
			content +="       <div class='center'>";
			content +="         " + getFormatDate(new Date(info.mg_dt),'kor');
			content +="      </div>";
			content +="</div>";
    	}else{
    		var your_text_yn = (info.user_id == g_user_info.user_id ? "Y" : "N");
			var item = info.user_id + "^" + getFormatDate(new Date(info.mg_dt),'ymd') + "^" + getFormatDate(new Date(info.mg_dt),'hh:mm');

	    	if(info.mg_type =="text"){
	    		if(your_text_yn=="Y"){
	    			var readcount = 0;
					for(var icnt =0;icnt < info.mg_reads.length;icnt++){
						if(info.mg_reads[icnt].read_yn=="N"){
							readcount++;
						}
					}
	    			var content = "<div class='msg-item msg-right' id='mg_idx_"+info.mg_idx+"' data-info='"+item+"'>";
			       	content +="      <div class='msg_cont'>";
			       	content +="            <div class='msg'>";
			       	content +="                " + info.mg_text.replace(/\n/g, "<br />");
					content +="     			<div class='regdt'>"+getFormatDate(new Date(info.mg_dt),'hh:mm')+"</div>";
					if(readcount > 0){
						content +="     			<div class='readcount'>"+readcount+"</div>";
					}else{
						content +="     			<div class='readcount'></div>";
					}
			       	content +="            </div>";
			       	content +="       </div>";
			       	content +="</div>";

	    		}else{
	    			var content = "<div class='msg-item msg-left' data-info='"+item+"'>";
	    			content +="       <div class='icon'>";
		            content +="          <img src='"+info.user_info[0].user_img+"' />";
		            content +="      </div>";
		            content +="      <div class='name'>"+info.user_info[0].user_nick+"</div>";
			       	content +="      <div class='msg_cont'>";
			       	content +="            <div class='msg'>";
			       	content +="                " + info.mg_text.replace(/\n/g, "<br />");
					content +="     			<div class='regdt'>"+getFormatDate(new Date(info.mg_dt),'hh:mm')+"</div>";
			       	content +="            </div>";
			       	content +="       </div>";
			       	content +="</div>";
	    		}
			}else if(info.mg_type =="file"){

	    	}else if(info.mg_type =="img"){

	    	}
    	}

    	if(is_prepend==true){
    		$("#chat_body").prepend(content);
    	}else{
    		$("#chat_body").append(content);
    	}
    }

    function scrollBottom(){
    	$("#wrap").scrollTop($("#wrap")[0].scrollHeight);
    }
</script>
<?php include("./layout_footer.php")?>
