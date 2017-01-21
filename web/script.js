var user = $('#user').val();
$('#signup').validate({
  rules: {
    user: {
      required: true,
      rangelength: [6, 12],
      remote: function () {
        var username = $('#user').val();
        return '/validateuser/' + username;
      }
    },
    pass: {
      required: true
    },
    confirm_pass: {
      required: true,
      equalTo: '#pass'
    },
    email: {
      required: true,
      email: true
    }
  },
  messages: {
    user: {
      required: 'Username is required',
      rangelength: 'Username should be 6 ~ 12 letters',
    },
    pass: {
      required: 'Password is required'
    },
    confirm_pass: {
      required: 'Password should be confirmed',
      equalTo: 'Password should be the same'
    },
    email: {
      required: 'E-mail is required',
      email: 'Your E-mail is invalid'
    }
  }
});