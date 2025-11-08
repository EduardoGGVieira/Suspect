
// seleciona o feed
const feed = document.querySelector(".feed");

fetch("buscar_animais.php")
  .then((response) => response.json())
  .then((data) => {
    data.forEach((animal) => {
      const card = criarCardAnimal(animal);
      feed.appendChild(card);
    });
  })
  .catch((error) => {
    console.error("Erro ao buscar os dados dos animais disponiveis:", error);
  });

  
// Função para criar um card de animal
function criarCardAnimal(animal) {
  const card = document.createElement("div");
  card.classList.add("animal");

  const img = document.createElement("img");
  if (animal.foto && animal.foto.trim() !== "") {
    let fotoCaminho = animal.foto.trim();
    if (!fotoCaminho.startsWith("http")) {
      fotoCaminho = "http://localhost/suspect/novobanco/postar/uploads/animais/" + fotoCaminho;
    }
    img.src = fotoCaminho;
  } else {
    img.src = "https://media.istockphoto.com/id/1296353835/pt/foto/group-of-many-farm-animals-standing-together.jpg";
  }

  const descricao = document.createElement("div");
  descricao.classList.add("nomeanimal");

  const nome = document.createElement("h3");
  nome.textContent = `Nome: ${animal.nome || ""}`;

  const idadeElement = document.createElement("p");
  idadeElement.textContent = `Idade: ${animal.idade || ""}`;

  const racaElement = document.createElement("p");
  racaElement.textContent = `Raça: ${animal.raca || ""}`;

  const VerMais = document.createElement("button");
  const linkVermais = document.createElement("a");
  linkVermais.href = "detalhes?id=" + (animal.id || "");
  linkVermais.textContent = "Ver mais";
  VerMais.appendChild(linkVermais);

  const patas = document.createElement("button");
  const link4patas = document.createElement("a");
  link4patas.textContent = "ONG";

  if (animal.id_ong) {
    link4patas.href = "../ong/detalhes/index.html?id=" + animal.id_ong;
  } else {
    link4patas.href = "/suspect/novobanco/ong/";
  }
  patas.appendChild(link4patas);

  // Montar o card
  descricao.appendChild(nome);
  descricao.appendChild(idadeElement);
  descricao.appendChild(racaElement);
  card.appendChild(img);
  card.appendChild(descricao);
  card.appendChild(VerMais);
  card.appendChild(patas);

// Botão de Editar e Deletar
  fetch("../conta/verificarlogin.php")
    .then((response) => response.json())
    .then((data) => {
      if ((data.tipo === "ong" && animal.id_ong == data.id) || data.tipo === "adm") {
        const editarBtn = document.createElement("button");
        editarBtn.textContent = "✏️ Editar";
        editarBtn.onclick = () => editarAnimal(animal);

        const deletarBtn = document.createElement("button");
        deletarBtn.textContent = "🗑️ Deletar";
        deletarBtn.onclick = () => deletarAnimal(animal.id);

        card.appendChild(editarBtn);
        card.appendChild(deletarBtn);
      }
    });

  return card;
}
