<?php
require_once 'functions.php';
require_once 'send_code.php';


if(isset($_GET['test'])){
 
}

if(isset($_GET['block'])){
    $user_id = $_GET['block'];
    $user = $_GET['username']; 
      if(blockUser($user_id)){
          header("location:../../?u=$user");
      }else{
          echo "An error occurred while attempting to block the user.";
      }
}

if(isset($_GET['deletenote'])){
    $note_id = $_GET['deletenote'];
      if(deleteNote($note_id)){
          header("location:{$_SERVER['HTTP_REFERER']}");
      }else{
          echo "An error occurred while attempting to delete the note.";
      }
}

// Handling user signup process
if(isset($_GET['signup'])){
$response=validateSignupForm($_POST);
if($response['status']){
    if(createUser($_POST)){
    header('location:../../?login&newuser');
    }else{
        echo "<script>alert('An error occurred during the signup process. Please try again.')</script>";
    }
}else{
    $_SESSION['error']=$response;
    $_SESSION['formdata']=$_POST;
    header("location:../../?signup");
}
}

// Handling user login process
if(isset($_GET['login'])){
    $response=validateLoginForm($_POST);
    if($response['status']){
     $_SESSION['Auth'] = true;
     $_SESSION['userdata'] = $response['user'];

     if($response['user']['ac_status']==0){
         $_SESSION['code']=$code = rand(111111,999999);
         sendCode($response['user']['email'],'Email Verification Required',$code);
     }

     header("location:../../");
    }else{
        $_SESSION['error']=$response;
        $_SESSION['formdata']=$_POST;
        header("location:../../?login");
    }
}

if(isset($_GET['resend_code'])){
    $_SESSION['code']=$code = rand(111111,999999);
    sendCode($_SESSION['userdata']['email'],'Email Verification Code Resent',$code);
    header('location:../../?resended');
}

if(isset($_GET['verify_email'])){
   $user_code = $_POST['code'];
   $code = $_SESSION['code'];
   if($code==$user_code){
       if(verifyEmail($_SESSION['userdata']['email'])){
           header('location:../../');
       }else{
           echo "An error occurred during email verification.";
       }
   }else{
       $response['msg']='The verification code entered is incorrect.';
       if(!$_POST['code']){
           $response['msg']='Please enter the 6-digit verification code.';
       }
       $response['field']='email_verify';
       $_SESSION['error']=$response;
       header('location:../../');
   }
}

if(isset($_GET['forgotpassword'])){
    if(!$_POST['email']){
        $response['msg']="Please enter your email address.";
        $response['field']='email';
        $_SESSION['error']=$response;
        header('location:../../?forgotpassword');
    }elseif(!isEmailRegistered($_POST['email'])){
        $response['msg']="The email address is not registered.";
        $response['field']='email';
        $_SESSION['error']=$response;
        header('location:../../?forgotpassword');
    }else{
        $_SESSION['forgot_email']=$_POST['email'];
        $_SESSION['forgot_code']=$code = rand(111111,999999);
        sendCode($_POST['email'],'Password Reset Verification Code',$code);
        header('location:../../?forgotpassword&resended');
    }
}

// Logging out the user
if(isset($_GET['logout'])){
    session_destroy();
    header('location:../../');
}

// Verifying the code entered for password reset
if(isset($_GET['verifycode'])){
    $user_code = $_POST['code'];
    $code = $_SESSION['forgot_code'];
    if($code==$user_code){
        $_SESSION['auth_temp']=true;
        header('location:../../?forgotpassword');
    }else{
        $response['msg']='The verification code entered is incorrect.';
        if(!$_POST['code']){
            $response['msg']='Please enter the 6-digit verification code.';
        }
        $response['field']='email_verify';
        $_SESSION['error']=$response;
        header('location:../../?forgotpassword');
    }
}

// Changing the user password after verification
if(isset($_GET['changepassword'])){
    if(!$_POST['password']){
        $response['msg']="Please enter your new password.";
        $response['field']='password';
        $_SESSION['error']=$response;
        header('location:../../?forgotpassword');
    }else{
        resetPassword($_SESSION['forgot_email'],$_POST['password']);
        session_destroy();
        header('location:../../?reseted');
    }
}

if(isset($_GET['updateprofile'])){
    $response=validateUpdateForm($_POST,$_FILES['profile_pic']);
    if($response['status']){
        if(updateProfile($_POST,$_FILES['profile_pic'])){
            header("location:../../?editprofile&success");
        }else{
            echo "An error occurred while updating your profile.";
        }
    }else{
        $_SESSION['error']=$response;
        header("location:../../?editprofile");
    }
}

// Handling note addition process
if(isset($_GET['addnote'])){
   $response = validateNoteImage($_FILES['note_img']);
   if($response['status']){
       if(createNote($_POST,$_FILES['note_img'])){
           header("location:../../?new_note_added");
       }else{
           echo "An error occurred while adding the note.";
       }
   }else{
       $_SESSION['error']=$response;
       header("location:../../");
   }
}