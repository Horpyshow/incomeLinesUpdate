<?php 
ob_start();
include 'include/session.php';
// $host = 'localhost';
// $user = 'root';
// $pass = '';
// $dbname = 'wealth_creation';
// $dbcon = new mysqli($host, $user, $pass, $dbname);
// if ($dbcon->connect_error) {
//     die("Connection failed: " . $dbcon->connect_error); 
// }

date_default_timezone_set('Africa/Lagos');
$current_time = new DateTime();
$begin_time = new DateTime('12:00');
$end_time = new DateTime('22:00'); 

$wc_begin_time = new DateTime('18:30');
$wc_begin_time_exception = new DateTime('19:00');
$wc_end_time = new DateTime('23:59');

if ($current_time >= $begin_time && $current_time <= $end_time){
	//include ('include/delete_pending_posts.php');
} 

// if session is not set this will redirect to login page
if(isset($_SESSION['staff']) ) {
    // select loggedin users detail
    $query = "SELECT * FROM staffs WHERE user_id=".$_SESSION['staff'];
    $result = @mysqli_query($dbcon, $query); 
    $staff = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $tresult = mysqli_query($dbcon, "SELECT token FROM users_logs WHERE user_id='".$_SESSION['staff']."'");
    if (mysqli_num_rows($tresult) > 0)  {
        $row = mysqli_fetch_array($tresult); 
        $token = $row['token']; 
    } else {
        unset($_SESSION['staff']);
        unset($_SESSION['admin']);
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }    
} 
elseif (isset($_SESSION['admin']) ) {
    // select loggedin users detail
    $query = "SELECT * FROM staffs WHERE user_id=".$_SESSION['admin'];
    $result = @mysqli_query($dbcon, $query); 
    $staff = mysqli_fetch_array($result, MYSQLI_ASSOC);
      //Check token
    $tresult = mysqli_query($dbcon, "SELECT token FROM users_logs WHERE user_id='".$_SESSION['admin']."'");
    if (mysqli_num_rows($tresult) > 0)  {
    $row = mysqli_fetch_array($tresult); 
    $token = $row['token']; 
    } else {
        unset($_SESSION['staff']);
        unset($_SESSION['admin']);
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: ../../login.php");
    exit;	
}
// Generate unique scroll board name
function generateBoardName($dbcon) {
    $prefix = "SB";
    // Fetch the total count of existing boards
    $result = $dbcon->query("SELECT COUNT(id) AS count FROM scroll_boards"); 
    if (!$result) {
        die("Database error: " . $dbcon->error);
    }
    $row = $result->fetch_assoc();
    $count = isset($row['count']) ? (int)$row['count'] + 1 : 1;
    return $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
}

// Function to sanitize input
function cleanInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$errors = [];
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $location = isset($_POST['location']) ? cleanInput($_POST['location']) : '';
    $expected_rent_monthly = isset($_POST['expected_rent_monthly']) ? floatval($_POST['expected_rent_monthly']) : 0;
    $expected_rent_yearly = isset($_POST['expected_rent_yearly']) ? floatval($_POST['expected_rent_yearly']) : 0;
    $allocated_to = isset($_POST['allocated_to']) ? cleanInput($_POST['allocated_to']) : '';
    $start_date = isset($_POST['start_date']) ? cleanInput($_POST['start_date']) : '';
    $end_date = isset($_POST['end_date']) ? cleanInput($_POST['end_date']) : '';

    // Basic validation
    if (empty($location) || empty($expected_rent_monthly)) {
        $errors[] = "<div class='alert alert-warning'>Location and monthly rent are required.</div>";
    }

    // Check if location already exists
    if (empty($errors)) {
        $checkLocation = $dbcon->prepare("SELECT id FROM scroll_boards WHERE board_location = ?");
        if (!$checkLocation) {
            die("Prepare failed: " . $dbcon->error);
        }

        $checkLocation->bind_param("s", $location);
        $checkLocation->execute();
        $checkLocation->store_result();

        if ($checkLocation->num_rows > 0) {
            $errors[] = "<div class='alert alert-warning'>Location already exists. Choose a different location.</div>";
        }
        $checkLocation->close();
    }

    // Insert record if no errors
    if (empty($errors)) {
        $board_name = generateBoardName($dbcon);
        $status = !empty($allocated_to) ? 'rented' : 'vacant';

        $stmt = $dbcon->prepare("
            INSERT INTO scroll_boards (board_name, board_location, expected_rent_monthly, expected_rent_yearly, `status`, allocated_to, `start_date`, end_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            die("Prepare failed: " . $dbcon->error);
        }

        // Bind parameters
        $stmt->bind_param("ssddssss", $board_name, $location, $expected_rent_monthly, $expected_rent_yearly, $status, $allocated_to, $start_date, $end_date);

        // Execute the query
        if ($stmt->execute()) {
            $success = "<div class='alert alert-success'>Scroll board created successfully.</div>";
        } else {
            $errors[] = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Welcome - <?php echo $staff['full_name']; ?> | Wealth Creation ERP</title>
		<meta http-equiv="Content-Type" name="description" content="Wealth Creation ERP Management System; text/html; charset=utf-8" />
		<meta name="author" content="Woobs Resources Ltd">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/formValidation.min.css">
		
		<link rel="stylesheet" type="text/css" href="../../css/datepicker.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/datepicker3.min.css">
		
		
		<link rel="stylesheet" type="text/css" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="../../css/bootstrapValidator.min.css">
		<!--<script type="text/javascript" src="../../js/jquery.min.js"></script>-->
		
		
		
		<link rel="stylesheet" href="../../css/sub_menu.css">
	</head>
<body>
<?php include ('include/staff_navbar.php'); ?>
    <script>
        function calculateYearlyRent() {
            let monthlyRent = document.getElementById('expected_rent_monthly').value;
            let yearlyRent = monthlyRent * 12;
            document.getElementById('expected_rent_yearly').value = yearlyRent.toFixed(2);
        }
    </script>
<div class="jumbotron"></div>
<div class="container-fluid">
    <div class="col-8 offset-md-3">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12">
                <h3>
                    <a href="javascript:history.go(-1)" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-backward"></span> GO BACK</a>
                    <a href="scrollboard.php" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-backward"></span> Analysis Dashboard</a>
                    <!-- <a href="scrollboard_rentals.php" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-backward"></span> Rental List</a> -->
                    <a href="scrollboard_creation.php" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span> Create New Scroll Board</a>
                </h3>
                <h3><strong>Scroll Board Creation & Allocation | Wealth Creation ERP </strong></h3>
            </div>
        </div>
        <div class="col-md-3">
            <?php
                if (!empty($success)) {
                    echo $success;
                }
                
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo $error;
                    }
                }   
            ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Board Location:</label>
                    <input type="text" name="location" class="form-control input-sm" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Allocated to:</label>
                    <input type="text" name="allocated_to" class="form-control input-sm" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Expected Rent (Monthly) ₦</label>
                    <input type="number" id="expected_rent_monthly" name="expected_rent_monthly" step="0.01" class="form-control" onkeyup="calculateYearlyRent()" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Expected Rent (Yearly) ₦</label>
                    <input type="text" id="expected_rent_yearly" name="expected_rent_yearly" class="form-control" readonly>
                </div>
                <!-- <div class="mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div> -->
                <!-- <div class="mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div> -->
                <br>
                <button type="submit" class="btn btn-primary">Create Scroll Board</button>
                <button href="index.php" class="btn btn-secondary">Back to Dashboard</a>
                <br>
            </form>	
        </div>
        <div class="col-md-9">
            <?php
                $sql = "SELECT * FROM scroll_boards ORDER BY id DESC";
                $stmt = $dbcon->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result(); // Get the result set
                $scrollboards = $result->fetch_all(MYSQLI_ASSOC); // Fetch all as associative array
            ?>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Scroll No</th>
                        <th>Location</th>
                        <th>Assigned To</th>
                        <th>Expected Rent (Monthly)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($scrollboards)): ?>
                        <?php foreach ($scrollboards as $index => $row): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($row['board_name']) ?></td>
                                <td><?= htmlspecialchars($row['board_location']) ?></td>
                                <td><?= htmlspecialchars($row['allocated_to']) ?></td>
                                <td>₦<?= number_format($row['expected_rent_monthly'], 2) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal<?= $row['id'] ?>">Edit</button>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?= $row['id'] ?>">Delete</button>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Edit Scroll Board</h4>
                                        </div>
                                        <form action="edit_scroll_board.php" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <div class="form-group">
                                                    <label>Scroll Board No</label>
                                                    <input type="text" name="board_name" class="form-control" value="<?= htmlspecialchars($row['board_name']) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Location</label>
                                                    <input type="text" name="board_location" class="form-control" value="<?= htmlspecialchars($row['board_location']) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Allocated To</label>
                                                    <input type="text" name="allocated_to" class="form-control" value="<?= htmlspecialchars($row['allocated_to']) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Expected Rent (Monthly)</label>
                                                    <input type="number" step="0.01" name="expected_rent_monthly" class="form-control" onkeyup="calculateYearlyRent()" value="<?= $row['expected_rent_monthly'] ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Expected Rent (Yearly)</label>
                                                    <input type="number" step="0.01" name="expected_rent_yearly" class="form-control" value="<?= $row['expected_rent_yearly'] ?>" required readonly>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Delete Confirmation</h4>
                                        </div>
                                        <form action="delete_scroll_board.php" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <p>Are you sure you want to delete <strong><?= htmlspecialchars($row['board_name']) ?></strong>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No scroll boards found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
                


            </div>       
        </div>
    </div>

</div>





<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../../js/formValidation.min.js"></script>
<script type="text/javascript" src="../../js/framework/bootstrap.min.js"></script>
<script type='text/javascript' src="../../js/bootstrap-datepicker.min.js"></script>
<script type='text/javascript' src="../../js/fv.js"></script>
<script type="text/javascript" src="../../js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="../../js/bootstrap.min.js"></script>

<script src="../../js/sub_menu.js"></script>
</body>
</html>
