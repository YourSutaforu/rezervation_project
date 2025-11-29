let swich_count = 0
let current_date = new Date()


function createCalendar(elem, year, month) {
    let days = ["po", "út", "st", "čt", "pá", "so", "ne"];

    // какой сегодня день месяца
    let today = new Date().getDate();
    let todayMonth = new Date().getMonth();
    let todayYear = new Date().getFullYear();

    // с какого дня недели начинается месяц (1–7)
    let firstDay = new Date(year, month, 1).getDay();
    if (firstDay === 0) firstDay = 7;

    // сколько дней в месяце
    let daysInMonth = new Date(year, month, 0).getDate();

    let calendar = `<table><tr class="calendar_base">`;

    // заголовки
    for (let d of days) {
        calendar += `<th>${d}</th>`;
    }
    calendar += `</tr>`;

    let day = 1;

    for (let i = 0; i < 6; i++) {
        calendar += `<tr>`;

        for (let j = 1; j <= 7; j++) {
            if (i === 0 && j < firstDay) {
                // пустые ячейки до первого дня месяца
                calendar += "<td class = 'empty'></td>";
            } else if (day > daysInMonth) {
                // после окончания месяца
                calendar += "<td class = 'empty'></td>";
            } else {
                // обычное число
                let cls = "";

                // подсветить сегодняшний день
                if (day === today && month === todayMonth && year === todayYear) {
                    cls = ` class="today"`;
                }

                calendar += `<td${cls}>${day}</td>`;
                day++;
            }
        }

        calendar += `</tr>`;
    }

    calendar += `</table>`;

    document.querySelector(elem).innerHTML += calendar;
}
function actualize_bottons(elem, elem2, sw_c){
    let element = document.querySelector(elem)
    let element2 = document.queryselector(elem2)
    if (sw_c == 0){
        element.remove()
        element2.innerHTML += `
        <${elem}>
        <button class = "not_work_butt">knopka</button>
        <button onclick = "actualize_buttons("div", "body", swich_count)">knopka_vpered</button>
        </${elem}>`
    }
    else{
        element.remove()
        element2.innerHTML += `
        <${elem}>
        <button onclick = >knopka</button>
        <button onclick = "actualize_buttons("div", "body", swich_count)">knopka_vpered</button>
        </${elem}>`
    }
    

}

createCalendar("body", current_date.getFullYear(), current_date.getMonth());
