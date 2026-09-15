/*!
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

import {
  describe, expect, it, vi,
} from 'vitest';
import { lockThemeMode } from './themeModeLock';

const flush = () => new Promise((resolve) => { setTimeout(resolve, 0); });

describe('lockThemeMode', () => {
  it('keeps the dark mode whatever mode Matomo is asked to switch to', async () => {
    const root = document.createElement('html');
    root.setAttribute('data-theme-mode', 'light');

    const setThemeMode = vi.fn((mode: string) => root.setAttribute('data-theme-mode', mode));
    const matomo: { setThemeMode?: (mode: string) => void, getThemeMode?: () => string } = {
      setThemeMode,
    };

    lockThemeMode(matomo, root);
    expect(root.getAttribute('data-theme-mode')).toBe('dark');

    // personal settings saved with the light mode
    matomo.setThemeMode!('light');
    expect(setThemeMode).toHaveBeenCalledWith('dark');
    expect(matomo.getThemeMode!()).toBe('dark');

    // any other change of the attribute
    root.setAttribute('data-theme-mode', 'auto');
    await flush();
    expect(root.getAttribute('data-theme-mode')).toBe('dark');
  });
});
