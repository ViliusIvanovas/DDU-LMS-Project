<div class="login-styling" style="overflow: hidden;">
    <div>
        <form action="" method="post">
            <div class="glass card">
                <div class="d-flex login-body align-items-center flex-column">
                    <div class="card login-box">
                        <h2>Login</h2>
                        <div class="form-group">
                            <input type="text" class="form-control" id="mail" placeholder="Mail" name="mail">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="password" placeholder="Kodeord" name="password">
                        </div>
                        <input type="hidden" name="csrf_token" value="<?php echo Token::generate(); ?>">
                        <input type="submit" value="Log in">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>