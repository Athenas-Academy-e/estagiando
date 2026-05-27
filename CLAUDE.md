# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

**Estagiando** is a Brazilian internship/jobs platform written as a custom-built PHP MVC application (no Composer, no framework). It serves three user types — `admin`, `empresa` (company), `profissional` (candidate) — and runs under Apache + PHP on XAMPP locally and cPanel in production.

## Commands

Tailwind/asset pipeline (run from project root):

```bash
npm run dev    # concurrently: tailwind --watch + BrowserSync proxy of localhost
npm run build  # tailwind --watch (input: resources/css/input.css → public_html/assets/css/output.css)
npm run prod   # tailwind --minify  ⚠️ paths in package.json (src/input.css, public_html/css/output.css) don't match the dev paths — verify before relying on `prod`
npm run serve  # BrowserSync only, proxies http://localhost
```

The PHP app itself isn't "built" — XAMPP serves it. Access locally at `http://localhost/estagiando/` (the root `.htaccess` rewrites everything into `public_html/`). There are no PHP tests or linters configured.

`config.php` is gitignored — copy `config.example.php` and fill DB + SMTP credentials before the app will run.

## Architecture

### Front controller and routing

`public_html/index.php` is the single entry point. Apache rewrites (`public_html/.htaccess`) send every non-file request through it as `?url=<path>`.

The router is convention-based with no route table:

- `/segments[0]/segments[1]` → `ucfirst(segments[0]) . 'Controller'` → method `segments[1]` (default `index`).
- `spl_autoload_register` auto-loads classes from `app/Controllers/` and `app/Models/` by class name.
- **Special routes are carved out before the generic dispatch** and won't follow the convention — be careful when adding new top-level paths:
  - Numeric error routes: `/401`, `/403`, `/404`, `/500` → `ErrorController`
  - `/redirect`, `/pdf/...`, `/esqueci-senha`, `/redefinir-senha` — each is hand-wired in `index.php`
- Any uncaught exception inside a controller falls through to `ErrorController::serverError()` (500).

When adding a new top-level URL, check whether it collides with the special-route block in `public_html/index.php` first.

### Layers

- **`app/Controllers/`** — receive the request, validate, call models, then `require` view partials in order: `partials/head.php` → `partials/header.php` → `<view>.php` → `partials/footer.php`. There is no view engine; variables in scope at the `require` site are available to the view.
- **`app/Models/`** — one class per domain entity (Empresa, Profissional, Job, Admin, Categoria, Curriculo, Publicidade). Each gets its PDO connection via `Database::getInstance()->getConnection()` in the constructor. Queries use prepared statements with named parameters.
- **`app/Views/`** — plain PHP templates, organized by feature (`cadastro/`, `empresas/`, `profissional/`, `admin/`, `login/`, `errors/`). Shared chunks live in `app/Views/partials/`.
- **`app/Core/`** — cross-cutting infrastructure:
  - `Database.php` — PDO Singleton. On connection failure it `header("Location: /500")`s; don't expect to catch a connection exception from callers.
  - `Auth.php` — session-based gate. `Auth::check('admin'|'empresa'|'profissional')` redirects to `/login` if the matching `$_SESSION[..._id]` is missing. Call it at the top of any protected controller method.
  - `Mailer.php` — PHPMailer wrapper using the SMTP_* constants from `config.php`. Always `Mailer::enviar($to, $subject, $html)`.
  - `ImageProcessor.php` — GD-based resize+reencode. Writes into `public_html/assets/<folder>/` and returns the public path (e.g. `/assets/img/logos/img_xxx.jpg`). Used for company logos and candidate photos.
  - `pdf_generator.php` — uses TCPDF (vendored in `libs/`) for résumé export.
- **`app/Emails/`** — `EmailTemplate::render($name, $vars)` does `extract()` + output buffering over `app/Emails/templates/<name>.php`. Templates currently: `boas_vindas`, `recuperacao_senha`.
- **`libs/`** — vendored third-party code (PHPMailer, TCPDF). No Composer; require paths are relative.

### Static assets and the Tailwind pipeline

Tailwind scans `./**/*.php` (so class names in views are picked up) and writes to `public_html/assets/css/output.css`. The dev/build scripts both target that path; only `prod` writes elsewhere (see warning above).

User-uploaded files land in `public_html/assets/cv/`, `assets/img/fotos/`, `assets/img/logos/` — all gitignored. `ImageProcessor` will `mkdir` these on first upload.

### Sessions and auth state

- `$_SESSION['admin_id']`, `$_SESSION['empresa_id']`, `$_SESSION['profissional_id']` are mutually exclusive and indicate which dashboard the user can reach.
- There's no CSRF token system and no central input sanitizer — controllers validate field-by-field (see `CadastroController::validarEmpresa`/`validarProfissional` for the pattern).

### Deployment

`.cpanel.yml` rsyncs the repo into `/home/hg4bea48/public_html/` on git push, **excluding `config.php`** so server credentials survive. Don't add files that need to be writable at runtime (uploads, logs) to git — the gitignore already covers the common cases.
