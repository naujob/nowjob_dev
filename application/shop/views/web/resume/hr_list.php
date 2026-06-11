<?php
$banner_list = getBannerList("BN00302041");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<script src="<?= RES_PATH ?>/js/ad-banner.js"></script>
<style>
.search-result{
  width: 103%;
}
  .search-result .content-area:first-child {
    margin-left: -63px;
  }
  .search-result .content-area {
    margin: 0 77px;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
  }

  .search-result .sidebar {
    width: 200px;
    height: 870px;
    -webkit-box-flex: 0;
    -ms-flex: 0 0 200px;
    flex: 0 0 200px;
    position: sticky;
    top: 90px;
    
  }

  /*--상세검색--*/

  dl,
  dt,
  dd,
  ul,
  ol,
  menu,
  li {
    list-style: none;
  }

  .search-option1 {
    width: 100%;
    height: 450px;
    margin: 0;
    display: flex;
    flex-direction: column;
    font-family: sans-serif;
  }

  .search-cont {
    padding: 20px;
    background: #ffffff;
    display: flex;
    border: 1px solid #E8E8E8;
    border-radius: 5px 5px 0 0;
  }

  .search-input {
    display: flex;
    align-items: center;
    height: 100%;
    width: 50%;
  }

  .search-cnt {
    display: flex;
    align-items: center;
    height: 100%;
    width: 50%;
    font-size: 1rem;
    justify-content: flex-end;
  }

  .search-cnt .addr_sel_cnt {
    color: #57CCAF;
    font-weight: 600;
    margin: 0 5px;
  }

  .search-input input {
    flex: 1;
    height: 30px;
    padding: 5px 10px;
    font-size: 14px;
    background-color: #fff;
    border: 1px solid #E8E8E8;
    border-radius: 3px;
  }

  .search-input .icon {
    margin-left: 5px;
  }

  .addr_list {
    flex: 1 1 auto;
    display: flex;
    flex-direction: row;
    min-height: 100px;
  }

  .search_addr,
  .addr_dept,
  .addr_dept1,
  .addr_dept2,
  .addr_dept3 {
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  .search_addr {
    width: 100%;
    border: 1px solid #E8E8E8;
    border-bottom: 0px;
  }

  .addr_dept {
    width: 100%;
    display: flex;
    flex-direction: row;
    border: 1px solid #E8E8E8;
    border-bottom: 0px;
  }

  .addr_dept1 {
    width: 25%;
    border-right: 1px solid #E8E8E8;
  }

  .addr_dept2 {
    width: 25%;
    border-right: 1px solid #E8E8E8;
  }

  .addr_dept3 {
    width: 50%;
  }

  .addr_title {
    padding: 5px 8px;
    background: #f9f9f9;
    font-weight: bold;
    flex: 0 0 auto;
    border-bottom: 1px solid #E8E8E8;
    text-align: center;
  }

  .search_addr ul,
  .addr_dept1 ul,
  .addr_dept2 ul,
  .addr_dept3 ul {
    flex: 1 1 auto;
    margin: 0;
    padding: 0;
    list-style: none;
    overflow-y: auto;
  }

  .addr_dept1 ul li.selectable:hover,
  .addr_dept2 ul li.selectable:hover,
  .addr_dept1 ul li.selectable.addr_on,
  .addr_dept2 ul li.selectable.addr_on {
    background-color: #31A182;
    color: #fff;
  }

  .search_addr ul li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0;
    position: relative;
    font-size: 1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
  }

  .search_addr ul li label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    cursor: pointer;
  }

  .search_addr ul li label:hover,
  .search_addr ul li.addr_on label {
    background-color: #fff;
    color: #31A182;
  }

  .search_addr ul li .text {
    flex: 1;
    overflow: hidden;
    white-space: nowrap;
    position: relative;
    text-overflow: ellipsis;
    cursor: pointer;
    padding: 0.5rem 1rem;
    padding-left: 2rem;
  }

  .search_addr ul li input[type="checkbox"] {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    position: absolute;
    width: 1.25rem;
    height: 1.25rem;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    z-index: 2;
    cursor: pointer;
  }

  .search_addr ul li label::before {
    content: "";
    display: block;
    position: absolute;
    left: 5px;
    width: 1.25rem;
    height: 1.25rem;
    background: url(/resource/shop/mobile/img/ic_chkbox2025_off.svg) no-repeat 0 0;
    background-size: contain;
    border-radius: 100%;
    z-index: 1;
  }

  .search_addr ul li.addr_on label::before {
    background: url(/resource/shop/mobile/img/ic_chkbox2025_on.svg) no-repeat 0 0;
    background-size: contain;
  }

  .addr_list_sel_box {
    flex: 0 0 auto;
    max-height: 150px;
    overflow-y: auto;
    padding: 10px;
    box-sizing: border-box;
    border: 1px solid #E8E8E8;
    border-radius: 0 0 5px 5px;
  }

  .addr_dept1 ul li,
  .addr_dept2 ul li,
  .addr_dept3 ul li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0;
    position: relative;
    font-size: 1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
  }

  .addr_dept1 ul li label,
  .addr_dept2 ul li label,
  .addr_dept3 ul li label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    cursor: pointer;
  }

  .addr_dept1 ul li label:hover,
  .addr_dept2 ul li label:hover,
  .addr_dept3 ul li label:hover,
  .addr_dept2 ul li.addr_on label,
  .addr_dept3 ul li.addr_on label {
    background-color: #fff;
    color: #31A182;
  }

  .addr_dept1 ul li .text,
  .addr_dept2 ul li .text,
  .addr_dept3 ul li .text {
    flex: 1;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    cursor: pointer;
    padding: 0.5rem 1rem;
  }

  .addr_dept1 ul li input[type="checkbox"],
  .addr_dept2 ul li input[type="checkbox"],
  .addr_dept3 ul li input[type="checkbox"] {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    position: absolute;
    width: 1.25rem;
    height: 1.25rem;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    z-index: 2;
    cursor: pointer;
  }

  .addr_dept1 ul li label::after,
  .addr_dept2 ul li label::after,
  .addr_dept3 ul li label::after {
    content: "";
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    width: 1.25rem;
    height: 1.25rem;
    background: url(/resource/shop/mobile/img/ic_chkbox2025_off.svg) no-repeat center center;
    background-size: contain;
    border-radius: 100%;
    z-index: 1;
  }

  .addr_dept1 ul li.addr_on label::after,
  .addr_dept2 ul li.addr_on label::after,
  .addr_dept3 ul li.addr_on label::after {
    background: url(/resource/shop/mobile/img/ic_chkbox2025_on.svg) no-repeat center center;
    background-size: contain;
  }

  .search-input {
    position: relative;
  }

  .form-text {
    width: 100%;
    padding-right: 36px;
    height: 40px;
    box-sizing: border-box;
  }

  .icon-btn {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
  }

  .icon-btn img {
    width: 20px;
    height: 20px;
  }

  .hidden {
    height: 0 !important;
    overflow: hidden !important;
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
    flex: 0 0 0 !important;
    opacity: 0;
    pointer-events: none;
  }

  .visible {
    height: auto;
    opacity: 1;
    flex: 1 1 auto;
    pointer-events: auto;
  }

  .search-option2 {
    display: flex;
    width: 100%;
    height: 450px;
    flex-direction: column;
  }

  .search-list-wrap {
    border: 1px solid #E8E8E8;
    border-bottom: 0px;
    border-radius: 5px 5px 0 0;
    padding-bottom: 1px;
  }

  .search-list {
    display: flex;
    flex-direction: row;
    position: relative;
    margin: 0;
    overflow: hidden;
  }

  .search-list-header {
    width: 100px;
    padding: 10px;
  }

  .search-list-header .title {
    font-weight: 700;
    padding: 10px;
  }

  .search-list-body {
    flex: 1;
    padding: 10px;
    box-sizing: border-box;
    overflow: auto;
  }

  .align-center {
    text-align: center !important;
  }

  .search-list-options ul {
    display: flex;
    flex-wrap: wrap;
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .search-list-options li {
    position: relative;
    margin: 10px;
    margin-left: 0;
    white-space: nowrap;
  }

  .search-list-option {
    color: #ACACAC;
    line-height: 1.5;
  }

  input[type="checkbox"],
  input[type="radio"] {
    display: none;
  }

  .search-list-options li label {
    cursor: pointer;
    padding: 5px 16px;
    font-size: 16px;
    color: #ACAEB9;
    line-height: 1.5;
    border: 1px solid #e8e8e8;
    border-radius: 5px;
    text-align: center;
    background-color: #ffffff;
  }

  .search-list-options li label.checked {
    border: 1px solid #31A182;
    background-color: #31A182;
    color: #fff;
  }

  /* 근무형태 스타일 20250923 */
  .search-list-options ul.work-type li label {
    display: inline-block;
    width: 60.26px;
    height: 31.11px;
    padding: 5px 8px;
    box-sizing: border-box;
    line-height: 1.2;
  }

  /* 근무요일 체크박스 크기 고정 */
  .search-list-options .left-week li label {
    display: inline-block;
    width: 59.89px;
    height: 30.91px;
    box-sizing: border-box;
    line-height: 1.2;
  }

  .search-wrap-body .filter-empty {
    display: block;
    font-size: 16px;
    color: #ACACAC;
    line-height: 80px;
    text-align: center;
    border: 1px solid #E8E8E8;
    border-radius: 0 0 5px 5px;
    min-height: 82px;
  }

  .search-wrap-body .filter-empty.on {
    display: none;
  }

  .search-wrap-body .filter-box {
    display: none;
    border: 1px solid #E8E8E8;
    border-radius: 0 0 5px 5px;
    min-height: 82px;
    padding: 10px;
  }

  .search-wrap-body .filter-box.on {
    display: flex;
    gap: 5px;
  }

  .search-wrap-body.on .filter-empty,
  .search-wrap-body.on .filter-box {
    border-radius: 5px;
  }

  .filter-box .search-selected-filters {
    position: relative;
  }

  .filter-box .search-selected-filters:after {
    display: block;
    clear: both;
    content: ''
  }

  .filter-box .search-selected-filters .searchSelBtn {
    float: left;
    padding: 6px 3px 0 0;
    font-size: 12px;
    color: #333
  }

  .filter-box .search-selected-filters .searchAddrBtn,
  .filter-box .search-selected-filters .searchSelBtn {
    float: left;
    margin-right: 5px;
    margin-bottom: 5px;
    padding: 5px 10px 4px;
    font-size: 14px;
    border: 1px solid #eee;
    border-radius: 5px;
    line-height: 1;
    color: #333;
    background-color: #fafafa;
    transition: background-color .5s;
    cursor: pointer;
  }

  .filter-box .search-selected-filters .searchAddrBtn .selBtnclose,
  .filter-box .search-selected-filters .searchSelBtn .selBtnclose {
    display: inline-block;
    background-repeat: no-repeat;
    background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iIzMzMyIgY2xhc3M9ImJpIGJpLXgiIHZpZXdCb3g9IjAgMCAxNiAxNiI+CiAgPHBhdGggZD0iTTQuNjQ2IDQuNjQ2YS41LjUgMCAwIDEgLjcwOCAwTDggNy4yOTNsMi42NDYtMi42NDdhLjUuNSAwIDAgMSAuNzA4LjcwOEw4LjcwNyA4bDIuNjQ3IDIuNjQ2YS41LjUgMCAwIDEtLjcwOC43MDhM OCA4LjcwN2wtMi42NDYgMi42NDdhLjUuNSAwIDAgMS0uNzA4LS43MDhMNy4yOTMgOCA0LjY0NiA1LjM1NGEuNS41IDAgMCAxIDAtLjcwOCIvPgo8L3N2Zz4=");
    overflow: hidden;
    width: 16px;
    height: 16px;
    margin: -2px 0 0 3px;
    font-size: 0;
    text-indent: -3000em;
    vertical-align: middle;
    background-position: center;
    background-size: contain;
    transition: transform ease 0.2s;
    cursor: pointer;
  }

  .filter-box .search-selected-filters .searchAddrBtn:hover,
  .filter-box .search-selected-filters .searchSelBtn:hover {
    background-color: #eee
  }

  .filter-box .search-selected-filters .searchAddrBtn:hover .selBtnclose,
  .filter-box .search-selected-filters .searchSelBtn:hover .selBtnclose {
    -ms-transform: rotate(90deg);
    transform: rotate(90deg)
  }

  .search-wrap {
    width: 100%;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    position: relative;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    border: 1px solid #E8E8E8;
    border-radius: 10px;
    background: #fff;
    margin-bottom: 15px;
  }

  .search-wrap-header {
    position: relative;
    padding: 10px 10px;
    background: #ffffff;
    border-radius: 10px 10px 0 0;
  }

  .search-wrap-title {
    font-weight: 700;
  }

  .search-wrap-title em {
    display: inline-block;
    font-style: normal;
    color: #1B9D7D;
  }

  .search-wrap-header:after {
    content: "";
    display: table;
    clear: both;
  }

  .search-wrap-body {
    position: relative;
    padding: 0 10px 10px 10px;
    margin-top: -10px;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    overflow: hidden;
  }

  .search-wrap-body.on {
    padding: 10px;
    margin-top: 0px;
  }

  .search-wrap-reset {
    position: absolute;
    right: 10px;
    top: 7px;
  }

  .search-wrap-reset a {
    display: inline-block;
    padding: 5px 10px 4px;
    line-height: 1;
    color: #000 !important;
    font-size: 12px;
    transition: all .3s
  }

  .search-wrap-reset a span {
    display: inline-block;
    background-repeat: no-repeat;
    background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iIzMzMyIgY2xhc3M9ImJpIGJpLXgiIHZpZXdCb3g9IjAgMCAxNiAxNiI+CiAgPHBhdGggZD0iTTQuNjQ2IDQuNjQ2YS41LjUgMCAwIDEgLjcwOCAwTDggNy4yOTNsMi42NDYtMi42NDdhLjUuNSAwIDAgMSAuNzA4LjcwOEw4LjcwNyA4bDIuNjQ3IDIuNjQ2YS41LjUgMCAwIDEtLjcwOC43MDhM OCA4LjcwN2wtMi42NDYgMi42NDdhLjUuNSAwIDAgMS0uNzA4LS43MDhMNy4yOTMgOCA0LjY0NiA1LjM1NGEuNS41IDAgMCAxIDAtLjcwOCIvPgo8L3N2Zz4=");
    overflow: hidden;
    width: 16px;
    height: 16px;
    margin: -2px 0 0 3px;
    font-size: 0;
    text-indent: -3000em;
    vertical-align: middle;
    background-position: center;
    background-size: contain;
    transition: transform ease 0.2s;
  }

  .search-wrap-reset a:hover span {
    -ms-transform: rotate(90deg);
    transform: rotate(90deg)
  }

  .search-wrap-part-reset {
    display: none;
    position: absolute;
    right: 10px;
    bottom: 14px;
    margin-right: 14px;
    margin-bottom: 12px;
    border: 1px solid #E8E8E8;
    border-radius: 5px;
    color: #333;
    cursor: pointer;
  }

  .search-wrap-part-reset.on {
    display: block;
  }

  .search-wrap-part-reset a {
    display: inline-block;
    padding: 5px 10px 4px;
    line-height: 1;
    color: #333;
    background-color: #fafafa;
    border-radius: 4px;
    font-size: 12px;
    transition: all .3s
  }

  .search-wrap-part-reset a span {
    display: inline-block;
    background-repeat: no-repeat;
    background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iIzMzMyIgY2xhc3M9ImJpIGJpLXgiIHZpZXdCb3g9IjAgMCAxNiAxNiI+CiAgPHBhdGggZD0iTTQuNjQ2IDQuNjQ2YS41LjUgMCAwIDEgLjcwOCAwTDggNy4yOTNsMi42NDYtMi42NDdhLjUuNSAwIDAgMSAuNzA4LjcwOEw4LjcwNyA4bDIuNjQ3IDIuNjQ2YS41LjUgMCAwIDEtLjcwOC43MDhM OCA4LjcwN2wtMi42NDYgMi42NDdhLjUuNSAwIDAgMS0uNzA4LS43MDhMNy4yOTMgOCA0LjY0NiA1LjM1NGEuNS41IDAgMCAxIDAtLjcwOCIvPgo8L3N2Zz4=");
    overflow: hidden;
    width: 16px;
    height: 16px;
    margin: -2px 0 0 3px;
    font-size: 0;
    text-indent: -3000em;
    vertical-align: middle;
    background-position: center;
    background-size: contain;
    transition: transform ease 0.2s;
  }

  .search-wrap-part-reset a:hover span {
    -ms-transform: rotate(90deg);
    transform: rotate(90deg)
  }

  .search-option {
    display: none;
    padding: 10px;
  }

  .search-option.on {
    display: flex;
  }

  .searchOptionBtnWrap {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    padding: 10px;
  }

  .searchOptionBtn {
    background-color: #ffffff;
    color: #222222;
    font-size: 16px;
    line-height: 1.5;
    text-align: center;
    padding: 5px 16px;
    border: 1px solid #E8E8E8;
    border-radius: 5px;
    position: relative;
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    width: 140px;
  }

  .searchOptionBtn.on {
    border: 1px solid #ADAEB9;
  }

  .searchOptionBtn span {
    display: inline-block;
    background-repeat: no-repeat;
    background-image: url("data:image/svg+xml;utf8,%3Csvg%20width%3D%228%22%20height%3D%226%22%20viewBox%3D%220%200%208%206%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20opacity%3D%221%22%20d%3D%22M3.19%204.876a1%201%200%200%200%201.62%200l2.376-3.29A1%201%200%200%200%206.376%200H1.624a1%201%200%200%200-.811%201.585l2.375%203.291z%22%20fill%3D%22%23000%22/%3E%3C/svg%3E");
    overflow: hidden;
    width: 8px;
    height: 6px;
    margin-bottom: -2px;
    margin-left: auto;
    font-size: 0;
    text-indent: -3000em;
    vertical-align: middle;
    background-position: center;
    background-size: contain;
    transition: transform ease 0.2s;
  }

  .searchOptionBtn.on span {
    transform: rotate(180deg);
  }

  /*--------*/

  .arrow--disabled {
    display: none;
  }
</style>
<div id="content" class="page page-person page-hr-list">
  <main class="page-content">

    <!-- <div class="visual-banner person">
      <div class="container">
        <div class="visual-content">
          <p>새로 등록된 추천 인재</p>
          <p class="headline">지금 꼭, 필요한 인재</p>
          <a href="javascript:void(0);" id="btnRegist" class="btn-round">채용공고 등록</a>
        </div>
      </div>
    </div> -->

    <div class="recomm-slider person">
      <div class="container">
        <h2 class="primary-title">추천인재</h2>
        <div class="slider-wrap keen-slider" id="my-keen-slider">
        </div>
      </div>
    </div>

    <div class="section">
      <div class="container">
        <div class="search-result">

          <div class="content-area">
            <div class="search-wrap">

              <div class="search-wrap-header">
                <p class="search-wrap-title">인재정보 <em>상세검색</em></p>
                <div class="search-wrap-reset"><a href="#">전체 초기화<span></span></a></div>
              </div>

              <div class="searchOptionBtnWrap">
                <div class="searchOptionBtn" data-target="1">
                  지역
                  <span>^</span>
                </div>
                <div class="searchOptionBtn" data-target="2">
                  상세조건
                  <span>^</span>
                </div>
              </div>

              <div class="search-option search-option1">
                <div class="search-cont">
                  <div class="search-input">
                    <input type="text" id="txt_addr" name="txt_addr" placeholder="예시 : 신림동, 관악구" autocomplete="off" required class="form-text">
                    <button id="iconBtn" type="button" class="icon-btn">
                      <img id="iconImg" src="/resource/shop/mobile/img/ic_search2025.svg" alt="search icon">
                    </button>
                  </div>
                  <div class="search-cnt">
                    <span class="addr_sel_cnt">0</span> / 3
                  </div>
                </div>

                <div class="addr_list">
                  <div class="search_addr hidden">
                    <div class="addr_title">검색목록</div>
                    <ul>
                    </ul>
                  </div>
                  <div class="addr_dept visible">
                    <div class="addr_dept1">
                      <div class="addr_title">시·도</div>
                      <ul>
                        <li data-step="1" data-addr="서울특별시" class="selectable"><span class="text">서울</span></li>
                        <li data-step="1" data-addr="경기도" class="selectable"><span class="text">경기</span></li>
                        <li data-step="1" data-addr="인천광역시" class="selectable"><span class="text">인천</span></li>
                        <li data-step="1" data-addr="강원특별자치도" class="selectable"><span class="text">강원</span></li>
                        <li data-step="1" data-addr="대전광역시" class="selectable"><span class="text">대전</span></li>
                        <li data-step="1" data-addr="세종특별자치시" class="selectable"><span class="text">세종</span></li>
                        <li data-step="1" data-addr="충청남도" class="selectable"><span class="text">충남</span></li>
                        <li data-step="1" data-addr="충청북도" class="selectable"><span class="text">충북</span></li>
                        <li data-step="1" data-addr="부산광역시" class="selectable"><span class="text">부산</span></li>
                        <li data-step="1" data-addr="울산광역시" class="selectable"><span class="text">울산</span></li>
                        <li data-step="1" data-addr="경상남도" class="selectable"><span class="text">경남</span></li>
                        <li data-step="1" data-addr="경상북도" class="selectable"><span class="text">경북</span></li>
                        <li data-step="1" data-addr="대구광역시" class="selectable"><span class="text">대구</span></li>
                        <li data-step="1" data-addr="광주광역시" class="selectable"><span class="text">광주</span></li>
                        <li data-step="1" data-addr="전라남도" class="selectable"><span class="text">전남</span></li>
                        <li data-step="1" data-addr="전북특별자치도" class="selectable"><span class="text">전북</span></li>
                        <li data-step="1" data-addr="제주특별자치도" class="selectable"><span class="text">제주</span></li>
                      </ul>
                    </div>
                    <div class="addr_dept2">
                      <div class="addr_title">시·군·구</div>
                      <ul>
                      </ul>
                    </div>
                    <div class="addr_dept3">
                      <div class="addr_title">읍·면·동</div>
                      <ul>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <div class="search-option search-option2">
                <div class="search-list-wrap">
                  <div class="search-list">
                    <div class="search-list-header">
                      <p class="title">업 · 직종</p>
                    </div>
                    <div class="search-list-body">
                      <div class="search-list-options">
                        <ul>
                          <li><label><input type="checkbox" name="category" value="물류">물류</label></li>
                          <li><label><input type="checkbox" name="category" value="생산">생산</label></li>
                          <li><label><input type="checkbox" name="category" value="건설">건설</label></li>
                          <li><label><input type="checkbox" name="category" value="식당">식당</label></li>
                          <li><label><input type="checkbox" name="category" value="상담">상담</label></li>
                          <li><label><input type="checkbox" name="category" value="서비스">서비스</label></li>
                          <li><label><input type="checkbox" name="category" value="사무직">사무직</label></li>
                          <li><label><input type="checkbox" name="category" value="운전배달">운전배달</label></li>
                          <li><label><input type="checkbox" name="category" value="기타">기타</label></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="search-list">
                    <div class="search-list-header">
                      <p class="title">근무형태</p>
                    </div>
                    <div class="search-list-body">
                      <div class="search-list-options">
                        <ul class="work-type">
                          <li><label><input type="checkbox" name="work_type" value="주간">주간</label></li>
                          <li><label><input type="checkbox" name="work_type" value="야간">야간</label></li>
                          <li><label><input type="checkbox" name="work_type" value="2교대">2교대</label></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="search-list">
                    <div class="search-list-header">
                      <p class="title">급여형태</p>
                    </div>
                    <div class="search-list-body">
                      <div class="search-list-options">
                        <ul>
                          <li><label><input type="checkbox" name="price_type" value="시급">시급</label></li>
                          <li><label><input type="checkbox" name="price_type" value="일급">일급</label></li>
                          <li><label><input type="checkbox" name="price_type" value="주급">주급</label></li>
                          <li><label><input type="checkbox" name="price_type" value="월급">월급</label></li>
                          <li><label><input type="checkbox" name="price_type" value="협의">협의</label></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="search-list">
                    <div class="search-list-header">
                      <p class="title">근무요일</p>
                    </div>
                    <div class="search-list-body">
                      <div class="search-list-options">
                        <ul class="form-group left-week week-day">
                          <li><label><input type="checkbox" name="week" value="월">월</label></li>
                          <li><label><input type="checkbox" name="week" value="화">화</label></li>
                          <li><label><input type="checkbox" name="week" value="수">수</label></li>
                          <li><label><input type="checkbox" name="week" value="목">목</label></li>
                          <li><label><input type="checkbox" name="week" value="금">금</label></li>
                          <li><label><input type="checkbox" name="week" value="토">토</label></li>
                          <li><label><input type="checkbox" name="week" value="일">일</label></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="search-list">
                    <div class="search-list-header">
                      <p class="title">성별</p>
                    </div>
                    <div class="search-list-body">
                      <div class="search-list-options">
                        <ul>
                          <li><label><input type="checkbox" name="sex" value="남자">남자</label></li>
                          <li><label><input type="checkbox" name="sex" value="여자">여자</label></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="search-list">
                    <div class="search-list-header">
                      <p class="title">나이</p>
                    </div>
                    <div class="search-list-body">
                      <div class="search-list-options">
                        <ul class="age-group">
                          <li><label><input type="checkbox" name="eage" value="20대">20대</label></li>
                          <li><label><input type="checkbox" name="eage" value="30대">30대</label></li>
                          <li><label><input type="checkbox" name="eage" value="40대">40대</label></li>
                          <li><label><input type="checkbox" name="eage" value="50대">50대</label></li>
                          <li><label><input type="checkbox" name="eage" value="60대">60대</label></li>
                          <li><label><input type="checkbox" name="eage" value="70대">70대</label></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="search-wrap-body on">
                <div class="filter-empty">
                  상세검색 조건을 설정해주세요.
                </div>
                <div class="filter-box">
                  <div class="search-selected-filters">
                  </div>
                  <div class="search-wrap-part-reset"><a href="#">초기화<span></span></a></div>

                </div>
              </div>
            </div><!-- //search-wrap -->

            <div class="result-wrap">

              <div class="result-header">
                <p class="total">인재정보 <em id="total_count"></em>건</p>
                <div class="orderby">
                  <label class="option">
                    <input type="radio" name="order_by" value="recent" checked>
                    <span class="label">최신순</span>
                  </label>
                  <label class="option">
                    <input type="radio" name="order_by" value="fav">
                    <span class="label">인기순</span>
                  </label>
                </div>
              </div>

              <!-- 목록 -->
              <ol class="result-list" id="ajaxList" data-page="1" style="min-height:400px;">
              </ol>
              <!-- //목록 -->

              <div id="page_cont">
              </div>

              <div class="list-action v2">
                <a href="/resume/hr_map" title="지도에서 찾기" class="btn-map"><span class="sr-text">지도보기</span></a>
              </div>

              <script>
                /* 목록이 3개이하인경우 리스트액션 고정 */
                window.addEventListener('scroll', function() {
                  var minY = document.querySelector('.result-wrap').getBoundingClientRect().top - (window.innerHeight / 2);
                  var stickyElm = document.querySelector('.result-wrap .list-action');

                  if (document.querySelector('.result-list').children.length < 3) return;

                  if (minY <= document.body.scrollTop) {
                    stickyElm.style.position = 'sticky';
                  } else {
                    stickyElm.style.position = '';
                  }
                });
              </script>
            </div><!-- //result-wrap -->
          </div><!-- //content-area -->
          <div class="sidebar">
            <div class="adBanner adBanner2">
              <img src="/outer/ad/ad1.jpg" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
            </div>
            <div class="adBanner adBanner1">
              <a id="adBannerLink" href="" target="_blank">
                <img id="adBannerImg" src="" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
              </a>
            </div>
          </div>
        </div><!-- //search-result -->
      </div><!-- //container -->
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<!-- 인재 목록 템플릿 (공유) -->
<?php
$tpl_id               = 'tpl_resume_card';
$tpl_show_timestamp   = true;
$tpl_show_mm_idx      = true;
$tpl_price_wrap_width = '290px';
include APPPATH . 'views/web/_layout/resume_list_template.php';
?>

<!-- 추천 리스트 템플릿 -->
<?php
$reco_show_bookmark = true;
$reco_show_mm_idx   = true;
include APPPATH . 'views/web/_layout/reco_list_template.php';
?>

<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />

<script>
  var appBaseUrl = "<?php echo rtrim($this->config->item('app_base_url') ? $this->config->item('app_base_url') : base_url(), '/'); ?>";

  // 현재 로그인한 사용자 정보
  var currentUserMmIdx = <?php echo $this->user->isLogin() ? $this->user->getUserIdx() : 'null'; ?>;
  var currentUserType = '<?php echo isset($mm_type) ? $mm_type : ""; ?>';

  var list_id = "#ajaxList";
  var list_empty_id = "#ajaxEmpty";
  var list_page = 1;
  var list_loading = false;
  var search_addr = [];
  var searchAreaStr = "";
  var addrName = "";
  var search_array = {
    category: "",
    work_type: "",
    week: "",
    price_type: "",
    sex: "",
    sage: "",
    eage: "",
    order_by: "",
  };

  $(document).ready(function() {
    $('.btn-map').insertBefore('#bottomchatbtn');
    loadRandomAdBanner('hr');
    getRecoList();
    initPage(false);
  });

  // 값이 없을 때 trim에서 에러가 나지 않도록 안전하게 처리하는 헬퍼
  function safeTrim(value) {
    if (value === null || value === undefined) {
      return "";
    }
    return String(value).trim();
  }

  $("[name='order_by']").click(function() {
    initPage(true);
  });

  function initPage(is_top) {
    list_page = 1;
    getBoardList(is_top);
  }

  function goPage(page) {
    list_page = page;
    getBoardList(true);
  }

  function getCheckedValues(name) {
    var result = "";
    $("[name='" + name + "']:checked").each(function() {
      var val = $(this).val();
      if (val !== "") {
        result += (result === "" ? "" : "^") + val;
      }
    });
    return result;
  }

  function setBoardParams() {

    var arr_category = getCheckedValues("category");
    var arr_work_type = getCheckedValues("work_type");
    var arr_price_type = getCheckedValues("price_type");
    var arr_week = getCheckedValues("week");
    var arr_sex = getCheckedValues("sex");

    search_array.sage = "";
    search_array.eage = "";

    var checkedAges = $("[name='eage']:checked").map(function() {
      return parseInt($(this).val().replace("대", ""), 10);
    }).get();

    if (checkedAges.length > 0) {
      var minAge = Math.min(...checkedAges);
      var maxAge = Math.max(...checkedAges);
      search_array.sage = minAge;
      search_array.eage = maxAge + 9;
    }

    search_array.category = arr_category;
    search_array.work_type = arr_work_type;
    search_array.week = arr_week;
    search_array.price_type = arr_price_type;
    search_array.sex = arr_sex;
    search_array.order_by = $("[name='order_by']:checked").val();
  }

  function getBoardList(is_top) {
    setTimeout(function() {
      getBoardListDelay(is_top);
    }, 500);
  }

  function getBoardListDelay(is_top) {
    setBoardParams();
    var params = {
      page: list_page,
      pagesize: 20,
      "search_addr[]": search_addr,
      addrName: addrName,
      searchAreaStr: searchAreaStr,
      category: search_array.category,
      work_type: search_array.work_type,
      week: search_array.week,
      price_type: search_array.price_type,
      sex: search_array.sex,
      sage: search_array.sage,
      eage: search_array.eage,
      order_by: search_array.order_by,
    };
    $.ajax({
      url: "/resume_proc/search_list2025",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        if (ret.code == "00") {
          $(list_id).attr("data-page", list_page);
          $("#total_count").html(ret.data.count);
          if (ret.data.pagenation && ret.data.pagenation.trim() !== '') {
            $("#page_cont").html(ret.data.pagenation.replace(/<li>/g, '').replace(/page-next/g, 'page-next btn'));
          } else {
            $("#page_cont").html('');
          }
          showBoardList(ret.data.list, is_top);
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  function showBoardList(list, is_top) {
    $(list_id).html("");

    list.forEach(function(row, index) {
      try {
        var $clone = $("#tpl_resume_card").clone();
        var $content = $clone.html();
        var cate_type = "cate1";
        if (row.bd_cate == "업무") {
          cate_type = "cate1";
        } else if (row.bd_cate == "일상") {
          cate_type = "cate2";
        } else if (row.bd_cate == "질문") {
          cate_type = "cate3";
        }

        if (row.pm_idx == null) {
          $content = $content.replaceAll("{{timestamp}}", row.up_dt_timestamp || "");
          $content = $content.replaceAll("<p class=\"recomm-badge\"><span>추천</span></p>", "");
        } else {
          $content = $content.replaceAll("{{timestamp}}", "<p class=\"reco-title\">지금뜨는 추천인재</p>");
          $content = $content.replaceAll("등록일 : ", "");
        }

        $content = $content.replaceAll("{{ru_idx}}", row.ru_idx || "");
        $content = $content.replaceAll("{{mm_idx}}", row.mm_idx || "");
        $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
        $content = $content.replaceAll("{{ru_name}}", row.ru_name || "");
        $content = $content.replaceAll("{{ru_week}}", row.ru_week || "");
        $content = $content.replaceAll("{{distance}}", (row.distance || "") + (row.distance ? "km" : ""));
        $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img || "");

        var nationText = safeTrim(row.mm_nation);
        if (nationText === '') {
          $content = $content.replaceAll("{{mm_nation}}", "");
          $content = $content.replaceAll("class=\"national\" style=\"display: inline-block;\"", "class=\"national\" style=\"display: none;\"");
        } else {
          $content = $content.replaceAll("{{mm_nation}}", nationText);
        }

        if (row.mm_age == '' || row.mm_age == '0' || !row.mm_age) {
          $content = $content.replaceAll("{{ma_age}}", "");
          $content = $content.replaceAll("class=\"age\" style=\"display: inline-block;\"", "class=\"age\" style=\"display: none;\"");
        } else {
          $content = $content.replaceAll("{{ma_age}}", row.mm_age + "대");
        }

        $content = $content.replaceAll("{{ru_price_type}}", getPriceTypeJSFirst(row.ru_price_type));
        $content = $content.replaceAll("{{ru_my_type}}", getHashTagJS(row.ru_my_type, 2));
        $content = $content.replaceAll("{{ru_work_type}}", (row.ru_work_type));
        $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img || "");
        $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));

        $clone.html($content);

        $(list_id).append($clone.html());
      } catch (e) {
        console.error("리스트 아이템 렌더링 오류 (인덱스 " + index + "):", e, row);
        // 에러가 발생해도 다음 아이템은 계속 처리
      }
    });

    if (is_top == true) {
      var ajax_top = $(".search-result").position().top - 30;
      $('html, body').animate({
        scrollTop: ajax_top
      }, 400);
    }
  }

  function getRecoList() {
    getRecoListCommon({
      containerId: 'my-keen-slider',
      limit: 10,
      showMmIdx: true,
      perView: 5
    });
  }


  function getKoreanAge(birth) {
    if (!birth || birth.length !== 8) {
      return 0;
    }

    let formattedBirth = birth.slice(0, 4) + "-" + birth.slice(4, 6) + "-" + birth.slice(6, 8);
    let birthday = new Date(formattedBirth);
    let currentDate = new Date();
    let age = currentDate.getFullYear() - birthday.getFullYear();

    return age;
  }
</script>

<script>
  $("#btnRegist").click(function() {
    if (typeof requireCompCertWithGuide === 'function' && !requireCompCertWithGuide()) {
      return;
    }

    // 채용공고 개수 확인 후 페이지 이동
    $.ajax({
      url: "/compage_proc/getRecruitCount",
      type: "post",
      dataType: "json",
      success: function(ret) {
        if (ret.code == "00") {
          var recruitCount = ret.data.count || 0;

          // 채용공고가 1개 이상이면 관리 페이지로, 0개이면 등록 페이지로 이동
          if (recruitCount >= 1) {
            location.href = appBaseUrl + "/compage/recruit_list";
          } else {
            location.href = appBaseUrl + "/compage/recruit_write";
          }
        } else {
          commonAlert(ret.msg || "채용공고 개수를 확인할 수 없습니다.", function() {});
        }
      },
      error: function(xhr, status, error) {
        console.error("채용공고 개수 확인 오류:", error);
        commonAlert("채용공고 개수를 확인할 수 없습니다.", function() {});
      }
    });
  });

  $(document).on('click', '.desc', function() {
    var $target = $(this).closest('.list-item,.person-card');
    var ruIdx = $target.data('ru_idx');
    var resumeMmIdx = $target.data('mm_idx'); // 이력서 소유자 인덱스

    // 개인회원이고 자신의 이력서인 경우 클릭 가능
    if (currentUserType === "MEM" && currentUserMmIdx && resumeMmIdx && currentUserMmIdx == resumeMmIdx) {
      if (ruIdx) {
        var url = '/resume/hr_post/' + ruIdx;
        window.open(url, '_blank');
      }
      return;
    }

    // 기업회원인 경우 클릭 가능
    <?php if ($mm_type === "COMP") { ?>
      if (ruIdx) {
        var url = '/resume/hr_post/' + ruIdx;
        window.open(url, '_blank');
      }
    <?php } else { ?>
      // 개인회원이지만 자신의 이력서가 아닌 경우
      commonAlert("기업회원 전용 서비스입니다.", function() {});
    <?php } ?>
  });
</script>

<script>
  const $txtInput = $('#txt_addr');
  const $iconBtn = $('#iconBtn');
  const $iconImg = $('#iconImg');
  const $searchAddr = $('.search_addr');
  const $dept = $('.addr_dept');
  const $targetContainers = $('.search_addr, .addr_dept1, .addr_dept2, .addr_dept3');
  let debounceTimer = null;

  $(function() {

    $(document).on('click', '.searchAddrBtn', function() {
      const $btn = $(this);
      const step = $btn.data('step');
      const addr = $btn.data('addr');

      $(`li[data-step="${step}"][data-addr="${addr}"]`).removeClass('addr_on');
      $btn.remove();
      updateSelectedCount();
    });

    $('input[type="checkbox"]').on('click', function(e) {
      e.stopPropagation();
    });

    $(document).on('input', '#txt_addr', function() {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        updateSearchView();
        fSearchKeyUp();
      }, 300);
    });

    $iconBtn.on('click', function() {
      if ($iconImg.attr('alt') === 'clear') {
        $txtInput.val('');
        updateSearchView();
        $txtInput.focus();
      }
    });

    $targetContainers.on('click', 'li[data-step][data-addr]', function(e) {
      const $li = $(this);
      const step = $li.data('step');
      const addr = $li.data('addr');
      const hasCheckbox = $li.find('input[type="checkbox"]').length > 0;

      if ($li.data('processing')) return;
      $li.data('processing', true);
      setTimeout(() => $li.removeData('processing'), 200);

      if (hasCheckbox) {
        searchSelect(step, addr, $li);
      } else {
        searchLocation(step, addr, $li);
      }

      $targetContainers.each(function() {
        const $targetContainer = $(this);
        const $targetLi = $targetContainer.find(`li[data-step="${step}"][data-addr="${addr}"]`).not($li);
        if (!$targetLi.length) return;

        const targetHasCheckbox = $targetLi.find('input[type="checkbox"]').length > 0;

        if (targetHasCheckbox === hasCheckbox) {
          if (hasCheckbox) {
            $targetLi.toggleClass('addr_on');
          } else {
            $targetContainer.find('li.addr_on').each(function() {
              if ($(this).find('input[type="checkbox"]').length === 0) {
                $(this).removeClass('addr_on');
              }
            });
            $targetLi.addClass('addr_on');
          }
        }
      });
    });

    $('.searchOptionBtn').on('click', function() {
      const $this = $(this);
      const target = $this.data('target');
      const $targetOption = $('.search-option' + target);
      const isActive = $this.hasClass('on');
      $('.searchOptionBtn').removeClass('on');
      $('.search-option').removeClass('on');
      $('.search-wrap-body').addClass('on');
      $('.search-wrap-part-reset').removeClass('on');
      if (!isActive) {
        $this.addClass('on');
        $targetOption.addClass('on');
        $('.search-wrap-body').removeClass('on');
        $('.search-wrap-part-reset').addClass('on');
      }
    });

    $('.search-selected-filters').on('click', '.searchSelBtn', function(e) {
      e.preventDefault();
      var $a = $(this);
      var type = $a.data('type');
      var key = $a.data('key');
      handleFilterAction(type, key, true);
      $a.remove();
      selectedFiltersCheck();
    });

    $('.search-wrap-reset a').on('click', function(e) {
      e.preventDefault();
      resetAddressFilters();
      resetCategoryFilters();
    });

    $('.search-wrap-part-reset a').on('click', function(e) {
      e.preventDefault();

      if ($('.search-option.search-option1').hasClass('on')) {
        resetAddressFilters();
      }

      if ($('.search-option.search-option2').hasClass('on')) {
        resetCategoryFilters();
      }
    });

    $('input[type="checkbox"][name="category"]').on('change', function() {
      handleCheckboxFilter('category', '업종무관');
    });

    $('input[type="checkbox"][name="work_type"]').on('change', function() {
      handleCheckboxFilter('work_type', '근무협의');
    });

    $('input[type="checkbox"][name="price_type"]').on('change', function() {
      handleCheckboxFilter('price_type', '급여협의');
    });

    $('input[type="checkbox"][name="sex"]').on('change', function() {
      handleCheckboxFilter('sex', '성별무관');
    });

    $('input[type="checkbox"][name="week"]').on('change', function() {
      handleCheckboxFilter('week', '요일무관');
    });

    $('input[type="checkbox"][name="eage"]').on('change', function() {
      handleCheckboxFilter('eage', '나이무관');
    });

    document.querySelectorAll('input[type="checkbox"]').forEach(function(input) {
      const label = input.closest('label');

      // label이 존재하는지 확인 (null 체크)
      if (!label) {
        return; // label이 없으면 다음 input으로 넘어감
      }

      function updateLabelStyle() {
        if (!label) return; // 안전장치: label이 없으면 함수 종료
        if (input.checked) {
          label.classList.add('checked');
        } else {
          label.classList.remove('checked');
        }
      }
      updateLabelStyle();
      input.addEventListener('change', updateLabelStyle);
    });
  });

  function selectedFiltersCheck() {
    var divs = $('.search-selected-filters .searchAddrBtn').length +
      $('.search-selected-filters .searchSelBtn').length;
    if (divs === 0) {
      $('.filter-box').removeClass("on");
      $('.filter-empty').removeClass("on");
    } else {
      $('.filter-box').addClass("on");
      $('.filter-empty').addClass("on");
    }
  }

  function resetAddressFilters() {
    $('.addr_dept2 ul, .addr_dept3 ul').empty();
    $txtInput.val('');
    $('li.addr_on').removeClass('addr_on');
    $('.search-selected-filters .searchAddrBtn').remove();
    updateSelectedCount();
    updateSearchView();
  }

  function resetCategoryFilters() {
    $('.search-selected-filters .searchSelBtn').each(function() {
      var $a = $(this);
      var type = $a.data('type');
      var key = $a.data('key');
      if (typeof type !== 'undefined' && typeof key !== 'undefined') {
        handleFilterAction(type, key, false);
      }
      $a.remove();
    });
    initPage(true);
  }

  function updateAddr() {
    const count = $('.searchAddrBtn').length;
    var addrNames = [];

    if ($('.searchAddrBtn').length > 0) {
      $('.searchAddrBtn').each(function() {
        const text = $(this).clone().children().remove().end().text().trim();
        addrNames.push(text);
      });
      addrName = addrNames.join(', ');
      $('.addrName').text(addrName);
    } else {
      addrName = '전체';
      $('.addrName').text(addrName);
    }

    const searchArea = [];
    search_addr = [];

    $('.searchAddrBtn').each(function() {
      const addr = $(this).data('addr');
      const step = $(this).data('step');
      if (addr) {
        searchArea.push({
          addr: addr,
          step: step
        });
        search_addr.push(addr);
      }
    });
    searchAreaStr = JSON.stringify(searchArea);

    initPage(true);
  }

  function updateSearchView() {
    if ($.trim($txtInput.val()) !== '') {
      $iconImg.attr('src', '/resource/shop/mobile/img/ic_close2025.svg').attr('alt', 'clear');
      $searchAddr.removeClass('hidden').addClass('visible');
      $dept.removeClass('visible').addClass('hidden');
    } else {
      $iconImg.attr('src', '/resource/shop/mobile/img/ic_search2025.svg').attr('alt', 'search icon');
      $searchAddr.removeClass('visible').addClass('hidden');
      $dept.removeClass('hidden').addClass('visible');
      $('.search_addr ul').empty();
    }
  }

  function searchLocation(step, addr, $clickedLi) {
    const hasCheckbox = $clickedLi.find('input[type="checkbox"]').length > 0;
    if (hasCheckbox) return;

    const $parentDiv = $clickedLi.closest('.addr_dept1, .addr_dept2');
    if (!$parentDiv.length) return;

    $parentDiv.find('li.addr_on').each(function() {
      if ($(this).find('input[type="checkbox"]').length === 0) {
        $(this).removeClass('addr_on');
      }
    });

    $clickedLi.addClass('addr_on');
    get_location_data(step, addr);
  }

  function searchSelect(step, addr, $clickedLi) {
    const isSelected = $clickedLi.toggleClass('addr_on').hasClass('addr_on');
    const currentStep = parseInt(step, 10);
    const $addr_sel_list = $('.search-selected-filters');
    const text = $clickedLi.text().trim();
    var combinedText = "";
    const textParts = text.split(" ");
    if (textParts[textParts.length - 1] === "전체") {
      if (currentStep === 1) {
        combinedText = replaceRegionNames(text)
      } else {
        combinedText = removeRegionNames(text);
      }
    } else {
      combinedText = textParts[textParts.length - 1];
    }

    const existingBtnSelector = `.searchAddrBtn[data-step="${step}"][data-addr="${addr}"]`;
    if (isSelected) {
      const $existing = $addr_sel_list.find(existingBtnSelector);
      const alreadyExists = $existing.length > 0;
      const currentCount = $('.searchAddrBtn').length;
      let removeCount = 0;
      $('.searchAddrBtn[data-step][data-addr]').each(function() {
        const $btn = $(this);
        const btnStep = parseInt($btn.data('step'), 10);
        const btnAddr = $btn.data('addr');

        if (
          (currentStep === 1 && btnStep > 1 && btnAddr.startsWith(addr)) ||
          (currentStep === 2 &&
            ((btnStep === 1 && addr.startsWith(btnAddr)) || (btnStep === 3 && btnAddr.startsWith(addr)))) ||
          (currentStep === 3 &&
            ((btnStep === 1 && addr.startsWith(btnAddr)) || (btnStep === 2 && addr.startsWith(btnAddr))))
        ) {
          removeCount++;
        }
      });

      const projectedCount = currentCount + (alreadyExists ? 0 : 1) - removeCount;

      if (projectedCount > 3) {
        $clickedLi.removeClass('addr_on');
        return;
      }

      if (!alreadyExists) {
        const $searchAddrBtn = $('<div>')
          .addClass('searchAddrBtn')
          .attr('data-step', step)
          .attr('data-addr', addr)
          .text(combinedText)
          .append(
            $('<div>')
            .addClass('selBtnclose')
            .text('x')
          );

        const $lastBtn = $addr_sel_list.find('.searchAddrBtn').last();
        if ($lastBtn.length > 0) {
          $searchAddrBtn.insertAfter($lastBtn);
        } else {
          $addr_sel_list.prepend($searchAddrBtn);
        }
        updateSelectedCount();
      }
    } else {
      $addr_sel_list.find(existingBtnSelector).remove();
      updateSelectedCount();
    }

    $('li[data-step][data-addr]').each(function() {
      const $li = $(this);
      if ($li.is($clickedLi)) return;

      const hasCheckbox = $li.find('input[type="checkbox"]').length > 0;
      if (!hasCheckbox) return;

      const liStep = parseInt($li.data('step'), 10);
      const liAddr = $li.data('addr');

      if (
        (currentStep === 1 && liStep > 1 && liAddr.startsWith(addr)) ||
        (currentStep === 2 &&
          ((liStep === 1 && addr.startsWith(liAddr)) || (liStep === 3 && liAddr.startsWith(addr)))) ||
        (currentStep === 3 &&
          ((liStep === 1 && addr.startsWith(liAddr)) || (liStep === 2 && addr.startsWith(liAddr))))
      ) {
        $li.removeClass('addr_on');
      }
    });

    $('.searchAddrBtn[data-step][data-addr]').each(function() {
      const $btn = $(this);
      const btnStep = parseInt($btn.data('step'), 10);
      const btnAddr = $btn.data('addr');

      if (
        (currentStep === 1 && btnStep > 1 && btnAddr.startsWith(addr)) ||
        (currentStep === 2 &&
          ((btnStep === 1 && addr.startsWith(btnAddr)) || (btnStep === 3 && btnAddr.startsWith(addr)))) ||
        (currentStep === 3 &&
          ((btnStep === 1 && addr.startsWith(btnAddr)) || (btnStep === 2 && addr.startsWith(btnAddr))))
      ) {
        $btn.remove();
        updateSelectedCount();
      }
    });
  }

  function get_location_data(step, addr) {
    const addrParts = addr.trim().split(" ");
    const sido_name = addrParts[0] || "";
    const gugun_name = addrParts[1] ? addrParts[1] + (addrParts[2] ? " " + addrParts[2] : "") : "";

    const currentStep = parseInt(step, 10);
    const searchStep = currentStep + 1;

    const params = {
      step: searchStep,
      sido_name: sido_name,
      gugun_name: gugun_name
    };

    $.ajax({
      url: "/common_proc/get_location_data",
      type: "post",
      data: params,
      traditional: true,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        if (ret.code !== "00") return;

        if (currentStep === 1) {
          $('.addr_dept2 ul, .addr_dept3 ul').empty();

          const addr1 = addr;
          const isSelected = $(`.searchAddrBtn[data-step="1"][data-addr="${addr1}"]`).length > 0;

          const allLi = $('<li>')
            .attr('data-step', '1')
            .attr('data-addr', addr1)
            .toggleClass('addr_on', isSelected)
            .append(
              $('<label>').append(
                $('<span>').addClass('text').text(replaceRegionNames(sido_name) + ' 전체'),
                $('<input>').attr({
                  type: 'checkbox',
                  name: 'chk[]'
                })
              )
            );
          $('.addr_dept2 ul').append(allLi);

          $.each(ret.data, function(i, item) {
            const addr2 = addr + ' ' + item.gugun_name;
            const isSelected = $(`.searchAddrBtn[data-step="2"][data-addr="${addr2}"]`).length > 0;

            const newLi = $('<li>')
              .attr('data-step', '2')
              .attr('data-addr', addr2)
              .addClass('selectable')
              .toggleClass('addr_on', isSelected)
              .append(
                $('<span>').addClass('text').text(item.gugun_name)
              );
            $('.addr_dept2 ul').append(newLi);
          });

        } else if (currentStep === 2) {
          $('.addr_dept3 ul').empty();

          const addr2 = addr;
          const isSelected = $(`.searchAddrBtn[data-step="2"][data-addr="${addr2}"]`).length > 0;

          const allLi = $('<li>')
            .attr('data-step', '2')
            .attr('data-addr', addr2)
            .toggleClass('addr_on', isSelected)
            .append(
              $('<label>').append(
                $('<span>').addClass('text').text(gugun_name + ' 전체'),
                $('<input>').attr({
                  type: 'checkbox',
                  name: 'chk[]'
                })
              )
            );
          $('.addr_dept3 ul').append(allLi);

          $.each(ret.data, function(i, item) {
            const addr3 = addr + ' ' + item.b_dong_name;
            const isSelected = $(`.searchAddrBtn[data-step="3"][data-addr="${addr3}"]`).length > 0;

            const newLi = $('<li>')
              .attr('data-step', '3')
              .attr('data-addr', addr3)
              .toggleClass('addr_on', isSelected)
              .append(
                $('<label>').append(
                  $('<span>').addClass('text').text(item.b_dong_name),
                  $('<input>').attr({
                    type: 'checkbox',
                    name: 'chk[]'
                  })
                )
              );
            $('.addr_dept3 ul').append(newLi);
          });
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  function updateSelectedCount() {
    const count = $('.searchAddrBtn').length;

    $('.addr_sel_cnt').text(count);
    selectedFiltersCheck();
    updateAddr();
  }

  function fSearchKeyUp() {
    const search_keyword = $('#txt_addr').val().trim();
    if (search_keyword.length < 2) return;

    const params = {
      search_keyword: search_keyword
    };

    $.ajax({
      url: "/common_proc/get_location_search",
      type: "post",
      data: params,
      traditional: true,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        if (ret.code !== "00") return;

        $('.search_addr ul').empty();

        if (ret.data) {
          $.each(ret.data, function(i, item) {
            const addr = item.search_b_dong;
            const step = item.step;
            const isSelected = $(`.searchAddrBtn[data-step="${step}"][data-addr="${addr}"]`).length > 0;

            const displayText = step < 3 ? addr + ' 전체' : addr;

            const newLi = $('<li>')
              .attr('data-step', step)
              .attr('data-addr', addr)
              .toggleClass('addr_on', isSelected)
              .append(
                $('<label>').append(
                  $('<span>').addClass('text').text(displayText),
                  $('<input>').attr({
                    type: 'checkbox',
                    name: 'chk[]'
                  })
                )
              );
            $('.search_addr ul').append(newLi);
          });

        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  function replaceRegionNames(text) {
    const regionMap = {
      "서울특별시": "서울",
      "부산광역시": "부산",
      "대구광역시": "대구",
      "인천광역시": "인천",
      "광주광역시": "광주",
      "대전광역시": "대전",
      "울산광역시": "울산",
      "세종특별자치시": "세종",
      "경기도": "경기",
      "강원특별자치도": "강원",
      "충청북도": "충북",
      "충청남도": "충남",
      "전북특별자치도": "전북",
      "전라남도": "전남",
      "경상북도": "경북",
      "경상남도": "경남",
      "제주특별자치도": "제주"
    };
    return text.replace(
      new RegExp(Object.keys(regionMap).join("|"), "g"),
      match => regionMap[match]
    );
  }

  function removeRegionNames(text) {
    const regionMap = {
      "서울특별시 ": "",
      "부산광역시 ": "",
      "대구광역시 ": "",
      "인천광역시 ": "",
      "광주광역시 ": "",
      "대전광역시 ": "",
      "울산광역시 ": "",
      "세종특별자치시 ": "",
      "경기도 ": "",
      "강원특별자치도 ": "",
      "충청북도 ": "",
      "충청남도 ": "",
      "전북특별자치도 ": "",
      "전라남도 ": "",
      "경상북도 ": "",
      "경상남도 ": "",
      "제주특별자치도 ": ""
    };
    return text.replace(
      new RegExp(Object.keys(regionMap).join("|"), "g"),
      match => regionMap[match]
    );
  }

  function handleFilterAction(type, key, re) {
    var filterName = type.replace('Filter', '');
    var $inputs = $('input[name="' + filterName + '"]');
    var isRadio = $inputs.is('[type="radio"]');
    var keyArray = key.split(',').map(k => k.trim());
    if (isRadio) {
      $inputs.each(function() {
        if (keyArray.includes(this.value)) {
          this.checked = false;
          const label = this.closest('label');
          if (label) label.classList.remove('checked');
        }
      });
    } else {
      if (key.includes('무관') || key.includes('협의')) {
        $inputs.each(function() {
          this.checked = false;
          const label = this.closest('label');
          if (label) label.classList.remove('checked');
        });
      } else {
        $inputs.each(function() {
          if (keyArray.includes(this.value)) {
            this.checked = false;
            const label = this.closest('label');
            if (label) label.classList.remove('checked');
          }
        });
      }
    }
    if (re == true) initPage(true);
  }

  function handleCheckboxFilter(filterName, allSelectedLabel) {
    var $checkboxes = $('input[type="checkbox"][name="' + filterName + '"]');

    if (filterName === "eage") {
      var checkedValues = $checkboxes.filter(':checked').map(function() {
        return parseInt($(this).val().replace('대', ''));
      }).get();

      if (checkedValues.length >= 2) {
        var min = Math.min(...checkedValues);
        var max = Math.max(...checkedValues);

        $checkboxes.each(function() {
          var val = parseInt($(this).val().replace('대', ''));
          if (val >= min && val <= max) {
            $(this).prop("checked", true);
            $(this).closest("label").addClass("checked");
          }
        });
        checkedValues = $checkboxes.filter(':checked').map(function() {
          return parseInt($(this).val().replace('대', ''));
        }).get();
      }
      $checkboxes.filter(':checked').each(function() {
        $(this).closest("label").addClass("checked");
      });

      $checkboxes.not(':checked').each(function() {
        $(this).closest("label").removeClass("checked");
      });
    }

    var checked = $checkboxes.filter(':checked').map(function() {
      return $(this).val();
    }).get();

    var $dd = $('.search-selected-filters');
    var filterType = filterName + 'Filter';
    var $filterTag = $dd.find('div[data-type="' + filterType + '"]');

    if (checked.length === 0) {
      if ($filterTag.length) {
        handleFilterAction(filterType, $filterTag.data('key'), true);
        $filterTag.remove();
      }
    } else {
      var label, label2;

      if (filterName === "eage") {
        var ageNums = $checkboxes.filter(':checked').map(function() {
          return parseInt($(this).val().replace('대', ''));
        }).get().sort(function(a, b) {
          return a - b;
        });

        if (ageNums.length >= 2) {
          var min = ageNums[0];
          var max = ageNums[ageNums.length - 1];

          $checkboxes.each(function() {
            var val = parseInt($(this).val().replace('대', ''));
            if (val >= min && val <= max) {
              $(this).prop("checked", true);
              $(this).closest("label").addClass("checked");
            }
          });
        }

        var finalChecked = $checkboxes.filter(':checked').map(function() {
          return parseInt($(this).val().replace('대', ''));
        }).get().sort(function(a, b) {
          return a - b;
        });

        label2 = finalChecked.map(function(n) {
          return n + "대";
        }).join(",");

        var minAge = finalChecked[0];
        var maxAge = finalChecked[finalChecked.length - 1];
        label = (minAge === maxAge) ? `${minAge}대` : `${minAge}~${maxAge}대`;

        $checkboxes.each(function() {
          if ($(this).prop("checked")) {
            $(this).closest("label").addClass("checked");
          } else {
            $(this).closest("label").removeClass("checked");
          }
        });
        var checked = $checkboxes.filter(':checked').map(function() {
          return $(this).val();
        }).get();
        label = (checked.length === $checkboxes.length) ? allSelectedLabel : label;
      } else {
        var checked = $checkboxes.filter(':checked').map(function() {
          return $(this).val();
        }).get();
        label = (checked.length === $checkboxes.length) ? allSelectedLabel : checked.join(', ');
        label2 = label;
      }

      if ($filterTag.length) {
        $filterTag.attr('data-key', label2).html(`${label}<span class="selBtnclose">삭제</span>`);
      } else {
        var $newA = $('<div class="searchSelBtn" data-type="' + filterType + '" data-key="' + label2 + '">' + label + '<span class="selBtnclose">삭제</span></div>');
        $newA.appendTo($dd);
      }

    }

    selectedFiltersCheck();

    initPage(true);
  }
</script>