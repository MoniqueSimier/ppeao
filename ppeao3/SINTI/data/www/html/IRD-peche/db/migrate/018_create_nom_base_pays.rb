class CreateNomBasePays < ActiveRecord::Migration
  def self.up
    create_table :nom_base_pays do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :nom_base_pays
  end
end
