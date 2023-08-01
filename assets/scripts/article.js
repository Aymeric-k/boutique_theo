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
const variantBtns = document.querySelectorAll('.variant-btn');
const priceElement = document.querySelector('.price');

const variantsContainer = document.querySelector('.variants');
console.log(variantBtns)

if (variantBtns.length>0){
variantBtns[0].classList.add('selected');
priceElement.textContent = variantBtns[0].getAttribute('data-variant-price') + ' €';
variantsContainer.addEventListener('click', (e) => {
    if (e.target.classList.contains('variant-btn')) {
        const btn = e.target;

        variantBtns.forEach(innerBtn => {
            innerBtn.classList.remove('selected');
        });

        btn.classList.add('selected');
        const selectedPrice = btn.getAttribute('data-variant-price');
        priceElement.textContent = selectedPrice + ' €';
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

});

carousel.addEventListener('touchmove', (e) => {
    endX = e.touches[0].clientX;
});

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