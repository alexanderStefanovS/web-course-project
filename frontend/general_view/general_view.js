
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
const searchDistBtn = document.getElementById('search-dist-btn');
const distMessage = document.getElementById('dist-min');
const scheduleBtn = document.getElementById('search-schedule-btn');
const scheduleMessage = document.getElementById('schedule-message');
const hour = document.getElementById('hour');
const exportBtn = document.getElementById('csv-export-btn');
const logoutBtn = document.getElementById('logout-btn');

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
  location.href = '../hall_reservation/hall_reservation.html';
});

searchDistBtn.addEventListener('click', () => {
  const fromHall = document.getElementById('from').value;
  const toHall = document.getElementById('to').value;

  if (!fromHall || !toHall) {
    distMessage.innerText = 'Моля, въведете номерата и на двете зали.';
    distMessage.style.color = 'red';
    return;
  }

  const halls = {
    fromHall: fromHall, 
    toHall: toHall
  };

  getDistance(halls);
})

scheduleBtn.addEventListener('click', () => {
  const date = document.getElementById('date').value;
  const hour = document.getElementById('hour').value;

  if (!date || !hour) {
    scheduleMessage.innerText = 'Моля, въведете дата и час.';
    scheduleMessage.style.color = 'red';
    return;
  }

  const schedule = {
    date: date, 
    hour: hour,
  };

  getSchedule(schedule);
})

exportBtn.addEventListener('click', () => {
  const date = document.getElementById('date').value;
  const hour = document.getElementById('hour').value;

  if (!date || !hour) {
    scheduleMessage.innerText = 'Моля, въведете дата и час.';
    scheduleMessage.style.color = 'red';
    return;
  }

  const schedule = {
    date: date, 
    hour: hour,
  };

  exportCSV(schedule);
})

logoutBtn.addEventListener('click', () => {
  logout();
})

//#endregion

//#region Services

function getHalls() {
  fetch("../../backend/endpoints/get_halls.php")
    .then(response => {
      if (!response.ok) {
        throw new Error('Error loading halls.');
      }
      return response.json();
    })
    .then(data => {
      halls = data.value;
      placeHalls(data.value);
    })
    .catch(error => {
      console.error('Грешка при зареждане на залите.');
    });
}

function checkUser() {
  fetch('../../backend/endpoints/check_user.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Error checking user.');
      }
      return response.json()
    })
    .then( data => {
      if (!data.value) {
        location.href = "../login/login.html";
      } else {
        if (data.value.role !== TEACHER_ROLE) {
          hideGoBtn();
        }
      }
    })
    .catch(error => {
      console.error('Грешка при автентикация на потребител.');
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
      if (!response.ok) {
        throw new Error('Error getting distance.');
      }
      return response.json();
    })
    .then( (data) => {
      if (data.success === true) {
        showDistance(data.value);
      } else {
        showDistanceError('Въведените номера на зали са некоректни');
      }
    })
    .catch(error => {
      const message = 'Грешка при намиране на разстояние между две зали.';
      showDistanceError(message);
      console.error(message);
    });
}

function getSchedule(schedule) {
  fetch('../../backend/endpoints/get_schedule.php', {
    method: 'POST',
    body: JSON.stringify(schedule),
  })
    .then( (response) => {
      if (!response.ok) {
        throw new Error('Error searching schedule.');
      }
      return response.json();
    })
    .then( (data) => {
      if (data.success === true) {
        placeHalls(halls);
        showSearchScheduleMessage(data.message);
        showSearchScheduleData(data.value);
      } else {
        showSearchScheduleError(data.message);
      }
    })
    .catch(error => {
      const message = 'Грешка при търсене на график.';
      showSearchScheduleError(message);
      console.error(message);
    });
}

function exportCSV(schedule) {
  fetch('../../backend/endpoints/get_schedule.php', {
    method: 'POST',
    body: JSON.stringify(schedule),
  }).then( (response) => {
      if (!response.ok) {
        throw new Error('Error exporting schedule.');
      }
       return response.json();
    })
    .then( (data) => {
      console.log(data);
      if (data.success === true) {
        showSearchScheduleMessage(data.message);
        const csvContent = getScheduleAsCSV(data.value);
        const filename = 'schedule_' + data.value[0].date + '_' + data.value[0].hour + '.csv'
        download(filename, csvContent);
      } else {
        showSearchScheduleError(data.message);
      }
    })
    .catch(error => {
      const message = 'Грешка при експорт на график.';
      showSearchScheduleError(message);
      console.error(message);
    });
}

function logout() {
  fetch('../../backend/endpoints/logout.php', {
    method: 'GET'
  })
  .then((response) => {
    if (!response.ok) {
      throw new Error('Error logout user.');
    }
    return response.json();
  })
  .then(response => {
      if (response.success) {
          document.location.reload();
      }
  })
  .catch(error => {
    const message = 'Грешка при изход на потребител.';
    showSearchScheduleError(message);
    console.error(message);
  });
}

// #endregion

//#region Functions

function download(filename, csvContent) {
  csvContent = 'data:text/csv;charset=utf-8,' + csvContent;
  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", filename);
  document.body.appendChild(link);
  link.click();
}

function getHourOptions() {
  for(var i = 7; i <= 20; i ++){
      var option = document.createElement('option');
      option.text = i + ":00 ч.";
      option.value = i;
      hour.appendChild(option);
  }
}

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

function showDistanceError(message) {
  distMessage.innerText = message;
  distMessage.style.color = 'red';
}

function showSearchScheduleError(message) {
  scheduleMessage.innerText = message;
  distMessage.style.color = 'red';
}

function showSearchScheduleMessage(message) {
  scheduleMessage.innerText = message;
  scheduleMessage.style.color = '#ff8000';
}

function showSearchScheduleData(data) {
  data.forEach( (item) => {
    const element = document.getElementById(item.hallNumber);
    if (element) {
      element.innerHTML += "<br>" + item.specialty + ", " + item.course + "<br>" + item.subjectName + "<br>" 
      + item.teacherFirstname + "<br>" + item.teacherLastname;
    }
  });
}

function getScheduleAsCSV(schedule) {
  let csvContent = 'Дата,Час,Номер,Предмет,Име,Фамилия,Курс,Специалност\n';
  schedule.forEach( (obj) => {
    for (const item in obj) {
      if (item !== 'id') {
        if (item !== 'specialty') {
          csvContent += obj[item] + ','
        } else {
          csvContent += obj[item];
        }
      }
    }
    csvContent += '\n';
  });
  return csvContent;
}

//#endregion

checkUser();

let halls;
getHalls();

getHourOptions();
