*** Settings ***
Documentation     A test suite containing tests related to invalid login.
Suite Setup       Open Browser To Legacy Login Page
Suite Teardown    Close Browser
Test Setup        Go To Legacy Login Page
Resource          ../../Pages/login_page.robot
Resource          authentication.resource

*** Test Cases ***
Invalid Email
    [Template]    Login With Invalid Email Should Fail
    ${EMPTY}    ${VALID PASSWORD}
    invalid    ${VALID PASSWORD}
    email@donot.exist    ${VALID PASSWORD}

Missing Password
    Submit Credentials To Legacy Form Login    ${VALID USER}    ${EMPTY}
    Location Should Be    ${LOGIN_PAGE.url}
    Password Field Should Show An Error

Invalid Password
    Submit Credentials To Legacy Form Login    ${VALID USER}    invalid
    Location Should Be    ${LOGIN_PAGE.url}
    An Error should be notified

*** Keywords ***
Login With Invalid Email Should Fail
    [Arguments]    ${username}    ${password}
    Submit Credentials To Legacy Form Login    ${username}    ${password}
    Location Should Be    ${LOGIN_PAGE.url}
    Email Field Should Show An Error
