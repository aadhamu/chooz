
<?php
$userid=$user['id'];
$username=$user['username'];
$userfirstname=$user['firstname'];
$ongoing ="SELECT * FROM votes 
JOIN polls p ON poll_id = p.id
WHERE user_id = '$userid' AND poll_status='ongoing' AND p.is_username='1'";
$ongoingQuery=mysqli_query($conn, $ongoing);
$ongoingArray=mysqli_fetch_assoc($ongoingQuery);

$nominated = "SELECT * FROM polls p
JOIN poll_options ON p.id = poll_id
WHERE p.is_username='1' AND FIND_IN_SET('$username', option_text) > 0 AND p.poll_status='ongoing'";
$nominatedQuery = mysqli_query($conn, $nominated);
$nominatedArray = mysqli_fetch_assoc($nominatedQuery);

$pollId=$nominatedArray['poll_id'];

$totalVote="SELECT SUM(num_vote) AS total_votes FROM votes
JOIN polls p ON p.id='$pollId' AND p.poll_status='ongoing'
";
$totalQuery=mysqli_query($conn,$totalVote);
$totalArray=mysqli_fetch_assoc($totalQuery);
// print_r($totalArray);

$userVote=" SELECT num_vote,
       (num_vote / (SELECT SUM(num_vote) FROM votes WHERE poll_id = '$pollId') * 100) AS percentage
FROM votes
WHERE poll_id = $pollId
";
$userQuery=mysqli_query($conn, $userVote);
$userArray=mysqli_fetch_assoc($userQuery);
$userPercntage=intval($userArray['percentage']);
?>
<section class="ml-10">
   <h1 class="text-blue-800 font-bold text-3xl mb-2">Hello, <?= ucfirst(strtolower($user['firstname'])) ?>!</h1>
   <p class='text-gray-500'>Welcome To chooz online voting platform</p>

   <div class="flex">
    <!-- LEFT CORNER -->
    <div>
        <div class="w-11/12 mb-5 border mt-5 space-x-5 flex bg-white p-5 rounded-lg shadow-md justify-between items-center">
            <div class="flex flex-col space-y-2">
                <h3 class=" text-medium font-semibold text-blue-800">Ongoing Elections</h3>
                <h1 class="text-2xl font-bold text-blue-800"><?= $ongoingArray['poll_title'] ?></h1>
                <button class="mt-auto mx-auto px-4 py-2 text-blue-500 border-2 border-blue-500 rounded-3xl transition">View Analysis</button>
            </div>
            <img src="assets/polling-phone.png" alt="polling-phone" class="w-40 rounded-lg ">
        </div>

        <div class="w-11/12 mb-5 border mt-5 bg-white p-5 rounded-lg shadow-md ">
            <h3 class="text-medium font-semibold text-blue-800">Live Result (% of people who has voted for you)</h3>
            <div>
                <div class="w-7/12 mx-auto flex my-4 items-center justify-between">
                    <h3 class="font-bold text-blue-800">President Student Council</h3>
                </div>

                    <!-- Do a graph to show design between all candidate -->
                <div class="space-y-6 mt-7 ">
                   

                    <div class="flex items-center space-x-10 justify-between">
                        <p class="text-lg"><?= $userfirstname; ?></p>
                        <div class="relative w-80 bg-gray-200 h-6 rounded-full">
                            <div class="bg-red-600 h-full rounded-full" style="width: <?=$userPercntage?>%;"></div>
                            <span class="absolute right-0 top-0 text-sm text-gray-800 pr-2"><?=$userPercntage?>%</span>
                        </div>
                    </div>

                   
                </div>
            </div>
        </div>
    </div>

      <!-- RIGHT CORNER -->
      <div>
        <div class=" mb-5 border mt-5 space-x-5 flex bg-white p-5 rounded-lg shadow-md justify-between items-center">
            <div class="flex flex-col space-y-2 ">
                <h3 class=" text-medium font-semibold text-blue-800">Nominated</h3>
                <h1 class="text-2xl font-bold text-blue-800"><?= $nominatedArray['poll_title'] ?></h1>
                <button class="mt-auto mx-auto px-4 py-2 text-blue-500 border-2 border-blue-500 rounded-3xl transition ">View Analysis</button>
            </div>
            <img src="assets/polling-phone.png" alt="polling-phone" class="w-40 rounded-lg ">
        </div>
    

      </div>
   </div>
</section>
