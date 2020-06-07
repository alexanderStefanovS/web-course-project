
//#region Constants

  const TEACHER_ROLE = '2';

//#endregion

//#region DOM elements

const floor1 = document.getElementById('f1');
const floor2 = document.getElementById('f2');
const floor3 = document.getElementById('f3');
const block2 = document.getElementById('bl2');
const floorHeader = document.getElementById('floor-header');
const goBtn = document.getElementById('go-btn');
const searchBtn = document.getElementById('search-btn');
const distMessage = document.getElementById('dist-min');

//#endregion

//#region DOM events

document.getElementById('f1-b').addEventListener("click", () => {
  floorHeader.innerHTML = 'Първи етаж';
  floor1.style.display = 'block';
  floor2.style.display = 'none';
  floor3.style.display = 'none';
  block2.style.display = 'none';
});

document.getElementById('f2-b').addEventListener("click", () => {
  floorHeader.innerHTML = 'Втори етаж';
  floor1.style.display = 'none';
  floor2.style.display = 'block';
  floor3.style.display = 'none';
  block2.style.display = 'none';
});

document.getElementById('f3-b').addEventListener("click", () => {
  floorHeader.innerHTML = 'Трети етаж';
  floor1.style.display = 'none';
  floor2.style.display = 'none';
  floor3.style.display = 'block';
  block2.style.display = 'none';
});

document.getElementById('bl2-b').addEventListener("click", () => {
  floorHeader.innerHTML = 'Втори блок - БАН';
  floor1.style.display = 'none';
  floor2.style.display = 'none';
  floor3.style.display = 'none';
  block2.style.display = 'block';
});

goBtn.addEventListener('click', () => {
  location.replace('../hall_reservation/hall_reservation.html');
});

searchBtn.addEventListener('click', () => {
  const fromHall = document.getElementById('from').value;
  const toHall = document.getElementById('to').value;

  const halls = {
    fromHall: fromHall, 
    toHall: toHall
  };

  getDistance(halls);
})

//#endregion

//#region Services

function getHalls() {
  fetch("../../backend/endpoints/get_halls.php")
    .then(response => {
      return response.json();
    })
    .then(data => {
      placeHalls(data.value);
    });
}

function checkUser() {
  fetch('../../backend/endpoints/check_user.php')
    .then(response => {
      return response.json()
    })
    .then( data => {
      if (!data.value) {
        location.replace("../login/login.html");
      } else {
        if (data.value.role !== TEACHER_ROLE) {
          hideGoBtn();
        }
      }
    });
}

function getDistance(halls) {
  const data = new FormData();
  data.append('halls', JSON.stringify(halls));

  const init = {
    method: 'POST',
    body: data
  }
  fetch('../../backend/endpoints/get_distance.php', init)
    .then( (response) => {
      return response.json();
    })
    .then( (data) => {
      if (data.success === true) {
        showDistance(data.value);
      } else {
        showDistanceError();
      }
    });
}

// #endregion

//#region Functions

function placeHalls(halls) {
  halls.forEach( (hall) => {
    const element = document.getElementById(hall.number);
    if (element) {
      element.innerHTML = hall.number + "<br>" + hall.type;
    }
  });
}

function hideGoBtn() {
  goBtn.style.display = 'none';
}

function showDistance(mins) {
  distMessage.style.color = '#ff8000';
  min = (mins === 1) ? 'минута' : 'минути';
  distMessage.innerText = 'Търсеното разстояние е ' + mins + ' ' + min + '.'; 
}

function showDistanceError() {
  distMessage.innerText = 'Въведените номера на зали са некоректни';
  distMessage.style.color = 'red';
}

//#endregion

checkUser();
getHalls();
