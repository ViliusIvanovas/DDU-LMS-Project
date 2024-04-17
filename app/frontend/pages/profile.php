<div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad">
      <div class="panel panel-info">
        <div class="panel-heading">
          <?php
          $user_data = $user->data();
          $user_id = $user_data->user_id;
          $name = User::getFullName($user_id);
          ?>
          <div class="row">
            <div class="col-md-8">
              <h3 class="panel-title"><?php echo escape($name); ?></h3>
            </div>
              <div class="col-md-4">
                <img src="image.php?id=<?php echo $user_data->profile_picture; ?>" class="card-img room-image mb-3" alt="">
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
  </div>
</div>
<div class="panel-body">
  <div class="row">
    <div class=" col-md-9 col-lg-9 ">
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
      <button> <a href="view_grades.php?>">Se Karakterer</a></button>
      <a href="update-account.php" class="btn btn-primary">Update Information</a>
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </div>
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