class CreateModelePecharts < ActiveRecord::Migration
  def self.up
    create_table :modele_pecharts do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :modele_pecharts
  end
end
