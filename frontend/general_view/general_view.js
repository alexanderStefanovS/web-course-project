
//#region DOM elements

const floor1 = document.getElementById('f1');
const floor2 = document.getElementById('f2');
const floor3 = document.getElementById('f3');
const block2 = document.getElementById('bl2');
const floorHeader = document.getElementById('floor-header');

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

//#endregion

// #region Services

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
      return response.text()
    })
    .then( data => {
      console.log(data)
    });
}

// #endregion

//#region functions

function placeHalls(halls) {
  
  halls.forEach( (hall) => {
    const element = document.getElementById(hall.number);
    if (element) {
      element.innerHTML = hall.number + "<br>" + hall.type;
    }
  });

}

//#endregion

getHalls();
checkUser();

