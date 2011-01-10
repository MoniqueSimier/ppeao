class CreateCoupDePechePechexps < ActiveRecord::Migration
  def self.up
    create_table :coup_de_peche_pechexps do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :coup_de_peche_pechexps
  end
end
