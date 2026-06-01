/**
 * 광고 배너 관리 - 공유 모듈
 * job_list.php, hr_list.php 등에서 사용
 */

// 광고 배너 설정 (페이지별)
var adBannerConfig = {
  recruit: {
    banners: [
      {
        img: '/outer/ad/nowjob-banner-vertical_v5-1.png',
        link: 'https://naver.me/FOZat0A2'
      },
      {
        img: '/outer/ad/nowjob-banner-vertical_v5-2.png',
        link: 'https://naver.me/FDn3Ggxk'
      }
    ]
  },
  hr: {
    banners: [
      {
        img: '/outer/ad/nowjob-banner-vertical_v5-1.png',
        link: 'https://naver.me/FOZat0A2'
      },
      {
        img: '/outer/ad/nowjob-banner-vertical_v5-2.png',
        link: 'https://naver.me/FDn3Ggxk'
      }
    ]
  }
};

/**
 * 랜덤 광고 배너 로드
 * @param {string} pageType - 페이지 유형 ('recruit' 또는 'hr')
 */
function loadRandomAdBanner(pageType) {
  var config = adBannerConfig[pageType];

  if (!config || !config.banners || config.banners.length === 0) {
    console.warn('광고 배너 설정이 없습니다:', pageType);
    return;
  }

  var randomIndex = Math.floor(Math.random() * config.banners.length);
  var selectedBanner = config.banners[randomIndex];

  var $bannerImg = $('#adBannerImg');
  var $bannerLink = $('#adBannerLink');

  if ($bannerImg.length > 0) {
    $bannerImg.attr('src', selectedBanner.img);
  }

  if ($bannerLink.length > 0) {
    $bannerLink.attr('href', selectedBanner.link);
  }
}
