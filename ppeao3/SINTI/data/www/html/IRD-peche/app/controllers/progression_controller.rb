#File name : progression_controller.rb
#Date Created : 11/11/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group

require 'net/sftp'
class ProgressionController < ApplicationController
  
  # author	Alassane
  # desc
  #Cettte m�thode pr�vient le syst�me qu'il n'y a plus de migration en cours.
  #Pour cela il supprime le fichier migration.lock du serveur PostGresql sur lequel on dispose
  #�galement d'un serveur Ftp S�curis�.
  def notify_end_migration
    parametre = BaseSysteme::Parametre.find('migration_cours')    
    parametre.valeur = 0
    parametre.save
  end
  
  def tracer
   flash[:titre] = session['sess_migration'] 
  @image="balingo.jpg"
  end
  
  def filtre_migration_lancee
    if(session['sess_migration_lancee']==0)
      redirect_to(:controller => "journal", :action => "list")
    end
  end
  
  
  
  # author	Alassane
  # desc
  #Cette m�thode r�cup�re le nombre de transformations effectu�es 
  #depuis le lancement de la migration.
  def getNombreTransformationsEffectuees
    nTransfo = 0
    nTransfo = BaseSysteme::Log.find(:all, :conditions => "status = 'end' ").size - (session['sess_dernier_transfo'])
    #puts "Nouvelles transformations : "+nTransfo.to_s
    return nTransfo
  end
  
  # author	Alassane
  # desc
  #Cette m�thode retourne le nombre total de transformations de la migration.
  def getNombreTotalTransformations
    return session['sess_nombre_total_transformations'].to_i
  end
  
  # author	Alassane
  # desc
  #Cette m�thode est invoqu�e � intervalle de temps r�guliers par la page tracer.rhtml.
  #Elle permet de construire la barre de progression. 
  #Pour cela, nous utilisons un tableau html de id "barreProgression" d'une ligne et dont
  #le nombre de colonnes est �gal au nombre total de transformations.
  #Chaque "interval" secondes, on �value le nombre n de nouvelles transformations
  #depuis le lancement de la migration. Ensuite on ajoute dans la table html n cases bleues 
  #le reste reste gris�.
  def tracerBarre
    barre='';
    if(session['sess_migration_lancee']==1)
      largeur_unite_barre = APP_CONFIG["largeur_unite_barre"].to_i
      nombreTransformationsEffectuees = getNombreTransformationsEffectuees
      progression = ((nombreTransformationsEffectuees * 700) / getNombreTotalTransformations).to_i
      barre='';
      barre+='<table id="barreProgression" width="700px">';
      barre+='<tr>';
      barre+='<td class="portionBarreProgression" width="'+progression.to_s+'px">&nbsp;</td>'
      barre+='<td width="'+(700 - progression ).to_s+'px">&nbsp;</td>'
      barre+='</tr>';
      barre+='</table>';
      session['sess_barre'] = barre
      render(:text=>barre)
    else
    render(:text => session['sess_barre'])
    end
    
  end
  
  # author	Alassane
  # desc
  #Cette m�thode est invoqu�e � intervalle de temps r�guliers par la page tracer.rhtml.
  #Elle permet d'afficher l'�tat de la migration. 
  #Pour cela, on effectue diff�rents tests :
  #   *Si le dernier enregistrement de la table des logs de Kettle est � start, 
  #   il y a une transformation en cours.
  #   *Si le dernier enregistrement de la table des logs de Kettle est � stop, 
  #   il y a eu une erreur de transformation, on restaure automatiquement la base.
  #   *Si le dernier est � end et que le nombre de transformations effectu�es est �gal
  #   au nombre de transformations de la migration, alors elle s'est pass�e sans probl�mes et on redirige 
  #   vers le journal.
  def message
    message = '<div>'
    erreur_base = false
    begin
      log = BaseSysteme::Log.find(:first, :order => 'logdate desc')
    rescue
      erreur_base = true
      message+= "Veuillez patienter pendant que le syst�me vous redirige vers le journal d'�v�nements ..."
      message+= '</div>'    
      #render_text ''+message
    end
    if((session['sess_migration_lancee']==1)&&(!erreur_base)&&(log!=nil))
      transformation = log.transname
      if log.status == 'start'
       
      message+='Transformations En Cours : '+transformation+'...<a href="/launcher/cancel">Annuler</a>'
      message+= '</div>'    
      #render_text ''+message
      elsif log.status == 'stop'
        session['sess_migration_lancee']=0
        log(session[:sess_utilisateur].login_utilisateur,session['sess_migration'],"Echec","Lors de la transformation "+transformation+" apr�s "+log.lines_output.to_s)
        session['sess_barre']=''
        message+='Erreurs lors de la migration.<br/>';
        message+='La base de donn�es est sur le point d\' �tre restaur�e.';
        message+="<script>document.location='/restore/doRestore';</script>"        
        notify_end_migration  
        message+= '</div>'    
        #render_text ''+message
      
        
      elsif (log.status == 'end') && (getNombreTransformationsEffectuees==getNombreTotalTransformations)
        session['sess_migration_lancee']=0
        log(session[:sess_utilisateur].login_utilisateur,session['sess_migration'],"Succ�s","")
        session['sess_barre']=''
        message+='Migration termin�e avec succ�s.';
        message+='<script>document.location=\'/journal/list\';</script>' 
        notify_end_migration 
        message+= '</div>'    
        #render_text ''+message     
      end
     
    else
       if (log!=nil)&&(log.status == 'end') && (session['sess_migration_annulee']==1)
        notify_end_migration        
        message+="<script>document.location='/restore/doRestore';</script>"
        message+= '</div>'    
        #render_text ''+message
       end
       
       message+= "Veuillez patienter ..."
       
      
    end
    render_text ''+message      
    message+= '</div>'  
    
  end

#before_filter :filtre_migration_lancee
end
