class CreateAccessGroupes < ActiveRecord::Migration
  def self.up
    create_table :groupes do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :groupes
  end
end
