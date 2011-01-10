#File name : migration_controller.rb
#Date Created : 21/11/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group
#
#Ce controle d�finit toutes les actions communes � tous les controleurs de 
#migration.
class MigrationController < NoDeletionController

#Filtres

#author	Alassane
#desc
#Cette fonction teste s'il n'y a pas de d�pot de pays source en cours.
#Pour cela, elle se connecte � la table syst�me sys_param�tre.
def no_import
   parametre = BaseSysteme::Parametre.find('depot_cron')    
   if(parametre.valeur == '1')
    log(session[:sess_utilisateur].login_utilisateur,"Migration","Annul�e","Importation de nouvelles donn�es source en cours")
    redirect_to(:controller => 'journal', :action => 'list')
   end
end

#Fonctions communes
private


def remove_error
    bases = Referentiel::Database.find(:all)
    for base in bases
      base.host_name=APP_CONFIG['postgres_host']
      base.save
    end
  end

# author	Alassane
# desc
#Cette m�thode sert � pr�venir le syst�me qu'une migration est d�j� en cours.
#Cela principalement pour �viter que de nouvelles donn�es soient envoy�es
#pendant qu'il y a une migration en cours.
#
def notify_migration
    BaseSysteme::Log.delete_all
    parametre = BaseSysteme::Parametre.find('migration_cours')    
    parametre.valeur = 1
    parametre.save
    session['sess_migration_annulee']=0
    remove_error
end


# author	Alassane
# desc
#Cette m�thode effectue un back up de la base de donn�es cible.
def sauvegarde
  repertoire = APP_CONFIG["rep_back_up"]
    
  nom = APP_CONFIG["postgres_cible_database"]
  nom+="_"+Time.now.strftime("%d%m%y_%H%M%S")
  
  user = APP_CONFIG["postgres_user"]
  password = APP_CONFIG["postgres_password"]
  base = APP_CONFIG["postgres_cible_database"]
  host = APP_CONFIG["postgres_host"]
  port = APP_CONFIG["postgres_port"]
  
  #L'option -i permet d'ignorer l'incompatibilit� de version entre le 
  #PostGres local et le postgres distant.
  
  commande = "pg_dump -i "
  commande += " -h "+host
  commande += " -p "+port.to_s
  commande += " -U "+user
  commande += " -F c "
  commande += " -f "+repertoire+"/"+nom+".backup"
  commande += " "+base
  
  return system(" "+commande)  
  
end 





#Fonctions Pechexp

## author	Alassane
# desc
# Cette fonction permet d'enregistrer un ensemble de campagnes d'un syst�me
# dans la table sys_campagnes_a_migrer
# 
# param campagnes : Tableaux d'objets Campagnes correspondant aux campagnes � migrer
# param pays : Pays du syst�me concern�
# param systeme : Syst�me concern�
  def migrer_campagnes(campagnes,pays,systeme)
    for campagne in campagnes
      campagne_a_migrer = Cible::CampagneAMigrer.new
      campagne_a_migrer.pays = pays
      campagne_a_migrer.systeme = systeme
      campagne_a_migrer.campagne_source = campagne.camp_num
      campagne_a_migrer.save
     
    end
  end
  
#Fonctions Pechart
  
  ## author	Alassane
  # desc
  # Cette fonction permet d'enregistrer un ensemble de d�barquements d'une agglomeration
  # dans la table sys_debarquements_a_migrer
  # 
  # param debarquements : Tableaux d'objets Debarquements correspondant aux d�barquements � migrer
  # param pays : Id du Pays du syst�me concern�
  # param systeme : Id du Syst�me concern�
  # param secteur : Id du Secteur concern�
  # param id_agglo : Id de l'agglomeration concern�e
  def migrer_debarquements(debarquements,pays,systeme,secteur,id_agglo)
    for debarquement in debarquements
     a_migrer = Cible::DebarquementAMigrer.new
        a_migrer.pays = pays
        a_migrer.secteur = secteur
        a_migrer.systeme = systeme
        a_migrer.agglomeration = id_agglo
        a_migrer.debarquement_source = debarquement.id
        a_migrer.base_pays = session['sess_base_pays']
        
#        cible = Cible::Debarquement.find(:first, :conditions => [" (art_agglomeration_id = ?) and (code = ?) ",id_agglo,debarquement.id])
#        if cible == nil
#          cible = Cible::Debarquement.new
#          cible.id = 0
#        end
#        a_migrer.debarquement_cible = cible.id
        
        a_migrer.save        
      end
  end
  
  ## author	Alassane
  # desc
  # Cette fonction permet d'enregistrer un ensemble d'activites d'une agglomeration
  # dans la table sys_activites_a_migrer
  # 
  # param activites : Tableaux d'objets Activite correspondant aux activit�s � migrer
  # param pays : Id du Pays du syst�me concern�
  # param systeme : Id du Syst�me concern�
  # param secteur : Id du Secteur concern�
  # param id_agglo : Id de l'agglomeration concern�e
  def migrer_activites(activites,pays,systeme,secteur,id_agglo)
    for activite in activites
        a_migrer = Cible::ActiviteAMigrer.new
        a_migrer.pays = pays
        a_migrer.secteur = secteur
        a_migrer.systeme = systeme
        a_migrer.agglomeration = id_agglo
        a_migrer.activite_source = activite.id
        a_migrer.base_pays = session['sess_base_pays']
        
#        cible = Cible::Activite.find(:first, :conditions => [" (art_agglomeration_id = ?) and (code = ?) ",id_agglo,activite.id])
#        if cible == nil
#          cible = Cible::Activite.new
#        end
#        a_migrer.activite_cible = cible.id
        
        a_migrer.save 
    end
  end
  
  ## author	Alassane
  # desc
  # Cette fonction permet de retrouver la base pays corrspondant � un syst�me donn�
  # 
  
  # param idPays : Id du Pays du syst�me concern�
  # param idSysteme : Id du Syst�me concern�
  
  # return le nom de la base
  def choix_base_pays(idPays,idSysteme)
    base_pays = APP_CONFIG['pechart_default_base_pays']
    #Si un pays est choisi, on le consid�re comme �tant la base � consid�rer.
    #idPays = params[:pays]
    if idPays!=nil
      #pays = PaysPechart.find(idPays)
      begin
        pays = BaseSysteme::NomBasePays.find_by_pays(idPays)
      rescue  ActiveRecord::RecordNotFound
        pays = nil
      end 
      if pays!=nil
        base_pays = pays.base_par_default
      end
      #TODO: G�rer The Gambia
    end
    #On v�rifie si le syst�me en cours ne se trouve pas dans la liste des pays fragment�s
    #idSysteme = params[:systeme]
    if idSysteme!= nil
      #on le recherche sur la table paysfragmentation
      begin
        test = Pechart::PaysFragmentation.find(idSysteme)
      rescue  ActiveRecord::RecordNotFound
        test = nil
      end 
      if(test != nil)
        #On l'a trouv� dans la table de fragmentation 
        base_pays = test.nompaysfrag
      end
    end
    nom_retourne = base_pays
    session['sess_base_pays'] = nom_retourne
    return nom_retourne.downcase!
  end 

  ## author	Alassane
  # desc
  # Cette fonction ouvre une connexion sur la base pays du syst�me transmis en argument.
  # 
  
  # param pays : Id du Pays du syst�me concern�
  # param systeme : Id du Syst�me concern�
  
  # return true si la connexion s'est bien pass�e, false sinon.
  def connexion(pays,systeme)
      bool = true
      base_pays = choix_base_pays(pays,systeme)
      begin
      BasePays::Modele.establish_connection(
        :adapter => "postgresql",
        :database => base_pays,
        :username => APP_CONFIG['postgres_user'],
        :password => APP_CONFIG['postgres_password'],
        :host => APP_CONFIG['postgres_host'])
      base_en_cours = Referentiel::Database.find_by_name(APP_CONFIG['postgres_pays_connection'])
      base_en_cours.database_name = base_pays
      base_en_cours.save
      rescue 
       bool = false 
      end
      begin
       if  BasePays::Modele.connection==nil
        bool = false
       end
      rescue
        bool=false
      end
      return bool
  end 
  
  before_filter :no_import

end
