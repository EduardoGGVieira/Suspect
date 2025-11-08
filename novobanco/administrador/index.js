// Seleciona o contêiner onde os dados dos usuários serão exibidos
const dadosUsuarioContainer = document.querySelector(".dadosusuario");

// Função para buscar e exibir os usuários
fetch("usuarios.php") 
  .then((response) => response.json())
  .then((data) => {
    data.forEach((usuario) => {
      const card = criarCardUsuario(usuario);
      dadosUsuarioContainer.appendChild(card);
    });
  })
  .catch((error) => {
    console.error("Erro ao buscar dados dos usuários:", error);
    const erroMsg = document.createElement("p");
    erroMsg.textContent = "Não foi possível carregar os usuários. Tente novamente mais tarde.";
    dadosUsuarioContainer.appendChild(erroMsg);
  });

// Função para criar um card de usuário
function criarCardUsuario(usuario) {
  const card = document.createElement("div");
  card.classList.add("usuario-card"); 
  
  if (usuario.tipo) {
    card.classList.add(`tipo-${usuario.tipo}`); 
  }

  // Placeholder de Ícone
  const img = document.createElement("img");
  img.alt = `Ícone de ${usuario.tipo.toUpperCase()}`;
  
   switch (usuario.tipo) {
    case 'ong':
      // Ícone para Organização 
      imgSrc = "https://123ecos.com.br/wp-content/uploads/2024/04/ONGs.png"; 
      break;
    case 'vol':
      // Ícone para Voluntário 
      imgSrc = "https://tse1.mm.bing.net/th/id/OIP.IoOZhJH-sJYVfSx36iBe6wHaE7?rs=1&pid=ImgDetMain&o=7&rm=3"; 
      break;
    case 'adm':
      // Ícone para Administrador 
      imgSrc = "https://cdn-icons-png.flaticon.com/512/3135/3135715.png"; 
      break;
  }
  
  img.src = imgSrc;
  // --------------------------------------------------------------------

  // Descrição do usuário
  const descricao = document.createElement("div");
  descricao.classList.add("usuario-descricao"); 

  const nome = document.createElement("p");
  nome.textContent = `Nome: ${usuario.nome || "Usuário Exemplo"}`;

  const email = document.createElement("p");
  email.textContent = `Email: ${usuario.email || "Não informado"}`;

  const tipo = document.createElement("p");
  tipo.innerHTML = `Tipo: <strong>${usuario.tipo.toUpperCase() || "N/A"}</strong>`; 

  // Montar a descrição
  descricao.appendChild(nome);
  descricao.appendChild(email);
  descricao.appendChild(tipo);

  // --- Div para agrupar os botões de ação ---
  const botoesAcao = document.createElement("div");
  botoesAcao.classList.add("usuario-acoes"); 

  // Botão 1: Deletar
  const botaoDeletar = document.createElement("button");
  botaoDeletar.classList.add("botao-deletar"); 
  const linkDeletar = document.createElement("a");
  linkDeletar.href = `deletarperfiladm.html?id=${usuario.id}`; 
  linkDeletar.textContent = "Deletar";
  botaoDeletar.appendChild(linkDeletar);

  botoesAcao.appendChild(botaoDeletar);
  // --------------------------------------------------
  
  // Montar o card final
  card.appendChild(img);
  card.appendChild(descricao);
  card.appendChild(botoesAcao); 
  
  return card;
}