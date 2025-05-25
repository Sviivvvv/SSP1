// JS for mobile nav button
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


// Click for description (mobile only)
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