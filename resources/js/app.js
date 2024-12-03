import './bootstrap';

document.getElementById('sidebar-toggle').addEventListener('click', () => {
    document.getElementById('sidebar').style.left = '0';
});

document.getElementById('close-sidebar').addEventListener('click', () => {
    document.getElementById('sidebar').style.left = '-100%';
});
