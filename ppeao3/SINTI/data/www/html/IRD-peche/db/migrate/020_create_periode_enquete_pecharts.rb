class CreatePeriodeEnquetePecharts < ActiveRecord::Migration
  def self.up
    create_table :periode_enquete_pecharts do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :periode_enquete_pecharts
  end
end
