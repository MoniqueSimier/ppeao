class Utilisateur < BaseSysteme::Modele
  set_table_name 'sys_utilisateur'
  
  belongs_to :groupe,
             :class_name => 'Groupe',
             :foreign_key => 'sys_groupe_id'
end
