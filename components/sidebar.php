<div class="h-screen w-64 bg-gradient-to-r from-blue-100 to-white shadow-lg fixed top-0 left-0 z-10 transition-all duration-300 ease-in-out" id="sidebar">
    <!-- Sidebar Header with Hamburger Menu -->
    <div class="flex justify-between items-center p-4">
        <!-- Hamburger Menu Icon -->
        <button class="text-2xl text-blue-700" id="hamburger-menu">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Sidebar Logo -->
        <a href="home.php" class="text-blue-700 flex items-center text-2xl font-bold transition-all duration-300 ease-in-out" id="logo">
            <span class="bg-gray-200 text-blue font-bold w-10 h-10 flex items-center justify-center rounded-tr-full rounded-br-full rounded-bl-full rounded-tl-2xl" id="logo-letter">C</span>
            <span class="-ml-3" id="logo-text">HOOZ</span>
        </a>
    </div>

    <!-- User Profile Section -->
    <div class="flex flex-col items-center p-6 border-b border-gray-200 transition-all duration-300 ease-in-out" id="profile-section">
        <img 
            src="https://via.placeholder.com/100" 
            alt="User Profile" 
            class="w-16 h-16 rounded-full object-cover border-4 border-blue-300 shadow-md transition-all duration-300 ease-in-out" id="profile-image"
        >
        <div class="mt-3 text-center" id="profile-text">
            <h3 class="text-lg font-semibold text-gray-800" id="profile-name">Juan Dela Cruz</h3>
            <a href="#" class="text-sm text-blue-500 hover:underline">View Profile</a>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="mt-6">
        <ul class="space-y-3">
            <li class="nav-item">
                <a href="dashboard.php" class="flex items-center space-x-3 px-4 py-3 text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition">
                    <i class="fas fa-chart-line w-5 h-5"></i>
                    <span class="font-medium nav-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="vote.php" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition">
                    <i class="fas fa-vote-yea w-5 h-5"></i>
                    <span class="font-medium nav-text">Vote</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="createpoll.php" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition">
                    <i class="fas fa-poll-h w-5 h-5"></i>
                    <span class="font-medium nav-text">Create Poll</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="mypoll.php" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition">
                    <i class="fas fa-poll w-5 h-5"></i>
                    <span class="font-medium nav-text">My Polls</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="setting.php" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition">
                    <i class="fas fa-cog w-5 h-5"></i>
                    <span class="font-medium nav-text">Settings</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition">
                    <i class="fas fa-right-from-bracket w-5 h-5"></i>
                    <span class="font-medium nav-text">Log Out</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Add this JavaScript for toggling the sidebar width, hiding text, and logo text -->
<script>
    const hamburgerMenu = document.getElementById('hamburger-menu');
    const sidebar = document.getElementById('sidebar');
    const logo = document.getElementById('logo');
    const logoLetter = document.getElementById('logo-letter');
    const logoText = document.getElementById('logo-text');
    const profileSection = document.getElementById('profile-section');
    const profileImage = document.getElementById('profile-image');
    const profileText = document.getElementById('profile-text');
    const profileName = document.getElementById('profile-name');
    const navItems = document.querySelectorAll('.nav-item');
    const navText = document.querySelectorAll('.nav-text');

    hamburgerMenu.addEventListener('click', () => {
        // Toggle sidebar width
        sidebar.classList.toggle('w-16');  // Collapse sidebar to 16rem
        sidebar.classList.toggle('w-64');  // Expand sidebar to 64rem
        
        // Toggle logo (both letter and text) visibility
        logo.classList.toggle('text-2xl');
        logoLetter.classList.toggle('hidden');  // Hide logo letter "C"
        logoText.classList.toggle('hidden');    // Hide logo text "HOOZ"
        
        // Toggle profile section visibility
        profileSection.classList.toggle('hidden');
        profileName.classList.toggle('hidden');
        
        // Keep the profile image visible but shrink it when collapsed
        profileImage.classList.toggle('w-12');
        profileImage.classList.toggle('h-12');

        // Toggle nav item text visibility (hide when collapsed)
        navText.forEach((item) => {
            item.classList.toggle('hidden');
        });
    });
</script>
