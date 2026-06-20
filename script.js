// assets/js/script.js

document.addEventListener('DOMContentLoaded', function () {

    // 1. Auto-hide Flash Message setelah 5 detik
    // Agar notifikasi sukses/error tidak mengganggu tampilan terlalu lama
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000); // 5000 milidetik = 5 detik
    });


    // 2. Inisialisasi Bootstrap Tooltips
    // Jika ada elemen dengan atribut data-bs-toggle="tooltip"
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });


    // 3. Fitur Toggle Show/Hide Password
    // Cara pakai di HTML: 
    // <input type="password" id="password">
    // <button type="button" class="toggle-password" data-target="#password"><i class="fas fa-eye"></i></button>
    const togglePasswords = document.querySelectorAll('.toggle-password');
    togglePasswords.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.querySelector(targetId);
            const icon = this.querySelector('i');
            
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    if(icon) icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    if(icon) icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            }
        });
    });


    // 4. Smooth Scroll untuk anchor link (#)
    // Digunakan di halaman panduan atau beranda
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if(targetId === '#') return; // Abaikan jika hanya #
            
            const targetEl = document.querySelector(targetId);
            if (targetEl) {
                e.preventDefault();
                targetEl.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

});
