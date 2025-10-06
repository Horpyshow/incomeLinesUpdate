<?php
include 'include/session.php';
if (isset($_POST['board_name'])) {
    $board_name = $_POST['board_name'];

    $queryboard = $dbcon->prepare("SELECT expected_rent_monthly, expected_rent_yearly, allocated_to FROM scroll_boards WHERE board_name = ?");
    $queryboard->bind_param("s", $board_name);
    $queryboard->execute();
    $result = $queryboard->get_result();

    if ($scrollboards = $result->fetch_assoc()) {
        echo json_encode([
            "status" => "success",
            "expected_rent_monthly" => $scrollboards["expected_rent_monthly"],
            "expected_rent_yearly" => $scrollboards["expected_rent_yearly"],
            "allocated_to" => $scrollboards["allocated_to"]
        ]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}


?>