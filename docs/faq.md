## FAQ

### Installation

**How do I install this theme?**

1. Go to your Matomo Administration panel
2. Navigate to Marketplace > Themes
3. Search for "Dark Theme"
4. Click Install and then Activate

Alternatively, download from GitHub and extract to your `plugins/` directory.

**Is the theme active for all users?**

Yes. When activated, the Dark Theme applies to all users on your Matomo instance. Individual users cannot switch between themes.

**Which Matomo versions are supported?**

- Dark Theme **5.3.x** requires **Matomo 5.10.0 or later** (uses the extended `ThemeStyles` API).
- Dark Theme **5.2.x** supports Matomo 5.0 – 5.9.
- For Matomo 4.x, use Dark Theme version 1.x.

### Compatibility

**Does it work with Matomo plugins?**

Yes. The theme is designed to work with official Matomo plugins including:
- Tag Manager
- Funnels
- Heatmaps & Session Recording
- AI Chats (ChatGPT, MistralAI)
- Custom Reports
- Multi Sites Dashboard
- Scheduled Reports
- Transitions
- And more

**Will it conflict with other themes?**

No. Only one theme can be active at a time. The Dark Theme completely replaces the default styling without modifying any HTML structure.

**Does it affect tracking or data collection?**

No. This is a purely visual theme. It does not modify tracking code, data collection, or any Matomo functionality.

### Customization

**Can I customize the colors?**

Yes. Since version 5.3.0, all colors are configured in PHP via Matomo's `Theme.configureThemeVariables` event. The recommended approach is to fork the plugin and edit `DarkTheme.php`:

```php
$vars->colorBrand            = '#your-brand-color';
$vars->colorBackgroundBase   = '#your-page-background';
$vars->colorWidgetBackground = '#your-widget-background';
```

You can also override the generated CSS variables from a custom stylesheet:

```css
:root {
  --theme-color-brand: #your-brand-color;
  --theme-color-background-base: #your-page-background;
}
```

Remember to clear Matomo's cache after any change.

**Where are the style files located?**

- Color configuration: `DarkTheme.php` (in `configureThemeVariables()`)
- LESS overrides: `stylesheets/` (entry point: `theme.less`)
- Per-component overrides: `stylesheets/components/`

**Can I modify specific components?**

Yes. The component files in `stylesheets/components/` each target a focused area (visitor log, sidebar, dropdowns, charts, plugin-specific UIs, etc.). Edit the relevant file and clear the asset cache.

### Troubleshooting

**Charts or maps don't look right**

Clear your Matomo cache:
1. Go to Administration > System > General Settings
2. Click "Clear all caches"

Or delete the files in the `tmp/assets/` directory.

**Some elements still appear light**

This is usually a caching issue. Clear both Matomo's asset cache and your browser cache. If the problem persists, please report it on GitHub.

**The theme stopped working after a Matomo update**

We update the theme regularly to support new Matomo versions. Check for theme updates in the Marketplace. If no update is available yet, please open a GitHub issue.

**I just upgraded to Dark Theme 5.3 and the install fails**

Dark Theme 5.3 requires **Matomo 5.10.0 or later**. Either upgrade Matomo or stay on Dark Theme 5.2.x until you can.

**My custom CSS variable overrides stopped working after upgrading to 5.3**

The internal variable names changed when colors moved from local CSS custom properties to Matomo's `ThemeStyles` system. Override the official `--theme-color-*` variables (e.g. `--theme-color-brand`, `--theme-color-background-base`) instead of the previous `--primary` / `--dark` names.

### Support & Contributing

**How can I report a bug?**

Open an issue on our [GitHub repository](https://github.com/openmost/DarkTheme/issues) with:
- Matomo version
- Dark Theme version
- Browser and version
- Screenshot of the issue

**How can I contribute?**

Fork the repository, make your changes (prefer adjusting `DarkTheme.php` over adding new LESS overrides when possible), test on the main Matomo screens, and submit a pull request.

**How long will this theme be maintained?**

We actively use this theme on multiple production Matomo instances and are committed to maintaining compatibility with new Matomo releases.

**Can you create a custom theme for my company?**

Yes! We offer custom theme development. Contact us at ronan@openmost.io or visit [openmost.io](https://openmost.io).
