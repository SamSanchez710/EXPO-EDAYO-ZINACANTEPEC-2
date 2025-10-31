document.addEventListener('DOMContentLoaded', function() {
  setTimeout(function() {
    const carousel = document.querySelector('.talleres-carousel');
    const items = document.querySelectorAll('.taller-item');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');

    if (items.length === 0) return;

    let currentIndex = 0;

    function showSlide(index) {
      items.forEach(item => {
        item.classList.remove('active');
        item.style.opacity = '0';
        item.style.pointerEvents = 'none';
        item.style.position = 'absolute';
      });

      dots.forEach(dot => dot.classList.remove('active'));

      items[index].classList.add('active');
      items[index].style.opacity = '1';
      items[index].style.pointerEvents = 'all';
      items[index].style.position = 'relative';

      if (dots[index]) dots[index].classList.add('active');

      currentIndex = index;
    }

    prevBtn?.addEventListener('click', function() {
      let newIndex = (currentIndex - 1 + items.length) % items.length;
      showSlide(newIndex);
    });

    nextBtn?.addEventListener('click', function() {
      let newIndex = (currentIndex + 1) % items.length;
      showSlide(newIndex);
    });

    dots.forEach(dot => {
      dot.addEventListener('click', function() {
        const index = parseInt(this.getAttribute('data-index'));
        if (!isNaN(index)) showSlide(index);
      });
    });

    let intervalId = null;
    if (items.length > 1) {
      intervalId = setInterval(() => {
        let newIndex = (currentIndex + 1) % items.length;
        showSlide(newIndex);
      }, 5000);

      carousel.addEventListener('mouseenter', () => clearInterval(intervalId));
      carousel.addEventListener('mouseleave', () => {
        intervalId = setInterval(() => {
          let newIndex = (currentIndex + 1) % items.length;
          showSlide(newIndex);
        }, 5000);
      });
    }

    showSlide(0);
    console.log(`Carrusel inicializado con ${items.length} talleres`);
  }, 100);
});