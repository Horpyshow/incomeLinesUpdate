<?php 
ob_start();
include 'include/session.php';
if (!isset($_POST['id']) || empty($_POST)) {
    die("Invalid request.");
} else {
    $id = trim(htmlspecialchars($_POST['id']));

    //delete scrollboard
    $delete_sql = "DELETE FROM scroll_boards WHERE id = ?";
    $delete_stmt =$dbcon->prepare($delete_sql);
    $delete_stmt->bind_param("i",$id);
    $delete_stmt->execute();

    if ($delete_stmt->execute()) {
        $_SESSION['success'] =  "Scroll board deleted Successfuly!";
        header('Location: scrollboard_creation.php');
             echo "<script>alert('Scrollboard deleted successfully!'); window.location.href='scrollboard.php';</script>";
        exit;
    } else {
        $_SESSION['errors'] = "Error deleting Scrollboard!";
        header('Location: scrollboard_creation.php');
        exit;
        echo "<script>alert('Error deleting scrollboard!');</script>";
    }
}