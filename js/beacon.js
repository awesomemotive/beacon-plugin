BN = {} || BN;

BN.showIssues = function() { };

BN.receiveMessage = function(e) {

  var $ = jQuery;

	if ( e.origin.indexOf('//:beacon.') ) {
		var data = $.parseJSON( e.data ),
			html = [];

		BN.logged_on = (typeof data.publication !== 'undefined');
		BN.title = data.publication;	
		BN.url = e.origin + '/' + data.url;
		BN.issues = data.issues;
		BN.showIssues();

		html.push('<hr />');
		html.push('<p>');

		if ( BN.logged_on ) {
			html.push('<i class="fa fa-check"></i>');
			html.push('Beacon logged in as: ');
			html.push(BN.title);
		} else {
			$('.prompt-login').show();
			$('.requires-login').css('opacity', 0.6);
			$('.requires-login button').attr('disabled', 'disabled');
			$('.requires-login a').attr('disabled', 'disabled');
			html.push('<p>');
			html.push('<i class="fa fa-warning"></i>');
			html.push('Not logged into Beacon - ');
			html.push('<a href="'+ e.origin +'" target="_blank">');
			html.push('Log in');
			html.push('</a>');
		}

		html.push('</p>');

		$('.beacon-account').html(html.join(''));


		if ( $('.beacon-status') ) {
			$('.beacon-status').html(html);	
			if (BN.logged_on) {
				$('.beacon-status').addClass('success');
			} else {
				$('.beacon-status').addClass('fail');
			}
		}
	}
};



window.addEventListener('message', function(e) {
	BN.receiveMessage(e);
}, false );
