<?php
use Kernel as k;



// Affiche le superviseur
k\Debug\Supervisor::show();

// Charge Less
k\Html\Less::init();

// Affiche le message de prevention
k\Security\Vulnerability\SelfXss::prevent();

// Ferme le flux de donnees
k\Io\Stream::close();

?>