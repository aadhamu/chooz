<?php
// IF USER HAS AN ONGOING VOTE
$userId = $user['id']; 
if (isset($_SESSION['vote_key'])) {
    $voteKey = $_SESSION['vote_key'];

    $query = "
        SELECT uk.vote_key
        FROM user_vote_key uk
        JOIN polls p ON uk.vote_key = p.poll_key
        WHERE uk.user_id = ? AND p.poll_status = 'ongoing' AND uk.vote_key = ?
    ";

    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("is", $userId, $voteKey);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['vote_key'] = $row['vote_key']; 

            // Uncomment below lines to redirect if needed
            // header("Location: vote.php");
            // exit();
        } 
    } else {
        echo "Failed to prepare the database query: " . $conn->error;
    }
} 

$voteKey = $voteKeyError = '';

if (isset($_POST['submit_vote_key'])) {
    $voteKey = mysqli_real_escape_string($conn, sanitize($_POST['vote_key']));

    if (empty($voteKey)) {
        $voteKeyError = "Please input a valid vote key.";
    }

    if (empty($voteKeyError)) {
        $query = "SELECT * FROM polls WHERE poll_key = ? AND poll_status = ?";
        $status = 'ongoing';
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ss", $voteKey, $status);  
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $keyRepitionQuery = "SELECT * FROM user_vote_key WHERE vote_key = ? AND user_id = ?";
                $keyRepititionStmt = $conn->prepare($keyRepitionQuery);

                if ($keyRepititionStmt) {
                    $keyRepititionStmt->bind_param("si", $voteKey, $userId);  
                    $keyRepititionStmt->execute();
                    $keyRepititionResult = $keyRepititionStmt->get_result();

                    if ($keyRepititionResult && $keyRepititionResult->num_rows > 0) {
                        $row = $keyRepititionResult->fetch_assoc();
                        $_SESSION['vote_key'] = $row['vote_key'];
                        session_write_close();
                        header("Location: vote.php");
                        exit();
                    } else {
                        $insertQuery = "INSERT INTO user_vote_key (user_id, vote_key) VALUES (?, ?)";
                        $insertStmt = $conn->prepare($insertQuery);

                        if ($insertStmt) {
                            $insertStmt->bind_param("is", $userId, $voteKey);
                            $insertStmt->execute();

                            $_SESSION['vote_key'] = $voteKey;
                            session_write_close();
                            header("Location: validatekey.php");
                            exit();
                        } else {
                            $voteKeyError = "Failed to save vote key. Please try again.";
                        }
                    }
                } else {
                    $voteKeyError = "Database error: Unable to prepare statement.";
                }
            } else {
                $voteKeyError = "Invalid vote key or the poll is not currently ongoing.";
            }
        } else {
            $voteKeyError = "Database error: Unable to prepare statement.";
        }
    }
}

?>

<section class="ml-10">

    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Enter Vote Key to Vote</h2>

    <form action="validatekey.php" method="POST" class="w-6/12 mx-auto">
        <div class="mb-3">
            <label for="vote_key" class="block text-gray-700 text-sm font-medium">Vote Key:</label>
            <input type="text" id="vote_key" name="vote_key" class="mt-2 p-3 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-800 placeholder-gray-500" placeholder="Enter your vote key" value="<?php echo isset($_POST['vote_key']) ? htmlspecialchars($_POST['vote_key']) : ''; ?>" />
        </div>
        <?php if($voteKeyError):?>
                <div class="text-red-500 text-sm font-bold mb-2 text-center"><?= $voteKeyError?></div>
            <?php endif;?>
        
        <input type="submit" name="submit_vote_key" class="w-full cursor-pointer bg-blue-800 text-white py-3 rounded-lg font-medium hover:bg-indigo-600 transition duration-300" value="Submit">
    </form>

</section>
