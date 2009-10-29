/*globals,
  SWFUpload
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

var Admin = {
  currentTreeItem: null,
  navigationItems: $('.sidebar-tree-item'),
  addButton: $('.add'),
  saveButton: $('.save'),
  deleteButton: $('.delete'),
  searchField: null,
  init: function() {
    this.searchField = $('#search').closest('.toolbar-item');
    this.searchField.hide();
    this.searchField.keyup();
    if (!$.browser.safari) {
      $('.toolbar-item.flexable-space').hide();
    }
    this.showLastView();
    
    this.errorContainer = document.createElement('div');
    this.errorContainer.id = 'error-container';
    $(this.errorContainer).css({
      position: 'absolute',
      'z-index': 100,
      width: 640,
      height: 360,
      top: $(window).height() / 2 - 180,
      left: $(window).width() / 2 - 320,
      display: 'none',
      background: 'rgba(0, 0, 0, 0.75)',
      fontSize: '20px',
      'padding': '40px',
      'color': 'white',
      'text-shadow': 'rgba(0, 0, 0, 0.5) 0px 1px 1px',
      '-webkit-border-radius': '20px',
      '-moz-border-radius': '20px',
      '-webkit-box-shadow': 'rgba(0, 0, 0, 0.5) 0px 10px 20px',
      '-moz-box-shadow': 'rgba(0, 0, 0, 0.5) 0px 10px 20px',
      // 'white-space': 'nowrap',
      'text-overflow': 'ellipsis',
      'overflow': 'hidden'
    });
    $(document.body).append(this.errorContainer);
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
  
  showError: function(errorMessage) {
    $(this.errorContainer).html(errorMessage).css({'display':'block'});
    var self = this;
    var handler = function() {
      $(self.errorContainer).stop(true).fadeOut(3000);
    };
    setTimeout(handler, 2000);
  }
};

$(
  function(){
    
    $('.sidebar-tree-item').click(
      function(e){
        e.preventDefault();
        
        Admin.currentTreeItem = this;
        $('.sidebar-tree-item.selected').removeClass('selected');
        $(Admin.currentTreeItem).addClass('selected');
        var itemURL = $('a', this).attr('href');
        var model = itemURL.split('/')[2];
        window.location.hash = '#' + model;
        $('.add').unbind().click(function() {
          var model = itemURL.split('/')[2];
          $('.status', Admin.currentTreeItem).text('Loading...');
          $.post('/admin/'+model+'/add/',
            {},
            function(data, textStatus){
              $('.status', Admin.currentTreeItem).text('');
              $('#main-container-content').html(data);
              
              // Attaching date picker
              $('#main-container-content input.date').datepicker();
            }
          );
          return false;
        }).mousedown(function(){
          $(this).addClass('toggled-on');
        }).mouseup(function(){
          $(this).removeClass('toggled-on');
        }).mouseout(function(){
          $(this).removeClass('toggled-on');
        });
        
        $('.status', Admin.currentTreeItem).text('Loading...');
        $.get(itemURL,
          {},
          function(data, textStatus){
            $('.status', Admin.currentTreeItem).text('');
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
            // $('#main-container-content a').unbind('click');
            // $('#main-container-content a').live('click', function(event){
            //   event.preventDefault();
            //   $('#main-container-content .item').removeClass('revealed');
            //   $(this).closest('.item').addClass('revealed');
            // });
            
            $('.delete').click(function(event){
              var selectedItems = $('#main-container-content .item.ui-selected');
              if (selectedItems.length) {
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
                    }
                  );
                });
              }
            });
            
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
              
              if ($.browser.safari) {
                if (parseFloat(jQuery.browser.version) > 531) {
                  // console.log(itemURL.split('/')[2]);
                  $('#multi-upload-button').unbind('click').click(function(){
                    var model = itemURL.split('/')[2];
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
                    $('#multi-upload').change(function(){
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
                    });
                  });
                }
              }
              else {
                var model = itemURL.split('/')[2];
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
            
            $('.item').live('dblclick',
              function(e){
                e.preventDefault();
                $('.status', Admin.currentTreeItem).text('Loading...');
                var URL = $('a', this).attr('href');
                $.get(URL,
                  {},
                  function(data, textStatus){
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
                    $('.status', Admin.currentTreeItem).text('');
                    $('#admin-form').ajaxForm();
                    $('.delete').unbind('click').click(function() {
                      var model = URL.split('/')[2];
                      var id = URL.split('/')[4];
                      $('.status', Admin.currentTreeItem).text('Loading...');
                      $.post('/admin/'+model+'/delete/'+id,
                        {},
                        function(data, textStatus){
                          $('.status', Admin.currentTreeItem).text('');
                          $('#main-container-content').html(data);
                        }
                      );
                      return false;
                    }).mousedown(function(){
                      $(this).addClass('toggled-on');
                    }).mouseup(function(){
                      $(this).removeClass('toggled-on');
                    }).mouseout(function(){
                      $(this).removeClass('toggled-on');
                    });
                  }
                );
                return false;
              }
            );
            $('.save').unbind().click(function() {
              $('.status', Admin.currentTreeItem).text('Saving...');
              var id = $('#admin-form').attr('action').split('/')[4];
              $('#admin-form').ajaxSubmit(
              {
                success: function(data){
                  // if (id === '0') {
                  //   $('#main-container-content').html(data);
                  // }
                  $('.status', Admin.currentTreeItem).text('');
                  $('#main-container-content').html(data);
                },
                error: function(request, textStatus, errorThrown) {
                  Admin.showError();
                }
              });
              return false;
            }).mousedown(function(){
              $(this).addClass('toggled-on');
            }).mouseup(function(){
              $(this).removeClass('toggled-on');
            }).mouseout(function(){
              $(this).removeClass('toggled-on');
            });
          }
        );
        return false;
      }
    );
    Admin.init();
  }
);
