
<?php
session_start();
if(!isset($_SESSION["Email"])){
  header("location:index.php");


}
$uid=$_SESSION['UID'];
require_once('./connection.php');
$sql = "SELECT * FROM users where uid='$uid' ";

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$query = sqlsrv_query( $conn, $sql , $params, $options );
if( $query === false ) {
    die( print_r( sqlsrv_errors(), true));
}
$info=Sqlsrv_fetch_array($query,SQLSRV_FETCH_ASSOC);
$_SESSION['img']=$info['img'];


//getting sales data from db
$sql="select * from dtsl('$uid')";
$result=sqlsrv_query($conn,$sql);
$row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
if($row['tday']==null){
  $d=0;
}else{
  $d=$row['tday'];
} 

if($row['tweek'] ==null){
  $w=0;
}else{
  $w=$row['tweek'];
} 

 if
($row['tmonth']==null){
$m=0;
} else{
  $m=$row['tmonth'];
}

if
($row['tyear']==null){
$y=0;
}else{
  $y=$row['tyear'];
}  

 
sqlsrv_close($conn);



?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>

  <link rel="stylesheet" href="./css/style2.css">
  <link rel="stylesheet" href="./index_style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
  <script src="./js/index.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<style>
  
  #sidebarCollapse {display:none; }

  @media (max-width: 768px){
  
    .navbar-brand{
      font-size: small;
    }

    #sidebarCollapse{
      margin-top: 50px;
      margin-right:290px ;
      display: inline-block;}
      .img-circle1{
      display: none;
      }
    
  };
</style>


  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
</head>
<body>
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


  
  <div class="container-fluid  ">

  <nav class="navbar text-center navbar-expand-lg navbar-light bg-light">
            <div class="row">
             <button type="button"  class="btn btn-info sidebarCollapse ">
                                    <i class="fas fa-align-left"></i>
                                    
                                </button>
                        <a class="navbar-brand" href="./index.php"><h5><i >NEXUS SYSTEMS</i></h1></a>
            </div>        
      </nav>
     
<div class ="">
    

  
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          
          <div class="row">
            <!-- daily sales card-->
                <div class="col-xl-3 col-md-6">
                  <div class="card card-stats">
                    
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-uppercase text-muted mb-0"> today's sales</h5>
                          <span class="h5  mb-0">$<?php echo $d ?></span>
                        </div>
                        <div class="col-auto">
                          <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                            <i class="ni ni-active-40"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              
              <!-- weekly sales card-->
                <div class="col-xl-3 col-md-6">
                  <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-uppercase text-muted mb-0">  This week's Sales</h5>
                          <span class="h5  mb-0">$<?php echo $w ?></span>
                        </div>
                        <div class="col-auto">
                          <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                            <i class="ni ni-money-coins"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
             
                <!-- monthly sales card-->
                <div class="col-xl-3 col-md-6">
                  <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-uppercase text-muted mb-0"> this  month's Sales</h5>
                          <span class="h5  mb-0">$<?php echo  $m ?></span>
                        </div>
                        <div class="col-auto">
                          <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                            <i class="ni ni-money-coins"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
<br>
                <!-- yearly sales card-->
                <div class="col-xl-3 col-md-6">
                  <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-uppercase text-muted mb-0"> This  year's Sales</h5>
                          <span class="h5  mb-0">$<?php echo $y ?></span>
                        </div>
                        <div class="col-auto">
                          <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                            <i class="ni ni-money-coins"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
          

          </div>
        </div>
      </div>
    </div>


    <br>
    <br>
    <div class="container-fluid mt--6 tab" id="home">
      <div class="row">
       
          <div  id="chrt" class="card bg-default">
            <div class="card-header bg-transparent">
              <div class="row align-items-center" >
                <div class="col ">
                  <h6 class="text-info text-uppercase ls-1 mb-1">Overview </h6>
                  <h5 class=" text-info mt-3">Product Sales of current items </h5>
                </div>
              </div>
            </div>
            <div class="card-body">
              <!-- Chart -->
              <div class="charts">
                <!-- Chart wrapper -->
                <canvas id="barChart" ></canvas>   
              </div>
            </div>
          </div>
      </div>

      <br>
       <br>
    
      
      

    
    </div>
</div>
</div>
  </body> 
      

    
  



  
  <script>
  $(document).ready(function () {
    showGraph(); 
  });
  </script>

 
</html>
