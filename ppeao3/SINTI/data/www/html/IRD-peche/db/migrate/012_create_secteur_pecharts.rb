class CreateSecteurPecharts < ActiveRecord::Migration
  def self.up
    create_table :secteur_pecharts do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :secteur_pecharts
  end
end
