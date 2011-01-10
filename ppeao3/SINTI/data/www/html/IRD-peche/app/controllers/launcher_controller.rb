#File name : launcher_controller.rb
#Date Created :4/12/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group

#Ce controleur se charge de tous les traitements concernant le journal de l'application.
class LauncherController < AdminApplicationController
  
  def inject_error
    bases = Referentiel::Database.find(:all)
    for base in bases
      base.host_name=""
      base.save
    end
  end
  
  def notify_end_migration
    parametre = BaseSysteme::Parametre.find('migration_cours')    
    parametre.valeur = 0
    parametre.save
  end
  
  def cancel    
    session['sess_migration_lancee']=0
    session['sess_migration_annulee']=1
    notify_end_migration
    inject_error
    log(session[:sess_utilisateur].login_utilisateur,session['sess_migration'],"Annulé","")
    fermer_connexions
    redirect_to(:controller => 'progression', :action => 'tracer')
  end
end