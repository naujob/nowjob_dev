<div class="page page-join">
  <div class="page-content" style="display:flex;align-items:center;justify-content:center;height:100vh;">
    <p style="font-size:16px;color:#666;">회원가입 처리중입니다...</p>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var params = {};
    params["ma_type"] = "<?= $ma_type ?>";
    params["proc_type"] = "ALL";
    params["uuid"] = "<?= $uuid ?>";
    try {
      var joinType = localStorage.getItem("join_sns_type") || sessionStorage.getItem("join_sns_type") || "";
      var joinKey = localStorage.getItem("join_sns_key") || sessionStorage.getItem("join_sns_key") || "";
      if (joinType) params["join_type"] = joinType;
      if (joinKey) params["join_key"] = joinKey;
    } catch (e) {}

    comAjax("/member_proc/join_complete_proc", params, function (ret) {
      if (ret.code == "00") {
        try {
          localStorage.removeItem("join_sns_type");
          localStorage.removeItem("join_sns_key");
          sessionStorage.removeItem("join_sns_type");
          sessionStorage.removeItem("join_sns_key");
          document.cookie = "join_sns_type=; path=/; max-age=0";
          document.cookie = "join_sns_key=; path=/; max-age=0";
        } catch (e) {}

        // 개인회원: 자동이력서 완성 flow로 이동
        if ("<?= $ma_type ?>" == "MEM") {
          location.href = "/member/join_mem_finish";
        } else {
          // 기업회원: 기업가입 완료 페이지
          location.href = "/member/join_comp_finish";
        }
      } else {
        commonAlert(ret.msg || "회원가입 처리 중 오류가 발생했습니다.", function () {
          location.href = "/member/join_start";
        });
      }
    });
  });
</script>
