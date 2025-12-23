// Firebase Messaging Service Worker
// FCM 푸시 알림을 처리하는 Service Worker

importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');

// Firebase 설정
const firebaseConfig = {
  apiKey: "AIzaSyBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX", // 실제 API 키로 교체 필요
  authDomain: "nowjob-558df.firebaseapp.com",
  projectId: "nowjob-558df",
  storageBucket: "nowjob-558df.appspot.com",
  messagingSenderId: "123456789012", // 실제 Sender ID로 교체 필요
  appId: "1:123456789012:web:abcdefghijklmnop" // 실제 App ID로 교체 필요
};

// Firebase 초기화
firebase.initializeApp(firebaseConfig);

// Firebase Messaging 인스턴스 가져오기
const messaging = firebase.messaging();

// 백그라운드에서 푸시 알림 수신 시 처리
messaging.onBackgroundMessage(function (payload) {
  console.log('📱 백그라운드 푸시 메시지 수신:', payload);

  const notificationTitle = payload.notification.title || 'NowJob 알림';
  const notificationOptions = {
    body: payload.notification.body || '새로운 알림이 있습니다.',
    icon: '/favicon.ico',
    badge: '/favicon.ico',
    data: payload.data || {},
    tag: 'nowjob-notification',
    requireInteraction: true,
    actions: [
      {
        action: 'open',
        title: '열기'
      },
      {
        action: 'close',
        title: '닫기'
      }
    ]
  };

  // 알림 표시
  return self.registration.showNotification(notificationTitle, notificationOptions);
});

// 알림 클릭 시 처리
self.addEventListener('notificationclick', function (event) {
  console.log('📱 알림 클릭 이벤트:', event);

  event.notification.close();

  // 클릭한 액션에 따라 처리
  if (event.action === 'close') {
    return;
  }

  // URL이 있으면 해당 페이지로 이동
  if (event.notification.data && event.notification.data.url) {
    const url = event.notification.data.url;
    console.log('🔗 알림 클릭 URL 처리:', url);

    // 새 탭에서 열기
    event.waitUntil(
      clients.openWindow(url)
    );
  } else {
    // 기본 페이지로 이동
    event.waitUntil(
      clients.openWindow('/')
    );
  }
});

// 알림 닫기 시 처리
self.addEventListener('notificationclose', function (event) {
  console.log('📱 알림 닫기 이벤트:', event);
});

// 메시지 이벤트 처리 (메인 스레드와 통신)
self.addEventListener('message', function (event) {
  console.log('📱 Service Worker 메시지 수신:', event.data);

  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

// 설치 이벤트
self.addEventListener('install', function (event) {
  console.log('📱 Service Worker 설치됨');
  self.skipWaiting();
});

// 활성화 이벤트
self.addEventListener('activate', function (event) {
  console.log('📱 Service Worker 활성화됨');
  event.waitUntil(self.clients.claim());
});
