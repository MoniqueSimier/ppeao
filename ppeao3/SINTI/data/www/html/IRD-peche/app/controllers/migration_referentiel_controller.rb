#File name : migration_referentiel_controller.rb
#Date Created : 17/11/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group
class MigrationReferentielController < MigrationController
  
  
  # author	Alassane
  # desc
  #Il s'agit de l'action invoquée par défaut.
  def index
    flash[:titre] = "Migration des données <br/> <br/>du  référentiel"
    @image="balingo.jpg"
  end
  
  # author	Alassane
  # desc
  #Cette méthode effectue tous les traitements necessaires avant la sauvegarde.
  #Il s'agit principalement de l'enregistrement de l'événement dans le journal.
  def before_sauvegarde
   log(session[:sess_utilisateur].login_utilisateur,"Sauvegarde","Démarrage","")
  end
  
  # author	Alassane
  # desc
  #Cette méthode effectue tous les traitements necessaires après la sauvegarde.
  #Il s'agit principalement de l'enregistrement de l'événement dans le journal.
  def after_sauvegarde(etat)
    log(session[:sess_utilisateur].login_utilisateur,"Sauvegarde",etat,"")
  end
  
  # author	Alassane
  # desc
  #Cette méthode se charge d'effectuer tous les traitements nécessaires avant le déclenchement
  #effectif de la migration du référentiel. En l'occurrence, elle enregistre l'événement dans le journal ainsi que
  #le nom de lamigration lancée dans la session. ce dernier permettra de tracer la fin de la migration 
  #dans le journal.
  def before_launch
      
    notify_migration
    log(session[:sess_utilisateur].login_utilisateur,"Migration référentiel","Démarrage",params[:commentaires])
    session['sess_migration'] = "Migration référentiel"
    session['sess_dernier_transfo'] = BaseSysteme::Log.find(:all, :conditions => "status = 'end' ").size
    session['sess_nombre_total_transformations'] = APP_CONFIG["nb_trans_migration_referentiel"].to_i
    session['sess_migration_lancee']=1
    
  end
  
  
  # author	Alassane
  # desc
  #Cette méthode est commune à tous les controleurs de migration.
  #De manière générale, elle lance le script de la migration et redirige vers la barre de 
  #progression.
  def launch
    before_sauvegarde
    bool = sauvegarde  
    if bool
      after_sauvegarde('Succès')
      before_launch    
      chemin = APP_CONFIG["rep_batch"]+APP_CONFIG["migration_referentiel_batch"]
      system(APP_CONFIG["launcher"]+" "+chemin)
      redirect_to(:controller => 'progression', :action => 'tracer')
    else
      after_sauvegarde('Echec')
      redirect_to(:controller => 'journal', :action => 'list')
    end   
  end
  
  
  def launch_globale

      before_launch    
      chemin = APP_CONFIG["rep_batch"]+APP_CONFIG["migration_referentiel_batch"]
      system(APP_CONFIG["launcher"]+" "+chemin)
      redirect_to(:controller => 'progression_globale', :action => 'tracer')
        
  end
 before_filter :filtre_suppression, :only => [:launch]
 before_filter :filtre_logs_transformations
 #before_filter :no_import 
end
