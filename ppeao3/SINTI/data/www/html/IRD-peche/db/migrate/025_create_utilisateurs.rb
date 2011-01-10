class CreateUtilisateurs < ActiveRecord::Migration
  def self.up
    create_table :utilisateurs do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :utilisateurs
  end
end
