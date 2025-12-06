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

Dark Theme 5.x supports Matomo 5.0 and later. For Matomo 4.x, use Dark Theme version 1.x.

### Compatibility

**Does it work with Matomo plugins?**

Yes. The theme is designed to work with official Matomo plugins including:
- Tag Manager
- Funnels
- Heatmaps & Session Recording
- AI Chats (ChatGPT, MistralAI)
- Custom Reports
- And more

**Will it conflict with other themes?**

No. Only one theme can be active at a time. The Dark Theme completely replaces the default styling without modifying any HTML structure.

**Does it affect tracking or data collection?**

No. This is a purely visual theme. It does not modify tracking code, data collection, or any Matomo functionality.

### Customization

**Can I customize the colors?**

Yes. The theme uses CSS custom properties. Create a custom stylesheet and override variables like:

```css
:root {
  --primary: #your-color;
  --dark: #your-background;
}
```

**Where are the style files located?**

All styles are in the `stylesheets/` folder within the plugin directory. The main entry point is `theme.less`.

**Can I modify specific components?**

Yes. Components are organized in `stylesheets/components/`. Each file handles a specific UI element (buttons, modals, tables, etc.).

### Troubleshooting

**Charts or maps don't look right**

Clear your Matomo cache:
1. Go to Administration > System > General Settings
2. Click "Clear all caches"

Or delete files in `tmp/assets/` directory.

**Some elements still appear light**

This might be a caching issue. Clear both Matomo cache and your browser cache. If the issue persists, please report it on GitHub.

**The theme stopped working after a Matomo update**

We update the theme regularly to support new Matomo versions. Check for theme updates in the Marketplace. If no update is available yet, please open a GitHub issue.

### Support & Contributing

**How can I report a bug?**

Open an issue on our [GitHub repository](https://github.com/openmost/DarkTheme/issues) with:
- Matomo version
- Dark Theme version
- Browser and version
- Screenshot of the issue

**How can I contribute?**

We welcome contributions! Fork the repository, make your changes following the component-based structure, and submit a pull request.

**How long will this theme be maintained?**

We actively use this theme on multiple production Matomo instances. We're committed to maintaining compatibility with new Matomo releases.

**Can you create a custom theme for my company?**

Yes! We offer custom theme development. Contact us at ronan@openmost.io or visit [openmost.io](https://openmost.io).
