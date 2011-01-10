class Pechart::Pays < Pechart::Modele
set_table_name 'pays'
set_primary_key 'codepays'

has_many   :systemes,
           :class_name => 'Pechart::Systeme',
           :foreign_key => 'codepays',
           :order => 'nomsysteme asc'

end
