import { changeFragmentSite } from './site/main.js';
import { changeFragmentUser } from './user/main.js';

export function initializeNavigation() {
    const loadingIndicator = document.getElementById('loadingIndicator');

    window.appState = {
        endLoadingState: false,
    };

    navigation.addEventListener('navigate', () => {
        if (!window.appState.endLoadingState) {
            loadingIndicator.style.display = 'flex';
        }
        window.appState.endLoadingState = false;
    });

    window.addEventListener('popstate', () => {
        window.appState.endLoadingState = true;
    });
}

export function initializeFragments() {
    const path = window.location.pathname;

    const fragmentHandlers = {
        '/': changeFragmentSite(),
        '/user': changeFragmentUser(),
    };

    for (const [key, handler] of Object.entries(fragmentHandlers)) {
        if (path.startsWith(key) && typeof handler === 'function') {
            handler();
            break;
        }
    }
}