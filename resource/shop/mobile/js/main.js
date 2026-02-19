(function () {
    'use strict';

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
    (function setLazyLoadImg() {
        if (typeof lozad == 'undefined') return;

        var observer = lozad();
        observer.observe();
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

                        // Swift에 패널 열림 알림 (iOS WebView 스크롤 비활성화)
                        if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.panelState) {
                            try {
                                window.webkit.messageHandlers.panelState.postMessage('open');
                            } catch (e) {
                                console.log('Swift messageHandler not available');
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

            // body에서 no-scroll 클래스 제거 (position, overflow, width 등 모든 스타일 해제)
            // body 위치 변경 없이 스크롤만 복구
            document.body.classList.remove('no-scroll');

            // 저장된 스크롤 위치로 복구 (body 위치 변경 없이 스크롤만 이동)
            if (savedScrollPosition > 0) {
                window.scrollTo(0, savedScrollPosition);
                savedScrollPosition = 0;
            }

            // Swift에 패널 닫힘 알림 (iOS WebView 스크롤 활성화)
            if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.panelState) {
                try {
                    window.webkit.messageHandlers.panelState.postMessage('close');
                } catch (e) {
                    console.log('Swift messageHandler not available');
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

            // 폼 초기화
            $el.querySelectorAll('input, textarea, select').forEach(function (item) {
                item.value = '';
            });

            $el.querySelectorAll('form').forEach(function (form) {
                form.reset();
            });

            $el.scrollTo(0, 0);
        };

        window.addEventListener('click', function (e) {
            // e.target이 DOM 요소인지 확인
            if (!e.target || typeof e.target.getAttribute !== 'function') return;

            if (e.target.classList && !e.target.classList.contains('js-toggle-panel') && !e.target.parentNode.classList.contains('js-toggle-panel')) return;

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
        ['input', 'keydown'].forEach(function (evt) {
            window.addEventListener(evt, function (e) {
                var target = e.target;
                if (target.classList && target.classList.contains('js-autoheight')) {
                    target.style.height = '1px';
                    target.style.height = (target.scrollHeight + 8) + 'px';
                }
            });
        });

        document.querySelectorAll('.js-autoheight').forEach(function (el) {
            if (el.scrollHeight) {
                el.style.height = (el.scrollHeight + 8) + 'px';
            }
        });

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

}());
