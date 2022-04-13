<?php
use Kernel as k;



// Affiche le superviseur
k\Supervisor::show();

// Charge Less
k\Less::compile();

// Ferme le flux de donnees
k\Stream::close();

?>