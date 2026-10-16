(function () {
    'use strict';

    /* iOS/Android location permission result bridge */
    (function setLocationPermissionBridge() {
        var LOCATION_PERMISSION_KEY = 'nowjob_location_permission_status';

        function normalizePermissionStatus(value) {
            if (value === true || value === 'true' || value === 'granted' || value === 1 || value === '1') {
                return 'granted';
            }
            if (value === false || value === 'false' || value === 'denied' || value === 0 || value === '0' || value === 'never_ask_again') {
                return value === 'never_ask_again' ? 'never_ask_again' : 'denied';
            }
            return null;
        }

        function setStoredPermissionStatus(status) {
            try {
                if (status) {
                    localStorage.setItem(LOCATION_PERMISSION_KEY, status);
                }
            } catch (e) {}
        }

        function getStoredPermissionStatus() {
            try {
                return localStorage.getItem(LOCATION_PERMISSION_KEY);
            } catch (e) {
                return null;
            }
        }

        // Native -> Web callback
        window.onLocationPermissionResult = function (status) {
            var normalized = normalizePermissionStatus(status);
            if (!normalized) return;
            setStoredPermissionStatus(normalized);
        };

        // Global getter for any page
        window.getLocationPermissionStatus = getStoredPermissionStatus;
    }());

    /* 맨위로 가기 */
    (function setBtnTop() {
        if (!document.querySelector('.js-goto-top')) return;

        var btnTop = document.querySelector('.js-goto-top');
        var scrollArea = document.querySelectorAll('.page, .popup-content, .panel-body');

        scrollArea.forEach(function (elm) {
            elm.addEventListener('scroll', function (e) {
                var curpos = this.scrollTop;
                if ((window.innerHeight / 10) <= curpos) {
                    btnTop.classList.add('show');
                } else {
                    btnTop.classList.remove('show');
                }
            });

            btnTop.addEventListener('click', function (e) {
                e.preventDefault();
                elm.scrollTo(0, 0);
            });
        });
    }());

    /* 홈메뉴 열기,닫기 */
    (function setBottomNav() {
        if (!document.querySelector('.bottom-nav')) return;

        var menu = document.querySelector('.bottom-nav .menu');

        function toggleMenu() {
            if (menu.classList.contains('open')) {
                document.body.style.overflow = '';
            } else {
                document.body.style.overflow = 'hidden';
            }

            menu.classList.toggle('open');
        }

        /*
        document.querySelector('.js-toggle-menu').addEventListener('click', function (e) {
            e.preventDefault();

            toggleMenu();

            if (this.getAttribute('aria-expanded')) {
                this.setAttribute('aria-expanded', 'false');
            } else {
                this.setAttribute('aria-expanded', 'true');
            }
        });

        menu.addEventListener('click', function (e) {
            if(e.target.nodeName == 'A' || (e.target.classList && e.target.classList.contains('dimmed'))) {
                toggleMenu();
            }
        });
        */
    }());

    /* Lazy Loader : https://github.com/ApoorvSaxena/lozad.js */
    window.initLazyLoad = function() {
        if (typeof lozad == 'undefined') return;
        var observer = lozad();
        observer.observe();
    };

    (function setLazyLoadImg() {
        window.initLazyLoad();
    }());

    /* 모달 팝업 */
    (function setModal() {

        function setFocus($el) {
            if (!$el) return false;

            var $focusableEl = $el.querySelectorAll('a, button, input, select, textarea');
            var $firstEl = $el.querySelector('[autofocus]') || $focusableEl[0];
            var $lastEl = $focusableEl[$focusableEl.length - 1];

            $el.addEventListener('keydown', function (ev) {
                var keycode = ev.keycode || ev.which;

                switch (keycode) {
                    case 27: // esc key
                        closePopup($el);
                        break;
                    case 9: // tab key
                        if (ev.target === $lastEl && !ev.shiftKey) {
                            ev.preventDefault();
                            $firstEl.focus();
                        }
                        if (ev.target === $firstEl && ev.shiftKey) {
                            ev.preventDefault();
                            $lastEl.focus();
                        }
                        break;
                    default:
                        break;
                }
            });

            if ($firstEl) {
                setTimeout(function () {
                    $firstEl.focus();
                }, 100);
            }
        }

        window.closePopup = function ($el) {
            if (!$el) return false;

            var $trigger = document.querySelector('.is-trigger[aria-haspopup]');
            var activePopup = document.querySelectorAll('.layer-popup.in');

            if (activePopup.length == 1) {
                document.body.classList.remove('has-popup');
            }

            $el.classList.remove('in');

            setTimeout(function () {
                $el.remove();
            }, 1000);

            if ($trigger) {
                $trigger.classList.remove('is-trigger');
                $trigger.focus();
            }
        };

        window.openPopup = function (type, options) {
            if (!options || typeof options !== 'object') return false;

            var html = '';
            var $popup = document.createElement('div');
            var index = document.querySelectorAll('.layer-popup').length || 1;
            var id = options.id ? options.id.toString() : '';
            var title = options.title ? options.title.toString() : '알림';
            var content = options.content || '내용이 없습니다.';
            var firstButton = options.firstButton ? options.firstButton.toString() : '';
            var secondButton = options.secondButton ? options.secondButton.toString() : '';
            var thirdButton = options.thirdButton ? options.thirdButton.toString() : '';
            var firstCallback = options.firstCallback || null;
            var secondCallback = options.secondCallback || null;
            var thirdCallback = options.thirdCallback || null;
            var removeClose = options.removeClose == false ? '' : ' js-close-popup ';
            var clickOnce = options.clickOnce == false ? false : true;

            if (type == 'confirm') {
                id = id ? id + index : 'confirmDialog' + index;
                firstButton = firstButton ? firstButton : '취소';
                secondButton = secondButton ? secondButton : '확인';
                thirdButton = thirdButton ? '<button type="submit" aria-controls="' + id + '" class="btn btn-tertiary ' + removeClose + ' js-popup-action3">' + thirdButton + '</button>' : thirdButton;

                html = '<div id="' + id + '" class="layer-popup">' +
                    '<div role="alertdialog" aria-labelledby="confirmTitle' + index + '" aria-modal="true" class="popup-container">' +
                    '<h4 id="confirmTitle' + index + '" class="sr-text">' + title + '</h4>' +
                    '<button type="button" aria-controls="' + id + '" class="btn-close-popup  js-close-popup"><span class="sr-text">닫기</span></button>' +
                    '<div class="popup-content">' +
                    '<div class="msg">' + content + '</div>' +
                    '</div> <div class="popup-footer">' +
                    '<button type="button" aria-controls="' + id + '" class="btn btn-secondary  ' + removeClose + ' js-popup-action1">' + firstButton + '</button>' +
                    '<button type="submit" aria-controls="' + id + '" class="btn btn-primary ' + removeClose + ' js-popup-action2">' + secondButton + '</button>' + thirdButton +
                    '</div> </div> </div>';
            } else if (type == 'alert') {
                id = id ? id + index : 'alertDialog' + index;
                html = '<div id="' + id + '" class="layer-popup"><div role="alertdialog" aria-labelledby="alertTitle' + index + '" aria-modal="true" class="popup-container">' +
                    '<h4 id="alertTitle' + index + '" class="sr-text">' + title + '</h4>' +
                    '<button type="button" aria-controls="' + id + '" class="btn-close-popup  js-close-popup"><span class="sr-text">닫기</span></button>' +
                    '<div class="popup-content">' +
                    '<div class="msg">' + content + '</div>' +
                    '</div> <div class="popup-footer">' +
                    '<button type="button" aria-controls="' + id + '" class="btn btn-primary ' + removeClose + ' js-popup-action1">확인</button>' +
                    '</div> </div> </div>';
            } else if (type == 'alert2') {
                id = id ? id + index : 'alertDialog' + index;
                var closeButtonHtml = options.removeClose == false ? '' : '<button type="button" aria-controls="' + id + '" class="btn-close-popup js-close-popup"><span class="sr-text">닫기</span></button>';
                html = '<div id="' + id + '" class="layer-popup"><div role="alertdialog" aria-labelledby="alertTitle' + index + '" aria-modal="true" class="popup-container">' +
                    '<h4 id="alertTitle' + index + '" class="sr-text">' + title + '</h4>' +
                    closeButtonHtml +
                    '<div class="popup-content">' +
                    '<div class="msg">' + content + '</div>' +
                    '</div> <div class="popup-footer">' +
                    '<button type="button" aria-controls="' + id + '" class="btn btn-primary ' + removeClose + ' js-popup-action1">확인</button>' +
                    '</div> </div> </div>';
            } else if (type == 'modal') {
                id = id ? id + index : 'defaultDialog' + index;

                if (firstButton && !secondButton) {
                    firstButton = '<button type="button" aria-controls="' + id + '" class="btn btn-primary ' + removeClose + ' js-popup-action1">' + firstButton + '</button>';
                } else if (firstButton && secondButton) {
                    firstButton = '<button type="button" aria-controls="' + id + '" class="btn btn-secondary ' + removeClose + ' js-popup-action1">' + firstButton + '</button>';
                    secondButton = '<button type="button" aria-controls="' + id + '" class="btn btn-primary  ' + removeClose + ' js-popup-action2">' + secondButton + '</button>';
                }

                thirdButton = thirdButton ? '<button type="button" aria-controls="' + id + '" class="btn btn-tertiary ' + removeClose + ' js-popup-action3">' + thirdButton + '</button>' : thirdButton;

                // removeClose 옵션이 false일 때 닫기 버튼 제거
                var closeButtonHtml = options.removeClose == false ? '' : '<button type="button" aria-controls="' + id + '" class="btn-close-popup  js-close-popup"><span class="sr-text">닫기</span></button>';

                html = '<div id="' + id + '" class="layer-popup"><div role="dialog" aria-labelledby="popupTitle' + index + '" aria-modal="true" class="popup-container">' +
                    '<h4 id="popupTitle' + index + '" class="sr-text">' + title + '</h4>' +
                    closeButtonHtml +
                    '<div class="popup-content">' + content + '</div>' +
                    (firstButton ? '<div class="popup-footer">' + firstButton + secondButton + thirdButton + '</div>' : '') +
                    '</div> </div>';
            }

            $popup.innerHTML = html;
            $popup = $popup.querySelector('.layer-popup');
            document.body.appendChild($popup);
            document.body.classList.add('has-popup');
            $popup.classList.add('in');
            setFocus($popup);

            $popup.querySelectorAll('.js-close-popup').forEach(function (closeBtn) {
                closeBtn.addEventListener('click', function (e) {
                    closePopup($popup);
                }, { once: clickOnce });
            });

            //if (type === 'alert') return;

            if (firstButton && firstCallback && typeof firstCallback === 'function') {
                $popup.querySelector('.js-popup-action1').addEventListener('click', function (e) {
                    setTimeout(function () {
                        firstCallback();
                    }, 100);
                }, { once: clickOnce });
            }

            if (secondButton && secondCallback && typeof secondCallback === 'function') {
                $popup.querySelector('.js-popup-action2').addEventListener('click', function (e) {
                    setTimeout(function () {
                        secondCallback();
                    }, 100);
                }, { once: clickOnce });
            }

            if (thirdButton && thirdCallback && typeof thirdCallback === 'function') {
                $popup.querySelector('.js-popup-action3').addEventListener('click', function (e) {
                    setTimeout(function () {
                        thirdCallback();
                    }, 100);
                }, { once: clickOnce });
            }
        };

    }());

    /* 레이어 패널 */
    (function setTogglePanel() {
        // 스크롤 위치 저장용 변수
        var savedScrollPosition = 0;

        window.openPanel = function ($el) {
            if (!$el) return;

            // 1단계: 스크롤 위치 저장 (패널 닫을 때 원래 위치로 복구하기 위해)
            savedScrollPosition = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;

            // 2단계: 패널 열리는 중 플래그 설정 (스와이프 코드가 transform을 건드리지 않도록)
            $el.isOpening = true;

            // 2-1단계: 컨테이너 참조 저장
            var container = $el.querySelector('.bottom-sheet-container');

            // 3단계: 패널을 보이게 하되 transform은 translateY(100%)로 유지 (높이 측정용)
            // 패널이 DOM에 존재하지만 화면 아래에 있어야 정확한 scrollHeight를 측정할 수 있음
            // 기본 상태가 이미 transform: translateY(100%)이므로 별도 조작 불필요
            // 패널이 보이도록 하되 transform 애니메이션은 아직 시작하지 않음

            // 4단계: 레이아웃 계산 완료 후 정확한 높이 측정 및 고정
            // 세 번의 requestAnimationFrame으로 레이아웃 계산 완료 보장
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        // 이제 정확한 scrollHeight를 측정할 수 있음 (레이아웃 계산 완료 후)
                        if (container) {
                            var fixedHeight = container.scrollHeight;
                            // 높이를 픽셀로 고정 (transform 시작 전에 고정하여 reflow 방지)
                            container.style.height = fixedHeight + 'px';

                            // height 고정이 적용되도록 한 프레임 더 대기
                            requestAnimationFrame(function () {
                                // 이제 transform 애니메이션 시작 (높이가 이미 고정되어 있음)
                                // transition은 CSS에서 이미 선언되어 있으므로 그대로 사용
                                $el.classList.add('on');
                            });
                        } else {
                            // 컨테이너가 없으면 즉시 .on 클래스 추가
                            $el.classList.add('on');
                        }

                        // body에 no-scroll 클래스 추가 (iOS 스크롤 차단)
                        // no-scroll 클래스가 이미 position: fixed, overflow: hidden, width: 100% 등을 담당
                        // body 위치 변경 없이 스크롤만 차단 (바텀시트 위치에 영향 없도록)
                        document.body.classList.add('no-scroll');

                        // panel-open 클래스 추가 (bottom-nav 숨기기)
                        document.body.classList.add('panel-open');

                        // Swift에 패널 열림 알림 (iOS WebView 스크롤 비활성화)
                        if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.panelState) {
                            try {
                                window.webkit.messageHandlers.panelState.postMessage('open');
                            } catch (e) {
                            }
                        }

                        // 포커스 설정
                        setTimeout(function () {
                            $el.focus();
                        }, 100);
                    });
                });
            });

            // 5단계: CSS transform 애니메이션이 완료될 때까지 isOpening 유지
            // CSS transition이 0.35초이므로 애니메이션 완료 후에 isOpening 해제 및 height: auto 복구
            if (container) {
                // transitionend 이벤트로 애니메이션 완료 감지
                var transitionEnded = false;
                var onTransitionEnd = function (e) {
                    // transform 속성의 transition이 끝났을 때만 처리
                    if (!transitionEnded && (e.propertyName === 'transform' || e.propertyName === '-webkit-transform' || e.target === container)) {
                        transitionEnded = true;
                        $el.isOpening = false;
                        // 높이를 auto로 복구 (애니메이션 완료 후)
                        container.style.height = '';
                        // 바텀시트 transform 애니메이션 완료 후 스크롤만 0으로 이동 (body 위치 변경 없이)
                        window.scrollTo(0, 0);
                        container.removeEventListener('transitionend', onTransitionEnd);
                        container.removeEventListener('webkitTransitionEnd', onTransitionEnd);
                    }
                };
                container.addEventListener('transitionend', onTransitionEnd);
                container.addEventListener('webkitTransitionEnd', onTransitionEnd);

                // 안전장치: transitionend가 발생하지 않을 경우를 대비해 400ms 후에도 해제
                setTimeout(function () {
                    if ($el.isOpening && !transitionEnded) {
                        transitionEnded = true;
                        $el.isOpening = false;
                        // 높이를 auto로 복구
                        container.style.height = '';
                        // 바텀시트 transform 애니메이션 완료 후 스크롤만 0으로 이동 (body 위치 변경 없이)
                        window.scrollTo(0, 0);
                        container.removeEventListener('transitionend', onTransitionEnd);
                        container.removeEventListener('webkitTransitionEnd', onTransitionEnd);
                    }
                }, 400);
            } else {
                // 컨테이너가 없으면 기본 타이밍으로 해제
                setTimeout(function () {
                    $el.isOpening = false;
                    // 바텀시트 transform 애니메이션 완료 후 스크롤만 0으로 이동 (body 위치 변경 없이)
                    window.scrollTo(0, 0);
                }, 400);
            }
        };

        window.closePanel = function ($el) {
            if (!$el) return;

            // 컨테이너 높이를 픽셀로 고정 (닫힐 때 튕김 방지 - 핵심)
            var container = $el.querySelector('.bottom-sheet-container');
            if (container) {
                // 닫기 애니메이션 중 height 변경을 방지하기 위해 현재 높이를 픽셀로 고정
                var currentHeight = container.scrollHeight;
                container.style.height = currentHeight + 'px';
            }

            // 패널 닫기 (transform 애니메이션 시작)
            $el.classList.remove('on');

            // 다른 열린 패널이 없을 때만 body 클래스 제거 (중첩 패널 대응)
            var hasOtherOpenPanel = document.querySelector('.layer-panel.on') !== null;
            if (!hasOtherOpenPanel) {
                // body에서 no-scroll 클래스 제거 (position, overflow, width 등 모든 스타일 해제)
                document.body.classList.remove('no-scroll');

                // panel-open 클래스 제거 (bottom-nav 다시 표시)
                document.body.classList.remove('panel-open');
            }

            // 다른 열린 패널이 없을 때만 스크롤 위치 복구
            if (!hasOtherOpenPanel && savedScrollPosition > 0) {
                window.scrollTo(0, savedScrollPosition);
                savedScrollPosition = 0;
            }

            // 다른 열린 패널이 없을 때만 Swift에 패널 닫힘 알림
            if (!hasOtherOpenPanel && window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.panelState) {
                try {
                    window.webkit.messageHandlers.panelState.postMessage('close');
                } catch (e) {
                }
            }

            // transform 애니메이션이 완료된 후 height: auto로 복구 (튕김 방지)
            if (container) {
                var restoreHeight = function (e) {
                    // transform 속성의 transition이 끝났을 때만 처리
                    if (e.propertyName === 'transform' || e.propertyName === '-webkit-transform' || e.target === container) {
                        container.style.height = '';
                        container.removeEventListener('transitionend', restoreHeight);
                        container.removeEventListener('webkitTransitionEnd', restoreHeight);
                    }
                };
                container.addEventListener('transitionend', restoreHeight);
                container.addEventListener('webkitTransitionEnd', restoreHeight);

                // 안전장치: transitionend가 발생하지 않을 경우를 대비해 400ms 후에도 복구
                setTimeout(function () {
                    container.style.height = '';
                    container.removeEventListener('transitionend', restoreHeight);
                    container.removeEventListener('webkitTransitionEnd', restoreHeight);
                }, 400);
            }

            // 폼 초기화 (data-disable-form-reset="true" 패널은 건너뜀)
            if ($el.getAttribute('data-disable-form-reset') !== 'true') {
                $el.querySelectorAll('input, textarea, select').forEach(function (item) {
                    item.value = '';
                });

                $el.querySelectorAll('form').forEach(function (form) {
                    form.reset();
                });
            }

            $el.scrollTo(0, 0);
        };

        window.addEventListener('click', function (e) {
            // e.target이 DOM 요소인지 확인
            if (!e.target || typeof e.target.getAttribute !== 'function') return;

            if (e.target.classList && !e.target.classList.contains('js-toggle-panel') && !(e.target.parentNode && e.target.parentNode.classList && e.target.parentNode.classList.contains('js-toggle-panel'))) return;

            e.preventDefault();

            var id = e.target.getAttribute('aria-controls') || (e.target.parentNode && e.target.parentNode.getAttribute ? e.target.parentNode.getAttribute('aria-controls') : null),
                $controlTarget = id ? this.document.getElementById(id) : null;

            if (!$controlTarget) return false;

            e.target.classList.toggle('on');

            if (!$controlTarget.classList.contains('on')) {
                openPanel($controlTarget);
            } else {
                closePanel($controlTarget);
            }
        });

        // 배경 클릭 시 레이어 패널 닫기
        window.addEventListener('click', function (e) {
            // 열려있는 레이어 패널 찾기
            var $openPanel = document.querySelector('.layer-panel.on');
            if (!$openPanel) return;
            if ($openPanel.getAttribute('data-disable-bg-close') === 'true') return;

            // 클릭한 요소가 패널 컨테이너 내부인지 확인 (기존 패널 및 바텀시트 모두 체크)
            var $panelContainer = $openPanel.querySelector('.panel-container');
            var $bottomSheetContainer = $openPanel.querySelector('.bottom-sheet-container');

            // 패널 내부 또는 바텀시트 내부를 클릭한 경우 닫지 않음
            if (($panelContainer && $panelContainer.contains(e.target)) ||
                ($bottomSheetContainer && $bottomSheetContainer.contains(e.target))) {
                return;
            }

            // 배경(레이어 패널 자체)을 클릭한 경우 팝업 닫기
            if ($openPanel === e.target || $openPanel.contains(e.target)) {
                closePanel($openPanel);
            }
        });

        // 레이어 패널 배경 클릭 이벤트 직접 처리 (stopPropagation 우회)
        // 모든 레이어 패널에 배경 클릭 이벤트 추가
        function initPanelBackgroundClick() {
            document.querySelectorAll('.layer-panel').forEach(function ($panel) {
                // 이미 이벤트가 추가되었는지 확인
                if ($panel.hasAttribute('data-bg-click-handler')) return;
                $panel.setAttribute('data-bg-click-handler', 'true');

                $panel.addEventListener('click', function (e) {
                    // 레이어 패널이 열려있지 않으면 무시
                    if (!$panel.classList.contains('on')) return;
                    if ($panel.getAttribute('data-disable-bg-close') === 'true') return;

                    // 클릭한 요소가 컨테이너 내부인지 확인
                    var $panelContainer = $panel.querySelector('.panel-container');
                    var $bottomSheetContainer = $panel.querySelector('.bottom-sheet-container');

                    // 컨테이너 내부를 클릭한 경우 닫지 않음
                    if (($panelContainer && $panelContainer.contains(e.target)) ||
                        ($bottomSheetContainer && $bottomSheetContainer.contains(e.target))) {
                        return;
                    }

                    // 레이어 패널 자체 또는 배경 영역을 클릭한 경우 닫기
                    // e.target이 레이어 패널 자체이거나, 컨테이너 외부인 경우
                    if ($panel === e.target ||
                        ($panel.contains(e.target) &&
                            !($panelContainer && $panelContainer.contains(e.target)) &&
                            !($bottomSheetContainer && $bottomSheetContainer.contains(e.target)))) {
                        closePanel($panel);
                    }
                });
            });
        }

        // DOMContentLoaded 또는 즉시 실행
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPanelBackgroundClick);
        } else {
            initPanelBackgroundClick();
        }

        // 동적으로 추가되는 레이어 패널을 위해 주기적으로 체크
        setTimeout(initPanelBackgroundClick, 100);

        document.querySelectorAll('.layer-pannel').forEach(function (elm) {
            var $focusableEl = elm.querySelectorAll('a, button, input, select, textarea');
            var $firstEl = elm.querySelector('[autofocus]') || $focusableEl[0];
            var $lastEl = $focusableEl[$focusableEl.length - 1];

            elm.addEventListener('keydown', function (ev) {
                var keycode = ev.keycode || ev.which;

                switch (keycode) {
                    case 27: // esc key
                        if (elm.getAttribute('data-disable-esc-close') === 'true') {
                            break;
                        }
                        closePanel(elm);
                        break;
                    case 9: // tab key
                        if (ev.target === $lastEl && !ev.shiftKey) {
                            ev.preventDefault();
                            $firstEl.focus();
                        }
                        if (ev.target === $firstEl && ev.shiftKey) {
                            ev.preventDefault();
                            $lastEl.focus();
                        }
                        break;
                    default:
                        break;
                }
            });
        });

    }());

    /* Tooltip */
    (function setTooltip() {

        function show(el) {
            // el이 유효한 DOM 요소인지 확인
            if (!el || !el.parentNode || typeof el.parentNode.querySelector !== 'function') return;

            var $tooltip = el.parentNode.querySelector('.tooltip');
            var offset = 27,
                xpos = el.offsetLeft + (el.offsetWidth / 2) - offset,
                ypos = el.offsetTop + el.offsetHeight;

            if (!$tooltip) return;

            if (!$tooltip.classList.contains('right') && xpos + $tooltip.offsetWidth > window.innerWidth) {
                $tooltip.classList.remove('center', 'left');
                $tooltip.classList.add('right');
                xpos = xpos - $tooltip.offsetWidth + (offset * 2);
            } else if ($tooltip.classList.contains('center')) {
                xpos = el.offsetLeft - ($tooltip.offsetWidth / 2) + (el.offsetWidth / 2);
            } else if ($tooltip.classList.contains('right')) {
                xpos = xpos - $tooltip.offsetWidth + (offset * 2);
            }

            if (!$tooltip.classList.contains('bottom') && $tooltip.offsetHeight + ypos < document.body.scrollHeight) {
                $tooltip.classList.remove('bottom');
            } else {
                ypos = ypos - $tooltip.offsetHeight - el.offsetHeight;
                $tooltip.classList.add('bottom');
            }

            if (!$tooltip.classList.contains('on')) {
                $tooltip.style.top = ypos + 'px';
                $tooltip.style.left = xpos + 'px';
            }

            setTimeout(function () {
                $tooltip.classList.toggle('on');
            }, 1);
        }

        ['click', 'mouseover'].forEach(function (etype) {
            window.addEventListener(etype, function (e) {
                if (e.target.classList && !e.target.classList.contains('js-toggle-tooltip')) {
                    if (document.querySelector('.tooltip.on')) {
                        document.querySelector('.tooltip.on').classList.remove('on');
                    }

                    return;
                }
                e.preventDefault();
                show(e.target);
            });
        });

        window.addEventListener('resize', function () {
            document.querySelectorAll('.tooltip.on').forEach(function (item) {
                item.classList.remove('on');
            });
        });
    }());

    /* 폼파일 이미지 미리보기 */
    (function setFormFilePreview() {
        function previewPic(input) {
            var fileReader = new FileReader();
            var preview = input.parentNode.querySelector('.preview');
            var img = document.createElement('IMG');

            if (input.files && input.files[0]) {
                preview.innerHTML = '';
                preview.appendChild(img);

                fileReader.onload = function () {
                    img.src = fileReader.result;
                };

                fileReader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '';
            }
        }

        document.querySelectorAll('.form-pic .js-file-preview').forEach(function (input) {
            input.addEventListener('change', function (e) {
                previewPic(e.target);
            });
        });
    }());

    /* 폼파일 파일명 표시 */
    (function setFormFile() {
        function showFilename(container, input) {
            var filename = container.querySelector('.file-name');
            if (input.files && input.files[0]) {
                filename.value = input.files[0].name;
            } else {
                filename.value = '';
            }
        }

        document.querySelectorAll('.js-show-filename').forEach(function (input) {
            input.addEventListener('change', function (e) {
                showFilename(this, e.target);
            });
        });
    }());

    /* Custom input Range Slider */
    (function setRangeSlider() {
        if (!document.querySelector('.js-range-slider')) return;

        function chgValue(slider, input) {
            var fill = slider.querySelector('.fill'),
                minEl = slider.querySelector('.min input'),
                maxEl = slider.querySelector('.max input'),
                parent = input.parentNode,
                label = parent.querySelector('.label'),
                thumb = parent.querySelector('.thumb'),
                unit = input.getAttribute('data-unit'),
                step = parseInt(input.getAttribute('step')),
                rangeMin = parseInt(input.getAttribute('min')),
                rangeMax = parseInt(input.getAttribute('max')) - rangeMin,
                curVal = parseInt(input.value),
                total = input.offsetWidth - thumb.offsetWidth + 4;

            if (minEl && parseInt(maxEl.value) <= parseInt(minEl.value)) {
                minEl.value = parseInt(maxEl.value) - step;
            }

            if (minEl && parseInt(minEl.value) >= parseInt(maxEl.value)) {
                maxEl.value = parseInt(minEl.value) + step;
            }

            if (curVal !== parseInt(input.value)) {
                curVal = parseInt(input.value);
            }

            if (curVal == 0) {
                label.style.opacity = 0;
            } else {
                label.style.opacity = '';
            }

            if (minEl) {
                fill.style.clipPath = 'inset(0px ' + (100 - ((parseInt(maxEl.value) / rangeMax * 100) - (rangeMin / rangeMax * 100))) + '% 0px ' + ((parseInt(minEl.value) / rangeMax * 100) - (rangeMin / rangeMax * 100)) + '%)';
            } else {
                fill.style.clipPath = 'inset(0px ' + (100 - ((parseInt(maxEl.value) / rangeMax * 100) - (rangeMin / rangeMax * 100))) + '% 0px 0px)';
            }

            thumb.style.transform = 'translateX(' + total * ((curVal / rangeMax) - (rangeMin / rangeMax)) + 'px)';
            label.innerHTML = curVal + unit;
        }

        document.querySelectorAll('.js-range-slider input[type="range"]').forEach(function (input) {
            var slider = input.closest('.js-range-slider');

            setTimeout(function () {
                input.addEventListener('input', function (e) {
                    chgValue(slider, this);
                });

                window.addEventListener('resize', function () {
                    chgValue(input.closest('.js-range-slider'), input);
                });

                chgValue(slider, input);
            }, 1);
        });

    }());


    /*!Keen-slider : https://keen-slider.io */
    (function initKeenSlider() {
        /* Keen Slider dots plugin */
        window.sliderDots = function (slider) {
            var container, dots;

            function markup(remove) { dotMarkup(remove); }
            function removeElement(elment) { elment.parentNode.removeChild(elment); }

            function createDiv(className) {
                var div = document.createElement("div");
                var classNames = className.split(" ");
                classNames.forEach(function (name) { div.classList.add(name); });
                return div;
            }

            function createButton(className) {
                var div = document.createElement("Button");
                var classNames = className.split(" ");
                classNames.forEach(function (name) { div.classList.add(name); });
                return div;
            }

            function dotMarkup(remove) {
                if (remove) {
                    removeElement(dots);
                    return;
                }

                container = slider.container;
                dots = createDiv("dots");

                slider.track.details.slides.forEach(function (_e, idx) {
                    var dot = createButton("dot");
                    dot.innerHTML = '<span>' + (idx + 1) + '</span>';
                    dot.addEventListener("click", function () {
                        slider.moveToIdx(idx);
                    });
                    dots.appendChild(dot);
                });

                container.parentNode.insertBefore(dots, container.nextSibling);
            }

            function updateClasses() {
                if (!slider.track || !slider.track.details || !dots) return;
                var slide = slider.track.details.rel;
                Array.from(dots.children).forEach(function (dot, idx) {
                    idx === slide ?
                        dot.classList.add("dot--active") :
                        dot.classList.remove("dot--active");
                });
            }

            slider.on("created", function () {
                markup();
                updateClasses();
            });
            slider.on("optionsChanged", function () {
                markup(true);
                markup();
                updateClasses();
            });
            slider.on("slideChanged", function () { updateClasses(); });
            slider.on("destroyed", function () { markup(true); });
        };

        /* Keen Slider Arrow plugin */
        window.sliderArrows = function (slider) {
            var container, arrowLeft, arrowRight;

            function markup(remove) { arrowMarkup(remove); }
            function removeElement(elment) { elment.parentNode.removeChild(elment); }

            function createButton(className) {
                var div = document.createElement("Button");
                var classNames = className.split(" ");
                classNames.forEach(function (name) { div.classList.add(name); });
                return div;
            }

            function arrowMarkup(remove) {
                if (remove) {
                    removeElement(arrowLeft);
                    removeElement(arrowRight);
                    return;
                }
                container = slider.container;
                arrowLeft = createButton("arrow arrow--left");
                arrowLeft.innerHTML = '<span>Previous</span>';
                arrowLeft.addEventListener("click", function () { slider.prev(); });
                arrowRight = createButton("arrow arrow--right");
                arrowRight.innerHTML = '<span>Next</span>';
                arrowRight.addEventListener("click", function () { slider.next(); });
                container.parentNode.insertBefore(arrowLeft, container);
                container.parentNode.insertBefore(arrowRight, container.nextSibling);
            }

            function updateClasses() {
                if (!slider.track || !slider.track.details || !arrowLeft || !arrowRight) return;
                var slide = slider.track.details.rel;
                var perView = 1;
                if (slider.options && slider.options.slides && slider.options.slides.perView) {
                    perView = slider.options.slides.perView === "auto" ? 1 : slider.options.slides.perView;
                }
                slide === 0 ?
                    arrowLeft.classList.add("arrow--disabled") :
                    arrowLeft.classList.remove("arrow--disabled");
                slide === slider.track.details.slides.length - perView ?
                    arrowRight.classList.add("arrow--disabled") :
                    arrowRight.classList.remove("arrow--disabled");
            }

            slider.on("created", function () {
                markup();
                updateClasses();
            });
            slider.on("optionsChanged", function () {
                // console.log(2);
                markup(true);
                markup();
                updateClasses();
            });
            slider.on("slideChanged", function () { updateClasses(); });
            slider.on("destroyed", function () { markup(true); });
        };

        window.sliderAutoplay = function (slider, timer, hoverPause) {
            var timeout;
            var mouseOver = false;

            if (slider.slides.length == 1) return;

            function clearNextTimeout() { clearTimeout(timeout); }
            function nextTimeout() {
                clearTimeout(timeout);
                if (mouseOver && hoverPause) return;
                timeout = setTimeout(function () {
                    if (!slider.options.loop && slider.track.details.rel == slider.slides.length - 1) {
                        slider.moveToIdx(0);
                    } else {
                        slider.next();
                    }
                }, timer);
            }
            slider.on("created", function () {
                slider.container.addEventListener("mouseover", function () {
                    if (!hoverPause) return;
                    mouseOver = true;
                    clearNextTimeout();
                });
                slider.container.addEventListener("mouseout", function () {
                    if (!slider || !hoverPause) return;
                    mouseOver = false;
                    nextTimeout();
                });
                nextTimeout();
            });

            slider.on("dragStarted", clearNextTimeout);
            slider.on("animationEnded", nextTimeout);
            slider.on("updated", clearNextTimeout);
            slider.on("destroyed", function () {
                clearNextTimeout();
                if (slider.container.parentNode !== undefined) {
                    slider.container.parentNode.replaceChild(slider.container.cloneNode(true), slider.container);
                }
            });
        };

        window.addActiveClass = function (slider) {
            function removeActive() {
                slider.slides.forEach(function (slide) {
                    slide.classList.remove("active");
                });
            }

            function addActive(idx) {
                removeActive();
                slider.slides[idx].classList.add("active");
            }

            slider.on("created", function () { addActive(slider.track.details.rel); });
            slider.on("slideChanged", function () { addActive(slider.track.details.rel); });
        };
    }());

    /* Toggle Bookmark */
    (function setBtnBookmark() {
        function toggle(btn) {
            if (btn.classList.contains('on')) {
                btn.children[0].textContent = btn.textContent.replace('해제', '추가');
            } else {
                btn.children[0].textContent = btn.textContent.replace('추가', '해제');
            }

            btn.classList.toggle('on');
        }

        window.addEventListener('click', function (e) {
            if (e.target.classList && e.target.classList.contains('js-toggle-bookmark')) {
                toggle(e.target);
            }
        });
    }());

    /* 탭목록 init */
    (function setTabList() {
        var tabContainer = document.querySelectorAll('.tab-container');
        var maxWidth = window.innerWidth;
        var offset = 30;

        tabContainer.forEach(function (con) {
            var activeItem = con.querySelector('.on');

            con.scrollTo(0, 0);

            if (activeItem && maxWidth < activeItem.offsetLeft + activeItem.offsetWidth) {
                setTimeout(function () {
                    con.scrollTo(activeItem.offsetLeft - offset, 0);
                }, 1);
            }
        });
    }());

    /* Detect touchEvent */
    (function detectTouchEvent() {
        var flag = false;
        var target = null;
        var direction = null;
        var startX, startY;

        function deleteBefore() {
            target.parentNode.classList.remove('is-swipe-left', 'is-swipe-right', 'is-swipe-down', 'is-swipe-up');
        }

        function onStart(e) {
            if (!e.target.classList || !e.target.classList.contains('js-detect-touch')) {

                if (target) {
                    deleteBefore();
                }

                return;
            }

            e.preventDefault();

            if (target) {
                deleteBefore();
            }

            direction = null;
            startX = e.changedTouches[0].clientX;
            startY = e.changedTouches[0].clientY;
            target = e.target;
            flag = true;

            target.addEventListener('touchend', onEnd, { once: true });
        }

        function onEnd(e) {
            if (!flag) return;

            var curX = e.changedTouches[0].clientX, curY = e.changedTouches[0].clientY;
            var touchStyle = window.getComputedStyle(target).getPropertyValue('touch-action');

            if (Math.abs(startX - curX) > 20 && touchStyle !== 'pan-y') {
                if (startX > curX) {
                    direction = 'left';
                } else {
                    direction = 'right';
                }
            }

            if (Math.abs(startY - curY) > 20 && touchStyle !== 'pan-x') {
                if (startY > curY) {
                    direction = 'up';
                } else {
                    direction = 'down';
                }
            }

            if (direction) {
                target.parentNode.classList.add('is-swipe-' + direction);
            }

            flag = false;
        }

        window.addEventListener('touchstart', function (e) {
            setTimeout(function () {
                onStart(e);
            }, 100);
        });

    }());

    /* Delete Item */
    (function deleteItem() {
        window.addEventListener('click', function (e) {
            if (e.target.classList && e.target.classList.contains('js-del-item')) {
                var item = e.target.parentNode;
                item.style.height = item.offsetHeight + 'px';
                item.classList.add('is-deleted');
                item.addEventListener('animationend', function () {
                    this.remove();
                });
            }
        });
    }());

    /* Accordion List Toggle */
    (function setAccordionList() {
        var currentEl = null;

        function toggle(el) {
            var content = el.parentNode.querySelector('.accordion-content');
            var isOpen = content.hidden;

            if (!content) return;

            if (isOpen) {
                content.hidden = false;
                el.setAttribute('aria-expanded', 'true');
                currentEl = el;
            } else {
                content.hidden = true;
                el.setAttribute('aria-expanded', 'false');
                currentEl = null;
            }
        }

        window.addEventListener('click', function (e) {
            if (e.target.classList && e.target.classList.contains('js-toggle-accordion')) {
                e.preventDefault();

                if (currentEl && currentEl !== e.target) {
                    toggle(currentEl);
                }

                toggle(e.target);
            }
        });
    }());

    /* AutoHeight TextArea */
    (function autoHeightTextarea() {
        var scrollFollowTimer = null;
        var textareaHeights = new WeakMap(); // 각 textarea의 이전 높이 저장

        // scroll-margin-top 설정: .top-bar 높이 + 여유 여백(12px)
        function setupScrollMarginTop() {
            var topBar = document.querySelector('.top-bar');
            var topBarHeight = topBar ? topBar.offsetHeight : 0;
            var scrollMargin = topBarHeight + 12;

            document.querySelectorAll('.js-autoheight').forEach(function (el) {
                el.style.scrollMarginTop = scrollMargin + 'px';
            });
        }

        // 화면에서 실제로 보이는 하단 좌표 계산
        // 고정 푸터(.view-footer, position:fixed)와 소프트 키보드(visualViewport)에 가려지는 영역을 제외한다
        function getVisibleBottom(page, pageRect) {
            var bottom = pageRect.bottom;

            // 푸터는 fixed뿐 아니라 sticky(.view-footer 공통 기본값)일 때도 화면 하단을 가리므로 position으로 거르지 않는다
            // 스크롤 중이 아닌 위치의 푸터는 top이 bottom보다 커서 자동으로 제외된다
            document.querySelectorAll('.sticky-bottom.view-footer').forEach(function (footer) {
                var footerRect = footer.getBoundingClientRect();
                if (footerRect.height > 0 && footerRect.top < bottom) {
                    bottom = footerRect.top;
                }
            });

            // 키보드가 열리면 visualViewport 하단이 실제 보이는 영역의 끝 (iOS 등 layout viewport 유지 환경)
            if (window.visualViewport) {
                var viewportBottom = window.visualViewport.offsetTop + window.visualViewport.height;
                if (viewportBottom < bottom) {
                    bottom = viewportBottom;
                }
            }

            return bottom;
        }

        // contenteditable 전용: 현재 캐럿(선택 영역)의 화면 좌표를 반환한다
        // textarea이거나 캐럿을 구할 수 없으면 null을 반환해 호출부가 요소 전체 좌표를 쓰도록 한다
        function getCaretRect(el) {
            if (!el.isContentEditable || !window.getSelection) return null;
            var sel = window.getSelection();
            if (!sel || sel.rangeCount === 0) return null;
            var range = sel.getRangeAt(0);
            if (!el.contains(range.startContainer)) return null;

            // 줄바꿈이 있는 range는 마지막 rect가 캐럿이 있는 줄이다
            var rects = range.getClientRects();
            var rect = rects.length ? rects[rects.length - 1] : range.getBoundingClientRect();
            if (rect && rect.height > 0) return rect;

            // 줄 끝/<br> 직후처럼 rect 높이가 0이면 직전 노드 기준으로 한 줄 높이를 추정한다
            // DOM을 건드리는 마커는 쓰지 않는다 (normalizeEditorDom과 충돌 방지)
            var style = window.getComputedStyle(el);
            var lineH = parseFloat(style.lineHeight) || (parseFloat(style.fontSize) * 1.5);
            var prev = range.startContainer.nodeType === 1 ? range.startContainer.childNodes[range.startOffset - 1] : null;
            var base = (prev && prev.getBoundingClientRect) ? prev.getBoundingClientRect() : el.getBoundingClientRect();
            var top = base.height > 0 ? base.bottom : base.top;
            return { top: top, bottom: top + lineH, left: base.left, right: base.right, height: lineH };
        }

        // 고정 푸터/키보드에 textarea 또는 contenteditable 캐럿이 가려졌을 때만 .page를 스크롤해서 화면에 보이게 한다
        // textarea는 요소 전체 기준, contenteditable은 캐럿 기준으로 판정한다
        // blockValue: 'start' (포커스 시) 또는 'end' (입력으로 높이가 늘어난 경우)
        function scrollTextareaIntoView(el, blockValue) {
            if (!el || !el.classList || !el.classList.contains('js-autoheight')) return;
            blockValue = blockValue || 'start';
            if (scrollFollowTimer) clearTimeout(scrollFollowTimer);
            scrollFollowTimer = setTimeout(function () {
                var page = el.closest('.page');
                if (!page) return;

                var pageRect = page.getBoundingClientRect();
                // contenteditable은 캐럿 줄 기준, textarea는 요소 전체 기준
                var caretRect = getCaretRect(el);
                var rect = caretRect || el.getBoundingClientRect();
                var gap = 12; // 가림 판정 여유 (px)

                // 보이는 영역 상단: .top-bar 하단 + 여유 / 하단: 푸터·키보드 위 + 여유
                var visibleTop = pageRect.top;
                var topBar = document.querySelector('.top-bar');
                if (topBar) {
                    visibleTop = Math.max(visibleTop, topBar.getBoundingClientRect().bottom + gap);
                }
                var visibleBottom = getVisibleBottom(page, pageRect) - gap;

                var delta = 0;
                if (rect.height > visibleBottom - visibleTop) {
                    // textarea가 보이는 영역보다 크면: 상단이 가려졌을 때 상단 기준, 입력 중이면 하단 기준
                    if (rect.top < visibleTop) {
                        delta = rect.top - visibleTop;
                    } else if (blockValue === 'end' && rect.bottom > visibleBottom) {
                        delta = rect.bottom - visibleBottom;
                    }
                } else {
                    // 작으면 하단 또는 상단 중 가려진 쪽만 맞춘다
                    if (rect.bottom > visibleBottom) {
                        delta = rect.bottom - visibleBottom;
                    } else if (rect.top < visibleTop) {
                        delta = rect.top - visibleTop;
                    }
                }

                // contenteditable 캐럿 모드에서는 상세업무 래퍼(.description-field-wrapper)의 하단도 푸터 위에 있어야 한다
                // 캐럿을 위로 올리는 보정(delta < 0)은 건드리지 않고, 아래로 내려야 할 때만 더 큰 값을 쓴다
                if (caretRect && delta >= 0) {
                    var wrapper = el.closest('.description-field-wrapper');
                    if (wrapper) {
                        var wrapperExtra = wrapper.getBoundingClientRect().bottom - visibleBottom;
                        if (wrapperExtra > delta) delta = wrapperExtra;
                    }
                }

                // 가려지지 않았으면 스크롤하지 않음 (불필요한 점프 방지)
                if (delta === 0) return;

                // 스크롤 범위 제한 (0 ~ 최대 스크롤)
                var newScrollTop = page.scrollTop + delta;
                newScrollTop = Math.max(0, Math.min(newScrollTop, page.scrollHeight - page.clientHeight));

                // .page는 CSS에서 scroll-behavior: smooth라 대입이 애니메이션으로 적용되어 즉시 반영되지 않는다
                // 보정 스크롤만 instant로 바꿔 적용하고, 사용자 스크롤 동작은 그대로 둔다
                page.style.scrollBehavior = 'auto';
                page.scrollTop = newScrollTop;
                page.style.scrollBehavior = '';
            }, 120);
        }

        // 높이 자동 조절
        ['input', 'keydown'].forEach(function (evt) {
            window.addEventListener(evt, function (e) {
                var target = e.target;
                if (target.classList && target.classList.contains('js-autoheight')) {
                    var prevHeight = textareaHeights.get(target) || target.offsetHeight;
                    target.style.height = '1px';
                    target.style.height = (target.scrollHeight + 8) + 'px';
                    var newHeight = target.offsetHeight;

                    // 높이 변화에 따라 block 값 결정: 증가='end', 감소='start'
                    var blockValue = (newHeight > prevHeight) ? 'end' : 'start';
                    textareaHeights.set(target, newHeight);

                    scrollTextareaIntoView(target, blockValue);
                }
            });
        });

        // 포커스 시: 임시 여백 추가 + 캐럿 위치 보정
        window.addEventListener('focusin', function (e) {
            if (e.target && e.target.classList && e.target.classList.contains('js-autoheight')) {
                // .page-content에 임시 하단 여백 추가 (스크롤 여유 확보)
                var pageContent = e.target.closest('.page-content');
                if (pageContent) {
                    pageContent.style.paddingBottom = '50vh';
                }
                scrollTextareaIntoView(e.target, 'start');
                // WebView가 키보드 표시 후 포커스 요소를 네이티브로 한 번 더 스크롤해 보정이 되돌아가는 경우가 있어
                // 키보드가 안정된 뒤 같은 판정을 다시 수행한다 (가려지지 않았으면 delta 0이라 스크롤하지 않음)
                var settledTarget = e.target;
                [400, 800].forEach(function (ms) {
                    setTimeout(function () {
                        if (document.activeElement !== settledTarget) return;
                        scrollTextareaIntoView(settledTarget, 'start');
                    }, ms);
                });
            }
        });

        // 포커스 아웃 시: 임시 여백 제거
        window.addEventListener('focusout', function (e) {
            if (e.target && e.target.classList && e.target.classList.contains('js-autoheight')) {
                var pageContent = e.target.closest('.page-content');
                if (pageContent) {
                    pageContent.style.paddingBottom = '';
                }
            }
        });

        // 키보드 열림/닫힘으로 뷰포트가 바뀌면 포커스된 textarea를 다시 판정
        // visualViewport(iOS)와 window resize(Android adjustResize)를 모두 처리
        function onViewportResize(e) {
            var active = document.activeElement;
            if (active && active.classList && active.classList.contains('js-autoheight')) {
                scrollTextareaIntoView(active, 'start');
            }
        }
        if (window.visualViewport) {
            window.visualViewport.addEventListener('resize', onViewportResize);
        }
        window.addEventListener('resize', onViewportResize);

        // contenteditable에서 캐럿만 움직인 경우(탭, 화살표 키 등)도 캐럿 기준으로 다시 판정
        // 입력 이벤트가 없으므로 selectionchange를 쓰며, 포커스된 contenteditable일 때만 동작한다
        document.addEventListener('selectionchange', function () {
            var active = document.activeElement;
            if (active && active.isContentEditable && active.classList && active.classList.contains('js-autoheight')) {
                scrollTextareaIntoView(active, 'start');
            }
        });

        // 초기 높이 설정 및 scroll-margin-top 설정
        document.querySelectorAll('.js-autoheight').forEach(function (el) {
            if (el.scrollHeight) {
                el.style.height = (el.scrollHeight + 8) + 'px';
            }
            textareaHeights.set(el, el.offsetHeight);
        });
        setupScrollMarginTop();

    }());

    /* Custom Selectbox */
    window.initSelectbox = function (el) {
        var selectbox = el.querySelector('select'),
            liner = el.querySelector('.liner');

        if (!selectbox) return;//20221107 수정

        function getValue(el) {
            var value;

            el.setAttribute('autocompleted', 'off');

            Array.prototype.forEach.call(el.children, (function (option) {
                if (option.selected) {
                    value = option.textContent;
                }
            }));

            if (!value) {
                value = el.children[0].textContent;
            }

            return value;
        }

        selectbox.addEventListener('change', function () {
            liner.innerHTML = getValue(this);
        });

        liner.innerHTML = getValue(selectbox);
    };

    (function setSelectbox() {
        if (!document.querySelector('.form-selectbox')) return;

        document.querySelectorAll('.form-selectbox').forEach(function (el) {
            initSelectbox(el);
        });
    }());

    // 공통: 채팅 제목 말줄임(18자)
    if (!window.truncateRcTitle) {
        window.truncateRcTitle = function (text) {
            if (typeof text !== 'string') return '';
            var chars = Array.from(text);
            var maxLength = 18;
            return (chars.length > maxLength) ? chars.slice(0, maxLength).join('') + '...' : text;
        };
    }

    // 바텀시트 Handlebar Swipe 기능 초기화 (공통 함수)
    // 카카오톡/네이버지도 방식의 부드러운 바텀시트 모션
    window.initPanelSwipe = function(panelId) {
        var panel = document.getElementById(panelId);
        if (!panel) return;

        var container = panel.querySelector('.bottom-sheet-container');
        var handlebar = panel.querySelector('.bottom-sheet-handlebar');
        if (!container || !handlebar) return;

        var startY = 0;
        var currentY = 0;
        var dragging = false;
        var SWIPE_CLOSE_DISTANCE = 120;

        handlebar.addEventListener('touchstart', function(e) {
            if (panel.isOpening) return;
            if (e.touches.length === 0) return;

            startY = e.touches[0].clientY;
            dragging = true;
            container.style.transition = 'none';
            e.stopPropagation();
        }, { passive: false });

        handlebar.addEventListener('touchmove', function(e) {
            if (panel.isOpening) {
                dragging = false;
                return;
            }
            if (!dragging || e.touches.length === 0) return;

            currentY = e.touches[0].clientY;
            var deltaY = currentY - startY;

            if (deltaY > 0) {
                container.style.transform = 'translateY(' + deltaY + 'px)';
                e.preventDefault();
                e.stopPropagation();
            }
        }, { passive: false });

        handlebar.addEventListener('touchend', function(e) {
            if (!dragging) return;

            var deltaY = currentY - startY;
            dragging = false;
            e.stopPropagation();

            if (deltaY > SWIPE_CLOSE_DISTANCE) {
                container.style.transition = 'transform 0.25s ease';
                container.style.transform = '';

                if (typeof closePanel === 'function') {
                    closePanel(panel);
                } else {
                    panel.classList.remove('on');
                    document.body.style.overflow = 'auto';
                }
            } else {
                container.style.transition = 'transform 0.25s ease';
                container.style.transform = 'translateY(0)';
            }

            startY = 0;
            currentY = 0;
        }, { passive: false });

        var mouseMoveHandler = function(e) {
            if (panel.isOpening) {
                dragging = false;
                return;
            }
            if (!dragging) return;
            currentY = e.clientY;
            var deltaY = currentY - startY;

            if (deltaY > 0) {
                container.style.transform = 'translateY(' + deltaY + 'px)';
                e.preventDefault();
            }
        };

        var mouseUpHandler = function() {
            if (!dragging) return;

            var deltaY = currentY - startY;
            dragging = false;

            if (deltaY > SWIPE_CLOSE_DISTANCE) {
                container.style.transition = 'transform 0.25s ease';
                container.style.transform = '';

                if (typeof closePanel === 'function') {
                    closePanel(panel);
                } else {
                    panel.classList.remove('on');
                    document.body.style.overflow = 'auto';
                }
            } else {
                container.style.transition = 'transform 0.25s ease';
                container.style.transform = 'translateY(0)';
            }

            startY = 0;
            currentY = 0;

            document.removeEventListener('mousemove', mouseMoveHandler);
            document.removeEventListener('mouseup', mouseUpHandler);
        };

        handlebar.addEventListener('mousedown', function(e) {
            if (panel.isOpening) return;

            startY = e.clientY;
            dragging = true;
            container.style.transition = 'none';
            e.preventDefault();

            document.addEventListener('mousemove', mouseMoveHandler);
            document.addEventListener('mouseup', mouseUpHandler);
        });
    };

}());
