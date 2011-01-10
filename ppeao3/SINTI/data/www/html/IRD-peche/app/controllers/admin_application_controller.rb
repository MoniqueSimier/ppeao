#File name : admin_application_controller.rb
#Date Created : 15/11/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group

require 'net/sftp'

#Ce controleur est la classe m�re de l'ensemble des controleurs dont l'acc�s est 
#r�serv� aux administrateurs.

class AdminApplicationController < ApplicationController

#Cette m�thode v�rifie si l'utilisateur est administrateur afin de lui donner acc�s � l'application.
def check_admin
    user = session[:sess_utilisateur]
    if user != nil
      if (user.groupe.libelle != 'Admin')
        redirect_to :controller => 'bienvenue'
        flash[:notice] = "D�sol�, vous ne disposez pas d'un compte Administrateur."
      end
    end
  end

#Cette m�thode v�rifie si l'utilisateur s'est bien authentifi�.
  def check_connected
    user = session[:sess_utilisateur]
    
    if (user == nil)
      redirect_to :controller => 'bienvenue'
      flash[:notice] = "Veuillez vous connecter SVP!!!"
    end
  end

def filtre_logs_transformations
  if !BaseSysteme::Log.table_exists?
    BaseSysteme::Modele.connection().rename_table("nouvo_sys_logs_transformations","sys_logs_transformations")
  end
end 

before_filter :check_connected, :except=>'doRestore'
before_filter :check_admin, :except=>'doRestore'

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
 
 # author	Alassane
  # desc
  #Cette m�thode permet de cr�er une base postgresql.
  def creer_base
    user = APP_CONFIG["postgres_user"]
    password = APP_CONFIG["postgres_password"]
    base = APP_CONFIG["postgres_cible_database"]
    host = APP_CONFIG["postgres_host"]
    port = APP_CONFIG["postgres_port"]
    
    commande = "createdb "
    commande += " -E latin9"
    commande += " -h "+host
    commande += " -p "+port.to_s
    commande += " -U "+user
    commande += " -T template0"
  
    commande += " "+base
  
    return system(" "+commande)
  
  end


end
  