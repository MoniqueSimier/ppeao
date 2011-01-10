#File name : restore_controller.rb
#Date Created : 18/11/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group

#Ce controleur g�re la restauration de donn�es.
class RestoreController < AdminApplicationController
  
  private
  
  # author	Alassane
  # desc
  #Cette m�thode permet de retrouver la derni�re sauvegarde effectu�e.
  #Pour cela, on proc�de � un tri classique.
  def file_to_restore
    repertoire = APP_CONFIG["rep_back_up"]
    fichiers = Array.new
    
    Find.find(repertoire) do |path|
      if (File.basename(path)!=File.basename(repertoire))
      fichiers << File.basename(path)    
      end
    end   
    
    tris = fichiers.sort {|a,b| File.new(repertoire+"/"+a).mtime <=> File.new(repertoire+"/"+b).mtime}
    
    return tris.last
  end
  
  # author	Alassane
  # desc
  #Cette m�thode permet de supprimer une base PostGesql.
  def supprimer_base
  
    user = APP_CONFIG["postgres_user"]
    password = APP_CONFIG["postgres_password"]
    base = APP_CONFIG["postgres_cible_database"]
    host = APP_CONFIG["postgres_host"]
    port = APP_CONFIG["postgres_port"]
    
    commande = "dropdb "
    commande += " -h "+host
    commande += " -p "+port.to_s
    commande += " -U "+user
    
    commande += " "+base
    
    return system(" "+commande)  
  end
  
  

  public
  # author	Alassane
  # desc
  #Il s'agit de la seule action du controleur et c'est celle qui effectue la restauration de donn�es.
  #Pour cela, on se d�connecte de la base. 
  #Ensuite on supprime la base pour ensuite la recr�er avant de rapatrier les informations.
  def doRestore
    #puts "Entr�"
    log(session[:sess_utilisateur].login_utilisateur,"Restauration","D�marrage","")
    bool_creation = false
    bool_suppression = false
    bool = false    
    Cible::Modele.connection().disconnect!()
#    Cible::Activite.connection().disconnect!()
#    Cible::DebarquementAMigrer.connection().disconnect!()
#    Cible::ActiviteAMigrer.connection().disconnect!()
#    Cible::CampagneAMigrer.connection().disconnect!()
#    Cible::Debarquement.connection().disconnect!()
#    Cible::Log.connection().disconnect!()
    bool_suppression = supprimer_base
    if(bool_suppression)
      bool_creation = creer_base
      if(bool_creation)
    
      repertoire = APP_CONFIG["rep_back_up"]
        
      nom = file_to_restore
      
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
      
      if bool 
        log(session[:sess_utilisateur].login_utilisateur,"Restauration","Succ�s","")
        redirect_to :controller => 'journal', :action => 'list'
      else
      log(session[:sess_utilisateur].login_utilisateur,"Restauration","Echec","Lors de la restauration des donn�es.")
      redirect_to :controller => 'journal', :action => 'list'
      end 
    else
      log(session[:sess_utilisateur].login_utilisateur,"Restauration","Echec","Erreur lors de la reg�n�ration de la base cible.")    
      redirect_to :controller => 'journal', :action => 'list' 
    end
    else
      log(session[:sess_utilisateur].login_utilisateur,"Restauration","Echec","Erreur lors de la suppression de la base cible. V�rifiez qu'il n'y a pas d'utilisateurs connect�s.")
      redirect_to :controller => 'journal', :action => 'list'
    end
    
    
    
    return bool  
  end
  
  after_filter :fermer_connexions
end
