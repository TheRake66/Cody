<?php
use Kernel as k;



// Affiche le superviseur
k\Supervisor::show();

// Charge Less
k\Less::compile();

// Ajoute un separateur
k\Debug::separator();



ob_flush();
?>