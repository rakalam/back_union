<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personnel;
use App\Models\Planning;
use App\Models\Activite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PersonnelController extends Controller
{


    //ajout personnel
    public function ajout_personnel(Request $request)
    {
        $message = "";
        try {
            // Validation des données pour un personnel
            $validatedData = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'date_naissance' => 'required|date',
                'sexe' => 'required|string',
                'adresse' => 'required|string',
                'contact' => 'required|string',
                'cin' => 'required|string',
                'photos' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Extraction des données après validation
            $nom = $validatedData['nom'];
            $prenom = $validatedData['prenom'];
            $date_naissance = $validatedData['date_naissance'];
            $sexe = $validatedData['sexe'];
            $adresse = $validatedData['adresse'];
            $contact = $validatedData['contact'];
            $cin = $validatedData['cin'];
            $photos = $validatedData['photos'] ?? null;

            // Gestion de l'image si elle est présente
            if ($photos) {
                $photo_name = Str::random(32) . "." . $photos->getClientOriginalExtension();
                Storage::disk('public')->put($photo_name, file_get_contents($photos));
            } else {
                $photo_name = null; // Aucun fichier d'image
            }

            // Création d'un avatar à partir du nom et prénom
            $avatar = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
            try {
                // Création du personnel avec valeurs par défaut
                $personnel = Personnel::create([
                    "avatar" => $avatar,
                    "nom" => $nom,
                    "prenom" => $prenom,
                    "date_naissance" => $date_naissance,
                    "sexe" => $sexe,
                    "adresse" => $adresse,
                    "contact" => $contact,
                    "cin" => $cin,
                    "photos" => $photo_name,
                ]);

                // Création de l'activité liée
                $titre = "Concernant Personnel";
                $description = "Nouveau personnel ajouté : " . $avatar;
                Activite::create([
                    "titre_activite" => $titre,
                    "description" => $description,
                    "status" => "Personnel",
                ]);

                $message = "Le personnel a été enregistré avec succès";
            } catch (QueryException $e) {
                if ($e->errorInfo[1] === 1062) {  // Problème sur l'unicité
                    $message = "Identifiant déjà existant";
                } else {
                    $message = "Une erreur est survenue lors de l'ajout du personnel";
                }
            }
        } catch (\Exception $e) {
            $message = "Validation des champs incorrecte !";
        }

        return response()->json([
           "message" => $message,
        ]);
    }

   //modification d'un personnel
   public function modifie_personnel(Request $request)
   {
       $message = "";

       try {
           // Validation des données
           $validatedData = $request->validate([
               'id_personnel' => 'required',
               'nom' => 'required|string|max:255',
               'prenom' => 'required|string|max:255',
               'date_naissance' => 'required|date',
               'sexe' => 'required|string',
               'adresse' => 'required|string',
               'contact' => 'required|string',
               'cin' => 'required|string',
               'photos' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
           ]);

           // Extraction des données après validation
           $id_personnel = $validatedData['id_personnel'];
           $nom = $validatedData['nom'];
           $prenom = $validatedData['prenom'];
           $date_naissance = $validatedData['date_naissance'];
           $sexe = $validatedData['sexe'];
           $adresse = $validatedData['adresse'];
           $contact = $validatedData['contact'];
           $cin = $validatedData['cin'];
           $photos = $validatedData['photos'] ?? null;

           // Création d'un avatar à partir de nom et prénom
           $avatar = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));

           // Recherche du personnel existant
           $existe_personnel = Personnel::find($id_personnel);

           if ($existe_personnel) {
               try {
                   // Gestion de la nouvelle image si elle est fournie
                   if ($photos) {
                       $storage = Storage::disk('public');

                       // Suppression de l'ancienne image si elle existe
                       if ($existe_personnel->photos && $storage->exists($existe_personnel->photos)) {
                           $storage->delete($existe_personnel->photos);
                       }

                       // Génération d'un nouveau nom de fichier pour l'image
                       $photo_name = Str::random(32) . "." . $photos->getClientOriginalExtension();
                       $storage->put($photo_name, file_get_contents($photos));
                       $existe_personnel->photos = $photo_name;
                   }

                   // Mise à jour des autres informations
                   $existe_personnel->nom = $nom;
                   $existe_personnel->prenom = $prenom;
                   $existe_personnel->date_naissance = $date_naissance;
                   $existe_personnel->sexe = $sexe;
                   $existe_personnel->adresse = $adresse;
                   $existe_personnel->contact = $contact;
                   $existe_personnel->cin = $cin;
                   $existe_personnel->avatar = $avatar;
                   $existe_personnel->save();

                   // Enregistrement de l'activité
                   $titre = "Concernant Personnel";
                   $description = "Informations modifiées sur : " . $avatar;
                   Activite::create([
                       "titre_activite" => $titre,
                       "description" => $description,
                       "status" => "Personnel",
                   ]);

                   $message = "Modification réussie avec succès !";
               } catch (QueryException $e) {
                   $message = "Une erreur s'est produite lors de la modification.";
               }
           } else {
               $message = "Personnel n'existe pas !";
           }

       } catch (\Exception $e) {
           $message = "Validation des champs incorrecte !";
       }

       return response()->json([
           "message" => $message
       ]);
   }




  // Suppression d'un personnel
    public function supprimer_personnel($id)
    {
        $message = "";
        $existe_personnel = Personnel::find($id);

        if ($existe_personnel) {
            // Suppression de la photo du stockage si elle existe


            if ($existe_personnel->photos) {
                $storage = Storage::disk('public');
                if ($storage->exists($existe_personnel->photos)) {
                    $storage->delete($existe_personnel->photos);
                }
            }

            // Suppression du personnel
            $existe_personnel->delete();
            $message = "Suppression de " . $existe_personnel->avatar . " réussie !";
            
        } else {
            $message = "Personnel n'existe pas !";
        }

        return response()->json([
            "message" => $message
        ]);
    }



   public function select_personnel_simplement(){
     $donnes = Personnel::orderBy('id', 'asc')->get();
     return response()->json([
        "donnes"=>$donnes
     ]);
   }


   public function select_personnel_pas_planning(){

    $personnels = Personnel::whereNotIn('id', function ($query)  {
        $query->select('id_personnel')->from('plannings');
    })->orderBy('id', 'asc')->get();

    return response()->json([
        "donnes"=>$personnels
     ]);

   }

   public function select_sanction(){
    $donnes = Personnel::orderBy('nb_absent', 'desc')
                        ->orderBy('nb_retard', 'desc')
                        ->limit(5)
                         ->get();
    return response()->json([
       "donnes"=>$donnes
    ]);
  }

  public function statistique_personnel(){
    $masculin_total = Personnel::where('sexe', 'masculin')->count();
    $feminin_total = Personnel::where('sexe', 'feminin')->count();

    $pourcentage_masculin = ($masculin_total / ($masculin_total + $feminin_total)) *100 ;
    $pourcentage_feminin = ($feminin_total / ($masculin_total + $feminin_total)) *100 ;


    $avatar_masculin = Personnel::select('*')
                                ->where('sexe', 'masculin')
                                ->orderBy('id', 'asc')
                                ->limit(6)->get();
    $avatar_feminin= Personnel::select('*')
                                ->where('sexe', 'feminin')
                                ->orderBy('id', 'asc')
                                ->limit(4)->get();

    return response()->json([
       "masculin_total"=>$masculin_total,
       "feminin_total"=>$feminin_total,
       "avatar_masculin"=>$avatar_masculin,
       "avatar_feminin"=>$avatar_feminin,
       "pourcentage_masculin"=>$pourcentage_masculin,
       "pourcentage_feminin"=>$pourcentage_feminin,
    ]);
  }




}
