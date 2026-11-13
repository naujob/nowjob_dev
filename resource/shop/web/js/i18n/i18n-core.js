/*
 * 웹 다국어 공통 함수 (정적 파일 — 모든 웹 페이지 공통, 브라우저 캐시 사용)
 *  - window.t(s)        : 화면 문구 번역. 사전(window.I18N_ZH)에 있으면 중국어, 없으면 원문 반환
 *  - window.lang_url(p) : 중국어 페이지일 때 내부 경로 앞에 /zh 를 붙여 반환
 *
 * 현재 언어는 PHP(i18n_helper.php :: site_lang_head_tags)가 출력하는
 * <html lang="zh-CN"> 값으로 판단한다. (요청마다 달라지는 값은 PHP가 한 곳에서만 출력)
 * 사전 파일(zh.js)은 중국어 페이지에서만 따로 로드되므로, 한국어 페이지에서는 원문이 그대로 나온다.
 */
(function (window) {
    'use strict';

    // 현재 페이지가 중국어인지 여부 (<html lang="zh-CN">)
    function isZhPage() {
        return document.documentElement.lang === 'zh-CN';
    }

    // 번역 함수: 공백 제거한 문구를 사전에서 찾고, 없으면 원문을 돌려준다
    window.t = function (s) {
        var d = window.I18N_ZH;
        var k = String(s).trim();
        return (d && Object.prototype.hasOwnProperty.call(d, k)) ? d[k] : s;
    };

    // 내부 링크 함수: 중국어 페이지에서 /zh 가 없는 내부 경로에만 /zh 를 붙인다
    window.lang_url = function (p) {
        return (isZhPage() && p && p.charAt(0) === '/' && !/^\/zh(\/|\?|$)/.test(p)) ? '/zh' + p : p;
    };
})(window);
