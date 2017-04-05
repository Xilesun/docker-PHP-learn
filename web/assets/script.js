$('#signin').validate()
$('#signup').validate()

function renderComments (data, page) {
  data = JSON.parse(data)
  var comments = {
    count: data[1],
    lastPage: page - 1,
    page: page,
    nextPage: page + 1,
    data: data[0]
  }
  var tplText = $('#new-comment').html()
  var complied = new jSmart(tplText)
  $('.comments').html(complied.fetch(comments))
  $('#content').text(null)
}

$('#comment').validate({
  submitHandler: function(form) {
    event.preventDefault()
    var data = $(form).serialize()
    var page = $(form).attr('data-page')
    var comment = new Comment({'data': data})
    comment.save()
    comment.rerender(page).then((response) => {
      renderComments(response, page)
    })
  }
})

$('body').on('click', '.edit', function () {
  var content = $(this).prevAll('div')
  var id = $(this).attr('data-id')
  $('#content').text(content.text())
  $('#content').focus()
  $('#comment').unbind('submit')
  $('#comment').submit(function (e) {
    e.preventDefault()
    var data = $(this).serialize()
    data += '&_method=PUT'
    var comment = new Comment({'id': id, 'data': data})
    comment.save().then(function (response) {
      content.text(response)
      $('#content').text(null)
    })
  })
  return false
})

$('body').on('click', '.del', function () {
  var id = $(this).attr('data-id')
  var page = $('#comment').attr('data-page')
  var comment = new Comment({'id': id})
  comment.delete().then(() => {
    comment.rerender(page).then((response) => {
      renderComments(response, page)
    })
  })

  return false
})

$('.reply').on('click', function () {
  var user = $(this).attr('data-user')
  var page = $('#comment').attr('data-page')
  var content = 'To ' + user + ': '
  $('#content').text(content)
  $('#content').focus()
  $('#comment').unbind('submit')
  $('#comment').submit(function (e) {
    e.preventDefault()
    var data = $(this).serialize()
    var comment = new Comment({'user': user, 'data': data})
    comment.save()
    comment.rerender(page).then((response) => {
      renderComments(response, page)
    })
  })
  return false
})

$('body').on('click', '.btn-next', function () {
  var page = parseInt($('#comment').attr('data-page'))
  var nextPage = page + 1
  var comment = new Comment()
  var url = '/page/' + nextPage
  comment.rerender(nextPage).then((response) => {
    renderComments(response, nextPage)
    $('#comment').attr('data-page', nextPage)
    window.history.replaceState(null, null, url)
  })
  return false
})

$('body').on('click', '.btn-prev', function () {
  var page = parseInt($('#comment').attr('data-page'))
  var lastPage = page - 1
  var comment = new Comment()
  var url = '/page/' + lastPage
  comment.rerender(lastPage).then((response) => {
    $('#comment').attr('data-page', lastPage)
    renderComments(response, lastPage)
    window.history.replaceState(null, null, url)
  })
  return false
})