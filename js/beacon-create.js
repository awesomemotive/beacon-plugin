//
// create eBook

jQuery(document).ready(function() {

  var $ = jQuery,
      cats = $('.beacon-by-admin-wrap span.toggle-cat'),
      filter = $('.beacon-by-admin-wrap input.filter'),
      toggleVisible = $('.beacon-by-admin-wrap input[name="toggle_visible"]'),
      form = $('.beacon-by-admin-wrap form'),
      errMsg = $('.beacon-by-admin-wrap .error-no-posts'),
      showAllCat = $('.beacon-by-admin-wrap .all-cat'),
      clearSearch = $('.beacon-by-admin-wrap span.clear'),
      consent = $('input[name="beacon_consent_given"]');


  var handleConsent = function() {

    var hasContent = $(consent).is(':checked');

    if (hasContent) {
      $('require-login').show();
    } else {
      $('require-login').hide();
    }

  };

  handleConsent();


  $('.post_data').each(function() {
    $(this).attr('disabled', 'disabled');
  });

  var refreshCats = function() {

    var filtered = [],
        parent,
        found,
        tmp;

    $('.toggle-cat').each(function() {
      if ( $(this).hasClass('find') ) {
        filtered.push($(this).text());
      }
    });

    if ( filtered.length === 0 ) {
      return showAll();
    }

    filtered = filtered.join('|') + '|';

    $('.post_data').each(function() {

      var cats = $(this).data('cats').split(','),
          title = $(this).data('title') || 'blank',
          found = false, 
          i = cats.length,
          parent = $(this).parent('div').addClass('hide');

      $(this).parent('div').addClass('hide');

      while (i--) {
        tmp = cats[i] + '|';
        if (filtered.indexOf(tmp) !== -1) {
          found = true;
        }
      }

      if (found) {
        $(this).parent('div').removeClass('hide');
      } 

    });
  };


  var showAll = function() {

    $('.toggle-cat').each(function() {
      $(this).removeClass('find');
    });
    $('.post_data').each(function() {
      $(this).parent('div').removeClass('hide');
    });

  };

  var searchFilter = function(terms) {

    var title, 
        label,
        highlight = new RegExp(terms, 'gi'),
        str;

    terms = terms.toLowerCase();

    $('.post_data').each(function() {
      title = $(this).data('title').toLowerCase();
      label = $(this).parent('div').find('label b');
      label.html(label.text());

      if ( title.indexOf(terms) === -1 ) {
        $(this).parent('div').hide();
      } else {
        $(this).parent('div').show();
        str = label.text();
        str = str.replace(highlight, function(str) {
          return '<i>'+str+'</i>';
        });
        label.html(str);
      }
    });
  };


  filter.keyup(function() {
    searchFilter($(this).val());
  });

  toggleVisible.change(function() {
  }); 


  $('.post_toggle').change(function() {
    var checked = $(this).attr('checked') === 'checked',
        data = $(this).parent('div').find('.post_data');
    if ( checked )  {
      data.removeAttr('disabled');
      errMsg.fadeOut('slow');
    } else {
      data.attr('disabled', 'disabled');
    }
  });


  $('.button.create').click(function(e) {
    e.preventDefault();
    var count = 0;
    $('.post_data').each(function() {
      if ( $(this).attr('disabled') ) {
      } else {
        count += 1;
      }
    });

    if ( count ) {
      form.submit();
    } else {
      errMsg.show()
          .slideDown('fast');
    }

  });


  cats.click(function(e) {
    e.preventDefault();

    $(this).toggleClass('find');

    refreshCats();

  });

  showAllCat.click(function(e) {
    e.preventDefault();
    showAll();
  });

  clearSearch.click(function(e) {
    filter.val('');
    searchFilter('');
  });

});

