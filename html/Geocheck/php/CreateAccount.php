<?php
session_start();

//Database Information
$host = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";
$port = 3306;
$conn = mysqli_connect($host, $username, $password, $dbname, $port);

// Connecting to the Database
if (!$conn)
     die("Database error: " . mysqli_connect_error());

/*

                REGISTRATION PAGE CODE

*/

if(isset($_POST['signupForm1'])) {
        $error_msg = "";  // output message to user and also our check for errors
        foreach ($_POST as $key => $value) {
            if (empty($_POST[$key])) // Testing to see if all fields were entered
                $error_msg = "All fields must be filled in!";
        }

        //Getting the attributes from the html page
        if ($error_msg == "") { // try and catch for registration
            $fName = $_POST['fName1']; //first name
            $lName = $_POST['lName']; //last name
            $email = $_POST['email']; //email for user
            $password = $_POST['password']; //password for user
            $passwordCheck = $_POST['password2']; // password verification
            $accountType = $_POST['AccountType']; //professor or student

            if (!preg_match("/^[a-zA-Z ]+$/", $fName)) // Checking for incorrect characters in First Name
                $error_msg = "First name only can have letters/spaces";

            if (!preg_match("/^[a-zA-Z ]+$/", $lName)) // Checking for incorrect characters in Last Name
                $error_msg = "Last name only can have letters/spaces";

            if (!strpos($email,"@vnu.edu")) // Checking for OU Email
                $error_msg = "Must be an Vietnam National University email";

            if ((strlen($password) < 6) || (strlen($password) > 20)) // Checking password length
                $error_msg = "Invalid password length";

            if ($password != $passwordCheck) {// Verifying password
                echo("<script LANGUAGE='JavaScript'>
                        window.alert('Passwords don\'t match!');
                        window.location.href='../login.php#signup';
                        </script>");
            }

            // CREATING ACCOUNT
            if ($error_msg == "") // if no errors thrown, continue
            { // no error found

                // CHECKING FOR STUDENT ACCOUNT
                if ($_POST['AccountType'] == "student") {
                    $q = "CALL createAccountS('$fName', '$lName', '$email', '$password')"; // SQL Procedure for Student Add
                }

                // CHECKING FOR PROFESSOR ACCOUNT
                if ($_POST['AccountType'] == "professor") {
                   $q = "CALL createAccountP('$fName', '$lName', '$email', '$password')"; // SQL Procedure for Professor Add
                }

                // ADDING ACCOUNT INTO DATABASE
                if (mysqli_real_query($conn, $q)) {
                    echo ("<script LANGUAGE='JavaScript'>
                            window.alert('Account Registered Successfully!');
                            window.location.href='../login.php';
                            </script>");
                }
                else // Error in database addition
                    echo("<script LANGUAGE='JavaScript'>
                        window.alert('Error adding account.');
                        window.location.href='../login.php#signup';
                        </script>");

            } else { // Error in validation
                echo("<script LANGUAGE='JavaScript'>
                        window.alert('$error_msg');
                        window.location.href='../login.php#signup';
                        </script>");

                }
        } else { // Error in missing field data
            echo("<script LANGUAGE='JavaScript'>
                        window.alert('$error_msg');
                        window.location.href='../login.php#signup';
                        </script>");
            }
}


/*

                LOGIN PAGE CODE

*/

elseif (isset($_POST['loginForm1'])) {
    $email = $_POST['emailLogin']; // user email
    $password = $_POST['loginPassword']; // user password
    $loginType = $_POST['loginType'];


    if ($loginType == "student") {

        $query = "SELECT loginS('$email', '$password')";  // JAKOB MAGIC CODE
        $result = mysqli_real_query($conn, $query);       // THAT CHECKS LOGIN
        $result = mysqli_use_result($conn);               // INFO AND COMPARES TO
        $row = mysqli_fetch_row($result);                 // DATABASE ENTRY TO
        $row1 = mysqli_fetch_row($result);                // COMPLETE LOGIN FOR STUDENT

        if ($row[0] == 1) { // STUDENT LOGIN
            $result1 = mysqli_query($conn, "SELECT STU_ID FROM STUDENT WHERE STU_EMAIL = '$email';"); // Grabbing profID from database
            $temp = $result1->fetch_assoc();
            $stuID = $temp["STU_ID"];

            $_SESSION['logged_in'] = TRUE;
            $_SESSION['email'] = $email;
            $_SESSION['stuID'] = $stuID;

            header("Location: ../View-Classes-S.php");

        }
        else { // no account found error
            echo("<script LANGUAGE='JavaScript'>
                        window.alert('Error Logging in! Check your credentials.');
                        window.location.href='../login.php';
                        </script>");
            session_destroy();
        }
    } else if ($loginType == "professor") {

        $query = "SELECT loginP('$email', '$password')";   // JAKOB MAGIC CODE
        $result = mysqli_real_query($conn, $query);        // THAT CHECKS LOGIN
        $result = mysqli_use_result($conn);                // INFO AND COMPARES TO
        $row = mysqli_fetch_row($result);                  // DATABASE ENTRY TO
        $row1 = mysqli_fetch_row($result);                 // COMPLETE LOGIN FOR PROFESSOR

        if ($row[0] == 1) { // PROFESSOR LOGIN
            $result1 = mysqli_query($conn, "SELECT PROF_ID FROM PROFESSOR WHERE PROF_EMAIL = '$email';"); // Grabbing profID from database
            $temp = $result1->fetch_assoc();
            $profID = $temp["PROF_ID"];

            $_SESSION['logged_in'] = TRUE;
            $_SESSION['email'] = $email;
            $_SESSION['profID'] = $profID;

            header("Location: ../View-Classes-P-Updated.php");

        }
        else { // no account found error
            echo("<script LANGUAGE='JavaScript'>
                        window.alert('Error Logging in! Check your credentials.');
                        window.location.href='../login.php';
                        </script>");
            session_destroy();
        }
    }
}