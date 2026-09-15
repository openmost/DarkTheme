## Documentation

Dark Theme transforms your Matomo Analytics interface into a modern dark experience, for every user. This documentation covers the theme architecture and how to customize it.

### Dark for every user

Matomo 6 lets each user choose a light or dark mode in their personal settings. Dark Theme applies its dark palette to everyone instead:

- every theme color gets a single dark value, whatever mode the user picked,
- the page is rendered in dark mode, so the Matomo dark mode rules (icons, flags, charts, maps) apply as well,
- the mode stays dark when Matomo switches it in the browser, for example after saving the personal settings,
- the theme mode choice is hidden from the personal settings, as it has no effect while Dark Theme is active.

Scheduled report emails keep Matomo's light colors, so they stay readable in every mail client.

To let each user choose again, activate the default Matomo theme (Morpheus) under *Administration > Themes*.

### Theme Architecture

All colors are configured in PHP through Matomo's `Theme.configureThemeVariables` event. LESS files are kept only for the few areas that need targeted overrides on top of the variables provided by Matomo core.

```
DarkTheme/
├── DarkTheme.php          # Palette and dark mode for every user
├── plugin.json            # Theme metadata (requires Matomo 6)
├── vue/
│   ├── src/ThemeModeLock/ # Keeps the dark mode in the browser
│   └── dist/              # Built library (Matomo Vite build)
└── stylesheets/
    ├── theme.less         # Main entry point
    ├── layout/
    │   └── _main.less     # Layout-level rules (e.g. scrollbar)
    ├── pages/
    │   └── _login.less    # Login & onboarding overrides
    └── components/
        ├── _activity_log.less
        ├── _admin.less
        ├── _alert.less
        ├── _copy_clipboard.less
        ├── _custom_reports.less
        ├── _entity_list.less
        ├── _funnels.less
        ├── _input.less
        ├── _jqplot.less
        ├── _multi_sites.less
        ├── _notification.less
        ├── _period_selector.less
        ├── _personal_settings.less
        ├── _scheduled_reports.less
        ├── _segment.less
        ├── _sidebar.less
        ├── _tag_manager.less
        ├── _visitor_profile.less
        ├── _visits_log.less
        └── _widget.less
```

### PHP Theme Variables

`DarkTheme.php` registers a `configureThemeVariables` listener and assigns values to `\Piwik\Plugin\ThemeStyles`. The values are exposed by Matomo core as CSS custom properties (e.g. `--theme-color-background-base`) and are used throughout the UI.

The palette is split into four scales:

**Brand**
- `primary`: `#4a6fc7`
- `primaryLight`: `#6b8fd9` (used for links, focus ring, selected menu)

**Surfaces (lightest to darkest)**
- `surfaceOverlay`: `#3a424d` (popovers, hover backgrounds, code blocks)
- `surfaceRaised`: `#2b3138` (widgets, cards, header background)
- `surfaceBase`: `#202329` (page background)
- `surfaceGround`: `#181a1f` (deepest layer)

**Text (most prominent to faded)**
- `textPrimary`: `#ffffff`
- `textSecondary`: `rgba(255, 255, 255, 0.85)`
- `textTertiary`: `rgba(255, 255, 255, 0.65)`
- `textDisabled`: `rgba(255, 255, 255, 0.40)`

**Borders**
- `borderSubtle`: `#3a424d`
- `borderStrong`: `#4a525d`

These local variables feed the official `ThemeStyles` properties: `colorBrand`, `colorSuccess`, `colorText*`, `colorBackground*`, `colorBorder*`, `colorWidget*`, `colorMenuContrast*`, `colorHeader*`, `colorLink`, `colorFocusRing`, `colorCode*`, `colorBoxShadow`, `shadowOverlay` and `filterOnIllustration` (which inverts white illustrations so they look right on a dark background). Any color the palette does not set uses the Matomo dark value.

### Customizing Colors

The recommended way to customize the palette is to fork the plugin and adjust the variables in `DarkTheme.php`:

```php
private function applyPalette(ThemeStyles $vars): void
{
    $vars->colorBrand            = '#your-brand-color';
    $vars->colorBackgroundBase   = '#your-background-color';
    $vars->colorBackgroundContrast = '#your-widget-color';
    // ...
}
```

You can also override the generated CSS variables from your own stylesheet:

```css
:root {
  --theme-color-brand: #your-brand-color;
  --theme-color-background-base: #your-background-color;
}
```

After changing any value, clear Matomo's asset cache (Administration > System > General Settings > "Clear all caches").

### Component Overrides

Each file in `stylesheets/components/` targets a specific area of Matomo where the default core CSS does not pick up the theme variables cleanly:

- **Charts** (`_jqplot.less`): series picker popover
- **Forms** (`_input.less`, `_multi_sites.less`): input and select colors
- **Visitor reports** (`_visits_log.less`, `_visitor_profile.less`, `_activity_log.less`): timeline and profile colors
- **Plugin support**: `_funnels.less`, `_tag_manager.less`, `_custom_reports.less`, `_scheduled_reports.less`
- **UI chrome**: `_sidebar.less`, `_admin.less`, `_widget.less`, `_alert.less`, `_notification.less`, `_segment.less`, `_entity_list.less`, `_copy_clipboard.less`, `_period_selector.less`, `_personal_settings.less`

### Compatibility

| Dark Theme | Matomo |
|---|---|
| 6.x | 6 |
| 5.3.x | 5.10 or later |
| 5.2.x | 5.0 to 5.9 |
| 1.x | 4 |

### Contributing

To contribute:

1. Fork the [GitHub repository](https://github.com/openmost/DarkTheme)
2. Add or refine variables in `DarkTheme.php` first; only fall back to LESS overrides when a setting is not exposed by `ThemeStyles`
3. Test across Matomo's main screens (Dashboard, Visitors > Overview, Behaviour, Acquisition, Admin, Tag Manager, Funnels)
4. Submit a pull request

### Credits

- Theme by [Openmost](https://openmost.com)
