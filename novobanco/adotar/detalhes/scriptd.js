const corpo = document.querySelector(".corpo");

fetch("buscar_dados.php")
  .then((response) => response.json())
  .then((data) => {
    // Pega o ID do animal a partir da URL
      const urlParams = new URLSearchParams(window.location.search);
      const animalId = urlParams.get("id"); // Busca o ID
      
    // Encontra o animal correspondente ao ID na URL
    const animal = data.find(a => a.id === animalId);
    
    if (animal) {
      const cabecalho = criarCabecalhoAnimal(animal);
      const informacoes = criarInformacoesAnimal(animal);
      // Montar o corpo
      corpo.appendChild(cabecalho);
      corpo.appendChild(informacoes);
    } else {
        corpo.innerHTML = "<h1>Animal não encontrado.</h1>";
    }
  });

function criarCabecalhoAnimal(animal) {
  //cabecalho
  const cabecalho = document.createElement("div");
  cabecalho.classList.add("titulo"); 

  const h1Cabecalho = document.createElement("h1");
  h1Cabecalho.textContent = `Detalhes`;

  cabecalho.appendChild(h1Cabecalho);

  return cabecalho;
}

// A função que cria a caixa de informações do animal, usando os campos de 'animal_encontrado'
function criarInformacoesAnimal(animal) {
  //informacoes
  const informacoes = document.createElement("div");
  informacoes.classList.add("animal"); 

  const img = document.createElement("img");
  if (animal.foto && animal.foto.trim() !== "") {
    let fotoCaminho = animal.foto.trim();

    if (!fotoCaminho.startsWith("http")) {
      // Adiciona o caminho base correto do servidor
      fotoCaminho = "http://localhost/suspect/novobanco/postar/uploads" + fotoCaminho;
    }
  
    img.src = fotoCaminho;
  } else {
    // Imagem padrão caso não tenha foto
    img.src = "https://tse3.mm.bing.net/th/id/OIP.y70ibdagkVuPw8uWG-t_twHaJw?rs=1&pid=ImgDetMain&o=7&rm=3";
  }

  const infoDiv = document.createElement("div");
  infoDiv.classList.add("info");

  const nome = document.createElement("h3");
  nome.textContent = animal.nome || "Nome do animal";

  const idade = document.createElement("p");
  idade.textContent = `Idade: ${animal.idade || "Não informado"}`;

  const raca = document.createElement("p");
  raca.textContent = `Raça: ${animal.raca || "Não informado"}`;

  const localizacao = document.createElement("p");
  localizacao.textContent = `Localização: ${animal.localizacao || "Não informado"}`; 
  
  const sexo = document.createElement("p");
  sexo.textContent = `Sexo: ${animal.sexo || "Não informado"}`;

  const altura = document.createElement("p");
  altura.textContent = `Altura: ${animal.altura || "Não informado"}`;

  const peso = document.createElement("p");
  peso.textContent = `Peso: ${animal.peso || "Não informado"}`;

  const vacinas = document.createElement("p");
  vacinas.textContent = `Vacinas: ${animal.vacinas || "Não informado"}`;

  const estado = document.createElement("p");
  estado.textContent = `Estado: ${animal.estado || "Não informado"}`;
  
  const saude = document.createElement("p");
  saude.textContent = `Estado de saúde: ${animal.estado_saude || "Não informado"}`;

  const castrado = document.createElement("p");
  castrado.textContent = `Castração: ${animal.castrado || "Não informado"}`;
  
  const observacoes = document.createElement("p");
  observacoes.textContent = `Observação: ${animal.observacoes || "Nenhuma observação."}`;

  
  // Botão Voltar
  const linkVoltar = document.createElement("a");
  const voltarButton = document.createElement("button");
  linkVoltar.href = "/suspect/Adotar/adotar.html";
  linkVoltar.textContent = "Voltar";
  voltarButton.appendChild(linkVoltar);

  // Segundo Botão
  const patas = document.createElement("button");
  const link4patas = document.createElement("a");
  link4patas.href = "../ong/detalhes/index.html?id=" + (animal.id || "");
  link4patas.textContent = "4patas";
  patas.appendChild(link4patas); 
  
  
  // Monta a div de informações
  infoDiv.appendChild(nome);
  infoDiv.appendChild(idade);
  infoDiv.appendChild(raca);
  infoDiv.appendChild(localizacao);
  infoDiv.appendChild(sexo);
  infoDiv.appendChild(altura);
  infoDiv.appendChild(peso);
  infoDiv.appendChild(vacinas);
  infoDiv.appendChild(estado);
  infoDiv.appendChild(saude);
  infoDiv.appendChild(castrado);
  infoDiv.appendChild(observacoes);

  // Monta o corpo principal do card
  informacoes.appendChild(img);
  informacoes.appendChild(infoDiv);
  informacoes.appendChild(voltarButton);
  
  return informacoes;
}
