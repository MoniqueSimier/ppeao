require 'composite_primary_keys'

class Pechexp::Campagne < Pechexp::Modele
set_table_name 'campagne'
set_primary_keys :camp_sys_num, :camp_num

has_many :coups_de_peche,
         :class_name => 'Pechexp::CoupDePeche',
         :foreign_key => [:cp_sys_num,:cp_camp_num],
         :order => 'cp_date desc'
end
