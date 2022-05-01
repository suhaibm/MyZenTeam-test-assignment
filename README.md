
# MyZenTeam code test assignment


## Notes about the project
- A TDD approach has been used in the development
- A pivot table called 'hiring_process' to represent the relationship between the candidate and the company models


## Get started
- git clone https://github.com/suhaibm/MyZenTeam-test-assignment.git
- cd mzt-test-assignment
- composer install
- npm install
- npm run dev # or npm run prod
- cp .env.example .env # change DB and mail credentials
- php artisan key:generate
- php artisan migrate --seed
- php artisan serve


## to run the tests
./vendor/bin/phpunit


## User stories

1. At the start each company has a wallet with 20 coins of credit
1. when a company contacts a candidate, we should send an email
1. when a company contacts a candidate, charge the company 5 coins from its wallet
1. When a company hires a candidate, we should mark the candidate as hired
1. When a company hires a candidate, put back 5 coins in the wallet of the company
1. When a company hires a candidate, send an email to the candidate to tell them they were hired
1. A company can hire only candidates that they have contacted before

### E2E tests
1. On the candidates' list there is a button Contact
1. the button Hire is where a company can hire a candidate

### Assumptions
1. A candidate can be hired by only one company
1. A candidate is contacted only once by each company