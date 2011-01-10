class CritereRecherche
  attr_writer :date_debut, :date_fin, :utilisateur, :action, :statut
  attr_reader :date_debut, :date_fin, :utilisateur, :action, :statut
  
  public 
  
  def to_array
  
    tab = Array.new
    tab["date_debut"] = date_debut
    tab["date_fin"] = date_fin
    tab["utilisateur"] = utilisateur
    tab["action"] = action
    tab["statut"] = statut
    
    return tab
    
  end
  
end