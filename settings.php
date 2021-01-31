<?php
session_start();
if(!isset($_SESSION["Email"])){
    header("location:index.php");
  

  }
include('connection.php');
$uid=$_SESSION['UID'];



$sql = "SELECT * FROM users where uid='$uid' ";

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$query = sqlsrv_query( $conn, $sql , $params, $options );
if( $query === false ) {
    die( print_r( sqlsrv_errors(), true));
}
$info=Sqlsrv_fetch_array($query,SQLSRV_FETCH_ASSOC);
$_SESSION['img']=$info['img'];
// updating user information
$error=array('email'=> '', 'password'=>'','file'=>'');



$fname=$lname=$email='';


if(isset($_POST['uppasword'])){
   
    $rep=htmlspecialchars($_POST['npassword']);
         
           
            $sql="UPDATE users  set Password_='$rep' where Uid='$uid'";
          $p = sqlsrv_query($conn, $sql);
          if( $p === false ) {
            die( print_r( sqlsrv_errors(), true));
            
        }else{
            header("location:settings.php");
        }
     }



if (isset($_POST['update'])){

$email=htmlspecialchars( $_POST['email']);

$fname=htmlspecialchars($_POST['fname']);
$lname=htmlspecialchars($_POST['lname']);
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    if(filter_var($email,FILTER_VALIDATE_EMAIL)){ 
        $sql="SELECT * from Users where email='$email'";                                                                   
        $result=sqlsrv_query( $conn, $sql , $params, $options );
        if( $result === false ) {
            die( print_r( sqlsrv_errors(), true));
            
        }
        $row_cnt = sqlsrv_num_rows($result);
            if($row_cnt>1){
            
            $error['email']='email address already taken';

        }
           
        
    }else{
        $error['email']='must be a valid email adresss';
    }






if(array_filter($error)){

}else{
    

            $sql="UPDATE Users  set Fname='$fname',Lname='$lname',Email='$email' where Uid='$uid'";
          $in = sqlsrv_query($conn, $sql);
    if($in==true){
            header("location:settings.php");
        } else{
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
       
    }

        

}


if(isset($_POST['submit'])){
    $fileinfo = @getimagesize($_FILES["file"]["tmp_name"]);
    $width = $fileinfo[0];
    $height = $fileinfo[1];
    
    $allowed_image_extension = array(
        "png",
        "jpg",
        "jpeg"
    );
    
   
    $file_extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
    
    
    if (! file_exists($_FILES["file"]["tmp_name"])) {
       $error['file']="error empty file ";
    }    
    else if (! in_array($file_extension, $allowed_image_extension)) {
       
            
            $error['file']="Upload valiid images. Only PNG and JPEG are allowed.";
        
        
    }    // Validate image file size
    else if (($_FILES["file"]["size"] > 2000000)) {
       
            
            $error['file']= "Image size exceeds 2MB";
        
    }   
  
     else {
        if (  move_uploaded_file($_FILES['file']['tmp_name'],"imgs/".$_FILES['file']['name'])) {

            $q = sqlsrv_query($conn,"UPDATE Users SET img = '".$_FILES['file']['name']."' WHERE uid = '$uid'");
            if($q==false){
                die( print_r( sqlsrv_errors(), true));
            }else{
                header("location:settings.php");
            }
              
            
            
        } else {
            $error['file']="error file not uploaded";
        }
    }




  
   
    
}






// Close connection
sqlsrv_close($conn);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    
    <link rel="stylesheet" href="./css/style2.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <script src="./js/index.js"></script>



    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>

</head>
<body >
   <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
          
          <div class="sidebar-header ">
          
          <span  class="sidebarCollapse" aria-hidden="true">×</span>   
                        
                  
               <div id="profile"><?php
                if($_SESSION['img']=="")
                {echo'<img class=" img-fluid  rounded-circle " src="./imgs/Chef.png" />';
                }else{
                   echo" <img class=' img-fluid  rounded-circle ' src='./imgs/".$_SESSION['img']."' />";
                }



                ?>
                      
                           
                                <h5 > Welcome <?php echo $_SESSION["Email"]; ?></h5>
                </div>
                 
            
          </div>
    
          <ul class="list-unstyled components">
              

              <li>
                  <a href="./dashboard.php"> <i class="fa fa-home" aria-hidden="true"></i>  Dashboard </a>
              </li>
              <li>
                  <a href="inventory.php"><i class="fa fa-clipboard" aria-hidden="true"></i> Inventory</a>
              </li>
              <li>
                  <a href="./pos.php" ><i class="fa fa-shopping-bag" aria-hidden="true"></i>  POS</a>
              </li>
             
              <li>
                  <a href="./suppliers.php"> <i class="fas fa-people-carry    "></i> Suppliers</a>

              </li>
              <li>
                  <a href="./settings.php "> <i class="fa fa-user" aria-hidden="true"></i> Account settings</a>

              </li>


              <li> <a href="./logout.php"> <i class="fas fa-sign-out-alt    "></i> logout</a> </li>
          </ul> 
    
      
      </nav>

        
        <div id="content">

                    <nav class="navbar text-center navbar-expand-lg navbar-light bg-light">
                        <div class="row">
                        <button type="button"  class="btn btn-info sidebarCollapse ">
                                                <i class="fas fa-align-left"></i>
                                                
                                            </button>
                                    <a class="navbar-brand" href="./index.php"><h5><i >NEXUS SYSTEMS</i></h1></a>
                        </div> 
                    </nav>

            <div class="container-fluid">
                <div class="row">
                    <h1>Update Account Information</h1>
                </div>
                <br>
                    <form action="settings.php" method="post">
                        
                                    <div class="row">
                                    <label for="fname">Update first Name</label>
                                        <input type="text" name="fname" class="form-control form-control-user"   required value="<?php echo $info['Fname']?> " > 
                                    </div>
                                        
                                    <div class="row">
                                    <label for="lname"> Enter Last Name</label>
                                        <input type="text" name="lname" class="form-control form-control-user" required value="<?php echo $info['Lname']?> " > 
                                    </div>

                       
                        
                                    <div class="row">
                                    <label for="email"> Update  Email address </label>  
                                    <input type="email" class="form-control form-control-user" required  name="email"placeholder="Email Address"value="<?php echo $info['Email']?> ">        
                                    </div>
                                    <br>
                                    <div class="row">
                                        <button name="update" value="update" class="btn btn-info" > Update profiile</button>
                                    </div>

                     </form>  
                                        <br>
                                       
                    <form action="settings.php" method="post">
                                    <div class="row">
                                        <h5>Update Password</h5>
                                    </div>
                            
                                    <div class="row">
                                   <label for="password"> Enter previous password</label>
                                    <input type="password" class="form-control "
                                        required      name="password" placeholder="Password"> 
                                    </div>
                                        
                                    <div class="row">
                                    <label for="npassword"> Enter new password</label>  
                                   <input type="password" class="form-control form-control-user"
                                        required      name="npassword" placeholder=" new Password"> 

                                       <?php $error['password']  ?>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <button name="uppasword" value="uppassword" class="btn btn-info"> Update Password</button>
                                    </div>
                    

                    </form>
                    <br>
                    <br>
                    <form action="./settings.php" method="post" enctype="multipart/form-data" >
                    <div class="row">
                    
                        <label for="exampleFormControlFile1"> Insert profile picture</label>
                        <input type="file" class="form-control-file" name="file" id="profilepic">
                        <?php echo $error['file'];?>
                      </div>
                      <br>
                      <div class="row">
                                        <button name="submit" value="submit" class="btn btn-info"> submit profile picture</button>
                                    </div>
                    </form>  
                     
                   

            </div>
                
                
                  
          
                

    
</body>
</html>