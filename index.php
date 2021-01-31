
<?php
session_start();
include('connection.php');
$password=' ';
$user='  ';
$rowcnt=0;
$error=array('username'=>'','password'=>'');
if(isset($_POST['submit'])){
        $user=htmlspecialchars($_POST['username']);
        $password=htmlspecialchars($_POST['password']);
       
  
        $sql=" exec ulogin '$user','$password'" 
        ;

            $stmt=sqlsrv_query($conn,$sql);
            if( $stmt === false ) {
              die( print_r( sqlsrv_errors(), true));
        }
        
          $rowcnt=sqlsrv_num_rows($stmt);
        

        echo $rowcnt;
          $log=sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

            if($rowcnt==0){
           $_SESSION["UID"] = $log['UID'];
        $_SESSION["Email"] = $log['Fname'];
        if(isset($_SESSION["Email"])){
              header("location:dashboard.php");
            

            }else{
                $error['password']='email or password incorrect';
                  echo $error['password'];
              
              }

//$sql='select';
 }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title> Login</title>
  <link rel="stylesheet" href="/css/style2.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

  </head>
  <body class="bg-info">
  <div class="container">
    <div>
      <div class="title">
        
        <h1  ><i class="text-dark">NEXUS SYSTEMS INVENTORY & POS</i></h1>
      </div>
     
              
        <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                      <div class="row">
                        <div class="col-lg-5 d-none d-lg-block bg-image"></div>
                          <div class="col-lg-7">
                            <div class="p-5">
                                      <form action="./index.php" method="post">
                                          <h2 class="text-center">Log in</h2>  
                                          <div class="form-group">
                                              <input type="email" class="form-control form-control-user" required  name="username"
                                                placeholder="Email Address">
                                                <div class="text-danger">
                            
                                                      <?php  echo $error['password'];?>
                                                </div>
                                          </div>   
                                                <div class="form-group">
                                                <input type="password" class="form-control form-control-user"
                                        required      name="password" placeholder="Password">
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit"  name="submit" value="submit" class="btn btn-primary  btn-user btn-block">Log in</button>
                                                    <hr>
                                
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.">Forgot Password?</a>
                            </div>
                                <a class="small" href="./registration.php">First time? Sign up!</a>
                            </div>
                            </div>
                          </div>
          
                        </div>
                      </div>
                </div>
        </div>

  </div>
  </body>
  </html>