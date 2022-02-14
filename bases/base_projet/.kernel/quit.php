<?php
use Kernel as k;



// Affiche le superviseur
k\Suppervisor::showSuppervisor();

// Charge Less
k\Less::compileLessToCss();



ob_flush();
?>