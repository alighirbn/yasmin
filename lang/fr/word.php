<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Lignes de Langue
    |--------------------------------------------------------------------------
    |
    | Les lignes de langue suivantes sont utilisées lors de l'affichage de toutes les pages pour divers
    | messages que nous devons afficher à l'utilisateur. Vous êtes libre de modifier
    | ces lignes de langue en fonction des besoins de votre application.
    |
     */
    //auth
    'user_create' => 'Créé par',
    'user_update' => 'Mis à jour par',
    //buttons
    'action' => 'Actions',
    'view' => 'Voir',
    'add' => 'Ajouter',
    'edit' => 'Modifier',
    'delete' => 'Supprimer',
    'update' => 'Mettre à jour',
    'save' => 'Sauvegarder',
    'back' => 'Retour',
    'Log Out' => 'Déconnexion',
    'print' => 'Imprimer',

    // general values
    'year' => 'Année',
    'month' => 'Mois',
    'day' => 'Jour',

    //main navigation
    'dashboard' => 'Tableau de bord',
    'Map' => 'Carte',
    'contract' => 'Contrats',
    'accountant' => 'Comptabilité',
    'Building' => 'Bâtiments',
    'payment' => 'Paiements',
    'expense' => 'Bons de dépenses',
    'service' => 'Services',
    'Customer' => 'Clients',
    'users' => 'Utilisateurs',
    'roles' => 'Rôles',

    // dashboard
    'dashboard_msg' => 'Bienvenue sur le site du Complexe Résidentiel Yasmin Oasis dans la province de Najaf',
    'dashboard_title' => 'Bienvenue',

    // basic info
    'basic_msg' => 'Page pour les informations de base',

    //************************************* map        *******************************************
    'map_contracts' => 'Carte des Contrats',
    'map_due_installments_0' => 'Échéance Maintenant',
    'map_due_installments_30' => 'Échéance dans un Mois',
    'map_due_installments_60' => 'Échéance dans Deux Mois',
    'map_buildings' => 'Carte des Bâtiments',
    'map_empty_buildings' => 'Carte des Bâtiments Disponibles',

    //************************************* contract        *******************************************
    'id' => 'Numéro du Contrat',
    'contract_id' =>  'Numéro du Contrat',
    'contract_date' => 'Date du Contrat',
    'contract_amount' => 'Montant',
    'contract_note' => 'Remarques',

    'contract_customer_id' => 'Nom du Client',
    'contract_building_id' => 'Numéro du Bâtiment',
    'contract_payment_method_id' => 'Type de Paiement',
    'method_name' => 'Type de Paiement',

    'installment_number' => 'Séquence',
    'installment_name' => 'Échéance',
    'installment_percent' => 'Pourcentage',
    'installment_amount' => 'Montant de l\'Échéance',
    'installment_date' => 'Date d\'Échéance',
    'installment_payment' => 'Statut du Paiement',

    //nav
    'contract_add' => 'Ajouter un Contrat',
    'contract_search' => 'Rechercher un Contrat',
    'contract_view' => 'Voir le Contrat',
    'contract_edit' => 'Modifier le Contrat',
    'contract_print' => 'Imprimer le Contrat',
    'contract_transfer' => 'Transférer le Contrat',
    'contract_due' => 'Échéances',
    'statement' => 'État',

    //contract_info
    'contract_info' => 'Informations sur le Contrat',
    'installment_info' => 'Informations sur l\'Échéance',

    //************************************* transfer        *******************************************
    'transfer_id' => 'Numéro de Transfert',
    'transfer_date' => 'Date du Transfert',
    'transfer_amount' => 'Frais de Transfert',
    'transfer_note' => 'Remarques',

    'oldcustomer' => 'Ancien Client',
    'newcustomer' => 'Nouveau Client',
    'new_customer_id' => 'Nom du Nouveau Client',

    //nav
    'transfer_add' => 'Ajouter un Transfert',
    'transfer_search' => 'Rechercher un Transfert',
    'transfer_edit' => 'Modifier un Transfert',
    'transfer_print' => 'Imprimer le Transfert',
    //transfer_info
    'transfer_info' => 'Informations sur le Transfert',
    'transfer_contract' => 'Voir les Transferts',
    'transfer_approve' => 'Approuvé',

    'old_customer_picture' => 'Photo de l\'Ancien Client',
    'new_customer_picture' => 'Photo du Nouveau Client',
    'capture' => 'Capturer',

    //************************************* building        *******************************************
    'building_number' => 'Numéro du Bâtiment',
    'block_number' => 'Numéro du Bloc',
    'house_number' => 'Numéro de Maison',
    'building_area' => 'Surface',
    'building_map_x' => 'Coordonnée X',
    'building_map_y' => 'Coordonnée Y',

    'building_category_id' => 'Catégorie',
    'building_type_id' => 'Type',

    //nav
    'building_add' => 'Ajouter un Bâtiment',
    'building_search' => 'Rechercher un Bâtiment',

    //building_info
    'building_info' => 'Informations sur le Bâtiment',

    //************************************* payment        *******************************************
    'payment_id' => 'Numéro de Paiement',
    'payment_date' => 'Date de Paiement',
    'payment_amount' => 'Montant Reçu',
    'payment_note' => 'Remarques',
    'add_payment' => 'Effectuer un Paiement',

    //nav
    'payment_add' => 'Ajouter un Paiement',
    'payment_search' => 'Rechercher un Paiement',
    'payment_approve' => 'Approuver le Paiement',
    'approved' => 'Approuvé',
    'pending' => 'En Attente',

    //payment_info
    'payment_info' => 'Informations sur le Paiement',
    'last_payment' => 'Dernier Paiement',
    'payment_pending' => 'Paiements en Attente',
    'payment_status' => 'Statut du Paiement',
    'approve_status' => 'Statut d\'Approbation',

    //************************************* expense        *******************************************
    'expense_id' => 'Numéro du Bon de Dépense',
    'expense_date' => 'Date du Bon de Dépense',
    'expense_amount' => 'Montant',
    'expense_note' => 'Remarques',
    'add_expense' => 'Ajouter un Bon de Dépense',
    'expense_type_id' => 'Type de Dépense',

    //nav
    'expense_add' => 'Ajouter un Bon de Dépense',
    'expense_search' => 'Rechercher un Bon de Dépense',
    'expense_approve' => 'Approuver le Bon de Dépense',

    //expense_info
    'expense_info' => 'Informations sur le Bon de Dépense',
    'expense_pending' => 'Bons de Dépense en Attente',
    'expense_status' => 'Statut du Paiement',

    //************************************* cash_account        *******************************************
    'cash_account_id' => 'Numéro de Compte de Trésorerie',
    'balance' => 'Solde',
    'account_name' => 'Nom du Compte',

    //nav
    'cash_account_add' => 'Ajouter un Compte de Trésorerie',
    'cash_account_search' => 'Rechercher un Compte de Trésorerie',

    //cash_account_info
    'cash_account_info' => 'Informations sur le Compte de Trésorerie',

    //************************************* cash_transfer        *******************************************
    'cash_transfer_id' => 'Numéro de Transfert',
    'amount' => 'Montant',

    'transfer_date' => 'Date du Transfert',
    'from_account_id' => 'Compte d\'Origine',
    'to_account_id' => 'Compte de Destination',
    'from_account' => 'Compte d\'Origine',
    'to_account' => 'Compte de Destination',

    'transfer_number' => 'Numéro de Transfert',
    'transfer_note' => 'Remarques',

    //nav
    'cash_transfer_add' => 'Ajouter un Transfert',
    'cash_transfer_search' => 'Rechercher un Transfert',

    //cash_transfer_info
    'cash_transfer_info' => 'Informations sur le Transfert',
    'cash_transfer_pending' => 'Transferts en Attente',
    'cash_transfer_approve' => 'Approuver le Transfert',

    //************************************* service        *******************************************
    'service_id' => 'Numéro de Service',
    'service_date' => 'Date de Service',
    'service_amount' => 'Montant',
    'service_note' => 'Remarques',
    'service_type_id' => 'Type de Service',

    //nav
    'service_add' => 'Ajouter un Service',
    'service_search' => 'Rechercher un Service',

    //service_info
    'service_info' => 'Informations sur le Service',

    //************************************* customer        *******************************************
    'customer_full_name' => 'Nom Complet',
    'customer_phone' => 'Numéro de Téléphone',
    'customer_email' => 'Adresse Email',
    'customer_card_number' => 'Numéro de Carte Nationale',
    'customer_card_issud_auth' => 'Autorité de Délivrance',
    'customer_card_issud_date' => 'Date de Délivrance',

    //nav
    'customer_add' => 'Ajouter un Client',
    'customer_search' => 'Rechercher un Client',

    //customer_info
    'customer_info' => 'Informations sur le Client',
    'customer_card' => 'Informations sur la Carte Nationale',

    //************************************* users        *******************************************
    'user_name' => 'Nom d\'Utilisateur',
    'password' => 'Mot de Passe',
    'confirm_password' => 'Confirmer le Mot de Passe',
    'email' => 'Adresse Email',
    'user_status' => 'Statut',
    'user_role' => 'Rôle',
    'department_id' => 'Département',

    //nav
    'user_add' => 'Ajouter un Utilisateur',
    'user_search' => 'Voir les Utilisateurs',

    //user_info
    'user_info' => 'Informations sur l\'Utilisateur',

    //************************************* role        *******************************************
    //fields
    'role_name' => 'Nom du Rôle',
    'guard' => 'Garde',
    'permission' => 'Permissions',

    //nav
    'role_add' => 'Ajouter un Rôle',
    'role_search' => 'Voir les Rôles',

    //role_info
    'role_info' => 'Informations sur le Rôle',

    //************************************* reports        *******************************************
    'total_amount' => 'Montant Total',
    'received_amount' => 'Montant Reçu',
    'remaining_amount' => 'Montant Restant',
    'contract_report' => 'Rapport sur les Contrats',
    'installment_report' => 'Rapport sur les Échéances',
    'installment_status' => 'Statut des Échéances',

];
