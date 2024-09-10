<?php $page='report';
include("php/dbconnect.php");
include("php/checklogin.php");

$errormsg = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = isset($_POST['student']) ? $_POST['student'] : '';
  $grade = isset($_POST['grade']) ? $_POST['grade'] : '';

  if (empty($name) && empty($grade)) {
      $errormsg = "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Please enter at least one field to filter!</div>";
  }
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fees Management System For SSB college - Kommadikottai</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="css/font-awesome.css" rel="stylesheet" />
       <!--CUSTOM BASIC STYLES-->
    <link href="css/basic.css" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
	
	<link href="css/ui.css" rel="stylesheet" />
	<link href="css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" />	
	<link href="css/datepicker.css" rel="stylesheet" />	
	   <link href="css/datatable/datatable.css" rel="stylesheet" />
	   
    <script src="js/jquery-1.10.2.js"></script>	
    <script type='text/javascript' src='js/jquery/jquery-ui-1.10.1.custom.min.js'></script>
   <script type="text/javascript" src="js/validation/jquery.validate.min.js"></script>
 
		 <script src="js/dataTable/jquery.dataTables.min.js"></script>
		
		 
	
</head>
<?php
include("php/header.php");
?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">View Reports
						
						</h1>

                    </div>
                </div>
				
				
	
                <?php
              echo $errormsg;
            ?>		
		

<div class="row" style="margin-bottom:20px;">
<div class="col-md-12">
<fieldset class="scheduler-border" >
    <legend  class="scheduler-border">Search:</legend>
<form class="form-inline" role="form" id="searchform" method="POST">
  <div class="form-group">
    <label for="email">Name/Reg No:</label>
    <input type="text" class="form-control" id="student" name="student">
  </div>

  
  <div class="form-group">
    <label for="email"> Course: </label>
    <select  class="form-control" id="grade" name="grade" >
		<option value="" >Select Course</option>
                                    <?php
									$sql = "select * from grade where delete_status='0' order by grade.createddate desc";
									$q = $conn->query($sql);
									
									while($r = $q->fetch_assoc())
									{
									echo '<option value="'.$r['id'].'"  '.(($grade==$r['id'])?'selected="selected"':'').'>'.$r['grade'].' '.$r['yearofstart'].'-'.$r['yearofend'].'</option>';
									}
									?>
	</select>
  </div>
  
   <button type="button" class="btn btn-success btn-sm" style="border-radius:0%" id="find" > Filter </button>
  <button type="reset" class="btn btn-danger btn-sm" style="border-radius:0%" id="clear" > Reset </button>
</form>
</fieldset>

</div>
</div>

<script type="text/javascript">
$(document).ready( function() {

/*
$('#doj').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false,
        dateFormat: 'mm/yy',
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
    });
	
*/
	
/******************/	
	//  $("#doj").datepicker({
         
  //       changeMonth: true,
  //       changeYear: true,
  //       showButtonPanel: true,
  //       dateFormat: 'mm/yy',
  //       onClose: function(dateText, inst) {
  //           var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
  //           var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
  //           $(this).val($.datepicker.formatDate('MM yy', new Date(year, month, 1)));
  //       }
  //   });

  //   $("#doj").focus(function () {
  //       $(".ui-datepicker-calendar").hide();
  //       $("#ui-datepicker-div").position({
  //           my: "center top",
  //           at: "center bottom",
  //           of: $(this)
  //       });
  //   });

/*****************/

$.ajax({
    // ...
    success: function(response) {
        // Update your HTML or perform actions based on the response
        $('#resultContainer').html(response);
    },
    // ...
});

	
$('#student').autocomplete({
		      	source: function( request, response ) {
		      		$.ajax({
		      			url : 'ajx.php',
		      			dataType: "json",
						data: {
						   name_startsWith: request.term,
						   type: 'report'
						},
						 success: function( data ) {
						 
							 response( $.map( data, function( item ) {
							
								return {
									label: item,
									value: item
								}
							}));
						}
						
						
						
		      		});
		      	}
				/*,
		      	autoFocus: true,
		      	minLength: 0,
                 select: function( event, ui ) {
						  var abc = ui.item.label.split("-");
						  //alert(abc[0]);
						   $("#student").val(abc[0]);
						   return false;

						  },
                 */
  

						  
		      });
	

$('#find').click(function () {
mydatatable();
        });


$('#clear').click(function () {

$('#searchform')[0].reset();
mydatatable();
        });
		
function mydatatable()
{
        
              $("#subjectresult").html('<table class="table table-striped table-bordered table-hover" id="tSortable22"><thead><tr><th>Name/Reg No</th><th>Course & Batch</th><th>Semester</th><th>Fees</th><th>Paid Amount</th><th>Balance</th><th>Action</th></tr></thead><tbody></tbody></table>');
			  
			    $("#tSortable22").dataTable({
							      'sPaginationType' : 'full_numbers',
							     "bLengthChange": false,
                  "bFilter": false,
                  "bInfo": false,
							       'bProcessing' : true,
							       'bServerSide': true,
							       'sAjaxSource': "datatable.php?"+$('#searchform').serialize()+"&type=report",
							       'aoColumnDefs': [{
                                   'bSortable': false,
                                   'aTargets': [-1] /* 1st one, start by the right */
                                                }]
                                   });


}
		
////////////////////////////
 $("#tSortable22").dataTable({
			     
                  'sPaginationType' : 'full_numbers',
				  "bLengthChange": false,
                  "bFilter": false,
                  "bInfo": true,
                  
                  'bProcessing' : true,
				  'bServerSide': true,
                  'sAjaxSource': "datatable.php?type=report",
				  
			      'aoColumnDefs': [{
                  'bSortable': false,
                  'aTargets': [-1] /* 1st one, start by the right */
              }]
            });

///////////////////////////		


	
});


function GetFeeForm(sid)
{

$.ajax({
            type: 'post',
            url: 'getfeeform.php',
            data: {student:sid,req:'2'},
            success: function (data) {
              $('#formcontent').html(data);
			  $("#myModal").modal({backdrop: "static"});
            }
          });


}

</script>


		

<style>
#doj .ui-datepicker-calendar
{
display:none;
}

</style>


		
		<div class="panel panel-default">
                        <div class="panel-heading">
                            Manage Fees  
                        </div>
                        <div class="panel-body">
                            <div class="table-sorting table-responsive" id="subjectresult">
                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
                                    <thead>
                                        <tr>
                                          
                                            <th>Name/Reg No</th> 
                                          <th>Course & Batch</th>
                                          <th>Semester</th>                                           
                                            <th>Fees</th>
                                            <th>Paid Amount</th>
                                          <th>Balance</th>
                                          <th>Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
								                      </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                     
	
	<!-------->
	
	<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Fee Report</h4>
        </div>
        <div class="modal-body" id="formcontent" id="exampl">
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" style="border-radius:0%" data-dismiss="modal" OnClick="CallPrint(this.value)" >Print</button>
          <button type="button" class="btn btn-danger" style="border-radius:0%" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

	<script>
function CallPrint(strid) {
  
  var prtContent = document.getElementById("exampl");
  var collegeLogoUrl = "logo.png";
  var WinPrint = window.open("", "", "height=1000, width=1000");
  WinPrint.document.write("<html>");
  WinPrint.document.write("<style>.exampl{border: 1px solid #ccc;padding: 20px;margin: 20px;}table {width: 100%;border-collapse: collapse;margin-top: 20px;}th, td {border: 1px solid #ddd; padding: 8px; text-align: left;}th { background-color: #f2f2f2;}h3{margin-top: 20px;}</style>");
  WinPrint.document.write("<body>");
  WinPrint.document.write("<h3 align='center'>Sri Sankara Bagavathi Arts And Science College,Kommadikottai </h3>");
  WinPrint.document.write(prtContent.innerHTML);
  WinPrint.document.write("</body></html>");
  WinPrint.document.close();
  WinPrint.focus();
  WinPrint.print();
  WinPrint.close();
  }
  </script>
    <!--------->
    			
            
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
   
  
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="js/bootstrap.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="js/jquery.metisMenu.js"></script>
       <!-- CUSTOM SCRIPTS -->
    <script src="js/custom1.js"></script>

    
</body>
</html>
