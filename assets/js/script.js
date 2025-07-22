// Confirmação de exclusão já está no index.php via onclick
// Validação de formulário básica
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const nome = form.querySelector('input[name="nome"]');
            const email = form.querySelector('input[name="email"]');
            
            if (!nome.value.trim()) {
                alert('O campo Nome é obrigatório!');
                nome.focus();
                e.preventDefault();
                return false;
            }
            
            if (!email.value.trim()) {
                alert('O campo Email é obrigatório!');
                email.focus();
                e.preventDefault();
                return false;
            }
            
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                alert('Por favor, insira um email válido!');
                email.focus();
                e.preventDefault();
                return false;
            }
        });
    });
});