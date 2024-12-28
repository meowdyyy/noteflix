<?php
require_once 'config.php';
$db = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die("Unable to connect to the database.");

// Function to display a specific page
function showPage($page,$data=""){
include("assets/pages/$page.php");
}

// Function to retrieve active chat user IDs
function getActiveChatUserIds(){
    global $db;
    $current_user_id = $_SESSION['userdata']['id'];
    $query = "SELECT from_user_id,to_user_id FROM messages WHERE to_user_id=$current_user_id || from_user_id=$current_user_id ORDER BY id DESC";
    $run = mysqli_query($db,$query);
    $data =  mysqli_fetch_all($run,true);
    $ids=array();
    foreach($data as $ch){
    if($ch['from_user_id']!=$current_user_id && !in_array($ch['from_user_id'],$ids)){
       $ids[]=$ch['from_user_id'];
    }

    if($ch['to_user_id']!=$current_user_id && !in_array($ch['to_user_id'],$ids)){
        $ids[]=$ch['to_user_id'];
     }

    }

    return $ids;
}

function getMessages($user_id){
    global $db;
    $current_user_id = $_SESSION['userdata']['id'];
    $query = "SELECT * FROM messages WHERE (to_user_id=$current_user_id && from_user_id=$user_id) || (from_user_id=$current_user_id && to_user_id=$user_id) ORDER BY id DESC";
    $run = mysqli_query($db,$query);
    return  mysqli_fetch_all($run,true);
}

function sendMessage($user_id,$msg){
    global $db;
    $current_user_id = $_SESSION['userdata']['id'];
    $query = "INSERT INTO messages (from_user_id,to_user_id,msg) VALUES($current_user_id,$user_id,'$msg')";
    return mysqli_query($db,$query);
}

function newMsgCount(){
global $db;
$current_user_id = $_SESSION['userdata']['id'];
$query="SELECT COUNT(*) as row FROM messages WHERE to_user_id=$current_user_id && read_status=0";
$run=mysqli_query($db,$query);
return mysqli_fetch_assoc($run)['row'];
}

function updateMessageReadStatus($user_id){
    $cu_user_id = $_SESSION['userdata']['id'];
    global $db;
    $query="UPDATE messages SET read_status=1 WHERE to_user_id=$cu_user_id && from_user_id=$user_id";
    return mysqli_query($db,$query);
}

function gettime($date){
    return date('H:i - (F jS, Y )', strtotime($date));
}

function getAllMessages(){
    $active_chat_ids = getActiveChatUserIds();
    $conversation=array();
    foreach($active_chat_ids as $index=>$id){
        $conversation[$index]['user_id'] = $id;
        $conversation[$index]['messages'] = getMessages($id);
    }
    return $conversation;
}

// Function to follow a user
function followUser($user_id){
    global $db;
    $cu = getUser($_SESSION['userdata']['id']);
    $current_user=$_SESSION['userdata']['id'];
    $query="INSERT INTO follow_list(follower_id,user_id) VALUES($current_user,$user_id)";
  
    createNotification($cu['id'],$user_id,"started following you !");
    return mysqli_query($db,$query);
    
}


// Function to block a user
function blockUser($blocked_user_id){
    global $db;
    $cu = getUser($_SESSION['userdata']['id']);
    $current_user=$_SESSION['userdata']['id'];
    $query="INSERT INTO block_list(user_id,blocked_user_id) VALUES($current_user,$blocked_user_id)";
  
    createNotification($cu['id'],$blocked_user_id,"blocked you");
    $query2="DELETE FROM follow_list WHERE follower_id=$current_user && user_id=$blocked_user_id";
    mysqli_query($db,$query2);
    $query3="DELETE FROM follow_list WHERE follower_id=$blocked_user_id && user_id=$current_user";
    mysqli_query($db,$query3);

    return mysqli_query($db,$query);
    
}

// Function to unblock a user
function unblockUser($user_id){
    global $db;
    $current_user=$_SESSION['userdata']['id'];
    $query="DELETE FROM block_list WHERE user_id=$current_user && blocked_user_id=$user_id";
    createNotification($current_user,$user_id,"Unblocked you !");
    return mysqli_query($db,$query);   
}

// Function to check the pin status of a note
function checkPinStatus($note_id){
    global $db;
    $current_user = $_SESSION['userdata']['id'];
    $query="SELECT count(*) as row FROM pins WHERE user_id=$current_user && note_id=$note_id";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_assoc($run)['row'];
}

// Function to pin a note
function pin($note_id){
    global $db;
    $current_user=$_SESSION['userdata']['id'];
    $query="INSERT INTO pins(note_id,user_id) VALUES($note_id,$current_user)";
   $noteer_id = getNoteerId($note_id);
   
   if($noteer_id!=$current_user){
    createNotification($current_user,$noteer_id,"pinned your note !",$note_id);
   }

    return mysqli_query($db,$query);
}

// Function to add feedback for a note
function addFeedback($note_id,$feedback){
    global $db;
 $feedback = mysqli_real_escape_string($db,$feedback);

    $current_user=$_SESSION['userdata']['id'];
    $query="INSERT INTO feedbacks(user_id,note_id,feedback) VALUES($current_user,$note_id,'$feedback')";
    $noteer_id = getNoteerId($note_id);

    if($noteer_id!=$current_user){
        createNotification($current_user,$noteer_id,"gave a feedback on your note",$note_id);
    }

    return mysqli_query($db,$query);
}

// Function to create a notification
function createNotification($from_user_id,$to_user_id,$msg,$note_id=0){
    global $db;
    $query="INSERT INTO notifications(from_user_id,to_user_id,message,note_id) VALUES($from_user_id,$to_user_id,'$msg',$note_id)";
    mysqli_query($db,$query);    
}

// Function to get all feedback for a note
function getFeedbacks($note_id){
    global $db;
    $query="SELECT * FROM feedbacks WHERE note_id=$note_id ORDER BY id DESC";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
}

// Function to retrieve notifications
function getNotifications(){
  $cu_user_id = $_SESSION['userdata']['id'];

    global $db;
    $query="SELECT * FROM notifications WHERE to_user_id=$cu_user_id ORDER BY id DESC";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
}

// Function to get the count of unread notifications
function getUnreadNotificationsCount(){
    $cu_user_id = $_SESSION['userdata']['id'];
  
      global $db;
      $query="SELECT count(*) as row FROM notifications WHERE to_user_id=$cu_user_id && read_status=0 ORDER BY id DESC";
      $run = mysqli_query($db,$query);
      return mysqli_fetch_assoc($run)['row'];
}

// Function to display the time of a notification
function show_time($time){
    return '<time style="font-size:small" class="timeago text-muted text-small" datetime="'.$time.'"></time>';
}

// Function to mark notifications as read
function setNotificationStatusAsRead(){
       $cu_user_id = $_SESSION['userdata']['id'];
      global $db;
      $query="UPDATE notifications SET read_status=1 WHERE to_user_id=$cu_user_id";
      return mysqli_query($db,$query);
}

// Function to get pins for a note
function getPins($note_id){
    global $db;
    $query="SELECT * FROM pins WHERE note_id=$note_id";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
}

// Function to unpin a note
function unpin($note_id){
    global $db;
    $current_user=$_SESSION['userdata']['id'];
    $query="DELETE FROM pins WHERE user_id=$current_user && note_id=$note_id";
    
    $noteer_id = getNoteerId($note_id);
    if($noteer_id!=$current_user){
        createNotification($current_user,$noteer_id,"unpinned your note !",$note_id);
    }
  
    return mysqli_query($db,$query);
}

// Function to unfollow a user
function unfollowUser($user_id){
    global $db;
    $current_user=$_SESSION['userdata']['id'];
    $query="DELETE FROM follow_list WHERE follower_id=$current_user && user_id=$user_id";

    createNotification($current_user,$user_id,"Unfollowed you !");
    return mysqli_query($db,$query);
}
// Function to display error messages
function showError($field){
    if(isset($_SESSION['error'])){
        $error = $_SESSION['error'];
        if(isset($error['field']) && $field == $error['field']){
           ?>
<div class="alert alert-danger my-2" role="alert">
  <?=$error['msg']?>
</div>
           <?php
        }
    }
}


// Function to display previously submitted form data
function showFormData($field){
    if(isset($_SESSION['formdata'])){
        $formdata = $_SESSION['formdata'];
        return $formdata[$field];
    }
}


// Function to check if the email is already registered
function isEmailRegistered($email){
    global $db;
    $query = "SELECT count(*) as row FROM users WHERE email='$email'";
    $run = mysqli_query($db, $query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

// Function to check if the username is already registered
function isUsernameRegistered($username){
    global $db;
    $query = "SELECT count(*) as row FROM users WHERE username='$username'";
    $run = mysqli_query($db, $query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

// Function to check if the username is already registered by another user
function isUsernameRegisteredByOther($username){
    global $db;
    $user_id = $_SESSION['userdata']['id'];
    $query = "SELECT count(*) as row FROM users WHERE username='$username' && id != $user_id";
    $run = mysqli_query($db, $query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

// Function to validate the signup form
function validateSignupForm($form_data){
    $response = array();
    $response['status'] = true;

    if(!$form_data['password']){
        $response['msg'] = "Password is required";
        $response['status'] = false;
        $response['field'] = 'password';
    }

    if(!$form_data['username']){
        $response['msg'] = "Username is required";
        $response['status'] = false;
        $response['field'] = 'username';
    }

    if(!$form_data['email']){
        $response['msg'] = "Email is required";
        $response['status'] = false;
        $response['field'] = 'email';
    }

    if(!$form_data['last_name']){
        $response['msg'] = "Last name is required";
        $response['status'] = false;
        $response['field'] = 'last_name';
    }

    if(!$form_data['first_name']){
        $response['msg'] = "First name is required";
        $response['status'] = false;
        $response['field'] = 'first_name';
    }

    if(isEmailRegistered($form_data['email'])){
        $response['msg'] = "This email is already registered";
        $response['status'] = false;
        $response['field'] = 'email';
    }

    if(isUsernameRegistered($form_data['username'])){
        $response['msg'] = "This username is already taken";
        $response['status'] = false;
        $response['field'] = 'username';
    }

    return $response;
}

// Function to validate the login form
function validateLoginForm($form_data){
    $response = array();
    $response['status'] = true;
    $blank = false;

    if(!$form_data['password']){
        $response['msg'] = "Password is required";
        $response['status'] = false;
        $response['field'] = 'password';
        $blank = true;
    }

    if(!$form_data['username_email']){
        $response['msg'] = "Username or Email is required";
        $response['status'] = false;
        $response['field'] = 'username_email';
        $blank = true;
    }

    if(!$blank && !checkUser($form_data)['status']){
        $response['msg'] = "Incorrect username or password";
        $response['status'] = false;
        $response['field'] = 'checkuser';
    } else {
        $response['user'] = checkUser($form_data)['user'];
    }

    return $response;
}

// Function to check if the user exists
function checkUser($login_data){
    global $db;
    $username_email = $login_data['username_email'];
    $password = md5($login_data['password']);

    $query = "SELECT * FROM users WHERE (email='$username_email' || username='$username_email') && password='$password'";
    $run = mysqli_query($db, $query);
    $data['user'] = mysqli_fetch_assoc($run) ?? array();
    if(count($data['user']) > 0){
        $data['status'] = true;
    } else {
        $data['status'] = false;
    }

    return $data;
}

// Function to get user data by user ID
function getUser($user_id){
    global $db;
    $query = "SELECT * FROM users WHERE id=$user_id";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run);
}

// Function to filter follow suggestions with ranking based on pin counts
function filterFollowSuggestion(){
    $list = getFollowSuggestions(); // Fetch suggestions with pin counts
    $filter_list = array();

    // Sort the list by pin count in descending order
    usort($list, function($a, $b) {
        return $b['pin_count'] - $a['pin_count'];
    });

    foreach($list as $user){
        if(!checkFollowStatus($user['id']) && !checkBS($user['id']) && count($filter_list) < 5){
            $filter_list[] = $user;
        }
    }
    return $filter_list;
}
// Function to check if the user is followed by the current user
function checkFollowStatus($user_id){
    global $db;
    $current_user = $_SESSION['userdata']['id'];
    $query = "SELECT count(*) as row FROM follow_list WHERE follower_id=$current_user && user_id=$user_id";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}

// Function to check if the user is blocked by the current user
function checkBlockStatus($current_user, $user_id){
    global $db;

    $query = "SELECT count(*) as row FROM block_list WHERE user_id=$current_user && blocked_user_id=$user_id";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}

function checkBS($user_id){
    global $db;
    $current_user = $_SESSION['userdata']['id'];
    $query = "SELECT count(*) as row FROM block_list WHERE (user_id=$current_user && blocked_user_id=$user_id) || (user_id=$user_id && blocked_user_id=$current_user)";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}

// Function to get follow suggestions for users with the number of pins they received
function getFollowSuggestions(){
    global $db;

    $current_user = $_SESSION['userdata']['id'];
    
    // Query to get the users and their pin counts based on the notes they created
    $query = "
        SELECT users.*, 
               (SELECT COUNT(*) 
                FROM pins 
                WHERE pins.note_id IN 
                    (SELECT id FROM notes WHERE notes.user_id = users.id)
               ) AS pin_count
        FROM users
        WHERE users.id != $current_user
    ";
    
    $run = mysqli_query($db, $query);
    return mysqli_fetch_all($run, true); // Fetch as associative array
}

// Function to get followers count
function getFollowers($user_id){
    global $db;
    $query = "SELECT * FROM follow_list WHERE user_id=$user_id";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_all($run, true);
}

// Function to get following count
function getFollowing($user_id){
    global $db;
    $query = "SELECT * FROM follow_list WHERE follower_id=$user_id";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_all($run, true);
}

// Function to get notes by user ID
function getNoteById($user_id){
    global $db;
    $query = "SELECT * FROM notes WHERE user_id=$user_id ORDER BY id DESC";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_all($run, true);
}

// Function to get the user ID who created the note
function getNoteerId($note_id){
    global $db;
    $query = "SELECT user_id FROM notes WHERE id=$note_id";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['user_id'];
}

// Function to search users by keyword
function searchUser($keyword){
    global $db;
    $query = "SELECT * FROM users WHERE username LIKE '%" . $keyword . "%' || (first_name LIKE '%" . $keyword . "%' || last_name LIKE '%" . $keyword . "%') LIMIT 5";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_all($run, true);
}

// Function to get user data by username
function getUserByUsername($username){
    global $db;
    $query = "SELECT * FROM users WHERE username='$username'";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run);
}

// Function to get all notes
function getNote(){
    global $db;
    $query = "SELECT users.id as uid, notes.id, notes.user_id, notes.note_img, notes.note_text, notes.created_at, users.first_name, users.last_name, users.username, users.profile_pic FROM notes JOIN users ON users.id=notes.user_id ORDER BY id DESC";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_all($run, true);
}

// Function to delete a note
function deleteNote($note_id){
    global $db;
    $user_id = $_SESSION['userdata']['id'];
    $delpin = "DELETE FROM pins WHERE note_id=$note_id && user_id=$user_id";
    mysqli_query($db, $delpin);
    $delcom = "DELETE FROM feedbacks WHERE note_id=$note_id && user_id=$user_id";
    mysqli_query($db, $delcom);
    $not = "UPDATE notifications SET read_status=2 WHERE note_id=$note_id && to_user_id=$user_id";
    mysqli_query($db, $not);

    $query = "DELETE FROM notes WHERE id=$note_id";
    return mysqli_query($db, $query);
}

// Function to filter notes based on follow status
function filterNotes(){
    $list = getNote();
    $filter_list = array();
    foreach($list as $note){
        if(checkFollowStatus($note['user_id']) || $note['user_id'] == $_SESSION['userdata']['id']){
            $filter_list[] = $note;
        }
    }

    return $filter_list;
}
// Function for creating a new user
function createUser($data){
    global $db;
    $first_name = mysqli_real_escape_string($db,$data['first_name']);
    $last_name = mysqli_real_escape_string($db,$data['last_name']);
    $gender = $data['gender'];
    $email = mysqli_real_escape_string($db,$data['email']);
    $username = mysqli_real_escape_string($db,$data['username']);
    $password = mysqli_real_escape_string($db,$data['password']);
    $password = md5($password);

    $query = "INSERT INTO users(first_name,last_name,gender,email,username,password) ";
    $query.="VALUES ('$first_name','$last_name',$gender,'$email','$username','$password')"; 
    return mysqli_query($db,$query);
}

// Function to verify email
function verifyEmail($email){
    global $db;
    $query="UPDATE users SET ac_status=1 WHERE email='$email'";
    return mysqli_query($db,$query);
}

// Function to reset the user's password
function resetPassword($email,$password){
    global $db;
    $password=md5($password);
    $query="UPDATE users SET password='$password' WHERE email='$email'";
    return mysqli_query($db,$query);
}

// Function for validating update form
function validateUpdateForm($form_data,$image_data){
    $response=array();
    $response['status']=true;

    // Ensure username is provided
    if(!$form_data['username']){
        $response['msg']="Username is not provided";
        $response['status']=false;
        $response['field']='username';
    }
    
    // Ensure last name is provided
    if(!$form_data['last_name']){
        $response['msg']="Last name is not provided";
        $response['status']=false;
        $response['field']='last_name';
    }

    // Ensure first name is provided
    if(!$form_data['first_name']){
        $response['msg']="First name is not provided";
        $response['status']=false;
        $response['field']='first_name';
    }

    // Check if the username is already registered by another user
    if(isUsernameRegisteredByOther($form_data['username'])){
        $response['msg']=$form_data['username']." is already taken";
        $response['status']=false;
        $response['field']='username';
    }

    // Validate image type and size if provided
    if($image_data['name']){
        $image = basename($image_data['name']);
        $type = strtolower(pathinfo($image,PATHINFO_EXTENSION));
        $size = $image_data['size']/1000;

        // Only allow jpg, jpeg, png images
        if($type!='jpg' && $type!='jpeg' && $type!='png'){
            $response['msg']="Only jpg, jpeg, and png images are allowed";
            $response['status']=false;
            $response['field']='profile_pic';
        }

        // Ensure image size is less than 1 MB
        if($size>1000){
            $response['msg']="Please upload an image smaller than 1 MB";
            $response['status']=false;
            $response['field']='profile_pic';
        }
    }

    return $response;
}

// Function to update user profile
function updateProfile($data,$imagedata){
    global $db;
    $first_name = mysqli_real_escape_string($db,$data['first_name']);
    $last_name = mysqli_real_escape_string($db,$data['last_name']);
    $username = mysqli_real_escape_string($db,$data['username']);
    $password = mysqli_real_escape_string($db,$data['password']);

    if(!$data['password']){
        $password = $_SESSION['userdata']['password'];
    }else{
        $password = md5($password);
        $_SESSION['userdata']['password']=$password;
    }

    $profile_pic="";
    if($imagedata['name']){
        $image_name = time().basename($imagedata['name']);
        $image_dir="../images/profile/$image_name";
        move_uploaded_file($imagedata['tmp_name'],$image_dir);
        $profile_pic=", profile_pic='$image_name'";
    }

    $query = "UPDATE users SET first_name = '$first_name', last_name='$last_name',username='$username',password='$password' $profile_pic WHERE id=".$_SESSION['userdata']['id'];
    return mysqli_query($db,$query);
}

// Function for validating the add note form
function validateNoteImage($image_data){
    $response=array();
    $response['status']=true;

    // Ensure image is uploaded
    if(!$image_data['name']){
        $response['msg']="Only handwritten notes are accepted. Please upload one.";
        $response['status']=false;
        $response['field']='note_img';
    }

    // Validate image type and size
    if($image_data['name']){
        $image = basename($image_data['name']);
        $type = strtolower(pathinfo($image,PATHINFO_EXTENSION));
        $size = $image_data['size']/1000;

        // Only allow jpg, jpeg, png images
        if($type!='jpg' && $type!='jpeg' && $type!='png'){
            $response['msg']="Only jpg, jpeg, and png images are allowed";
            $response['status']=false;
            $response['field']='note_img';
        }

        // Ensure image size is less than 2 MB
        if($size>2000){
            $response['msg']="Please upload an image smaller than 2 MB";
            $response['status']=false;
            $response['field']='note_img';
        }
    }

    return $response;
}

// Function to create a new note
function createNote($text,$image){
    global $db;
    $note_text = mysqli_real_escape_string($db,$text['note_text']);
    $user_id = $_SESSION['userdata']['id'];

    $image_name = time().basename($image['name']);
    $image_dir="../images/notes/$image_name";
    move_uploaded_file($image['tmp_name'],$image_dir);

    $query = "INSERT INTO notes(user_id,note_text,note_img)";
    $query.="VALUES ($user_id,'$note_text','$image_name')"; 
    return mysqli_query($db,$query);
}

?>