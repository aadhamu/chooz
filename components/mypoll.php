<?php
unset($_SESSION['invited_id']);
unset($_SESSION['created_id']);
unset($_SESSION['participated_id']);
$userId=$user['id'];
$userUsername=$user['username'];

$queryCreated = "
    SELECT * 
    FROM polls p 
    WHERE p.poll_creator = '$userId'
    ORDER BY p.created_at DESC 
";
$resultCreated = mysqli_query($conn, $queryCreated);
$createdPolls = mysqli_fetch_all($resultCreated, MYSQLI_ASSOC);

$queryInvited= "
    SELECT * 
    FROM poll_options po
    JOIN polls p ON p.id = po.poll_id 
    WHERE FIND_IN_SET(?, po.option_text) > 0 
    AND po.is_username = true
    ORDER BY p.created_at DESC 
";
$stmt = mysqli_prepare($conn, $queryInvited);
mysqli_stmt_bind_param($stmt, 's', $userUsername);
mysqli_stmt_execute($stmt);

$resultInvited = mysqli_stmt_get_result($stmt);
$invitedPolls = mysqli_fetch_all($resultInvited, MYSQLI_ASSOC);

$queryParticipated = "
    SELECT DISTINCT v.user_id, p.*, v.* 
    FROM votes v
    JOIN polls p ON p.id = v.poll_id
    WHERE v.user_id = ?
";

$stmt = mysqli_prepare($conn, $queryParticipated);
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);

$participatedResult = mysqli_stmt_get_result($stmt);
$participatedPolls = mysqli_fetch_all($participatedResult, MYSQLI_ASSOC);

if(isset($_POST['invitedpoll'])){
  $invitedId= mysqli_real_escape_string($conn, sanitize($_POST['invitedId']));
  
  $_SESSION['invited_id']=$invitedId;
  header("location: analysis.php");
  exit();
}
if(isset($_POST['createdpoll'])){
  $createdId= mysqli_real_escape_string($conn, sanitize($_POST['createdId']));
  $is_username= mysqli_real_escape_string($conn, sanitize($_POST['is_username']));
  
  if($is_username === '1'){
      $_SESSION['created_id']=$createdId;
      header("location: pollanalysis.php");
      exit();
  }else{
    $_SESSION['option_id']=$createdId;
    header("location: optionanalysis.php");
    exit();
  }
}
if(isset($_POST['participatedpoll'])){
  $participatedId= mysqli_real_escape_string($conn, sanitize($_POST['participatedId']));
  $username= mysqli_real_escape_string($conn, sanitize($_POST['username']));
  
  $_SESSION['participated_id']=$participatedId;
  $_SESSION['username']=$username;
  header("location: participatedpoll.php");
  exit();
}
?>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-blue-800 mb-6">My Polls</h1>
        <div class="relative">
            <div class="overflow-hidden w-full">
                <div id="carouselTrack" class="carousel-track flex">
                

                <!-- design -->
               
                 <!-- Edn of design -->
                <?php
            foreach($createdPolls as $poll):
            ?>
            <form action="mypoll.php" method="post" class=" flex flex-col justify-center items-center  rounded-lg relative">
                <input type="hidden" name="createdId" value="<?=$poll['id']?>">
                <input type="hidden" name="is_username" value="<?=$poll['is_username']?>">
                <input type="hidden" name="is_username" value="<?=$poll['is_username']?>">
                <button 
                name="createdpoll"
                type="submit" class="bg-gradient-to-b from-blue-200 to-white p-3 h-60 flex justify-center items-center flex-col rounded-lg w-5/6 mr-10 hover:shadow-lg transition">
                    <img src="poll_image/<?= $poll['poll_image']?>" alt="Poll Image" class="rounded-full w-40 h-40 object-cover">
                    <div class="mt-3">
                        <h3 class="text-lg text-start font-semibold text-gray-800"><span class="text-blue-800 font-bold">Title: </span><?= $poll['poll_title']?> </h3>
                    </div>
                </button>
            </form>
            <?php endforeach;?>
                </div>
            </div>
            <button id="prevButton" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-700 text-white px-4 py-2 rounded-l hover:bg-gray-800">
                Prev
            </button>
            <button id="nextButton" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-700 text-white px-4 py-2 rounded-r hover:bg-gray-800">
                Next
            </button>
        </div>

        <h2 class="text-2xl font-bold text-blue-800 mt-12 mb-6">Polls You Participated In</h2>
        <div class="relative">
            <div class="overflow-hidden w-full">
                <div id="participatedCarouselTrack" class="carousel-track flex">
                    <?php
            foreach($participatedPolls as $poll):
            ?>
            <form action="mypoll.php" method="post" class="relative">
                <input type="hidden" name="participatedId" value="<?=$poll['poll_id']?>">
                <input type="hidden" name="username" value="<?=$poll['username']?>">
                <button 
                name="participatedpoll"
                type="submit" class="flex-none bg-white shadow-md rounded-lg w-5/6 mr-4 hover:shadow-lg  transition">
                    <img src="poll_image/<?= $poll['poll_image']?>" alt="Poll Image" class="w-full h-32 object-cover">
                    <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800"><span class="text-blue-800 font-bold">Title: </span><?= $poll['poll_title']?> </h3>
                            <h3 class="text-lg font-semibold text-gray-800">Participated</h3>
                            <p class="text-sm text-gray-600">Your vote: <?=$poll['username']?></p>
                        </div>
                </button>
            </form>
            <?php endforeach;?>
                </div>
            </div>
            <button id="prevParticipatedButton" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-700 text-white px-4 py-2 rounded-l hover:bg-gray-800">
                Prev
            </button>
            <button id="nextParticipatedButton" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-700 text-white px-4 py-2 rounded-r hover:bg-gray-800">
                Next
            </button>
        </div>

        <h2 class="text-2xl font-bold text-blue-800 mt-12 mb-6">Polls You Are Invited To</h2>
        <div class="relative w-full h-40">
    <div class="overflow-hidden w-full">
        <div id="invitedCarouselTrack" class="carousel-track flex">

            <?php
            foreach($invitedPolls as $poll):
            ?>
            <form action="mypoll.php" method="post" class="relative">
                <input type="hidden" name="invitedId" value="<?=$poll['poll_id']?>">
                <button 
                name="invitedpoll"
                type="submit" class="flex-none bg-white shadow-md rounded-lg w-5/6 mr-4 hover:shadow-lg transition">
                    <img src="poll_image/<?= $poll['poll_image']?>" alt="Poll Image" class="w-full h-32 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800"><span class="text-blue-800 font-bold">Title: </span><?= $poll['poll_title']?> </h3>
                    </div>
                </button>
            </form>
            <?php endforeach;?>
        </div>
    </div>

    <!-- Previous Button -->
    <button id="prevInvitedButton" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-700 text-white px-4 py-2 rounded-l hover:bg-gray-800">
        Prev
    </button>

    <!-- Next Button -->
    <button id="nextInvitedButton" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-700 text-white px-4 py-2 rounded-r hover:bg-gray-800">
        Next
    </button>
</div>

    </div>


<script src="js/mypoll.js"></script>
