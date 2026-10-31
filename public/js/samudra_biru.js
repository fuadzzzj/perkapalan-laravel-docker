document.addEventListener('DOMContentLoaded', () => {
    const targets = document.querySelectorAll('.card, .mini-item, .materi-item, .gallery-item, .about-card');

    targets.forEach((element, index) => {
        element.style.transitionDelay = `${index * 80}ms`;
        requestAnimationFrame(() => {
            element.classList.add('is-visible');
        });
    });
});
