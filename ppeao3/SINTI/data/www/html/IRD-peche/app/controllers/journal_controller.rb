#File name : journal_controller.rb
#Date Created : 20/11/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group

#Ce controleur se charge de tous les traitements concernant le journal de l'application.
class JournalController < AdminApplicationController 
  
  
  # author	Alassane
  # desc
  #Cette m�thode recherche l'ensemble des �v�nements du journal r�pondant aux crit�res de recherche.
  #Pour cela, on r�cup�re l'objet CritereRecherche stock� dans la session et encapsulant l'ensemble
  #des crit�res de recherche.
  #
  #On construit dynamiquement une requ�te selon le contenu de cet objet.
  #On r�cup�re l'ensemble des �v�nements renvoy�s par la requ�te.
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
                 "D�marrage", 
                 "Succ�s", 
                 "Echec", 
                 "Annul�", 
                 "Initialis�"
                ]
    
    @actions = [
                 "",
                 "Migration globale",
                 "Migration r�f�rentiel",
                 "Migration tout Pechart",
                 "Migration param�tres Pechart",
                 "Migration enqu�tes Pechart",
                 "Migration tout Pechexp",
                 "Migration param�tres Pechexp",
                 "Migration campagnes Pechexp",
                 "Sauvegarde",
                 "Restauration",
                 "Journal",
                 "Connexion",
                 "D�connexion"
               ]
    
  flash[:titre] = "Journal d' �v�nements"
  end
  
  # author	Alassane
  # desc
  #Cette m�thode se charge de vider les crit�res de recherche de la session.
  def annuler
    session[:sess_criteres]=nil
    redirect_to :action=>'list'
  end
  
  # author	Alassane
  # desc
  #Cette m�thode permet de remplir l'objet CritereRecherche et de le stocker dans la session.
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
  #Cette m�thode permet d'exporter une liste d'�v�nements  r�pondant 
  #aux crit�res de recherche sous un format csv.
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
  #Cette action permet de vider le journal en supposant que ce dernier a d�j� �t� export�.
  def initialiser
    
    BaseSysteme::Journal.delete_all
    log(session[:sess_utilisateur].login_utilisateur,"Journal","Initialis�","")    
    redirect_to :action=>'list'
    
    
  end
  
end