// Function to get query parameters from URL
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Simulated database response (to be replaced with actual backend call)
const products = [
    { product_id: 1, name: "Product Name 1", price: 100, image_url: "product1.jpg", description: "This is the description for product 1.", stock_quantity: 50 },
    { product_id: 2, name: "Product Name 2", price: 200, image_url: "product2.jpg", description: "This is the description for product 2.", stock_quantity: 20 },
    { product_id: 3, name: "Product Name 3", price: 150, image_url: "product3.jpg", description: "This is the description for product 3.", stock_quantity: 0 },
    { product_id: 4, name: "Product Name 4", price: 120, image_url: "product4.jpg", description: "This is the description for product 4.", stock_quantity: 30 },
    { product_id: 5, name: "Product Name 5", price: 180, image_url: "product5.jpg", description: "This is the description for product 5.", stock_quantity: 10 },
    { product_id: 6, name: "Product Name 6", price: 220, image_url: "product6.jpg", description: "This is the description for product 6.", stock_quantity: 15 },
];

// Function to display product details
function displayProduct() {
    const productId = getQueryParam('product_id');
    const product = products.find(p => p.product_id == productId);

    if (product) {
        document.getElementById('product-image').src = product.image_url;
        document.getElementById('product-name').textContent = product.name;
        document.getElementById('product-price').textContent = `Price: $${product.price}`;
        document.getElementById('product-stock').textContent = `In Stock: ${product.stock_quantity}`;
        document.getElementById('product-description').textContent = product.description;
    } else {
        // Handle case where product is not found
        document.querySelector('.container').innerHTML = '<p>Product not found.</p>';
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
