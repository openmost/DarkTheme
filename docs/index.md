## Documentation

Dark Theme transforms your Matomo Analytics interface into a modern dark experience. This documentation covers the theme architecture and how to customize it.

### Theme Architecture

Since version 5.3.0, all colors are configured in PHP through Matomo's `Theme.configureThemeVariables` event. LESS files are kept only for the few areas that need targeted overrides on top of the variables provided by Matomo core.

```
DarkTheme/
├── DarkTheme.php          # Theme variable configuration
├── plugin.json            # Theme metadata (requires Matomo >= 5.10)
└── stylesheets/
    ├── theme.less         # Main entry point
    ├── _variables.less    # Local LESS variables (optional)
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
        ├── _dropdown.less
        ├── _entity_list.less
        ├── _funnels.less
        ├── _input.less
        ├── _jqplot.less          # Chart canvas, tooltips, axes
        ├── _multi_sites.less
        ├── _notification.less
        ├── _scheduled_reports.less
        ├── _segment.less
        ├── _sidebar.less
        ├── _tag_manager.less
        ├── _transitions_report.less
        ├── _visitor_profile.less
        ├── _visits_log.less
        └── _widget.less
```

### PHP Theme Variables

`DarkTheme.php` registers a `configureThemeVariables` listener and assigns values to `\Piwik\Plugin\ThemeStyles`. The values are exposed by Matomo core as CSS custom properties (e.g. `--theme-color-background-base`) and are used throughout the UI.

The palette is split into four scales:

**Brand**
- `primary` — `#4a6fc7`
- `primaryLight` — `#6b8fd9` (used for links, focus ring, selected menu)
- `primaryLighter` — `#8aa8e6`
- `primaryDark` — `#3450a3`

**Surfaces (lightest → darkest)**
- `surfaceOverlay` — `#3a424d` (popovers, hover backgrounds, code blocks)
- `surfaceRaised` — `#2b3138` (widgets, cards, header background)
- `surfaceBase` — `#202329` (page background)
- `surfaceGround` — `#181a1f` (deepest layer / scrollbar track)

**Text (most prominent → faded)**
- `textPrimary` — `#ffffff`
- `textSecondary` — `rgba(255, 255, 255, 0.85)`
- `textTertiary` — `rgba(255, 255, 255, 0.65)`
- `textDisabled` — `rgba(255, 255, 255, 0.40)`

**Borders**
- `borderSubtle` — `#3a424d`
- `borderStrong` — `#4a525d`

These local variables feed the official `ThemeStyles` properties: `colorBrand`, `colorText*`, `colorBackground*`, `colorBorder*`, `colorWidget*`, `colorMenuContrast*`, `colorHeader*`, `colorLink`, `colorFocusRing`, `colorCode*`, `colorBoxShadow`, and `filterOnIllustration` (which inverts white illustrations so they look right on a dark background).

### Customizing Colors

The recommended way to customize the palette is to fork the plugin and adjust the variables in `DarkTheme.php`:

```php
public function configureThemeVariables(Plugin\ThemeStyles $vars)
{
    $vars->colorBrand            = '#your-brand-color';
    $vars->colorBackgroundBase   = '#your-background-color';
    $vars->colorWidgetBackground = '#your-widget-color';
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

After changing any value, clear Matomo's asset cache (Administration → System → General Settings → "Clear all caches").

### Component Overrides

Each file in `stylesheets/components/` targets a specific area of Matomo where the default core CSS does not pick up the theme variables cleanly:

- **Charts** (`_jqplot.less`) — canvas backgrounds, gridlines, tooltips
- **Forms & dropdowns** (`_input.less`, `_dropdown.less`) — input backgrounds, autocomplete panels
- **Visitor reports** (`_visits_log.less`, `_visitor_profile.less`, `_activity_log.less`) — timeline and profile colours
- **Plugin support** — `_funnels.less`, `_tag_manager.less`, `_custom_reports.less`, `_multi_sites.less`, `_scheduled_reports.less`, `_transitions_report.less`
- **UI chrome** — `_sidebar.less`, `_admin.less`, `_widget.less`, `_alert.less`, `_notification.less`, `_segment.less`, `_entity_list.less`, `_copy_clipboard.less`

### Compatibility

Dark Theme 5.3.x requires **Matomo 5.10.0 or later** because it relies on the extended `ThemeStyles` API. For Matomo 5.0–5.9, use Dark Theme 5.2.x. For Matomo 4.x, use Dark Theme 1.x.

### Contributing

To contribute:

1. Fork the [GitHub repository](https://github.com/openmost/DarkTheme)
2. Add or refine variables in `DarkTheme.php` first; only fall back to LESS overrides when a setting is not exposed by `ThemeStyles`
3. Test across Matomo's main screens (Dashboard, Visitors → Overview, Behaviour, Acquisition, Admin, Tag Manager, Funnels)
4. Submit a pull request

### Credits

- Theme by [Openmost](https://openmost.io)
