<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retard;
use App\Models\Personnel;
use App\Models\Activite;
use App\Models\Planning;
use Carbon\Carbon;

class RetardController extends Controller
{

     //pointage retard
    public function pointer_retard(Request $request){
        $message = "";
        $nb_heure_retard = "";
        try {
            //validation des donne
            $validatedData = $request->validate([
                'id_personnel' => 'required',
                'date_retard' => 'required',
                'heure_arrive' => 'required',
            ]);
            // Extraction des données
            $id_personnel = $validatedData['id_personnel'];
            $heure_arrive = $validatedData['heure_arrive'];
            $date_retard = $validatedData['date_retard'];
            $date_retard_carbon = Carbon::parse($date_retard); // Transformer la date en type Carbon
            $jour = $date_retard_carbon->locale('fr')->dayName; // Capteur du jour de la date de retard
            $planning_du_personnel_en_question = Planning::where('id_personnel', $id_personnel)->first();//cherche le planning en question
            if($planning_du_personnel_en_question){// Si le planning existe
                $colonne = $jour."_deb";//reperer le collenone du jour
                $heure_debut_planning = $planning_du_personnel_en_question->$colonne; //stocker le planning du jour en question
                if($heure_debut_planning != "off" ){ //Si le planning est different de off sur ce jour là
                         // Transformer les heures en type Carbon
                        $heure_debut_planning_carbon = Carbon::createFromFormat('H:i', $heure_debut_planning);
                        $heure_arrive_carbon = Carbon::createFromFormat('H:i', $heure_arrive);
                        // Calculer la différence en secondes
                        $diffInSeconds = $heure_arrive_carbon->diffInSeconds($heure_debut_planning_carbon);
                        // Convertir la différence en heures, minutes, et secondes
                        $h = floor($diffInSeconds / 3600);           // Calcul des heures
                        $m = floor(($diffInSeconds % 3600) / 60);    // Calcul des minutes
                        $s = $diffInSeconds % 60;                    // Calcul des secondes
                        // Construire l'heure en fonction des valeurs
                        if ($h > 0) {
                            $nb_heure_retard .= $h . " h ";
                        }
                        if ($m > 0) {
                            $nb_heure_retard .= $m . " mn ";
                        }
                        if ($s > 0) {
                            $nb_heure_retard .= $s . " s";
                        }
                        // Supprimer les espaces en fin de chaîne si nécessaire
                        $nb_heure_retard = trim($nb_heure_retard);
                        try {
                            //stocker le pointage reterd
                            Retard::create([
                                'id_personnel' => $id_personnel,
                                'date_retard' => $date_retard,
                                'jour' => $jour,
                                'nb_heure_retard' => $nb_heure_retard
                            ]);
                            //mis à jour du nb des retards
                            $personnel = Personnel::find($id_personnel);
                            $nb_retard_mis_a_jour = $personnel->nb_retard + 1;
                            $personnel->nb_retard = $nb_retard_mis_a_jour;
                            $personnel->save();
                            //ajout dans l'activite
                            $titre = "Pointage Retard";
                            $description = "Le personnel ".$personnel->identifiant." est en retard" ;
                            Activite::create([
                                "titre_activite"=>$titre,
                                "description"=>$description,
                                "status"=>"Retard",
                            ]);
                            $message = "C'est fait avec succès ";
                        }  catch (QueryException $e) {
                            $message = "Une erreur est survenue ";
                    }
                }else{
                    $message = "Ce personnel est OFF le ".$jour;
                }
            } else {
                $message = "Planning du personnel n'existe pas !";
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json([
        "message" => $message,
        ]);
    }


    //modification retard
    public function modifier_pointage_retard(Request $request){
        $message ="";
        $nb_heure_retard = "";
        try {
             //validation des donne
             $validatedData = $request->validate([
                'id_pointage' => 'required',
                'id_personnel' => 'required',
                'date_retard' => 'required',
                'heure_arrive' => 'required',
            ]);
              // Extraction des données
              $id_pointage = $validatedData['id_pointage'];
              $id_personnel = $validatedData['id_personnel'];
              $heure_arrive = $validatedData['heure_arrive'];
              $date_retard = $validatedData['date_retard'];
              $date_retard_carbon = Carbon::parse($date_retard); // Transformer la date en type Carbon
              $jour = $date_retard_carbon->locale('fr')->dayName; // Capteur du jour de la date de retard
              $planning_du_personnel_en_question = Planning::where('id_personnel', $id_personnel)->first();//cherche le planning en question
            if($planning_du_personnel_en_question){// Si le planning existe
                $colonne = $jour."_deb";//reperer le collenone du jour
                $heure_debut_planning = $planning_du_personnel_en_question->$colonne; //stocker le planning du jour en question
                if($heure_debut_planning != "off" ){ //Si le planning est different de off sur ce jour là
                    // Transformer les heures en type Carbon
                    $heure_debut_planning_carbon = Carbon::createFromFormat('H:i', $heure_debut_planning);
                    $heure_arrive_carbon = Carbon::createFromFormat('H:i', $heure_arrive);
                    // Calculer la différence en secondes
                    $diffInSeconds = $heure_arrive_carbon->diffInSeconds($heure_debut_planning_carbon);
                    // Convertir la différence en heures, minutes, et secondes
                    $h = floor($diffInSeconds / 3600);           // Calcul des heures
                    $m = floor(($diffInSeconds % 3600) / 60);    // Calcul des minutes
                    $s = $diffInSeconds % 60;                    // Calcul des secondes
                    // Construire l'heure en fonction des valeurs
                    if ($h > 0) {
                        $nb_heure_retard .= $h . "h ";
                    }
                    if ($m > 0) {
                        $nb_heure_retard .= $m . "mn ";
                    }
                    if ($s > 0) {
                        $nb_heure_retard .= $s . "s";
                    }
                    // Supprimer les espaces en fin de chaîne si nécessaire
                    $nb_heure_retard = trim($nb_heure_retard);
                    $retard_en_question = Retard::find($id_pointage);
                    if($retard_en_question){
                        try {
                            $retard_en_question->update([
                                'date_retard' => $date_retard,
                                'jour' => $jour,
                                'nb_heure_retard' => $nb_heure_retard
                            ]);
                            $message ="Modification avec succès";
                        }  catch (QueryException $e) {
                            $message = "Une erreur est survenue ";
                        }
                    }else{
                        $message="retard n'existe pas";
                    }
                }else{
                    $message = "Ce personnel est OFF le ".$jour;
                }
            }else{
                $message="planning n'existe pas";
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return response()->json([
            "message"=>$message
         ]);

    }


    public function suprimer_retard($id){



        // Récupère le mois actuel
        $currentMonth = Carbon::now()->month;

        $message = "";
        $existe_retard = Retard::find($id);
        if($existe_retard){
            $date_ret = $existe_retard->date_retard;
            // Convertit la date en instance de Carbon
            $date_ret_carbon = Carbon::parse($date_ret);

            if($date_ret_carbon->month === $currentMonth){
                //algorithme pour diminuer le nombre de retard
                $personnel_en_question = Personnel::find($existe_retard->id_personnel);//chercher le personnel qui va etre pointer
                $nb_absent_a_jour = $personnel_en_question->nb_retard - 1;
                $personnel_en_question->nb_retard = $nb_absent_a_jour;
                $personnel_en_question->save();
            }
            try {
                $existe_retard->delete();

            } catch (QueryException $e) {
                $message = $e->getMessage();
            }

            $message = "Supression avec succès";
        }else{
            $message = "Absent n'existe pas !";
        }
        return response()->json([
            "message"=>$message
        ]);
    }


    //selection retard
    public function select_retard_simplement(){
        $donnes = Retard::with('personnel')->orderBy('id', 'desc')->limit(35)->get();
        return response()->json([
           "donnes"=>$donnes
        ]);
    }

}
