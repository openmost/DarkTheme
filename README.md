# Dark Theme

A modern dark theme for Matomo, applied to every user of the instance.

## Features

- **Dark for every user**: every theme color gets a single dark value, so the whole team gets the same dark interface whatever light or dark mode each user picked.
- **Complete coverage**: every screen, widget, modal and component is styled, including the scrollbars.
- **Charts and maps**: sparklines, bar, pie and evolution graphs, the Real-time Map and the Visitor Map render on dark backgrounds with readable data colors.
- **Native theme API**: colors are set through Matomo's `Theme.configureThemeVariables` event, so core and third party plugins pick them up.
- **Plugin support**: Tag Manager, Funnels, Custom Reports, All Websites dashboard, Scheduled Reports, Transitions, AI chat plugins (ChatGPT, MistralAI) and more.
- **Purely visual**: no change to tracking, data or reports.

## Requirements

- Matomo 5.10.0 or later, up to Matomo 6 excluded (`>=5.10.0,<6.0.0-b1`)
- For Matomo 5.0 to 5.9, install Dark Theme 5.2.x. On Matomo 6, install Dark Theme 6.x.

## Installation / Configuration

1. Go to *Administration > Platform > Marketplace*, filter by **Themes** and search for "Dark Theme".
2. Click **Install**, then **Activate**. The theme applies to every user immediately.
3. If some elements still look light after an update, run `./console core:clear-caches` on the server, or empty `tmp/assets/`.

There are no settings. To go back to the default look, activate the default Matomo theme (Morpheus).

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
