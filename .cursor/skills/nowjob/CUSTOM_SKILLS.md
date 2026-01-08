---
name: nowjob-custom-skills
description: NOWJOB 프로젝트 전용 커스텀 스킬 모음
---

# NOWJOB 프로젝트 커스텀 스킬

이 문서는 NOWJOB 프로젝트에서 자주 사용되는 작업을 위한 커스텀 스킬을 정의합니다.

## 1. explain-flow

**명령 이름**: `explain-flow`  
**설명**: 이 프로젝트 기준으로 코드 흐름을 단계별로 설명한다

**사용 시나리오**:
- CodeIgniter MVC 구조를 따라 Controller → Model → View 흐름 설명
- jQuery 이벤트 핸들러의 실행 순서 설명
- Socket.IO 이벤트의 emit/on 흐름 설명
- AJAX 요청의 전체 라이프사이클 설명

**지침**:
- CodeIgniter 3.1.11의 MVC 패턴을 기준으로 설명
- `comAjax()` 함수를 사용한 AJAX 통신 패턴 설명
- 데이터 흐름: View (jQuery) → Controller → Model → Database
- 각 단계별로 어떤 파일이 관여하는지 명시
- 한글로 설명하며, 코드 예시 포함

**예시 출력 형식**:
```
1. 사용자 액션 (View)
   └─> jQuery 이벤트 핸들러 실행
       └─> comAjax() 호출

2. 서버 요청 (Controller)
   └─> application/shop/controllers/{controller}.php
       └─> Model 호출

3. 데이터 처리 (Model)
   └─> application/common/models/{model}.php
       └─> Database 쿼리 실행

4. 응답 반환
   └─> JSON 응답
       └─> jQuery 콜백 함수 실행
```

---

## 2. refactor-legacy

**명령 이름**: `refactor-legacy`  
**설명**: 기존 구조를 유지한 채 최소 수정으로 리팩토링

**사용 시나리오**:
- PHP 5.3+ 호환성 유지하면서 코드 개선
- jQuery 레거시 코드를 현대적 패턴으로 변환 (기존 API 유지)
- CodeIgniter 컨트롤러/모델 구조 유지하면서 가독성 개선

**지침**:
- **기존 API 호환성 필수**: `comAjax()`, `commonAlert()` 등 공통 함수 시그니처 변경 금지
- **점진적 개선**: 한 번에 모든 것을 바꾸지 말고, 작은 단위로 개선
- **var 사용 허용**: 레거시 코드에서 `var` 사용은 유지 가능
- **함수명 규칙**: 동사로 시작 (`get`, `make`, `update` 등)
- **한글 주석 추가**: 복잡한 로직에 한글 주석 필수
- **에러 처리 강화**: try-catch 또는 조건문으로 예외 처리
- **data-* 속성 활용**: HTML 구조 의존적 DOM 탐색을 data-* 속성으로 변경

**리팩토링 우선순위**:
1. 인라인 JavaScript를 외부 파일로 분리
2. 하드코딩된 값을 설정 변수로 변경
3. 중복 코드를 공통 함수로 추출
4. 주석 처리된 코드 제거
5. 변수명을 명확하게 변경

---

## 3. debug-jquery

**명령 이름**: `debug-jquery`  
**설명**: jQuery 이벤트/DOM 기준으로 버그 원인 분석

**사용 시나리오**:
- jQuery 이벤트가 발생하지 않는 문제
- DOM 요소를 찾지 못하는 문제
- 이벤트 위임이 작동하지 않는 문제
- AJAX 응답 처리 오류

**지침**:
- **이벤트 바인딩 확인**: `$(document).on()` vs `$().on()` 차이점 설명
- **DOM 준비 상태**: `$(document).ready()` vs `DOMContentLoaded` 타이밍 이슈
- **선택자 문제**: 클래스 기반 선택자 대신 data-* 속성 사용 권장
- **AJAX 디버깅**: `comAjax()` 함수의 success/error 콜백 확인
- **이벤트 전파**: `event.stopPropagation()`, `event.preventDefault()` 사용 여부
- **비동기 타이밍**: AJAX 완료 전 DOM 조작 시도 여부

**디버깅 체크리스트**:
1. jQuery 로드 여부 확인
2. DOM 요소 존재 여부 확인 (`console.log($('.selector').length)`)
3. 이벤트 핸들러 등록 여부 확인
4. AJAX 요청/응답 로그 확인
5. 에러 콜백에서 상세 정보 출력

**예시 디버깅 코드**:
```javascript
// 이벤트 바인딩 확인
console.log('이벤트 바인딩 시도:', $('.target-element').length, '개 요소 발견');

// AJAX 요청 전 로그
console.log('AJAX 요청:', url, params);

// AJAX 응답 로그
comAjax(url, params, function(result) {
    console.log('AJAX 응답:', result);
    if (result.code !== '00') {
        console.error('에러 코드:', result.code, '메시지:', result.msg);
    }
});
```

---

## 4. mobile-debug

**명령 이름**: `mobile-debug`  
**설명**: console.log로는 모바일 디버깅 내용 확인 어려우므로 모바일 디버깅을 위한 코드 만들기

**사용 시나리오**:
- 모바일 기기에서 발생하는 버그 디버깅
- 터치 이벤트 문제
- 모바일 브라우저 호환성 이슈
- WebView (안드로이드/iOS) 특정 문제

**지침**:
- **화면에 디버그 정보 표시**: `console.log` 대신 화면에 표시하는 디버그 패널 생성
- **에러 수집**: try-catch로 에러를 캡처하여 화면에 표시
- **이벤트 로깅**: 터치 이벤트, 스크롤 이벤트 등을 화면에 표시
- **네트워크 상태**: AJAX 요청/응답을 화면에 표시
- **디바이스 정보**: User-Agent, 화면 크기 등 정보 표시

**디버그 패널 구조**:
```javascript
// 모바일 디버그 패널 생성 함수
function createMobileDebugPanel() {
    var debugPanel = $('<div id="mobile-debug-panel"></div>')
        .css({
            position: 'fixed',
            bottom: '10px',
            right: '10px',
            width: '300px',
            maxHeight: '400px',
            backgroundColor: 'rgba(0,0,0,0.8)',
            color: '#fff',
            padding: '10px',
            fontSize: '12px',
            zIndex: 99999,
            overflow: 'auto',
            borderRadius: '5px'
        });
    
    $('body').append(debugPanel);
    
    // 디버그 로그 함수
    window.mobileDebug = function(message, type) {
        type = type || 'info';
        var color = type === 'error' ? '#ff0000' : type === 'warn' ? '#ffaa00' : '#00ff00';
        var logEntry = $('<div></div>')
            .css({ color: color, marginBottom: '5px' })
            .text(new Date().toLocaleTimeString() + ' [' + type + '] ' + message);
        $('#mobile-debug-panel').prepend(logEntry);
        
        // 최대 50개까지만 유지
        if ($('#mobile-debug-panel > div').length > 50) {
            $('#mobile-debug-panel > div:last').remove();
        }
    };
    
    // 에러 전역 캐처
    window.addEventListener('error', function(e) {
        mobileDebug('에러: ' + e.message + ' (' + e.filename + ':' + e.lineno + ')', 'error');
    });
    
    // AJAX 에러 캐처
    $(document).ajaxError(function(event, xhr, settings, error) {
        mobileDebug('AJAX 에러: ' + settings.url + ' - ' + error, 'error');
    });
}

// 개발 환경에서만 활성화
if (window.location.hostname === 'localhost' || window.location.search.indexOf('debug=1') !== -1) {
    $(document).ready(function() {
        createMobileDebugPanel();
        mobileDebug('디버그 패널 초기화 완료', 'info');
    });
}
```

**사용법**:
- URL에 `?debug=1` 파라미터 추가하여 디버그 패널 활성화
- `mobileDebug('메시지', 'info|warn|error')` 함수로 로그 출력

---

## 5. create-ajax-handler

**명령 이름**: `create-ajax-handler`  
**설명**: 프로젝트 표준 패턴에 맞는 AJAX 핸들러 생성

**사용 시나리오**:
- 새로운 AJAX 기능 추가
- 기존 AJAX 코드를 표준 패턴으로 변환

**지침**:
- **공통 함수 사용**: `comAjax()` 또는 `comAjaxLoading()` 사용
- **에러 처리**: 공통 에러 처리 패턴 적용
- **응답 형식**: `{code: "00", msg: "success", data: {...}}` 형식 준수
- **로딩 표시**: 필요시 `showAjaxLoading()`, `hideAjaxLoading()` 사용
- **한글 주석**: 각 단계별 한글 주석 필수

**표준 AJAX 핸들러 템플릿**:
```javascript
/**
 * {기능 설명} AJAX 처리
 * @param {타입} param1 파라미터 설명
 */
function handleAjaxRequest(param1) {
    // 입력값 검증
    if (!param1 || param1 === '') {
        commonAlert('{에러 메시지}', function() {
            // 포커스 이동 등
        });
        return;
    }
    
    // 파라미터 준비
    var params = {
        param1: param1
        // 추가 파라미터
    };
    
    // AJAX 요청
    comAjax('/{controller}/{method}', params, function(result) {
        // 성공 처리
        if (result.code === '00') {
            // 성공 로직
            // 예: 화면 업데이트, 리다이렉트 등
        } else {
            // 서버 에러 처리
            commonAlert(result.msg || '처리 중 오류가 발생했습니다.', function() {});
        }
    });
}
```

**이벤트 바인딩 예시**:
```javascript
// jQuery 이벤트 위임 사용
$(document).on('click', '.ajax-trigger', function(e) {
    e.preventDefault();
    var dataValue = $(this).data('value'); // data-* 속성 사용
    handleAjaxRequest(dataValue);
});
```

---

## 6. create-ci-controller

**명령 이름**: `create-ci-controller`  
**설명**: CodeIgniter 컨트롤러 생성 (프로젝트 표준 패턴)

**사용 시나리오**:
- 새로운 기능을 위한 컨트롤러 생성
- 기존 컨트롤러를 참고하여 표준 패턴 적용

**지침**:
- **MY_Controller 상속**: `application/common/core/MY_Controller.php` 상속
- **네이밍**: 파일명과 클래스명 일치 (PascalCase)
- **메서드명**: camelCase 사용
- **한글 주석**: 각 메서드에 한글 주석 필수
- **에러 처리**: try-catch 또는 조건문으로 처리
- **응답 형식**: JSON 응답 시 `{code: "00", msg: "success", data: {...}}` 형식

**표준 컨트롤러 템플릿**:
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * {컨트롤러 설명}
 */
class {ControllerName} extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        // 필요한 라이브러리/모델 로드
        // $this->load->model('commonmodel');
        // $this->load->library('user');
    }
    
    /**
     * {메서드 설명}
     * @return void
     */
    public function index()
    {
        // 뷰 데이터 준비
        $data = array(
            // 데이터
        );
        
        // 뷰 로드
        $this->load->view('{web|mobile}/{controller}/{view}', $data);
    }
    
    /**
     * AJAX 처리 메서드
     * @return void
     */
    public function ajax_method()
    {
        // 입력값 검증
        $param1 = $this->input->post('param1');
        if (empty($param1)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'code' => '01',
                    'msg' => '필수 파라미터가 없습니다.',
                    'data' => null
                )));
            return;
        }
        
        try {
            // 비즈니스 로직 처리
            // $result = $this->commonmodel->someMethod($param1);
            
            // 성공 응답
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'code' => '00',
                    'msg' => 'success',
                    'data' => $result
                )));
        } catch (Exception $e) {
            // 에러 응답
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'code' => '99',
                    'msg' => '처리 중 오류가 발생했습니다.',
                    'data' => null
                )));
        }
    }
}
```

---

## 7. create-jquery-event

**명령 이름**: `create-jquery-event`  
**설명**: jQuery 이벤트 핸들러 생성 (프로젝트 표준 패턴)

**사용 시나리오**:
- 새로운 이벤트 핸들러 추가
- 기존 이벤트 핸들러를 표준 패턴으로 변환

**지침**:
- **이벤트 위임 사용**: `$(document).on()` 패턴 사용
- **data-* 속성 활용**: HTML 구조 의존적 선택자 지양
- **함수명**: 동사로 시작 (`handle`, `toggle`, `update` 등)
- **에러 처리**: 입력값 검증 및 에러 메시지 표시
- **한글 주석**: 각 함수에 한글 주석 필수

**표준 이벤트 핸들러 템플릿**:
```javascript
/**
 * {기능 설명} 이벤트 핸들러
 * @param {Event} e 이벤트 객체
 */
function handleSomeEvent(e) {
    e.preventDefault();
    
    // data-* 속성에서 값 가져오기
    var $target = $(e.currentTarget);
    var dataValue = $target.data('value');
    var action = $target.data('action');
    
    // 입력값 검증
    if (!dataValue || dataValue === '') {
        commonAlert('{에러 메시지}', function() {
            // 포커스 이동 등
        });
        return;
    }
    
    // 처리 로직
    // ...
}

// 이벤트 바인딩 (이벤트 위임)
$(document).on('click', '.event-trigger', handleSomeEvent);

// 또는 DOM 준비 후 직접 바인딩
$(document).ready(function() {
    $('.event-trigger').on('click', handleSomeEvent);
});
```

**이벤트 위임 vs 직접 바인딩**:
- **이벤트 위임**: 동적으로 추가되는 요소에도 작동 (`$(document).on()`)
- **직접 바인딩**: 페이지 로드 시 존재하는 요소만 (`$().on()`)

---

## 8. analyze-data-flow

**명령 이름**: `analyze-data-flow`  
**설명**: 데이터 흐름 분석 (프론트엔드 → 백엔드 → 데이터베이스)

**사용 시나리오**:
- 특정 기능의 데이터 흐름 파악
- 버그 발생 지점 추적
- 새로운 기능 추가 시 기존 패턴 파악

**지침**:
- **전체 흐름 추적**: View → Controller → Model → Database → Model → Controller → View
- **파일 경로 명시**: 각 단계에서 사용되는 파일 경로 표시
- **데이터 변환**: 각 단계에서 데이터가 어떻게 변환되는지 설명
- **에러 처리**: 각 단계에서 발생할 수 있는 에러와 처리 방법

**분석 출력 형식**:
```
[데이터 흐름 분석: {기능명}]

1. 프론트엔드 (View)
   파일: application/shop/views/{web|mobile}/{controller}/{view}.php
   - 사용자 입력: {입력값}
   - jQuery 이벤트: {이벤트명}
   - AJAX 요청: comAjax('{url}', {params}, callback)

2. 라우팅
   파일: application/shop/config/routes.php (또는 기본 라우팅)
   - URL: /{controller}/{method}
   - 매핑: {Controller}::{method}

3. 컨트롤러
   파일: application/shop/controllers/{controller}.php
   - 메서드: {method}()
   - 입력 검증: {검증 로직}
   - Model 호출: $this->{model}->{method}({params})

4. 모델
   파일: application/common/models/{model}.php
   - 메서드: {method}()
   - 데이터베이스 쿼리: {쿼리 설명}
   - 반환값: {반환 데이터 구조}

5. 데이터베이스
   - 테이블: {table_name}
   - 작업: {INSERT|UPDATE|SELECT|DELETE}
   - 조건: {WHERE 조건}

6. 응답 흐름
   Model → Controller → JSON 응답 → jQuery 콜백 → 화면 업데이트
```

---

## 9. socket-io-handler

**명령 이름**: `socket-io-handler`  
**설명**: Socket.IO 이벤트 처리 코드 생성 (채팅 기능용)

**사용 시나리오**:
- 채팅 기능 관련 Socket.IO 이벤트 추가
- 실시간 통신 기능 구현

**지침**:
- **이벤트 명명 규칙**: `{action}` (emit), `{action}_return` (on)
- **에러 처리**: `code !== "00"` 체크
- **이벤트 중복 방지**: `socket.off()` 후 `socket.on()` 사용
- **한글 주석**: 각 이벤트 핸들러에 한글 주석 필수

**표준 Socket.IO 핸들러 템플릿**:
```javascript
/**
 * Socket.IO 이벤트 초기화
 */
function initSocketEvents() {
    // 기존 이벤트 제거 (중복 방지)
    socket.off('event_name_return');
    
    // 이벤트 수신
    socket.on('event_name_return', function(result) {
        // 응답 처리
        if (result.code === '00') {
            // 성공 처리
            // 예: 화면 업데이트, 메시지 표시 등
        } else {
            // 에러 처리
            commonAlert(result.msg || '처리 중 오류가 발생했습니다.', function() {});
        }
    });
}

/**
 * Socket.IO 이벤트 전송
 * @param {Object} data 전송할 데이터
 */
function sendSocketEvent(data) {
    // 입력값 검증
    if (!data || !data.required_field) {
        commonAlert('필수 데이터가 없습니다.', function() {});
        return;
    }
    
    // 이벤트 전송
    socket.emit('event_name', data);
}

// DOM 준비 후 초기화
$(document).ready(function() {
    // Socket 연결 확인
    if (typeof socket !== 'undefined' && socket.connected) {
        initSocketEvents();
    } else {
        // Socket 연결 대기
        setTimeout(initSocketEvents, 100);
    }
});
```

**주요 Socket.IO 이벤트 패턴**:
- **방 입장**: `socket.emit('room_join', data)`
- **메시지 전송**: `socket.emit('message_insert', data)`
- **메시지 수신**: `socket.on('message_insert_return', callback)`
- **실시간 알림**: `socket.on('realtime_message', callback)`

---

## 10. create-common-function

**명령 이름**: `create-common-function`  
**설명**: 공통 함수 생성 (add.js 또는 common.js에 추가)

**사용 시나리오**:
- 여러 곳에서 사용되는 기능을 공통 함수로 추출
- 새로운 공통 유틸리티 함수 추가

**지침**:
- **파일 위치**: 
  - 웹: `resource/shop/web/js/add.js`
  - 모바일: `resource/shop/mobile/js/add.js`
  - 관리자: `resource/admin/js/common.js`
  - 채팅: `chatting/js/common.js`
- **함수명**: 동사로 시작 (`get`, `make`, `update`, `check` 등)
- **재사용성**: 매개변수를 통해 다양한 상황에서 사용 가능하도록 설계
- **에러 처리**: 입력값 검증 및 에러 메시지 반환
- **한글 주석**: JSDoc 형식의 한글 주석 필수

**표준 공통 함수 템플릿**:
```javascript
/**
 * {함수 설명}
 * @param {타입} param1 파라미터1 설명
 * @param {타입} param2 파라미터2 설명 (선택)
 * @param {Function} callback 콜백 함수 (선택)
 * @return {타입} 반환값 설명
 */
function functionName(param1, param2, callback) {
    // 입력값 검증
    if (!param1 || param1 === '') {
        if (typeof callback === 'function') {
            callback({
                code: '01',
                msg: '필수 파라미터가 없습니다.',
                data: null
            });
        }
        return false;
    }
    
    try {
        // 처리 로직
        var result = {
            // 결과 데이터
        };
        
        // 콜백 호출 (있는 경우)
        if (typeof callback === 'function') {
            callback({
                code: '00',
                msg: 'success',
                data: result
            });
        }
        
        return result;
    } catch (e) {
        // 에러 처리
        console.error('functionName 에러:', e);
        if (typeof callback === 'function') {
            callback({
                code: '99',
                msg: '처리 중 오류가 발생했습니다.',
                data: null
            });
        }
        return false;
    }
}
```

**공통 함수 예시**:
- `comAjax()`: AJAX 통신
- `commonAlert()`: 알림 표시
- `showAjaxLoading()`: 로딩 표시
- `hideAjaxLoading()`: 로딩 숨김

---

## 사용 방법

각 스킬은 Cursor의 커맨드로 호출할 수 있습니다:

1. **explain-flow**: "explain-flow로 이 코드의 흐름을 설명해줘"
2. **refactor-legacy**: "refactor-legacy로 이 코드를 리팩토링해줘"
3. **debug-jquery**: "debug-jquery로 이 jQuery 코드의 버그를 찾아줘"
4. **mobile-debug**: "mobile-debug 코드를 만들어줘"
5. **create-ajax-handler**: "create-ajax-handler로 AJAX 핸들러 만들어줘"
6. **create-ci-controller**: "create-ci-controller로 컨트롤러 만들어줘"
7. **create-jquery-event**: "create-jquery-event로 이벤트 핸들러 만들어줘"
8. **analyze-data-flow**: "analyze-data-flow로 이 기능의 데이터 흐름 분석해줘"
9. **socket-io-handler**: "socket-io-handler로 Socket.IO 이벤트 처리 코드 만들어줘"
10. **create-common-function**: "create-common-function로 공통 함수 만들어줘"

---

**작성일**: 2026-01-08  
**최종 수정일**: 2026-01-08
