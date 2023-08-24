let slideIndex = 1;

const slides = document.querySelectorAll('.carousel-slide img');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const indicators = document.querySelectorAll('.indicator');


prevBtn.addEventListener('click', () => {
    slideIndex--;
    updateCarousel();
});

nextBtn.addEventListener('click', () => {
    slideIndex++;
    updateCarousel();
});


indicators[0].classList.add('active')
indicators.forEach((indicator, index) => {

    indicator.addEventListener('click', () => {
        slideIndex = index + 1;
        updateCarousel();
    });
});

function updateCarousel() {
    if (slideIndex > slides.length) {
        slideIndex = 1;
    } else if (slideIndex < 1) {
        slideIndex = slides.length;
    }


    const carouselSlide = document.querySelector('.carousel-slide');
    carouselSlide.style.transform = 'translateX(' + (-slideIndex + 1) * 100 + '%)';


    indicators.forEach(indicator => indicator.classList.remove('active'));
    indicators[slideIndex - 1].classList.add('active');
}
const variantBtns = document.querySelectorAll('.variant-label');
const priceElement = document.querySelector('.price');

const variantsContainer = document.querySelector('.variants');
priceElement.textContent = variantBtns[0].getAttribute('data-variant-price') + ' €';

if (variantBtns.length > 0) {
    const initialInput = document.getElementById(variantBtns[0].htmlFor);
    initialInput.checked = true;
    variantBtns[0].classList.add('selected');
    priceElement.textContent = initialInput.getAttribute('data-variant-price') + ' €';

    variantsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('variant-label')) {
            const btn = e.target;
            variantBtns.forEach(innerBtn => {
                innerBtn.classList.remove('selected');
            });

            btn.classList.add('selected');

            const associatedInput = btn.htmlFor ? document.getElementById(btn.htmlFor) : null;
            const selectedPrice = associatedInput ? associatedInput.getAttribute('data-variant-price') : null;

            if (selectedPrice) {
                priceElement.textContent = selectedPrice + ' €';
            }
        }
    });
}
document.getElementById('incrementBtn').addEventListener('click', function () {
    let input = document.getElementById('quantity');
    if (input.value < 99) {
        input.value = parseInt(input.value, 10) + 1;
    }
});

document.getElementById('decrementBtn').addEventListener('click', function () {
    let input = document.getElementById('quantity');
    if (input.value > 1) {
        input.value = parseInt(input.value, 10) - 1;
    }
});

let startX;
let endX;
const carousel = document.querySelector('.carousel-slide');

carousel.addEventListener('touchstart', (e) => {
    startX = e.touches[0].clientX;
}, { passive: true });

carousel.addEventListener('touchmove', (e) => {
    endX = e.touches[0].clientX;
}, { passive: true });




carousel.addEventListener('touchend', () => {
    let threshold = 25;

    if (startX - endX > threshold) {
        nextSlide();
    } else if (endX - startX > threshold) {
        prevSlide();
    }
})
function nextSlide() {
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1; // Retour au début
    }
    updateCarousel();
}

function prevSlide() {
    slideIndex--;
    if (slideIndex < 1) {
        slideIndex = slides.length; // Aller à la dernière slide
    }
    updateCarousel();
}
function updateIndicatorBackgrounds() {
    requestAnimationFrame(() => {
        if (window.innerWidth > 850) {
            indicators.forEach((indicator, index) => {
                const imageUrl = slides[index].src;
                indicator.style.backgroundImage = `url(${imageUrl})`;
            });
        } else {
            indicators.forEach(indicator => {
                indicator.style.backgroundImage = '';
            });
        }
    });
}


updateIndicatorBackgrounds();



window.addEventListener('resize', updateIndicatorBackgrounds);
const shopList = document.querySelector('.shopping-list');
const addToCartBtn = document.querySelector('.add-cart');
const counterItems = document.querySelector('.item-count')
const count = document.querySelectorAll(".shopping-list li")

function loadCartData() {
    let formData = new FormData(document.getElementById("formAddToCart"));
    const subTotal = document.querySelector('.subtotal-nbr')
    let currentTotal = parseFloat(subTotal.textContent.replace('€', '').trim());
    fetch('addToCart.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let currentTotal = 0;
                let totalItems = 0;

                document.querySelector('.item-count').textContent = totalItems + " items";
                shopList.innerHTML = '';
                for (const key in data.panier) {
                    const subTotal = document.querySelector('.subtotal-nbr')
                    let item = data.panier[key];

                    shopList.innerHTML += `<li>
                                        <img src="${item.photoUrl}" alt="${item.photoLegende}">
                                        <div>
                                        <p>${item.libelle}  #${item.produitId} </p>
                                        <p>${item.format} <span>${item.prix} €</span> <a href="/pages/article.php?name=${item.libelle}&id=${item.produitId}">article page</a></p>
                                        <p> x ${item.quantite} </p>
                                        </div>
                                    </li>`;
                    currentTotal += parseFloat(item.prix) * parseInt(item.quantite);
                    subTotal.textContent = currentTotal.toFixed(2) + ' €';

                    totalItems += parseInt(item.quantite);



                }
                if (totalItems > 0) {
                    console.log("jysuis" + totalItems)
                    let cartContainers = document.querySelectorAll('.cart-container');
                    cartContainers.forEach(container => {
                        let existingCounter = container.querySelector('.cart-count-container');
                        if (!existingCounter) {
                            let newDiv = document.createElement('div');
                            newDiv.className = 'cart-count-container';
                            newDiv.innerHTML = `<p id="cart-count">${totalItems}</p>`;
                            container.appendChild(newDiv);
                        } else {
                            existingCounter.querySelector('#cart-count').textContent = totalItems;
                        }
                    });
                }

                counterItems.textContent = totalItems + " items"
                
                const cartContainer = document.querySelector('.add-cart');
                const cartButton = document.querySelector('.text-add');
                const cartIcon = cartContainer.querySelector('img');
                const checkmark = cartContainer.querySelector('.checkmark');

                cartIcon.style.display = "none";
                cartButton.textContent = "";
                checkmark.style.display = "block";




                setTimeout(() => {
                    
                    checkmark.style.display = "none";
                    cartIcon.style.display = "block";
                    cartButton.innerHTML = '<span class="text-add">Add to cart</span> '

                }, 1800);
                
            }
        })
}


addToCartBtn.addEventListener('click', (e) => {
    e.preventDefault();
    loadCartData();
});

