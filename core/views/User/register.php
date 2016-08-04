<div class="login-page">
    <div class="form">
        <form action="<?= $data["self_uri"] ?>" method="POST" class="register-form" autocomplete="off">

            <label for="username">Username</label>
            <div class="error-message"><?php if (isset($data['errors']['username'])) echo $data['errors']['username']; ?></div>
            <input type="text" name="username" id="username" placeholder="username"
                   <?php if (isset($data['POST']['username'])): ?> value="<?= $data['POST']['username'] ?>"/> <?php endif; ?>

            <label for="email">Email</label>
            <div class="error-message"><?php if (isset($data['errors']['email'])) echo $data['errors']['email']; ?></div>
            <input type="text" name="email" id="email" placeholder="email address"
                <?php if (isset($data['POST']['email'])): ?> value="<?= $data['POST']['email'] ?>"/> <?php endif; ?>

            <label for="password">Password</label>
            <div class="error-message"><?php if (isset($data['errors']['password'])) echo $data['errors']['password']; ?></div>
            <input type="password" name="password" id="password" placeholder="password"/>

            <label for="confirm_password">Confirm password</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="confirm password"/>

            <input type="hidden" name="<?= $data['scrf.field'] ?>" value="<?= $data['scrf'] ?>"/>

            <button name="create" value="create user">create</button>
            <p class="message">Already registered? <a href="<?= $data["login link"]  ?>">Sign In</a></p>
        </form>
    </div>
</div>