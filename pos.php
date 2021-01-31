<?php
session_start();
if(!isset($_SESSION["Email"])){
    header("location:index.php");
  

  }
include('connection.php');
$uid=$_SESSION['UID'];
$n='';


$sql = "SELECT * FROM inventorysuppliers where uid='$uid' ";

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$query = sqlsrv_query( $conn, $sql , $params, $options );
if( $query === false ) {
    die( print_r( sqlsrv_errors(), true));
}
$nid='';

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
    <title>point of sale</title>

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
              
  
             
                            <div class="col-sm-5">
                                    <div class="bg-info"><h4 style="margin-left:1%;">Items</h4></div> 
                                        <div class="panel-body " id="items">  
                                                <?php
                                                    $id='';
                                                    
                                                    while( $rows = sqlsrv_fetch_array( $query, SQLSRV_FETCH_ASSOC) ){
                                                        $id=$rows['ProdName'];
                                                        $pri=$rows['price'];
                                                        $ty=$rows['Qty'];
                                                        
                                                                echo"<div class='img_item'   data-toggle='modal'    data-target='#qtymodal' id='$id' >";
                                                                    echo    " <b>".$rows['ProdName']."</b>";
                                                                    echo    " <h5 style='display:none;'>" . $pri."</h5>";
                                                                    echo    " <h3 style='display:none;'>" . $ty."</h3>";
                                                                echo" </div>    ";  
                                                    }
                                                        

                                                ?>
                                        </div>
            
                                            
                                        
                                    </div>
                            
                            
                                    <div class="col-sm-7">
                            
                                
                            <div class="bg-info"><h4 style="margin-left:1%;">New Items List</h4>  
                            </div>
                            <form action="./posaction.php" method="post"  id="user_form">
                                            <table   id="t1"  class="table   table-bordered">
                                                    <thead>
                                                        <tr>
                                
                                                            <th>Item </th>
                                                            <th>Price</th>
                                                            <th>Qty</th>
                                                            <th>Subtotal</th>
                                                            <th>Remove</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                
                                                        
                                                    </tbody>
                                                </table>
                                            
                                
                        
                    
                                            <div class="row roww">
                                                    <h5>Total</h5>
                                                    <input type="text" name="total"  id="total" readonly required >
                                                    <input type="submit" class="btn btn-info" name="print"  id="print" value="Print receipt">
                                                    
                                            
                                            </div>
                                            
                                            
                            </form>
                                
            
                </div> 
                            </div>
                 
                            
                
                </div>
                
                
                  
          
                
                          <div class="modal fade" id="qtymodal" tabindex="-1" role="dialog" aria-labelledby="qtymodallabel " aria-hidden="true">
                              <div class="modal-dialog" role="document">
                              <div class="modal-header  bg-info">
                                        <h5 class="modal-title " id="qtymodallabel">enter quantity </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                  </div>

                              <div class="modal-content">
                                  
                                  
                                                <div class="modal-body  bg-dark " >
                                                <select  name="qty" id="qty">
                                                   </select>
                                                </div>
                                                <div class="modal-footer  bg-info">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button class="btn btn-danger "    id="add"  >ADD Items</button>
                                                </div>
                                   
                              </div>
                              </div>
                          </div>
              
                  </div>
          
                

          
          </div>
    </div>


    
</body>
</html>