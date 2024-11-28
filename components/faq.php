<div class="max-w-3xl mx-auto py-12 px-4 md:px-0">
    <h1 class="text-4xl font-bold text-blue-800 mb-6 text-center">Frequently Asked Questions</h1>
    <p class="text-gray-600 mb-10 text-center">
        Find answers to common questions about using Chooz for voting, tracking results, and staying informed.
    </p>

    <div class="space-y-6">
        <div x-data="{ open: false }" class="border border-gray-300 rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-all duration-200">
            <button @click="open = !open" class="w-full text-left p-5 font-semibold text-blue-800 hover:bg-gray-100 focus:outline-none flex justify-between items-center">
                <span>How do I register to vote on Chooz?</span>
                <svg :class="{'transform rotate-180': open}" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" class="p-4 text-gray-700  font-semibold bg-gray-50">
                To register to vote, create an account on Chooz using your email and set up a secure password. Once registered, you’ll have access to all voting features and updates.
            </div>
        </div>

        <div x-data="{ open: false }" class="border border-gray-300 rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-all duration-200">
            <button @click="open = !open" class="w-full text-left p-5 font-semibold text-blue-800 hover:bg-gray-100 focus:outline-none flex justify-between items-center">
                <span>Is my vote anonymous?</span>
                <svg :class="{'transform rotate-180': open}" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" class="p-4 font-semibold text-gray-700 bg-gray-50">
            The poll creator decides if a poll is anonymous or not. When setting up a poll, they can choose to keep votes anonymous for privacy or allow identifiable votes for transparency. Before voting, you’ll see whether the poll is anonymous or not so you can make an informed choice.
            </div>
        </div>

        <div x-data="{ open: false }" class="border border-gray-300 rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-all duration-200">
            <button @click="open = !open" class="w-full text-left p-5 font-semibold text-blue-800 hover:bg-gray-100 focus:outline-none flex justify-between items-center">
                <span>How can I view real-time election results?</span>
                <svg :class="{'transform rotate-180': open}" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" class="p-4 font-semibold  text-gray-700 bg-gray-50">
                You can view election results on the Chooz platform by navigating to the "Results" section. Results are updated in real-time as votes are counted, so you can stay informed on the progress.
            </div>
        </div>

        <div x-data="{ open: false }" class="border border-gray-300 rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-all duration-200">
            <button @click="open = !open" class="w-full text-left p-5 font-semibold text-blue-800 hover:bg-gray-100 focus:outline-none flex justify-between items-center">
                <span>Can I vote for multiple candidates?</span>
                <svg :class="{'transform rotate-180': open}" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" class="p-4 font-semibold text-gray-700 bg-gray-50">
                Voting for multiple candidates depends on the polling rules set by the organizers. If allowed, you’ll see the option to select more than one candidate on the voting page.
            </div>
        </div>

        <div x-data="{ open: false }" class="border border-gray-300 rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-all duration-200">
            <button @click="open = !open" class="w-full text-left p-5 font-semibold text-blue-800 hover:bg-gray-100 focus:outline-none flex justify-between items-center">
                <span>What should I do if I encounter an issue while voting?</span>
                <svg :class="{'transform rotate-180': open}" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" class="p-4 font-semibold text-gray-700 bg-gray-50">
                If you experience any issues while voting, please contact our support team via email or through our social media channels. Our team will assist you as soon as possible.
            </div>
        </div>
    </div>
</div>
