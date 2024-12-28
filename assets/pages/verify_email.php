<?php
global $user;
?>
<div class="login">
    <div class="col-md-4 col-sm-11 bg-white border rounded p-4 shadow-sm">
        <form method="post" action="assets/php/actions.php?verify_email">
            <div class="d-flex justify-content-center"></div>
            <h1 class="h5 mb-3 fw-normal">Verify Your Email Address (<?=$user['email']?>)</h1>

            <p>Please enter the 6-digit code sent to your email address.</p>
            <div class="form-floating mt-1">
                <input type="text" name="code" class="form-control rounded-0" id="floatingPassword" placeholder="Enter code">
                <label for="floatingPassword">######</label>
            </div>
            <?php
if (isset($_GET['resended'])) {
    ?>
<p class="text-success">A new verification code has been sent!</p>
<?php
}
?>
            <?=showError('email_verify')?>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <button class="btn btn-primary" type="submit">Verify Email</button>
                <a href="assets/php/actions.php?resend_code" class="text-decoration-none clklink" type="submit">Resend Code</a>
            </div>
            <br>
            <a href="assets/php/actions.php?logout" class="text-decoration-none mt-5 clklink">
                <i class="bi bi-arrow-left-circle-fill"></i> Logout
            </a>
        </form>
    </div>
</div>