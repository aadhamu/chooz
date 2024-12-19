<?php
if(!isset($_SESSION['created_id'])){
  header('location: mypoll.php');
  exit();
}
$createdID=mysqli_real_escape_string($conn,$_SESSION['created_id']) ;

$username=$user['username'];
$userId=$user['id'];
 
 $creator="
   SELECT * FROM polls p
   JOIN poll_options po ON p.id = po.poll_id
   WHERE p.is_username='1'AND p.poll_creator='$userId' AND p.id='$createdID'
 ";
 $createdQuery=mysqli_query($conn, $creator);
 $createdArray=mysqli_fetch_assoc($createdQuery);

 $userDetails = [];
 $highestVoter = null; 
 $maxVotes = 0; 
 $allNominees = $createdArray['option_text'];
 $nomineesArray = explode(',', $allNominees);
 $nomineeDetailsArray = [];

foreach ($nomineesArray as $username) {
    // Getting nominee votes
    $username = trim($username);
    $userQuery = "SELECT SUM(num_vote) AS total_votes, username
                  FROM votes 
                  WHERE poll_id='$createdID' AND username='$username'";
    $userResult = mysqli_query($conn, $userQuery);
    
    if ($userResult && mysqli_num_rows($userResult) > 0) {
        $user = mysqli_fetch_assoc($userResult);
        $userDetails[] = $user;
         if($user['total_votes'] == $maxVotes){
            $draw="it is a draw";
         }elseif ($user['total_votes'] > $maxVotes) {
            $maxVotes = $user['total_votes'];
            $highestVoter = $user;
        }

    }



    $nomineeDetails = "SELECT * FROM nominees_details nd
    JOIN users u ON u.username = nd.nominee_username
    JOIN polls p ON p.id = nd.poll_id
    WHERE nd.nominee_username = '$username'";
$nomineeDetailsQuery = mysqli_query($conn, $nomineeDetails);

// Initialize an empty array to store results

// Fetch all rows into the array
while ($row = mysqli_fetch_assoc($nomineeDetailsQuery)) {
    $nomineeDetailsArray[] = $row;
}

}
if(!isset($draw)){
    $firstTwoWordsString =ucwords(substr($highestVoter['username'], 0, 2));
}

$gettingParticipant = "SELECT COUNT(DISTINCT user_id) AS participant_count FROM votes WHERE poll_id = ?";
$stmt = $conn->prepare($gettingParticipant);
$stmt->bind_param("i", $createdID);
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
    $stmt->bind_param("s", $createdID);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
}

// $totalVotesAndUsers = "SELECT SUM(num_vote) AS total_votes, COUNT(DISTINCT user_id) AS num_users
//                        FROM votes 
//                        WHERE poll_id='$nominatedID' AND username='$username'";

// $totalQuery = mysqli_query($conn, $totalVotesAndUsers);
// $totalArray = mysqli_fetch_assoc($totalQuery);

// $allVotes = $totalArray['total_votes'];
// $numUsers = $totalArray['num_users'];




?>

<div class=" p-6">
    <div class="bg-white rounded-lg p-6 mb-8">
        <h2 class="text-3xl font-bold text-blue-900">Poll Analysis</h2>
        <div class="mt-4 space-y-2">
            <p class="text-blue-800 font-bold">Poll Name: <span class="font-semibold text-gray-800"><?=$createdArray['poll_title']?>.</span></p>
            <p class="text-blue-800 font-bold">Poll Description: <span class="italic font-semibold text-gray-800"><?=$createdArray['poll_description']?>.</span></p>
            <p class="text-blue-800 font-bold">Status: <span class="font-semibold text-gray-800"><?=$createdArray['poll_status']?>.</span></p>
        </div>
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
              foreach($userDetails as $user):
            ?>
            <div class="flex items-center justify-between">
                <p class="text-lg font-bold text-gray-800"><?=$user['username']?></p>
                <p class="text-lg font-semibold text-blue-600"><?= $user['total_votes']?></p>
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
                <?=$highestVoter['username']?> is the most voted with 
                <span class="text-yellow-600 font-bold"><?=$highestVoter['total_votes']?> votes</span>.</p> 
            <?php endif;?>
        </div>
    </div>
</div>

<div class="container mx-auto py-6">
    <div class="bg-white rounded-lg p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Nominees</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-6" id="candidates">
    <?php foreach ($nomineeDetailsArray as $index => $key): ?>
        <div class="space-y-3 flex flex-col justify-center items-center bg-gradient-to-b from-blue-200 to-white  rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden w-full p-3">
            <img 
                src="<?= $key['nominee_image'] ? 'nominees/' . $key['nominee_image'] : 'path/to/placeholder.jpg' ?>" 
                alt="Nominee Image" 
                class="rounded-full w-40 h-40 object-cover"
            />

                <h2 class="font-bold text-xl text-blue-800">
                    <?= $key['firstname'] . ' ' . $key['middle_name'] . ' ' . $key['last_name'] ?>
                </h2>

                <div class="flex space-x-3 justify-between items-center w-full">
                    <button class="rounded-3xl text-white w-6/12 bg-blue-800 p-3 rule-btn"  data-username="<?=$key['username']?>">Rules</button>
                    <button data-index="<?=$index?>" class="view-details  rounded-3xl text-blue-800 w-6/12 bg-white border-2 border-blue-800 p-3" >View Details</button>
                </div>

        </div>
    <?php endforeach; ?>
</div>
<div id="candidate-modal" class="hidden fixed z-40 inset-0 bg-black bg-opacity-50 flex justify-center items-center mx-auto">
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
        <button id="close-candidate-modal" class="text-white bg-blue-800 px-4 py-2 rounded-lg">Close</button>
    </div>
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
        if($keys['voter_pay_amount']){
            echo 'Voters pay '.$keys['voter_pay_amount'].' to vote';
        }?>
        <?php endforeach;?>
    </ul>
    <button id="close-modal" class="text-white bg-blue-800 px-4 py-2 rounded-lg">Close</button>
</div>

</div>
<script>
    let rulesModal=document.getElementById('rules-modal')
    const candidates = <?= json_encode($nomineeDetailsArray, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
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

    document.getElementById("close-candidate-modal").addEventListener("click", removeCandidateModal);
    document.getElementById("candidates").addEventListener("click", (e) => {
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

function showRulesModal(){
    rulesModal.classList.remove('hidden')
}

function removeRulesModal(){
    rulesModal.classList.add('hidden')
}

function showCandidateModal(){
    candidateModal.classList.remove('hidden')
}

function removeCandidateModal(){
    candidateModal.classList.add('hidden')
}

document.querySelectorAll('.rule-btn').forEach( key => {
    key.addEventListener('click', showRulesModal)
});
document.getElementById('close-modal').addEventListener('click', removeRulesModal);

</script>
