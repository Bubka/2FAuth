*** Settings ***
Documentation     A test suite containing tests related to legacy login.
Suite Setup       Run Keywords
...                   Open Custom Browser
...                   AND    Play Logout Workflow
Suite Teardown    Close All Browsers
Test Setup        Go To Legacy Login Page
Resource          ../../Pages/login_page.robot
Resource          authentication.resource

*** Test Cases ***
Invalid Email Should Be Rejected
    [Template]    Email Submit Should Fail
    ${EMPTY}    ${PASSWORD}
    ${INVALID EMAIL MALFORMED}    ${PASSWORD}
    ${INVALID EMAIL DOES NOT EXIST}    ${PASSWORD}

Missing Password Should Be Rejected
    Submit Credentials To Legacy Form Login    ${ADMIN EMAIL}    ${EMPTY}
    Login Page Should Be Open
    login_page.Password Field Should Show An Error

Invalid Password Should Be Rejected
    Submit Credentials To Legacy Form Login    ${ADMIN EMAIL}    ${INVALID PASSWORD}
    Login Page Should Be Open
    An Error Notification Should Appear

Password Should Not Be Readable
    Input Text    ${PASSWORD FIELD}    ${PASSWORD}
    Element Attribute Value Should Be    ${PASSWORD FIELD}    type    password

Password Should Be Readable
    Input Text    ${PASSWORD FIELD}    ${PASSWORD}
    Click Element    ${TOGGLE PASSWORD VISIBILITY BUTTON}
    Element Attribute Value Should Be    ${PASSWORD FIELD}    type    text

Valid Login
    Login Page Should Be Open
    User Should Be Welcomed
    Submit Credentials To Legacy Form Login    ${ADMIN EMAIL}    ${PASSWORD}
    Wait Until Location Is    ${ACCOUNTS PAGE URL}    2s

*** Keywords ***
Email Submit Should Fail
    [Arguments]    ${email}    ${password}
    Submit Credentials To Legacy Form Login    ${email}    ${password}
    Location Should Be    ${LOGIN PAGE URL}
    login_page.Email Field Should Show An Error
