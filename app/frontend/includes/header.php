<?php
session_start();

// check if user is logged in if the current site, isn't the login page
if (!isset($_SESSION['user']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
  header('Location: login.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="/app/frontend/assets/js/bootstrap.bundle.min.js"></script>

  <link rel="stylesheet" href="/app/frontend/assets/css/bootstrap.min.css">

  <script src="/app/frontend/assets/js/color-modes.js"></script>

  <link rel="stylesheet" href="<?php echo FRONTEND_ASSET . 'css/profile.css'; ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

  <title>
    <?php
    $currentFile = basename($_SERVER['PHP_SELF']);
    $pageName = str_replace(".php", "", $currentFile);
    $pageName = str_replace(array('-', '_'), ' ', $pageName);
    $pageName = ucwords($pageName);

    echo $pageName;
    ?>
  </title>

  <link rel="icon" href="/app/frontend/assets/img/StudX_logo.webp">

</head>