// para filtro: const btn = document.querySelector('#salvar');
// // btn.addEventListener('click', () => {
// // console.log('clicou');
// });

// seleciona o feed
const feed = document.querySelector(".feed");

fetch("ong.php")
  .then((response) => response.json())
  .then((data) => {
    data.forEach((ong) => {
      const card = criarCardOng(ong);
      feed.appendChild(card);
    });
  })
  .catch((error) => {
    console.error("Erro ao buscar dados da ONG:", error);
  });

// Função para criar um card de animal
function criarCardOng(ong) {
  const card = document.createElement("div");
  card.classList.add("card"); // usa classe do CSS

  // Imagem da ong
  const img = document.createElement("img");
  img.alt = ong.Nome || "LogoOng";
  if (ong.logo) {
    // Converte os dados binários da imagem em base64 para exibição no navegador
    img.src = `data:image/jpeg;base64,${ong.logo}`;
  } else {
    // Caso não haja imagens no banco, exibe uma mensagem
    img.src =
      "https://www.google.com/url?sa=i&url=https%3A%2F%2Ffotografia.folha.uol.com.br%2Fgalerias%2F19208-pac-man-e-as-aventuras-fantasmagoricas&psig=AOvVaw1eyiXrmSnpgdxsU6ODegcg&ust=1761922181319000&source=images&cd=vfe&opi=89978449&ved=0CBIQjRxqFwoTCNiG2t-VzJADFQAAAAAdAAAAABAE";
  }

  // const img = document.createElement("img");
  // img.src =
  //   "https://tse2.mm.bing.net/th/id/OIP.qMrSv3oTCf7SpoRcZA64eQHaHa?rs=1&pid=ImgDetMain&o=7&rm=3";
  // img.alt = ong.Nome || "LogoOng";

  // Descrição da ong
  const descricao = document.createElement("div");
  descricao.classList.add("descriongfeed");

  const nome = document.createElement("p");
  nome.textContent = `Nome: ${ong.Nome || "ONG Exemplo"}`;

  const endereco = document.createElement("p");
  endereco.textContent = `Endereço: ${ong.Endereco || ""}`;

  const contato = document.createElement("p");
  contato.textContent = `Contatos: ${ong.Telefone || ""}`;

  // Botão mais informações
  const maisInfo = document.createElement("button");
  const linkMaisInfo = document.createElement("a");
  linkMaisInfo.href = "detalhes?id=" + (ong.id || "");
  linkMaisInfo.textContent = "Ver mais";
  maisInfo.appendChild(linkMaisInfo);

  // Montar o card
  descricao.appendChild(nome);
  descricao.appendChild(endereco);
  descricao.appendChild(contato);

  card.appendChild(img);
  card.appendChild(descricao);
  card.appendChild(maisInfo);
  return card;
}
