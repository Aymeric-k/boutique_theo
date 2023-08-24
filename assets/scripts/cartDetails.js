function addEventListeners() {
    const cartCount = document.querySelector('#cart-count')
    let removeLinks = document.querySelectorAll('.remove-link');
    const checkoutButton = document.querySelector('.checkout button');
    removeLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            let variantId = this.getAttribute('data-variant-id');
            fetch(`removeFromCart.php?variantId=${variantId}`, {
                method: 'GET'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        e.target.closest('li').remove();
                        addEventListeners();

                        let cartCountValue = parseInt(cartCount.textContent)
                        cartCountValue-=1
                        cartCount.textContent = cartCountValue
                        document.querySelector('.subtotal p').innerHTML = `<span>Subtotal :</span> ${data.newTotal.toFixed(2)} €`;

                        // Gérer le cas où le panier est vide
                        if (data.newTotal === 0) {
                            document.querySelector('.subtotal p').innerHTML = `<span>Subtotal :</span> 0 €`;
                            checkoutButton.disabled=true
                            cartCount.style.display = "none"
                        }  else {
                            cartCount.style.display = "flex"
                        checkoutButton.disabled = false;
                    }
                   
                    } else {
                        console.error(data.error);
                    }
                });
        });
    });
}


const quantityInputs = document.querySelectorAll('.quantity');


const buttons = document.querySelectorAll('.incrementBtn, .decrementBtn');

buttons.forEach((button) => {
    button.addEventListener('click', function (event) {
        let input = event.target.closest('.details-container').querySelector('.quantity');
        let delta = event.target.classList.contains('incrementBtn') ? 1 : -1;

        let newValue = parseInt(input.value, 10) + delta;

        if (newValue >= 1 && newValue <= 99) {
            updateQuantity(input, delta);
        }
    });
});

function updateQuantity(inputElement, delta) {
    let variantId = inputElement.closest('.details-container').querySelector('.remove-link').getAttribute('data-variant-id');
    let newQuantity = Number(inputElement.value) + delta;

    let itemPrice = parseFloat(inputElement.closest('li').querySelector('p:last-child').textContent.replace(' €', ''));
    let subTotalElement = document.querySelector('.subtotal p');
    let currentTotalText = subTotalElement.textContent.replace('Subtotal :', '').replace(' €', '');
    let currentTotal = parseFloat(currentTotalText);

    // Mise à jour du total
    currentTotal = currentTotal - (itemPrice * inputElement.value) + (itemPrice * newQuantity);
    subTotalElement.textContent = `Subtotal : ${currentTotal.toFixed(2)} €`;

    fetch('updateCart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ variantId: variantId, newQuantity: newQuantity })
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                inputElement.value = newQuantity;
                document.querySelector('.subtotal p').innerHTML = `<span>Subtotal :</span> ${data.newTotal.toFixed(2)} €`;
            }
        });
}
document.querySelector('button.clear-cart').addEventListener('click', function(event) {
    fetch('clearCart.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
           
            document.querySelector('.article-list ul').innerHTML = '';

            document.querySelector('.subtotal p').innerHTML = `<span>Subtotal :</span> 0 €`;

            document.querySelector('.checkout button').disabled = true;
            document.querySelector('#card-count').style.display = "none"
        } else {
            console.error(data.error);
        }
    });
});
document.addEventListener('DOMContentLoaded', addEventListeners);
document.addEventListener('DOMContentLoaded', () =>{
    const cartContainers = document.querySelectorAll('.cart-container');
    cartContainers.forEach(cartContainer=> {
        cartContainer.style.display ="none";
    });
});
