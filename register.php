<?php
// Include config file
require_once "config.php";

/*use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/phpmailer/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/phpmailer/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/phpmailer/SMTP.php';*/
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6LekwigaAAAAAMoyF8AfdlpKa3JgWhGvEKQu2uPJ';
    $recaptcha_response = $_POST['recaptcha_response'];

    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    // Take action based on the score returned:
    if ($recaptcha->score < 0.5) 
    {
        die("Bot!");
    }
 
    // Validate username
    if(empty(trim($_POST["username"])))
    {
        $username_err = "Please enter a username.";
    } 
    elseif (strlen(trim($_POST["username"])) != strlen(htmlspecialchars(trim($_POST["username"]),ENT_QUOTES, 'UTF-8')))
    {
        $username_err = "That is not an accepted username.";
    }
    else
    {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "This username is already taken.";
                } 
                else
                {
                    $username = trim($_POST["username"]);
                }
            } 
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"])))
    {
        $password_err = "Please enter a password.";     
    } 
    elseif(strlen(trim($_POST["password"])) < 6)
    {
        $password_err = "Password must have atleast 6 characters.";
    } 
    else
    {
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"])))
    {
        $confirm_password_err = "Please confirm password.";     
    } 
    else
    {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password))
        {
            $confirm_password_err = "Password did not match.";
        }
    }

    /*if(empty(trim($_POST["email"])))
    {
        $username_err = "Please enter an email.";
    }
    else
    {
        $email = test_input($_POST["email"]);

        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            $email_err = "Invalid email format";
        }
        /*list($pre, $post) = explode("@", $email);
        list($prepost, $postpost) = explode(".", $post);
        if ((strlen($pre) != strlen(htmlspecialchars($pre),ENT_QUOTES, 'UTF-8')) or (strlen($prepost) != strlen(htmlspecialchars($prepost),ENT_QUOTES, 'UTF-8')) or (strlen($postpost) != strlen(htmlspecialchars($postpost),ENT_QUOTES, 'UTF-8')))
        {
            $email_err = "That is not an accepted email.";
        }
    }*/
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
    {
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            // Creates a password hash
            $param_password = password_hash($password, PASSWORD_DEFAULT); 

            /*$param_email = $email;
            echo $param_email;*/
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                /*//send email
                $mail = new PHPMailer;
                $mail->isSMTP(); 
                $mail->SMTPDebug = 2; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
                $mail->Host = "smtp.gmail.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
                $mail->Port = 587; // TLS only
                $mail->SMTPSecure = 'tls'; // ssl is deprecated
                $mail->SMTPAuth = true;
                $mail->Username = 'magazine.musaic@gmail.com'; // email
                $mail->Password = '161000MusaicMail'; // password
                $mail->setFrom('magazine.musaic@gmail.com', 'Musaic Magazine'); // From email and name
                $mail->addAddress($email); // to email
                $mail->Subject = 'New Account - Musaic Magazine';
                $mail->msgHTML("Your account on Musaic Magazine has been validated. Enjoy!"); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file,                    convert referenced images to embedded,
                $mail->AltBody = 'HTML messaging not supported'; // If html emails is not supported by the receiver, show this body
                // $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
                $mail->SMTPOptions = array
                (
                    'ssl' => array
                    (
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                if(!$mail->send())
                {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }
                else
                {
                    echo "Message sent!";
                }*/

                // Redirect to login page
                header("location: login.php");
            } 
            else
            {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    function test_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://www.google.com/recaptcha/api.js?render=6LekwigaAAAAAHDWPKmZtNUovOpmwrMKIQVoUQg2"></script>

    <script>
        grecaptcha.ready(function () 
        {
            grecaptcha.execute('6LekwigaAAAAAHDWPKmZtNUovOpmwrMKIQVoUQg2', { action: 'register' }).then(function (token) 
            {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
    </script>

    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>  

            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>

            <!--<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>-->

            <script>
                function onSubmit(token)
                {
                    document.getElementById("demo-form").submit();
                }
            </script>
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>

            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>