<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\DarkTheme\Widgets;

use Piwik\Plugins\DarkTheme\OpenmostCommunication as Communication;
use Piwik\Widget\Widget;
use Piwik\Widget\WidgetConfig;

/**
 * Renders the Openmost communication page widgets, see OpenmostCommunication::filterWidgets()
 */
class OpenmostCommunication extends Widget
{
    public static function configure(WidgetConfig $config)
    {
        Communication::configureRenderWidget($config);
    }

    public function render()
    {
        return Communication::renderWidget('DarkTheme');
    }
}
