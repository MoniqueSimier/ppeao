#File name : migration_pechart_controller.rb
#Date Created : 17/11/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group


#Ce controleur se charge de tout ce qui concerne la migration Pechart.

class MigrationPechartController < MigrationController
    
  # author	Alassane
  # desc
  #Cette action récupère toutes les informations qu'il faut pour afficher la
  #page de la migration par période.
  #
  
  def enquetes
      @image="pechart.jpg"
      @pays_source = Pechart::Pays.find(:all, :order => 'nompays asc') 
      
      @systemes = Array.new
      @secteurs = Array.new
      @agglomerations = Array.new
      @agglomerations_choisies = Array.new
      
      
      @periodes = Array.new
      
      @etat_bouton_ok_systeme = 'disabled'
      @etat_bouton_ok_secteur = 'disabled'
      @etat_bouton_ok_agglomeration = 'disabled'
      
      if params[:pays]!= nil
        pays_selectionne = Pechart::Pays.find(params[:pays])
        
        if pays_selectionne != nil
         @systemes = pays_selectionne.systemes
         if @systemes.size >0
          @etat_bouton_ok_systeme = ''
         end
        end
      end
      
      if params[:systeme]!= nil
        systeme_selectionne = Pechart::Systeme.find(params[:systeme])
        
        if systeme_selectionne != nil
         @secteurs = systeme_selectionne.secteurs
        end
      end
      
      
      if params[:secteurs]!= nil   
        @etat_bouton_ok_secteur = ''    
         
        secteurs_selectionnes = Pechart::Secteur.find(params[:secteurs])
        if secteurs_selectionnes != nil
         for secteur in secteurs_selectionnes
          for agglo in secteur.agglomerations
            @agglomerations <<  agglo
          end            
         end        
        end
      end
      
      #TODO Tri temporel des périodes d'enquete des agglos chiosies
      if params[:agglomerations]!= nil     
      @etat_bouton_ok_agglomeration = ''   
        #@agglomerations_choisies = Pechart::Agglomeration.find(params[:agglomerations])
        sql = "select periodeenquete.*, agglomeration.idagglo from periodeenquete  inner join agglomeration "
        sql = sql + "on periodeenquete.agglo = agglomeration.nomagglo "
        sql = sql + "where agglomeration.idagglo in ( "
        where = ""
        for id in params[:agglomerations]
          where = where + id+", "
        end
        where = where + "0 "
        sql = sql+where+") order by "
        sql = sql + "periodeenquete.datedeb desc "
        @periodes = Pechart::PeriodeEnquete.find_by_sql(sql)
      end
      
      flash[:titre] = "Migration des <br/> <br/> enquêtes Pechart"
      
  end
  
  # author	Alassane
  # desc
  #Cette action redirige vers la page de migration de l'ensemble des données Pechart.
  def tout
       @image="pechart.jpg"
       flash[:titre] = "Migration de <br/> <br/> toutes les données Pechart"
  end
  
  # author	Alassane
  # desc
  #Cette action redirige vers la page de migration de l'ensemble des données de paramétrage Pechart.
  def parametrage
      @image="pechart.jpg"
      flash[:titre] = "Migration des <br/> <br/> données de paramétrage Pechart"
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
  #Cette méthode est invoquée avant le déclenchement de la migration de toutes les données Pechart.
  #Elle se charge essentiellement d'enregistrer l'événement dans le journal. Mais aussi, elle stocke 
  #dans une variable de session le nombre total de transformations, le nom de la migration et un flag
  #indiquant que la migration est lancée.
  def before_launch_tout
    notify_migration
    log(session[:sess_utilisateur].login_utilisateur,"Migration tout Pechart","Démarrage",params[:commentaires])        
    session['sess_migration'] = 'Migration tout Pechart'
    session['sess_dernier_transfo'] = BaseSysteme::Log.find(:all, :conditions => "status = 'end' ").size
    nombre_base_pays = BaseSysteme::SysBasePays.count
    session['sess_nombre_total_transformations'] = APP_CONFIG["nb_trans_migration_parametrage_pechart"].to_i + 1 
    session['sess_nombre_total_transformations'] =  session['sess_nombre_total_transformations'] + ( nombre_base_pays * (APP_CONFIG["nb_trans_migration_enquetes_pechart"].to_i + 3) )
    session['sess_migration_lancee']=1
  end
  
  # author	Alassane
  # desc
  #Cette méthode est invoquée juste avant le déclenchement de la migration de toutes les données Pechart.
  #Elle se charge essentiellement d'enregistrer dans la base système l'ensemble des débarquements et des activités à
  #migrer.
  def pre_launch_tout
    Cible::DebarquementAMigrer.delete_all
    Cible::ActiviteAMigrer.delete_all
    bool = true
    pays = Pechart::Pays.find(:all)
    for p in pays      
      systemes = p.systemes
      for systeme in systemes
        if(connexion(pays.id,systeme.id))
         secteurs = systeme.secteurs
         for secteur in secteurs
          agglomerations = secteur.agglomerations
          for agglomeration in agglomerations
              debarquements = agglomeration.debarquements      
              migrer_debarquements(debarquements,p.id,systeme.id,secteur.id,agglomeration.id)              
              activites = agglomeration.activites
              migrer_activites(activites,p.id,systeme.id,secteur.id,agglomeration.id)              
          end
        end
       else
         bool = false
       end
      end
    end
    return bool
  end
  
  
  # author	Alassane
  # desc
  #Cette méthode déclenche la migration de toutes les données Pechart.
  #Elle commence par faire une sauvegarde. Si elle réussit, elle lance le script de la migration 
  #et redirige vers la barre de progression.
  #Sinon elle redirige vers le journal et précise que la sauvegarde a échoué.
  def launch_tout
    before_sauvegarde
    bool = sauvegarde
    if bool
     after_sauvegarde('Succés')
     before_launch_tout
     #if(pre_launch_tout)
      chemin = APP_CONFIG["rep_batch"]+APP_CONFIG["migration_tout_pechart_batch"]
      system(APP_CONFIG["launcher"]+" "+ chemin)
      redirect_to(:controller => 'progression', :action => 'tracer')
     #else
      #log(session[:sess_utilisateur].login_utilisateur,"Migration enquêtetes Pechart","Demarrage","Base inexistante")
      #redirect_to(:controller => 'journal', :action => 'list') 
     #end
    else
    after_sauvegarde('Echec')
    redirect_to(:controller => 'journal', :action => 'list')
    end
  end
  
  # author	Alassane
  # desc
  #Cette méthode est invoquée avant le déclenchement de la migration de toutes les données de paramétrage Pechart.
  #Elle se charge essentiellement d'enregistrer l'événement dans le journal.
  def before_launch_parametrage
    notify_migration
    log(session[:sess_utilisateur].login_utilisateur,"Migration paramètres Pechart","Démarrage",params[:commentaires])   
    
    
    session['sess_migration'] = "Migration paramètres Pechart"
    session['sess_dernier_transfo'] = BaseSysteme::Log.find(:all, :conditions => "status = 'end' ").size
    session['sess_nombre_total_transformations'] = APP_CONFIG["nb_trans_migration_parametrage_pechart"].to_i
    session['sess_migration_lancee']=1
  end
  
  # author	Alassane
  # desc
  #Cette action déclenche la migration approprement dite de toutes les données de paramétrage Pechart.
  def launch_parametrage
      before_sauvegarde
      bool = sauvegarde
      if bool
        after_sauvegarde('Succès')
        before_launch_parametrage
        chemin = APP_CONFIG["rep_batch"]+APP_CONFIG["migration_parametrage_pechart_batch"]
        system(APP_CONFIG["launcher"]+" "+ chemin)
        redirect_to(:controller => 'progression', :action => 'tracer')
      else
        after_sauvegarde('Echec')
        redirect_to(:controller => 'journal', :action => 'list')
      end
    
  end
  
  
  
  # author	Alassane
  # desc
  #Cette méthode est invoquée avant le déclenchement de la migration de toutes les données d'enquêtes Pechart.
  #Elle se charge essentiellement d'enregistrer l'événement dans le journal.
  def before_launch_enquetes
    notify_migration
    log(session[:sess_utilisateur].login_utilisateur,"Migration enquêtes Pechart","Démarrage",params[:commentaires])
    
    session['sess_migration'] = "Migration enquêtes Pechart"
    session['sess_dernier_transfo'] = BaseSysteme::Log.find(:all, :conditions => "status = 'end' ").size
    session['sess_nombre_total_transformations'] = APP_CONFIG["nb_trans_migration_enquetes_pechart"].to_i + 1
    session['sess_migration_lancee']=1
    
  end
  
  # author	Alassane
  # desc
  #Cette méthode est invoquée avant le déclenchement de la migration de toutes les données d'enquêtes Pechart.
  #Elle se charge essentiellement d'enregistrer les débarquements et les activités à migrer.
  def pre_launch_enquetes
    bool = true
    Cible::DebarquementAMigrer.delete_all
    Cible::ActiviteAMigrer.delete_all
#    
    #Enregistrement des débarquements
    Cible::PeriodeAMigrer.delete_all
    
    systeme = params[:systeme].to_i
    pays = params[:pays]
    choix_base_pays(pays,systeme);
    
    #Mise à jour de la base pays sur le référentiel
    base_en_cours = Referentiel::Database.find_by_name(APP_CONFIG['postgres_pays_connection'])
    base_en_cours.database_name = session['sess_base_pays']
    base_en_cours.save
    
    donnees_a_migrer = params[:periodes]
    for donnee in params[:periodes]
      tab = donnee.split(',')
      id_agglo = tab[0].to_i
      date_debut = tab[1]
      date_fin = tab[2]
      agglo = Pechart::Agglomeration.find(id_agglo)
      secteur = agglo.secteur
      
      
      a_migrer = Cible::PeriodeAMigrer.new 
      
      a_migrer.pays_id = pays
      a_migrer.systeme_id = systeme
      a_migrer.secteur_id = secteur.id
      a_migrer.agglomeration_id = id_agglo
      a_migrer.date_debut = date_debut
      a_migrer.date_fin = date_fin
      a_migrer.base_pays = session['sess_base_pays']
      
      #      agglo = Pechart::Agglomeration.find(id_agglo)
#      secteur = agglo.secteur
#      systeme = secteur.systeme
#      pays = systeme.pays
#      
#      if(connexion(pays.id,systeme.id))
#        debarquements = agglo.debarquements_in_interval(date_debut,date_fin)
#        migrer_debarquements(debarquements,pays.id,systeme.id,secteur.id,agglo.id)
#        activites = agglo.activites_in_interval(date_debut,date_fin)
#        migrer_activites(activites,pays.id,systeme.id,secteur.id,agglo.id)
#      else
#        bool = false
#      end     
      a_migrer.save
    end
    return bool
  end
  
  # author	Alassane
  # desc
  #Cette action déclenche la migration approprement dite de toutes les données d'enquêtes Pechart.
  def launch_enquetes
    before_sauvegarde
    bool = sauvegarde
    if bool
      after_sauvegarde('Succès')
      before_launch_enquetes
      if(pre_launch_enquetes)
        chemin = APP_CONFIG["rep_batch"]+APP_CONFIG["migration_enquetes_pechart_batch"]
        system(APP_CONFIG["launcher"]+" "+ chemin)
        redirect_to(:controller => 'progression', :action => 'tracer')
      else
        log(session[:sess_utilisateur].login_utilisateur,"Migration enquêtetes Pechart","Demarrage","Base inexistante")
        redirect_to(:controller => 'journal', :action => 'list')
      end    
      
    else
      after_sauvegarde('Echec')
      redirect_to(:controller => 'journal', :action => 'list')
    end
    
  end
  
 def launch_parametrage_globale
    
        before_launch_parametrage
        chemin = APP_CONFIG["rep_batch"]+APP_CONFIG["migration_parametrage_pechart_batch"]
        system(APP_CONFIG["launcher"]+" "+ chemin)
        redirect_to(:controller => 'progression_globale', :action => 'tracer')
     
   
 end
 
  # author	Alassane
  # desc
  #Cette méthode est invoquée avant le déclenchement de la migration de toutes les données d'enquêtes Pechart.
  #Elle se charge essentiellement d'enregistrer l'événement dans le journal.
  def before_launch_pays
    notify_migration
    log(session[:sess_utilisateur].login_utilisateur,"Migration enquêtes "+session['sess_base_pays_globale'][session['sess_pays_en_cours']],"Démarrage", '')
    session['sess_migration'] = "Migration enquêtes "+session['sess_base_pays_globale'][session['sess_pays_en_cours']]
    session['sess_dernier_transfo'] = BaseSysteme::Log.find(:all, :conditions => "status = 'end' ").size
    session['sess_nombre_total_transformations'] = APP_CONFIG["nb_trans_migration_pays_pechart"].to_i
    session['sess_migration_lancee']=1
    
  end
 
 
 def launch_pays
 
      base_en_cours = Referentiel::Database.find_by_name(APP_CONFIG['postgres_pays_connection'])
      base_en_cours.database_name = session['sess_base_pays_globale'][session['sess_pays_en_cours']]
      base_en_cours.save   
      before_launch_pays
      chemin = APP_CONFIG["rep_batch"]+APP_CONFIG["migration_pays_batch"]
      system(APP_CONFIG["launcher"]+" "+ chemin)
      redirect_to(:controller => 'progression_globale', :action => 'tracer')

 end 
  
before_filter :filtre_suppression, :only => [:launch_enquetes, :launch_parametrage, :launch_tout] 
before_filter :filtre_logs_transformations
   
end
