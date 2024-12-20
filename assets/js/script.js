import { linkMenu } from './user/linkMenu.js';
import { repertoire } from './user/repertoire.js';
import { contact } from './user/contact.js';

document.addEventListener('DOMContentLoaded', () => {
    if (window.location.pathname.startsWith('/user')) {
        linkMenu(); 
        repertoire();
        contact(); 
    }
});