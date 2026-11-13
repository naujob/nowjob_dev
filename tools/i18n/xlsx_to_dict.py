# -*- coding: utf-8 -*-
"""
엑셀 번역표 → 웹 다국어 사전 파일 생성기

사용법 (저장소 루트에서):
    python tools/i18n/xlsx_to_dict.py [엑셀 경로]

기본 엑셀 경로: docs/i18n/나우잡_중국어번역_단어.xlsx (없으면 저장소 루트의 같은 이름 파일)

생성물 (직접 수정 금지 — 엑셀을 고친 뒤 이 스크립트를 다시 실행):
    application/common/language/zh/ui_dict.php   서버(PHP) t() 용 배열
    resource/shop/web/js/i18n/zh.js              브라우저(JS) t() 용 사전 window.I18N_ZH

엑셀 형식: 모든 시트의 1행 머리글에 '한국어', '중국어(간체)' 컬럼이 있어야 한다.
같은 한국어에 서로 다른 중국어가 있으면 생성하지 않고 실패한다.
"""
import json
import os
import sys

try:
    import openpyxl
except ImportError:
    sys.exit("openpyxl 이 필요합니다: pip install openpyxl")

REPO_ROOT = os.path.abspath(os.path.join(os.path.dirname(__file__), "..", ".."))
EXCEL_NAME = "나우잡_중국어번역_단어.xlsx"
DEFAULT_EXCEL_CANDIDATES = [
    os.path.join(REPO_ROOT, "docs", "i18n", EXCEL_NAME),
    os.path.join(REPO_ROOT, EXCEL_NAME),
]
PHP_OUT = os.path.join(REPO_ROOT, "application", "common", "language", "zh", "ui_dict.php")
JS_OUT = os.path.join(REPO_ROOT, "resource", "shop", "web", "js", "i18n", "zh.js")

KO_HEADER = "한국어"
ZH_HEADER = "중국어(간체)"


def find_excel(argv):
    """명령행 인자 또는 기본 위치에서 엑셀 파일 경로를 찾는다."""
    if len(argv) > 1:
        return argv[1]
    for path in DEFAULT_EXCEL_CANDIDATES:
        if os.path.exists(path):
            return path
    sys.exit("엑셀 파일을 찾을 수 없습니다: " + ", ".join(DEFAULT_EXCEL_CANDIDATES))


def read_dict(excel_path):
    """모든 시트에서 한국어→중국어 짝을 읽어 병합한다. 충돌이 있으면 종료."""
    wb = openpyxl.load_workbook(excel_path, read_only=True, data_only=True)
    merged = {}
    origin = {}  # 충돌 보고용: 한국어 -> (시트, 행)
    conflicts = []
    for ws in wb.worksheets:
        rows = ws.iter_rows(values_only=True)
        header = next(rows, None)
        if not header:
            continue
        header = [str(h).strip() if h is not None else "" for h in header]
        if KO_HEADER not in header or ZH_HEADER not in header:
            print("[건너뜀] 시트 '%s': '%s'/'%s' 머리글 없음" % (ws.title, KO_HEADER, ZH_HEADER))
            continue
        ko_col = header.index(KO_HEADER)
        zh_col = header.index(ZH_HEADER)
        count = 0
        for row_no, row in enumerate(rows, start=2):
            ko = row[ko_col] if ko_col < len(row) else None
            zh = row[zh_col] if zh_col < len(row) else None
            if ko is None or zh is None:
                continue
            ko = str(ko).strip()
            zh = str(zh).strip()
            if not ko or not zh:
                continue
            if ko in merged and merged[ko] != zh:
                conflicts.append((ko, merged[ko], zh, origin[ko], (ws.title, row_no)))
                continue
            merged[ko] = zh
            origin.setdefault(ko, (ws.title, row_no))
            count += 1
        print("[시트] %s: %d행" % (ws.title, count))
    if conflicts:
        for ko, zh1, zh2, o1, o2 in conflicts:
            print("[충돌] '%s' → '%s' (%s %d행) / '%s' (%s %d행)" % (ko, zh1, o1[0], o1[1], zh2, o2[0], o2[1]))
        sys.exit("번역 충돌 %d건 — 엑셀을 정리한 뒤 다시 실행하세요." % len(conflicts))
    return merged


def php_quote(s):
    """PHP 작은따옴표 문자열 리터럴로 변환 (\\ 와 ' 만 이스케이프)."""
    return "'" + s.replace("\\", "\\\\").replace("'", "\\'") + "'"


def write_php(d, path):
    """PHP 5.4 호환 배열 파일 생성."""
    os.makedirs(os.path.dirname(path), exist_ok=True)
    lines = [
        "<?php if (!defined('BASEPATH')) exit('No direct script access allowed');",
        "/**",
        " * 웹 다국어 정적 문구 사전 (한국어 원문 => 중국어 간체)",
        " * 자동 생성 파일 — 직접 수정 금지. 엑셀 수정 후 tools/i18n/xlsx_to_dict.py 재실행.",
        " * 항목 수: %d" % len(d),
        " */",
        "return array(",
    ]
    for ko in sorted(d):
        lines.append("    %s => %s," % (php_quote(ko), php_quote(d[ko])))
    lines.append(");")
    with open(path, "w", encoding="utf-8", newline="\n") as f:
        f.write("\n".join(lines) + "\n")


def write_js(d, path):
    """브라우저용 사전 파일 생성 (t() 함수는 레이아웃에서 정의)."""
    os.makedirs(os.path.dirname(path), exist_ok=True)
    body = json.dumps(dict(sorted(d.items())), ensure_ascii=False, indent=0)
    with open(path, "w", encoding="utf-8", newline="\n") as f:
        f.write("/* 웹 다국어 정적 문구 사전 — 자동 생성 파일, 직접 수정 금지 (tools/i18n/xlsx_to_dict.py). 항목 수: %d */\n" % len(d))
        f.write("window.I18N_ZH = " + body + ";\n")


def main():
    excel = find_excel(sys.argv)
    print("[엑셀] " + excel)
    d = read_dict(excel)
    write_php(d, PHP_OUT)
    write_js(d, JS_OUT)
    print("[완료] 항목 %d개" % len(d))
    print("  " + os.path.relpath(PHP_OUT, REPO_ROOT))
    print("  " + os.path.relpath(JS_OUT, REPO_ROOT))


if __name__ == "__main__":
    main()
