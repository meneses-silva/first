$(document).ready(() => {
	
	$('#documentacao').on('click', () => {
		//$('#pagina').load('documentacao.html')

		/*
		$.get('documentacao.html', data => { 
			$('#pagina').html(data)
		})
		*/
		$.post('documentacao.html', data => { 
			$('#pagina').html(data)
		})
	})

	$('#suporte').on('click', () => {
		//$('#pagina').load('suporte.html')

		/*
		$.get('suporte.html', data => { 
			$('#pagina').html(data)
		})
		*/
		$.post('suporte.html', data => { 
			$('#pagina').html(data)
		})
	})
	
	$('#d').on('click', ()=>{
		$.post('main.html', data =>{
		$('#pagina').html(data);
		
	})
				  
				  
				  
	})
	
	$('#competencia').on('change', e =>{
		let competencia = $(e.target).val();
			
		$.ajax({
			
			//metodo, url, dados,secesso, erro
			type: 'GET',
			 url: 'app.php',
		    data:`competencia=${competencia}`,//x-www-form-urlencoded
			dataType: 'json',
			success: dados => {
				$('#numeroVendas').html(dados.numeroVendas)
				$('#totalVendas').html(dados.totalVendas)	
				$('#despesas').html(dados.totalDespesas)
				$('#ativos').html(dados.ativosC)
				$('#inativos').html(dados.inativosC)
				
			},
			error: erro => {console.log(erro)}
			
			
		})
	}) 
	
})