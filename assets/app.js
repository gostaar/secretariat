import { initializeNavigation, initializeFragments } from './js/mainFunctions.js';

function main() {
    document.addEventListener('DOMContentLoaded', () => {
        initializeFragments();
        initializeNavigation();
    });
}

main();
