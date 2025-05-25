document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            try {
                const response = await fetch('/luxuryperfumestore/adminDashboard-Products', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                alert(result.message);

                if (result.success) {
                    setTimeout(() => window.location.reload(), 1000);
                }
            } catch (error) {
                alert('Network error - please try again');
            }
        });
    });
});