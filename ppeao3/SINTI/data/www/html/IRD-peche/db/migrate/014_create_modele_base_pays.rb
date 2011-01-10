class CreateModeleBasePays < ActiveRecord::Migration
  def self.up
    create_table :modele_base_pays do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :modele_base_pays
  end
end
