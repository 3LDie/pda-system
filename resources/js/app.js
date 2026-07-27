

import Alpine from 'alpinejs';

window.Alpine = Alpine;

const THEME_STORAGE_KEY = 'pda-theme';

function getPreferredTheme() {
    if (typeof window === 'undefined') {
        return 'light';
    }

    // Per-layout override: a page can opt out of theming by setting
    // data-force-theme="light|dark" on <html> (e.g. login/register).
    const forcedTheme = document.documentElement.dataset.forceTheme;
    if (forcedTheme === 'dark' || forcedTheme === 'light') {
        return forcedTheme;
    }

    const savedTheme = window.localStorage.getItem(THEME_STORAGE_KEY);

    if (savedTheme === 'dark' || savedTheme === 'light') {
        return savedTheme;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

function applyTheme(theme) {
    const root = document.documentElement;
    const isDark = theme === 'dark';

    root.classList.toggle('dark', isDark);
    root.setAttribute('data-theme', theme);
    root.style.colorScheme = theme;
    window.localStorage.setItem(THEME_STORAGE_KEY, theme);
}

function toggleTheme() {
    const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
    applyTheme(nextTheme);
    updateThemeButtons(nextTheme);
}

function updateThemeButtons(theme) {
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        const lightIcon = button.querySelector('[data-theme-icon-light]');
        const darkIcon = button.querySelector('[data-theme-icon-dark]');
        const label = button.querySelector('[data-theme-label]');

        if (lightIcon) {
            lightIcon.classList.toggle('hidden', theme === 'dark');
        }

        if (darkIcon) {
            darkIcon.classList.toggle('hidden', theme === 'light');
        }

        if (label) {
            label.textContent = theme === 'dark' ? 'Light mode' : 'Dark mode';
        }

        button.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
        button.setAttribute('title', theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode');
    });
}

function initializeTheme() {
    applyTheme(getPreferredTheme());
    updateThemeButtons(document.documentElement.classList.contains('dark') ? 'dark' : 'light');
}

window.PDATheme = {
    toggle: toggleTheme,
    apply: applyTheme,
};

document.addEventListener('DOMContentLoaded', () => {
    initializeTheme();

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            toggleTheme();
        });
    });
});

window.addEventListener('storage', (event) => {
    if (event.key === THEME_STORAGE_KEY) {
        // Never override a per-layout forced theme.
        if (document.documentElement.dataset.forceTheme) {
            return;
        }
        applyTheme(event.newValue === 'dark' ? 'dark' : 'light');
        updateThemeButtons(document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    }
});

initializeTheme();
Alpine.start();
