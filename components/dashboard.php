<section class="ml-10">
   <h1 class="text-blue-800 font-bold text-3xl mb-2">Hello, Juan!</h1>
   <p class='text-gray-500'>Welcome To chooz online voting platform</p>

   <div class="flex">
    <!-- LEFT CORNER -->
    <div>
        <div class="w-11/12 mb-5 border mt-5 space-x-5 flex bg-white p-5 rounded-lg shadow-md justify-between items-center">
            <div class="flex flex-col space-y-5">
                <h3 class=" text-medium font-semibold text-blue-800">Ongoing Elections</h3>
                <h1 class="text-2xl font-bold text-blue-800">President Student Council</h1>
                <button class="mt-auto mx-auto px-4 py-2 text-blue-500 border-2 border-blue-500 rounded-3xl transition w-7/12">Vote now</button>
            </div>
            <img src="assets/polling-phone.png" alt="polling-phone" class="w-40 rounded-lg ">
        </div>

        <div class="w-11/12 mb-5 border mt-5 bg-white p-5 rounded-lg shadow-md ">
            <h3 class="text-medium font-semibold text-blue-800">Live Result</h3>
            <div>
                <div class="w-7/12 mx-auto flex items-center justify-between">
                    <span class="text-blue-800"><i class="fas fa-chevron-left"></i></span>
                    <h3 class="font-bold text-blue-800">President Student Council</h3>
                    <span class="text-blue-800 font-bold"><i class="fas fa-chevron-right"></i></span>
                </div>

                    <!-- Do a graph to show design between all candidate -->
                <div class="space-y-6 mt-7 ">
                    <div class="flex space-x-10 items-center justify-between">
                        <p class="text-lg">Candidate</p>
                        <div class="relative w-80 bg-gray-200 h-6 rounded-full">
                            <div class="bg-blue-600 h-full rounded-full" style="width: 70%;"></div> 
                            <span class="absolute right-0 top-0 text-sm text-gray-800 pr-2">70%</span>
                        </div>
                    </div>

                    <div class="flex space-x-10 items-center justify-between">
                        <p class="text-lg">Candidate</p>
                        <div class="relative w-80 bg-gray-200 h-6 rounded-full">
                            <div class="bg-green-600 h-full rounded-full" style="width: 40%;"></div>
                            <span class="absolute right-0 top-0 text-sm text-gray-800 pr-2">40%</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-10 justify-between">
                        <p class="text-lg">Candidate</p>
                        <div class="relative w-80 bg-gray-200 h-6 rounded-full">
                            <div class="bg-red-600 h-full rounded-full" style="width: 90%;"></div> <!-- 90% progress -->
                            <span class="absolute right-0 top-0 text-sm text-gray-800 pr-2">90%</span>
                        </div>
                    </div>

                    <div class="relative mt-2 ml-28">
                        <div class="flex justify-between border m1-56 w-80 px-2">
                            <span class="text-xs">0</span>
                            <span class="text-xs">20</span>
                            <span class="text-xs">40</span>
                            <span class="text-xs">60</span>
                            <span class="text-xs">80</span>
                            <span class="text-xs">100</span>
                        </div>
                        <div class="absolute left-0 top-0 h-6 border-l-2 border-gray-600"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-11/12 mb-5 border mt-s bg-white p-5 rounded-lg shadow-md">
            <h3 class="text-medium font-semibold text-blue-800">Election Activities</h3>
            <div class="flex w-9/12 justify-between mb-5 text-blue-800 font-semibold">
                <div class="flex items-center space-x-2">
                    <div class="rounded-full w-3 h-3 bg-blue-500"></div>
                    <p>Ongoing</p>
                </div>
                <div class="flex items-center space-x-2" >
                    <div class="rounded-full w-3 h-3  bg-blue-700"></div>
                    <p>Pending</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="rounded-full w-3 h-3  bg-blue-900"></div>
                    <p>Concluded</p>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center p-2">
                    <p class="font-semibold truncate w-2/3 text-blue-800">President Student Council</p>
                    <div class="rounded-full w-3 h-3 bg-blue-500"></div>
                    <p class="truncate text-blue-800 w-1/3 text-right font-semibold">12/22/2022</p>
                </div>
                <div class="flex justify-between items-center p-2">
                    <p class="font-semibold truncate w-2/3 text-blue-800">Vice President Student Council</p>
                    <div class="rounded-full w-3 h-3 bg-blue-700"></div>
                    <p class="truncate w-1/3 text-blue-800 text-right font-semibold">Pending</p>
                </div>
                <div class="flex justify-between items-center p-2">
                    <p class="font-semibold truncate w-2/3 text-blue-800">Secretary Student Council</p>
                    <div class="rounded-full w-3 h-3 bg-blue-900"></div>
                    <p class="truncate w-1/3 text-blue-800 text-right font-semibold">Concluded</p>
                </div>
            </div>

        </div>
    </div>

      <!-- RIGHT CORNER -->
      <div>
        <div class="mb-5 border mt-5 bg-white p-5 rounded-lg shadow-md w-96 h-52">
            <h3 class="text-medium mb-2 font-semibold text-blue-800">Calendar</h3>
            <div class="flex justify-between items-center space-x-10">
                <div class="text-blue-800 font-bold border-b-2 border-blue-800 pb-1">Today</div>  
                <div class="text-gray-400 font-bold">Next week</div>  
                <div class="text-gray-400 font-bold">This month</div>  
            </div>
            <div class="flex space-x-5 mt-2">
                <h1 class="mb-2 font-semibold text-blue-800">December 22</h1>
                <h1 class="text-medium mb-2 font-semibold text-blue-800">President Student Council</h1>
            </div>
        </div>
        <div class="w-96 h-96 mb-5 border mt-s bg-white p-5 rounded-lg shadow-md">
            <h3 class=" text-medium font-semibold text-blue-800">Voting Process</h3>
            <div class="flex flex-col items-center space-y-6 h-full py-5 overflow-y-scroll customer-scrollbar">

                <div class="flex flex-col items-center">
                    <div class="relative w-24">
                        <svg class="transform -rotate-90" viewBox="0 0 100 100">
                            <!-- Background Circle -->
                            <circle cx="50" cy="50" r="45" class="text-gray-300" fill="none" stroke="currentColor" stroke-width="10"></circle>
                            <!-- Foreground Circle -->
                            <circle cx="50" cy="50" r="45" class="text-blue-500" fill="none" stroke="currentColor" 
                                    stroke-width="10" stroke-dasharray="283" stroke-dashoffset="0"></circle>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center text-blue-800 font-bold text-lg">
                            100%
                        </div>
                    </div>
                    <p class="text-gray-700 font-medium mt-2 text-center">Total number of registered voters</p>
                    <p class="text-sm text-gray-500">362 voters</p>
                </div>

                <div class="flex flex-col items-center">
                    <div class="relative w-24 ">
                        <svg class="transform -rotate-90" viewBox="0 0 100 100">
                            <!-- Background Circle -->
                            <circle cx="50" cy="50" r="45" class="text-gray-300" fill="none" stroke="currentColor" stroke-width="10"></circle>
                            <!-- Foreground Circle -->
                            <circle cx="50" cy="50" r="45" class="text-green-500" fill="none" stroke="currentColor" 
                                    stroke-width="10" stroke-dasharray="283" stroke-dashoffset="14.15"></circle>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center text-green-800 font-bold text-lg">
                            95%
                        </div>
                    </div>
                    <p class="text-gray-700 font-medium mt-2 text-center">Total number of voters</p>
                    <p class="text-sm text-gray-500">344 voters</p>
                </div>

                <div class="flex flex-col items-center">
                    <div class="relative w-24">
                        <svg class="transform -rotate-90" viewBox="0 0 100 100">
                            <!-- Background Circle -->
                            <circle cx="50" cy="50" r="45" class="text-gray-300" fill="none" stroke="currentColor" stroke-width="10"></circle>
                            <!-- Foreground Circle -->
                            <circle cx="50" cy="50" r="45" class="text-purple-500" fill="none" stroke="currentColor" 
                                    stroke-width="10" stroke-dasharray="283" stroke-dashoffset="0"></circle>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center text-purple-800 font-bold text-lg">
                            100%
                        </div>
                    </div>
                    <p class="text-gray-700 font-medium mt-2 text-center">Total number of registered candidates</p>
                    <p class="text-sm text-gray-500">362 candidates</p>
                </div>
            </div>
        </div>

      </div>
   </div>
</section>
