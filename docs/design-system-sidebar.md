# Design System – Sidebar (Banking Web)

This document specifies the design tokens, typography, spacing, interaction patterns, accessibility requirements, and implementation examples for the redesigned sidebar component.

## Color Palette

- Neutrals
  - Neutral 100: #F8F9FA (sidebar background)
  - Neutral 200: #E9ECEF (hover background)
  - Neutral 300: #DEE2E6 (borders/dividers)
  - Neutral 500: #6C757D (muted text/icons)
  - Neutral 700: #343A40 (primary body text)
- Primary
  - Primary 500: #0D6EFD (active indicators, focus, key actions)

Usage guidelines:
- Sidebar background uses Neutral 100 with a subtle shadow for elevation.
- Active navigation indicator uses Primary 500 as a 3px left border.
- Hover states use Neutral 200 for background fill.
- Borders and separators use Neutral 300.
- Text hierarchy: body text Neutral 700; secondary/muted text Neutral 500.

## Typography

- Font family: Inter, system-ui
- Modular scale: 1.25
- Base sizes:
  - Item label: 14px
  - Section header: 12px (uppercase, letter-spacing 0.04em)
  - Optional large label: 18px for prominent items (rare usage; ensure 3:1 contrast)
- Line-height: 1.2–1.4 within sidebar; avoid wrapping where possible.

## Spacing

- Grid: 8px system with 4px increments
  - space-1: 4px
  - space-2: 8px
  - space-3: 12px
  - space-4: 16px
  - space-6: 24px
  - space-7: 32px
  - space-8: 40px
- Navigation items:
  - Height: 40px visual, minimum tap target 44px
  - Horizontal padding: 16px
  - Gap between icon and text: 8px
  - Gap between items: 8px

## Layout and Responsiveness

- Widths
  - Desktop: 240–280px (default 260px)
  - Tablet: 200–240px (default 220px)
  - Mobile: full-height overlay, max 320px
- Collapsed state (desktop): 64px width, icons only
- Transitions: 300ms cubic-bezier(0.4, 0, 0.2, 1) for expand/collapse
- Shadow: 0 2px 4px rgba(0,0,0,0.08); Right border: 1px #DEE2E6

## Iconography

- Size: 20×20
- Placement: 8px gap before label
- Color: Neutral 700; may inherit state color where applicable

## Interaction Patterns

- Hover (200ms ease-in-out):
  - Background: Neutral 200
  - Text/Icon: remain Neutral 700
- Active:
  - 3px left border: Primary 500
  - Background: rgba(13,110,253,0.10)
- Focus:
  - 2px outline with Primary 500 and 2px offset
  - Ensure visible with WCAG 2.1 AA
- Disabled:
  - Text/Icon: reduce opacity to 0.5; no hover
- Keyboard:
  - Tab/Shift+Tab traverses controls and links
  - Enter/Space activates toggle and dropdown triggers
  - Esc closes mobile overlay

## Accessibility

- role="navigation" on sidebar landmark
- aria-label for the sidebar (e.g., “Primary navigation”)
- aria-expanded on toggle and dropdown triggers
- aria-controls linking triggers to submenu DOM IDs
- Focus ring 2px with offset; maintain minimum contrast (4.5:1 normal, 3:1 large)
- Touch targets: min 44×44px

## Android Orientation Handling

- Detect Android via JS and apply class to body
- In landscape on Android: show orientation overlay and blur content
- CSS fallback using @media (orientation: landscape) for body.android

## Code Examples

HTML (excerpt):

```html
<nav class="sidebar" role="navigation" aria-label="Primary navigation">
  <div class="sidebar-inner">
    <div class="sidebar-logo">
      <a href="/" class="sidebar-main-logo">
        <img src="/logo.svg" alt="logo">
      </a>
      <button class="sidebar-menu-bar" aria-label="Toggle sidebar" aria-expanded="false" aria-controls="primary-sidebar-menu">
        <svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path d="..." /></svg>
      </button>
    </div>
    <ul id="primary-sidebar-menu" class="sidebar-menu">
      <li class="sidebar-menu-item-header">My Accounts</li>
      <li class="sidebar-menu-item active">
        <a href="/dashboard">
          <span class="menu-icon" aria-hidden="true"></span>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      <li class="sidebar-menu-item sidebar-dropdown">
        <a href="javascript:void(0)" role="button" aria-haspopup="true" aria-expanded="false" aria-controls="submenu-settings">
          <span class="menu-icon" aria-hidden="true"></span>
          <span class="menu-title">Settings</span>
        </a>
        <ul class="sidebar-submenu" id="submenu-settings">
          <li class="sidebar-menu-item"><a href="/security/pin">Security Pin</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
```

CSS Variables (excerpt):

```css
:root {
  --neutral-100:#F8F9FA; --neutral-200:#E9ECEF; --neutral-300:#DEE2E6;
  --neutral-500:#6C757D; --neutral-700:#343A40; --primary-500:#0D6EFD;
  --sidebar-width-desktop:260px; --sidebar-width-tablet:220px; --sidebar-width-collapsed:64px;
}
.sidebar .sidebar-menu-item > a {
  min-height:44px; padding:12px 16px; gap:8px; border-left:3px solid transparent;
}
.sidebar .sidebar-menu-item.active > a { border-left-color: var(--primary-500); }
```

JS (accessibility excerpt):

```js
$(document).on('keydown', '.sidebar-menu-bar', function (e) {
  if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); $(this).click(); }
});
$(document).on('click', '.sidebar-dropdown > a[role="button"]', function (e) {
  e.preventDefault();
  const $t=$(this), expanded=$t.attr('aria-expanded')==='true';
  const $p=$t.closest('.sidebar-menu-item'), $s=$p.find('.sidebar-submenu').first();
  if (expanded) { $p.removeClass('active'); $s.slideUp(200); $t.attr('aria-expanded','false'); }
  else { $p.siblings('.sidebar-menu-item').removeClass('active').find('.sidebar-submenu').slideUp(200).prev('a[role="button"]').attr('aria-expanded','false'); $p.addClass('active'); $s.slideDown(200); $t.attr('aria-expanded','true'); }
});
```

## Testing Matrix

- Browsers: Chrome, Firefox, Safari, Edge (latest two)
- Devices: iOS Safari, Android Chrome; test touch, focus, and overlay
- Screen sizes: ≥1200 desktop, 768–1199 tablet, ≤767 mobile
- Orientation: verify Android landscape overlay appears and blocks interaction

## Notes

- Do not disable user scaling in viewport meta to preserve accessibility.
- Keep focus states always visible and consistent.

