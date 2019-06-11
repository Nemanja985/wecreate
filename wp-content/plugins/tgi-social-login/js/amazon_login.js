/**
 * Amazon Login - Login for WordPress
 *
 * @category    Amazon
 * @package     Amazon_Login
 * @copyright   Copyright (c) 2015 Amazon.com
 * @license     http://opensource.org/licenses/Apache-2.0  Apache License, Version 2.0
 */
function r(f){/in/.test(document.readyState)?setTimeout('r('+f+')',9):f()}
r(function(){
    var formTargetIDs = ['registerform', 'loginform'];
    var idx;
    for (idx = 0; idx < formTargetIDs.length; ++idx) {
        var formElement = document.getElementById(formTargetIDs[idx]);
        if(formElement) {
            addLoginToForm(formElement, idx);
        }
    }
});

function addLoginToForm(formElement, idx) {
    var loginElement = buildLoginElement(idx);
    if (document.getElementsByClassName("wp-social-login-widget").length > 0) {
			  insertAfter(loginElement, document.getElementsByClassName("wp-social-login-widget")[0]);
    } else {
		    formElement.appendChild(loginElement);
    }
    //activateLoginWithAmazonButtons is defined in the footer of each page.
    activateLoginWithAmazonButtons('LoginWithAmazon-' + idx);
}

function buildLoginElement(idx) {
    var loginElement = document.createElement('div');
    loginElement.id = 'loginwithamazon_button';
    loginElement.style.textAlign = 'center';
    loginElement.innerHTML = '<a href="#" id="LoginWithAmazon-' + idx + '" style="display:inline-block;"><img border="0" alt="Login with Amazon" src="https://images-na.ssl-images-amazon.com/images/G/01/lwa/btnLWA_gold_156x32.png" width="156" height="32" style="display:block;" /></a>';
    return loginElement;
}

function insertAfter(newNode, referenceNode) {
	  referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}
