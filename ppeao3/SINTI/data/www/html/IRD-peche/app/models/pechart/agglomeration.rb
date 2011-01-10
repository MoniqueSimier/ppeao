class Pechart::Agglomeration < Pechart::Modele
  set_table_name 'agglomeration'
  set_primary_key 'idagglo'
  
  has_many :debarquements,
           :class_name => 'BasePays::Debarquement',
           :foreign_key => 'idagglo'
  
   has_many :activites,
           :class_name => 'BasePays::Activite',
           :foreign_key => 'idagglo'
         
  belongs_to :secteur,
             :class_name => 'Pechart::Secteur',
             :foreign_key => 'numerosecteur'
           
  def periodesEnquetes
    periodes = Array.new
    periodes = Pechart::PeriodeEnquete.find(:all, :conditions => ["agglo = ?",self.nomagglo], :order => 'an desc, periode desc' )
    return periodes
  end 
  
  def debarquements_in_interval(d1,d2)
    return BasePays::Debarquement.find(:all, :conditions => [" (idagglo = ?) and (datecondphyschim between ? and ? ) ",self.idagglo,d1,d2])
  end
  
  def activites_in_interval(d1,d2)
    return BasePays::Activite.find(:all, :conditions => [" (idagglo = ?) and (dateact between ? and ? ) ",self.idagglo,d1,d2])
  end
  
end
