const cartContainer = document.querySelector(".cart-container");
const cart = document.querySelector(".cart")
const burger = document.querySelector(".burger-icon");
const navDiv = document.querySelector(".nav-div");
const cross = document.querySelector(".cross-icon");
const animatedItems = document.querySelectorAll('.animated-item');
function resetAnimation(item, index) {
    item.classList.remove('animate');
    void item.offsetWidth; // Force le reflow
    // Ajoute la classe d'animation à nouveau pour redémarrer l'animation
    setTimeout(() => {
        if (navDiv.classList.contains('open')) {
            item.classList.remove("animated-item-closed")
            item.classList.add('animated-item')
            item.classList.add('animate');
        }
    }, 100);
}
function resetAnimationClose(item, index) {
    setTimeout(() => {
        if (navDiv.classList.contains('open')) {
            item.classList.remove('animate'); // Retirer la classe animate
            item.classList.remove('animated-item'); // Retirer la classe animated-item
            item.classList.add('animated-item-closed'); // Ajouter la classe animated-item-closed
            item.classList.add('animate'); // Ajouter la classe animate
        }
    }, 100);
}
if (cartContainer) {
    cartContainer.addEventListener("click", (e) => {
        cartContainer.style.backgroundColor = '#235af0'
        e.stopPropagation();
        cart.querySelectorAll("path").forEach(path => {
            path.setAttribute("fill", "white");
        });

    });

    document.addEventListener('click', () => {
        cartContainer.style.backgroundColor = 'transparent'
        cart.querySelectorAll("path").forEach(path => {
            path.setAttribute("fill", "#235af0");
        });
    });
}

    burger.addEventListener("click", () => {
        navDiv.classList.add('open');
        burger.classList.add('invisible');
        cross.classList.remove('invisible');
        cross.classList.remove('tourne');
        console.log("divvvv")
        animatedItems.forEach((item, index) => {
            resetAnimation(item, index);
        });

    });

if (cross) {

    cross.addEventListener("click", () => {
        animatedItems.forEach((item, index) => {
            resetAnimationClose(item, index)
        })
        cross.classList.add('tourne')
        setTimeout(() => {
            navDiv.classList.remove('open');
            cross.classList.add('invisible');
            burger.classList.remove('invisible');
        }, 1300);
    });
}
function adjustPosition() {
    const aside = document.querySelector('.pres');
    const footer = document.querySelector('footer');
    const footerTop = footer.getBoundingClientRect().top;
    if (footerTop <= window.innerHeight) {
        aside.style.position = 'absolute';
        aside.style.bottom = '120px'; // la hauteur de votre footer
    } else {
        aside.style.position = 'fixed';
        aside.style.bottom = '0';
    }
}


adjustPosition();


window.addEventListener("scroll", adjustPosition);



