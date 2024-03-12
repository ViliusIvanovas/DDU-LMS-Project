<body>
    <?php
    $section_id = $_GET['section_id'];

    $section = Rooms::getSectionById($section_id);
    ?>

    <div id="main" class="container">
        <h1>Vælg hvilket slags oplæg du ønsker at oprette</h1>
        <br>

        <h4>Nuværende sti:</h4>
        // call room.php command to figure out where the fuck we are, I will cook later

        <style>
            .post-type-choice {
                display: flex;
                justify-content: space-around;
                align-items: center;
                height: auto;
                width: 100%;
            }

            .choice {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 150px;
                height: 150px;
                font-size: 30px;
                cursor: pointer;
                border: 1px solid #000;
                border-radius: 10px;
                text-decoration: none;
                color: black;
            }

            .choice:hover {
                background-color: #f2f2f2;
            }
        </style>

        <div class="post-type-choice">
            <a href="setup-file.php" class="choice">
                <h5>Fil</h5>
                <i class="bi bi-file-earmark-text"></i>
            </a>
            <a href="setup-group.php" class="choice">
                <h5>Grupper</h5>
                <i class="bi bi-people-fill"></i>
            </a>
            <a href="setup-bulletin-board.php" class="choice">
                <h5>Opslagsværk</h5>
                <i class="bi bi-book-half"></i>
            </a>
            <a href="setup-assignment.php" class="choice">
                <h5>Aflevering</h5>
                <i class="bi bi-file-earmark-check"></i>
            </a>
            <a href="setup-note.php" class="choice">
                <h5>Note/tekst</h5>
                <i class="bi bi-file-earmark-richtext"></i>
            </a>
        </div>
    </div>
</body>