*** Settings ***
Documentation     A test suite containing tests related to invalid login.
Suite Setup       Open Blank Browser
Suite Teardown    Close All Browsers
Test Setup        Run keywords
...                   Go To Legacy Login Page
...                   AND    Login Page Should Be Open
...                   AND    Legacy Form Should Be Visible
Resource          ../../Pages/login_page.robot
Resource          authentication.resource

*** Test Cases ***
Invalid Email
    [Template]    Login With Invalid Email Should Fail
    ${EMPTY}    ${PASSWORD}
    ${INVALID EMAIL MALFORMED}    ${PASSWORD}
    ${INVALID EMAIL DOES NOT EXIST}    ${PASSWORD}

Missing Password
    Submit Credentials To Legacy Form Login    ${EMAIL}    ${EMPTY}
    Page Should Remain Login Page
    login_page.Password Field Should Show An Error

Invalid Password
    Submit Credentials To Legacy Form Login    ${EMAIL}    ${INVALID PASSWORD}
    Page Should Remain Login Page
    An Error Notification Should Appear

*** Keywords ***
Login With Invalid Email Should Fail
    [Arguments]    ${email}    ${password}
    Submit Credentials To Legacy Form Login    ${email}    ${password}
    Location Should Be    ${LOGIN PAGE URL}
    login_page.Email Field Should Show An Error

Page Should Remain Login Page
    Location Should Be    ${LOGIN PAGE URL}