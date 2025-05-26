document.getElementById('form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const dinBlock = document.querySelector('.din');
    
    
    const isLogout = e.submitter && e.submitter.name === 'logout_form';
    
    try {
        const response = await fetch('index.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: isLogout ? new URLSearchParams({logout_form: 1}) : formData
        });
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        const data = await response.json();
        
        if (isLogout) {
            
            window.location.reload();
            return;
        }
        
        if (data.success) {
            
            alert(data.messages.success);
            if (data.login && data.password) {
                dinBlock.innerHTML = `
                Логин: <strong>${data.login}</strong><br>
                Пароль: <strong>${data.password}</strong>
                `;
            }
        } else {
            
            for (const [field, message] of Object.entries(data.messages)) {
                const errorElement = document.getElementById(`${field}-error`);
                if (errorElement) {
                    errorElement.textContent = message;
                }
            }
        }
    } catch (error) {
        console.error('Error:', error);
    }
});
