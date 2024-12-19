<?php
$edit=false;
$errors=[
    'firstname'=>'',
    'email'=>'',
    'phonenumber'=>''
];
$error=$success='';
$Firstname=$user['firstname'];
$Lastname=$user['last_name'];
$Middlename=$user['middle_name'];
$Phone_number=$user['phone_number'];
$Email=$user['email'];
$Organization=$user['organization'];
$Province=$user['province'];
$City=$user['city'];
$user_id= $user['id'];

 if(isset($_POST['Update'])){
      $Firstname= mysqli_real_escape_string($conn, sanitize($_POST['firstname']));
      $Lastname= mysqli_real_escape_string($conn, sanitize($_POST['lastname']));
      $Middlename= mysqli_real_escape_string($conn, sanitize($_POST['middlename']));
      $Phone_number= mysqli_real_escape_string($conn, sanitize($_POST['phonenumber']));
      $Email=mysqli_real_escape_string($conn, sanitize($_POST['email']));
      $Organization=mysqli_real_escape_string($conn, sanitize($_POST['organization']));
      $Province= mysqli_real_escape_string($conn, sanitize($_POST['province']));
      $City= mysqli_real_escape_string($conn, sanitize($_POST['city']));

      if (empty($Email) || !filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Input a valid email address';
        $edit= true;
      }
      if (empty($Firstname)) {
        $errors['firstname'] = 'full name is required';
        $edit= true;

      }
      if (!preg_match('/^\+?\d{4,15}$/', $Phone_number)) {
        $errors['phonenumber'] = 'Phone number should start with country format "+".';
        $edit= true;
      }
    

      if (!array_filter($errors)) {
        if ($user['email'] !== $Email) {
            $checkEmailSql = "SELECT * FROM users WHERE email = '$Email'";
            $checkEmailQuery = mysqli_query($conn, $checkEmailSql);

            if (mysqli_num_rows($checkEmailQuery) > 0) {
                $errors['email'] = 'User already exists with this email';
        $edit= true;

            }
        }

        if (!array_filter($errors)) {
            $profileImageSQL = "";

            if (isset($_FILES['profile-image']) && $_FILES['profile-image']['error'] === UPLOAD_ERR_OK) {
                $filename = $_FILES['profile-image']['name'];
                $fileTmpPath = $_FILES['profile-image']['tmp_name'];
                $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $uploadDir = 'profile_images/';
                    $newFileName = uniqid('profile_', true) . '.' . $fileExtension;
                    $profileImagePath =$newFileName;
                    $profilePath = $uploadDir . $newFileName;
                    
                    if ($user['profile_image']) {
                        $oldProfileImagePath = $uploadDir . $user['profile_image'];
                        if (file_exists($oldProfileImagePath)) {
                            unlink($oldProfileImagePath);
                        }
                    }

                    if (move_uploaded_file($fileTmpPath, $profilePath)) {
                        $profileImageSQL = ", profile_image = '$profileImagePath'";
                    } else {
                        $error = "Error uploading profile image.";
                    }
                } else {
                    $error = "Invalid file type. Allowed types: jpg, jpeg, png";
                }
            }

            $sql = "UPDATE users 
                    SET firstname = '$Firstname', 
                        last_name = '$Lastname', 
                        middle_name = '$Middlename', 
                        phone_number = '$Phone_number', 
                        email = '$Email', 
                        organization = '$Organization', 
                        province = '$Province', 
                        city = '$City'
                        $profileImageSQL
                    WHERE id = '$user_id'";

            if (mysqli_query($conn, $sql)) {
                $fetchUpdatedUserSql = "SELECT * FROM users WHERE id = '$user_id'";
                $updatedUserQuery = mysqli_query($conn, $fetchUpdatedUserSql);
                if ($updatedUser = mysqli_fetch_assoc($updatedUserQuery)) {
                    $user = $updatedUser;
                }
            
                $_SESSION['success'] = "User information updated successfully!";
                header("location: profile.php");
                exit();
            } else {
                $error = "Error updating user information: " . mysqli_error($conn);
            }
        }
    }
 }


 if(isset($_POST['remove-image'])){
    $currentImage = $user['profile_image'];

    if (!empty($currentImage)) {
        $imagePath = 'profile_images/' . $currentImage;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $removeImageSql = "UPDATE users SET profile_image = NULL WHERE id = '$user_id'";
    if (mysqli_query($conn, $removeImageSql)) {
        $_SESSION['success'] = "Image removed successfully!";
        header("location: profile.php");
        exit();
    } else {
        $error = "Error removing image: " . mysqli_error($conn);
    }
 }

 $success=$_SESSION['success'] ?? '';
 unset($_SESSION['success']);
?>
<section>
    <div class="flex items-center justify-between ">
        <div class="w-full p-3 bg-gradient-to-r from-blue-100 to-white flex flex-col items-center justify-center space-y-4 h-full">
            <div class="flex flex-col justify-center space-y-5 items-center">
                <h1 class="text-2xl font-bold text-blue-800">PROFILE</h1>
                <?php if($error):?>
                    <div class="text-red-500 font-bold text-center"><?=$error?></div>
                <?php endif;?>
                <?php if($success):?>
                    <div class="text-green-500 font-bold text-center"><?=$success?></div>
                <?php endif;?>
                <!-- Image Preview -->
                <form action="profile.php" method="post" id="profile-form" enctype="multipart/form-data" class="flex flex-col justify-center items-center h-full ">
                <div class="w-40 h-40  rounded-full overflow-hidden flex justify-center items-center bg-gray-100">
                <img 
                    id="image-preview" 
                    src="<?php echo $user['profile_image'] ? 'profile_images/' . $user['profile_image'] : 'assets/profile.png'; ?>" 
                    alt="Preview" 
                    class="w-full h-full object-cover">

                </div>
                
                <!-- Buttons -->
                <div class="flex space-x-4 mt-3 hidden" id="img-options">
                    <label for="upload-image" class="cursor-pointer bg-blue-800 text-white px-4 py-2 rounded-2xl" >
                        Upload Image
                    </label>
                    <input id="upload-image" type="file" accept="image/*" class="hidden" name="profile-image">
                    
                         <input type="submit" id="remove-image" name="remove-image" class=" bg-transparent border border-blue-800 text-blue-800 px-4 py-2 rounded-2xl" value="Remove Image" <?php if(!$user['profile_image']){ echo "disabled" ;}?>>
                </div>
                
            </div>
            <div class="flex flex-col "> 
                <label for="" class="text-gray-500 text-sm">First name</label>
                <input type="text" name="firstname" value="<?= $user['firstname']?>" id="" readonly class="focus:outline-none bg-transparent  border-2 p-2 rounded-2xl  border-blue-300 text-blue-800 font-bold">
            </div>
            <?php if($errors['firstname']):?>
                <div class="text-red-500 text-sm mb-3 text-center"><?=$errors['firstname']?></div>
            <?php endif;?>
            
            <div class="flex flex-col ">
            <label for="" class="text-gray-500 text-sm">Last name</label>
                <input type="text" name="lastname" value="<?= $user['last_name']?>" id="" readonly class="focus:outline-none bg-transparent border-2 p-2 rounded-2xl border-blue-300 text-blue-800 font-bold">
            </div>
            <div class="flex flex-col ">
                <label for="" class="text-gray-500 text-sm">Middle name</label>
                <input type="text" name="middlename" value="<?= $user['middle_name']?>" id="" readonly class="focus:outline-none bg-transparent border-2 p-2 rounded-2xl border-blue-300 text-blue-800 font-bold">
            </div>
            <div class="flex flex-col ">
                <label for="" class="text-gray-500 text-sm">Username</label>
                <input type="text" name="username" value="<?= $user['username']?>" id="" readonly class="focus:outline-none bg-transparent border-2 p-2 rounded-2xl border-blue-300 text-blue-800 font-bold">
                <span class="text-sm text-gray-500">Note: you can't change your username</span>
            </div>
        </div>
        <div class="w-full  h-full p-3">
        <button  type="button" class="float-right p-2 text-blue-800 border-2 border-blue-800 w-24 rounded-3xl flex items-center justify-center" id="edit-button">
            <span><i class="fas fa-edit"></i></span>
            <span class="ml-2">Edit</span>
        </button>
            <div class="flex flex-col mt-5  space-y-4 ">

                <div >
                    <div class="flex items-center space-x-2 mb-2 mt-2">
                        <i class="fas fa-phone-alt text-white rounded-full bg-blue-800 p-2 text-md transform rotate-180"></i>
                        <span class="text-gray-500 text-sm">Contact Number</span>
                    </div>
                    <input type="text" name="phonenumber" id="" value="<?= $user['phone_number']?>" readonly class="focus:outline-none bg-transparent w-80 border-2 p-2 rounded-2xl  border-blue-300 text-blue-800 font-bold">
                    <?php if($errors['phonenumber']):?>
                        <div class="text-red-500 text-sm mb-3 text-center"><?=$errors['phonenumber']?></div>
                    <?php endif;?>
                </div>
                <div>
                    <div class="flex items-center space-x-2 mb-2 mt-2">
                        <i class="fas fa-envelope text-white rounded-full bg-blue-800 p-2 text-md transform rotate-180"></i>
                        <span class="text-gray-500 text-sm">Email</span>
                    </div>
                    <input type="text" name="email" id="" value="<?= $user['email']?>" readonly class="focus:outline-none bg-transparent w-80  border-2 p-2 rounded-2xl  border-blue-300 text-blue-800 font-bold">
                    <?php if($errors['email']):?>
                        <div class="text-red-500 text-sm mb-3 text-center"><?=$errors['email']?></div>
                    <?php endif;?>
                </div>
                <div>
                    <div class="flex items-center space-x-2 mb-2 mt-2">
                        <i class="fas fa-building text-white rounded-full bg-blue-800 p-2 text-md transform rotate-180"></i>
                        <span class="text-gray-500 text-sm">Organization</span>
                    </div>
                    <input type="text" name="organization" id="" value="<?= $user['organization']?>" readonly class="focus:outline-none bg-transparent w-80 border-2 p-2 rounded-2xl  border-blue-300 text-blue-800 font-bold">
                </div>
                <div class="flex flex-col space-y-2 mt-3">
                    <div class="flex items-center space-x-2 ">
                        <i class="fas fa-map-marker-alt text-white rounded-full bg-blue-800 p-2 text-md transform rotate-180"></i>
                        <span class="text-gray-500 text-sm">CURRENT ADDRESS</span>    
                    </div>
                  <div>
                    <div>
                        <span class="text-gray-500 text-sm">Province</span>
                    </div>
                      <input type="text" name="province" id="" value="<?= $user['province']?>" readonly class=" focus:outline-none bg-transparent w-80 border-2 p-2 rounded-2xl  border-blue-300 text-blue-800 font-bold">
                  </div>
                  <div>
                    <div>
                        <span class="text-gray-500 text-sm">City</span>
                    </div>
                      <input type="text" name="city" id="" value="<?= $user['city']?>" readonly class="focus:outline-none bg-transparent w-80 border-2 p-2 rounded-2xl  border-blue-300 text-blue-800 font-bold">
                  </div>
                </div>
               
                <div class="mt-4  hidden" id="update-container">
                    <input type="submit" value="Update"name="Update" class="rounded-3xl text-white w-full bg-blue-800 p-3">
                </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    const uploadImageInput = document.getElementById("upload-image");
    const imagePreview = document.getElementById("image-preview");

    uploadImageInput.addEventListener("change", (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove("hidden");
            };
            reader.readAsDataURL(file);
        }
    });


    const editButton = document.getElementById("edit-button");
    const updateContainer = document.getElementById("update-container");
    const formInputs = document.querySelectorAll("input[readonly]:not([type='file']):not([name='username'])");
    const imgCont = document.getElementById("img-options");

    
    editButton.addEventListener("click", (event) => {
        event.preventDefault();
        
        formInputs.forEach(input => {
            input.removeAttribute("readonly");
        });
        
        updateContainer.classList.remove("hidden");
        imgCont.classList.remove("hidden");
    });
    
    let edit = <?= json_encode($edit) ?> ;
    if(edit){
        formInputs.forEach(input => {
            input.removeAttribute("readonly");
        });
        
        imgCont.classList.remove("hidden");
        updateContainer.classList.remove("hidden");
    }
</script>
