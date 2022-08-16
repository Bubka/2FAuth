*** Settings ***
Documentation     A test suite containing tests related to invalid registration.
Suite Setup       Register Suite Setup
Suite Teardown    Register Suite Teardown
Test Setup        Register Test Setup
Resource          ../../Pages/register_page.robot
Resource          ../../Pages/accounts_page.robot
Resource          authentication.resource

*** Test Cases ***
Invalid Email
    [Template]    Registering With Invalid Email Should Fail
    ${EMPTY}
    ${INVALID EMAIL MALFORMED}
    ${INVALID EMAIL TOO LONG}

Invalid Username
    [Template]    Registering With Invalid Username Should Fail
    ${EMPTY}
    ${INVALID USERNAME TOO LONG}

Invalid Password
    [Template]    Registering With Invalid Passwords Should Fail
    ${EMPTY}    ${EMPTY}
    ${PASSWORD}    ${EMPTY}
    ${PASSWORD}    ${INVALID PASSWORD}
    ${INVALID PASSWORD TOO SHORT}    ${INVALID PASSWORD TOO SHORT}

*** Keywords ***
Registering With Invalid Username Should Fail
    [Arguments]    ${bad username}
    Submit User Data To Registration Form     ${bad username}    ${EMAIL}    ${PASSWORD}    ${PASSWORD}
    register_page.Username Field Should Show An Error

Registering With Invalid Email Should Fail
    [Arguments]    ${bad email}
    Submit User Data To Registration Form     ${USERNAME}    ${bad email}    ${PASSWORD}    ${PASSWORD}
    register_page.Email Field Should Show An Error

Registering With Invalid Passwords Should Fail
    [Arguments]    ${bad password}    ${bad password confirmation}
    Submit User Data To Registration Form     ${USERNAME}    ${EMAIL}    ${bad password}    ${bad password confirmation}
    register_page.Password Field Should Show An Error

User should be asked To Register A Webauthn Device
    Page Should Contain Element    ${REGISTER NEW DEVICE BUTTON}
    Page Should Contain Element    ${MAYBE LATER BUTTON}

Register Suite Setup
    Open Blank Browser
    Play Delete User Account Workflow

Register Suite Teardown
    Close All Browsers
    Play Register New User Workflow

Register Test Setup
    Go To Register Page
    Location Should Be    ${REGISTER PAGE URL}