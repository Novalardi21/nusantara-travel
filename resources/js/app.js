import './bootstrap';
import { initializeShell } from './ui';

initializeShell();

document.querySelectorAll('[data-print]').forEach(button => {
    button.addEventListener('click', () => window.print());
});
