(function($) {
  $.fn.categoriesTreeview = function(options) {
    var tree = $.extend({}, $.fn.categoriesTreeview.defaults, options);

    (function($) {
      $.fn.folder = function() {
        function getCookie(name) {
          var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
          return v ? v[2] : '';
        }
        function setCookie(name, value) {
          var d = new Date;
          d.setTime(d.getTime() + 24*60*60*1000*365);
          document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
        }
        return this.each(function() {
          var that = $(this);
          var folder = that.closest('.checkbox').next('div.folder');
          that.set = function(new_class) {
            if(folder.data('id')) {
              setCookie('folder_' + folder.data('id'), new_class);
            }
            return that;
          };
          that.get = function() {
            return getCookie('folder_' + folder.data('id')) == '' ? 'closed' : 'opened';
          };
          that.on({
            'click': function() {
              if(folder.hasClass('closed')) {
                that.trigger('open');
              } else {
                that.trigger('close');
              }
            },
            'show': function(e, speed) {
              that.removeClass('fa-folder').addClass('fa-folder-open');
              folder.removeClass('closed').addClass('opened');
              if(speed) {
                folder.show(speed);
              } else if(folder.css('display') != '') {
                folder.css('display', '');
              }
            },
            'open': function() {
              that.set('opened').trigger('show', 'fast');
            },
            'hide': function(e, speed) {
              that.removeClass('fa-folder-open').addClass('fa-folder');
              folder.removeClass('opened').addClass('closed');
              if(speed) {
                folder.hide(speed);
              } else {
                folder.css('display', 'none');
              }
            },
            'close': function() {
              that.set('').trigger('hide', 'fast');
              folder.find('i.folder').trigger('hide');
            },
            'restore': function() {
              if(folder.has('input[type="checkbox"]:checked').length > 0) {
                that.trigger('show');
              } else {
                that.trigger(that.get() == 'opened' ? 'show' : 'hide');
              }
            }
          });
        });
      }

      }(jQuery));

    var that = $(this);
    $(document).ready(function() {
      if(tree.display) {
        $('#categories').show();
      }
    });
    var folders = that.find('i.folder:not(.root)').folder();
    if(tree.start == 'filtered') {
      that.find('input[type="checkbox"]').on('click', function() {
        if(!$(this).is(':checked')) {
          if(!that.find('input[type="checkbox"]:checked').length) {
            $(this).prop('checked', true);
          }
        }
      });
      that.find('.checkbox.current').removeClass('current');
      that.find('i.fa.fa-circle').remove();
    }
    if(typeof tree.remove !== 'undefined') {
      if(tree.remove.split(',').indexOf('checkboxes') !== -1) {
        that.find('input[type="checkbox"]').remove();
      }
      if(tree.remove.split(',').indexOf('status') !== -1) {
        that.find('i.fa.fa-circle').remove();
      }
    }
    tree.input.on({
      'input': function(){
        var val = $(this).val();
        var filtered = false;
        var results = false;
        that.find('input[type="checkbox"]').each(function() {
          if($(this).is(":checked") || (val != '' && $(this).data('name').toLowerCase().indexOf(val.toLowerCase()) !== -1)) {
            $(this).parent('.checkbox').show();
            results = true;
          } else {
            $(this).parent('.checkbox').hide();
            filtered = true;
          }
        });
        if(filtered && results) {
          that.addClass('filtered');
          if(val != '') {
            that.collapsed('hide').addClass('on');
          } else {
            that.collapsed('hide').removeClass('on');
          }
        } else {
          if(!results) {
            that.find('.checkbox').show();
          }
          that.removeClass('filtered');
        }
      },
      'blur': function() {
        if(tree.start == 'filtered') {
          if(!that.hasClass('filtered')) {
            $(this).trigger('input');
          }
        } else if($(this).val() == '')  {
          that.reset();
        }
      }
    });
    that.expanded = function(mode) {
      folders.trigger(mode ? mode :'open');
      return that;
    };
    that.collapsed = function(mode) {
      folders.trigger(mode ? mode : 'close');
      return that;
    };
    that.restored = function() {
      folders.trigger('restore');
      return that;
    };
    that.filtered = function() {
      tree.input.val('').trigger('input');
      return that;
    };

    that.on({
      'mouseenter': function() {
        if(tree.start == 'filtered' && that.hasClass('filtered') && tree.input.val() == '') {
          that.restored().removeClass('filtered').find('.checkbox').show().promise().done(that.scroll());
        }
      },
      'mouseleave': function() {
        if(tree.start == 'filtered') {
          that.reset();
        } else {
          tree.input.val('').trigger('blur');
        }
      }
    });

    that.reset = function(init) {
      $(document).ready(function() {
        that.removeClass('filtered').find('.checkbox').show();
        that[tree.start]().promise().done(that.scroll(init));
      });
      return that;
    }
    that.scroll = function(init) {
      var current = that.find('.current');
      var scroll_to = that.find('input[type="checkbox"]:checked').length ? that.find('input[type="checkbox"]:checked').first() : (current.length ? current : false);
      if(scroll_to) {
        that.scrollTop(that.scrollTop() + scroll_to.offset().top - that.offset().top - (tree.start == 'filtered' ? 10 : 5));
      }
      if(init == true && tree.start != 'filtered' && current.length) {
        that.find('.checkbox.current').find('i.folder').trigger('show').parents('div.folder').prev('.checkbox').find('i.folder').trigger('show');
      }
      return that;
    }
    that.reset(true);

    return this;
  };
  $.fn.categoriesTreeview.defaults = {
    start: 'restored',
    display: true,
    input: $('input[name="query"]'),
  };
  }(jQuery));