<link href="/resource/admin/css/ppurio.css" rel="stylesheet" />

<form id="frm" class="controls" method="post">
    <div class="contents-frm">
        <!-- 기업회원 -->
        <div class="section">
            <h3 class="sub-title mb-10">기업회원</h3>

            <!-- 지원자 알림 -->
            <h4 class="sub-title-sm mb-10">지원자 미열람 알림</h4>
            <table class="table-form table-katalk">
                <tbody>
                <tr>
                    <th class="td-label">카카오 채널 발송</th>
                    <th class="td-label">1차 알림<em class="ess"></em></th>
                    <td>
                        <input type="time" id="comp_apply_alarm1_time" name="comp_apply_alarm1_time" value="<?=getData($info,"comp_apply_alarm1_time","")?>" />
                    </td>
                    <th class="td-label">2차 알림<em class="ess"></em></th>
                    <td>
                        <input type="time" id="comp_apply_alarm2_time" name="comp_apply_alarm2_time" value="<?=getData($info,"comp_apply_alarm2_time","")?>" />
                    </td>
                    <th class="td-label">미열람 알림<em class="ess"></em></th>
                    <td class="narrow-cell">
                        <div class="input-radio">
                            <?=inputRadio("comp_apply_unread_day","comp_apply_unread_day",["2"=>"2일 후","3"=>"3일 후","7"=>"7일 후"],getData($info,"comp_apply_unread_day"))?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- 지원 접수 알림 -->
            <h4 class="sub-title-sm mb-10 mt-30">지원 접수 알림</h4>
            <table class="table-form table-katalk">
                <tbody>
                <tr>
                    <th class="td-label">카카오 채널 발송</th>
                    <th class="td-label">오전 배치<em class="ess"></em></th>
                    <td>
                        <input type="time" id="comp_request_alarm1_time" name="comp_request_alarm1_time" value="<?=getData($info,"comp_request_alarm1_time","")?>" />
                    </td>
                    <th class="td-label">오후 배치<em class="ess"></em></th>
                    <td>
                        <input type="time" id="comp_request_alarm2_time" name="comp_request_alarm2_time" value="<?=getData($info,"comp_request_alarm2_time","")?>" />
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- 이력서 매칭 알림 -->
            <h4 class="sub-title-sm mb-10 mt-30">이력서 매칭 알림</h4>
            <table class="table-form table-katalk">
                <tbody>
                <tr>
                    <th class="td-label">카카오 채널 발송</th>
                    <th class="td-label">1차 알림<em class="ess"></em></th>
                    <td>
                        <input type="time" id="comp_match_alarm1_time" name="comp_match_alarm1_time" value="<?=getData($info,"comp_match_alarm1_time","")?>" />
                    </td>
                    <th class="td-label">2차 알림<em class="ess"></em></th>
                    <td>
                        <input type="time" id="comp_match_alarm2_time" name="comp_match_alarm2_time" value="<?=getData($info,"comp_match_alarm2_time","")?>" />
                    </td>
                    <th class="td-label">매칭 발송 요일<em class="ess"></em></th>
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
        <div class="section mt-40">
            <h3 class="sub-title mb-10">개인회원</h3>

            <!-- 채팅 알림 -->
            <h4 class="sub-title-sm mb-10">채팅 미열람 알림</h4>
            <table class="table-form table-katalk">
                <tbody>
                <tr>
                    <th class="td-label">카카오 채널 발송</th>
                    <th class="td-label">1차 알림<em class="ess"></em></th>
                    <td>
                        <input type="time" id="mem_apply_alarm1_time" name="mem_apply_alarm1_time" value="<?=getData($info,"mem_apply_alarm1_time","")?>" />
                    </td>
                    <th class="td-label">2차 알림<em class="ess"></em></th>
                    <td>
                        <input type="time" id="mem_apply_alarm2_time" name="mem_apply_alarm2_time" value="<?=getData($info,"mem_apply_alarm2_time","")?>" />
                    </td>
                    <th class="td-label">미열람 알림<em class="ess"></em></th>
                    <td class="narrow-cell">
                        <div class="input-radio">
                            <?=inputRadio("mem_apply_unread_day","mem_apply_unread_day",["2"=>"2일 후","3"=>"3일 후","7"=>"7일 후"],getData($info,"mem_apply_unread_day"))?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- 제안 접수 알림 -->
            <h4 class="sub-title-sm mb-10 mt-30">제안 접수 알림</h4>
            <table class="table-form table-katalk">
                <tbody>
                <tr>
                    <th class="td-label">카카오 채널 발송</th>
                    <th class="td-label">오전 배치<em class="ess"></em></th>
                    <td>
                        <input type="time" id="mem_request_alarm1_time" name="mem_request_alarm1_time" value="<?=getData($info,"mem_request_alarm1_time","")?>" />
                    </td>
                    <th class="td-label">오후 배치<em class="ess"></em></th>
                    <td>
                        <input type="time" id="mem_request_alarm2_time" name="mem_request_alarm2_time" value="<?=getData($info,"mem_request_alarm2_time","")?>" />
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- 채용공고 매칭 알림 -->
            <h4 class="sub-title-sm mb-10 mt-30">채용공고 매칭 알림</h4>
            <table class="table-form table-katalk">
                <tbody>
                <tr>
                    <th class="td-label">카카오 채널 발송</th>
                    <th class="td-label">1차 알림<em class="ess"></em></th>
                    <td>
                        <input type="time" id="mem_match_alarm1_time" name="mem_match_alarm1_time" value="<?=getData($info,"mem_match_alarm1_time","")?>" />
                    </td>
                    <th class="td-label">2차 알림<em class="ess"></em></th>
                    <td>
                        <input type="time" id="mem_match_alarm2_time" name="mem_match_alarm2_time" value="<?=getData($info,"mem_match_alarm2_time","")?>" />
                    </td>
                    <th class="td-label">매칭 발송 요일<em class="ess"></em></th>
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

        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
        </div>
    </div>
</form>

<script>
$(document).ready(function(){
    $("#btnSave").click(function(){
        var params = $("#frm").serialize();
        comAjaxLoading("/admin/ppurio/ppurio_katalk_subs_proc", params, function(result){
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
