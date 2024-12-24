import { linkMenu } from './user/linkMenu.js';
import { repertoire } from './user/repertoire.js';
import { contact } from './user/contact.js';
import { facture } from './user/facture.js';

document.addEventListener('DOMContentLoaded', () => {
    if (window.location.pathname.startsWith('/user')) {
        linkMenu(); 
        // repertoire();
        contact();
        // facture(); 
    }
});