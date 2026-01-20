<?php
$platforms = [
    'android' => 'Android',
    'ios' => 'iOS',
];
?>
<div class="contents-frm">

    <?php foreach ($platforms as $key => $label) :
        $policy = isset($policies[$key]) ? $policies[$key] : [];
    ?>
        <form id="frm_<?= $key ?>" class="controls" method="post">
            <input type="hidden" name="platform" value="<?= $key ?>">
            <div class="section mb-30">
                <h3 class="sub-title mb-10"><?= $label ?> 앱 업데이트 정책</h3>
                <p class="exc1 mb-15">
                    <?= $label ?> 측 버전 정책을 조정하면 서버가 반환하는 JSON이 바뀌며, 앱은 그 결과를 기반으로 강제/권장/선택 업데이트 팝업을 띄웁니다.
                </p>
                <table class="table-form">
                    <colgroup>
                        <col width="200px">
                        <col width="*">
                    </colgroup>
                    <tbody>
                        <tr>
                            <th scope="col">강제(min) 버전</th>
                            <td>
                                <div class="input-text w200">
                                    <input type="text" name="min_version" value="<?= isset($policy['min_version']) ? $policy['min_version'] : '' ?>" placeholder="예: 2.0.0">
                                </div>
                                <p class="exc1">이 버전 미만은 사용을 차단합니다.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">최신(latest) 버전</th>
                            <td>
                                <div class="input-text w200">
                                    <input type="text" name="latest_version" value="<?= isset($policy['latest_version']) ? $policy['latest_version'] : '' ?>" placeholder="예: 2.3.0">
                                </div>
                                <p class="exc1">이 버전보다 낮으면 선택 업데이트 안내를 보여줍니다.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">강제 안내 메시지</th>
                            <td>
                                <div class="input-text w400">
                                    <textarea name="message_force" rows="2" placeholder="강제 업데이트 팝업에 보여줄 문구"><?= isset($policy['message_force']) ? $policy['message_force'] : '' ?></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">권장 안내 메시지</th>
                            <td>
                                <div class="input-text w400">
                                    <textarea name="message_optional" rows="2" placeholder="선택 업데이트 팝업 문구"><?= isset($policy['message_optional']) ? $policy['message_optional'] : '' ?></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">스토어 URL</th>
                            <td>
                                <div class="input-text w400">
                                    <input type="text" name="store_url" value="<?= isset($policy['store_url']) ? $policy['store_url'] : '' ?>" placeholder="https://play.google.com/...">
                                </div>
                                <p class="exc1">PlayStore / AppStore 링크를 입력하면 앱이 그 주소로 이동합니다.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="btn-wrap inline-wrap mt-15 tc">
                <button type="button" class="btn-l btn-type10 w150 ml-10 btn-version-save" data-platform="<?= $key ?>">저장하기</button>
            </div>
        </form>
    <?php endforeach; ?>

</div>

<script>
$(document).ready(function(){
    $(".btn-version-save").on("click", function(){
        var platform = $(this).data("platform");
        var params = $("#frm_" + platform).serialize();
        comAjaxLoading("/admin/setting/version_policy/index_proc", params, function(result){
            if(result.code == "00"){
                COMMON.alert(result.msg, function(){
                    location.reload();
                });
            }else{
                COMMON.alert(result.msg);
            }
        });
    });
});
</script>
