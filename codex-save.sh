#!/bin/bash

MSG="before codex $(date '+%Y-%m-%d %H:%M:%S')"
git add -A
git commit -m "$MSG"
echo "✅ 자동 커밋 완료: $MSG"

