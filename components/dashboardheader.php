<?php
$username = $user['username']; 
$notificationQuery = "SELECT * FROM notifications n
JOIN polls p ON p.id = n.poll_id 
WHERE username = '$username' ORDER BY n.created_at DESC";
$result = mysqli_query($conn, $notificationQuery);

$notifications = [];
while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
}
$totalNotifications = count($notifications);



if (isset($_POST['poll_details'])) {
    $poll_id =mysqli_real_escape_string($conn,sanitize($_POST['poll_id']));

    $query = "SELECT * FROM polls WHERE id = '$poll_id'";
    $result = mysqli_query($conn, $query);
    $poll = mysqli_fetch_assoc($result);
    $_SESSION['pollid']=$poll['id'];

    header('Location: polldetails.php');
    exit;
}

$unreadNotificationCount="SELECT COUNT(*) AS total FROM notifications WHERE username = '$username' AND is_read='False'";
$unreadNotificationCountResult = mysqli_query($conn, $unreadNotificationCount);
$unreadNotificationArray=mysqli_fetch_assoc($unreadNotificationCountResult);

?>



<div class="bg-white shadow-md p-4 ml-64" id="header">
    <nav class="flex justify-between items-center">
        <div class="flex items-center border border-gray-300 rounded-lg px-4 py-2 max-w-xs">
            <input 
                type="text" 
                placeholder="Search..." 
                class="w-full text-sm text-gray-700 placeholder-gray-500 focus:outline-none">
            <i class="fas fa-search text-gray-500 ml-2"></i>
        </div>

        <div class="flex items-center space-x-4">
                <div class="relative">
    <i id="notificationIcon" class="fas fa-bell text-gray-700 text-lg cursor-pointer"></i>
    <span class="absolute top-0 -right-2  bg-red-500 text-white text-xs rounded-full px-1"><?= $unreadNotificationArray['total']?></span> <!-- Notification count -->

    <div
        id="notificationDropdown"
        class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-md hidden"
    >
        <div class="p-4 border-b">
            <span class="font-bold text-gray-700">Notifications</span>
        </div>
        <ul class="p-2 space-y-2">
        <?php
        $limit = 5;
        for ($i = 0; $i < min($limit, $totalNotifications); $i++) :
            $notice = $notifications[$i];
            $isReadClass = $notice['is_read'] ? 'text-gray-500' : 'bg-gray-100';
        ?>
        <li>
            <form action="dashboard.php" method="POST">
                <input type="hidden" name="poll_id" value="<?= $notice['poll_id'] ?>" />

                <button
                    type="submit"
                    class="flex justify-between p-2 space-x-3 border-b pb-2 hover:bg-gray-100 transition <?= $isReadClass ?>"
                    name="poll_details"
                >
                    <img
                        src="poll_image/<?= $notice['poll_image'] ?>"
                        alt="poll_image"
                        class="w-10 h-10 rounded-full object-cover"
                    />
                    <span class="text-gray-800 text-sm text-start" ><?= $notice['message'] ?></span>
                </button>
            </form>
        </li>
        <?php endfor; ?>


                </ul>
                <?php if ($totalNotifications > $limit) : ?>
                    <div class="text-center mt-2">
                        <a href="javascript:void(0);" id="seeMoreLink" class="text-blue-600">See more</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

            <div class="relative">
                <button id="dropdownButton" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 focus:outline-none">
                    <img 
                    src="<?php echo $user['profile_image'] ? 'profile_images/' . $user['profile_image'] : 'assets/profile.png'; ?>" 
                        alt="User Profile" 
                        class="w-8 h-8 rounded-full object-cover border-2 border-blue-300 shadow-md">
                    <span class="font-medium"><?= ucfirst(strtolower($user['firstname'])) ?></span>
                    <i class="fas fa-caret-down text-gray-500"></i>
                </button>
                

                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg border border-gray-200">
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li><a href="profile.php" class="block px-4 py-2 hover:bg-gray-100">Profile</a></li>
                        <li><a href="setting.php" class="block px-4 py-2 hover:bg-gray-100">Settings</a></li>
                        <li><a href="javascript:void(0)" class="block px-4 py-2 hover:bg-gray-100" onclick="showLogoutModal()">Log Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
            <h2 class="text-lg font-semibold text-gray-800 text-center">Are you sure you want to log out?</h2>
            <div class="mt-6 flex justify-between">
                <button 
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition" 
                    onclick="confirmLogout()">Yes</button>
                <button 
                    class="bg-gray-100 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-200 transition" 
                    onclick="closeLogoutModal()">No</button>
            </div>
        </div>
    </div>
</div>

<script>
        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownMenu = document.getElementById('dropdownMenu');

        dropdownButton.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
        const notificationIcon = document.getElementById("notificationIcon");
        const notificationDropdown = document.getElementById("notificationDropdown");
        const seeMoreLink = document.getElementById("seeMoreLink");
        const notificationList = document.getElementById("notificationList");

        notificationIcon.addEventListener("click", () => {
            notificationDropdown.classList.toggle("hidden");
        });

        seeMoreLink?.addEventListener("click", () => {
            const allNotifications = <?= json_encode($notifications); ?>;
            let html = '';
            for (let i = 5; i < allNotifications.length; i++) {
                html += `
                    <li>
                        <a href="#" class="flex items-center space-x-3 border-b pb-2 hover:bg-gray-100 transition">
                            <img src="poll_image/${allNotifications[i].poll_image}" alt="User" class="w-10 h-10 rounded-full object-cover" />
                            <span class="text-gray-800 text-sm">${allNotifications[i].message}</span>
                        </a>
                    </li>
                `;
            }
            notificationList.innerHTML += html; 

            seeMoreLink.style.display = "none";
        });

        document.addEventListener("click", (event) => {
            if (!notificationIcon.contains(event.target) && !notificationDropdown.contains(event.target)) {
                notificationDropdown.classList.add("hidden");
            }
        });

        function showLogoutModal() {
            document.getElementById('logoutModal').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
        }

        function confirmLogout() {
            window.location.href = 'logout.php';
        }

       
</script>

