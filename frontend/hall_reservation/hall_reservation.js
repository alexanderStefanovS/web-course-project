function checkUser() {
    fetch('../../backend/endpoints/check_user.php')
    .then(response => {
        return response.json()
    })
    .then( data => {
        if (!data.value) {
            location.replace("../login/login.html");
        } else {
            user = data.value;
        }
    });
}

function getTimeOptions() {
    var element = document.getElementById('hour');
    for(var i = 7; i <= 19; i ++){
        var option = document.createElement('option');
        option.text = i + ":00";
        option.value = i;
        element.appendChild(option);
    }
}

function getHalls() {
    fetch("../../backend/endpoints/get_halls.php")
    .then(response => {
        return response.json();
    })
    .then(data => {
        halls = data.value;
    });
}

function findHallId(hall) {
    let hall_id = -1;
    halls.forEach( (h) => {
        if (h.number === hall) {
            hall_id = h.id;
        }
    });
    return hall_id;
}

function getSubjects() {
    fetch("../../backend/endpoints/get_subjects.php")
    .then(response => {
        return response.json();
    })
    .then(data => {
        subjects = data.value;
    });
}

function findSubjectId(subject) {
    let subject_id = -1;
    subjects.forEach( (s) => {
        if (s.name == subject) {
            subject_id = s.id;
        }
    });
    return subject_id;
}

const onFormSubmitted = event => {
    event.preventDefault();

    const formElement = event.target;

    const hall = formElement.querySelector("input[name='hall']").value;
    const subject = formElement.querySelector("input[name='subject']").value;
    const date = formElement.querySelector("input[name='date']").value;
    const hour = formElement.querySelector("select[name='hour']").value;

    const hall_id = findHallId(hall);
    const subject_id = findSubjectId(subject);

    const formData = {
        user_id: user.id,
        hall_id: hall_id,
        subject_id: subject_id,
        date: date,
        hour: time,
    };

    console.log(formData);
    
    fetch('../../backend/endpoints/hall_reservation.php', {
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

document.getElementById('reservation-form').addEventListener('submit', onFormSubmitted);

getTimeOptions();

let user;
checkUser();

let halls;
getHalls();

let subjects;
getSubjects();









