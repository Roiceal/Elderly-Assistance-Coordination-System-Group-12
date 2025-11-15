document.addEventListener("DOMContentLoaded", () => {
  const toggleBtn = document.getElementById("toggleBtn"); // hamburger
  const closeBtn = document.getElementById("closeBtn");   // X button
  const sidebar = document.getElementById("sidebar");


  toggleBtn.addEventListener("click", (e) => {
    sidebar.classList.toggle("active");
    e.stopPropagation(); 
  });


  if (closeBtn) {
    closeBtn.addEventListener("click", () => {
      sidebar.classList.remove("active");
    });
  }

  
  document.addEventListener("click", (e) => {
    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
      sidebar.classList.remove("active");
    }
  });

  
  sidebar.addEventListener("click", (e) => {
    e.stopPropagation();
  });
  
});
