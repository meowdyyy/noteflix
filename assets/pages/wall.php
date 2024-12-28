<?php
global $user;
global $notes;
global $follow_suggestions;
?>
<div class="container col-md-10 col-sm-12 col-lg-9 rounded-0 d-flex justify-content-between">
    <div class="col-md-8 col-sm-12" style="max-width:93vw">

        <?php
        showError('note_img');
        if (count($notes) < 1) {
            echo "<p style='width:93vw' class='p-2 bg-white border rounded text-center my-3 col-12'>Expand your network by following others or create a new note to get started.</p>";
        }
        foreach ($notes as $note) {
            $pins = getPins($note['id']);
            $feedbacks = getFeedbacks($note['id']);
        ?>
            <div class="card mt-4">
                <div class="card-title d-flex justify-content-between align-items-center">

                    <div class="d-flex align-items-center p-2">
                        <img src="assets/images/profile/<?=$note['profile_pic']?>" alt="" height="30" width="30" class="rounded-circle border">&nbsp;&nbsp;<a href='?u=<?=$note['username']?>' class="text-decoration-none text-dark"><?=$note['first_name']?> <?=$note['last_name']?></a>
                    </div>
                    <div class="p-2">
                        <?php
                        if ($note['uid'] == $user['id']) {
                        ?>

                            <div class="dropdown">

                                <i class="bi bi-three-dots-vertical" id="option<?=$note['id']?>" data-bs-toggle="dropdown" aria-expanded="false"></i>

                                <ul class="dropdown-menu" aria-labelledby="option<?=$note['id']?>">
                                    <li><a class="dropdown-item" href="assets/php/actions.php?deletenote=<?=$note['id']?>"><i class="bi bi-trash-fill"></i> Remove Note</a></li>
                                </ul>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <img src="assets/images/notes/<?=$note['note_img']?>" loading=lazy class="" alt="...">
                <h4 style="font-size: x-larger" class="p-2 border-bottom d-flex">
                    <span>
                        <?php
                        if (checkPinStatus($note['id'])) {
                            $pin_btn_display = 'none';
                            $unpin_btn_display = '';
                        } else {
                            $pin_btn_display = '';
                            $unpin_btn_display = 'none';
                        }
                        ?>
                        <i class="bi bi-pin-angle-fill unpin_btn text-danger" style="display:<?=$unpin_btn_display?>" data-note-id='<?=$note['id']?>'></i>
                        <i class="bi bi-pin-angle pin_btn" style="display:<?=$pin_btn_display?>" data-note-id='<?=$note['id']?>'></i>
                    </span>
                    &nbsp;&nbsp;<i class="bi bi-chat-left d-flex align-items-center"><span class="p-1 mx-2 text-small" style="font-size:small" data-bs-toggle="modal" data-bs-target="#noteview<?=$note['id']?>"><?=count($feedbacks)?> feedbacks</span></i>
                </h4>
                <div>
                    <span class="p-1 mx-2" data-bs-toggle="modal" data-bs-target="#pins<?=$note['id']?>"><span id="pincount<?=$note['id']?>"><?=count($pins)?></span> pins</span>
                    <span style="font-size:small" class="text-muted">Published</span> <?=show_time($note['created_at'])?>
                </div>
                <?php
                if ($note['note_text']) {
                ?>
                    <div class="card-body">
                        <?=$note['note_text']?>
                    </div>
                <?php
                }
                ?>
                <div class="input-group p-2 <?=$note['note_text'] ? 'border-top' : ''?>">
                    <input type="text" class="form-control rounded-0 border-0 feedback-input" placeholder="Share your feedback..." aria-label="Recipient's username" aria-describedby="button-addon2">
                    <button class="btn btn-outline-primary rounded-0 border-0 add-feedback" data-page='wall' data-cs="feedback-section<?=$note['id']?>" data-note-id="<?=$note['id']?>" type="button" id="button-addon2">Submit</button>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

    <div class="col-lg-4 col-sm-0 overflow-hidden mt-4 p-sm-0 p-md-3">

        <div class="d-flex align-items-center p-2">
            <div><img src="assets/images/profile/<?=$user['profile_pic']?>" alt="" height="60" width="60" class="rounded-circle border"></div>
            <div>&nbsp;&nbsp;&nbsp;</div>
            <div class="d-flex flex-column justify-content-center">
                <a href='?u=<?=$user['username']?>' class="text-decoration-none text-dark"><h6 style="margin: 0px;"><?=$user['first_name']?> <?=$user['last_name']?></h6></a>
                <p style="margin:0px;" class="text-muted">@<?=$user['username']?></p>
            </div>
        </div>

        <div>
            <h6 class="text-muted p-2">Suggested Connections</h6>
            <?php
            foreach ($follow_suggestions as $suser) {
            ?>
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center p-2">
                        <div><img src="assets/images/profile/<?=$suser['profile_pic']?>" alt="" height="40" width="40" class="rounded-circle border"></div>
                        <div>&nbsp;&nbsp;</div>
                        <div class="d-flex flex-column justify-content-center">
                            <a href='?u=<?=$suser['username']?>' class="text-decoration-none text-dark"><h6 style="margin: 0px;font-size: small;"><?=$suser['first_name']?> <?=$suser['last_name']?></h6></a>
                            <p style="margin:0px;font-size:small" class="text-muted">@<?=$suser['username']?></p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-primary followbtn" data-user-id='<?=$suser['id']?>'>Follow</button>
                    </div>
                </div>
            <?php
            }

            if (count($follow_suggestions) < 1) {
                echo "<p class='p-2 bg-white border rounded text-center'>No connection suggestions available right now.</p>";
            }
            ?>
        </div>
    </div>
</div>