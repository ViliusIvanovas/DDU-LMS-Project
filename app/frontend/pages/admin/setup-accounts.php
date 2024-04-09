<body>
    <div class="container">
        <h1>Opstil konto</h1>

        <form action="setup-accounts.php" method="post" id="accountForm">
            <div class="mb-3">
                <label for="numAccounts" class="form-label">Antal konti</label>
                <select class="form-select" id="numAccounts" name="numAccounts">
                    <?php for ($i = 1; $i <= 25; $i++) echo "<option value=\"$i\">$i</option>"; ?>
                </select>
            </div>

            <div id="accountInputs"></div>

            <button type="submit" class="btn btn-primary">Opret konto</button>
        </form>
    </div>

    <script>
        function generateInputs() {
            var numAccounts = document.getElementById('numAccounts').value;
            var accountInputs = document.getElementById('accountInputs');

            // Clear the current inputs
            accountInputs.innerHTML = '';

            for (var i = 0; i < numAccounts; i++) {
                accountInputs.innerHTML += `
                <div class="row align-items-center rounded-3 border shadow-lg bg-body-tertiary">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email[]" required>
            </div>
            <div class="mb-3">
                <label for="firstName" class="form-label">Fornavn</label>
                <input type="text" class="form-control" id="firstName" name="firstName[]" required>
            </div>
            <div class="mb-3">
                <label for="middleName" class="form-label">Mellemnavn</label>
                <input type="text" class="form-control" id="middleName" name="middleName[]">
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Efternavn</label>
                <input type="text" class="form-control" id="lastName" name="lastName[]" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="address" name="address[]" required>
            </div>
            <div class="mb-3">
                <label for="birthdate" class="form-label">Fødselsdato</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate[]" required>
            </div>
            <div class="mb-3">
                <label for="schoolStart" class="form-label">Skolestart</label>
                <input type="date" class="form-control" id="schoolStart" name="schoolStart[]" required>
            </div>
            <div class="mb-3">
                <label for="accessLevel" class="form-label">Adgangsniveau</label>
                <select class="form-select" id="accessLevel" name="accessLevel[]" required>
                    <option value="student">Studerende</option>
                    <option value="teacher">Lærer</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>
        </div>
                `;
            }
        }

        // Generate the initial inputs
        generateInputs();

        function formatDateForMySQL(date) {
            var yyyy = date.getFullYear();
            var mm = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero based
            var dd = String(date.getDate()).padStart(2, '0');
            var hh = String(date.getHours()).padStart(2, '0');
            var mi = String(date.getMinutes()).padStart(2, '0');
            var ss = String(date.getSeconds()).padStart(2, '0');

            return `${yyyy}-${mm}-${dd} ${hh}:${mi}:${ss}`;
        }

        document.getElementById('accountForm').addEventListener('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            var birthdates = document.querySelectorAll('input[name="birthdate[]"]');
            var schoolStarts = document.querySelectorAll('select[name="schoolStart[]"]');

            birthdates.forEach(function(input) {
                var date = new Date(input.value);
                input.value = formatDateForMySQL(date);
            });

            schoolStarts.forEach(function(select) {
                var date = new Date(select.value);
                select.value = formatDateForMySQL(date);
            });

            fetch('setup-accounts.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => console.log(data))
                .catch(error => console.error(error));
        });
    </script>
</body>

</html>