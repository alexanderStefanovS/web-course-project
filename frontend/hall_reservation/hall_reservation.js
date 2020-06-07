//#region Services

function checkUser() {
    fetch('../../backend/endpoints/check_user.php')
    .then(response => {
        return response.json()
    })
    .then( data => {
        if (!data.value) {
            location.replace("../login/login.html");
        } else {
            console.log(data.value);
            user = data.value;
        }
    });
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

function getSubjects() {
    fetch("../../backend/endpoints/get_subjects.php")
    .then(response => {
        return response.json();
    })
    .then(data => {
        subjects = data.value;
    });
}

//#endregion

//#region Functions
function getTimeOptionsFrom() {
    var hour_from = document.getElementById('hour_from');
    for(var i = 7; i <= 20; i ++){
        var option = document.createElement('option');
        option.text = i + ":00 ч.";
        option.value = i;
        hour_from.appendChild(option);
    }
}

function getTimeOptionsTo() {
    var hour_to = document.getElementById('hour_to');
    for(var i = 7; i <= 20; i ++){
        var option = document.createElement('option');
        option.text = i + ":00 ч.";
        option.value = i;
        hour_to.appendChild(option);
    }
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

function findSubjectId(subject) {
    let subject_id = -1;
    subjects.forEach( (s) => {
        if (s.name === subject) {
            subject_id = s.id;
        }
    });
    return subject_id;
}
//#endregion

//#region DOM events

const onFormSubmitted = event => {
    event.preventDefault();

    const formElement = event.target;

    const hall = formElement.querySelector("input[name='hall']").value;
    const subject = formElement.querySelector("input[name='subject']").value;
    const date = formElement.querySelector("input[name='date']").value;
    const hour_from = formElement.querySelector("select[name='hour_from']").value;
    const hour_to = formElement.querySelector("select[name='hour_to']").value;

    const hall_id = findHallId(hall);
    const subject_id = findSubjectId(subject);

    const formData = {
        user_id: user.id,
        hall_id: hall_id,
        subject_id: subject_id,
        date: date,
        hour_from: hour_from,
        hour_to: hour_to,
    };

    console.log(formData);
    
    fetch('../../backend/endpoints/hall_reservation.php', {
        method: 'POST',
        body: JSON.stringify(formData),
    })
    .then(response=>response.json())
    .then(response => {
        console.log(response);
        document.getElementById('user-message').innerText = response.message;
    });

    return false;
}

document.getElementById('reservation-form').addEventListener('submit', onFormSubmitted);

//#endregion

getTimeOptionsFrom();
getTimeOptionsTo();

let user;
checkUser();

let halls;
getHalls();

let subjects;
getSubjects();









