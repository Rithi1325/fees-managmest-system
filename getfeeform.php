<?php
include("php/dbconnect.php");
include("php/checklogin.php");

if(isset($_POST['req']) && $_POST['req']=='1') 
{

$sid = (isset($_POST['student']))?mysqli_real_escape_string($conn,$_POST['student']):'';


$sql = "SELECT s.id, s.sname, s.roll_no, s.balance, s.fees, s.contact, b.grade, b.yearofstart, b.yearofend, 
        s.sem, s.classfee, s.busfee, s.vanfee, s.skillfee, s.otherfee
        FROM student AS s
        INNER JOIN grade AS b ON b.id = s.grade
        WHERE s.delete_status = '0' AND s.id = '$sid'";
        
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $res = $result->fetch_assoc();
echo '  <form class="form-horizontal" id ="signupForm1" action="fees.php" method="post" onsubmit="showConfirmation()">

<div class="form-group">
<label class="control-label col-sm-2" for="email">Reg No:</label>
<div class="col-sm-10">
  <input type="text" class="form-control" id="roll" disabled  value="'.$res['roll_no'].'" >
</div>
</div>

  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Name:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="sname" disabled  value="'.$res['sname'].'" >
    </div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Course:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="grade" disabled  value="'.$res['grade'].' '.$res['yearofstart'].'-'.$res['yearofend'].'" />
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Semester:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="sem" disabled  value="'.$res['sem'].'" />
    </div>
  </div>
  
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Total Fee:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="totalfee" id="totalfee"   value="'.$res['fees'].'" disabled />
    </div>
  </div>
  
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Balance:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="balance"  id="balance" value="'.$res['balance'].'" disabled />
	  <input type="hidden" value="'.$res['id'].'" name="sid">
    </div>
  </div>
  
  
  <div class="form-group">
  <label class="control-label col-sm-2" for="email">Fee Type:</label>
  <div class="col-sm-10">
    <select class="form-control" name="transcation_remark" id="transcation_remark" onchange="updateAmount()">
      <option value="">Select fees type</option>';

if ($res['classfee'] != 0) {
    echo '<option value="Semester Fees">Semester Fees</option>';
}
if ($res['busfee'] != 0) {
    echo '<option value="Bus Fees">Bus Fees</option>';
}
if ($res['vanfee'] != 0) {
    echo '<option value="Van Fees">Van Fees</option>';
}
if ($res['skillfee'] != 0) {
    echo '<option value="Skill Fees">Skill Fees</option>';
}
if ($res['otherfee'] != 0) {
    echo '<option value="Others">Others</option>';
}

echo '</select>
  </div>
</div>
  
<div class="form-group">
  <label class="control-label col-sm-2" for="email">Mode:</label>
  <div class="col-sm-10">
    <select class="form-control" id="paymentMode" name="paymentMode" onchange="showTextBox(this.value)">
      <option value="">Select payment mode</option>
      <option value="Cash">Cash</option>
      <option value="Online">Online</option>
      <option value="Cheque">Cheque</option>
    </select>
  </div>
</div>

<div class="form-group" id="onlineTextBox" style="display: none;">
    <label class="control-label col-sm-2" for="email">Transaction ID:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="transid"  id="transid"  />
    </div>
  </div>

  <div class="form-group" id="checkTextBox" style="display: none;">
    <label class="control-label col-sm-2" for="email">Cheque No:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="check"  id="check"  />
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Amount:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="paid"  id="paid"  />
    </div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="email">Date:</label>
    <div class="col-sm-10">
	
    <input type="text" class="form-control" name="submitdate"  id="submitdate" style="background:#fff;" />
    </div>
  </div>
  
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit"  class="btn btn-info" style="border-radius:0%" name="save" >Submit</button>
    </div>
  </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">

$(document).ready( function() {
  
$("#submitdate").datepicker( {
        changeMonth: true,
        changeYear: true,    
        dateFormat: "yy-mm-dd",
      
    });
    // Get the current date
    var currentDate = new Date();

    // Get the year, month, and day
    var year = currentDate.getFullYear();
    var month = (currentDate.getMonth() + 1).toString().padStart(2, "0"); // Add leading zero if needed
    var day = currentDate.getDate().toString().padStart(2, "0"); // Add leading zero if needed

    // Create the date string in the format YYYY-MM-DD
    var dateString = year + "-" + month + "-" + day;

    // Set the value of the text box to the current date
    document.getElementById("submitdate").value = dateString;
	
///////////////////////////
function showConfirmation() {
  var reg = value="'.$res['roll_no'].'";
  var name = value="'.$res['sname'].'";
  var grade = "'.$res['grade'].' '.$res['yearofstart'].'-'.$res['yearofend'].'";
  var paid = document.getElementById("paid").value;
  var transcation_remark = document.getElementById("transcation_remark").value;
  
  // Unicode characters for mathematical bold digits
  var boldDigits = {
    "0": "\u{1D7EC}", // Mathematical double-bold digit zero
    "1": "\u{1D7ED}", // Mathematical double-bold digit one
    "2": "\u{1D7EE}", // Mathematical double-bold digit two
    "3": "\u{1D7EF}", // Mathematical double-bold digit three
    "4": "\u{1D7F0}", // Mathematical double-bold digit four
    "5": "\u{1D7F1}", // Mathematical double-bold digit five
    "6": "\u{1D7F2}", // Mathematical double-bold digit six
    "7": "\u{1D7F3}", // Mathematical double-bold digit seven
    "8": "\u{1D7F4}", // Mathematical double-bold digit eight
    "9": "\u{1D7F5}"  // Mathematical double-bold digit nine
  };


  // Function to convert a number to bold representation using Unicode characters
  function getBoldRepresentation(number) {
    var boldRepresentation = "";
    for (var i = 0; i < number.length; i++) {
      if (boldDigits.hasOwnProperty(number[i])) {
        boldRepresentation += boldDigits[number[i]];
      } else {
        boldRepresentation += number[i];
      }
    }
    return boldRepresentation;
  }

  var boldPaid = getBoldRepresentation(paid); // Get the bold representation of the paid amount

  var confirmationMessage = "Reg No: " + reg + "\n";
  confirmationMessage += "Student Name: " + name + "\n";
  confirmationMessage += "Course: " + grade + "\n";
  confirmationMessage += "Paid Amount: " + boldPaid + "\n"; // Include the bold paid amount
  confirmationMessage += "Fee Type: " + transcation_remark + "\n\n";
  confirmationMessage += "Are you sure you want to submit this payment? (y/yes)";

  var userInput = prompt(confirmationMessage);

  // Check if the user input is yes
  if (userInput =="y" || userInput =="yes" || userInput =="Y" || userInput =="Yes" || userInput =="YES" || userInput =="yES") {
    return true; // Proceed with form submission
  } else {
    return false; // Cancel form submission
  }
}

$( "#signupForm1" ).validate( {
				rules: {
					submitdate: "required",
          transcation_remark: "required",
					recno: "required",
          transid: "required",
          check: "required",
          paymentMode: "required",
					paid: {
						required: true,
						digits: true,
						max:'.$res['balance'].',
            min: 1,
					}	
					
					
				},
				errorElement: "em",
				errorPlacement: function ( error, element ) {
					// Add the `help-block` class to the error element
					error.addClass( "help-block" );

					// Add `has-feedback` class to the parent div.form-group
					// in order to add icons to inputs
					element.parents( ".col-sm-10" ).addClass( "has-feedback" );

					if ( element.prop( "type" ) === "checkbox" ) {
						error.insertAfter( element.parent( "label" ) );
					} else {
						error.insertAfter( element );
					}

					
					if ( !element.next( "span" )[ 0 ] ) {
						$( "<span class=\'glyphicon glyphicon-remove form-control-feedback\'></span>" ).insertAfter( element );
					}
				},
				success: function ( label, element ) {
					if ( !$( element ).next( "span" )[ 0 ] ) {
						$( "<span class=\'glyphicon glyphicon-ok form-control-feedback\'></span>" ).insertAfter( $( element ) );
					}
				},
				highlight: function ( element, errorClass, validClass ) {
					$( element ).parents( ".col-sm-10" ).addClass( "has-error" ).removeClass( "has-success" );
					$( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
				},
				unhighlight: function ( element, errorClass, validClass ) {
					$( element ).parents( ".col-sm-10" ).addClass( "has-success" ).removeClass( "has-error" );
					$( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
				},

        submitHandler: function(form) {
          if (showConfirmation()) {
            form.submit(); // Submit the form if confirmed
          }
        } 
			});


//////////////////////////	
	

	
});

</script>';
}else
{
echo "Something Goes Wrong! Try After sometime.";
}

}


if(isset($_POST['req']) && $_POST['req']=='2') 
{
$sid = (isset($_POST['student']))?mysqli_real_escape_string($conn,$_POST['student']):'';
$sql = "select * from fees_transaction  where stdid='".$sid."'";
$fq = $conn->query($sql);
if($fq->num_rows>0)
{


 $sql = "select s.id,s.sname,s.balance,s.emailid,s.fees,s.contact,b.grade,b.yearofstart,b.yearofend,s.sem,c.recno,c.stdid from student as s,grade as b,fees_transaction as c where b.id=s.grade  and s.id='".$sid."' and c.stdid='".$sid."'";
$sq = $conn->query($sql);
$sr = $sq->fetch_assoc();
$stdid = $sr['stdid'];

echo '<div id="exampl">

<h4>Student Info</h4>
<div class="table-responsive">
<table class="table table-bordered" border="1"">
<tr>
<th>Name</th>
<td>'.$sr['sname'].'</td>
<th>Course</th>
<td>'.$sr['grade'].' '.$sr['yearofstart'].'-'.$sr['yearofend'].'</td>
</tr>
<tr>
<th>Contact</th>
<td>'.$sr['contact'].'</td>
<th>Email Id</th>
<td>'.$sr['emailid'].'</td>
</tr>
<!--- <th>Semester</th>
<td>
<select class="form-control" name="sem" id="sem">
      <option value="">Select semester</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
    </select>
</td>
</tr> ---!>


</table>
</div>
';

echo '
<h4>Fee Info</h4>
<div class="table-responsive">
    <table class="table table-bordered" border="1">
        <thead>
            <tr>
                <th>Reciept No</th>
                <th>Date</th>
                <th>Payment Mode</th>
                <th>Amount</th>
                <th>Fee Type</th>';
                if ($_SESSION['typeUser'] == "admin") {
                  echo '<th>Action</th>';
              }
            echo '</tr>
        </thead>
        <tbody>';
$totapaid = 0;
while ($res = $fq->fetch_assoc()) {
    $totapaid += $res['paid'];
    $balance = ($sr['fees'] - $totapaid);
    echo '<tr>
            <td>' . $res['recno'] . '</td>
            <td>' . date("d-m-Y", strtotime($res['submitdate'])) . '</td>
            <td>' . $res['paymentmode'].'<br>'.$res['transid'] .$res['checkno']. '</td>
            <td>' . $res['paid'] . '</td>
            <td>' . $res['transcation_remark'] . '</td>';
            if($_SESSION['typeUser']=="admin"){
            echo '<td>
                <a id=' . $res['id'] . ' class="btn btn-success btn-xs" style="border-radius:60px;" onclick="edit(id)">
                    <span class="glyphicon glyphicon-edit"></span>
                </a>
                ';
                // <a href="#" class="btn btn-danger btn-xs" style="border-radius:60px;" onclick="deleteTransaction(' . $res['id'] . ')">
                //     <span class="glyphicon glyphicon-trash"></span>
                // </a>
            echo '</td>';
            }
          echo '</tr>';
}
$sql = "UPDATE student SET balance = '$balance' WHERE id = '$stdid'";
$conn->query($sql);      
echo '	  
    </tbody>
  </table>
 </div> 
<table style="width:200px;" >
<tr>
<th>Total Fees: 
</th>
<td>'.'Rs. '.$sr['fees'].'
</td>
</tr>

<tr>
<th>Total Paid: 
</th>
<td>'.'Rs. '.$totapaid.'
</td>
</tr>

<tr>
<th>Balance: 
</th>
<td>'.'Rs. '.$balance.'
</td>
</tr>
</table>
</div>';
 }
else
{
echo 'No fees submit.';
}
 
}

//updatation queryyy
if (isset($_POST['req']) && $_POST['req'] == 'edit_payment') {
  $transactionId = (isset($_POST['transaction_id'])) ? mysqli_real_escape_string($conn, $_POST['transaction_id']) : '';
  $newAmount = (isset($_POST['new_amount'])) ? mysqli_real_escape_string($conn, $_POST['new_amount']) : '';

  $sql = "UPDATE fees_transaction SET paid = '$newAmount' WHERE id = '$transactionId'";
  if ($conn->query($sql)) {
      echo json_encode(['success' => true]);
  } else {
      echo json_encode(['success' => false, 'error' => $conn->error]);
  }
  exit();
}
//deletew
if (isset($_POST['req']) && $_POST['req'] == 'delete_transaction') {
  $transactionId = (isset($_POST['transaction_id'])) ? mysqli_real_escape_string($conn, $_POST['transaction_id']) : '';

  $deleteSql = "DELETE FROM fees_transaction WHERE id = '$transactionId'";
  if ($conn->query($deleteSql)) {
      echo json_encode(['success' => true]);
      exit();
  } else {
      echo json_encode(['success' => false, 'error' => $conn->error]);
      exit();
  }
}


?>


<script>
  function edit(id) {
    var newAmount = parseInt(prompt("Enter the new payment amount"));
    if (!isNaN(newAmount)) {
      $.ajax({
        type: "POST",
        url: "getfeeform.php",
        data: {
          req: 'edit_payment',
          transaction_id: id,
          new_amount: newAmount
        },
        dataType: 'json',
        success: function (response) {
          if (response.success) {
            alert('Payment amount updated successfully');
            location.reload();
    //         window.onload = function() {
    //         location.reload();
    //     $('#myModal').modal('show');
    // };
            
            //document.getElementById("paid_" + id).innerHTML = newAmount;
           
          } else {
            alert('Failed to update payment amount. Error: ' + response.error);
          }
        
        },
        error: function () {
          alert('An error occurred while processing the request.');
        }
      });
    }
  }

//delete scripy
function deleteTransaction(transactionId) {
    if (confirm("Are you sure you want to delete this?")) {
        $.ajax({
            type: "POST",
            url: "getfeeform.php",
            data: {
                req: 'delete_transaction',
                transaction_id: transactionId,
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    location.reload(); // Reload the page after successful deletion
                    alert('Deleted Successfully');
                } else {
                    alert('Failed to delete. Error: ' + response.error);
                }
            },
            error: function () {
                alert('An error occurred while processing the request.');
            }
        });
    }
}


    function showTextBox(paymentMode) {
            var onlineTextBox = document.getElementById('onlineTextBox');
            var checkTextBox = document.getElementById('checkTextBox');
            onlineTextBox.style.display = (paymentMode === 'Online') ? 'block' : 'none';
            checkTextBox.style.display = (paymentMode === 'Cheque') ? 'block' : 'none';
        }

        function updateAmount() {
        var selectedFee = document.getElementById('transcation_remark').value;
        var amountTextbox = document.getElementById('paid');
<?php
 $sql = "SELECT * FROM student WHERE id = $sid";
 $result = $conn->query($sql);

 if ($result->num_rows > 0) {
     $row = $result->fetch_assoc();
     $classFee = $row['classfee']-$row['classpaid'];
     $busFee = $row['busfee']-$row['buspaid'];
     $vanFee = $row['vanfee']-$row['vanpaid'];
     $skillFee = $row['skillfee']-$row['skillpaid'];
     $otherFee = $row['otherfee']-$row['otherpaid'];
 }
?>
        switch (selectedFee) {
          case 'Semester Fees':
                amountTextbox.value = <?php echo $classFee; ?>; // Replace this with the actual amount for Bus Fees
                break;
            case 'Bus Fees':
                amountTextbox.value = <?php echo $busFee; ?>; // Replace this with the actual amount for Bus Fees
                break;
            case 'Van Fees':
                amountTextbox.value = <?php echo $vanFee; ?> // Replace this with the actual amount for Van Fees
                break;
            case 'Skill Fees':
                amountTextbox.value = <?php echo $skillFee; ?>// Replace this with the actual amount for Skill Fees
                break;
            case 'Others':
                amountTextbox.value = <?php echo $otherFee; ?>// Replace this with the actual amount for Skill Fees
                break;
            default:
                amountTextbox.value = ''; // Clear the amount if no fee type is selected or for other fee types
                break;
        }
    }
</script>
