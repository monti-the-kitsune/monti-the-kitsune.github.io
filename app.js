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
