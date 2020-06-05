const onFormSubmitted = event => {
    event.preventDefault();

    const formElement = event.target;
    
    const formData = {
        username: formElement.querySelector("input[name='username']").value,
        password: formElement.querySelector("input[name='password']").value,
    };
    
    fetch('../../backend/login.php', {
        method: 'POST',
        body: JSON.stringify(formData),
    })
    .then(response=>response.json())
    .then(response => {
        if (response.success) {
    
        } else {
            document.getElementById('user-message').innerText = response.message;
        }
    });

    return false;
}

document.getElementById('login-form').addEventListener('submit', onFormSubmitted);