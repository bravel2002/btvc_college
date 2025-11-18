function loadPage(page) {
    fetch(page)
    .then(response => response.text())
    .then(data => {
        document.querySelector('.main-content').innerHTML = data;
    })
    .catch(error => console.error('Error loading page:', error));
}

// Load Dashboard by default
window.addEventListener('DOMContentLoaded', () => {
    loadPage('idashboard.php');
});
