# Plugin template

Plugin permettant de créer une notification sur Android TV via PiPup.

# Introduction


# Configuration du plugin

La configuration est très simple, après téléchargement du plugin, il vous suffit de renseigner quelques sondes.

Sonde de température
    Température extérieure : Commande pour la température extérieure
    
Sonde de présence 
    Présence : ce champ est optionnel
        Si le champs est non renseigné : le plugin vérifiera toujours l'état des ouvertures
        Si le champs est renseigne : le plugin vérifiera uniquement si la présence est à 1

Température Saison
    Température Saison : section optionnelle
        Le plugin cherche à réguler la température par rapport à la saison
        La saison est calculé par rapport à la date, ou mieux selon la température maxi selon la météo.
        En effet, il peut y avoir des périodes plus chaude en hiver, et la règle doit s'adapter pour profiter de ces moments plus chaud.

        Température maxi : Commande du plugin météo indiquant la température maximum du jour
        Température hiver (°C) : température minimum indiquant que le calcul passe sur le mode Hiver
        Température été (°C) : température minimum pour l'Eté

        S'il n'y a pas de température renseignée, alors le calcul se base sur les dates :
        Hivers : jour compris entre le 21 septembre et le 21 mars (période plus ou moins fraiche)
        Eté : jour entre le 21 juin et le 21 septembre (période plus ou moins chaude)
        Sinon on prend les règle de la saison intermédiaire

# Configuration des équipements

La configuration des équipements Gestion Ouvrants est accessible à partir du menu Plugins puis Confort.

Une fois dessus vous retrouvez alors la liste de vos Ouvrants.

## Général

Vous retrouvez ici toute la configuration de votre équipement :

    Nom de l’équipement : nom de votre pièce.
    Objet parent : indique l’objet parent auquel appartient l’équipement.
    Catégorie : les catégories de l’équipement (il peut appartenir à plusieurs catégories).
    Activer : permet de rendre votre équipement actif.
    Visible : le rend visible sur le dashboard.
    
## Informations

Cet onglet récapitules les informations sur la nécessité d'aérer. Il donne aussi des conseils sur la durée et les horaires.

## Sondes

Listes des sondes pour suivre la santé de la pièce

    Température intérieure : Commande pour la température intérieure
    
    Durée hiver : durée souhaitée d'aération en hiver (5 minutes par exemple)
    Durée été : durée souhaitée d'aération en été (5 minutes par exemple)
    Notifier : permet d'envoyer une notification s'il faut ouvrir ou fermer une fenêtre

    Calcul sur température : ce champ est optionnel
        Le but est de garder la pièce dans une température acceptable. i.e : garder la pièce au alenture de la température de consigne d'un thermostat
        Consigne Thermostat : consigne du thermostat
        Seuil hiver : dépassement acceptable de la température de la pièce par rapport à la consigne
        Seuil été : A VOIR


## Ouvertures

Listes des fenêtres à surveiller.
    
    0 sera consédéré comme fermé,
    1 comme ouvert

Utiliser la cocher "Inverser" si votre module renvoie la valeur inverse.

## Actions

Actions et scénario à déclencher
Lorsque le plugin detectera qu'il serait bien d'ouvrir ou de fermer une fenêtre, alors les actions seront déclenchées.
Il est possible d'utiliser un scénario ou des commandes.
Il est possible d'utiliser des commandes de type PushBullet. Il est alors demandé un Titre et un Message

Actuellement, il existe quelques variables qui peuvent être utilisées :
    
    #name# = Nom de l'objet
    #message# = Message à afficher  = 'il faut ouvrir' ou 'il faut fermer'
    #temperature_indoor# = température intérieure
    #parent# = nom de l'objet parent (la pièce par exemple) 


## Commandes

Commandes créées pour voir des informations :

    Action : affiche 1 si une action est souhaitée, 0 sinon
    Rafraichir : relance le calcul
    Compteur : Temps d'aération dans la journée

