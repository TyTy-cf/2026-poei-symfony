window.addEventListener("load", () => {
    const searchForm = document.querySelector(".input-group");
    const searchInput = document.querySelector(
        '.input-group input[type="text"]',
    );
    const searchButton = document.querySelector(".input-group button");

    if (!searchForm || !searchInput || !searchButton) return;

    searchButton.addEventListener("click", () => {
        const query = searchInput.value.trim();

        if (query) {
            const url = new URL(searchForm.action, window.location.origin);
            url.searchParams.set("query", query);
            window.location.href = url.toString();
        }
    });

    searchInput.addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            searchButton.click();
        }
    });
});
