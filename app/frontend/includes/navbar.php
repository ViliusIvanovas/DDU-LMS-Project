<body>
  <div class="d-flex">
    <div id="sidebar" class="background-sidebar sidebar">
      <div>
        <div class="flex-shrink-0 p-3">
          <ul class="mt-3 list-unstyled">
            <li class="mb-2">
              <a href="index.php" class="nav-link link-body-emphasis d-flex align-items-center">
                <img src="/app/frontend/assets/img/StudX_logo.webp" alt="Home Icon" style="width: 48px; height: 48px;">
                <span class="sidebar-item" style="font-size: 2em; margin-left: 10px;">StudX</span>
              </a>
            </li>
            <li class="mb-2">
              <a href="#submenu1" data-bs-toggle="collapse" class="nav-link link-body-emphasis">
                <i class="bi bi-folder2-open"></i>
                <span class="sidebar-item">Mine kurser</span>
              </a>
              <ul id="submenu1" class="list-unstyled collapse">
                <li><a href="rooms.php" id="myCoursesLink" class="nav-link link-body-emphasis boldListText"></a></li> <!-- Keeps code working -->
              </ul>
              <?php
              $rooms = Rooms::getAllRoomsByUserId($user->data()->user_id);

              if (count($rooms) > 0 && $rooms != null) {
                echo '<ul id="submenu1" class="list-unstyled collapse">';
                foreach ($rooms as $room) {
                  $class = Rooms::getClassByRoomId($room->room_id);
                  echo '<li>';
                  echo '<a href="room.php?room_id=' . $room->room_id . '" data-room-name="' . $room->name . '" class="nav-link link-body-emphasis roomName">' . $room->name . '</a>';
                  // Fetch sections for this room
                  $sections = Rooms::getAllSectionsByRoomId($room->room_id);

                  if (count($sections) > 0) {
                    echo '<ul id="subroom' . $room->room_id . '" class="list-unstyled collapse">';
                    foreach ($sections as $section) {
                      echo '<li><a id="section' . $section->section_id . '" href="section.php?section_id=' . $section->section_id . '" class="nav-link link-body-emphasis sectionName">' . $section->name . '</a></li>';
                    }
                    echo '</ul>';
                  }

                  echo '</li>';
                }
                echo '</ul>';
              }
              ?>
            </li>
            <!-- More items here -->
          </ul>
        </div>
      </div>
      <div class="sidebar-footer d-flex justify-content-evenly flex-wrap sidebarPhone">
        <!-- Account, theme buttons here -->
        <div class="dropup"> <!-- Dropdown for account -->
  <button class="btn btn-primary dropdown-toggle" type="button" id="accountButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="bi bi-person"></i>
  </button>
  <div class="dropdown-menu" aria-labelledby="accountButton">
  <a class="dropdown-item" href="view_grades.php"><i class="bi bi-star"></i> <span class="moreSpacing">Karakterer</span></a>
            <a class="dropdown-item" href="profile.php"><i class="bi bi-person-circle"></i> <span class="moreSpacing">Min profil</span></a>
            <a class="dropdown-item" href="check_abscence.php"><i class="bi bi-x-circle"></i> <span class="moreSpacing">Fravær</span></a>
            <a class="dropdown-item" href="chat.php"><i class="bi bi-chat-left-dots"></i> <span class="moreSpacing">Samtaler</span></a>
  </div>
</div>
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
            <!--
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                <i class="bi bi-circle-half" class="emIcon"></i>
                <span class="moreSpacing">Automatisk</span>
                <svg class="bi ms-auto d-none" class="emIcon">
                  <use href="#check2"></use>
                </svg>
              </button>
            </li>
            -->
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
          var root = document.documentElement;
          if (mode === 'dark') {
            root.style.setProperty('--sidebar-background', 'var(--sidebar-background-dark)');
          } else {
            root.style.setProperty('--sidebar-background', 'var(--sidebar-background-light)');
          }
        }

        /* Expandable items */

        // Function to set the expandable item state in localStorage
        function setExpandableItemState(itemId, state) {
          localStorage.setItem(itemId, state);
        }

        // Function to get the expandable item state from localStorage
        function getExpandableItemState(itemId) {
          return localStorage.getItem(itemId);
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

          // Update the "Mine kurser" link based on the current room or section
          function updateMineKurserLink() {
            var selectedRoomName = localStorage.getItem('selectedRoomName');
            var currentLocation = window.location.pathname;

            // Check if the current location is within a room or section
            if (selectedRoomName && (currentLocation.includes('room.php') || currentLocation.includes('section.php'))) {
              // Set the room name to be bold
              var roomLinks = document.querySelectorAll('.roomName');
              roomLinks.forEach(function(link) {
                if (link.textContent === selectedRoomName) {
                  link.style.fontWeight = 'bold';
                } else {
                  link.style.fontWeight = 'normal';
                }
              });

              // Update the "Mine kurser" link
              document.getElementById('myCoursesLink').textContent = '';
            } else {
              // Revert to "empty" if not in a room or section
              document.getElementById('myCoursesLink').textContent = '';
            }
          }


          // Call updateMineKurserLink function on page load
          updateMineKurserLink();

          // Add event listener for the collapse button
          document.getElementById('collapseButton').addEventListener('click', function() {
            var sidebar = document.getElementById('sidebar');
            var expandableItems = document.querySelectorAll('.collapse.show'); // Select all expandable items that are currently expanded
            sidebar.classList.toggle('collapsed');
            var newState = sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded';
            setSidebarState(newState);

            // Collapse all expandable items when the sidebar collapses
            if (newState === 'collapsed') {
              expandableItems.forEach(function(item) {
                item.classList.remove('show');
                var itemId = '#' + item.getAttribute('id');
                setExpandableItemState(itemId, 'collapsed'); // Update their state in localStorage
              });
            }
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

          /* Expandable items */

          document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(item) {
            item.addEventListener('click', function(event) {
              // Prevent the default behavior of the link
              event.preventDefault();

              var itemId = this.getAttribute('href');
              var currentState = getExpandableItemState(itemId);

              // If the sidebar is collapsed, expand it first before expanding the item
              if (document.getElementById('sidebar').classList.contains('collapsed')) {
                document.getElementById('sidebar').classList.remove('collapsed');
                setSidebarState('expanded');
              }

              // Toggle the expandable item state
              if (currentState === 'expanded') {
                setExpandableItemState(itemId, 'collapsed');
              } else {
                setExpandableItemState(itemId, 'expanded');
              }
            });
          });

          // Set expandable items state on initial load
          document.querySelectorAll('.collapse').forEach(function(item) {
            var itemId = '#' + item.getAttribute('id');
            var savedState = getExpandableItemState(itemId);
            if (savedState === 'expanded') {
              item.classList.add('show');
            }
          });

          /* Expandable items end */

        });

        // Rooms and subrooms

        window.onload = function() {
          var urlParams = new URLSearchParams(window.location.search);
          var roomId = urlParams.get('room_id');
          if (roomId) {
            var subroom = document.getElementById('subroom' + roomId);
            if (subroom) {
              subroom.classList.add('show');
            }
          }
        };

        // Update the room name when a new room is selected
        document.querySelectorAll('.roomName').forEach(function(roomLink) {
          roomLink.addEventListener('click', function() {
            var roomName = this.textContent;
            localStorage.setItem('selectedRoomName', roomName);
            updateMineKurserLink(); // Update the "Mine kurser" link
          });
        });
        // Rooms and subrooms

        // Function to set the room state in sessionStorage
        function setRoomState(roomId, state) {
          sessionStorage.setItem(roomId, state);
        }

        // Function to get the room state from sessionStorage
        function getRoomState(roomId) {
          return sessionStorage.getItem(roomId);
        }

        // Set room state on initial load
        document.querySelectorAll('.roomName').forEach(function(item) {
          var roomId = item.getAttribute('href').split('=')[1];
          var savedState = getRoomState(roomId);
          if (savedState === 'expanded') {
            document.querySelector('#subroom' + roomId).classList.add('show');
          }
        });

        // Add event listener for the room links
        document.querySelectorAll('.roomName').forEach(function(item) {
          item.addEventListener('click', function() {
            var roomId = this.getAttribute('href').split('=')[1];
            var currentState = getRoomState(roomId);

            // Toggle the room state
            if (currentState === 'expanded') {
              setRoomState(roomId, 'collapsed');
            } else {
              setRoomState(roomId, 'expanded');
            }
          });
        });

        // On page load, check if the URL has a room_id parameter and if so, expand that room
        document.addEventListener('DOMContentLoaded', function() {
          var urlParams = new URLSearchParams(window.location.search);
          var roomId = urlParams.get('room_id');
          if (roomId) {
            var room = document.querySelector('#subroom' + roomId);
            if (room) {
              room.classList.add('show');
              setRoomState(roomId, 'expanded');
            }
          }
        });
      </script>
</body>