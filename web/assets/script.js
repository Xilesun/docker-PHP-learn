$('#signin').validate()
$('#signup').validate()
$('#comment').validate()

$('.edit').on('click', function () {
  var content = $(this).prevAll('div').text()
  var id = $(this).data('id')
  $('#content').text(content)
  $('#content').focus()
  $('#comment').attr('action', '/comments/' + id)
  $('#comment').append("<input type='hidden' name='_method' value='PUT' />")
  return false
})

$('.del').on('click', function () {
  var _this = this
  $.ajax({
    url: $(this).children('a').attr('href'),
    type: 'DELETE',
    success: function(data) {
      $(_this).parent('li').remove();
    }
  })
  return false
})

$('.reply').on('click', function () {
  var user = $(this).data('user')
  var content = 'To ' + user + ': '
  $('#content').text(content)
  $('#content').focus()
  $('#comment').attr('action', '/comments/' + user)
  return false
})