window.addEventListener('DOMContentLoaded', () => {
    const status = document.getElementById('session.status');
    const isAuthenticated = (status.textContent.trim() === 'connecté');
    if (!isAuthenticated) {clearData() };
});

function clearData(){
    sessionStorage.clear();
    localStorage.clear();
    const cookies = document.cookie.split(';');
    cookies.forEach(cookie => {
        const cookieName = cookie.split('=')[0].trim();
        document.cookie = `${cookieName}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/, secure, sameSite=None`;
    });
}