*** Settings ***
Documentation     A page object to use in User Options settings tests.
...
Library           SeleniumLibrary
Resource          settings.resource
Resource          ../common.resource

*** Variables ***
${OPTIONS SETTINGS PAGE URL}    ${ROOT URL}/settings/options

${ACTIVE BUTTON CLASS}    is-link

*** Keywords ***
Run Set Option Keyword And Come Back
    [Arguments]    ${set option keyword}
    ${current url} =    Get Location
    Go To Options Settings Page
    Run Keyword    ${set option keyword}
    Go To    ${current url}
    
Run Multiple Set Option Keyword And Come Back
    [Arguments]    @{set option keywords}
    ${current url} =    Get Location
    Go To Options Settings Page
    FOR    ${keyword}    IN    @{set option keywords}
        Close Notification
        Run Keyword    ${keyword}
    END
    Go To    ${current url}

Options Tab Is Active
    Run Keyword And Return Status    Element Should Have Class    lnkTabOptions    router-link-active

Options Settings Page Should Be Open
    Location Should Be    ${OPTIONS SETTINGS PAGE URL}

Go To Options Settings Page
    Go Authenticated To    ${OPTIONS SETTINGS PAGE URL}
    Set Footer As Static

Browse To Options Settings Tab
    Browse To Settings Tab    ${OPTIONS TAB}


# Display Mode
Set Option Display Mode To List
    Activate Option Button    btnDisplaymodelist

Set Option Display Mode To Grid
    Activate Option Button    btnDisplaymodegrid

# Show icons
Enable Option Show Icons
    Set Option Checkbox As    showAccountsIcons    True
    
Disable Option Show Icons
    Set Option Checkbox As    showAccountsIcons    False

# Show Password
Set Option Show Password To On Demand
    Activate Option Button    btnGetotponrequesttrue

Set Option Show Password To Constantly
    Activate Option Button    btnGetotponrequestfalse


# Show OTP as dots
Enable Option Show Otp As Dot
    Set Option Checkbox As    showOtpAsDot    True

Disable Option Show Otp As Dot
    Set Option Checkbox As    showOtpAsDot    False


# Password formatting
Enable Option Password Formatting
    Set Option Checkbox As    formatPassword    True
    
Disable Option Password Formatting
    Set Option Checkbox As    formatPassword    False

Set Option Password Formatting By Pair
    Activate Option Button    btnFormatpasswordby2

Set Option Password Formatting By Trio
    Activate Option Button    btnFormatpasswordby3

Set Option Password Formatting By Half
    Activate Option Button    btnFormatpasswordby0.5


# Close OTP after copy
Enable Option Close Otp After Copy
    Set Option Checkbox As    closeOtpOnCopy    True
    
Disable Option Close Otp After Copy
    Set Option Checkbox As    closeOtpOnCopy    False


# Copy OTP on display
Enable Option Copy Otp On Display
    Set Option Checkbox As    copyOtpOnDisplay    True
    
Disable Option Copy Otp On Display
    Set Option Checkbox As    copyOtpOnDisplay    False


# ---------------------------------------------------

Set Option Checkbox As
    [Arguments]    ${checkbox}    ${checked}
    execute javascript  
    ...  document.querySelector('#${checkbox}').classList.remove('is-checkradio');
    Wait For Condition    return document.querySelector('#isReady').value == 'true'
    ${current_state}=  Run Keyword And Return Status    Checkbox Should Be Selected    ${checkbox}
    IF    ${current_state} != ${checked}
        IF    ${checked}
            Select Checkbox    ${checkbox}
        ELSE
            Unselect Checkbox    ${checkbox}
        END
        A Success Notification Should Appear
    END

Activate Option Button
    [Arguments]    ${button}
    Wait For Condition    return document.querySelector('#isReady').value == 'true'
    Wait Until Page Contains Element    ${button}
    ${is active} =    Run Keyword And Return Status    Element Should Have Class    ${button}    ${ACTIVE BUTTON CLASS}
    IF    ${is active} == False
        Click Element    ${button}
        A Success Notification Should Appear
    END