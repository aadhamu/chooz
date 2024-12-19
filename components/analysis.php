<?php
if(!isset($_SESSION['invited_id'])){
  header('location: mypoll.php');
  exit();
}
$nominatedID=mysqli_real_escape_string($conn,$_SESSION['invited_id']) ;

$username=$user['username'];
 
 $nominated="
   SELECT * FROM polls p
   JOIN poll_options po ON p.id = po.poll_id
   WHERE p.is_username='1' AND FIND_IN_SET('$username', option_text) > 0 AND p.poll_status='ongoing' AND p.id='$nominatedID'
 ";
 $nominatedQuery=mysqli_query($conn, $nominated);
 $nominatedArray=mysqli_fetch_assoc($nominatedQuery);


$totalVotesAndUsers = "SELECT SUM(num_vote) AS total_votes, COUNT(DISTINCT user_id) AS num_users
                       FROM votes 
                       WHERE poll_id='$nominatedID' AND username='$username'";

$totalQuery = mysqli_query($conn, $totalVotesAndUsers);
$totalArray = mysqli_fetch_assoc($totalQuery);

$allVotes = $totalArray['total_votes'];
$numUsers = $totalArray['num_users'];

// $allNominees = $nominatedArray['option_text']; 
// $nomineesArray = explode(',', $allNominees);
// foreach ($nomineesArray as $username) {
//     $username = trim($username);
//     $userQuery = "SELECT * FROM users  
//     JOIN nominees_details ON nominee_username=username
//     WHERE username='$username'";
//     $userResult = mysqli_query($conn, $userQuery);
    
//     if (mysqli_num_rows($userResult) > 0) {
//         $userDetails = mysqli_fetch_assoc($userResult);
//         print_r($userDetails);
//     } 
// }


?>

<div class=" p-6">
    <!-- Poll Overview Section -->
    <div class="bg-white rounded-lg p-6 mb-8">
        <h2 class="text-3xl font-bold text-blue-900">Poll Analysis</h2>
        <div class="mt-4 space-y-2">
            <p class="text-blue-800 font-bold">Poll Name: <span class="font-semibold text-gray-800"><?=$nominatedArray['poll_title']?>.</span></p>
            <p class="text-blue-800 font-bold">Poll Description: <span class="italic font-semibold text-gray-800"><?=$nominatedArray['poll_description']?>.</span></p>
            <p class="text-blue-800 font-bold">Status: <span class="font-semibold text-gray-800"><?=$nominatedArray['poll_status']?>.</span></p>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
    <!-- Total Participants -->
    <div class="bg-blue-50 border border-blue-300 rounded-lg p-6">
        <h3 class="text-xl font-bold text-blue-800">Total Participants</h3>
        <p class="text-4xl font-bold text-blue-600 mt-4"><?= $numUsers?></p>
    </div>
    
    <!-- Number of Votes Per Option -->
    <div class="bg-blue-50 border border-blue-300 rounded-lg p-6">
        <h3 class="text-xl font-bold text-blue-800">Number Of Votes</h3>
        <p class="text-4xl font-bold text-blue-600 mt-4"><?= $allVotes?></p>
       
    </div>

    <!-- Most Voted Option -->
    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-6">
        <h3 class="text-xl font-semibold text-yellow-800">Most Voted</h3>
        <div class="mt-4 flex items-center">
            <!-- <div class="w-16 h-16 bg-yellow-300 text-yellow-800 font-bold rounded-full flex items-center justify-center text-2xl">
                JS
            </div>
            <p class="ml-4 text-lg font-semibold text-gray-800">JavaScript is the most voted option with <span class="text-yellow-600 font-bold">150 votes</span>.</p> 
            <span class="text-yellow-600 font-bold">150 votes</span>
            -->
            <p class="ml-4 text-lg font-semibold text-gray-800">Will be revealed at the End Of The Poll.</p>
        </div>
    </div>
</div>
<?php
  if(isset($_SESSION['created_poll'])):
?>
<div class="container mx-auto py-6">
    <!-- Participant Details Section -->
    <div class="bg-white rounded-lg p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Participants</h3>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="bg-gray-100 rounded-lg p-6 hover:bg-gray-200 transition duration-300">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">John Doe</h4>
                <p class="text-sm text-gray-600">Email: john.doe@example.com</p>
                <p class="text-sm text-gray-600">Submitted: Yes</p>
                <!-- View More Button -->
                <button 
                    class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200"
                    data-name="John Doe" 
                    data-email="john.doe@example.com" 
                    data-submitted="Yes"
                    onclick="openModal(this)"
                >
                    View More
                </button>
            </div>

            <!-- Card 2 -->
            <div class="bg-gray-100 rounded-lg p-6 hover:bg-gray-200 transition duration-300">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Jane Smith</h4>
                <p class="text-sm text-gray-600">Email: jane.smith@example.com</p>
                <p class="text-sm text-gray-600">Submitted: No</p>
                <!-- View More Button -->
                <button 
                    class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200"
                    data-name="Jane Smith" 
                    data-email="jane.smith@example.com" 
                    data-submitted="No"
                    onclick="openModal(this)"
                >
                    View More
                </button>
            </div>

            <!-- Card 3 -->
            <div class="bg-gray-100 rounded-lg p-6 hover:bg-gray-200 transition duration-300">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Michael Brown</h4>
                <p class="text-sm text-gray-600">Email: michael.brown@example.com</p>
                <p class="text-sm text-gray-600">Submitted: Yes</p>
                <!-- View More Button -->
                <button 
                    class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200"
                    data-name="Michael Brown" 
                    data-email="michael.brown@example.com" 
                    data-submitted="Yes"
                    onclick="openModal(this)"
                >
                    View More
                </button>
            </div>

            <!-- Card 4 -->
            <div class="bg-gray-100 rounded-lg p-6 hover:bg-gray-200 transition duration-300">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Emily Johnson</h4>
                <p class="text-sm text-gray-600">Email: emily.johnson@example.com</p>
                <p class="text-sm text-gray-600">Submitted: No</p>
                <!-- View More Button -->
                <button 
                    class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200"
                    data-name="Emily Johnson" 
                    data-email="emily.johnson@example.com" 
                    data-submitted="No"
                    onclick="openModal(this)"
                >
                    View More
                </button>
            </div>
        </div>
    </div>


    <div id="modal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg w-3/4 sm:w-1/2 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Participant Details</h2>
        <div class="mb-4">
            <p class="text-gray-600"><span class="font-bold">Name:</span> <span id="modalName"></span></p>
            <p class="text-gray-600"><span class="font-bold">Email:</span> <span id="modalEmail"></span></p>
            <p class="text-gray-600"><span class="font-bold">Submitted:</span> <span id="modalSubmitted"></span></p>
        </div>
        <button 
            onclick="closeModal()" 
            class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-200"
        >
            Close
        </button>
    </div>
</div>
<?php endif;?>

</div>
<script>
    // Open Modal Function
function openModal(button) {
    const name = button.getAttribute('data-name');
    const email = button.getAttribute('data-email');
    const submitted = button.getAttribute('data-submitted');

    // Set Modal Content
    document.getElementById('modalName').innerText = name;
    document.getElementById('modalEmail').innerText = email;
    document.getElementById('modalSubmitted').innerText = submitted;

    // Show Modal
    document.getElementById('modal').classList.remove('hidden');
}

// Close Modal Function
function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

</script>
