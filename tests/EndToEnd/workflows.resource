*** Settings ***
Documentation     Workflows execute recurrent tasks.
...               They don't assert anything.
...               They must be triggered while a browser is already opened.
...               They must not close the browser
Resource          common.resource
Resource          Pages/login_page.robot
Resource          Pages/register_page.robot
Resource          Pages/accounts_page.robot
Resource          Pages/settings_account_page.robot
Resource          Pages/autolock_page.robot

*** Variables ***


*** Keywords ***
Play Delete Current User Account Workflow
    Go To Account Settings Page
    Delete User Account
    Wait Until Location Is    ${REGISTER PAGE URL}

Play Admin Sign In Workflow
    Play Logout Workflow
    Delete All Cookies
    Go To Legacy Login Page
    Submit Credentials To Legacy Form Login    ${ADMIN EMAIL}    ${PASSWORD}
    Wait Until Location Is Not    ${LOGIN PAGE URL}

Play User Sign In Workflow
    Play Logout Workflow
    Delete All Cookies
    Go To Legacy Login Page
    Submit Credentials To Legacy Form Login    ${USER EMAIL}    ${PASSWORD}
    Wait Until Location Is    ${ACCOUNTS PAGE URL}

Play Logout Workflow
    Go To    ${ACCOUNTS PAGE URL}
    ${is_authenticated}=  Run Keyword And Return Status    Page Should Contain Element    ${LOGOUT LINK}
    IF    ${is_authenticated} == True
        Click Element    ${LOGOUT LINK}
        Handle Alert
    END
    Wait Until Location Is    ${LOGIN PAGE URL}