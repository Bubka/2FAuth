*** Settings ***
Documentation     A page object to use in Accounts tests.
...
Library           SeleniumLibrary
Library    Collections
Resource          ../common.resource

*** Variables ***
${ACCOUNTS PAGE URL}    ${ROOT URL}/accounts

${GROUP SWITCH}    groupSwitch
${SEARCH FIELD}    txtSearch
${2FA ACCOUNT}    class:tfa-cell
${MODAL OTP}    class:modal-otp
${ALWAYS ON OTP}    class:always-on-otp
${SHOW GROUP SWITCH BUTTON}    btnShowGroupSwitch
${HIDE GROUP SWITCH BUTTON}    btnHideGroupSwitch

*** Keywords ***
Accounts Page Should Be Open
    Wait Until Location Is    ${ACCOUNTS PAGE URL}
    
Go To Accounts Page
    Go Authenticated To    ${ACCOUNTS PAGE URL}

Show An Otp In Modal
    Wait Until Page Contains Element    ${2FA ACCOUNT}
    ${account} =    Get WebElement    ${2FA ACCOUNT}:first-child
    Click Element    ${account}
    Wait Until Element Is Visible    ${OTP}

Get OTP Value Shown In Modal
    ${string}=    Get Text    ${OTP}
    [return]  ${string}

Get OTP Values Shown On Home
    Wait Until Page Contains Element    ${ALWAYS ON OTP}
    @{elements}=    Get WebElements    ${ALWAYS ON OTP}
    ${otps} =          Create List
    FOR    ${element}    IN    @{elements}
        ${otp} =    Get Text    ${element}
        Append To List    ${otps}    ${otp}
    END
    [return]  @{otps}

Show Group Switch
    Wait Until Page Contains Element    ${SHOW GROUP SWITCH BUTTON}
    Click Element    ${SHOW GROUP SWITCH BUTTON}
    Wait Until Page Contains Element    ${GROUP SWITCH}

Hide Group Switch
    Click Element    ${HIDE GROUP SWITCH BUTTON}
    Wait Until Page Does Not Contain Element    ${GROUP SWITCH}

Click Otp In Modal To Copy It
    Click Element    ${OTP}

Click Otp On Home To Copy It
    Wait Until Page Contains Element    ${ALWAYS ON OTP}
    ${otp element} =    Get WebElement    ${ALWAYS ON OTP}:first-child
    Click Element    ${otp element}

Clipboard Should Contain
    [Arguments]    ${expected}
    Close Modal Otp
    Wait Until Element Is Visible    ${SEARCH FIELD}
    Set Focus To Element    ${SEARCH FIELD}
    Press Keys    None    CTRL+v
    ${clipboard} =    Get Value    ${SEARCH FIELD}
    Should Be Equal    ${expected}    ${clipboard}
    Input Text    ${SEARCH FIELD}    ${CLEARED CLIPBOARD VALUE}
    Press Keys    ${SEARCH FIELD}    CTRL+c
    Clear Search

Clear Search
    Run Keyword And Ignore Error    Click Element    btnClearSearch

Copy To Clipboard
    [Arguments]    ${string}
    Close Modal Otp
    Wait Until Element Is Visible    ${SEARCH FIELD}
    Input Text    ${SEARCH FIELD}    ${string}
    Set Focus To Element    ${SEARCH FIELD}
    Press Keys    None    CTRL+a
    Press Keys    None    CTRL+c
    Clear Search

Close Modal Otp
    ${modal is open} =    Run Keyword And Return Status    Element Should Be Visible    ${MODAL OTP}
    Run Keyword If    ${modal is open}    Click Element    ${CLOSE BUTTON}
    Wait Until Element Is Not Visible    ${MODAL OTP}

