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
                <li><a href="rooms.php" class="nav-link link-body-emphasis">Dine rum</a></li>
                <li><a href="schedule.php" class="nav-link link-body-emphasis">Skema</a></li>
              </ul>
            </li>
            <!-- More items here -->
          </ul>
        </div>
      </div>
      <div class="sidebar-footer d-flex justify-content-evenly flex-wrap">
        <!-- Account, theme buttons here -->
        <a href="classes.php">
    <button id="accountButton" class="btn btn-primary d-flex"><i class="bi bi-person"></i></button> <!-- Account icon -->
</a>
        <div class="dropup">
          <button class="btn btn-primary py-2 dropdown-toggle d-flex" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
            <svg class="bi my-1 theme-icon-active" class="emIcon">
              <use href="#circle-half"></use>
            </svg>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                <i class="bi bi-brightness-high" class="emIcon"></i>
                <span class="moreSpacing">Lys tilstand</span>
                <svg class="bi ms-auto d-none" class="emIcon">
                  <use href="#check2"></use>
                </svg>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                <i class="bi bi-moon-stars" class="emIcon"></i>
                <span class="moreSpacing">Mørk tilstand</span>
                <svg class="bi ms-auto d-none" class="emIcon">
                  <use href="#check2"></use>
                </svg>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                <i class="bi bi-circle-half" class="emIcon"></i>
                <span class="moreSpacing">Automatisk</span>
                <svg class="bi ms-auto d-none" class="emIcon">
                  <use href="#check2"></use>
                </svg>
              </button>
            </li>
          </ul>
        </div>
        <!-- Popout button here -->
        <button id="collapseButton" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></button>
      </div>
    </div>
    <div class="flex-grow-1 content" id="main">
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
            document.documentElement.style.setProperty('--sidebar-background-light', 'linear-gradient(to top, rgba(148,215,242,1) 0%, rgba(252,247,248,1) 56%);');
            document.documentElement.style.setProperty('--sidebar-background-dark', 'linear-gradient(to top, rgba(148,215,242,1) 0%, rgba(49,49,49,1) 44%, rgba(28,27,31,1) 83%)');
          } else if (mode === 'dark') {
            document.documentElement.style.setProperty('--sidebar-background-light', 'linear-gradient(to top, rgba(148,215,242,1) 0%, rgba(49,49,49,1) 44%, rgba(28,27,31,1) 83%)');
            document.documentElement.style.setProperty('--sidebar-background-dark', 'linear-gradient(to top, rgba(148,215,242,1) 0%, rgba(49,49,49,1) 44%, rgba(28,27,31,1) 83%)'); 
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
          setSidebarState(savedState || 'collapsed');
        });
      </script>
</body>