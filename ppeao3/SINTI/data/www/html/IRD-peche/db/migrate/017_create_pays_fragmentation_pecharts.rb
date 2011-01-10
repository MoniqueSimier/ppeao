class CreatePaysFragmentationPecharts < ActiveRecord::Migration
  def self.up
    create_table :pays_fragmentation_pecharts do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :pays_fragmentation_pecharts
  end
end
