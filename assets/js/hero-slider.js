// // Hero Slider Functionality
// function initHeroCarousel() {
//     const slides = document.querySelectorAll('.hero-slide');
//     const dotsContainer = document.querySelector('.slider-dots');
    
//     if (!slides.length) return;

//     let currentSlide = 0;
//     let slideInterval;

//     // Create dots
//     if (dotsContainer) {
//         dotsContainer.innerHTML = '';
//         slides.forEach((_, index) => {
//             const dot = document.createElement('div');
//             dot.classList.add('dot');
//             if (index === 0) dot.classList.add('active');
//             dot.addEventListener('click', () => goToSlide(index));
//             dotsContainer.appendChild(dot);
//         });
//     }

//     const dots = document.querySelectorAll('.dot');

//     function goToSlide(index) {
//         slides[currentSlide].classList.remove('active');
//         if (dots[currentSlide]) dots[currentSlide].classList.remove('active');
        
//         currentSlide = index;
        
//         slides[currentSlide].classList.add('active');
//         if (dots[currentSlide]) dots[currentSlide].classList.add('active');
        
//         // Reset timer
//         startSlideTimer();
//     }

//     function nextSlide() {
//         let next = (currentSlide + 1) % slides.length;
//         goToSlide(next);
//     }

//     function startSlideTimer() {
//         clearInterval(slideInterval);
//         slideInterval = setInterval(nextSlide, 5000);
//     }

//     startSlideTimer();
// }

// // Update the init call in DOMContentLoaded
// document.addEventListener('DOMContentLoaded', function() {
//     initPreloader();
//     initHeader();
//     initMobileMenu();
//     initScrollAnimations();
//     initProductCards();
//     initQuantityControls();
//     initBackToTop();
//     initSearchBox();
//     initHeroCarousel(); // Ensure this is called
// });






