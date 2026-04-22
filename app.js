const DEFAULT_IMAGE =
  "https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&fit=crop&w=1200&q=80";

const seedProperties = [
  {
    id: crypto.randomUUID(),
    title: "Casa moderna com excelente fachada",
    type: "casa",
    price: 365000,
    neighborhood: "Centro",
    city: "Campo Mourão - PR",
    code: "JH-001",
    bedrooms: 3,
    bathrooms: 2,
    garage: 2,
    area: 120,
    status: "disponivel",
    highlight: true,
    description: "Casa moderna com excelente acabamento e ótima localização.",
    images: [
      "https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=1200&q=80"
    ]
  },
  {
    id: crypto.randomUUID(),
    title: "Apartamento elegante e bem localizado",
    type: "apartamento",
    price: 420000,
    neighborhood: "Jardim Santa Nilce",
    city: "Campo Mourão - PR",
    code: "JH-002",
    bedrooms: 2,
    bathrooms: 2,
    garage: 1,
    area: 90,
    status: "disponivel",
    highlight: true,
    description: "Apartamento com visual moderno, ótima iluminação e excelente distribuição.",
    images: [
      "https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1200&q=80"
    ]
  },
  {
    id: crypto.randomUUID(),
    title: "Sobrado espaçoso com garagem coberta",
    type: "sobrado",
    price: 520000,
    neighborhood: "Lar Paraná",
    city: "Campo Mourão - PR",
    code: "JH-003",
    bedrooms: 3,
    bathrooms: 3,
    garage: 2,
    area: 165,
    status: "reservado",
    highlight: false,
    description: "Sobrado amplo, moderno e ideal para famílias que buscam mais espaço.",
    images: [
      "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80"
    ]
  }
];

function loadProperties() {
  const saved = localStorage.getItem("juliaHanseProperties");
  if (saved) return JSON.parse(saved);
  localStorage.setItem("juliaHanseProperties", JSON.stringify(seedProperties));
  return seedProperties;
}

function formatPrice(value) {
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
    maximumFractionDigits: 0
  }).format(Number(value || 0));
}

function sanitizeImages(images) {
  if (!images || !images.length) return [DEFAULT_IMAGE];
  return images.filter(Boolean);
}

function getStatusLabel(status) {
  const map = {
    disponivel: "Disponível",
    reservado: "Reservado",
    vendido: "Vendido"
  };
  return map[status] || "Disponível";
}

function typeLabel(type) {
  const map = {
    casa: "Casa",
    apartamento: "Apartamento",
    sobrado: "Sobrado"
  };
  return map[type] || "Imóvel";
}

function createPropertyCard(property) {
  const image = sanitizeImages(property.images)[0];
  const article = document.createElement("article");
  article.className = "property-card";
  article.innerHTML = `
    <div class="property-image" style="background-image: linear-gradient(rgba(0,0,0,0.14), rgba(0,0,0,0.14)), url('${image}')">
      <span class="property-badge">${property.code || "Sem código"}</span>
      <span class="property-status status-${property.status}">${getStatusLabel(property.status)}</span>
    </div>
    <div class="property-body">
      <span class="property-type">${typeLabel(property.type)}</span>
      <h3 class="property-title">${property.title}</h3>
      <p class="property-location">${property.neighborhood} • ${property.city}</p>
      <div class="property-meta">
        <span>${property.bedrooms || 0} quartos</span>
        <span>${property.bathrooms || 0} banheiros</span>
        <span>${property.garage || 0} vagas</span>
        <span>${property.area || 0} m²</span>
      </div>
      <div class="property-price">${formatPrice(property.price)}</div>
      <div class="property-actions">
        <button class="chip" data-view="${property.id}">Ver mais</button>
        <a class="chip chip-dark" target="_blank" href="https://wa.me/5544999999999?text=Olá!%20Tenho%20interesse%20no%20imóvel%20${encodeURIComponent(property.title)}">WhatsApp</a>
      </div>
    </div>
  `;
  return article;
}

function renderGrid(gridId, list) {
  const grid = document.getElementById(gridId);
  if (!grid) return;
  grid.innerHTML = "";
  if (!list.length) {
    grid.innerHTML = `<div class="empty-state">Nenhum imóvel encontrado.</div>`;
    return;
  }
  list.forEach((property) => grid.appendChild(createPropertyCard(property)));
}

function openPropertyModal(property) {
  const modal = document.getElementById("propertyModal");
  const content = document.getElementById("propertyModalContent");
  if (!modal || !content) return;

  const images = sanitizeImages(property.images);
  content.innerHTML = `
    <div class="view-grid">
      <div class="view-gallery">
        <div class="view-main" id="viewMain" style="background-image: url('${images[0]}')"></div>
      </div>

      <div class="view-panel">
        <span class="property-type">${typeLabel(property.type)}</span>
        <h2 class="view-title">${property.title}</h2>
        <div class="view-price">${formatPrice(property.price)}</div>
        <p class="view-location">${property.neighborhood} • ${property.city}</p>

        <div class="view-meta">
          <div><strong>Código:</strong><br>${property.code || "—"}</div>
          <div><strong>Status:</strong><br>${getStatusLabel(property.status)}</div>
          <div><strong>Quartos:</strong><br>${property.bedrooms || 0}</div>
          <div><strong>Banheiros:</strong><br>${property.bathrooms || 0}</div>
          <div><strong>Garagem:</strong><br>${property.garage || 0}</div>
          <div><strong>Metragem:</strong><br>${property.area || 0} m²</div>
        </div>

        <p class="view-description">${property.description || "Sem descrição cadastrada."}</p>

        <div class="view-actions">
          <a class="btn btn-primary" target="_blank" href="https://wa.me/5544999999999?text=Olá!%20Tenho%20interesse%20no%20imóvel%20${encodeURIComponent(property.title)}">Falar no WhatsApp</a>
        </div>
      </div>
    </div>
  `;

  modal.classList.add("active");
  document.body.classList.add("no-scroll");
}

function bindViewButtons(properties) {
  document.querySelectorAll("[data-view]").forEach((btn) => {
    btn.onclick = () => {
      const property = properties.find((p) => p.id === btn.dataset.view);
      if (property) openPropertyModal(property);
    };
  });
}

function initMenu() {
  const menuToggle = document.getElementById("menuToggle");
  const mobilePanel = document.getElementById("mobilePanel");

  if (menuToggle && mobilePanel) {
    menuToggle.addEventListener("click", () => {
      mobilePanel.classList.toggle("active");
    });
  }
}

function initModalClose() {
  const modal = document.getElementById("propertyModal");
  const closeBtn = document.getElementById("closePropertyModal");

  if (closeBtn && modal) {
    closeBtn.addEventListener("click", () => {
      modal.classList.remove("active");
      document.body.classList.remove("no-scroll");
    });

    modal.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.classList.remove("active");
        document.body.classList.remove("no-scroll");
      }
    });
  }
}

function initHome() {
  const properties = loadProperties();
  renderGrid("featuredGrid", properties.filter((p) => p.highlight).slice(0, 3));
  bindViewButtons(properties);
}

function initImoveisPage() {
  const properties = loadProperties();
  renderGrid("allPropertiesGrid", properties);
  bindViewButtons(properties);

  const searchBtn = document.getElementById("searchBtn");
  if (!searchBtn) return;

  searchBtn.addEventListener("click", () => {
    const type = document.getElementById("filterType").value;
    const neighborhood = document.getElementById("filterNeighborhood").value.trim().toLowerCase();
    const minPrice = Number(document.getElementById("filterMinPrice").value || 0);
    const maxPrice = Number(document.getElementById("filterMaxPrice").value || 0);

    const filtered = properties.filter((property) => {
      const typeOk = type === "todos" || property.type === type;
      const neighborhoodOk =
        !neighborhood ||
        `${property.neighborhood} ${property.city}`.toLowerCase().includes(neighborhood);
      const minOk = !minPrice || Number(property.price) >= minPrice;
      const maxOk = !maxPrice || Number(property.price) <= maxPrice;

      return typeOk && neighborhoodOk && minOk && maxOk;
    });

    renderGrid("allPropertiesGrid", filtered);
    bindViewButtons(properties);
  });
}

initMenu();
initModalClose();
initHome();
initImoveisPage();