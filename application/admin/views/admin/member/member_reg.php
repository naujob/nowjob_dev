<form id="frm" class="controls" method="post">
  <input type="hidden" name="mm_idx" value="" />
  <input type="hidden" name="proc_mode" value="" />
  <input type="hidden" name="mm_type" id="mm_type" value="<?= $type ?>" />

  <div class="contents-frm">
    <div class="section">
      <h3 class="sub-title mb-10"><?= ($type == "COMP" ? "기업" : "개인") ?>회원생성</h3>
      <table class="table-form">
        <colgroup>
          <col width="180px" />
          <col width="600px" />
          <col width="180px" />
          <col width="600px" />
        </colgroup>
        <tbody>
          <tr>
            <th scope="col">아이디</th>
            <td>
              <div class="input-text ">
                <input type="tel" id="mm_id" name="mm_id" style="max-width: 150px;" maxlength="12" pattern="[0-9]{10,12}">
                예) 01012345678 (핸드폰번호입력)
              </div>
            </td>
            <th scope="col">회원명</th>
            <td>
              <div class="input-text ">
                <label>
                  <input type="text" id="mm_name" name="mm_name" style="max-width: 150px;" maxlength="50">
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <th scope="col">성별</th>
            <td>
              <div class="input-text ">
                <select id="mm_sex" name="mm_sex">
                  <option value="남">남</option>
                  <option value="여">여</option>
                </select>
              </div>
            </td>
            <th scope="col">국적</th>
            <td>
              <div class="input-text ">
                <label>
                  <select id="mm_nation" name="mm_nation">
                    <option value="내국인">내국인</option>
                    <option value="외국인">외국인</option>
                  </select>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <th scope="col">생년월일</th>
            <td>
              <div class="input-text ">
                <label>
                  <input type="text" id="mm_birth" value="<?= $info["mm_birth"] ?>" style="max-width: 100px;" maxlength="8" pattern="[0-9]{8}">
                  예) YYYYMMDD
                </label>
              </div>
            </td>
            <th scope="col">휴대폰번호<em class="ess"></em></th>
            <td class="inline-wrap">
              <div class="input-text  mt-5">
                <label>
                  <input type="tel" id="mm_phone" value="<?= $info["mm_phone"] ?>" style="max-width: 100px;" maxlength="12" pattern="[0-9]{10,12}">
                  예) 01012345678
                </label>
              </div>
            </td>
          </tr>
          <tr style="<?= ($type == "COMP" ? "" : "display:none;") ?>">
            <th scope="col">사업자명<em class="ess"></em></th>
            <td class="inline-wrap">
              <div class="input-text  mt-5">
                <label>
                  <input type="text" id="mm_comp_name" value="<?= $info["mm_comp_name"] ?>" style="max-width: 200px;" maxlength="30">
                </label>
              </div>
            </td>
            <th scope="col">사업자번호</th>
            <td>
              <div class="input-text  mt-5">
                <label>
                  <input type="text" id="mm_comp_num" value="<?= $info["mm_comp_num"] ?>" style="max-width: 100px;" maxlength="10" pattern="[0-9]{10}">
                  예) 1231212345
                </label>
              </div>
            </td>
          </tr>
      </table>
    </div>

    <div class="btn-wrap inline-wrap mt-40 tc">
      <button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button>
      <button type="button" id="btnReg" class="btn-l btn-type6 w150 ml-10">생성</button>
    </div>
  </div>
</form>


<script>
  //리스트이동
  $("#btnList").click(function() {
    history.go(-1);
  });

  function isPhonenum(text) {
    const phonePattern = /^010\d{8,10}$/;
    if (!phonePattern.test(text)) {
      return false;
    }
    return true;
  }

  function isYYYYMMDD(text) {
    const datePattern = /^(19|20)\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])$/;
    if (!datePattern.test(text)) {
      //alert("올바른 생년월일(YYYYMMDD) 형식을 입력하세요. 예: 19900101");
      return false;
    }
    return true;
  }

  $("#btnReg").click(function() {
    if ($('#mm_id').val() == '') {
      alert('아이디를 입력하세요');
      return;
    }
    if (isPhonenum($('#mm_id').val()) === false) {
      alert('아이디를 핸드폰번호 형태로 입력하세요');
      return;
    }
    if ($('#mm_name').val() == '') {
      alert('회원명을 입력하세요');
      return;
    }
    if ($('#mm_birth').val() == '') {
      alert('생년월일을 입력하세요');
      return;
    }
    if (isYYYYMMDD($('#mm_birth').val()) === false) {
      alert('생년월일을 YYYYMMDD 형태로 입력하세요');
      return;
    }
    if ($('#mm_phone').val() == '') {
      alert('휴대폰번호를 입력하세요');
      return;
    }
    if (isPhonenum($('#mm_phone').val()) === false) {
      alert('휴대폰번호를 휴대폰번호 형태로 입력하세요');
      return;
    }
    <?php if ($type == "COMP") { ?>
      if ($('#mm_comp_name').val() == '') {
        alert('사업자명을 입력하세요');
        return;
      }
      if ($('#mm_comp_num').val() == '') {
        alert('사업자번호를 입력하세요');
        return;
      }
    <?php } ?>

    COMMON.confirm("회원정보를 생성하시겠습니까?", function() {
      var params = {};
      params["mm_id"] = $('#mm_id').val();
      params["mm_name"] = $('#mm_name').val();
      params["mm_birth"] = $('#mm_birth').val();
      params["mm_nation"] = $('#mm_nation').val();
      params["mm_sex"] = $('#mm_sex').val();
      params["mm_phone"] = $('#mm_phone').val();
      params["mm_comp_name"] = $('#mm_comp_name').val();
      params["mm_comp_num"] = $('#mm_comp_num').val();
      params["mm_type"] = $('#mm_type').val();

      comAjax("/admin/member/member_reg_proc", params, function(result) {
        if (result.code == "00") {
          COMMON.alert(result.msg, function() {
            <?php if ($type == "COMP") { ?>
              location.replace('./comp_list');
            <?php } else { ?>
              location.replace('./member_list');
            <?php } ?>

          });
        } else {
          COMMON.alert(result.msg);
        }
      });
    }, function() {

    });
  });
</script>