function isValidName(field) {
	if (field.value == "") {
		return "Полетo \"" + field.placeholder + "\" е задължително."
	}
	if (field.value.length < 2) {
		return "Полетo \"" + field.placeholder + "\" трябва да съдържа поне 2 символа."
	}
	if (field.value.length > 45) {
		return "Полетo \"" + field.placeholder + "\" трябва да съдържа не повече от 45 символа."
	}
	if (field.value.match(/^[а-яА-Я]+[- \']?[а-яА-Я]+$/) == null && field.value.match(/^[a-zA-Z]+[- \']?[a-zA-Z]+$/) == null) {
		return "Полетo \"" + field.placeholder + "\" трябва да съдържа само текст на латиница или кирилица.";
	}
	return "";
}

function isValidEmail(field) {
	if (field == "") {
		return "Полетo \"E-mail\" е задължително."
	}
	if (field.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/) == null) {
		return "Въведен е невалиден имейл."
	}
	return "";
}

function isValidUsername(field) {
	if (field == "") {
		return "Полетo \"Потребителско име\" е задължително."
	}
	if (field.length < 3) {
		return "Полетo \"Потребителско име\" трябва да съдържа поне 3 символа."
	}
	if (field.length > 45) {
		return "Полетo \"Потребителско име\" трябва да съдържа не повече от 45 символа."
	}
	if (field.match(/^[a-zA-Z0-9]+([-._]?[a-zA-Z0-9]+)*$/) == null) {
		return "Полетo \"Потребителско име\" трябва съдържа само латински букви, цифри, точка, тире и долна черта, както и да започва с буква."
	}
	return "";
}

function isValidPassword(field) {
	if (field == "") {
		return "Полетo \"Парола\" е задължително."
	}
	if (field.length < 6) {
		return "Полетo \"Парола\" трябва да съдържа поне 6 символа."
	}
	if (field.length > 30) {
		return "Полетo \"Парола\" трябва да съдържа не повече от 30 символа."
	}
	if (field.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{6,30}$/) == null) {
		return "Въведена е невалидна парола."
	}
	return "";
}

const validateFirstname = event => {
	firstname = event.target;
	document.querySelector("p[id='errFirstname']").innerText = isValidName(firstname);
};

const validateLastname = event => {
	firstname = event.target;
	document.querySelector("p[id='errLastname']").innerText = isValidName(lastname);
};

const validateEmail = event => {
	email = event.target.value;
	document.querySelector("p[id='errEmail']").innerText = isValidEmail(email);
};

const validateUsername = event => {
	username = event.target.value;
	document.querySelector("p[id='errUsername']").innerText = isValidUsername(username);
};

const validatePassword = event => {
	password = event.target.value;
	document.querySelector("p[id='errPassword']").innerText = isValidPassword(password);
};

document.querySelector("input[name='firstname']").addEventListener('keyup', validateFirstname);
document.querySelector("input[name='lastname']").addEventListener('keyup', validateLastname);
document.querySelector("input[name='email']").addEventListener('keyup', validateEmail);
document.querySelector("input[name='username']").addEventListener('keyup', validateUsername);
document.querySelector("input[name='password']").addEventListener('keyup', validatePassword);

function validate(errors, fields) {
	
	for (const field of fields) {
		if(field == "") {
			document.getElementById('user-message').innerText = "Моля, попълнени всички задължителни полета.";
			return false;
		}
	}
	
	for (const err of errors) {
		if (err != "") {
			document.getElementById('user-message').innerText = "Mоля, въведете валидни данни.";
			return false;
		}
	}
	
	document.getElementById('user-message').innerText = "";
	return true;
}

function selectedRole(roles) {
	id = "";
	for(i = 0; i < roles.length; i++) { 
        if(roles[i].checked) {
			id = roles[i].value;
		}
    } 
	return id; 
}

const onFormSubmitted = event => {
    event.preventDefault();

    const formElement = event.target;
	
	roleID = selectedRole(formElement.querySelectorAll("input[name='role']"));
	
    const formData = {
		firstname: formElement.querySelector("input[name='firstname']").value,
		lastname: formElement.querySelector("input[name='lastname']").value,
		email: formElement.querySelector("input[name='email']").value,
        username: formElement.querySelector("input[name='username']").value,
        password: formElement.querySelector("input[name='password']").value,
		role: roleID,
    };
	
	const fields = [
		formData.firstname,
		formData.lastname,
		formData.email, 
		formData.username,
		formData.password,	
		formData.role,
	];
	
	const errors = [
		formElement.querySelector("p[id='errFirstname']").innerText,
		formElement.querySelector("p[id='errLastname']").innerText,
		formElement.querySelector("p[id='errUsername']").innerText, 
		formElement.querySelector("p[id='errEmail']").innerText,
		formElement.querySelector("p[id='errPassword']").innerText,		
		];
	
	if (validate(errors, fields)) {		
		fetch('../../backend/endpoints/register.php', {
			method: 'POST',
			body: JSON.stringify(formData),
		})
		.then(response=>response.json())
		.then(response => {
			if (response.success) {
				var popup = document.getElementById("successful-registration")
				var login = document.getElementsByClassName("login")[0];
				
				popup.style.display = "block";
				
				login.onclick = function() {
				  popup.style.display = "none";
				  window.location.replace("../login/login.html");
				}
			} else {
				document.getElementById('user-message').innerText = response.message;
			}
		});	
	}
};

document.getElementById('register-form').addEventListener('submit', onFormSubmitted);