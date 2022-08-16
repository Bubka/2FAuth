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

*** Variables ***


*** Keywords ***
Play Delete User Account Workflow
    Go To Account Settings Page
    Delete User Account

Play sign In Workflow
    Delete All Cookies
    Go To Legacy Login Page
    Submit Credentials To Legacy Form Login    ${EMAIL}    ${PASSWORD}

Play Register New User Workflow
    Go To Register Page
    Submit User Data To Registration Form    ${USERNAME}    ${EMAIL}    ${PASSWORD}    ${PASSWORD}

Play Logout Workflow
    Go To    ${ROOT URL}/accounts
    ${is_authenticated}=  Run Keyword And Return Status    Page Should Contain Element    ${LOGOUT LINK}
    IF    ${is_authenticated} == True
        Click Element    ${LOGOUT LINK}
        Handle Alert
    END