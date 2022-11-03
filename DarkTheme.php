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
        $primary = '#3450a3';

        $vars->colorBrand = $primary;
        $vars->colorBrandContrast = '#ffffff';
        $vars->colorText = '#f7f7f7';
        $vars->colorTextLight = '#444';
        $vars->colorTextLighter = '#666666';
        $vars->colorTextContrast = '#37474f';
        $vars->colorLink = $primary;
        $vars->colorBaseSeries = '#ee3024';
        $vars->colorHeadlineAlternative = '#4E4E4E';
        $vars->colorHeaderBackground = '#2b3138';
        $vars->colorHeaderText = '#ffffff';

        $vars->colorMenuContrastText = '#f1f1f1';
        $vars->colorMenuContrastTextSelected = $primary;
        $vars->colorMenuContrastTextActive = '#fff';
        $vars->colorMenuContrastBackground = 'transparent';

        $vars->colorWidgetBorder = '#2b3138';
        $vars->colorWidgetBackground = '#2b3138';
        $vars->colorWidgetExportedBackgroundBase = '#2b3138';
        $vars->colorWidgetTitleBackground = '#2b3138';
        $vars->colorWidgetTitleText = '#fff';

        $vars->colorBackgroundBase = '#202329';
        $vars->colorBackgroundTinyContrast = '#f2f2f2';
        $vars->colorBackgroundLowContrast = '#d9d9d9';
        $vars->colorBackgroundContrast = '#2b3138';
        $vars->colorBackgroundHighContrast = '#202020';

        $vars->colorBorder = '#cccccc';
        $vars->colorCode = '#f3f3f3';
        $vars->colorCodeBackground = '#4d4d4d';
    }
}
