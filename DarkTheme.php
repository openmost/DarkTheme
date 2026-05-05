<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Plugins\DarkTheme;

use Piwik\Plugin;

class DarkTheme extends Plugin
{
    public function registerEvents()
    {
        return [
            'Theme.configureThemeVariables' => 'configureThemeVariables',
        ];
    }

    public function configureThemeVariables(Plugin\ThemeStyles $vars)
    {
        // Primary (brand) scale
        $primary       = '#4a6fc7';
        $primaryLight  = '#6b8fd9';
        $primaryLighter = '#8aa8e6';
        $primaryDark   = '#3450a3';

        // Neutral / surface scale (lightest -> darkest)
        $surfaceOverlay  = '#3a424d'; // dark-elevated, popovers, hover surfaces
        $surfaceRaised   = '#2b3138'; // widgets, cards, headers
        $surfaceBase     = '#202329'; // page background
        $surfaceGround   = '#181a1f'; // deepest layer / scrollbar track

        // Text scale (most prominent -> faded)
        $textPrimary    = '#ffffff';
        $textSecondary  = 'rgba(255, 255, 255, 0.85)';
        $textTertiary   = 'rgba(255, 255, 255, 0.65)';
        $textDisabled   = 'rgba(255, 255, 255, 0.40)';

        // Borders
        $borderSubtle  = '#3a424d';
        $borderStrong  = '#4a525d';

        // Brand
        $vars->colorBrand         = $primary;
        $vars->colorBrandContrast = $textPrimary;

        // Focus
        $vars->colorFocusRing            = $primaryLight;
        $vars->colorFocusRingAlternative = $primaryLight;

        // Text (dark theme always shows light text — both light/dark mode array entries
        // need to be light because the plugin is meant as a single dark experience)
        $vars->colorTextHighContrast = $textPrimary;
        $vars->colorText             = $textSecondary;
        $vars->colorTextContrast     = $textPrimary;
        $vars->colorTextLight        = $textTertiary;
        $vars->colorTextLighter      = $textTertiary;
        $vars->colorTextOnDisabled   = $textDisabled;
        $vars->colorTextDisabled     = $textDisabled;
        $vars->colorTextInvert       = '#444';
        $vars->colorTextInvertContrast = '#000';
        $vars->colorTextInvertLight  = '#666';

        // Links
        $vars->colorLink = $primaryLight;

        // Charts
        $vars->colorBaseSeries = '#ee3024';

        // Headlines
        $vars->colorHeadlineAlternative = $textTertiary;

        // Header (top bar)
        $vars->colorHeaderBackground = $surfaceRaised;
        $vars->colorHeaderText       = $textPrimary;

        // Menus (top navigation)
        $vars->colorMenuContrastText             = $textTertiary;
        $vars->colorMenuContrastTextSelected     = $primaryLight;
        $vars->colorMenuContrastTextActive       = $textPrimary;
        $vars->colorMenuContrastBackground       = 'transparent';
        $vars->colorMenuContrastBackgroundHover  = $surfaceOverlay;

        // Widgets
        $vars->colorWidgetBackground             = $surfaceRaised;
        $vars->colorWidgetBorder                 = $borderSubtle;
        $vars->colorWidgetExportedBackgroundBase = $surfaceRaised;
        $vars->colorWidgetTitleBackground        = $surfaceRaised;
        $vars->colorWidgetTitleText              = $textPrimary;

        // Backgrounds (used widely by Matomo core CSS)
        $vars->colorBackgroundBase         = $surfaceBase;
        $vars->colorBackgroundTinyContrast = $surfaceOverlay;
        $vars->colorBackgroundLowContrast  = $surfaceRaised;
        $vars->colorBackgroundContrast     = $surfaceRaised;
        $vars->colorBackgroundHighContrast = $surfaceGround;
        $vars->colorBackgroundDisabled     = '#303339';

        // Borders
        $vars->colorBorder      = $borderSubtle;
        $vars->colorBorderLight = $borderStrong;

        // Code blocks
        $vars->colorCode           = $textSecondary;
        $vars->colorCodeBackground = $surfaceOverlay;

        // Box shadow
        $vars->colorBoxShadow = 'rgba(0, 0, 0, 0.4)';

        // Illustration filter (invert white pngs/svgs to look right on dark)
        $vars->filterOnIllustration = 'brightness(89%) invert(100%) hue-rotate(180deg)';
    }
}