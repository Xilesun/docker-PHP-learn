function Comment (obj) {
  this.obj = obj
}

Comment.prototype = {
  constructor: Comment,
  save: function () {
    var url = '/comments'
    var _this = this
    if (this.obj.id) url += '/' + this.obj.id
    if (this.obj.user) url += '/' + this.obj.user
    return new Promise(function (resolve, reject) {
      var xhr = new XMLHttpRequest()
      xhr.open('POST', url, true)
      xhr.onload = function () {
        if (xhr.status === 200) {
          resolve(xhr.responseText)
        }  else {
          reject(new Error(xhr.statusText))
        }
      }
      xhr.onerror = function () {
        reject(new Error(xhr.statusText))
      }
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
      xhr.send(_this.obj.data)
    })
  },
  delete: function () {
    var url = '/comments/' + this.obj.id
    return new Promise(function (resolve, reject) {
      var xhr = new XMLHttpRequest()
      xhr.open('DELETE', url, true)
      xhr.onload = function () {
        if (xhr.status === 200) {
          resolve(xhr.responseText)
        }  else {
          reject(new Error(xhr.statusText))
        }
      }
      xhr.onerror = function () {
        reject(new Error(xhr.statusText))
      }
      xhr.send()
    })
  }
}