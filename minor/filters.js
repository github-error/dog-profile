function applyFilters() {
    const animalType = document.getElementById("animalType").value;
    const popularity = document.getElementById("popularity").value;
    const timeRange = document.getElementById("timeRange").value;

    const results = document.getElementById("filteredResults");
    results.innerHTML = ""; // Clear previous results

    // Dummy data for filtering
    const dummyData = [
        { type: "dog", popularity: "most-liked", time: "today", name: "Buddy 🐶" },
        { type: "dog", popularity: "most-shared", time: "last-7-days", name: "Charlie 🐶" },
        { type: "cattle", popularity: "most-liked", time: "today", name: "Jersey 🐄" },
        { type: "cattle", popularity: "trending", time: "last-30-days", name: "Brahman 🐂" },
    ];

    // Filtering logic
    const filteredResults = dummyData.filter(item => 
        (animalType === "all" || item.type === animalType) &&
        (popularity === "all" || item.popularity === popularity) &&
        (timeRange === "all" || item.time === timeRange)
    );

    // Display results
    if (filteredResults.length > 0) {
        filteredResults.forEach(item => {
            const li = document.createElement("li");
            li.textContent = `${item.name} - ${item.popularity.replace("-", " ")} (${item.time})`;
            results.appendChild(li);
        });
    } else {
        results.innerHTML = "<li>No results found.</li>";
    }
}