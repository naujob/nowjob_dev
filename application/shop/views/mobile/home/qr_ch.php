<style>
    /* QR 다운로드 페이지 스타일 */
    .qr-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background-image: url('<?= RES_PATH ?>/img/qr/qr_background.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .qr-page .header {
        width: 100%;
        background: transparent;
    }

    .qr-page .qr-hero {
        padding-top: 5vh;
    }

    .qr-page .qr-top-logo {
        display: block;
        width: 12.5rem;
        margin: 0 auto;
    }

    .qr-page .qr-top-logo img {
        display: block;
        width: 100%;
        height: auto;
    }

    .qr-page .qr-store {
        margin: 2.75rem auto 0;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        align-items: center;
    }

    .qr-page .qr-store img {
        display: block;
        width: 17.5rem;
        height: auto;
    }

    .qr-page .qr-content {
        margin-top: 2.75rem;
        background: #ffffff;
       
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 0;
        height: 247px;
    }

    .qr-page .qr-video {
        width: 100%;
        max-width: none;
        flex: 1;
    }

    .qr-page .qr-video video {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        
    }

    .qr-page .qr-video-label {
        font-size: 1rem;
        color: #1a1a1a;
    }

    .qr-page .qr-bottom-logo {
        margin: auto;
        width: 30%;
        min-height: 3.5rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .qr-page .qr-bottom-logo img {
        display: block;
        width: 100%;
        height: auto;
    }
</style>

<div class="page page-qr qr-page">
    <div class="qr-hero">
        <div class="header">
            <h1 class="qr-top-logo">
                <img src="<?= RES_PATH ?>/img/qr/qr_toplogo_ch.svg" alt="NOW JOB">
            </h1>
        </div>
        <div class="qr-store">
            <a href="https://play.google.com/store/apps/details?id=app.nowapk.com&pcampaignid=web_share">
                <img src="<?= RES_PATH ?>/img/qr/qr_google.svg" alt="Google Play QR">
            </a>
            <a href="https://apps.apple.com/kr/app/나우잡/id6446226901">
                <img src="<?= RES_PATH ?>/img/qr/qr_ios.svg" alt="App Store QR">
            </a>
        </div>
    </div>
    <div class="qr-content">
        <div class="qr-video">
        <video autoplay muted playsinline loop controls>
            <source src="<?= RES_PATH ?>/img/qr/qr_video_ch1.mp4" type="video/mp4">
        </video>
        </div>
       
    </div>
    <div class="qr-bottom-logo">
        <a href="/" target="_blank" rel="noopener noreferrer">
            <img src="<?= RES_PATH ?>/img/qr/ch_qr_bottomlogo.svg" alt="NOW JOB">
        </a>
    </div>
</div>
