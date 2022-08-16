*** Settings ***
Documentation     A test suite containing tests related to valid registration.
Suite Setup       Open Blank Browser
Suite Teardown    Close All Browsers
Resource          ../../Pages/register_page.robot
Resource          ../../Pages/accounts_page.robot
Resource          authentication.resource

*** Test Cases ***
Valid Registration Without Webauthn Device
    Play Delete User Account Workflow
    Go To Register Page
    Register Page Should Be Open
    Submit User Data To Registration Form     ${USERNAME}    ${EMAIL}    ${PASSWORD}    ${PASSWORD}
    User Should Be Asked To Register A Webauthn Device Or Postpone
    Postpone Webauthn Registration

*** Keywords ***
User Should Be Asked To Register A Webauthn Device Or Postpone
    Page Should Contain Element    ${REGISTER NEW DEVICE BUTTON}
    Page Should Contain Element    ${MAYBE LATER BUTTON}