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


	$P0 = $P[0];




// Tabela Price

	if ($tabela=='Price') {
	//contador

	$j=0;

	// Métodos

		function FRC($i, $n){

			$base = 1 + $i;
			$res = pow($base, $n);

			$FRC = ($res*$i)/($res-1);
			return $FRC;

		}

		function FVA($i, $n){

			$base = 1 + $i;
			$res = pow($base, $n);

			$FVA = ($res-1)/($res*$i);
			return $FVA;
		}



		function fPrice1 ($P0, $i, $n){

			$FRC = FRC($i, $n);
			$R = $P0 * $FRC;
			return $R;

		}

		function fPrice2 ($R, $i, $n, $t){
			$res = $n - $t;
			$FVA = FVA($i, $res);
			$Pt1 = $R * $FVA;
			return $Pt1;
		}

		function fPrice3 ($R, $i, $n, $t){
			$res = $n - $t + 1;
			$FVA = FVA($i, $res);
			$Ptmenos1 = $R * $FVA;
			return $Ptmenos1;
		}

		function fPrice4 ($i, $Ptmenos1){

			$Jt = $i * $Ptmenos1;
			return $Jt;

		}

		function fPrice5 ($R, $i, $P0){

			$A1 = $R - $i * $P0;
			return $A1;

		}

		function fPrice6 ($A1, $i, $t){

			$base = 1 + $i;
			$exp = $t - 1; 
			$res = pow($base, $exp);
			$At = $A1 * $res;
			return $At;

		}

		function fPrice7 ($R, $i, $n, $t){

			$SomaAac = $R * FVA($i, $n) - FVA($i, ($n - $t));
			return $SomaAac;
		}

		function fPrice8 ($R, $i, $n, $t, $K){

			$SomaAacttmaisk = $R * FVA($i, $n - $t) - FVA($i, ($n - $t - $K));
			return $SomaAacttmaisk;
		}

		function fPrice9 ($R, $t, $i, $n){

			$SomaJact = $R * ($t - FVA($i, $n) - FVA($i, ($n - $t)));
			return $SomaJact;
		}

		function fPrice10 ($R, $K, $SomaAacttmaisk){

			$SomaJacttmaisk = $R * $K - $SomaAacttmaisk;
			return $SomaJacttmaisk;
		}

		$FRC = FRC($i, $n);
		$FVA = FVA($i, $n);

		$R = fPrice1 ($P0, $i, $n);
		$Pt1 = fPrice2 ($R, $i, $n, $t);
		$Ptmenos1 = fPrice3 ($R, $i, $n, $t);
		$Jt = fPrice4 ($i, $Ptmenos1);
		$A1 = fPrice5 ($R, $i, $P0);
		$At = fPrice6 ($A1, $i, $t);
		$SomaAac = fPrice7 ($R, $i, $n, $t);
		$SomaAacttmaisk = fPrice8 ($R, $i, $n, $t, $K);
		$SomaJact = fPrice9 ($R, $t, $i, $n);
		$SomaJacttmaisk = fPrice10 ($R, $K, $SomaAacttmaisk);







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

			//Incrementos
			$j++;
			$at++;
			$jt++;
		}

		$suprema = fPrice2 ($R, $i, $n, $fun);

		echo "<br>O saldo devedor no período ".$fun." = R$".$suprema = round($suprema,2);

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
