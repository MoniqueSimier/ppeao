class CreateModeleSystemes < ActiveRecord::Migration
  def self.up
    create_table :modele_systemes do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :modele_systemes
  end
end
