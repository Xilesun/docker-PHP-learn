$('#signup').validate({
  rules: {
    user: {
      remote: function () {
        var username = $('#user').val();
        return '/validateuser/' + username;
      }
    }
  }
});