document.addEventListener('DOMContentLoaded', function () {
    const navbar = document.getElementById('navbar');

    if (navbar) {
        function handleScroll() {
            if (window.scrollY > 20) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }

        window.addEventListener('scroll', handleScroll);
        handleScroll(); // initial check
    }
});