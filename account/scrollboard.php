<?php 
ob_start();
include 'include/session.php'; 
// ob_start();
// session_start();
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
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
// Function to fetch rental data
function getScrollBoards($dbcon, $searchQuery) {
    $sql = "SELECT sb.id, sb.board_name, sb.board_location, sb.allocated_to, sb.expected_rent_monthly, sb.expected_rent_yearly, sb.start_date, sb.end_date, srca.rent_start, srca.rent_due, srca.amount_paid, srca.customer_name
            FROM scroll_boards sb
            LEFT JOIN scrollboard_rentals_collection_analysis srca ON sb.board_name = srca.shop_no";
    if (!empty($searchQuery)) {
        $sql .= " WHERE sb.board_name LIKE '%$searchQuery%'";
    }
    return $dbcon->query($sql);
}
// Compute financial summary
function computeFinancials($dbcon) {
    $result = $dbcon->query("SELECT SUM(expected_rent_monthly) AS total FROM scroll_boards");
    $row = $result->fetch_assoc();
    $totalExpected = isset($row['total']) ? $row['total'] : 0;

    $result = $dbcon->query("SELECT SUM(amount_paid) AS total FROM scrollboard_rentals_collection_analysis");
    $row = $result->fetch_assoc();
    $totalGenerated = isset($row['total']) ? $row['total'] : 0;

    $totalDeficit = $totalExpected - $totalGenerated;
    return compact('totalExpected', 'totalGenerated', 'totalDeficit');
}
$financials = computeFinancials($dbcon);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scroll Board Management | Wealth Creation ERP </title>
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.css">	
    <link rel="stylesheet" type="text/css" href="../css/datepicker.min.css">
    <script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
    <script type="text/javascript" src="../../js/framework/bootstrap.min.js"></script>
    <script type='text/javascript' src="../../js/bootstrap-datepicker.min.js"></script>
    <script type='text/javascript' src="../../js/fv2.js"></script> 
    
    <script type="text/javascript" src="../../js/bootstrapValidator.min.js"></script>
    
    <link rel="stylesheet" type="text/css" href="../../../css/bootstrapValidator.min.css">
    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
    
    <script src="../../js/sub_menu.js"></script>
    <link rel="stylesheet" href="../../css/sub_menu.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<body>
<?php //include ('scrollBoard/include/staff_navbar_scrollboard.php');	
    include ('include/staff_navbar.php'); 		
?>
<div class="jumbotron"></div>
<div class="container-fluid">
    <div class="col-md-12">
        <h3>
        <strong>Scroll Board Management</strong>
        <a href="javascript:history.go(-1)" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-backward"></span> GO BACK</a>
        <a href="scrollboard.php" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-backward"></span> Analysis Dashboard</a>
        <a href="scrollboard_creation.php" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span> Create New Scroll Board</a>
        </h3>
    </div>
    <div class="col-md-12">
        <?php 
            $scrollBoardCount = 0;
            $query = "SELECT COUNT(*) AS total FROM scroll_boards";
            $result = $dbcon->query($query);
            if ($result) {
                $row = $result->fetch_assoc();
                $scrollBoardCount = $row['total'];
            }
           
        ?>
        <div class="card">Total Scroll Board: <?php echo $scrollBoardCount; ?></div>
        <div class="card">Vacant Scroll Board: <?php //echo $driverCount; ?></div>
        <div class="card">Rented ScrollBoard: <?php //echo $customerCount; ?></div>


        <table id="scrollboard" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Scroll Board</th>
                    <th>Expected Rent</th>
                    <th>Company</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Amount Paid</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $scrollBoards = getScrollBoards($dbcon, $searchQuery);
                while ($row = $scrollBoards->fetch_assoc()) { 
                    ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['board_name'] ?></td>
                    <td>₦<?= number_format($row['expected_rent_monthly'], 2) ?></td>
                    <td><?= isset($row['customer_name']) ? $row['customer_name'] : 'Unrented';?></td>
                    <td><?= isset($row['start_date']) ? $row['start_date'] : '-' ?></td>
                    <td><?= isset($row['end_date']) ? $row['end_date'] : '-' ?></td>
                    <td>₦<?= number_format(isset($row['amount_paid']) ? $row['amount_paid'] : 0, 2) ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <h3>Scroll Board Revenue Summary</h3>
        <p><strong>Total Expected Income:</strong> ₦<?= number_format($financials['totalExpected'], 2) ?></p>
        <p><strong>Actual Income:</strong> ₦<?= number_format($financials['totalGenerated'], 2) ?></p>
        <p><strong>Deficit (Unrented Units):</strong> ₦<?= number_format($financials['totalDeficit'], 2) ?></p>
        </div>
    
    <script>
        $(document).ready(function() {
            $('#scrollboard').DataTable();
        });
    </script>
    
</body>
</html>