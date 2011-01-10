class CreateDebarquementPecharts < ActiveRecord::Migration
  def self.up
    create_table :debarquement_pecharts do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :debarquement_pecharts
  end
end
