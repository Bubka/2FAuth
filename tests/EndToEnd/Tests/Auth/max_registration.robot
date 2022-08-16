*** Settings ***
Documentation     A test suite containing tests related to maximum user registration.
Suite Setup       Open Blank Browser
Suite Teardown    Close All Browsers
Resource          ../../Pages/register_page.robot
Resource          ../../Pages/accounts_page.robot
Resource          authentication.resource

*** Test Cases ***
A User Already Exists
    Go To Register Page
    Location Should Be    ${REGISTER PAGE URL}
    Submit User Data To Registration Form     ${USERNAME}    ${EMAIL}    ${PASSWORD}    ${PASSWORD}
    An Error Notification Should Appear