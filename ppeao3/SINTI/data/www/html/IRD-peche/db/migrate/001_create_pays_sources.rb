class CreatePaysSources < ActiveRecord::Migration
  def self.up
    create_table :pays_sources do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :pays_sources
  end
end
