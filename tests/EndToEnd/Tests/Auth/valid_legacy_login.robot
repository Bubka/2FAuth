*** Settings ***
Documentation     A test suite containing tests related to valid login.
Suite Setup       Open Blank Browser
Suite Teardown    Close All Browsers
Resource          ../../Pages/login_page.robot
Resource          authentication.resource

*** Test Cases ***
Valid Login
    Delete All Cookies
    Go To Legacy Login Page
    Login Page Should Be Open
    User Should Be Welcomed
    Submit Credentials To Legacy Form Login    ${EMAIL}    ${PASSWORD}
     