#File name : migration_pechexp_controller.rb
#Date Created : 17/11/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group
class MigrationPechexpController < MigrationController
  
  # author	Alassane
  # desc
  #Cette action récupère toutes les informations qu'il faut pour afficher la
  #page de la migration par campagne.
  
  def campagnes
      @pays_source = Pechexp::Pays.find(:all, :order => 'pays_nom asc')
      @systemes = Array.new
      @campagnes = Array.new
      
      @etat_bouton_ok_systeme = 'disabled'
      
      if params[:pays]!= nil
        pays_selectionne = Pechexp::Pays.find(params[:pays])
        
        if pays_selectionne != nil
         @systemes = pays_selectionne.systemes
         if @systemes.size >0
          @etat_bouton_ok_systeme = ''
         end
        end
      end
      
      if params[:systeme]!= nil
      systeme_selectionne = Pechexp::Systeme.find(params[:systeme])
      if systeme_selectionne != nil
      @campagnes = systeme_selectionne.campagnes
      end
      end
      
     flash[:titre] = "Migration des <br/> <br/> campagnes Pechexp"
     @image="pechexp.jpg"
  end
  
  
  # author	Alassane
  # desc
  #Cette action redirige vers la page de migration de l'ensemble des données Pechexp.
  def tout
     flash[:titre] = "Migration de <br/> <br/> toutes les données Pechexp"
     @image="pechexp.jpg"
  end
  
  # author	Alassane
  # desc
  #Cette action redirige vers la page de migration de l'ensemble des données de paramétrage Pechexp.
  def parametrage
     flash[:titre] = "Migration des <br/> <br/> données de paramétrage Pechexp"
     @image="pechexp.jpg"
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
  #Cette méthode est invoquée avant le déclenchement de la migration de toutes les données Pechexp.
  #Elle se charge essentiellement d'enregistrer l'événement dans le journal. Mais aussi, elle stocke 
  #dans une variable de session le nombre total de transformations, le nom de la migration et un flag
  #indiquant que la migration est lancée.
  def before_launch_tout
    notify_migration
    log(session[:sess_utilisateur].login_utilisateur,"Migration tout Pechexp","Démarrage",params[:commentaires])
    
    session['sess_migration'] = "Migration tout Pechexp"
    session['sess_dernier_transfo'] = BaseSysteme::Log.find(:all, :conditions => "status = 'end' ").size
    session['sess_nombre_total_transformations'] = 
    APP_CONFIG["nb_trans_migration_parametrage_pechexp"].to_i + APP_CONFIG["nb_trans_migration_campagnes_pechexp"].to_i
    session['sess_migration_lancee']=1
    
  end
  
  # author	Alassane
  # desc
  #Cette méthode est invoquée juste avant le déclenchement de la migration de toutes les données Pechexp.
  #Elle se charge essentiellement d'enregistrer dans la base système l'ensemble des campagnes à
  #migrer.
  def pre_launch_tout
    Cible::CampagneAMigrer.delete_all
    pays = Pechexp::Pays.find(:all)
    for p in pays
      systemes = p.systemes
      for systeme in systemes
        campagnes = systeme.campagnes
        migrer_campagnes(campagnes,p.id,systeme.id)
      end
    end
  end
  
  # author	Alassane
  # desc
  #Cette méthode déclenche la migration de toutes les données Pechexp.
  #Elle commence par faire une sauvegarde. Si elle réussit, elle lance le script de la migration 
  #et redirige vers la barre de progression.
  #Sinon elle redirige vers le journal et précise que la sauvegarde a échoué.
  def launch_tout
    before_sauvegarde
    bool = sauvegarde
    if bool
      after_sauvegarde('Succès')
      before_launch_tout
      pre_launch_tout
      chemin = APP_CONFIG["rep_batch"]+APP_CONFIG["migration_tout_pechexp_batch"]
      system(APP_CONFIG["launcher"]+" "+ chemin)
      redirect_to(:controller => 'progression', :action => 'tracer')
    else
      after_sauvegarde('Echec')
      redirect_to(:controller => 'journal', :action => 'list')
    end    
  end
  
  # author	Alassane
  # desc
  #Cette méthode est invoquée avant le déclenchement de la migration de toutes les données de paramétrage Pechexp.
  #Elle se charge essentiellement d'enregistrer l'événement dans le journal.
  def before_launch_parametrage
    notify_migration
    log(session[:sess_utilisateur].login_utilisateur,"Migration paramétrage Pechexp","Démarrage",params[:commentaires])
    
    session['sess_migration'] = "Migration paramétrage Pechexp"
    session['sess_dernier_transfo'] = BaseSysteme::Log.find(:all, :conditions => "status = 'end' ").size
    session['sess_nombre_total_transformations'] = APP_CONFIG["nb_trans_migration_parametrage_pechexp"].to_i
    session['sess_migration_lancee']=1
    
  end
  
  # author	Alassane
  # desc
  #Cette action déclenche la migration approprement dite de toutes les données de paramétrage Pechexp.
  def launch_parametrage
    before_sauvegarde
    bool = sauvegarde
    if bool
      before_sauvegarde()
      before_launch_parametrage
      chemin = APP_CONFIG["rep_batch"]+APP_CONFIG["migration_parametrage_pechexp_batch"]
      system(APP_CONFIG["launcher"]+" "+ chemin)
      redirect_to(:controller => 'progression', :action => 'tracer')
    else
      after_sauvegarde('Echec')
      redirect_to(:controller => 'journal', :action => 'list')
    end    
  end
  
  # author	Alassane
  # desc
  #Cette méthode est invoquée avant le déclenchement de la migration de toutes les données d'enquêtes Pechexp.
  #Elle se charge essentiellement d'enregistrer l'événement dans le journal.
  def before_launch_campagnes
    notify_migration
    log(session[:sess_utilisateur].login_utilisateur,"Migration campagnes Pechexp","Démarrage",params[:commentaires])
    
    session['sess_migration'] = "Migration campagnes Pechexp"
    session['sess_dernier_transfo'] = BaseSysteme::Log.find(:all, :conditions => "status = 'end' ").size
    session['sess_nombre_total_transformations'] = APP_CONFIG["nb_trans_migration_campagnes_pechexp"].to_i
    session['sess_migration_lancee']=1
    
  end 
  
  # author	Alassane
  # desc
  #Cette méthode est invoquée avant le déclenchement de la migration de toutes les données de campagnes Pechexp.
  #Elle se charge essentiellement d'enregistrer les campagnes à migrer.
  def pre_launch_campagne
  
    Cible::CampagneAMigrer.delete_all
  
    pays = params[:pays]
    systeme = params[:systeme]
    campagnes = params[:campagnes]
    
    camps = Array.new
    for campagne in campagnes
      c = Pechexp::Campagne.find_by_camp_sys_num_and_camp_num(systeme.to_i,campagne.to_i)
      camps << c
    end
    
    migrer_campagnes(camps,pays,systeme.to_i)   
  end
  
  # author	Alassane
  # desc
  #Cette action déclenche la migration approprement dite de toutes les données de campagnes Pechexp.
  def launch_campagnes
    before_sauvegarde
    bool = sauvegarde
    if bool
       after_sauvegarde('Succès')
       before_launch_campagnes
       pre_launch_campagne
       chemin = APP_CONFIG["rep_batch"]+APP_CONFIG["migration_campagnes_pechexp_batch"]
       system(APP_CONFIG["launcher"]+" "+ chemin)
       redirect_to(:controller => 'progression', :action => 'tracer')
    else
      after_sauvegarde('Echec')
      redirect_to(:controller => 'journal', :action => 'list')
    end
   
  end
  
  def launch_globale

      before_launch_tout    
      pre_launch_tout
      chemin = APP_CONFIG["rep_batch"]+APP_CONFIG["migration_tout_pechexp_batch"]
      system(APP_CONFIG["launcher"]+" "+chemin)
      redirect_to(:controller => 'progression_globale', :action => 'tracer')
        
  end
  
 before_filter :filtre_suppression, :only => [:launch_campagnes, :launch_parametrage, :launch_tout]
 before_filter :filtre_logs_transformations
   
end
