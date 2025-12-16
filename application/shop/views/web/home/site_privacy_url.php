<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>개인정보처리방침</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0;
            background: #fff;
        }
        
        .image-container {
            width: 100%;
            text-align: center;
            padding: 0;
        }
        
        .privacy-image {
            width: 100%;
            height: auto;
            max-width: 100%;
            display: block;
        }
        
        /* 모바일 최적화 */
        @media (max-width: 768px) {
            .container {
                padding: 0;
            }
            
            .image-container {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <!-- 개인정보처리방침 파일 보여주기 2025.07.18 -->
    <div class="container">
        <div class="image-container">
            <img src="<?php echo $image_path; ?>" alt="개인정보처리방침" class="privacy-image">
        </div>
    </div>
</body>
</html>