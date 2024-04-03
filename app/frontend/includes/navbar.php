<body>
  <div class="d-flex">
    <div id="sidebar" class="background-sidebar sidebar">
      <div>
        <div class="flex-shrink-0 p-3">
          <ul class="mt-3 list-unstyled">
            <li class="mb-2">
              <a href="index.php" class="nav-link link-body-emphasis">
                <i class="bi bi-house"></i>
                <span class="sidebar-item">Home</span>
              </a>
            </li>
            <li class="mb-2">
              <a href="#submenu1" data-bs-toggle="collapse" class="nav-link link-body-emphasis">
                <i class="bi bi-folder2-open"></i>
                <span class="sidebar-item">Expandable Item</span>
              </a>
              <ul id="submenu1" class="list-unstyled collapse">
                <li><a href="#" class="nav-link link-body-emphasis">Sub Item 1</a></li>
                <li><a href="#" class="nav-link link-body-emphasis">Sub Item 2</a></li>
              </ul>
            </li>
            <!-- More items here -->
          </ul>
        </div>
      </div>
      <div class="sidebar-footer">
        <!-- Account, theme, and collapse buttons here -->
        <button id="accountButton" class="btn btn-primary"><i class="bi bi-person"></i></button> <!-- Account icon -->
        <div class="dropup">
          <button class="btn btn-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
            <svg class="bi my-1 theme-icon-active" width="1em" height="1em">
              <use href="#circle-half"></use>
            </svg>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                <svg class="bi me-2 opacity-50" width="1em" height="1em">
                  <use href="#sun-fill"></use>
                </svg>
                Lys tilstand
                <svg class="bi ms-auto d-none" width="1em" height="1em">
                  <use href="#check2"></use>
                </svg>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                <svg class="bi me-2 opacity-50" width="1em" height="1em">
                  <use href="#moon-stars-fill"></use>
                </svg>
                Mørk tilstand
                <svg class="bi ms-auto d-none" width="1em" height="1em">
                  <use href="#check2"></use>
                </svg>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                <svg class="bi me-2 opacity-50" width="1em" height="1em">
                  <use href="#circle-half"></use>
                </svg>
                Automatisk
                <svg class="bi ms-auto d-none" width="1em" height="1em">
                  <use href="#check2"></use>
                </svg>
              </button>
            </li>
          </ul>
        </div>
        <button id="collapseButton" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></button>
      </div>
    </div>
    <div class="flex-grow-1 content">
      <!-- Main content here -->


      <script>
        // Function to set the sidebar state in localStorage
function setSidebarState(state) {
  localStorage.setItem('sidebarState', state);
}

// Function to get the sidebar state from localStorage
function getSidebarState() {
  return localStorage.getItem('sidebarState');
}

// Function to set the color mode
function setColorMode(mode) {
  if (mode === 'light') {
    document.documentElement.style.setProperty('--sidebar-background-light', 'linear-gradient(to bottom, var(--bs-light), var(--bs-primary))');
    document.documentElement.style.setProperty('--sidebar-background-dark', 'linear-gradient(to bottom, var(--bs-dark), var(--bs-primary))');
  } else if (mode === 'dark') {
    document.documentElement.style.setProperty('--sidebar-background-light', 'linear-gradient(to bottom, var(--bs-dark), var(--bs-primary))');
    document.documentElement.style.setProperty('--sidebar-background-dark', 'linear-gradient(to bottom, var(--bs-dark), var(--bs-primary))');
  }
}

document.addEventListener('DOMContentLoaded', function() {
  var savedState = getSidebarState();

  // Set the sidebar state
  if (savedState === 'collapsed') {
    document.getElementById('sidebar').classList.add('collapsed');
  }

  // Set the color mode
  var preferredColorMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  var savedColorMode = localStorage.getItem('colorMode');
  setColorMode(savedColorMode || preferredColorMode);

  // Add event listener for the collapse button
  document.getElementById('collapseButton').addEventListener('click', function() {
    var sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
    var newState = sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded';
    setSidebarState(newState);
  });

  // Add event listener for the theme buttons
  document.querySelectorAll('[data-bs-theme-value]').forEach(function(button) {
    button.addEventListener('click', function() {
      var theme = this.getAttribute('data-bs-theme-value');
      setColorMode(theme);
      localStorage.setItem('colorMode', theme);
    });
  });

  // Set sidebar state on initial load
  setSidebarState(savedState || 'expanded');
});

      </script>
</body>