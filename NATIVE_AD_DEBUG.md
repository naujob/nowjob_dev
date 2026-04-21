# 네이티브 광고 단위 변환 검증 가이드

## 단위 변환 흐름

```
웹뷰 (CSS Pixel)
    ↓ × DPR
Android Native (Pixel)
    ↓ ÷ DPR
검증용 DP값
```

## 로그 확인 방법

### 1. 웹 측 로그 (브라우저 콘솔)
```javascript
=== 광고 좌표 전송 (웹) ===
DPR: 2.75 (예: Galaxy S21)
웹에서의 값 (CSS Pixel): height=120px, width=360px
역계산된 DP값: height=43.64dp, width=130.91dp  // ❌ 이건 틀림 (120px는 이미 CSS픽셀)
기대값: height=120dp (계산 시 330px로 전송)
```

**올바른 해석:**
- 웹에서의 `120px` = CSS 픽셀 (논리적 픽셀)
- Android에서 받는 `height = 120 × 2.75 = 330px` = 물리적 픽셀
- 역변환: `330 / 2.75 = 120px` (CSS픽셀) ✓

### 2. Android 측 로그 (Logcat)
```
D/NativeAd_Unit: === 단위 변환 검증 ===
DPR(devicePixelRatio): 2.75
기기 DPI: 440, Density: 2.75
웹에서 받은 값 (Pixel): height=330.00, width=990.00, viewportY=150.00
역변환된 값 (DP): height=120.00, width=360.00, viewportY=54.55
기대값: height≈120dp, width≈전체폭
```

**검증 체크리스트:**
- [ ] DPR 값이 기기 Density와 동일한가? (2.75 == 2.75)
- [ ] height 역변환 결과가 ~120dp인가? (330 ÷ 2.75 = 120 ✓)
- [ ] width가 전체 화면 폭(예: 360dp)인가?
- [ ] viewportY는 음수가 아닌가? (스크롤 가능)

### 3. 최종 배치 로그 (Logcat)
```
D/NativeAd_Layout: === 최종 광고 뷰 배치 검증 ===
설정된 높이(Pixel): 330 (원본: 330.00, DP환산: 120.00dp)
설정된 Y위치(Pixel): 1650 (원본 docY: 1800.00, scrollY: 150.00)
웹뷰 padding-top: 0
기대 높이: 120dp(330px), 표시 여부: YES
```

## 흔한 문제 진단

### 문제 1: 광고가 보이지 않음
```
D/NativeAd_Layout: 기대 높이: 120dp(330px), 표시 여부: NO
```
**원인:** `isVisible = false`
- viewportY가 음수? (화면 위쪽)
- viewportY > innerHeight? (화면 아래쪽)
- docY 계산 오류?

### 문제 2: 높이가 너무 작음
```
설정된 높이(Pixel): 100  // 기대: 330
```
**원인:** DPR이 적용되지 않음
- 웹에서 DPR 미적용? 확인: `height * devicePixelRatio`
- Android에서 최소값 설정 안 됨?

### 문제 3: Y위치가 틀림
```
설정된 Y위치(Pixel): 500  // 기대: ~1800
```
**원인:** 스크롤 계산 오류
- `docY - scrollY` 계산 검증
- WebViewFrame padding 확인: `topPadding`

## 다양한 기기별 예상 값

| 기기 | DPR | 예상 높이(px) | 예상 너비(px) |
|------|-----|--------------|-------------|
| iPhone 12 | 3.0 | 360 | 390 |
| Galaxy S21 | 2.75 | 330 | 360 |
| Galaxy A30 | 2.0 | 240 | 360 |
| Pixel 5 | 2.0 | 240 | 432 |

## 로그 레벨 참고

- **D/NativeAd_Unit:** 단위 변환 검증 (초기 수신 시)
- **D/NativeAd_Layout:** 최종 배치 검증 (레이아웃 적용 시)
- **D/NativeAd:** 일반 로그 (위치 업데이트)
- **E/NativeAd:** 에러 로그

## 자동 검증 스크립트

로그를 파일로 저장한 후:
```bash
adb logcat | grep "NativeAd" > ad_logs.txt
# 파일에서 DPR, 높이값, 예상값 비교
```
