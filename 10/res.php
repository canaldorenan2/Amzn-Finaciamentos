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
					<li><a href="index.html">Homepage</a></li>
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

	

	<!--começa aqui o php-->

<?php

	$tabela = $_POST['tabela'];


	/* -------Ìndices---------*/
	$jt=0; // Juros
	$at=0; // Amortização
	$t=0; // Saldo Devedor
	$K =0;

	// Valores da tabela Price
	$Juros=[];
	$Amortização=[];

	
	$P[$t] = $_POST['Pt']; //Saldo devedor mes 0
	$n = $_POST['n']; //Tempo total
	$i= ($_POST['i'])/100; //Taxa

	//----------------------------
	//----------------------------
	$_SESSION["tabela"] = $tabela;

	$_SESSION["t"] = $t;
	$_SESSION["K"] = $K;

	$_SESSION["P"] = $P;
	$_SESSION["n"] = $n;
	$_SESSION["i"] = $i;
	//----------------------------
	//----------------------------

// Tabela Price

	if ($tabela=='Price') {
	//contador

	$j = 0;

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
	}

// Tabela SAC

	if ($tabela=="SAC") {


		function SAC1 ($P, $n){

			$A = $P[0]/$n; //1. Valor da Amortização  Constante
			return $A;

		}

		function SAC2 ($A, $n, $t){

			$Pt = $A*($n-$t); // 2. Valor do saldo devedor de ordem t 
			return $Pt;

		}

		function SAC3 ($A, $n, $t){

			$Pt1 = $A*($n-$t+1); // 3. Valor Saldo devedor de ordem (t-1)
			return $Pt1;

		}
		function SAC4 ($i, $Pt1){


			$Jt = $i* $Pt1; // 4. Valor Parcela de juros em ordem t
			return $Jt;

		}

		function SAC5 ($A, $Jt){

			$Presta = $A+$Jt; // 5. Valor da Prestação em ordem t
			return $Presta;

		}

		function SAC6 ($K, $A){

			$SomaA = $K*$A; // 6. Soma das amortizações nos períodos t à t+k
			return $SomaA;

		}

		function SAC7 ($i, $A, $t, $n){

			$SomaJ = $i*$A*$t*(((2*$n)-$t+1)/2); // 7. Soma dos Juros acumulados até tempo t
			return $SomaJ;

		}

		function SAC8 ($i, $A, $K, $n, $t){

			$SomaJ = $i*$A*$K*($n-$t*(($K-1)/2)); // 8. Soma dos juros de t à t+k
			return $SomaJ;

		}

		function SAC9 ($A, $t, $i, $n){

			$SomaR = $A*$t*(1+$i*((2*$n-$t+1)/2)); // 9. Soma das Prestações acumuladas
			return $SomaR;

		}

		function SAC10 ($A, $K, $i, $n, $t){

			$SomaR = $A*$K*(1+$i*($n-$t-(($K-1)/2))); // 10. Soma das Prestações de t à t+k
			return $SomaR;

		}

		function SAC11 ($i, $A){

			$R = $i*$A; // 11. Decréscimo nas Prestações
			return $R;
		}



		$A = SAC1 ($P, $n);

		$Pt = SAC2 ($A, $n, $t);

		$Pt1 = SAC3 ($A, $n, $t);

		$Jt = SAC4 ($i, $Pt1);

		$Presta = SAC5 ($A, $Jt);

		$SomaA = SAC6 ($K, $A);

		$SomaJ = SAC7 ($i, $A, $t, $n);

		$SomaJ = SAC8 ($i, $A, $K, $n, $t);

		$SomaR = SAC9 ($A, $t, $i, $n);

		$SomaR = SAC10 ($A, $K, $i, $n, $t);

		$R = SAC11 ($i, $A);




		echo "Saldo devedor inicial: R$".$P[0].",00<br>";

		echo "<br>Decréscimo nas Prestações: R$".SAC11 ($i, $A)."<br><br>";


		//Tabela
			$free = 0;
			while ($free != $n) {

				// Deve-se atualizar n ou t ? Vamos testar                                            
				
				if ($free==0) {
					$Jt = $i*$P[0]; // Calculo dos juros
				}
				if ($free>0) {
					$Jt = $sd*$i;
				}
				
				$free ++;
				$Presta = $Jt + $A; // Calculo da prestação

				
				$sd=$P[0]-($free*$A); // Saldo devedor


				//Imprimir tabela
				echo "<table id='01'>\n";
				echo "<tr>\n";
				echo "<td>".$free."º Pagamento, "."</td>\n";
				echo "<td>"."Prestação = R$".$Presta." "."</td>\n";
				echo "<td>"."	Juros = R$".$Jt." "."</td>\n";
				echo "<td>"."  Amortização = R$".$A.""."</td>\n";
				echo "<td>"."  Saldo devedor = R$".$sd."</td>\n";
				echo "</tr>\n";
				echo "<table>\n";
				

		} echo "\n\n";
		
		
	}

  ?>

<!-- Termina aqui o php -->
	
 <!-- Concluido -->	
<div class="row">

 	<!-- PRICE -->
 	<div class="column" >	
 	<h3>Calculos Avançados para tabela PRICE</h3>
	<form method="POST" action="PriFun2.php">
	<label><br>(2) Saldo devedor em uma parcela específica: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="PriFun3.php">
	<label><br>(3) Saldo devedor na parcela anterior: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="PriFun4.php">
	<label><br>(4) Valor do Juros em uma parcela: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="PriFun6.php">
	<label><br>(6) Amortização no período: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="PriFun7.php">
	<label><br>(7) Soma das amortizações acumuladas até: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="PriFun8.php">
	<label><br>(8) Soma das amortizações acumuladas entre: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<input type=number name="fun3" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="PriFun9.php">
	<label><br>(9) Soma dos juros acumulados até: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="PriFun10.php">
	<label><br>(10) Soma dos juros acumulados entre: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<input type=number name="fun3" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	</div>

 <!-- SAC  -->
 	<div class="column">	
 	<h3>Calculos Avançados para tabela SAC</h3>
	<form method="POST" action="ResFun2.php">
	<label><br>(2) Saldo devedor em uma parcela específica: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="ResFun3.php">
	<label><br>(3) Saldo devedor na parcela anterior: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="ResFun4.php">
	<label><br>(4) Valor do Juros em uma parcela: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="ResFun6.php">
	<label><br>(6) Soma de amortizações entre períodos: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<input type=number name="fun3" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="ResFun7.php">
	<label><br>(7) Soma dos juros acumulados até: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="ResFun8.php">
	<label><br>(8) Soma dos juros entre: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<input type=number name="fun3" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="ResFun9.php">
	<label><br>(9) Soma das prestações acumuladas: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	<br>
	<form method="POST" action="ResFun10.php">
	<label><br>(10) Soma entre as Prestações: </label><br>
	<input type=number name="fun2" step=1 /><br>
	<input type=number name="fun3" step=1 /><br>
	<button type="submit">Calcular</button>
	</form>
	</div>

</div>
				</div>
			</div>	
			<div >&nbsp;</div>
		</div>
		<!-- Fim do conteúdo -->
		<div >&nbsp;</div>
	</div>
	<!-- Fim da página --> 
</div>
<div >
	<p>&copy; Todos os direitos reservados. Design by <a href="http://templated.co" rel="nofollow">TEMPLATED</a>. Photos by  GOOGLE</a>.</p>
</div>
<!-- Fim rodapé -->
</body>
</html>
