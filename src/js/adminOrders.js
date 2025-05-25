document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
    const clearFiltersBtn = document.getElementById('clearFilters');

    // Handle form submission
    filterForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        try {
            const response = await fetch('/luxuryperfumestore/adminDashboard-Orders/filter', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                window.location.reload();
            } else {
                alert(result.message || 'Error applying filters');
            }
        } catch (error) {
            alert('An error occurred while processing your request');
            console.error(error);
        }
    });

    // Handle clear filters
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function () {
            fetch('/luxuryperfumestore/adminDashboard-Orders/clear-filters')
                .then(() => window.location.reload())
                .catch(error => {
                    alert('Error clearing filters');
                    console.error(error);
                });
        });
    }
});