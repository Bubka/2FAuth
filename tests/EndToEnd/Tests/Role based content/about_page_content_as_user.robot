*** Settings ***
Documentation     A test suite containing tests related to the about page static content.
Suite Setup       Run Keywords
...                   Open Custom Browser
...                   AND    Play User Sign In Workflow
...                   AND    Go To About Page
...                   AND    Set Footer As Static
Suite Teardown    Close All Browsers
Resource          ../../Pages/about_page.robot
Resource          ../../common.resource
Library    String

*** Test Cases ***
Environment Vars Should Be Visible To User
    Environment Vars Should Be Visible

User Preferences Should Be Visible To User
    User Preferences Should Be Visible

User Preferences Should Have A Value
    Block Vars Should Have A Value    listUserPreferences

Copying User Preferences Should Notify On Success
    Copying Block Vars Should Notify On Success    btnCopyUserPreferences
    
Admin Settings Should Not Be Visible
    Page Should Not Contain    id:listAdminSettings