/*globals,
  SWFUpload,
  SWFObject,
  uploader,
  WavePanel
*/

if (typeof window.console == 'undefined') {
  window.console = {};
  console.log = console.info = console.warn = console.debug = console.group = console.groupEnd = console.error = function(){};
}

if (!('console' in window)) {
  if (!('group' in console)) {
    console.group = console.groupEnd = function(){};
  }
}

var $A;

$A = Array.from = function(iterable)
{
  if (!iterable) {
    return [];
  }

  if (iterable.toArray) {
    return iterable.toArray();
  } else {
    var results = [];
    for (var i = 0, length = iterable.length; i < length; i++) {
      results.push(iterable[i]);
    }
    return results;
  }
};

$.extend( Function.prototype, {
  bind: function() {
    var __method = this, args = $A(arguments), object = args.shift();

    return function() {
      return __method.apply(object, args.concat($A(arguments)));
    };
  }
});

var Admin = {
  currentTreeItem: null,
  navigationItems: $('.sidebar-tree-item'),
  addButton: $('.add'),
  saveButton: $('.save'),
  deleteButton: $('.delete'),
  searchField: null,
  
  debug: null,
  
  init: function() {
    this.searchField = $('#search').closest('.toolbar-item');
    this.searchField.hide();
    this.searchField.keyup();
    if (!$.browser.safari) {
      $('.toolbar-item.flexable-space').hide();
    }
    
    this.debug = new Admin.Debug();
    
    this.initToolbar();
    this.bindNavigation();
    this.bindControls();
    
    this.showLastView();
  },
  
  showError: function(errorMessage) {
    this.debug.message(errorMessage);
  },
  
  initToolbar: function() {
    $('button.toolbar-item').mousedown(function(){
      $(this).addClass('toggled-on');
    }).mouseup(function(){
      $(this).removeClass('toggled-on');
    }).mouseout(function(){
      $(this).removeClass('toggled-on');
    });
  },
  
  setCurrentTreeItem: function(itemElement) {
    this.currentTreeItem = null;
    this.currentTreeItem = new Admin.Item(this, itemElement);
    console.log(this.currentTreeItem);
    if (this.currentTreeItem && this.currentTreeItem.url) {
      window.location.hash = '#' + this.currentTreeItem.model;
      this.currentTreeItem.select();
    }
  },
  
  showLastView: function() {
    if (window.location.hash) {
      var model = window.location.hash.match(/#(\w+)/)[1];
      $('.sidebar-tree-item a[href*='+ model +']').closest('.sidebar-tree-item').trigger('click');
    }
  },
  
  findAll: function(){

  },
  find: function(){

  },
  save: function(){

  },
  remove: function(){

  },
  
  bindNavigation: function() {
    $('.sidebar-tree-item').click(
      function(e, element){
        e.preventDefault();
        this.setCurrentTreeItem(e.currentTarget);
        return false;
      }.bind(this)
    );
  },
  
  bindControls: function() {
    var self = this;
    $('.add').unbind().click(function(e) {
      e.preventDefault();
      this.currentTreeItem.add();
      return false;
    }.bind(this));
    
    $('.save').unbind().click(function(e) {
      e.preventDefault();
      this.currentTreeItem.save();
      return false;
    }.bind(this));
    
    $('.delete').unbind('click').click(function(e) {
      e.preventDefault();
      this.currentTreeItem.remove();
      return false;
    }.bind(this));
    
    $('.item').live('dblclick',
      function(e){
        console.log(e);
        e.preventDefault();
        self.currentTreeItem.edit(this);
        return false;
      }
    );
    $('.sidebar-tree-item .wavvy').click(function(){
      this.addWave();
    }.bind(this));
  },

  addWave: function() {
    if (!window.WavePanel) {
      var waveAPIscript = document.createElement('script');
      waveAPIscript.type = 'text/javascript';
      waveAPIscript.src = 'http://wave-api.appspot.com/public/embed.js';
      var waveGadgets = document.createElement('script');
      waveGadgets.type = 'text/javascript';
      waveGadgets.src = 'https://wave.google.com/gadgets/js/core:rpc?debug=1&c=1';
      var head = document.getElementsByTagName('head')[0];
      head.appendChild(waveGadgets);
      head.appendChild(waveAPIscript);
      
      waveGadgets.addEventListener('load', function() {
        waveAPIscript.addEventListener('load', function() {
          this.enableWave();
        }.bind(this));
      }.bind(this));
    }
  },
  
  enableWave: function() {
    console.log('Enabling wave.');
    var waveUI = new WavePanel.UIConfig();
    // waveUI.setBgcolor('white');
    waveUI.setFont('Lucida Grande');
    waveUI.setFontSize(10);
    waveUI.setHeaderEnabled(true);
    waveUI.setToolbarEnabled(true);
    
    var waveURL = 'https://wave.google.com/wave/';
    var wavePanel = new WavePanel(waveURL);
    
    if (waveUI) {
      wavePanel.setUIConfigObject(waveUI);
    }
    
    var waveContainer = $('#main-container-content').get(0);
    $(waveContainer).empty();
    var search = 'tag:zsystem';
    wavePanel.loadSearch(search);
    wavePanel.init(waveContainer);
    wavePanel.setToolbarVisible(true);
    window.wavePanel = wavePanel;
  }
};

Admin.Item = function(parent, itemElement) {
  this.parent = parent;
  this.element = itemElement;
  this.url = $('a', this.element).attr('href');
  if (!this.url) {
    return;
  }
  this.model = this.url.split('/')[2];
  return this;
};

Admin.Item.prototype = {
  select: function() {
    $('.sidebar-tree-item.selected').removeClass('selected');
    $(this.element).addClass('selected');
    this.setStatus('Loading...');
    $.get(this.url,
      {},
      function(data, textStatus){
        $('.status', this.element).text('');
        $('#main-container-content').html(data);
        
        $('.item').click(
          function(e){
            e.preventDefault();
            e.stopPropagation();
            $('.item.ui-selected').removeClass('ui-selected');
            $(this).addClass('ui-selected');
            return false;
          }
        );
        
        if (!$('#main-container-content').hasClass('ui-selectable')) {
          $('#main-container-content').selectable({
            filter: '.item',
            helperStyles: {
              border: '1px solid rgba(255,255,255, 0.5)',
              background: 'rgba(0,0,0,0.25)'
            }
          });
        }
        
        if ($('#main-container-content .gallery-view')) {
          if (!$('#toolbar .multi-upload').length) {
            $('#toolbar').append('<button class="toolbar-item toggleable multi-upload" id="multi-upload-button">'+
                    '<div class="toolbar-icon"></div>'+
                    '<div class="toolbar-label">Много файлов</div>'+
                  '</button>');
          }
          $('#main-container-content').unbind('dragenter').bind('dragenter', function(event){
            console.log('Started drag');
            event.stopPropagation();
            event.preventDefault();
            
          });
          $('#main-container-content').unbind('dragover').bind('dragover', function(event){
            event.stopPropagation();
            event.preventDefault();
          });
          $('#main-container-content').unbind('drop').bind('drop',
            function(event){
              event.stopPropagation();
              event.preventDefault();
              var model = this.model;
              var dt = event.originalEvent.dataTransfer;
              var files = dt.files;
              var url = '/admin/'+model+'/single/';
              console.log(files);
              var handler = function(xhr) {
                $('#main-container-content').html(xhr.responseText);
                Admin.currentTreeItem.select();
              };
              for (var idx = 0; idx < files.length; idx++) {
                var file = files[idx];
                this.uploadFile(file, url, handler);
              }
              
            }.bind(this)
          );
          
          if ($.browser.safari) {
            if (parseFloat(jQuery.browser.version) > 531) {
              $('#multi-upload-button').unbind('click').click(function(){
                var model = this.model;
                $(document.body).append('<form id="multi-upload-form" action="/admin/'+model+'/multiple/" method="POST" enctype="multipart/form-data" style="position: absolute; left: -2000px; top: -2000px;">'+
                  '<label for="multi-upload">Кинуть пачку:</label>'+
                  '<input type="file" name="files[]" id="multi-upload" multiple />'+
                  '<button id="send-multiple">Sned</button>'+
                  '</form>');

                  // $('#send-multiple').click(function(){
                    // $('#multi-upload-form').submit();
                    // $('#multi-upload-form').ajaxSubmit();
                  // });
                  var test = function() {
                    return 'You uploading some files.';
                  };
                  window.onbeforeunload = test;
                $('#multi-upload').trigger('click');
                $('#multi-upload').change(
                  function(){
                    this.upload();
                  }.bind(this)
                );
              }.bind(this)
              );
            }
          }
          else {
            var model = this.currentModel;
            $(document.body).append('<form id="multi-upload-form" action="/admin/'+model+'/multiple/" method="POST" enctype="multipart/form-data" style="position: absolute; left: -2000px; top: -2000px;">'+
              '<div id="uploader-movie"></div>'+
            '</form>');

            var uploaderMovie = new SWFObject('/admin/media/files/FileUpload.swf','uploader', 0, 0,'9');
            uploaderMovie.addParam("allowscriptaccess","always");
            uploaderMovie.addParam("wmode", 'transparent');
            uploaderMovie.write('uploader-movie');
            console.log(uploaderMovie);
            $(window).bind('listReady', function(){
              uploader.selected = function(data) {
                console.log(data);
              };
              uploader.browse();
            });
            $('#uploader').css({
              position: 'absolute',
              top: $('#multi-upload-button').offset().top,
              left: $('#multi-upload-button').offset().left,
              width: $('#multi-upload-button').width(),
              height: $('#multi-upload-button').height()
            });

            // $('#multi-upload-button').unbind('click').click(function(){
            //   
            //   // 
            //   // uploader.fireCallback = function(test){
            //   //   console.log(test);
            //   // };
            //   // fireCallback.
            // });
          }

          // $('#multi-upload-button').bind('click', function(){
          //   
          // });
          // window.onload = function () {
            // var settings = {
            //   upload_url : "http://www.swfupload.org/upload.php",
            //   flash_url : "/admin/media/files/swfupload.swf",
            //   file_size_limit : "200 MB"//,
            //   // upload_start_handler : myCustomUploadStartEventHandler,
            //   // upload_success_handler : myCustomUploadSuccessEventHandler
            //   // button_placeholder_id: 'multi-upload-button'
            // };
            // 
            // window.swfu = new SWFUpload(settings);
          // };
        }
        
        $(window).bind('keydown', function(event){
          // console.log($('#main-container-content #admin-form').length);
          if ($('#main-container-content .gallery-view').length > 0) {
            // event.preventDefault();
            var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : null;
            // console.log(keyCode);
            var selectedItems = $('#main-container-content .item.ui-selected');
            switch (keyCode) {
              //Left/right keys
              case 37:
                event.preventDefault();
                if (selectedItems.length) {
                  selectedItems.removeClass('ui-selected');
                  var prevItem = selectedItems.prev('.item');
                  if (prevItem.length) {
                    prevItem.addClass('ui-selected');
                  }
                  else {
                    $('#main-container-content .item:last').addClass('ui-selected');
                  }
                }
                else {
                  $('#main-container-content .item:last').addClass('ui-selected');
                }
                break;
              case 13:
                var currentItem = $('#main-container-content .ui-selected');
                if (currentItem.length) {
                  currentItem.trigger('dblclick');
                }
                break;
              case 39:
                event.preventDefault();
                if (selectedItems.length) {
                  selectedItems.removeClass('ui-selected');
                  var nextItem = selectedItems.next('.item');
                  if (nextItem.length) {
                    nextItem.addClass('ui-selected');
                  }
                  else {
                    $('#main-container-content .item:first').addClass('ui-selected');
                  }
                }
                else {
                  $('#main-container-content .item:first').addClass('ui-selected');
                }
                break;
              //Backspace and Delete keys
              case 73:
              case 8:
                event.preventDefault();
                if (selectedItems.length) {
                  console.log(selectedItems);
                  $(selectedItems).each(function(idx, selectedItem){
                    var URL = $('a', selectedItem).attr('href');
                    var model = URL.split('/')[2];
                    var id = URL.split('/')[4];
                    $('.status', Admin.currentTreeItem).text('Deleting...');
                    $(selectedItem).addClass('deleting');
                    $.post('/admin/'+model+'/delete/'+id,
                      {},
                      function(data, textStatus){
                        $('.status', Admin.currentTreeItem).text('');
                        $(selectedItem).remove();
                        var prevItem = selectedItems.prev('.item');
                        if (prevItem.length) {
                          prevItem.addClass('ui-selected');
                        }
                        else {
                          $('#main-container-content .item:last').addClass('ui-selected');
                        }
                      }
                    );
                  });
                }
                break;
            }
          }
        });
      }.bind(this)
    );
  },
  
  upload: function() {
    if ($.browser.safari) {
      this.setStatus('Uploading...');
      var uploading = function() {
        return 'You uploading some files.';
      };
      window.onbeforeunload = uploading;
      $('#multi-upload-form').ajaxSubmit({
        success: function(data){
          $('#main-container-content').html(data);
          $('#main-container-content .gallery-view').selectable({
            filter: '.item',
            helperStyles: {
              border: '1px solid rgba(255,255,255, 0.5)',
              background: 'rgba(0,0,0,0.25)'
            }
          });
          window.onbeforeunload = null;
        }
      });
      $('#multi-upload-form').remove();
    } else {
      
    }
  },
  
  uploadFile: function(file, url, handler) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4) {
        handler(xhr);
      }
    };
    xhr.open('post', url || '?upload=true', true);
    xhr.setRequestHeader('If-Modified-Since', 'Mon, 26 Jul 1997 05:00:00 GMT');
    xhr.setRequestHeader('Cache-Control', 'no-cache');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('X-File-Name', file.fileName);
    xhr.setRequestHeader('X-File-Size', file.fileSize);
    xhr.setRequestHeader('Content-Type', 'multipart/form-data');
    xhr.send(file);
  },
  
  add: function() {
    var model = this.model;
    this.setStatus('Adding...');
    $.post('/admin/'+model+'/add/',
      {},
      function(data, textStatus){
        $('.status', this.element).text('');
        $('#main-container-content').html(data);
        
        // Attaching date picker
        $('#main-container-content input.date').datepicker();
      }.bind(this)
    );
  },
  
  save: function() {
    this.setStatus('Saving...');
    var id = $('#admin-form').attr('action').split('/')[4];
    $('#admin-form').ajaxSubmit(
    {
      success: function(data){
        this.setStatus('');
        $('#main-container-content').html(data);
      }.bind(this),
      error: function(request, textStatus, errorThrown) {
        this.parent.showError(errorThrown);
      }.bind(this)
    });
  },
  
  edit: function(itemElement) {
    var URL = $('a', itemElement).attr('href');
    var callback = function(data, textStatus){
      $('#main-container-content').html(data);
      
      $('#main-container-content textarea').focus(function(){
        $(this).addClass('uploading');
        $(this).parent().append('<div class="image-append">Прикрепить картинку</div>');
      }).blur(function(){
        if (!$('.image-append', $(this).parent()).hasClass('hover'))
        {
          $('.image-append, input[type="file"]', $(this).parent()).remove();
        }
      });
      
      // $('#main-container-content .related').append('<div class="related-toolbar"><div class="filler"></div><div class="related-toolbar-content"><button>+</button></div></div>');
      var selectedItems = $('#main-container-content .related select option:selected');
      $('#main-container-content .related select option').dblclick(function(e){
        var parent = $(e.target).closest('select');
        var relatedModelName = $(parent).attr('id');
        var relatedId = parseInt($(e.target).attr('value'), 10);
        $('#main-container-content .related select option').removeAttr('selected');
        selectedItems.attr('selected', 'selected');
        $.get('/admin/'+ relatedModelName +'/edit/'+ relatedId,{},function(data, textStatus){
          if (!$('#related-container').length) {
            $('#main-container-content').append('<div id="related-container"><div id="related-container-content"></div></div>');
          }
          $('#admin-form').css({opacity: 0.25});
          $('#related-container-content').html(data);
          $('#related-container-content #admin-form')
            .attr('id', 'admin-related-form')
            .attr('name', 'admin-related-form');
          $('#related-container-content').append('<button id="save-related">Save</button><button id="cancel-related">Cancel</button>');
          $('#cancel-related').click(function(){
            $('#main-container-content #related-container').remove();
            $('#admin-form').css({opacity: 1});
          });
          $('#save-related').click(function(){
            this.setStatus('Saving...');
            $('#admin-related-form').ajaxSubmit({
              success: function(data){
                this.setStatus('');
                $('#main-container-content #related-container').remove();
                $('#admin-form').css({opacity: 1});
              }.bind(this),
              error: function(request, textStatus, errorThrown) {
                this.parent.showError(errorThrown);
              }.bind(this)
            });
          }.bind(this));
        }.bind(this));
        // console.log(parent);
      }.bind(this));
      
      // Attaching date picker
      $('#main-container-content input.date').datepicker();
      
      $('#main-container-content .image-append').live('mouseover',
        function(){ $(this).addClass('hover'); }
      );
      $('#main-container-content .image-append').live('mouseout',
        function(){ $(this).removeClass('hover'); }
      );
      $('#main-container-content .image-append').live('click', function(event){
        var appender = this;
        $(this).parent().append('<input type="file" name="attach-file" id="attach-file"/>');
        $('input[type="file"]', $(this).parent()).css({visiblity:'hidden'}).click().change(function(){
          $.ajaxFileUpload(
              {
            url:'/admin/uploader/',
            secureuri:false,
            fileElementId:'attach-file',
            dataType: 'json',
            success: function (data, status)
            {
              /* show success message */
              var element = $('#main-container-content textarea.uploading');
              var oldText = element.val();
              $(element).val(oldText+'<img src="'+data.msg+'"/>');
              $(appender).remove();
              $('#attach-file').remove();
            },
            error: function (data, status, e)
            {
              /* handle error */
            }
          });
          // $().ajaxFileUpload();
        });
      });
      $('.status', this.element).text('');
      $('#admin-form').ajaxForm();
      // this.deleteItem();
    }.bind(this);
    
    $.get(URL, {}, callback);
  },
  
  remove: function() {
    var model = this.model;
    var id = this.url.split('/')[4];
    
    var selectedItems = $('#main-container-content .item.ui-selected');
    if (selectedItems.length) {
      $(selectedItems).each(function(idx, selectedItem){
        var URL = $('a', selectedItem).attr('href');
        var model = URL.split('/')[2];
        var id = URL.split('/')[4];
        this.setStatus('Deleting...');
        $(selectedItem).addClass('deleting');
        $.post('/admin/'+model+'/delete/'+id,
          {},
          function(data, textStatus){
            this.setStatus('');
            $(selectedItem).remove();
          }.bind(this)
        );
      }.bind(this));
    }
    else {
      this.setStatus('Deleting...');
      $.post('/admin/'+model+'/delete/'+id,
        {},
        function(data, textStatus){
          $('.status', this.element).text('');
          $('#main-container-content').html(data);
        }.bind(this)
      );
    }
  },
  
  setStatus: function(statusMessage) {
    $('.status', this.element).text(statusMessage);
  }
};

Admin.Debug = function() {
  this.init();
  return this;
};

Admin.Debug.prototype = {
  init: function() {
    this._initDebugBox();
  },
  
  _initDebugBox: function() {
    this.errorContainer = document.createElement('div');
    this.errorContainer.id = 'error-container';
    $(document.body).append(this.errorContainer);
    console.log(this.errorContainer);
  },
  
  message: function(errorMessage) {
    console.debug(this.errorContainer);
    if (errorMessage) {
      $(this.errorContainer).html(errorMessage).css({'display':'block'});
      var self = this;
      var handler = function() {
        $(self.errorContainer).stop(true).fadeOut(3000);
      };
      setTimeout(handler, 2000);
    }
  }
};

$(
  function(){
    Admin.init();
  }
);