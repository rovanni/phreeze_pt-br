/**
 * validation code for analyzer form
 */


(function($){

	$(document).ready(function(){
		
		/**
		 * attach validation code to the generation form
		 */
		$('#generateForm').submit(function(e){
			
			$('#errorContainer').hide('fast');
			
			var error = '';
			var delim = '';
			
			if ($('#appname').val() == '')
			{
				error += delim + 'O nome do aplicativo é obrigatório';
				delim = '<br/>';
			}
			
			$('.objname-singular').each(function(i){
				
				var tableName = $(this).attr('id').replace('_singular','');
				var pluralId = tableName + '_plural';
				var singularVal = $(this).val();
				var pluralVal = $('#'+pluralId).val();
				
				if (singularVal == '' || pluralVal == '')
				{
					error += delim + 'Nome Singular e Plural são necessários para a tabela \'' + tableName + '\'';
					delim = '<br/>';
				}
				else if (singularVal == pluralVal)
				{
					error += delim + 'O nome Singular e Plural não pode ser o mesmo para a tabela \'' + tableName + '\'';
					delim = '<br/>';
				}
				
			});
			
			if (error != '') 
			{
				$('#errorContainer').html('<div class="alert alert-error">'
					+ '<button type="button" class="close" data-dismiss="alert">&times;</button>'
					+ error + '</div>');
				
				$('#errorContainer').show('fast');
				e.preventDefault();
			}
			
		});
		
	});
	
})(jQuery);


