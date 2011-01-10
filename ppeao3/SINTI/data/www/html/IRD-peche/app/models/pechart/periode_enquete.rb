class Pechart::PeriodeEnquete < Pechart::Modele
  set_table_name 'periodeenquete'
  has_many   :agglomeration,
             :class_name => 'Pechart::Agglomeration', 
             :finder_sql => "SELECT * FROM agglomeration  WHERE agglomeration.nomagglo = '" +'#{agglo}'+"'",
             :limit => 1
end
