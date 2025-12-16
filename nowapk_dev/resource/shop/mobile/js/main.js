(function () {
    'use strict';

    /* 맨위로 가기 */
    (function setBtnTop () {
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
                elm.scrollTo(0,0);
            });
        });
    }());

    /* 홈메뉴 열기,닫기 */
    (function setBottomNav () {
        if (!document.querySelector('.bottom-nav')) return;

        var menu = document.querySelector('.bottom-nav .menu');

        function toggleMenu () {
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
    (function setLazyLoadImg () {
        if (typeof lozad == 'undefined') return;

        var observer = lozad();
        observer.observe();
    }());

    /* 모달 팝업 */
    (function setModal () {

        function setFocus ($el) {
            if (!$el) return false;

            var $focusableEl = $el.querySelectorAll('a, button, input, select, textarea');
            var $firstEl = $el.querySelector('[autofocus]')||$focusableEl[0];
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
            var title =  options.title ? options.title.toString() : '알림';
            var content = options.content || '내용이 없습니다.';
            var firstButton = options.firstButton ? options.firstButton.toString() : '';
            var secondButton = options.secondButton ? options.secondButton.toString() : '';
            var thirdButton = options.thirdButton ? options.thirdButton.toString() : '';
            var firstCallback = options.firstCallback || null;
            var secondCallback = options.secondCallback || null;
            var thirdCallback = options.thirdCallback || null;
            var removeClose = options.removeClose==false ? '' : ' js-close-popup ';
            var clickOnce = options.clickOnce ==false ? false : true;

            if(type == 'confirm') {
                id = id ? id + index : 'confirmDialog' + index;
                firstButton = firstButton ? firstButton : '취소';
                secondButton = secondButton ? secondButton : '확인';
                thirdButton = thirdButton ? '<button type="submit" aria-controls="'+ id +'" class="btn btn-tertiary '+removeClose+' js-popup-action3">'+ thirdButton +'</button>' : thirdButton;

                html = '<div id="'+ id +'" class="layer-popup">'+
                    '<div role="alertdialog" aria-labelledby="confirmTitle'+ index +'" aria-modal="true" class="popup-container">' +
                    '<h4 id="confirmTitle'+ index +'" class="sr-text">'+ title +'</h4>' +
                    '<button type="button" aria-controls="'+ id +'" class="btn-close-popup  js-close-popup"><span class="sr-text">닫기</span></button>' +
                    '<div class="popup-content">' +
                    '<div class="msg">'+ content +'</div>' +
                    '</div> <div class="popup-footer">' +
                    '<button type="button" aria-controls="'+ id +'" class="btn btn-secondary  '+removeClose+' js-popup-action1">'+ firstButton +'</button>'+
                    '<button type="submit" aria-controls="'+ id +'" class="btn btn-primary '+removeClose+' js-popup-action2">'+ secondButton +'</button>' + thirdButton +
                    '</div> </div> </div>';
            } else if (type == 'alert') {
                id = id ? id + index : 'alertDialog' + index;
                html = '<div id="'+ id +'" class="layer-popup"><div role="alertdialog" aria-labelledby="alertTitle'+ index +'" aria-modal="true" class="popup-container">' +
                    '<h4 id="alertTitle'+ index +'" class="sr-text">'+ title +'</h4>' +
                    '<button type="button" aria-controls="'+ id +'" class="btn-close-popup  js-close-popup"><span class="sr-text">닫기</span></button>' +
                    '<div class="popup-content">' +
                    '<div class="msg">'+ content +'</div>' +
                    '</div> <div class="popup-footer">' +
                    '<button type="button" aria-controls="'+ id +'" class="btn btn-primary '+removeClose+' js-popup-action1">확인</button>' +
                    '</div> </div> </div>';
            } else if (type == 'alert2') {
                id = id ? id + index : 'alertDialog' + index;
                html = '<div id="'+ id +'" class="layer-popup"><div role="alertdialog" aria-labelledby="alertTitle'+ index +'" aria-modal="true" class="popup-container">' +
                    '<h4 id="alertTitle'+ index +'" class="sr-text">'+ title +'</h4>' +
                    '<button type="button" aria-controls="'+ id +'" class="btn-close-popup js-close-popup"><span class="sr-text">닫기</span></button>' + // 닫기버튼추가 2025-09-19
                    '<div class="popup-content">' +
                    '<div class="msg">'+ content +'</div>' +
                    '</div> <div class="popup-footer">' +
                    '<button type="button" aria-controls="'+ id +'" class="btn btn-primary '+removeClose+' js-popup-action1">확인</button>' +
                    '</div> </div> </div>';
            } else if (type == 'modal') {
                id = id ? id + index : 'defaultDialog' + index;

                if (firstButton && !secondButton) {
                    firstButton = '<button type="button" aria-controls="'+ id +'" class="btn btn-primary '+removeClose+' js-popup-action1">'+ firstButton +'</button>';
                } else if (firstButton && secondButton) {
                    firstButton = '<button type="button" aria-controls="'+ id +'" class="btn btn-secondary '+removeClose+' js-popup-action1">'+ firstButton +'</button>';
                    secondButton = '<button type="button" aria-controls="'+ id +'" class="btn btn-primary  '+removeClose+' js-popup-action2">'+ secondButton +'</button>';
                }

                thirdButton = thirdButton ? '<button type="button" aria-controls="'+ id +'" class="btn btn-tertiary '+removeClose+' js-popup-action3">'+ thirdButton +'</button>' : thirdButton;

                html = '<div id="'+ id +'" class="layer-popup"><div role="dialog" aria-labelledby="popupTitle'+ index +'" aria-modal="true" class="popup-container">' +
                '<h4 id="popupTitle'+ index +'" class="sr-text">'+ title +'</h4>' +
                '<button type="button" aria-controls="'+ id +'" class="btn-close-popup  js-close-popup"><span class="sr-text">닫기</span></button>' +
                '<div class="popup-content">' + content + '</div>' +
                (firstButton ? '<div class="popup-footer">'+ firstButton + secondButton + thirdButton +'</div>' : '')+
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
                }, {once: clickOnce});
            });

            //if (type === 'alert') return;

            if (firstButton && firstCallback && typeof firstCallback === 'function') {
                $popup.querySelector('.js-popup-action1').addEventListener('click', function (e) {
                    setTimeout(function () {
                        firstCallback();
                    }, 100);
                }, {once: clickOnce});
            }

            if (secondButton && secondCallback && typeof secondCallback === 'function') {
                $popup.querySelector('.js-popup-action2').addEventListener('click', function (e) {
                    setTimeout(function () {
                        secondCallback();
                    }, 100);
                }, {once: clickOnce});
            }

            if (thirdButton && thirdCallback && typeof thirdCallback === 'function') {
                $popup.querySelector('.js-popup-action3').addEventListener('click', function (e) {
                    setTimeout(function () {
                        thirdCallback();
                    }, 100);
                }, {once: clickOnce});
            }
        };

    }());

    /* 레이어 패널 */
    (function setTogglePanel() {

        window.openPanel = function ($el) {
            if (!$el) return;

            document.body.style.overflow = 'hidden';
            $el.classList.add('on');

            setTimeout(function () {
                $el.focus();
            }, 100);
        };

        window.closePanel = function ($el) {
            if (!$el) return;
            $el.classList.remove('on');
            document.body.style.overflow = 'auto';

            $el.querySelectorAll('input, textarea, select').forEach(function (item) {
                item.value = '';
            });

            $el.querySelectorAll('form').forEach(function (form) {
                form.reset();
            });

            $el.scrollTo(0,0);
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

        document.querySelectorAll('.layer-pannel').forEach(function (elm) {
            var $focusableEl = elm.querySelectorAll('a, button, input, select, textarea');
            var $firstEl = elm.querySelector('[autofocus]')||$focusableEl[0];
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
    (function setTooltip () {

        function show (el) {
            // el이 유효한 DOM 요소인지 확인
            if (!el || !el.parentNode || typeof el.parentNode.querySelector !== 'function') return;
            
            var $tooltip = el.parentNode.querySelector('.tooltip');
            var offset = 27,
                xpos = el.offsetLeft + (el.offsetWidth / 2) - offset,
                ypos = el.offsetTop + el.offsetHeight;

            if (!$tooltip) return;

            if (!$tooltip.classList.contains('right') && xpos + $tooltip.offsetWidth > window.innerWidth) {
                $tooltip.classList.remove('center','left');
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

        ['click','mouseover'].forEach(function(etype) {
            window.addEventListener(etype, function (e){
                if(e.target.classList && !e.target.classList.contains('js-toggle-tooltip')) {
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
    (function setFormFilePreview () {
        function previewPic (input) {
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
    (function setFormFile () {
        function showFilename (container, input) {
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
   (function setRangeSlider () {
       if (!document.querySelector('.js-range-slider')) return;

       function chgValue (slider, input) {
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
               fill.style.clipPath = 'inset(0px '+ (100 - ((parseInt(maxEl.value) / rangeMax * 100) - (rangeMin / rangeMax * 100))) +'% 0px ' + ((parseInt(minEl.value) / rangeMax * 100) - (rangeMin / rangeMax * 100)) +'%)';
           } else {
               fill.style.clipPath = 'inset(0px '+ (100 - ((parseInt(maxEl.value) / rangeMax * 100) - (rangeMin / rangeMax * 100))) +'% 0px 0px)';
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
    (function initKeenSlider () {
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
                    dot.innerHTML = '<span>'+ (idx+1) +'</span>';
                    dot.addEventListener("click", function () {
                        slider.moveToIdx(idx);
                    });
                    dots.appendChild(dot);
                });

                container.parentNode.insertBefore(dots, container.nextSibling);
            }

            function updateClasses() {
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
                var slide = slider.track.details.rel;
                slide === 0 ?
                    arrowLeft.classList.add("arrow--disabled") :
                    arrowLeft.classList.remove("arrow--disabled");
                slide === slider.track.details.slides.length - 1 ?
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
    (function setBtnBookmark () {
        function toggle (btn) {
            if (btn.classList.contains('on')) {
                btn.children[0].textContent = btn.textContent.replace('해제','추가');
            } else {
                btn.children[0].textContent = btn.textContent.replace('추가','해제');
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
    (function setTabList () {
        var tabContainer = document.querySelectorAll('.tab-container');
        var maxWidth = window.innerWidth;
        var offset = 30;

        tabContainer.forEach(function (con) {
            var activeItem = con.querySelector('.on');

            con.scrollTo(0,0);

            if (activeItem && maxWidth < activeItem.offsetLeft + activeItem.offsetWidth) {
                setTimeout(function () {
                    con.scrollTo(activeItem.offsetLeft - offset, 0);
                }, 1);
            }
        });
    }());

    /* Detect touchEvent */
    (function detectTouchEvent () {
        var flag = false;
        var target = null;
        var direction = null;
        var startX, startY;

        function deleteBefore() {
            target.parentNode.classList.remove('is-swipe-left','is-swipe-right','is-swipe-down','is-swipe-up');
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

            target.addEventListener('touchend', onEnd, {once:true});
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
    (function deleteItem () {
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
    (function setAccordionList () {
        var currentEl = null;

        function toggle (el) {
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
    (function autoHeightTextarea () {
        ['input','keydown'].forEach(function (evt) {
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

        function getValue (el) {
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

}());
