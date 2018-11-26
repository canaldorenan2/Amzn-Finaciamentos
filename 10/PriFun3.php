<?php
// Start the session
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
		<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
	padding-right: auto;
	padding-left: auto;
    padding: 10px;
    text-align: center;
}
table#t01 {
    width: 100%;    
    background-color: #f1f1c1;
}
</style>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Finaciamentos</title>
<link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css' />
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="wrapper">
	<div id="header-wrapper">
		<div id="header" class="container">
			<div id="logo">
				<h2><a href="#">Financimento com tabela SAC e Prime </a></h1>
			</div>
			<div id="menu">
				<ul>
					<li><a href="#">Homepage</a></li>
					<li><a href="#">Sobre</a></li>
				</ul>
			</div>
		</div>
		<div id="banner">
			<div class="content"><img src="images/tabela-price.jpg" width="1000" height="300" alt="" /></div>
		</div>
	</div>
	<!-- end #header -->
	
	<div id="page">
		<div id="content">
			<div class="post">
				<h2 class="title"><a href="#">Resultado</a></h2>
				<div style="clear: both;">&nbsp;</div>
				<div class="entry">

	<form method="POST" action="calculos.php">

	<!--começa aqui o php-->

<?php
	// Inicioooooooooooooooooooo

	$tabela = $_SESSION['tabela'];


	/* -------Ìndices---------*/
	$rt=0; // Prestação
	$jt=0; // Juros
	$at=0; // Amortização

	$t = $_SESSION['t']; // Saldo Devedor
	$K = $_SESSION["K"];

	// Valores da tabela Price
	$Juros=[];
	$Amortização=[];

	
	$P = $_SESSION["P"]; //Saldo devedor mes 0
	$n = $_SESSION["n"]; //Tempo total
	$i= $_SESSION["i"]; //Taxa



	$fun = $_POST['fun2'];
	$suprema = 0;







// Tabela Price

	if ($tabela=='Price') {
	//contador

	$j=0;

	// Métodos

		$var = (1+$i);
		$var = pow($var, $n);
		$Prestação = $P[$t]*(($var*$i)/($var-1));


		//---------------------
		// Limitação de 2 numeros decimais
		$Prestação = round($Prestação,2);
		//----------------------
		// Imprimir
		echo "  Saldo devedor inicial = R$".$P[$t].",00<br><br>";
		$j++;



		while ($j < $n+1) {

			// Calculos
			$resultado = 0;
			$resultado = $P[$t] * $i;
			array_push($Juros, $resultado);

			$Amortização[$at]=$Prestação - $Juros[$t];

			$P[$t+1]=$P[$t]-$Amortização[$at];

			$t++;


			// Limitação de 2 numeros decimais
			$Juros[$jt] = round($Juros[$jt],2);
			$Amortização[$at] = round($Amortização[$at],2);
			$P[$t] = round($P[$t],2);

			//Imprimir tabela
			echo "<table id='01'>\n";
			echo "<tr>\n";
			echo "<td>".$j."º Pagamento, "."</td>\n";
			echo "<td>"."Prestação = R$".$Prestação." "."</td>\n";
			echo "<td>"."	Juros = R$".$Juros[$jt]." "."</td>\n";
			echo "<td>"."  Amortização = R$".$Amortização[$at].""."</td>\n";
			echo "<td>"."  Saldo devedor = R$".$P[$t]."</td>\n";
			echo "</tr>\n";
			echo "<table>\n";

			$j++;
			if ($fun == $j ) {
				
				$suprema = $P[$t];

			}
			$j--;

			//Incrementos
			$j++;
			$at++;
			$jt++;
		}

		echo "<br>O saldo devedor no período ".$fun." = R$".$suprema;

	}



  ?>


	<!-- Termina aqui o php -->



	


				</div>
			</div>
			
			
			<div style="clear: both;">&nbsp;</div>
		</div>
		<!-- Fim do conteúdo -->
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- Fim da página --> 
</div>
<div id="footer">
	<p>&copy; Todos os direitos reservados. Design by <a href="http://templated.co" rel="nofollow">TEMPLATED</a>. Photos by  GOOGLE</a>.</p>
</div>
<!-- Fim rodapé -->
</body>
</html>
