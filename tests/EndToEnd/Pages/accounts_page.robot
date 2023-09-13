*** Settings ***
Documentation     A page object to use in Accounts tests.
...
Library           SeleniumLibrary
Library           Collections
Resource          start_page.robot
Resource          ../common.resource

*** Variables ***
${ACCOUNTS PAGE URL}    ${ROOT URL}/accounts

${GROUP SWITCH}    groupSwitch
${SEARCH FIELD}    txtSearch
${GRID CLASS}    tfa-grid
${LIST CLASS}    tfa-list
${ACCOUNTS CONTAINER}    css:.accounts > span
${2FA ACCOUNT}    class:tfa-cell
${MODAL OTP}    class:modal-otp
${ALWAYS ON OTP}    class:always-on-otp
${SHOW GROUP SWITCH BUTTON}    btnShowGroupSwitch
${HIDE GROUP SWITCH BUTTON}    btnHideGroupSwitch
${MANAGE BUTTON}    btnManage
${SELECT ALL BUTTON}    btnSelectAll
${UNSELECT ALL BUTTON}    btnUnselectAll
${MOVE BUTTON}    btnMove
${DELETE BUTTON}    btnDelete
${EXPORT BUTTON}    btnExport

*** Keywords ***    
Go To Accounts Page
    Go Authenticated To    ${ACCOUNTS PAGE URL}

Wait Until Accounts Are Loaded
    Wait Until Page Contains Element    class:accounts

Show An Otp In Modal
    Wait Until Page Contains Element    ${2FA ACCOUNT}
    ${account} =    Get WebElement    ${2FA ACCOUNT}:first-child
    Click Element    ${account}
    Wait Until Element Is Visible    ${OTP}

Get Visible TwoFAccounts Elements
    Wait Until Page Contains Element    ${ACCOUNTS CONTAINER}
    @{twofaccounts} =    Get WebElements    ${ACCOUNTS CONTAINER} > div
    [return]  @{twofaccounts}

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

Delete All TwoFAccounts
    Wait Until Page Contains Element    ${MANAGE BUTTON}
    Click Element    ${MANAGE BUTTON}
    Wait Until Page Contains Element    ${SELECT ALL BUTTON}
    Click Element    ${SELECT ALL BUTTON}
    Wait Until Element Is Enabled    ${DELETE BUTTON}
    Click Element    ${DELETE BUTTON}
    Handle Alert
    Wait Until Location Is    ${START PAGE URL}