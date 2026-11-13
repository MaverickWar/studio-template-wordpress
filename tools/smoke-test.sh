#!/usr/bin/env bash
# Boots a real WordPress (WordPress Playground, no database or Docker needed) with this theme active,
# then checks the home page uses the project's design. Used by the Checks workflow.
set -euo pipefail
echo '{"steps":[{"step":"activateTheme","themeFolderName":"studio-starter"}]}' > /tmp/blueprint.json
npx --yes @wp-playground/cli@3 server --port=9400 --mount=.:/wordpress/wp-content/themes/studio-starter --blueprint=/tmp/blueprint.json > /tmp/playground.log 2>&1 &
for _ in $(seq 1 60); do curl -sf http://127.0.0.1:9400/ -o /tmp/home.html && break; sleep 3; done
grep -q 'studio-primary' /tmp/home.html || { echo "Home page doesn't use the design"; cat /tmp/playground.log; exit 1; }
grep -q 'fonts.googleapis.com' /tmp/home.html || { echo "Home page doesn't load the fonts"; exit 1; }
if grep -qiE 'Fatal error|Warning:|Notice:|Deprecated:' /tmp/home.html; then echo "PHP printed an error"; exit 1; fi
echo "✓ WordPress runs the theme with the project's design"
