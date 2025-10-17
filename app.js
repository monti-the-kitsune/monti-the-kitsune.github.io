document.addEventListener("DOMContentLoaded", () => {
    console.log("app.js loaded (single init)");

    // Theme toggle
    const toggleButton = document.getElementById("themeToggle");
    const body = document.body;
    if (toggleButton) {
        const savedTheme = localStorage.getItem("theme");
        if (savedTheme === "light") {
            body.classList.add("light-mode");
            toggleButton.textContent = "🌙";
        } else {
            toggleButton.textContent = "☀️";
        }
        toggleButton.addEventListener("click", () => {
            const isLight = body.classList.toggle("light-mode");
            toggleButton.textContent = isLight ? "🌙" : "☀️";
            localStorage.setItem("theme", isLight ? "light" : "dark");
            console.log("Theme toggled:", isLight ? "light" : "dark");
        });
    } else {
        console.warn("No #themeToggle button found");
    }

    // Add to Cart forms
    const addToCartForms = document.querySelectorAll('form:not(.removeForm):not(#checkoutForm)');
    console.log("Add to Cart forms:", addToCartForms.length);
    addToCartForms.forEach((form, index) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(form);
            try {
                const response = await fetch(form.action, { method: 'POST', body: formData });
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                const result = await response.json();
                showToast(result.message || (result.success ? 'Item added to cart!' : 'Failed to add item'));
            } catch (err) {
                console.error("Add to cart error:", err);
                showToast("Error adding item: " + err.message);
            }
        });
    });

    // Remove from cart forms
    const removeForms = document.querySelectorAll(".removeForm");
    removeForms.forEach((form) => {
        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            const formData = new FormData(form);
            try {
                const response = await fetch("remove_from_cart.php", { method: "POST", body: formData });
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                const result = await response.json();
                if (result.success) {
                    showToast("Item removed from cart!");
                    const productEl = form.closest(".productElement");
                    if (productEl) productEl.remove();
                    const productsContainer = document.querySelector(".products");
                    if (productsContainer && !document.querySelector(".productElement")) {
                        productsContainer.innerHTML = '<p class="subtitle2">Your cart is empty.</p>';
                        const checkoutForm = document.querySelector(".checkoutForm");
                        if (checkoutForm) checkoutForm.style.display = "none";
                    }
                } else {
                    showToast("Error: " + (result.message || "Could not remove item"));
                }
            } catch (err) {
                console.error("Remove error:", err);
                showToast("Error removing item: " + err.message);
            }
        });
    });

    // Checkout form
    const checkoutForm = document.getElementById("checkoutForm");
    if (checkoutForm) {
        checkoutForm.addEventListener("submit", async (event) => {
            event.preventDefault();
            try {
                const response = await fetch("checkout.php", { method: "POST" });
                if (!response.ok) {
                    const text = await response.text();
                    console.error("Checkout response:", text);
                    throw new Error(`HTTP ${response.status}`);
                }
                const result = await response.json();
                if (result.success) {
                    showToast("Checkout successful! Your cart has been cleared.");
                    const productsContainer = document.querySelector(".products");
                    if (productsContainer) productsContainer.innerHTML = '<p class="subtitle2">Your cart is empty.</p>';
                    checkoutForm.style.display = "none";
                } else {
                    showToast("Error: " + (result.message || "Checkout failed"));
                }
            } catch (err) {
                console.error("Checkout error:", err);
                showToast("Error during checkout: " + err.message);
            }
        });
    }

    // Toast helper
    function showToast(message) {
        // prefer a dynamic toast element so we don't rely on #toast existing
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.textContent = message;
        Object.assign(toast.style, {
            position: 'fixed',
            bottom: '20px',
            left: '50%',
            transform: 'translateX(-50%)',
            background: 'rgba(0,0,0,0.8)',
            color: '#fff',
            padding: '8px 12px',
            borderRadius: '6px',
            zIndex: 9999,
            opacity: 0,
            transition: 'opacity .25s'
        });
        document.body.appendChild(toast);
        requestAnimationFrame(() => toast.style.opacity = '1');
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Simple intersection observer for .productElement
    const productElements = document.querySelectorAll('.productElement');
    if (productElements.length) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        productElements.forEach(el => observer.observe(el));
    }
});
