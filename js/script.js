document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("toggleSidebar");
  const sidebar = document.getElementById("sidebar");
  const mainContent = document.getElementById("mainContent");

  // Sidebar Toggle
  if (toggleBtn && sidebar && mainContent) {
    toggleBtn.addEventListener("click", function () {
      sidebar.classList.toggle("hidden");
      mainContent.classList.toggle("full");
    });
  }

  // Tab Navigation (optional if you have tablinks)
  const tablinks = document.getElementsByClassName("tablinks");
  const tabcontent = document.getElementsByClassName("tabcontent");

  if (tablinks.length > 0 && tabcontent.length > 0) {
    for (let i = 0; i < tablinks.length; i++) {
      tablinks[i].addEventListener("click", function (evt) {
        openMenuTab(evt, this.getAttribute("data-tab"));
      });
    }

    function openMenuTab(evt, tabName) {
      for (let i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }

      for (let i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
      }

      const targetTab = document.getElementById(tabName);
      if (targetTab) {
        targetTab.style.display = "block";
      }

      evt.currentTarget.classList.add("active");
    }
  }
});
