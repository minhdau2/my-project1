// Common JavaScript functions used across all pages

// Login modal functions
function showLoginModal() {
    document.getElementById('login-modal').classList.remove('hidden');
    document.getElementById('login-modal').classList.add('flex');
}

function hideLoginModal() {
    document.getElementById('login-modal').classList.add('hidden');
    document.getElementById('login-modal').classList.remove('flex');
}

// Login function
function login(event) {
    event.preventDefault();
    alert('Đăng nhập thành công! (Demo)');
    hideLoginModal();
}

// Scroll animation observer
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                
                if (entry.target.classList.contains('movie-card')) {
                    const cards = entry.target.parentElement.querySelectorAll('.movie-card');
                    cards.forEach((card, index) => {
                        setTimeout(() => {
                            card.classList.add('animate-in');
                        }, index * 100);
                    });
                }
            }
        });
    }, observerOptions);

    document.querySelectorAll('.scroll-animate, .scroll-animate-left, .scroll-animate-right, .scroll-animate-scale').forEach(el => {
        observer.observe(el);
    });
}

// Format currency
function formatCurrency(amount) {
    return amount.toLocaleString() + ' VNĐ';
}

// Generate booking ID
function generateBookingId() {
    return 'CB' + Date.now();
}

// Show success modal
function showSuccessModal(title, message, callback) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4 text-center">
            <div class="text-6xl mb-4">✅</div>
            <h3 class="text-2xl font-bold mb-4 text-green-600">${title}</h3>
            <p class="text-gray-600 mb-6">${message}</p>
            <button onclick="this.parentElement.parentElement.remove(); ${callback ? callback + '()' : ''}" 
                    class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition-colors">
                Đóng
            </button>
        </div>
    `;
    document.body.appendChild(modal);
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initScrollAnimations();
    document.documentElement.style.scrollBehavior = 'smooth';
});