window.addEventListener('DOMContentLoaded', () => {
    const status = document.getElementById('session.status');
    const isAuthenticated = (status.textContent.trim() === 'connecté');
    if (!isAuthenticated) {clearData() };

    const proButton = document.getElementById('btnPro');
    const partButton = document.getElementById('btnPart');

    if (proButton && partButton) {
        proButton.addEventListener('click', () => toggleSection('pro'));
        partButton.addEventListener('click', () => toggleSection('part'));
    } else {
        console.warn('Les boutons "btnPro" ou "btnPart" sont introuvables.');
    }
});

window.addEventListener('hashchange', function () {
    const hash = location.hash.replace('#', '');
    showSection(hash);
});

function updateURLWithoutHash() {
    history.replaceState(null, '', window.location.pathname);
}

function showSection(targetId) {
    
    const allSections = document.querySelectorAll('[id^="link-"]');
    
    allSections.forEach(section => {
        if (section.id === targetId) {
            section.classList.remove('d-none');
        } else {
            section.classList.add('d-none');
        }
    });
    history.replaceState(null, '', window.location.pathname);
}

function clearData(){
    sessionStorage.clear();
    localStorage.clear();
    const cookies = document.cookie.split(';');
    cookies.forEach(cookie => {
        const cookieName = cookie.split('=')[0].trim();
        document.cookie = `${cookieName}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/, secure, sameSite=None`;
    });
}

function toggleSection(targetId) {
    const sections = ['pro', 'part']; 
    const btnPartPro = document.getElementById('btnPartPro');

    sections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (sectionId === targetId) {
            section.classList.remove('d-none'); 
            section.classList.add('fade-in'); 
            location.replace(`/#link-start-${targetId}`);
        } else {
            section.classList.add('d-none'); 
            section.classList.remove('fade-in');
        }
    });

    if (btnPartPro) {
        btnPartPro.classList.add('d-none');
    }
}