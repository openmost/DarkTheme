# Dark Theme

A modern dark theme for Matomo, applied to every user of the instance.

## Features

- **Dark for every user**: Matomo 6 lets each user pick a light or dark mode. Dark Theme gives the whole team the same dark interface, whatever mode each user picked. The theme mode option is hidden from the personal settings while the theme is active, as it has no effect.
- **Complete coverage**: every screen, widget, modal and component is styled, including browser controls (scrollbars, form fields, date pickers).
- **Charts and maps**: sparklines, bar, pie and evolution graphs, the Real-time Map and the Visitor Map render on dark backgrounds with readable data colors.
- **Readable report emails**: scheduled report emails keep Matomo's light colors, so they display well in every mail client.
- **Native theme API**: colors are set through Matomo's `Theme.configureThemeVariables` event, so core and third party plugins pick them up. Colors added by future Matomo releases fall back to their Matomo dark value.
- **Plugin support**: Tag Manager, Funnels, Custom Reports, All Websites dashboard, Scheduled Reports, Transitions, AI chat plugins (ChatGPT, MistralAI) and more.
- **Purely visual**: no change to tracking, data or reports.

## Requirements

- Matomo 6 (`>=6.0.0-b1,<7.0.0-b1`)
- PHP 8.1 or higher
- On Matomo 5, install Dark Theme 5.x instead (5.3.x for Matomo 5.10 or later, 5.2.x for Matomo 5.0 to 5.9).

## Installation / Configuration

1. Go to *Administration > Platform > Marketplace*, filter by **Themes** and search for "Dark Theme".
2. Click **Install**, then **Activate**. The theme applies to every user immediately.
3. If some elements still look light after an update, run `./console core:clear-caches` on the server, or empty `tmp/assets/`.

There are no settings. To let each user choose their own mode again, activate the default Matomo theme (Morpheus).

To adjust the palette, fork the plugin and edit the brand, surface, text and border scales in `DarkTheme.php`, or override the generated `--theme-color-*` CSS variables from your own plugin. See [docs/index.md](docs/index.md).

## Need help with Matomo?

Openmost is an official Matomo Implementation Partner. We also build [custom Matomo themes](https://openmost.com/matomo/services/custom-theme?utm_source=matomo_marketplace&utm_medium=referral&utm_campaign=services&utm_content=darktheme) in your own brand colours and fonts, on the official theme API, in light and dark mode.

## Support

- Homepage: https://openmost.com/matomo/extensions/dark-theme
- Issues: https://github.com/openmost/DarkTheme/issues
- Email: ronan@openmost.com

## Screenshots

See the `screenshots/` folder, or the plugin page on the Matomo Marketplace.

## License

GPL v3 or later, see [LICENSE](LICENSE).
