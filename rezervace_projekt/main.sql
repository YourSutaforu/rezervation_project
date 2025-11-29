/*
velka mistnost 80 m^2 = big_room
stredni mistnost 40 m^2 = normal_room
mala mistnost 15 m^2 = small_room
*/

create table big_room_res(
    id CHAR(7) not null
    person_password nvarchar(max) not null
    start_time nvarchar(5) /*оба время в формате MM:SS*/
    end_time nvarchar(5)
    reserv_date nvarchar(10) /* формат DD.MM.YYYY */
)

create table normal_room_res(
    id CHAR(7) not null
    person_password nvarchar(max) not null
    start_time nvarchar(5) /*оба время в формате MM:SS*/
    end_time nvarchar(5)
    reserv_date nvarchar(10) /* формат DD.MM.YYYY */
)

create table small_room_res(
    id CHAR(7) not null
    person_password nvarchar(max) not null
    start_time nvarchar(5) /*оба время в формате MM:SS*/
    end_time nvarchar(5)
    reserv_date nvarchar(10) /* формат DD.MM.YYYY */
)