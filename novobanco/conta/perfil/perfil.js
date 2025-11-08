// Função para criar a estrutura do perfil do usuário
function criarCardPerfil(usuario) {
    const infoContainer = document.createElement("div");
    infoContainer.classList.add("info-perfil-container"); 

// oq é STRONG??????????

    const nome = document.createElement("p");
    nome.innerHTML = `<strong>Nome:</strong> ${usuario.nome || 'Não informado'}`;
    infoContainer.appendChild(nome);

   
    const email = document.createElement("p");
    email.innerHTML = `<strong>Email:</strong> ${usuario.email || 'Não informado'}`;
    infoContainer.appendChild(email);
    
  
    const cpf = document.createElement("p");
    cpf.innerHTML = `<strong>CPF:</strong> ${usuario.cpf || 'Não informado'}`;
    infoContainer.appendChild(cpf);
    
    
    const telefone = document.createElement("p");
    telefone.innerHTML = `<strong>Telefone:</strong> ${usuario.telefone || 'Não informado'}`;
    infoContainer.appendChild(telefone);
    
 
    const endereco = document.createElement("p");
    endereco.innerHTML = `<strong>Endereço:</strong> ${usuario.endereco || 'Não informado'}`;
    infoContainer.appendChild(endereco);


    const dataNascimento = document.createElement("p");
    dataNascimento.innerHTML = `<strong>Nascimento:</strong> ${usuario.data_nascimento || 'Não informado'}`;
    infoContainer.appendChild(dataNascimento);


      
    const acoesContainer = document.createElement("div");
    acoesContainer.classList.add("acoes-perfil"); 

    // Botão de Edição
    const botaoEditar = document.createElement("button");
    botaoEditar.classList.add("btn-editar");
    const linkEditar = document.createElement("a");
    linkEditar.href = "editar/editarperfil.html?id=" + (usuario.id || ""); 
    linkEditar.textContent = "Editar Perfil";
    botaoEditar.appendChild(linkEditar);
    acoesContainer.appendChild(botaoEditar);


    




    // Botão de Deletar
    const botaoDeletar = document.createElement("button");
    botaoDeletar.classList.add("btn-deletar");
    const linkDeletar = document.createElement("a");
    linkDeletar.href = "editar/deletarperfil.html?id=" + (usuario.id || ""); 
    linkDeletar.textContent = "Deletar Perfil";
    botaoDeletar.appendChild(linkDeletar);
    acoesContainer.appendChild(botaoDeletar);
    
    infoContainer.appendChild(acoesContainer);

    return infoContainer;

}