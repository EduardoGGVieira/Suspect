const corpo = document.querySelector(".corpo");

// Normaliza URL: adiciona https:// se o usuário salvou sem protocolo
function ensureUrl(url) {
  if (!url) return null;
  url = url.trim();
  // se já começa com http:// ou https:// ou //, retorna como está
  if (/^(https?:)?\/\//i.test(url)) return url;
  // caso tenha apenas 'www...' ou 'example.com', prefixa com https://
  return "https://" + url;
}

fetch("maisdetalhes.php")
  .then((response) => response.json())
  .then((data) => {
    // Pega o ID da ONG a partir da URL
    const urlParams = new URLSearchParams(window.location.search);
    const ongId = urlParams.get("id");
    data.forEach((ong) => {
      if (ong.id === ongId) {
        const cabecalho = criarCabecalhoOng(ong);
        const descricao = criarDescricaoOng(ong);
        const informacoes = criarInformacoesOng(ong);
        // Montar o corpo
        corpo.appendChild(cabecalho);
        corpo.appendChild(descricao);
        corpo.appendChild(informacoes);
      }
    });
  });

function criarCabecalhoOng(ong) {
  //cabecalho
  const cabecalho = document.createElement("div");
  cabecalho.classList.add("cabecalho");

  const imgCabecalho = document.createElement("img");
  imgCabecalho.alt = ong.nome || "LogoOng";
  if (ong.logo) {
    // Converte os dados binários da imagem em base64 para exibição no navegador
    imgCabecalho.src = `data:image/jpeg;base64,${ong.logo}`;
  } else {
    // Caso não haja imagens no banco, exibe uma mensagem
    imgCabecalho.src =
      "https://www.google.com/url?sa=i&url=https%3A%2F%2Ffotografia.folha.uol.com.br%2Fgalerias%2F19208-pac-man-e-as-aventuras-fantasmagoricas&psig=AOvVaw1eyiXrmSnpgdxsU6ODegcg&ust=1761922181319000&source=images&cd=vfe&opi=89978449&ved=0CBIQjRxqFwoTCNiG2t-VzJADFQAAAAAdAAAAABAE";
  }

  const h1Cabecalho = document.createElement("h1");
  h1Cabecalho.textContent = ong.nome || "Nome da Ong";

  cabecalho.appendChild(imgCabecalho);
  cabecalho.appendChild(h1Cabecalho);

  return cabecalho;
}

function criarDescricaoOng(ong) {
  //descricao
  const descricao = document.createElement("div");
  descricao.classList.add("descricao");

  const pDescricao = document.createElement("p");
  pDescricao.textContent = ong.descricao || "Descrição da ONG";

  descricao.appendChild(pDescricao);
  return descricao;
}

function criarInformacoesOng(ong) {
  //informacoes
  const informacoes = document.createElement("div");
  informacoes.classList.add("informacoes");

  const caixadeinformacoes = document.createElement("div");
  caixadeinformacoes.classList.add("caixadeinformacoes");

  const nome = document.createElement("p");
  nome.textContent = `Nome: ${ong.nome || "Ong"}`;

  const cnpj = document.createElement("p");
  cnpj.textContent = `CNPJ: ${ong.cnpj || "99999999999999"}`;

  const endereco = document.createElement("p");
  endereco.textContent = `Endereço: ${ong.endereco || "Aqui"}`;

  const telefone = document.createElement("p");
  telefone.textContent = `Telefone: ${ong.telefone || "4199999999"}`;

  const financeiro = document.createElement("p");
  financeiro.textContent = `Para doações: ${ong.financeiro || "Pix"}`;

  const link = document.createElement("p");
  link.textContent = "Links: ";
  const aLink = document.createElement("a");
  // usa ensureUrl para garantir que o href seja absoluto (com protocolo)
  const href = ensureUrl(ong.link) || "../Home.html";
  aLink.href = href;
  aLink.target = "_blank"; // abre em nova aba
  aLink.rel = "noopener noreferrer"; // segurança quando target _blank
  aLink.textContent = ong.nome_link || "Link";
  link.appendChild(aLink);

  caixadeinformacoes.appendChild(nome);
  caixadeinformacoes.appendChild(cnpj);
  caixadeinformacoes.appendChild(endereco);
  caixadeinformacoes.appendChild(telefone);
  caixadeinformacoes.appendChild(financeiro);
  caixadeinformacoes.appendChild(link);

  informacoes.appendChild(caixadeinformacoes);
  return informacoes;
}
