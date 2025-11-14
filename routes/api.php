<?php

// routes/api.php

use App\Http\Controllers\Auth\AuthControllerGlobal; // Votre contrôleur d'authentification global
use App\Http\Controllers\Tenant\AuthControllerTenant; // Votre contrôleur d'authentification tenant
use App\Http\Controllers\Tenant\ProduitController; // Exemple de contrôleur de tenant

// -----------------------------------------------------
// Routes d'authentification Globale (pour pilotpro_main)
// -----------------------------------------------------
Route::post('/auth/global/login', [AuthControllerGlobal::class, 'login']);
Route::post('/auth/global/register', [AuthControllerGlobal::class, 'register']); // Pour l'inscription de nouvelles entreprises

// -------------------------------------------------------------------------
// Routes d'authentification et d'API Spécifiques au Tenant (pour pilotpro_N)
// -------------------------------------------------------------------------
Route::middleware(['tenant.resolver'])->group(function () {
    // Routes d'authentification DANS le tenant (si vous voulez un jeton distinct)
    Route::post('/auth/tenant/login', [AuthControllerTenant::class, 'login']);
    Route::post('/auth/tenant/logout', [AuthControllerTenant::class, 'logout'])->middleware('auth:api_tenant');

    // Routes d'API du Tenant, protégées par le guard du tenant
    Route::middleware('auth:api_tenant')->prefix('tenant')->group(function () {
        Route::apiResource('produits', ProduitController::class);
        // ... ajoutez ici toutes les routes de vos modules (finance, stock, etc.)
    });
});