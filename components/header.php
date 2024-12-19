<script>
        function toggleMenu() {
            const menu = document.getElementById('side-menu');
            menu.classList.toggle('hidden');
            menu.classList.toggle('visible');
        }
    </script>

<nav class="bg-transparent text-blue-800 py-4 px-6 relative ">
    <div class=" flex justify-between items-center">
        
        <!-- Logo -->
        <div class="text-2xl font-bold flex items-center space-x-2">
            <a href="home.php" class="text-blue-700 flex items-center">
                <span class="bg-gray-200 text-blue font-bold w-10 h-10 flex items-center justify-center rounded-tr-full rounded-br-full rounded-bl-full rounded-tl-2xl">C</span>
                <span class="-ml-3">HOOZ</span>
            </a>
        </div>

        <!-- Hamburger Menu for Mobile -->
        <div class="lg:hidden">
            <button onclick="toggleMenu()" class="focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Links and Buttons -->
        <div class="hidden lg:flex items-center space-x-20">
        <div class="space-x-16">
            <?php
            $page = isset($page) ? $page: 'homepage';

            // Home link
            if ($page === 'homepage'): ?>
                <a href="home.php" class="underline underline-offset-8 font-semibold">Home</a>
            <?php else: ?>
                <a href="home.php" class="hover:underline underline-offset-8 font-semibold">Home</a>
            <?php endif; ?>

            <!-- About link -->
            <?php if ($page === 'about'): ?>
                <a href="about.php" class="underline underline-offset-8 font-semibold">About</a>
            <?php else: ?>
                <a href="about.php" class="hover:underline underline-offset-8 font-semibold">About</a>
            <?php endif; ?>

            <!-- Contact Us link -->
            <?php if ($page === 'contact'): ?>
                <a href="contact.php" class="underline underline-offset-8 font-semibold">Contact Us</a>
            <?php else: ?>
                <a href="contact.php" class="hover:underline underline-offset-8 font-semibold">Contact Us</a>
            <?php endif; ?>

            <!-- FAQs link -->
            <?php if ($page === 'faq'): ?>
                <a href="faq.php" class="underline underline-offset-8 font-semibold">FAQs</a>
            <?php else: ?>
                <a href="faq.php" class="hover:underline underline-offset-8 font-semibold">FAQs</a>
            <?php endif; ?>
        </div>

            <!-- Buttons -->
            <div class="flex space-x-4">
           
                <a href="login.php" class="border border-blue-600 rounded-full font-semibold px-6 py-2 hover:bg-white hover:text-blue-600 transition">Log In</a>
                <a href="signup.php" class="bg-white text-blue-600 font-bold rounded-full px-6 py-2 hover:text-white hover:bg-blue-600 border hover:border-transparent">Signup</a>
            </div>
        </div>
    </div>

    
</nav>

 <div id="side-menu" class="fixed inset-0 bg-blue-700 bg-opacity-90 overflow-auto z-50 hidden transform -translate-x-full">
    <div class="flex flex-col items-center mt-16">
        <button onclick="toggleMenu()" class="self-end mr-8 text-white focus:outline-none">
            <i class="fas fa-times text-3xl"></i>
        </button>
        <div class="flex flex-col space-y-7">
            <?php
            $page = isset($page) ? $page: 'homepage';

            // Home link
            if ($page === 'homepage'): ?>
                <a href="home.php" class="underline text-white underline-offset-8 font-semibold">Home</a>
            <?php else: ?>
                <a href="home.php" class="hover:underline text-white underline-offset-8 font-semibold">Home</a>
            <?php endif; ?>

            <!-- About link -->
            <?php if ($page === 'about'): ?>
                <a href="about.php" class="underline text-white underline-offset-8 font-semibold">About</a>
            <?php else: ?>
                <a href="about.php" class="hover:underline text-white underline-offset-8 font-semibold">About</a>
            <?php endif; ?>

            <!-- Contact Us link -->
            <?php if ($page === 'contact'): ?>
                <a href="contact.php" class="underline text-white underline-offset-8 font-semibold">Contact Us</a>
            <?php else: ?>
                <a href="contact.php" class="hover:underline text-white underline-offset-8 font-semibold">Contact Us</a>
            <?php endif; ?>

            <!-- FAQs link -->
            <?php if ($page === 'faq'): ?>
                <a href="faq.php" class="underline text-white underline-offset-8 font-semibold">FAQs</a>
            <?php else: ?>
                <a href="faq.php" class="hover:underline text-white underline-offset-8 font-semibold">FAQs</a>
            <?php endif; ?>
        </div>
        <div class="flex flex-col space-y-4 mt-8">
            <a href="login.php" class="border border-white rounded-full font-semibold px-6 py-2 text-center">Log In</a>
            <a href="signup.php" class="bg-white text-blue-600 font-bold rounded-full px-6 py-2 text-center">Register</a>
        </div>
    </div>
</div>

<style>
        /* Transition for the side menu */
        #side-menu {
            transition: transform 0.3s linear;
        }
        #side-menu.hidden {
            transform: translateX(-100%);
        }
        #side-menu.visible {
            transform: translateX(0);
        }
    </style>



