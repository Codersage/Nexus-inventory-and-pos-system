<?php
session_start();
$connect = new PDO("sqlsrv:server= SQL5097.site4now.net,1433; Database=DB_A6C4FE_sagecoder","DB_A6C4FE_sagecoder_admin", "Badbush5");

$uid=$_SESSION['UID'];
if(!isset($uid)){
    echo"id not set";
}
require_once('./connection.php');
include('./fpdf17/fpdf.php');

 


if(isset($_POST['print'])){
    
    $tot=$_POST['total'];
        $or="insert into Orders(TotalPrice,UID) values('$tot','$uid')";     
        $state=sqlsrv_query($conn,$or);
        if( $state === false ) {
            die( print_r( sqlsrv_errors(), true));
        }
        $or="select ORID  from Orders where UID='$uid'  and  created_at=(select MAX(created_at) from Orders where UID='$uid')";
        $state=sqlsrv_query($conn,$or);
        if( $state === false ) {
            die( print_r( sqlsrv_errors(), true));
        }
        $row=sqlsrv_fetch_array( $state, SQLSRV_FETCH_ASSOC);
 $r=$row['ORID'];
        //updating product qty
              






            $query = "
                INSERT INTO  OrderLine 
                VALUES (:prodn, :price,:qty,:subtot,:orid,:uid)
                ";


                for($count = 0; $count<count($_POST['hiddenitem']); $count++)
                {

                   
                                $data = array(
                                ':prodn' => $_POST['hiddenitem'][$count],
                                ':price' => $_POST['hiddenprice'][$count],
                                ':qty'=>$_POST['hiddenqty'][$count],
                                ':subtot'=>$_POST['hiddensubtotal'][$count],
                                ':orid'=>$r,
                                ':uid'=>$uid
                                );
                                //updating quantity of products
                                $pr=$_POST['hiddenitem'][$count];
                                $or="select Qty from inventory where ProdName='$pr' and uid='$uid' ";
                                $state=sqlsrv_query($conn,$or);
                                if( $state === false ) {
                                    die( print_r( sqlsrv_errors(), true));
                                }
                                $row=sqlsrv_fetch_array( $state, SQLSRV_FETCH_ASSOC);
                                
                                $new= $row['Qty']-$_POST['hiddenqty'][$count];
                                $or="update   inventory set Qty='$new' where ProdName='$pr' and uid='$uid' ";
                                $state=sqlsrv_query($conn,$or);
                                if( $state === false ) {
                                    die( print_r( sqlsrv_errors(), true));
                                }



                                $statement = $connect->prepare($query);
                                $statement->execute($data);
                }
                $sql="select * from Orders where UID='$uid'  and  created_at=(select MAX(created_at) from Orders where UID='$uid')";
                $result=sqlsrv_query($conn,$sql);
                $nrow=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                $pdf = new FPDF('P','mm','A4');
                $pdf->AddPage();

                //set font to arial, bold, 14pt
                $pdf->SetFont('Arial','B',14);
                $pdf->Cell(130	,5,'Nexus inventory & pos system  ',0,0);
                $pdf->Cell(59	,5,'INVOICE',0,1);//end of line
                $pdf->SetFont('Arial','',12);

                $pdf->Cell(130	,5,'French park spur tree p.o',0,0);
                $pdf->Cell(59	,5,'',0,1);//end of line

                $pdf->Cell(130	,5,'Mandeville,Manchester, Jamaica',0,0);
                $pdf->Cell(25	,5,'Date',0,0);
                $pdf->Cell(34	,5,date("Y/m/d"),0,1);//end of line

                $pdf->Cell(130	,5,'Phone [+12345678]',0,0);
                $pdf->Cell(25	,5,'Invoice #',0,0);
                $pdf->Cell(34	,5,$nrow['ORID'],0,1);//end of line

            

                //make a dummy empty cell as a vertical spacer
                $pdf->Cell(189	,10,'',0,1);//end of line

                //billing address
                $pdf->Cell(100	,5,'Bill to',0,1);//end of line

                //add dummy cell at beginning of each line for indentation
                $pdf->Cell(10	,5,'',0,0);
                //$pdf->Cell(90	,5,$invoice['name'],0,1);

                $pdf->Cell(10	,5,'',0,0);


                //make a dummy empty cell as a vertical spacer
                $pdf->Cell(189	,10,'',0,1);//end of line

                //invoice contents
                $pdf->SetFont('Arial','B',12);

                $pdf->Cell(36	,5,'Item',1,0);
                $pdf->Cell(39	,5,'Price',1,0);
                $pdf->Cell(36	,5,'Qty',1,0);  

                $pdf->Cell(38	,5,'Amount',1,1);//end of line

                $pdf->SetFont('Arial','',12);
                    $ORI=$nrow['ORID'];

                $Sl="select * from Orderline where  Orid='$ORI'";
                $r=sqlsrv_query($conn,$Sl);
                
                //items

                //display the items
                    while($OL=sqlsrv_fetch_array($r, SQLSRV_FETCH_ASSOC)){
                    $pdf->Cell(36	,5,$OL['prodname'],1,0);
                    //add thousand separator using number_format function
                    $pdf->Cell(4	,5,'$',1,0);
                    $pdf->Cell(35	,5,number_format($OL['Price']),1,0);
                    $pdf->Cell(36	,5,number_format($OL['Qty']),1,0);//end of line
                    $pdf->Cell(4	,5,'$',1,0);
                    $pdf->Cell(34	,5,number_format($OL['amt']),1,1,'R');
                    //accumulate tax and amount
                    
                    }
                  
                    $pdf->Cell(86,5,'',0,0);
                    $pdf->Cell(25	,5,'Total Due',0,0);
                    $pdf->Cell(4	,5,'$',1,0);
                    $pdf->Cell(34	,5,number_format($nrow['TotalPrice']),1,1,'R');//end of line
                    


                $pdf->Output();
}
              
?>