<style>
  .footer {
    background-color: #f2f2f2;
    padding: 10px;
    width: 100%;
  }
  .footer h6 {
    margin: 0;
  }
  .footer a {
    text-decoration: none;
    color: black;
  }
  .footer a:hover {
    text-decoration: underline;
  }
</style>

<br>

<div id="footer" class="jumbotron text-center container footer" style="margin-bottom:0">
  <?php if ($user->isAdmin()) {
    echo '<h6><a href="management-hub.php">Management Hub</a></h6>';
  } ?>
  <h6><a href="site-policies.php">Site Policies</a></h6>
  <h6>© 2023. UWU games (I/S)</h6>
</div>

</body>

</html>