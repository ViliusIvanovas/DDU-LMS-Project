<div class="container">
  <div class="card d-flex flex-column">
    <div class="card-header">
      <div class="row">
        <?php
        $user_data = $user->data();
        $user_id = $user_data->user_id;
        $name = User::getFullName($user_id);
        ?>
        <div class="col-md-8">
          <h3 class="panel-title"><?php echo escape($name); ?></h3>
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td>Navn :</td>
                <td><?php echo escape($name); ?></td>
              </tr>
              <tr>
                <td>Email :</td>
                <td><?php echo escape($data->email); ?></td>
              </tr>
              <tr>
                <td>Dag startede :</td>
                <td><?php echo escape($data->start_date); ?></td>
              </tr>
            </tbody>
          </table>
          <a href="view_grades.php?>" class="btn btn-primary">Se karakterer</a>
          <a href="update-account.php" class="btn btn-primary">Opdater information</a>
          <a href="logout.php" class="btn btn-danger">Log af</a>
        </div>
        <div class="col-md-4">
          <img src="image.php?id=<?php echo $user_data->profile_picture; ?>" class="room-image" alt="">
          <form action="upload.php" method="post" enctype="multipart/form-data">
            Vælg fil:
            <input type="file" name="fileToUpload" id="fileToUpload" onchange="displayFileType(event)">
            <input type="hidden" name="return_page" value="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="post_type" value="<?php echo $post_type; ?>">
            <input type="submit" value="Skift Profil Billede" class="btn btn-primary mt-2">
          </form>
          <p id="fileTypeDisplay"></p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function displayFileType(event) {
    var file = event.target.files[0];
    var fileTypeDisplay = document.getElementById('fileTypeDisplay');

    if (file) {
      var fileType = file.name.split('.').pop();
      var fileTypeText = '';

      switch (fileType) {
        case 'jpg':
        case 'jpeg':
          fileTypeText = 'JPEG image';
          break;
        case 'png':
          fileTypeText = 'PNG image';
          break;
        case 'gif':
          fileTypeText = 'GIF image';
          break;
        default:
          fileTypeText = 'Other file type';
      }

      fileTypeDisplay.textContent = 'File type: ' + fileTypeText;
    }
  }
</script>