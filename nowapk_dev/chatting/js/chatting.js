function getRoomSubject(rm_info,user_id){
	for(var icnt = 0;icnt < rm_info.user_info.length;icnt++){
		if(rm_info.user_info[icnt].user_id!=user_id){
			return rm_info.user_info[icnt].user_name;
		}
	}
}

function getRoomImg(rm_info,user_id){
	for(var icnt = 0;icnt < rm_info.user_info.length;icnt++){
		if(rm_info.user_info[icnt].user_id!=user_id){
			return rm_info.user_info[icnt].img;
		}
	}
}

function getRoomDesc(rm_info,rm_type){
	if(rm_type=="chat"){
		return replaceAll(rm_info.rm_last_msg,"\n","<br/>");
	}else if(rm_type=="channel"){
		return rm_info.rm_desc;
	}
}
