<div class="flex items-center p-10 justify-center">
    <div class="flex flex-col md:flex-row bg-white shadow-lg rounded-2xl overflow-hidden max-w-3xl w-full">

        <div class="flex flex-col items-center justify-center bg-blue-600 text-white p-8 md:w-1/2">
            <div class="text-4xl font-extrabold mb-2">Chooz</div>
            <p class="text-lg font-medium text-center">Simplifying group decisions for everyone.</p>
            <div class="relative mt-6 space-y-3">
                <div class="w-3 h-3 bg-white rounded-full animate-bounce"></div>
                <div class="w-3 h-3 bg-blue-300 rounded-full animate-bounce delay-200"></div>
                <div class="w-3 h-3 bg-white rounded-full animate-bounce delay-400"></div>
            </div>
        </div>

        <div class="flex flex-col justify-center p-8 md:w-1/2">
            <h2 class="text-3xl font-bold text-blue-800 mb-6 text-center">Create an Account</h2>

            <form action="#" method="POST" class="space-y-4">
                <div>
                    <label for="signup-name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="signup-name" name="signup-name" class="w-full border border-gray-300 p-3 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200">
                </div>

                <div>
                    <label for="signup-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="signup-email" name="signup-email" class="w-full border border-gray-300 p-3 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" >
                </div>

                <div>
                    <label for="signup-password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="signup-password" name="signup-password" class="w-full border border-gray-300 p-3 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200">
                </div>

                <div>
                    <label for="confirm-password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" class="w-full border border-gray-300 p-3 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200">
                </div>

                <button type="submit" class="w-full rounded-lg bg-blue-600 text-white font-bold py-3 transition duration-300 hover:bg-blue-700">
                    Sign Up
                </button>
            </form>

            <div class="text-center mt-4">
                <span class="text-sm text-gray-600">Already have an account? </span>
                <a href="login.php" class="text-sm font-semibold text-blue-600 hover:underline">Login</a>
            </div>
        </div>
    </div>
</div>
