window.addEventListener("load", () => {
    const form = document.querySelector('form[name="review"]');
    if (!form) return;

    const ratingInput = form.querySelector('input[name="review[rating]"]');
    if (!ratingInput) return;

    const emptyStarClass = "fa-regular fa-star";
    const fullStarClass = "fa-solid fa-star";
    const style = "color: rgb(255, 212, 59);";

    const stars = createComponent();

    stars.forEach((star, index) => {
        star.addEventListener("click", () => {
            if (index === ratingInput.value - 1) {
                stars.forEach((s) => {
                    s.className = emptyStarClass;
                    s.style.cssText = style;
                });
                ratingInput.value = 0;
                return;
            }
            stars.forEach((s, i) => {
                s.className = i <= index ? fullStarClass : emptyStarClass;
                s.style.cssText = style;
            });

            const currentRating = Number(ratingInput.value);
            if (index === currentRating - 1) {
                ratingInput.value = 0;
                return;
            }
            ratingInput.value = index + 1;
        });
    });

    ratingInput.style.display = "none";

    function createComponent() {
        const starContainer = document.createElement("div");
        starContainer.className = "d-flex gap-2";

        for (let i = 0; i < 5; i++) {
            const star = document.createElement("i");
            star.className = emptyStarClass;
            star.style.cssText = style;
            starContainer.appendChild(star);
        }

        ratingInput.parentNode.insertBefore(starContainer, ratingInput);
        return Array.from(starContainer.children);
    }
});
