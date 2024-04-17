<?php $data = $user->data(); ?>
<div class="container" style="padding-top: 5%; padding-bottom: 5%;">
  <h2>Update Information</h2>
  <form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label for="email">Email :</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="<?php echo escape($data->email); ?>">
    </div>
    <div class="form-group">
      <label for="current_password">Current Password :</label>
      <input type="password" class="form-control" id="current_password" placeholder="Enter current password" name="current_password">
    </div>
    <div class="form-group">
      <label for="new_password">New Password :</label>
      <input type="password" class="form-control" id="new_password" placeholder="Enter new_password" name="new_password">
    </div>
    <div class="form-group">
      <label for="confirm_new_password">Confirm Password :</label>
      <input type="password" class="form-control" id="confirm_new_password" placeholder="Confirm your new password" name="confirm_new_password">
    </div>
    <input type="hidden" name="csrf_token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Update Profile">
  </form>
</div>