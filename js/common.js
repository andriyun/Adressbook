Ajax = function()
{
    var self = this;    
}
Ajax.prototype = 
{
	load: function (url, selector, func) {
		if (!selector) selector = '#main-container';
		_ajax.blocker(true);
		console.log(document.domain.search(/www/)+" other"+url.search(/www/)+" url"+url);
		if(document.domain.search(/www/)>=0&&url.search(/www/)<0)
			{
			url=url.replace("http://","http://www.");
			}
		$.get(url)
			.success(function(data, textStatus, xhr){
				_ajax.blocker();
				if (data !='') $(selector).html(data);				
				_ajax.parseXHR(xhr);				
				if (func && !xhr.getResponseHeader('error')) func();
				})
			.error(function(xhr, textStatus, errorThrown){
				_ajax.blocker();
				var message = 'Помилка ' + xhr.status+': '+xhr.statusText+' '+errorThrown;
				message += "\n url: "+url;
				alert(message);
				});
		},
	loadPopup: function (url, func) {
		_ajax.blocker(true);
		$.ajax({
			url: url,
			success: function(data, textStatus, xhr){
				_ajax.blocker();			
				_ajax.parseXHR(xhr);				
				if (func && !xhr.getResponseHeader('error')) func(data);
				},
			error: function(xhr, textStatus, errorThrown){
				_ajax.blocker();
				var message = 'Помилка ' + xhr.status+': '+xhr.statusText;
				message += "\n url: "+url;
				alert(message);
				}
			});				
		},		
	post: function (url, data, selector, func) {
		if (!selector) selector = '#main-container';
		_ajax.blocker(true);
		$.ajax({
			type:'POST',		
			url: url,
			data: data,			
			success: function(data, textStatus, xhr){
				_ajax.blocker();			
				if (data !='') $(selector).html(data);								
				_ajax.parseXHR(xhr);
				if (func && !xhr.getResponseHeader('error')) func();
				},
			error: function(xhr, textStatus, errorThrown){
				_ajax.blocker();			
				var message = 'Error ' + xhr.status+': '+xhr.statusText;
				message += "\n url: "+url;
				alert(message);
				}
			});				
		},
	blocker: function(mode)	{
		if (mode) {
			$('#ajaxWait').show();
			$('#messageBox .close').click();
			}
			else $('#ajaxWait').hide();
		},
	parseXHR: function(xhr)	{
		var error = xhr.getResponseHeader('error');
		if (error) alert('Error: ' + eval(error), 'error'); 	
		var redirect = xhr.getResponseHeader('redirect');
		if (redirect) window.location = redirect;
		},
	validForm: function(form)	{
		$(form).find('.message').remove();
		var validForm = true;
		$(form).find('.require').each(function(){
			if (!$(this).val()) {
				$(this).parents('.control-group:first').addClass('error'); 
				validForm = false;
				}
				else $(this).parents('.control-group:first').removeClass('error');
			});
		if (!validForm) $(form).find('.form_header').after('<div class="message alert alert-error"><button type="button" class="close" onclick="$(this).parent(\'.message\').remove();" data-dismiss="alert">&times;</button>Empty required field.</div>');
		return validForm;
		}
}
_ajax = new Ajax();

