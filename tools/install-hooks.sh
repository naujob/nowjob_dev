#!/usr/bin/env bash
# tools/install-hooks.sh
# .git/hooks/pre-commit 를 .github/hooks/pre-commit.sh로 심볼릭 또는 복사합니다.

set -e

REPO_ROOT=$(dirname "$(dirname "${BASH_SOURCE[0]}")")
HOOK_SRC="$REPO_ROOT/.github/hooks/pre-commit.sh"
HOOK_TARGET="$REPO_ROOT/.git/hooks/pre-commit"

if [ ! -f "$HOOK_SRC" ]; then
  echo "hook script not found: $HOOK_SRC"
  exit 1
fi

if [ -L "$HOOK_TARGET" ] || [ -f "$HOOK_TARGET" ]; then
  echo "기존 pre-commit hook이 존재합니다: $HOOK_TARGET"
  echo "기존을 백업합니다 -> ${HOOK_TARGET}.bak"
  mv "$HOOK_TARGET" "${HOOK_TARGET}.bak"
fi

ln -sf "$HOOK_SRC" "$HOOK_TARGET"
chmod +x "$HOOK_TARGET"

echo "pre-commit hook 설치 완료: $HOOK_TARGET -> $HOOK_SRC"
