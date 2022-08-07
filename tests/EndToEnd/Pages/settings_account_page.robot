*** Settings ***
Documentation     A page object to use in Account settings tests.
...
Library           SeleniumLibrary
Resource          ../common.resource
Resource          login_page.robot

*** Variables ***
${ACCOUNT SETTINGS PAGE URL}    http://${SERVER}/settings/account
${PASSWORD FIELD FOR DELETE}    css:form#frmDeleteAccount >> css:input#pwdPassword
${DELETE YOUR ACCOUNT BUTTON}    btnDeleteAccount

*** Keywords ***

Account Settings Page Should Be Open
    Location Should Be    ${ACCOUNT SETTINGS PAGE URL}

Go To Account Settings Page
    Go Authenticated To    ${ACCOUNT SETTINGS PAGE URL}

Delete User Account
    Input Text    ${PASSWORD FIELD FOR DELETE}    ${PASSWORD}
    Click Button    ${DELETE YOUR ACCOUNT BUTTON}
    Handle Alert
