*** Settings ***
Documentation     A resource file with reusable keywords and variables.
Library           SeleniumLibrary
Resource          workflows.resource

*** Variables ***
${SERVER}         2fauth
${BROWSER}        chrome
${DELAY}          0.5

${USERNAME}     Tester
${EMAIL}     testing@2fauth.app
${PASSWORD}    password

${ROOT URL}    https://${SERVER}
${LOGOUT LINK}    lnkSignOut

*** Keywords ***
Open Blank Browser
    Set Selenium Speed    ${DELAY}
    ${options}=    Evaluate    sys.modules['selenium.webdriver'].ChromeOptions()    sys
    Call Method    ${options}    add_argument    --lang\=en,en-us
    Call Method    ${options}    add_argument    --disable-dev-shm-usage    # See https://stackoverflow.com/questions/50642308/org-openqa-selenium-webdriverexception-unknown-error-devtoolsactiveport-file-d
    ${prefs}=    Create Dictionary    intl.accept_languages=en,en-us
    Call Method    ${options}    add_experimental_option    prefs    ${prefs}
    Open Browser    url=${ROOT URL}    browser=${BROWSER}    options=${options}
    Maximize Browser Window

Go Authenticated To
    [Arguments]    ${url}
    Go To    ${url}
    ${is_authenticated}=  Run Keyword And Return Status    Location Should Be    ${url}
    IF    ${is_authenticated} == False
        Play sign In Workflow
        Go To    ${url}
    END

An Error Notification Should Appear
    Wait Until Element Is Visible    css:#vueNotification .is-danger

Field Should Show An Error
    [Arguments]    ${field error}
    Element Should Be Visible   ${field error}
    Should Not Be Empty    ${field error}

Scroll To Bottom
    Execute Javascript  window.scrollTo(0,document.body.scrollHeight)
