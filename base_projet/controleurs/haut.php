<?php

// ####################################################################################################
// Header
$header = new Formulaire('post', 'index.php', 'fBandeau', 'fBandeau');
$header->image('./images/logo.png');  
$header->label('Bienvenue dans Cody-PHP');
$header->build();
// ####################################################################################################





// ####################################################################################################
// Menu de navigation
$nav = new Formulaire('post', 'index.php', 'fMennuNav', 'fMennuNav');

if (isset($_GET['messageSuccess'])) {
	$nav->label($_GET['messageSuccess'], 'messageSuccess', 'messageSuccess');
} elseif (isset($_GET['messageFail'])) {
	$nav->label($_GET['messageFail'], 'messageFail', 'messageFail');
}

$nav->build();
// ####################################################################################################