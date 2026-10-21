<?php

/**
 * 공통 바텀시트 템플릿 함수
 * 
 * @param string $id - 바텀시트 ID
 * @param string $title - 바텀시트 제목
 * @param string $content - 본문 내용 (HTML)
 * @param string $buttonText - 버튼 텍스트
 * @param string $buttonId - 버튼 ID
 * @param string $buttonOnclick - 버튼 onclick 이벤트
 * @param string $containerClass - 컨테이너 추가 클래스 (선택)
 * @param string $contentClass - 본문 콘텐츠 클래스 (기본: bottom-sheet-text-content)
 */
function renderBottomSheetBase($id, $title, $content, $buttons, $containerClass = '', $contentClass = 'bottom-sheet-text-content', $footerWrap = true)
{
    $buttons = is_array($buttons) ? array_values($buttons) : [];
    $buttonCount = count($buttons);
    $actionsClass = 'bottom-sheet-actions' . ($buttonCount > 1 ? ' bottom-sheet-actions--split' : '');
?>
    <div id="<?php echo $id; ?>" class="layer-panel card-type" tabindex="-1" data-disable-bg-close="true">
        <div class="bottom-sheet-container <?php echo $containerClass; ?>">
            <!-- Handlebar (드래그용 가로선) -->
            <div class="bottom-sheet-handlebar"></div>

            <?php // 우측 상단 X 버튼: 배경 클릭이 막힌 시트도 닫을 수 있도록 공통 제공 ?>
            <button type="button" class="bottom-sheet-close-btn" aria-label="닫기" onclick="event.stopPropagation(); closePanel(this.closest('.layer-panel'));"></button>

            <div class="bottom-sheet-header">
                <h2 class="bottom-sheet-title"><?php echo htmlspecialchars($title); ?></h2>
                <?php // 우측 상단 X 닫기 버튼: closePanel()로 현재 바텀시트만 닫음 ?>
                <button type="button" class="bottom-sheet-close" onclick="closePanel(document.getElementById('<?php echo htmlspecialchars($id); ?>'));"><span class="sr-text">닫기</span></button>
            </div>

            <div class="bottom-sheet-body">
                <div class="bottom-sheet-content">
                    <div class="<?php echo $contentClass; ?>">
                        <?php echo $content; ?>
                    </div>
                </div>
            </div>

            <?php // $footerWrap=false면 bottom-sheet-footer 부모 태그 없이 버튼 영역만 출력 ?>
            <?php if ($footerWrap): ?>
            <div class="bottom-sheet-footer">
            <?php endif; ?>
                <div class="bottom-sheet-sticky-bottom <?php echo $actionsClass; ?>">
                    <?php foreach ($buttons as $button): ?>
                        <?php
                            $btnText = isset($button['text']) ? $button['text'] : '';
                            $btnId = isset($button['id']) ? $button['id'] : '';
                            $btnOnclick = isset($button['onclick']) ? $button['onclick'] : '';
                            $btnVariant = isset($button['variant']) ? $button['variant'] : 'primary';
                        ?>
                        <button type="button" id="<?php echo htmlspecialchars($btnId); ?>" <?php echo $btnOnclick ? 'onclick="' . htmlspecialchars($btnOnclick) . '"' : ''; ?> class="btn-bottom-sheet btn-bottom-sheet-<?php echo htmlspecialchars($btnVariant); ?> bottom-sheet-action"><?php echo htmlspecialchars($btnText); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php if ($footerWrap): ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
<?php
}

function renderBottomSheet($id, $title, $content, $buttonText, $buttonId, $buttonOnclick = '', $containerClass = '', $contentClass = 'bottom-sheet-text-content')
{
    $buttons = [
        [
            'text' => $buttonText,
            'id' => $buttonId,
            'onclick' => $buttonOnclick,
            'variant' => 'primary'
        ]
    ];
    renderBottomSheetBase($id, $title, $content, $buttons, $containerClass, $contentClass);
}

/**
 * 선택형 바텀시트 템플릿 함수 (버튼 2개)
 * 
 * @param string $id - 바텀시트 ID
 * @param string $title - 바텀시트 제목
 * @param string $content - 본문 내용 (HTML)
 * @param string $button1Text - 첫 번째 버튼 텍스트
 * @param string $button1Id - 첫 번째 버튼 ID
 * @param string $button1Onclick - 첫 번째 버튼 onclick 이벤트
 * @param string $button2Text - 두 번째 버튼 텍스트
 * @param string $button2Id - 두 번째 버튼 ID
 * @param string $button2Onclick - 두 번째 버튼 onclick 이벤트
 * @param string $containerClass - 컨테이너 추가 클래스 (선택)
 * @param string $contentClass - 본문 콘텐츠 클래스 (기본: bottom-sheet-content-body)
 * @param bool $footerWrap - bottom-sheet-footer 부모 태그 출력 여부 (기본: true)
 */
function renderSelectBottomSheet($id, $title, $content, $button1Text, $button1Id, $button1Onclick = '', $button2Text, $button2Id, $button2Onclick = '', $containerClass = '', $contentClass = 'bottom-sheet-content-body', $footerWrap = true)
{
    $buttons = [
        [
            'text' => $button1Text,
            'id' => $button1Id,
            'onclick' => $button1Onclick,
            'variant' => 'secondary'
        ],
        [
            'text' => $button2Text,
            'id' => $button2Id,
            'onclick' => $button2Onclick,
            'variant' => 'primary'
        ]
    ];
    renderBottomSheetBase($id, $title, $content, $buttons, $containerClass, $contentClass, $footerWrap);
}
?>
