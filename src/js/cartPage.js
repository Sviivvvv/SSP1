// Remove Item
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.remove-item-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const productID = this.closest('tr').dataset.productId;

            const formData = new FormData();
            formData.append('action', 'removeCart');
            formData.append('productID', productID);

            fetch('route.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    alert(data.message || 'Item removed.');
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('An error occurred while removing item.');
                });
        });
    });
});

// Handle increase and decrease buttons
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.quantity-increase, .quantity-decrease').forEach(button => {
        button.addEventListener('click', function () {
            const row = button.closest('tr');
            const productID = row.dataset.productId;
            let quantity = parseInt(row.querySelector('.quantity-display').textContent);

            if (button.classList.contains('quantity-increase')) {
                quantity++;
            } else if (button.classList.contains('quantity-decrease') && quantity > 1) {
                quantity--;
            }

            updateQuantity(productID, quantity, row);
        });
    });

    function updateQuantity(productID, quantity, row) {
        const formData = new FormData();
        formData.append('action', 'updateCart');
        formData.append('productID', productID);
        formData.append('quantity', quantity);

        fetch('route.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    row.querySelector('.quantity-display').textContent = quantity;
                    const price = parseFloat(row.querySelector('.price').textContent.replace(/,/g, ''));
                    row.querySelector('.total').textContent = (price * quantity).toFixed(2);
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update quantity.');
                }
            });
    }
});