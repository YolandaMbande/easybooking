import './bootstrap';

const menuToggle = document.querySelector('[data-menu-toggle]');
const mainNav = document.querySelector('[data-main-nav]');

if (menuToggle && mainNav) {
    menuToggle.addEventListener('click', () => {
        const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', String(!isOpen));
        mainNav.classList.toggle('is-open', !isOpen);
        document.body.classList.toggle('nav-open', !isOpen);
    });

    mainNav.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            menuToggle.setAttribute('aria-expanded', 'false');
            mainNav.classList.remove('is-open');
            document.body.classList.remove('nav-open');
        });
    });
}
