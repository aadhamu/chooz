<?php
if(!isset($_SESSION['option_id'])){
  header('location: mypoll.php');
  exit();
}
$optionID=mysqli_real_escape_string($conn,$_SESSION['option_id']) ;

$username=$user['username'];
$userId=$user['id'];
 
 $option="
   SELECT * FROM polls p
   JOIN poll_options po ON p.id = po.poll_id
   WHERE p.is_username='0'AND p.poll_creator='$userId' AND p.id='$optionID'
 ";
 $optionQuery=mysqli_query($conn, $option);
 $optionArray=mysqli_fetch_assoc($optionQuery);

 $optionDetails = [];
 $highestVoter = null; 
 $maxVotes = 0; 
 $allOptions = $optionArray['option_text'];
 $listedOptionArray = explode(',', $allOptions);
 $nomineeDetailsArray = [];

foreach ($listedOptionArray as $option) {
    // Getting nominee votes
    $option = trim($option);
    $optionQuery = "SELECT SUM(num_vote) AS total_votes, option
                  FROM votes 
                  WHERE poll_id='$optionID' AND option='$option'";
    $optionResult = mysqli_query($conn, $optionQuery);
    
    if ($optionResult && mysqli_num_rows($optionResult) > 0) {
        $option_text = mysqli_fetch_assoc($optionResult);
        $optionDetails[] = $option_text;
         if($option_text['total_votes'] == $maxVotes){
            $draw="it is a draw";
         }elseif ($option_text['total_votes'] > $maxVotes) {
            $maxVotes = $option_text['total_votes'];
            $highestVoter = $option_text;
        }

    }




}
if(!isset($draw)){
    $firstTwoWordsString =ucwords(substr($highestVoter['option'], 0, 2));
}

$gettingParticipant = "SELECT COUNT(DISTINCT user_id) AS participant_count FROM votes WHERE poll_id = ?";
$stmt = $conn->prepare($gettingParticipant);
$stmt->bind_param("i", $optionID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$participantCount = $row['participant_count'];


// RULES
$query = "
    SELECT *
    FROM polls p 
    WHERE p.id = ? AND
    p.poll_status = 'ongoing'
";

$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $optionID);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
}

// Option details

$publicVote = "SELECT * FROM polls p 
JOIN poll_options po ON p.id = po.poll_id
WHERE   p.poll_status='ongoing' AND p.id='$optionID' AND p.is_username ='0' ORDER BY p.created_at DESC";
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
?>
<section>
<div class=" p-6">
    <div class="bg-white rounded-lg p-6 mb-8">
        <h2 class="text-3xl font-bold text-blue-900">Poll Analysis</h2>
        <div class="mt-4 space-y-2">
            <p class="text-blue-800 font-bold">Poll Name: <span class="font-semibold text-gray-800"><?=$optionArray['poll_title']?>.</span></p>
            <p class="text-blue-800 font-bold">Poll Description: <span class="italic font-semibold text-gray-800"><?=$optionArray['poll_description']?>.</span></p>
            <p class="text-blue-800 font-bold">Status: <span class="font-semibold text-gray-800"><?=$optionArray['poll_status']?>.</span></p>
        </div>
    </div>
<div class="flex justify-center space-x-5 p-6 ">
  <button class="rounded-3xl text-white w-6/12 bg-blue-800 p-3 rule-btn">Rules</button>
  <button class="rounded-3xl text-white w-6/12 bg-blue-800 p-3 view-option-details" onclick="showDetails(<?= htmlspecialchars(json_encode($publicVoteArrays)) ?>)">View Details</button>
</div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
    <div class="bg-blue-50 border border-blue-300 rounded-lg p-6">
        <h3 class="text-xl font-bold text-blue-800">Total Participants</h3>

        <p class="text-4xl font-bold text-blue-600 mt-4"><?= $participantCount?></p>
    </div>
    
    <div class="bg-blue-50 border border-blue-300 rounded-lg p-6">
        <h3 class="text-xl font-bold text-blue-800">Number Of Votes</h3>
        <div class="mt-6 space-y-4">
            <?php
              foreach($optionDetails as $option):
            ?>
            <div class="flex items-center justify-between">
                <p class="text-lg font-bold text-gray-800"><?=$option['option']?></p>
                <p class="text-lg font-semibold text-blue-600"><?= $option['total_votes']?></p>
            </div>
            <?php endforeach; ?>
        </div>
       
    </div>

    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-6">
        <h3 class="text-xl font-semibold text-yellow-800">Most Voted</h3>
        <div class="mt-4 flex items-center">
            <?php if(isset($draw)):?>
            <div class="w-16 h-16 bg-yellow-300 p-2 text-yellow-800 font-bold rounded-full flex items-center justify-center text-2xl">
                D
            </div>
            <p class="ml-4 text-lg font-semibold text-gray-800">
                <?= $draw?> with a total of
                <span class="text-yellow-600 font-bold"><?=$maxVotes?> votes</span>.</p>
            <?php else:?>
                <div class="w-16 h-16 bg-yellow-300 p-2 text-yellow-800 font-bold rounded-full flex items-center justify-center text-2xl">
                    <?=$firstTwoWordsString?>
                </div>
                <p class="ml-4 text-lg font-semibold text-gray-800">
                <?=$highestVoter['option']?> is the most voted with 
                <span class="text-yellow-600 font-bold"><?=$highestVoter['total_votes']?> votes</span>.</p> 
            <?php endif;?>
        </div>
    </div>
</div>

 
<div id="rules-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white rounded-lg w-1/2 p-5 space-y-5">
      <h2 class="text-blue-800 text-xl font-bold">Voting Rules</h2>
      <ul class="list-disc pl-5 space-y-2 text-gray-600">
      <?php 
          foreach($rows as $keys):
        ?>
        <li><?= $keys['polling_method'] === 'multiple_choice' ? 'You can vote more than one nominee' : 'You can only vote one nominee.'?></li>
        <li><?= $keys['polling_type'] === 'multiple_vote' ? 'You can cast more than one vote' : 'You can only cast one vote.'?></li>
        <li><?= $keys['anonymous_poll'] === '0' ? 'Votes are not anonymous': 'Votes are anonymous'?></li>
        <li>Vote closes by <?= $keys['end_date']?></li>
        <li><?php 
        if($keys['voter_pay_amount']){
            echo 'Voters pay '.$keys['voter_pay_amount'].' to vote';
        }?>
        <?php endforeach;?>
    </ul>
    <button id="close-modal" class="text-white bg-blue-800 px-4 py-2 rounded-lg">Close</button>
</div>
<!-- option details -->
    </div>
<div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white  w-3/4 lg:w-1/2 p-6 rounded-lg space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Poll Details</h2>
                <button class="text-gray-500 text-xl" onclick="closeModal()">×</button>
            </div>
            <div id="modalContent" class="space-y-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            
            </div>
            <button class="w-full bg-blue-800 text-white py-2 rounded-lg" onclick="closeModal()">Close</button>
        </div>
    </div>
</div>
</section>


<script>
    const pollOption = <?= json_encode($allOptionDetails, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    let rulesModal=document.getElementById('rules-modal')

function showRulesModal(){
    rulesModal.classList.remove('hidden')
}

function removeRulesModal(){
    rulesModal.classList.add('hidden')
}

function showDetails(details) {
    details.forEach(detail =>{
        const voterPayAmount = detail.polling_payment === 'poll-participant'
  ? `<p class="text-blue-800"><strong>Voter Pay Amount:</strong><span class="block text-gray-600"> $${detail.voter_pay_amount}</span></p>`
  : `<p class="text-blue-800"><strong>Voter Pay Amount:</strong><span class="block text-gray-600">Free</span></p>`;

document.getElementById('modalContent').innerHTML = `
    <p class="text-blue-800"><strong>Title:</strong> <span class="block text-gray-600">${detail.poll_title}</span></p>
    <p class="text-blue-800"><strong>Description:</strong> <span class="block text-gray-600">${detail.poll_description}</span></p>
    <p class="text-blue-800"><strong>Status:</strong> <span class="block text-gray-600">${detail.poll_status}</span></p>
    <p class="text-blue-800"><strong>Polling Choice:</strong> <span class="block text-gray-600">${detail.polling_method}</span></p>
    ${voterPayAmount}
    <p class="text-blue-800"><strong>Category:</strong> <span class="block text-gray-600">${detail.poll_category}</span></p>
    <p class="text-blue-800"><strong>Anonymous:</strong> <span class="block text-gray-600">${detail.anonymous_poll === "0" ? 'False': 'True'}</span></p>
      <p class="text-blue-800"><strong>Start Date:</strong> <span class="block text-gray-600">${new Date(detail.start_date).toISOString().split('T')[0]}</span></p>
    <p class="text-blue-800"><strong>End Date:</strong> <span class="block text-gray-600">${new Date(detail.end_date).toISOString().split('T')[0]}</span></p>
    `;
})
openModal()
}
function openModal(){
    document.getElementById('detailsModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('detailsModal').classList.add('hidden');
}


document.querySelector('.rule-btn').addEventListener('click', showRulesModal)
document.getElementById('close-modal').addEventListener('click', removeRulesModal);

</script>
