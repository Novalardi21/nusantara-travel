export function initializeShell() {
    const sidebar = document.getElementById('operator-sidebar');
    const toggle = document.querySelector('[aria-controls="operator-sidebar"]');

    if (!sidebar) {
        return;
    }

    const sync = () => {
        const isOpen = document.body.classList.contains('sidebar-open');
        sidebar.inert = window.innerWidth < 1024 && !isOpen;
        toggle?.setAttribute('aria-expanded', String(isOpen));
    };

    const close = () => {
        document.body.classList.remove('sidebar-open');
        sync();
    };

    sync();
    window.addEventListener('resize', sync);

    document.querySelectorAll('[data-toggle-sidebar]').forEach(button => {
        button.addEventListener('click', () => {
            document.body.classList.toggle('sidebar-open');
            sync();
        });
    });

    sidebar.querySelectorAll('a').forEach(link => {
        const destination = new URL(link.href);
        if (destination.pathname === location.pathname && destination.hash === location.hash) {
            link.classList.add('is-active');
            link.setAttribute('aria-current', 'page');
        }
        link.addEventListener('click', close);
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && document.body.classList.contains('sidebar-open')) {
            close();
            toggle?.focus();
        }
    });
}
