var winFixST = 0;
var clicking = false;
$(function(){
    browserCheck();//브라우저 체크
    gnbToggle();//GNB 메뉴 토글 이벤트
    datePeriod();//Datepicker 기간 설정
    tabEvent();//탭 이벤트
    checkoutFeeSelect();//이용요금 결제 선택 이벤트
    btnTop();//상단으로 이동 버튼 클릭
    fileText();//인풋 파일 텍스트 삽입 이벤트
});

$(window).on('scroll', function(){
   var winST = $(this).scrollTop();
   if(winST > 0){
       $('body').addClass('scrolled');
   } else {
       $('body').removeClass('scrolled');
   }
});

//클릭중 처리
function chkClicking(){
	if(clicking){
		COMMON.alert("데이터를 처리중입니다.",function(){});
	}
	return clicking;
}

//클릭중 해제
function initClicking(){
	clicking = false;
}

//클릭중 해제
function startClicking(){
	clicking = true;
}

function browserCheck(){
    if(navigator.userAgent.indexOf('9.0') != -1) {
        $('body').addClass('ie9');//explorer 9
    }
}

function gnbToggle(){
    $('.gnb').find('.depth1').on('click', function(){
        var target = $(this);
        var nextUL = target.closest('li').find('ul');
        if(!target.hasClass('on')){
            $('.gnb').find('.depth1').removeClass('on');
            $('.gnb li').find('ul').stop().slideUp(200);
            target.addClass('on');
            nextUL.stop().slideDown(200);
        } else {
            target.removeClass('on');
            nextUL.stop().slideUp(200);
        }
    });
}

function datePeriod(){
    $('.date-period').each(function(){
        var start = $(this).find('.data-start');
        var end = $(this).find('.data-end');
        var start_date = (start.val() === '') ? new Date() : new Date(start.val());
        var end_date = (end.val() === '') ? new Date() : new Date(end.val());
        
        $(this).find('.data-start').datepicker({
	    	language: 'ko',
	    	autoClose: true,
	    }); 
	    
        $(this).find('.data-end').datepicker({
	    	language: 'ko',
	    	autoClose: true,
	    }); 
	    
	    $(this).find('.form-start').datepicker({
	    	language: 'ko',
	    	autoClose: true,
	    }); 
	    
        $(this).find('.form-end').datepicker({
	    	language: 'ko',
	    	autoClose: true,
	    }); 
    });
}

function popOpen(obj){
    winFixST = $(window).scrollTop();
    $('body').addClass('scroll-fixed');
    $(window).scrollTop(winFixST);
    //$('.popup').stop().fadeOut(200);
    $(obj).stop().fadeIn(200);
}

function popClose(obj){
    $('body').removeClass('scroll-fixed');
    $(window).scrollTop(winFixST);
    $(obj).stop().fadeOut(200);
}

function toastOpen(obj, close, time){
    $(obj).addClass('on');
    if(time != null){
        var timer = setTimeout(function(){
            $(obj).removeClass('on');
            clearTimeout(timer);
        }, time);
    }
    if(close == true){
        $(obj).addClass('on-close');
    } else {
        $(obj).removeClass('on-close');
    }
}

function toastClose(obj){
    $(obj).removeClass('on');
}

function tabEvent(){
    $('.js-tab').find('a').on('click', function(){
    	var tab_id = $(this).data("tab_id");
    	$(".js-tab").find("a").removeClass("on");
    	$(".tab-article").removeClass("on");
    	$(this).addClass("on");
    	$(tab_id).addClass("on");
    });
}

function checkoutFeeSelect(){
    $('.month-select').find('.month-item').on('click', function(){
        var Idx = $(this).index();
        $(this).closest('.month-select').find('.month-item').removeClass('on');
        $(this).addClass('on');
    });
}

function btnTop(){
    $('.btn-top').on('click', function(){
       $('html, body').stop().animate({scrollTop : 0}, 300);
    });
}

function loadingOpen(){
    $('.loading').stop().fadeIn(200);
}

function loadingClose(){
    $('.loading').stop().fadeOut(200);
}

function fileText(){
    $('.js-input-file').find('input').on('change', function(){
        var filename = $(this).val();
        $(this).parent().find('span').text(filename);
    });
    
    $('.js-input-file').find('button').on('click', function(){
        $(this).parents('.js-input-file').find('input').click();
    });
}

/*  메시지 처리  */
(function (root, factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        // AMD
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node, CommonJS-like
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals (root is window)
        root.COMMON = factory(root.jQuery);
    }
} (this, function init($, undefined) {
    'use strict';

    if (!Object.keys) {
        Object.keys = (function () {
            var hasOwnProperty = Object.prototype.hasOwnProperty;
            var hasDontEnumBug = !({ toString: null }).propertyIsEnumerable('toString');
            var dontEnums = [
                'toString',
                'toLocaleString',
                'valueOf',
                'hasOwnProperty',
                'isPrototypeOf',
                'propertyIsEnumerable',
                'constructor'
            ];
            var dontEnumsLength = dontEnums.length;

            return function (obj) {
                if (typeof obj !== 'function' && (typeof obj !== 'object' || obj === null)) {
                    throw new TypeError('Object.keys called on non-object');
                }

                var result = [], prop, i;

                for (prop in obj) {
                    if (hasOwnProperty.call(obj, prop)) {
                        result.push(prop);
                    }
                }

                if (hasDontEnumBug) {
                    for (i = 0; i < dontEnumsLength; i++) {
                        if (hasOwnProperty.call(obj, dontEnums[i])) {
                            result.push(dontEnums[i]);
                        }
                    }
                }

                return result;
            };
        } ());
    }

    var exports = {};
    var VERSION = '1.0.0';
    exports.VERSION = VERSION;

    var locales = {
        ko : {
            OK      : '확인',
            CANCEL  : '취소',
            CONFIRM : '확인'
        }
    };

    var defaults = {
        locale: 'ko'
    };

    // 기본 설정
    exports.setDefaults = function () {
        var values = {};

        if (arguments.length === 2) {
            // allow passing of single key/value...
            values[arguments[0]] = arguments[1];
        } else {
            // ... and as an object too
            values = arguments[0];
        }

        $.extend(defaults, values);

        return exports;
    };

    exports.alert = function (message, callback) {
        drawPopup('alert', message, callback);
    };

    exports.confirm = function (message, callback, close_callback) {
        drawPopup('confirm', message, callback, close_callback);
    };

    exports.toast = function (message, time, callback) {
        var new_layer = $('<div></div>').addClass('toast').append(
            $('<p></p>').text(message)
        ).append(
            $('<button></button>').addClass('btn-close').text('닫기').click(function () {
                $(this).parents('.toast').remove();

                if (typeof callback === 'function') {
                    callback();
                }
            })
        );

        new_layer.addClass('on');
        if(time != null){
            var timer = setTimeout(function(){
                $(new_layer).removeClass('on');
                new_layer.remove();

                if (typeof callback === 'function') {
                    callback();
                }
            }, time);
        } else {
            new_layer.addClass('on-close');
        }

        $('body').append(new_layer);
    };

    exports.loadingOpen = function (msg) {
        if (msg === '') {
            msg = '로딩 중';
        }

        $('.loading').find('.ds-table-cell p').text(msg);
        $('.loading').stop().fadeIn(200);
    };

    exports.loadingClose = function () {
        $('.loading').stop().fadeOut(200);
    };

    function drawPopup(type, message, callback, close_callback) {
        var buttonGroup = $('<div></div>').addClass('btn-wrap').addClass('inline-wrap').addClass('tc');
		var titleMsg = "";
		
        if (type === 'alert') {
        	titleMsg = "메시지";
            buttonGroup.append(
                $('<button></button>').attr('type', 'button').addClass('btn-m').addClass('btn-type6').addClass('btnFocus').text(locales[defaults.locale].OK).click(function () {
                    $(this).parents('.popup').remove();
                    $('body').removeClass('scroll-fixed');

                    if (typeof callback === 'function') {
                        callback();
                    }
                })
            );
        } else if (type === 'confirm') {
        	titleMsg = "확인";
            buttonGroup.append(
                $('<button></button>').attr('type', 'button').addClass('btn-m').addClass('btn-type1').text(locales[defaults.locale].CANCEL).click(function () {
                    $(this).parents('.popup').remove();
                    $('body').removeClass('scroll-fixed');

                    if (typeof close_callback === 'function') {
                        close_callback();
                    }
                })
            ).append(
                $('<button></button>').attr('type', 'button').addClass('btn-m').addClass('btn-type6').addClass('btnFocus').text(locales[defaults.locale].CONFIRM).click(function () {
                    $(this).parents('.popup').remove();
                    $('body').removeClass('scroll-fixed');

                    if (typeof callback === 'function') {
                        callback();
                    }
                })
            );
        }

		
        var popup = $("<div></div>").addClass('popup').addClass(type).append(
            $('<div></div>').addClass('ds-table').append(
                $('<div></div>').addClass('ds-table-cell').append(
                    $('<div></div>').addClass('container').addClass('w350')
                    .append(
                    	$('<div></div>').addClass('header')
                    	.append(
                            $('<h2></h2>').html(titleMsg)
	                     )
	                     .append(
	                     	$('<button type="button" class="btn-close" onclick=\'popClose(".popup");\'>닫기</button>')
	                     )
                    )
                    .append(
                        $('<div></div>').addClass('contents').append(
                            $('<p></p>').addClass('tc').html(message)
                        )
                    ).append(
                        buttonGroup
                    )
                )
            )
        );

        $('body').append(popup);
        popOpen(popup);
        
        //포커스 변경.
        $(".btnFocus").focus();
    }

    return exports;
}));
