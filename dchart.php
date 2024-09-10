<?php
// Retrieve data for the first chart
$sql = "SELECT * FROM student WHERE delete_status='0'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Initialize arrays to store chart data
$labels = [];
$balances = [];
$busFees = [];
$vanFees = [];
$skillFees = [];
$otherFees = [];
$classFees = []; // Initialize classFees array

// Fetch data for the first chart
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['sname'];
        $balances[] = $row['balance'];
        $classFees[] = $row['classfee']; // Add class fee data to the array
        $busFees[] = $row['busfee'];
        $vanFees[] = $row['vanfee'];
        $skillFees[] = $row['skillfee'];
        $otherFees[] = $row['otherfee'];
    }
}

// Prepare data for the first chart
$chartData = [
    'labels' => $labels,
    'balances' => $balances,
    'classFees' => $classFees, // Corrected variable name
    'busFees' => $busFees,
    'vanFees' => $vanFees,
    'skillFees' => $skillFees,
    'otherFees' => $otherFees
];

// Count fee types for the pie chart
$sql = "SELECT SUM(busfee > 0) AS busFeeCount, SUM(classfee > 0) AS classFeeCount, SUM(vanfee > 0) AS vanFeeCount, SUM(skillfee > 0) AS skillFeeCount, SUM(otherfee > 0) AS otherFeeCount FROM student WHERE delete_status='0'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pieChartData = [
        'labels' => ['Class Fee','Bus Fee', 'Van Fee', 'Skill Fee', 'Other Fee'],
        'data' => [$row['classFeeCount'],$row['busFeeCount'], $row['vanFeeCount'], $row['skillFeeCount'], $row['otherFeeCount']], // Corrected variable names
        'backgroundColor' => ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)', 'rgba(25, 195, 125, 0.5)','rgba(153, 102, 255, 0.5)'],
        'borderColor' => ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(25, 195, 125, 1)','rgba(153, 102, 255, 1)'],
    ];
}

// Retrieve gender distribution data
$sqlGender = "SELECT gender, COUNT(*) AS count FROM student WHERE delete_status='0' GROUP BY gender";
$stmtGender = $conn->prepare($sqlGender);
$stmtGender->execute();
$resultGender = $stmtGender->get_result();

$genderLabels = [];
$genderData = [];

if ($resultGender->num_rows > 0) {
    while ($row = $resultGender->fetch_assoc()) {
        $genderLabels[] = $row['gender'];
        $genderData[] = $row['count'];
    }
}

$genderChartData = [
    'labels' => $genderLabels,
    'data' => $genderData,
    'backgroundColor' => ['rgba(255, 159, 64, 0.5)', 'rgba(75, 192, 192, 0.5)'], // You can add more colors as needed
    'borderColor' => ['rgba(255, 159, 64, 1)', 'rgba(75, 192, 192, 1)'] // You can add more colors as needed
];

// SQL query to retrieve payment mode counts
$sqlPaymentMode = "SELECT paymentmode, COUNT(*) AS modeCount FROM fees_transaction  GROUP BY paymentmode";

// Prepare and execute the SQL query
$stmtPaymentMode = $conn->prepare($sqlPaymentMode);
$stmtPaymentMode->execute();
$resultPaymentMode = $stmtPaymentMode->get_result();

// Initialize arrays to store chart data
$paymentModes = [];
$modeCounts = [];

// Fetch data for payment mode chart
if ($resultPaymentMode->num_rows > 0) {
    while ($row = $resultPaymentMode->fetch_assoc()) {
        $paymentModes[] = $row['paymentmode'];
        $modeCounts[] = $row['modeCount'];
    }
}

// Chart data for payment mode chart
$paymentModeChartData = [
    'labels' => $paymentModes,
    'data' => $modeCounts,
    'backgroundColor' => ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)'],
    'borderColor' => ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
];

// Retrieve grade distribution data
$sqlGrade = "SELECT CONCAT(g.grade, ' (', g.yearofstart, '-', g.yearofend, ')') AS course, COUNT(DISTINCT s.roll_no) AS count
            FROM student s
            JOIN grade g ON s.grade = g.id
            WHERE s.delete_status='0'
            GROUP BY s.grade";

$stmtGrade = $conn->prepare($sqlGrade);
$stmtGrade->execute();
$resultGrade = $stmtGrade->get_result();


$gradeLabels = [];
$gradeData = [];

if ($resultGrade->num_rows > 0) {
    while ($row = $resultGrade->fetch_assoc()) {
        $gradeLabels[] = $row['course'];
        $gradeData[] = $row['count'];
    }
}

$gradeChartData = [
    'labels' => $gradeLabels,
    'data' => $gradeData,
    'backgroundColor' => [
        'rgba(255, 99, 132, 0.5)', 
        'rgba(54, 162, 235, 0.5)', 
        'rgba(255, 206, 86, 0.5)', 
        'rgba(75, 192, 192, 0.5)',
        'rgba(153, 102, 255, 0.5)',
        'rgba(255, 159, 64, 0.5)',
        'rgba(255, 0, 0, 0.5)',
        'rgba(0, 255, 0, 0.5)',
        'rgba(0, 0, 255, 0.5)',
        'rgba(128, 128, 128, 0.5)'
    ],
    'borderColor' => [
        'rgba(255, 99, 132, 1)', 
        'rgba(54, 162, 235, 1)', 
        'rgba(255, 206, 86, 1)', 
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        'rgba(255, 0, 0, 1)',
        'rgba(0, 255, 0, 1)',
        'rgba(0, 0, 255, 1)',
        'rgba(128, 128, 128, 1)'
    ]
];
// SQL query to retrieve transaction data
$sqlTransaction = "SELECT DATE_FORMAT(submitdate, '%Y-%m-%d') AS submitdate, SUM(paid) AS total_amount
                   FROM fees_transaction 
                   GROUP BY DATE_FORMAT(submitdate, '%Y-%m-%d')";


// Prepare and execute the SQL query for transaction data
$stmtTransaction = $conn->prepare($sqlTransaction);
$stmtTransaction->execute();
$resultTransaction = $stmtTransaction->get_result();

// Initialize arrays to store transaction chart data
$transactionLabels = [];
$transactionAmounts = [];

// Fetch data for transaction chart
if ($resultTransaction->num_rows > 0) {
    while ($row = $resultTransaction->fetch_assoc()) {
        $transactionLabels[] = $row['submitdate'];
        $transactionAmounts[] = $row['total_amount'];
    }
}

// Retrieve data for bus routes
$sqlBus = "SELECT s.id, s.roll_no, s.emailid, s.sname, s.gender, s.dob, s.sem, s.about, s.contact, s.classfee, s.bus, s.balance, bf.from_location, bf.to_location
        FROM student s
        INNER JOIN bus_fees bf ON s.bus = bf.id
        WHERE s.delete_status = '0'
        GROUP BY bf.id";
$stmtBus = $conn->prepare($sqlBus);
$stmtBus->execute();
$resultBus = $stmtBus->get_result();

// Initialize arrays to store chart data
$busRoutes = [];

// Fetch data for bus routes
if ($resultBus->num_rows > 0) {
    while ($row = $resultBus->fetch_assoc()) {
        $busRoutes[] = $row['from_location'] . ' to ' . $row['to_location'];
    }
}

// Count occurrences of each route
$busRouteCounts = array_count_values($busRoutes);

// Prepare data for the chart
$busLabels = array_keys($busRouteCounts);
$busData = array_values($busRouteCounts);

// Retrieve data for van routes
$sqlVan = "SELECT s.id, s.roll_no, s.emailid, s.sname, s.gender, s.dob, s.sem, s.about, s.contact, s.classfee, s.van, s.balance, vf.from_location, vf.to_location
        FROM student s
        INNER JOIN van_fees vf ON s.van = vf.id
        WHERE s.delete_status = '0'
        GROUP BY vf.id";
$stmtVan = $conn->prepare($sqlVan);
$stmtVan->execute();
$resultVan = $stmtVan->get_result();

// Initialize arrays to store chart data
$vanRoutes = [];

// Fetch data for van routes
if ($resultVan->num_rows > 0) {
    while ($row = $resultVan->fetch_assoc()) {
        $vanRoutes[] = $row['from_location'] . ' to ' . $row['to_location'];
    }
}

// Count occurrences of each route
$vanRouteCounts = array_count_values($vanRoutes);

// Prepare data for the chart
$vanLabels = array_keys($vanRouteCounts);
$vanData = array_values($vanRouteCounts);

// SQL query to retrieve skill-wise student count
$sqlSkill = "SELECT sk.skill_name, COUNT(s.id) AS student_count
             FROM student s
             INNER JOIN skill_fees sk ON s.skill = sk.id
             WHERE s.delete_status = '0'
             GROUP BY sk.skill_name";
$stmtSkill = $conn->prepare($sqlSkill);
$stmtSkill->execute();
$resultSkill = $stmtSkill->get_result();

// Initialize arrays to store chart data
$skillLabels = [];
$skillCounts = [];

// Fetch data for skill chart
if ($resultSkill->num_rows > 0) {
    while ($row = $resultSkill->fetch_assoc()) {
        $skillLabels[] = $row['skill_name'];
        $skillCounts[] = $row['student_count'];
    }
}

// SQL query to retrieve community distribution data
$sqlCommunity = "SELECT about, COUNT(*) AS count FROM student WHERE delete_status='0' GROUP BY about";
$stmtCommunity = $conn->prepare($sqlCommunity);
$stmtCommunity->execute();
$resultCommunity = $stmtCommunity->get_result();

$communityLabels = [];
$communityData = [];

if ($resultCommunity->num_rows > 0) {
    while ($row = $resultCommunity->fetch_assoc()) {
        $communityLabels[] = $row['about'];
        $communityData[] = $row['count'];
    }
}

$communityChartData = [
    'labels' => $communityLabels,
    'data' => $communityData,
    'backgroundColor' => ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)', 'rgba(75, 192, 192, 0.5)', 'rgba(153, 102, 255, 0.5)', 'rgba(255, 159, 64, 0.5)', 'rgba(255, 0, 0, 0.5)', 'rgba(0, 255, 0, 0.5)', 'rgba(0, 0, 255, 0.5)', 'rgba(128, 128, 128, 0.5)'],
    'borderColor' => ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)', 'rgba(255, 0, 0, 1)', 'rgba(0, 255, 0, 1)', 'rgba(0, 0, 255, 1)', 'rgba(128, 128, 128, 1)']
];
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
    <link href="css/datepicker.css" rel="stylesheet" />
    <link href="css/nav.css" rel="stylesheet" />
    <script src="js/jquery-1.10.2.js"></script>
    <script type='text/javascript' src='js/jquery/jquery-ui-1.10.1.custom.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
</head>

<body>
    <style>
        
        .navigation-bar .list-items .item.active .link {
    color: black;
    border-bottom: 2px solid #6719bd;
    /* Add any other styles to indicate the active state */
}
        </style>
        <!-- Chart Containers -->
        

        <div class="chart-container" id="pieChart" style="display: none;width: 800px;">
            <h2 align="center">Number of Students with Fees</h2>
            <canvas id="feePieChart"></canvas>
        </div>

        <div class="chart-container" id="genderChart" style="display: none;width: 800px;">
            <h2 align="center">Gender Distribution</h2>
            <canvas id="genderChartCanvas"></canvas>
        </div>
        <div class="chart-container" id="commChart" style="display: none;width: 800px;">
            <h2 align="center">Community Distribution</h2>
            <canvas id="commChartCanvas"></canvas>
        </div>

        <div class="chart-container" id="gradeChart" style="display: none;width: 800px;">
            <h2 align="center">Course Distribution</h2>
            <canvas id="gradeChartCanvas"></canvas>
        </div>
        <div class="chart-container" id="payChart" style="display: none;width: 800px;">
            <h2 align="center">Total Payment Count</h2>
            <canvas id="paymentModeChart"></canvas>
        </div>
        <div class="chart-container" id="transChart" style="width: 800px;">
            <h2 align="center">Total Transaction Count</h2>
            <canvas id="transactionChart"></canvas>
        </div>
        <div class="chart-container" id="busChart" style="display: none;width: 800px;">
            <h2 align="center">Total Bus Route</h2>
            <canvas id="busRouteChart" ></canvas>
        </div>
        <div class="chart-container" id="vanChart" style="display: none;width: 800px;">
            <h2 align="center">Total Van Route</h2>
            <canvas id="vanRouteChart" ></canvas>
        </div>
        <div class="chart-container" id="skillChart" style="display: none;width: 800px;">
            <h2 align="center">Skill Chart </h2>
            <canvas id="skillChartCanvas" ></canvas>
        </div>
        <div class="chart-container" id="barChart">
            <h2 align="center">Total Students Fees</h2>
            <canvas id="studentChart"></canvas>
        </div>
        
        <script>
            
//   document.addEventListener("DOMContentLoaded", function() {
//     const chartContainers = document.querySelectorAll(".chart-container");
//     let currentIndex = 0;

//     function showNextChart() {
//       chartContainers.forEach((container, index) => {
//         if (index === currentIndex) {
//           container.style.display = "block";
//         } else {
//           container.style.display = "none";
//         }
//       });

//       currentIndex++;
//       if (currentIndex >= chartContainers.length) {
//         currentIndex = 0; // Reset index to loop through charts again
//       }
//     }

//     // Initial call to show the first chart
//     showNextChart();

//     // Automatically switch charts every 2 seconds
//     setInterval(showNextChart, 2000);
//   });


    // Function to toggle chart visibility and activate navigation item
    function showChart(chartType) {
        // Hide all chart containers
        document.querySelectorAll('.chart-container').forEach(function(chartContainer) {
            chartContainer.style.display = 'none';
        });
        // Show the selected chart container
        document.getElementById(chartType).style.display = 'block';

        // Activate the corresponding navigation item
        document.querySelectorAll('.list-items .item').forEach(function(item) {
            item.classList.remove('active');
        });
        document.querySelector('.list-items .item a[href="#' + chartType + '"]').parentNode.classList.add('active');
    }
</script>


<script>
        // Chart data from PHP
        var paymentModeData = <?php echo json_encode($paymentModeChartData); ?>;

// Create chart
var paymentModeCtx = document.getElementById('paymentModeChart').getContext('2d');
var paymentModeChart = new Chart(paymentModeCtx, {
    type: 'pie',
    data: {
        labels: paymentModeData.labels,
        datasets: [{
            label: 'Payment Mode Count',
            data: paymentModeData.data,
            backgroundColor: paymentModeData.backgroundColor,
            borderColor: paymentModeData.borderColor,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        legend: {
            position: 'bottom'
        }
    }
});



        var pieChartData = <?php echo json_encode($pieChartData); ?>;
        var ctx = document.getElementById('feePieChart').getContext('2d');

        var feePieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: pieChartData.labels,
                datasets: [{
                    data: pieChartData.data,
                    backgroundColor: pieChartData.backgroundColor,
                    borderColor: pieChartData.borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'bottom'
                }
            }
        });
    </script>

        <script>
            // Chart data from PHP
            var pieChartData = <?php echo json_encode($pieChartData); ?>;
            var chartData = <?php echo json_encode($chartData); ?>;
            var genderChartData = <?php echo json_encode($genderChartData); ?>;
            var gradeChartData = <?php echo json_encode($gradeChartData); ?>;

            // Create charts
            var ctx = document.getElementById('studentChart').getContext('2d');
            var genderCtx = document.getElementById('genderChartCanvas').getContext('2d');
            var gradeCtx = document.getElementById('gradeChartCanvas').getContext('2d');

            var studentChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Balance',
                            data: chartData.balances,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Class Fee',
                            data: chartData.classFees,
                            backgroundColor: 'rgba(255, 159, 64, 0.5)', // Adjust color as needed
                            borderColor: 'rgba(255, 159, 64, 1)', // Adjust color as needed
                            borderWidth: 1
                        },
                        {
                            label: 'Bus Fee',
                            data: chartData.busFees,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Van Fee',
                            data: chartData.vanFees,
                            backgroundColor: 'rgba(255, 206, 86, 0.5)',
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Skill Fee',
                            data: chartData.skillFees,
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Other Fee',
                            data: chartData.otherFees,
                            backgroundColor: 'rgba(153, 102, 255, 0.5)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        }
                    ]
                },
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

            var genderChart = new Chart(genderCtx, {
                type: 'doughnut',
                data: {
                    labels: genderChartData.labels,
                    datasets: [{
                        data: genderChartData.data,
                        backgroundColor: genderChartData.backgroundColor,
                        borderColor: genderChartData.borderColor,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'bottom'
                    }
                }
            });

            var gradeChart = new Chart(gradeCtx, {
                type: 'bar',
                data: {
                    labels: gradeChartData.labels, // Updated labels with 'Grade' prefix
                    datasets: [{
                        label: 'Number of Students',
                        data: gradeChartData.data,
                        backgroundColor: gradeChartData.backgroundColor,
                        borderColor: gradeChartData.borderColor,
                        borderWidth: 1
                    }]
                },
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
    // Extracting data from JSON response
    var transactionLabels = <?php echo json_encode($transactionLabels); ?>;
            var transactionAmounts = <?php echo json_encode($transactionAmounts); ?>;

    // Creating the chart
    var transactionCtx = document.getElementById('transactionChart').getContext('2d');
    var transactionChart = new Chart(transactionCtx, {
                type: 'line',
                data: {
                    labels: transactionLabels,
                    datasets: [{
                        label: 'Transaction Amount',
                        data: transactionAmounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'bottom'
                    }
                }
            });

           // Chart data from PHP
        var busRouteData = <?php echo json_encode(['labels' => $busLabels, 'data' => $busData]); ?>;
        var vanRouteData = <?php echo json_encode(['labels' => $vanLabels, 'data' => $vanData]); ?>;
        
        // Create chart
        var busRouteCtx = document.getElementById('busRouteChart').getContext('2d');
        var vanRouteCtx = document.getElementById('vanRouteChart').getContext('2d');
        
        var busRouteChart = new Chart(busRouteCtx, {
            type: 'bar',
            data: {
                labels: busRouteData.labels,
                datasets: [{
                    label: 'Number of Students',
                    data: busRouteData.data,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true
                    }
                }
            }
        });
        
        var vanRouteChart = new Chart(vanRouteCtx, {
            type: 'bar',
            data: {
                labels: vanRouteData.labels,
                datasets: [{
                    label: 'Number of Students',
                    data: vanRouteData.data,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true
                    }
                }
            }
        });

        // Skill chart data from PHP
    var skillChartData = <?php echo json_encode(['labels' => $skillLabels, 'data' => $skillCounts]); ?>;

// Create skill chart
var skillCtx = document.getElementById('skillChartCanvas').getContext('2d');
var skillChart = new Chart(skillCtx, {
    type: 'bar',
    data: {
        labels: skillChartData.labels,
        datasets: [{
            label: 'Number of Students',
            data: skillChartData.data,
            backgroundColor: 'rgba(255, 99, 132, 0.5)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        legend: {
            display: false
        },
        scales: {
            xAxes: [{
                ticks: {
                    autoSkip: false
                }
            }],
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }]
        }
    }
});
var communityChartData = <?php echo json_encode($communityChartData); ?>;

// Create chart
var commCtx = document.getElementById('commChartCanvas').getContext('2d');
var commChart = new Chart(commCtx, {
    type: 'bar',
    data: {
        labels: communityChartData.labels,
        datasets: [{
            label: 'Number of Students',
            data: communityChartData.data,
            backgroundColor: communityChartData.backgroundColor,
            borderColor: communityChartData.borderColor,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        legend: {
            position: 'bottom'
        },
        scales: {
            xAxes: [{
                ticks: {
                    autoSkip: false
                }
            }],
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }]
        }
    }
});
        </script>
    </div>
</body>
</html>
