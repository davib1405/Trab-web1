const sombrancelhas = ["sadConcerned", "default", "angry"];
const olhos = ["cry", "default", "squint"];
const boca = ["screamOpen", "default", "grimace"];

const avatar = document.getElementById('avatar');
const skinButtons = document.querySelectorAll('.skinCorButton');
const hairButtons = document.querySelectorAll('.hairCorButton');
const sentimento = document.querySelectorAll('.sentimentoInput');
const hairType = document.getElementById('hairType');
const clothesButtons = document.querySelectorAll('.clothesColorButton');
const clotheType = document.getElementById('clotheType');
const saveAvatarButton = document.getElementById('save-avatar');
const dashboard = document.getElementById('dashboard');
const doctorName = document.getElementById('doctor-name');
const feelingAvatars = document.querySelectorAll('.feeling-avatar');
const saveDashButton = document.getElementById('save-dash');
const feedbackForm = document.getElementById('feedback-form');
const saveFeedbackButton = document.getElementById('save-feedback');
const skinColorInput = document.getElementById('skin-color');
const hairColorInput = document.getElementById('hair-color');
const sentimentoInput = document.getElementById('sentimento');
const hairTypeInput = document.getElementById('hair-type');
const clotheTypeInput = document.getElementById('clothe-type');
const clothesColorInput = document.getElementById('clothes-color');

document.addEventListener('DOMContentLoaded', () => {
    selectedSkinColor = document.querySelector('.skinCorButton:checked')?.value || 'ffdbac';
    selectedHairColor = document.querySelector('.hairCorButton:checked')?.value || '000000';
    selectedSentimento = document.querySelector('.sentimentoInput:checked')?.value || 1;
    booleanOculos = 1; // Ajustar se necessário
    selectedHairType = hairType ? hairType.value || "curvy" : "curvy";
    selectedClotheType = clotheType ? clotheType.value || "blazerAndShirt" : "blazerAndShirt";
    selectedClothesColor = document.querySelector('.clothesColorButton:checked')?.value || '3c4f5c';

    // Verificar se os inputs existem antes de acessar seus valores
    if (skinColorInput) {
        selectedSkinColor = skinColorInput.value || 'ffdbac';
    }
    if (hairColorInput) {
        selectedHairColor = hairColorInput.value || '000000';
    }
    if (sentimentoInput) {
        selectedSentimento = sentimentoInput.value || 1;
    }
    if (hairTypeInput) {
        selectedHairType = hairTypeInput.value || "curvy";
    }
    if (clotheTypeInput) {
        selectedClotheType = clotheTypeInput.value || "blazerAndShirt";
    }
    if (clothesColorInput) {
        selectedClothesColor = clothesColorInput.value || '3c4f5c';
    }
    
    updateAvatar();
});

function updateAvatar() {
    if (avatar) {
        const link = `https://api.dicebear.com/9.x/avataaars/svg?accessories=&hairColor=${selectedHairColor}&skinColor=${selectedSkinColor}&mouth=${boca[selectedSentimento]}&eyes=${olhos[selectedSentimento]}&accessoriesProbability=${booleanOculos}&accessoriesColor=262e33&eyebrows=${sombrancelhas[selectedSentimento]}&top=${selectedHairType}&clothing=${selectedClotheType}&clothesColor=${selectedClothesColor}`;
        avatar.src = link;
    }

    const feelingCrying = document.getElementById('feeling-crying');
    const feelingSmiling = document.getElementById('feeling-smiling');
    const feedbackFeelingCrying = document.getElementById('feedback-feeling-crying');
    const feedbackFeelingSmiling = document.getElementById('feedback-feeling-smiling');

    if (feelingCrying) {
        feelingCrying.src = `https://api.dicebear.com/9.x/avataaars/svg?accessories=&hairColor=${selectedHairColor}&skinColor=${selectedSkinColor}&mouth=screamOpen&eyes=cry&accessoriesProbability=1&accessoriesColor=262e33&eyebrows=sadConcerned&top=${selectedHairType}&clothing=${selectedClotheType}&clothesColor=${selectedClothesColor}`;
    }
    if (feelingSmiling) {
        feelingSmiling.src = `https://api.dicebear.com/9.x/avataaars/svg?accessories=&hairColor=${selectedHairColor}&skinColor=${selectedSkinColor}&mouth=default&eyes=default&accessoriesProbability=1&accessoriesColor=262e33&eyebrows=default&top=${selectedHairType}&clothing=${selectedClotheType}&clothesColor=${selectedClothesColor}`;
    }
    if (feedbackFeelingCrying) {
        feedbackFeelingCrying.src = `https://api.dicebear.com/9.x/avataaars/svg?accessories=&hairColor=${selectedHairColor}&skinColor=${selectedSkinColor}&mouth=screamOpen&eyes=cry&accessoriesProbability=1&accessoriesColor=262e33&eyebrows=sadConcerned&top=${selectedHairType}&clothing=${selectedClotheType}&clothesColor=${selectedClothesColor}`;
    }
    if (feedbackFeelingSmiling) {
        feedbackFeelingSmiling.src = `https://api.dicebear.com/9.x/avataaars/svg?accessories=&hairColor=${selectedHairColor}&skinColor=${selectedSkinColor}&mouth=default&eyes=default&accessoriesProbability=1&accessoriesColor=262e33&eyebrows=default&top=${selectedHairType}&clothing=${selectedClotheType}&clothesColor=${selectedClothesColor}`;
    }
}

if (hairType) {
    hairType.addEventListener('change', (event) => {
        selectedHairType = event.target.value;
        updateAvatar();
    });
}
if (clotheType) {
    clotheType.addEventListener('change', (event) => {
        selectedClotheType = event.target.value;
        updateAvatar();
    });
}

sentimento.forEach(input => {
    if (input) {
        input.addEventListener('click', (event) => {
            selectedSentimento = event.target.value;
            updateAvatar();
        });
    }
});

skinButtons.forEach(input => {
    if (input) {
        input.addEventListener('click', (event) => {
            selectedSkinColor = event.target.value;
            updateAvatar();
        });
    }
});

hairButtons.forEach(input => {
    if (input) {
        input.addEventListener('click', (event) => {
            selectedHairColor = event.target.value;
            updateAvatar();
        });
    }
});

clothesButtons.forEach(input => {
    if (input) {
        input.addEventListener('click', (event) => {
            selectedClothesColor = event.target.value;
            updateAvatar();
        });
    }
});

function showSection(section) {
    const sections = document.querySelectorAll('.section');
    sections.forEach(div => {
        div.classList.add('hidden');
    });
    const sectionToShow = document.getElementById(section);
    if (sectionToShow) {
        sectionToShow.classList.remove('hidden');
    }
}

updateAvatar(); 

feelingAvatars.forEach(avatar => {
    if (avatar) {
        avatar.addEventListener('click', (event) => {
            const feeling = event.target.id.split('-')[1]; 
            const feelingOption = document.getElementById(`feeling-${feeling}-option`);
            if (feelingOption) {
                feelingOption.checked = true;
                selectedSentimento = feeling === 'crying' ? 0 : 1;
                updateAvatar();
            }
        });
    }
});
