


 var total=0;
 var subtotal;
 var item;
 var price;
 var id;
 
$(document).ready(function () {
    var count=0;
         
    $('.sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
    $(".img_item").click(function () { 
        item=" ";
        price=0; 
        item=$(this).children("b").html();
        price=$(this).children("h5").html();
        ty=$(this).children("h3").html();
        select=$("#qty");
        for(i=0;i<=ty;i++){
            select.append($('<option></option>').val(i).html(i));
        }
    });     
  
   
   
   
   // for pos 
   $("#add").click( function()
    {
       
       
   
       
   
           var qty= $('#qty').val();
           subtotal=price*qty;
           count = count + 1;
         
           var orderitem=
           "<tr>"+
           "<td>"+item+'<input type="hidden" name="hiddenitem[]" id="item '+count+'" class="item" value="'+item+'" />'+"</td>"+
           "<td>"+price+'<input type="hidden" name="hiddenprice[]" id="price '+count+'" class="price" value="'+price+'" />'+"</td>"+
           "<td>"+qty+'<input type="hidden" name="hiddenqty[]" id="qty'+count+'" class="qty" value="'+qty+'" />'+"</td>"+
           "<td>"+subtotal+'<input type="hidden" name="hiddensubtotal[]" id="subtotal '+count+'" class="subtotal" value="'+subtotal+'" />'+"</td>"+
           "<td> <input type='button' class='btn btn-danger  color='white' name='remove' id='remove' value='Remove'   onclick='removeitem(this)'> </td>"+
           "</tr>" ;
   
          
           
   
           $("table tbody").append(orderitem);
           $("#qtymodal").modal("toggle");
           total+=  Number( subtotal);
           $("#total").val(total); 
           $('#qty').val("1");
   
          
    });   
  


});
var inid;
var pn;
var pr;
var qty;
var spid;
//edit items for inventory


function edititems(e){
pn=$(e).parent().parent().children().eq(0).text();
pr=Number($(e).parent().parent().children().eq(2).html());
qty=Number($(e).parent().parent().children().eq(1).html());
spid=$(e).parent().parent().children().eq(3).children("input").val();
inid=$(e).parent().parent().children().eq(4).children("input").val();
console.log(pr);
console.log(inid);
$("#edititem").val(pn);
$("#editprice").val(pr);
$("#editqty").val(qty);
$("#editspid").val(spid);
$("#inid").val(inid);

}


//remove item for pos 
function removeitem(e) {
        var tot=parseInt($(e).parent().parent().find('td:nth-child(4)').text(),10);
       
        if( confirm('do you want to remove?')==true){
           
            total -= tot;
            $("#total").val(total);
        
            $(e).parent().parent().remove();
        }
    }



    //removes supplier
    function remsupp(e){
        spid=$(e).parent().parent().children().eq(0).html();
        $("#sspid").val(spid);
        console.log(spid);
    }

    var tele
    var em
    var loc;
    //edit supplier
function editsupp(e){
spid=$(e).parent().parent().children().eq(0).html();
supp=$(e).parent().parent().children().eq(1).html();
loc=$(e).parent().parent().children().eq(2).html();
em=$(e).parent().parent().children().eq(3).html();
tele=$(e).parent().parent().children().eq(4).html();
$("#editsupplier").val(supp);
$("#editsupploc").val(loc);
$("#editsuppem").val(em);
$("#editspid").val(spid);
$("#editpn").val(tele);
console.log(spid);

}
//removes item on inventory
    function remitem(e) {
        
        inid=$(e).parent().parent().children().eq(4).children("input").val();
        $("#nnid").val(inid);
        console.log(inid);
    }



    function showGraph()
    {
        {
            $.post("dashbord_action.php",
            function (data)
            {
                console.log(data);
                 var name=[];
                var  amt=[];

                for (var i in data) {
                    name.push(data[i].prodname);
                    amt.push(data[i].amtsold);
                    console.log(data.prodname);

                }
              //  console.log(name);
               // console.log(amt);
                var chartdata = {
                    labels: name,
                    datasets: [
                        {
                            label: 'product sold',
                            backgroundColor: '#49e2ff',
                            borderColor: '#46d5f1',
                            hoverBackgroundColor: '#CCCCCC',
                            hoverBorderColor: '#666666',
                            data:  amt
                        }
                    ]
                };

                var graphTarget = $("#barChart");

                var myChart  = new Chart(graphTarget, {
                    type: 'bar',
                    data: chartdata,
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            });
        }
    }