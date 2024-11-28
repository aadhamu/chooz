<div class="p-10 flex items-center justify-center ">
    <div class="flex flex-col md:flex-row bg-white shadow-lg rounded-2xl overflow-hidden max-w-3xl w-full">
        
        <div class="flex items-center justify-center bg-blue-600 md:w-1/2 p-6">
            <img src="path/to/logo.png" alt="Chooz Logo" class="w-32 h-auto">
        </div>

        <div class="flex flex-col justify-center p-8 md:w-1/2">
            <h2 class="text-3xl font-bold text-blue-800 mb-6 text-center">Login to Chooz</h2>

            <form class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="w-full border border-gray-300 p-3 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="w-full border border-gray-300 p-3 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200">
                </div>

                <!-- Forgot Password link -->
                <div class="text-right">
                    <a href="#" class="text-sm text-blue-600 hover:underline">Forgot Password?</a>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg transition duration-300 hover:bg-blue-700">
                    Login
                </button>
            </form>

            <!-- Sign-up link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Don’t have an account? 
                    <a href="signup.php" class="text-blue-600 font-semibold hover:underline">Sign up</a>
                </p>
            </div>
        </div>
    </div>
</div>
