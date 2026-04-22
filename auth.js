const loginForm = document.getElementById("loginForm");

const ADMIN_EMAIL = "admin@juliahanse.com";
const ADMIN_PASSWORD = "123456";

if (loginForm) {
  loginForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    if (email === ADMIN_EMAIL && password === ADMIN_PASSWORD) {
      localStorage.setItem("juliaAdminLogged", "true");
      window.location.href = "painel.html";
    } else {
      alert("E-mail ou senha inválidos.");
    }
  });
}