import { initializeNavigation, initializeFragments } from './js/mainFunctions.js';

// Initialisation des fragments en fonction de l'URL


function main() {
    document.addEventListener('DOMContentLoaded', () => {
        initializeFragments();
        initializeNavigation();
    });
}

main();
