class CreateDebarquementCibles < ActiveRecord::Migration
  def self.up
    create_table :debarquement_cibles do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :debarquement_cibles
  end
end
