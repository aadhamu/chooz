<?php


if(!isset($_SESSION['vote_key'])){
    header("location: dashboard.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['selected_poll'])) {
        $selectedPoll = mysqli_real_escape_string($conn, sanitize($_POST['selected_poll']));

        $_SESSION['vote_key'] = $selectedPoll;

        header('Location: vote.php');
        exit;
    } 
}

$voteKey=$_SESSION['vote_key'];
$userId = $user['id']; 

$query = "
    SELECT p.*, uk.vote_key
    FROM user_vote_key uk
    JOIN polls p ON uk.vote_key = p.poll_key
    WHERE uk.user_id = ? AND p.poll_status = 'ongoing'
";

$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
}
$private='private';
$voteQuery="SELECT * FROM polls p 
JOIN poll_options ON poll_id = p.id
WHERE poll_key= ? AND poll_visibility= ?";
$voteStmt = $conn->prepare($voteQuery);
if ($voteStmt) {
    $voteStmt->bind_param("ss", $voteKey,$private );
    $voteStmt->execute();
    $voteResult = $voteStmt->get_result();
    $voteRows = $voteResult->fetch_all(MYSQLI_ASSOC);
}

$allUserDetails = [];
$allOptionDetails = [];

foreach ($voteRows as $poll) {
    if($poll['is_username'] === 1){
        $optionTexts = explode(',', $poll['option_text']);

        foreach ($optionTexts as $username) {
            $username = trim($username);

            $pollerDetailsQuery = "SELECT * FROM nominees_details nd
            JOIN users s ON s.username=nd.nominee_username 
            JOIN polls p ON p.id = nd.poll_id
            WHERE nd.nominee_username = ?";
            $pollerStmt = $conn->prepare($pollerDetailsQuery);

            if ($pollerStmt) {
                $pollerStmt->bind_param("s", $username);
                $pollerStmt->execute();
                $pollerResult = $pollerStmt->get_result();

                while ($userDetails = $pollerResult->fetch_assoc()) {
                    $allUserDetails[] = $userDetails;
                }
            }
        }
    }else{
        $allOptionDetails=$voteRows;
    }
}

if(isset($_POST['participate'])){
   unset($_SESSION['vote_key']);
   header('location: validatekey.php');
   exit();
}


// VOTE
$voteError=$optionalVoteError=$vote=$pollingType=$pollingMethod=$voteId=$error=$optionText=$isUsername=$success=$options="";
if(isset($_POST['vote'])){

    if (isset($_POST['single_vote'])) {
        $vote = mysqli_real_escape_string($conn, sanitize($_POST['single_vote']));
    } elseif (isset($_POST['multiple_vote'])) {
        $vote = mysqli_real_escape_string($conn, sanitize($_POST['multiple_vote']));
    }
    $voteId=mysqli_real_escape_string($conn, sanitize( $_POST['poll_id']));
    $isUsername=mysqli_real_escape_string($conn, sanitize( $_POST['username']));
    $optionText=mysqli_real_escape_string($conn, sanitize( $_POST['option_text']));
    $pollingType=mysqli_real_escape_string($conn, sanitize( $_POST['polling_type']));
    $pollingMethod=mysqli_real_escape_string($conn, sanitize($_POST['polling_method']));

    if(empty($vote)){
        $voteError="This field is required";
    }elseif(!is_numeric($vote)){
        $voteError="Input a number";
      }

      if (!$voteError) {

        if ($pollingMethod === 'single_choice') {

            $singleChoiceQuery = "SELECT * FROM votes WHERE poll_id='$voteId' AND user_id='$userId'";
            $singleChoice = mysqli_query($conn, $singleChoiceQuery);
            $singleResult = mysqli_num_rows($singleChoice);
    
            if ($singleResult > 0) {
                if ($pollingType === 'single_vote') {
                    $singleVoteQuery = "SELECT * FROM votes WHERE username='$isUsername' AND poll_id='$voteId' AND user_id='$userId'";
                    $singleVote = mysqli_query($conn, $singleVoteQuery);
                    $singleVoteResult = mysqli_num_rows($singleVote);
    
                    if ($singleVoteResult > 0) {
                        $error = "You've voted already.";
                    }else{
                        $error="You've voted for a nominee already";
                    }
                } elseif ($pollingType === 'multiple_vote') {
                    
                    $multipleVoteQuery = "SELECT * FROM votes WHERE username='$isUsername' AND poll_id='$voteId' AND user_id='$userId'";
                    $multipleVote = mysqli_query($conn, $multipleVoteQuery);
                    $multipleVoteResult = mysqli_num_rows($multipleVote);

                    if ($multipleVoteResult > 0) {

                        $insertSQL = "INSERT INTO votes (poll_id, user_id, username,num_vote) VALUES (?, ?,?,?)";
                        if ($insertStmt = $conn->prepare($insertSQL)) {
                            $insertStmt->bind_param("sssi", $voteId, $userId,$isUsername, $vote);
                            if ($insertStmt->execute()) {
                                $success = "You've voted successfully.";
                            } else {
                                $error = "Unable to vote.";
                            }
                    } 
                  
                }else{
                    $error="Vote for the nominee you voted before";
                }
                }
            } else {
                // First vote by the user for this poll
                $insertSQL = "INSERT INTO votes (poll_id, user_id, username, num_vote) VALUES (?, ?, ?,?)";
                if ($insertStmt = $conn->prepare($insertSQL)) {
                    $insertStmt->bind_param("sssi", $voteId, $userId, $isUsername, $vote);
                    if ($insertStmt->execute()) {
                        $success = "You've voted successfully.";
                    } else {
                        $error = "Unable to vote.";
                    }
                }
            }
            }elseif($pollingMethod === 'multiple_choice') {
                $multipleChoiceQuery = "SELECT * FROM votes WHERE poll_id='$voteId' AND user_id='$userId' AND username='$isUsername'";
                $multipleChoice = mysqli_query($conn, $multipleChoiceQuery);
                $multipleResult = mysqli_num_rows($multipleChoice);
        
                if ($multipleResult > 0) {
                    if ($pollingType === 'single_vote') {
                        $singleVoteQuery = "SELECT * FROM votes WHERE username='$isUsername' AND poll_id='$voteId' AND user_id='$userId'";
                        $singleVote = mysqli_query($conn, $singleVoteQuery);
                        $singleVoteResult = mysqli_num_rows($singleVote);
        
                        if ($singleVoteResult > 0) {
                            $error = "You've voted for this nominee already.";
                        }
                    } elseif ($pollingType === 'multiple_vote') {
                    
                        $multipleVoteQuery = "SELECT * FROM votes WHERE username='$isUsername' AND poll_id='$voteId' AND user_id='$userId'";
                        $multipleVote = mysqli_query($conn, $multipleVoteQuery);
                        $multipleVoteResult = mysqli_num_rows($multipleVote);
    
                        if ($multipleVoteResult > 0) {
    
                            $insertSQL = "INSERT INTO votes (poll_id, user_id, username, num_vote) VALUES (?, ?, ?,?)";
                            if ($insertStmt = $conn->prepare($insertSQL)) {
                                $insertStmt->bind_param("sssi", $voteId, $userId, $isUsername, $vote);
                                if ($insertStmt->execute()) {
                                    $success = "You've voted successfully.";
                                } else {
                                    $error = "Unable to vote.";
                                }
                        } 
                      
                    }
                    }
                }else {
                    // First vote by the user for this poll
                    $insertSQL = "INSERT INTO votes (poll_id, user_id, username, num_vote) VALUES (?, ?, ?,?)";
                    if ($insertStmt = $conn->prepare($insertSQL)) {
                        $insertStmt->bind_param("sssi", $voteId, $userId, $isUsername, $vote);
                        if ($insertStmt->execute()) {
                            $success = "You've voted successfully.";
                        } else {
                            $error = "Unable to vote.";
                        }
                    }
                }
            }
        }
}
if(isset($_POST['optionalpoll'])){
    if (isset($_POST['single_vote'])) {
        $vote = mysqli_real_escape_string($conn, sanitize($_POST['single_vote']));
    } elseif (isset($_POST['multiple_vote'])) {
        $vote = mysqli_real_escape_string($conn, sanitize($_POST['multiple_vote']));
    }
    $voteId=mysqli_real_escape_string($conn, sanitize( $_POST['poll_id']));
    $pollingType=mysqli_real_escape_string($conn, sanitize( $_POST['polling_type']));
    $pollingMethod=mysqli_real_escape_string($conn, sanitize($_POST['polling_method']));

    if(empty($vote)){
        $optionalVoteError="This field is required";
    }elseif(!is_numeric($vote)){
        $optionalVoteError="Input a number";
    }
    
    if(empty($_POST['option'])){
        $optionalVoteError="Select an option";
    }else{
          $options=mysqli_real_escape_string($conn, sanitize( $_POST['option']));
      }

      if (!$optionalVoteError) {

        if ($pollingMethod === 'single_choice') {

            $singleChoiceQuery = "SELECT * FROM votes WHERE poll_id='$voteId' AND user_id='$userId'";
            $singleChoice = mysqli_query($conn, $singleChoiceQuery);
            $singleResult = mysqli_num_rows($singleChoice);
    
            if ($singleResult > 0) {
                if ($pollingType === 'single_vote') {
                    $singleVoteQuery = "SELECT * FROM votes WHERE option='$options' AND poll_id='$voteId' AND user_id='$userId'";
                    $singleVote = mysqli_query($conn, $singleVoteQuery);
                    $singleVoteResult = mysqli_num_rows($singleVote);
    
                    if ($singleVoteResult > 0) {
                        $error = "You've voted already.";
                    }else{
                        $error="You can only vote once for an option";
                    }
                } elseif ($pollingType === 'multiple_vote') {
                    
                    $multipleVoteQuery = "SELECT * FROM votes WHERE option='$options' AND poll_id='$voteId' AND user_id='$userId'";
                    $multipleVote = mysqli_query($conn, $multipleVoteQuery);
                    $multipleVoteResult = mysqli_num_rows($multipleVote);

                    if ($multipleVoteResult > 0) {

                        $insertSQL = "INSERT INTO votes (poll_id, user_id, option, num_vote) VALUES (?, ?, ?,?)";
                        if ($insertStmt = $conn->prepare($insertSQL)) {
                            $insertStmt->bind_param("sssi", $voteId, $userId, $options,$vote);
                            if ($insertStmt->execute()) {
                                $success = "You've voted successfully.";
                            } else {
                                $error = "Unable to vote.";
                            }
                    } 
                  
                }else{
                    $error="Vote for the nominee you voted before";
                }
                }
            } else {
                // First vote by the user for this poll
                $insertSQL = "INSERT INTO votes (poll_id, user_id, option, num_vote) VALUES (?, ?, ?, ?)";
                if ($insertStmt = $conn->prepare($insertSQL)) {
                    $insertStmt->bind_param("sssi", $voteId, $userId, $options, $vote);
                    if ($insertStmt->execute()) {
                        $success = "You've voted successfully.";
                    } else {
                        $error = "Unable to vote.";
                    }
                }
            }
            }elseif($pollingMethod === 'multiple_choice') {
                $multipleChoiceQuery = "SELECT * FROM votes WHERE poll_id='$voteId' AND user_id='$userId' AND option='$options'";
                $multipleChoice = mysqli_query($conn, $multipleChoiceQuery);
                $multipleResult = mysqli_num_rows($multipleChoice);
        
                if ($multipleResult > 0) {
                    if ($pollingType === 'single_vote') {
                        $singleVoteQuery = "SELECT * FROM votes WHERE  option='$options' AND poll_id='$voteId' AND user_id='$userId'";
                        $singleVote = mysqli_query($conn, $singleVoteQuery);
                        $singleVoteResult = mysqli_num_rows($singleVote);
        
                        if ($singleVoteResult > 0) {
                            $error = "You've voted for this nominee already.";
                        }
                    } elseif ($pollingType === 'multiple_vote') {
                    
                        $multipleVoteQuery = "SELECT * FROM votes WHERE option='$options' AND poll_id='$voteId' AND user_id='$userId'";
                        $multipleVote = mysqli_query($conn, $multipleVoteQuery);
                        $multipleVoteResult = mysqli_num_rows($multipleVote);
    
                        if ($multipleVoteResult > 0) {
    
                            $insertSQL = "INSERT INTO votes (poll_id, user_id, option, num_vote) VALUES (?, ?, ?,?)";
                            if ($insertStmt = $conn->prepare($insertSQL)) {
                                $insertStmt->bind_param("sssi", $voteId, $userId, $options, $vote);
                                if ($insertStmt->execute()) {
                                    $success = "You've voted successfully.";
                                } else {
                                    $error = "Unable to vote.";
                                }
                        } 
                      
                    }
                    }
                }else {
                    // First vote by the user for this poll
                    $insertSQL = "INSERT INTO votes (poll_id, user_id, option, num_vote) VALUES (?, ?, ?,?)";
                    if ($insertStmt = $conn->prepare($insertSQL)) {
                        $insertStmt->bind_param("sssi", $voteId, $userId, $options, $vote);
                        if ($insertStmt->execute()) {
                            $success = "You've voted successfully.";
                        } else {
                            $error = "Unable to vote.";
                        }
                    }
                }
            }
        
    }
}
?>
 <?php if($voteError):?>
    <div class="text-red-500 font-bold text-center"><?=$voteError?></div>
<?php endif;?>
 <?php if($error):?>
    <div class="text-red-500 font-bold text-center"><?=$error?></div>
<?php endif;?>
 <?php if($success):?>
    <div class="text-green-500 font-bold text-center"><?=$success?></div>
<?php endif;?>
<?php
foreach ($voteRows as $poll) :?>

<section class="ml-10">
    <div class="flex justify-between items-center mb-7">
        <h1 class="text-blue-800 font-bold text-2xl">YOU MAY NOW CAST YOUR VOTES!</h1>
        <button id="rules-btn" class="text-white py-1 px-3 border-blue-800 bg-blue-800 border-2 rounded-3xl">Rules</button>
        <form action="vote.php" method="POST" class="w-5/12 border-4" id="pollForm">
     <select name="selected_poll" class="w-full p-2 border-2 rounded-lg border-blue-800" 
        onchange="document.getElementById('pollForm').submit();">
        <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $option): ?>
                <option value="<?= htmlspecialchars($option['poll_key']) ?>">
                    <?= htmlspecialchars(ucwords($option['poll_title'])) ?>
                </option>
            <?php endforeach; ?>
        <?php else: ?>
            <option value="" disabled>No ongoing polls available</option>
        <?php endif; ?>
    </select>
</form>

    </div>
    <div>
        <div class="flex flex-col  justify-center items-center">
            <h2 class="font-bold text-2xl text-blue-800"><?= ucwords($poll['poll_question'])?></h2>
            <p class="text-gray-400 font-semibold">
                 <?php
                   if($poll['polling_method'] === 'single_choice'){
                     echo 'You can vote for one candidate';
                   }elseif ($poll['polling_method'] === 'multiple_choice') {
                     echo 'You can vote for one or more candidate';
                    }
                    ?>
            </p>
        </div>
        <?php if($poll['is_username'] === 1):?>
        <div class="grid grid-cols-3 gap-6 mt-5" id="candidates">
            </div>
        <?php else:?>
            <div class="w-4/12 mx-auto p-3 space-y-3 flex flex-col justify-center items-center bg-gradient-to-b from-blue-200 to-white rounded-lg">
           <img src="poll_image/<?= $poll['poll_image'] ?>" alt="${candidate.name}" class="rounded-full w-40 h-40 object-cover">
            <h2 class="font-bold text-xl text-blue-800"><?= $poll['poll_question']?></h2>
            <p class="font-semibold text-sm text-gray-400"><?= $poll['poll_status']?></p>
            <div class="flex space-x-3 justify-between items-center w-full">
                <button class="rounded-3xl text-white w-6/12 bg-blue-800 p-3 triggerOptionalModal" >Vote</button>
                <button id="view-option-details" class="  rounded-3xl text-blue-800 w-6/12 bg-white border-2 border-blue-800 p-3">View Details</button>
            </div>
        </div>
        <?php endif;?>
            <?php endforeach;?>
  </div>
  <div id="rules-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white rounded-lg w-1/2 p-5 space-y-5">
      <h2 class="text-blue-800 text-xl font-bold">Voting Rules</h2>
      <ul class="list-disc pl-5 space-y-2 text-gray-600">
        <?php 
          foreach($rows as $keys):
        ?>
        <li><?= $keys['polling_method'] === 'multiple_choice' ? 'You can vote more than once' : 'You can only vote once.'?></li>
        <li><?= $keys['anonymous_poll'] === '0' ? 'Votes are not anonymous': 'Votes are anonymous'?></li>
        <li>Vote closes by <?= $keys['end_date']?></li>
        <li><?php 
        if($keys['voter_pay_amount'] != ''){
            echo 'Voters pay '.$keys['voter_pay_amount'].' to vote';
        }?>
    </li>
    <li><?= $keys['polling_type'] === 'multiple_vote' ? 'Votes can cast multiple votes on a nominee': 'Votes can only cast a single vote on a nominee'?></li>
        <?php endforeach;?>
        
    </ul>
    <button id="close-modal" class="text-white bg-blue-800 px-4 py-2 rounded-lg">Close</button>
</div>
</div>

<div id="candidate-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center mx-auto">
    <div class="bg-white rounded-lg w-7/12 p-5 space-y-5">
        <div class="flex items-center space-x-5">
            <img id="candidate-image" src="" alt="" class="rounded-full w-44 h-44 object-cover">
            <div>
                <p class="text-blue-600">Vote</p>
                <h2 id="candidate-name" class="text-2xl text-blue-800 font-bold"></h2>
                <div class="mb-3">
                    <span class="text-blue-600">for</span>
                    <span id="candidate-position" class="text-xl text-blue-800 font-semibold"></span>
                </div>
                <div class="flex items-center space-x-4">
                    <i class="fas fa-chart-bar text-blue-800 text-xl"></i>
                    <span id="candidate-field" class="text-blue-800 font-semibold"></span>
                </div>
                <div class="flex items-center space-x-4 mt-2">
                    <i class="fas fa-calendar text-blue-800 text-xl"></i>
                    <span id="poll-enddate" class="text-blue-800 font-semibold"></span>
                </div>
                <div class="flex items-center space-x-4 mt-2" id="linkCont"></div>
            </div>
        </div>
        <div>
            <h1 class="text-xl text-blue-800 font-bold mb-4">Bio</h1>
                <span id="candidate-bio" class="list-decimal text-blue-800 font-semibold"></span>
        </div>
        <div>
            <h1 class="text-xl text-blue-800 font-bold mb-4">Nominee Statement</h1>
                <span id="candidate-platform" class="list-decimal text-blue-800 font-semibold"></span>
        </div>
        <div>
            <h1 class="text-xl text-blue-800 font-bold mb-4">Nominee Qualification</h1>
                <span id="candidate-qualification" class="list-decimal text-blue-800 font-semibold"></span>
        </div>
        <div class="flex justify-center">
            <button    class="rounded-3xl text-white text-xl w-3/12 bg-blue-800 p-3 triggerVoteModal">Vote</button>
        </div>
        <button id="close-candidate-modal" class="text-white bg-blue-800 px-4 py-2 rounded-lg">Close</button>
    </div>
</div>
<div id="option-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center mx-auto">
    <div class="bg-white rounded-lg w-7/12 p-5 space-y-5">
            <img id="option-image" src="" alt="" class="rounded-full w-44 h-44 object-cover mx-auto">
           
        <div>
            <h1 class="text-xl text-blue-800 font-bold mb-4">Poll Title</h1>
                <span id="option-title" class="list-decimal text-blue-800 font-semibold"></span>
        </div>
        <div>
            <h1 class="text-xl text-blue-800 font-bold mb-4">Poll question</h1>
                <span id="option-question" class="list-decimal text-blue-800 font-semibold"></span>
        </div>
        <div>
            <h1 class="text-xl text-blue-800 font-bold mb-4">Poll Options</h1>
                <span id="options" class="list-decimal text-blue-800 font-semibold">
                    <ul id="listContainer" class="list-disc p-4"></ul>
                </span>
        </div>
        <div class="flex justify-center">
            <button    class="rounded-3xl text-white text-xl w-3/12 bg-blue-800 p-3 triggerOptionalModal">Vote</button>
        </div>
        <button id="close-option-modal" class="text-white bg-blue-800 px-4 py-2 rounded-lg">Close</button>
    </div>
</div>
    <form action="vote.php" method="post" class="flex justify-center items-center mt-3">
        <input type="submit" value="Participate in Another Vote" name="participate" class="rounded-3xl text-white  bg-blue-800 p-3">
    </form>
    
    
     
</div>

   <div id="optionModalPoll" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-96">
    <form action="vote.php" method="post" class="flex flex-col justify-center mt-3">    
        <h2 class="text-blue-800 font-bold text-lg mb-4">Cast Your Vote</h2>
        <label for="voteInput" class="block text-blue-800 mb-2">Your Vote:</label>
        <?php 
        if (!empty($voteRows)) {
            foreach ($voteRows as $row) {
                $optionTexts = explode(',', $row['option_text']);

                foreach($optionTexts as $option){?>
                    <div>
                        <label for="">
                            <input type="radio" name="option" id="" value="<?=$option?>" class="mr-2"><?=$option?>
                        </label>
                    </div>
                <?php  }
                    
                if ($row['polling_type'] === 'multiple_vote') {?>
                     <input type="hidden" name="poll_id" value='<?= $row['poll_id']?>'>
                    <input type="hidden" name="polling_type" value='<?= $row['polling_type']?>'>
                    <input type="hidden" name="polling_method" value='<?= $row['polling_method']?>'>
                    
                           <input 
                           name="multiple_vote"
                            type="text" 
                            id="voteInput" 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none ">
               <?php } else {?>
                        <input 
                            name="single_vote" 
                            type="text" 
                            id="" 
                            value="1" 
                            readonly 
                            class="w-full p-2 border border-gray-300 focus:outline-none rounded-lg bg-gray-200 text-gray-600">
                            <input type="hidden" name="poll_id" value='<?= $row['poll_id']?>'>
                    <input type="hidden" name="polling_type" value='<?= $row['polling_type']?>'>
                    <input type="hidden" name="polling_method" value='<?= $row['polling_method']?>'>
                <?php }
            }
        }
        ?>

        <?php if($optionalVoteError):?>
            <div class="text-red-500 font-bold text-center"><?=$optionalVoteError?></div>
        <?php endif;?>
        <div class="mt-4 flex justify-end">
            <button 
                id="closeOptionalModal" 
                class="px-4 py-2 bg-gray-300 rounded-lg text-gray-800 hover:bg-gray-400">
                Close
            </button>
            <button 
                name="optionalpoll"
                type="submit"
                class="ml-2 px-4 py-2 bg-blue-800 rounded-lg text-white hover:bg-blue-900">
                Submit
            </button>
        </div>
    </form>
    </div>
</div>


<div id="voteModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
<form action="vote.php" method="post" class="flex justify-center items-center mt-3">
        <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-blue-800 font-bold text-lg mb-4">Cast Your Vote</h2>
        <label for="voteInput" class="block text-blue-800 mb-2">Your Vote:</label>
        
        <?php 
        if (!empty($voteRows)) {
            foreach ($voteRows as $row) {
                if ($row['polling_type'] === 'multiple_vote') {
                    ?>
                 <h1 class="text-blue-800 font-bold mb-3">You can select more than one options</h1>
                 <form class="flex flex-wrap items-center space-x-3" method="POST" action="vote.php">
                 <input type="hidden" name="username" value='' class="nominee-username">
                 <input type="hidden" name="option_text" value='<?= $row['option_text']?>'>
                    <input type="hidden" name="poll_id" value='<?= $row['poll_id']?>'>
                    <input type="hidden" name="polling_type" value='<?= $row['polling_type']?>'>
                    <input type="hidden" name="polling_method" value='<?= $row['polling_method']?>'>
                     <input 
                     name="multiple_vote"
                     type="text" 
                     id="voteInput" 
                     class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none ">
                     <?php } else {?>
      
                        <h1 class="text-blue-800 font-bold mb-3">You can vote for only one nominee</h1>
                        <input type="hidden" name="username" value='' class="nominee-username">
                        <input type="hidden" name="option_text" value='<?= $row['option_text']?>'>
                        <input type="hidden" name="poll_id" value='<?= $row['poll_id']?>'>
                        <input type="hidden" name="polling_type" value='<?= $row['polling_type']?>'>
                        <input type="hidden" name="polling_method" value='<?= $row['polling_method']?>'>
                    <input 
                            type="text" 
                            name="single_vote" 
                            id="" 
                            value="1" 
                            readonly 
                            class="w-full p-2 border border-gray-300 focus:outline-none rounded-lg bg-gray-200 text-gray-600">
                <?php }
            }
        }
        ?>
         <?php if($voteError):?>
            <div class="text-red-500 font-bold text-center"><?=$voteError?></div>
        <?php endif;?>

    <div class="mt-4 flex justify-end">
        <button 
        id="closeModal" 
        class="px-4 py-2 bg-gray-300 rounded-lg text-gray-800 hover:bg-gray-400">
        Close
        </button>
        <button 
        type="submit"
        name="vote"
        class="ml-2 px-4 py-2 bg-blue-800 rounded-lg text-white hover:bg-blue-900">
        Submit
        </button>
    </div>
</form>
</div>
</section>
<script>
    let rulesModal=document.getElementById('rules-modal');

    const optionModal = document.getElementById("option-modal");
    const optionModalPoll = document.getElementById("optionModalPoll");
    const optionImage = document.getElementById("option-image");
    const optionTitle = document.getElementById("option-title");
    const optionQuestion = document.getElementById("option-question");
    const option = document.getElementById("options");

    const candidateModal = document.getElementById("candidate-modal");
    const candidateName = document.getElementById("candidate-name");
    const candidateImage = document.getElementById("candidate-image");
    const candidateField = document.getElementById("candidate-field");
    const enddate = document.getElementById("poll-enddate");
    const candidatePosition = document.getElementById("candidate-position");
    const candidateQualification = document.getElementById("candidate-qualification");
    const platformList = document.getElementById("candidate-platform");
    const bioList = document.getElementById("candidate-bio");

    const linkedIn = document.getElementById("linkedIn");
    const instagram = document.getElementById("Instagram");
    const Twitter = document.getElementById("Twitter");
    const candidates = <?= json_encode($allUserDetails, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const pollOption = <?= json_encode($allOptionDetails, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    
function removeRulesModal(){
 rulesModal.classList.add('hidden')
}
function removeCandidateModal(){
    candidateModal.classList.add('hidden')
}
function removeOptionalModal(){
    optionModal.classList.add('hidden')
}

function showRulesModal(){
    rulesModal.classList.remove('hidden')
}
function showCandidateModal(){
    candidateModal.classList.remove('hidden')
}
function showOptionalModal(){
    optionModal.classList.remove('hidden')
}
function showOptionalModalPoll(){
    optionModalPoll.classList.remove('hidden')
}
function closeOptionalModalPoll(){
    optionModalPoll.classList.add('hidden')
}

document.getElementById('rules-btn').addEventListener('click',showRulesModal);
document.getElementById('close-modal').addEventListener('click', removeRulesModal);
document.getElementById('rules-modal').addEventListener('click', removeRulesModal)


const candidatesContainer = document.getElementById("candidates");

candidates.forEach((candidate, index) => {
    
    candidatesContainer.innerHTML += `
    <div class="w-full p-3 space-y-3 flex flex-col justify-center items-center bg-gradient-to-b from-blue-200 to-white rounded-lg">
        <img src="${candidate.nominee_image ? `nominees/${candidate.nominee_image}` : 'assets/profile.png'}" alt="${candidate.name}" class="rounded-full w-40 h-40 object-cover">
        <h2 class="font-bold text-xl text-blue-800">${candidate.firstname}</h2>
        <p class="font-semibold text-sm text-gray-400">${candidate.organization ?? ''}</p>
        <p class='p-text'>${candidate.nominee_username}</p>
        <div class="flex space-x-3 justify-between items-center w-full">
            <button class="rounded-3xl text-white w-6/12 bg-blue-800 p-3 triggerVoteModal" data-username="${candidate.nominee_username}">Vote</button>
            <button data-index="${index}" class="view-details rounded-3xl text-blue-800 w-6/12 bg-white border-2 border-blue-800 p-3">View Details</button>
        </div>
    </div>
`;
});



document.getElementById("close-candidate-modal").addEventListener("click", removeCandidateModal);

if(candidatesContainer){
    
    candidatesContainer.addEventListener("click", (e) => {
        if (e.target.classList.contains("view-details")) {
            const index = e.target.dataset.index;
            const candidate = candidates[index];
            
            function capitalizeWords(str) {
            return str
                .split(" ")
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(" "); 
            }
            
            candidateName.textContent = candidate.firstname;
            candidateImage.src = `nominees/${candidate.nominee_image}`;
            candidateField.textContent = capitalizeWords(candidate.poll_status);
            enddate.textContent = `${candidate.end_date.split(" ")[0]}`;
            candidatePosition.textContent = candidate.poll_title;
            bioList.textContent = candidate.nominee_bio;
            platformList.textContent = candidate.nominee_statement;
            candidateQualification.textContent=candidate.nominee_qualifications
            
            const linkContainer = document.getElementById('linkCont');
            
            function checkAndAppendLink(platform, url, iconClass) {
            if (url) { 
                const anchorTag = document.createElement('a');
            anchorTag.href = url;
            anchorTag.classList.add('text-blue-800');
            anchorTag.target = '_blank'; 
            
            const iconTag = document.createElement('i');
            iconTag.classList.add(iconClass);
            
            anchorTag.appendChild(iconTag);
            linkContainer.appendChild(anchorTag); 
        }
    }
    
    checkAndAppendLink('linkedin', candidate.linkedin, 'fab fa-linkedin');
    checkAndAppendLink('twitter', candidate.twitter, 'fab fa-twitter');
    checkAndAppendLink('instagram', candidate.instagram, 'fab fa-instagram');
    
    
            showCandidateModal();
        }
    });

}
    const triggerButtons = document.querySelectorAll('.triggerVoteModal');
    const voteModal = document.getElementById('voteModal');
    const closeModal = document.getElementById('closeModal');
    
    triggerButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const username = e.currentTarget.getAttribute("data-username");
            const nomineeUsernameInput = document.querySelector(".nominee-username");
            
            if (nomineeUsernameInput) {
                nomineeUsernameInput.value = username;
            }
            voteModal.classList.remove('hidden');
        });
    });
    
    closeModal.addEventListener('click', () => {
        voteModal.classList.add('hidden');
    });
    
    window.addEventListener('click', (event) => {
        if (event.target === voteModal) {
            voteModal.classList.add('hidden');
        }
    });

    const triggerOptionButtons = document.querySelectorAll('.triggerOptionalModal');
    // const voteModal = document.getElementById('voteModal');
    const closeOptionalModal = document.getElementById('closeOptionalModal');
    
    triggerOptionButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            // const username = e.currentTarget.getAttribute("data-username");
            // const nomineeUsernameInput = document.querySelector(".nominee-username");
            
            // if (nomineeUsernameInput) {
            //     nomineeUsernameInput.value = username;
            // }
            showOptionalModalPoll()
        });
    });

    closeOptionalModal.addEventListener("click", function (){
        closeOptionalModalPoll()
    })

    let optionButton=document.getElementById('view-option-details');
    if(optionButton){
        optionButton.addEventListener("click", function (){
            
            
            pollOption.forEach(poll => {
                optionImage.src=`poll_image/${poll.poll_image}`
                optionTitle.textContent=poll.poll_title
                optionQuestion.textContent=poll.poll_question
                
                const options = poll.option_text.split(",").map(option => option.trim());
                
                const listContainer = document.getElementById("listContainer");
                options.forEach(option => {
                    const li = document.createElement("li");
                    li.textContent = option; 
                listContainer.appendChild(li);
            });
        });
            showOptionalModal()
        })
    }
    document.getElementById("close-option-modal").addEventListener("click", removeOptionalModal);
</script>