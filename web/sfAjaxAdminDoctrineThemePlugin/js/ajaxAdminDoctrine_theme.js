
var ajaxadmindoctrine_mode = 'list';
var ajaxadmindoctrine_saveAndAddClicked = false;
var ajaxadmindoctrine_lastListURL = '';
var ajaxadmindoctrine_modeChangeCallbacks = [];

// Execute an AJAX request to the server, parse the JSON it returns, and use that
// to update sections of the page and change the current mode.  The "mode" controls
// which sections of the page are visible to the user.
function ajaxadmindoctrine_ajaxPartial(url, addlURLParams, httpMethod, data) {

	$('#sf_ajax_spinner').show();

	url = ajaxadmindoctrine_setURLQueryParams(url, 'ajaxPartial=true');
	if (addlURLParams != '') url = ajaxadmindoctrine_setURLQueryParams(url, addlURLParams);
	$.ajax({
		type: httpMethod,
		url: url,
		data: data,
		async: true,
		cache: false,
		success: function(data, textStatus, jqXHR) {
			$('#sf_ajax_spinner').hide();
			ajaxadmindoctrine_parseJSONReturn(data);
		},
		error: function(jqXHR, textStatus, errorThrown) {
			$('#sf_ajax_spinner').hide();
			var msg = "AJAX Error: "+textStatus;
			if (confirm("Your session may have timed out.  Would you like to reload the page so you can log back in?")) {
				window.location.reload();
			}
		}
	});
}

function ajaxadmindoctrine_initAdminAJAXHandlers() {
	// Handle search results filtering.
	$('.sf_admin_filter form').live(
		'submit',
		function() {
			var form = $('.sf_admin_filter form');
			ajaxadmindoctrine_ajaxPartial(form.attr('action'), '', 'POST', form.serialize());
			return false;
		}
	);
	$('.sf_admin_filter .sf_admin_filter_reset_button').live(
		'click',
		function(event) {
			var form = $('.sf_admin_filter form');
			ajaxadmindoctrine_clearForm(form);
			ajaxadmindoctrine_ajaxPartial(form.attr('action'), '_reset', 'POST', form.serialize());
			return false;
		}
	);

	// Handle pagination.
	$('.sf_admin_pagination a, .sf_admin_list thead th[class*=sf_admin_list_th_] a').live(
		'click',
		function(event) {
			ajaxadmindoctrine_lastListURL = this.href;
			ajaxadmindoctrine_ajaxPartial(this.href, '', 'GET', {});
			return false;
		}
	);

	// Handle action links within list table rows, except for the Delete link which
	// is handled by the ajaxadmindoctrine_executeDeleteAction() function.
	$(".sf_admin_td_actions a").live(
		'click',
		function(event) {
			if ($(this).parents("li.sf_admin_action_delete").length == 0) {
				ajaxadmindoctrine_ajaxPartial(this.href, '', 'GET', {});
				return false;
			} else {
				return true;
			}
		}
	);

	// Handle New link.
	$(".sf_admin_action_new a").live(
		'click',
		function(event) {
			ajaxadmindoctrine_ajaxPartial(this.href, '', 'GET', {});
			return false;
		}
	);

	// Handle "Back to list" link on edit form.
	$('ul.sf_admin_actions li.sf_admin_action_list a').live(
		'click',
		function(event) {
			ajaxadmindoctrine_ajaxPartial(ajaxadmindoctrine_lastListURL, '', 'GET', {});
			return false;
		}
	);

	// Handle "Save" and "Save and add" on add/edit form.
	$('.sf_admin_form form').live(
		'submit',
		function() {
			var form = $('.sf_admin_form form');
			var formData = form.serialize();
			if (ajaxadmindoctrine_saveAndAddClicked) formData += '&_save_and_add=Save+and+add';
			ajaxadmindoctrine_ajaxPartial(form.attr('action'), '', 'POST', formData);
			return false;
		}
	);
	$(".sf_admin_action_save input[type='submit']").live(
		'click',
		function() {
			ajaxadmindoctrine_saveAndAddClicked = false;
		}
	);
	$(".sf_admin_action_save_and_add input[type='submit']").live(
		'click',
		function() {
			ajaxadmindoctrine_saveAndAddClicked = true;
		}
	);

	// Handle batch actions.
	$(".sf_admin_batch_actions_choice input[type='submit']").live(
		'click',
		function() {
			var form = $(this).parents('form');
			var formData = form.serialize();
			var arr = ajaxadmindoctrine_parseQueryString(formData);
			if ((typeof(arr['ids[]']) == 'undefined') ||
				(arr['ids[]'].length < 1)) {
				alert('Please select one or more items in the list.');
				return false;
			}
			if ((typeof(arr['batch_action']) == 'undefined') ||
				(arr['batch_action'].length < 1)) {
				alert('Please select a batch action to perform.');
				return false;
			}
			if (confirm('Are you sure?')) {
				ajaxadmindoctrine_ajaxPartial(form.attr('action'), '', 'POST', formData);
			}
			return false;
		}
	);
}

// This function handles delete links, which are fixed to call this function
// instead of submitting their ad-hoc forms to the server, by using str_replace()
// on the generated javascript code in the theme templates.
function ajaxadmindoctrine_executeDeleteAction(url, formElement) {
	var form = $(formElement);
	ajaxadmindoctrine_ajaxPartial(form.attr('action'), '', 'POST', form.serialize());
}

// Add a mode change callback function.  The function will get called
// each time the mode has been changed.  The function must have one argument,
// newMode, which is the mode to which we just changed.  It will be one of
// 'list', 'new', or 'edit'.
function ajaxadmindoctrine_addModeChangeCallback(func) {
	ajaxadmindoctrine_modeChangeCallbacks[ajaxadmindoctrine_modeChangeCallbacks.length] = func;
}

function ajaxadmindoctrine_setMode(newMode) {
	switch (newMode) {
	case 'list':
		$('#sf_admin_title_list').show();
		$('#sf_admin_header_list').show();
		$('#sf_admin_bar').show();
		$('#sf_admin_content_list').show();
		$('#sf_admin_footer_list').show();

		$('#sf_admin_title_new').hide();
		$('#sf_admin_title_edit').hide();
		$('#sf_admin_header_new_edit').hide();
		$('#sf_admin_content_new_edit').hide();
		$('#sf_admin_footer_new_edit').hide();

		ajaxadmindoctrine_mode = newMode;

		for (var i = 0; i < ajaxadmindoctrine_modeChangeCallbacks.length; i++) {
			ajaxadmindoctrine_modeChangeCallbacks[i](newMode);
		}
		break;
	case 'new':
		$('#sf_admin_title_list').hide();
		$('#sf_admin_header_list').hide();
		$('#sf_admin_bar').hide();
		$('#sf_admin_content_list').hide();
		$('#sf_admin_footer_list').hide();

		$('#sf_admin_title_new').show();
		$('#sf_admin_title_edit').hide();
		$('#sf_admin_header_new_edit').show();
		$('#sf_admin_content_new_edit').show();
		$('#sf_admin_footer_new_edit').show();

		ajaxadmindoctrine_mode = newMode;

		for (var i = 0; i < ajaxadmindoctrine_modeChangeCallbacks.length; i++) {
			ajaxadmindoctrine_modeChangeCallbacks[i](newMode);
		}
		break;
	case 'edit':
		$('#sf_admin_title_list').hide();
		$('#sf_admin_header_list').hide();
		$('#sf_admin_bar').hide();
		$('#sf_admin_content_list').hide();
		$('#sf_admin_footer_list').hide();

		$('#sf_admin_title_new').hide();
		$('#sf_admin_title_edit').show();
		$('#sf_admin_header_new_edit').show();
		$('#sf_admin_content_new_edit').show();
		$('#sf_admin_footer_new_edit').show();

		ajaxadmindoctrine_mode = newMode;

		for (var i = 0; i < ajaxadmindoctrine_modeChangeCallbacks.length; i++) {
			ajaxadmindoctrine_modeChangeCallbacks[i](newMode);
		}
		break;
	default:
		alert('ajaxadmindoctrine_setMode() invalid new mode: '+newMode);
		break;
	}
}

// Parse the various parts of the page from a returned JSON object.
function ajaxadmindoctrine_parseJSONReturn(obj) {
	if (typeof(obj.mode) != 'undefined') {
		switch (obj.mode) {
		case 'list':
			if (typeof(obj.flashes) != 'undefined') $('#sf_admin_flashes').html(obj.flashes);
			if (typeof(obj.title) != 'undefined') $('#sf_admin_title_list').html(obj.title);
			if (typeof(obj.header) != 'undefined') $('#sf_admin_header_list').html(obj.header);
			if (typeof(obj.bar) != 'undefined') $('#sf_admin_bar_list').html(obj.bar);
			if (typeof(obj.content) != 'undefined') $('#sf_admin_content_list').html(obj.content);
			if (typeof(obj.footer) != 'undefined') $('#sf_admin_footer_list').html(obj.footer);
			// Special instances for updating the list page.
			if (typeof(obj.list) != 'undefined') $('.sf_admin_list').replaceWith(obj.list);
			ajaxadmindoctrine_setMode(obj.mode);
			break;
		case 'new':
			if (typeof(obj.flashes) != 'undefined') $('#sf_admin_flashes').html(obj.flashes);
			if (typeof(obj.title) != 'undefined') $('#sf_admin_title_new').html(obj.title);
			if (typeof(obj.header) != 'undefined') $('#sf_admin_header_new_edit').html(obj.header);
			if (typeof(obj.content) != 'undefined') $('#sf_admin_content_new_edit').html(obj.content);
			if (typeof(obj.footer) != 'undefined') $('#sf_admin_footer_new_edit').html(obj.footer);
			ajaxadmindoctrine_setMode('new');
			break;
		case 'edit':
			if (typeof(obj.flashes) != 'undefined') $('#sf_admin_flashes').html(obj.flashes);
			if (typeof(obj.title) != 'undefined') $('#sf_admin_title_edit').html(obj.title);
			if (typeof(obj.header) != 'undefined') $('#sf_admin_header_new_edit').html(obj.header);
			if (typeof(obj.content) != 'undefined') $('#sf_admin_content_new_edit').html(obj.content);
			if (typeof(obj.footer) != 'undefined') $('#sf_admin_footer_new_edit').html(obj.footer);
			ajaxadmindoctrine_setMode('edit');
			break;
		default:
			alert('invalid mode \"'+obj.mode+'\" defined in AJAX JSON response');
			break;
		}
	} else {
		alert('mode not defined in AJAX JSON response');
	}
}

// Clear a form.
// The form parameter must be a jQuery-selected form element.
function ajaxadmindoctrine_clearForm(form) {
	form.find(':input').each(function() {
		switch(this.type) {
		case 'password':
		case 'select-multiple':
		case 'select-one':
		case 'text':
		case 'textarea':
			$(this).val('');
			break;
		case 'checkbox':
		case 'radio':
			this.checked = false;
		}
	});
}

// Replace or append request parameter(s) in a URL.
// First argument is the URL to work on.
// Second argument is either one or more parameters in the format name=value[&name=value...]
// (properly URL-encoded and separated by & characters), or an array of parameters where each
// element is in the format name=value.
function ajaxadmindoctrine_setURLQueryParams(url, parameters) {
	if (!(parameters instanceof Array)) {
		parameters = (parameters.length > 0) ? parameters.split('&') : [];
	}

	var qidx = url.indexOf('?');
	var query;
	if (qidx < 0) {
		query = '';
	} else {
		query = url.substring(qidx+1);
		url = url.substring(0, qidx);
	}
	var pieces = (query.length > 0) ? query.split('&') : [];

	for (var pri = 0; pri < parameters.length; pri++) {
		if (parameters[pri].length == 0) continue;
		var eidx = parameters[pri].indexOf('=');
		if (eidx < 0) eidx = parameters[pri].length;
		var paramName = parameters[pri].substr(0, eidx);
		var found = false;
		for (var pci = 0; pci < pieces.length; pci++) {
			if (((pieces[pci].length == eidx) && (pieces[pci] == paramName)) ||
				((pieces[pci].length > eidx) && (pieces[pci].substr(0, eidx) == paramName) && (pieces[pci].charAt(eidx) == '='))) {
				// The parameter already exists on the URL.  Replace it.
				pieces[pci] = parameters[pri];
				found = true;
				break;
			}
		}
		if (!found) {
			// The parameter does not exist on the URL.  Append it.
			pieces[pieces.length] = parameters[pri];
		}
	}

	query = pieces.join('&');
	if (query != '') url += '?'+query;
	return url;
}

// Parse a query string into an associative array.
// Parameters which exist without values, will appear in the array with a null value.
// Parameters whose names end with '[]' will appear in the array as a linear array containing
// the values.
function ajaxadmindoctrine_parseQueryString(queryString) {
	if ((queryString.length > 0) && (queryString.charAt(0) == '?')) {
		queryString = queryString.substring(1);
	}
	var result = {};
	var pieces = queryString.split('&');
	for (var i = 0; i < pieces.length; i++) {
		var eqidx = pieces[i].indexOf('=');
		var name, val;
		if (eqidx >= 0) {
			name = decodeURIComponent(pieces[i].substring(0, eqidx));
			val = decodeURIComponent(pieces[i].substring(eqidx+1));
		} else {
			name = decodeURIComponent(pieces[i]);
			val = null;
		}
		if ((name.length >= 2) && (name.substring(name.length-2) == '[]')) {
			if (typeof(result[name]) == 'undefined') {
				result[name] = [ val ];
			} else {
				result[name][result[name].length] = val;
			}
		} else {
			result[name] = val;
		}
	}
	return result;
}

$(document).ready(function() {
	ajaxadmindoctrine_initAdminAJAXHandlers();
	ajaxadmindoctrine_setMode('list');
	ajaxadmindoctrine_lastListURL = window.location.href;
	$('#sf_ajax_spinner').hide();
});
