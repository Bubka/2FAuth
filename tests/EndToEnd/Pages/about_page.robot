*** Settings ***
Documentation     A page object to use in About page tests.
...
Library           SeleniumLibrary
Resource          ../common.resource
Library           String

*** Variables ***
${ABOUT PAGE URL}    ${ROOT URL}/about

*** Keywords ***
About Page Should Be Open
    Location Should Be    ${ABOUT PAGE URL}

Go To About Page
    Go To    ${ABOUT PAGE URL}

Browse To About Page
    Click Link    ${ABOUT LINK}

Exit About Page
    Wait Until Page Contains Element    ${BACK BUTTON}
    Click Link    ${BACK BUTTON}

Block Vars Should Be Visible
    [Arguments]    ${elementId}
    Wait Until Page Contains Element    id:${elementId}
    ${COUNT} =    Get Element Count    css:#${elementId} li
    Should Be True    0 < ${COUNT}

Environment Vars Should Be Visible
    Block Vars Should Be Visible    listInfos

User Preferences Should Be Visible
    Block Vars Should Be Visible    listUserPreferences

User Preferences Should Not Be Visible
    Page Should Not Contain    id:listUserPreferences
    
Admin Settings Should Be Visible
    Block Vars Should Be Visible    listAdminSettings

Admin Settings Should Not Be Visible
    Page Should Not Contain    id:listAdminSettings

Block Vars Should Have A Value
    [Arguments]    ${elementId}
    Wait Until Page Contains Element    id:${elementId}
    ${elements} =    Get WebElements    css:#${elementId} li
    FOR    ${element}    IN    @{elements}
        ${text} =    Get Text    ${element}
        @{values} =    Split String    ${text}    :
        Should Not Be Empty    ${values}[1]
    END

Copying Block Vars Should Notify On Success
    [Arguments]    ${button}
    Click Button    ${button}
    A Success Notification Should Appear
    Click Element    ${SUCCESS NOTIFICATION}