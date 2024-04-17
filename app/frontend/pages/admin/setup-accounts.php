<!DOCTYPE html>
<html>

<body>
    <div class="container">
        <h1>Opstil konto</h1>

        <form action="setup-account.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="firstName" class="form-label">Fornavn</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
            </div>
            <div class="mb-3">
                <label for="middleName" class="form-label">Mellemnavn</label>
                <input type="text" class="form-control" id="middleName" name="middleName"> <!-- Add this line -->
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Efternavn</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="mb-3">
                <label for="birthdate" class="form-label">Fødselsdato</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
            </div>
            <div class="mb-3">
                <label for="schoolStart" class="form-label">Skolestart</label>
                <input type="date" class="form-control" id="schoolStart" name="schoolStart" required>
            </div>
            <div class="mb-3">
                <label for="accessLevel" class="form-label">Adgangsniveau</label>
                <select class="form-select" id="accessLevel" name="accessLevel" required>
                    <option value="student">Studerende</option>
                    <option value="teacher">Lærer</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Opret konto</button>
        </form>
    </div>
</body>

</html>