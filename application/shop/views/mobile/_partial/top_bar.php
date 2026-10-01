<?php
/**
 * top-bar 컴포넌트
 *
 * 사용 예시:
 * $top_bar = array(
 *     'title'      => '페이지 제목',
 *     'back_type'  => 'back'|'replace'|'none'  (기본값: 'back')
 *     'back_href'  => '#'  (기본값: '#')
 *     'right_html' => '우측 영역 HTML'
 * );
 * include_once(get_base_url() . '/application/shop/views/mobile/_partial/top_bar.php');
 */

// 변수 기본값 처리 (PHP 5.4 호환 - isset 사용)
$_tb_title     = isset($top_bar['title'])     ? $top_bar['title']     : '';
$_tb_back_type = isset($top_bar['back_type']) ? $top_bar['back_type'] : 'back';
$_tb_back_href = isset($top_bar['back_href']) ? $top_bar['back_href'] : '#';
$_tb_right_html= isset($top_bar['right_html'])? $top_bar['right_html']: '';
?>
<div class="top-bar">
    <?php if ($_tb_back_type === 'back'): ?>
        <a href="/recruit/job_list" class="btn-back"><span class="sr-text">이전으로</span></a>
    <?php elseif ($_tb_back_type === 'replace'): ?>
        <a href="<?php echo $_tb_back_href; ?>" class="btn-replace"><span class="sr-text">이전으로</span></a>
    <?php endif; ?>

    <h1 class="title" style="margin: 0 0 0 11px;"><?php echo $_tb_title; ?></h1>

    <?php if (!empty($_tb_right_html)): ?>
        <?php echo $_tb_right_html; ?>
    <?php endif; ?>
</div><!-- //top-bar -->
    <!--사용한 변수 지우기-->
<?php unset($top_bar, $_tb_title, $_tb_back_type, $_tb_back_href, $_tb_right_html); ?>
