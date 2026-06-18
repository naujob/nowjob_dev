# recruit_write1_1.php - JavaScript 함수 재배치 계획

## 현재 상태
- 파일 크기: 2570줄
- HTML 부분: 1-570줄
- CSS 부분: 571-775줄
- JavaScript 부분: 777-2570줄

## 목표
JavaScript 함수들을 실행 순서에 맞게 11개 그룹으로 체계적으로 정렬

---

## 11개 함수 그룹 (실행 순서대로)

### 1️⃣ 유틸리티 함수 (숫자/문자열 처리) - 3개
**용도**: 기본 포맷팅 도구  
**현재 위치**: 820-862줄

```
- formatNumberWithComma() [라인 820]
- removeComma() [라인 860]
- syncTimeHiddenFields() [라인 835]
```

---

### 2️⃣ 데이터 마이그레이션 & 정리 - 2개
**용도**: localStorage 키 관리, 임시 데이터 삭제  
**현재 위치**: 821-1445줄 (분산)

```
- migrateRecruitDraftKeys() [라인 821]
- clearRecruitDraftData() [라인 1440]
```

---

### 3️⃣ 페이지별 데이터 저장 (localStorage) - 3개
**용도**: 사용자 입력 임시 저장  
**현재 위치**: 866-981줄

```
- savePage1Data() [라인 866]
- savePage2Data() [라인 919]
- savePage3Data() [라인 936]
```

---

### 4️⃣ 페이지별 데이터 로드 - 4개
**용도**: localStorage 또는 서버에서 데이터 복원  
**현재 위치**: 983-1438줄

```
- loadPage1Data() [라인 983]
- loadPage2Data() [라인 1227]
- loadPage3Data() [라인 1265]
- restorePageData() [라인 1812]
```

---

### 5️⃣ 주소 처리 - 3개
**용도**: 주소 선택 시 폼 업데이트  
**현재 위치**: 1483-1550줄

```
- selectAddrInfo() [라인 1483]
- selectAddrInfoExpose1() [라인 1507]
- selectAddrInfoExpose2() [라인 1530]
```

---

### 6️⃣ 폼 데이터 수집 & 검증 - 4개
**용도**: 필수 데이터 검증, 해시태그/이미지 수집  
**현재 위치**: 1552-1647줄

```
- buildHashString() [라인 1552]
- collectRcWorkImg() [라인 1560]
- validateRequiredForSubmit() [라인 1574]
- hasFileUploads() [라인 1638]
```

---

### 7️⃣ UI 업데이트 - 1개 (메인)
**용도**: UI 상태 동기화  
**현재 위치**: 1447-1481줄

```
- updateSalaryOptions() [라인 1447]
```

**참고**: `updateTimeSection()`, `updateNegotiationCheckbox()` 는 DOMContentLoaded 내부 함수로 유지

---

### 8️⃣ API 호출 & 저장 - 2개
**용도**: 서버에 데이터 전송  
**현재 위치**: 1649-1773줄

```
- saveStep1And2() [라인 1649]
  └─ 1,2페이지 저장 후 rc_idx 발급
  
- saveStep3AndComplete() [라인 1698]
  └─ 3페이지 저장 및 미리보기/최종 등록
```

---

### 9️⃣ 화면 전환 - 2개
**용도**: 페이지 이동  
**현재 위치**: 1776-1794줄

```
- showPreviewPage() [라인 1776]
- submitPreview() [라인 1792]
```

---

### 🔟 핵심 흐름 - 1개
**용도**: 전체 제출 프로세스 조율  
**현재 위치**: 1796-1808줄

```
- handleSubmit() [라인 1796]
  └─ 검증 → 저장 → API 호출 → 화면 전환
```

---

### 1️⃣1️⃣ 초기화 & 이벤트 핸들링
**용도**: 페이지 로드 시 초기화 및 모든 사용자 상호작용  
**현재 위치**: 1828-2481줄 (DOMContentLoaded)

#### 11-1. 페이지 초기화
```
- Draft 로드 여부 판단 (shouldLoadDraft 플래그)
- 데이터 복원 (restorePageData)
- 시간 섹션 초기화 (updateTimeSection)
- 급여 옵션 업데이트 (updateSalaryOptions)
```

#### 11-2. 내부 함수 (DOMContentLoaded 내에서만 정의)
```
- chgMonth() [라인 1871] - flatpickr 월 표시 변경
- updateTimeSection() [라인 2002] - 근무시간 섹션 표시/숨김
- updateNegotiationCheckbox() [라인 2037] - 근무협의 자동 체크
```

#### 11-3. 사용자 상호작용 이벤트 (~30개 이상)
```
입력 필드 이벤트:
- rc_name 입력 → savePage1Data [라인 1991]
- rc_price_type_main 변경 → updateSalaryOptions + savePage1Data [라인 1992]
- rc_price_amount 입력 → savePage1Data [라인 1996]
- rc_price_possible 변경 → savePage1Data [라인 1997]

체크박스 이벤트:
- rc_work[] 변경 → savePage1Data [라인 1999]
- rc_work_type[] 변경 → updateTimeSection + savePage1Data [라인 2048]
- rc_work_negotiation 변경 → 근무형태 동기화 [라인 2057, 2106]
- rc_life_type[] 변경 → savePage2Data [라인 2074]

상세정보 이벤트:
- rc_desc 입력 → savePage3Data [라인 2076]
- rc_hash[] 변경 → savePage3Data [라인 2077]
- rc_day[], rc_day_all 변경 → savePage3Data [라인 2078]
- rc_sex[], rc_sex_all 변경 → savePage3Data [라인 2079]
- rc_nation[], rc_nation_all 변경 → savePage3Data [라인 2080]
- rc_sage, rc_eage, rc_age_all 변경 → savePage3Data [라인 2081]

날짜 선택 이벤트:
- flatpickr 초기화 [라인 1895-1978]
- 날짜 변경 → savePage3Data [라인 1944, 1975]

UI 상호작용:
- 선택 제한 체크 (data-select-limit) [라인 2088]
- 라벨 클릭 토글 [라인 2145]
- 상세등록 버튼 (토글 슬라이드) [라인 2162]
- 해시태그 추가 팝업 [라인 2187]
- 해시태그 확인 버튼 [라인 2198]
- 해시태그 최대 개수 체크 [라인 2247]

요일/성별/국적/나이 동기화:
- 요일 "모두" 토글 [라인 2301]
- 요일 개별 체크 [라인 2310]
- 성별 "무관" 토글 [라인 2323]
- 성별 개별 체크 [라인 2332]
- 국적 "무관" 토글 [라인 2345]
- 국적 개별 체크 [라인 2354]
- 나이 "무관" 토글 [라인 2367]
- 나이 개별 선택 [라인 2375]

주소 관련:
- 공고노출지역1 버튼 [라인 2389]
- 공고노출지역2 버튼 [라인 2395]
- 공고노출지역 삭제 버튼 [라인 2401]
- 파일 선택 미리보기 [라인 2432]

폼 제출:
- 미리보기 버튼 [라인 2451]
- 최종저장 버튼 [라인 2455]
- 뒤로가기 버튼 [라인 2459]

히스토리 & 기본값:
- 히스토리 상태 저장 [라인 2469]
- popstate 이벤트 [라인 2471]

초기값 설정:
- 요일 기본값 (평일) [라인 2255]
- 성별 기본값 (무관) [라인 2271]
- 국적 기본값 (무관) [라인 2281]
- 나이 기본값 (무관) [라인 2291]
```

---

### 이후 외부 이벤트 핸들러 - 2개
```
- pageshow 이벤트 (물리적 뒤로가기) [라인 2484]
- form submit 이벤트 [라인 2492]
- 모바일 사진 업로드 [라인 2499]
```

---

## 재배치 작업 단계

### Phase 1: 섹션 추가 ✅
- 각 함수 그룹 앞에 명확한 섹션 주석 추가

### Phase 2: 함수 순서 변경
기본 순서 (현재):
```
1. 유틸리티 [O 위치 적절]
2. 마이그레이션 → 정리 [△ 분산되어 있음]
3. 저장 [O 위치 적절]
4. 로드 [O 위치 적절]
5. 주소 [O 위치 적절]
6. 검증/수집 [O 위치 적절]
7. UI 업데이트 [△ 뒤에 위치]
8. API호출 [O 위치 적절]
9. 화면전환 [O 위치 적절]
10. 핵심흐름 [O 위치 적절]
11. 초기화/이벤트 [O 위치 적절]
```

**문제점**:
- `updateSalaryOptions()` → 1447줄 (유틸리티와 마이그레이션 사이)
- `clearRecruitDraftData()` → 1440줄 (로드 함수들 이후)

**해결책**: 이 두 함수를 올바른 위치로 이동

### Phase 3: 의존성 검증
- 함수 호출 순서가 선언 순서보다 뒤인지 확인
- 예: `handleSubmit()` → `validateRequiredForSubmit()` (의존성 있음)

---

## 최종 구조 (새로운 순서)

```
수정전:                          수정후:
Line 820: formatNumber ✓        Line 820: formatNumber ✓
Line 835: syncTime      ✓        Line 835: syncTime      ✓
Line 860: removeComma   ✓        Line 860: removeComma   ✓
Line 821: migrate       ✓        Line 865: migrate       ✓
Line 1440: clear        ✗        Line 880: clear         ✓ [이동]
                                 Line 890: savePage1     ✓ [이동]
Line 1447: updateSal    ✗        Line 950: savePage2     ✓ [이동]
                                 Line 970: savePage3     ✓ [이동]
Line 1483: selectAddr   ✓        ...
                                 Line 1000: loadPage1    ✓ [이동]
                                 ...
```

---

## 예상 효과

✅ **가독성 향상**: 함수 선언 순서 = 함수 호출 순서  
✅ **유지보수 용이**: 기능별 그룹화로 관련 함수 쉽게 찾음  
✅ **의존성 명확**: 상위 함수부터 하위 함수 순서로 배열  
✅ **문서화 개선**: 섹션 주석으로 코드 흐름 한눈에 파악

---

## 실행 전 체크리스트

- [ ] 원본 파일 백업 확인
- [ ] 각 함수의 위치 검증
- [ ] 함수 간 의존성 재확인
- [ ] Edit 작업 후 테스트 실행

---

## 주의사항

1. **DOMContentLoaded 내부 함수 유지**
   - `updateTimeSection()`, `updateNegotiationCheckbox()` 는 내부에 정의된 상태 유지
   - 이들을 전역으로 옮기면 `window.updateTimeSection_fn = updateTimeSection;` 로직 수정 필요

2. **변수 스코프**
   - `updateTimeSection_fn` 은 전역 변수로, restorePageData()에서 호출되므로 주의

3. **이벤트 리스너 순서**
   - flatpickr 초기화는 DOMContentLoaded 내에서만 가능
   - 순서대로 옮기되 이 제약을 고려
