/**
 * JQUERY 확장홤수
 */
(function($) {
	//숫자만제한,영문+숫자,전화번호
	$(document).on("keyup", "input[numberOnly]", function() {$(this).val( $(this).val().replace(/[^0-9]/gi,"") );});
	$(document).on("keyup", "input[numEngOnly]", function() {$(this).val( $(this).val().replace(/[^a-zA-Z0-9]/gi,"") );});
	$(document).on("keyup", "input[telOnly]", function() {$(this).val( $(this).val().replace(/[^0-9-]/gi,"") );});

	//이미지 미리보기
	try{
		$('.btn-img-preivew').magnificPopup({
		  type: 'image'
		});
	}catch(ex){}

	//에디터 탭처리
	$(".js-tab").click(function(){
		$('.SEditor').each(function(){
			oEditors.getById[$(this).attr("id")].exec('RESIZE_EDITING_AREA_BY',[true]);
		});
	});

	//back space 처리
	function suppressBackspace(evt) {
	    evt = evt || window.event;
	    var target = evt.target || evt.srcElement;

	    if (evt.keyCode == 8 && !/input|textarea|iframe|div/i.test(target.nodeName)) {
	        history.go(-1);
	    }
	}
	document.onkeydown = suppressBackspace;
	document.onkeypress = suppressBackspace;

    $(document).on('click', '.btn-calendar', function () {
        var start_date = '';
        var end_date = '';
        var today = new Date();
        switch ($(this).attr('data-calendar-type')) {
            case 'full-period':
                start_date = new Date('2020-01-01');
                end_date = new Date('2999-12-31');
                break;
            case 'prev-month':
                today.setMonth(today.getMonth() - 1);
            case 'cur-month':
                start_date = new Date(today.getFullYear(), today.getMonth(), 1);
                end_date = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                break;
            case 'today':
                start_date = end_date = today;
                break;
        }
        $('[name=period_type]').val($(this).attr('data-calendar-type'));
        setDate($(this), start_date, end_date);
    }).on('change', '.data-start, .data-end', function () {
        $('[name=period_type]').val('');
        $('.btn-calendar').removeClass('on');
    }).on('click', '.btn-reset', function () {
        var parent_form = $(this).parents('form');
        parent_form.find('input[type=text]').val('');
        parent_form.find('input:radio').each(function () {
            parent_form.find('input:radio[name=' + $(this).attr('name') + ']:first').attr('checked', true);
        });
        parent_form.find('select').each(function () {
            $(this).val($(this).find('option:first').val());
        });

        parent_form.find('.btn-calendar[data-calendar-type=cur-month]').trigger('click');
    }).on('click', 'thead tr input[type=checkbox]', function () {
        var flag = $(this).is(':checked');
        $(this).closest('table').find('tbody tr td input:checkbox').prop('checked', flag);
    });

    function setDate(button, start, end) {
        var parent = button.parents('.inline-wrap');
        var start_input = parent.find('.data-start');
        var end_input = parent.find('.data-end');

        var start_month = '' + (start.getMonth() + 1);
        if (start_month.length !== 2) {
            start_month = '0' + start_month;
        }
        var start_date = '' + start.getDate();
        if (start_date.length !== 2) {
            start_date = '0' + start_date;
        }

        var end_month = '' + (end.getMonth() + 1);
        if (end_month.length !== 2) {
            end_month = '0' + end_month;
        }
        var end_date = '' + end.getDate();
        if (end_date.length !== 2) {
            end_date = '0' + end_date;
        }

        start_input.val(start.getFullYear() + '-' + start_month + '-' + start_date);
        end_input.val(end.getFullYear() + '-' + end_month + '-' + end_date);

        $('.btn-calendar').not(button).removeClass('on');
        button.addClass('on');
    }

}(jQuery));

/**
 * 문자열 일괄 변경
 * @param org : 대상
 * @param dest : 변경문구
 * @returns {string}
 */
String.prototype.replaceAll = function(org, dest) {
    return this.split(org).join(dest);
}

String.prototype.number = function() {
    return this.replaceAll(",","");
}

/**
 * 숫자 콤마 처리
 * @returns {string|number}
 */
Number.prototype.format = function(){
    if(this==0) return 0;

    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (this + '');

    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

    return n;
};

if (typeof validateEmail === 'undefined') {
    function validateEmail(email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    }
}

/**
 * 숫자 콤마 처리
 * @returns {string|number}
 */
String.prototype.format = function(){
    var num = parseFloat(this);
    if( isNaN(num) ) return "0";

    return num.format();
};

/**
 * 데이터 로딩 처리
 * @param tmpl_id : 템플릿 아이디
 * @param data : 매칭데이터 배열
 */
function loadTmpl(tmpl_id,data){
    var item_tmpl = $(tmpl_id);
    if(item_tmpl==undefined || item_tmpl == null){
        return "";
    }

    var result_html  = "";

    //데이터 처리
    $.each(data, function(key, obj) {
        var clone = item_tmpl.clone();
        var temp = clone.html();
        $.each(obj, function(key, value) {
            temp = temp.replaceAll('{{'+ key + '}}',value);
        });

        result_html += temp;
    });
    return result_html;
}

/**
 * 공용 ajax 처리
 * @param url : 호출URL
 * @param params : 파라미터
 * @param callback : 콜백
 */
var ajax_first_num = 0; 
function comAjax(url,params,callback) {
    $.ajax({
        type: "POST",
        url: url,
        data: params,
		beforeSend : function(xhr, opts) {
	        // when validation is false
	        ajax_first_num = ajax_first_num + 1; 
			if(ajax_first_num!=1){
				COMMON.toast("데이터 처리중입니다.",1000,function(){});
				xhr.abort();
				return false;
			}
	    },
        success: function (result) {
        	ajax_first_num = 0;
            callback(result);
        },
        error: function (xhr, status, error) {
        	ajax_first_num = 0;
            COMMON.alert('통신오류 : ' + error);
        }
    });
}

/**
 * 공용 ajax 처리
 * @param url : 호출URL
 * @param params : 파라미터
 * @param callback : 콜백
 */
function comAjaxLoading(url,params,callback) {
    loadingOpen();
    $.ajax({
        type: "POST",
        url: url,
        data: params,
        beforeSend : function(xhr, opts) {
	        // when validation is false
	        ajax_first_num = ajax_first_num + 1; 
			if(ajax_first_num!=1){
				COMMON.toast("데이터 처리중입니다.",1000,function(){});
				xhr.abort();
				return false;
			}
	    },
        success: function (result) {
        	ajax_first_num = 0;
            loadingClose();
            callback(result);
        },
        error: function (xhr, status, error) {
        	ajax_first_num = 0;
            loadingClose();
            COMMON.alert('통신오류 : ' + error);
        }
    });
}

/**
 * 공용 ajax 처리
 * @param url : 호출URL
 * @param params : 파라미터
 * @param callback : 콜백
 */
function fileAjax(url,params,callback) {
    $.ajax({
        type: "POST",
        url: url,
        data: params,
        beforeSend : function(xhr, opts) {
	        // when validation is false
	        ajax_first_num = ajax_first_num + 1; 
			if(ajax_first_num!=1){
				COMMON.toast("데이터 처리중입니다.",1000,function(){});
				xhr.abort();
				return false;
			}
	    },
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
        	ajax_first_num = 0;
            loadingClose();
            callback(result);
        },
        error: function (xhr, status, error) {
        	ajax_first_num = 0;
            loadingClose();
            COMMON.alert('통신오류 : ' + error);
        }
    });
}

/**
 * 공용 ajax 처리
 * @param url : 호출URL
 * @param params : 파라미터
 * @param callback : 콜백
 */
function fileAjaxLoading(url,params,callback) {
    loadingOpen();
    $.ajax({
        type: "POST",
        url: url,
        data: params,
        beforeSend : function(xhr, opts) {
	        // when validation is false
	        ajax_first_num = ajax_first_num + 1; 
			if(ajax_first_num!=1){
				COMMON.toast("데이터 처리중입니다.",1000,function(){});
				xhr.abort();
				return false;
			}
	    },
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
        	ajax_first_num = 0;
            loadingClose();
            callback(result);
        },
        error: function (xhr, status, error) {
        	ajax_first_num = 0;
            loadingClose();
            COMMON.alert('통신오류 : ' + error);
        }
    });
}

//쿠키 설정
var setCookie = function(name, value, day) {
    var date = new Date();
    date.setTime(date.getTime() + day * 60 * 60 * 24 * 1000);
    document.cookie = name + '=' + value + ';expires=' + date.toUTCString() + ';path=/';
};

//쿠키 가져오기
var getCookie = function(name) {
    var value = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return value? value[2] : null;
};

//쿠키 삭제
var deleteCookie = function(name) {
    var date = new Date();
    document.cookie = name + "= " + "; expires=" + date.toUTCString() + "; path=/";
}

//마진유형별 금액계산
function getSalePrice(type,per,p_price,s_price){
	p_price = parseInt(p_price);
	s_price = parseInt(s_price);
	if(type=="SAME"){
		return Math.floor((p_price + (p_price * (per/ 100)))/10 ) * 10;
	}else if(type=="DIFF"){
		return Math.floor((p_price + ((s_price - p_price) * (per/ 100)))/10 ) * 10;
	}
	return 0;
}

function loadEditor(element,height){
	$(element).summernote({
		toolbar: [
			['style', ['bold', 'italic', 'underline', 'clear']],
			['font', ['strikethrough', 'superscript', 'subscript']],
			['fontsize', ['fontsize']],
			['fontname', ['fontname']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['height', ['height']]
			['table', ['table']],
			['insert', ['link', 'picture', 'video']],
			['view', ['fullscreen', 'codeview', 'help']],
		]
		,fontNames: ['Noto Sans KR','맑은 고딕','Dotum','Gulim','Gungsuh','Arial', 'Arial Black', 'Comic Sans MS', 'Courier New']
		,height:height
		,callbacks: {
	    	onImageUpload: function (files,editor) {
				for(var i=0; i < files.length; i++) {
					var file = files[i];
					var formData = new FormData();
					formData.append("file", file);
					$.ajax({
							url: '/admin/common_proc/img_upload',
							data: formData,
							cache: false,
							contentType: false,
							processData: false,
							type: 'POST',
							success: function (data) {
								$(element).summernote('insertImage', data, function ($image) {
										$image.attr('src', data);
										$image.attr('class', 'childImg');
								});
								var imgUrl=$("#imgUrl").val();
								if(imgUrl){
										imgUrl=imgUrl+",";
								}
								$("#imgUrl").val(imgUrl+data);
							}
					});
				}
			}
		}
	});
}


//윈도우 센터 열기
function PopupCenter(url, title, w, h) {
    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }
}


//텍스트박스 입력확인
function isInputEmpty(element,msg){
	if($(element).val()==""){
		COMMON.alert(msg,function(){
			$(element).focus();
		});
		return true;
	}
	return false;
}

//텍스트박스 입력확인
function isCheckEmpty(element,msg){
	if($(element).is(":checked")==false){
		COMMON.alert(msg,function(){
			$(element).focus();
		});
		return true;
	}
	return false;
}

// 필수 입력필드 체크 + focus
function chkRequiredFocus(requiredFields) {
    var result = true;
    $.each(requiredFields, function(key, obj) {
    	var item = obj.element;
        if($(item)===undefined){
            COMMON.toast(key + '는 잘못 지정된 항목명입니다.');
            return false;
        }

        var tag_name = ( $(item).prop('tagName').toLowerCase());
        var tag_type = '';
        if(tag_name==='input'){
            tag_type = $(item).attr('type');
        }

        if(key =="empty"){
            //유형별 확인처리
            if(tag_type !='radio' && tag_type !='checkbox'){
            	alert(item);
                if($(item).val()==''){
                    showMsg(item,tag_name,tag_type,'empty',obj.name,obj.msg);
                    result = false;
                    return false;
                }
            }else{
                if($(item).is(':checked')==false){
                    showMsg(item,tag_name,tag_type,'empty',obj.name,obj.msg);
                    result = false;
                    return false;
                }
            }
        }else if(key =="minLen"){
            //유형별 확인처리
            var value = $(item).val().length;
            if(value < obj.min){
            	if(obj.msg==""){
            		obj.msg = obj.name + "은(는) " + obj.min + " 보다 작은 길이로 설정해주세요.";
            	}

                showMsg(item,tag_name,tag_type,'min',obj.name,obj.msg);
                result = false;
                return false;
            }
        }else if(key =="maxLen"){
            //유형별 확인처리
            var value = $(item).val().length;
            if(value > obj.max){
            	if(obj.msg==""){
            		obj.msg = obj.name + "은(는) " + obj.max + " 보다 큰 길이로 설정해주세요.";
            	}
                showMsg(item,tag_name,tag_type,'max',obj.name,obj.msg);
                result = false;
                return false;
            }
        }else if(key =="minmaxLen"){
            //유형별 확인처리
            var value = $(item).val().length;
            if(value > obj.max){
            	if(obj.msg=="" || obj.msg==undefined){
            		obj.msg = obj.name + "은(는) " + obj.min + "~" + obj.max + " 사이 값으로 설정해주세요.";
            	}
                showMsg(item,tag_name,tag_type,'min-max',obj.name,obj.msg);
                result = false;
                return false;
            }
        }
    });

    return result;

    function showMsg(key,tag_name,tag_type,chk_type,name,msg){
        var show_yn = false;
        if(msg!==undefined && msg!==''){
            show_yn = true;
            COMMON.alert(msg,function(){
                $(key).focus();
            });
        }else{
            if(chk_type==='empty') {
                if (tag_name === 'selected' && tag_name === 'checkbox' && tag_name === 'radio') {
                    show_yn = true;
                    COMMON.alert(name + '은 필수 선택사항 입니다.',function(){
                        $(key).focus();
                    });
                } else {
                    show_yn = true;
                    COMMON.alert(name + '은 필수 입력사항입니다.',function(){
                        $(key).focus();
                    });
                }
            }else {
                COMMON.alert(msg,function(){
                    $(key).focus();
                });
            }
        }

        //누락된 태그가 있는경우 처리
        if(show_yn===false){
            COMMON.alert(field_name + '은 필수 입력사항입니다.');
        }
    }
}

if (!String.prototype.padStart) {
    String.prototype.padStart = function padStart(targetLength, padString) {
        if (this.length >= targetLength) {
            return String(this);
        } else {
            if(padString == null || padString == ' ') {
                padString = ' ';
            } else if(padString.length > 1) {
                padString = padString.substring(0,1);
            }
            targetLength = targetLength - this.length;
            var prefix = "";
            for(var i=0; i<targetLength; i++) {
                prefix += padString;
            }
            return prefix + String(this);
        }
    };
}

if (String.prototype.trim === undefined) {
    String.prototype.trim = function () {
        return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
    };
}

var calByte = {
    getByteLength : function(s) {
        if (s == null || s.length == 0) {
            return 0;
        }

        var size = 0;
        for ( var i = 0; i < s.length; i++) {
            size += this.charByteSize(s.charAt(i));
        }
        return size;
    },

    cutByteLength : function(s, len) {
        if (s == null || s.length == 0) {
            return 0;
        }

        var size = 0;
        var rIndex = s.length;

        for ( var i = 0; i < s.length; i++) {
            size += this.charByteSize(s.charAt(i));
            if( size == len ) {
                rIndex = i + 1;
                break;
            } else if( size > len ) {
                rIndex = i;
                break;
            }
        }
        return s.substring(0, rIndex);
    },

    charByteSize : function(ch) {
        if (ch == null || ch.length == 0) {
            return 0;
        }

        var charCode = ch.charCodeAt(0);

        if (charCode <= 0x00007F) {
            return 1;
        } else if (charCode <= 0x0007FF) {
            return 2;
        } else if (charCode <= 0x00FFFF) {
            return 3;
        } else {
            return 4;
        }
    }
};

if (String.prototype.byteLength === undefined) {
    String.prototype.byteLength = function() {
        return calByte.getByteLength(this);
    }
}

if (typeof remaindStringBytes === 'undefined') {
    function remaindStringBytes(target, display_elmt, maxlength, cutFlag) {
        var byteLength = target.val().byteLength();

        if (byteLength > maxlength && cutFlag === true) {
            target.val(calByte.cutByteLength(target.val(), maxlength));
            byteLength = target.val().byteLength();
        }

        display_elmt.text(byteLength);
    }
}

if (typeof checkPass === 'undefined') {
    function checkPass(password){
        var regex = /^(?=.*\d)(?=.*[a-z])[0-9a-z!@#$%^&*?]{6,}$/i;
        var result = false;
        if (regex.test(password)) {
            result = true;
        }
        return result;
    }
}

if (typeof normalizePhoneNo === 'undefined') {
    function normalizePhoneNo(no) {
        return no.replace(/[^0-9]/g, "").replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/,"$1-$2-$3").replace("--", "-");
    }
}

if (typeof checkPhoneNo === 'undefined') {
    function checkPhoneNo(no) {
        return /(01[016789])-([1-9]{1}[0-9]{2,3})-([0-9]{4})$/.test(no);
    }
}

/**
 * 숫자인지 체크 [-,+ 부호 소수점 2자리까지 ]
 * @param num
 * @returns {boolean}
 */
function is_number(num) {
    var reg = /^[-+]?\d+.?\d?\d?$/;
    return reg.test(num);
}

/**
 * 금액형식을 숫자로만 변환
 * 폼/DOM 개체 숫자값, 타입(폼 개체 값 = 1, DOM 객체 = 2)
 * @param obj
 * @param type
 * @returns {string|boolean}
 */
function money_to_number(obj, type)
{
    var money = (type == 1) ? obj.value : String(obj);

    if (money == undefined) return false;

    var strMoney = new String;
    strMoney = money.toString();

    while (strMoney.indexOf(',') > -1)
    {
        strMoney = strMoney.replace(',', '');
    }

    if (type == 1)   // 폼 객체이면.
    {
        if (!isNaN(strMoney)) {
            obj.value = strMoney;
        }
    }
    else
    {
        if (strMoney == "-") return strMoney;
        else {

            if (isNaN(strMoney))
                return false;
            else
                return strMoney;
        }
    }
}
/**
 * 숫자를 금액형식으로 변환
 * @param obj
 * @param type
 * @returns {string|boolean}
 */
function number_to_money(obj, type) {
    var number = (type == 1) ? obj.value : String(obj);

    number = money_to_number(number);

    var numStr = number;

    var reg = /(^[+-]?\d+)(\d{3})/;
    number += '';

    while (reg.test(number))
        number = number.replace(reg, '$1' + ',' + '$2');


    if (type == 1)   // 폼 객체이면.
    {
        if (is_number(numStr)) {
            obj.value = number;
        }
    } else {
        if (is_number(numStr) || numStr == '-')
            return number;
        else
            return "";
    }
}

// 텍스트 카피하기
function copyToClipboard(textToCopy) {
	var textArea;

	function isOS() {
		//can use a better detection logic here
		return navigator.userAgent.match(/ipad|iphone/i);
	}

	function createTextArea(text) {
		textArea = document.createElement('textArea');
		textArea.readOnly = true;
		textArea.contentEditable = true;
		textArea.value = text;
		document.body.appendChild(textArea);
	}

	function selectText() {
		var range, selection;

		if (isOS()) {
			range = document.createRange();
			range.selectNodeContents(textArea);
			selection = window.getSelection();
			selection.removeAllRanges();
			selection.addRange(range);
			textArea.setSelectionRange(0, 999999);
		} else {
			textArea.select();
		}
	}

	function copyTo() {
		document.execCommand('copy');
		document.body.removeChild(textArea);
		COMMON.alert(textToCopy + "\n복사되었습니다!");
	}

	createTextArea(textToCopy);
	selectText();
	copyTo();
}

/**
 * price comma
 * @param str
 * @returns {string}
 */
function set_comma(str){
    //int형으로 들어온 경우 오류 수정.
    str = ""+str;

    return str.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
}

function showDeliveryTracking(invoice_no){
	PopupCenter("/admin/popup/delivery_tracking?invoice_no=" + invoice_no,"송장번호 조회",600,600);
}
