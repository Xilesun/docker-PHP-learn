//Common functions
function addEventHandler (target, type, func) {
  if (target.addEventListener) {
    target.addEventListener(type, func, false);
  } else if (target.attachEvent) {
    target.attachEvent('on' + type, func);
  } else {
    target['on' + type] = func;
  }
}
//Validation
var notice = document.getElementsByClassName('notice');
var form = document.forms[0];
var user = form.user;
var user_notice = notice[0];
var pass = form.pass;
var pass_notice = notice[1];
var confirm_pass = form.confirm_pass;
var confirm_pass_notice = document.getElementsByClassName('notice')[2];
var email = form.email;
var email_notice = document.getElementsByClassName('notice')[3];
function ValidateUser () {
  if (user.value.trim() === '') {
    user.className = 'warn';
    user_notice.innerHTML = 'a Username is required';
    return false;
  } else if (user.value.length < 6 || user.value.length > 12) {
    user.className = 'warn';
    user_notice.innerHTML = 'Username should be 6 ~ 12 letters';
    return false;
  } else {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
      if (this.readyState === 4 && this.status === 200) {
        if (this.responseText === 'Username exists') {
          user.className = 'warn';
          user_notice.innerHTML = this.responseText;
          return false;
        } else {
          user.className = '';
          user_notice.innerHTML = '';
          return true;
        }
      }
    }
    xhttp.open('GET', '/validateuser/' + user.value, true);
    xhttp.send();
  }
}
function ValidatePass () {
  if (pass.value.trim() === '') {
    pass.className = 'warn';
    pass_notice.innerHTML = 'Password is required';
    return false;
  } else {
    pass.className = '';
    pass_notice.innerHTML = '';
    return true;
  }
}
function ValidateConfirmPass () {
  if (confirm_pass.value.trim() === '') {
    confirm_pass.className = 'warn';
    confirm_pass_notice.innerHTML = 'Password should be confirmed';
    return false;
  } else if (pass.value !== confirm_pass.value) {
    confirm_pass.className = 'warn';
    confirm_pass_notice.innerHTML = 'two Passwords should be the same';
    return false;
  } else {
    confirm_pass.className = '';
    confirm_pass_notice.innerHTML = '';
    return true;
  }
}
function ValidateEmail () {
  var re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
  if (email.value.trim() === '') {
    email.className = 'warn';
    email_notice.innerHTML = 'E-mail is required';
    return false;
  } else if (!re.test(email.value)) {
    email.className = 'warn';
    email_notice.innerHTML = 'Your E-mail is invalid';
    return false;
  } else {
    email.className = '';
    email_notice.innerHTML = '';
    return true;
  }
}
function ValidateSubmit (e) {
  if (!ValidateUser() && !ValidatePass() && !ValidateConfirmPass() && !ValidateEmail()) {
    e.preventDefault();
  }
}
addEventHandler(user, 'input', ValidateUser);
addEventHandler(pass, 'input', ValidatePass);
addEventHandler(confirm_pass, 'input', ValidateConfirmPass);
addEventHandler(email, 'input', ValidateEmail);
addEventHandler(form, 'submit', ValidateSubmit);