#Cette classe représente le controlleur père de tous les autres dont 
#les traitements exigent une cohérence entre les données source et
#cible.
#Il s'agira donc de l'ensemble de tous les controleurs.
class NoDeletionController < AdminApplicationController

def filtre_suppression 
    if (tables_supprimees)
      redirect_to(:action => "list", :controller => "no_deletion")
    end
end

 

def filtre_list
if (session['sess_tables_supprimees'] ==nil)
  redirect_to(:action => "list", :controller => "journal")
end
end

@data = Array.new
private
#Cette fonction teste si des données sources ont été supprimées.

#author	Evelyne
#desc
#cette action recupere dans une variable de session , les enregistrements supprimés
#elle retourne un booleen egale à true s'il en trouve 
def tables_supprimees
  bool = false
  @tab_modeles_utilises = [
        [Pechart::Systeme,Cible::Systeme,"systemes","libelle"],
        [Pechexp::Pays,Cible::Pays,"pays","nom"],
        [Pechart::Secteur,Cible::Secteur,"secteurs","nom"],
        [Pechexp::Ordre,Cible::Ordre,"ordres","libelle"],
        [Pechexp::Famille,Cible::Famille,"familles","libelle"],
        [Pechexp::CategorieEcologique,Cible::CategorieEcologique,"categories ecologiques","libelle"],
        [Pechexp::CategorieTrophique,Cible::CategorieTrophique,"categories trophiques","libelle"],
        [Pechart::Originekb,Cible::Originekb,"originekb","libelle"],
        [Pechexp::Espece,Cible::Espece,"especes","libelle"],
        [Pechart::EtatCiel,Cible::EtatCiel,"etats ciels","libelle"],
        [Pechart::CategorieSocioProfessionnelle,Cible::CategorieSocioProfessionnelle,"categories socio-professionnelles","libelle"],
        [Pechart::GrandTypeEngin,Cible::GrandTypeEngin,"etats ciels","libelle"],
        [Pechart::Milieu,Cible::Milieu,"milieux","libelle"],
        [Pechart::TypeActivite,Cible::TypeActivite,"grands types engins","libelle"],
        [Pechart::TypeAgglomeration,Cible::TypeAgglomeration,"types agglomerations","libelle"],
        [Pechart::TypeEngin,Cible::TypeEngin,"types engins","libelle"],
        [Pechart::TypeSortie,Cible::TypeSortie,"types sorties","libelle"],
        [Pechart::Vent,Cible::Vent,"vents","libelle"],
        [Pechart::Agglomeration,Cible::Agglomeration,"agglomerations","nom"],
        [Pechexp::Engin,Cible::Engin,"engins","libelle"],
        [Pechexp::Contenu,Cible::Contenu,"contenus","libelle"],
        [Pechexp::Debris,Cible::Debris,"debris","libelle"],
        [Pechexp::ForceCourant,Cible::ForceCourant,"forces du courant","libelle"],
        [Pechexp::Position,Cible::Position,"positions","libelle"],
        [Pechexp::Qualite,Cible::Qualite,"qualites","libelle"],
        [Pechexp::Remplissage,Cible::Remplissage,"remplissages","libelle"],
        [Pechexp::Sediment,Cible::Sediment,"sediments","libelle"],
        [Pechexp::SensCourant,Cible::SensCourant,"sens du courant","libelle"],
        [Pechexp::Sexe,Cible::Sexe,"sexes","libelle"],
        [Pechexp::Stade,Cible::Stade,"stades","libelle"],
        [Pechexp::Vegetation,Cible::Vegetation,"vegetations","libelle"]
      ]
    i = 0
    session['sess_tables_supprimees'] = Array.new
      
      while i < @tab_modeles_utilises.length 
        session['sess_tables_supprimees'][i]= tests_suppression_table( @tab_modeles_utilises[i][0], @tab_modeles_utilises[i][1])
        if (session['sess_tables_supprimees'][i].size!=0)
          bool = true
        end
        i += 1
      end
      #puts "Taille "+session['sess_tables_supprimees'].size.to_s
    
  return bool
end


#Cette fonction traite les suppressions effectuées dans les sources
def tests_suppression_table(source,cible)

  i = 0
  resultat = Array.new
  for dest in cible.find(:all) do
    begin
      #recoit l'objet source ayant une correspondance dans la cible, 
      #sinon(donc en cas d'exception) elle sera forcé à nil 
      origine = source.find(dest.id)
    rescue
      origine = nil
    end
    if origine == nil
      trouve = dest.id
      if i == 0
        resultat = [trouve]
        i +=1
      else
        resultat << dest.id
      end
    end
  end
  return resultat
end

public 
def list
  @tab_modeles_utilises = [
        [Pechart::Systeme,Cible::Systeme,"systemes","libelle"],
        [Pechexp::Pays,Cible::Pays,"pays","nom"],
        [Pechart::Secteur,Cible::Secteur,"secteurs","nom"],
        [Pechexp::Ordre,Cible::Ordre,"ordres","libelle"],
        [Pechexp::Famille,Cible::Famille,"familles","libelle"],
        [Pechexp::CategorieEcologique,Cible::CategorieEcologique,"categories ecologiques","libelle"],
        [Pechexp::CategorieTrophique,Cible::CategorieTrophique,"categories trophiques","libelle"],
        [Pechart::Originekb,Cible::Originekb,"originekb","libelle"],
        [Pechexp::Espece,Cible::Espece,"especes","libelle"],
        [Pechart::EtatCiel,Cible::EtatCiel,"etats ciels","libelle"],
        [Pechart::CategorieSocioProfessionnelle,Cible::CategorieSocioProfessionnelle,"categories socio-professionnelles","libelle"],
        [Pechart::GrandTypeEngin,Cible::GrandTypeEngin,"grands types engins","libelle"],
        [Pechart::Milieu,Cible::Milieu,"milieux","libelle"],
        [Pechart::TypeActivite,Cible::TypeActivite,"types activites","libelle"],
        [Pechart::TypeAgglomeration,Cible::TypeAgglomeration,"types agglomerations","libelle"],
        [Pechart::TypeEngin,Cible::TypeEngin,"types engins","libelle"],
        [Pechart::TypeSortie,Cible::TypeSortie,"types sorties","libelle"],
        [Pechart::Vent,Cible::Vent,"vents","libelle"],
        [Pechart::Agglomeration,Cible::Agglomeration,"agglomerations","nom"],
        [Pechexp::Engin,Cible::Engin,"engins","libelle"],
        [Pechexp::Contenu,Cible::Contenu,"contenus","libelle"],
        [Pechexp::Debris,Cible::Debris,"debris","libelle"],
        [Pechexp::ForceCourant,Cible::ForceCourant,"forces du courant","libelle"],
        [Pechexp::Position,Cible::Position,"positions","libelle"],
        [Pechexp::Qualite,Cible::Qualite,"qualites","libelle"],
        [Pechexp::Remplissage,Cible::Remplissage,"remplissages","libelle"],
        [Pechexp::Sediment,Cible::Sediment,"sediments","libelle"],
        [Pechexp::SensCourant,Cible::SensCourant,"sens du courant","libelle"],
        [Pechexp::Sexe,Cible::Sexe,"sexes","libelle"],
        [Pechexp::Stade,Cible::Stade,"stades","libelle"],
        [Pechexp::Vegetation,Cible::Vegetation,"vegetations","libelle"]
      ]
  @tab = session['sess_tables_supprimees']
  session['sess_tables_supprimees'] = nil
  @data = Array.new
  @titres = Array.new  
  @indices = Array.new 
  k = 0
  for i in 0..@tab.size-1
    if (@tab[i].size >0)
      @indices[k] = i
      k = k+1
    end
    @titres[i] = @tab_modeles_utilises[i][2]
    @data[i] = Array.new
    j = 0
   while(j<@tab[i].size) do
      #puts "je regarde"+t[j].to_s
     @data[i][j] = @tab_modeles_utilises[i][1].find(@tab[i][j]).send(@tab_modeles_utilises[i][3])
     #puts i.to_s+" "+@data[i][j].id.to_s
     j = j + 1
   end
  end

end

#before_filter :filtre_suppression, :except => 'list'
before_filter :filtre_list, :only => 'list'
end
