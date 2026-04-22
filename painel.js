if (localStorage.getItem("juliaAdminLogged") !== "true") {
  window.location.href = "login.html";
}

const DEFAULT_IMAGE =
  "https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&fit=crop&w=1200&q=80";

let editingId = null;

const adminList = document.getElementById("adminList");
const formTitle = document.getElementById("formTitle");

const form = {
  title: document.getElementById("title"),
  type: document.getElementById("type"),
  price: document.getElementById("price"),
  neighborhood: document.getElementById("neighborhood"),
  city: document.getElementById("city"),
  code: document.getElementById("code"),
  bedrooms: document.getElementById("bedrooms"),
  bathrooms: document.getElementById("bathrooms"),
  garage: document.getElementById("garage"),
  area: document.getElementById("area"),
  status: document.getElementById("status"),
  highlight: document.getElementById("highlight"),
  description: document.getElementById("description"),
  images: document.getElementById("images")
};

function loadProperties() {
  const saved = localStorage.getItem("juliaHanseProperties");
  return saved ? JSON.parse(saved) : [];
}

function saveProperties(properties) {
  localStorage.setItem("juliaHanseProperties", JSON.stringify(properties));
}

function formatPrice(value) {
  return new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
    maximumFractionDigits: 0
  }).format(Number(value || 0));
}

function typeLabel(type) {
  const map = {
    casa: "Casa",
    apartamento: "Apartamento",
    sobrado: "Sobrado"
  };
  return map[type] || "Imóvel";
}

function resetForm() {
  editingId = null;
  formTitle.textContent = "Cadastrar novo imóvel";
  Object.values(form).forEach((input) => (input.value = ""));
  form.type.value = "casa";
  form.status.value = "disponivel";
  form.highlight.value = "true";
}

function fillForm(property) {
  editingId = property.id;
  formTitle.textContent = "Editar imóvel";
  form.title.value = property.title || "";
  form.type.value = property.type || "casa";
  form.price.value = property.price || "";
  form.neighborhood.value = property.neighborhood || "";
  form.city.value = property.city || "";
  form.code.value = property.code || "";
  form.bedrooms.value = property.bedrooms || 0;
  form.bathrooms.value = property.bathrooms || 0;
  form.garage.value = property.garage || 0;
  form.area.value = property.area || 0;
  form.status.value = property.status || "disponivel";
  form.highlight.value = String(property.highlight);
  form.description.value = property.description || "";
  form.images.value = (property.images || [DEFAULT_IMAGE]).join(", ");
}

function renderAdminList() {
  const properties = loadProperties();
  adminList.innerHTML = "";

  if (!properties.length) {
    adminList.innerHTML = `<div class="empty-state">Nenhum imóvel cadastrado.</div>`;
    return;
  }

  properties
    .slice()
    .reverse()
    .forEach((property) => {
      const item = document.createElement("div");
      item.className = "admin-item";
      item.innerHTML = `
        <div class="admin-item-top">
          <div>
            <h4>${property.title}</h4>
            <p>${typeLabel(property.type)} • ${property.neighborhood} • ${property.city}</p>
          </div>
          <strong>${formatPrice(property.price)}</strong>
        </div>
        <div class="admin-item-actions">
          <button class="tiny-btn tiny-edit" data-edit="${property.id}">Editar</button>
          <button class="tiny-btn tiny-delete" data-delete="${property.id}">Excluir</button>
        </div>
      `;
      adminList.appendChild(item);
    });

  document.querySelectorAll("[data-edit]").forEach((btn) => {
    btn.onclick = () => {
      const properties = loadProperties();
      const property = properties.find((p) => p.id === btn.dataset.edit);
      if (property) fillForm(property);
    };
  });

  document.querySelectorAll("[data-delete]").forEach((btn) => {
    btn.onclick = () => {
      let properties = loadProperties();
      properties = properties.filter((p) => p.id !== btn.dataset.delete);
      saveProperties(properties);
      renderAdminList();
      resetForm();
    };
  });
}

document.getElementById("savePropertyBtn").addEventListener("click", () => {
  let properties = loadProperties();

  const payload = {
    id: editingId || crypto.randomUUID(),
    title: form.title.value.trim(),
    type: form.type.value,
    price: Number(form.price.value || 0),
    neighborhood: form.neighborhood.value.trim(),
    city: form.city.value.trim(),
    code: form.code.value.trim(),
    bedrooms: Number(form.bedrooms.value || 0),
    bathrooms: Number(form.bathrooms.value || 0),
    garage: Number(form.garage.value || 0),
    area: Number(form.area.value || 0),
    status: form.status.value,
    highlight: form.highlight.value === "true",
    description: form.description.value.trim(),
    images: form.images.value.split(",").map((url) => url.trim()).filter(Boolean)
  };

  if (!payload.title || !payload.neighborhood || !payload.city || !payload.price) {
    alert("Preencha título, preço, bairro e cidade.");
    return;
  }

  if (editingId) {
    properties = properties.map((p) => (p.id === editingId ? payload : p));
  } else {
    properties.push(payload);
  }

  saveProperties(properties);
  renderAdminList();
  resetForm();
  alert("Imóvel salvo com sucesso.");
});

document.getElementById("resetFormBtn").addEventListener("click", resetForm);

document.getElementById("logoutBtn").addEventListener("click", () => {
  localStorage.removeItem("juliaAdminLogged");
  window.location.href = "login.html";
});

renderAdminList();
resetForm();