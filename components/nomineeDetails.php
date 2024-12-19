<?php
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

$pollid = $_SESSION['pollid'] ?? '';
$username = $user['username'];

// GETTING NOMINEE INFO FOR A POLL
$nomineeDetails = "SELECT * FROM nominees_details WHERE poll_id ='$pollid' AND nominee_username='$username'";
$nomineeDetailsQuery = mysqli_query($conn, $nomineeDetails);
$nomineeDetailsArray = mysqli_fetch_assoc($nomineeDetailsQuery);
?>

<div class="flex items-center justify-center min-h-screen">
<div class="w-full max-w-md bg-blue-800 text-white rounded-lg shadow-xl overflow-hidden">
        <?php if (!empty($success)): ?>
            <div class="text-green-100 bg-green-700 text-center py-2">
                <?= htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <div class="flex justify-center mt-6">
            <div class="rounded-full border-4 border-white p-1 bg-gradient-to-r from-red-500 to-green-400">
                <img src="nominees/<?php echo htmlspecialchars($nomineeDetailsArray['nominee_image']); ?>" 
                     alt="Nominee Image" 
                     class="w-32 h-32 rounded-full object-cover shadow-md">
            </div>
        </div>

        <div class="p-6 text-center">
            <p class="text-gray-200 mt-4"><strong>Username: </strong>
                <span class="block text-xl  text-yellow-300 font-bold"><?php echo htmlspecialchars($nomineeDetailsArray['nominee_username']); ?></span>
        </p>
           
            <p class="text-gray-200 mt-4"><strong>Bio:</strong> <span class="block font-bold text-xl text-yellow-300"><?php echo htmlspecialchars($nomineeDetailsArray['nominee_bio']); ?></span></p>
            <p class="text-gray-200 mt-2"><strong>Goals (Statement of Intent):</strong> <span class="block font-bold text-xl text-yellow-300"><?php echo htmlspecialchars($nomineeDetailsArray['nominee_statement']); ?></span></p>
            <p class="text-gray-200 mt-2"><strong>Expetise & Achievement:</strong> <span class="block font-bold text-xl text-yellow-300"><?php echo htmlspecialchars($nomineeDetailsArray['nominee_qualifications']); ?></span></p>

            <!-- Social Links -->
            <div class="mt-4">
                <p class="text-lg text-yellow-300 font-semibold">Follow Me:</p>
                <div class="flex justify-center space-x-6 mt-2">
                    <?php if (!empty($nomineeDetailsArray['linkedin'])): ?>
                        <a href="<?php echo htmlspecialchars($nomineeDetailsArray['linkedin']); ?>" 
                           class="text-yellow-300 hover:underline">LinkedIn</a>
                    <?php endif; ?>
                    <?php if (!empty($nomineeDetailsArray['twitter'])): ?>
                        <a href="<?php echo htmlspecialchars($nomineeDetailsArray['twitter']); ?>" 
                           class="text-yellow-300 hover:underline">Twitter</a>
                    <?php endif; ?>
                    <?php if (!empty($nomineeDetailsArray['instagram'])): ?>
                        <a href="<?php echo htmlspecialchars($nomineeDetailsArray['instagram']); ?>" 
                           class="text-yellow-300 hover:underline">Instagram</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Created At -->
            <p class="text-sm text-gray-300 mt-4">
                <strong>Created At:</strong> <?php echo htmlspecialchars($nomineeDetailsArray['created_at']); ?>
            </p>
        </div>
    </div>
</div>
