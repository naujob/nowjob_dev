<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>급여입력</title>
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Apple SD Gothic Neo", "Noto Sans KR", "Malgun Gothic", "Nanum Gothic", sans-serif;
      background: #ffffff;
      color: #777;
      font-size: 14px; 
    }
    .wrap { max-width: 760px; margin: 48px auto; padding: 0 16px; }
    .row { display: flex; gap: 8px; flex-direction: column; }
    .extraField { display: flex; gap: 8px; flex-direction: column; }
    .checks { display: grid; grid-template-columns: repeat(auto-fit, minmax(60px, 1fr)); gap: 10px; }

    .chip2 input[type="radio"] { display: none; }
    
    
    
    .checks2 { display: flex; gap: 12px;}
    .chip2 { position: relative; padding-left: 28px; cursor: pointer; }
    .chip2.on{color: #57CCAF;} 
    .chip2::before {
      content: "";
      display: block;
      position: absolute;
      left: 5px;
      width: 1.25rem;
      height: 1.25rem;
      background: url(https://nowjob.co.kr/resource/shop/mobile/img/ic_chkbox_off.svg) no-repeat 0 0;
      background-size: contain;
      border-radius: 100%;
      z-index: 1;
    }
    .chip2.on::before {
      background: url(https://nowjob.co.kr/resource/shop/mobile/img/ic_chkbox_on.svg) no-repeat 0 0;
      background-size: contain;
    }
    .amountField{display: flex; gap: 8px; height:34px;width: 50%;}
    .amountField .amount { display: flex; gap: 4px; width: 100%; align-items: center; border-radius: 17px; border: 1px solid #57CCAF; }
    .amountField.disabled .amount{border: 1px solid #e5e7eb; background: #e5e7eb;}

    .amountField .amount input[type="text"] { width: 100%; margin-left: 16px; border: none; outline: none;}
    .amountField .amount input[type="text"]:focus {  }  

    .unit { white-space: nowrap; padding-right: 12px; }
    .hide { display: none !important; }
    .result { background: #0b1020; margin: 50px 0; color: #fff; border-radius: 12px; padding: 16px; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace; overflow: auto; font-size: 13px; }
    
    
    .preview .pay {
      display: flex;
      flex-direction: column;
    } 
    .preview .pay .period {
      display: flex;
      gap: 5px;
      justify-content: flex-end;
      position: relative;
      margin: 0;
      font-size: 16px;
      line-height: 1.1;
      font-weight: 600;
    }   
    .preview .pay .period em {
      position: relative;
      display: inline-block;
      font-style: normal;
      color: #777;
    }
    .preview .pay .period em:not(:last-child) {
      padding-right: 5px;
    }
    .preview .pay .period em:not(:last-child)::after {
      content: "";
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      width: 1px;
      height: 0.5em;
      margin: auto;
      border-right: 1px solid #777777;
    }
    .preview .pay .period em.on {
      color: #1B9D7D;
    }
    .preview .pay .amount {
      display: flex;
      justify-content: flex-end;
      position: relative;
      margin: 3px 0 0;
      font-size: 22px;
      font-weight: 700;
      color: #1B9D7D;
      letter-spacing: 0;
      line-height: 1.1;
    }
    .preview .pay .amount .num {
      font-style: normal;
      font-weight: 600;
    }
    
    .pay-select-wrap {
      display: flex;
      gap:5px;
      position: relative;
      width: 100%;
    }
    .pay-select {
      position: relative;
      width: 50%;
      font-size: 14px;
    }
    .pay-select-trigger {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #57CCAF;
      border: 1px solid #57CCAF;
      border-radius: 17px;
      height: 34px;
      padding: 0 30px;
      cursor: pointer;
      color: #fff;
      z-index: 5;
    }
    .pay-select-trigger:after {
      content: "";
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      width: 8px;
      height: 6px;
      background-repeat: no-repeat;
      background-position: center;
      background-size: contain;
      background-image: url("data:image/svg+xml;utf8,%3Csvg%20width%3D%228%22%20height%3D%226%22%20viewBox%3D%220%200%208%206%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20opacity%3D%221%22%20d%3D%22M3.19%204.876a1%201%200%200%200%201.62%200l2.376-3.29A1%201%200%200%200%206.376%200H1.624a1%201%200%200%200-.811%201.585l2.375%203.291z%22%20fill%3D%22%23fff%22/%3E%3C/svg%3E");
      transition: transform ease 0.2s;
    }
    .pay-select-trigger.active:after {
      transform: translateY(-50%) rotate(180deg);
    }
    .pay-select-options {
      display: none;
      flex-direction: column; /* 세로 배치 */
      gap: 0;
      position: absolute;
      top: 17px;
      padding-top:17px;
      left: 0;
      width: 100%;
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 0 0 17px 17px;
      overflow: hidden;
      z-index: 2;
    }

    .pay-select-option {
      height: 34px;
      padding: 7px 12px;
      cursor: pointer;
      text-align: center;
    }
    .pay-select-option:hover {
      background: #57CCAF;
      color: #fff;
    }
 
    .amountNotice { padding:0;margin:0;}

    .amountNotice p {
      display: inline-block;
      padding-left: 20px;
      margin: 0;
      background: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iIzMxQTE4MiIgdmlld0JveD0iMCAwIDE2IDE2Ij4KICA8cGF0aCBkPSJNOCAxNkE4IDggMCAxIDAgOCAwYTggOCAwIDAgMCAwIDE2bS45My05LjQxMi0xIDQuNzA1Yy0uMDcuMzQuMDI5LjUzMy4zMDQuNTMzLjE5NCAwIC40ODctLjA3LjY4Ni0uMjQ2bC0uMDg4LjQxNmMtLjI4Ny4zNDYtLjkyLjU5OC0xLjQ2NS41OTgtLjcwMyAwLTEuMDAyLS40MjItLjgwOC0xLjMxOWwuNzM4LTMuNDY4Yy4wNjQtLjI5My4wMDYtLjM5OS0uMjg3LS40NzFsLS40NTEtLjA4MS4wODItLjM4MSAyLjI5LS4yODd6TTggNS41YTEgMSAwIDEgMSAwLTIgMSAxIDAgMCAxIDAgMiIvPgo8L3N2Zz4=) no-repeat left center;
      background-size: 12px 12px;
      font-size: 0.8125rem;
      font-style: normal;
      font-weight: 400;
    }    
    .amountNotice span{ color:red;}
    
.form-label {
    display: block;
    position: relative;
    margin: 0 0 0.875rem;
    font-size: 0.9375rem;
    font-weight: 700;
    color: #222;
    line-height: 1.1;
    text-align: left;
}
.form-label:after {
    content: "";
    display: table;
    clear: both;
}
.form-label > em {
    display: inline-block;
    margin: 1px 0 0 6px;
    font-size: 0.8125rem;
    color: #1B9D7D;
    font-style: normal;
    font-weight: 400;
    line-height: 1.1538461538;
    vertical-align: top;
}
.form-label .option {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    position: absolute;
    top: 0;
    right: 0;
}
 
  </style>
</head>
<body>
<?php
  $rc_price_type_list = ["시급", "일급", "주급", "월급", "협의"];
  $rc_price_type_unit = [
    "시급" => "원/시",
    "일급" => "원/일",
    "주급" => "원/주",
    "월급" => "원/월",
    "협의" => "-"
  ];
  $info['rc_price_type'] = "일급,월급";
  $info['rc_price_rep'] = "일급";
  $info['rc_price_amount'] = "200000";

  function getConvertPriceType($rc_price_type, $rc_price_rep) {
    
    if ($rc_price_rep === "협의") {
      return '';
    }
    
    $types = explode(',', $rc_price_type);

    if (count($types) === 1) {
      return '<em class="on">' . htmlspecialchars($types[0]) . '</em>';
    }

    $result = array_map(function($type) use ($rc_price_rep) {
      if ($type === $rc_price_rep) {
        return '<em class="on">' . htmlspecialchars($type) . '</em>';
      } else {
        return '<em>' . htmlspecialchars($type) . '</em>';
      }
    }, $types);

    return implode('', $result);
  } 

?>
<div class="wrap">
  <div class="preview">
    <div class="pay">
      <p class="period"><?= getConvertPriceType($info['rc_price_type'], $info['rc_price_rep']) ?></p>
      <p class="amount">
        <em class="num">
          <?= ($info['rc_price_rep'] === '협의' ? '협의가능' : number_format($info['rc_price_amount']) . '원') ?>
        </em>
      </p>
    </div>
  </div>      

  <div class="result" id="resultBox">주의!! 기준 급여형태를 선택해주세요.</div>
  <div class="row">


      

<div class="form-label">급여형태 <div class="amountNotice option"><p>최저임금제도 : 시급 <span>10,030</span>원</p></div></div>
    <div class="pay-select-wrap">
      <div class="pay-select" id="priceSelect">
        <div class="pay-select-trigger"><?= htmlspecialchars($info['rc_price_rep']) ?></div>
        <div class="pay-select-options">
          <?php foreach($rc_price_type_list as $row): ?>
            <div class="pay-select-option" data-value="<?= $row ?>">
              <?= $row ?>
            </div>
          <?php endforeach; ?>
        </div>
        <input type="hidden" name="rc_price_rep" id="rc_price_rep" value="<?= htmlspecialchars($info['rc_price_rep']) ?>">
      </div>
      <div class="amountField" id="amountField">
        <div class="amount">
          <input type="text" 
                 class="form-text" 
                 id="rc_price_amount" 
                 value="<?= number_format($info['rc_price_amount']) ?>" 
                 placeholder="금액 입력" />
          <span class="unit" id="amountUnit">
            <?= isset($rc_price_type_unit[$info['rc_price_rep']]) ? $rc_price_type_unit[$info['rc_price_rep']] : '' ?>
          </span>
        </div>
      </div>
    </div>

    

    <div class="extraField <?= ($info['rc_price_rep'] === '협의' ? 'hide' : '') ?>" id="extraField">

      <div class="checks2" id="extraChecks">
        <?php foreach($rc_price_type_list as $row) : ?>
          <?php if ($row !== $info['rc_price_rep'] && $row !== "협의") : ?>
            <label class="chip2 <?= (strpos($info['rc_price_type'], $row) !== false ? 'on' : '') ?>">
              <input type="radio" 
                     name="rc_price_extra" 
                     value="<?=$row?>" 
                     <?= (strpos($info['rc_price_type'], $row) !== false ? 'checked' : '') ?> /> 
              <span><?=$row?>가능</span>
            </label>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    $(function(){
      const PAY_TYPES = ["시급", "일급", "주급", "월급", "협의"];
      const UNIT_BY_TYPE = { "시급": "원/시", "일급": "원/일", "주급": "원/주", "월급": "원/월", "협의": "-" };
      const $amountUnit = $("#amountUnit"), $amountField = $("#amountField"), $amountInput = $("#rc_price_amount"), $extraField = $("#extraField"), $extraChecks = $("#extraChecks"), $resultBox = $("#resultBox");

      function getConvertPriceType(rc_price_type, rc_price_rep) {
        
        if (rc_price_rep === "협의") {
          return '';
        }
        
        const types = rc_price_type.split(',');

        if (types.length === 1) {
          return `<em class="on">${types[0]}</em>`;
        }

        return types.map(type => {
          if (type === rc_price_rep) {
            return `<em class="on">${type}</em>`;
          } else {
            return `<em>${type}</em>`;
          }
        }).join('');
      }
  
      function buildExtraChecks(rep){
        $extraChecks.empty();
        const extras = PAY_TYPES.filter(t => t !== rep && t !== "협의");
        extras.forEach((t,i)=>{
          $extraChecks.append(`<label class="chip2"><input type="radio" name="rc_price_extra" value="${t}" /> <span>${t}가능</span></label>`);
        });
      }

      function setVisibility(rep){
        const isNeg = rep === "협의";
        $extraField.toggleClass("hide", isNeg);
        if(isNeg) $amountInput.val("");
      }

      $(document).on("change", "#rc_price_rep", function() {
        const val = $(this).val();

        $amountInput.val("");
        $amountUnit.text(UNIT_BY_TYPE[val] || "원");

        if (!val) {
          $extraChecks.empty();
          $extraField.addClass("hide");
          result();
          return;
        }

        setVisibility(val);

        if (val !== "협의") {
          buildExtraChecks(val); 
          $amountInput.attr("placeholder", "금액 입력").prop("disabled", false);
          $amountField.removeClass("disabled");
        } else {
          $extraChecks.empty();
          $extraField.addClass("hide");
          $amountInput.attr("placeholder", "").prop("disabled", true);
          $amountField.addClass("disabled");
        }
        result();
      });

      $(document).on("click", 'input[name="rc_price_extra"]', function(e){
        const $this = $(this);
        const name = $this.attr('name');
        const val = $this.val();

        $(`input[name="${name}"]`).closest('label').removeClass('on');

        if ($this.data("waschecked") === true) {
          $this.prop("checked", false).data("waschecked", false);

          result();
          e.preventDefault();
          return;
        }
        $(`input[name="${name}"]`).data("waschecked", false);
        $this.data("waschecked", true).closest('label').addClass('on');

        result();
      });

      $amountInput.on("input", function(){
        let val = $(this).val().replace(/,/g, "");
        if(val && !isNaN(val)){
          $(this).val(Number(val).toLocaleString());
        }
        result();
      });
      
      function result() {
        const rep = $('#rc_price_rep').val();
        if (!rep) { 
          $resultBox.text("주의!! 기준 급여형태를 선택해주세요."); 
          return; 
        }

        const isNeg = rep === "협의";
        let amount = null;

        if (!isNeg) {
          amount = $amountInput.val().replace(/,/g, "");
          if (!amount) { 
            $resultBox.text("주의!! 급여액을 입력해주세요."); 
            return; 
          }
          amount = Number(amount);
        }

        const rc_price_type = [];
        rc_price_type.push(rep);

        $('input[name="rc_price_extra"]:checked').each(function() {
          rc_price_type.push($(this).val());
        });

        rc_price_type.sort((a, b) => PAY_TYPES.indexOf(a) - PAY_TYPES.indexOf(b));

        $(".preview .period").html(getConvertPriceType(rc_price_type.join(','), rep));
        $(".preview .amount").html(isNeg ? "협의가능" : amount.toLocaleString() + "원");

        $resultBox.text(JSON.stringify({
          rc_price_type,
          rc_price_rep: rep,
          rc_price_amount: amount
        }, null, 2));
      }


      const $select = $('#priceSelect');
      const $trigger = $select.find('.pay-select-trigger');
      const $options = $select.find('.pay-select-options');
      const $hiddenInput = $('#rc_price_rep');

      $trigger.on('click', function(e) {
        e.stopPropagation();
        $options.toggle();
        $trigger.toggleClass('active');
      });

      $options.on('click', '.pay-select-option', function(e) {
        const val = $(this).data('value');
        $trigger.text(val);
        $hiddenInput.val(val).trigger('change');
        $options.hide();
        $trigger.removeClass('active');
        $('.pay-select-option').removeClass('selected');
        $(this).addClass('selected');
      });

      $(document).on('click', function(e) {
        if (!$(e.target).closest('#priceSelect').length) {
          $options.hide();
          $trigger.removeClass('active');
        }
      });
      
      
      result();
    
    });
    
  </script>
</body>
</html>
