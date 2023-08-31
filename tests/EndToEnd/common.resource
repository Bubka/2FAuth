*** Settings ***
Documentation     A resource file with reusable keywords and variables.
Library           SeleniumLibrary
Resource          workflows.resource

*** Variables ***
${SERVER}           localhost
${BROWSER}          chrome
# ${DELAY}            1

${USERNAME}         Tester
${ADMIN EMAIL}      testingadmin@2fauth.app
${USER EMAIL}       testingadmin@2fauth.app
${PASSWORD}         password

${ROOT URL}         http://${SERVER}

${LOGOUT LINK}      lnkSignOut
${ABOUT LINK}       lnkAbout
${SETTINGS LINK}    lnkSettings
${BACK BUTTON}      lnkBack
${CANCEL BUTTON}    btnCancel
${CLOSE BUTTON}     btnClose
${OTP}              otp

${SUCCESS NOTIFICATION}    css:#vueNotification .is-success
${ALERT NOTIFICATION}    css:#vueNotification .is-danger

${OTP OBFUSCATOR SYMBOL}    ●
${CLEARED CLIPBOARD VALUE}    -clear-

*** Keywords ***
Open Custom Browser
    [Arguments]    ${url}=${ROOT URL}
    # Set Selenium Speed    ${DELAY}
    ${options}=    Evaluate    sys.modules['selenium.webdriver'].ChromeOptions()    sys
    Call Method    ${options}    add_argument    --lang\=en,en-us
    Call Method    ${options}    add_argument    --disable-dev-shm-usage    # See https://stackoverflow.com/questions/50642308/org-openqa-selenium-webdriverexception-unknown-error-devtoolsactiveport-file-d
    ${prefs}=    Create Dictionary    intl.accept_languages=en,en-us
    Call Method    ${options}    add_experimental_option    prefs    ${prefs}
    Open Browser    url=${url}    browser=${BROWSER}    options=${options}
    Maximize Browser Window

Go Authenticated To
    [Arguments]    ${url}
    Go To    ${url}
    ${is_authenticated}=  Run Keyword And Return Status    Location Should Be    ${url}
    IF    ${is_authenticated} == False
        Play Admin Sign In Workflow
        Go To    ${url}
    END
    Wait Until Location Is    ${url}

A Success Notification Should Appear
    Wait Until Element Is Visible    ${SUCCESS NOTIFICATION}

An Error Notification Should Appear
    Wait Until Element Is Visible    ${ALERT NOTIFICATION}

Field Should Show An Error
    [Arguments]    ${field error}
    Wait Until Element Is Visible   ${field error}
    Should Not Be Empty    ${field error}

Scroll To Bottom
    Execute Javascript    window.scrollTo(0,document.body.scrollHeight)

Element Should Have Class
    [Arguments]  ${element}  ${className}
    ${class}=       Get Element Attribute  ${element}    class
    Should Contain    ${class}    ${className}

Element Should Not Have Class
    [Arguments]  ${element}  ${className}
    ${class}=       Get Element Attribute  ${element}    class
    Should Not Contain    ${class}    ${className}

Set Footer As Static
    Wait Until Page Contains Element    tag:footer
    execute javascript  
    ...  document.querySelector('footer').style = 'position: static';

Browse To Settings
    Click Element    ${SETTINGS LINK}