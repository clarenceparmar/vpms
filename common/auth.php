<?php 

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'a') {
        header("Location: /admin/admin_dashboard.php");
        exit();
    } 
    
    else if ($_SESSION['role'] == 'u') {
        header("Location: /user/user_dashboard.php");
        exit();
    }

} else {
    
    header("Location: /login.php");
    exit();
}
?>
