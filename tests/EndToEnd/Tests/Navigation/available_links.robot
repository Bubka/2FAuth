*** Settings ***
Documentation     A test suite to check links availability.
Suite Setup       Run keywords
...                   Open Custom Browser
...                   AND    Go To Legacy Login Page
...                   AND    Legacy Form Should Be Visible
Suite Teardown    Close All Browsers
Resource          ../../Pages/login_page.robot
Resource          ../../Pages/register_page.robot

*** Test Cases ***

### LEGACY LOGIN PAGE LINKS ###

Reset Link Is Visible
    Element Should Be Visible    ${RESET PASSWORD LINK}

Webauthn login Link Is Visible
    Element Should Be Visible    ${SIGN IN WITH WEBAUTHN LINK}

Register Link Is Visible
    Element Should Be Visible    ${REGISTER LINK}

### / LEGACY LOGIN PAGE LINKS ###

### WEBAUTHN PAGE LINKS ###

Legacy login Link Is Visible
    Show Webauthn Form
    Webauthn Form Should Be Visible
    Element Should Be Visible    ${SIGN IN WITH LOGIN PASSWORD LINK}

Webauthn Recover Account Link Is Visible
    Show Webauthn Form
    Webauthn Form Should Be Visible
    Element Should Be Visible    ${RECOVER YOUR ACCOUNT LINK}

### / WEBAUTHN PAGE LINKS ###

### REGISTER PAGE LINKS ###

Sign In Link Is Visible
    Go To Register Page
    Element Should Be Visible    ${SIGN IN LINK}

### / REGISTER PAGE LINKS ###