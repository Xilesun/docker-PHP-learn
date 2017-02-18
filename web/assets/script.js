$('#signin').validate();
$('#signup').validate();
$('#comment').validate();

$('.edit').on('click', function () {
  var content = $(this).prevAll('div').text();
  var id = $(this).data('id');
  $('#content').text(content);
  $('#content').focus();
  $('#comment').attr('action', '/post?id=' + id);
  return false;
});

$('.reply').on('click', function () {
  var user = $(this).data('user');
  var content = 'To ' + user + ': ';
  $('#content').text(content);
  $('#content').focus();
  $('#comment').attr('action', '/post?user=' + user);
  return false;
});