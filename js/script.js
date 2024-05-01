const RegLinkbtn = document.querySelector('.RegLink-btn');
const LoginLinkbtn = document.querySelector('.LoginLink-btn');
const container = document.querySelector('.container');

RegLinkbtn.addEventListener("click", () =>{
    container.classList.toggle('active');
    document.getElementById('.container').style.height = "1000px";
});

LoginLinkbtn.addEventListener("click", () =>{
    container.classList.toggle('active');
});

