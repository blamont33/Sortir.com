# Sortir.com
Plateforme web destinée aux stagiaires en formation permettant l'organisation de sorties sur le temps hors formation

## Développement </br>
Back-End :</br>
PHP/Symfony/MySQL

Front-End :</br>
Html/Css/Javascript/Bootstrap

## Fonctionnalités:</br>
- En tant qu'utilisateur, je peux me connecter sur la plateforme sortir.com avec un login (adresse mail ou pseudo) et un mot de passe
- En tant qu'utilisateur, je peux choisir d'enregistrer mon login sur mon ordinateur pour ne pas avoir à le resaisir.
- En tant qu’utilisateur, je peux gérer mes informations de profil, notamment mon nom, prénom, pseudo, email, mot de passe, et téléphone. Le pseudo doit être unique entre tous les participants.
- En tant que participant, je peux lister les sorties publiées sur chaque campus, celles auxquelles je suis inscrit et celles dont je suis l’organisateur. Je peux filtrer cette liste suivant différents critères
- En tant qu'organisateur d'une sortie, je peux créer une nouvelle sortie ( définir un nom pour la sortie, une date et heure, une durée, un lieu (nom, adresse, gps), un nombre limite de participants, une note textuelle, et une date limite d'inscription )
- En tant que participant, je peux m’inscrire à une sortie. Il faut que la sortie ai été publiée (ouverte), et que la date limite d’inscription ne soit pas dépassée.
- En tant que participant inscrit à une sortie, je peux me désister tant que la sortie n'a pas débuté. En cas de désistement, la place devient libre pour un autre participant si la date limite d'inscription n'est pas dépassée.
- Les participants ne peuvent pas s’inscrire à une sortie après la date de clôture des inscriptions.
- En tant qu'organisateur d'une sortie, je peux annuler une sortie si celle-ci n'est pas encore commencée. La sortie sera alors marquée comme annulée et sera accompagnée d'un motif d'annulation.
- Les sorties réalisées depuis plus d’un mois ne sont pas consultables.
- En tant que participant je peux afficher le profil des autres participants. Cette fonctionnalité est notamment disponible sur la page d’affichage des sorties et sur la page qui affiche les détails de la sortie.
- En tant que participant je peux uploader une photo pour être affichée dans ma page Profil.
- En tant que participant, je peux utiliser un petit écran de type smartphone pour afficher les sorties de mon campus de rattachement. Dans la version petit écran, la plateforme ne permet pas de créer des sorties ou des groupes.
- En tant que participant, je peux utiliser la plateforme avec un  écran de taille moyenne de type tablette. Les fonctionnalités sont les mêmes que l’utilisation sur grand écran de type ordinateur de bureau.
- En qu'administrateur, je peux inscrire plusieurs utilisateurs via l'intégration d'un fichier .csv
- En tant qu'administrateur, je peux créer un nouvel utilisateur par un écran d'administration avec saisie manuelle des informations
- En tant qu'utilisateur, je peux faire une demande de ré-initialisation de mot de passe. La plateforme créé un lien vers un écran de saisie du nouveau mot de passe.
- En tant que participant je peux ajouter des lieux dans la plateforme.
- En tant qu'administrateur, je peux rendre inactifs des utilisateurs sélectionné dans une liste d'utilisateurs.
- En tant qu'administrateur, je peux supprimer des utilisateurs sélectionné dans une liste d'utilisateurs.
- En tant qu'administrateur, je peux annuler une sortie qui a été proposée par un autre participant.
- En tant que participant je peux ajouter des villes utilisables dans la plateforme (une ville est toujours associée à un code postal).

## Notes:</br>
Projet réalisé seul dans le cadre d'une formation en développement Web et Web mobile, sur une durée de deux semaines.
