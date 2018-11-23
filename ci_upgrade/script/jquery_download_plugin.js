/*
 * --------------------------------------------------------------------
 * jQuery-Plugin - $.download - allows for simple get/post requests for files
 * by Scott Jehl, scott@filamentgroup.com
 * http://www.filamentgroup.com
 * reference article: http://www.filamentgroup.com/lab/jquery_plugin_for_requesting_ajax_like_file_downloads/
 * Copyright (c) 2008 Filament Group, Inc
 * Dual licensed under the MIT (filamentgroup.com/examples/mit-license.txt) and GPL (filamentgroup.com/examples/gpl-license.txt) licenses.
 * --------------------------------------------------------------------
 */
 
jQuery.download = function(url, data, method)
{
	//url and data options required
	if( url && data )
	{ 
		//data can be string of parameters or array/object
		data = typeof data == 'string' ? data : jQuery.param(data);
		//split params into form inputs
		var inputs = '';
		if(data.search(':') != -1)
		{
			jQuery.each(data.split(':'), function(){ 
				var one_pair = this.split('=');
				inputs+='<input type="hidden" name="'+ one_pair[0] +'" value="'+ one_pair[1] +'" />'; 
			});
		}
		else if(data.search('=') != -1)
		{
			key_value_pair = data.split('='); 
			inputs+='<input type="hidden" name="'+ key_value_pair[0] +'" value="'+ key_value_pair[1] +'" />'; 
		}
		
		//send request
		jQuery('<form action="'+ url +'" method="'+ (method||'post') +'">'+inputs+'</form>').appendTo('body').submit().remove();
	}
};