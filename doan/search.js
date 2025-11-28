document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById('searchInput');
  if (!searchInput) return;

  searchInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      const query = this.value.trim();
      if (query) {
        console.log("Redirecting to:", 'movies.php?q=' + encodeURIComponent(query));
        window.location.href = 'movies.php?q=' + encodeURIComponent(query);
      } else {
        console.log("Redirecting to: movies.php");
        window.location.href = 'movies.php';
      }
    }
  });
});