#File name : migration_globale_controller.rb
#Date Created : 16/11/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group

#Ce controleur se charge de tout ce qui concerne la migration globale.
class MigrationGlobaleController < MigrationController
  # author	Anna
  # desc
  # d�claration d'un tableau qui va contenir les diff�rentes migrations � chaque �tape
  

  # author	Alassane
  # desc
  #Il s'agit de la seule action du controleur et c'est celle qui effectue la restauration de donn�es.
  #Pour cela, on se d�connecte de la base. 
  #Ensuite on supprime la base pour ensuite la recr�er avant de rapatrier les informations.
  
  def purger
    #puts "Entr�"
    log(session[:sess_utilisateur].login_utilisateur,"R�initialisation Base ","D�marrage","")
    bool_creation = false
    bool_suppression = false
    bool = false    
    Cible::Modele.connection().disconnect!()

    bool_suppression = supprimer_base
    if(bool_suppression)
      bool_creation = creer_base
      if(bool_creation)
    
      repertoire = APP_CONFIG["rep_back_up"]
        
      #nom = APP_CONFIG["base_init"]
      
      nom = "init.backup"
      
      user = APP_CONFIG["postgres_user"]
      password = APP_CONFIG["postgres_password"]
      base = APP_CONFIG["postgres_cible_database"]
      host = APP_CONFIG["postgres_host"]
      port = APP_CONFIG["postgres_port"]
      
      commande = "pg_restore "
      commande += " -h "+host
      commande += " -p "+port.to_s
      commande += " -U "+user
      commande += " -v "+repertoire+"/"+nom
      commande += " -d "+base
      
      bool = system(" "+commande) 
      if(bool==false)
        log(session[:sess_utilisateur].login_utilisateur,"Restauration","Echec","Lors de la restauration des donn�es")
      else
         log(session[:sess_utilisateur].login_utilisateur,"Restauration","Succ�s","")     
      end
    else
      log(session[:sess_utilisateur].login_utilisateur,"Restauration","Echec","Lors de la recr�ation de la base")
    end
    else
      log(session[:sess_utilisateur].login_utilisateur,"Suppression","Echec","V�rifiez qu'il n'y a pas d'utilisateurs connect�s.")
    end
    return bool
 end
  
  
  # author	Alassane
  # desc
  #Il s'agit de l'action invoqu�e par d�faut.
   
  def index
    flash[:titre] = "Migration Globale"
    @image="balingo.jpg"
  end
  
  # author	Alassane
  # desc
  #Cette m�thode se charge de remplir les debarquements, les activit� et les campagnes � migrer
  #quelque soit le pays, le syst�me, ...
  def pre_launch_tout
    bool = true  
    bool = purger
    
    return bool
  end
  
  # author	Alassane
  # desc
  #Cette m�thode se charge d'effectuer tous les traitements n�cessaires avant le d�clenchement
  #effectif de la migration globale. En l'occurrence, elle enregistre l'�v�nement dans le journal ainsi que
  #le nom de lamigration lanc�e dans la session. ce dernier permettra de tracer la fin de la migration 
  #dans le journal.
  def before_launch
  
    step=0
    
    controleurs=['migration_referentiel',
                'migration_pechexp',
                'migration_pechart',
                'migration_pechart'             
               ]    

    actions= ['launch_globale',
               'launch_globale',
               'launch_parametrage_globale',
               'launch_pays'             
               ]
               
    
    
    notify_migration
    
    log(session[:sess_utilisateur].login_utilisateur,"Migration globale","D�marrage",params[:commentaires])    
    @base_pays = BaseSysteme::SysBasePays.find(:all)
    
    pays = Array.new
    for p in @base_pays
      pays << p.nom_base
    end
      
    session['sess_migration'] = "Migration globale"
    
    #La variable de session sess_dernier_transfo permet de retrouver le nombre de transformations dans la base
    #avant le d�but de la migration. Elle servira � connaitre le nombre de transformations d�j� trait�es
    #par la migration en cours.
    
    session['sess_dernier_transfo'] = 0
    
    session['sess_step']=step
    session['sess_controleurs']=controleurs
    session['sess_actions']=actions
    session['sess_pays_en_cours']=0
    session['sess_base_pays_globale']= pays
    
  end
  
   # author	Alassane
  # desc 
  #Cette m�thode effectue tous les traitements necessaires avant la sauvegarde.
  #Il s'agit principalement de l'enregistrement de l'�v�nement dans le journal.
  def before_sauvegarde
    log(session[:sess_utilisateur].login_utilisateur,"Sauvegarde","D�marrage","")
  end
  
  # author	Alassane
  # desc
  def after_sauvegarde(etat)
  
    log(session[:sess_utilisateur].login_utilisateur,"Sauvegarde",etat,"")
    
  end
  
  # author	Alassane
  # desc
  #Cette m�thode est commune � tous les controleurs de migration.
  #De mani�re g�n�rale, elle lance le script de la migration et redirige vers la barre de 
  #progression.
  def launch
      if (session['sess_step']==nil)
      @step=0
      elsif (session['sess_step']==0)        
      @step = 1
      else
      @step = session['sess_step']
      end
      @controleurs=['migration_referentiel',
                'migration_pechexp',
                'migration_pechart',
                'migration_pechart'             
                    ]    

      @actions= ['launch_globale',
               'launch_globale',
               'launch_parametrage_globale',
               'launch_pays'             
                ]
       if(@step==0)
           
        before_sauvegarde
        bool = sauvegarde
        if(bool)
          after_sauvegarde('Succ�s')  
           
          if pre_launch_tout
            before_launch
            controleur_a_executer=@controleurs[session['sess_step']]
            action_a_executer=@actions[session['sess_step']]
            redirect_to(:controller => controleur_a_executer, :action => action_a_executer)
          else
            redirect_to(:controller => 'journal', :action => 'list')
          end
        else   
          after_sauvegarde('Echec')
          redirect_to(:controller => 'journal', :action => 'list') 
        end
      else
        if session['sess_step']< 3
          session['sess_step']+= 1
          controleur_a_executer=@controleurs[session['sess_step']]
          action_a_executer=@actions[session['sess_step']]
          redirect_to(:controller => controleur_a_executer, :action => action_a_executer)
        elsif session['sess_step']== 3
         if session['sess_pays_en_cours'] < session['sess_base_pays_globale'].size-1
            session['sess_pays_en_cours']+=1
            controleur_a_executer=@controleurs[session['sess_step']]
            action_a_executer=@actions[session['sess_step']]
            redirect_to(:controller => controleur_a_executer, :action => action_a_executer)
         else
            notify_end_migration
            session['sess_step'] = nil
            session['sess_pays_en_cours'] = 0
            session['sess_migration_lancee']=0
            log(session[:sess_utilisateur].login_utilisateur,"Migration globale","Succ�s","")
            redirect_to(:controller => 'journal', :action => 'list') 
         end  
        end 
        
       end
       
        
  end
#before_filter :filtre_logs_transformations
end


