const form = document.getElementById('form');

// Pour Selectionner Tout Les Inputs Et Les Metre Dans Un Tableaux
const inputs = form.querySelectorAll('input');
const allParagraphs = document.querySelectorAll('p');

form.addEventListener('submit', Submit);

function Submit(e) {
    e.preventDefault();
    
    let passwordValue = '';
    inputs.forEach(input => {
        if (input.dataset.type === 'Mdp') {
            passwordValue = input.value.trim();
        }
    });

    inputs.forEach(input => {

        // Username Check
        if (input.dataset.type === 'Nom') {
            const UserRegex = /^[A-Z].{8,}$/;
            if (!UserRegex.test(input.value.trim())) {
                input.nextElementSibling.textContent = `La format du Nom n'est pas valide`;
                allParagraphs[0].className = 'input-group-text text-danger';
                return;
            }else{
                input.nextElementSibling.textContent = `Valid`;
                allParagraphs[0].className = 'input-group-text text-success';
                return;
            }
        }

        // Email Check
        if (input.dataset.type === 'Email') {
            const emailRegex = /^[\w-\.]+@gmail\.com$/;
            if (!emailRegex.test(input.value.trim())) {
                input.nextElementSibling.textContent = `La format de l'e-mail n'est pas valide`;
                allParagraphs.forEach(p => {
                    p.className = 'input-group-text text-danger';
                });
                return;
            }else{
                input.nextElementSibling.textContent = `Valid`;
                allParagraphs[1].className = 'input-group-text text-success';
                return;
            }
        }

        // Password Check
        const TestMdp = /^[A-Z].{8,}$/
        const numberRegex = /\d/;
        if ((input.dataset.type === 'Mdp' && input.value.trim() === '') && !TestMdp.test(input.value.trim())) {
            input.nextElementSibling.textContent = `Le ${input.dataset.type} 8 caractères avec le 1er caractere en majuscule`;
            allParagraphs[2].className = 'input-group-text text-danger';
            return;
        }else if(input.dataset.type === 'Mdp' && !numberRegex.test(input.value.trim())){
            input.nextElementSibling.textContent = `le Mdp doit contenir 8 caractere et au moins un chiffre`;
            allParagraphs[2].className = 'input-group-text text-danger';
            return;
        }else if(input.dataset.type === 'Mdp'){
            const spaceRegex = /\s/;
            if (spaceRegex.test(input.value.trim())) {
                input.nextElementSibling.textContent = `${input.dataset.type} ne doit pas contenir d'espaces`;
                allParagraphs[2].className = 'input-group-text input-group-text text-danger';
                return;
            }else{
                input.nextElementSibling.textContent = `Valid`;
                allParagraphs[2].className = 'input-group-text text-success';
                return;
            }
        }



        // Password Confirmation Check
        if (input.dataset.type === 'Mdp Confirmation') {
            if (input.value.trim() !== passwordValue && input.value.trim() == '') {
                input.nextElementSibling.textContent = `Le Mdp ne correspondent pas`;
                allParagraphs[3].className = 'input-group-text text-danger';
                return;
            }else if(input.value.trim() == ''){
                input.nextElementSibling.textContent = ``;
                return;
            }else if(input.value.trim() == passwordValue){
                input.nextElementSibling.textContent = `Valid`;
                allParagraphs[3].className = 'input-group-text text-success';
                return;
            }
            
        }else if(input.dataset.type === 'Mdp Confirmation'){
            const spaceRegex = /\s/;
            if (spaceRegex.test(input.value.trim())) {
                input.nextElementSibling.textContent = `${input.dataset.type} ne doit pas contenir d'espaces`;
                allParagraphs[3].className = 'input-group-text text-danger';
                return;
            }
        }

        input.nextElementSibling.textContent = "";
    });
}