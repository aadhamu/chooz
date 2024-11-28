<form action="" method="post" class="space-y-4">

    <!-- Poll Title -->
    <div>
        <label for="pollTitle" class="block text-sm font-medium text-gray-700">Poll Title</label>
        <input type="text" id="pollTitle" name="pollTitle" class="mt-1 block w-full border-2 p-2 rounded-xl" placeholder="Enter poll title" required>
    </div>

    <!-- Poll Description -->
    <div>
        <label for="pollDescription" class="block text-sm font-medium text-gray-700">Poll Description</label>
        <textarea id="pollDescription" name="pollDescription" class="mt-1 block w-full border-2 p-2 rounded-xl" rows="3" placeholder="Provide a description for the poll"></textarea>
    </div>

    <!-- Poll Type -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Poll Type</label>
        <div class="space-x-4">
            <input type="radio" id="singleChoice" name="pollType" value="single" required>
            <label for="singleChoice">Single Choice</label>
            
            <input type="radio" id="multipleChoice" name="pollType" value="multiple">
            <label for="multipleChoice">Multiple Choice</label>
        </div>
    </div>

    <!-- Poll Options -->
    <div>
        <label for="pollOptions" class="block text-sm font-medium text-gray-700">Poll Options</label>
        <div id="pollOptions">
            <input type="text" name="option[]" class="mt-1 block w-full border-2 p-2 rounded-xl" placeholder="Enter option" required>
            <input type="text" name="option[]" class="mt-1 block w-full border-2 p-2 rounded-xl" placeholder="Enter option" required>
            <!-- Add more options dynamically as needed -->
        </div>
        <button type="button" onclick="addOption()">Add Another Option</button>
    </div>

    <!-- Allow Voters to Vote Once or Multiple Votes -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Allow Voters to Vote Multiple Times</label>
        <input type="radio" id="voteOnce" name="voteType" value="once" required>
        <label for="voteOnce">Once</label>
        
        <input type="radio" id="voteMultiple" name="voteType" value="multiple">
        <label for="voteMultiple">Multiple Votes</label>
    </div>

    <!-- Vote Cost -->
    <div>
        <label for="voteCost" class="block text-sm font-medium text-gray-700">Vote Cost (if applicable)</label>
        <input type="number" id="voteCost" name="voteCost" class="mt-1 block w-full border-2 p-2 rounded-xl" placeholder="Enter vote cost (e.g., $1)" min="0">
    </div>

    <!-- Vote Limit -->
    <div>
        <label for="voteLimit" class="block text-sm font-medium text-gray-700">Vote Limit per User</label>
        <input type="number" id="voteLimit" name="voteLimit" class="mt-1 block w-full border-2 p-2 rounded-xl" placeholder="Maximum number of votes per user" min="1" value="1">
    </div>

    <!-- Voting Deadline -->
    <div>
        <label for="voteDeadline" class="block text-sm font-medium text-gray-700">Voting Deadline</label>
        <input type="datetime-local" id="voteDeadline" name="voteDeadline" class="mt-1 block w-full border-2 p-2 rounded-xl">
    </div>

    <!-- Visibility Settings -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Poll Visibility</label>
        <input type="checkbox" id="visibility" name="visibility">
        <label for="visibility">Show Results After Voting</label>
    </div>

    <button type="submit" class="w-full bg-blue-800 text-white p-3 rounded-3xl">Create Poll</button>
</form>

<script>
    function addOption() {
        const optionsContainer = document.getElementById('pollOptions');
        const newOption = document.createElement('input');
        newOption.type = 'text';
        newOption.name = 'option[]';
        newOption.classList.add('mt-1', 'block', 'w-full', 'border-2', 'p-2', 'rounded-xl');
        newOption.placeholder = 'Enter option';
        optionsContainer.appendChild(newOption);
    }
</script>
