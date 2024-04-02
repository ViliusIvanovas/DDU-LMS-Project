<div class="login-styling" style="padding-top: 1%; padding-bottom: 5%;">
    <div>
        <h2 style="">Login</h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" class="form-control" id="mail" placeholder="Mail" name="mail">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="password" placeholder="Kodeord" name="password">
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo Token::generate(); ?>">
            <input type="submit" value="Log In">
        </form>
    </div>
</div>