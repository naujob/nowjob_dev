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
 * @param string $headerClass - 헤더 추가 클래스 (선택)
 * @param string $titleClass - 타이틀 추가 클래스 (선택)
 * @param string $contentClass - 본문 콘텐츠 클래스 (기본: bottom-sheet-text-content)
 */
function renderBottomSheet($id, $title, $content, $buttonText, $buttonId, $buttonOnclick = '', $containerClass = '', $headerClass = '', $titleClass = '', $contentClass = 'bottom-sheet-text-content')
{
?>
    <div id="<?php echo $id; ?>" class="layer-panel card-type" tabindex="-1">
        <div class="bottom-sheet-container <?php echo $containerClass; ?>">
            <!-- Handlebar (드래그용 가로선) -->
            <div class="bottom-sheet-handlebar"></div>

            <div class="bottom-sheet-header <?php echo $headerClass; ?>">
                <h2 class="bottom-sheet-title <?php echo $titleClass; ?>"><?php echo htmlspecialchars($title); ?></h2>
            </div>

            <div class="bottom-sheet-body">
                <div class="bottom-sheet-content">
                    <div class="<?php echo $contentClass; ?>">
                        <?php echo $content; ?>
                    </div>
                </div>
            </div>

            <div class="bottom-sheet-footer">
                <div class="bottom-sheet-sticky-bottom">
                    <button type="button" id="<?php echo $buttonId; ?>" <?php echo $buttonOnclick ? 'onclick="' . htmlspecialchars($buttonOnclick) . '"' : ''; ?> class="btn-bottom-sheet btn-bottom-sheet-primary btn-login-guide"><?php echo htmlspecialchars($buttonText); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php
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
 * @param string $headerClass - 헤더 추가 클래스 (선택)
 * @param string $titleClass - 타이틀 추가 클래스 (선택)
 * @param string $contentClass - 본문 콘텐츠 클래스 (기본: select-bottom-sheet-content-wrapper)
 */
function renderSelectBottomSheet($id, $title, $content, $button1Text, $button1Id, $button1Onclick = '', $button2Text, $button2Id, $button2Onclick = '', $containerClass = '', $headerClass = '', $titleClass = '', $contentClass = 'select-bottom-sheet-content-wrapper')
{
?>
    <div id="<?php echo $id; ?>" class="layer-panel card-type" tabindex="-1">
        <div class="bottom-sheet-container <?php echo $containerClass; ?>">
            <!-- Handlebar (드래그용 가로선) -->
            <div class="bottom-sheet-handlebar"></div>

            <div class="bottom-sheet-header <?php echo $headerClass; ?>">
                <h2 class="bottom-sheet-title <?php echo $titleClass; ?>"><?php echo htmlspecialchars($title); ?></h2>
            </div>

            <div class="bottom-sheet-body">
                <div class="bottom-sheet-content">
                    <div class="<?php echo $contentClass; ?>">
                        <?php echo $content; ?>
                    </div>
                </div>
            </div>

            <div class="bottom-sheet-footer">
                <div class="bottom-sheet-sticky-bottom select-bottom-sheet-buttons">
                    <button type="button" id="<?php echo $button1Id; ?>" <?php echo $button1Onclick ? 'onclick="' . htmlspecialchars($button1Onclick) . '"' : ''; ?> class="btn-bottom-sheet btn-bottom-sheet-secondary btn-select-bottom-sheet"><?php echo htmlspecialchars($button1Text); ?></button>
                    <button type="button" id="<?php echo $button2Id; ?>" <?php echo $button2Onclick ? 'onclick="' . htmlspecialchars($button2Onclick) . '"' : ''; ?> class="btn-bottom-sheet btn-bottom-sheet-primary btn-select-bottom-sheet"><?php echo htmlspecialchars($button2Text); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>