/**
 * job_list.php 페이지 전용 스크립트
 * - 앱 프롬프트 레이어 (app-intercept-layer) 제어
 */

(function() {
  const layer = document.getElementById("app-intercept-layer");
  if (!layer) {
    return;
  }

  const COOL_DOWN_KEY = "nowjobAppLayerCooldownAt";
  const COOL_DOWN_MS = 0;
  const ANDROID_PKG = "app.nowapk.com";
  const ANDROID_STORE_INTENT = "intent://details?id=" + ANDROID_PKG + "#Intent;scheme=market;package=com.android.vending;end";
  const ANDROID_STORE_MARKET = "market://details?id=" + ANDROID_PKG;
  const ANDROID_STORE_WEB = "https://play.google.com/store/apps/details?id=" + ANDROID_PKG;
  const IOS_STORE = "https://apps.apple.com/kr/app/id6446226901";
  const OPEN_DELAY = 200;

  const openButton = layer.querySelector(".app-intercept-open");
  const closeButton = layer.querySelector(".app-intercept-close");

  let fallbackTimer = null;
  let hiddenTimer = null;

  function isMobileBrowser() {
    const ua = navigator.userAgent || "";
    return /Android|iPhone|iPad|iPod/i.test(ua);
  }

  function isIOSWebView() {
    const ua = navigator.userAgent || "";
    const isIOS = /iPhone|iPad|iPod/i.test(ua);
    const isSafari = /Safari/i.test(ua);
    return isIOS && !isSafari;
  }

  function isAppViaUrl() {
    try {
      const query = new URLSearchParams(window.location.search);
      return query.get('is_app') === 'true';
    } catch (ignore) {
      return false;
    }
  }

  function ensureAppFlagForIOSWebView() {
    if (!isIOSWebView() || isAppViaUrl()) {
      return;
    }
    try {
      const url = new URL(window.location.href);
      url.searchParams.set("is_app", "true");
      window.history.replaceState(null, "", url.toString());
    } catch (ignore) {}
  }

  function writeCooldown() {
    try {
      localStorage.setItem(COOL_DOWN_KEY, Date.now().toString());
    } catch (ignore) {}
  }

  function readCooldown() {
    try {
      const stored = localStorage.getItem(COOL_DOWN_KEY);
      const timestamp = Number(stored);
      return Number.isNaN(timestamp) ? null : timestamp;
    } catch (ignore) {
      return null;
    }
  }

  function shouldShowLayer() {
    const timestamp = readCooldown();
    if (!timestamp) {
      return true;
    }
    return Date.now() - timestamp >= COOL_DOWN_MS;
  }

  function showLayer() {
    layer.classList.add("visible");
    layer.setAttribute("aria-hidden", "false");
  }

  function hideLayer() {
    layer.classList.remove("visible");
    layer.setAttribute("aria-hidden", "true");
  }

  function attachFallbackCancels() {
    function cleanup() {
      window.removeEventListener("pagehide", pagehideHandler);
      document.removeEventListener("visibilitychange", visibilityHandler);
      if (hiddenTimer) {
        clearTimeout(hiddenTimer);
        hiddenTimer = null;
      }
    }

    function pagehideHandler() {
      cancelFallback();
      cleanup();
    }

    function visibilityHandler() {
      if (document.visibilityState === "hidden") {
        hiddenTimer = setTimeout(function() {
          cancelFallback();
          cleanup();
        }, 200);
      } else if (hiddenTimer) {
        clearTimeout(hiddenTimer);
        hiddenTimer = null;
      }
    }

    window.addEventListener("pagehide", pagehideHandler);
    document.addEventListener("visibilitychange", visibilityHandler);

    return cleanup;
  }

  function cancelFallback() {
    if (fallbackTimer) {
      clearTimeout(fallbackTimer);
      fallbackTimer = null;
    }
  }

  function openApp() {
    writeCooldown();
    hideLayer();

    const ua = navigator.userAgent || "";
    const isIOS = /iPhone|iPad|iPod/i.test(ua);
    const storeUrl = isIOS ? IOS_STORE : ANDROID_STORE_INTENT;

    const removeCancelHandlers = attachFallbackCancels();
    fallbackTimer = setTimeout(function() {
      removeCancelHandlers();
      if (document.visibilityState === "hidden") {
        return;
      }
      if (isIOS) {
        location.href = storeUrl;
        return;
      }
      location.href = ANDROID_STORE_INTENT;
      setTimeout(function() {
        if (document.visibilityState === "hidden") {
          return;
        }
        location.href = ANDROID_STORE_MARKET;
      }, 300);
      setTimeout(function() {
        if (document.visibilityState === "hidden") {
          return;
        }
        location.href = ANDROID_STORE_WEB;
      }, 900);
    }, OPEN_DELAY);
  }

  function dismissLayer() {
    writeCooldown();
    hideLayer();
  }

  function initializePrompt() {
    ensureAppFlagForIOSWebView();
    if (!isMobileBrowser() || isAppViaUrl()) {
      return;
    }
    if (shouldShowLayer()) {
      showLayer();
    }
  }

  if (document.readyState === "complete" || document.readyState === "interactive") {
    initializePrompt();
  } else {
    window.addEventListener("DOMContentLoaded", initializePrompt);
  }

  if (openButton) {
    openButton.addEventListener("click", openApp);
  }

  if (closeButton) {
    closeButton.addEventListener("click", dismissLayer);
  }
})();
