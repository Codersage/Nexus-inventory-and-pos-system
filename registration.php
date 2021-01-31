

<?php
include('connection.php');

$error=array('email'=> '', 'password'=>'');
$email="";
$fname="";
$lname="";
$telephone;
$deptnum;
$userlevel;
//echo $email;

$fname=$lname=$telephone=$email='';


If(isset($_POST['submit'])){

$email=htmlspecialchars( $_POST['email']);
$password=htmlspecialchars( $_POST['password']);
$rep=htmlspecialchars($_POST['repeatPassword']);
$fname=htmlspecialchars($_POST['fname']);
$lname=htmlspecialchars($_POST['lname']);
//$telephone=htmlspecialchars($_POST['telephone']);
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );





    if(filter_var($email,FILTER_VALIDATE_EMAIL)){ 
        $sql="SELECT * from users where email='$email'";                                                                   
        $result=sqlsrv_query( $conn, $sql , $params, $options );
        if( $result === false ) {
            die( print_r( sqlsrv_errors(), true));
            
        }
        $row_cnt = sqlsrv_num_rows($result);
            if($row_cnt>=1){
            
            $error['email']='email address already taken';

        }
           
        
    }else{
        $error['email']='must be a valid email adresss';
    }




if($password!==$rep){
   $error['password']='passwords must match!';
}
/*
if(strlen($telephone)!==10){
        $error['telephone']="enter 10 digit number";
    } */


if(array_filter($error)){

}else{
    
          

            $sql="exec register '$fname','$lname','$email','$password'";
          $in = sqlsrv_query($conn, $sql);
    if($in==true){
            header("location:index.php");
        } else{
           
                die( print_r( sqlsrv_errors(), true));
                
            
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
       
    }

        
// Close connection
}



sqlsrv_close($conn);


?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> Register</title>
    <link rel="stylesheet" href="./css/style2.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
   
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">



</head>

<body class="bg-info ">

    <div class="container">
    <div class="title">
    <h1  > 
        <i class="text-dark">NEXUS SYSTEMS INVENTORY & POS</i></h1>
      </div>
     
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
               
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" method="POST" action="./registration.php" >
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" required name="fname"
                                            placeholder="First Name" value="<?php echo $fname?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" required  name="lname"
                                            placeholder="Last Name"value="<?php echo $lname?>" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" required  name="email"
                                        placeholder="Email Address"value="<?php echo $email?> ">
                                        <?php      echo $error['email'];         ?>
                                </div>
                               
                               
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                        required      name="password" placeholder="Password">
                                    </div>

                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            name ="repeatPassword" required  placeholder="Repeat Password">
                                            <?php echo $error['password']?>
                                    </div>
                                </div>
                                <div ><input type="submit"   name="submit" value="submit"  class="btn btn-primary btn-user btn-block"    >
                                
                                    Register Account
                                </div>
                                
                                    
                                <hr>
                               
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.">Forgot Password?</a>
                            </div>
                                <a class="small" href="./index.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>