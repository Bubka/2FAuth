*** Settings ***
Documentation     A page object to use in Accounts tests.
...
Library           SeleniumLibrary
Resource          ../common.resource

*** Variables ***
${ACCOUNTS PAGE URL}    ${ROOT URL}/accounts

${GROUP SWITCH}    groupSwitch
${SHOW GROUP SWITCH BUTTON}    btnShowGroupSwitch
${HIDE GROUP SWITCH BUTTON}    btnHideGroupSwitch

*** Keywords ***
Accounts Page Should Be Open
    Wait Until Location Is    ${ACCOUNTS PAGE URL}
    
Go To Accounts Page
    Go Authenticated To    ${ACCOUNTS PAGE URL}

Show First Totp In Modal
    Wait Until Page Contains Element    class:tfa-cell
    ${account} =    Get WebElement    class:tfa-cell:first-child
    Click Element    ${account}
    Wait Until Element Is Visible    ${OTP}

Show Group Switch
    Wait Until Page Contains Element    ${SHOW GROUP SWITCH BUTTON}
    Click Element    ${SHOW GROUP SWITCH BUTTON}
    Wait Until Page Contains Element    ${GROUP SWITCH}

Hide Group Switch
    Click Element    ${HIDE GROUP SWITCH BUTTON}
    Wait Until Page Does Not Contain Element    ${GROUP SWITCH}