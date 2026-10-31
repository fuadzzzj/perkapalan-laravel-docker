(function () {
    var header = document.getElementById('site-nav-header');
    if (!header) return;

    var burger = header.querySelector('.site-nav-burger');
    var sheet = header.querySelector('.site-nav-sheet');
    var nav = header.querySelector('.site-nav-links');
    var links = Array.prototype.slice.call(nav.querySelectorAll('a'));
    var current = nav.querySelector('[aria-current="page"]');

    function setCurrent(link) {
        if (!link) return;
        links.forEach(function (item) {
            item.classList.toggle('site-nav-current', item === link);
        });
    }

    function setMenu(open) {
        if (open) {
            var bounds = burger.getBoundingClientRect();
            sheet.style.setProperty('--site-nav-origin-x', (bounds.left + bounds.width / 2) + 'px');
            sheet.style.setProperty('--site-nav-origin-y', (bounds.top + bounds.height / 2) + 'px');
            header.classList.remove('site-nav-hidden');
        }
        sheet.classList.toggle('site-nav-open', open);
        header.classList.toggle('site-nav-open', open);
        document.body.classList.toggle('site-nav-lock', open);
        burger.setAttribute('aria-expanded', String(open));
        burger.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
        sheet.setAttribute('aria-hidden', String(!open));
    }

    links.forEach(function (link) {
        link.addEventListener('click', function () { setCurrent(link); });
    });
    burger.addEventListener('click', function () {
        setMenu(!sheet.classList.contains('site-nav-open'));
    });
    sheet.addEventListener('click', function (event) {
        if (event.target.closest('a')) setMenu(false);
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') setMenu(false);
    });
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 960) setMenu(false);
    });

    var lastY = window.scrollY;
    var ticking = false;
    function onScroll() {
        var y = window.scrollY;
        header.classList.toggle('site-nav-scrolled', y > 24);
        if (!sheet.classList.contains('site-nav-open')) {
            if (y > lastY + 6 && y > 140) header.classList.add('site-nav-hidden');
            else if (y < lastY - 6 || y <= 24) header.classList.remove('site-nav-hidden');
        }
        lastY = y;
        ticking = false;
    }

    window.addEventListener('scroll', function () {
        if (!ticking) {
            ticking = true;
            window.requestAnimationFrame(onScroll);
        }
    }, { passive: true });

    setCurrent(current);
    onScroll();
})();
