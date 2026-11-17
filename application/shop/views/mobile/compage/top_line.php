<div class="tab-container" style="position: -webkit-sticky; position: sticky; top: 0; z-index: 100; background: #fff;">
    <?php // 탭 이름은 t()로 번역 (포인트·광고 화면 상단 공통 탭) ?>
    <ul class="tab-list">
        <li class="<?=$tab_on=="tab4" ? "on":""?>"><a href="javascript:_replace('/compage/point_promotion_buy');" class="tab"><?= t('광고구매') ?></a></li>
        <li class="<?=$tab_on=="tab1" ? "on":""?>"><a href="javascript:_replace('/compage/point_charge_step1');" class="tab"><?= t('포인트충전') ?></a></li>
        <li class="<?=$tab_on=="tab2" ? "on":""?>"><a href="javascript:_replace('/compage/point_charge_list');" class="tab"><?= t('포인트충전내역') ?></a></li>
        <li class="<?=$tab_on=="tab3" ? "on":""?>"><a href="javascript:_replace('/compage/point_use_list');" class="tab"><?= t('포인트사용내역') ?></a></li>
        <li class="<?=$tab_on=="tab5" ? "on":""?>"><a href="javascript:_replace('/compage/point_promotion_list');" class="tab"><?= t('광고관리') ?></a></li>
    </ul>
</div>


