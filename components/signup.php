<?php



$errors = [
    'firstname' => '',
    'email' => '',
    'username' => '',
    'password' => '',
    'confirm-password' => ''
];

$Firstname = $Email = $Password = $Username = $confirmPassword = $error='';

if (isset($_POST['signup'])) {
    $Firstname = sanitize($_POST['signup-name']);
    $Email = sanitize($_POST['email']);
    $Username = sanitize($_POST['username']);
    $Password = sanitize($_POST['signup-password']);
    $confirmPassword = sanitize($_POST['confirm-password']);

    if (empty($Firstname)) {
        $errors['firstname'] = "First Name is required.";
    }

    if (empty($Username)) {
        $errors['username'] = "Username cannot be empty.";
    } elseif (!preg_match("/[a-zA-Z]/", $Username)) {
        $errors['username'] = "Username must contain at least one letter.";
    } elseif (!preg_match("/\d/", $Username)) {
        $errors['username'] = "Username must contain at least one number.";
    } elseif (!preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $Username)) {
        $errors['username'] = "Username must contain at least one special character.";
    }

    if (empty($Password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($Password) < 5) {
        $errors['password'] = "Password must be at least 5 characters long.";
    } elseif (!preg_match("/\d/", $Password)) {
        $errors['password'] = "Password must contain at least one number.";
    } elseif (!preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $Password)) {
        $errors['password'] = "Password must contain at least one special character.";
    }

    if ($confirmPassword !== $Password) {
        $errors['confirm-password'] = "Passwords do not match.";
    }

    if (empty($Email)) {
        $errors['email'] = "Email Address is required.";
    } elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (!array_filter($errors)) {

        $checkSQL = "SELECT id FROM users WHERE username = ? OR email = ?";
        if ($stmt = $conn->prepare($checkSQL)) {
            $stmt->bind_param("ss", $Username, $Email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id);
                $stmt->fetch();
                $error = "Username or Email already exists.";
            } else {
                $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

                $insertSQL = "INSERT INTO users (firstname, email, username, password) 
                              VALUES (?, ?, ?, ?)";

                if ($insertStmt = $conn->prepare($insertSQL)) {
                    $insertStmt->bind_param("ssss", $Firstname, $Email, $Username, $hashedPassword);

                    if ($insertStmt->execute()) {
                        $Firstname = $Email = $Password = $Username = $confirmPassword = $error='';
                        $_SESSION['success']='User registered successfully.';
                        header("location: login.php");
                        exit;
                    } else {
                        echo 'Something went wrong. Please try again later.';
                    }

                    $insertStmt->close();
                }
            }

            $stmt->close();
        }
        $conn->close();
    }
}
?>


<div class="flex items-center p-10 justify-center">
    <div class="flex flex-col md:flex-row bg-white shadow-lg rounded-2xl overflow-hidden max-w-3xl w-full">

        <div class="flex flex-col items-center justify-center bg-blue-600 text-white p-8 md:w-1/2">
            <div class="text-4xl font-extrabold mb-2">Chooz</div>
            <p class="text-lg font-medium text-center">Simplifying group decisions for everyone.</p>
            <div class="relative mt-6 space-y-3">
                <div class="w-3 h-3 bg-white rounded-full animate-bounce"></div>
                <div class="w-3 h-3 bg-blue-300 rounded-full animate-bounce delay-200"></div>
                <div class="w-3 h-3 bg-white rounded-full animate-bounce delay-400"></div>
            </div>
        </div>

        <div class="flex flex-col justify-center p-8 md:w-1/2">
            <h2 class="text-3xl font-bold text-blue-800 mb-6 text-center">Create an Account</h2>
            <?php if($error):?>
                <div class="text-red-500 text-sm mb-3 text-center"><?=$error?></div>
            <?php endif;?>
            <form action="signup.php" method="POST" class="space-y-4">
                <div>
                    <label for="signup-name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" id="signup-name" name="signup-name" class="w-full border border-gray-300 p-3 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" value="<?=$Firstname?>"/>
                </div>
                <?php
                    if(isset($errors['firstname'])):
                ?>
                        <div class="text-sm text-red-600"><?= $errors['firstname']?></div>
                <?php endif;?>

                <div>
                    <label for="signup-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="text" id="signup-email" name="email" class="w-full border border-gray-300 p-3 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" value="<?= $Email?>" />
                </div>
                <?php
                    if(isset($errors['email'])):
                ?>
                        <div class="text-sm text-red-600"><?= $errors['email']?></div>
                <?php endif;?>

                <div>
                    <label for="confirm-password" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" class="w-full border border-gray-300 p-3 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" value="<?= $Username?>">
                </div>
                <?php
                    if(isset($errors['username'])):
                ?>
                        <div class="text-sm text-red-600"><?= $errors['username']?></div>
                <?php endif;?>

                <div>
                    <label for="signup-password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="signup-password" name="signup-password" class="w-full border border-gray-300 p-3 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" value="<?=$Password?>">
                </div>
                <?php
                    if(isset($errors['password'])):
                ?>
                        <div class="text-sm text-red-600"><?= $errors['password']?></div>
                <?php endif;?>

                <div>
                    <label for="confirm-password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" class="w-full border border-gray-300 p-3 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" value="<?=$confirmPassword?>">
                </div>
                <?php
                    if(isset($errors['confirm-password'])):
                ?>
                        <div class="text-sm text-red-600"><?= $errors['confirm-password']?></div>
                <?php endif;?>

                <input type="submit" class="w-full rounded-lg bg-blue-600 text-white font-bold py-3 transition duration-300 hover:bg-blue-700" value="Sign Up" name="signup"/>
                    
            </form>

            <div class="mt-6 flex items-center justify-center space-x-4">
                <button class="w-full flex items-center justify-center bg-white border border-gray-300 py-3 rounded-lg shadow-sm transition duration-300 hover:bg-gray-100">
                    <img src="assets/google-logo.png" alt="Google Logo" class="w-6 h-6 mr-2">
                    <span class="text-sm font-semibold text-gray-700">Sign in with Google</span>
                </button>
            </div>
            <div class="text-center mt-4">
                <span class="text-sm text-gray-600">Already have an account? </span>
                <a href="login.php" class="text-sm font-semibold text-blue-600 hover:underline">Login</a>
            </div>
        </div>
    </div>
</div>
