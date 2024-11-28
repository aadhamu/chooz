<section class="ml-10">
    <div class="flex justify-between items-center mb-7">
        <h1 class="text-blue-800 font-bold text-2xl">YOU MAY NOW CAST YOUR VOTES!</h1>
        <button id="rules-btn" class="text-white py-1 px-3 border-blue-800 bg-blue-800 border-2 rounded-3xl">Rules</button>
        <select name="" id="" class="w-5/12 p-2 border-2 rounded-lg border-blue-800">
            <option value="">Choose who to vote</option>
            <option value="">Presidential Election</option>
            <option value="">School system</option>
        </select>
    </div>
  <div>
    <div class="flex flex-col  justify-center items-center">
        <h2 class="font-bold text-2xl text-blue-800">President Student Council</h2>
        <p class="text-gray-400 font-semibold">You can vote for one candidate</p>
    </div>
  
    <div class="grid grid-cols-3 gap-6 mt-5" id="candidates">
        
    </div>
  </div>
  <div id="rules-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white rounded-lg w-1/2 p-5 space-y-5">
      <h2 class="text-blue-800 text-xl font-bold">Voting Rules</h2>
      <ul class="list-disc pl-5 space-y-2 text-gray-600">
        <li>You can only vote once.</li>
        <li>Ensure you review all candidates before casting your vote.</li>
        <li>Voting closes at 5 PM sharp.</li>
        <li>Votes are confidential.</li>
      </ul>
      <button id="close-modal" class="text-white bg-blue-800 px-4 py-2 rounded-lg">Close</button>
    </div>
  </div>
  <div id="candidate-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center mx-auto">
    <div class="bg-white rounded-lg w-7/12 p-5 space-y-5">
        <div class="flex items-center space-x-5">
            <img id="candidate-image" src="" alt="" class="rounded-full w-44 h-44 object-cover">
            <div>
                <p class="text-blue-600">Vote</p>
                <h2 id="candidate-name" class="text-2xl text-blue-800 font-bold"></h2>
                <div class="mb-3">
                    <span class="text-blue-600">for</span>
                    <span id="candidate-position" class="text-xl text-blue-800 font-semibold"></span>
                </div>
                <div class="flex items-center space-x-4">
                    <i class="fas fa-graduation-cap text-blue-800 text-xl"></i>
                    <span id="candidate-field" class="text-blue-800 font-semibold"></span>
                </div>
                <div class="flex items-center space-x-4 mt-2">
                    <i class="fas fa-user text-blue-800 text-xl"></i>
                    <span id="candidate-age" class="text-blue-800 font-semibold"></span>
                </div>
            </div>
        </div>
        <div>
            <h1 class="text-xl text-blue-800 font-bold mb-4">Campaign Platform</h1>
            <div class="px-4">
                <ol id="candidate-platform" class="list-decimal text-blue-800 font-semibold"></ol>
            </div>
        </div>
        <div class="flex justify-center">
            <button class="rounded-3xl text-white text-xl w-3/12 bg-blue-800 p-3">Vote</button>
        </div>
        <button id="close-candidate-modal" class="text-white bg-blue-800 px-4 py-2 rounded-lg">Close</button>
    </div>
</div>
   <form action="" method="post" class="flex justify-center items-center mt-3">
     <input type="submit" value="Participate in Another Vote" class="rounded-3xl text-white text-lg bg-blue-800 p-3">
   </form>
</section>

<script>
let rulesModal=document.getElementById('rules-modal')
const candidates = [
    {
        name: "Felisa Monteverde",
        image: "assets/friends-collaborating.jpg",
        position: "PRESIDENT STUDENT COUNCIL",
        field: "System Development",
        age: 22,
        campaignPlatform: [
            "Increase student involvement in the Student Council: I will strive to make the student council more accessible and open to all students by holding open forums and making the student council more visible.",
            "Improve Communication: I will work to improve the availability of information about Student Council activities and events, as well as ensure that students are kept informed of upcoming initiatives and activities.",
            "Foster Collaboration: I will work to bring together student organizations and clubs to foster collaboration, as well as create opportunities for students to take part in meaningful activities outside of the classroom.",
            "Strengthen Relationships: I will work to strengthen relationships between the student body and faculty, as well as the student body and the wider community."
        ]
    },
    {
        name: "Emma Ainsley Zamora",
        image: "assets/friends-interacting.jpg",
        position: "PRESIDENT STUDENT COUNCIL",
        field: "Web Development",
        age: 21,
        campaignPlatform: [
            "Increase opportunities for students to learn web development through workshops and hackathons.",
            "Enhance communication between tech enthusiasts and the student council.",
            "Establish a mentorship program connecting students with alumni in the tech industry.",
            "Encourage collaboration on web projects that can benefit the student community."
        ]
    },
    {
        name: "Lorenzo Agustin",
        image: "assets/friends-collaborating.jpg",
        position: "PRESIDENT STUDENT COUNCIL",
        field: "Animation",
        age: 20,
        campaignPlatform: [
            "Promote arts and animation clubs through greater visibility and support.",
            "Introduce animation workshops to inspire creative expression among students.",
            "Create a platform for showcasing student animations to a broader audience.",
            "Foster collaboration between creative teams for interdisciplinary projects."
        ]
    },
    {
        name: "Kasey Rachel Flores",
        image: "assets/friends-interacting.jpg",
        position: "PRESIDENT STUDENT COUNCIL",
        field: "System Development",
        age: 23,
        campaignPlatform: [
            "Implement technological solutions to streamline student council processes.",
            "Introduce coding bootcamps to enhance technical skills within the student body.",
            "Strengthen ties between students in technology and other fields for diverse collaboration.",
            "Organize tech fairs to exhibit innovative student projects."
        ]
    }
];



function removeRulesModal(){
    rulesModal.classList.add('hidden')
}
function removeCandidateModal(){
    candidateModal.classList.add('hidden')
}
function showRulesModal(){
    rulesModal.classList.remove('hidden')
}
function showCandidateModal(){
    candidateModal.classList.remove('hidden')
}
// Toggling rules modal
document.getElementById('rules-btn').addEventListener('click',showRulesModal);
document.getElementById('close-modal').addEventListener('click', removeRulesModal);
document.getElementById('rules-modal').addEventListener('click', removeRulesModal)


const candidatesContainer = document.getElementById("candidates");
candidates.forEach((candidate, index) => {
    candidatesContainer.innerHTML += `
        <div class="w-full p-3 space-y-3 flex flex-col justify-center items-center bg-gradient-to-b from-blue-200 to-white rounded-lg">
            <img src="${candidate.image}" alt="${candidate.name}" class="rounded-full w-40 h-40 object-cover">
            <h2 class="font-bold text-xl text-blue-800">${candidate.name}</h2>
            <p class="font-semibold text-sm text-gray-400">${candidate.field}</p>
            <div class="flex space-x-3 justify-between items-center w-full">
                <button class="rounded-3xl text-white w-6/12 bg-blue-800 p-3">Vote</button>
                <button data-index="${index}" class="view-details rounded-3xl text-blue-800 w-6/12 bg-white border-2 border-blue-800 p-3">View Details</button>
            </div>
        </div>
    `;
});

const candidateModal = document.getElementById("candidate-modal");
const candidateName = document.getElementById("candidate-name");
const candidateImage = document.getElementById("candidate-image");
const candidateField = document.getElementById("candidate-field");
const candidateAge = document.getElementById("candidate-age");
const candidatePosition = document.getElementById("candidate-position");
const platformList = document.getElementById("candidate-platform");

document.getElementById("close-candidate-modal").addEventListener("click", removeCandidateModal);

document.getElementById("candidates").addEventListener("click", (e) => {
    if (e.target.classList.contains("view-details")) {
        const index = e.target.dataset.index;
        const candidate = candidates[index];

        candidateName.textContent = candidate.name;
        candidateImage.src = candidate.image;
        candidateField.textContent = candidate.field;
        candidateAge.textContent = `${candidate.age} years old`;
        candidatePosition.textContent = candidate.position;

        platformList.innerHTML = "";

        const maxInitialItems = 2;
        let visibleItems = candidate.campaignPlatform.slice(0, maxInitialItems);
        let remainingItems = candidate.campaignPlatform.slice(maxInitialItems);

        visibleItems.forEach((point) => {
            const li = document.createElement("li");
            li.textContent = point;
            platformList.appendChild(li);
        });

        if (remainingItems.length > 0) {
            const seeMoreButton = document.createElement("button");
            seeMoreButton.textContent = "See More ";
            seeMoreButton.classList.add("text-blue-800", "bg-white", "underline", "rounded-3xl", "p-2");
            seeMoreButton.addEventListener("click", () => {
                remainingItems.forEach((point) => {
                    const li = document.createElement("li");
                    li.textContent = point;
                    platformList.appendChild(li);
                });
                seeMoreButton.remove();
            });

            platformList.appendChild(seeMoreButton);
        }

        showCandidateModal();
    }
});

// candidateModal.addEventListener('click', removeCandidateModal);
</script>