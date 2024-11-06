document.querySelector('.sign-in-button').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const email = document.querySelector('input[type="email"]').value;
    const password = document.querySelector('input[type="password"]').value;

    // Basic client-side validation
    if (email && password) {
        // Send the data to the server for authentication
        fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // If login is successful, redirect to the homepage or dashboard
                window.location.href = '/dashboard.html';
            } else {
                // If login fails, show an error message
                alert('Invalid email or password');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    } else {
        alert('Please fill out both fields');
    }
});
