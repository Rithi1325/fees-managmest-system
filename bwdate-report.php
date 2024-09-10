<?php
$page = 'search report';
include("php/dbconnect.php");
include("php/checklogin.php");
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $d1 = $_POST['d1'];
    $d2 = $_POST['d2'];

    // Validate date inputs
    if (!isValidDate($d1) || !isValidDate($d2)) {
        $error = "Please enter a correct date format!";
    }

    $query = "SELECT DISTINCT student.sname, student.roll_no, student.grade, student.sem, student.contact, fees_transaction.recno, fees_transaction.paymentmode, fees_transaction.transid, fees_transaction.checkno, fees_transaction.submitdate, fees_transaction.paid, fees_transaction.transcation_remark
    FROM fees_transaction
    JOIN student ON fees_transaction.stdid = student.id
    WHERE DATE(fees_transaction.submitdate) BETWEEN ? AND ?";


    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $d1, $d2);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
} 
elseif (isset($_POST['reset'])) {
    $d1 = '';
    $d2 = '';
}
else {
    // Set default values or handle this part based on your needs
    $d1 = '';
    $d2 = '';
}

function isValidDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') == $date;
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fees Management System For SSB college - Kommadikottai</title>
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/basic.css" rel="stylesheet" />
    <link href="css/custom.css" rel="stylesheet" />
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
<body>
    <?php
    include("php/header.php");
    ?>

    <div id="page-wrapper">
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-head-line">Search Reports</h1>
                </div>
            </div>
            <?php
            if (!empty($error)) {
                echo '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' . $error . '</div>';
            }
            ?>

            <div class="row" style="margin-bottom:20px;">
                <div class="col-md-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Search:</legend>
                        <form class="form-inline" role="form" id="searchform" method="POST">
                            <div class="form-group">
                                <label for="email">From Date </label>
                                <input type="date" class="form-control" id="d1" name="d1" value="<?php echo isset($d1) ? $d1 : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="email">To Date </label>
                                <input type="date" class="form-control" id="d2" name="d2" value="<?php echo isset($d2) ? $d2 : ''; ?>">
                            </div>

                            <button type="submit" class="btn btn-success btn-sm" style="border-radius:0%" id="search" name="search">Search</button>
                            <button type="reset" class="btn btn-danger btn-sm" style="border-radius:0%" id="clear">Reset</button>
                        </form>
                    </fieldset>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Search Report
                </div>
                <div class="panel-body">
                    <div class="table-sorting table-responsive" id="print">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name/Reg No</th>
                                    <th>Course</th>
                                    <th>Semester</th>
                                    <th>Reciept No</th>
                                    <th>Payment Mode</th>
                                    <th>Amount</th>
                                    <th>Fees Type</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if (isset($result)) {
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($result)) {
                                        $sql1 = $conn->query("SELECT * FROM grade WHERE id = " . $row['grade'] . "");
                                        $r = $sql1->fetch_assoc();
                                ?>
                                        <tr>
                                            <th scope="row"><?php echo $cnt; ?></th>
                                            <td><?php echo $row['sname'] . '<br/>' . $row['roll_no']; ?></td>
                                            <td><?php echo $r['grade'] . " " . $r['yearofstart'] . "-" . $r['yearofend']; ?></td>
                                            <td><?php echo $row['sem']; ?> </td>
                                            <td><?php echo $row['recno']; ?></td>
                                            <td><?php echo $row['paymentmode'] . '<br>' . $row['transid'] . $row['checkno']; ?></td>
                                            <td><?php echo $row['paid']; ?></td>
                                            <td><?php echo $row['transcation_remark']; ?></td>
                                            <td><?php echo date("d-m-Y", strtotime($row['submitdate'])); ?></td>
                                        </tr>
                                <?php
                                        $cnt = $cnt + 1;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <p style="margin-top:1%" align="center">
                    <i class="fa fa-print fa-2x" style="cursor: pointer;" onclick="CallPrint()"></i>
                    </p>
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    $('#table').dataTable({
                        "bPaginate": true,
                        "bLengthChange": true,
                        "bFilter": true,
                        "bInfo": true,
                        "bAutoWidth": true
                    });

                    $('#clear').click(function () {
                        $('#searchform')[0].reset();
                        $('#searchform input[name=reset]').val(1);
                        $('#searchform').submit();
                    });
                });

                function CallPrint() {
            var prtContent = document.getElementById("print").innerHTML;
            var WinPrint = window.open("", "", "height=1000,width=1000");
            WinPrint.document.write("<html><head><title>Print Report</title>");
            // WinPrint.document.write("<link rel='stylesheet' href='css/print.css' type='text/css' media='print'/>");
            WinPrint.document.write("<style>table {width: 100%; border-collapse: collapse;} th, td {border: 1px solid #ddd; padding: 8px; text-align: left;} th { background-color: #f2f2f2;}</style></head><body>");
            WinPrint.document.write(prtContent);
            WinPrint.document.write("</body></html>");
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        }
            </script>
        </div>
    </div>

    <script src="js/bootstrap.js"></script>
    <script src="js/jquery.metisMenu.js"></script>
    <script src="js/custom1.js"></script>
</body>
</html>
