window.addEventListener("load", () => {
    const searchInput = document.querySelector(
        '.input-group input[type="text"]',
    );
    const searchButton = document.querySelector(".input-group button");

    if (!searchInput || !searchButton) return;

    searchButton.addEventListener("click", () => {
        const query = searchInput.value.trim();

        if (query) {
            window.location.href += `search?query=${encodeURIComponent(query)}`;
        }
    });

    searchInput.addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            searchButton.click();
        }
    });
});
