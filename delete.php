<?php
// Initialize the session
session_start();
require_once 'token.php';

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

// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"]))
{
    // Include config file
    require_once "config.php";

    // Prepare a delete statement
    $sql = "DELETE FROM users WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_POST["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt))
        {
            // Records deleted successfully. Redirect to landing page
            session_destroy();
            header("location: profile.html");
            exit();
        } 
        else
        {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} 
else
{
    // Check existence of id parameter
    if(empty(trim($_GET["id"])))
    {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="css/testlogin.css" rel="stylesheet">
    <link rel="stylesheet" href="https://jqueryvalidation.org/files/demo/site-demos.css">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>

    <style type="text/css">
        .wrapper
        {
            width: 500px;
            margin: 0 auto;
        }
    </style>

</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Delete Account</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Are you sure you want to delete this account?</p><br>

                            <input id="login-username" type="hidden" class="form-control" name="csrf_token" value="<?php  echo $_SESSION["token"];  ?>">

                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="account-details.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>

