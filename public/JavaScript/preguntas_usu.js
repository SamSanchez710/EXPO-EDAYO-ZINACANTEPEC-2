document.addEventListener('DOMContentLoaded', function() {
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            // Simplemente alternar la clase active en el item padre
            const faqItem = this.parentElement;
            faqItem.classList.toggle('active');
        });
    });
});