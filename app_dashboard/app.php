<?php

/*
a classe dashboard contém atributos que estão no banco de dados
para get e set magico

*/


//classe dashboard
class Dashboard{
	//atributos
	public $data_inicio;
	public $data_fim;
	public $numeroVendas;
	public $totalVendas;
	public $ativosC;
	public $inativosC;
	public $totalDespesas;
	
	
	
	//metodos
	//getter magico
	public function __get($atributo){
		return $this->$atributo;
		
	}
	//setter magico
	public function __set($atributo, $valor){
		$this->$atributo = $valor;
		return $this;
		
	}
	
	
}
//classe para conexao com o banco de dados
class Conexao {
	//attr para conexao
	private $host = 'localhost';
	private $dbname = 'dashboard';
	private $user = 'root';
	private $pass = '';
	 
	   //metodo conectar
	  public function conectar(){
		  //tente fazer..
		  try{
			  //conexão pdo
			  $conexao = new PDO("mysql:host=$this->host; dbname=$this->dbname","$this->user","$this->pass");
			  
			  //executar com utf8
			  $conexao->exec('set charset set utf8');
			  return $conexao;
			  //tratamento caso erro
		  } catch(PDOExpection $e){
			  //mensagem de erro 
			  echo '<p>'.$e->getMessege().'</p>';
			  
		  }
		  
	  }
}

//classe model
//classe que reune a conexao com o objeto para dados
class Bd{
	//atributos que reunem
	private $conexao;
	private $dashboard;
	//metodo construção que reune objeto e atributo
	public function __construct(Conexao $conexao, Dashboard $dashboard){
		$this->conexao = $conexao->conectar();
		$this->dashboard = $dashboard;
		
	}
	//metodo para pegar numero de vendas
	public function getNumeroVendas(){
		$query = "select count(*) as numero_vendas from tb_vendas where data_venda between :data_inicio and :data_fim";
		$stmt = $this->conexao->prepare($query); 
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();
		
		return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
	}	
	public function getTotalVendas(){
		$query = "select SUM(total) as total_vendas from tb_vendas where data_venda between :data_inicio and :data_fim";
		$stmt = $this->conexao->prepare($query); 
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();
		
		return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
	}
		public function getAtivos(){
		$query = "select count(cliente_ativo) as ativos from tb_clientes where cliente_ativo = '1'";
		$stmt = $this->conexao->prepare($query); 
		$stmt->execute();
		
		return $stmt->fetch(PDO::FETCH_OBJ)->ativos;
	}		public function getInativos(){
		$query = "select count(cliente_ativo) as inativos from tb_clientes where cliente_ativo = '0'";
		$stmt = $this->conexao->prepare($query); 
		$stmt->execute();
		
		return $stmt->fetch(PDO::FETCH_OBJ)->inativos;
	}
		public function getDespesas(){
		$query = "select SUM(total) as despesas from tb_despesas where data_despesa between :data_inicio and :data_fim";
		$stmt = $this->conexao->prepare($query); 
		$stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
		$stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
		$stmt->execute();
		
		return $stmt->fetch(PDO::FETCH_OBJ)->despesas;
	}
	
	
}
//instancias/objs
$dashboard = new Dashboard();
$conexao = new Conexao();

$competencia = explode('-', $_GET['competencia']);
$ano = $competencia[0];
$mes = $competencia[1];

$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);


$dashboard->__set('data_inicio', $ano.'-'.$mes.'-01');
$dashboard->__set('data_fim', $ano.'-'.$mes.'-'.$dias_do_mes);
$bd = new Bd($conexao, $dashboard);
$dashboard->__set('numeroVendas', $bd->getNumeroVendas());
$dashboard->__set('totalVendas', $bd->getTotalVendas());
$dashboard->__set('ativosC', $bd->getAtivos());
$dashboard->__set('inativosC', $bd->getInativos());
$dashboard->__set('totalDespesas', $bd->getDespesas());

echo json_encode($dashboard);





?>