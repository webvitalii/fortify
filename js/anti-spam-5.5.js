/*
Anti-spam plugin
No spam in comments. No captcha.
wordpress.org/plugins/anti-spam/
*/

"use strict";
(function() {
	function anti_spam_init() {

		var i,
			len,
			elements,
			answer = '',
			current_year = new Date().getFullYear(),
			dynamic_control;

		elements = document.querySelectorAll('.antispam-group');
		len = elements.length;
		for (i = 0; i < len; i++) { // hide inputs from users
			elements[i].style.display = 'none';
		}

		elements = document.querySelectorAll('.antispam-control-a');
		if ((elements) && (elements.length > 0)) { // get the answer
			answer = elements[0].value;
		}

		elements = document.querySelectorAll('.antispam-control-q');
		len = elements.length;
		for (i = 0; i < len; i++) { // set answer into other input instead of user
			elements[i].value = answer;
		}
		
		// clear value of the empty input because some themes are adding some value for all inputs
		elements = document.querySelectorAll('.antispam-control-e');
		len = elements.length;
		for (i = 0; i < len; i++) {
			elements[i].value = '';
		}

		//dynamic_control = '<input type="text" name="antspm-d" class="antispam-control antispam-control-d" value="' + current_year + '" />';
		dynamic_control = document.createElement('input');
		dynamic_control.setAttribute('type', 'hidden');
		dynamic_control.setAttribute('name', 'antspm-d');
		dynamic_control.setAttribute('class', 'antispam-control antispam-control-d');
		dynamic_control.setAttribute('value', current_year);

		// add input for every comment form if there are more than 1 form with IDs: comments, respond or commentform
		elements = document.querySelectorAll('form');
		len = elements.length;
		for (i = 0; i < len; i++) {
			if ( (elements[i].id === 'comments') || (elements[i].id === 'respond') || (elements[i].id === 'commentform') ) {
				var class_index = elements[i].className.indexOf('anti-spam-form-processed');
				if ( class_index == -1 ) { // form is not yet js processed
					elements[i].appendChild(dynamic_control);
					elements[i].className = elements[i].className + ' anti-spam-form-processed';
				}
			}
		}
	}

	if (document.addEventListener) {
		document.addEventListener('DOMContentLoaded', anti_spam_init, false);
	}

	// set 1 second timeout for having form loaded and adding support for browsers which does not support 'DOMContentLoaded' listener
	setTimeout(function () {
		anti_spam_init();
	}, 1000);

})();