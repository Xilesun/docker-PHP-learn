$('#signin').validate()
$('#signup').validate()
$('#comment').validate({
  submitHandler: async function(form) {
    event.preventDefault()
    var data = $(form).serialize()
    var comment = new Comment({'data': data})
    var response = await comment.save()
    response = JSON.parse(response)[0]
    var tplText = $('#new-comment').html()
    var complied = new jSmart(tplText)
    $('.comments').prepend(complied.fetch(response))
  }
})

$('body').on('click', '.edit', function () {
  var _this = this
  var content = $(this).prevAll('div')
  var id = $(this).data('id')
  $('#content').text(content.text())
  $('#content').focus()
  $('#comment').unbind('submit')
  $('#comment').submit(async function (e) {
    e.preventDefault()
    var data = $(this).serialize()
    data += '&_method=PUT'
    var comment = new Comment({'id': id, 'data': data})
    var response = await comment.save()
    content.text(response)
    $('#content').text(null)
  })
  return false
})

$('body').on('click', '.del', function () {
  var _this = this
  var id = $(this).data('id')
  var comment = new Comment({'id': id})
  comment.delete().then(function () {
    $(_this).parent('li').remove()
  })
  return false
})

$('.reply').on('click', function () {
  var user = $(this).data('user')
  var content = 'To ' + user + ': '
  $('#content').text(content)
  $('#content').focus()
  $('#comment').unbind('submit')
  $('#comment').submit(async function (e) {
    e.preventDefault()
    var data = $(this).serialize()
    var comment = new Comment({'user': user, 'data': data})
    var response = await comment.save()
    response = JSON.parse(response)[0]
    var tplText = $('#new-comment').html()
    var complied = new jSmart(tplText)
    $('.comments').prepend(complied.fetch(response))
  })
  return false
})