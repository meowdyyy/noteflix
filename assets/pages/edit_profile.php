<?php global $user;?>
<div class="container col-md-9 col-sm-12 rounded-0 d-flex justify-content-between">
    <div class="col-12 bg-white border rounded p-4 mt-4 shadow-sm">
        <form method="post" action="assets/php/actions.php?updateprofile" enctype="multipart/form-data">
            <div class="d-flex justify-content-center"></div>
            <h1 class="h5 mb-3 fw-normal">Edit Profile</h1>
            <?php
            if(isset($_GET['success'])){
            ?>
            <p class="text-success">Your profile has been successfully updated!</p>
            <?php
            }
            ?>
            <div class="form-floating mt-1 col-md-6 col-sm-12">
                <img src="assets/images/profile/<?=$user['profile_pic']?>" class="img-thumbnail my-3" style="height:150px;width:150px" alt="Profile Picture">
                <div class="mb-3">
                    <label for="formFile" class="form-label">Change Profile Picture</label>
                    <input class="form-control" type="file" name="profile_pic" id="formFile">
                </div>
            </div>
            <?=showError('profile_pic')?>

            <div class="d-flex">
                <div class="form-floating mt-1 col-6">
                    <input type="text" name="first_name" value="<?=$user['first_name']?>" class="form-control rounded-0" placeholder="First Name">
                    <label for="floatingInput">First Name</label>
                </div>
                <div class="form-floating mt-1 col-6">
                    <input type="text" name="last_name" value="<?=$user['last_name']?>" class="form-control rounded-0" placeholder="Last Name">
                    <label for="floatingInput">Last Name</label>
                </div>
            </div>
            <?=showError('first_name')?>
            <?=showError('last_name')?>

            <div class="d-flex gap-3 my-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1"
                        value="option1" <?=$user['gender']==1?'checked':''?> disabled>
                    <label class="form-check-label" for="exampleRadios1">
                        Male
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3"
                        value="option2" <?=$user['gender']==2?'checked':''?> disabled>
                    <label class="form-check-label" for="exampleRadios3">
                        Female
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2"
                        value="option2" <?=$user['gender']==0?'checked':''?> disabled>
                    <label class="form-check-label" for="exampleRadios2">
                        Other
                    </label>
                </div>
            </div>

            <div class="form-floating mt-1">
                <input type="email" value="<?=$user['email']?>" class="form-control rounded-0" placeholder="Email" disabled>
                <label for="floatingInput">Email</label>
            </div>
            <div class="form-floating mt-1">
                <input type="text"  value="<?=$user['username']?>" name="username" class="form-control rounded-0" placeholder="Username">
                <label for="floatingInput">Username</label>
            </div>
            <?=showError('username')?>

            <div class="form-floating mt-1">
                <input type="password" name="password" class="form-control rounded-0" id="floatingPassword" placeholder="New Password">
                <label for="floatingPassword">New Password</label>
            </div>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <button class="btn btn-primary" type="submit">Update Profile</button>
            </div>
        </form>
    </div>
</div>