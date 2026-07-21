<link href="/resource/admin/css/ppurio.css" rel="stylesheet" />

<form id="frm" class="controls" method="post">
    <div class="contents-frm">
        <!-- 탭 버튼 -->
        <div class="ppurio-tab js-tab">
            <a href="javascript:void(0);" data-tab_id="#tab_comp" class="on">기업회원</a>
            <a href="javascript:void(0);" data-tab_id="#tab_mem">개인회원</a>
        </div>

        <div class="tab-content-wrap">
        <!-- 기업회원 -->
        <div id="tab_comp" class="section tab-article on">
            <h3 class="sub-title mb-10">기업회원</h3>

            <!-- 지원 접수 알림 -->
            <h4 class="sub-title-sm mb-10">신규 지원자 알림</h4>
            <table class="table-form table-katalk">
                <tbody>
                <tr>
                    <th class="td-label">카카오 발송</th>
                    <th class="td-label-small">1차 발송시간<em class="ess"></em></th>
                    <td>
                        <input type="time" id="comp_request_alarm1_time" name="comp_request_alarm1_time" value="<?=getData($info,"comp_request_alarm1_time","")?>" />
                    </td>
                    <th class="td-label-small">2차 발송시간<em class="ess"></em></th>
                    <td>
                        <input type="time" id="comp_request_alarm2_time" name="comp_request_alarm2_time" value="<?=getData($info,"comp_request_alarm2_time","")?>" />
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- 지원자 알림 -->
            <h4 class="sub-title-sm mb-10 mt-30">지원자 미열람 재알림</h4>
            <table class="table-form table-katalk">
                <tbody>
                <tr>
                    <th class="td-label">카카오 발송</th>
                    <th class="td-label-small">1차 발송시간<em class="ess"></em></th>
                    <td>
                        <input type="time" id="comp_apply_alarm1_time" name="comp_apply_alarm1_time" value="<?=getData($info,"comp_apply_alarm1_time","")?>" />
                    </td>
                    <th class="td-label-small">2차 발송시간<em class="ess"></em></th>
                    <td>
                        <input type="time" id="comp_apply_alarm2_time" name="comp_apply_alarm2_time" value="<?=getData($info,"comp_apply_alarm2_time","")?>" />
                    </td>
                    <th class="td-label-small">미열람 재발송 기간<em class="ess"></em></th>
                    <td class="narrow-cell">
                        <div class="input-radio">
                            <?=inputRadio("comp_apply_unread_day","comp_apply_unread_day",["2"=>"2일 후","3"=>"3일 후","7"=>"7일 후"],getData($info,"comp_apply_unread_day"))?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- 이력서 매칭 알림 -->
            <h4 class="sub-title-sm mb-10 mt-30">이력서 매칭 알림</h4>
            <table class="table-form table-katalk">
                <tbody>
                <tr>
                    <th class="td-label">PUSH/카카오 발송</th>
                    <th class="td-label-small">발송시간<em class="ess"></em></th>
                    <td>
                        <input type="time" id="comp_match_alarm1_time" name="comp_match_alarm1_time" value="<?=getData($info,"comp_match_alarm1_time","")?>" />
                    </td>
                    <th class="td-label-small">매칭요일<em class="ess"></em></th>
                    <td class="narrow-cell">
                        <div class="input-checkbox">
                            <?php
                            $comp_days_arr = isset($info["comp_match_days"]) ? explode("^", $info["comp_match_days"]) : [];
                            $days_list = ["MON"=>"월","TUE"=>"화","WED"=>"수","THU"=>"목","FRI"=>"금","SAT"=>"토","SUN"=>"일"];
                            foreach ($days_list as $day_code => $day_name) {
                                $checked = in_array($day_code, $comp_days_arr) ? "checked" : "";
                                echo "<label><input type='checkbox' name='comp_match_days[]' value='{$day_code}' {$checked} /><span>{$day_name}</span></label>";
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <tr class="cond-row">
                    <th class="td-label">이력서 매칭 조건<em class="ess"></em></th>
                    <td colspan="6">
                        <div class="input-checkbox">
                            <?php
                            $comp_cond_arr = isset($info["comp_match_cond"]) ? explode("^", $info["comp_match_cond"]) : [];
                            $cond_list = ["area"=>"지역","nation"=>"국적","sex"=>"성별","age"=>"나이","work"=>"업종","price"=>"급여","work_type"=>"근무형태"];
                            foreach ($cond_list as $cond_code => $cond_name) {
                                $checked = in_array($cond_code, $comp_cond_arr) ? "checked" : "";
                                echo "<label><input type='checkbox' name='comp_match_cond[]' value='{$cond_code}' {$checked} /><span>{$cond_name}</span></label>";
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- 개인회원 -->
        <div id="tab_mem" class="section tab-article">
            <h3 class="sub-title mb-10">개인회원</h3>

            <!-- 제안 접수 알림 -->
            <h4 class="sub-title-sm mb-10">신규 제안공고 알림</h4>
            <table class="table-form table-katalk">
                <tbody>
                <tr>
                    <th class="td-label">카카오 발송</th>
                    <th class="td-label send-time">1차 발송시간<em class="ess"></em></th>
                    <td>
                        <input type="time" id="mem_request_alarm1_time" name="mem_request_alarm1_time" value="<?=getData($info,"mem_request_alarm1_time","")?>" />
                    </td>
                    <th class="td-label send-time">2차 발송시간<em class="ess"></em></th>
                    <td>
                        <input type="time" id="mem_request_alarm2_time" name="mem_request_alarm2_time" value="<?=getData($info,"mem_request_alarm2_time","")?>" />
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- 채팅 알림 -->
            <h4 class="sub-title-sm mb-10 mt-30">제안공고 미열람 재알림</h4>
            <table class="table-form table-katalk">
                <tbody>
                <tr>
                    <th class="td-label">카카오 발송</th>
                    <th class="td-label">1차 발송시간<em class="ess"></em></th>
                    <td>
                        <input type="time" id="mem_apply_alarm1_time" name="mem_apply_alarm1_time" value="<?=getData($info,"mem_apply_alarm1_time","")?>" />
                    </td>
                    <th class="td-label">2차 발송시간<em class="ess"></em></th>
                    <td>
                        <input type="time" id="mem_apply_alarm2_time" name="mem_apply_alarm2_time" value="<?=getData($info,"mem_apply_alarm2_time","")?>" />
                    </td>
                    <th class="td-label">미열람 재발송 기간<em class="ess"></em></th>
                    <td class="narrow-cell">
                        <div class="input-radio">
                            <?=inputRadio("mem_apply_unread_day","mem_apply_unread_day",["2"=>"2일 후","3"=>"3일 후","7"=>"7일 후"],getData($info,"mem_apply_unread_day"))?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- 채용공고 매칭 알림 -->
            <h4 class="sub-title-sm mb-10 mt-30">채용공고 매칭 알림</h4>
            <table class="table-form table-katalk">
                <tbody>
                <tr>
                    <th class="td-label">PUSH/카카오 발송</th>
                    <th class="td-label">발송시간<em class="ess"></em></th>
                    <td>
                        <input type="time" id="mem_match_alarm1_time" name="mem_match_alarm1_time" value="<?=getData($info,"mem_match_alarm1_time","")?>" />
                    </td>
                    <th class="td-label">매칭요일<em class="ess"></em></th>
                    <td class="narrow-cell">
                        <div class="input-checkbox">
                            <?php
                            $mem_days_arr = isset($info["mem_match_days"]) ? explode("^", $info["mem_match_days"]) : [];
                            foreach ($days_list as $day_code => $day_name) {
                                $checked = in_array($day_code, $mem_days_arr) ? "checked" : "";
                                echo "<label><input type='checkbox' name='mem_match_days[]' value='{$day_code}' {$checked} /><span>{$day_name}</span></label>";
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <tr class="cond-row">
                    <th class="td-label">채용공고 매칭 조건<em class="ess"></em></th>
                    <td colspan="6">
                        <div class="input-checkbox">
                            <?php
                            $mem_cond_arr = isset($info["mem_match_cond"]) ? explode("^", $info["mem_match_cond"]) : [];
                            foreach ($cond_list as $cond_code => $cond_name) {
                                $checked = in_array($cond_code, $mem_cond_arr) ? "checked" : "";
                                echo "<label><input type='checkbox' name='mem_match_cond[]' value='{$cond_code}' {$checked} /><span>{$cond_name}</span></label>";
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        </div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
        </div>
    </div>
</form>

<script>
$(document).ready(function(){
    $("#btnSave").click(function(){
        var params = $("#frm").serialize();
        comAjaxLoading("/admin/ppurio/ppurio_katalk_usual_proc", params, function(result){
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
