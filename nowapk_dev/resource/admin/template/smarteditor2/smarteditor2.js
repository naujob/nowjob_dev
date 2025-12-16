
$(document).ready(function(){
	SEditor_init();
});

var oEditors = []; // 에디터 저장변수
// 네이버 에디터 초기화
function SEditor_init(){
	$('.SEditor').each(function(){
		$(this).css({"width":"100%" , "min-width":"260px" , "min-height":"300px"});
		var TextMode = $(this).attr('data-text-mode');
		if( TextMode == undefined || TextMode == ''){  TextMode = '';}
		SeEditorCreate($(this).attr("id"),TextMode);
	});
	
	$('.SEditor').each(function(i,v){
		$(this).closest('form').on('submit', submitContents);
	});
}
// 네이버 에디터 적용 - 아이디기준 개별적용
function SeEditorCreate(id,TextMode){
	var sLang = "ko_KR";	

	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: id,
		sSkinURI: "/resource/admin/template/smarteditor2/SmartEditor2Skin.html",
		htParams : {
			bUseToolbar : TextMode == 'true' ? false : true, 
			bUseVerticalResizer : true, 
			bUseModeChanger : TextMode == 'true' ? false : true,
			bSkipXssFilter : true,
			//aAdditionalFontList : aAdditionalFontSet, // 추가 글꼴 목록
			fOnBeforeUnload : function(){},
			I18N_LOCALE : sLang
		},
		fOnAppLoad : function(){
				
		},
		fCreator: "createSEditor2"
	});
}

function getSeEditText(id) {
	try {
		var object = oEditors.getById[id];
		if(oEditors.getById[id].getContents() == null || oEditors.getById[id].getContents() == '<p><br></p>' || oEditors.getById[id].getContents() == '<p>&nbsp;</p>') {
			$(oEditors.getById[id].elPlaceHolder).val('');
		}
		else {
			oEditors.getById[id].exec("UPDATE_CONTENTS_FIELD", []);
			return $("#" + id).val();
		}
	} catch(e) {
		alert(e);
		return "ERROR";
	}
}

function submitContents() {
	for(var i=0; i<oEditors.length; i++){
		if(oEditors[i].getContents() == null || oEditors[i].getContents() == '<p><br></p>' || oEditors[i].getContents() == '<p>&nbsp;</p>') {
			$(oEditors[i].elPlaceHolder).val('');
		}
		else {
			oEditors[i].exec("UPDATE_CONTENTS_FIELD", []);
		}
	}
	try {
	} catch(e) {}
}