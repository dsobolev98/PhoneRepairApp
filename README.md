# PhoneRepairApp
This contains the web applications for a phone repair service. Users can make an account and request a repair. An employee can sign in and the site will display all avaiable new service requests. Then the employee can review a request and upload an estimate and etc. Once the user has an invoice, they can pay through the app and have their device delivered back to them. 

## Prerequisites

You will need the following things properly installed on your computer

* [GIT](https://git-scm.com/)
* [XAMPP](https://apachefriends.org/index.html)

## Installation

* `cd ~/XAMPP/htdocs`
* `git clone https://github.com/dsobolev98/PhoneRepairApp.git`

## Running / Development

* Run xampp-control as admin
* Start Apache and MySQL
* In PhoneRepairApp folder, open ddl.txt and copy all
* Visit phpmyadmin at [http://localhost:80/phpmyadmin]
* Select SQL and paste code from ddl.txt
* Visit Server at [http://localhost:80/PhoneRepairApp/home.php] 
* DB is preloaded with first worker (Username: Admin, Password: password)

