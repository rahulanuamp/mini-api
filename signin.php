<?php
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {

        $error = "All fields are required!";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Invalid Email Address!";

    } else {

        if (file_exists("users.json")) {

            $users = json_decode(file_get_contents("users.json"), true);

            if (!is_array($users)) {
                $users = [];
            }

            foreach ($users as $user) {

                if (
                    strtolower($user["email"]) == strtolower($email) &&
                    password_verify($password, $user["password"])
                ) {

                    $_SESSION["user"] = [
                        "name"  => $user["name"],
                        "email" => $user["email"]
                    ];

                    header("Location: index.php");
                    exit;
                }
            }
        }

        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Sign In</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="auth-container">

    <div class="auth-card">

        <h1>Sign In</h1>

        <form method="POST">

            <input
                type="email"
                name="email"
                placeholder="Email Address"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <button type="submit">
                Sign In
            </button>

        </form>

        <?php if($error!=""){ ?>

            <div class="error-box">

                <?php echo htmlspecialchars($error); ?>

            </div>

        <?php } ?>

        <p>

            New User?

            <a href="signup.php">
                Create Account
            </a>

        </p>

    </div>

</div>

</body>
</html>