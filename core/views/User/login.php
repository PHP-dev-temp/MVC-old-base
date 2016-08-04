<div class="login-page">
    <div class="form">
        <form action="<?= $data["self_uri"] ?>" method="POST" class="login-form" autocomplete="off">

            <label for="user">Username or email</label>
            <div class="error-message"><?php if (isset($data['errors']['user'])) echo $data['errors']['user']; ?></div>
            <input type="text" name="user" id="user" placeholder="username or email address"
                <?php if (isset($data['POST']['user'])): ?> value="<?= $data['POST']['user'] ?>"/> <?php endif; ?>

            <label for="password">Password</label>
            <div class="error-message"><?php if (isset($data['errors']['password'])) echo $data['errors']['password']; ?></div>
            <input type="password" name="password" id="password" placeholder="password"/>

            <input type="hidden" name="<?= $data['scrf.field'] ?>" value="<?= $data['scrf'] ?>"/>

            <button name="login" value="user login">login</button>
            <p class="message">Not registered? <a href="<?= $data["register link"]  ?>">Create an account</a></p>

            <input type="checkbox" name="remember" id="remember"/><label class="checkbox" for="remember">Remember me</label>
        </form>
    </div>
</div>