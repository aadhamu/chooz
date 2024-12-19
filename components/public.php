<?php
$userId = $user['id']; 
$publicVote = "SELECT * FROM polls p 
JOIN poll_options po ON p.id = po.poll_id
WHERE p.poll_visibility='public' AND p.poll_status='ongoing' ORDER BY p.created_at DESC";
$publicVoteQuery = mysqli_query($conn, $publicVote);

$publicVoteArrays = [];
$optionalVoteError=$error=$success='';
while ($row = mysqli_fetch_assoc($publicVoteQuery)) {
    $publicVoteArrays[] = $row; 
}
foreach ($publicVoteArrays as $key ) {
    if(!$key['is_username']){
        $allOptionDetails=[$key];
    }
}
  if(isset($_POST['participate'])){
     $voteId=mysqli_real_escape_string($conn,$_POST['voteid']);
     $_SESSION['public_vote_key']=$voteId;
     header("location: publicvote.php");
     exit();
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

<?php if($optionalVoteError):?>
    <div class="text-red-500 font-bold text-center"><?=$optionalVoteError?></div>
<?php endif;?>
 <?php if($error):?>
    <div class="text-red-500 font-bold text-center"><?=$error?></div>
<?php endif;?>
 <?php if($success):?>
    <div class="text-green-500 font-bold text-center"><?=$success?></div>
<?php endif;?>
<section>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php if ($publicVoteArrays[0] != ''): ?>
            <?php foreach ($publicVoteArrays as $index => $key):?>
                <div class="w-full p-3 space-y-2 flex flex-col justify-center items-center bg-gradient-to-b from-blue-200 to-white rounded-lg">
                    <img src="<?php echo $key['poll_image'] ? 'poll_image/' . $key['poll_image'] : 'assets/profile.png'; ?>" 
                        alt="poll_image" 
                        class="rounded-full w-40 h-40 object-cover">
                    <h2 class="font-bold text-xl text-gray-600 text-center"><span class="block font-bold text-blue-800">Title: </span><?= ucwords($key['poll_title'])?></h2>
                    <p class="font-bold text-md text-gray-600 text-center"><span class="block font-bold text-blue-800">Description: </span><?=$key['poll_description']?></p>
                    <p class="font-bold text-md text-gray-600 text-center"><span class="block font-bold text-blue-800">Status: </span> <?=$key['poll_status']?></p>
                    <div class="flex space-x-3 justify-between items-center w-full">
                        <form action="public.php" method="post" class="w-6/12">
                            
                            <input type="hidden" name="voteid" value="<?=$key['poll_id']?>">
                            <button 
                                class="rounded-3xl text-white w-full bg-blue-800 p-3 <?= $key['is_username'] ? '' : 'view-option-details' ?>" 
                                type="<?= $key['is_username'] ? 'submit' : 'button' ?>" 
                                name="<?= $key['is_username'] ? 'participate' : 'Vote' ?>">

                                <?php echo $key['is_username'] ? 'Participate' : 'Vote'?>  
                            </button>
                        </form>
                        <button 
                            class="view-details rounded-3xl text-blue-800 w-6/12 bg-white border-2 border-blue-800 p-3" 
                            onclick="showDetails(<?= htmlspecialchars(json_encode($key)) ?>)">
                            View Details
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full">
                <p class="bg-blue-500 text-white text-center p-4 rounded-lg">No public votes available</p>
            </div>
        <?php endif; ?>
    </div>

    <div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white  w-3/4 lg:w-1/2 p-6 rounded-lg space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Poll Details</h2>
                <button class="text-gray-500 text-xl" onclick="closeModal()">×</button>
            </div>
            <div id="modalContent" class="space-y-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"">
            
            </div>
            <button class="w-full bg-blue-800 text-white py-2 rounded-lg" onclick="closeModal()">Close</button>
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
        <button id="closeOptionalModal" class="text-white bg-blue-800 px-4 py-2 rounded-lg">Close</button>
    </div>
</div>


<div id="optionModalPoll" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-96">
    <form action="public.php" method="post" class="flex flex-col justify-center mt-3">    
        <h2 class="text-blue-800 font-bold text-lg mb-4">Cast Your Vote</h2>
        <?php 
if (!empty($allOptionDetails)) {
    foreach ($allOptionDetails as $row) {
        $options = explode(',', $row['option_text']); 
        foreach ($options as $option): ?>
            <label class="flex items-center space-x-2">
                <input type="radio" name="option" value="<?= htmlspecialchars($option) ?>"> 
                <span><?= htmlspecialchars($option) ?></span>
            </label>
            <?php endforeach; 
        if ($row['polling_type'] === 'multiple_vote') {
            ?>
            <h1 class="text-blue-800 font-bold mb-3">You can select more than option</h1>
             <input type="hidden" name="poll_id" value='<?= $row['poll_id']?>'>
                    <input type="hidden" name="polling_type" value='<?= $row['polling_type']?>'>
                    <input type="hidden" name="polling_method" value='<?= $row['polling_method']?>'>
                    
                           <input 
                           name="multiple_vote"
                            type="text" 
                            id="voteInput" 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none ">
            
        <?php } elseif ($row['polling_type'] === 'single_vote') {
            ?>
            <h1 class="text-blue-800 font-bold mb-3">You can select only one option</h1>
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
                type="button"
                id="closeOptionalPollModal" 
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
</section>
<script>
    const pollOption = <?= json_encode($allOptionDetails, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    const optionModal = document.getElementById("option-modal");
    const optionImage = document.getElementById("option-image");
    const optionTitle = document.getElementById("option-title");
    const optionQuestion = document.getElementById("option-question");
    const option = document.getElementById("options");

    function removeOptionalModal(){
    optionModal.classList.add('hidden')
}
function showOptionalModal(){
    optionModal.classList.remove('hidden')
}

const triggerOptionalButtons = document.querySelectorAll('.triggerOptionalModal');
    const optionModalPoll = document.getElementById('optionModalPoll');
    const closeOptionalModal = document.getElementById('closeOptionalModal');
    const closeOptionalPollModal = document.getElementById('closeOptionalPollModal');


    triggerOptionalButtons.forEach(button => {
        button.addEventListener('click', () => {
            optionModalPoll.classList.remove('hidden');
        });
    });
    
    closeOptionalPollModal.addEventListener('click', () => {
        optionModalPoll.classList.add('hidden');
    });
    
    let optionButton=document.querySelector('.view-option-details');
    
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
    closeOptionalModal.addEventListener("click", removeOptionalModal);

    function showDetails(details) {
      const voterPayAmount = details.polling_payment === 'poll-participant'
  ? `<p class="text-blue-800"><strong>Voter Pay Amount:</strong><span class="block text-gray-600"> $${details.voter_pay_amount}</span></p>`
  : `<p class="text-blue-800"><strong>Voter Pay Amount:</strong><span class="block text-gray-600">Free</span></p>`;

document.getElementById('modalContent').innerHTML = `
    <p class="text-blue-800"><strong>Title:</strong> <span class="block text-gray-600">${details.poll_title}</span></p>
    <p class="text-blue-800"><strong>Description:</strong> <span class="block text-gray-600">${details.poll_description}</span></p>
    <p class="text-blue-800"><strong>Status:</strong> <span class="block text-gray-600">${details.poll_status}</span></p>
    <p class="text-blue-800"><strong>Polling Choice:</strong> <span class="block text-gray-600">${details.polling_method}</span></p>
    ${voterPayAmount}
    <p class="text-blue-800"><strong>Category:</strong> <span class="block text-gray-600">${details.poll_category}</span></p>
    <p class="text-blue-800"><strong>Anonymous:</strong> <span class="block text-gray-600">${details.anonymous_poll === "0" ? 'False': 'True'}</span></p>
    <p class="text-blue-800"><strong>Start Date:</strong> <span class="block text-gray-600">${new Date(details.start_date).toISOString().split('T')[0]}</span></p>
    <p class="text-blue-800"><strong>End Date:</strong> <span class="block text-gray-600">${new Date(details.end_date).toISOString().split('T')[0]}</span></p>
`;

        document.getElementById('detailsModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('detailsModal').classList.add('hidden');
    }


</script>

