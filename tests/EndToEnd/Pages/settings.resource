*** Settings ***
Library    SeleniumLibrary
*** Variables ***

${OPTIONS TAB}      lnkTabOptions
${ACCOUNT TAB}      lnkTabAccount
${OAUTH TAB}        lnkTabOAuth
${WEBAUTHN TAB}     lnkTabWebauthn
${CLOSE BUTTON}     btnClose

*** Keywords ***
Exit Settings
    Wait Until Page Contains Element    ${CLOSE BUTTON}
    Click Link    ${CLOSE BUTTON}

Activate Settings Tab
    [Arguments]    ${tab}
    Wait Until Page Contains Element    ${tab}
    ${requested tab is active}=  Run Keyword And Return Status    Element Attribute Value Should Be    .tabs > li.is-active a    id    ${tab} 
    Run Keyword If    ${requested tab is active} == False   Click Link    ${tab}