function loadPage(url) {
    fetch(url)
        .then(response => response.text())
        .then(data => {
            document.getElementById('main-content').innerHTML = data;
        })
        .catch(err => console.error(err));
}

// Load default page
window.addEventListener('DOMContentLoaded', () => {
    loadPage('add_student.php');
});
function loadPage(url) {
    fetch(url)
        .then(response => response.text())
        .then(data => {
            const content = document.getElementById('main-content');
            content.innerHTML = data;

            // ✅ Attach form logic if Add Student page is loaded
            if (url.includes('add_student.php')) {
                const form = document.getElementById('addStudentForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const formData = new FormData(this);

                        fetch('add_student.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.text())
                        .then(data => {
                            console.log("Server Response:", data);
                            if (data.trim() === 'success') {
                                alert('✅ Student added successfully!');
                                loadPage('view_students.php');
                            } else {
                                alert('❌ Error adding student: ' + data);
                            }
                        })
                        .catch(err => console.error(err));
                    });
                }
            }
        })
        .catch(err => console.error(err));
}

// Load default page
window.addEventListener('DOMContentLoaded', () => {
    loadPage('add_student.php');
});
