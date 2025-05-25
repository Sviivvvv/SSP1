document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.addToCart').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('route.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                    } else {
                        alert(data.message || 'Failed to add to cart.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
});