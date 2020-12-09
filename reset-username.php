<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$new_username  = "";
$new_username_err  = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate new username
    if(empty(trim($_POST["new_username"]))){
        $new_username_err = "Please enter the new username.";
    } elseif(strlen(trim($_POST["new_username"])) < 6){
        $new_username_err = "username must have at least 6 characters.";
    } else{
        $new_username = trim($_POST["new_username"]);
    }

    // Check input errors before updating the database
    if(empty($new_username_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET username = ? WHERE id = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_username, $param_id);

            // Set parameters
            $param_username = $new_username;
            $param_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // username updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location:login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset username</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Reset username</h2>
        <p>Please fill out this form to reset your username.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($new_username_err)) ? 'has-error' : ''; ?>">
                <label>New username</label>
                <input type="username" name="new_username" class="form-control" value="<?php echo $new_username; ?>">
                <span class="help-block"><?php echo $new_username_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link" href="account-details.php">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>