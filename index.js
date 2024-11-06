// Simulated database response (to be replaced with actual backend call)
const products = [
    { product_id: 1, name: "Product Name 1", price: 100, image_url: "product1.jpg" },
    { product_id: 2, name: "Product Name 2", price: 200, image_url: "product2.jpg" },
    { product_id: 3, name: "Product Name 3", price: 150, image_url: "product3.jpg" },
    { product_id: 4, name: "Product Name 4", price: 120, image_url: "product4.jpg" },
    { product_id: 5, name: "Product Name 5", price: 180, image_url: "product5.jpg" },
    { product_id: 6, name: "Product Name 6", price: 220, image_url: "product6.jpg" },
];

// Function to dynamically create product elements
function displayProducts(products) {
    const mainContent = document.querySelector('.main-content');
    products.forEach(product => {
        // Create product div
        const productDiv = document.createElement('div');
        productDiv.className = 'product';

        // Create anchor element for product link
        const productLink = document.createElement('a');
        productLink.href = `productpage.html?product_id=${product.product_id}`; // Link to product page

        // Create image element
        const img = document.createElement('img');
        img.src = product.image_url;
        img.alt = product.name;

        // Create name element
        const productName = document.createElement('h3');
        productName.textContent = product.name;

        // Create price element
        const productPrice = document.createElement('p');
        productPrice.textContent = `Price: $${product.price}`;

        // Append elements to the product link
        productLink.appendChild(img);
        productLink.appendChild(productName);
        productLink.appendChild(productPrice);

        // Append productLink to productDiv
        productDiv.appendChild(productLink);

        // Append productDiv to mainContent
        mainContent.appendChild(productDiv);
    });
}

// Call function to display products when the page loads
document.addEventListener('DOMContentLoaded', () => {
    displayProducts(products);
});
