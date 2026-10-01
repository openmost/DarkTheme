<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\DarkTheme;

use Piwik\Plugin;
use Piwik\Plugin\ThemeStyles;
use Piwik\View\HtmlReportEmailHeaderView;

class DarkTheme extends Plugin
{
    public function registerEvents()
    {
        return [
            'Template.afterEventsReport' => 'renderOpenmostCommunicationAfterEvents',
            'Widget.filterWidgets' => 'addOpenmostCommunicationWidgets',
            'Template.beforeContent' => 'renderOpenmostCommunication',
            'Theme.configureThemeVariables' => 'configureThemeVariables',
        ];
    }

    /**
     * Dark Theme applies to every user, whatever theme mode they picked in their personal settings:
     * every color gets a single dark value and the page is rendered in dark mode, so the core dark
     * mode rules apply too. The vue/src/ThemeModeLock library keeps it dark when Matomo switches
     * the mode in the browser.
     */
    public function configureThemeVariables(ThemeStyles $vars)
    {
        // report emails ask for the light mode: light text would not be readable on their white body
        if ($this->isRenderingReportEmail()) {
            return;
        }

        $this->forceDarkMode($vars);
        $this->applyPalette($vars);
        $this->keepDarkValuesOnly($vars);
    }

    private function applyPalette(ThemeStyles $vars): void
    {
        // Primary (brand) scale
        $primary      = '#4a6fc7';
        $primaryLight = '#6b8fd9';

        // Neutral / surface scale (lightest -> darkest)
        $surfaceOverlay = '#3a424d'; // dark-elevated, popovers, hover surfaces
        $surfaceRaised  = '#2b3138'; // widgets, cards, headers
        $surfaceBase    = '#202329'; // page background
        $surfaceGround  = '#181a1f'; // deepest layer / scrollbar track

        // Text scale (most prominent -> faded)
        $textPrimary   = '#ffffff';
        $textSecondary = 'rgba(255, 255, 255, 0.85)';
        $textTertiary  = 'rgba(255, 255, 255, 0.65)';
        $textDisabled  = 'rgba(255, 255, 255, 0.40)';

        // Borders
        $borderSubtle = '#3a424d';
        $borderStrong = '#4a525d';

        // Brand
        $vars->colorBrand         = $primary;
        $vars->colorBrandContrast = $textPrimary;
        $vars->colorSuccess       = '#66bb6a';

        // Focus
        $vars->colorFocusRing            = $primaryLight;
        $vars->colorFocusRingAlternative = $primaryLight;

        // Text
        $vars->colorTextHighContrast   = $textPrimary;
        $vars->colorText               = $textSecondary;
        $vars->colorTextContrast       = $textPrimary;
        $vars->colorTextLight          = $textTertiary;
        $vars->colorTextLighter        = $textTertiary;
        $vars->colorTextOnDisabled     = $textDisabled;
        $vars->colorTextDisabled       = $textDisabled;
        $vars->colorTextPlaceholder    = $textDisabled;
        $vars->colorTextInvert         = '#444';
        $vars->colorTextInvertContrast = '#000';
        $vars->colorTextInvertLight    = '#666';

        // Links
        $vars->colorLink = $primaryLight;

        // Charts
        $vars->colorBaseSeries = '#ee3024';

        // Headlines
        $vars->colorHeadlineAlternative = $textTertiary;

        // Header (top bar)
        $vars->colorHeaderBackground = $surfaceRaised;
        $vars->colorHeaderText       = $textPrimary;

        // Menus (deprecated in Matomo 6, no replacement yet)
        $vars->colorMenuContrastText            = $textTertiary;
        $vars->colorMenuContrastTextSelected    = $primaryLight;
        $vars->colorMenuContrastTextActive      = $textPrimary;
        $vars->colorMenuContrastBackground      = 'transparent';
        $vars->colorMenuContrastBackgroundHover = $surfaceOverlay;

        // Widgets (deprecated in Matomo 6, no replacement yet)
        $vars->colorWidgetBackground             = $surfaceRaised;
        $vars->colorWidgetBorder                 = $borderSubtle;
        $vars->colorWidgetExportedBackgroundBase = $surfaceRaised;
        $vars->colorWidgetTitleBackground        = $surfaceRaised;
        $vars->colorWidgetTitleText              = $textPrimary;

        // Backgrounds
        $vars->colorBackgroundBase         = $surfaceBase;
        $vars->colorBackgroundTinyContrast = $surfaceOverlay;
        $vars->colorBackgroundLowContrast  = $surfaceRaised;
        $vars->colorBackgroundContrast     = $surfaceRaised;
        $vars->colorBackgroundHighContrast = $surfaceGround;
        $vars->colorBackgroundDisabled     = '#303339';

        // Borders
        $vars->colorBorder            = $borderSubtle;
        $vars->colorBorderAlternative = $borderSubtle;
        $vars->colorBorderLight       = $borderStrong;

        // Code blocks
        $vars->colorCode           = $textSecondary;
        $vars->colorCodeBackground = $surfaceOverlay;

        // Shadows
        $vars->colorBoxShadow = 'rgba(0, 0, 0, 0.4)';
        $vars->shadowOverlay  = '0 0 3px rgba(0, 0, 0, 0.5), 0 10px 40px rgba(0, 0, 0, 0.5)';

        // Illustration filter (invert white pngs/svgs to look right on dark)
        $vars->filterOnIllustration = 'brightness(89%) invert(100%) hue-rotate(180deg)';
    }

    /**
     * The page is rendered with data-theme-mode="dark" and the browser scripts resolve the dark mode.
     * ThemeStyles only reads the mode from its constructor, hence the scoped closure.
     */
    private function forceDarkMode(ThemeStyles $vars): void
    {
        $setMode = \Closure::bind(static function (ThemeStyles $styles): void {
            $styles->themeMode = ThemeStyles::DARK_MODE;
        }, null, ThemeStyles::class);

        $setMode($vars);
    }

    /**
     * Colors the palette does not set, including ones added by future Matomo releases, keep their
     * dark value in both modes.
     */
    private function keepDarkValuesOnly(ThemeStyles $vars): void
    {
        foreach (get_object_vars($vars) as $name => $value) {
            if (is_array($value)) {
                $vars->$name = $value[1] ?? $value[0] ?? '';
            }
        }
    }

    private function isRenderingReportEmail(): bool
    {
        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20) as $frame) {
            if (($frame['class'] ?? '') === HtmlReportEmailHeaderView::class) {
                return true;
            }
        }

        return false;
    }

    public function renderOpenmostCommunication(&$out, $layout, $module = '', $action = '')
    {
        OpenmostCommunication::beforeContent($out, (string) $layout, (string) $module, (string) $action, $this->getPluginName());
    }

    public function addOpenmostCommunicationWidgets($list)
    {
        OpenmostCommunication::filterWidgets($list, $this->getPluginName());
    }

    public function renderOpenmostCommunicationAfterEvents(&$out, $dataTable = null)
    {
        OpenmostCommunication::afterEventsReport($out, $this->getPluginName());
    }
}
