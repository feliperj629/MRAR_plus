<?php
// error_reporting(E_ALL);
// ini_set('display_errors','1');

class Mrar
{
    var $DS; //Dados do grafo - bruto
    // var $List; // Lista em tripla
    var $Entities_V;
    var $EntityInfo;
    var $entityArray;


    //valores passados pelo Usuário
    var $minsup;
    var $minconf;
    var $minlevel;
    var $maxlevel;

    var $delimiter;

    var $bestsup;

    //var $minmetric;
    //var $MetricMinSup;
    //var $MetricMinConf;
    //var $MetricLift;
    //var $MetricConviction;

    var $TotalGrafo;
    var $TotalRelacoes;
    var $totalNewDt;

    var $ChainID;
    var $List_ItemChains;

    var $EndPoints_Ant;
    var $EndPoints_Cons;

    var $Conviction_min;
    var $Conviction_max;

    var $Entities_DtLocal;
    var $Entities_Var_Caminho;
    var $Entities_DtExt;
    var $nomegrafo;

    var $NewRules;
    var $json_msg;

function CompRules($array1,$array2)
{
// print '<pre>';
// print_r($array2);

$RegrasNovas = $array2;

$RegrasDiscartadas = $array1;

  foreach ($array2 as $key2 => $value2)
  {
     foreach ($array1 as $key1 => $value1)
     {
       if(array_values($value2[Ant]) == array_values($value1[Ant]) && array_values($value2[Cons]) == array_values($value1[Cons]))
        {
          //se a regra for igual a antiga, deleta da lista, para ter um conjunto de regras que só são novas.
          unset($RegrasNovas[$key2]);

          //Do conjunto original, ao remover as regras comuns, sobram apenas as que não foram geradas.
          unset($RegrasDiscartadas[$key1]);

          $RegrasComuns[] = $value2;
        }
     }
  }

// echo '<br><br>------------------- TotalRullesArray1 -----------------';
//  print '<br>Total array1: '.count($array1);

// echo '<br><br>------------------- TotalRullesArray2 -----------------';
//  print '<br>Total array2: '.count($array2);

// echo '<br><br>------------------- RegrasNovas -----------------';
//   // print_r($RegrasNovas);

//  print '<br>Total de regras novas: '.count($RegrasNovas);

// echo '<br><br>------------------- RegrasComuns -----------------';
//   // print_r($RegrasComuns);
// print '<br>Total de regras comuns: '.count($RegrasComuns);

// echo '<br><br>------------------- RegrasDiscartadas -----------------';
//   // print_r($RegrasDiscartadas);
// print '<br>Total de regras descartadas: '.count($RegrasDiscartadas);
//  // exit;

$htmlTable = '
        <table id="CompRules" class="table table-bordered table-hover sortable">
          <thead>
            <tr>
              <th> Rules MRAR </th>
              <th> Rules MRAR+ </th>
              <th> New Rules </th>
              <th> Common Rules</th>
              <th> Discarded Rules</th>
            </tr>
          </thead>
          <tbody>  ';

       $htmlTable .= "
            <tr>
                <td> <b>".count($array1)."</b> </td>
                <td>".count($array2)." (".round((count($array2)/*-count($array1)*/)*100/count($array1))."%)  </td>
                <td> ".count($RegrasNovas)." (".round(count($RegrasNovas)*100/count($array1))."%) </td>
                <td> ".count($RegrasComuns)." (".round(count($RegrasComuns)*100/count($array1))."%) </td>
                <td> ".count($RegrasDiscartadas)." (".round(count($RegrasDiscartadas)*100/count($array1))."%) </td>
           </tr> ";

      $htmlTable .= '
                    </tbody>
               <!-- <tfoot>
                <tr>
                  <th> Total rules MRAR </th>
                  <th> Total rules MRAR+ </th>
                  <th> New Rules </th>
                  <th> Common Rules</th>
                  <th> Discarded Rules</th>
                </tr>
                </tfoot> -->
              </table>';

  $this->NewRules = $RegrasNovas;

  return $htmlTable;
}



function CalcularTamanhoGrafo($ArrayValor,$DS)
{
	//print 'aqui-2';
    if(!empty($DS) && empty($ArrayValor))
    {
		//print"<pre>";
		 //print_r($DS);
		// print "</pre>";
		// exit;
      foreach($DS as $key => $campo)
      {
        $Subject[$key] = $campo['Subject'];
        $Object[$key] = $campo['Object'];
      }
      $Entidades = array_unique(array_merge($Subject,$Object));
	  // print_r (array_merge($Subject,$Object));
	   //print '<br>$Entidades: '.count($Entidades);
      $TotalGrafo = count($Entidades);
	  //print '<br>$TotalGrafo: '.$TotalGrafo;
	  //exit;
    }
    else
    {
       $TotalGrafo = count($ArrayValor);
    }

    $this->TotalRelacoes = count($this->DS);

    if(!empty($ArrayValor))
       $this->TotalGrafo = $TotalGrafo;

  return  $TotalGrafo;
}


function CalcSugestaoSuporte($original_header)
{
  //print_r($original_header);
 // exit;
  //fórmula(Suporte_old*TotalGrafo_old/(TotalGrafo_new/100))/100;
  if(!empty($original_header))
      $bestsup = ($original_header['minsup']*$original_header['TotalGrafo_old']/($original_header['TotalGrafo_new']/100))/100;

  //print($bestsup);
  //exit;
	//$bestsup = number_format($bestsup,2);

    if(!empty($bestsup))
        $this->bestsup = $bestsup;

  return  true;

}
function CalcularSuporte($valor)
{
    if(empty($this->TotalGrafo))
    {
      foreach($this->DS as $key => $campo)
      {
        $Subject[$key] = $campo['Subject'];
        $Object[$key] = $campo['Object'];
      }
        // print count($Subject);
        // print '<br>';
        // print count($Object);
        // print '<br>';
        // print count(array_merge($Subject,$Object));
        // print '<br>';
        $Entidades = array_unique(array_merge($Subject,$Object));
        // print count($Entidades);
        // print '<br>';
        // print_r($Entidades);
        // exit;
        //Calcular valor total de vertices no grafo.
		//print 'aqui';
        $this->CalcularTamanhoGrafo($Entidades,null);

    }

    // print $this->TotalGrafo;
    // print '<br>';
    // print $valor;
    // exit();
    $support = $valor / $this->TotalGrafo;//19 = número de total de nó no grafo
    $support = number_format($support,2); //Formata nº 0.12
    //$support = substr($support, 1, 3);  // bcdd



    return $support;
}



// function MontaEndpointEntity($DS){
//     $k = 0;
//     //Monta lista de $endpoint
//     foreach ($DS as $key => $value) {
//       if(!in_array($value['Object'], $EndpointEntity)){//para nao duplicar
//           $EndpointEntity[$k] = $value['Object'];
//           $k++;
//       }
//     }

//     $this->EndpointEntity = $EndpointEntity;

//     return $EndpointEntity;
// }



function UnionIncomingEdgesOf($Entities_Var)
{
//União das arestas de entrada das entidades_Var (entidades envolvidas)
  $EntityInfo = $this->EntityInfo;

  // print '<pre>';
  // print_r($Entities_Var);
  // print_r($EntityInfo);
  // print '</pre>';
//  exit;
  $this->entityArray = array();
  foreach ($Entities_Var as $key => $entiti)
  {
    // print '<br>'.$key;
    // print '<pre>';
    // print_r($EntityInfo[$entiti]);
    // exit;

    if(empty($this->entityArray))
    {
      $this->entityArray = $EntityInfo[$entiti];
    }
    else
    {
      if (array_key_exists(key($this->entityArray), $EntityInfo[$entiti]))
      {
        foreach ($EntityInfo[$entiti][key($this->entityArray)] as $key2 => $value) {
            $this->entityArray[key($this->entityArray)][$key2] = $value;
        }
      }
    }
      // print '<pre>';
      // print '<br> ------------------------------------------------------------------------------------------<br>';
      // print_r($EntityInfo[$entiti]);
      // print_r($this->entityArray);
      // // print_r(key($this->entityArray));
      // print '<br> ------------------------------------------------------------------------------------------<br>';
      // print '</pre><br><br>';
      // exit;
  }

  // exit;
    $Relations = key($this->entityArray);
    $this->Entities_V = $this->entityArray[$Relations];
    $Relations_Var[0] =  $Relations;
    // print $teste;

      // print '<pre>';
      // print '<br> -----------------------------------------------------------------------------------<br>';
      // print'<br>Relations: '.$Relations.'<br><br>';
      // print_r($this->entityArray);
      //   print '</pre>';
      // exit;


    return $Relations_Var;
}



function MontaEntitiesRelations($DS){
// print '<pre>';
// print_r($DS);
// print '</pre>';
// exit;
    foreach($DS as $key => $campo)
    {
          $EntityInfo[$campo['Object']][$campo['Predicado']][$key] = $campo['Subject']; //MOnta uma lista com os endpoint como objeto ()-[]->(objeto)
    }

    $this->EntityInfo =  $EntityInfo;
    return $EntityInfo;
}



function GenerateItemChains($EndpointEntity, $Relations_Parameter, $Entities_Parameter, $level,$endpoint_Comp)
{
          // print '<pre>';
          // print '<br><b>EndpointEntity: </b>';
          // print_r($EndpointEntity);

          // print '<br><b>Relations_Parameter:</b> ';
          // print_r($Relations_Parameter);

          // print '<br><b>Entities_Parameter:</b> ';
          // print_r($Entities_Parameter);
          // print '<br>level:</b> '.$level;
          // print '<br>minlevel:</b> '.$this->minlevel;
          // print '<br>maxlevel:</b> '.$this->maxlevel;
          // print '</pre>';
          // print '<br>';
          // exit;


    if($level >= $this->minlevel && $level <= $this->maxlevel)
    {
      //  print '-------------------------------'.$level.'--------------------------------------<br>';


      $Entities_Var =  array_unique($Entities_Parameter);   //remover duplicidade

		// print '<pre>';
          // print '<br><b>Entities_Var: </b>';
          // print_r($Entities_Var);
          // exit;
      $support = $this->CalcularSuporte(count($Entities_Var));
      // print '<pre>';
      // print '<br><b>support: </b>';
      // print $support.', ';
      // exit;


        if($support > $this->minsup)
        {
          // print '<pre>';
          // print 'aqui<br>';
          //print_r($Entities_Var);
          // print 'support: '.$support;
          // print '</pre>';
        //  exit;

          $entidades = implode($this->delimiter, $Entities_Var);//Junta array separando por vírgula
          //TEste felipe (2019/11/11)
          // if(strpos($entidades, 'Brasil'))
          // {
          //   print '<pre>';
          //   print 'aqui<br>';
          //   print $entidades;
          //   print '</pre>';
          //   exit;
          // }
          // //Guardar recursos no caminho da regra.
          if (!empty($entidades)) {
              $this->Entities_Var_Caminho .= $entidades.$this->delimiter.$EndpointEntity.$this->delimiter;
          }
          //Criando novo ItemChain
          // $ChainID = $this->ChainID;

          $this->ChainID++; //Identificador único
          $this->List_ItemChains[$this->ChainID]['ChainID'] =  $this->ChainID;  //ID
          $this->List_ItemChains[$this->ChainID]['LOE'] =  $entidades;  //List of Entities
          $this->List_ItemChains[$this->ChainID]['LOR'] =  $Relations_Parameter; //List of Relations
          $this->List_ItemChains[$this->ChainID]['EndpointEntity'] =  $EndpointEntity;
          $this->List_ItemChains[$this->ChainID]['Support'] =  $support;
          //$this->List_ItemChains[$this->ChainID]['endpoint_Comp'] =  $endpoint_Comp;

        } //FIM  - if($support >
    }// FIM - if($level >= $this->minlevel && $level <= $this->maxlevel)

    // // Mostrar List_ItemChains
    // print '<pre>';
    // print 'List_ItemChains:<br> ';
    // print_r($this->List_ItemChains);
    // exit;


    if($level < $this->maxlevel){
        $Relations_Var = $this->UnionIncomingEdgesOf($Entities_Var);
        // print '<pre>';
        // print 'Relations_Var:<br> ';
        // print_r($Relations_Var);

       //  print '<pre>';
       //  print 'aqui<br>';
       //  print 'Entities_Var_Caminho:<br> ';
       //  print_r($this->Entities_Var_Caminho);
       //
       //  print 'Entities_Var:<br> ';
       //  print_r($Entities_Var);
       // exit;
        //$Entities_Var_endpoint = $endpoint_Comp.$this->delimiter.implode($this->delimiter, $Entities_Var);

        foreach ($Relations_Var as $key => $relacao) {
           $this->GenerateItemChains($EndpointEntity, $Relations_Parameter.$this->delimiter.$relacao, $this->Entities_V, $level+1,$Entities_Var_endpoint);
        }
    }
   //  // Mostrar List_ItemChains
    // print '<pre>';
    // print 'List_ItemChains:<br> ';
    // print_r($this->List_ItemChains);
    // print '<pre>';
    // print 'Entities_Var_Caminho:<br> ';
    // print_r($this->Entities_Var_Caminho);
    // exit;

    return $this->List_ItemChains;
}



function Generate2LargeItemChains($List_ItemChains, $ExtLink){

$total = count($List_ItemChains);
// print '<pre>';
// echo 'Total: '.$total;
// print_r($List_ItemChains);
// exit;

  for ($i=1; $i < $total; $i++)
  {
    for ($j=($i+1); $j <= $total; $j++)
    {
        $IC1['LOE'] = explode($this->delimiter, $List_ItemChains[$i]['LOE']);
        $IC1['ChainID'] = $List_ItemChains[$i]['ChainID'];

        $IC2['LOE'] = explode($this->delimiter, $List_ItemChains[$j]['LOE']);
        $IC2['ChainID'] = $List_ItemChains[$j]['ChainID'];


        // Mostar a comparação 1-2, 1-3, 1-4, 1-5, .... 2-3, 2-4, 2-5 ...
        // print '<br><br><br>'.$i.' - '.$j.'<br>';
        //print '<pre>';
        //print_r($IC1[LOE]);
        //print_r($IC2[LOE]);
        //print_r(array_intersect($IC1[LOE], $IC2[LOE]));

        // print_r(array_intersect($IC1[LOE], $IC2[LOE]));
        //exit;
        $LOE = count(array_intersect($IC1['LOE'], $IC2['LOE']));


       // if($IC1[ChainID].$this->delimiter. $IC2[ChainID] == '1,2,10'){
        // print_r(array_intersect($IC1[LOE], $IC2[LOE]));
        // print '<br>LOC: '.$IC1[ChainID].$this->delimiter. $IC2[ChainID];
        // print '<br>LOE: '.$LOE;
        // exit;
        //}

        $support = $this->CalcularSuporte($LOE);//$LOE / $this->TotalGrafo;//19 = número de total de nó no grafo

      // print '<pre>';
      // print '<br><b>support: </b>';
        // print $support.', ';


        //$support = number_format($support,2); //Formata nº 0.12

        if($support >= $this->minsup)
        {
          // print implode($this->delimiter, $IC1[LOE]).' --- '.implode($this->delimiter, $IC2[LOE]).' -> '.$LOE.' -> '.$support.'<br>';
            $id++;
            $LOE_var = implode($this->delimiter, array_intersect($IC1['LOE'], $IC2['LOE']));//une array separando por vírgula

            $LargeItemChain[$id]['LOC'] =   $IC1['ChainID'].$this->delimiter. $IC2['ChainID']; // Uniao de $a e $b   //List of ChainIDs (LOC)
            $LargeItemChain[$id]['LOE'] = $LOE/*.' {'.$LOE_var.'}'*/; //$LOE QTD de Entidades
            $LargeItemChain[$id]['LOE_var'] = $LOE_var;
            $LargeItemChain[$id]['Support'] = $support;

          if($ExtLink)
              $Entities_DtLocal[$id] = $LOE_var;

            // $Entities_DtLocal[$id] = implode($this->delimiter, array_intersect($IC1['LOE'], $IC2['LOE']));

        }

    }
  }
  // print('aqui<br><br><br>');
  // print '<pre>';
  // print '$Entities_DtLocal: <br><br>';
  // print_r($Entities_DtLocal);
  // print '$LargeItemChain: <br><br><br><br>';
  // print_r($LargeItemChain);
  // exit();

  //juntar array e remover duplicidade
  if($ExtLink)
      $this->Entities_DtLocal = array_unique(explode($this->delimiter, implode($this->delimiter, $Entities_DtLocal)));

  // print '$this->Entities_DtLocal: <br><br>';
  // print_r($this->Entities_DtLocal);
  // exit;

  // print '$IC1: <br><br>';
  // print_r($IC1);
  // print '$IC2: <br><br>';
  // print_r($IC2);
  // exit;



  //$this->SalvarJson($Entities_DtLocal,$this->nomegrafo,'EntidadesExternas');
   // print '<pre>';
   // print_r($Entities_DtLocal);
   // print_r($LargeItemChain);
   // exit;

    return $LargeItemChain;
}




function GenerateRules($List_LargeItemChains){

// print '<pre>';
// print_r($List_LargeItemChains);
// exit;

    foreach ($List_LargeItemChains as $key => $LIC)
    {
      // print '<pre>';
      // print_r($LIC);
      // print_r($this->List_ItemChains);
      // print '</pre>';
      // exit;
        $LICs = explode($this->delimiter, $LIC['LOC']);

        foreach ($LICs as $key => $ChainID)
        {
            $Consequent = $ChainID;
            //   print '<br>';
            // print_r($Consequent);
            // exit;
            // unset($LICs[$i]); // - Consequent //remove o consequente do array para ficar só o antecedente
            //$Antecedent = array_diff($LICs,  [$ChainID]); // - Consequent (Remove o consequente)
            $Antecedent = array_diff($LICs,  array($ChainID)); // - Consequent (Remove o consequente)

            //Inicio - pegar todos os endpoints do Antecedente (principal e Intermediario)
              // foreach ($Antecedent as $ant) {
              //   // print   '$ant :'.$ant;
              //       $EndPoints_Ant[$ant] = $this->List_ItemChains[$ant]['endpoint_Comp'];
              // }
              //      $EndPoints_Ant = implode($this->delimiter, $EndPoints_Ant);
              //      $EndPoints_Ant = explode($this->delimiter, $EndPoints_Ant);
              //      $EndPoints_Ant = array_unique($EndPoints_Ant);
              //      $EndPoints_Cons = $this->List_ItemChains[$Consequent]['endpoint_Comp'];
              // print_r($EndPoints_Ant);
              // exit;

              // print '<pre>';
              // print '<br>$EndPoints_Ant: ';
              // print_r($EndPoints_Ant);

              // print '<br>$EndPoints_Cons: ';
              // print_r($EndPoints_Cons);
              // print '</pre>';
            //FIM - pegar todos os endpoints do Antecedente (principal e Intermediario)

            $Antecedent = implode($this->delimiter, $Antecedent);
            if (strpos($Antecedent, $this->delimiter))
            {
              //pegar valor de suporte do antecedente, quando for mais de um itemchains
              foreach ($List_LargeItemChains as $List) {
                    if($List['LOC'] ==  $Antecedent){
                         $Sup_Ant = $List['Support'];
                         // // $item_calc_sup;
                         // print '<pre>';
                         // print_r($List);
                         // print '</pre>';
                         // echo 'Sup_Ant: '.$Sup_Ant;
                         // exit;
                         break;
                    }

                }
            }
            else
            {
               $Sup_Ant = $this->List_ItemChains[$Antecedent]['Support'];
            }

              $Sup_Cons = $this->List_ItemChains[$Consequent]['Support'];

              // echo '<br>Sup (A u B): '.$LIC[Support];
              // echo '<br>Sup A: '.$Sup_Ant;
              // echo '<br>Sup B: '.$Sup_Cons;
              // echo '<br>Lift: '.$LIC[Support]/($Sup_Ant * $Sup_Cons);
              // exit;
          //////////////////////////////// ==  LITF  == /////////////////////////////////
            $Lift = ($LIC['Support'] / ($Sup_Ant * $Sup_Cons)) / 10;
            $Lift = number_format($Lift,2);
          ///////////////////////////// ==  FIM LITF  == ////////////////////////////////

          ///////////////////////////// ==  Confidence  == ////////////////////////////////
            $Confidence = $LIC['Support'] / $Sup_Ant;
            $Confidence = number_format($Confidence,2); //Formata nº 0.12
          ///////////////////////////// ==  FIM Confidence  == ////////////////////////////////

          //////////////////////////////// ==  Conviction  == /////////////////////////////////
            $x = 1 - $Sup_Cons; // 1- Sup(B)
            $y = 1 - $Confidence; // 1 - Conf(A -> B)
            if($y == '0'){
              $y = 0.001;
            }
            $Conviction = ( $x / $y) / 10;
            $Conviction = number_format($Conviction,2);
          ///////////////////////////// ==  FIM Conviction  == ////////////////////////////////

            if(empty($Conviction_min) && empty($Conviction_max)){
              $Conviction_min = $Conviction;
              $Conviction_max = $Conviction;
            }

            if($Conviction < $Conviction_min){
                $Conviction_min = $Conviction;
            }

            if($Conviction > $Conviction_max){
                $Conviction_max = $Conviction;
            }

            $this->Conviction_min = $Conviction_min;
            $this->Conviction_max = $Conviction_max;


            if($Confidence >= $this->minconf){

                $j++;
                $Rules[$j]['Antecedent'] = $Antecedent;
                $Rules[$j]['Consequent'] = $Consequent;
                $Rules[$j]['Confidence'] = $Confidence;
                $Rules[$j]['Support'] = $LIC['Support'];
                $Rules[$j]['LOE_var'] = $LIC['LOE_var'];
                $Rules[$j]['Lift'] = $Lift;
                $Rules[$j]['Conviction'] = $Conviction;
               // $Rules[$j]['Metrics'] = $this->CalcTotalMetrics($LIC['Support'],$Confidence,$Lift,$Conviction);
                // $Rules[$j]['EndPoints_Ant'] = implode($this->delimiter,$EndPoints_Ant);
                // $Rules[$j]['EndPoints_Cons'] = $EndPoints_Cons;
            }
        }
    }
   // exit;

    // print '<br>'.$this->Conviction_min.' - '.$this->Conviction_max;

    // $Rules_new = $Rules;
    $Rules_new = $this->ConvertConviction($Rules, $Conviction_min, $Conviction_max);

    // print '<pre>';
    // print_r($Rules_new);
    // print '</pre>';
    // exit;

    $this->Rules = $Rules_new;
    return $Rules_new;
}



function ConvertConviction($Rules, $Conviction_min, $Conviction_max){

    foreach ($Rules as $key => $rules) {
        $Conviction = ($rules['Conviction'] - $Conviction_min) / ($Conviction_max - $Conviction_min);
        $Conviction = number_format($Conviction,3);
        // print '<pre>';
        // print_r($Rules[$key]);
        // print '</pre>';
        $Rules[$key]['Conviction'] = $Conviction;
        //$Rules[$key]['Metrics'] = $this->CalcTotalMetrics($rules['Support'],$rules['Confidence'],$rules['Lift'],$Conviction);
        // echo $rules['Conviction'].' - '.$Conviction_min.' - '.$Conviction_max.' = '.$Conviction.'<br>';
        // echo $Rules[$key]['Conviction'];
        // print '<pre>';
        // print_r($Rules);
        // print '</pre>';
        // exit;

    }
    return $Rules;

}


function PegadadosJson($arquivo,$key,$bestsup){
	// print '<br>$arquivo: '.$arquivo;
	// print '<br>$key: '.$key;
	// print '<br>$bestsup: '.$bestsup;
	// exit;
	// print $arquivo;
	$info = file_get_contents($arquivo);
	$DS_total = json_decode($info, true);

	// print '<br> Total array Json:'.count($DS_total[$key]);
	// exit;
	if(empty($DS_total))
	{
		switch (json_last_error())
		{
			case JSON_ERROR_NONE:
				$this->json_msg = 'Validação Json: Sem erros.';
			break;
			case JSON_ERROR_DEPTH:
				$this->json_msg = 'Validação Json: Profundidade máxima da pilha excedida.';
			break;
			case JSON_ERROR_STATE_MISMATCH:
				$this->json_msg = 'Validação Json: Sub-fluxo ou a incompatibilidade de modos.';
			break;
			case JSON_ERROR_CTRL_CHAR:
				$this->json_msg = 'Validação Json: Carácter de controle inesperado encontrado.';
			break;
			case JSON_ERROR_SYNTAX:
				$this->json_msg = 'Validação Json: Erro de sintaxe, JSON malformado.';
			break;
			case JSON_ERROR_UTF8:
				$this->json_msg = 'Validação Json: Caracteres UTF-8 malformados, possivelmente codificados incorretamente.';
			break;
			default:
				$this->json_msg = 'Validação Json:  Erro desconhecido.';
			break;
		}

		echo PHP_EOL;
	}


   // print'<pre>';
   // print 'total array:'.count($DS_total[$key]);
   ////print_r($DS_total);
   // print'</pre>';
   // exit;

  if (!empty($key))//se vier alguma chave busca por ela.
  {
    $DS = $DS_total[$key];
  }

  //calcular o melhor suporte
  if (!empty($bestsup))
  {
    $this->CalcSugestaoSuporte($DS_total['original_header']);
  }

  return $DS;

}

function PegaDadosExternos($Entitiesfinal,$DS,$nomegrafo,$endpoint_ext)
{
  // print  '<pre>';
  // print 'PegaDadosExternos';
    // print_r($Entitiesfinal);
    // print_r($nomegrafo);
    // print_r($DS);
    // print($endpoint_ext);
  // print  '</pre>';
  // exit;

   // Setup some additional prefixes for DBpedia
    EasyRdf_Namespace::set('category', 'http://dbpedia.org/resource/Category:');
    EasyRdf_Namespace::set('dbpedia', 'http://dbpedia.org/resource/');
    EasyRdf_Namespace::set('dbo', 'http://dbpedia.org/ontology/');
    EasyRdf_Namespace::set('dbp', 'http://dbpedia.org/property/');

    $sparql = new EasyRdf_Sparql_Client($endpoint_ext);


  foreach ($Entitiesfinal as $key => $entities)
  {
	$entities = $this->TratarDados($entities);
	
    $result  = $sparql->query(
        'SELECT distinct ?p ?o
            WHERE
            { '.$entities.' ?p ?o;
                  <http://dbpedia.org/ontology/team> ?o
            }
        ');
    // print"<pre>";
    // print_r($result);
    //exit;
      foreach ($result as $key2 => $row)
      {
        if($row->p <> 'http://dbpedia.org/ontology/wikiPageWikiLink') //Toda consulta retorna com a página de origem, por isso estou removendo este item
        {
          $this->i++;
            // print"<pre>";
            // print_r($row);
            // print"</pre>";
          //   exit;
            // print"<hr>";
            // print '<br>P-> '.$row->p;
            // print '<br>O-> '.$row->o;
            // exit;

          //Aqui os dados externos são associados aos recusos externos do sameAs.
          //$DS_Ext[$this->i]['Subject'] = $entities;
		  //Atualizado em 21/11/2019 para mater o entendimento que os dados vindos do dataset externo precisam ser associados direto aos recursos locais.		  
          //Aqui os dados externos são associados aos recusos locais.
          $DS_Ext[$this->i]['Subject'] = $key; //key é a chave do array que está com o recurso local.
          $DS_Ext[$this->i]['Predicado'] =  (string) $row->p;
          $DS_Ext[$this->i]['Object'] = (string) $row->o;
        }
      }

  }

	$NewDT = array_merge($DS, $DS_Ext);

    // print "<pre>";
    // print_r($NewDT);
    // print "</pre>";
    // exit;
	
	$this->totalNewDt = $this->CalcularTamanhoGrafo(NULL,$NewDT);

  $this->SalvarJson($NewDT,$nomegrafo,'dados',null);

    //print $totalNewDt;
    //exit;
	
	return  '<b>'.$this->totalNewDt.'</b> nodes and <b>'.count($NewDT).'</b> edges!';
}

function TratarDados($string)
{
	//tratamento para evitar erros.
	$caracteres = array("(", ")", ",");
	$correcao   = array("\(", "\)", "\,");

	$novastring = str_replace($caracteres, $correcao, $string);
	
	return $novastring;
}

function PegaLinksExternos($entitiesLocais, $externalPredicates, $DS) //pegar os links externos dos recursos associados as regras.
{
      foreach ($externalPredicates as $predicate) //Para cada predicados definido como externo (arquivo: links_ex.json)
      {
          foreach ($DS as $key => $entiti)  //para todos os dados do grafo original
          {
              if($predicate == $entiti['Predicado']) // quando o predicado for igual a um dos predicados definidos como externos. ex. sameAs
              {
                //print $predicate.' - '.$entiti['Predicado'];
              //  exit;
                  if (in_array($entiti['Subject'], $entitiesLocais)){
                    $EntitiesExt[$entiti['Subject']] = $entiti['Object'];
                  }
                  //else {
                    // print $entiti['Subject'].'- nao encontrada <br>';
                  //}
              }
          }
      }
      // print"<pre>";
	  // print '$entitiesLocais:';
      // print_r($entitiesLocais);
      //  print_r($externalPredicates);
      // print '$EntitiesExt:';
      // print_r($EntitiesExt);
      // print_r($DS);
      // print"</pre>";
      // exit;

    return $EntitiesExt;
}

function SalvarJson($Rules,$nome,$pasta,$original_header_old)
{
    // print  '<pre>';
    // print_r($Rules);
    // print  '</pre>';
    // exit;
  // Mantem as configurações que as regras foram gerados

      $Rules_new['original_header'] = array('minsup' => $this->minsup, 'minconf' => $this->minconf, 'maxlevel' => $this->maxlevel, 'minlevel' => $this->minlevel, 'TotalGrafo_old' => $this->TotalGrafo, 'TotalGrafo_new' => $this->totalNewDt );

       $Rules_new['dados'] = $Rules;

    // print  '<pre>';
    // print_r($Rules_new);
    // print  '</pre>';
    // exit;

  $Nome = explode('/', $nome);
  if(is_array($Nome))
  {
    $nome = $Nome['1'];
  }
// echo $pasta.'/'.date("YmdHis_").$Nome[1];

$salvarem = $pasta.'/'.date("YmdHis_").$nome;
$fp = fopen($salvarem, 'w');
fwrite($fp, json_encode($Rules_new));

  fclose($fp);

  return true;
}


function MontaTabela($Rules){

// if($_REQUEST[Entities_Var] == 'on'){
//     $htmlTable .=     '<th> Entities_Var </th>';
//   }
$htmlTable .= '
        <table id="example2" class="table table-bordered table-hover sortable">

                <thead>
                <tr>
                  <th> Row </th>
                  <th> Ant. </th>
                  <th> Cons. </th>
                  <th> Sup. </th>
                  <th> Conf. </th>
                  <th> Lift </th>
                  <th> Conv. </th>' ;
                  //<th> Metrics </th> ';
  if($_REQUEST['Entities_Var'] == 'on'){
    // $htmlTable .= '<th> EndPoints_Ant </th>';
    // $htmlTable .= '<th> EndPoints_Cons </th>';
    $htmlTable .= '<th> Entities_Var </th>';
  }
  $htmlTable .= ' </tr>
                </thead>
                <tbody>  ';

$row = 1;
    foreach ($Rules as $key => $itemRules) {

       $htmlTable .= "
            <tr>
                <td> <b>".$row++."</b> </td>
                <td>".$itemRules['Antecedent']." </td>
                <td> ".$itemRules['Consequent']." </td>
                <td> ".$itemRules['Support']." </td>
                <td> ".$itemRules['Confidence']." </td>
                <td> ".$itemRules['Lift']." </td>
                <td> ".$itemRules['Conviction']." </td> ";
                //<td> ".$itemRules['Metrics']." </td> ";
if($_REQUEST['Entities_Var'] == 'on'){
  // $htmlTable .= " <td> ".$itemRules['EndPoints_Ant']." </td>";
  // $htmlTable .= " <td> ".$itemRules['EndPoints_Cons']." </td>";
  $htmlTable .= " <td> ".$itemRules['LOE_var']." </td>";
}
    $htmlTable .= " </tr> ";
        // print_r($itemRules);
        // exit;

    }
     $htmlTable .= '
                    </tbody>
                <tfoot>
                <tr>
                  <th> Row </th>
                  <th> Ant. </th>
                  <th> Cons. </th>
                  <th> Sup. </th>
                  <th> Conf. </th>
                  <th> Lift </th>
                  <th> Conv.   </th>';
                  //<th> Metrics </th>';
  if($_REQUEST['Entities_Var'] == 'on'){
    // $htmlTable .= ' <th> EndPoints_Ant </th> ';
    // $htmlTable .= ' <th> EndPoints_Cons </th> ';
    $htmlTable .= ' <th> Entities_Var </th> ';
    }
    $htmlTable .= ' </tr>
                </tfoot>
              </table>';

    return $htmlTable;

}
/*
function CalcTotalMetrics($minsup,$minconf,$Lift,$Conviction){

  $MetricSup =  $this->MetricMinSup * $minsup;
  $MetricConf =  $this->MetricMinConf * $minconf;
  $MetricLift =  $this->MetricLift * $Lift;
  $MetricConviction =  $this->MetricConviction * $Conviction;


  $TotalMetrics = ($MetricSup + $MetricConf + $MetricLift + $MetricConviction) / 4;
  $TotalMetrics = number_format($TotalMetrics,3);

  // print '<br>'.$MetricSup.' - '.$MetricConf.' - '.$MetricLift.' - '.$MetricConviction.' = '.$TotalMetrics;
  // exit;

  return $TotalMetrics;
}
*/


function FormataRules($Rules,$List_ItemChains){

    // print '<pre>';
    // print_r($Rules);
    // print_r($List_ItemChains);
    // exit;

    foreach ($Rules as $keys => $regras)
    {
        $AntRules = explode($this->delimiter, $regras['Antecedent']);
        $ConsRules = explode($this->delimiter, $regras['Consequent']);

        // print_r($AntRules);
        // print_r($ConsRules);
        // exit;
        //$g = count($AntRules);
        //if($g > 1){
            //print_r($AntRules);
            //exit;
       // }
            $j++;
            // $t =0;
            foreach ($AntRules as $key => $Ant)
            {


                $item = $List_ItemChains[$Ant];
                // $EndpointEntity = $item[EndpointEntity]; //aqui
                // exit;
                // print '<pre>';
                // print_r($item);
                // exit;
                $rel = explode($this->delimiter, $item['LOR']);
                $count = count($rel);

                $result = array_reverse($rel);

                $result2 = implode(' (', $result);
                $i = 0;
                $fim = null;
               while ( $i < $count) {
                    $fim .= ')';
                    $i++;
                   // echo '<br>aqui: '.$i.'-'.$count;
                }

                $result3[$j]['Ant'][$Ant] = $result2.' ('.$item['EndpointEntity'].$fim;


                // if($j ==31){
                //     print_r($Rules[$j]);
                //     print_r($Ant);
                //     print_r($result3);
                //     //exit;
                // }

            }
           // print "<br><br><br><br>";


            // $t =0;
            foreach ($ConsRules as $key => $Cons)
            {
                $itemCons = $List_ItemChains[$Cons];

                // $EndpointEntity = $itemCons[EndpointEntity]; //aqui
                // print '<pre>';
                // print_r($itemCons);
                // exit;
                $relCons = explode($this->delimiter, $itemCons['LOR']);
                $countCons = count($relCons);
                //print($countCons);

                $resultCons = array_reverse($relCons);

                $result2Cons = implode(' (', $resultCons);

                //print $result2Cons;
                //exit;
                $ii = 0;
                $fimcons = null;
               while ( $ii < $countCons) {
                    $fimcons .= ')';
                    $ii++;
                   // echo '<br>aqui: '.$i.'-'.$count;
                }


                // $t++;
                $result3[$j]['Cons'][$Cons] = $result2Cons.' ('.$itemCons['EndpointEntity'].$fimcons;


            }
             $result3[$j]['LOE_var'] = $regras['LOE_var'];
             $result3[$j]['Conf'] = $regras['Confidence'];
             $result3[$j]['Sup'] = $regras['Support'];
             $result3[$j]['Lift'] = $regras['Lift'];
             $result3[$j]['Conviction'] = $regras['Conviction'];
            // $result3[$j]['Metrics'] = $regras['Metrics'];

            //if($g > 1){
            //    print_r($result3);
            //    exit;
            // }
             // print '<pre>';
             // print_r($result3);
             // print '</pre>';
             // exit;


    }

//List_ItemChains
  // print '<pre>';
  // print 'aqui';
  // print_r($result3);
  // print '</pre>';
  // exit;

  return $result3;


}



function MontaTabelaRules($FormatacaoRules){

    // print '<pre>';
    // print_r($FormatacaoRules);
    // exit;
$htmlTableRules .= '
        <table id="tabela" class="table table-bordered table-hover sortable" >

                <thead>

                <tr>
                    <th> Row </th>
                    <th><input class="form-control" placeholder="Antecedent" type="text" id="txtColuna1"/></th>
                    <th><input class="form-control" placeholder="Consequent" type="text" id="txtColuna2"/></th>
                  <th> Sup. </th>
                  <th> Conf </th>
                  <th> Lift </th>
                  <th> Conv. </th></tr>
                </thead>
                <tbody>';

$row = 1;
    foreach ($FormatacaoRules as $key => $itemRules) {

       $htmlTableRules .= "
            <tr>
                <td> <b>".$row++."</b> </td>
                <td> <h4>".implode('<b>,</b> ',$itemRules['Ant'])." &#10132; </h4> </td>
                <td> <h4>".implode('<b>,</b> ',$itemRules['Cons'])."</h4></td>
                <td> <h4>".$itemRules['Sup']." </h4></td>
                <td> <h4>".$itemRules['Conf']." </h4></td>
                <td> <h4>".$itemRules['Lift']." </h4></td>
                <td> <h4>".$itemRules['Conviction']." </h4></td>
           </tr>
                ";
        // print_r($itemRules);
        // exit;

    }
     $htmlTableRules .= '
                    </tbody>
                <tfoot>
                <tr>
                  <th> Row </th>
                  <th> Antecedent </th>
                  <th> Consequent </th>
                  <th> Sup  </th>
                  <th> Conf </th>
                  <th> Lift  </th>
                  <th> Conv. </th>
                </tr>
                </tfoot>
              </table>';

  return $htmlTableRules;

}


}

?>
