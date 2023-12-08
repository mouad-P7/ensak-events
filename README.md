# ENAK EVENTS

-SiteWeb complet pour la gestion d’événements, offrant des fonctionnalités aux organisateurs pour créer, gérer, et promouvoir leurs événements, ainsi qu'aux participants pour s'informer et s'inscrire.

## FEATURES WE IMPLEMENTED

- Page d'accueil présentant les événements à venir. (index.php)
- Système d'authentification. (login.php / logout.php / register.php)
- Page pour chaque événement avec informations complètes. (event.php)
- Système d'insciption au événement. (registerEvent.php / unregisterEvent.php)
- Panneau d'administration pour CRUD toutes les événements. (createEvent.php / viewEvents.php / editEvent.php / deleteEvent.php / eventDashboard.php)

## INSTALATION

- Create a database called: event_management
- Import the file: sql/ensakEvents.sql
- Start Apache and MySQL.
- If you want to upload your own photos, add them in images folder.
- If you want to use google maps localisation add config.php file in utils folder:

```
<?php
$googleMapsApiKey = "PUT YOUR GOOGLE MAPS API KEY HERE";
if (!$googleMapsApiKey) {
  die('Google Maps API key not set.');
}
?>
```

Now you're ready to go 😀.
