$('.moeda').on("keyup",function() {
		
	// Se iniciar digitando com vírgula altera para "0," para funcionar com a máscara abaixo
	
	var v = $(this).val();	
	if(v == "," && v.length == 1) {
		this.value = this.value.replace(',','0,');
	}
	
	// Trata o campo para só aceitar números e uma vírgula
	
	inputControl($(this),'float');
		function inputControl(input,format) { 
		var value=input.val();
		var values=value.split("");
		var update="";
		var transition="";
		
		if (format=='float') {
			var expression=/(^\d+$)|(^\d+\.\d+$)|[,\.]/;
			var finalExpression=/^([0-9][0-9]*[,\.]?\d{0,2})$/;
		}   
		for(id in values) {           
		
			if (expression.test(values[id])==true && values[id]!='') {
				transition+=''+values[id].replace('.','.');
			
				if(finalExpression.test(transition)==true) {
					update+=''+values[id].replace('.',',');
				}
			}
		}
			input.val(update);
	}
	
});
			
$(document).ready(function() {	
	
		$('.moeda').on('blur', function() {
	
			var s = $(this), // O input
			v = s.val(), // O valor do input
			p = v.split(','), // Divide se houver a vírgula
			a = p[0]||false; // Pega o valor antes da virgula, se houver 
			d = p[1]||false; // Pega o valor depois da virgula, se houver 
					   
			// Se o usuário sair do campo sem digitar algum valor, aplica o valor default "0"
			if(v=="" || v==0 || v<0) v = 0;
									
			// Caso não haja virgula acrescenta ",00"	
			
			if(!d){  
				s.val(v+',00');
			}	
									
			// Caso o usuário não digite todas as casas decimais completa com zero
			
			if (d.length == 1) {   
				s.val(v+'0');
			}	
							
			// Caso não haja número antes da vírgula, limpa o campo
			
			if(!a){
				s.val('');
			}	
		
			// Remove vírgula dupla		
		
			$(this).val($(this).val().replace(",,", ","));
			
		});	
		
		
		// Seleciona todo campo de moeda ao trocar com TAB	
		
		$('body').bind("keyup keypress", function(e) {
			var code = e.keyCode || e.which; 
			if (code == 9) {               
				e.preventDefault();
				return false;	
			} 
		});	
		
		var input = $('.moeda');
		input.on('keyup', function(e) {
			if (e.which === 9) {
				$('.moeda').on('focus', function (e) {
					$(this).one('mouseup', function () {
							$(this).select();
							return false;
						}).select();
				});	
			}
		});		
		
		//Selecionar tudo ao clicar no campo de moeda
		
		$('.moeda').click(function() {
			$(".moeda:focus").select();
		});			
	
});	