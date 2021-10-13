<?php

// ####################################################################################################
// Creation du formulaire
${NAME} = new Formulaire('post', 'index.php', 'f{NAME}', 'f{NAME}');
${NAME}->ajouterComposantLigne(${NAME}->creerParagh('Le fomulaire "{NAME}" fonctionne !'));
${NAME}->ajouterComposantTab();
${NAME}->creerFormulaire();
// ####################################################################################################





// ####################################################################################################
// Dispatch vers la bonne vue
require_once dispatcher::vue();
// ####################################################################################################