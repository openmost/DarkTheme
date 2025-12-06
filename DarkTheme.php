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
        // Primary colors
        $primary = '#4a6fc7';
        $primaryLight = '#6b8fd9';
        $primaryDark = '#3450a3';

        // Dark scale
        $darkest = '#181a1f';
        $darker = '#202329';
        $dark = '#2b3138';
        $darkElevated = '#3a424d';

        // Light scale
        $light = '#ffffff';
        $lightMuted = 'rgba(255, 255, 255, 0.85)';
        $lightTransparent = 'rgba(255, 255, 255, 0.7)';
        $lightSubtle = 'rgba(255, 255, 255, 0.5)';

        $vars->colorBrand = $primary;
        $vars->colorBrandContrast = $light;
        $vars->colorText = '#212121'; // Email text color (dark for white email backgrounds)
        $vars->colorTextLight = '#444444'; // Email secondary text
        $vars->colorTextLighter = '#666666'; // Email tertiary text
        $vars->colorTextContrast = $light;
        $vars->colorLink = $primaryLight;
        $vars->colorBaseSeries = '#ee3024';
        $vars->colorHeadlineAlternative = $lightTransparent;
        $vars->colorHeaderBackground = $dark;
        $vars->colorHeaderText = $light;

        $vars->colorMenuContrastText = $lightTransparent;
        $vars->colorMenuContrastTextSelected = $primaryLight;
        $vars->colorMenuContrastTextActive = $light;
        $vars->colorMenuContrastBackground = 'transparent';

        $vars->colorWidgetBorder = $darkElevated;
        $vars->colorWidgetBackground = $dark;
        $vars->colorWidgetExportedBackgroundBase = $dark;
        $vars->colorWidgetTitleBackground = $dark; // Same as widget body
        $vars->colorWidgetTitleText = $light;

        $vars->colorBackgroundBase = $darker;
        $vars->colorBackgroundTinyContrast = $darkElevated;
        $vars->colorBackgroundLowContrast = $dark;
        $vars->colorBackgroundContrast = $dark;
        $vars->colorBackgroundHighContrast = $darkest;

        $vars->colorBorder = $darkElevated;
        $vars->colorCode = $lightMuted;
        $vars->colorCodeBackground = $darkElevated;
    }
}
