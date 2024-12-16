import { linkMenu } from './user/linkMenu.js';
import { page } from './page.js';
import { repertoire } from './user/repertoire.js';
import { contact } from './user/contact.js';

document.addEventListener('DOMContentLoaded', () => {
    linkMenu();
    page();
    repertoire();
    contact();
});