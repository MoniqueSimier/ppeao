class Pechexp::Pays < Pechexp::Modele
  set_table_name 'pays'
  set_primary_key 'pays_code_fip'
  
  has_many :systemes,
           :class_name => 'Pechexp::Systeme',
           :foreign_key => 'pay_code_fip',
           :order => 'sys_nom asc'
  
end
