// Toggle card fields based on payment method
const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
const cardFields = document.getElementById('cardFields');

paymentRadios.forEach(radio => {
    radio.addEventListener('change', function () {
        if (this.value === 'cod') {
            cardFields.style.display = 'none';
            cardFields.querySelectorAll('[required]').forEach(field => {
                field.required = false;
            });
        } else {
            cardFields.style.display = 'block';
            cardFields.querySelectorAll('[required]').forEach(field => {
                field.required = true;
            });
        }
    });
});

// Handle form submission and validation
document.getElementById('checkoutForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

    if (paymentMethod === 'card') {
        const cardNumber = this.card_number.value.trim();
        const cvv = this.cvv.value.trim();

        if (!/^\d{16}$/.test(cardNumber)) {
            alert('Please enter a valid 16-digit card number');
            return;
        }

        if (!/^\d{3,4}$/.test(cvv)) {
            alert('Please enter a valid CVV (3-4 digits)');
            return;
        }
    }

    // Submit via Fetch 
    try {
        const formData = new FormData(this);
        const response = await fetch('/luxuryperfumestore/checkout', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert(`Order #${result.orderID} placed successfully!`);
            window.location.href = result.redirect;
        } else {
            alert(`Error: ${result.message}`);
        }
    } catch (error) {
        alert("Checkout failed. Please try again.");
        console.error(error);
    }
});
