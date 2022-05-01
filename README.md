
# MyZenTeam code test assignment

## Description

In this test assignment we have prepared a hiring platform where job seekers (candidates) can be found and get contacted and hired from companies' hiring managers.

The platform is free for job seekers, but not for companies.
The billing is handled by using a wallet. At the start each company has a wallet with 20 coins of credit.
These coins can be used to contact candidates and contacting a candidate will cost the company 5 coins.

On the candidates' list there is a button `Contact` and this is where a company can contact a candidate.
Similarly, the button `Hire` is where a company can hire a candidate.

One of the tasks for this test assignment is to implement the `Contact a candidate` feature which should consist of the following:
when a company contacts a candidate, we should send an email to that candidate and charge the company 5 coins from its wallet.

The other feature that is part of this test assignment is to `hire a candidate`.
When a company hires a candidate we should mark the candidate as `hired`, put back 5 coins in the wallet of the company, and send an email to the candidate to tell them they were hired.
A company can hire only candidates that they have contacted before.

Aside from the features, we're aware that this app is far from perfect, so we'd like you to fix/improve anything that you find to be wrong or needs improvement (code, architecture, naming, readability, robustness, etc.).

While doing this test assignment, please pay attention to these aspects:

- Security - we do not want to be hacked
- Best practices - code should be clean and easy to maintain
- Documentation - provide information on how to set up the project
- Tests - test the parts that you feel necessary to
- Logic - pay attention to the constraints throughout the test assignment

## Notes
- Authentication **IS NOT** in the scope of this assignment.
- The list of candidates, the company and the wallet are predefined and there is no need to create new ones.
- The emails that should be sent to the candidates can consist of only text, no design is needed.


_**This app was created only for the purpose of the test assignment and code quality in this repository DOES NOT represent code quality in MyZenTeam.**_

## Get started

Use this repository as your starting point, but **DO NOT** fork it. Create a public repository on GitHub for your application source code, push it and send the link to us.


composer install
npm install
cp .env.example .env # change DB and mail credentials,
php artisan key:generate
npm run dev # or npm run prod
php artisan migrate --seed
php artisan serve


# to run the tests
./vendor/bin/phpunit


# User stories

1. At the start each company has a wallet with 20 coins of credit
2. Contacting a candidate will cost the company 5 coins
3. when a company contacts a candidate, we should send an email
4. when a company contacts a candidate, charge the company 5 coins from its wallet
5. When a company hires a candidate, we should mark the candidate as hired
6. When a company hires a candidate, put back 5 coins in the wallet of the company
7. When a company hires a candidate, send an email to the candidate to tell them they were hired
8. A company can hire only candidates that they have contacted before
// check if the company has balance or not when contacting a candidate?
// a candidate can be hired by only one company
// test an email is sent to the candidate upon contact
// a candidate is contacted only once
E2E tests
1. On the candidates' list there is a button Contact
2. the button Hire is where a company can hire a candidate