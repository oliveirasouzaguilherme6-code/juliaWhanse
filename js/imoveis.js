const catalogGrid = document.getElementById("catalogGrid");
const tipoFiltro = document.getElementById("tipoFiltro");
const bairroFiltro = document.getElementById("bairroFiltro");
const valorFiltro = document.getElementById("valorFiltro");
const filtrarBtn = document.getElementById("filtrarBtn");

const imovelModal = document.getElementById("imovelModal");
const fecharModal = document.getElementById("fecharModal");
const modalBody = document.getElementById("modalBody");

const imoveis = [
  {
    id: 1,
    titulo: "Casa moderna com fachada marcante",
    tipo: "casa",
    bairro: "Centro",
    valor: 365000,
    quartos: 3,
    banheiros: 2,
    vagas: 2,
    area: 120,
    imagem: "https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=1200&q=80",
    descricao: "Uma casa com presença visual forte, ótimo acabamento e excelente potencial de apresentação."
  },
  {
    id: 2,
    titulo: "Apartamento sofisticado e funcional",
    tipo: "apartamento",
    bairro: "Jardim Santa Nilce",
    valor: 420000,
    quartos: 2,
    banheiros: 2,
    vagas: 1,
    area: 89,
    imagem: "https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1200&q=80",
    descricao: "Ambientes bem distribuídos, aparência refinada e localização estratégica."
  },
  {
    id: 3,
    titulo: "Sobrado espaçoso e elegante",
    tipo: "sobrado",
    bairro: "Lar Paraná",
    valor: 520000,
    quartos: 3,
    banheiros: 3,
    vagas: 2,
    area: 165,
    imagem: "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80",
    descricao: "Uma opção com presença premium, muito espaço e excelente impacto visual."
  },
  {
    id: 4,
    titulo: "Casa clean com boa iluminação",
    tipo: "casa",
    bairro: "Centro",
    valor: 310000,
    quartos: 2,
    banheiros: 2,
    vagas: 2,
    area: 98,
    imagem: "https://images.unsplash.com/photo-1605146769289-440113cc3d00?auto=format&fit=crop&w=1200&q=80",
    descricao: "Casa leve, moderna e muito agradável visualmente."
  }
];

function formatarValor(valor) {
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
    maximumFractionDigits: 0
  }).format(valor);
}

function criarCard(imovel) {
  const card = document.createElement("article");
  card.className = "catalog-card reveal show";
  card.innerHTML = `
    <div class="catalog-image" style="background-image: url('${imovel.imagem}')">
      <span class="catalog-badge">${imovel.tipo}</span>
    </div>
    <div class="catalog-body">
      <h3>${imovel.titulo}</h3>
      <p>${imovel.bairro}</p>

      <div class="catalog-meta">
        <span>${imovel.quartos} quartos</span>
        <span>${imovel.banheiros} banheiros</span>
        <span>${imovel.vagas} vagas</span>
        <span>${imovel.area} m²</span>
      </div>

      <div class="catalog-price">${formatarValor(imovel.valor)}</div>

      <div class="catalog-actions">
        <button class="small-btn small-btn-dark" data-id="${imovel.id}">Ver imóvel</button>
        <a href="contato.html" class="small-btn small-btn-light">Contato</a>
      </div>
    </div>
  `;
  return card;
}

function renderizarImoveis(lista) {
  if (!catalogGrid) return;
  catalogGrid.innerHTML = "";

  if (!lista.length) {
    catalogGrid.innerHTML = `<p>Nenhum imóvel encontrado com esses filtros.</p>`;
    return;
  }

  lista.forEach((imovel) => {
    catalogGrid.appendChild(criarCard(imovel));
  });

  document.querySelectorAll("[data-id]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = Number(btn.dataset.id);
      const imovel = imoveis.find((item) => item.id === id);
      if (imovel) abrirModal(imovel);
    });
  });
}

function abrirModal(imovel) {
  modalBody.innerHTML = `
    <div class="modal-layout">
      <div class="modal-image" style="background-image: url('${imovel.imagem}')"></div>
      <div class="modal-text">
        <span class="pill">${imovel.tipo}</span>
        <h2>${imovel.titulo}</h2>
        <p>${imovel.descricao}</p>

        <div class="modal-data">
          <div><strong>Bairro</strong><br>${imovel.bairro}</div>
          <div><strong>Valor</strong><br>${formatarValor(imovel.valor)}</div>
          <div><strong>Quartos</strong><br>${imovel.quartos}</div>
          <div><strong>Banheiros</strong><br>${imovel.banheiros}</div>
          <div><strong>Vagas</strong><br>${imovel.vagas}</div>
          <div><strong>Área</strong><br>${imovel.area} m²</div>
        </div>

        <a href="contato.html" class="btn btn-gold">Tenho interesse</a>
      </div>
    </div>
  `;
  imovelModal.classList.add("active");
  document.body.style.overflow = "hidden";
}

fecharModal?.addEventListener("click", () => {
  imovelModal.classList.remove("active");
  document.body.style.overflow = "";
});

imovelModal?.addEventListener("click", (e) => {
  if (e.target === imovelModal) {
    imovelModal.classList.remove("active");
    document.body.style.overflow = "";
  }
});

filtrarBtn?.addEventListener("click", () => {
  const tipo = tipoFiltro.value.trim().toLowerCase();
  const bairro = bairroFiltro.value.trim().toLowerCase();
  const valor = Number(valorFiltro.value || 0);

  const filtrados = imoveis.filter((item) => {
    const tipoOk = tipo === "todos" || item.tipo === tipo;
    const bairroOk = !bairro || item.bairro.toLowerCase().includes(bairro);
    const valorOk = !valor || item.valor <= valor;
    return tipoOk && bairroOk && valorOk;
  });

  renderizarImoveis(filtrados);
});

renderizarImoveis(imoveis);