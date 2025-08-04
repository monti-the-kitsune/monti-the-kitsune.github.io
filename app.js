document.addEventListener("DOMContentLoaded", () => {
    console.log("app.js loaded");

    // Theme toggle
    const toggleButton = document.getElementById("themeToggle");
    const body = document.body;
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "light") {
        body.classList.add("light-mode");
        toggleButton.textContent = "🌙";
    }
    toggleButton?.addEventListener("click", () => {
        console.log("Theme toggle clicked");
        const isLight = body.classList.toggle("light-mode");
        toggleButton.textContent = isLight ? "🌙" : "☀️";
        localStorage.setItem("theme", isLight ? "light" : "dark");
    });

    // Handle Add to Cart
    const addToCartForms = document.querySelectorAll('form:not(.removeForm):not(#checkoutForm)');
    console.log("Add to Cart forms found:", addToCartForms.length);
    addToCartForms.forEach((form, index) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            console.log(`Add to Cart form ${index + 1} submitted`, form.action);
            const formData = new FormData(form);
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });
                console.log("Fetch response status:", response.status);
                if (!response.ok) {
                    console.error('Fetch error: HTTP status', response.status, response.statusText);
                    throw new Error(`HTTP error: ${response.status}`);
                }
                const result = await response.json();
                console.log("Fetch response JSON:", result);
                if (result.success) {
                    showToast(result.message || 'Item added to cart!');
                } else {
                    showToast('Error: ' + (result.message || 'Failed to add item to cart'));
                }
            } catch (error) {
                console.error('Add to Cart error:', error);
                showToast('Error adding item to cart: ' + error.message);
            }
        });
    });

    // Handle remove from cart
    const removeForms = document.querySelectorAll(".removeForm");
    console.log("Remove forms found:", removeForms.length);
    removeForms.forEach((form, index) => {
        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            console.log(`Remove form ${index + 1} submitted`);
            const formData = new FormData(form);
            try {
                const response = await fetch("remove_from_cart.php", {
                    method: "POST",
                    body: formData
                });
                if (!response.ok) throw new Error(`HTTP error: ${response.status}`);
                const result = await response.json();
                console.log("Remove response JSON:", result);
                if (result.success) {
                    showToast("Item removed from cart!");
                    form.closest(".productElement").remove();
                    if (!document.querySelector(".productElement")) {
                        document.querySelector(".products").innerHTML = '<p class="subtitle2">Your cart is empty.</p>';
                        document.querySelector(".checkoutForm").style.display = "none";
                    }
                } else {
                    showToast("Error: " + result.message);
                }
            } catch (error) {
                console.error('Remove error:', error);
                showToast("Error removing item: " + error.message);
            }
        });
    });

    // Handle checkout
    const checkoutForm = document.getElementById("checkoutForm");
    if (checkoutForm) {
        console.log("Checkout form found");
        checkoutForm.addEventListener("submit", async (event) => {
            event.preventDefault();
            console.log("Checkout form submitted");
            try {
                const response = await fetch("checkout.php", {
                    method: "POST"
                });
                if (!response.ok) {
                    const text = await response.text();
                    console.error("Checkout response:", text);
                    throw new Error(`HTTP error: ${response.status}`);
                }
                const result = await response.json();
                console.log("Checkout response JSON:", result);
                if (result.success) {
                    showToast("Checkout successful! Your cart has been cleared.");
                    document.querySelector(".products").innerHTML = '<p class="subtitle2">Your cart is empty.</p>';
                    checkoutForm.style.display = "none";
                } else {
                    showToast("Error: " + result.message);
                }
            } catch (error) {
                console.error("Checkout error:", error);
                showToast("Error during checkout: " + error.message);
            }
        });
    }

    // Toast notification
    function showToast(message) {
        console.log("Showing toast:", message);
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.textContent = message;
        document.body.appendChild(toast);
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            toast.style.opacity = '1';
        }, 10);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }

});

document.addEventListener("DOMContentLoaded", () => {
    // Theme toggle
    const toggleButton = document.getElementById("themeToggle");
    const body = document.body;
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "light") {
        body.classList.add("light-mode");
        toggleButton.textContent = "🌙";
    }
    toggleButton?.addEventListener("click", () => {
        const isLight = body.classList.toggle("light-mode");
        toggleButton.textContent = isLight ? "🌙" : "☀️";
        localStorage.setItem("theme", isLight ? "light" : "dark");
    });

    // Handle remove from cart
    const removeForms = document.querySelectorAll(".removeForm");
    removeForms.forEach(form => {
        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            const formData = new FormData(form);
            try {
                const response = await fetch("remove_from_cart.php", {
                    method: "POST",
                    body: formData
                });
                if (!response.ok) throw new Error(`HTTP error: ${response.status}`);
                const result = await response.json();
                if (result.success) {
                    showToast("Item removed from cart!");
                    form.closest(".productElement").remove();
                    if (!document.querySelector(".productElement")) {
                        document.querySelector(".products").innerHTML = '<p class="subtitle2">Your cart is empty.</p>';
                        document.querySelector(".checkoutForm").style.display = "none";
                    }
                } else {
                    showToast("Error: " + result.message);
                }
            } catch (error) {
                showToast("Error removing item: " + error.message);
            }
        });
    });

    // Handle checkout
    const checkoutForm = document.getElementById("checkoutForm");
    checkoutForm?.addEventListener("submit", async (event) => {
        event.preventDefault();
        try {
            const response = await fetch("checkout.php", {
                method: "POST"
            });
            if (!response.ok) {
                const text = await response.text();
                console.error("Checkout response:", text);
                throw new Error(`HTTP error: ${response.status}`);
            }
            const result = await response.json();
            if (result.success) {
                showToast("Checkout successful! Your cart has been cleared.");
                document.querySelector(".products").innerHTML = '<p class="subtitle2">Your cart is empty.</p>';
                checkoutForm.style.display = "none";
            } else {
                showToast("Error: " + result.message);
            }
        } catch (error) {
            console.error("Checkout error:", error);
            showToast("Error during checkout: " + error.message);
        }
    });

    // Toast notification
    function showToast(message) {
        const toast = document.getElementById("toast");
        toast.textContent = message;
        toast.style.display = "block";
        setTimeout(() => {
            toast.style.display = "none";
        }, 3000);
    }
});

document.addEventListener("DOMContentLoaded", () => {
  const productElements = document.querySelectorAll('.productElement');

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
        observer.unobserve(entry.target); // Only animate once
      }
    });
  }, {
    threshold: 0.1
  });

  productElements.forEach(el => {
    observer.observe(el);
  });
});
