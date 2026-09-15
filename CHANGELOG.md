# Changelog

## v6.0.0

### Matomo 6

- Compatibility with Matomo 6.x (`>=6.0.0-b1,<7.0.0-b1`), requires PHP 8.1+ and MySQL 8.0+ (or MariaDB 10.6+).

### Improvements

- **Dark for every user**: Matomo 6 comes with a light / dark mode chosen by each user. Dark Theme keeps applying its dark palette to everyone, whatever mode they picked, and the page is rendered in dark mode so the Matomo dark mode rules (icons, flags, charts, maps) apply as well.
- The theme mode stays dark when Matomo switches it in the browser, e.g. after saving the personal settings. The theme mode choice is hidden from the personal settings, as it has no effect.
- Browser controls (scrollbars, form fields, date pickers) are drawn dark (`color-scheme: dark`).
- Palette completed with the colors added in Matomo 6: success, input placeholder, alternative border and overlay shadow. Colors added by future Matomo releases use their dark value.
- Report emails keep Matomo's light colors, so they stay readable in every mail client.
- Login and onboarding overrides are loaded again (they were missing from the stylesheet entry point).

### Technical

- Less overrides use the Matomo 6 theme variables instead of the deprecated `widget-*` and `menu-contrast-*` ones.
- Removed the overrides of UI elements that no longer exist in Matomo 6 (legacy selector dropdown, Transitions text), and the empty `_variables.less` and config files.
- New TypeScript library built with the Matomo Vite build (`vue/`), locking the dark mode in the browser.
- Plugin homepage and support on openmost.com.

## v5.3.0

### Breaking
- Bumped Matomo requirement to **>= 5.10.0** (the new theme variable API is required)

### Major Improvements
- **Theme variables moved to PHP** — colors are now configured through the `Theme.configureThemeVariables` event in `DarkTheme.php`, replacing the previous CSS custom-properties approach. This makes the theme respect Matomo's native `ThemeStyles` system and stay consistent across core and plugins.
- **Reorganised color tokens** into four explicit scales:
  - **Brand**: `primary`, `primaryLight`, `primaryLighter`, `primaryDark`
  - **Surface**: `surfaceGround`, `surfaceBase`, `surfaceRaised`, `surfaceOverlay`
  - **Text**: `textPrimary`, `textSecondary`, `textTertiary`, `textDisabled`
  - **Border**: `borderSubtle`, `borderStrong`
- **Wider coverage of `ThemeStyles` properties**: focus rings, menu hover background, disabled backgrounds, light borders, box shadow, and `filterOnIllustration` (auto-inverts white illustrations/SVGs for dark backgrounds).

### Component Reorganisation
- Removed component overrides now handled correctly by the PHP theme variables: `_button`, `_card`, `_modal`, `_table`, `_form`, `_headings`, `_icon`, `_tabs`, `_menu`, `_search`, `_dialog`, `_chip`, `_collection`, `_data_list`, `_datepicker`, `_canvas`, `_demo`, `_debug_preview`, `_message`, `_overlay`, `_plugin`, `_plugin_promo`, `_scrollbar`, `_segment_generator`, `_sidenav`, `_sparkline`, `_annotation`, `_comparison`, `_control`, `_expandable_selector`, `_map`, `_navbar`, `_ui_menu`, `_ai_chat`, `_visits_live`.
- Added focused stylesheets where targeted overrides are still needed:
  - `_activity_log`, `_copy_clipboard`, `_custom_reports`, `_input`, `_jqplot`, `_multi_sites`, `_scheduled_reports`, `_segment`, `_sidebar`, `_transitions_report`
- Renamed `_tagmanager` / `_tagmanager_debugbar` / `_transitions` to `_tag_manager` and `_transitions_report`.
- Updated remaining component overrides: `_admin`, `_alert`, `_dropdown`, `_entity_list`, `_funnels`, `_notification`, `_visitor_profile`, `_visits_log`, `_widget`.

### Fixes
- Light text now consistently rendered on dark surfaces (previous override forced dark text in some screens).
- Restored visibility of disabled inputs and buttons via `colorBackgroundDisabled` / `colorTextDisabled`.
- Focus ring is now visible on form fields and links.

## v5.2.1

- Support AI Chats plugins (ChatGPT and MistralAI)

## v5.2.0

### Major Improvements
- **Complete CSS Variable System**: Expanded color palette with light scale, primary scale, dark scale, borders, surfaces, text, and status colors
- **Chart Theming**: Full jqPlot chart support using Matomo color namespaces (sparklines, bar graphs, pie charts, evolution graphs)
- **Map Theming**: RealTimeMap and UserCountryMap with proper dark backgrounds and blue choropleth color scales
- **Consistent UI**: Unified backgrounds across sidebar, cards, widgets, and modals

### Features
- Added comprehensive color variables for easy customization
- Implemented Matomo theming documentation standards for charts and maps
- Added choropleth range colors for data visualization on maps
- Added special metrics color scale for visitor maps
- Improved modal header/body background consistency
- Widget title now matches widget body background

### Fixes
- Fixed chart canvas backgrounds to match card backgrounds
- Fixed map country boundaries visibility on active countries
- Fixed search input text visibility (white text on dark background)
- Fixed action list timeline colors (darker than card background)
- Fixed alert and notification border colors for better contrast
- Fixed dropdown menu styling consistency
- Fixed visitor log timeline styling

### Styling
- Grid lines in charts now subtle gray instead of harsh black/white
- Improved hover states across interactive elements
- Better shadow definitions for depth perception
- Refined border colors for visual hierarchy

## v5.1.1

- matomo error: re-submit theme changes

## v5.1.0

- fix: Dropdown search items color
- update: Users invitation page

## v5.0.14

- fix: comparison title color

## v5.0.13

- update: Funnels plugin support

## v5.0.12

- fix: visitor log exand text color

## v5.0.11

- fix: profile icon

## v5.0.10

- fix: Email text color and calendar active days

## v5.0.9

- update: Support AI Chats plugin (ChatGPT and MistralAI)

## v5.0.8

- update: marketplace cover

## v5.0.7

- Update: Color scheme

## v5.0.6
- Update documentation URL

## v5.0.5
- Fix forgot password message box

## v5.0.4
- Update Calendar, TagManager chip and CustomReport dimension selector

## v5.0.2
- Set theme logo

## v5.0.0
- Compatibility to Matomo 5.x
- Start using semver for versioning

## v1.2.2
- Fix profile name color

## v1.2.0
- Fix TagManager debug input background color
- Remove bold font-weight on navbar controls
- Fix jqplot canvas popover inverted color
- Fix segment comparator icon view
- Fix TagManager version description textarea background color
- Change notification background color
- Set theme variables in PHP DarkTheme.php file

## v1.1.7
- Fix dropdown in user permissions and capabilities
- Fix form input while disabled
- Fix demo page cards

## v1.1.6
- Fix user profile icon contrast

## v1.1.5
- Fix username text color in "Realtime Visitor"

## v1.1.4
- Fix device icon in all pages
- Fix Apple icon
- Fix IOS icon

## v1.1.3
- Fix device icon in real time visitor log
- Fix date selector in "period" mode
- Fix background color for collapsing navbar

## v1.1.0
- Support for Matomo 4.x
- Fix sidenav CSS
- Fix chip CSS
- Fix collection CSS (Tag Manager)

## v1.0.0
- Refactoring calendar
- Revert icon colors
- Many patches

## v0.1.5
- Updating dropdowns
- Updating segment selector
- Updating widget selector

## v0.1.4
- Updating GitHub default repo
- Major update incoming 

## v0.1.0
- Majors screen are done
