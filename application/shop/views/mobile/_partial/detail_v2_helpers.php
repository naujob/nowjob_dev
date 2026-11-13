<?php
// 채용·인재 상세 v2 공용 함수
if (!function_exists('jdIcon')) {
    function jdIcon($name, $cls = "jd-i")
    {
        $paths = [
            "pin" => "M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z M12 7a2.5 2.5 0 1 0 0 5a2.5 2.5 0 1 0 0-5",
            "won" => "M3 6h18v12H3z M12 9a3 3 0 1 0 0 6a3 3 0 1 0 0-6 M6 9v.01 M18 15v.01",
            "clock" => "M12 3a9 9 0 1 0 0 18a9 9 0 1 0 0-18 M12 7v5l3 2",
            "cal" => "M4 6h16v14H4z M4 10h16 M8 3v4 M16 3v4",
            "back" => "M15 5l-7 7 7 7",
            "share" => "M18 8a3 3 0 1 0 0-6a3 3 0 0 0 0 6z M6 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6z M18 22a3 3 0 1 0 0-6a3 3 0 0 0 0 6z M8.6 13.5l6.8 4 M15.4 6.5l-6.8 4",
            "phone" => "M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2",
            "bld" => "M4 21V5l8-2v18 M12 8h8v13 M7 8h2 M7 12h2 M7 16h2 M15 12h2 M15 16h2 M2 21h20",
            "eye" => "M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z M12 9a3 3 0 1 0 0 6a3 3 0 1 0 0-6",
            "zoom" => "M11 4a7 7 0 1 0 0 14a7 7 0 1 0 0-14 M16 16l5 5 M8 11h6 M11 8v6",
            "heart" => "M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10z",
            "bag" => "M3 8h18v12H3z M8 8V5h8v3 M3 13h18",
            "award" => "M12 3a6 6 0 1 0 0 12a6 6 0 1 0 0-12 M8.5 14l-1.5 7 5-3 5 3-1.5-7 M12 6.5l1 2 2.2.3-1.6 1.5.4 2.2-2-1-2 1 .4-2.2-1.6-1.5 2.2-.3z",
        ];
        return '<svg class="' . $cls . '" viewBox="0 0 24 24" aria-hidden="true"><path d="' . $paths[$name] . '"/></svg>';
    }
}

// 업무구분: 글자 + 구분선 (운전배달은 운전직으로 표시)
if (!function_exists('jdWorkText')) {
    function jdWorkText($csv)
    {
        $labels = ["운전배달" => "운전직"];
        $out = "";
        foreach (explode(",", (string) $csv) as $w) {
            $w = trim($w);
            if ($w === "") continue;
            $label = isset($labels[$w]) ? $labels[$w] : $w;
            $out .= '<span class="jd-work">' . htmlspecialchars(t($label), ENT_QUOTES, 'UTF-8') . '</span>';
        }
        return $out === "" ? "-" : $out;
    }
}

// 해시태그: 상자 안 칩
if (!function_exists('jdTags')) {
    function jdTags($csv, $max = 10)
    {
        $out = "";
        $n = 0;
        foreach (explode(",", (string) $csv) as $tag) {
            $tag = trim(ltrim(trim($tag), "#"));
            if ($tag === "" || $n >= $max) continue;
            $out .= '<span><i>#</i>' . htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') . '</span>';
            $n++;
        }
        return $out === "" ? "" : '<div class="jd-tags">' . $out . '</div>';
    }
}

// 근무요일: 선택한 요일만 원으로
if (!function_exists('jdWeekDays')) {
    function jdWeekDays($csv)
    {
        $sel = array_map('trim', explode(",", (string) $csv));
        $out = "";
        foreach (["월", "화", "수", "목", "금", "토", "일"] as $d) {
            if (in_array($d, $sel)) $out .= '<li class="on">' . t($d) . '</li>';
        }
        return '<ul class="jd-days">' . $out . '</ul>';
    }
}

// 근무요일 요약: [월~금, 주 5일]
if (!function_exists('jdWeekSummary')) {
    function jdWeekSummary($csv)
    {
        $all = ["월", "화", "수", "목", "금", "토", "일"];
        $sel = array_map('trim', explode(",", (string) $csv));
        $idx = [];
        foreach ($all as $i => $d) {
            if (in_array($d, $sel)) $idx[] = $i;
        }
        $n = count($idx);
        if ($n === 0) return ["-", ""];
        if ($n >= 3 && $idx[$n - 1] - $idx[0] + 1 === $n) {
            $text = t($all[$idx[0]]) . "~" . t($all[$idx[$n - 1]]);
        } else {
            $names = [];
            foreach ($idx as $i) $names[] = t($all[$i]);
            $text = implode("·", $names);
        }
        return [$text, "주 " . $n . "일"];
    }
}
