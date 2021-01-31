
<?php
session_start();
if(!isset($_SESSION["Email"])){
    header("location:index.php");
  

  }
$name=$spid='';
$qty=$price=0;

include('connection.php');
$uid=$_SESSION['UID'];

$sql = "SELECT * FROM inventorysuppliers where uid='$uid' ";

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$query = sqlsrv_query( $conn, $sql , $params, $options );
if( $query === false ) {
    die( print_r( sqlsrv_errors(), true));
}
$num_rows = sqlsrv_num_rows($query);


	$per_page = 5;   // Per Page
	$page  = 1;
	
	if(isset($_GET["Page"]))
	{
		$page = $_GET["Page"];
	}

	$prev_page = $page-1;
	$next_page = $page+1;

	$row_start = (($per_page*$page)-$per_page);
	if($num_rows<=$per_page)
	{
		$num_pages =1;
	}
	else if(($num_rows % $per_page)==0)
	{
		$num_pages =($num_rows/$per_page) ;
	}
	else
	{
		$num_pages =($num_rows/$per_page)+1;
		$num_pages = (int)$num_pages;
	}
	$row_end = $per_page * $page;
	if($row_end > $num_rows)
	{
		$row_end = $num_rows;
    }

   

$sql="SELECT c.* FROM (
	SELECT ROW_NUMBER() OVER(order  BY  uid ) AS RowID,*  FROM inventorysuppliers WHERE uid='$uid'
    ) AS c  WHERE c.RowID > $row_start AND c.RowID <= $row_end and uid='$uid' ";
    
   // $query = sqlsrv_query( $conn, $sql ); 


$stmt = sqlsrv_query( $conn, $sql ,$params, $options);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
}

$error=array('email'=>'','telephone'=>'');
//new item function
if(isset($_POST['additem'])){
    
            $name=htmlspecialchars( $_POST['pnamevalue']);
            $spid=htmlspecialchars( $_POST['spid']);
            $qty=htmlspecialchars( $_POST['qtyvalue']);
            $price=htmlspecialchars( $_POST['pricevalue']);
            $sql="SELECT * from inventory where ProdName='$name' and uid='$uid'";                                                                   
            $result=sqlsrv_query( $conn, $sql , $params, $options );

            if( $result === false ) {
                die( print_r( sqlsrv_errors(), true));
                
            }
            $row_cnt = sqlsrv_num_rows($result);
                if($row_cnt>=1){
                
                $error['email']=' product already in inventory';

          }
            echo $error['email'] ;
            if($price<0){
                $error['telephone']= "please enter price above 0";
            }
            echo $error['telephone'];


            if($qty<0){
                $error['telephone']= "please enter number above 0";
            }

            if(array_filter($error)){}
                        else{
                    $check="exec addinventory'$name','$price','$qty','$uid','$spid' ";
                    $result=sqlsrv_query( $conn, $check);
                    if( $result === false ) {
                        die( print_r( sqlsrv_errors(), true));
                    }else{
                        header('location:inventory.php');
                    }
            }
        }
   

if(isset($_POST['ediitem'])){

    $ename=htmlspecialchars ($_POST['edititem']);
    $eprice=htmlspecialchars ($_POST['editprice']);
    $qty=htmlspecialchars ($_POST['editqty']);
    $espid=htmlspecialchars( $_POST['editspid']);
    $inid=htmlspecialchars ($_POST['inid']);
   
    if($eprice<0){
        $error['telephone']= "please enter price above 0";
    }
    echo $error['telephone'];


    if($qty<0){
        $error['telephone']= "please enter number above 0";
    }

    if(array_filter($error)){}
                else{
                    $sl=" update inventory
                    set ProdName='$ename',
                     price='$eprice',
                     Spid='$espid',
                     Qty='$qty'
                    where INID ='$inid'";
                    $eresult=sqlsrv_query($conn,$sl);
                    if($eresult ===false ) {
                        die( print_r( sqlsrv_errors(), true));
                    }else{
                        header('location:inventory.php');
                    }
    }



  
}

if(isset($_POST['remove'])){

   
    $inid=$_POST['nnid'];
    
    $sl=" delete from inventory
    where INID ='$inid'";
    $eresult=sqlsrv_query($conn,$sl);
    if($eresult ===false ) {
        die( print_r( sqlsrv_errors(), true));
    }else{
      
        header('location:inventory.php');
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="./index_style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    
  <link rel="stylesheet" href="./css/style2.css">
  <link rel="stylesheet" href="./index_style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
  <script src="./js/index.js"></script>


  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>inventory</title>
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


  
 <div id="content"  >

      <nav class="navbar text-center navbar-expand-lg navbar-light bg-light">
            <div class="row">
             <button type="button"  class="btn btn-info sidebarCollapse ">
                                    <i class="fas fa-align-left"></i>
                                    
                                </button>
                        <a class="navbar-brand" href="./dashboard.php"><h5><i >NEXUS SYSTEMS</i></h1></a>
            </div>        
      </nav>


    <div > 
        <br>
        <br>
        <h3><i>Products</i></h3>
       
       <!-- CONTAIN ITEM -->
        <div class="table-responsive">

        <table cellspacing="0" class="table  table-bordered table-hover  ">
                <thead  class="thead-dark">
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Supplier</th>
                        <th>Edit</th>
                        <th>Delete</th> 
                    </tr>
                   
                </thead>
                <tbody>
                        <?php
                        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) )
                                    { 
                                        $sp=$row['SPID'];
                                        $eid=$row['INID'];
                                        echo "<tr>";
                                        echo"<td>".$row['ProdName'].  "</td>" ;
                                        echo "<td>".$row['Qty'].  "</td>";
                                        echo"<td>"  .$row['price'].   "</td> ";
                                        echo"<td>"  .$row['SupplierName'].  " <input type='hidden'  value='$sp'></td> ";
                                        echo"<td style='display:none;' > <input type='hidden'  value='$eid'></td> ";
                                        echo   "<td><button name='edit' onclick='edititems(this)' style='border:none;' class='btntbl'><a href='#'  data-toggle='modal' data-target='#editmodal' ><i class='fas fa-edit' style='font-size:24px;'></i></a></button></td>";
                                        echo  "<td><button  onclick='remitem(this)' style='border:none;'class='btntbl'><a href='#'  data-toggle='modal' data-target='#remmodal'><i class='fas fa-cut' style='font-size:24px;'></i></a></button></td>";
                                            
                                        echo "</tr>";
                                    }
                                    
                        ?>
                </tbody>
            </table>
        </div>
            
            
            

        <button class="btn btn-primary" id="addpro" style ="margin-bottom:20px; width: 100%;border-radius: 2%; height: 30px;"  data-toggle="modal" data-target="#addmodal">ADD PRODUCT</button>
        
        <nav aria-label="Page navigation example mt-5">
                Total <?php echo $num_rows;?> Record : <?php echo $num_pages;?> Page :

               

                    <ul class="pagination justify-content-center">
                    <?php
                    
                    if($prev_page)
                    {
                        echo  "<button class=' btn btn-info'> <a  style=' color:white;text-decoration:none;' href='$_SERVER[SCRIPT_NAME]?Page=$prev_page'>Back</a> </button>";
                    }
                    ?>
                    
                    <div class="pg" >
                    <?php
                            for($i=1; $i<=$num_pages; $i++){
                                if($i != $page)
                                {
                                    echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$i'>$i</a>";
                                }
                                else
                                {
                                    echo "<b> $i </b>";
                                }
                            }
                    ?>
                    </div>
                    
                    <?php
                    if($page!=$num_pages)
                    {
                        echo " <button class='btn btn-info'><a  style='color:white;text-decoration:none;' href ='$_SERVER[SCRIPT_NAME]?Page=$next_page'>Next</a></button> ";
                    }
                
                    sqlsrv_close($conn);
                    ?>
                    
                    </ul>

            



        </nav>
      </div>
        
        <div id="addmodal" class="modal fade"   tabindex="-1" role="dialog" aria-labelledby="addmodallabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                                        <div class="modal-header  bg-info">
                                                <h5 class="modal-title " id="qtymodallabel">enter product </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                        </div>
                                        <div class="modal-content">
                                            <form action="./inventory.php" method="post">
                                                <div class="modal-body    bg-dark ">
                                                    
                                                        <input name="pnamevalue"placeholder ="NAME" type="text" required    >
                                                        <input name="qtyvalue" placeholder ="QTY" type="number" required>
                                                        <input name="pricevalue" placeholder ="price" type="number" required >  
                                                        <input type="text"  name="spid"  placeholder="supplier id "  required>
                                            </div>
                                                        <div class="modal-footer  bg-info">

                                                                    <button class="btn btn-success"  name="additem" value ="additem" style ="width: 180px">ADD Items</button>
                                                        
                                                        </div>
                                            </form>
                                                
                                        </div>
                </div>
        </div>
        
        <div class="modal fade" id="remmodal" tabindex="-1" role="dialog" aria-labelledby="remmodallabel " aria-hidden="true">
                              <div class="modal-dialog" role="document">
                              <div class="modal-header  bg-info">
                                        <h5 class="modal-title " id="qtymodallabel">enter quantity </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                  </div>

                              <div class="modal-content">
                                  
                                  <form action="./inventory.php" method="post">
                                                <div class="modal-body  bg-dark " >
                                                    <h5  class="text-white">Are you sure you want to remove item? (action cannot be  undone)</h5>
                                                    <input type="hidden" name="nnid" id="nnid">
                                                </div>
                                                <div class="modal-footer  bg-info">
                                                       
                                                        <button class="btn btn-danger "  name="remove"  value="remove" id="remove"  >remove Items</button>
                                                </div>
                                    </form>
                                </div>
                              </div>
                          </div>

        
        
        
        
        <div id="editmodal" class="modal fade"   tabindex="-1" role="dialog" aria-labelledby="addmodallabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                                        <div class="modal-header  bg-info">
                                                <h5 class="modal-title " id="qtymodallabel">Edit product entry</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                        </div>
                                        <div class="modal-content">
                                            <form action="./inventory.php" method="post">
                                                <div class="modal-body    bg-dark ">
                                                        <input type="hidden"  id="inid" name="inid" >
                                                        <label for="edititem"class="text-white">product</label><br>
                                                        <input id="edititem" name="edititem"placeholder ="NAME" type="text" required > <br>
                                                        <label for="editqty"class="text-white">Qty</label><br>
                                                        <input id="editqty" name="editqty" placeholder ="QTY" type="number" required> <br>
                                                        <label for="editprice"class="text-white">price</label><br>
                                                        <input id="editprice" name="editprice" placeholder ="price" type="number" required > <br>
                                                        <label for="editspid"class="text-white">spid</label><br>
                                                        <input id="editspid" type="text"  name="editspid"  placeholder="supplier id "  required> 
                                            </div>
                                                        <div class="modal-footer  bg-info">

                                                                    <button class="btn btn-success"  name="ediitem" value ="ediitem" style ="width: 180px">Edit Items</button>
                                                        
                                                        </div>
                                            </form>
                                                
                                        </div>
                </div>
        </div>





    </div>
</div>
</div>
</body>

</html>
