
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">SSB Fees Management</a>
            </div>
            <div class="text-center">
                <h3 style="color:white;">Sri Sankara Bhagavathi Arts And Science College, Kommadikottai </h3>
            </div>
        </nav>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li>
                        <div class="user-img-div text-center">
                            <img src="img/admin-p.png" class="img" />
                            <h5 style="color:white;"><?php echo $_SESSION['rainbow_name'];?></h5>
                        </div>

                    </li>


                    <li>
                        <a class="<?php if($page=='dashboard'){ echo 'active-menu';}?>" href="index.php"><i class="fa fa-dashboard "></i>Dashboard</a>
                    </li>
					
					 <li>
                        <a class="<?php if($page=='student'){ echo 'active-menu';}?>" href="student.php"><i class="fa fa-users "></i>Student Management</a>
                    </li>
                    <?php
                    if($_SESSION['typeUser']=="admin"){
                        
                    ?>
                    <li>
                        <a class="<?php if($page=='inact'){ echo 'active-menu';}?>" href="inactivestd.php"><i class="fa fa-graduation-cap "></i>Passed-out Students</a>
                    </li>

                    <li>
                        <a class="<?php if($page=='discontinue'){ echo 'active-menu';}?>" href="discont.php"><i class="fa fa-toggle-off "></i>Discontinue Students</a>
                    </li>

                    <li>
                        <a class="<?php if($page=='course'){ echo 'active-menu';}?>" href="class.php"><i class="fa fa-th-large"></i>Course Fee Management</a>
                    </li>

                    <li>
                        <a class="<?php if($page=='inactclass'){ echo 'active-menu';}?>" href="inactiveclass.php"><i class="fa fa-toggle-off "></i>In-Active Course</a>
                    </li>

                    <li>
                        <a class="<?php if($page=='fees_structure'){ echo 'active-menu';}?>" href="fees_structure.php"><i class="fa fa-list "></i>Fees Structure</a>
                    </li>
                    <?php
                    }
                    ?>
                    
					<li>
                        <a class="<?php if($page=='fees'){ echo 'active-menu';}?>" href="fees.php"><i class="fa fa-rupee "></i>Pay Fees</a>
                    </li>
					 <li>
                        <a class="<?php if($page=='report'){ echo 'active-menu';}?>" href="report.php"><i class="fa fa-file-text-o "></i>Fees Invoice Report</a>
                    </li>
                    <li>
                        <a class="<?php if($page=='unpaid students'){ echo 'active-menu';}?>" href="unpaid_std.php"><i class="fa fa-exclamation-triangle"></i>Unpaid Students</a>
                    </li>
					<li>
                        <a class="<?php if($page=='search report'){ echo 'active-menu';}?>" href="bwdate-report.php"><i class="fa fa-search "></i>Search Report</a>
                    </li>
                    <li>
                        <a class="<?php if($page=='search student'){ echo 'active-menu';}?>" href="search_std.php"><i class="fa fa-search "></i>Search Student</a>
                    </li>
                    <li>
                        <a class="<?php if($page=='search reciept'){ echo 'active-menu';}?>" href="search_reciept.php"><i class="fa fa-search "></i>Search Reciept</a>
                    </li>
                    <li>
                        <a class="<?php if($page=='data analysis'){ echo 'active-menu';}?>" href="chart.php"><i class="fa fa-bar-chart "></i>Data Analysis</a>
                    </li>
					
					<li>
                        <a class="<?php if($page=='setting'){ echo 'active-menu';}?>" href="setting.php"><i class="fa fa-cogs "></i>Account Setting</a>
                    </li>
                    <?php
                    if($_SESSION['typeUser']=="admin"){
                        
                    ?>
                    <li>
                        <a class="<?php if($page=='adduser'){ echo 'active-menu';}?>" href="adduser.php"><i class="fa fa-user"></i>Add New User</a>
                    </li>
					<?php
                    }
                    ?>
					 <li>
                        <a href="logout.php"><i class="fa fa-power-off "></i>Logout</a>
                    </li>
					
			
                </ul>

            </div>

        </nav>
        <!-- /. NAV SIDE  -->