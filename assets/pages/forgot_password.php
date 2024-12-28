<div class="login">
    <div class="col-lg-4 col-md-8 col-sm-12 bg-white border rounded p-4 shadow-sm">
        <?php
if (isset($_SESSION['forgot_code']) && !isset($_SESSION['auth_temp'])) {
    $action = 'verifycode';
} elseif (isset($_SESSION['forgot_code']) && isset($_SESSION['auth_temp'])) {
    $action = 'changepassword';
} else {
    $action = 'forgotpassword';
}
        ?>
        <form method="post" action="assets/php/actions.php?<?=$action?>">
            <div class="d-flex justify-content-center"></div>
            <h1 class="h5 mb-3 fw-normal">Reset Your Password</h1>
            <?php
if ($action == 'forgotpassword') {
    ?>
            <div class="form-floating">
                <input type="email" name="email" class="form-control rounded-0" placeholder="Enter your email">
                <label for="floatingInput">Enter your email</label>
            </div>
            <?=showError('email')?>

            <br>
            <button class="btn btn-primary" type="submit">Send Verification Code</button>
            <?php
}
?>

            <?php
if ($action == 'verifycode') {
    ?>
            <p>Please enter the 6-digit verification code sent to your email - <?=$_SESSION['forgot_email']?></p>
            <div class="form-floating mt-1">
                <input type="text" name="code" class="form-control rounded-0" id="floatingPassword" placeholder="Enter code">
                <label for="floatingPassword">######</label>
            </div>
            <?=showError('email_verify')?>

            <br>
            <button class="btn btn-primary" type="submit">Verify Code</button>
            <?php
}
?>

            <?php
if ($action == 'changepassword') {
    ?>
            <p>Please enter a new password for your account - <?=$_SESSION['forgot_email']?></p>
            <div class="form-floating mt-1">
                <input type="password" name="password" class="form-control rounded-0" id="floatingPassword" placeholder="New password">
                <label for="floatingPassword">New password</label>
            </div>
            <?=showError('password')?>

            <br>
            <button class="btn btn-primary" type="submit">Change Password</button>
            <?php
}
?>

            <br>
            <br>
            <a href="?login" class="text-decoration-none mt-5 clklink"><i class="bi bi-arrow-left-circle-fill"></i> Return to Login</a>
        </form>
    </div>
</div>