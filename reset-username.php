<?php
// Initialize the session
session_start();
require_once "token.php";

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
    header("location: login.php");
    exit;
}

if($_SESSION['agent'] != $_SERVER['HTTP_USER_AGENT']) 
{
    die('Session MAY have been hijacked');
}

// Token verification
$_id = trim($_GET["id"]);
/*echo "sesiune".$_SESSION["id"];
echo "get".$_id;*/
if($_id and $_SESSION["id"] != $_id)
{
    die('Invalid token.');
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$new_username  = "";
$new_username_err  = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Validate new username
    if(empty(trim($_POST["new_username"])))
    {
        $new_username_err = "Please enter the new username.";
    } 
    elseif(strlen(trim($_POST["new_username"])) < 6)
    {
        $new_username_err = "Username must have at least 6 characters.";
    } 
    else
    {
        $new_username = trim($_POST["new_username"]);
    }

    // Check input errors before updating the database
    if(empty($new_username_err))
    {
        // Prepare an update statement
        $sql = "UPDATE users SET username = ? WHERE id = ?";

        if($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_username, $param_id);

            // Set parameters
            $param_username = $new_username;
            $param_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                // username updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location:login.php");
                exit();
            } 
            else
            {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset username</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="css/testlogin.css" rel="stylesheet">
    <link rel="stylesheet" href="https://jqueryvalidation.org/files/demo/site-demos.css">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>

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

            <input id="login-username" type="hidden" class="form-control" name="csrf_token" value="<?php  echo $_SESSION["token"];  ?>">

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link" href="account-details.php">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>