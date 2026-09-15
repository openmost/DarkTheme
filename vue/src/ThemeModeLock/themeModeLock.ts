/*!
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

// Dark Theme applies to every user: the page is always rendered in dark mode (DarkTheme.php), and
// this keeps it dark when Matomo switches the theme mode on the fly, e.g. after saving the personal
// settings, which calls Matomo.setThemeMode() with the mode the user picked.

const DARK_MODE = 'dark';

interface ThemeModeApi {
  setThemeMode?: (mode: string) => void;
  getThemeMode?: () => string;
}

export function lockThemeMode(
  matomo: ThemeModeApi,
  root: HTMLElement = document.documentElement,
): void {
  const { setThemeMode } = matomo;
  if (typeof setThemeMode === 'function') {
    matomo.setThemeMode = () => setThemeMode.call(matomo, DARK_MODE);
  }

  matomo.getThemeMode = () => DARK_MODE;

  if (root.getAttribute('data-theme-mode') !== DARK_MODE) {
    root.setAttribute('data-theme-mode', DARK_MODE);
  }

  // anything else changing the attribute, the mode is set back right away
  new MutationObserver(() => {
    if (root.getAttribute('data-theme-mode') !== DARK_MODE) {
      root.setAttribute('data-theme-mode', DARK_MODE);
    }
  }).observe(root, { attributes: true, attributeFilter: ['data-theme-mode'] });
}

const { piwik } = window as unknown as { piwik?: ThemeModeApi };
if (piwik) {
  lockThemeMode(piwik);
}
