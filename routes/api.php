<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\RetardController;
use App\Http\Controllers\AbsentController;
use App\Http\Controllers\MelangeController;
use App\Http\Controllers\ActiviteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware(['throttle:100,1'])->group(function () {
    //Route pour personnel
    Route::post('ajout_personnel', [PersonnelController::class, 'ajout_personnel']);// ajout personnel
    Route::post('modifie_personnel', [PersonnelController::class, 'modifie_personnel']);// modifier personnel
    Route::get('suprimer_personnel/{id}', [PersonnelController::class, 'supprimer_personnel']);// suprimer personnel
    Route::get('select_personnel_simplement', [PersonnelController::class, 'select_personnel_simplement']);// select personnel simplement
    Route::get('select_personnel_pas_planning', [PersonnelController::class, 'select_personnel_pas_planning']);// select personnel simplement
    Route::get('select_sanction', [PersonnelController::class, 'select_sanction']);// select personnel simplement
    Route::get('statistique_personnel', [PersonnelController::class, 'statistique_personnel']);


    //Route pour Planning
    Route::post('ajout_plannign', [PlanningController::class, 'ajout_plannign']);// ajout  planning
    Route::post('modifie_planning', [PlanningController::class, 'modifie_planning']);// modifier  planning
    Route::get('suprimer_planning/{id}', [PlanningController::class, 'suprimer_planning']);// select planning simplement
    Route::get('select_planning_simplement', [PlanningController::class, 'select_planning_simplement']);// select planning simplement


    //Route pour les Absents
    Route::post('pointer_absent', [AbsentController::class, 'pointer_absent']);// pointer absent
    Route::post('modifier_pointage_absent', [AbsentController::class, 'modifier_pointage_absent']);// modifier absence
    Route::get('suprimer_absent/{id}', [AbsentController::class, 'suprimer_absent']);// supression absent
    Route::get('select_absent_simplement', [AbsentController::class, 'select_absent_simplement']);// select absent simplement



    //Route pour les Absents
    Route::post('pointer_retard', [RetardController::class, 'pointer_retard']);// pointer retard
    Route::post('modifier_pointage_retard', [RetardController::class, 'modifier_pointage_retard']);// modifier retard
    Route::get('suprimer_retard/{id}', [RetardController::class, 'suprimer_retard']);// supression absent
    Route::get('select_retard_simplement', [RetardController::class, 'select_retard_simplement']);// select retard simplement



    //Route pour les activites
    Route::get('select_activite', [ActiviteController::class, 'select_activite']);// select cativite simplement


    //recherche_deux_date
    Route::post('recherche_deux_date', [MelangeController::class, 'recherche_deux_date']);// rechercher deux date
    Route::get('planning_retard_absent_personnel/{id}', [MelangeController::class, 'planning_retard_absent_personnel']);//planning , retard, et absent pour un personnel
    Route::get('reinitialistion', [MelangeController::class, 'reinitialistion']);// select cativite simplement
    Route::get('donne_statistique', [MelangeController::class, 'donne_statistique']);//donne pour les dashboar
    Route::get('petit_statistique', [MelangeController::class, 'petit_statistique']);//total des retards et absents pour le mois actuelle
// });



//statistiquesAbsentsParSexe




