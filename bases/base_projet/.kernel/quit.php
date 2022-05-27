<?php
use Kernel as k;



// Affiche le superviseur
k\Debug\Supervisor::show();

// Charge Less
k\Html\Less::init();

// Affiche le message de prevention
k\Security\Vulnerability\Self_XSS::prevent();

// Ferme le flux de donnees
k\IO\Stream::close();

?>