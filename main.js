document.addEventListener('DOMContentLoaded', () => {
  // --- SHOP PAGE FUNCTIONALITY ---
  const productElements = document.querySelectorAll('.pro');
  if (productElements.length > 0) {
    productElements.forEach(product => {
      product.addEventListener('click', () => {
        const productImg = product.querySelector('img')?.src || '';
        const productBrand = product.querySelector('.des span')?.textContent.trim() || '';
        const productTitle = product.querySelector('.des h5')?.textContent.trim() || '';
        const productPrice = product.querySelector('.des h4')?.textContent.trim() || '';

        const productData = {
          img: productImg,
          brand: productBrand,
          title: productTitle,
          price: productPrice
        };

        localStorage.setItem('selectedProduct', JSON.stringify(productData));

        window.location.href = 'sproduct.html';
      });
    });
  }

  // --- PRODUCT DETAILS PAGE FUNCTIONALITY ---
  const mainImg = document.getElementById('MainImg');
  if (mainImg) {
    const productDataString = localStorage.getItem('selectedProduct');
    if (productDataString) {
      const productData = JSON.parse(productDataString);

      // Update the main image using the existing "MainImg" ID
      mainImg.src = productData.img;

      // Update the product title and price.
      const productTitleElem = document.querySelector('.single-pro-details h4');
      const productPriceElem = document.querySelector('.single-pro-details h2');

      if (productTitleElem) {
        productTitleElem.textContent = productData.title;
      }
      if (productPriceElem) {
        productPriceElem.textContent = productData.price;
      }

    } else {
      // If no product data is found, show an error message
      const detailContainer = document.querySelector('.single-pro-details');
      if (detailContainer) {
        detailContainer.innerHTML = '<p>No product data found.</p>';
      }
    }
  }
});

document.addEventListener('DOMContentLoaded', () => {
  // ... [Your existing code for shop page and product details page] ...

  // ADD TO CART FUNCTIONALITY (Product Details Page)
  // We assume that the "Add To Cart" button is inside the .single-pro-details container
  const addToCartBtn = document.querySelector('.single-pro-details button.normal');
  if (addToCartBtn) {
    addToCartBtn.addEventListener('click', () => {
      // Retrieve the product details from the page.
      // Here we use your existing selectors.
      // Using the image element with the id "MainImg" (or if you kept it as is).
      const productImgElem = document.getElementById('MainImg');
      const productImg = productImgElem ? productImgElem.src : '';

      // Assume the first <h4> inside .single-pro-details is the product title.
      const productTitleElem = document.querySelector('.single-pro-details h4');
      const productTitle = productTitleElem ? productTitleElem.textContent.trim() : '';

      // Assume the <h2> in .single-pro-details is the product price.
      const productPriceElem = document.querySelector('.single-pro-details h2');
      const productPrice = productPriceElem ? productPriceElem.textContent.trim() : '';

      // Get the quantity from the input (if available)
      const quantityInput = document.querySelector('.single-pro-details input[type="number"]');
      const quantity = quantityInput ? parseInt(quantityInput.value, 10) : 1;

      // Build the product object to add to cart.
      const product = {
        img: productImg,
        title: productTitle,
        price: productPrice,
        quantity: quantity
      };

      // Retrieve the existing cart from localStorage (if any)
      let cart = JSON.parse(localStorage.getItem('cart')) || [];

      // Check if the product already exists in the cart (using title as identifier)
      const existingProductIndex = cart.findIndex(item => item.title === product.title);
      if (existingProductIndex !== -1) {
        // If it exists, update the quantity
        cart[existingProductIndex].quantity += product.quantity;
      } else {
        // Otherwise, add the new product to the cart
        cart.push(product);
      }

      // Save the updated cart array back to localStorage
      localStorage.setItem('cart', JSON.stringify(cart));

      // Optionally, notify the user (this could be replaced with a custom notification or UI update)
      alert('Product added to cart!');
    });
  }
});