*** Settings ***
Documentation     A resource file with reusable keywords and variables.
Library           SeleniumLibrary
# Library           RequestsLibrary
Resource          workflows.resource

*** Variables ***
${SERVER}         localhost
${BROWSER}        chrome
${DELAY}          0.1

${USERNAME}     Tester
${EMAIL}     testing@2fauth.app
${PASSWORD}    password

${HOME PAGE URL}    http://${SERVER}/
${LOGOUT LINK}    lnkSignOut

*** Keywords ***
Open Blank Browser
    Set Selenium Speed    ${DELAY}
    # ${options}=    Evaluate    sys.modules['selenium.webdriver'].ChromeOptions()    sys
    # Call Method    ${options}    add_argument    --lang\=en,en-us
    # Call Method    ${options}    add_argument    --disable-dev-shm-usage    # See https://stackoverflow.com/questions/50642308/org-openqa-selenium-webdriverexception-unknown-error-devtoolsactiveport-file-d
    # ${prefs}=    Create Dictionary    intl.accept_languages=en,en-us
    # Call Method    ${options}    add_experimental_option    prefs    ${prefs}
    # Create Webdriver    Chrome    chrome_options=${options}
    # Go To    ${HOME PAGE URL}
    Open Browser    ${HOME PAGE URL}    Chrome

Go Authenticated To
    [Arguments]    ${url}
    Go To    ${url}
    ${is_authenticated}=  Run Keyword And Return Status    Location Should Be    ${url}
    IF    ${is_authenticated} == False
        Play sign In Workflow
        Go To    ${url}
    END

An Error Notification Should Appear
    Element Should Be Visible    css:#vueNotification >> css:.is-danger

Field Should Show An Error
    [Arguments]    ${field error}
    Element Should Be Visible   ${field error}
    Should Not Be Empty    ${field error} 