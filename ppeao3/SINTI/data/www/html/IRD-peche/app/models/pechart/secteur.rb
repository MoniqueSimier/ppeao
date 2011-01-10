class Pechart::Secteur < Pechart::Modele
set_table_name 'secteur'
set_primary_key 'numerosecteur'

has_many   :agglomerations,
           :class_name => 'Pechart::Agglomeration',
           :foreign_key => 'numerosecteur',
           :order => 'nomagglo asc'
           
belongs_to   :systeme,
             :class_name => 'Pechart::Systeme',
             :foreign_key => 'idsysteme'


end
