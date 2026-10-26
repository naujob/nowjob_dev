<?php

/**
 * 네이버 간편로그인 - 액세스 토큰 검증용 라이브러리
 *
 * 앱(iOS/안드로이드)이 네이버 SDK로 받은 액세스 토큰을 서버가 직접 네이버에 물어
 * 토큰 주인이 누구인지 확인하는 용도. 클라이언트가 보낸 ID/전화번호를 믿지 않기 위함.
 *
 * KakaoLogin 라이브러리와 같은 반환 계약을 쓴다: ["code" => HTTP코드, "data" => 디코드된 JSON]
 */
class NaverLogin
{
    /** 네이버 오픈API 서버 주소 */
    private $api_url;

    public function __construct()
    {
        $this->api_url = "https://openapi.naver.com";
    }

    /**
     * 액세스 토큰으로 네이버 사용자 정보 조회
     * 응답 구조: ["resultcode" => "00", "response" => ["id", "name", "email", "gender", "mobile", "birthyear", "birthday"]]
     *
     * @param string $accessToken 네이버 액세스 토큰
     * @return array ["code" => HTTP 상태코드, "data" => 응답 JSON 배열]
     */
    public function getUserInfo($accessToken)
    {
        $url = $this->api_url . "/v1/nid/me";

        $headers = array(
            "Authorization: Bearer {$accessToken}",
            "Content-type: application/x-www-form-urlencoded;charset=utf-8",
        );

        // 사용자 정보 조회는 GET 요청 (postData 없음)
        $arrResult = $this->call($headers, $url, null);

        $returnData = json_decode($arrResult[0], true);

        $retVal["code"] = $arrResult[3];
        $retVal["data"] = $returnData;

        return $retVal;
    }

    /**
     * 실제 API 호출 메서드
     * postData가 없으면 GET, 있으면 POST로 호출한다
     *
     * @param array $headers HTTP 헤더 배열
     * @param string $url 호출 주소
     * @param array $postData POST 데이터 (없으면 GET)
     * @return array [응답본문, errno, error, HTTP코드]
     */
    private function call($headers, $url, $postData = null)
    {
        try {
            $postDataString = "";
            if (is_array($postData)) {
                $postDataString = http_build_query($postData);
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, false);

            if ($postData !== null) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataString);
            } else {
                curl_setopt($ch, CURLOPT_POST, false);
            }

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // 네트워크 지연으로 로그인 요청이 무한정 붙잡히지 않도록 제한
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $result[0] = curl_exec($ch);
            $result[1] = curl_errno($ch);
            $result[2] = curl_error($ch);
            $result[3] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            return $result;
        } catch (Exception $e) {
            // 호출 실패 시 빈 응답 + HTTP 0 으로 돌려 호출부가 검증 실패로 처리하게 한다
            return array("", 0, $e->getMessage(), 0);
        }
    }
}
