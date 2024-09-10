<script>
    function DiscontForm(studentId) {
    $.ajax({
        type: "POST",
        url: "get_student_details.php",
        data: { student_id: studentId },
        dataType: "json",
        success: function(response) {
            if (response.error) {
                alert(response.error); // Display any error messages
            } else {
                // Populate modal form with student details
                $('#student_id').val(response.id);
                $('#student_reg').val(response.roll_no);
                $('#student_name').val(response.sname);
                $('#student_sem').val(response.sem);
                $('#student_fees').val(response.fees);
                $('#class_grade').val(response.grade + ' ' + response.yearofstart + '-' + response.yearofend);
                $('#discontinue_reason').val(''); // Clear any previous input in the reason field
                $('#refund_amount').val(''); // Clear any previous input in the refund amount field

                // Show the modal
                $('#discontinueFormModal').modal('show');
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('An error occurred. Please try again.');
        }
    });
}

</script>

<!-- Include jQuery library and jQuery Validate plugin -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>



<div class="modal fade" id="discontinueFormModal" tabindex="-1" role="dialog" aria-labelledby="discontinueFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="color:red;">&times;</button>
                <h4 class="modal-title">Discontinue Student</h4>
            </div>
            <div class="modal-body">
                <form id="discontinueForm" method="POST" action="discont.php" class="form-horizontal">
                    <div class="form-group">
                        <label for="student_reg" class="control-label col-sm-3">Reg No:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="student_reg" name="student_reg" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="student_name" class="control-label col-sm-3">Student Name:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="student_name" name="student_name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="class_grade" class="control-label col-sm-3">Course:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="class_grade" name="class_grade" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="student_sem" class="control-label col-sm-3">Semester:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="student_sem" name="student_sem" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="student_fees" class="control-label col-sm-3">Total Fees:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="student_fees" name="student_fees" required>
                            <input type="hidden" name="student_id" id="student_id">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="discontinue_reason" class="control-label col-sm-3">Reason for Discontinuation:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="discontinue_reason" name="discontinue_reason" required></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="refund_amount" class="control-label col-sm-3">Refund Amount:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="refund_amount" name="refund_amount" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date" class="control-label col-sm-3">Date:</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="submitdate" name="submitdate" style="background:#fff;" required/>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="save" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
        $(document).ready(function () {
            // Function to format the current date as YYYY-MM-DD
            function getCurrentDate() {
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
                var yyyy = today.getFullYear();
                return yyyy + '-' + mm + '-' + dd;
            }

            // Set the current date to the date field when the form is opened
            $('#discontinueFormModal').on('shown.bs.modal', function () {
                var currentDate = getCurrentDate();
                $('#submitdate').val(currentDate);
            });
        
        $.validator.addMethod("maxRefundAmount", function (value, element) {
            var maxAmount = parseFloat($("#student_fees").val());
            var refundAmount = parseFloat(value);
            return refundAmount <= maxAmount;
        }, "Refund amount must not exceed the total fees amount.");

        // Form validation using jQuery Validate plugin
        $("#discontinueForm").validate({
            rules: {
                refund_amount: {
                    required: true,
                    digits: true,
                    min: 0,
                    maxRefundAmount: true // Use custom validation method
                }
            },
            messages: {
                refund_amount: {
                    min: "Refund amount must be greater than 0"
                }
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                error.addClass("help-block");
                error.insertAfter(element);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
            }
        });

        // Additional validation logic
        $("#discontinueForm").submit(function (e) {
            if (!$("#discontinueForm").valid()) {
                e.preventDefault(); // Prevent form submission if validation fails
            } else {
                // Show confirmation dialog or perform additional checks
                if (!showConfirmation()) {
                    e.preventDefault(); // Prevent form submission if confirmation is not received
                }
            }
        });

        // Function to show confirmation dialog
        function showConfirmation() {
            return confirm("Are you sure you want to discontinue this student?");
        }
    });
</script>