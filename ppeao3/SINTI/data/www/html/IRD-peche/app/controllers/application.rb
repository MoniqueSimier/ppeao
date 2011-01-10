#File name : application_controller.rb
#Date Created : 15/11/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group

#Ce controleur est le controleur de base de l'application.

class ApplicationController < ActionController::Base
  
  #Cette méthode sert à déterminer le menu à afficher selon la présence
  #de session ou pas.
  def get_menu
    @menu = 'connexion'
    user = session[:sess_utilisateur]
    if user != nil
      @menu = 'menu'
    end
  end

  #Cette méthode sert à déterminer le layout à utiliser. 
  #Pour le moment, il n'y en a qu'un seul.
  def getLayout
   "index"
  end

  #Cette méthode fixe l'encodage des caractèrees de l'ensemble des pages de l'application.
  def check_header
    @headers["Content-Type"] = "text/html; charset=iso-8859-1"
  end
  
  def log(user,action,statut,detail)
    evenement = BaseSysteme::Journal.new
    evenement.date_log = Time.now
    evenement.action_log = action
    evenement.statut = statut
    evenement.login_utilisateur = user
    evenement.detail = detail
    evenement.adresse_ip = request.remote_ip()
    evenement.save
  end
  
  def fermer_connexions
	Cible::Modele.connection().disconnect!()
#    Cible::Activite.connection().disconnect!()
#    Cible::DebarquementAMigrer.connection().disconnect!()
#    Cible::ActiviteAMigrer.connection().disconnect!()
#    Cible::CampagneAMigrer.connection().disconnect!()
#    Cible::Debarquement.connection().disconnect!()
#    Cible::Log.connection().disconnect!()
    
    BaseSysteme::Modele.connection().disconnect!()
    Referentiel::Modele.connection().disconnect!()
    Pechexp::Modele.connection().disconnect!()
    Pechart::Modele.connection().disconnect!()
    
  end
  
   
  # author	Alassane
  # desc
  #Cettte méthode prévient le système qu'il n'y a plus de migration en cours.
  #Pour cela il supprime le fichier migration.lock du serveur PostGresql sur lequel on dispose
  #également d'un serveur Ftp Sécurisé.
  def notify_end_migration
    parametre = BaseSysteme::Parametre.find('migration_cours')    
    parametre.valeur = 0
    parametre.save
  end
  
before_filter :check_header
before_filter :get_menu

after_filter :fermer_connexions

layout :getLayout



end
