document.getElementById('form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target());

    try {
        const response = await fetch('index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Обработка успешной отправки
            alert(data.messages.success);
            if (data.login && data.password) {
                console.log(`Login: ${data.login}, Password: ${data.password}`);
            }
            if (data.profile_url) {
                window.location.href = data.profile_url;
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
