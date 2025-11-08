// Seleciona a div onde os cards de campanha serão exibidos
const mostraCampanhaContainer = document.querySelector(".mostracampanha");

// Função para buscar e exibir as campanhas da ONG logada
fetch("campanha.php") // Você precisará criar este arquivo PHP
  .then((response) => {
    if (!response.ok) {
        throw new Error(`Erro ao buscar campanhas: ${response.statusText}`);
    }
    return response.json();
  })
  .then((data) => {
    if (data.erro) {
      mostraCampanhaContainer.innerHTML = `<p style="color: red;">❌ Erro ao carregar campanhas: ${data.erro}</p>`;
      return;
    }
    
    if (data.length === 0) {
      mostraCampanhaContainer.innerHTML = '<p>📢 Você ainda não publicou nenhuma campanha. Use o formulário para começar!</p>';
      return;
    }

    data.forEach((campanha) => {
      const card = criarCardCampanha(campanha);
      mostraCampanhaContainer.appendChild(card);
    });
  })
  .catch((error) => {
    console.error("Erro ao buscar dados da campanha:", error);
    mostraCampanhaContainer.innerHTML = '<p style="color: red;">Falha na comunicação com o servidor de campanhas.</p>';
  });

// Função para criar um card de campanha (Modelo similar ao de ONG/Usuário)
function criarCardCampanha(campanha) {
  const card = document.createElement("div");
  // Usaremos a classe 'campanha-card' para estilização
  card.classList.add("campanha-card"); 

  // Imagem da campanha
  const img = document.createElement("img");
  img.alt = campanha.nome_campanha || "Foto da Campanha";
  
  if (campanha.foto_campanha) {
    // Converte os dados binários da imagem em base64 para exibição
    img.src = `data:image/jpeg;base64,${campanha.foto_campanha}`;
  } else {
    // Placeholder se não houver imagem
    img.src = "https://cdn-icons-png.flaticon.com/512/3208/3208743.png"; 
  }

  // Div de Descrição
  const descricao = document.createElement("div");
  descricao.classList.add("campanha-descricao"); 

  const nome = document.createElement("h3");
  nome.textContent = campanha.nome_campanha || "Nome da Campanha";

  const dataPublicacao = document.createElement("p");
  // Formata a data (se o campo data_publicacao for válido)
  const dataFormatada = campanha.data_publicacao 
    ? new Date(campanha.data_publicacao).toLocaleDateString('pt-BR', { dateStyle: 'medium' })
    : 'Não informada';
  dataPublicacao.textContent = `Publicado em: ${dataFormatada}`;

  const observacoes = document.createElement("p");
  // Limita o texto da descrição para a visualização no card
  const descricaoCurta = campanha.descricao_campanha.length > 100 
    ? campanha.descricao_campanha.substring(0, 100) + '...' 
    : campanha.descricao_campanha;
  observacoes.textContent = `Descrição: ${descricaoCurta || "Sem descrição."}`;

  // Botão de Ação (Ver Detalhes, Editar, etc.)
  const botaoAcao = document.createElement("button");
  const linkAcao = document.createElement("a");
  // Link para uma página de detalhes, se existir, passando o ID da campanha
  linkAcao.href = "detalhes_campanha.html?id=" + (campanha.id || ""); 
  linkAcao.textContent = "Ver Detalhes"; 
  botaoAcao.appendChild(linkAcao);

  // Montar o card
  descricao.appendChild(nome);
  descricao.appendChild(dataPublicacao);
  descricao.appendChild(observacoes);

  card.appendChild(img);
  card.appendChild(descricao);
  card.appendChild(botaoAcao);
  
  return card;
}