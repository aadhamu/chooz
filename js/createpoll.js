const groupVote = document.getElementById('groupVoting ');
const prepaidPackages = document.getElementById('prepaidPackages');
const perVoteOption = document.getElementById('perVoteOption');
const optionsContainer = document.getElementById('pollOptions');
const createPoll = document.querySelector('.create-poll');

let creatorPaysOptions = document.getElementById('creatorPaysOptions');
let voterPaysInput = document.getElementById('voterPaysInput');

const simplePollInputs = document.getElementById('simplePollInputs');
const pollOptions = document.getElementById('pollOptions');
const addOptionButton = document.getElementById('addOptionButton');

function showStep(step) {
    const steps = document.querySelectorAll('.step');
    steps.forEach((el) => el.classList.add('hidden'));
    document.getElementById(`step${step}`).classList.remove('hidden');
}

function addUsernameOption() {

    const newOption = document.createElement('div');
    newOption.classList.add('poll-option', 'border', 'p-6', 'rounded-lg', 'mb-6', 'bg-gray-50', 'shadow-md');

    newOption.innerHTML = `
        <label class="block text-sm font-medium text-gray-700">Enter a Username for the Poll Option</label>
        <input type="text" name="option_username[]" class="block w-full border-2 p-4 rounded-lg mt-2 focus:ring-2 focus:ring-blue-500" placeholder="Enter username" >
    `;

    optionsContainer.appendChild(newOption);
}

function addUsernameOption() {
    const pollOptionsContainer = document.getElementById('pollOptions');
    const newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.name = 'option_username[]';
    newInput.classList.add("font-bold", "w-full", "border-2", "p-4", "mt-2", "border-gray-300", "rounded-lg", "bg-blue-100", "focus:outline-none", "focus:border-blue-500","transition", "duration-200", "text-gray-800", "mb-2");
    newInput.placeholder = 'Poller username';
    pollOptionsContainer.appendChild(newInput);
}

document.querySelectorAll('input[name="creator_payment_type"]').forEach(radio => {
radio.addEventListener('change', function() {
    
    perVoteOption.classList.add('hidden');
    prepaidPackages.classList.add('hidden');

    if(this.value === 'bulk_voting'){
        prepaidPackages.classList.remove('hidden');
    }else if(this.value === 'per_vote'){
        perVoteOption.classList.remove('hidden');
    }
})
})

document.querySelectorAll('input[name="paid_voting"]').forEach(radio => {
radio.addEventListener('change', function() {
    const freeVote = document.getElementById('freeVote');
    
    creatorPaysOptions.classList.add('hidden');
    voterPaysInput.classList.add('hidden');

    if (this.value === 'poll-participant') {
        perVoteOption.classList.add('hidden');
        prepaidPackages.classList.add('hidden');
        
        voterPaysInput.classList.remove('hidden');
    } else if (this.value === 'poll-creator') {
        perVoteOption.classList.add('hidden');
        prepaidPackages.classList.add('hidden');
        creatorPaysOptions.classList.remove('hidden');
    } else if (this.value === 'free_vote') {

        perVoteOption.classList.add('hidden');
        prepaidPackages.classList.add('hidden');
    }
});
});

function togglePollInputs() {
    const pollType = document.querySelector('input[name="pollStructure"]:checked').value;


    if (pollType === 'simplePoll') {
        simplePollInputs.classList.remove('hidden');
        pollOptions.classList.add('hidden');
        addOptionButton.classList.add('hidden');
    } else {
        simplePollInputs.classList.add('hidden');
        pollOptions.classList.remove('hidden');
        addOptionButton.classList.remove('hidden');
    }
}

function addSimplePollOption() {
    const simplePollOptionsContainer = document.getElementById('simplePollOptions');
    const newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.name = 'poll_option[]';
    newInput.classList.add("font-bold", "w-full", "border-2", "p-4", "mt-2", "border-gray-300", "rounded-lg", "bg-blue-100", "focus:outline-none", "focus:border-blue-500", "transition", "duration-200", "text-gray-800");
    newInput.placeholder = 'Polling Option';
    simplePollOptionsContainer.appendChild(newInput);
}

let currentStep = 1;

function navigateCarousel(direction) {
    const totalSteps = document.querySelectorAll('.carousel-step').length;

    if (direction === 'next' && currentStep < totalSteps) {
        currentStep++;
    } else if (direction === 'prev' && currentStep > 1) {
        currentStep--; 
    }

    showCarouselSteps(currentStep);
}

function showCarouselSteps(stepNumber) {
    const allSteps = document.querySelectorAll('.carousel-step');
    allSteps.forEach(step => step.classList.add('hidden'));
    document.getElementById(`carouselstep${stepNumber}`).classList.remove('hidden');
}

function closePollDetailsModal() {
    document.getElementById('pollDetailsModal').classList.add('hidden');
}

createPoll.addEventListener('click', function(event) {

    const pollDetailsModal = document.getElementById('pollDetailsModal');
    const formTitleValue= document.getElementById('pollTitle').value
    const formDescriptionValue= document.getElementById('pollDescription').value
    
    document.getElementById('pollHeader').textContent = formTitleValue ;
    document.getElementById('pollBody').textContent = formDescriptionValue;

    const pollVotingButtons = document.querySelectorAll('input[name="voting_method"]');
    const pollMethodSpan = document.getElementById('pollMethod');

    pollVotingButtons.forEach(radio => {
        if (radio.checked) {
            pollMethodSpan.textContent=radio.value
        }
    });

    const pollPaymentRadioButtons = document.querySelectorAll('input[name="paid_voting"]');
    const pollPaymentSpan = document.getElementById('pollPayment');

    pollPaymentRadioButtons.forEach(radio => {
        if (radio.checked) {
            pollPaymentSpan.textContent=radio.value;
        }
    });

    const singleVoteButtons = document.querySelectorAll('input[name="voting_type"]');
    const singleVoteSpan = document.getElementById('pollType');

    singleVoteButtons.forEach(radio => {
        if (radio.checked) {
            singleVoteSpan.textContent=radio.value;
        }
    });
    
    const pollVisibilityButtons = document.querySelectorAll('input[name="poll_visibility"]');
    const pollVisibilitySpan = document.getElementById('pollVisibility');
    
    pollVisibilityButtons.forEach(radio => {
        if (radio.checked) {
            pollVisibilitySpan.textContent=radio.value;
        }
    });
    
    const anonymousPoll = document.querySelector('input[name="anonymous_poll"]')
    const pollAnonymousSpan = document.getElementById('pollAnonymous');
    
    if(anonymousPoll.checked){
        pollAnonymousSpan.textContent='True'
    }else{
        pollAnonymousSpan.textContent='False'
    }
    
    const pollCategorySpan = document.getElementById('pollCategories');
    const pollCategory=document.querySelector('#pollCategory')
    
    pollCategorySpan.textContent=pollCategory.value


    const pollStartDate = document.querySelector('input[name="start_date"]')
    const pollStartDateSpan=document.getElementById('pollStartDate')

    pollStartDateSpan.textContent=pollStartDate.value.replace("T",' ')

    const pollEndDate = document.querySelector('input[name="end_date"]')
    const pollEndDateSpan=document.getElementById('pollEndDate')
    pollEndDateSpan.textContent=pollEndDate.value.replace("T", ' ')
    
    const pollType = document.querySelectorAll('input[name="pollStructure"]')
    pollType.forEach((radio)=>{
        if(radio.value === 'simplePoll' && radio.checked){
            const simplePollQuestion=document.getElementById('simple_poll_question')
            document.getElementById('pollQuestionSpan').textContent=simplePollQuestion.value

            const simplePollOptions = document.querySelectorAll('input[name="poll_option[]"]');
            const optionsContainer = document.getElementById('pollOptionsCont');
           
            simplePollOptions.forEach(option => {
                console.log(option.value);
                
                optionsContainer.insertAdjacentHTML('beforeend', `<li>${option.value}</li>`);
            });

        }else if(radio.value === 'detailedPoll' && radio.checked){
            document.getElementById('Pollers').textContent ="Poll Username"
            
            const detailedPollQuestion=document.getElementById('detail_poll_question').value
            document.getElementById('pollQuestionSpan').textContent=detailedPollQuestion

            const detailPollOptions = document.querySelectorAll('input[name="option_username[]"]');
            const optionsContainer = document.getElementById('pollOptionsCont');
           
            detailPollOptions.forEach(option => {
                console.log(option.value);
                
                optionsContainer.insertAdjacentHTML('beforeend', `<li>${option.value}</li>`);
            });
            
        }

    })


    
   
    
    pollDetailsModal.classList.remove('hidden');
    
    showCarouselSteps(1);
});

window.addEventListener('load', function() {
    
    if (document.querySelector('input[name="paid_voting"]:checked')) {
        const selectedValue = document.querySelector('input[name="paid_voting"]:checked').value;
        if (selectedValue === 'poll-participant') {
            voterPaysInput.classList.remove('hidden');
        } else if (selectedValue === 'poll-creator') {
            creatorPaysOptions.classList.remove('hidden');
        }else if (selectedValue === 'free_vote') {
            voterPaysInput.classList.add('hidden');
            creatorPaysOptions.classList.add('hidden');
            prepaidPackages.classList.add('hidden')
        }
    }

    if (document.querySelector('input[name="creator_payment_type"]:checked')) {
        const selectedValue = document.querySelector('input[name="creator_payment_type"]:checked').value;
        if (selectedValue === 'bulk_voting') {
            prepaidPackages.classList.remove('hidden');
        }
    }

    if (document.querySelector('input[name="pollStructure"]:checked')) {
        const pollTypes = document.querySelector('input[name="pollStructure"]:checked').value;

    if (pollTypes === 'simplePoll') {
        simplePollInputs.classList.remove('hidden');
        pollOptions.classList.add('hidden');
        addOptionButton.classList.add('hidden');
    } else {
        simplePollInputs.classList.add('hidden');
        pollOptions.classList.remove('hidden');
        addOptionButton.classList.remove('hidden');
    }
}
});