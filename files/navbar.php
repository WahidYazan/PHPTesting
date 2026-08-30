<nav class="bg-slate-900 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-2">
                <span class="text-white font-semibold text-lg">Sistem Parkir</span>
            </div>
            <div class="hidden md:flex items-center gap-1">
                <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-slate-800 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' ?> px-3 py-2 rounded-md text-sm font-medium transition-colors">Dashboard</a>
                <a href="tambah_transaksi.php" class="<?= basename($_SERVER['PHP_SELF']) == 'tambah_transaksi.php' ? 'bg-slate-800 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' ?> px-3 py-2 rounded-md text-sm font-medium transition-colors">Kendaraan Masuk</a>
                <a href="checkout.php" class="<?= basename($_SERVER['PHP_SELF']) == 'checkout.php' ? 'bg-slate-800 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' ?> px-3 py-2 rounded-md text-sm font-medium transition-colors">Kendaraan Keluar</a>
                <a href="bayar.php" class="<?= basename($_SERVER['PHP_SELF']) == 'bayar.php' ? 'bg-slate-800 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' ?> px-3 py-2 rounded-md text-sm font-medium transition-colors">Pembayaran</a>
            </div>
            <div class="flex gap-4">
                <button id="theme-toggle" class="text-slate-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                    Dark Mode
                </button>
            </div>
        </div>
    </div>
</nav>

<script>
// Apply theme immediately to prevent flash
(function() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
    }
})();

// Theme toggle functionality
const themeToggle = document.getElementById('theme-toggle');
const html = document.documentElement;

// Initialize button appearance
updateThemeButton(html.getAttribute('data-theme'));

themeToggle.addEventListener('click', () => {
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    html.setAttribute('data-theme', newTheme);
    if (newTheme === 'dark') {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }
    localStorage.setItem('theme', newTheme);
    updateThemeButton(newTheme);
});

function updateThemeButton(theme) {
    if (theme === 'dark') {
        themeToggle.textContent = 'Light';
    } else {
        themeToggle.textContent = 'Dark Mode';
    }
}
</script>
