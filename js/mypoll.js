const carouselTrack = document.getElementById('carouselTrack');
const prevButton = document.getElementById('prevButton');
const nextButton = document.getElementById('nextButton');

const participatedCarouselTrack = document.getElementById('participatedCarouselTrack');
const prevParticipatedButton = document.getElementById('prevParticipatedButton');
const nextParticipatedButton = document.getElementById('nextParticipatedButton');

const invitedCarouselTrack = document.getElementById('invitedCarouselTrack');
const prevInvitedButton = document.getElementById('prevInvitedButton');
const nextInvitedButton = document.getElementById('nextInvitedButton');

let currentIndex = 0;
const cardsVisible = 5;
const cardWidth = carouselTrack.children[0].offsetWidth;
const totalCards = carouselTrack.children.length;

let participatedIndex = 0;
const participatedCardsVisible = 5;
const participatedCardWidth = participatedCarouselTrack.children[0].offsetWidth;
const totalParticipatedCards = participatedCarouselTrack.children.length;

let invitedIndex = 0;
const invitedCardsVisible = 5;
const invitedCardWidth = invitedCarouselTrack.children[0].offsetWidth;
const totalInvitedCards = invitedCarouselTrack.children.length;

nextButton.addEventListener('click', () => {
    if (currentIndex < totalCards - cardsVisible) {
        currentIndex++;
        carouselTrack.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
    }
});

prevButton.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
        carouselTrack.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
    }
});

nextParticipatedButton.addEventListener('click', () => {
    if (participatedIndex < totalParticipatedCards - participatedCardsVisible) {
        participatedIndex++;
        participatedCarouselTrack.style.transform = `translateX(-${participatedIndex * participatedCardWidth}px)`;
    }
});

prevParticipatedButton.addEventListener('click', () => {
    if (participatedIndex > 0) {
        participatedIndex--;
        participatedCarouselTrack.style.transform = `translateX(-${participatedIndex * participatedCardWidth}px)`;
    }
});

nextInvitedButton.addEventListener('click', () => {
    if (invitedIndex < totalInvitedCards - invitedCardsVisible) {
        invitedIndex++;
        invitedCarouselTrack.style.transform = `translateX(-${invitedIndex * invitedCardWidth}px)`;
    }
});

prevInvitedButton.addEventListener('click', () => {
    if (invitedIndex > 0) {
        invitedIndex--;
        invitedCarouselTrack.style.transform = `translateX(-${invitedIndex * invitedCardWidth}px)`;
    }
});