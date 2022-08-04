*** Settings ***
Documentation     A resource file with reusable keywords and variables.
Library           SeleniumLibrary

*** Variables ***
${SERVER}         localhost
${BROWSER}        chrome
${DELAY}          0.1


*** Keywords ***
Open Browser To Page
    [Arguments]    ${page}
    Set Selenium Speed    ${DELAY}
    Open Browser    ${page.url}    ${BROWSER}
    Page Should Be Open    ${page}

An Error should be notified
    Element Should Be Visible    id:vueNotification >> class:is-danger

Go To Page
    [Arguments]    ${page}
    Go To    ${page.url}
    Page Should Be Open    ${page}

Page Should Be Open
    [Arguments]    ${page}
    Location Should Be    ${page.url}
    Title Should Be    ${page.title}

Field Should Show An Error
    [Arguments]    ${field error}
    Element Should Be Visible   ${field error}
    Should Not Be Empty    ${field error}