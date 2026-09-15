# Dark Theme

## Description

A comprehensive dark theme for Matomo Analytics that transforms the entire interface into a modern, eye-friendly dark experience. Built with accessibility and readability in mind, this theme provides proper contrast ratios and consistent styling across all Matomo features.

### Key Features

- **Dark for every user** - The whole team gets the same dark interface, whatever theme mode each user picked in their personal settings
- **Complete UI Coverage** - Every screen, widget, modal, and component is styled for dark mode, including the browser controls (scrollbars, form fields, date pickers)
- **Charts & Graphs** - All charts (sparklines, bar graphs, pie charts, evolution graphs) render with dark backgrounds and vibrant data colors
- **Maps Support** - RealTimeMap and UserCountryMap fully themed
- **Readable Emails** - Scheduled report emails keep Matomo's light colors, so they display well in every mail client
- **Native Theme API** - Built on Matomo's `Theme.configureThemeVariables` event and dark mode for seamless integration with core and plugins
- **Plugin Compatibility** - Works with Matomo plugins including Funnels, Tag Manager, AI Chats, Custom Reports, Multi Sites, Scheduled Reports, Transitions, and more

### Why Choose Dark Theme?

Matomo 6 lets each user pick a light or dark mode. Dark Theme is for teams who want dark for everyone, with Openmost's own dark palette:

- Reduces eye strain during extended analytics sessions
- Perfect for low-light environments and night-time work
- Professional appearance that matches modern dark-mode applications
- Actively maintained and updated with each Matomo release

### Customization

The theme exposes its full color palette through Matomo's `ThemeStyles` API in `DarkTheme.php`. Fork the plugin to adjust the brand, surface, text, and border scales, or override the generated `--theme-color-*` CSS variables from your own stylesheet to match your brand.

**Thank you for installing!**

## Requirements

- Matomo **6** (Dark Theme 6.x), PHP 8.1 or higher, MySQL 8.0+ or MariaDB 10.6+
- For Matomo 5.10 or later, install Dark Theme **5.3.x**
- For Matomo 5.0 to 5.9, install Dark Theme **5.2.x**
- For Matomo 4.x, install Dark Theme **1.x**

## Installation

**From the Marketplace (recommended)**

1. Go to *Administration > Marketplace > Themes*
2. Search for "Dark Theme"
3. Click **Install**, then **Activate**

**Manual installation**

1. Download the latest release from [GitHub](https://github.com/openmost/DarkTheme)
2. Extract the archive into your Matomo `plugins/` directory as `plugins/DarkTheme/`
3. Activate the theme under *Administration > Themes*

After installation or any customization, clear the asset cache: *Administration > System > General Settings > "Clear all caches"*.

## Documentation

- [Theme architecture and customization](docs/index.md)
- [Frequently asked questions](docs/faq.md)
- [Changelog](CHANGELOG.md)

## Support

If you encounter any issues or have suggestions, please:
- Open an issue on [GitHub](https://github.com/openmost/DarkTheme/issues)
- Contact us at ronan@openmost.com

## Want More?

Looking for a custom theme tailored to your brand? We create bespoke Matomo themes for businesses. Visit [openmost.com](https://openmost.com) or contact us to discuss your requirements.

## License

Released under the [GPL v3 or later](LICENSE) license.
