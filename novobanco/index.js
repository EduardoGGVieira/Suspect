const hamburguer = document.querySelector(".barranavmobile");
const navLinks = document.querySelector(".barranavlinks");
hamburguer.addEventListener("click", () => {
  navLinks.classList.toggle("active");
});

const botaologin = document.querySelector(".botaologin");
const tiposlogin = document.querySelector(".tiposlogin");
const tiposlogin1 = document.getElementById("tiposlogin1");
const tiposlogin2 = document.getElementById("tiposlogin2");
const funcaousuario = document.getElementById("funcaousuario");

//Caixa flutuante
botaologin.addEventListener("click", () => {
  botaologin.classList.toggle("active");
  tiposlogin.classList.toggle("active");
});
