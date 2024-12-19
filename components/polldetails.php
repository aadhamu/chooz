<?php

if(!isset($_SESSION['pollid'])){
    header("location: dashboard.php");
  };
$pollid = $_SESSION['pollid'] ?? '';
$username = $user['username'];

if ($pollid) {
    $query = "UPDATE notifications SET is_read = 1 WHERE poll_id = '$pollid' AND username = '$username'";
    $result = mysqli_query($conn, $query);

    $pollDetailsQuery = "SELECT * FROM polls WHERE id = '$pollid'";
    $result = mysqli_query($conn, $pollDetailsQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $pollDetails = mysqli_fetch_assoc($result);
    } else {
        echo "No poll found with the given ID.";
    }
}


$alreadyFilled="SELECT * FROM nominees_details WHERE poll_id='$pollid' AND nominee_username='$username' ";
$alreadyFilledResult=mysqli_query($conn, $alreadyFilled);


$bio=$statement=$qualification=$linkedin=$twitter=$instagram='';
$errors=[
    'imageError'=>'',
    'bioError'=>'',
    'statementError'=>'',
    'qualificationError'=>'',
    'linkedinError'=>'',
    'twitterError'=>'',
    'instagramError'=>''
];

if(isset($_POST['nominee_details'])){
    
    $bio = mysqli_real_escape_string($conn,sanitize($_POST['nominee_bio']));
    $statement = mysqli_real_escape_string($conn,sanitize($_POST['nominee_statement']));
    $qualification = mysqli_real_escape_string($conn,sanitize($_POST['nominee_qualifications']));
    
    if(empty($bio)){
        $errors['bioError']="This field is required";
    }
    if(empty($statement)){
        $errors['statementError']="This field is required";
    }
    if(empty($qualification)){
        $errors['qualificationError']="This field is required";
    }
    if(!empty($_FILES['nominee_image']) && $_FILES['nominee_image']['size'] == 0){
        $errors['imageError']="This field is required";
    }
    if (!empty($_POST['linkedin'])) {
        if (!filter_var($_POST['linkedin'], FILTER_VALIDATE_URL) || !preg_match('/^https:\/\/(www\.)?linkedin\.com\/in\//', $_POST['linkedin'])) {
            $errors['linkedinError'] = "Please enter a valid URL)";
        }else{
            $linkedin = mysqli_real_escape_string($conn,sanitize($_POST['linkedin']));
        }
    }
    
    if (!empty($_POST['twitter'])) {
        if (!filter_var($_POST['twitter'], FILTER_VALIDATE_URL) || !preg_match('/^https:\/\/(www\.)?twitter\.com\//', $_POST['twitter'])) {
            $errors['twitterError'] = "Please enter a valid URL)";
        }else{
            $twitter = mysqli_real_escape_string($conn,sanitize($_POST['twitter']));
        }
    }
    
    if (!empty($_POST['instagram'])) {
        if (!filter_var($_POST['instagram'], FILTER_VALIDATE_URL) || !preg_match('/^https:\/\/(www\.)?instagram\.com\//', $_POST['instagram'])) {
            $errors['instagramError'] = "Please enter a valid URL";
        }else{
            $instagram = mysqli_real_escape_string($conn,sanitize($_POST['instagram']));
        }
    }
    
    if(!array_filter($errors)){
        
        // IMAGE VALIDATION
        $image_name = $_FILES['nominee_image']['name'];
        $image_tmp = $_FILES['nominee_image']['tmp_name'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $new_image_name = uniqid() . '.' . $image_ext;
        
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($image_ext), $allowed_extensions)) {
        $target_dir = 'nominees/'; 
        if (move_uploaded_file($image_tmp, $target_dir . $new_image_name)) {
            $query = "INSERT INTO nominees_details (nominee_bio, nominee_statement, nominee_qualifications, linkedin, twitter, instagram, nominee_image, nominee_username,poll_id) 
                      VALUES ('$bio', '$statement', '$qualification', '$linkedin', '$twitter', '$instagram', '$new_image_name', '$username','$pollid')";

            if (mysqli_query($conn, $query)) {
                $_SESSION['success'] = "Nominee details successfully submitted!";
                $bio=$statement=$qualification=$linkedin=$twitter=$instagram='';
                header('location: polldetails.php');
            } 
            } else {
                $errors['imageError'] = "Failed to upload image. Please try again.";
        }
        }else {
            $errors['imageError'] = "Invalid image file type. Only JPG, PNG, or GIF allowed.";
        }
    }
}

?>

<section class="ml-10 p-6 bg-white rounded-xl mx-auto ">
    <h2 class="text-3xl font-bold text-blue-800 mb-6"><?= ucwords($pollDetails['poll_title'])?></h2>

    <div class="mb-6 flex justify-center">
        <img id="pollImage" src="poll_image/<?=$pollDetails['poll_image']?>" alt="Poll Image" class="w-40 h-40 object-cover rounded-full cursor-pointer">
    </div>

    <div class="flex justify-center mb-4">
        <button id="viewDetailsButton" class="bg-blue-800 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-all">View Poll Details</button>
    </div>

    <div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg max-w-4xl">
                <div class="flex justify-center relative">
                <button id="closeImageModal" class="bg-red-600 absolute top-0 right-0 text-white py-2 px-4 rounded-lg hover:bg-red-500 transition-all">Close</button>
                <img id="modalImage" src="poll_image/<?=$pollDetails['poll_image']?>" alt="Poll Image" class="max-w-full max-h-96 object-contain">
            </div>
        </div>
    </div>

    <div id="pollDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg w-3/4 lg:w-1/2">
            <h2 class="text-2xl font-bold text-blue-800 mb-4">Poll Details</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="flex flex-col">
                    <p><strong class="text-blue-800">Poll Title:</strong> <span class="text-black block"><?=$pollDetails['poll_title']?></span></p>
                </div>
                <div class="flex flex-col">
                    <p><strong class="text-blue-800">Poll Description:</strong> <span class="text-black block"><?=$pollDetails['poll_description']?></span></p>
                </div>
                <div class="flex flex-col">
                    <p><strong class="text-blue-800">Poll Status:</strong> <span class="text-black block"><?=$pollDetails['poll_status']?></span></p>
                </div>

                <div class="flex flex-col">
                    <p><strong class="text-blue-800">Poll Category:</strong> <span class="text-black block"><?=$pollDetails['poll_category']?></span></p>
                </div>
                <div class="flex flex-col">
                    <p><strong class="text-blue-800">Poll Question:</strong> <span class="text-black block"><?=$pollDetails['poll_question']?></span></p>
                </div>
                <div class="flex flex-col">
                    <p><strong class="text-blue-800">Poll Visibility:</strong> <span class="text-black block"><?=$pollDetails['poll_visibility']?></span></p>
                </div>

                <div class="flex flex-col">
                    <p><strong class="text-blue-800">Poll Start Date:</strong> <span class="text-black block"><?=$pollDetails['start_date']?></span></p>
                </div>
                <div class="flex flex-col">
                    <p><strong class="text-blue-800">Poll End Date:</strong> <span class="text-black block"><?=$pollDetails['end_date']?></span></p>
                </div>
                <div class="flex flex-col">
                    <p><strong class="text-blue-800">Poll Anonymous:</strong> <span class="text-black block"><?= $pollDetails['anonymous_poll'] == 0 ? 'False' : 'True' ?></span></p>
                </div>

                <div class="flex flex-col">
                    <p><strong class="text-blue-800">Polling Method:</strong> 
                        <span class="text-black block">
                            <?php if ($pollDetails['polling_method'] === 'multiple_choice'): ?>
                                Voters can vote for more than one nominee.
                            <?php elseif ($pollDetails['polling_method'] === 'single_choice'): ?>
                                Voters can vote for only one nominee.
                            <?php endif; ?>
                        </span>
                    </p>
                </div>

                <?php if ($pollDetails['voter_pay_amount']): ?>
                    <div class="flex flex-col">
                        <p><strong class="text-blue-800">Amount Voters Pay:</strong> <span class="text-black block"><?=$pollDetails['voter_pay_amount']?></span></p>
                    </div>
                <?php endif; ?>

                <?php if ($pollDetails['poll_key']): ?>
                    <div class="flex flex-col">
                        <p><strong class="text-blue-800">Poll Key:</strong> <span class="text-black block"><?=$pollDetails['poll_key']?></span></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex justify-end mt-6">
                <button id="closeModalButton" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-500 transition-all">Close</button>
            </div>
        </div>
    </div>

  

    <?php 
      if ($alreadyFilledResult && mysqli_num_rows($alreadyFilledResult) === 0):?>
    <form action="polldetails.php" method="POST" enctype="multipart/form-data" class="nominee-form" id="nomineeForm">
        <h2 class="text-3xl font-bold text-blue-800 mb-6">Nominee Details</h2>

        <div class="mb-4">
            <label for="nominee_name" class="block text-blue-800 font-medium">Nominee Name:</label>
            <input type="text" id="nominee_name" name="nominee_name"  class="border p-2 w-full rounded" value="<?=$user['last_name']?> <?=$user['middle_name']?> <?=$user['firstname']?>">
        </div>

        <div class="mb-4">
            <label for="nominee_image" class="block text-blue-800 font-medium">Nominee Image:</label>
            <input type="file" id="nominee_image" name="nominee_image" accept="image/*" class="border p-2 w-full rounded">
        </div>
        <?php if(isset($errors['imageError'])):?>
            <div class="text-sm text-red-600"><?= $errors['imageError']?></div>
        <?php endif;?>

        <div class="mb-4">
            <label for="nominee_bio" class="block text-blue-800 font-medium">Short Biography:</label>
            <textarea id="nominee_bio" name="nominee_bio" rows="4" class="border p-2 w-full rounded"><?= $bio?></textarea>
        </div>
        <?php if(isset($errors['bioError'])):?>
            <div class="text-sm text-red-600"><?= $errors['bioError']?></div>
        <?php endif;?>

        <div class="mb-4">
            <label for="nominee_statement" class="block text-blue-800 font-medium">Goals (Statement of Intent):</label>
            <textarea id="nominee_statement" name="nominee_statement" rows="4" class="border p-2 w-full rounded"><?=$statement?></textarea>
        </div>
        <?php if(isset($errors['statementError'])):?>
            <div class="text-sm text-red-600"><?= $errors['statementError']?></div>
        <?php endif;?>

        <div class="mb-4">
            <label for="nominee_qualifications" class="block text-blue-800 font-medium">Expetise & Achievement:</label>
            <textarea id="nominee_qualifications" name="nominee_qualifications" rows="4" class="border p-2 w-full rounded"><?=$qualification?></textarea>
        </div>
        <?php if(isset($errors['qualificationError'])):?>
            <div class="text-sm text-red-600"><?= $errors['qualificationError']?></div>
        <?php endif;?>

        <div class="mb-4">
            <label for="nominee_social_media" class="block text-blue-800 font-medium">Social Media Links (Optional)</label>

            <div class="flex justify-between gap-4">
                <div>
                    <div class="flex items-center space-x-2 w-full">
                        <i class="fab fa-linkedin text-blue-800"></i>
                        <input type="url" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/nominee" class="border p-2 w-full rounded" value="<?=$linkedin?>">
                    </div>
                    <?php if(isset($errors['linkedinError'])):?>
                        <div class="text-sm block text-red-600"><?= $errors['linkedinError']?></div>
                    <?php endif;?>
                </div>

                <div>
                    <div class="flex items-center space-x-2 w-full">
                        <i class="fab fa-twitter text-blue-800"></i>
                        <input type="url" id="twitter" name="twitter" placeholder="https://twitter.com/nominee" class="border p-2 w-full rounded" value="<?=$twitter?>">
                    </div>
                    <?php if(isset($errors['twitterError'])):?>
                        <div class="text-sm text-red-600"><?= $errors['twitterError']?></div>
                    <?php endif;?>
                </div>

                <div>
                    <div class="flex items-center space-x-2 w-full">
                        <i class="fab fa-instagram text-pink-600"></i>
                        <input type="url" id="instagram" name="instagram" placeholder="https://instagram.com/nominee" class="border p-2 w-full rounded" value="<?=$instagram?>">
                    </div>
                    <?php if(isset($errors['instagramError'])):?>
                        <div class="text-sm text-red-600"><?= $errors['instagramError']?></div>
                    <?php endif;?>
                </div>
            </div>                                                                                                                                                                                                                                                                                                                                                                                        
        </div>

        <div class="mb-4 text-center">
        <button type="button" id="submitFormButton" class="bg-blue-800 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-all">
            Submit
        </button>
        </div>
        <div id="confirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Are you sure you want to submit?</h2>
                    <div class="flex justify-end gap-4">
                        <button id="cancelButton" class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-400 transition-all">
                            Cancel
                        </button>
                        <button type="submit" id="confirmSubmitButton" name="nominee_details" class="bg-blue-800 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-all">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
</form>
<?php else:
   require_once 'nomineeDetails.php';
endif;
?>

</section>

<script>
    const pollImage = document.getElementById('pollImage');
    const imageModal = document.getElementById('imageModal');
    const closeImageModal = document.getElementById('closeImageModal');
    const modalImage = document.getElementById('modalImage');

    pollImage.addEventListener('click', () => {
        imageModal.classList.remove('hidden');
        modalImage.src = pollImage.src;
    });

    closeImageModal.addEventListener('click', () => {
        imageModal.classList.add('hidden');
    });

    window.addEventListener('click', (e) => {
        if (e.target === imageModal) {
            imageModal.classList.add('hidden');
        }
    });

    const viewDetailsButton = document.getElementById('viewDetailsButton');
    const pollDetailsModal = document.getElementById('pollDetailsModal');
    const closeModalButton = document.getElementById('closeModalButton');

    viewDetailsButton.addEventListener('click', () => {
        pollDetailsModal.classList.remove('hidden');
    });

    closeModalButton.addEventListener('click', () => {
        pollDetailsModal.classList.add('hidden');
    });

    window.addEventListener('click', (e) => {
        if (e.target === pollDetailsModal) {
            pollDetailsModal.classList.add('hidden');
        }
    });

    const submitFormButton = document.getElementById('submitFormButton');
    const confirmationModal = document.getElementById('confirmationModal');
    const cancelButton = document.getElementById('cancelButton');
    // const confirmSubmitButton = document.getElementById('confirmSubmitButton');
    const nomineeForm = document.getElementById('nomineeForm');

    // Show modal on button click
    submitFormButton.addEventListener('click', () => {
        confirmationModal.classList.remove('hidden');
    });

    // Close modal on cancel
    cancelButton.addEventListener('click', () => {
        confirmationModal.classList.add('hidden');
    });

    // Submit form on confirm
    // confirmSubmitButton.addEventListener('click', () => {
    //     nomineeForm.submit();
    // });
</script>
