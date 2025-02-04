<?php
global $profile;
global $profile_note;
global $user;
?>
    <div class="container col-md-9 col-sm-11 rounded-0">
        <div class="col-12 rounded p-4 mt-4 d-md-flex gap-5">
            <div class="col-md-4 col-sm-12 d-flex justify-content-center mx-auto align-items-start"><div class="px-md-5"></div><img src="assets/images/profile/<?=$profile['profile_pic']?>"
                    class="img-thumbnail rounded-circle mb-3" style="width:170px;height:170px" alt="Profile Picture">
            </div>
            <div class="col-md-8 col-sm-11">
                <div class="d-flex flex-column">
                    <div class="d-flex gap-5 align-items-center">
                        <span style="font-size: xx-large;"><?=$profile['first_name']?> <?=$profile['last_name']?></span>
                        
                        <?php
if($user['id']!=$profile['id'] && !checkBS($profile['id'])){
    ?>
  <div class="dropdown">
                            <span class="" style="font-size:xx-large" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots"></i> </span>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#chatbox" onclick="popchat(<?=$profile['id']?>)"><i class="bi bi-chat-fill"></i> Send Message</a></li>
                                <li><a class="dropdown-item " href="assets/php/actions.php?block=<?=$profile['id']?>&username=<?=$profile['username']?>"><i class="bi bi-x-circle-fill"></i> Block User</a></li>
                            </ul>
                        </div>
    <?php
}
                        ?>
                      


                    </div>
                    <span style="font-size: larger;" class="text-secondary">@<?=$profile['username']?></span>
                    <?php
if(!checkBS($profile['id'])){
    ?>
 <div class="d-flex gap-2 align-items-center my-3">

<a class="btn btn-sm btn-primary"><i class="bi bi-file-note-fill"></i> <?=count($profile_note)?> Notes Uploaded</a>
<a class="btn btn-sm btn-primary <?=count($profile['followers'])<1?'disabled':''?>" data-bs-toggle="modal" data-bs-target="#follower_list"><i class="bi bi-people-fill"></i> <?=count($profile['followers'])?> Followers</a>
<a class="btn btn-sm btn-primary <?=count($profile['following'])<1?'disabled':''?>" data-bs-toggle="modal" data-bs-target="#following_list"><i class="bi bi-person-fill"></i> <?=count($profile['following'])?> Following</a>


</div>
    <?php

}
                    ?>
                   
<?php


if($user['id']!=$profile['id']){
?>
 <div class="d-flex gap-2 align-items-center my-1">
<?php
if(checkBlockStatus($user['id'],$profile['id'])){
?> 
<button class="btn btn-sm btn-danger unblockbtn" data-user-id='<?=$profile['id']?>' >Unblock User</button>

<?php
}else if(checkBlockStatus($profile['id'],$user['id'])){ ?>
    <div class="alert alert-danger" role="alert">
    <i class="bi bi-x-octagon-fill"></i> You have been blocked by @<?=$profile['username']?>.
</div>
   <?php }else if(checkFollowStatus($profile['id'])){
   ?>
<button class="btn btn-sm btn-danger unfollowbtn" data-user-id='<?=$profile['id']?>' >Unfollow</button>
   
   <?php
}else{
    ?>

<button class="btn btn-sm btn-primary followbtn" data-user-id='<?=$profile['id']?>' >Follow</button>

    <?php
}
?>



</div>
<?php
}
?>
                   
                </div>
            </div>


        </div>
        <h3 class="border-bottom">Notes</h3>
        <?php

if(checkBS($profile['id'])){
    $profile_note = array();

   ?>
 <div class="alert alert-secondary text-center" role="alert">
    <i class="bi bi-x-octagon-fill"></i> You are not authorized to view the notes.
</div>
   <?php
    
}else if(count($profile_note)<1){
    echo "<p class='p-2 bg-white border rounded text-center my-3'>No notes available</p>";
}
        ?>
        <div class="gallery d-flex flex-wrap gap-2 mb-4">
            <?php
               
foreach($profile_note as $note){
    $pins = getPins($note['id']);

    ?>
            <img src="assets/images/notes/<?=$note['note_img']?>" data-bs-toggle="modal" data-bs-target="#noteview<?=$note['id']?>" width="300px" class="rounded" />
       
            <div class="modal fade" id="noteview<?=$note['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body d-md-flex p-0">
                    <div class="col-md-8 col-sm-12">
                        <img src="assets/images/notes/<?=$note['note_img']?>" style="max-height:90vh" class="w-100 rounded-start">
                    </div>



                    <div class="col-md-4 col-sm-12 d-flex flex-column">
                        <div class="d-flex align-items-center p-2 <?=$note['note_text']?'':'border-bottom'?>">
                            <div><img src="assets/images/profile/<?=$profile['profile_pic']?>" alt="Profile Picture" height="50" width="50" class="rounded-circle border">
                            </div>
                            <div>&nbsp;&nbsp;&nbsp;</div>
                            <div class="d-flex flex-column justify-content-start">
                                <h6 style="margin: 0px;"><?=$profile['first_name']?> <?=$profile['last_name']?></h6>
                                <p style="margin:0px;" class="text-muted">@<?=$profile['username']?></p>
                            </div>

                            <div class="d-flex flex-column align-items-end flex-fill">
                <div class="" ></div>
                <div class="dropdown">
  <span class="<?=count($pins)<1?'disabled':''?>" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
  <?=count($pins)?> Pins
</span>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
  <?php
  foreach($pins as $pin){
      $lu = getUser($pin['user_id']);
      ?>
  <li><a class="dropdown-item" href="?u=<?=$lu['username']?>"><?=$lu['first_name'].' '.$lu['last_name']?> (@<?=$lu['username']?>)</a></li>

      <?php
  }
  ?> 
    
  </ul>
</div>
                <div style="font-size:small" class="text-muted">Published <?=show_time($note['created_at'])?> </div> 
                 
</div>
                        </div>
<div class="border-bottom p-2 <?=$note['note_text']?'':'d-none'?>"><?=$note['note_text']?> </div>

<div class="flex-fill align-self-stretch overflow-auto" id="feedback-section<?=$note['id']?>" style="height: 100px;">
  <?php
  $feedbacks = getFeedbacks($note['id']);
  if(count($feedbacks)<1){
      ?>
  <p class="p-3 text-center my-2 nce">No feedback available</p>
  <?php
  }
  foreach($feedbacks as $feedback){
      $cuser = getUser($feedback['user_id']);
      ?>
  <div class="d-flex align-items-center p-2">
      <div><img src="assets/images/profile/<?=$cuser['profile_pic']?>" alt="" height="40" width="40" class="rounded-circle border">
      </div>
      <div>&nbsp;&nbsp;&nbsp;</div>
      <div class="d-flex flex-column justify-content-start align-items-start">
          <h6 style="margin: 0px;"><a href="?u=<?=$cuser['username']?>" class="text-decoration-none text-muted">@<?=$cuser['username']?></a> - <?=$feedback['feedback']?></h6>
          <p style="margin:0px;" class="text-muted"><?=show_time($feedback['created_at'])?></p>
      </div>
  </div>
  <?php
  }
  ?>
</div>

<?php
if(checkFollowStatus($profile['id']) || $profile['id']==$user['id']){
  ?>
  <div class="input-group p-2 border-top">
      <input type="text" class="form-control rounded-0 border-0 feedback-input" placeholder="Provide feedback..."
          aria-label="Recipient's username" aria-describedby="button-addon2">
      <button class="btn btn-outline-primary rounded-0 border-0 add-feedback" data-cs="feedback-section<?=$note['id']?>" data-note-id="<?=$note['id']?>" type="button"
          id="button-addon2">Submit</button>
  </div>
  <?php
}else{
  ?>
  <div class="text-center p-2">
  To provide feedback, please follow this user.
  </div>
  <?php
}
?>

</div>

</div>

</div>
</div>
</div>
<?php
}
?>

<!-- Modal for follower list -->

<div class="modal fade" id="follower_list" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">Followers</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <?php
          foreach($profile['followers'] as $f){
              $fuser = getUser($f['follower_id']);
              $fbtn='';
              if(checkFollowStatus($f['follower_id'])){
                  $fbtn = '<button class="btn btn-sm btn-danger unfollowbtn" data-user-id='.$fuser['id'].' >Unfollow</button>';
              }else if($user['id']==$f['follower_id']){
                  $fbtn='';
              }else{
                  $fbtn = '<button class="btn btn-sm btn-primary followbtn" data-user-id='.$fuser['id'].' >Follow</button>';
              }
              ?>
  <div class="d-flex justify-content-between">
      <div class="d-flex align-items-center p-2">
          <div><img src="assets/images/profile/<?=$fuser['profile_pic']?>" alt="" height="40" width="40" class="rounded-circle border">
          </div>
          <div>&nbsp;&nbsp;</div>
          <div class="d-flex flex-column justify-content-center">
              <a href='?u=<?=$fuser['username']?>' class="text-decoration-none text-dark"><h6 style="margin: 0px;font-size: small;"><?=$fuser['first_name']?> <?=$fuser['last_name']?></h6></a>
              <p style="margin:0px;font-size:small" class="text-muted">@<?=$fuser['username']?></p>
          </div>
      </div>
      <div class="d-flex align-items-center">
        <?=$fbtn?>
      </div>
  </div>
          <?php
          }
          ?>
      </div>
  
  </div>
  </div>
</div>

<!-- Modal for following list -->
<div class="modal fade" id="following_list" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">Following</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <?php
          foreach($profile['following'] as $f){
              $fuser = getUser($f['user_id']);
              $fbtn='';
              if(checkFollowStatus($f['user_id'])){
                  $fbtn = '<button class="btn btn-sm btn-danger unfollowbtn" data-user-id='.$fuser['id'].' >Unfollow</button>';
              }else if($user['id']==$f['user_id']){
                  $fbtn='';
              }else{
                  $fbtn = '<button class="btn btn-sm btn-primary followbtn" data-user-id='.$fuser['id'].' >Follow</button>';
              }
              ?>
  <div class="d-flex justify-content-between">
      <div class="d-flex align-items-center p-2">
          <div><img src="assets/images/profile/<?=$fuser['profile_pic']?>" alt="" height="40" width="40" class="rounded-circle border">
          </div>
          <div>&nbsp;&nbsp;</div>
          <div class="d-flex flex-column justify-content-center">
              <a href='?u=<?=$fuser['username']?>' class="text-decoration-none text-dark"><h6 style="margin: 0px;font-size: small;"><?=$fuser['first_name']?> <?=$fuser['last_name']?></h6></a>
              <p style="margin:0px;font-size:small" class="text-muted">@<?=$fuser['username']?></p>
          </div>
      </div>
      <div class="d-flex align-items-center">
        <?=$fbtn?>
      </div>
  </div>
          <?php
          }
          ?>
      </div>
  
  </div>
  </div>
</div>