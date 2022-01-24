# Plugin template

Plugin permettant de créer une notification sur Android TV via PiPup.

# Prérequis

Le plugin est basé sur une application AndroidTV : PiPup. (https://github.com/rogro82/PiPup)

En résumé :
- Installer sur la TV l'application PiPup, 
- Sur son PC ou son MAC, il faut récupérer l'outil adb (https://developer.android.com/studio/releases/platform-tools)
- Puis via une ligne de commande, se connecter à sa TV
  adb connect <IPTV>
  Vérification :
  adb devices
- Donner les droits d'afficher une popup sur la TV pour cette application :
  adb shell appops set nl.rogro82.pipup SYSTEM_ALERT_WINDOW allow


# Configuration des équipements

La configuration des équipements PiPup est accessible à partir du menu Plugins puis Communication.

Une fois dessus vous retrouvez alors la liste de vos équipements.

## Général

Vous retrouvez ici toute la configuration de votre équipement :

    Nom de l’équipement : nom de votre équipement AndroidTV.
    Objet parent : indique l’objet parent auquel appartient l’équipement.
    Catégorie : les catégories de l’équipement (il peut appartenir à plusieurs catégories).
    Activer : permet de rendre votre équipement actif.
    Visible : le rend visible sur le dashboard.
    
## Informations

Cet onglet permet de définir l'équipement :

   IP TV : IP de l'équipement où afficher les notifications
   Duration : Durée en seconde pendant laquelle la notification s'affiche

## Commandes

Commandes créées pour voir des informations :

    notify : affiche une notification de type Notify
       Le titre est noir, l'icône est de type Cloche
    
    alert : affiche une notification de type Alert
       Le titre est rouge, l'icône est de type Alerte

