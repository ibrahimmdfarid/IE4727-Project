// Toggle dropdown visibility
function toggleDropdown() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close dropdown if clicked outside
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        const dropdowns = document.getElementsByClassName("dropdown-content");
        for (let i = 0; i < dropdowns.length; i++) {
            const openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

// Function to get query parameters from URL
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Function to display product details
async function displayProduct() {
    const productId = getQueryParam('product_id');
    
    // Fetch product data from the server
    try {
        const response = await fetch(`getProduct.php?product_id=${productId}`);
        if (!response.ok) {
            throw new Error("Product not found.");
        }
        
        const product = await response.json();

        // Check if the product is available
        if (product && product.product_id) {
            document.getElementById('product-image').src = product.image_url;
            document.getElementById('product-name').textContent = product.name;
            document.getElementById('product-price').textContent = `Price: $${product.price}`;
            document.getElementById('product-stock').textContent = `In Stock: ${product.stock_quantity}`;
            document.getElementById('product-description').textContent = product.description;
        } else {
            // Handle case where product is not found
            document.querySelector('.container').innerHTML = '<p>Product not found.</p>';
        }
    } catch (error) {
        console.error("Error fetching product:", error);
        document.querySelector('.container').innerHTML = `<p>${error.message}</p>`;
    }
}

// Quantity control functions
function updateQuantity(step) {
    const quantityInput = document.getElementById('quantity');
    let currentQuantity = parseInt(quantityInput.value) || 1; // Default to 1 if not a number

    currentQuantity += step;

    // Ensure quantity is at least 1 and not greater than stock quantity
    const stockQuantity = parseInt(document.getElementById('product-stock').textContent.split(': ')[1]) || 0;

    if (currentQuantity < 1) {
        currentQuantity = 1;
    } else if (currentQuantity > stockQuantity) {
        currentQuantity = stockQuantity;
    }

    quantityInput.value = currentQuantity;
}

// Event listener for quantity input
document.getElementById('quantity').addEventListener('input', function() {
    const value = this.value;

    // Allow only digits
    if (!/^\d*$/.test(value)) {
        this.value = value.replace(/\D/g, ''); // Remove non-digit characters
    }

    // Ensure the value is within the min and max range
    const stockQuantity = parseInt(document.getElementById('product-stock').textContent.split(': ')[1]) || 0;
    let currentQuantity = parseInt(this.value) || 1;

    if (currentQuantity < 1) {
        this.value = 1;
    } else if (currentQuantity > stockQuantity) {
        this.value = stockQuantity;
    }
});

document.getElementById('decrement').addEventListener('click', function() {
    updateQuantity(-1);
});

document.getElementById('increment').addEventListener('click', function() {
    updateQuantity(1);
});

// Call the function to display product details
displayProduct();
