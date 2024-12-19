<?php
$Email = $Password=$error='';
if(isset($_POST['Login'])){
    $Email=mysqli_real_escape_string($conn,sanitize($_POST['email']));
    $Password=mysqli_real_escape_string($conn,sanitize($_POST['password']));

    if(empty($Email) || empty($Password)){
        $error='Incorrect Password or email';
    }else{
        $sql="SELECT * FROM  users WHERE email='$Email'";
        $query= mysqli_query($conn,$sql);
        $result=mysqli_num_rows($query);

        if($result>0){
            $rows= mysqli_fetch_assoc($query);
            if(password_verify($Password, $rows['password'])){
                $_SESSION['user_id'] = $rows['id'];
                header('Location: dashboard.php');
                exit;
            }else{
                $error="Incorrect password";
            }
        }else{
            $error="Incorrect email address";
        }
    }

}
  $success=$_SESSION['success'] ?? '';
  unset($_SESSION['success']);
?>
  
<div class="p-10 flex items-center justify-center">
    <div class="flex flex-col md:flex-row bg-white shadow-lg rounded-2xl overflow-hidden max-w-3xl w-full">
        
        <div class="flex items-center justify-center bg-blue-600 md:w-1/2 p-6">
            <img src="path/to/logo.png" alt="Chooz Logo" class="w-32 h-auto">
        </div>

        <div class="flex flex-col justify-center p-8 md:w-1/2">
        <?php if($success):?>
            <div class="text-green-500 font-bold text-center"><?=$success?></div>
        <?php endif;?>
        <?php if($error):?>
            <div class="text-red-500 font-bold text-center"><?=$error?></div>
        <?php endif;?>
            <h2 class="text-3xl font-bold text-blue-800 mb-6 text-center">Login to Chooz</h2>

            <form class="space-y-4" method='POST' action="login.php">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="w-full border border-gray-300 p-3 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" value="<?= $Email?>">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="w-full border border-gray-300 p-3 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" value="<?= $Password?>">
                </div>

                <!-- Forgot Password link -->
                <div class="text-right">
                    <a href="#" class="text-sm text-blue-600 hover:underline">Forgot Password?</a>
                </div>

                <input type="submit" name="Login" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg transition duration-300 hover:bg-blue-700" value="Login">
            </form>

            <!-- Google Sign-In Button -->
            <div class="mt-6 flex items-center justify-center space-x-4">
                <button class="w-full flex items-center justify-center bg-white border border-gray-300 py-3 rounded-lg shadow-sm transition duration-300 hover:bg-gray-100">
                    <img src="assets/google-logo.png" alt="Google Logo" class="w-6 h-6 mr-2">
                    <span class="text-sm font-semibold text-gray-700">Sign in with Google</span>
                </button>
            </div>

            <!-- Sign-up link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Don’t have an account? 
                    <a href="signup.php" class="text-blue-600 font-semibold hover:underline">Sign up</a>
                </p>
            </div>
        </div>
    </div>
</div>
