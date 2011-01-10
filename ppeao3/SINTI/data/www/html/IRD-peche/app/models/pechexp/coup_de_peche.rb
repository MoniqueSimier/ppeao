require 'composite_primary_keys'

class Pechexp::CoupDePeche < Pechexp::Modele
  set_table_name 'cp_peche'
  set_primary_keys :cp_sys_num, :cp_camp_num, :cp_num
end
