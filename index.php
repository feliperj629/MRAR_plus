<?php  session_start();
// error_reporting(E_ALL);
// ini_set('display_errors','1');
error_reporting(0); //Oculta os Alertas do Servidor Web.

//phpinfo();
//exit;

require_once('classe/mrar.class.php');
$Classe = new Mrar;


   // set_include_path(get_include_path() . PATH_SEPARATOR . '../easyrdf-0.9.0/lib/');
    require_once "lib/EasyRdf.php";
    //require_once "lib/html_tag_helpers.php";

if(isset($_REQUEST['resp']))
	$resp = $_REQUEST['resp'];
if(isset($_REQUEST['salverules']))
	$salverules = $_REQUEST['salverules'];
if(isset($_REQUEST['op']))
	$op = $_REQUEST['op'];
if(isset($_REQUEST['edtdelimiter']))
	$delimiter = $_REQUEST['edtdelimiter'];
else
	$delimiter = '||';

if(isset($delimiter))
	$Classe->delimiter = $delimiter;

  // if(isset($_REQUEST['edtexternalpredicates']))
  // {
  // 	 // $externalPredicates = $_REQUEST['edtexternalpredicates'];
  //    // $externalPredicates =  explode($delimiter, $externalPredicates);
  //    // print_r($externalPredicates);
  //    // exit;
  // }
  // else
  //  {
      // print 'ESLE';
      // Atribui o conteúdo do arquivo para variável $arquivo
      $arquivo_link = file_get_contents('links_ex/links.json');
      // Decodifica o formato JSON e retorna um Objeto
      $json_link = json_decode($arquivo_link);
      $externalPredicates_array = $json_link->Predicado;
      $externalPredicates = implode($delimiter,$externalPredicates_array);
      // print"<pre>";
      // print_r($links_ex);
      // print_r($externalPredicates);
      // print"</pre>";
  // }



  // print '<pre>';
  // print_r($_REQUEST);
  // print '</pre>';
  //exit;

// Iniciamos o "contador"
list($usec, $sec) = explode(' ', microtime());
$script_start = (float) $sec + (float) $usec;

if(isset($_REQUEST['optionsRadios']))
	$radio = $_REQUEST['optionsRadios'];

if(isset($_REQUEST['Externo_Entidades']))
	$Externo_Entidades = $_REQUEST['Externo_Entidades'];
if(isset($_REQUEST['Externo_EndPoints']))
	$Externo_EndPoints = $_REQUEST['Externo_EndPoints'];
if(isset($_REQUEST['Show_table']))
	$show_table = $_REQUEST['Show_table'];
if(isset($_REQUEST['salvar_regras']))
	$Salvar_Regras = $_REQUEST['salvar_regras'];
if(isset($_REQUEST['salvar_externos']))
	$Salvar_Externos = $_REQUEST['salvar_externos'];

//Comparar dados
if(isset($_REQUEST['comp_dados']))
	$dados = $_REQUEST['comp_dados'];
if(isset($_REQUEST['comp_dados2']))
	$dados2 = $_REQUEST['comp_dados2'];

// print '$dados:';
// if(!empty($dados))
	//print_r ($dados);
	//print_r ($dados2);

// exit;
if(!empty($dados) && !empty($dados2))
{
	//print_r ($dados);

	$dados = $Classe->PegadadosJson(''.$_REQUEST['comp_dados'],'dados',$_REQUEST['best_support']);
	$dados2 = $Classe->PegadadosJson(''.$_REQUEST['comp_dados2'],'dados',$_REQUEST['best_support']);

	$htmlCompRules = $Classe->CompRules($dados,$dados2);
	if(!empty($Classe->NewRules))
		$htmlNewRulesTable = $Classe->MontaTabelaRules($Classe->NewRules);

	// print '<pre>';
	// print_r($dados2);
	// print_r($dados);
	// print '</pre>';
	// exit();
}


//------------------------------------------------------------------------------------------------------

$pasta = 'dados/';
$arquivos = glob("$pasta{*.json}", GLOB_BRACE);

// print '<pre>';
// print_r($arquivos);
// print '</pre>';
if(isset($radio))
{
	foreach($arquivos as $arq)
	{

		if($radio == $arq)
		{
			//Caso o arquivo não seja lida, basta incluir o caminho completo até a pasta de dados.
			// $arquivo = '$_SERVER['DOCUMENT_ROOT']'."/".$arq;
			$arquivo = $arq;
			$nomegrafo= $arq;
			$Classe->nomegrafo = $arq;
		}
	}
}

// echo '<pre> ';
// print_r($arquivo);
// print_r($_SERVER['DOCUMENT_ROOT']);
// echo '</pre>';
  //------------------------------------------------------------------------------------------------------
if(!empty($arquivo))
	$DS = $Classe->PegadadosJson($arquivo,'dados',$_REQUEST['best_support']);

// print 'aqui';
// print_r($DS);
// exit;

$minsup = '';
// $minconf = 0.7;
// $minlevel = 1;
// $maxlevel = 4;
if(!empty($Classe->bestsup))
	$minsup = $Classe->bestsup;
elseif(isset($_REQUEST['edtminsup']))
	$minsup = $_REQUEST['edtminsup'];

//echo 'minsup: '.$minsup;
//exit;

//Atribuição de variáveis
if(isset($_REQUEST['edtminconf']))
	$minconf = $_REQUEST['edtminconf'];
if(isset($_REQUEST['edtminlevel']))
	$minlevel = $_REQUEST['edtminlevel'];
if(isset($_REQUEST['edtmaxlevel']))
	$maxlevel = $_REQUEST['edtmaxlevel'];
if(isset($_REQUEST['edtminmetric']))
	$minmetric = $_REQUEST['edtminmetric'];
if(isset($_REQUEST['endpoint_ext']))
	$endpoint_ext = $_REQUEST['endpoint_ext'];
if(isset($_REQUEST['edtMetricLift']))
	$MetricLift = $_REQUEST['edtMetricLift'];
if(isset($_REQUEST['edtMetricConviction']))
	$MetricConviction = $_REQUEST['edtMetricConviction'];
if(isset($_REQUEST['edtMetricMinSup']))
	$MetricMinSup = $_REQUEST['edtMetricMinSup'];
if(isset($_REQUEST['edtMetricMinConf']))
	$MetricMinConf = $_REQUEST['edtMetricMinConf'];

//define endpoint padrão
if (empty($endpoint_ext))
	$endpoint_ext = 'http://dbpedia.org/sparql';


if((!empty($minsup) && !empty($minconf) && !empty($minlevel) && !empty($maxlevel)) && empty($resp) && $op!='rules')
{

  //Passa valores para serem usados na classe quando necessário
  $Classe->DS = $DS;
  // $Classe->List = $List;
  $Classe->minsup = $minsup;
  $Classe->minconf = $minconf;
  $Classe->minlevel = $minlevel;
  $Classe->maxlevel = $maxlevel;

  $Classe->minmetric = $minmetric;

  $Classe->MetricMinSup = $MetricMinSup;
  $Classe->MetricMinConf = $MetricMinConf;
  $Classe->MetricLift = $MetricLift;
  $Classe->MetricConviction = $MetricConviction;


  // print '<pre>';
  // print '$DS: ';
  // print_r($DS);
  // print '</pre>';
  // exit;

   if(empty($DS) && !empty($arquivo))
    {
      //echo 'Erro8';
      //unset($_REQUEST);
      header("Location: /index.php?resp=erro8&edtminsup=$minsup&edtminconf=$minconf&edtmaxlevel=$maxlevel&edtminlevel=$minlevel&optionsRadios=$radio");
      exit;
    }
    //------------------------------------------------------------------------------------
    //Monta Tripla com dados de DS - Separadas por grupo
    //$List = $Classe->MontaTripla($DS); //Tirar pois consegui que viesse direto do arquivo Json formatado


    //Monta lista de $endpoint
    // $EndpointEntity = $Classe->MontaEndpointEntity($DS);

    // print_r($EndpointEntity);
    // exit;

	//print 'aqui:1';
    //Monta Lista de Endidades e Relações, passando os EndPoints e o Grafo direcionado
    $EntityInfo = $Classe->MontaEntitiesRelations($DS);

    // print '<pre>';
    // print '$EntityInfo: ';
    // print_r($EntityInfo);
    // exit;


	//print 'aqui2:';
    //-----------------------Algoritmo2

    foreach ($EntityInfo as $endpoint => $dados)
    {

     //  print '<pre>';
     //  print '<br>$endpoint: '.$endpoint.'<br>';
     //  print_r($dados);
     // exit;
      foreach ($dados as $relations => $Entidade)
      {
        // print '<pre>';
        // print_r($Entidade);
        // print '</pre>';
        // exit;
        $List_ItemChains = $Classe->GenerateItemChains($endpoint, $relations, $Entidade, $level=1,$endpoint);
      }

    }

    // print '<pre>';
    // print 'Entities_Var_Caminho:<br> ';
    // print_r($Classe->Entities_Var_Caminho);
    // exit;
	// print '<pre>';
    // print '<br>$List_ItemChains: ';
    // print 'Total: '.(count($List_ItemChains));
    // print_r($List_ItemChains);
    // print '</pre>';
    // exit;


     // print 'FIM';
     //  exit;
    if(empty($List_ItemChains) && !empty($arquivo))
    {
      //echo 'Erro1';
      //unset($_REQUEST);
      header("Location: /index.php?resp=erro1&edtminsup=$minsup&edtminconf=$minconf&edtmaxlevel=$maxlevel&edtminlevel=$minlevel&optionsRadios=$radio");
      exit;
    }

    


    //-----------------------Algoritmo3

    $LLICs = $AllLICs = $Classe->Generate2LargeItemChains($List_ItemChains,$Salvar_Externos);

    if(empty($LLICs) && !empty($arquivo))
    {
      //echo 'erro2';
      header("Location: /index.php?resp=erro2&edtminsup=$minsup&edtminconf=$minconf&edtmaxlevel=$maxlevel&edtminlevel=$minlevel&optionsRadios=$radio");
      exit;
    }
    // print '<pre>';
    // print '<br>$Generate2LargeItemChains: ';
    // print_r($LLICs);
    // /*print_r($AllLICs);*/
    // exit;

    $L = 1;

	//Removido em 18/11/2019
    //do { //desnecessário pois o algoritmo só da uma passada e nao tem nada para decrementar $Candidates de seu valor atual até 0 // while (count($Candidates) == 0)

    // print '--------------------------------------------------------------------------<br>';
    // print "----------------------- ".count($Candidates)." ---------------------------------------<br>";
    // print '--------------------------------------------------------------------------<br>';


    $L++;
    $Candidates = null;

    $total = count($LLICs);
    //echo 'Total: '.$total;
    //CRIA $Candidates
    for ($i=1; $i < $total; $i++) {
        for ($j=($i+1); $j <= $total; $j++) {
        $LIC1 = explode($delimiter, $LLICs[$i]['LOC']);
        $LIC2 = explode($delimiter, $LLICs[$j]['LOC']);
        //Verifica se os elementos na posição L-1 (ex: 2ª posição do array com 2 elementos), de $LIC1[$L-1] == $LIC2[$L-1]
        //se as últimas posições dos arrays são iguais.
        if($LIC1[$L-1] == $LIC2[$L-1]){
          $k++;

          // print 'L: '.$L;
          // print '<br>LIC1: ';
          // print_r($LIC1);
          // print '<br>LIC2: ';
          // print_r($LIC2);
          $values = array_unique(array_merge($LIC1,$LIC2));//une e elimina duplicidade.
          sort($values);// Ordena valores do Array
          $Candidates[$k] = $values;

          // print_r($values);
          // print_r($Candidates);
          // exit;
        }
      }
    }


    $LLICs = null;

    foreach ($Candidates as $key => $CIS) 
	{
		//Removido em 18/11/2019
        // if(count($CIS)>3){
          // print 'Verificar possível erro no número de variáveis. Pois se tiver mais de 3 colunas dará erro no próximo comando (split). A principio não deve ter 3 colunas';
          // print '<pre>';
          // print_r($CIS);
          // print '</pre>';
          // exit;
        // }
      list ($Chains1,$Chains2,$Chains3) = $CIS;

      //print $Chains1.' - '.$Chains2.' - '.$Chains3;
      $List_1 = explode($delimiter, $List_ItemChains[$Chains1]['LOE']);
      $List_2 = explode($delimiter, $List_ItemChains[$Chains2]['LOE']);
      $List_3 = explode($delimiter, $List_ItemChains[$Chains3]['LOE']);

      // print '<br><br>------------------------<br>';
      // print_r(array_intersect($List_1, $List_2, $List_3));
      // print '<br>------------------------<br><br><br>';
      // exit;
        $itens = implode($delimiter,array_intersect($List_1, $List_2, $List_3));
        $qtd_itens = count(array_intersect($List_1, $List_2, $List_3));
        $support = $Classe->CalcularSuporte($qtd_itens);
        // print $support;
        // exit;
        if($support >= $Classe->minsup){
          $i++;

          $LLICs[$i]['LOC'] = implode($delimiter, $CIS);
          $LLICs[$i]['LOE'] = $qtd_itens; //quantidades de itens que correspondem a intercessão dos elementos de cada $CIS
          $LLICs[$i]['LOE_var'] = $itens;
          $LLICs[$i]['Support'] = $support;
        }
    }



    $AllLICs = array_merge($AllLICs,$LLICs);

    // print '<pre>';
    // print '$AllLICs';
    // print_r($AllLICs);
    // exit;
	
	//$teste++;
	//print $teste.' - ';
	//Removida em 18/11/2019
  // } while (count($Candidates) == 0);//desnecessário pois o algoritmo só da uma passada e nao tem nada para decrementar $Candidates de seu valor atual até 0



    //-----------------------Algoritmo4
    if(empty($AllLICs) && !empty($arquivo))
    {
      header("Location: /index.php?resp=erro3&edtminsup=$minsup&edtminconf=$minconf&edtmaxlevel=$maxlevel&edtminlevel=$minlevel&optionsRadios=$radio");
      exit;
    }

    $Rules = $Classe->GenerateRules($AllLICs);

    if(empty($Rules) && !empty($arquivo))
    {
     header("Location: /index.php?resp=erro4&edtminsup=$minsup&edtminconf=$minconf&edtmaxlevel=$maxlevel&edtminlevel=$minlevel&optionsRadios=$radio");
      exit;
    }

    // print '<pre>';
    // print '$Rules: ';
    // print_r($Rules);
    // exit();

  if($Salvar_Externos)
  {
	//if(empty($local_link))
	//{
        ////Apenas os recrusos que dão suporte as regras. (entidade de onde se iniciam os caminhos)
        //$entitiesLocais = $Classe->Entities_DtLocal;
		
		if(!isset($_REQUEST['GetExternalResources']))
		{
			////Pegar entidades com links externos (sameAs e etc... Com base no arquivo "links_ex/links.json")
			$entitiesFinais = $Classe->PegaLinksExternos($Classe->Entities_DtLocal, $externalPredicates_array, $DS);
		}
		else
		{
			$entitiesFinais = $Classe->Entities_DtLocal;
		}	
		
		// // Uma das tarefas listadas como trabalho futuro. (Em desenvolvimento) - Precisa de mais testes.
        ////Todos os recursos ao longo do caminho do grafo  (Todas as entidades ao longo de cada caminhos)
        // $Entities_Var_Caminho = array_filter(array_unique(explode($delimiter, $Classe->Entities_Var_Caminho)));
        ////Pegar entidades com links externos (sameAs e etc... Com base no arquivo "links_ex/links.json")
        // $entitiesFinais_caminhos = $Classe->PegaLinksExternos($Entities_Var_Caminho, $externalPredicates_array, $DS);

        // print '<pre>';
        // print_r($entitiesLocais);
        // print_r($Entities_Var_Caminho);
        // print_r($entitiesFinais);
        // print_r($entitiesFinais_caminhos);
        // print '</pre>';
        // exit();

        if(!empty($entitiesFinais))
        {
            $TotalNewDS = $Classe->PegaDadosExternos($entitiesFinais,$DS,$nomegrafo,$endpoint_ext);
        }
        else
        {
            header("Location: /index.php?resp=erro9&edtminsup=$minsup&edtminconf=$minconf&edtmaxlevel=$maxlevel&edtminlevel=$minlevel&optionsRadios=$radio");
            exit;
        }
	//}
	/*
	else
	{
    	//Trabalhar dados localmente.
        $i=0;
        foreach ($Rules as $key => $value)
        {
          // print_r($value);
          // exit();
            $i++;
            if($i>1)
              $a = $delimiter;
            //pegar todos os elementos para buscar os links externos primeiro converte o string para array remove os itens duplicados
            if(!empty($Externo_EndPoints) && !empty($Externo_Entidades)){
              $EntitiesExternos .= $a.$value['LOE_var'].$delimiter.$value['EndPoints_Ant'].$delimiter.$value['EndPoints_Cons'];
            }elseif(!empty($Externo_EndPoints) && empty($Externo_Entidades)){
              $EntitiesExternos .= $a.$value['EndPoints_Ant'].$delimiter.$value['EndPoints_Cons'];
            }elseif(empty($Externo_EndPoints) && !empty($Externo_Entidades)){
              $EntitiesExternos .= $a.$value['LOE_var'];
            }else{
              header("Location: /index.php?resp=erro5&edtminsup=$minsup&edtminconf=$minconf&edtmaxlevel=$maxlevel&edtminlevel=$minlevel&optionsRadios=$radio");
              exit;
            }

        }
        $Entitiesfinal = array_unique(explode($delimiter, $EntitiesExternos));
        // print '<pre>';
        // print_r($Entitiesfinal);
        // print '</pre>';
        // exit();
      $return = $Classe->PegaDadoslocais($Entitiesfinal,$DS,$nomegrafo);

      // if($return)
      // {
      //   header("Location: /index.php?salverules=ok_E");
      //   exit;
      // }
      // else
      // {
      //   header("Location: /index.php?salverules=erro_E");
      //   exit;
      // }

   	}*/
  }

    ////////////////////////////  FIM //////////////////////////////////////////////
    // Terminamos o "contador" e exibimos
    list($usec, $sec) = explode(' ', microtime());
    $script_end = (float) $sec + (float) $usec;
    $elapsed_time = round($script_end - $script_start, 5);
    // Exibimos uma mensagem
    // echo 'Elapsed time: ', $elapsed_time, ' secs. Memory usage: ', round(((memory_get_peak_usage(true) / 1024) / 1024), 2), 'Mb';


    // print '<pre>';

    // // print '<br><br><br>$AllLICs: ';
    // // print_r($AllLICs);

    // print '<br>$List_ItemChains: ';
    // print_r($List_ItemChains);

    // print '<br>$Rules: ';
    // print_r($Rules);


    $htmlTable = $Classe->MontaTabela($Rules);
	//$_SESSION['s_Rules'] = null;
	$FormatacaoRules = null;
    $FormatacaoRules = $Classe->FormataRules($Rules,$List_ItemChains);

    if(!empty($FormatacaoRules))
    {
        $pasta = 'Rules';
        //Salvar Rules em diretorio no formato Json
        // if($Salvar_Regras)
        // {
          // $Classe->SalvarJson($FormatacaoRules,$radio,$pasta);
        // }
        //TESTE Salvar SESSION
        $_SESSION['s_Rules'] = $FormatacaoRules;
        $_SESSION['s_radio'] = $radio;
        $_SESSION['s_pasta'] = $pasta;
        //$_SESSION[s_original_header] = array('minsup' => $Classe->minsup, 'minconf' => $Classe->minconf, 'maxlevel' => $Classe->maxlevel, 'minlevel' => $Classe->minlevel, 'Lift' => $Classe->Lift, 'Conviction' => $Classe->Conviction, 'TotalGrafo' => $Classe->TotalGrafo );
        // print '<pre>';
        // print_r($_SESSION);
        // exit();
      }

    $htmlTableRules = $Classe->MontaTabelaRules($FormatacaoRules);

    if($show_table == 'on')
    {
      echo $htmlTableRules;
      exit;
    }




}
elseif(isset($op) && $op=='rules')
{

  $Classe->SalvarJson($_SESSION['s_Rules'],$_SESSION['s_radio'],$_SESSION['s_pasta'],$_SESSION['s_original_header']);
  header("Location: /index.php?salverules=ok_L");
  exit;

}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Algorithm MRAR_plus</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!--DIV Carregando... -->
  <script src="js/loading.js"></script>

  <style>
    /* Sortable tables */
    table.sortable thead {
        background-color:#eee;
        color:#666666;
        font-weight: bold;
        cursor: default;
    }
  </style>
  <script src="js/sorttable.js"></script>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-collapse sidebar-mini">
<div class="wrapper">

<!-- TOPO -->

  <header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">MRAR+</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Algorithm MRAR+</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="dist/img/eu.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs">Felipe Alves</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="dist/img/eu.jpg" class="img-circle" alt="User Image">

                <p>
                 Aluno: Felipe Alves - Web Developer
                  <small>IME - 2018</small>
                </p>
              </li>
              <!-- Menu Body
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="http://lattes.cnpq.br/0510166925160914" class="btn btn-default btn-flat">CV Lattes</a>
                </div>
                <div class="pull-right">
                  <a href="https://www.linkedin.com/in/felipealvesdeoliveira" class="btn btn-default btn-flat">Linkedin</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

<!-- TOPO  FIM-->


<!-- MENU LATERAL -->
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/eu.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Felipe Alves</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="active"><a href="index.php?op=comp"><i class="fa fa-circle-o"></i> Compare Rules</a></li>
            <!-- <li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li> -->
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

<!-- MENU LATERAL FIM -->


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        MRAR+ Dashboard
        <?php if(!empty($elapsed_time)){ ?>
        <small>-<b>
        <?php
            echo 'Elapsed time: ', $elapsed_time, ' secs. Memory usage: ', round(((memory_get_peak_usage(true) / 1024) / 1024), 2), 'Mb';
        ?>
        </b>
		</small>
		<?php }?>
    </section>

<?php if(!empty($resp)){ ?>
    <div class="pad margin no-print">
      <div class="callout callout-warning" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-warning"></i> Alerta: <?php echo $resp; ?></h4>
        <?php
            if($resp == 'erro1'){
              echo 'Não foi possível gerar a variável <b>$List_ItemChains</b>. Altere os valores de suporte e confiança para executar o algoritmo!';
            }if($resp == 'erro2'){
              echo 'Não foi possível gerar a variável <b>$LLICs</b>. Altere os valores de suporte e confiança para executar o algoritmo!';
            }if($resp == 'erro3'){
              echo 'Não foi possível gerar a variável <b>$AllLICs</b>. Altere os valores de suporte e confiança para executar o algoritmo!';
            }if($resp == 'erro4'){
              echo 'Não foi possível gerar a variável <b>$Rules</b>. Altere os valores de suporte e confiança para executar o algoritmo!';
            }if($resp == 'erro5'){
              echo 'Selecione qual dos links externos você deseja buscar!';
            }if($resp == 'erro6'){
              echo 'Verifique o preenchimento dos campos!';
            }if($resp == 'erro7'){
              echo 'Verifique sua conexão com a internet e tente novamente!';
            }if($resp == 'erro8'){
              echo 'Não foi possível ler dos dados! <b>'.$Classe->json_msg.'</b>';
            }if($resp == 'erro9'){
              echo 'Não foi possível encontrar os links externos dos recursos! Verifique se os recusos possuem alguma relação com algum dos "External Predicates" para as relações presentes no arquivo "links_ex/links.json". </b>';
            }if($resp == 'erro10'){
              echo 'Não foi possível executar o comando do SPAQL Query. Verifique a função <b>PegaDadosExternos</b>. </b>';
            }
        ?>
      </div>
    </div>
<?php } if(isset($salverules) && $salverules=='ok_L'){ ?>
    <div class="pad margin no-print">
      <div class="callout callout-success" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-warning"></i> As Regras foram salvas com sucesso!</h4>
      </div>
    </div>
<?php } if(isset($salverules) && $salverules=='ok_E'){ ?>
    <div class="pad margin no-print">
      <div class="callout callout-success" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-warning"></i> Novo dataset criado com sucesso!</h4>
      </div>
    </div>
<?php } if(isset($salverules) && $salverules=='erro_E'){ ?>
    <div class="pad margin no-print">
      <div class="callout callout-danger" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-warning"></i> Não foi possível criar o novo dataset!</h4>
      </div>
    </div>
<?php } if(!empty($TotalNewDS)){ ?>
    <div class="pad margin no-print">
      <div class="callout callout-success" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-warning"></i> A new dataset was created with a total of <?php echo $TotalNewDS; ?> </h4>
      </div>
    </div>
<?php } ?>




<!-- /////////////////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////////////////////// -->
<?php
if(!empty($minsup)){
    $minsup = $minsup;
}else{
 $minsup = '0.1';
}
if(!empty($_REQUEST['edtminconf'])){
    $minconf = $_REQUEST['edtminconf'];
}else{
 $minconf = '0.7';
}
if(!empty($_REQUEST['edtminlevel'])){
    $minlevel = $_REQUEST['edtminlevel'];
}else{
 $minlevel = '1';
}
if(!empty($_REQUEST['edtmaxlevel'])){
    $maxlevel = $_REQUEST['edtmaxlevel'];
}else{
 $maxlevel = '4';
}
if(!empty($_REQUEST['edtminmetric'])){
    $minmetric = $_REQUEST['edtminmetric'];
}else{
 $minmetric = '0.2';
}


if(!empty($_REQUEST['edtMetricMinSup'])){
    $MetricMinSup = $_REQUEST['edtMetricMinSup'];
}else{
 $MetricMinSup = '0.15';
}
if(!empty($_REQUEST['edtMetricMinConf'])){
    $MetricMinConf = $_REQUEST['edtMetricMinConf'];
}else{
 $MetricMinConf = '0.20';
}
if(!empty($_REQUEST['edtMetricLift'])){
    $MetricLift = $_REQUEST['edtMetricLift'];
}else{
 $MetricLift = '0.30';
}
if(!empty($_REQUEST['edtMetricConviction'])){
    $MetricConviction = $_REQUEST['edtMetricConviction'];
}else{
 $MetricConviction = '0.35';
}



?>
<div class="row">
        <!-- left column -->
        <div class="col-md-4">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
            <h3 class="box-title">Variables Form
            <?php if(!empty($Classe->TotalGrafo)){ ?>
               <?php echo ' <br> Graph: <b>'.$Classe->TotalGrafo.' nodes and '.$Classe->TotalRelacoes.' edges. </b>';?>
            <?php }?>
            </h3>
              <!-- collapse -->
                   <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
               <!-- collapse -->
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" name="frm" id="frm" action="index.php" method="post">
              <div class="box-body">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edtminsup">MinSup</label>
                    <input type="text" class="form-control" value="<?php echo $minsup; ?>" id="edtminsup" name="edtminsup" placeholder="minsup">
                  </div>

                  <div class="form-group">
                    <label for="edtminlevel">MinLevel</label>
                    <input type="text" class="form-control"  value="<?php echo $minlevel; ?>" id="edtminlevel" name="edtminlevel" placeholder="minlevel">
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edtminconf">MinConf</label>
                    <input type="text" class="form-control"  value="<?php echo $minconf; ?>" id="edtminconf" name="edtminconf" placeholder="minconf">
                  </div>

                  <div class="form-group">
                    <label for="edtmaxlevel">MaxLevel</label>
                    <input type="text" class="form-control"  value="<?php echo $maxlevel; ?>" id="edtmaxlevel" name="edtmaxlevel" placeholder="maxlevel">
                  </div>
                </div>
                <!-- <div class="col-md-12">
                  <div class="form-group">
                    <label for="edtminmetric">MinMetric</label>
                    <input type="text" class="form-control"  value="<?php //echo $minmetric; ?>" id="edtminmetric" name="edtminmetric" placeholder="minmetric">
                  </div>

                </div> -->

               <div class="col-md-12">
                <label for="edtmaxlevel">Select dataset:</label> <br>
                    <?php
                        $pasta = 'dados/';
                        // $arquivos = glob("{*.json,*.png,*.jpg,*.jpeg,*.gif}", GLOB_BRACE);
                        $arquivos = glob("$pasta{*.json}", GLOB_BRACE);

                         $htmlRadio = " <div class='col-md-12S'>
                                        <select class='form-control' id='optionsRadios' name='optionsRadios'>
                                          "; //<option>Select Dataset</option>

                          foreach($arquivos as $arq)
                          {
                          	if(empty($_REQUEST['optionsRadios'])) $_REQUEST['optionsRadios'] = 'dados/Dt_Neymar.json';
                            $rest = str_replace(array('.json', $pasta), '',$arq);
                            if($_REQUEST['optionsRadios'] == $arq) $checked = "selected"; else $checked = "";
                            	$htmlRadio .=" <option value='$arq' $checked>$rest</option> ";
                          }
                            $htmlRadio.="</select> </div>";
                          echo $htmlRadio;  ?>
               </div>
              <div class="col-md-12">
                	<div class="form-group">
                      <label for="endpoint_ext">External Endpoint</label>
                      <input type="text" class="form-control"  value="<?php echo $endpoint_ext; ?>" id="endpoint_ext" name="endpoint_ext" placeholder="M. Conviction">
                  </div>
                    <label for="endpoint_ext">External Predicates: </label> (<?php echo $externalPredicates; ?>)
              </div>
				

               <!-- <div class="col-md-12">
               <div class="form-group">
               <label for="endpoint_ext">External Predicates: </label> (<?php// echo $externalPredicates; ?>)
                     <input type="text" class="form-control"  value="<?php //echo $externalPredicates; ?>" id="edtexternalpredicates" name="edtexternalpredicates" >
                  </div>
              </div> -->

              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseExample" aria-expanded="true" aria-controls="collapseExample">
               Configuration
              </a>

              <div id="collapseExample" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
               	<div class="row">
                 	<div class="col-md-12">
                    <div class="col-md-6">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="Entities_Var" <?php if(isset($_REQUEST['Entities_Var']) && $_REQUEST['Entities_Var'] == 'on') echo 'checked'; ?> > Show Entities
                        </label>
                      </div>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="Show_table" <?php if(isset($_REQUEST['Show_table']) && $_REQUEST['Show_table'] == 'on') echo 'checked'; ?> > Show only the table
                        </label>
                      </div>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="best_support" <?php if(isset($_REQUEST['best_support']) && $_REQUEST['best_support'] == 'on') echo 'checked'; ?> > Apply the best support
                        </label>
                      </div>
						<div class="checkbox">
							<label>
							  <input type="checkbox" name="GetExternalResources" <?php if(isset($_REQUEST['GetExternalResources']) && $_REQUEST['GetExternalResources'] == 'on') echo 'checked'; ?> > Get external resources for local data
							</label>
						</div>
					
					</div>
                      <div class="col-md-4">
              					<div class="form-group">
              					  <label for="edtdelimiter">Def. delimiter</label>
              					  <input type="text" class="form-control" value="<?php echo $delimiter; ?>" id="edtdelimiter" name="edtdelimiter" maxlength="2" size="2" required>
              					</div>
              				</div>
          				</div>

        				</div>
              </div>

              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="gerarregras();exibe('loading');" title="Minerar dados (DT A)">MRAR</button>
                <button type="button" class="btn btn-warning" onclick="gerarComLinkExterno();ping();exibe('loading');" title="Minerar dados e gerar novo dataset com os links externos (DT A+)">MRAR+</button>
                <button type="button" class="btn btn-success" onclick="salvarRegras();" title="Salvar Regras geradas em diretório de trabalho">Save Rules</button>
              </div>
            </div>
            </form>
          </div>
          <!-- /.box -->
      </div>


<?php if(isset($op) && $op == 'comp'){  ?>
<!-- ////////////////////////////////    Table  1  ////////////////////////////////////// -->
        <div class="col-xs-12" style="overflow:auto;height:460px; width:800px">
          <div class="box box-warning">
            <div class="box-header  with-border">
              <h3 class="box-title">Compare Rules </h3>
                <!-- collapse -->
                   <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
               <!-- collapse -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form role="form" action="index.php" method="post">
                <input type="hidden" name="op" id="op" value="<?php echo $op;?>">
                    <?php
                    //chdir( 'rules/' );
                    $pasta = 'rules/';
                        // $arquivos = glob("{*.json,*.png,*.jpg,*.jpeg,*.gif}", GLOB_BRACE);
                        $arquivos = glob("$pasta{*.json}", GLOB_BRACE);

                         $htmlRules .=" <div class='col-md-6'>
                                          <label for='OriginalDataset'>Original Dataset</label>
                                        <select class='form-control' name='comp_dados'>
                                          <option>Select the rules of the original dataset</option> ";

                          $htmlRules2 .=" <div class='col-md-6'>
                                           <label for='ExtendedDataset'>Extended Dataset</label>
                                            <select class='form-control' name='comp_dados2'>
                                            <option>Select extended dataset rules</option> ";
                          foreach($arquivos as $arq)
                          {
                            // $rest = substr($arq, 0, -5); // remove ".json" do nome do arquivo --ultimos 5 caracteres
                            $rest = str_replace(array('.json', $pasta), '',$arq);
                            // print '<br>'.$arq.' - '.$_REQUEST[comp_dados];

                             if($_REQUEST['comp_dados'] == $arq) $checked = "selected"; else $checked = "";
                             if($_REQUEST['comp_dados2'] == $arq) $checked2 = "selected";  else $checked2 = "";


                            $htmlRules .=" <option value='$arq' $checked>$rest</option> ";
                            $htmlRules2 .=" <option value='$arq' $checked2>$rest</option> ";

                          }
                            $htmlRules.="</select> </div>";
                            $htmlRules2.="</select> </div>";
                          echo $htmlRules;
                          echo $htmlRules2;

                    ?>
                <div class="col-md-12">
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary" onclick="exibe('loading');">Compare</button>
                    <!--<button type="submit" class="btn btn-danger" onclick="exibe('loading');">Delete</button>-->
                  </div>
                </div>
                </form>

            </div>
            <!-- /.box-body -->
          </div>

        <?php if(!empty($htmlCompRules)){  ?>
	<!-- ////////////////////////////////    Table  1  ////////////////////////////////////// -->
         <div class="box box-warning">
            <div class="box-header  with-border">
              <h3 class="box-title">Rules Result </h3>
                <!-- collapse -->
                   <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
               <!-- collapse -->

            </div>
            <!-- /.box-header -->
            <div class="box-body">
                    <?php
                        echo $htmlCompRules;
                    ?>
            </div>
            <!-- /.box-body -->
          </div>
		<?php } ?>
          <!-- /.box -->
      </div>



<?php }
	  if(!empty($htmlNewRulesTable)){  ?>


<!-- ////////////////////////////////    Table  1  ////////////////////////////////////// -->
        <div class="col-md-12" style="overflow:auto;height:500px; width:1245px">
          <div class="box box-warning">
            <div class="box-header  with-border">
              <h3 class="box-title">New rules table </h3>
                <!-- collapse -->
                   <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
               <!-- collapse -->

            </div>
            <!-- /.box-header -->
            <div class="box-body">
                    <?php
                        echo $htmlNewRulesTable;
                    ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
      </div>

<?php } if(!empty($htmlTable)){  ?>


<!-- ////////////////////////////////    Table  1  ////////////////////////////////////// -->
        <div class="col-xs-12" style="overflow:auto;height:420px; width:820px">
          <div class="box box-warning">
            <div class="box-header  with-border">
              <h3 class="box-title">Rules </h3>
                <!-- collapse -->
                   <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
               <!-- collapse -->

            </div>
            <!-- /.box-header -->
            <div class="box-body">
                    <?php
                        echo $htmlTable;
                    ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
      </div>

<?php }
		if(!empty($htmlTableRules)){  ?>
      <div class="col-md-12" style="overflow:auto;height:500px; width:1245px">
          <div class="box box-warning"">
           <div class="box-header with-border">
              <h3 class="box-title">Formatted Rules </h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>

              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php
                    echo $htmlTableRules;
                ?>
           </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
      </div>



      <?php } ?>


<!-- loading-  Carregando... -->
      <div id="loading" style="display: none;
          background: #FFFFFF;
          position: absolute;
          width: 400px;
          top: 25%;
          left: 50%;
          margin-left: -200px;
          margin-top: -100px;
          border-style: solid;
          border-color: black;
          border-width: 1px;
          text-align: center;
          text-transform: uppercase;
          font-family: arial;
          font-weight: bold;
          color: silver;
          z-index: 3;">
        <br>
        <br>
        <br><img alt="Loading..." src="imagens/rodas.gif" width="180" height="150">
        <br>Loading...

        <br>
      </div>



</div>







  </div>
  <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0
    </div>
    <strong>Copyright &copy; 2017-2018 <a href="http://www.ime.eb.br/">IME</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/filtro.js"></script>
<script type="text/javascript" charset="utf-8">
  function validaForm()
  {
     r = true;
     m = '';
    if ((document.getElementById('edtminsup').value == '')
    	|| (document.getElementById('edtminconf').value == '')
    	|| (document.getElementById('edtminlevel').value == '')
    	|| (document.getElementById('edtmaxlevel').value == '')  )
    {
        r = false;
      m = 'Verifique o preenchimento dos campos. \r\n';
    }
    if (r == false){
       alert(m);
       return false;
    }
    else
    {
        return true;
    }
  }

  function gerarComLinkExterno()
  {
  	if(validaForm()==true)
  	{
        document.getElementById('frm').action='index.php?salvar_externos=on';
        document.getElementById('frm').submit();
	}
	else
	{
		window.location="/index.php?resp=erro6";
	}
  }

  function gerarregras()
  {
    if(validaForm()==true)
  	{
        document.getElementById('frm').action='index.php';
        document.getElementById('frm').submit();
	}
	else
	{
		window.location="/index.php?resp=erro6";
	}

  }
  function salvarRegras()
  {
        document.getElementById('frm').action='index.php?op=rules';
        document.getElementById('frm').submit();

  }

  function ping(){
		<?php
			if(!$sock = fsockopen('www.google.com.br',80,$num,$error,5))
	   			$msg = "Você está sem conexão a internet, verifique a sua conexão e tente novamente!";
		?>
		var msgjs = "<?php echo $msg;?>";
	     if(msgjs) {
	       alert(msgjs);
	       window.location="/index.php?resp=erro7";
	       exit;
	     }
	}
</script>



<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery UI 1.11.4
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>-->
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
 -->
<script src="plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
-->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
