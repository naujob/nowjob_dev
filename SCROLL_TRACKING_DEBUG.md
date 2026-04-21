# 네이티브 광고 스크롤 추적 및 targetY 검증 가이드

## 스크롤 이벤트 흐름

```
사용자 스크롤
    ↓
JavaScript scroll 이벤트 발생
    ↓
sendAdPosition() 호출 (debounce 50ms)
    ↓
getBoundingClientRect() 계산
    ↓
Android updateNativeAdPosition() 호출
    ↓
targetY 계산 및 광고 뷰 배치
```

## targetY 계산 공식

### 웹 측 (JavaScript)
```javascript
viewportY = rect.top;           // 화면 기준 Y (음수면 화면 위쪽)
docY = rect.top + scrollY;      // 문서 기준 Y
targetY = docY - scrollY;       // = rect.top (viewportY와 동일)
```

### Android 측 (Java)
```java
targetY = (int) Math.round(viewportY - topPadding);
// viewportY = 웹에서 받은 값 (Pixel)
// topPadding = WebViewFrame의 padding-top
```

**예상 값 비교:**
| 상황 | viewportY | docY | scrollY | targetY 계산 |
|------|-----------|------|---------|-------------|
| 광고가 화면 상단 | 100 | 100 | 0 | 100 - 0 = 100 ✓ |
| 스크롤 후 중간 | 200 | 2200 | 2000 | 2200 - 2000 = 200 ✓ |
| 광고 위쪽 부분만 보임 | 20 | 2020 | 2000 | 2020 - 2000 = 20 ✓ |
| 화면 위쪽으로 벗어남 | -50 | 1950 | 2000 | 1950 - 2000 = -50 ✓ |

## 웹 콘솔 로그 분석

### 첫 스크롤 이벤트 로그 예시

```javascript
=== [1] 광고 좌표 전송 (scroll) ===
DPR: 2.75
웹에서의 값 (CSS Pixel):
  - height=120px (기대: 120px) ✓
  - width=360px
역계산된 DP값: height=43.64dp
스크롤 정보:
  - scrollY: 150.00px
광고 위치 (CSS Pixel):
  - viewportY(rect.top): 200.00px
  - docY(rect.top+scrollY): 350.00px
  - targetY(docY-scrollY): 200.00px (웹뷰 상단 기준)
전송할 데이터:
  - viewportY: 550.00px (200 × 2.75)
  - docY: 962.50px (350 × 2.75)
  - scrollY: 412.50px (150 × 2.75)
  - height: 330.00px (120 × 2.75)
가시성: YES
```

**검증 체크리스트:**
- ✓ scrollY 증가함 (150px)
- ✓ viewportY는 200px (광고가 화면에 표시)
- ✓ docY = 350px (200 + 150)
- ✓ targetY = 200px (웹뷰 상단 기준)

### 계속 스크롤했을 때 로그

```javascript
=== [2] 광고 좌표 전송 (scroll) ===
scrollY: 250.00px (50px 더 스크롤)
광고 위치:
  - viewportY(rect.top): 100.00px (줄어듦 = 화면 상단으로 이동)
  - docY(rect.top+scrollY): 350.00px (변화 없음 = 문서 상 동일 위치)
  - targetY(docY-scrollY): 100.00px
```

**관찰:**
- viewportY가 200px → 100px로 감소 (위쪽으로 이동한 것처럼 보임)
- docY는 350px 유지 (광고의 절대 위치는 동일)
- targetY = 100px (웹뷰 상단에서 더 위쪽)

## Android Logcat 분석

### NativeAd_Position 로그

```
D/NativeAd_Position: === [targetY 위치 검증] ===
수신 좌표 (Pixel):
  viewportY=550.00, docY=962.50, scrollY=412.50
계산: targetY = viewportY - padding
      = 550.00 - 0 = 550 px
검증: docY - scrollY = 962.50 - 412.50 = 550.00 (viewportY와 동일 ✓)
```

**올바른 검증:**
1. `targetY = viewportY - padding` → 550px
2. `targetY = docY - scrollY` → 550px
3. 두 값이 동일하면 **정확함** ✓

### NativeAd_Layout 로그

```
D/NativeAd_Layout: 높이: 330 px(330px기대) ✓ OK | Y위치: 550 px | 표시: YES
```

**검증 항목:**
- ✓ 높이가 기대값과 일치 (330px = 120dp)
- ✓ Y위치가 양수 (화면 내)
- ✓ 표시 여부: YES

## 스크롤 추적 디버깅 명령어

### 웹 콘솔에서 수동 위치 확인
```javascript
// 개발자 도구 콘솔에 입력
window.debugAdPosition();
```

**출력:**
```
=== 수동 위치 조회 ===
viewportY: 200.00px
scrollY: 150.00px
docY: 350.00px
targetY(docY-scrollY): 200.00px
```

### Logcat에서 특정 로그만 필터링
```bash
# 위치 검증 로그만
adb logcat | grep "NativeAd_Position"

# 배치 검증 로그만
adb logcat | grep "NativeAd_Layout"

# 모든 광고 로그
adb logcat | grep "NativeAd"
```

## 흔한 오류 진단

### 문제 1: targetY가 음수
```
Y위치: -100 px
```
**원인:** viewportY < padding
- 광고가 화면 위쪽으로 벗어남
- `isVisible = false` 확인

### 문제 2: targetY가 너무 큼
```
Y위치: 5000 px
```
**원인:** DPR이 적용된 픽셀값이 DP로 잘못 해석됨
- 웹에서 `viewportY * DPR` 제대로 전송했는지 확인
- Android에서 `viewportY / DPR` 역변환 로그 확인

### 문제 3: viewportY ≠ docY - scrollY
```
NativeAd_Position 로그에서 두 값이 다름
```
**원인:** 계산 오류 또는 변수 오염
- JavaScript에서 `getBoundingClientRect()` 재호출
- scrollY 정상 수신 확인

## 스크롤 성능 최적화

### 현재 debounce 설정
- **debounce 시간:** 50ms
- **효과:** 50ms 내에 여러 스크롤 이벤트는 1번만 처리

### 스크롤 이벤트 발생 빈도
```javascript
// 초고속 스크롤: 매 16ms마다 이벤트 (60fps)
// debounce 50ms: 3-4개 이벤트를 1번으로 통합
// 결과: updateNativeAdPosition() 호출 1/3 감소
```

## 최종 검증 체크리스트

스크롤 시 다음을 확인:

- [ ] 콘솔 로그에 `[N] 광고 좌표 전송 (scroll)` 표시
- [ ] scrollY 값이 계속 증가
- [ ] viewportY 값이 변함
- [ ] docY - scrollY = viewportY 성립
- [ ] Android NativeAd_Position 로그에서 두 계산식 일치
- [ ] targetY가 음수가 아님
- [ ] 높이가 기대값 ±5px 이내
- [ ] 광고 뷰가 화면에 부드럽게 따라옴

## 수동 테스트 시나리오

1. **천천히 스크롤**
   ```javascript
   window.debugAdPosition();  // 매번 호출 후 관찰
   ```
   기대: Y값이 부드럽게 변함

2. **빠르게 스크롤**
   - debounce로 인해 일부 업데이트 스킵
   - 최종 위치는 정확해야 함

3. **광고를 화면 위로 스크롤**
   - viewportY가 음수가 되어야 함
   - `isVisible = false` → 광고 숨김

4. **광고를 화면 아래로 스크롤**
   - viewportY > innerHeight
   - `isVisible = false` → 광고 숨김
