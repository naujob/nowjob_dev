<?php
/**
 * 복리후생 섹션 컴포넌트
 *
 * 사용 예:
 * $benefit_section = [
 *     'title'   => '보험',
 *     'items'   => [
 *         ['value' => '국민연금', 'class' => 'benefit1-1'],
 *         ['value' => '통근버스운행', 'class' => 'benefit4-5', 'label' => '통근버스 운행', 'alt_values' => ['통근버스 운행']],
 *     ],
 *     'checked' => isset($info['rc_life_type']) ? $info['rc_life_type'] : [],
 * ];
 * $this->load->view('mobile/_partial/benefit_section', ['benefit_section' => $benefit_section]);
 */
?>
<div class="form-row">
    <h3 class="form-label"><?= htmlspecialchars($benefit_section['title'], ENT_QUOTES, 'UTF-8') ?></h3>
    <div class="form-toggle-group col-4">
        <?php foreach ($benefit_section['items'] as $_item): ?>
        <!-- 준비한 변수에서 물건 꺼내기  -->

            <!-- <script>
             console.log("======",<?= json_encode($benefit_section['items']) ?>);   
            console.log(<?= json_encode($_item) ?>);         
            </script> -->
            <?php
                $_checked = isset($benefit_section['checked']) ? $benefit_section['checked'] : [];
                // 기본 체크 + alt_values 하위 호환 처리
                $_is_checked = in_array($_item['value'], $_checked); // db에서 가져온 rc_life_type과 비교하여 체크 여부 결정
                if (!$_is_checked && !empty($_item['alt_values'])) {
                    foreach ($_item['alt_values'] as $_alt_value) {
                        if (in_array($_alt_value, $_checked)) {
                            $_is_checked = true;
                            break;
                        }
                    }
                }
                $_label = isset($_item['label']) ? $_item['label'] : $_item['value'];
            ?>

            <label class="form-toggle-btn3">
                <input type="checkbox" name="rc_life_type[]" value="<?= htmlspecialchars($_item['value'], ENT_QUOTES, 'UTF-8') ?>" <?= $_is_checked ? "checked" : "" ?>>
                <span class="btn benefit-label <?= htmlspecialchars($_item['class'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($_label, ENT_QUOTES, 'UTF-8') ?></span>
            </label>
        <?php endforeach; ?>
    </div>
</div>
