<div class="grid w-full grid-cols-1 md:grid-cols-2 gap-10 py-10 px-6">
    <div class="flex flex-col justify-center">
        <h1 class="text-4xl font-bold text-blue-800 mb-4">Contact Us</h1>
        <p class="text-gray-600 mb-6">
            <strong>Need to get in touch with us?</strong> Either fill out the form with your inquiry or message us on our social media platforms.
        </p>
    </div>

    <div class="bg-white shadow-lg p-6 rounded-lg max-w-lg w-full mx-auto">
        <form action="#" method="POST">
            <div class="grid grid-cols-1 gap-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="firstname" class="block text-gray-700 font-semibold">First Name</label>
                        <input type="text" id="firstname" name="firstname" 
                               class="w-full p-3 border border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" >
                    </div>

                    <div>
                        <label for="lastname" class="block text-gray-700 font-semibold">Last Name</label>
                        <input type="text" id="lastname" name="lastname" 
                               class="w-full p-3 border border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" 
                               required>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-gray-700 font-semibold">Email Address</label>
                    <input type="email" id="email" name="email" 
                           class="w-full p-3 border border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" 
                           required>
                </div>

                <div>
                    <label for="inquiry" class="block text-gray-700 font-semibold">What can we help you with?</label>
                    <textarea id="inquiry" name="inquiry" rows="5" 
                              class="w-full p-3 border border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" 
                              required></textarea>
                </div>

                <div class="flex justify-start">
                    <button type="submit" class="px-6 py-3 bg-blue-700 text-white font-semibold rounded-lg hover:bg-blue-900 transition duration-300">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
