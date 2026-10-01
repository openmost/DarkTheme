<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\DarkTheme;

use Piwik\API\Request;
use Piwik\Common;
use Piwik\DataTable;
use Piwik\Piwik;
use Piwik\Plugin;
use Piwik\Plugins\LanguagesManager\LanguagesManager;
use Piwik\Widget\WidgetConfig;
use Piwik\Widget\WidgetsList;

/**
 * Openmost communications shown inside Matomo by the Openmost plugins.
 *
 * Every Openmost plugin ships this same class. A campaign defines the message and the placement where it
 * appears, the first plugin to render a placement claims it, so a message appears once whatever the number
 * of Openmost plugins activated. Changing the message means changing CAMPAIGNS.
 */
class OpenmostCommunication
{
    /** Administration home page, rendered through Template.beforeContent, admin users only */
    public const PLACEMENT_ADMIN_HOME = 'admin_home';

    /**
     * AI Chatbots pages of AI Insights, rendered as a page widget under the "no data collected" notice of
     * BotTracking, with the same middleware so it is shown to the same users, only while that notice is.
     */
    public const PLACEMENT_AI_CHATBOTS_SETUP = 'ai_chatbots_setup';

    /**
     * Events page, rendered as a page widget above the Events report while Matomo shows its own events tips
     * (ProfessionalServices) and the site tracks no event, or only the default media events.
     */
    public const PLACEMENT_EVENTS_TRACKING = 'events_tracking';

    /**
     * Below the Events report, rendered through Template.afterEventsReport as a plain link, next to the tips
     * Matomo shows there. Logged in users only, as the link opens the Marketplace of the instance.
     */
    public const PLACEMENT_EVENTS_REPORT_FOOTER = 'events_report_footer';

    /** Action of the Widgets\OpenmostCommunication class every Openmost plugin ships */
    public const WIDGET_ACTION = 'openmostCommunication';

    /**
     * Placements rendered as page widgets. A widget with the same order as the first one of the page is added
     * after it, so an order of 0 lands right after the page notices and before the reports.
     */
    private const PAGE_PLACEMENTS = [
        self::PLACEMENT_AI_CHATBOTS_SETUP => [
            'category' => 'General_AIAssistants',
            'subcategories' => [
                'BotTracking_AIChatbotsOverview',
                'BotTracking_AIChatbotsContentRequests',
                'BotTracking_AIChatbotsRealtime',
            ],
            'order' => 0,
            'middleware' => ['module' => 'BotTracking', 'action' => 'showNoRecentRequestsMessage'],
        ],
        self::PLACEMENT_EVENTS_TRACKING => [
            'category' => 'General_Actions',
            'subcategories' => ['Events_Events'],
            'order' => 0,
            'middleware' => null,
        ],
    ];

    /** Event categories sent by the Matomo media and JavaScript error trackers without any tracking plan */
    private const DEFAULT_EVENT_CATEGORIES = ['MediaVideo', 'MediaAudio', 'JavaScript Errors'];

    private const SITE_URL = 'https://openmost.com';

    /**
     * Locales openmost.com serves today, a banner links to the page in the language of the Matomo user only
     * when that locale is listed here, to the English page otherwise. Every locale serves every page (with
     * the English content until its translation ships), so all are listed. This list mirrors the `available`
     * flags of the LanguageSwitcher of the website: flip a locale in both places together.
     */
    private const SITE_LOCALES = ['en', 'fr', 'de', 'es', 'it', 'ar', 'nl', 'pt', 'pl', 'zh-hans', 'zh-hant', 'ja'];

    /** Matomo language codes whose site locale is not their own code nor their base language */
    private const LANGUAGE_TO_SITE_LOCALE = ['zh-cn' => 'zh-hans', 'zh-tw' => 'zh-hant'];

    /**
     * Active campaigns, in display priority: the first one matching a placement is shown there.
     * Texts are translation keys of the OpenmostCommunication namespace. The paths of a banner are those of
     * its openmost.com page per site locale (localized slugs), the UTM parameters are the same in every one.
     */
    private const CAMPAIGNS = [
        'ai_chatbots_tracking' => [
            'placement' => self::PLACEMENT_AI_CHATBOTS_SETUP,
            'title' => 'OpenmostCommunication_AIChatbotsTitle',
            'body' => 'OpenmostCommunication_AIChatbotsBody',
            'cta' => 'OpenmostCommunication_AIChatbotsCta',
            'paths' => [
            'en' => '/matomo/services/ai-search-tracking',
            'fr' => '/fr/matomo/services/suivi-recherche-ia',
            'de' => '/de/matomo/dienstleistungen/ki-suche-tracking',
            'es' => '/es/matomo/servicios/seguimiento-busqueda-ia',
            'it' => '/it/matomo/servizi/tracciamento-ricerca-ia',
            'ar' => '/ar/matomo/services/ai-search-tracking',
            'nl' => '/nl/matomo/diensten/ai-zoek-tracking',
            'pt' => '/pt/matomo/servicos/rastreamento-pesquisa-ia',
            'pl' => '/pl/matomo/uslugi/sledzenie-wyszukiwania-ai',
            'zh-hans' => '/zh-hans/matomo/services/ai-search-tracking',
            'zh-hant' => '/zh-hant/matomo/services/ai-search-tracking',
            'ja' => '/ja/matomo/services/ai-search-tracking',
            ],
        ],
        'tracking_plan' => [
            'placement' => self::PLACEMENT_EVENTS_TRACKING,
            'title' => 'OpenmostCommunication_TrackingPlanTitle',
            'body' => 'OpenmostCommunication_TrackingPlanBody',
            'cta' => 'OpenmostCommunication_TrackingPlanCta',
            'paths' => [
            'en' => '/matomo/services/tracking-architecture',
            'fr' => '/fr/matomo/services/plan-de-marquage',
            'de' => '/de/matomo/dienstleistungen/tracking-konzept',
            'es' => '/es/matomo/servicios/plan-de-medicion',
            'it' => '/it/matomo/servizi/piano-di-tracciamento',
            'ar' => '/ar/matomo/services/tracking-architecture',
            'nl' => '/nl/matomo/diensten/trackingplan',
            'pt' => '/pt/matomo/servicos/plano-de-medicao',
            'pl' => '/pl/matomo/uslugi/plan-trackingu',
            'zh-hans' => '/zh-hans/matomo/services/tracking-architecture',
            'zh-hant' => '/zh-hant/matomo/services/tracking-architecture',
            'ja' => '/ja/matomo/services/tracking-architecture',
            ],
        ],
        // A link to a plugin opens its page in the Marketplace of the instance, hidden once one of them is activated
        'events_enhanced' => [
            'placement' => self::PLACEMENT_EVENTS_REPORT_FOOTER,
            'format' => 'link',
            'text' => 'OpenmostCommunication_EventsEnhancedLink',
            'marketplacePlugin' => 'EventsEnhanced',
            'hiddenWhenActivated' => ['EventsEnhanced', 'EventsEnhancedPremium'],
        ],
    ];

    private const REQUEST_CLAIMS = 'openmostCommunicationPlacements';

    private const LOGO = '<svg class="openmostCommunicationBanner__logo" xmlns="http://www.w3.org/2000/svg" viewBox="60 60.4 1038 180" aria-hidden="true" focusable="false"><path fill="#242C8F" d="M120 60.3975H240V180.398H180V120.398H120V60.3975Z"/><path fill="#426CDA" d="M60 120.398H120V180.398H180V240.398H60V120.398Z"/><path fill="#FFA32A" d="M120 120.398L60 120.398C60 87.2605 86.8629 60.3975 120 60.3975L120 120.398Z"/><path fill="#F25F6F" d="M180 180.397H240C240 213.535 213.137 240.398 180 240.398V180.397Z"/><g fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M327.881 192.837C336.443 197.651 345.841 200.058 356.074 200.058C366.308 200.058 375.705 197.651 384.268 192.837C392.83 188.023 399.566 181.379 404.473 172.902C409.486 164.321 411.992 154.642 411.992 143.863C411.992 133.19 409.486 123.615 404.473 115.138C399.566 106.558 392.83 99.8603 384.268 95.0466C375.81 90.233 366.412 87.8262 356.074 87.8262C345.841 87.8262 336.443 90.233 327.881 95.0466C319.318 99.8603 312.531 106.558 307.519 115.138C302.507 123.615 300 133.19 300 143.863C300 154.642 302.507 164.321 307.519 172.902C312.531 181.379 319.318 188.023 327.881 192.837ZM373.46 176.042C368.448 178.972 362.653 180.437 356.074 180.437C349.496 180.437 343.648 178.972 338.532 176.042C333.52 173.007 329.604 168.717 326.784 163.17C323.965 157.624 322.555 151.189 322.555 143.863C322.555 136.538 323.965 130.155 326.784 124.713C329.604 119.167 333.52 114.929 338.532 111.999C343.648 109.069 349.496 107.604 356.074 107.604C362.653 107.604 368.448 109.069 373.46 111.999C378.473 114.929 382.388 119.167 385.208 124.713C388.027 130.155 389.437 136.538 389.437 143.863C389.437 151.189 388.027 157.624 385.208 163.17C382.388 168.717 378.473 173.007 373.46 176.042Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="M461.783 114.667C456.876 117.283 453.012 120.579 450.193 124.556V111.999H428.264V240.398H450.193V186.558C453.221 190.534 457.137 193.831 461.94 196.447C466.848 199.063 472.486 200.371 478.856 200.371C486.27 200.371 492.953 198.487 498.905 194.72C504.961 190.953 509.712 185.668 513.158 178.867C516.709 171.96 518.484 164.059 518.484 155.165C518.484 146.27 516.709 138.474 513.158 131.776C509.712 124.975 504.961 119.742 498.905 116.08C492.953 112.417 486.27 110.586 478.856 110.586C472.486 110.586 466.796 111.946 461.783 114.667ZM492.796 141.665C494.989 145.433 496.086 149.932 496.086 155.165C496.086 160.501 494.989 165.106 492.796 168.978C490.708 172.849 487.888 175.832 484.338 177.925C480.892 180.018 477.133 181.064 473.061 181.064C469.093 181.064 465.334 180.07 461.783 178.082C458.337 175.989 455.518 173.006 453.325 169.135C451.237 165.263 450.193 160.711 450.193 155.478C450.193 150.246 451.237 145.694 453.325 141.822C455.518 137.95 458.337 135.02 461.783 133.032C465.334 130.939 469.093 129.893 473.061 129.893C477.133 129.893 480.892 130.887 484.338 132.875C487.888 134.863 490.708 137.793 492.796 141.665Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="M615.254 153.595C615.254 156.734 615.045 159.56 614.627 162.071H551.191C551.714 168.35 553.906 173.268 557.77 176.826C561.634 180.384 566.385 182.163 572.023 182.163C580.168 182.163 585.964 178.657 589.409 171.646H613.061C610.555 180.018 605.751 186.924 598.651 192.366C591.55 197.703 582.831 200.371 572.493 200.371C564.14 200.371 556.621 198.54 549.938 194.877C543.36 191.11 538.191 185.825 534.432 179.023C530.777 172.222 528.95 164.373 528.95 155.478C528.95 146.479 530.777 138.578 534.432 131.776C538.087 124.975 543.203 119.742 549.782 116.08C556.36 112.417 563.931 110.586 572.493 110.586C580.743 110.586 588.104 112.365 594.578 115.923C601.157 119.481 606.221 124.556 609.772 131.149C613.426 137.637 615.254 145.119 615.254 153.595ZM592.542 147.316C592.438 141.665 590.401 137.166 586.433 133.817C582.465 130.364 577.61 128.637 571.867 128.637C566.437 128.637 561.842 130.311 558.083 133.66C554.429 136.904 552.183 141.456 551.348 147.316H592.542Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="M883.711 194.877C890.394 198.54 897.913 200.371 906.266 200.371C914.724 200.371 922.347 198.54 929.134 194.877C936.026 191.11 941.456 185.825 945.424 179.023C949.496 172.222 951.533 164.373 951.533 155.478C951.533 146.584 949.549 138.735 945.581 131.933C941.717 125.131 936.392 119.899 929.604 116.237C922.817 112.469 915.246 110.586 906.893 110.586C898.539 110.586 890.969 112.469 884.181 116.237C877.394 119.899 872.016 125.131 868.048 131.933C864.185 138.735 862.253 146.584 862.253 155.478C862.253 164.373 864.133 172.222 867.892 179.023C871.755 185.825 877.028 191.11 883.711 194.877ZM917.387 178.396C913.941 180.279 910.234 181.221 906.266 181.221C900.001 181.221 894.78 179.023 890.603 174.628C886.531 170.129 884.495 163.745 884.495 155.478C884.495 147.212 886.583 140.881 890.76 136.485C895.041 131.986 900.314 129.736 906.579 129.736C912.845 129.736 918.118 131.986 922.399 136.485C926.785 140.881 928.978 147.212 928.978 155.478C928.978 161.025 927.934 165.734 925.845 169.605C923.757 173.477 920.937 176.407 917.387 178.396Z"/><path d="M1078.58 133.204V172.117C1078.58 175.047 1079.26 177.192 1080.61 178.553C1082.08 179.809 1084.48 180.436 1087.82 180.436H1098V198.959H1084.22C1065.73 198.959 1056.49 189.959 1056.49 171.96V133.204H1046.16V111.999H1056.49V90.4943H1078.58V111.999H1098V133.204H1078.58Z"/><path d="M685.894 198.964V133.203H653.785V198.964H631.073V112.012H687.52C699.165 112.012 708.605 121.453 708.605 133.098V198.964H685.894Z"/><path d="M776.928 133.193L776.927 198.958H799.638L799.639 133.193H824.23L824.23 198.958H846.941L846.942 133.088C846.942 121.443 837.501 112.003 825.856 112.003H729.468L729.468 198.958H752.179L752.18 133.193H776.928Z"/><path d="M989.576 133.168V146.927H1035.63V176.878C1035.63 189.069 1025.76 198.951 1013.6 198.951H965.925V177.761H1012.91V163.408H966.865V134.051C966.865 121.86 976.726 111.977 988.891 111.977H1035.63V133.168H989.576Z"/></g></svg>';

    private const ARROW = '<svg class="openmostCommunicationBanner__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="m12.987 3.586 6.293 6.293a3 3 0 0 1 .71 3.117h-6.625V13H3.64v-2h13.932l-6-6zm3.591 10.41L11.573 19l1.414 1.414 6.293-6.293q.061-.061.119-.125z"/></svg>';

    private const CLOSE = '<svg class="openmostCommunicationBanner__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><g fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.172 10.586 5.345 6.759l1.414-1.414 3.827 3.827a4 4 0 0 1 0 5.656l-3.827 3.827-1.414-1.414 3.827-3.827a2 2 0 0 0 0-2.828"/><path d="m18.655 6.76-3.827 3.826a2 2 0 0 0 0 2.828l3.827 3.827-1.414 1.414-3.827-3.827a4 4 0 0 1 0-5.656l3.827-3.827z"/></g></svg>';

    // Surfaces, borders and text follow the Matomo theme (light, dark, auto), the glows, grid, logo and
    // button keep the openmost.com palette on both themes. Every property Matomo styles globally is reset explicitly,
    // typography is !important so Matomo's `#content p` sizing on report pages, or a theme, cannot change it.
    private const BANNER_STYLE = '.openmostCommunicationBanner{position:relative;isolation:isolate;overflow:hidden;display:block;box-sizing:border-box;width:100%;margin:0 0 24px;padding:24px 64px 24px 28px;border:1px solid var(--theme-color-background-lowContrast);border-radius:8px;background:var(--theme-color-background-contrast);color:var(--theme-color-text);font-size:14px;line-height:1.5;text-align:left}'
        . '.openmostCommunicationBanner *{box-sizing:border-box}'
        . '.openmostCommunicationBanner__glow,.openmostCommunicationBanner__grid{position:absolute;inset:0;z-index:-1;pointer-events:none}'
        . '.openmostCommunicationBanner__glow{background:radial-gradient(34% 140% at 100% 0%,rgba(255,163,42,.26),transparent 72%),radial-gradient(28% 120% at 82% 100%,rgba(242,95,111,.18),transparent 72%),radial-gradient(34% 150% at 0% 0%,rgba(66,108,218,.22),transparent 72%)}'
        . '.openmostCommunicationBanner__grid{opacity:.7;background-image:linear-gradient(to right,rgba(66,108,218,.09) 1px,transparent 1px),linear-gradient(to bottom,rgba(66,108,218,.09) 1px,transparent 1px);background-size:32px 32px;-webkit-mask-image:linear-gradient(to left,#000,transparent 60%);mask-image:linear-gradient(to left,#000,transparent 60%)}'
        . '.openmostCommunicationBanner__brand{display:block;margin:0 0 12px;line-height:0;color:var(--theme-color-text-highContrast)}'
        . '.openmostCommunicationBanner__logo{display:block;width:121px;height:21px}'
        . '.openmostCommunicationBanner__title,#content .openmostCommunicationBanner p.openmostCommunicationBanner__title{margin:0 0 4px!important;padding:0!important;font-size:18px!important;font-weight:700!important;line-height:1.35!important;letter-spacing:normal!important;text-transform:none!important;color:var(--theme-color-text-highContrast)}'
        . '.openmostCommunicationBanner__body,#content .openmostCommunicationBanner p.openmostCommunicationBanner__body{margin:0!important;padding:0!important;max-width:780px!important;font-size:14px!important;line-height:1.55!important;color:var(--theme-color-text-light)}'
        . '.openmostCommunicationBanner a.openmostCommunicationBanner__cta,.openmostCommunicationBanner a.openmostCommunicationBanner__cta:visited{display:inline-flex;align-items:center;gap:8px;margin:16px 0 0!important;padding:10px 16px!important;border:0;border-radius:6px!important;background:#426cda;box-shadow:none;color:#fff;font-size:14px!important;font-weight:600!important;line-height:20px!important;text-decoration:none!important;text-transform:none!important;white-space:nowrap;transition:background-color .15s ease}'
        . '.openmostCommunicationBanner a.openmostCommunicationBanner__cta:hover,.openmostCommunicationBanner a.openmostCommunicationBanner__cta:focus{background:#5a80e2;color:#fff;text-decoration:none}'
        . '.openmostCommunicationBanner__cta .openmostCommunicationBanner__icon{transition:transform .15s ease}'
        . '.openmostCommunicationBanner__cta:hover .openmostCommunicationBanner__icon{transform:translateX(2px)}'
        . '.openmostCommunicationBanner__icon{display:block;flex:none;width:18px;height:18px}'
        . '.openmostCommunicationBanner button.openmostCommunicationBanner__dismiss{position:absolute;top:12px;right:12px;display:flex;align-items:center;justify-content:center;width:32px;height:32px;min-width:0;margin:0;padding:0;border:0;border-radius:6px;background:transparent;box-shadow:none;color:var(--theme-color-text-light);line-height:0;cursor:pointer;-webkit-appearance:none;appearance:none;transition:background-color .15s ease,color .15s ease}'
        . '.openmostCommunicationBanner button.openmostCommunicationBanner__dismiss:hover{background:var(--theme-color-background-lowContrast);color:var(--theme-color-text-highContrast)}'
        . '.openmostCommunicationBanner a.openmostCommunicationBanner__cta:focus-visible,.openmostCommunicationBanner button.openmostCommunicationBanner__dismiss:focus-visible{outline:2px solid #426cda;outline-offset:2px}'
        . '@media (max-width:749px){.openmostCommunicationBanner{padding:20px 56px 20px 20px}}'
        . '@media (prefers-reduced-motion:reduce){.openmostCommunicationBanner *{transition:none}}';

    /**
     * Template.beforeContent listener of the Openmost plugins.
     */
    public static function beforeContent(string &$out, string $layout, string $module, string $action, string $pluginName): void
    {
        if ($layout === 'admin' && $module === 'CoreAdminHome' && in_array($action, ['', 'index', 'home'], true)) {
            $out .= self::render(self::PLACEMENT_ADMIN_HOME, $pluginName);
        }
    }

    /**
     * Template.afterEventsReport listener of the Openmost plugins.
     */
    public static function afterEventsReport(string &$out, string $pluginName): void
    {
        // Dashboard widgets stay free of it, like the tips Matomo shows there
        if (Common::getRequestVar('widget', 0, 'int')) {
            return;
        }

        $out .= self::render(self::PLACEMENT_EVENTS_REPORT_FOOTER, $pluginName);
    }

    /**
     * Widget.filterWidgets listener of the Openmost plugins: the first plugin adds the page widgets of the
     * placements that have an active campaign, pointing to its own Widgets\OpenmostCommunication class.
     */
    public static function filterWidgets(WidgetsList $list, string $pluginName): void
    {
        // The render classes of the plugins carry the same action but no category, only page widgets count
        foreach ($list->getWidgetConfigs() as $config) {
            if ($config->getAction() === self::WIDGET_ACTION && $config->getCategoryId()) {
                return;
            }
        }

        foreach (self::PAGE_PLACEMENTS as $placement => $page) {
            if (!self::findCampaign($placement)) {
                continue;
            }

            foreach ($page['subcategories'] as $subcategoryId) {
                $config = new WidgetConfig();
                $config->setName('OpenmostCommunication_WidgetName');
                $config->setCategoryId($page['category']);
                $config->setSubcategoryId($subcategoryId);
                $config->setModule($pluginName);
                $config->setAction(self::WIDGET_ACTION);
                $config->setParameters(['placement' => $placement, 'subcategory' => $subcategoryId]);
                if ($page['middleware']) {
                    $config->setMiddlewareParameters($page['middleware']);
                }
                $config->setIsWide();
                $config->setOrder($page['order']);
                $config->setIsNotWidgetizable();
                $list->addWidgetConfig($config);
            }
        }
    }

    /**
     * Configuration of the Widgets\OpenmostCommunication class: it only renders the page widgets added by
     * filterWidgets(), so it has no category of its own and never shows up in a page or widget list.
     */
    public static function configureRenderWidget(WidgetConfig $config): void
    {
        $config->setIsNotWidgetizable();
    }

    public static function renderWidget(string $pluginName): string
    {
        $placement = Common::getRequestVar('placement', '', 'string');
        if (!isset(self::PAGE_PLACEMENTS[$placement])) {
            return '';
        }

        if ($placement === self::PLACEMENT_EVENTS_TRACKING && !self::isEventsTrackingMissing()) {
            return '';
        }

        return self::render($placement, $pluginName);
    }

    /**
     * True while Matomo shows its own events tips below the Events report and the website tracks no event
     * in the displayed period, or only the default media events: a tracking plan would add the rest.
     */
    private static function isEventsTrackingMissing(): bool
    {
        if (!Plugin\Manager::getInstance()->isPluginActivated('ProfessionalServices')) {
            return false;
        }

        $idSite = Common::getRequestVar('idSite', 0, 'int');
        if ($idSite <= 0) {
            return false;
        }

        try {
            $categories = Request::processRequest('Events.getCategory', [
                'idSite' => $idSite,
                'period' => Common::getRequestVar('period', 'day', 'string'),
                'date' => Common::getRequestVar('date', 'yesterday', 'string'),
                'segment' => '',
                'flat' => 0,
                'filter_limit' => -1,
                'format' => 'original',
            ], []);
        } catch (\Exception $e) {
            return false;
        }

        if ($categories instanceof DataTable\Map) {
            $categories = $categories->mergeChildren();
        }
        $labels = $categories instanceof DataTable ? $categories->getColumn('label') : [];

        return array_diff($labels, self::DEFAULT_EVENT_CATEGORIES) === [];
    }

    /**
     * HTML of the campaign active on a placement, empty when there is none, when the user may not see it,
     * or when another Openmost plugin already rendered this placement during the request.
     */
    public static function render(string $placement, string $pluginName): string
    {
        if (!empty($GLOBALS[self::REQUEST_CLAIMS][$placement])) {
            return '';
        }

        // The admin home banner is for users who administer a website, page widgets follow the page access
        if ($placement === self::PLACEMENT_ADMIN_HOME && !Piwik::isUserHasSomeAdminAccess()) {
            return '';
        }
        // The Marketplace can be browsed by any logged in user, only installing a plugin needs a super user
        if ($placement === self::PLACEMENT_EVENTS_REPORT_FOOTER && Piwik::isUserIsAnonymous()) {
            return '';
        }

        $campaign = self::findCampaign($placement);
        if ($campaign === null) {
            return '';
        }

        $pluginManager = Plugin\Manager::getInstance();
        foreach ($campaign['hiddenWhenActivated'] ?? [] as $activatedPlugin) {
            if ($pluginManager->isPluginActivated($activatedPlugin)) {
                return '';
            }
        }
        $GLOBALS[self::REQUEST_CLAIMS][$placement] = true;

        if (($campaign['format'] ?? 'banner') === 'link') {
            return self::renderLink($campaign);
        }

        return self::renderBanner($campaign['id'], $campaign, $pluginName);
    }

    /**
     * A plain link in the Matomo link style, to a plugin page of the Marketplace of this instance.
     */
    private static function renderLink(array $campaign): string
    {
        // Super users can install from the Marketplace of the instance, other users get the public plugin page
        if (Piwik::hasUserSuperUserAccess()) {
            $url = 'index.php?' . http_build_query([
                'module' => 'Marketplace',
                'action' => 'overview',
                'idSite' => Common::getRequestVar('idSite', 1, 'int'),
                'period' => Common::getRequestVar('period', 'day', 'string'),
                'date' => Common::getRequestVar('date', 'yesterday', 'string'),
            ]) . '#?' . http_build_query(['showPlugin' => $campaign['marketplacePlugin']]);
            $target = '';
        } else {
            $url = 'https://plugins.matomo.org/' . rawurlencode($campaign['marketplacePlugin']);
            $target = ' target="_blank" rel="noopener"';
        }

        return '<p class="openmostCommunicationLink" style="margin:1em 0 0;text-align:center">'
            . '<a href="' . self::escape($url) . '"' . $target . '>' . self::escape(Piwik::translate($campaign['text'])) . '</a>'
            . '</p>';
    }

    private static function findCampaign(string $placement): ?array
    {
        foreach (self::CAMPAIGNS as $id => $campaign) {
            if ($campaign['placement'] === $placement) {
                return ['id' => $id] + $campaign;
            }
        }

        return null;
    }

    private static function renderBanner(string $id, array $campaign, string $pluginName): string
    {
        $url = self::campaignUrl($id, $pluginName);
        $domId = 'openmostCommunication_' . $id;
        $title = self::escape(Piwik::translate($campaign['title']));
        $dismiss = self::escape(Piwik::translate('OpenmostCommunication_Dismiss'));

        return '<style>' . self::BANNER_STYLE . '</style>'
            . '<section class="openmostCommunicationBanner" id="' . $domId . '" aria-label="' . $title . '">'
            . '<div class="openmostCommunicationBanner__glow" aria-hidden="true"></div>'
            . '<div class="openmostCommunicationBanner__grid" aria-hidden="true"></div>'
            . '<div class="openmostCommunicationBanner__brand">' . self::LOGO . '</div>'
            . '<p class="openmostCommunicationBanner__title">' . $title . '</p>'
            . '<p class="openmostCommunicationBanner__body">' . self::escape(Piwik::translate($campaign['body'])) . '</p>'
            . '<a class="openmostCommunicationBanner__cta" href="' . self::escape($url) . '" target="_blank" rel="noopener">'
            . '<span>' . self::escape(Piwik::translate($campaign['cta'])) . '</span>' . self::ARROW
            . '</a>'
            . '<button type="button" class="openmostCommunicationBanner__dismiss" aria-label="' . $dismiss . '" title="' . $dismiss . '">' . self::CLOSE . '</button>'
            . '</section>'
            // Hides the banner before paint once dismissed, the choice is kept per browser and per campaign
            . '<script>(function(){var k="openmostCommunication:' . $id . ':dismissed",b=document.getElementById("' . $domId . '");'
            . 'if(!b)return;try{if(localStorage.getItem(k)){b.remove();return}}catch(e){}'
            . 'b.querySelector(".openmostCommunicationBanner__dismiss").addEventListener("click",function(){try{localStorage.setItem(k,"1")}catch(e){}b.remove()})})();</script>';
    }

    /**
     * openmost.com URL of a banner campaign, on the page in the language of the current user when the site
     * serves it, on the English page otherwise or when anything fails: a banner never links to a 404.
     *
     * @param callable|null $userLanguage Returns the Matomo language code of the user, the current user by default
     */
    public static function campaignUrl(string $id, string $pluginName, ?callable $userLanguage = null): string
    {
        $paths = self::CAMPAIGNS[$id]['paths'] ?? [];
        $path = $paths['en'] ?? '/';

        try {
            $language = $userLanguage ? $userLanguage() : LanguagesManager::getLanguageCodeForCurrentUser();
            $locale = self::siteLocale((string) $language);
            if (isset($paths[$locale]) && in_array($locale, self::SITE_LOCALES, true)) {
                $path = $paths[$locale];
            }
        } catch (\Throwable $e) {
            // Keeps the English page, the one page every campaign is sure to have
        }

        // Identical in every language, so a campaign reports as one whatever the locale of the page
        return self::SITE_URL . $path . '?' . http_build_query([
            'utm_source' => 'matomo_onpremise',
            'utm_medium' => 'banner',
            'utm_campaign' => $id,
            'utm_content' => strtolower($pluginName),
        ]);
    }

    /**
     * openmost.com locale of a Matomo language code: zh-cn is zh-hans, pt-br is pt, en-gb is en, and a
     * language the site does not serve is en.
     */
    public static function siteLocale(string $languageCode): string
    {
        $code = strtolower(str_replace('_', '-', trim($languageCode)));
        if (isset(self::LANGUAGE_TO_SITE_LOCALE[$code])) {
            return self::LANGUAGE_TO_SITE_LOCALE[$code];
        }
        if (in_array($code, self::SITE_LOCALES, true)) {
            return $code;
        }
        $base = explode('-', $code)[0];

        return in_array($base, self::SITE_LOCALES, true) ? $base : 'en';
    }

    private static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
