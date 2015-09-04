<?php

$continents = [];
$names = [];
$f = file('names_new.txt');
foreach($f as $g)
{
	$t = explode("\t", $g);
	if( isset( $t[1] ) )
	{
		if( $t[0] != '' )
		{
			$cont = trim($t[0]);
			$continent[ $cont ] = trim($t[1]);
		}
		else
		{
			$names[ trim($cont . $t[1]) ] = trim($t[2]);
		}
	}
}
//print_r($names);

function loadAdjacencies($fn, &$adjacencies)
{
	$f = file($fn);
	foreach($f as $g)
	{
		$g = str_replace("\n",'',$g);
		if( $g == "#end" ) break;

		$t = explode(" ", $g);
		$o = array_shift($t);
		if( ! isset( $adjacencies[$o] ) )
			$adjacencies[ $o ] = [];
		$adjacencies[ $o ] = array_merge( $adjacencies[$o], $t );
	}
	return $adjacencies;
}
$adjacencies = [];
loadAdjacencies('adj_new_2.txt', $adjacencies);
loadAdjacencies('adj_new.txt', $adjacencies);
//print_r($adjacencies); die();


$nodes = ['n'=>'', 's'=>'', 'e'=>'', 'f'=>'', 'a'=>'','u'=>''];
foreach($names as $n => $name)
{
	$c = $n[0];
	$name = str_replace(" ","\\n", $name);
	$nodes[$c] .= "$n [style=filled,color=white,label=\"$name\"];\n";
}


//$edges = ['n'=>'', 's'=>'', 'e'=>'', 'f'=>'', 'a'=>'','u'=>''];
$edges = '';
foreach( $adjacencies as $a => $b )
{
	foreach( $b as $c )
	{
		$color = "blue";
		if( $a[0] != $c[0] )
			$color = "red";
		$edges //[$a[0]]
			.= "$a -- $c [color=$color];\n";
	}
}
//print_r($edges);

?>

strict graph G {
	node [fontname=Helvetica];
	graph [fontname=Helvetica];
	layout=dot;
	overlap=false;
	rankdir=LR;
	ranksep=0.25;
	//subgraph clusterNS {
		color=white;
		subgraph clusterN {
			label="North America";
			rank=minrank;
			color=lightgrey;
			style=filled;
			<?php echo $nodes['n']; ?>
		}
		subgraph clusterS {
			label="South America";
			color=lightgrey;
			style=filled;
			<?php echo $nodes['s']; ?>
		}
	//}
	subgraph clusterEA {
		color=white;
		subgraph clusterE {
			label="Europe";
			rank=minrank;
			color=lightgrey;
			style=filled;
			<?php echo $nodes['e']; ?>
		}
		subgraph clusterA {
			label="Asia";
			color=lightgrey;
			style=filled;
			<?php echo $nodes['a']; ?>
		}
	}
		subgraph clusterF {
			label="Africa";
			color=lightgrey;
			style=filled;
			<?php echo $nodes['f']; ?>
		}
	subgraph clusterAU {
		subgraph clusterU {
			label="Australia";
			rank=maxrank;
			color=lightgrey;
			style=filled;
			<?php echo $nodes['u']; ?>
		}
	}
	<?php echo $edges ?>
	p4 [style=invisible];
	n1 -- p4 -- n6 [style=invisible];

	p2 [style=invisible];
	s1 -- p2 -- f6 [style=invisible];
	p1 [style=invisible];
	a5 -- p1 [style=invisible];
}
