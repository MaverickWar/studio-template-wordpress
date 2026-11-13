# Studio template: WordPress block theme

The starter Universal App Studio copies when someone picks **WordPress block theme** while setting up a new project.

A WordPress block theme (full site editing) with header and footer parts, templates for the blog, posts, pages and 404, and a hero pattern. The repo is the theme folder. It has one change: **the look comes from `design/tokens.json`**.

- `design/tokens.json` holds the colours, fonts, corners and spacing. Studio replaces it with the project's own design straight after copying this template.
- `design/adapters/` reads those tokens in this stack's own language. See `design/adapters/README.md` once Studio has added it, or the comments at the top of each adapter.
- Style everything from the tokens. Change the look in Studio, never by typing colours into the code.

## Running it

```
npm start   # runs WordPress in your browser with this theme, no setup needed
# or copy this folder into wp-content/themes/ on any WordPress 6.6+ site
```

Open the address it prints (usually http://127.0.0.1:9400).

## Checks

```
find . -name "*.php" | xargs -n1 php -l
node tools/check-theme.mjs
npm run smoke   # boots WordPress with the theme
```

The same checks run on GitHub for every push and pull request (`.github/workflows/checks.yml`).
