export function contact(){
    document.getElementById('showContactFormBtn').addEventListener('click', function() {
        var contactForm = document.getElementById('contactForm');
        // Toggle visibility of the contact form
        if (contactForm.style.display === 'none') {
            contactForm.style.display = 'block';
        } else {
            contactForm.style.display = 'none';
        }
    });
}