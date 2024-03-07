<style>
  .container {
    display: flex;
  }

  .sidebar {
    width: 320px;
    transition: width 0.3s;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100vh;
    position: fixed;
    border-right: 2px solid gray;
  }

  .sidebar.collapsed {
    width: 80px;
  }

  .sidebar.collapsed .sidebar-item  {
    display: none;
  }

  .sidebar .bi {
    font-size: 1.5rem;
    display: inline-block;
    vertical-align: middle;
  }

  .sidebar-footer {
    padding: 1rem;
  }

  .sidebar-footer .btn {
    width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
  }

  .content {
    flex-grow: 1;
    transition: margin-left 0.3s;
    margin-left: 320px;
  }

  .sidebar.collapsed ~ .content {
    margin-left: 80px;
  }

  .sidebar.collapsed .bi {
    margin-left: auto;
    margin-right: auto;
  }
</style>

<body>
  
<div class="d-flex">
    <div id="sidebar" class="bg-dark sidebar">
      <div>
        <div class="flex-shrink-0 p-3">
          <ul class="mt-3 list-unstyled">
            <li class="mb-2">
              <a href="#" class="text-white text-decoration-none">
                <i class="bi bi-house"></i>
                <span class="sidebar-item">Home</span>
              </a>
            </li>
            <li class="mb-2">
              <a href="#submenu1" data-bs-toggle="collapse" class="text-white text-decoration-none">
                <i class="bi bi-folder2-open"></i>
                <span class="sidebar-item">Expandable Item</span>
              </a>
              <ul id="submenu1" class="list-unstyled collapse">
                <li><a href="#" class="text-white text-decoration-none">Sub Item 1</a></li>
                <li><a href="#" class="text-white text-decoration-none">Sub Item 2</a></li>
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
                Light
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
                Dark
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
                Auto
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
    document.getElementById('collapseButton').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('collapsed');
    });
  </script>
  <script src="/app/frontend/assets/js/color-modes.js"></script>
