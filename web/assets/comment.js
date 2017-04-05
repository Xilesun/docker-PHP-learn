function Comment (obj) {
  this.obj = obj || {}
  this.saving = false
}

Comment.prototype = {
  constructor: Comment,
  save: function () {
    var url = '/comments'
    if (this.obj.id) url += '/' + this.obj.id
    if (this.obj.user) url += '/' + this.obj.user
    return new Promise((resolve, reject) => {
      var xhr = new XMLHttpRequest()
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
          this.saving = false
          if (xhr.status === 200) {
            resolve(xhr.responseText)
          }  else {
            reject(new Error(xhr.statusText))
          }
        }
      }
      this.saving = true
      xhr.open('POST', url, true)
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
      xhr.send(this.obj.data)
    })
  },
  delete: function () {
    var url = '/comments/' + this.obj.id
    return new Promise((resolve, reject) => {
      if (this.deleteLoop) {
        clearInterval(this.deleteLoop)
      }
      this.deleteLoop = setInterval(() => {
        if (this.saving) {
          return
        } else {
          clearInterval(this.deleteLoop)
        }
        var xhr = new XMLHttpRequest()
        xhr.onreadystatechange = () => {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              resolve(xhr.responseText)
            }  else {
              reject(new Error(xhr.statusText))
            }
          }
        }
        xhr.open('DELETE', url, true)
        xhr.send()
      }, 50)
    })
  },
  rerender: function (page) {
    var url = '/page/' + page + '?rerender=true'
    return new Promise((resolve, reject) => {
      var xhr = new XMLHttpRequest()
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            resolve(xhr.responseText)
          }  else {
            reject(new Error(xhr.statusText))
          }
        }
      }
      xhr.open('GET', url, true)
      xhr.send()
    })
  }
}