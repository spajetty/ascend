    <!-- Spacer so content isn't hidden behind mobile nav -->
    <div class="md:hidden h-20"></div>

<script>
// ── Sidebar collapse toggle ───────────────────────────────────────────────
const toggleBtn = document.getElementById('toggleSidebar');
const sidebar   = document.getElementById('sidebar');
const main      = document.getElementById('mainContent');
const icon      = document.getElementById('toggleIcon');

if (toggleBtn && sidebar && main && icon) {
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar-collapsed');

        if (sidebar.classList.contains('sidebar-collapsed')) {
            main.classList.remove('md:ml-56');
            main.classList.add('md:ml-20');
            icon.style.transform = 'rotate(180deg)';
        } else {
            main.classList.remove('md:ml-20');
            main.classList.add('md:ml-56');
            icon.style.transform = 'rotate(0deg)';
        }
    });
}

// ── Mobile nav scroll arrows ──────────────────────────────────────────────
const mobileNav   = document.getElementById('mobileNav');
const navLeft     = document.getElementById('navLeft');
const navRight    = document.getElementById('navRight');
const scrollAmount = 120;

if (mobileNav && navLeft && navRight) {
    navLeft.addEventListener('click',  () => mobileNav.scrollBy({ left: -scrollAmount, behavior: 'smooth' }));
    navRight.addEventListener('click', () => mobileNav.scrollBy({ left:  scrollAmount, behavior: 'smooth' }));
}
</script>
</body>
</html>
