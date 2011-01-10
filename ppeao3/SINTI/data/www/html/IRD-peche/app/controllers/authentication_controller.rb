#File name : authentication_application_controller.rb
#Date Created : 15/11/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group

#Ce controleur se charge de l'authentification du système.
class AuthenticationController < ApplicationController

# author	Alassane
# desc	Cette fonction est un filtre vérifiant si l'utilisateur est déjà connecté.
def already_connected
  if (session[:sess_utilisateur] != nil)
    redirect_to :controller => 'bienvenue'
  end
end

before_filter :already_connected, :only => 'connect'


# author	Alassane
# desc	Cette fonction est invoquée lorsque l'utilisateur clique sur le bouton connexion.
def doConnexion
    userConnected = Utilisateur.find_by_login_utilisateur_and_mot_passe(params[:login],params[:password])
    if (userConnected!=nil)
      session[:sess_utilisateur] = userConnected 
      
   
      
      log(session[:sess_utilisateur].login_utilisateur,"Connexion","Succès","")
      
      
      redirect_to :controller => 'journal', :action => 'list'
    else
    
      log("","Connexion","Echec",params[:login])      
      flash[:notice] = "Erreur d'authentification, verifiez  vos parametres  de connexion."
      redirect_to :controller => 'bienvenue'
    end
 end
 
# author	Alassane
# desc	Cette fonction est invoquée lorsque l'utilisateur se déconnecte.
 def deconnexion
  log(session[:sess_utilisateur].login_utilisateur,"Déconnexion","Succès","") 
  reset_session 
  redirect_to :controller => 'bienvenue'
 end
end
