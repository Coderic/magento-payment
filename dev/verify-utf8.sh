#!/usr/bin/env bash
# Falla si algún archivo de texto del módulo no es UTF-8 válido (sin BOM).
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"
FAIL=0

while IFS= read -r -d '' f; do
  case "$f" in
    *.png|*.jpg|*.jpeg|*.gif|*.webp|*.ico|*.woff|*.woff2|*.svg) continue ;;
    */docs/assets/*) continue ;;
  esac
  if ! python3 -c "b=open('$f','rb').read(); assert b[:3]!=b'\\xef\\xbb\\xbf'; b.decode('utf-8')" 2>/dev/null; then
    echo "FAIL: no UTF-8: $f" >&2
    FAIL=1
  fi
done < <(find . -type f ! -path './.git/*' -print0)

if [[ "$FAIL" -ne 0 ]]; then
  echo "Verificacion UTF-8 fallida. Todo archivo de texto debe ser UTF-8 sin BOM." >&2
  exit 1
fi

echo "OK: todos los archivos de texto en UTF-8 sin BOM"
