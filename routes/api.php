<?php

// routes/api.php

use App\Http\Controllers\Auth\AuthControllerGlobal; // Votre contrôleur d'authentification global
use App\Http\Controllers\Tenant\AuthControllerTenant; // Votre contrôleur d'authentification tenant
use App\Http\Controllers\Tenant\ProduitController; // Exemple de contrôleur de tenant

// -----------------------------------------------------
// Routes d'authentification Globale (pour pilotpro_main)
// -----------------------------------------------------
// Routes globales (authentification, inscription d'entreprise)
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

// Groupe de routes nécessitant l'authentification ET la résolution du tenant
Route::middleware(['auth:api', 'tenant.resolver'])->group(function () {
    // Routes spécifiques au tenant (accèdent aux données du client)
    Route::get('/tenant/produits', [ProduitController::class, 'index']);
    // ... toutes vos autres routes pour les modules Finance, Stock, RH, CRM, PilotCom, etc.
});