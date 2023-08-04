*** Settings ***
Documentation     A test suite containing tests related to invalid registration.
Suite Setup       run Keywords
...                   Open Custom Browser
...                   AND    Play Logout Workflow
# We force the register page to reload at every test to prevent fields set with
# ${EMPTY} to fail
Test Setup        Go To Register Page
Suite Teardown    run Keywords
...                   Play Delete Current User Account Workflow
...                   AND    Close All Browsers
Resource          ../../Pages/register_page.robot
Resource          ../../Pages/start_page.robot
Resource          authentication.resource

*** Test Cases ***
Invalid Username Should Be Rejected
    [Template]    Username Submit Should Fail
    ${EMPTY}
    ${INVALID USERNAME TOO LONG}

Invalid Email Should Be Rejected
    [Template]    Email Submit Should Fail
    ${EMPTY}
    ${INVALID EMAIL MALFORMED}
    ${INVALID EMAIL TOO LONG}

Invalid Password Should Be Rejected
    [Template]    Password Submit Should Fail
    ${EMPTY}
    ${INVALID PASSWORD TOO SHORT}

Registration Without Webauthn Device Should Succeed
    Register Page Should Be Open
    Submit User Data To Registration Form     ${NEW USER NAME}     ${UNREGISTERED EMAIL}    ${PASSWORD}
    User Should Be Asked To Register A Webauthn Device Or Postpone
    Postpone Webauthn Registration
    Wait Until Location Is    ${START PAGE URL}

*** Keywords ***
Username Submit Should Fail
    [Arguments]    ${bad username}
    Submit User Data To Registration Form     ${bad username}    ${UNREGISTERED EMAIL}    ${PASSWORD}
    register_page.Username Field Should Show An Error

Email Submit Should Fail
    [Arguments]    ${bad email}
    Submit User Data To Registration Form     ${NEW USER NAME}    ${bad email}    ${PASSWORD}
    register_page.Email Field Should Show An Error

Password Submit Should Fail
    [Arguments]    ${bad password}
    Submit User Data To Registration Form     ${NEW USER NAME}    ${UNREGISTERED EMAIL}    ${bad password}
    register_page.Password Field Should Show An Error

User Should Be Asked To Register A Webauthn Device Or Postpone
    Wait Until Page Contains Element    ${REGISTER NEW DEVICE BUTTON}
    Wait Until Page Contains Element    ${MAYBE LATER BUTTON}