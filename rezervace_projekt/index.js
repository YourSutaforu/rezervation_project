let year_swich = 0
let swich_count = 0
let current_date = new Date()
const mesice = ["Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen", "Zaří", "Říjen", "Listopad", "Prosinec"]
let days = ["po", "út", "st", "čt", "pá", "so", "ne"]

let today = new Date().getDate();
let todayMonth = new Date().getMonth();
let todayYear = new Date().getFullYear();

let month_index = (todayMonth + swich_count + 12) % 12;

function createCalendar(elem_id, year, month) {
    
    let calendar = ""

    month_index = (todayMonth + swich_count + 12) % 12;
    let firstDay = new Date(year, month, 1).getDay();
    if (firstDay === 0) firstDay = 7;

    let daysInMonth = new Date(year, month + 1, 0).getDate();

    calendar += `<p> měsic: </p> <p id = "aktual_mouth"> ${mesice[month_index]} </p> <br> <p> rok: </p> <p id = "aktual_roks"> ${todayYear + year_swich} </p>`;


    calendar += `<table> <tr class="calendar_base">`;


    for (let d of days) {
        calendar += `<th>${d}</th>`;
    }
    calendar += `</tr>`;

    let day = 1;

    for (let i = 0; i < 6; i++) {
        calendar += `<tr>`;

        for (let j = 1; j <= 7; j++) {
            if (i === 0 && j < firstDay) {

                calendar += "<td class = 'empty'></td>";
            } else if (day > daysInMonth) {

                calendar += "<td class = 'empty'></td>";
            } else {

                let cls = "";
                calendar += ""

                if (day === today && month === todayMonth && year === todayYear) {
                    cls = ` class="today"`;
                }

                calendar += `<td${cls} onclick = "clicked_day(this, event)">${day}</td>`;
                day++;
            }
        }

        calendar += `</tr>`;
    }
    calendar += "</table>"

    document.getElementById(elem_id).innerHTML = calendar;
}
function calendar_forward(elem_id){
    if (month_index == 11){
        year_swich++
    }
    let button = document.getElementById("next_month")
    let table = document.getElementById(elem_id)
    if (swich_count < 12){
        button.classList.remove("non-clackable-button")
        swich_count++;
        table.innerHTML = '';
        createCalendar("calendar", current_date.getFullYear(), current_date.getMonth() + swich_count);
    } else{
        button.classList.add("non-clackable-button");
    }
}
function calendar_back(elem_id){
    if (month_index == 0){
        year_swich--
    }
    let button = document.getElementById("prev_month")
    let table = document.getElementById(elem_id)
    if (swich_count > 0){
        button.classList.remove("non-clackable-button")
        swich_count--;
        table.innerHTML = '';
        createCalendar("calendar", current_date.getFullYear(), current_date.getMonth() - swich_count);
    } else{
        button.classList.add("non-clackable-button");
    }
}

function clicked_day(element, event) {
    event.preventDefault();
    let input = document.getElementById("reserv_date")
    let the_value = `${element.innerHTML}.${month_index + 1}.${todayYear + year_swich}`

    input.value = the_value
}



createCalendar("calendar", current_date.getFullYear(), current_date.getMonth());


