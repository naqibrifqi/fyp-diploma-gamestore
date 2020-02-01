const myButton = document.querySelector('.top-button');

myButton.addEventListener('click', () =>{
    topFunction();
});

window.onscroll = () =>{
    scrollFunction();
}

const scrollFunction = () =>{
    if(document.body.scrollTop > 20 || document.documentElement.scrollTop > 20){
        myButton.style.display = "block";
    }else{
        myButton.style.display = "none";
    }
}

const topFunction = () =>{
    window.pageYOffset = 0;
    document.documentElement.scrollTop = 0;
    document.body.scrollTop = 0;
}