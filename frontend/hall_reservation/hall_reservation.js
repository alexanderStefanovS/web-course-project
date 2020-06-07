//#region Services

const TEACHER_ROLE = '2';

function checkUser() {
    fetch('../../backend/endpoints/check_user.php')
    .then(response => {
        return response.json();
    })
    .then( data => {
        if (!data.value || data.value.role !== '2') {
            location.replace("../login/login.html");
        } else {
            user = data.value;
            getTeacherSubjects();
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

function getTeacherSubjects() {
    fetch("../../backend/endpoints/get_teacher_subjects.php?userId=" + user.id)
    .then(response => {
        return response.json();
    })
    .then(data => {
        subjects = data.value;
        getTeacherSubjectsOptions();
    });
}

//#endregion

//#region Functions
function getTimeOptionsFrom() {
    var hourFrom = document.getElementById('hour-from');
    for(var i = 7; i <= 20; i ++){
        var option = document.createElement('option');
        option.text = i + ":00 ч.";
        option.value = i;
        hourFrom.appendChild(option);
    }
}

function getTimeOptionsTo() {
    var hourTo = document.getElementById('hour-to');
    for(var i = 7; i <= 20; i ++){
        var option = document.createElement('option');
        option.text = i + ":00 ч.";
        option.value = i;
        hourTo.appendChild(option);
    }
}

function getTeacherSubjectsOptions() {
    var subjectElement = document.getElementById('subject');
    subjects.forEach( (subject) => {
        var option = document.createElement('option');
        option.text = subject.subjectName + " - " + subject.course + " курс, " + subject.specialty;
        option.value = subject.id;
        subjectElement.appendChild(option);
    });
}

function findHallId(hall) {
    let hallId = -1;
    halls.forEach( (h) => {
        if (h.number === hall) {
            hallId = h.id;
        }
    });
    return hallId;
}

function validateHours(hourFrom, hourTo) {
    return hourFrom !== hourTo && parseInt(hourTo) > parseInt(hourFrom);
}

function setMinDate() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    if(dd < 10){
        dd = '0' + dd;
    } 
    if(mm < 10){
        mm = '0' + mm;
    } 
    today = yyyy + '-' + mm + '-' + dd;
    document.getElementById("date").setAttribute("min", today);
}

//#endregion

//#region DOM events

const onFormSubmitted = event => {
    event.preventDefault();

    const formElement = event.target;

    const hall = formElement.querySelector("input[name='hall']").value;
    const usersSubjectsId = formElement.querySelector("select[name='subject']").value;
    const date = formElement.querySelector("input[name='date']").value;
    const hourFrom = formElement.querySelector("select[name='hour-from']").value;
    const hourTo = formElement.querySelector("select[name='hour-to']").value;

    if (!validateHours(hourFrom, hourTo)) {
        document.getElementById('user-message').innerText = "Моля, въведете валиден часови диапазон.";
    } else {

        const hallId = findHallId(hall);

        const formData = {
            hallsId: hallId,
            usersSubjectsId: usersSubjectsId,
            date: date,
            hourFrom: hourFrom,
            hourTo: hourTo,
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
    }
}

document.getElementById('reservation-form').addEventListener('submit', onFormSubmitted);

//#endregion

getTimeOptionsFrom();
getTimeOptionsTo();
setMinDate();

let user;
checkUser();

let halls;
getHalls();

let subjects;









