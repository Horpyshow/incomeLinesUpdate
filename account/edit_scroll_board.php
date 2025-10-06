<?php 
ob_start();
include 'include/session.php';
if (!isset($_POST['id']) || empty($_POST)) {
    die("Invalid request.");
} else {
    $id = trim(htmlspecialchars($_POST['id']));
    $board_name = trim(htmlspecialchars($_POST['board_name']));
    $allocated_to = trim(htmlspecialchars($_POST['allocated_to']));
    $expected_rent_monthly = trim(htmlspecialchars($_POST['expected_rent_monthly']));
    $expected_rent_yearly = trim(htmlspecialchars($_POST['expected_rent_yearly']));
    // print_r($_POST);
    // exit();
    // Update query
    $updateSql = "UPDATE scroll_boards SET board_name = ?, allocated_to = ?, expected_rent_monthly = ?, expected_rent_yearly = ? WHERE id = ?";
    $stmt = $dbcon->prepare($updateSql);
    if($stmt){
        $stmt->bind_param("ssddi",$board_name, $allocated_to, $expected_rent_monthly, $expected_rent_yearly, $id);
        $stmt->execute();
    }
    // $stmt->bindParam(":board_name", $board_name);
    // $stmt->bindParam(":allocated_to", $allocated_to);
    // $stmt->bindParam(":expected_rent_monthly", $expected_rent_monthly);
    // $stmt->bindParam(":expected_rent_yearly", $expected_rent_yearly);
    // $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['success'] =  "Scroll board Uodated Successfuly!";
        header('Location: scrollboard_creation.php');
             echo "<script>alert('Scrollboard updated successfully!'); window.location.href='scrollboard.php';</script>";
        exit;
    } else {
        $_SESSION['errors'] = "Error updating Scrollboard!";
        header('Location: scrollboard_creation.php');
        exit;
        //echo "<script>alert('Error updating vehicle!');</script>";
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vehicle</title>
</head>
<!-- <body>
    <h2>Edit Vehicle</h2>
    <form method="POST">
        <label>Vehicle Name:</label>
        <input type="text" name="vehicle_name" value="<?php echo htmlspecialchars($vehicle['vehicle_name']); ?>" required>
        <br>
        <label>Model:</label>
        <input type="text" name="model" value="<?php echo htmlspecialchars($vehicle['model']); ?>" required>
        <br>
        <label>Plate Number:</label>
        <input type="text" name="plate_number" value="<?php echo htmlspecialchars($vehicle['plate_number']); ?>" required>
        <br>
        <button type="submit">Update Vehicle</button>
    </form>
    <a href="vehicle_list.php">Back to Vehicle List</a>
</body> -->
</html>

<?php $pdo = null; ?>
