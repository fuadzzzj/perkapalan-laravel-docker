# Migration Plan: PHP Native Perkapalan to Laravel + Docker

## Current Progress — 31 October 2026

- GitHub repository `fuadzzzj/perkapalan.com` was reachable and cloned read-only to `/tmp/perkapalan.com-audit` at commit `171ac0c16aec56d8ab0212aa2271e877b20c4ed3`.
- The legacy repository contains no SQL dump. Exact legacy schema/data and upload-file mapping remain unverified; the private staging folder is `storage/app/private/legacy-import/`.
- Existing Docker MySQL service is healthy. All 14 migrations are applied to `perkapalan`; legacy feature tables and `users` contain no rows. No records were imported.
- The Laravel service responds on port 8000. Public, login, and registration pages returned HTTP 200.
- Added one-time, 24-hour email activation after member approval. The token is stored hashed; login and registration have rate limits. SMTP is not configured here: local mailer is `log`.
- Added a prompt-based `perkapalan:make-admin` command for initial admin provisioning; it does not create a default account or password.
- New report and chat uploads use private storage and authorized routes. Explicit Eloquent table names now match the singular legacy table names.
- The legacy importer now hashes plaintext passwords instead of replacing them with a predictable reset value. A test confirms repeat import remains idempotent for its fixture.
- All 16 tests pass in isolated in-memory SQLite; no MySQL data is changed by the test suite.

### Remaining before real legacy import

1. Put a reviewed dump in the private staging folder.
2. Inspect its actual DDL, INSERT formats, table/column names, row counts, and file references.
3. Run the importer in dry-run and reconcile unsupported SQL or field differences.
4. Review backup location and import summary before a real import.
5. Configure SMTP before using email activation with real users.

## 1. Scope and Safety Constraints

- Source: `/home/fuadzzzj/Projects/perkapalan.com`
- Target: `/home/fuadzzzj/Projects/belajar-laravel`
- Source files are read-only inputs. No source file may be deleted, overwritten, or modified.
- All migration output belongs in the target project.
- Existing Docker services, MySQL volume, Laravel application, migrations, and user data must not be deleted or reset.
- `.env` must not be changed until its current configuration and required Docker values have been verified. Do not commit credentials or personal data.
- No legacy feature is to be dropped; unresolved behavior must be documented and covered by an implementation decision or user approval.

## 2. Legacy Source Inventory

### Entry point and routing

| Legacy file | Responsibility |
|---|---|
| `index.php` | Dispatcher based on `route`; routes `beranda`, `prestasi`, `samudra_biru`, `teknik_besi`, `tenaga_ahli_guru_perkapalan`, `workshop_perkapalan`, auth pages, student pages, teacher admin, and chat |
| `pages/.htaccess` | Legacy web-server rules |

### PHP pages

| Source file | Current target view/route/module |
|---|---|
| `pages/public/beranda.php` | `/` -> `public.beranda` |
| `pages/public/prestasi.php` | `/prestasi` -> `public.prestasi` |
| `pages/public/samudra_biru.php` | `/samudra-biru` -> `public.samudra-biru` |
| `pages/public/teknik_besi.php` | `/teknik-besi` -> `public.teknik-besi` |
| `pages/public/tenaga_ahli_guru_perkapalan.php` | `/tenaga-ahli` -> `public.tenaga-ahli` |
| `pages/public/workshop_perkapalan.php` | `/workshop` -> `public.workshop` |
| `pages/auth/halaman_login.php` | `/login` with Laravel session/auth |
| `pages/auth/register.php` | `/register` with Laravel registration |
| `pages/student/dashboard.php` | `/anggota/dashboard` |
| `pages/student/fitur_pelajar.php` | Shared student module/portal behavior; needs route-by-feature parity review |
| `pages/student/portal_pelajar.php` | `/anggota/portal`; consolidated report, score, schedule, announcement behavior |
| `pages/student/portal_utama.php` | Main member schedule/announcement view; needs parity review |
| `pages/teacher/admin.php` | `/guru/dashboard` and `/guru/*` academic actions |
| `pages/shared/chat.php` | `/chat`; message and media upload behavior |

### Layout and services

| Source file | Purpose | Laravel target |
|---|---|---|
| `layout/navbar.php` | Legacy navbar/theme placeholder | Blade layout plus existing navbar in `layouts/app.blade.php`; page-specific nav/components may be required |
| `layout/footer.html` | Footer links and company details | Blade footer/partial or Blade component |
| `service/navbar.php` | Shared navbar service | Blade layout/component |
| `service/footer.php` | Shared footer service | Blade layout/component |
| `service/database.php` | MySQLi connection to `data_anggota_perkapalan` | Laravel `database.php`, `.env`, Docker MySQL connection |

### CSS and JavaScript assets

The following source assets have already been copied under `public/css` and `public/js` in the target:

- CSS: `style.css`, `layout_navbar.css`, `footer.css`, `modern_footer.css`, `prestasi.css`, `samudra_biru.css`, `teknik_besi.css`, `tenaga_ahli_guru.css`, `workshop_perkapalan.css`, `login.css`, `dashboard_taruna.css`, `portal.css`, `admin.css`, `chat.css`, `chat_modern.css`, `service_navbar.css`, `welding_dark.css`
- JS: `navbar.js`, `script.js`, `prestasi.js`, `samudra_biru.js`, `tenaga_ahli_guru.js`, `workshop_perkapalan.js`

Interactive behavior identified in the source includes navbar/menu toggling, fade-in observers, prestige carousels or filters, page scroll behavior, login/register star background, member dashboard navigation, chat attachment menus, browser camera capture, microphone recording, form media previews, and responsive layout.

### Images and uploads

Source images under `images/` are already copied under target `public/images/`. Path-spaces and legacy names must be preserved or normalized without breaking asset URLs. Legacy runtime folders are `uploads/laporan`, `uploads/jadwal`, and `uploads/chat`; Laravel must use `storage/app/public` or a dedicated configured disk, create symbolic links if required, and retain existing file paths during import.

## 3. Legacy Features

1. Public home page, profile/facility sections, and anchor navigation.
2. Public program pages: achievements, Samudra Biru, Teknik Besi, teachers/experts, and workshop.
3. Login and registration with password verification/hash migration.
4. Role-based access for legacy `guru` and student/member users.
5. Member dashboard: profile, report statistics, approved/revised/waiting counts, recent reports, and average score.
6. Report submission/upload with title and stored file path.
7. Member portal views for reports, scores, schedules, announcements, and profile.
8. Teacher/guru panel: score entry, report status updates, schedules with image, announcements, and chat access.
9. Chat between authenticated users with text, image, video, and audio attachments plus browser recording/camera behavior.
10. Session logout and route-level authorization.
11. Legacy user password compatibility (`password_hash` and plain-text fallback) observed in login code.
12. Admin membership approval/rejection has been introduced in the Laravel target and needs parity verification against legacy/admin data conventions.

## 4. Existing Target Inventory

### Routes and controllers

The target already contains public routes, guest login/register routes, member routes, guru/admin routes, chat routes, role middleware, dashboard controllers, report controller, academic controller, chat controller, and approval controller.

### Blade views

Target Blade views exist for:

- Public pages: beranda, prestasi, samudra-biru, teknik-besi, tenaga-ahli, workshop
- Shared layout
- Auth login/register
- Member dashboard/report portal
- Guru dashboard/academic
- Admin dashboard/member approval
- Shared chat
- Profile

View existence does not prove behavior parity; each view must be compared against its source page for markup, assets, text, links, forms, responsiveness, and interaction behavior.

### Models

Target models are present for `User`, `Laporan`, `NilaiPraktikum`, `JadwalPraktikum`, `Pengumuman`, and `Chat`, with a subset of Eloquent relationships.

### Existing migrations

Target migrations include core Laravel tables and feature tables for users, reports, scores, schedules, announcements, chat, membership fields, legacy feature columns, and `legacy_source_id` mapping columns.

Important known conventions:

- Legacy user table is `user`; Laravel users use `users` and retain legacy mapping through `legacy_source_id` when imported.
- Legacy student role becomes `anggota` in the current Laravel enum.
- Legacy guru role becomes `guru`.
- Legacy report, score, schedule, announcement, and chat tables are represented as plural-independent Laravel names matching legacy table names.
- Existing user data, if any, must remain intact; imports are expected to be additive or idempotent rather than destructive.

### Existing tests

Automated tests already found in the target cover:

- Basic application response
- Academic features
- Legacy migration/import behavior
- Member reports

Additional public-page, asset, auth, session, chat/media, schedule/upload, role boundary, responsive, and database tests are still required.

## 5. Database and Legacy Data Findings

### Legacy database connection

`service/database.php` connects to local MySQL with host `localhost`, database name `data_anggota_perkapalan`, user `root`, and no password.

### SQL dump availability

No `.sql`, `.dump`, or `.bak` file was found under the legacy source. Therefore the exact legacy table definitions, indexes, constraints, character sets, and row data are not available from source files alone.

### Source-derived tables and fields

The PHP queries reveal the following application-level structure, but it must be treated as evidence rather than a complete schema dump:

- `user`: `id`, `username`, `password`, `role`, `nama_lengkap`, `kelas`, `nis`, `jurusan`, `tahun_masuk`; membership fields may be legacy/admin extensions and require confirmation.
- `laporan`: `id`, `user_id`, `judul`, `status`, `tanggal_kirim`, `file_path`.
- `nilai_praktikum`: `user_id`, `mata_pelajaran`, `nilai`, `semester`.
- `jadwal_praktikum`: `mata_pelajaran`, `tanggal`, `jam`, `ruang`, `foto_path`, `dibuat_oleh`.
- `pengumuman`: `judul`, `isi`, `dibuat_oleh`, timestamps.
- `chat`: `user_id`, `pesan`, `media_path`, `media_type`, timestamps.

### Required data input

To perform a real legacy import, provide a safe SQL dump or mysqldump export from the legacy database containing structure and data needed for migration. Before import, inspect it for credentials and personal data and keep it outside Git or in a protected location.

## 6. Docker and Environment Findings

`compose.yaml` defines an existing Laravel service on port `8000` and a MySQL 8.4 service on host port `127.0.0.1:3307`, using persistent volume `mysql_data` and network `perkapalan`. The target `.env.example` currently defaults to SQLite and does not document Docker MySQL values.

The checked container state showed no running services at this point. Do not remove or recreate the MySQL container or `mysql_data` volume. Required environment verification before enabling Docker MySQL:

1. Read the active target `.env`, but do not print or commit credentials.
2. Verify whether the active `.env` already overrides database values.
3. Set `DB_CONNECTION=mysql`, Docker hostname and credentials only if active Docker configuration confirms them.
4. Verify session, cache, and queue drivers for database availability.
5. Verify PHP extensions and image compatibility inside the container.
6. Build/run the existing compose definition if needed, preserving the current MySQL volume.

Any active `.env` change must be applied after comparison and reported explicitly. `.env.example` may be updated to provide documented non-secret Docker defaults if approved.

## 7. Risk Register

| Risk | Impact | Mitigation |
|---|---|---|
| Missing legacy SQL dump/schema | Data import and exact constraints remain uncertain | Use source-derived tables only as a baseline; import requires a reviewed dump |
| Plain-text legacy passwords | Authentication and security incompatibility | Laravel must verify both hashes and legacy plaintext, then upgrade only on successful login |
| Legacy role `siswa` vs target `anggota` | Authorization mismatch | Add an explicit import mapping and test every role boundary |
| File upload paths differ from Laravel storage | Broken downloaded reports and images | Preserve relative legacy paths where possible and add a storage mapping/import check |
| HTML/CSS references are duplicated or stale | Visual drift or asset 404s | Inventory source and target assets, replace legacy URLs only by route-aware Blade calls |
| Browser APIs depend on HTTPS or permissions | Camera/microphone behavior fails | Preserve feature but document secure-context and browser permission requirements |
| Existing target migrations and data may differ | Migration may fail or data could be transformed unexpectedly | Run `migrate:status`, backups, and non-destructive dry-runs before data import |
| Docker volume already contains data | Recreate/reset could destroy users | Never remove containers/volume; use existing volume and additive migrations |
| Mixed page implementations | Functional inconsistencies | Complete route/view/controller parity per module before removing old links |
| Personal data and credentials in dumps/logs | Privacy/security breach | Exclude dumps/secrets from Git; restrict backups and review before processing |

## 8. Implementation Sequence

### Module 0: Baseline and environment

1. Use Docker services without deleting MySQL data.
2. Inspect active `.env` and `.env.example` configuration.
3. Run migration status and route listing only; do not run destructive reset commands.
4. Run existing target tests and record failures.

### Module 1: Layout and public frontend

1. Verify or create Blade `layout/footer` and reusable navbar components.
2. Migrate/normalize legacy public route links in Blade.
3. Copy or reference existing CSS/JS without changing source files.
4. Verify home and five public pages against source assets and interactions.
5. Add focused public-route and asset tests.

### Module 2: Authentication

1. Audit existing login/register controllers and Blade forms.
2. Preserve legacy redirects and validation behavior.
3. Implement verified legacy password fallback and hash upgrade on login.
4. Add role-aware redirects and logout/session regeneration coverage.
5. Test guest, invalid credentials, registration, logout, and session invalidation.

### Module 3: Member portal

1. Consolidate existing member dashboard/report views and routes.
2. Convert report upload and listing to Laravel validation and storage APIs.
3. Preserve schedule, announcement, score, and profile behavior.
4. Add member route authorization and ownership checks.
5. Test upload types/sizes, report status visibility, and database relationships.

### Module 4: Guru/admin panel

1. Verify guru routes, academic controller, policies, and Blade views against legacy admin page.
2. Preserve score entry, report review/status, schedule image upload, and announcement publication.
3. Preserve admin membership approval/rejection behavior if approved and separate from legacy guru access.
4. Add RoleMiddleware and Policy/Form Request coverage.
5. Test every CRUD and authorization boundary.

### Module 5: Chat and media

1. Replace legacy PHP upload logic with Laravel validated uploads/storage.
2. Preserve text and media messages, maximum limits, message ordering, and last-message view behavior.
3. Preserve browser camera/microphone features only where secure-context and permissions allow.
4. Test accepted/rejected MIME types, file limits, storage, rendering, and role access.

### Module 6: Database import and data verification

1. Obtain reviewed legacy SQL dump if real data import is required.
2. Perform read-only/dry-run import analysis.
3. Map legacy `user` rows and `siswa` roles to Laravel users/`anggota`.
4. Map reports, scores, schedules, announcements, chat, uploads, and IDs with `legacy_source_id` where implemented.
5. Back up target MySQL data before actual import.
6. Run transactional, add-only/idempotent import without overwriting existing users.
7. Verify counts, relationships, passwords, uploads, and orphaned records.

### Module 7: Docker and production hardening

1. Keep existing network, image, ports, container, and volume unless a concrete blocker requires adjustment.
2. Verify Docker MySQL connection and migrations against the active environment.
3. Configure storage, session, cache, queue, logging, and asset build requirements.
4. Test container startup, restart persistence, health checks, and database persistence.
5. Document the final environment inputs without credentials.

### Module 8: Final quality gate

1. Route and feature inventory must match the legacy page list.
2. Public, auth, member, guru/admin, and chat tests must pass.
3. All uploads and assets must return successfully.
4. MySQL persistence and relational integrity must be verified.
5. Desktop/mobile and interaction checks must be completed.
6. Build and lint/dependency checks must be run.
7. Report any feature intentionally deferred only with explicit user approval.

## 9. Acceptance Criteria

- No file in the source directory is changed.
- Every legacy PHP route has a Laravel route or a documented reason for consolidation.
- Public design and navigation are preserved using the available source assets.
- Authentication, sessions, roles, registration, and logout work in Laravel.
- Member, guru, admin, report, schedule, announcement, score, and chat behavior is implemented or explicitly deferred by approval.
- Database migrations and imports are reversible, additive, and audited.
- Docker continues to use the existing MySQL volume and does not delete user data.
- Tests cover routes, views/assets, auth/session, database, CRUD, validation, uploads, and role boundaries.
