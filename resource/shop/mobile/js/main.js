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

        // 우리 코드가 마지막으로 설정한 scrollTop
        // scroll 이벤트는 대입 뒤 비동기로 오므로, 이 값과 같으면 사용자 스크롤이 아닌 보정 스크롤로 구분한다
        var programmaticScrollTop = null;
        var textareaFocusAt = 0; // 입력란에 포커스가 생긴 시각 (키보드 전환 중 판정을 막는 데 사용)


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

        // contenteditable 전용: 현재 캐럿(선택 영역)의 실제 화면 좌표를 반환한다
        // 빈 에디터, <br> 직후 빈 줄처럼 글자 상자가 없어 좌표가 0이면 null을 반환한다 (추정하지 않음)
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

            // <br> 뒤 빈 줄처럼 캐럿 자체의 rect가 0이면, 캐럿 뒤에 있는 첫 글자의 위치를 빌려 쓴다
            return getNextCharRect(el, range);
        }

        // 캐럿 뒤에 있는 첫 글자(공백 아님)의 화면 좌표를 반환한다. 없으면 null
        // 글 중간의 빈 줄에서 캐럿 좌표를 구할 때 사용한다 (글 끝은 getEndCaretRect가 처리)
        function getNextCharRect(el, range) {
            var after = document.createRange();
            after.setStart(range.startContainer, range.startOffset);
            after.setEnd(el, el.childNodes.length);

            var walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT, null, false);
            var textNode;
            while ((textNode = walker.nextNode())) {
                if (!/\S/.test(textNode.nodeValue) || !after.intersectsNode(textNode)) continue;

                // 캐럿이 이 텍스트 노드 안에 있으면 캐럿 오프셋부터 글자를 고른다
                var idx = (textNode === range.startContainer) ? range.startOffset : 0;
                while (idx < textNode.nodeValue.length && !/\S/.test(textNode.nodeValue.charAt(idx))) idx++;
                if (idx >= textNode.nodeValue.length) continue;

                var charRange = document.createRange();
                charRange.setStart(textNode, idx);
                charRange.setEnd(textNode, idx + 1);
                var charRect = charRange.getBoundingClientRect();
                if (charRect && charRect.height > 0) return charRect;
            }
            return null;
        }

        // 캐럿 실제 좌표를 못 구할 때 쓰는 보조 좌표 (글 맨 끝에서 입력하는 경우에만)
        // 캐럿이 글 중간이면 null을 반환해 호출부가 움직이지 않도록 한다
        function getEndCaretRect(el) {
            var sel = window.getSelection();
            if (!sel || sel.rangeCount === 0 || !sel.isCollapsed) return null;
            var range = sel.getRangeAt(0);
            if (!el.contains(range.startContainer)) return null;

            // 캐럿 뒤에 공백이 아닌 글자가 있으면 글 중간이므로 추정하지 않는다
            // (빈 에디터에 섞인 공백 텍스트 노드는 빈 것으로 본다)
            var after = document.createRange();
            after.setStart(range.endContainer, range.endOffset);
            after.setEnd(el, el.childNodes.length);
            if (/\S/.test(after.cloneContents().textContent || '')) return null;

            var style = window.getComputedStyle(el);
            var lineH = parseFloat(style.lineHeight) || (parseFloat(style.fontSize) * 1.5);
            var elRect = el.getBoundingClientRect();
            // 내용 영역의 맨 윗줄 위치 (테두리와 위쪽 여백 이후)
            var contentTop = elRect.top + el.clientTop + (parseFloat(style.paddingTop) || 0);

            // 공백이 아닌 글자 중 가장 아래에 있는 줄의 아래쪽과 여부를 구한다
            var hasText = false;
            var lastTextBottom = contentTop;
            var walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT, null, false);
            var textNode;
            while ((textNode = walker.nextNode())) {
                if (!/\S/.test(textNode.nodeValue)) continue;
                var textRange = document.createRange();
                textRange.selectNodeContents(textNode);
                var textRects = textRange.getClientRects();
                for (var i = 0; i < textRects.length; i++) {
                    if (textRects[i].height > 0 && textRects[i].bottom > lastTextBottom) lastTextBottom = textRects[i].bottom;
                }
                hasText = true;
            }

            // 글 끝에 이어진 <br> 개수 (사이의 공백 텍스트는 무시)
            var brCount = 0;
            for (var n = el.childNodes.length - 1; n >= 0; n--) {
                var child = el.childNodes[n];
                if (child.nodeType === 3 && !/\S/.test(child.nodeValue)) continue;
                if (child.nodeName !== 'BR') break;
                brCount++;
            }

            // 캐럿이 놓인 줄의 윗부분 계산
            // 글이 있으면: 마지막 글 줄 기준 + <br> 개수만큼 아래
            // 글이 없으면: 맨 윗줄 기준 + (<br> 개수 - 1)줄 아래 (빈 에디터의 자동 <br> 한 개는 줄바꿈이 아님)
            var caretTop;
            if (hasText) {
                caretTop = (lastTextBottom - lineH) + brCount * lineH;
            } else {
                caretTop = contentTop + Math.max(0, brCount - 1) * lineH;
            }
            return { top: caretTop, bottom: caretTop + lineH, left: elRect.left, right: elRect.right, height: lineH };
        }

        // textarea 전용: 캐럿의 화면 좌표를 미러 div로 측정한다
        // textarea와 같은 스타일의 숨은 div에 캐럿 앞 글자를 넣고, 마커 위치로 캐럿 줄을 구한다
        // 측정이 안 되면 null을 반환해 호출부가 요소 전체 좌표를 쓰도록 한다
        function getTextareaCaretRect(el) {
            var mirror = null;
            try {
                var style = window.getComputedStyle(el);
                var rect = el.getBoundingClientRect();
                mirror = document.createElement('div');
                ['boxSizing', 'width', 'fontFamily', 'fontSize', 'fontWeight', 'fontStyle', 'letterSpacing',
                 'lineHeight', 'textTransform', 'wordSpacing', 'textIndent', 'paddingTop', 'paddingRight',
                 'paddingBottom', 'paddingLeft', 'borderTopWidth', 'borderRightWidth', 'borderBottomWidth',
                 'borderLeftWidth', 'borderStyle'].forEach(function (p) {
                    mirror.style[p] = style[p];
                });
                mirror.style.position = 'absolute';
                mirror.style.visibility = 'hidden';
                mirror.style.whiteSpace = 'pre-wrap';
                mirror.style.wordWrap = 'break-word';
                mirror.style.overflow = 'hidden';
                mirror.style.left = (rect.left + window.pageXOffset) + 'px';
                mirror.style.top = (rect.top + window.pageYOffset) + 'px';

                // 캐럿 앞 글자까지 넣고, 마지막에 영폭 공백 마커를 붙인다
                mirror.textContent = el.value.substring(0, el.selectionStart);
                var marker = document.createElement('span');
                marker.textContent = '​';
                mirror.appendChild(marker);
                document.body.appendChild(mirror);

                var lineH = parseFloat(style.lineHeight) || (parseFloat(style.fontSize) * 1.5);
                var top = rect.top + marker.offsetTop - el.scrollTop;
                if (!isFinite(top)) return null;
                return { top: top, bottom: top + lineH, left: rect.left, right: rect.right, height: lineH };
            } catch (err) {
                return null;
            } finally {
                if (mirror && mirror.parentNode) mirror.parentNode.removeChild(mirror);
            }
        }

        // 고정 푸터/키보드에 textarea 또는 contenteditable 캐럿이 가려졌을 때만 .page를 스크롤해서 화면에 보이게 한다
        // textarea는 요소 전체 기준, contenteditable은 캐럿 기준으로 판정한다
        // blockValue: 'start' (포커스 시) 또는 'end' (입력으로 높이가 늘어난 경우)
        // 박스 전체 기준 보정(data-keep-box-visible)을 쓰는 칸인지 확인한다 (자기소개 등)
        function isKeepBoxEl(el) {
            return !!(el && el.classList && el.classList.contains('js-autoheight') && el.getAttribute('data-keep-box-visible') === 'true');
        }

        // 키보드가 올라오기 전(포커스 순간)에 contenteditable 박스 상단을 topbar 아래로 맞춘다
        // 박스가 키보드 위 영역에 들어갈 만큼 작을 때만 맞춘다. 그래야 키보드가 열린 뒤 네이티브가 박스를 맨 위로 다시 밀지 않는다
        // 자기 자신이 만든 스크롤이므로 programmaticScrollTop으로 표시해 사용자 스크롤과 구분한다
        function alignEditorTopBeforeKeyboard(el) {
            var KEYBOARD_VISIBLE_MAX = 340; // 키보드가 열린 상태에서 보이는 영역 높이(약 350px)보다 작게 잡은 값
            var page = el.closest('.page');
            var topBar = document.querySelector('.top-bar');
            if (!page || !topBar) return;

            var boxRect = el.getBoundingClientRect();
            if (boxRect.height > KEYBOARD_VISIBLE_MAX) return;

            var pageRect = page.getBoundingClientRect();
            var visibleTop = Math.max(pageRect.top, topBar.getBoundingClientRect().bottom + 12);
            var delta = boxRect.top - visibleTop;
            if (Math.abs(delta) < 2) return;

            var newScrollTop = page.scrollTop + delta;
            newScrollTop = Math.max(0, Math.min(newScrollTop, page.scrollHeight - page.clientHeight));
            page.style.scrollBehavior = 'auto';
            page.scrollTop = newScrollTop;
            programmaticScrollTop = page.scrollTop;
            page.style.scrollBehavior = '';
        }

        // 키보드/뷰포트가 바뀐 마지막 시각 (settleKeepBoxJudge 가 안정 여부를 판단할 때 사용)
        var lastViewportChangeAt = 0;
        var keepBoxSettleTimer = null;
        var KEEP_BOX_SETTLE_MIN = 400; // 포커스 직후 키보드가 올라오기 전 판정을 막는 최소 대기 (ms)
        var KEEP_BOX_SETTLE_QUIET = 300; // 뷰포트 변화가 이 시간 동안 없으면 안정으로 본다 (ms)
        var KEEP_BOX_SETTLE_MAX = 1500; // 계속 흔들려도 이 시간이 지나면 판정한다 (ms)

        // 자기소개 박스 보정: 키보드 전환과 스크롤이 가라앉은 뒤 한 번만 판정한다
        // 전환 중에 판정하면 네이티브 스크롤이 끝난 뒤 되돌리는 보정이 생겨 튕김이 난다
        function settleKeepBoxJudge(target, startedAt) {
            if (keepBoxSettleTimer) {
                clearTimeout(keepBoxSettleTimer);
                keepBoxSettleTimer = null;
            }
            startedAt = startedAt || Date.now();
            var now = Date.now();
            var waited = now - startedAt;
            // 단계 이동 스크롤(window.__stepScrolling)이 끝나기 전에는 판정하지 않는다
            var settled = waited >= KEEP_BOX_SETTLE_MIN
                && !window.__stepScrolling
                && now - lastViewportChangeAt >= KEEP_BOX_SETTLE_QUIET
                && now - lastPageScrollAt >= 200;
            if (!settled && waited < KEEP_BOX_SETTLE_MAX) {
                keepBoxSettleTimer = setTimeout(function () {
                    settleKeepBoxJudge(target, startedAt);
                }, 100);
                return;
            }
            if (document.activeElement !== target) return;
            scrollTextareaIntoView(target, 'start');
        }

        // retryCount: 단계 이동 스크롤 중이라 보류된 횟수 (최대 10회, 120ms 간격)
        function scrollTextareaIntoView(el, blockValue, retryCount) {
            if (!el || !el.classList || !el.classList.contains('js-autoheight')) return;
            blockValue = blockValue || 'start';
            retryCount = retryCount || 0;
            if (scrollFollowTimer) clearTimeout(scrollFollowTimer);
            scrollFollowTimer = setTimeout(function () {
                var page = el.closest('.page');
                if (!page) return;
                // 단계 이동 스크롤(scrollToCurrentStep) 중에는 보정 스크롤을 하지 않는다
                // 두 스크롤이 같은 순간 반대 방향으로 움직여 튕기는 것을 막기 위함
                // 단순히 버리면 첫 탭 보정이 누락되므로, 이동이 끝난 뒤 다시 판정한다
                if (window.__stepScrolling) {
                    if (retryCount < 10) scrollTextareaIntoView(el, blockValue, retryCount + 1);
                    return;
                }

                var pageRect = page.getBoundingClientRect();
                // contenteditable은 캐럿 좌표를 알 때만 판정한다. 모르면 추정하지 않고 움직이지 않는다
                // textarea는 캐럿 기준이 기본이고, data-keep-box-visible 이면 박스 전체 기준으로 판정한다
                var caretRect = null;
                var rect;
                // 캐럿 좌표를 실제로 쟀는지 여부 (요소 전체 좌표로 폴백했으면 false)
                var caretMeasured = true;
                // 박스 전체를 푸터 위에 보이게 할지 (자기소개 등). 보이는 영역보다 크면 아래에서 캐럿 기준으로 되돌린다
                var keepBox = !el.isContentEditable && el.getAttribute('data-keep-box-visible') === 'true';
                var boxRect = el.getBoundingClientRect();
                if (el.isContentEditable) {
                    caretRect = getCaretRect(el) || getEndCaretRect(el);
                    // 캐럿 좌표를 못 구해도 박스 기준(keepBox)이면 박스로 판정을 이어간다 (빈 에디터 첫 터치)
                    if (!caretRect && !keepBox) return;
                    caretMeasured = !!caretRect;
                    rect = caretRect || boxRect;
                } else {
                    // textarea도 캐럿 줄 기준으로 판정한다. 측정이 안 되면 요소 전체 좌표를 쓴다
                    rect = getTextareaCaretRect(el);
                    if (!rect) {
                        caretMeasured = false;
                        rect = el.getBoundingClientRect();
                    }
                }
                var gap = 12; // 가림 판정 여유 (px)

                // 보이는 영역 상단: .top-bar 하단 + 여유 / 하단: 푸터·키보드 위 + 여유
                var visibleTop = pageRect.top;
                var topBar = document.querySelector('.top-bar');
                if (topBar) {
                    visibleTop = Math.max(visibleTop, topBar.getBoundingClientRect().bottom + gap);
                }
                var visibleBottom = getVisibleBottom(page, pageRect) - gap;

                // 박스 전체가 보이는 영역에 들어가면 박스 기준으로 판정한다 (첫 탭에서 칸 아래쪽이 푸터에 덮이지 않도록)
                // 들어가지 않으면 캐럿 기준을 유지해 캐럿이 보이게 한다
                if (keepBox && boxRect.height <= visibleBottom - visibleTop) {
                    rect = boxRect;
                }

                var delta = 0;
                if (rect.height > visibleBottom - visibleTop) {
                    // textarea가 보이는 영역보다 크면: 상단이 가려졌을 때 상단 기준, 입력 중이면 하단 기준
                    if (rect.top < visibleTop) {
                        delta = rect.top - visibleTop;
                    } else if (blockValue === 'end' && caretMeasured && rect.bottom > visibleBottom) {
                        // 요소 전체 좌표(폴백)일 때는 하단 정렬을 하지 않는다 (selectionchange의 상단 정렬과 번갈아 흔들림)
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

                // 가려지지 않았으면 스크롤하지 않음 (불필요한 점프 방지)
                // 2px 미만의 차이는 소수점 오차라 무시한다 (미세한 왕복 흔들림 방지)
                if (Math.abs(delta) < 2) return;

                // 스크롤 범위 제한 (0 ~ 최대 스크롤)
                var newScrollTop = page.scrollTop + delta;
                newScrollTop = Math.max(0, Math.min(newScrollTop, page.scrollHeight - page.clientHeight));

                // .page는 CSS에서 scroll-behavior: smooth라 대입이 애니메이션으로 적용되어 즉시 반영되지 않는다
                // 보정 스크롤만 instant로 바꿔 적용하고, 사용자 스크롤 동작은 그대로 둔다
                page.style.scrollBehavior = 'auto';
                page.scrollTop = newScrollTop;
                programmaticScrollTop = page.scrollTop;
                page.style.scrollBehavior = '';
            }, 120);
        }

        // 높이 자동 조절
        // 글자 입력(input)마다 한 번만 높이를 맞추고 판정한다
        // keydown은 글자가 아직 들어가기 전이라 캐럿 위치가 틀리므로 쓰지 않는다
        // textarea/contenteditable 높이를 내용에 맞게 다시 계산하고, 높이 변화 방향('end'=증가, 'start'=감소)을 반환한다
        function fitTextareaHeight(target) {
            // 높이를 1px로 줄였다 다시 늘리는 동안 페이지 전체 높이가 잠깐 줄어 스크롤이 0 근처로 눌린다
            // 그래서 원래 스크롤 위치를 저장해 두었다가 높이 계산이 끝난 뒤 되돌린다
            var page = target.closest('.page');
            var savedScrollTop = page ? page.scrollTop : 0;

            var prevHeight = textareaHeights.get(target) || target.offsetHeight;
            target.style.height = '1px';
            target.style.height = (target.scrollHeight + 8) + 'px';
            var newHeight = target.offsetHeight;
            if (page) {
                // 복원도 보정 스크롤과 같이 즉시 적용한다 (smooth면 내려갔다 올라오는 튕김처럼 보임)
                page.style.scrollBehavior = 'auto';
                page.scrollTop = savedScrollTop;
                programmaticScrollTop = page.scrollTop;
                page.style.scrollBehavior = '';
            }

            textareaHeights.set(target, newHeight);
            return (newHeight > prevHeight) ? 'end' : 'start';
        }

        window.addEventListener('input', function (e) {
            var target = e.target;
            if (!target.classList || !target.classList.contains('js-autoheight')) return;

            var blockValue = fitTextareaHeight(target);
            scrollTextareaIntoView(target, blockValue);
        });

        // 포커스 시: 임시 여백 추가 + 캐럿 위치 보정
        window.addEventListener('focusin', function (e) {
            if (e.target && e.target.classList && e.target.classList.contains('js-autoheight')) {
                // 네이티브 포커스 스크롤이 이 값을 기준으로 움직이므로, 포커스 직전 topbar 높이로 다시 맞춘다
                // 초기화 시점에 잰 값이 낮으면 네이티브 스크롤이 박스를 topbar 아래로 밀어 넣고 그만큼 되돌리며 튕긴다
                setupScrollMarginTop();
                // .page-content에 임시 하단 여백 추가 (스크롤 여유 확보)
                var pageContent = e.target.closest('.page-content');
                if (pageContent) {
                    pageContent.style.paddingBottom = '50vh';
                }
                // 포커스 직후에는 키보드가 올라오는 중이라 visualViewport 높이가 순간적으로 틀리다(예: 153).
                // 이때 판정하면 필요 이상으로 내려가므로, 즉시 판정은 하지 않고 키보드가 안정된 뒤(400/800ms)에만 판정한다
                textareaFocusAt = Date.now();
                if (e.target.isContentEditable) {
                    alignEditorTopBeforeKeyboard(e.target);
                }
                var settledTarget = e.target;
                // contenteditable(상세업무 등)도 키보드 전환이 끝난 뒤 한 번만 판정한다
                // 고정 400/800ms 판정은 visualViewport가 아직 틀린 순간에 실행되어 필요 이상으로 내려간다
                if (isKeepBoxEl(settledTarget) || settledTarget.isContentEditable) {
                    settleKeepBoxJudge(settledTarget);
                } else {
                    [400, 800].forEach(function (ms) {
                        setTimeout(function () {
                            if (document.activeElement !== settledTarget) return;
                            scrollTextareaIntoView(settledTarget, 'start');
                        }, ms);
                    });
                }
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
            lastViewportChangeAt = Date.now();
            var active = document.activeElement;
            if (active && active.classList && active.classList.contains('js-autoheight')) {
                if (isKeepBoxEl(active) || active.isContentEditable) {
                    settleKeepBoxJudge(active);
                    return;
                }
                scrollTextareaIntoView(active, 'start');
            }
        }
        if (window.visualViewport) {
            window.visualViewport.addEventListener('resize', onViewportResize);
        }
        window.addEventListener('resize', onViewportResize);

        // contenteditable에서 캐럿만 움직인 경우(탭, 화살표 키 등)도 캐럿 기준으로 다시 판정
        // 입력 이벤트가 없으므로 selectionchange를 쓰며, 포커스된 contenteditable일 때만 동작한다
        // 캐럿을 옮기면 브라우저가 캐럿을 따라 스크롤하는데 이 동작은 비동기라, 바로 판정하면 그 뒤에 다시 가려질 수 있다
        // 그래서 움직임이 멈춘 뒤 한 번 더 판정한다
        // 사용자가 손가락으로 스크롤하는 중에는 캐럿 기준 판정을 하지 않는다
        // 판정이 끼어들면 사용자가 올린 화면을 캐럿 위치로 되돌려 튕김처럼 보이기 때문
        var lastPageScrollAt = 0; // 마지막 사용자 스크롤 시각 (보정 스크롤은 제외)
        document.addEventListener('scroll', function (e) {
            if (!e.target || !e.target.classList || !e.target.classList.contains('page')) return;
            var isProgrammatic = programmaticScrollTop !== null && Math.abs(e.target.scrollTop - programmaticScrollTop) < 2;
            if (!isProgrammatic) lastPageScrollAt = Date.now();
        }, true);

        var caretSettleTimer = null;
        function followCaret(src) {
            var active = document.activeElement;
            if (!active || !active.classList || !active.classList.contains('js-autoheight')) return;
            if (!active.isContentEditable && active.tagName !== 'TEXTAREA') return;
            // 키보드가 올라오는 중(포커스 후 400ms)에는 캐럿 이동 판정을 하지 않는다 (400ms 재판정에서 처리)
            if (Date.now() - textareaFocusAt < 400) return;
            // Enter 등 JS로 내용을 바꾼 경우 input 이벤트가 없어 높이가 갱신되지 않으므로, 넘치면 캐럿 이동 시점에 맞춘다
            // scrollHeight가 clientHeight보다 클 때만 계산하므로 캐럿 이동마다 리플로우가 나지 않는다
            if (active.isContentEditable && active.scrollHeight > active.clientHeight) {
                fitTextareaHeight(active);
            }
            // contenteditable은 키보드 전환과 네이티브 캐럿 스크롤이 가라앉은 뒤에만 판정한다
            // 즉시 판정하면 틀린 visualViewport로 과하게 움직인 뒤 네이티브 스크롤이 되돌려 튕긴다
            if (active.isContentEditable) {
                settleKeepBoxJudge(active);
                return;
            }
            scrollTextareaIntoView(active, 'start');
            if (caretSettleTimer) clearTimeout(caretSettleTimer);
            caretSettleTimer = setTimeout(function () {
                if (document.activeElement !== active) return;
                // 스크롤 직후라면 사용자가 움직이는 중일 수 있으므로 이번 재판정은 건너뛴다
                if (Date.now() - lastPageScrollAt < 300) return;
                scrollTextareaIntoView(active, 'start');
            }, 300);
        }
        // 글자 입력은 input 핸들러가 처리하므로, keyup은 캐럿만 움직이는 키(방향키 등)에서만 반응한다
        // 탭이나 터치 끝(click/touchend)은 스크롤 동작이 끝난 시점에도 발생하므로 감지 대상에서 뺀다
        // 캐럿 위치 변화는 selectionchange가 탭까지 모두 잡아준다
        var CARET_MOVE_KEYS = ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'Home', 'End', 'PageUp', 'PageDown'];
        document.addEventListener('selectionchange', function () { followCaret('selectionchange'); });
        document.addEventListener('keyup', function (e) {
            if (CARET_MOVE_KEYS.indexOf(e.key) !== -1) followCaret();
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
