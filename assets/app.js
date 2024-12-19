window.addEventListener('DOMContentLoaded', () => {
    // handleGoogleEventButton();
    changeFragmentUser();
    changeFragmentSite();
});

function changeFragmentSite() {

    document.body.addEventListener('click', function (event) {
        if (event.target && event.target.classList.contains('change-fragment-site')) {
            const fragment = event.target.dataset.fragment;
            const subFragment = event.target.dataset.subfragment;

            fetch(`/?fragment=${fragment}&subFragment=${subFragment}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.fragmentContent) {
                    const fragmentContent = document.getElementById('fragmentContent');
                    if (fragmentContent) {
                        fragmentContent.innerHTML = data.fragmentContent;
                    }
                }

                if (data.subFragmentContent) {
                    const subFragmentContent = document.getElementById('subFragmentContent');
                    if (subFragmentContent) {
                        subFragmentContent.innerHTML = data.subFragmentContent;
                    }
                }
            })
            .catch(error => console.error('Erreur:', error));
        }
    });
}

function changeFragmentUser(){
    const buttons = document.querySelectorAll('.change-fragment');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const fragment = button.getAttribute('data-fragment');

            fetch(`/user?fragment=${fragment}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', 
                },
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('fragmentContent').innerHTML = data;

                buttons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            })
            .catch(error => console.error('Error:', error));
        });
    });
}


// function handleGoogleEventButton() {
//     const btnNewGoogleEvent = document.getElementById('btnNewGoogleEvent');
//     if (btnNewGoogleEvent) {
//         console.log("btnNewGoogleEvent");
//         btnNewGoogleEvent.addEventListener('click', () => toggleSectionAgenda('link-newGoogleEvent'));
//     }
// }


// function clearData() {
//     sessionStorage.clear();
//     localStorage.clear();
//     const cookies = document.cookie.split(';');
//     cookies.forEach(cookie => {
//         const cookieName = cookie.split('=')[0].trim();
//         document.cookie = `${cookieName}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/; secure; sameSite=None`;
//     });
// }

// Détails event
document.addEventListener('DOMContentLoaded', () => {
    const eventList = document.querySelectorAll('#eventList .list-group-item');
    const modalTitle = document.getElementById('eventTitle');
    const modalDate = document.getElementById('eventDate');
    const modalDescription = document.getElementById('eventDescription');
    const modalLocation = document.getElementById('eventLocation');
    const modalEnd = document.getElementById('eventEnd');
    const modalGoogle = document.getElementById('eventGoogle');
    const modalGoogleId = document.getElementById('eventGoogleId');

    eventList.forEach(item => {
        item.addEventListener('click', () => {
            modalTitle.textContent = `Titre : ${item.dataset.title}`;
            modalDate.textContent = `Début : ${item.dataset.start}`;
            modalEnd.textContent = `Fin : ${item.dataset.end}`;
            modalDescription.textContent = `Description : ${item.dataset.description}`;
            modalLocation.textContent = `Lieu : ${item.dataset.location}`;
            modalGoogle.textContent = `Google Calendar ID : ${item.dataset.google}`;
            modalGoogleId.textContent = `Google Event ID : ${item.dataset.googleid}`;
        });
    });
});
