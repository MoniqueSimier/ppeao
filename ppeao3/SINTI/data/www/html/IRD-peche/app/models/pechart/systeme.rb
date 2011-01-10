class Pechart::Systeme < Pechart::Modele
set_table_name 'systeme'
set_primary_key 'idsysteme'

has_many   :secteurs,
           :class_name => 'Pechart::Secteur',
           :foreign_key => 'idsysteme',
           :order => 'nomsecteur asc'
           
belongs_to   :pays,
             :class_name => 'Pechart::Pays',
             :foreign_key => 'codepays'

end
