## Documentation

Dark Theme transforms your Matomo Analytics interface into a modern dark experience. This documentation covers the theme architecture and customization options.

### Theme Architecture

The theme uses LESS preprocessing and is organized into modular components for maintainability:

```
stylesheets/
├── theme.less          # Main entry point
├── _variables.less     # CSS custom properties
└── components/
    ├── _alert.less     # Alerts and notifications
    ├── _button.less    # Buttons and actions
    ├── _calendar.less  # Date picker
    ├── _canvas.less    # Charts (jqPlot)
    ├── _card.less      # Cards and widgets
    ├── _dropdown.less  # Dropdown menus
    ├── _form.less      # Form inputs
    ├── _map.less       # RealTimeMap & UserCountryMap
    ├── _modal.less     # Modal dialogs
    ├── _nav.less       # Navigation
    ├── _table.less     # Data tables
    ├── _visits_log.less # Visitor log
    └── ...
```

### CSS Variables

The theme defines a comprehensive set of CSS custom properties in `_variables.less`:

#### Color Scales

**Light Scale** (for text and highlights):
- `--light` - Pure white (#ffffff)
- `--light-muted` - Slightly transparent white (85%)
- `--light-transparent` - Semi-transparent (70%)
- `--light-subtle` - Subtle text (50%)
- `--light-dimmed` - Dimmed elements (30%)

**Primary Scale** (brand/accent colors):
- `--primary` - Main accent (#3450a3)
- `--primary-light` - Hover states
- `--primary-lighter` - Active states
- `--primary-dark` - Pressed states
- `--primary-subtle` - Backgrounds with accent

**Dark Scale** (backgrounds):
- `--dark-elevated` - Elevated surfaces
- `--dark` - Card/widget backgrounds (#2b3138)
- `--darker` - Page background (#202329)
- `--darkest` - Deepest shadows

#### Semantic Variables

- **Borders**: `--border-light`, `--border-medium`, `--border-strong`
- **Surfaces**: `--surface-base`, `--surface-raised`, `--surface-overlay`
- **Text**: `--text-primary`, `--text-secondary`, `--text-tertiary`
- **Status**: `--success`, `--warning`, `--danger`, `--info`

### Customizing Colors

Override any CSS variable in your own stylesheet:

```css
:root {
  --primary: #your-brand-color;
  --dark: #your-background-color;
}
```

### Chart Theming

Charts use Matomo's color namespace system. The theme defines colors for:

- `.sparkline-colors` - Sparkline graphs
- `.bar-graph-colors` - Bar charts
- `.pie-graph-colors` - Pie charts
- `.evolution-graph-colors` - Line/evolution charts

Each namespace defines background, series colors, grid lines, and labels.

### Map Theming

Maps use dedicated color namespaces:

- `.realtime-map-colors` - Real-time visitor map
- `.visitor-map-colors` - User country/region maps

These define country fills, boundaries, choropleth ranges, and tooltips.

### Contributing

To contribute:

1. Fork the [GitHub repository](https://github.com/openmost/DarkTheme)
2. Follow the component-based structure
3. Test across different Matomo screens
4. Submit a pull request

### Credits

- Theme by [Openmost](https://openmost.io)
- Sparkline implementation inspired by [lw1.at](https://lw1.at)
