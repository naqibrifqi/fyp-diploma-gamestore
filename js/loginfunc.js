const userInput = document.querySelector('.login-user');

userInput.addEventListener('focus', function(){
    userInput.classList.toggle('form-control');
    userInput.classList.toggle('form-control-on');
});

userInput.addEventListener('focusout', function(){
    userInput.classList.toggle('form-control');
    userInput.classList.toggle('form-control-on');
});

const passInput = document.querySelector('.pass-user');

passInput.addEventListener('focus', function(){
    passInput.classList.toggle('form-control');
    passInput.classList.toggle('form-control-on');
});

passInput.addEventListener('focusout', function(){
    passInput.classList.toggle('form-control');
    passInput.classList.toggle('form-control-on');
});