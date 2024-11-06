<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personnel;
use App\Models\Planning;
use App\Models\Activite;
use App\Models\Retard;
use App\Models\Absent;
use DB;
use Carbon\Carbon;

class MelangeController extends Controller
{

    public function recherche_deux_date(Request $request){

       $var1 = $request->debut;
       $var2 = $request->fin;
       $donnes_retard = Retard::with('personnel')
       ->select('id_personnel', DB::raw('COUNT(*) as nb_retard'))
       ->whereBetween('date_retard', [$var1, $var2])
       ->groupBy('id_personnel')
       ->get();


       $donnes_absent = Absent::with('personnel')
       ->select('id_personnel', DB::raw('COUNT(*) as nb_absent'))
       ->whereBetween('date_absent', [$var1, $var2])
       ->groupBy('id_personnel')
       ->get();



        return response()->json([
           "donnes_absent"=>$donnes_absent,
           "donnes_retard"=>$donnes_retard,
        ]);
      }


      public function planning_retard_absent_personnel($id){
        $retards = Retard::with('personnel')
        ->where('id_personnel', $id)
        ->orderBy('id', 'desc')
        ->limit(7)
        ->get();

        $absents = Absent::with('personnel')
        ->where('id_personnel', $id)
        ->orderBy('id', 'desc')
        ->limit(7)
        ->get();

           $plannings = Planning::where('id_personnel', $id)->first();
           $nb_planning = Planning::where('id_personnel', $id)->count();
           $personnel = Personnel::find($id);


         return response()->json([
          "plannings"=>$plannings,
          "nb_planning"=>$nb_planning,
          "personnels"=>$personnel,
          "retards"=>$retards,
          "absents"=>$absents,
         ]);
      }


      public function reinitialistion(){


        Personnel::query()->update([
            'nb_retard' => 0,
            'nb_absent' => 0
        ]);

        $titre = "Réinitialisation";
        $description = "Pointage réinitialisés" ;
        Activite::create([
            "titre_activite"=>$titre,
            "description"=>$description,
            "status"=>"Reinitialisation",
        ]);

        return response()->json([
            "message"=>"Les pointages pour chaque personnel ont été réinitialisés",
         ]);

      }


      public function donne_statistique()
        {
            $data = [];
            // Pour les 7 derniers jours, excluant aujourd'hui
            for ($i = 1; $i <= 7; $i++) {
                $date = Carbon::today()->subDays($i);

                // Compter les absences pour la date actuelle
                $totalAbsences = Absent::whereDate('date_absent', $date)->count();

                // Compter les retards pour la date actuelle
                $totalRetards = Retard::whereDate('date_retard', $date)->count();

                // Ajouter les données dans le tableau
                $data[] = [
                    'date' => $date->format('d M Y'),
                    'absences' => $totalAbsences,
                    'retards' => $totalRetards,
                ];
            }

            // Retourner les données en JSON pour React
            return response()->json(array_reverse($data)); // Pour avoir dans l'ordre chronologique
      }

      public function petit_statistique()
       {
          $currentMonth = Carbon::now()->month;
          $currentYear = Carbon::now()->year;

          //Pour les Retard
          // Obtenir le total des retards pour le mois actuel
          $totalRetardsCounts = Retard::whereRaw('EXTRACT(MONTH FROM TO_DATE(date_retard, \'YYYY-MM-DD\')) = ?', [$currentMonth])
                                ->whereRaw('EXTRACT(YEAR FROM TO_DATE(date_retard, \'YYYY-MM-DD\')) = ?', [$currentYear])
                                ->count();
          $totalRetards = Retard::with('personnel')
                        ->whereRaw('EXTRACT(MONTH FROM TO_DATE(date_retard, \'YYYY-MM-DD\')) = ?', [$currentMonth])
                        ->whereRaw('EXTRACT(YEAR FROM TO_DATE(date_retard, \'YYYY-MM-DD\')) = ?', [$currentYear])
                        ->get();

          $totalRetardsFeminin = 0;
          $totalRetardsMasculin = 0;
          foreach($totalRetards as $t){
            if($t->personnel->sexe === "feminin"){
                $totalRetardsFeminin = $totalRetardsFeminin + 1;
            }else{
                $totalRetardsMasculin = $totalRetardsMasculin + 1;
            }
          }


          // Obtenir le total des absents pour le mois actuel
          $totalAbsentscounts = Absent::whereRaw('EXTRACT(MONTH FROM TO_DATE(date_absent, \'YYYY-MM-DD\')) = ?', [$currentMonth])
                                ->whereRaw('EXTRACT(YEAR FROM TO_DATE(date_absent, \'YYYY-MM-DD\')) = ?', [$currentYear])
                                ->count();

          $totalAbsents = Absent::with('personnel')
                                ->whereRaw('EXTRACT(MONTH FROM TO_DATE(date_absent, \'YYYY-MM-DD\')) = ?', [$currentMonth])
                                ->whereRaw('EXTRACT(YEAR FROM TO_DATE(date_absent, \'YYYY-MM-DD\')) = ?', [$currentYear])
                                ->get();

            $totalAbsentFeminin = 0;
            $totalAbsentMasculin = 0;
            foreach($totalAbsents as $t){
                if($t->personnel->sexe === "feminin"){
                    $totalAbsentFeminin = $totalAbsentFeminin + 1;
                }else{
                    $totalAbsentMasculin = $totalAbsentMasculin + 1;
                }
            }

          return response()->json([
              'total_retards' => $totalRetardsCounts,
              'totalRetardsFeminin' => $totalRetardsFeminin,
              'totalRetardsMasculin' => $totalRetardsMasculin,


              'total_absents' => $totalAbsentscounts,
              'totalAbsentFeminin' => $totalAbsentFeminin,
              'totalAbsentMasculin' => $totalAbsentMasculin,
          ]);
      }




}
