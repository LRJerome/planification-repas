document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[data-add-to-planning]');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.needConfirmation) {
                    const confirmed = confirm(data.message);
                    if (confirmed) {
                        formData.append('force', 'true');
                        const confirmResponse = await fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        const confirmData = await confirmResponse.json();
                        if (confirmData.success) {
                            window.location.href = '/planning';
                        }
                    }
                } else if (data.success) {
                    window.location.href = '/planning';
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de l\'ajout au planning.');
            }
        });
    }
}); 