<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Fees Form</title>
<!-- Add Bootstrap CSS link here -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
</head>
<body>

<!-- Your PHP code goes here -->

<!-- jQuery and Bootstrap JS scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

<!-- Bootstrap Modal for Confirmation -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="confirmationMessage" style="font-size: 18px;"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="proceedWithSubmission()">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to display confirmation modal with form data
    function showConfirmation() {
        var reg = document.getElementById("roll").value;
        var name = document.getElementById("sname").value;
        var grade = document.getElementById("grade").value;
        var paid = document.getElementById("paid").value;
        var transcation_remark = document.getElementById("transcation_remark").value;

        var confirmationMessage = "Reg No: " + reg + "<br>";
        confirmationMessage += "Student Name: " + name + "<br>";
        confirmationMessage += "Course: " + grade + "<br>";
        confirmationMessage += "Paid Amount: " + paid + "<br>";
        confirmationMessage += "Fee Type: " + transcation_remark + "<br><br>";
        confirmationMessage += "Are you sure you want to submit this payment?";

        document.getElementById("confirmationMessage").innerHTML = confirmationMessage;
        $('#confirmationModal').modal('show'); // Show the Bootstrap modal
    }

    // Function to proceed with form submission
    function proceedWithSubmission() {
        // Add your form submission logic here
        document.getElementById("signupForm1").submit(); // Submit the form
    }
</script>

<!-- Your HTML form code goes here -->
<!-- Make sure to include the form within <form> tags with id="signupForm1" and action="fees.php" -->

</body>
</html>
