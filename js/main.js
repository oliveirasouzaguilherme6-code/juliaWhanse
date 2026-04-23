const siteHeader = document.getElementById("siteHeader");
const menuBtn = document.getElementById("menuBtn");
const mobileMenu = document.getElementById("mobileMenu");
const cursorGlow = document.querySelector(".cursor-glow");

window.addEventListener("scroll", () => {
  if (window.scrollY > 20) {
    siteHeader?.classList.add("scrolled");
  } else {
    siteHeader?.classList.remove("scrolled");
  }
});

menuBtn?.addEventListener("click", () => {
  mobileMenu?.classList.toggle("active");
});

document.querySelectorAll(".reveal").forEach((el) => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("show");
        }
      });
    },
    { threshold: 0.14 }
  );

  observer.observe(el);
});

window.addEventListener("mousemove", (e) => {
  if (!cursorGlow) return;
  cursorGlow.style.left = `${e.clientX}px`;
  cursorGlow.style.top = `${e.clientY}px`;
});