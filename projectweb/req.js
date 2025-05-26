document.getElementById('form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const dinBlock = document.querySelector('.din');
     const formData = new FormData(form);

    try {
        const response = await fetch('index.php', {
            method: 'POST',
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            body: formData,
        });
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        const data = await response.json();
        
        if (data.success) {
            // Обработка успешной отправки
            alert(data.messages.success);
            if (data.login && data.password) {
                dinBlock.innerHTML = '`
                    Логин: <strong>${data.login}</strong><br>
                    Пароль: <strong>${data.password}</strong>
                `';
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
