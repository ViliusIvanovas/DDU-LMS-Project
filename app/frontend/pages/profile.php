<div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad">
      <div class="panel panel-info">
        <div class="panel-heading">
          <?php
          $user_id = $user->data()->user_id;
          $name = User::getFullName($user_id);
          ?>
          <div class="row">
            <div class="col-md-8">
              <h3 class="panel-title"><?php echo escape($name); ?></h3>
            </div>
            <?php if ($user_id->profile_picture) : ?>
              <div class="col-md-4">
                <img src="image.php?id=<?php echo $user_id->profile_picture; ?>" class="card-img room-image" alt="">
              </div>
            <?php endif; ?>
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
            <td>Date Joined :</td>
            <td><?php echo escape($data->start_date); ?></td>
          </tr>
        </tbody>
      </table>

      <?php
      $is_student = User::isUserStudentForClass($user_id, $class->class_id);
      if ($is_student) : ?>
        <button> <a href="view_grades.php?room_id=<?php echo $room_id; ?>">Se Karakterer</a></button>
      <?php endif; ?>
      <a href="update-account.php" class="btn btn-primary">Update Information</a>
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </div>
</div>
</div>
</div>
</div>
</div>