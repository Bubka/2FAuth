*** Settings ***
Documentation     A test suite containing tests related to webauthn login.
Suite Setup       Run Keywords
...                   Open Custom Browser
...                   AND    Play Logout Workflow
Suite Teardown    Close All Browsers
Test Setup        Go To Webauthn Login Page
Resource          ../../Pages/login_page.robot
Resource          authentication.resource

*** Test Cases ***
Invalid Email Should Be Rejected
    [Template]    Email Submit Should Fail
    ${EMPTY}
    ${INVALID EMAIL MALFORMED}
    ${INVALID EMAIL DOES NOT EXIST}

*** Keywords ***
Email Submit Should Fail
    [Arguments]    ${email}
    Submit Credentials To Webauthn Form Login    ${email}
    Location Should Be    ${LOGIN PAGE URL}
    login_page.Email Field Should Show An Error
