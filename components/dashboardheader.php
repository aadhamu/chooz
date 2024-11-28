<div class="bg-white shadow-md p-4 ml-64" id="header">
    <nav class="flex justify-between items-center">
        <!-- Search Bar (optional) -->
        <div class="flex items-center border border-gray-300 rounded-lg px-4 py-2 max-w-xs">
            <input 
                type="text" 
                placeholder="Search..." 
                class="w-full text-sm text-gray-700 placeholder-gray-500 focus:outline-none">
            <i class="fas fa-search text-gray-500 ml-2"></i>
        </div>

        <!-- User and Notifications -->
        <div class="flex items-center space-x-4">
                <!-- Notifications Icon -->
                <div class="relative">
                    <i class="fas fa-bell text-gray-700 text-lg cursor-pointer"></i>
                    <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-1">3</span> <!-- Notification count -->
                </div>

                <!-- User Profile Dropdown -->
            <div class="relative">
                <button id="dropdownButton" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 focus:outline-none">
                    <img 
                        src="https://via.placeholder.com/40" 
                        alt="User Profile" 
                        class="w-8 h-8 rounded-full object-cover border-2 border-blue-300 shadow-md">
                    <span class="font-medium">Juan Dela Cruz</span>
                    <i class="fas fa-caret-down text-gray-500"></i>
                </button>

                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg border border-gray-200">
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li><a href="profile.php" class="block px-4 py-2 hover:bg-gray-100">Profile</a></li>
                        <li><a href="setting.php" class="block px-4 py-2 hover:bg-gray-100">Settings</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Log Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
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
</script>

