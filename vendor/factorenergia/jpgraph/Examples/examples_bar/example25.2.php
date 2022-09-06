<?php

/**
 * JPGraph v4.0.3
 */

require_once __DIR__ . '/../../src/config.inc.php';
use factorenergia\JpGraph\Graph;
use factorenergia\JpGraph\Plot;
use factorenergia\JpGraph\Text;

$datay = [12, 8, 19, 3, 10, 5];

// Create the graph. These two calls are always required
$__width  = 300;
$__height = 200;
$graph    = new Graph\Graph($__width, $__height);
$graph->SetScale('textlin');

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
$graph->img->SetMargin(40, 30, 20, 40);

// Create a bar pot
$bplot = new Plot\BarPlot($datay);
$graph->Add($bplot);

// Create and add a new text
$txt = new Text\Text("This is a text\nwith many\nand even\nmore\nlines of text");
$txt->SetPos(0.5, 0.5, 'center', 'center');
$txt->SetFont(FF_FONT2, FS_BOLD);
$txt->ParagraphAlign('center');
$txt->SetBox('yellow', 'navy', 'gray');
$txt->SetColor('red');
$graph->AddText($txt);

// Setup the titles
$graph->title->Set('A simple bar graph');
$graph->xaxis->title->Set('X-title');
$graph->yaxis->title->Set('Y-title');

$graph->title->SetFont(FF_FONT1, FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1, FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1, FS_BOLD);

// Display the graph
$graph->Stroke();