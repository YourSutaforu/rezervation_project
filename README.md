# Rezervation Project

Toto je malý rezervační systém napsaný v PHP + MySQL, vytvořený pro praxi. Cílem projektu je umožnit uživatelům:

- vytvořit si vlastného uživatele pomoci jmená a hesla,
- zarezervovat si místnost na konkrétní datum a čas ve třích různých mistnostech,
- zobrazit své rezervace,
- smazat existující rezervaci pomoci jeho id.

---

## Funkce projektu

- registrace uživatele (vytvoření jmena a hesla)  
- výběr místnosti a rezervace podle času  
- výpis všech vlastních rezervací  
- mazání rezervací s potvrzením  
- jednoduchý kalendář pro výběr data  

---

## Použité technologie

Projekt je napsán s využitím:

- PHP → server‑side logika  
- MySQL → databáze pro ukládání uživatelů a rezervací  
- HTML / CSS → jednoduché rozhraní stránek  
- JavaScript → interaktivní kalendář a jeho přepinání 
- XAMPP → lokální vývojové prostředí s Apache + MySQL

---

## Jak spustit projekt

1. **Stáhněte a nainstalujte XAMPP** (Apache + MySQL).  
2. Spusťte **Apache** a **MySQL** z ovládacího panelu XAMPP.  
3. Otevřete **phpMyAdmin** v prohlížeči:  
***http://localhost/phpmyadmin***

4. Vytvořte novou databázi s názvem `reservations`.  
5. Importujte SQL soubor ( `reservations`/`reservations_example` `.sql`) přes phpMyAdmin:  
*- klikněte na databázi*  
*- záložka **Import***  
*- vyberte `.sql` soubor*  
*- potvrďte import*

6. Vložte projekt do složky XAMPP:  
***C:\xampp\htdocs\rezervation_project*** (všechny soubory mimo tento projekt v htdocs lze smazat, nejsou potřebné)

7. Otevřete projekt v prohlížeči:
***http://localhost/rezervation_project/index.php***

---

## 📁 Obsah projektu

- **index.php** – vstupní stránka pro registraci uživatele  
- **big_room_calendar.php / normal_room_calendar.php / small_room_calendar.php**  
– interaktivní kalendáře pro rezervaci místností  
- **reservation_check.php** – ověření, výpis a mazání rezervací  
- **index.js** – JavaScript pro kalendář  
- **style.css** – jednoduché stylování  
- **database/reservations_example.sql** – struktura a příklad dat pro MySQL

---

## Ukázkový uživatel

Pro testování můžete vytvořit vlastní účet nebo přidat testovací data v SQL:

uživatel: `test`
heslo: `test123`

---

## Poznámky

- Projekt je určený především pro lokální běh s XAMPP.  
- Neobsahuje pokročilé zabezpečení (CSRF / SQL prepared statements), ale logika funguje pro učební účely.  
- Uživatelé se mohou registrovat a rezervovat více místností.

- elegantní responzivní UI  
- blokování již zarezervovaných časů v kalendáři

---

**Děkuju za použití mého projektu!**
