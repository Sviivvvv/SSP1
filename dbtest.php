<script>

    //Js for mobile nav button 

    const burgerButton = document.getElementById('burgerButton');
    const mobileNav = document.getElementById('mobileNav');
    const NavLinks = mobileNav.querySelectorAll('a');

    burgerButton.addEventListener('click', () => {
        if (mobileNav.classList.contains('hidden')) {
            mobileNav.classList.remove('hidden');
            setTimeout(() => {
                NavLinks.forEach(link => {
                    link.classList.remove('opacity-0', 'translate-y-3');
                });
            }, 50);
        } else {
            NavLinks.forEach(link => {
                link.classList.add('opacity-0', 'translate-y-3');
            });
            setTimeout(() => {
                mobileNav.classList.add('hidden');
            }, 300);
        }
    });

    //click for description -mobile
    document.addEventListener("DOMContentLoaded", () => {
        const isMobile = () => window.innerWidth < 640;

        function hideAllDescriptions() {
            document.querySelectorAll("[data-description-toggle]").forEach(el => {
                el.classList.remove("opacity-80");
            });
        }

        function handleCardClick(e) {
            const clickedOverlay = e.target.closest("[data-description-toggle]");
            if (!clickedOverlay) {
                hideAllDescriptions();
                return;
            }
            e.stopPropagation();
            document.querySelectorAll("[data-description-toggle]").forEach(el => {
                if (el !== clickedOverlay) el.classList.remove("opacity-80");
            });
            clickedOverlay.classList.toggle("opacity-80");
        }

        function mobileView() {
            document.removeEventListener("click", handleCardClick);
            if (isMobile()) {
                document.addEventListener("click", handleCardClick);
            } else {
                hideAllDescriptions();
            }
        }

        mobileView();

        window.addEventListener("resize", () => {
            mobileView();
        });
    });
</script>


<script>
    const burgerButton = document.getElementById('burgerButton');
    const mobileNav = document.getElementById('mobileNav');
    const NavLinks = mobileNav.querySelectorAll('a');

    burgerButton.addEventListener('click', () => {
        if (mobileNav.classList.contains('hidden')) {
            mobileNav.classList.remove('hidden');
            setTimeout(() => {
                NavLinks.forEach(link => {
                    link.classList.remove('opacity-0', 'translate-y-3');
                });
            }, 50);
        } else {
            NavLinks.forEach(link => {
                link.classList.add('opacity-0', 'translate-y-3');
            });
            setTimeout(() => {
                mobileNav.classList.add('hidden');
            }, 300);
        }
    });
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
    //click for description -mobile
    document.addEventListener("DOMContentLoaded", () => {
        const isMobile = () => window.innerWidth < 640;

        function hideAllDescriptions() {
            document.querySelectorAll("[data-description-toggle]").forEach(el => {
                el.classList.remove("opacity-80");
            });
        }

        function handleCardClick(e) {
            const clickedOverlay = e.target.closest("[data-description-toggle]");
            if (!clickedOverlay) {
                hideAllDescriptions();
                return;
            }
            e.stopPropagation();
            document.querySelectorAll("[data-description-toggle]").forEach(el => {
                if (el !== clickedOverlay) el.classList.remove("opacity-80");
            });
            clickedOverlay.classList.toggle("opacity-80");
        }

        function mobileView() {
            document.removeEventListener("click", handleCardClick);
            if (isMobile()) {
                document.addEventListener("click", handleCardClick);
            } else {
                hideAllDescriptions();
            }
        }

        mobileView();

        window.addEventListener("resize", () => {
            mobileView();
        });
    });
</script>



<script>
    const burgerButton = document.getElementById('burgerButton');
    const mobileNav = document.getElementById('mobileNav');
    const NavLinks = mobileNav.querySelectorAll('a');

    burgerButton.addEventListener('click', () => {
        if (mobileNav.classList.contains('hidden')) {
            mobileNav.classList.remove('hidden');
            setTimeout(() => {
                NavLinks.forEach(link => {
                    link.classList.remove('opacity-0', 'translate-y-3');
                });
            }, 50);
        } else {
            NavLinks.forEach(link => {
                link.classList.add('opacity-0', 'translate-y-3');
            });
            setTimeout(() => {
                mobileNav.classList.add('hidden');
            }, 300);
        }
    });



    document.addEventListener('DOMContentLoaded', function () {

        // Remove Item
        document.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const productID = this.closest('tr').dataset.productId; // Get the productID from the tr's data attribute

                const formData = new FormData();
                formData.append('action', 'removeCart');
                formData.append('productID', productID); // Add the productID to the request

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


    document.addEventListener('DOMContentLoaded', function () {
        // Handle increase and decrease buttons
        document.querySelectorAll('.quantity-increase, .quantity-decrease').forEach(button => {
            button.addEventListener('click', function () {
                const row = button.closest('tr'); // Get the row
                const productID = row.dataset.productId; // Get the product ID
                let quantity = parseInt(row.querySelector('.quantity-display').textContent); // Get the current quantity

                // Update quantity based on button clicked
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
    //click for description -mobile
    document.addEventListener("DOMContentLoaded", () => {
        const isMobile = () => window.innerWidth < 640;

        function hideAllDescriptions() {
            document.querySelectorAll("[data-description-toggle]").forEach(el => {
                el.classList.remove("opacity-80");
            });
        }

        function handleCardClick(e) {
            const clickedOverlay = e.target.closest("[data-description-toggle]");
            if (!clickedOverlay) {
                hideAllDescriptions();
                return;
            }
            e.stopPropagation();
            document.querySelectorAll("[data-description-toggle]").forEach(el => {
                if (el !== clickedOverlay) el.classList.remove("opacity-80");
            });
            clickedOverlay.classList.toggle("opacity-80");
        }

        function mobileView() {
            document.removeEventListener("click", handleCardClick);
            if (isMobile()) {
                document.addEventListener("click", handleCardClick);
            } else {
                hideAllDescriptions();
            }
        }

        mobileView();

        window.addEventListener("resize", () => {
            mobileView();
        });
    });
</script>


<script>
    // Toggle card fields based on payment method
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const cardFields = document.getElementById('cardFields');

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'cod') {
                cardFields.style.display = 'none';
                // Make card fields not required when COD is selected
                cardFields.querySelectorAll('[required]').forEach(field => {
                    field.required = false;
                });
            } else {
                cardFields.style.display = 'block';
                // Make card fields required when card is selected
                cardFields.querySelectorAll('[required]').forEach(field => {
                    field.required = true;
                });
            }
        });
    });

    // Basic form validation before submission
    const form = document.getElementById('checkoutForm');
    form.addEventListener('submit', function (e) {
        // Validate card details if card payment is selected
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

        if (paymentMethod === 'card') {
            const cardNumber = form.card_number.value.trim();
            const cvv = form.cvv.value.trim();

            // Simple card number validation (16 digits)
            if (!/^\d{16}$/.test(cardNumber)) {
                alert('Please enter a valid 16-digit card number');
                e.preventDefault();
                return;
            }

            // Simple CVV validation (3-4 digits)
            if (!/^\d{3,4}$/.test(cvv)) {
                alert('Please enter a valid CVV (3-4 digits)');
                e.preventDefault();
                return;
            }
        }


    });
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

        // Submit via Fetch API
        try {
            const formData = new FormData(this);
            const response = await fetch('/luxuryperfumestore/checkout', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert(`Order #${result.orderID} placed successfully!`);
                window.location.href = result.redirect; // Redirect to home
            } else {
                alert(`Error: ${result.message}`);
            }
        } catch (error) {
            alert("Checkout failed. Please try again.");
            console.error(error);
        }
    });
</script>

<script>
    const burgerButton = document.getElementById('burgerButton');
    const mobileNav = document.getElementById('mobileNav');
    const NavLinks = mobileNav.querySelectorAll('a');

    burgerButton.addEventListener('click', () => {
        if (mobileNav.classList.contains('hidden')) {
            mobileNav.classList.remove('hidden');
            setTimeout(() => {
                NavLinks.forEach(link => {
                    link.classList.remove('opacity-0', 'translate-y-3');
                });
            }, 50);
        } else {
            NavLinks.forEach(link => {
                link.classList.add('opacity-0', 'translate-y-3');
            });
            setTimeout(() => {
                mobileNav.classList.add('hidden');
            }, 300);
        }
    });

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


</script>