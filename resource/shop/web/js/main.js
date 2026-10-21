(function () {
    'use strict';

    /* Lazy Loader : https://github.com/ApoorvSaxena/lozad.js */
    (function setLazyLoadImg() {
        if (typeof lozad == 'undefined') return;

        var observer = lozad();
        observer.observe();
    }());

    /* 전체검색 표시/숨김 */
    (function toggleTopSearch() {
        if (!document.querySelector('.btn-top-search')) return;

        var body = document.body;
        var header = document.querySelector('#header');

        function close() {
            header.classList.remove('searching');
            body.style.overflow = '';
        }

        document.querySelector('.btn-top-search').addEventListener('click', function () {
            header.classList.toggle('searching');

            if (header.classList.contains('searching')) {
                body.style.overflowY = 'hidden';
                setTimeout(function () {
                    document.querySelector('.top-search input[autofocus]').focus({ focusVisible: true });
                }, 200);
            } else {
                body.style.overflowY = '';
            }
        });

        header.addEventListener('click', function (e) {
            if (header.classList.contains('searching') && !e.target.closest('.top-search')) {
                close();
            }
        });

        header.querySelector('.js-toggle-dropdown').addEventListener('click', function (e) {
            if (header.classList.contains('searching') && !e.target.closest('.btn-top-search')) {
                close();
            }
        });

        header.addEventListener('keydown', function (e) {
            if (e.keyCode == 27 && header.classList.contains('searching')) {
                close();
            }
        });
    }());

    /* 드롭다운 */
    (function setDropdown() {
        if (!document.querySelector('.js-toggle-dropdown')) return;

        function collapse(el) {
            el.hidden = true;
            el.classList.remove('on');
            el.parentNode.querySelector('.js-toggle-dropdown').setAttribute('aria-expanded', false);
        }

        function toggle(el) {
            var dropdown = el.parentNode.querySelector('.dropdown');
            var active = document.querySelector('.dropdown.on');
            var status = false;

            if (!dropdown) return;

            if (active && active !== dropdown) {
                collapse(active);
            }

            status = dropdown.classList.contains('on') ? false : true;

            if (status) {
                dropdown.hidden = false;
                setTimeout(function () { dropdown.focus(); }, 100);
            } else {
                dropdown.hidden = true;
            }

            dropdown.classList.toggle('on');
            el.setAttribute('aria-expanded', status);
        }

        document.querySelectorAll('.js-toggle-dropdown').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                toggle(btn);
            });
        });

        document.querySelectorAll('.dropdown').forEach(function (el) {
            el.addEventListener('keydown', function (ev) {
                if (ev.keyCode == 27) {
                    collapse(this);
                }
            });
        });

        window.addEventListener('click', function (e) {
            var active = document.querySelector('.dropdown.on');

            if (active && !e.target.closest('.dropdown')) {
                collapse(active);
            }
        });
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
                thirdButton = thirdButton ? '<button type="submit" aria-controls="' + id + '" class="btn btn-tertiary js-close-popup js-popup-action3">' + thirdButton + '</button>' : thirdButton;

                html = '<div id="' + id + '" class="layer-popup">' +
                    '<div role="alertdialog" aria-labelledby="confirmTitle' + index + '" aria-modal="true" class="popup-container">' +
                    '<h4 id="confirmTitle' + index + '" class="sr-text">' + title + '</h4>' +
                    '<button type="button" aria-controls="' + id + '" class="btn-close-popup js-close-popup"><span class="sr-text">닫기</span></button>' +
                    '<div class="popup-content">' +
                    '<div class="msg">' + content + '</div>' +
                    '</div> <div class="popup-footer">' +
                    '<button type="button" aria-controls="' + id + '" class="btn btn-secondary  js-close-popup js-popup-action1">' + firstButton + '</button>' +
                    '<button type="submit" aria-controls="' + id + '" class="btn btn-primary js-close-popup js-popup-action2">' + secondButton + '</button>' + thirdButton +
                    '</div> </div> </div>';
            } else if (type == 'alert') {
                id = id ? id + index : 'alertDialog' + index;
                html = '<div id="' + id + '" class="layer-popup"><div role="alertdialog" aria-labelledby="alertTitle' + index + '" aria-modal="true" class="popup-container">' +
                    '<h4 id="alertTitle' + index + '" class="sr-text">' + title + '</h4>' +
                    '<button type="button" aria-controls="' + id + '" class="btn-close-popup js-close-popup"><span class="sr-text">닫기</span></button>' +
                    '<div class="popup-content">' +
                    '<div class="msg">' + content + '</div>' +
                    '</div> <div class="popup-footer">' +
                    '<button type="button" aria-controls="' + id + '" class="btn btn-primary js-close-popup js-popup-action1">확인</button>' +
                    '</div> </div> </div>';
            } else if (type == 'alert2') {
                id = id ? id + index : 'alertDialog' + index;
                html = '<div id="' + id + '" class="layer-popup"><div role="alertdialog" aria-labelledby="alertTitle' + index + '" aria-modal="true" class="popup-container">' +
                    '<h4 id="alertTitle' + index + '" class="sr-text">' + title + '</h4>' +
                    '<div class="popup-content">' +
                    // 줄바꿈 문자(\n)는 HTML에서 공백으로 접히므로 <br>로 변환
                    '<div class="msg">' + String(content).replace(/\n/g, '<br>') + '</div>' +
                    '</div> <div class="popup-footer">' +
                    '<button type="button" aria-controls="' + id + '" class="btn btn-primary ' + removeClose + ' js-popup-action1">확인</button>' +
                    '</div> </div> </div>';
            } else if (type == 'modal') {
                id = id ? id + index : 'defaultDialog' + index;

                if (firstButton && !secondButton) {
                    firstButton = '<button type="button" aria-controls="' + id + '" class="btn btn-primary js-close-popup js-popup-action1">' + firstButton + '</button>';
                } else if (firstButton && secondButton) {
                    firstButton = '<button type="button" aria-controls="' + id + '" class="btn btn-secondary js-close-popup js-popup-action1">' + firstButton + '</button>';
                    secondButton = '<button type="button" aria-controls="' + id + '" class="btn btn-primary  js-close-popup js-popup-action2">' + secondButton + '</button>';
                }

                thirdButton = thirdButton ? '<button type="button" aria-controls="' + id + '" class="btn btn-tertiary js-close-popup js-popup-action3">' + thirdButton + '</button>' : thirdButton;

                html = '<div id="' + id + '" class="layer-popup"><div role="dialog" aria-labelledby="popupTitle' + index + '" aria-modal="true" class="popup-container">' +
                    '<h4 id="popupTitle' + index + '" class="sr-text">' + title + '</h4>' +
                    '<button type="button" aria-controls="' + id + '" class="btn-close-popup js-close-popup"><span class="sr-text">닫기</span></button>' +
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
                }, { once: true });
            });

            if (type === 'alert') return;

            if (firstButton && firstCallback && typeof firstCallback === 'function') {
                $popup.querySelector('.js-popup-action1').addEventListener('click', function (e) {
                    setTimeout(function () {
                        firstCallback();
                    }, 100);
                }, { once: true });
            }

            if (secondButton && secondCallback && typeof secondCallback === 'function') {
                $popup.querySelector('.js-popup-action2').addEventListener('click', function (e) {
                    setTimeout(function () {
                        secondCallback();
                    }, 100);
                }, { once: true });
            }

            if (thirdButton && thirdCallback && typeof thirdCallback === 'function') {
                $popup.querySelector('.js-popup-action3').addEventListener('click', function (e) {
                    setTimeout(function () {
                        thirdCallback();
                    }, 100);
                }, { once: true });
            }
        };

    }());

    /* Tooltip */
    (function setTooltip() {

        ['mouseover', 'focusin'].forEach(function (evt) {
            window.addEventListener(evt, function (e) {
                if (e.target.classList && !e.target.classList.contains('js-toggle-tooltip')) {
                    if (document.querySelector('.tooltip.on')) {
                        document.querySelector('.tooltip.on').classList.remove('on');
                    }
                    return;
                }

                e.preventDefault();

                var $tooltip = e.target.parentNode.querySelector('.tooltip');
                var offset = 27,
                    xpos = e.target.offsetLeft + (e.target.offsetWidth / 2) - offset,
                    ypos = e.target.offsetTop + e.target.offsetHeight;

                if (!$tooltip) return;

                if (!$tooltip.classList.contains('right') && xpos + $tooltip.offsetWidth > window.innerWidth) {
                    $tooltip.classList.remove('center', 'left');
                    $tooltip.classList.add('right');
                    xpos = xpos - $tooltip.offsetWidth + (offset * 2);
                } else if ($tooltip.classList.contains('center')) {
                    xpos = e.target.offsetLeft - ($tooltip.offsetWidth / 2) + (e.target.offsetWidth / 2);
                } else if ($tooltip.classList.contains('right')) {
                    xpos = xpos - $tooltip.offsetWidth + (offset * 2);
                }

                if (!$tooltip.classList.contains('bottom') && $tooltip.offsetHeight + ypos < document.body.scrollHeight) {
                    $tooltip.classList.remove('bottom');
                } else {
                    ypos = ypos - $tooltip.offsetHeight - e.target.offsetHeight;
                    $tooltip.classList.add('bottom');
                }

                if (!$tooltip.classList.contains('on')) {
                    $tooltip.style.top = ypos + 'px';
                    $tooltip.style.left = xpos + 'px';
                }

                setTimeout(function () {
                    $tooltip.classList.toggle('on');
                }, 100);
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
                min = slider.querySelector('.min input').value,
                max = slider.querySelector('.max input').value,
                parent = input.parentNode,
                label = parent.querySelector('.label'),
                thumb = parent.querySelector('.thumb'),
                unit = input.getAttribute('data-unit'),
                rangeMax = input.getAttribute('max'),
                total = input.offsetWidth - thumb.offsetWidth + 4;

            if (input.value == 0) {
                label.style.opacity = 0;
            } else {
                label.style.opacity = '';
            }

            min = min / rangeMax * 100;
            max = 100 - (max / rangeMax * 100);

            fill.style.clipPath = 'inset(0px ' + max + '% 0px ' + min + '%)';
            thumb.style.transform = 'translateX(' + total * (input.value / rangeMax) + 'px)';
            label.innerHTML = input.value + unit;
        }

        document.querySelectorAll('.js-range-slider input[type="range"]').forEach(function (input) {
            input.addEventListener('input', function (e) {
                chgValue(this.parentNode.parentNode, this);
            });
            chgValue(input.parentNode.parentNode, input);
        });

        window.addEventListener('resize', function () {
            document.querySelectorAll('.js-range-slider input[type="range"]').forEach(function (input) {
                chgValue(input.parentNode.parentNode, input);
            });
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
                    if (idx === slide) {
                        dot.classList.add("dot--active");
                    } else {
                        dot.classList.remove("dot--active");
                    }
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
                // 이미지에 명시적인 width/height 추가하여 리플로우 방지
                arrowLeft.innerHTML = '<img src="/resource/shop/web/img/ic_circle_prev_v2.png" alt="Previous" width="36" height="36" style="display: block; width: 100%; height: 100%; object-fit: contain;">';
                arrowLeft.addEventListener("click", function () { slider.prev(); });
                arrowRight = createButton("arrow arrow--right");
                arrowRight.innerHTML = '<img src="/resource/shop/web/img/ic_circle_next_v2.png" alt="Next" width="36" height="36" style="display: block; width: 100%; height: 100%; object-fit: contain;">';
                arrowRight.addEventListener("click", function () { slider.next(); });
                container.parentNode.insertBefore(arrowLeft, container);
                container.parentNode.insertBefore(arrowRight, container.nextSibling);
            }

            function updateClasses() {
                // 항상 화살표 표시 (disabled 클래스 미적용)
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

    /* Delete Item */
    (function deleteItem() {
        window.addEventListener('click', function (e) {
            if (e.target.classList && e.target.classList.contains('js-del-item')) {
                var item = e.target.parentNode;
                item.style.width = item.offsetWidth + 'px';
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

    /* 맨위로 가기 */
    (function setBtnTop() {
        if (!document.querySelector('.js-goto-top')) return;

        var body = document.body;
        var btnTop = document.querySelector('.js-goto-top');

        window.addEventListener('scroll', function (e) {
            var curpos = this.scrollY;
            if ((window.innerHeight / 2 / 10) <= curpos) {
                body.classList.add('is-scrolled');
                btnTop.classList.add('show');
            } else {
                body.classList.remove('is-scrolled');
                btnTop.classList.remove('show');
            }
        });

        btnTop.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo(0, 0);
        });

        setTimeout(function () {
            window.scroll();
        }, 1);
    }());

    /* 커스텀 스크롤바  */
    (function setJsScrollbar() {
        var scrollbars = document.querySelectorAll('.js-scrollbar');

        if (!scrollbars) return;

        function doScroll(e, item) {
            e = window.event || e;
            var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));

            if ((delta == -1 && item.current * item.mean >= -item.distance) || (delta == 1 && item.current * item.mean < 0)) {
                item.current = item.current + delta;
            }

            e.currentTarget.children[0].style.top = (item.current * item.mean) + 'px';
            item.thumb.style.top = 0 - Math.round(item.tHeight * (item.current * item.mean) / item.cHeight) + 'px';

            e.preventDefault();
        }

        scrollbars.forEach(function (scrollbar) {
            var item = {};
            item.track = scrollbar.querySelector('.scrollbar-track');
            item.thumb = scrollbar.querySelector('.scrollbar-thumb');
            item.height = parseInt(scrollbar.offsetHeight, 10);
            item.cHeight = parseInt(scrollbar.querySelector('.scroll-content').offsetHeight, 10);
            item.tHeight = parseInt(scrollbar.querySelector('.scrollbar-track').offsetHeight, 10);
            item.distance = item.cHeight - item.height;
            item.mean = 30;
            item.current = 0;

            scrollbar.querySelector('.scroll-content').style.top = item.current + "px";
            item.thumb.style.height = Math.round(item.tHeight * item.height / item.cHeight) + 'px';

            scrollbar.addEventListener("mousewheel", function (e) {
                doScroll(e, item);
            });

            scrollbar.addEventListener("DOMMouseScroll", function (e) {
                doScroll(e, item);
            });

            window.addEventListener('load', function () {
                item.height = parseInt(scrollbar.offsetHeight, 10);
                item.cHeight = parseInt(scrollbar.querySelector('.scroll-content').offsetHeight, 10);
                item.tHeight = parseInt(scrollbar.querySelector('.scrollbar-track').offsetHeight, 10);
                item.thumb.style.height = Math.round(item.tHeight * item.height / item.cHeight) + 'px';
            });
        });
    }());

    /* 탭목록 표시/숨김 */
    /*
    (function setTabList() {
        function close (el) {
            var tabPanel = document.getElementById(el.getAttribute('aria-controls'));

            el.setAttribute('aria-selected', false);
            el.parentNode.classList.remove('on');

            if (tabPanel) {
                tabPanel.hidden = true;
                tabPanel.classList.remove('on');
            }
        }

        function open (el) {
            var tabPanel = document.getElementById(el.getAttribute('aria-controls'));

            el.setAttribute('aria-selected', true);
            el.parentNode.classList.add('on');

            if (tabPanel) {
                tabPanel.hidden = false;
                tabPanel.classList.add('on');
            }
        }

        window.addEventListener('click', function (e) {
            var tab = e.target.closest('[role="tab"]');

            if (tab) {
                e.preventDefault();

                var active = tab.closest('.tabs').querySelector('.on [role="tab"]');

                if (active && active !== tab) {
                    close(active);
                }

                open(tab);
            }
        });

        // 2뎁스 탭메뉴 표시, 숨김 
        if (document.querySelectorAll('.tabs.has-sub')) {
            document.querySelectorAll('.tabs.has-sub').forEach(function (elm) {
                elm.addEventListener('mouseenter', function (e) {
                    var active = this.querySelector('.is-sub.on');

                    if (active) {
                        active.classList.remove('on');
                        this.querySelector('.tab:focus').blur();
                    }

                    this.classList.add('is-focusing');
                });

                elm.addEventListener('mouseleave', function (e) {
                    var active = this.querySelector('.is-sub.on');

                    if (active) {
                        active.classList.remove('on');
                        this.querySelector('.tab:focus').blur();
                    }

                    this.classList.remove('is-focusing');
                });

                elm.querySelectorAll('.js-toggle').forEach(function (item) {
                    var parent = item.parentNode;
                    var subMenu = parent.querySelector('.is-sub');
                    var container = parent.parentNode;
                    var list = container.children;

                    parent.addEventListener('focusin', function (e) {
                        container.classList.add('is-focusing');
                        subMenu.classList.add('on');
                    });

                    parent.addEventListener('focusout', function (e) {
                        subMenu.classList.remove('on');

                        if (this === list[0] || this === list[list.length-1]) {
                            container.classList.remove('is-focusing');
                        }
                    });
                });
            });
        }
    }());
    */
    /* 채팅창 열기/닫기 */
    (function toggleChat() {
        var popup = document.getElementById('chatPopup');

        if (!popup) return;

        var body = document.body;
        var container = popup.querySelector('.chat-container');
        var view = popup.querySelector('.chat-view');
        var openBtn = document.querySelector('.js-open-chat');
        var closeBtn = document.querySelector('.btn-close-chat');

        function setFocus($el) {
            if (!$el) return false;

            var $focusableEl = $el.querySelectorAll('a, button');
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

            setTimeout(function () {
                $el.focus();
            }, 500);
        }

        window.openView = function (trigger) {
            if (!view) return;

            var active = document.querySelector('.js-open-view.on');

            if (active) {
                active.classList.remove('on');
            }

            view.hidden = false;
            container.classList.add('is-viewing');

            if (trigger) {
                trigger.classList.add('on');
            }
        };

        window.closeView = function () {
            if (!view) return;
            var trigger = document.querySelector('.js-open-view.on');
            view.hidden = true;
            container.classList.remove('is-viewing');

            if (trigger) {
                trigger.classList.remove('on');
                trigger.focus();
            }
        };

        openBtn.addEventListener('click', function (e) {
            e.preventDefault();
            // 배경 dim 및 스크롤 잠금 제거: body.classList.add('has-popup');
            openBtn.classList.add('is-trigger');
            popup.classList.add('in');
            closeBtn.classList.add('show');
            // 채팅 팝업이 열릴 때 하단 채팅 버튼 숨기기
            var bottomChatBtn = document.getElementById('bottomchatbtn');
            if (bottomChatBtn) {
                bottomChatBtn.style.display = 'none';
            }
            setFocus(popup);
        });

        document.querySelectorAll('.js-close-chat').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                closeView();
                popup.classList.remove('in');
                closeBtn.classList.remove('show');
                openBtn.classList.remove('is-trigger');
                body.classList.remove('has-popup');
                // 채팅 팝업이 닫힐 때 하단 채팅 버튼 다시 보이기
                var bottomChatBtn = document.getElementById('bottomchatbtn');
                if (bottomChatBtn) {
                    bottomChatBtn.style.display = '';
                }
                setTimeout(function () {
                    openBtn.focus();
                }, 100);
            });
        });

        container.addEventListener('click', function (e) {
            if (e.target.closest('.js-open-view')) {
                e.preventDefault();
                openView(e.target.closest('.js-open-view'));
            }
            if (e.target.closest('.js-close-chat-view')) {
                e.preventDefault();
                closeView();
            }
            if (e.target.closest('.js-toggle-summary')) {
                e.preventDefault();
                var summary = document.getElementById('chatSummary');
                summary.classList.toggle('on');

                if (summary.classList.contains('on')) {
                    summary.focus();
                }
            }
        });

    }());

    /* 탭메뉴 넘칠때 휠 이벤트 */
    (function setOverflowTabs() {
        var allContainer = document.querySelectorAll('.tabs-wrap .tabs');

        if (!allContainer.length) return;

        function isScrolled(el) {
            setTimeout(function () {
                if (el.scrollLeft < 100) {
                    el.parentNode.classList.remove('is-scrolled');
                } else {
                    el.parentNode.classList.add('is-scrolled');
                }
            }, 1);
        }

        function chkWidth(el) {
            if (el.scrollWidth > el.offsetWidth) {
                el.parentNode.classList.add('has-scroll');
                el.scrollLeft = el.querySelector('.on').offsetLeft - 23;
                isScrolled(el);
            }
        }

        allContainer.forEach(function (con) {
            chkWidth(con);

            con.addEventListener('wheel', function (e) {
                if (e.deltaY > 0) {
                    con.scrollLeft += 100;
                    e.preventDefault();
                } else {
                    con.scrollLeft -= 100;
                    e.preventDefault();
                }

                isScrolled(con);
            });

            window.addEventListener('resize', function (e) {
                chkWidth(con);
            });
        });

    }());

    /* 레이어 패널 열기/닫기 */
    (function setTogglePanel() {
        // openPanel 함수 정의
        window.openPanel = function (el) {
            if (!el) return;
            el.classList.add('on');
            el.hidden = false;
            document.body.style.overflow = 'hidden';
            document.body.classList.add('panel-open');
            // 포커스 설정
            setTimeout(function () {
                el.focus();
            }, 100);
        };

        // closePanel 함수 정의
        window.closePanel = function (el) {
            if (!el) return;
            el.classList.remove('on');
            el.hidden = true;
            document.body.style.overflow = '';
            document.body.classList.remove('panel-open');
        };

        // js-toggle-panel 클릭 이벤트 처리
        document.addEventListener('click', function (e) {
            var target = e.target;
            var toggleBtn = target.closest('.js-toggle-panel');

            if (!toggleBtn) return;

            e.preventDefault();
            e.stopPropagation();

            // aria-controls 속성 또는 href 속성으로 패널 ID 가져오기
            var panelId = toggleBtn.getAttribute('aria-controls');
            if (!panelId) {
                var href = toggleBtn.getAttribute('href');
                if (href && href.indexOf('#') === 0) {
                    panelId = href.replace('#', '');
                }
            }

            if (!panelId) return;

            var panel = document.getElementById(panelId);
            if (!panel) return;
            // QuickInfo에서 열린 주소 팝업은 닫기 버튼으로 닫지 않음
            if (panelId === 'changeArea' && window.__disableChangeAreaClose) {
                return;
            }

            // 패널 열기/닫기 토글
            if (panel.classList.contains('on')) {
                closePanel(panel);
            } else {
                // 다른 열려있는 패널 닫기
                document.querySelectorAll('.layer-panel.on').forEach(function (openPanel) {
                    if (openPanel !== panel) {
                        closePanel(openPanel);
                    }
                });
                openPanel(panel);
            }
        });

        // 배경 클릭 시 패널 닫기
        document.addEventListener('click', function (e) {
            // 팝업이 열려있는 동안에는 패널 닫기 동작을 막음
            if (document.body.classList.contains('has-popup') || e.target.closest('.layer-popup')) {
                return;
            }

            var openPanel = document.querySelector('.layer-panel.on');
            if (!openPanel) return;
            // QuickInfo에서 열린 주소 팝업은 배경 클릭으로 닫지 않음
            if (openPanel.id === 'changeArea' && window.__disableChangeAreaClose) {
                return;
            }

            var panelContainer = openPanel.querySelector('.panel-container, .bottom-sheet-container');

            // 패널 컨테이너 내부를 클릭한 경우 닫지 않음
                if (panelContainer && panelContainer.contains(e.target)) {
                    return;
                }

            // 배경을 클릭한 경우 패널 닫기
            if (openPanel === e.target || (openPanel.contains(e.target) && (!panelContainer || !panelContainer.contains(e.target)))) {
                closePanel(openPanel);
            }
        });

        // ESC 키로 패널 닫기
        document.addEventListener('keydown', function (e) {
            if (e.keyCode === 27) { // ESC 키
                var openPanel = document.querySelector('.layer-panel.on');
                if (openPanel) {
                    if (openPanel.id === 'changeArea' && window.__disableChangeAreaClose) {
                        return;
                    }
                    closePanel(openPanel);
                }
            }
        });
    }());

    /* 통합 슬라이더 초기화 */
    (function initAllSliders() {
        // 추천공고 슬라이더 초기화 (AJAX 로드 후 호출)
        // @param {string} containerId - 슬라이더 컨테이너 선택자
        // @param {number} [maxPerView=5] - 한 화면에 보여줄 최대 카드 수 (기본 5)
        // @param {number} [slideWidth] - 슬라이드 1칸 고정 폭(px)
        window.initRecommendationSlider = function(containerId, maxPerView, slideWidth) {
            if (typeof KeenSlider === 'undefined') return;

            var container = document.querySelector(containerId);
            if (!container) return;

            var sliderWrap = container.querySelector('.slider-wrap');
            if (!sliderWrap) return;

            var slideCount = container.querySelectorAll('.keen-slider__slide').length;
            if (slideCount === 0) return;

            maxPerView = parseInt(maxPerView, 10) || 5;
            var perView = slideCount < maxPerView ? slideCount : maxPerView;

            // 슬라이드 아이템에 고정 너비 적용 (100 / maxPerView %)
            var slides = container.querySelectorAll('.keen-slider__slide');
            slides.forEach(function(slide) {
                slide.style.flex = '0 0 ' + (100 / maxPerView) + '%';
            });

            // 슬라이드 개수에 따라 슬라이더 너비 동적 설정
            var fixedSlideWidth = parseInt(slideWidth, 10) || 0;
            var unitWidth = fixedSlideWidth || 320; // 각 슬라이드 320px (최대너비) + 여백
            var totalWidth = slideCount * unitWidth;
            var wrapperWidth = Math.min(totalWidth, maxPerView * unitWidth); // 최대 maxPerView개 크기
            sliderWrap.style.width = wrapperWidth + 'px';
            sliderWrap.style.maxWidth = fixedSlideWidth ? 'none' : '100%';

            // 기존 슬라이더 인스턴스 정리
            if (sliderWrap.__KeenSliderInstance) {
                sliderWrap.__KeenSliderInstance.destroy();
            }

            var slider = new KeenSlider(containerId + " .slider-wrap", {
                loop: slideCount > perView,
                slides: {
                    perView: perView
                }
            }, [
                addActiveClass,
                sliderArrows
            ]);

            sliderWrap.__KeenSliderInstance = slider;
            return slider;
        };

        // 썸네일 슬라이더 초기화 (이미지 갤러리)
        window.initThumbnailSlider = function(mainSliderSelector, thumbnailSliderSelector) {
            if (typeof KeenSlider === 'undefined') return;

            if (!document.querySelector(mainSliderSelector) || !document.querySelector(thumbnailSliderSelector)) {
                return;
            }

            function ThumbnailPlugin(main) {
                return function(slider) {
                    function removeActive() {
                        slider.slides.forEach(function(slide) {
                            slide.classList.remove('active');
                        });
                    }

                    function addActive(idx) {
                        if (slider.slides[idx]) {
                            slider.slides[idx].classList.add('active');
                        }
                    }

                    function addClickEvents() {
                        slider.slides.forEach(function(slide, idx) {
                            slide.addEventListener('click', function() {
                                main.moveToIdx(idx);
                            });
                        });
                    }

                    slider.on('created', function() {
                        if (slider.track && slider.track.details) {
                            addActive(slider.track.details.rel);
                        }
                        addClickEvents();
                        main.on('animationStarted', function(main) {
                            removeActive();
                            var next = main.animator.targetIdx || 0;
                            addActive(main.track.absToRel(next));
                            slider.moveToIdx(Math.min(slider.track.details.maxIdx, next));
                        });
                    });
                };
            }

            var mslider = new KeenSlider(mainSliderSelector, {
                loop: true,
                defaultAnimation: {
                    duration: 100    // 애니메이션 지속 시간 (ms)
                },
                drag: false,  //드래그 비활성화
                detailsChanged: function(s) {
                    s.slides.forEach(function(element, idx) {
                        element.style.opacity = s.track.details.slides[idx].portion;
                    });
                },
                renderMode: 'custom' // 커스텀 렌더 모드로 설정하여 detailsChanged에서 직접 스타일 조정
            }, [addActiveClass]);

            var thumbnails = new KeenSlider(thumbnailSliderSelector, {
                initial: 0,
                slides: {
                    perView: 4
                },
                vertical: true, // 세로 방향으로 설정
                drag: false // 드래그 비활성화
            }, [
                ThumbnailPlugin(mslider), // 메인 슬라이더와 연동하는 플러그인
                sliderArrows
            ]);

            return {
                main: mslider,
                thumbnails: thumbnails
            };
        };

        // 세로 슬라이더 초기화 (히스토리, 최근본공고 등)
        // @param {string} selector - 슬라이더 선택자
        // @param {boolean} showArrows - 화살표 표시 여부 (기본값: true)
        window.initVerticalSlider = function(selector, showArrows) {
            if (typeof KeenSlider === 'undefined') return;

            var element = document.querySelector(selector);
            if (!element) return;

            // 기존 인스턴스 정리
            if (element.__KeenSliderInstance) {
                element.__KeenSliderInstance.destroy();
            }

            // 플러그인 목록 설정
            var plugins = [addActiveClass];
            if (showArrows !== false) {  // 기본값: 화살표 표시
                plugins.push(sliderArrows);
            }

            var slider = new KeenSlider(selector, {
                initial: 0, // 초기 슬라이드 인덱스
                // 슬라이드 레이아웃 설정
                slides: {
                    perView: 6 // 한 번에 6개씩 보이도록 설정
                },
                vertical: true,
                drag: true
            }, plugins);

            element.__KeenSliderInstance = slider;
            return slider;
        };

        // DOMContentLoaded 시 자동 초기화
        function autoInitSliders() {
            // 이미지 갤러리 슬라이더 (썸네일) - 페이지 로드 시에만
            if (document.querySelector('.visual-slider') && document.querySelector('.thumb-slider')) {
                initThumbnailSlider('.visual-slider', '.thumb-slider .slider-wrap');
            }

            // 내가 본 공고 슬라이더 (세로) - 페이지 로드 시, 화살표 없음
            if (document.querySelector('.recently-slider')) {
                initVerticalSlider('.recently-slider', false);
            }
        }

        // DOMContentLoaded 또는 이미 로드된 경우
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', autoInitSliders);
        } else {
            autoInitSliders();
        }
    }());

}());
