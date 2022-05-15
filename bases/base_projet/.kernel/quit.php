<?php
use Kernel as k;



// Affiche le superviseur
k\Debug\Supervisor::show();

// Charge Less
k\Html\Less::importLib();

// Ferme le flux de donnees
k\IO\Stream::close();

?>