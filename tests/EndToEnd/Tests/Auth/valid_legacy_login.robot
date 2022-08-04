*** Settings ***
Documentation     A test suite containing tests related to valid login.
Suite Setup       Open Browser To Legacy Login Page
Suite Teardown    Close Browser
Resource          ../../Pages/login_page.robot
Resource          ../../Pages/accounts_page.robot
Resource          authentication.resource

*** Test Cases ***
Valid Login
    Submit Credentials To Legacy Form Login    ${VALID USER}    ${VALID PASSWORD}
    Accounts Page Should Be Open