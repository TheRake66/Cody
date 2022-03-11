<?php
use Kernel as k;



// Affiche le superviseur
k\Suppervisor::showSuppervisor();

// Charge Less
k\Less::compileLessToCss();

// Ajoute un separateur
k\Debug::separator();



ob_flush();
?>