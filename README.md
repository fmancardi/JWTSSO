# JWTSSO
Plugin for MantisBT (https://www.mantisbt.org/)

# Context
System A needs to call features (pages) present on a MantisBT installation.  
Users will be create on System A and on MantisBT having same username.  
MantisBT feature (bug_report_page.php) will be called in this way:  

https://dexterlaboratory.com/mantis/bug_report_page.php?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6IkRlZSBEZWUifQ.d4VOzT-ux0Xwc3UujdHMpIHyDRSHmgYdemsVDHpi_0Y  

token is the JSON Web Token computed for:  
Payload:  
{  
  "username": "Dee Dee"  
}  
  
Header   
{  
  "typ": "JWT",  
  "alg": "HS256"  
}    
  
using secret key: dexter.laboratory

# Plugin pages
## pages/login_sso_page.php  
will be set as login page, for the features (pages) that we will want be called from System A.

## pages/sso.php  
will be set as credential page, when plugin method auth_user_flags() will be called by login_sso_page.  
*sso.php* is the place where token decoding and user authentication will be done.  

# Changes to MantisBT standard code
function auth_flags() present in authentication_api.php, was modified to fire event EVENT_AUTH_USER_FLAGS, even when non user is provided.

# Additional References
https://github.com/mantisbt/mantisbt/pull/1070#issuecomment-383283035  
https://github.com/mantisbt-plugins/SampleAuth/pull/5  
