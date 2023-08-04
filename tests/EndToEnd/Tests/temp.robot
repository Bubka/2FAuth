# *** Settings ***
# Documentation     A test suite to check available links on the login page.
# Suite Setup       Open Custom Browser
# Suite Teardown    Close Browser
# Resource          Pages/login_page.robot
# Resource          workflows.resource

# *** Variables ***
# ${ROOT URL}    https://${SERVER}/

# *** Test Cases ***
# My Test
#     Play Delete Current User Account Workflow
