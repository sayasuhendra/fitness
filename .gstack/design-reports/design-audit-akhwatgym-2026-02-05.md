# Design Audit — Akhwat Gym (halaman / dan /support)

Tanggal: 2026-02-05 · Scope: halaman depan (welcome) + halaman support · Mode: perbaikan minimal (pilihan user)

## Temuan & Status Perbaikan

### Home page (`resources/views/welcome.blade.php`)
| # | Temuan | Dampak | Status |
|---|--------|--------|--------|
| H1 | Radius tidak konsisten: section Bantuan pakai `rounded-2xl`/`rounded-xl`, semua card lain `rounded-lg` (8px) | Medium | ✅ fixed — diseragamkan ke `rounded-lg` (4 card + 3 icon box) |
| H2 | Heading FAQ `text-xl` padahal seluruh h3 lain `text-lg` (18px) | Polish | ✅ fixed |
| H3 | Tombol "Masuk Admin" 36px < target sentuh 44px | Medium | ✅ fixed — kini 44px (`py-3`) |
| H4 | Tidak ada gaya `focus-visible` global + `scroll-smooth` tanpa guard `prefers-reduced-motion` | Medium | ✅ fixed via `resources/css/app.css` |

### Support page (`resources/views/support.blade.php`) — CSS-only, struktur tidak diubah
| # | Temuan | Dampak | Status |
|---|--------|--------|--------|
| S1 | Palet brand tidak sinkron dengan home: magenta `#b400d8`, ink `#261233`, muted `#6f6075`, permukaan off-palette | High | ✅ fixed — fuchsia-600 `#c026d3` (CTA) / fuchsia-700 (link) / zinc-950 / zinc-600 / zinc-200, sama dengan home |
| S2 | `word-break: break-all` mematahkan email di tengah kata meski ada ruang | Medium | ✅ fixed — `overflow-wrap: anywhere` |
| S3 | Link header 23px & footer 17px (< 44px), tombol kontak 43px | Medium | ✅ fixed — semua ≥ 45px |
| S4 | Hover tombol hanya `opacity:.9`, tidak ada focus-visible, FAQ tanpa hover | Medium | ✅ fixed — hover ganti warna brand, outline focus-visible, border FAQ berubah saat hover |
| S5 | Gradasi latar pink-ungu miring tidak sesuai rasa home | Polish | ✅ fixed — gradasi halus putih → fuchsia-50 |

## Verifikasi (headless browser, 1280px & 375px)
- Radius card seluruh halaman depan: 8px seragam ✓
- Palet support ter-render = fuchsia-600 + zinc ✓
- Tidak ada link interaktif < 44px (link teks inline dikecualikan) ✓
- Tidak ada horizontal scroll 375px di kedua halaman ✓
- Tidak ada console error ✓

## Commit
- `972888a` style(design): unify home page card radii, FAQ heading scale, touch target and focus a11y
- `8f6486f` style(design): align support page palette with home design system, fix touch targets and interaction states

## Saran lanjutan (tidak dikerjakan — di luar scope "minimal")
- Aktifkan font brand **Instrument Sans** (sudah terkonfigurasi di `vite.config.js` via Bunny Fonts tapi belum direferensikan CSS mana pun).
- Konten FAQ di home & support duplikatif — bisa disatukan/merujuk halaman support.
- Ikon-ikon berlingkaran warna pada section Bantuan adalah pola template yang generik; bisa diganti gaya lebih khas bila suatu saat mau redesign.
