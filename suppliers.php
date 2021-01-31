
<?php
session_start();
if(!isset($_SESSION["Email"])){
    header("location:index.php");
  

  }
$name=$loc=$em=$pn='';
;

include('connection.php');
$uid=$_SESSION['UID'];

$sql = "SELECT * FROM supplier where uid='$uid'";

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
	SELECT ROW_NUMBER() OVER(order  BY SPID ) AS RowID,*  FROM supplier where uid ='$uid'
    ) AS c  WHERE c.RowID > $row_start AND c.RowID <= $row_end  and  uid = '$uid' ";
    
   // $query = sqlsrv_query( $conn, $sql );


$stmt = sqlsrv_query( $conn, $sql ,$params, $options);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
}
$error=array('email'=>'','telephone'=>'');



if(isset($_POST['addsupp'])){
$name=htmlspecialchars( $_POST['supplier']);
$em=htmlspecialchars( $_POST['suppem']);
$loc=htmlspecialchars( $_POST['supploc']);
$pn=htmlspecialchars( $_POST['pn']);
if(filter_var($em,FILTER_VALIDATE_EMAIL)){
    echo "sucess";
}else{
    $error['email']=" enter  legitimate email";
}
echo $error['email'];
if(strlen($pn)>12){
    $error['telephone']= "please enter a 10 digit phone number";
}
echo $error['telephone'];

if(array_filter($error)){

}else{
                $ql="exec addsupplier '$name','$loc','$em','$pn','$uid' ";
                $result=sqlsrv_query( $conn, $ql);
                if($result== true){
                    header('location:suppliers.php');
                }else{
                    die( print_r( sqlsrv_errors(), true));
                }
               
        }
}

if(isset($_POST['editsupp'])){
        $spid=$_POST['spid'];
        $name=htmlspecialchars( $_POST['editsupplier']);
        $em=htmlspecialchars( $_POST['editsuppem']);
        $loc=htmlspecialchars( $_POST['editsupploc']);
        $pn=htmlspecialchars( $_POST['editpn']);
       
        if(filter_var($em,FILTER_VALIDATE_EMAIL)){
        }else{
            $error['email']=" enter  legitimate email";
        }
        echo $error['email'];
        if(strlen($pn)>12){
            $error['telephone']= "please enter a 10 digit phone number";
        }
        echo $error['telephone'];
        
        if(array_filter($error)){
            echo "pl fix errors";
        }else{
                     
            $ql="update Supplier
                        set 	
                        SupplierName='$name',
                            Supplocation='$loc',	
                            Suppemail ='$em',	
                            PhoneNumber ='$pn'
                            WHERE SPID ='$spid'";
                        $result=sqlsrv_query( $conn, $ql);
                        if($result == true){
                         
                            header('location:suppliers.php');
                        }else{ die( print_r( sqlsrv_errors(), true));}
       
    }
}
if(isset($_POST['remove'])){

   
    $spid=$_POST['sspid'];
    
    $sl=" delete from Supplier
    where SPID ='$spid'";
    $eresult=sqlsrv_query($conn,$sl);
    if($eresult ===false ) {
        die( print_r( sqlsrv_errors(), true));
    }else{
      
        header('location:suppliers.php');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./js/style.css">
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="./index_style.css">
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


  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>supplier</title>
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


  
  <div id="content">

  <nav class="navbar text-center navbar-expand-lg navbar-light bg-light">
            <div class="row">
             <button type="button"  class="btn btn-info sidebarCollapse ">
                                    <i class="fas fa-align-left"></i>
                                    
                                </button>
                        <a class="navbar-brand" href="./index.php"><h5><i >NEXUS SYSTEMS</i></h1></a>
            </div>        
      </nav>


    <div > 
        <br>
        <br>
        <h3><i>Suppliers</i></h3>
       <!-- CONTAIN ITEM -->
        <div class="table-responsive">

        <table cellspacing="0" class="table  table-bordered table-hover  ">
                <thead  class="thead-dark">
                    <tr>
                        <th>SPID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Email </th>
                        <th>Supplier</th>
                        <th>Edit</th>
                        <th>Delete</th> 
                    </tr>
                   
                </thead>
                <tbody>
                        <?php
                        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) )
                                    {
                                        echo "<tr>";
                                        echo"<td>".$row['SPID'].   "</td>" ;
                                        echo"<td>".$row['SupplierName'].   "</td>" ;
                                        echo "<td>".$row['Supplocation'].  "</td>";
                                        echo"<td>"  .$row['Suppemail'].   "</td> ";
                                        echo"<td>"  .$row['PhoneNumber'].   "</td> ";
                                        echo   "<td><button name='edit' onclick='editsupp(this)' style='border:none;'class='btntbl'><a href='#'  data-toggle='modal' data-target='#editmodal' ><i class='fas fa-edit' style='font-size:24px;'></i></a></button></td>";
                                        echo  "<td><button  onclick='remsupp(this)' style='border:none;'class='btntbl'><a href='#'  data-toggle='modal' data-target='#remmodal'><i class='fas fa-cut' style='font-size:24px;'></i></a></button></td>";
                                        echo "</tr>";
                                    }
                        ?>
                </tbody>
            </table>
        </div>
            
            
            

        <button class="btn btn-primary" style ="margin-bottom:20px; width: 100%;border-radius: 2%; height: 30px;"  data-toggle="modal" data-target="#addmodal">ADD PRODUCT</button>
        
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
                                    <form action="./suppliers.php" method="post">
                                        <div class="modal-body    bg-dark ">
                                            
                                                <input name="supplier"placeholder ="NAME" type="text"  required>
                                                <input name="supploc" placeholder ="location " type="text"required  >
                                                <input name="suppem" placeholder ="email" type="email "   required  >  
                                                <input type="text"  name="pn"  placeholder="telephone"     required     >
                                       </div>
                                                <div class="modal-footer  bg-info">

                                                            <button class="btn btn-success"  name="addsupp" value ="addsupp" style ="width: 180px">ADD Items</button>
                                                
                                                </div>
                                    </form>
                                           
                                  </div>
        </div>
    </div>

    <    <div class="modal fade" id="remmodal" tabindex="-1" role="dialog" aria-labelledby="remmodallabel " aria-hidden="true">
                              <div class="modal-dialog" role="document">
                              <div class="modal-header  bg-info">
                                        <h5 class="modal-title " id="qtymodallabel">enter quantity </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                  </div>

                              <div class="modal-content">
                                  
                                  <form action="./suppliers.php" method="post">
                                                <div class="modal-body  bg-dark " >
                                                    <h5  class="text-white">Are you sure you want to remove supplier please ensure that all products supplied by this supplier are removed? (action cannot be  undone)</h5>
                                                    <input type="hidden" name="sspid" id="sspid">
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
                                            <form action="./suppliers.php" method="post">
                                                <div class="modal-body    bg-dark ">
                                                        <input type="hidden"  id="editspid" name="spid" >

                                                        <label for="editsupplier"class="text-white">NAME</label><br>

                                                        <input name="editsupplier"placeholder ="NAME" type="text"  required id="editsupplier"> <br>
                                                        <label for="editsupploc"class="text-white">location</label><br>
                                                        <input name="editsupploc" placeholder ="location " type="text"required id="editsupploc"> <br>
                                                        <label for="editsuppem"class="text-white">email</label><br>
                                                        <input name="editsuppem" placeholder ="email" type="email "   required  id="editsuppem" >   <br>
                                                        <label for="editpn"class="text-white">Telephone</label><br>
                                                        <input type="text"  name="editpn"  placeholder="telephone"     required   id="editpn"  > 
                                            </div>
                                                        <div class="modal-footer  bg-info">

                                                                    <button class="btn btn-success"  name="editsupp" value ="editsupp" style ="width: 180px">Edit Items</button>
                                                        
                                                        </div>
                                            </form>
                                                
                                        </div>
                </div>
        </div>


        
                             






</body>
    </div>
</div>
</div>


</html>
