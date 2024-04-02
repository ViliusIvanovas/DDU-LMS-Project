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
        window.onload = setSidebarBackground;

        // Function to set a cookie
        function setCookie(name, value, days) {
          var expires = "";
          if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
          }
          document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        // Function to get a cookie
        function getCookie(name) {
          var nameEQ = name + "=";
          var ca = document.cookie.split(';');
          for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
          }
          return null;
        }

        // Get the sidebar element
        var sidebar = document.getElementById('sidebar');

        // Check the cookie and set the sidebar state
        if (getCookie('sidebar') === 'collapsed') {
          sidebar.classList.add('collapsed');
        }

        // Add event listener for the collapse button
        document.getElementById('collapseButton').addEventListener('click', function() {
          sidebar.classList.toggle('collapsed');
          if (sidebar.classList.contains('collapsed')) {
            setCookie('sidebar', 'collapsed', 7);
          } else {
            setCookie('sidebar', '', 7);
          }
        });

        // Add event listener for the theme buttons
        document.querySelectorAll('[data-bs-theme-value]').forEach(function(button) {
          button.addEventListener('click', function() {
            var theme = this.getAttribute('data-bs-theme-value');
            if (theme === 'light') {
              document.documentElement.style.setProperty('--sidebar-background-light', 'linear-gradient(to bottom, var(--bs-light), var(--bs-primary))');
              document.documentElement.style.setProperty('--sidebar-background-dark', 'linear-gradient(to bottom, var(--bs-dark), var(--bs-primary))');
            } else if (theme === 'dark') {
              document.documentElement.style.setProperty('--sidebar-background-light', 'linear-gradient(to bottom, var(--bs-dark), var(--bs-primary))');
              document.documentElement.style.setProperty('--sidebar-background-dark', 'linear-gradient(to bottom, var(--bs-dark), var(--bs-primary))');
            } else if (theme === 'auto') {
              if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.style.setProperty('--sidebar-background-light', 'linear-gradient(to bottom, var(--bs-dark), var(--bs-primary))');
                document.documentElement.style.setProperty('--sidebar-background-dark', 'linear-gradient(to bottom, var(--bs-dark), var(--bs-primary))');
              } else {
                document.documentElement.style.setProperty('--sidebar-background-light', 'linear-gradient(to bottom, var(--bs-light), var(--bs-primary))');
                document.documentElement.style.setProperty('--sidebar-background-dark', 'linear-gradient(to bottom, var(--bs-dark), var(--bs-primary))');
              }
            }
          });
        });

        function setSidebarBackground() {
          var theme = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
          if (theme === 'light') {
            document.documentElement.style.setProperty('--sidebar-background-light', 'linear-gradient(to bottom, var(--bs-light), var(--bs-primary))');
            document.documentElement.style.setProperty('--sidebar-background-dark', 'linear-gradient(to bottom, var(--bs-dark), var(--bs-primary))');
          } else if (theme === 'dark') {
            document.documentElement.style.setProperty('--sidebar-background-light', 'linear-gradient(to bottom, var(--bs-dark), var(--bs-primary))');
            document.documentElement.style.setProperty('--sidebar-background-dark', 'linear-gradient(to bottom, var(--bs-dark), var(--bs-primary))');
          }
        }
      </script>
      <script src="/app/frontend/assets/js/color-modes.js"></script>
</body>