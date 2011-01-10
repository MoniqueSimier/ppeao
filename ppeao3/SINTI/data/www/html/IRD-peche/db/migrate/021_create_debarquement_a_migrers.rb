class CreateDebarquementAMigrers < ActiveRecord::Migration
  def self.up
    create_table :debarquement_a_migrers do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :debarquement_a_migrers
  end
end
