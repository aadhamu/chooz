<?php

// Function to generate a unique 4-letter key for private poll
function generatePollKey($length = 4) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $key;
}

$pollTitle =$pollDescription =$pollKey=$success =$error=$pollType=$pollPaymentMethod=$votersPayAmount=$creatorPaymentMethod=$creatorPricePerVote=$creatorPackage=$pollVisibility=$pollCategory=$anonymousPoll=$pollStartDate=$pollEndDate=$pollStructure=$pollQuestion=$pollOption=$pollUsername='';
$creator = $user['firstname'];
$creatorid = $user['id'];

$errors=[
    'pollTitle'=>'',
    'pollDescription'=>'',
    'pollType'=>'',
    'pollPaymentMethod'=>'',
    'votersPayAmount'=>'',
    'creatorPaymentMethod'=>'',
    'creatorPackage'=>'',
    'pollImage'=>'',
    'pollVisibility'=>'',
    'pollCategory'=>'',
    'pollStartDate'=>'',
    'pollEndDate'=>'',
    'pollStructure'=>'',
    'pollQuestion'=>'',
    'pollOption'=>'',
    'pollUsername'=>'',
    'pollMethod'=>''
];
if(isset($_POST['submit_poll'])){
    $pollTitle = mysqli_real_escape_string($conn,sanitize($_POST['poll_title']));
    $pollDescription = mysqli_real_escape_string($conn,sanitize($_POST['poll_description']));
    $votersPayAmount = mysqli_real_escape_string($conn,sanitize($_POST['voter_pay_amount']));
    $pollCategory = mysqli_real_escape_string($conn,sanitize($_POST['poll_category']));
    $anonymousPoll = isset($_POST['anonymous_poll']) 
    ? mysqli_real_escape_string($conn, sanitize($_POST['anonymous_poll'])) 
    : 0;
    $pollStartDate = mysqli_real_escape_string($conn,sanitize($_POST['start_date']));
    $pollEndDate = mysqli_real_escape_string($conn,sanitize($_POST['end_date']));
    
    if(empty($pollTitle)){
        $errors['pollTitle']='Poll Title required';
    }
    
    if(empty($pollDescription)){
        $errors['pollDescription']='Poll Description required';
    }
    
    if (empty($_POST['voting_type'])) {
        $errors['pollMethod'] = "Please select a polling method.";
    }else{
        $pollMethod = mysqli_real_escape_string($conn, sanitize($_POST['voting_type']));
    }
    
    if (empty($_POST['voting_method'])) {
        $errors['pollType'] = "Please select a polling type.";
    }else{
        $pollType = mysqli_real_escape_string($conn, sanitize($_POST['voting_method']));
    }
    
    
    if (empty($_POST['paid_voting'])) {
        $errors['pollPaymentMethod'] = "Payment method required";
    } else {
        $pollPaymentMethod = mysqli_real_escape_string($conn, sanitize($_POST['paid_voting']));
    }
    
    if (!isset($_FILES['poll_image']) || $_FILES['poll_image']['error'] === UPLOAD_ERR_NO_FILE || $_FILES['poll_image']['size'] === 0) {
        $errors['pollImage'] = "No file uploaded.";
    }
    
    if(isset($pollPaymentMethod)){
        if($pollPaymentMethod === 'poll-participant'){
            if(empty($votersPayAmount)){
                $errors['votersPayAmount']='input an amount';
            }
        }elseif($pollPaymentMethod === 'poll-creatorid'){
            if (empty($_POST['creator_payment_type'])) {
                $errors['creatorPaymentMethod'] = "Please select a payment method.";
            } else {
                $creatorPaymentMethod = mysqli_real_escape_string($conn, sanitize($_POST['creator_payment_type']));
            }
        }
    }
    
    if(isset($creatorPaymentMethod)){
        if($creatorPaymentMethod === 'per_vote'){
            $creatorPricePerVote = mysqli_real_escape_string($conn,sanitize($_POST['price_per_vote']));
        }elseif ($creatorPaymentMethod === 'bulk_voting') {
            $creatorPackage = mysqli_real_escape_string($conn,sanitize($_POST['vote_package']));
            
            if(empty($creatorPackage)){
                $errors['creatorPackage'] = "select a package plan.";
            }
        }
    }
    
    if (empty($_POST['poll_visibility'])) {
        $errors['pollVisibility'] = "This field is required.";
      } else {
          $pollVisibility = mysqli_real_escape_string($conn, sanitize($_POST['poll_visibility']));
        }

        if($pollVisibility === 'private'){
            $pollKey = generatePollKey();

            while (true) {
                $checkKeyQuery = "SELECT id FROM polls WHERE poll_key = '$pollKey'";
                $result = mysqli_query($conn, $checkKeyQuery);
        
                if (mysqli_num_rows($result) === 0) {
                    break; 
                } else {
                    $pollKey = generatePollKey(); 
                }
            }
        }else {
            $pollKey = null;
        }

        
        if (empty($pollCategory)) {
            $errors['pollCategory'] = "select a caategory.";
        }

      if (empty($pollStartDate)) {
        $errors['pollStartDate'] = 'This field is required';
    } else {
        $timestamp = strtotime($pollStartDate);
        if ($timestamp === false || $pollStartDate !== date('Y-m-d\TH:i', $timestamp)) {
            $errors['pollStartDate'] = 'Enter a valid start date and time.';
        }
    }
 
    if (empty($pollEndDate)) {
        $errors['pollEndDate'] = 'This field is required';
    } else {
        $timestampEnd = strtotime($pollEndDate);
        if ($timestampEnd === false || $pollEndDate !== date('Y-m-d\TH:i', $timestampEnd)) {
            $errors['pollEndDate'] = 'Enter a valid end date and time.';
        }
        if (!empty($pollStartDate) && strtotime($pollEndDate) <= strtotime($pollStartDate)) {
            $errors['pollEndDate'] = 'The end date must be later than the start date.';
        }
    }
    
    if (empty($_POST['pollStructure'])) {
       $errors['pollStructure'] = "This field is required.";
     } else {
        $pollStructure = mysqli_real_escape_string($conn, sanitize($_POST['pollStructure']));
    }
    
    if (isset($pollStructure)) {
        if($pollStructure ===  'simplePoll'){
            $pollQuestion = mysqli_real_escape_string($conn, sanitize($_POST['poll_simple_question']));
            $pollOption = $_POST['poll_option'] ?? [];
            
            if(empty($pollQuestion)){
                $errors['pollQuestion']="This field is required";
            }
            if (!is_array($pollOption) || count($pollOption) < 2) {
                $errors['pollOption']= 'At least two poll options are required.';
            }
            
            $pollOption = array_map(function($option) use ($conn) {
                return mysqli_real_escape_string($conn, sanitize($option));
            }, $pollOption);
            
        }elseif ($pollStructure === 'detailedPoll') {
            $pollQuestion = mysqli_real_escape_string($conn, sanitize($_POST['poll_username_question']));
            $pollUsername = $_POST['option_username'] ?? [];
            

            if(empty($pollQuestion)){
                $errors['pollQuestion']="This field is required";
            }
            if (!is_array($pollUsername) || count($pollUsername) < 2) {
                $errors['pollUsername']= 'At least two username are required.';
            }
            $pollUsername = array_map(function($option) use ($conn) {
                return mysqli_real_escape_string($conn, sanitize($option));
            }, $pollUsername);
        }
     } 

     $userNotFound = false;
     if(is_array($pollUsername)){
        foreach ($pollUsername as $username) {
            $sql = "SELECT username FROM users WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) == 0) {
                $errors['pollUsername'] = "User '$username' not found.";
                $error = "UnKnown username";
                $userNotFound = true;
            }
        }
    }
     if(!array_filter($errors)){
        if (isset($_FILES['poll_image']) && $_FILES['poll_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['poll_image']['tmp_name'];
            $fileName = $_FILES['poll_image']['name'];
            $allowedFileTypes = ['jpeg', 'png', 'jpg'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $uploadDir = 'profile_images/';
            if (in_array($fileExtension, $allowedFileTypes)) {
                $uploadDir = 'poll_image/';
                $newFileName = uniqid('poll_image_', true) . '.' . $fileExtension;
                $pollImagePath =$newFileName;
                $pollPath = $uploadDir . $newFileName;
                $isUsername=false;
                if(is_array($pollUsername)){
                    $isUsername=true;
                }
               
                if (move_uploaded_file($fileTmpPath, $pollPath )) {
                    $pollImagePath = $pollPath;
                    $pollStartDateCheck = substr($pollStartDate, 0, 10); 
                    $today = date('Y-m-d');
                    $pollStatus='';
                    if ($pollStartDateCheck === $today) {
                        $pollStatus = 'ongoing';
                    } elseif ($pollStartDateCheck > $today) {
                        $pollStatus = 'not_started';
                    } 

                    
                    $sql = "INSERT INTO polls (poll_title, poll_description, poll_question, polling_method, polling_payment, voter_pay_amount, creator_polling_type,price_per_vote,poll_package,poll_image,poll_visibility,poll_category,anonymous_poll,start_date,end_date,is_username,poll_key,poll_creator,poll_status,polling_type)
                            VALUES ('$pollTitle', '$pollDescription', '$pollQuestion', '$pollType','$pollPaymentMethod', '$votersPayAmount', '$creatorPaymentMethod','$creatorPricePerVote','$creatorPackage','$newFileName','$pollVisibility','$pollCategory','$anonymousPoll','$pollStartDate','$pollEndDate','$isUsername',".($pollKey === null ? "NULL" : "'$pollKey'").",'$creatorid','$pollStatus','$pollMethod')";

                    if (mysqli_query($conn, $sql)){
                        $pollId = mysqli_insert_id($conn);
                        
                        if (!empty($pollUsername) && is_array($pollUsername)) {
                            $options = implode(',', $pollUsername); 
                            $sqlOption = "INSERT INTO poll_options (poll_id, option_text, is_username) VALUES ('$pollId', '$options', '$isUsername')";
                            mysqli_query($conn, $sqlOption);

                            $usernames = explode(',', $options);
                            foreach ($usernames as $username) {
                                $username = trim($username);
                                $message = "Congratulations! You have been added as a participant in the poll titled $pollTitle. Best of luck!";
                                $notificationQuery = "INSERT INTO notifications (poll_id, username, message) VALUES ('$pollId', '$username', '$message')";
                                mysqli_query($conn, $notificationQuery);
                            }
                        } elseif (!empty($pollOption) && is_array($pollOption)) {
                            $options = implode(',', $pollOption); 
                            $sqlOption = "INSERT INTO poll_options (poll_id, option_text, is_username) VALUES ('$pollId', '$options', '$isUsername')";
                            mysqli_query($conn, $sqlOption);
                        }
                        
                        if(is_array($pollUsername)){
                        foreach ($pollUsername as $username) {
                            $sqlUser = "SELECT email, firstname,id FROM users WHERE username = '$username'";
                            $resultUser = mysqli_query($conn, $sqlUser);
                            if ($resultUser && $users = mysqli_fetch_assoc($resultUser)) {
                                $email = $users['email'];
                                $fullName = $users['firstname'];
            
                                if ($pollVisibility === 'private') {
                                    $headers = "From: ogbenihappy05@gmail.com\r\n";
                                    $subject = "You're Invited to Participate in a Private Poll";
                                    $message = "Dear $fullName,\n\n"
                                             . "You have been invited to participate in a <h2>private poll</h2>titled \"$pollTitle\" Created by $creator. This poll is exclusive and requires an access key to participate.\n\n"
                                             . "Here are the details:\n"
                                             . "------------------------------------------\n"
                                             . "Poll Question: $pollQuestion\n"
                                             . "Access Key: $pollKey\n"
                                             . "------------------------------------------\n\n"
                                             . "To participate, please visit our poll platform and use the access key provided above.\n\n"
                                             . "We value your input and look forward to your response.\n\n"
                                             . "If you have any questions or encounter any issues, feel free to contact us.\n\n"
                                             . "Best regards,\n"
                                             . "Chooz\n"
                                             . "Email: ogbenihappy05@gmail.com";


                                   
                                } else {
                                $headers = "From: ogbenihappy05@gmail.com\r\n";
                                    $subject = "You're Invited to Participate in a Poll";
                                    $message = "Dear $fullName,\n\n"
                                             . "You have been invited to participate in a poll titled \"$pollQuestion\"  Created by $creator .\n\n"
                                             . "To participate, please visit our poll platform and share your response.\n\n"
                                             . "We value your input and look forward to your response.\n\n"
                                             . "If you have any questions or encounter any issues, feel free to contact us.\n\n"
                                             . "Best regards,\n"
                                             . "Chooz\n"
                                             . "Email: ogbenihappy05@gmail.com";
                                }
                                
            
                                if (!mail($email, $subject, $message, $headers)) {
                                    $error= "Failed to send email to $email";
                                }
                            }
                        }
                    }
                    $success="poll created successfully";
                        $pollTitle =$pollDescription =$pollType=$pollPaymentMethod=$votersPayAmount=$creatorPaymentMethod=$creatorPricePerVote=$creatorPackage=$pollVisibility=$pollCategory=$anonymousPoll=$pollStartDate=$pollEndDate=$pollStructure=$pollQuestion=$pollOption=$pollUsername='';
                    }
                   
                } else {
                    $errors['pollImage'] = "There was an error moving the uploaded file.";
                }
            } else {
                $errors['pollImage'] = "Uploaded file is not a valid image. Allowed types are JPEG, PNG";
            }
        } else {
            $errors['pollImage'] = "File upload error or no file uploaded.";
        }
    
     }
}

$sumOfFreeVote="SELECT count(*) AS total FROM polls WHERE poll_creator ='$creatorid'";
$freeVoteQuery=mysqli_query($conn, $sumOfFreeVote);
$freeVoteArray=mysqli_fetch_assoc($freeVoteQuery);
?>

<div class=" bg-white flex space-x-5 shadow-lg border-2 to-blue-500  mt-8 p-6 rounded-lg bg">
    
    <div class="w-full">
        <h2 class="text-3xl font-bold text-blue-800 mb-8 text-center">Create Poll</h2>
        
        <form  action="createpoll.php" method="POST" enctype="multipart/form-data">
        <?php if($success):?>
        <div class="text-green-500 text-sm font-bold mb-3 text-center"><?=$success?></div>
        <?php endif;?>
        <?php if($error):?>
        <div class="text-red-500 text-sm font-bold mb-3 text-center"><?=$error?></div>
        <?php endif;?>
            <div id="step1" class="step active">
                <h3 class="text-xl font-bold text-blue-800 mb-6">Step 1: Polling Title and Description</h3>
                
                <div class="mb-6 flex flex-col space-y-3">
                    <input type="text" name="poll_title" id="pollTitle" class=" font-bold w-full border-2 p-4 mt-2   border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200 text-gray-800" placeholder="Poll Title" value="<?=$pollTitle?>">
                    <?php if($errors['pollTitle']):?>
                        <div class="text-red-500 text-sm font-bold mb-3 text-center"><?=$errors['pollTitle']?></div>
                        <?php endif;?>
                    </div>
    
                <div class="mb-6 flex flex-col space-y-3">
                    <textarea name="poll_description" id="pollDescription" rows="5" class="w-full text-gray-800 border-2 p-4 mt-2 border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" placeholder="Poll Description" ><?=$pollDescription?></textarea>
                    
                    <?php if($errors['pollDescription']):?>
                        <div class="text-red-500 text-sm font-bold mb-3 text-center"><?=$errors['pollDescription']?></div>
                    <?php endif;?>
                </div>
                
                <button type="button" onclick="showStep(2)" class="bg-blue-800 text-white px-6 py-3 rounded-lg mt-4  hover:bg-blue-900 transition duration-200 ">Next</button>
            </div>
            
            <div id="step2" class="step hidden">
                <h3 class="text-xl font-bold text-blue-800 mb-6">Step 2: Poll Setup</h3>
                
                <div class="mb-6">
                    <label class="block  font-bold text-blue-800">Polling Choice</label>
                    <div class="flex items-center mt-2">
                        <input type="radio" 
                        name="voting_method" 
                        id="singleChoice" 
                        value="single_choice" 
                        <?php if (isset($_POST['voting_method']) && $_POST['voting_method'] == 'single_choice') echo 'checked'; ?>
                        class="w-5 h-5 border-2 rounded-lg" >
                        <label for="singleChoice" class="ml-3 text-sm text-gray-700">Single Choice (Voters nominate one nominee)</label>
                    </div>
                    <div class="flex items-center mt-2">
                        <input type="radio" 
                        name="votving_method" 
                        id="multipleChoice" 
                        value="multiple_choice" 
                        class="w-5 h-5 border-2 rounded-lg" 
                        <?php if (isset($_POST['voting_method']) && $_POST['voting_method'] == 'multiple_choice') echo 'checked'; ?>
                        >
                        <label for="multipleChoice" class="ml-3 text-sm text-gray-700">Multiple Choice (Voters nominate more than one nominee)</label>
                    </div>
                    <?php if($errors['pollType']):?>
                        <div class="text-red-500 text-sm font-bold mt-2 text-center"><?=$errors['pollType']?></div>
                    <?php endif;?>
                </div>

                <div class="mb-6">
                    <label class="block  font-bold text-blue-800">Polling Type</label>
                    <div class="flex items-center mt-2">
                        <input type="radio" 
                        name="voting_type" 
                        id="singleVote" 
                        value="single_vote" 
                        <?php if (isset($_POST['voting_type']) && $_POST['voting_type'] == 'single_vote') echo 'checked'; ?>
                        class="w-5 h-5 border-2 rounded-lg" >
                        <label for="singleVote" class="ml-3 text-sm text-gray-700">Single Vote (Voters cast only a single vote)</label>
                    </div>
                    <div class="flex items-center mt-2">
                        <input type="radio" 
                        name="voting_type" 
                        id="multipleVote" 
                        value="multiple_vote" 
                        class="w-5 h-5 border-2 rounded-lg" 
                        <?php if (isset($_POST['voting_type']) && $_POST['voting_type'] == 'multiple_vote') echo 'checked'; ?>
                        >
                        <label for="multipleVote" class="ml-3 text-sm text-gray-700">Multiple Choice (Voters cast multiple vote on one nominee)</label>
                    </div>
                    <?php if($errors['pollMethod']):?>
                        <div class="text-red-500 text-sm font-bold mt-2 text-center"><?=$errors['pollMethod']?></div>
                    <?php endif;?>
                </div>
                
                <div class="mb-6">
                    <label class="block  font-bold text-blue-800">Select Polling Payment Method</label>
                
                    <div class="flex items-center mt-2">
                        <input type="radio" name="paid_voting" id="voterPays" value="poll-participant" class="w-5 h-5 border-2 rounded-lg"
                        <?php if (isset($_POST['paid_voting']) && $_POST['paid_voting'] == 'poll-participant') echo 'checked'; ?>
                        >
                        <label for="voterPays" class="ml-3 text-sm text-gray-700">Voters pay</label>
                    </div>
                    <div id="voterPaysInput" class="hidden mt-4">
                        <input type="text" name="voter_pay_amount" id="voterPayAmount" class="w-full text-blue-800 border-2 p-4 mt-2 border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" placeholder="Amount per vote" min="0.01" value="<?= $votersPayAmount?>">
                        <?php if($errors['votersPayAmount']):?>
                             <div class="text-red-500 text-sm font-bold mt-2 text-center"><?=$errors['votersPayAmount']?></div>
                        <?php endif;?>  
                    </div>
                    
                    <div class="flex items-center mt-2">
                        <input type="radio" name="paid_voting" id="creatorPays" value="poll-creator" class="w-5 h-5 border-2 rounded-lg"
                        <?php if (isset($_POST['paid_voting']) && $_POST['paid_voting'] == 'poll-creator') echo 'checked'; ?>
                        >
                        <label for="creatorPays" class="ml-3 text-sm text-gray-700">Creator pays</label>
                    </div>
                    
                    <div id="creatorPaysOptions" class="hidden mt-4 flex space-x-3 items-center">
                        <label class="block text-sm font-medium text-gray-700">Select Polling payment method:</label>
                        <div class="flex items-center mt-2">
                            <input type="radio" name="creator_payment_type" id="perVote" value="per_vote" class="w-5 h-5 border-2 rounded-lg"
                        <?php if (isset($_POST['creator_payment_type']) && $_POST['creator_payment_type'] == 'per_vote') echo 'checked'; ?>
                        >
                        <label for="perVote" class="ml-3 text-sm text-gray-700">Per Vote</label>
                    </div>
                    <div class="flex items-center mt-2">
                        <input type="radio" name="creator_payment_type" id="groupVoting" value="bulk_voting" class="w-5 h-5 border-2 rounded-lg"
                        <?php if (isset($_POST['creator_payment_type']) && $_POST['creator_payment_type'] == 'bulk_voting') echo 'checked'; ?>
                            >
                            <label for="groupVoting" class="ml-3 text-sm text-gray-700">Bulk Voting (Package)</label>
                        </div>
                    </div>
                    <?php if($errors['creatorPaymentMethod']):?>
                             <div class="text-red-500 text-sm font-bold mt-2 text-center"><?=$errors['creatorPaymentMethod']?></div>
                        <?php endif;?>
                        
                        <div id="prepaidPackages" class="hidden mt-4">
                            <label class="block text-sm font-medium text-gray-700">Select a package:</label>
                            <select name="vote_package" id="votePackage" class="w-full border-2 p-4 rounded-lg mt-2 focus:outline-none">
                                <option value="">Select...</option>
                                <option value="50" <?php if (isset($_POST['vote_package']) && $_POST['vote_package'] == '50') echo 'selected'; ?>>50 votes - $20</option>
                                <option value="100" <?php if (isset($_POST['vote_package']) && $_POST['vote_package'] == '100') echo 'selected'; ?>>100 votes - $35</option>
                                <option value="200" <?php if (isset($_POST['vote_package']) && $_POST['vote_package'] == '200') echo 'selected'; ?>>200 votes - $60</option>
                            </select>

                            <?php if($errors['creatorPackage']):?>
                                <div class="text-red-500 text-sm font-bold mt-2 text-center"><?=$errors['creatorPackage']?></div>
                            <?php endif;?>
                    </div>
                    
                    <div id="perVoteOption" class="hidden mt-4">
                        <label class="block text-sm text-gray-700">Price per vote:</label>
                        <input type="text" name="price_per_vote" id="pricePerVote" class="w-full border-2 p-4 rounded-lg mt-2 focus:outline-none" placeholder="Enter price per vote" min="0.01" value="$0.3" readonly>
                    </div>
                    <?php
                     if($freeVoteArray['total'] < 3):
                    ?>
                    <div class="flex items-center mt-2">
                        <input type="radio" name="paid_voting" id="freeVote" value="free_vote" class="w-5 h-5 border-2 rounded-lg"
                        <?php if (isset($_POST['paid_voting']) && $_POST['paid_voting'] == 'free_vote') echo 'checked'; ?>
                        >
                        <label for="freeVote" class="ml-3 text-sm text-gray-700">Free vote (you have <?php print_r(3-$freeVoteArray['total'])?> trials with 50 limited votes)</label>
                    </div>
                    <?php endif;?>
                    <?php if($errors['pollPaymentMethod']):?>
                        <div class="text-red-500 text-sm font-bold mt-2 text-center"><?=$errors['pollPaymentMethod']?></div>
                        <?php endif;?>
                    </div>
                    
                    <div class="mb-6">
                        <label for="pollImage" class="block  font-bold text-blue-800">Poll Image</label>
                        <input type="file" name="poll_image" id="pollImage" class="w-full bg-blue-100 border-2 p-4 rounded-lg mt-2 focus:ring-2 focus:ring-blue-500">
                        <?php if($errors['pollImage']):?>
                            <div class="text-red-500 text-sm font-bold mb-3 text-center"><?= $errors['pollImage']?></div>
                        <?php endif;?>
                    </div>
    
                    <div class="mb-6">
                        <label class="block  font-bold text-blue-800">Poll Visibility:</label>
                        <div class="flex items-center mt-2">
                            <input type="radio" name="poll_visibility" id="publicPoll" value="public" class="w-5 h-5 border-2 rounded-lg" 
                        <?php if (isset($_POST['poll_visibility']) && $_POST['poll_visibility'] == 'public') echo 'checked'; ?>
                            >
                            <label for="publicPoll" class="ml-3 text-sm text-gray-700">Public</label>
                        </div>
                        <div class="flex items-center mt-2">
                            <input type="radio" name="poll_visibility" id="privatePoll" value="private" class="w-5 h-5 border-2 rounded-lg" 
                        <?php if (isset($_POST['poll_visibility']) && $_POST['poll_visibility'] == 'private') echo 'checked'; ?>
                            >
                            <label for="privatePoll" class="ml-3 text-sm text-gray-700">Private</label>
                        </div>
                        <?php if($errors['pollVisibility']):?>
                            <div class="text-red-500 text-sm font-bold mb-3 text-center"><?=$errors['pollVisibility']?></div>
                        <?php endif;?>
                    </div>
    
                    <div class="mb-6">
                        <label for="pollCategory" class="block  font-bold text-blue-800">Poll Category</label>
                        <select name="poll_category" id="pollCategory" class="w-full border-2 p-4 rounded-lg bg-blue-100 mt-2 focus:outline-none">
                            <option value="">Select a category</option>
                            <option value="sports" <?php if (isset($_POST['poll_category']) && $_POST['poll_category'] == 'sports') echo 'selected'; ?>>Sports</option>
                            <option value="politics" <?php if (isset($_POST['poll_category']) && $_POST['poll_category'] == 'politics') echo 'selected'; ?>>Politics</option>
                            <option value="entertainment" <?php if (isset($_POST['poll_category']) && $_POST['poll_category'] == 'entertainment') echo 'selected'; ?>>Entertainment</option>
                            <option value="education" <?php if (isset($_POST['poll_category']) && $_POST['poll_category'] == 'education') echo 'selected'; ?>>Education</option>
                            <option value="other" <?php if (isset($_POST['poll_category']) && $_POST['poll_category'] == 'other') echo 'selected'; ?>>Other</option>
                        </select>
                        <?php if($errors['pollCategory']):?>
                            <div class="text-red-500 text-sm font-bold mb-3 text-center"><?= $errors['pollCategory']?></div>
                        <?php endif;?>
                    </div>
    
                    <div class="mb-6">
                        <label class="block font-bold text-blue-800">Anonymous Poll (Optional)</label>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" 
                                name="anonymous_poll" 
                                id="anonymousPoll" 
                                class="w-5 h-5 border-2 rounded-lg"
                            >
                            <label for="anonymousPoll" class="ml-3 text-sm text-gray-700">Enable anonymous voting</label>
                        </div>
                    </div>

                    <div class="mb-6 grid grid-cols-2 gap-6">
                        <div>
                            <label for="startDate" class="block  font-bold text-blue-800">Poll Start Date</label>
                            <input type="datetime-local" name="start_date" id="startDate" class="w-full bg-blue-100 border-2 p-4 rounded-lg mt-2 focus:ring-2 focus:ring-blue-500" value="<?= $pollStartDate?>" >
                            <?php if($errors['pollStartDate']):?>
                                <div class="text-red-500 text-sm font-bold mt-2 text-center"><?=$errors['pollStartDate']?></div>
                            <?php endif;?>
                        </div>
                        <div>
                            <label for="endDate" class="block  font-bold text-blue-800">Poll End Date</label>
                            <input type="datetime-local" name="end_date" id="endDate" class="w-full bg-blue-100 border-2 p-4 rounded-lg mt-2 focus:ring-2 focus:ring-blue-500" value="<?= $pollEndDate?>">
                            <?php if($errors['pollEndDate']):?>
                                <div class="text-red-500 text-sm font-bold mt-2 text-center"><?=$errors['pollEndDate']?></div>
                            <?php endif;?>
                        </div>
                    </div>
    
                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="showStep(1)" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200">Back</button>
                        <button type="button" onclick="showStep(3)" class="bg-blue-800 text-white px-6 py-3 rounded-lg hover:bg-blue-900 transition duration-200">Next</button>
                    </div>
            </div>
    
            <div id="step3" class="step hidden">
                <h3 class="text-xl font-bold text-blue-800 mb-6">Step 3: Poll</h3>
    
                <div id="pollTypeSelection" class="mb-6">
                    <label class="block  font-bold text-blue-800">Poll Structure</label>
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center">
                            <input type="radio" id="simplePoll" name="pollStructure" value="simplePoll" class="mr-2" onclick="togglePollInputs()"
                            <?php if (isset($_POST['pollStructure']) && $_POST['pollStructure'] === 'simplePoll') echo 'checked'; ?>
                            >
                            <label for="simplePoll" class="text-sm">Option-Based-Poll</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="detailedPoll" name="pollStructure" value="detailedPoll" class="mr-2" onclick="togglePollInputs()"
                            <?php if (isset($_POST['pollStructure']) && $_POST['pollStructure'] == 'detailedPoll') echo 'checked'; ?>
                            >
                            <label for="detailedPoll" class="text-sm">User-Based Poll</label>
                        </div>
                    </div>
                    <?php if($errors['pollStructure']):?>
                        <div class="text-red-500 text-sm font-bold mt-2 text-center"><?=$errors['pollStructure']?></div>
                    <?php endif;?>
                </div>
    
                <div id="simplePollInputs" class="hidden">
    
                    <label class="block text-sm font-medium text-gray-700 mb-2">Poll Question</label>
                    <input type="text" id="simple_poll_question" name="poll_simple_question" class="font-bold w-full border-2 p-4 mt-2   border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200 text-gray-800 mb-2" placeholder="Enter your poll question" value="<?=$pollQuestion?>">
                    <?php if($errors['pollQuestion']):?>
                        <div class="text-red-500 text-sm font-bold mt-2 text-center"><?=$errors['pollQuestion']?></div>
                    <?php endif;?>
                    
                    <label class="block text-sm font-medium text-gray-700 mb-2">Poll Options</label>
                    <div id="simplePollOptions">
                        <input type="text" name="poll_option[]" class="font-bold w-full border-2 p-4 mt-2   border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200 text-gray-800 mb-2" placeholder="Polling Option" >
                    </div>
                    <?php if($errors['pollOption']):?>
                        <div class="text-red-500 text-sm font-bold mt-2 text-center"><?=$errors['pollOption']?></div>
                        <?php endif;?>
                        <button type="button" onclick="addSimplePollOption()" class="mt-4 text-blue-600 hover:text-blue-800">+ Add Another Option</button>
                    </div>
                    
                    <div id="pollOptions" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Poll Question</label>
                        <input type="text" id="detail_poll_question" name="poll_username_question" class="font-bold w-full border-2 p-4 mt-2   border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200 text-gray-800 mb-2" placeholder="Enter your poll question" >
                        <?php if($errors['pollQuestion']):?>
                            <div class="text-red-500 text-sm font-bold mt-2 text-center"><?=$errors['pollQuestion']?></div>
                            <?php endif;?>
                            
                            <label class="block text-sm font-medium text-gray-700">Enter a Username for the Poll Option</label>
                            <input type="text" name="option_username[]" class="font-bold w-full border-2 p-4 mt-2   border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200 text-gray-800 mb-2" placeholder="Poller username" >
                            <?php if($errors['pollUsername']):?>
                                <div class="text-red-500 text-sm font-bold mt-2 text-center"><?=$errors['pollUsername']?></div>
                                <?php endif;?>
                </div>
    
                <button type="button" id="addOptionButton" onclick="addUsernameOption()" class="mt-4 text-blue-600 hover:text-blue-800 hidden">+ Add Another Option</button>
    
                <div class="flex justify-between mt-6">
                    <button type="button" onclick="showStep(2)" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200">Back</button>
                    <button type="button" class="create-poll bg-blue-800 text-white px-6 py-3 rounded-lg  hover:bg-blue-900 transition duration-200">Create Poll</button>
                </div>
    
           <div id="pollDetailsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center">
            <div class="bg-white rounded-lg w-11/12 sm:w-1/2 p-6">
                <h3 class="text-2xl font-semibold text-gray-700 mb-6">Poll Details</h3>
                
                <div  class="carousel-container">
                    <div class="carousel-step flex flex-col space-y-3" id="carouselstep1">
                        <div class="flex justify-between items-center flex-col">
                          <p class="font-bold text-blue-800">Title:</p> 
                          <p><span id="pollHeader" class="font-semibold"></span></p>
                        </div>
                        <div class="flex justify-between items-center flex-col">
                          <p class="font-bold text-blue-800">Description:</p> 
                          <p><span id="pollBody" class="font-semibold"></span></p>
                        </div>
                    </div>
                    <div class="carousel-step hidden" id="carouselstep2">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p><strong class="text-blue-800">Poll Method:</strong> <span id="pollMethod" class="block"></span></p>
                            </div>
                            <div>
                                <p><strong class="text-blue-800">Poll Type:</strong> <span id="pollType" class="block"></span></p>
                            </div>
                            <div>
                                <p><strong class="text-blue-800">Poll Payment:</strong> <span id="pollPayment" class="block"></span></p>
                            </div>
                            <div>
                                <p><strong class="text-blue-800">Poll Visibility:</strong> <span id="pollVisibility" class="block"></span></p>
                            </div>
                            <div>
                                <p><strong class="text-blue-800">Anonymous Poll:</strong> <span id="pollAnonymous" class="block"></span></p>
                            </div>
                            <div>
                                <p><strong class="text-blue-800">Poll Category:</strong> <span id="pollCategories" class="block"></span></p>
                            </div>
                            <div>
                                <p><strong class="text-blue-800">Poll Start Date:</strong> <span id="pollStartDate" class="block"></span></p>
                            </div>
                            <div>
                                <p><strong class="text-blue-800">Poll End Date:</strong> <span id="pollEndDate" class="block"></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-step hidden" id="carouselstep3">
                        <div class="flex justify-center mb-2 items-center flex-col">
                            <p class="font-bold text-blue-800">Poll Question</p>
                            <p><span id="pollQuestionSpan" class="font-semibold"></span></p>
                        </div>

                        <div class="flex justify-center items-center flex-col">
                            <p class="font-bold text-blue-800" id="Pollers">Poll Options</p>
                            <p><span id="pollQuestionSpan" class="font-semibold">
                            <ul id="pollOptionsCont" class="list-disc"></ul>
                            </span></p>
                        </div>
                    </div>
                </div>
    
                <div class="flex justify-between mt-4">
                    <button type="button" onclick="navigateCarousel('prev')" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition duration-200">Previous</button>
                    <button type="button" onclick="navigateCarousel('next')" class="bg-blue-800 text-white px-6 py-2 rounded-lg hover:bg-blue-900 transition duration-200 ml-4">Next</button>
                </div>
                
                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closePollDetailsModal()" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition duration-200">Close</button>
                    <input type="submit" class="bg-blue-800 text-white px-6 py-2 rounded-lg hover:bg-blue-900 transition duration-200 ml-4" value="Submit Poll" name="submit_poll">
                </div>
            </div>
          </div>
            </div>
        </form>
    </div>
    <div class="w-full">
        <!-- Wallet design  -->
        <div class="max-w-sm mx-auto p-6 flex-col flex space-y-3 bg-blue-800 rounded-xl shadow-lg">
            <h2 class="text-xl font-semibold text-white">Your Wallet</h2>
            <p class="text-xl text-white ">Balance: $<span id="balance">100.00</span></p>

            
             <a href="wallet.php" class="text-white underline">Transaction History</a>

            <div class=" flex space-x-4">
                <button id="add-funds" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none">Add Funds</button>
                <button id="withdraw" class="w-full py-2 px-4 bg-white border-2 border-blue-600 text-blue-600 rounded-md hover:bg-blue-600 hover:text-white focus:outline-none">Withdraw</button>
            </div>
        </div>
    </div>
</div>
<script src="js/createpoll.js"></script>

