function showLoginModal() {
  document.getElementById("login-modal").classList.remove("hidden");
  document.getElementById("login-modal").classList.add("flex");
}

function hideLoginModal() {
  document.getElementById("login-modal").classList.add("hidden");
  document.getElementById("login-modal").classList.remove("flex");
}

function showRegisterModal() {
  document.getElementById("Register-modal").classList.remove("hidden");
  document.getElementById("Register-modal").classList.add("flex");
}

function hideRegisterModal() {
  document.getElementById("Register-modal").classList.add("hidden");
  document.getElementById("Register-modal").classList.remove("flex");
}

function openRegister() {
  hideLoginModal();
  showRegisterModal();
}

function openLogin() {
  hideRegisterModal();
  showLoginModal();
}

function initScrollAnimations() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("animate-in");

        if (entry.target.classList.contains("movie-card")) {
          const cards =
            entry.target.parentElement.querySelectorAll(".movie-card");
          cards.forEach((card, index) => {
            setTimeout(() => {
              card.classList.add("animate-in");
            }, index * 100);
          });
        }
      }
    });
  }, observerOptions);

  document
    .querySelectorAll(
      ".scroll-animate, .scroll-animate-left, .scroll-animate-right, .scroll-animate-scale"
    )
    .forEach((el) => {
      observer.observe(el);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initScrollAnimations();
    document.documentElement.style.scrollBehavior = 'smooth';
});

