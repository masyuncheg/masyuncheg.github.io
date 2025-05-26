document.getElementById('form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = new e.target;
    
     const formData = new FormData(form);

    try {
        const response = await fetch('index.php', {
            method: 'POST',
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            body: formData,
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Обработка успешной отправки
            alert(data.messages.success);
            if (data.login && data.password) {
                console.log(`Login: ${data.login}, Password: ${data.password}`);
            }
            }
        } else {
            // Показ ошибок
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
