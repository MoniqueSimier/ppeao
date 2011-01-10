class Pechexp::Systeme < Pechexp::Modele
set_table_name 'systeme'
set_primary_key 'sys_num'
  
has_many   :campagnes,
           :class_name => 'Pechexp::Campagne',
           :foreign_key => 'camp_sys_num',
           :order => 'camp_num desc'
end
