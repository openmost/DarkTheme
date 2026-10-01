## FAQ

### Installation

**How do I install this theme?**

1. Go to your Matomo Administration panel
2. Navigate to Marketplace > Themes
3. Search for "Dark Theme"
4. Click Install and then Activate

Alternatively, download from GitHub and extract to your `plugins/` directory.

**Is the theme active for all users?**

Yes. When activated, the Dark Theme applies to all users on your Matomo instance, whatever theme mode they picked in their personal settings.

**Which Matomo versions are supported?**

- Dark Theme **6.x** supports Matomo 6.
- Dark Theme **5.3.x** requires Matomo 5.10.0 or later.
- Dark Theme **5.2.x** supports Matomo 5.0 to 5.9.
- For Matomo 4.x, use Dark Theme version 1.x.

### Matomo dark mode

**Matomo 6 has a dark mode, why use Dark Theme?**

In Matomo 6, each user chooses a light or dark mode, and the default is light. Dark Theme gives the whole team the same dark interface, with Openmost's dark palette, without asking every user to change their settings.

**Why is the theme mode option missing from my personal settings?**

Dark Theme applies the dark mode to every user, so the option has no effect and is hidden. It comes back when the default Matomo theme is activated.

**Can a user still choose the light mode?**

No, not while Dark Theme is active. To let each user choose, activate the default Matomo theme (Morpheus) under *Administration > Themes*.

**Are the scheduled report emails dark too?**

No. Report emails keep Matomo's light colors: many mail clients force a white background, where light text would not be readable.

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

No. Only one theme can be active at a time. The Dark Theme replaces the default styling without modifying any HTML structure.

**Does it affect tracking or data collection?**

No. This is a purely visual theme. It does not modify tracking code, data collection, or any Matomo functionality.

### Customization

**Can I customize the colors?**

Yes. All colors are configured in PHP via Matomo's `Theme.configureThemeVariables` event. The recommended approach is to fork the plugin and edit the palette in `DarkTheme.php`:

```php
$vars->colorBrand              = '#your-brand-color';
$vars->colorBackgroundBase     = '#your-page-background';
$vars->colorBackgroundContrast = '#your-widget-background';
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

- Color configuration: `DarkTheme.php`
- LESS overrides: `stylesheets/` (entry point: `theme.less`)
- Per-component overrides: `stylesheets/components/`

### Troubleshooting

**Charts or maps don't look right**

Clear the Matomo caches: run `./console core:clear-caches` on the server, or delete the files in the `tmp/assets/` directory.

**Some elements still appear light**

This is usually a caching issue. Clear both Matomo's asset cache and your browser cache. If the problem persists, please report it on GitHub.

**The theme stopped working after a Matomo update**

We update the theme regularly to support new Matomo versions. Check for theme updates in the Marketplace. If no update is available yet, please open a GitHub issue.

**My custom CSS overrides using `--theme-color-widget-*` or `--theme-color-menu-contrast-*` variables**

These variables are deprecated since Matomo 6 and will be removed in Matomo 7. Prefer `--theme-color-background-contrast`, `--theme-color-background-tinyContrast` and `--theme-color-text-highContrast`.

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

Yes. Openmost builds [custom Matomo themes](https://openmost.com/matomo/services/custom-theme?utm_source=matomo_marketplace&utm_medium=referral&utm_campaign=services&utm_content=darktheme) in your own brand colours and fonts, on the official theme API. You can also contact us at ronan@openmost.com.
