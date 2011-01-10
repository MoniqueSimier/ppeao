#File name : journal_controller.rb
#Date Created : 20/11/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group

#Ce controleur se charge de tous les traitements concernant le journal de l'application.
class JournalController < AdminApplicationController 
  
  
  # author	Alassane
  # desc
  #Cette méthode recherche l'ensemble des événements du journal répondant aux critères de recherche.
  #Pour cela, on récupère l'objet CritereRecherche stocké dans la session et encapsulant l'ensemble
  #des critères de recherche.
  #
  #On construit dynamiquement une requête selon le contenu de cet objet.
  #On récupère l'ensemble des événements renvoyés par la requête.
  def list
    @image="balingo.jpg"
    @critere = CritereRecherche.new
    @critere = session[:sess_criteres] unless session[:sess_criteres]==nil
    @journaux = Array.new
    
    conditions = '1 = 1 '
    
    if (@critere.utilisateur!=nil) && (@critere.utilisateur!='')
      conditions+= 'and login_utilisateur = :utilisateur '
    end
    
    if(@critere.action!=nil) && (@critere.action!='')
      conditions+= 'and action_log = :action '
    end
    
    if(@critere.statut!=nil) && (@critere.statut!='')
      conditions+= 'and statut = :statut '
    end
    
    if((@critere.date_debut !=nil) &&(@critere.date_fin !=nil)&&(@critere.date_fin !='')&&(@critere.date_debut !=''))
      conditions+= "and (to_date(date_log,'yyyy-mm-dd') between :date_debut and :date_fin) "
    end
    
    tab = Hash.new
    
    if(@critere.date_debut !=nil)
      tab[:date_debut] = @critere.date_debut
    end 
    
    if(@critere.date_fin !=nil)
      tab[:date_fin] = @critere.date_fin
    end
    
    if(@critere.utilisateur !=nil)
      tab[:utilisateur] = @critere.utilisateur
    end
    
    if(@critere.action !=nil)
      tab[:action] = @critere.action
    end
    
    if(@critere.statut !=nil)
      tab[:statut] = @critere.statut
    end
    
    @journaux = BaseSysteme::Journal.find(:all, :conditions => [conditions,tab], :order => 'id DESC')
    
    @statuts = [
                 "",
                 "Démarrage", 
                 "Succès", 
                 "Echec", 
                 "Annulé", 
                 "Initialisé"
                ]
    
    @actions = [
                 "",
                 "Migration globale",
                 "Migration référentiel",
                 "Migration tout Pechart",
                 "Migration paramètres Pechart",
                 "Migration enquêtes Pechart",
                 "Migration tout Pechexp",
                 "Migration paramètres Pechexp",
                 "Migration campagnes Pechexp",
                 "Sauvegarde",
                 "Restauration",
                 "Journal",
                 "Connexion",
                 "Déconnexion"
               ]
    
  flash[:titre] = "Journal d' événements"
  end
  
  # author	Alassane
  # desc
  #Cette méthode se charge de vider les critères de recherche de la session.
  def annuler
    session[:sess_criteres]=nil
    redirect_to :action=>'list'
  end
  
  # author	Alassane
  # desc
  #Cette méthode permet de remplir l'objet CritereRecherche et de le stocker dans la session.
  def search
  
    critere = CritereRecherche.new
    
#    critere.date_debut =
#    Date.new(
#    params[:critere]['date_debut(1i)'].to_i,
#    params[:critere]['date_debut(2i)'].to_i,
#    params[:critere]['date_debut(3i)'].to_i)
    
    critere.date_debut= params[:critere][:date_debut]
    critere.date_fin= params[:critere][:date_fin]
    
#    critere.date_fin =
#    Date.new(
#    params[:critere]['date_fin(1i)'].to_i,
#    params[:critere]['date_fin(2i)'].to_i,
#    params[:critere]['date_fin(3i)'].to_i)
    
    
    critere.utilisateur= params[:critere][:utilisateur]
    critere.statut= params[:critere][:statut]
    critere.action= params[:critere][:action]
    
    session[:sess_criteres] = critere
    
    redirect_to :action=>'list'
  end
  
  # author	Alassane
  # desc
  #Cette méthode permet d'exporter une liste d'événements  répondant 
  #aux critères de recherche sous un format csv.
  def exporter
    list
    s = ''
    for journal in @journaux
      ligne =  journal.login_utilisateur + "\t"
      ligne += journal.adresse_ip + "\t"
      ligne += journal.action_log + "\t"
      ligne += journal.statut + "\t"
      ligne += "\n"
      s+=ligne
    end    
    send_data(s, :filename => 'export.csv', :type => 'application/txt', :disposition => 'attachment')
  end
  
  # author	Alassane
  # desc
  #Cette action permet de vider le journal en supposant que ce dernier a déjà été exporté.
  def initialiser
    
    BaseSysteme::Journal.delete_all
    log(session[:sess_utilisateur].login_utilisateur,"Journal","Initialisé","")    
    redirect_to :action=>'list'
    
    
  end
  
end