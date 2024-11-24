// Menu mobile toggle
const menuToggle = document.getElementById('menu-toggle');
const navLinks = document.getElementById('nav-links');

// Lorsque le bouton menu est cliqué, on ajoute ou retire la classe 'active' aux liens de navigation
menuToggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});
