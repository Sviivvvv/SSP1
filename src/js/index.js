// Click for description - mobile
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