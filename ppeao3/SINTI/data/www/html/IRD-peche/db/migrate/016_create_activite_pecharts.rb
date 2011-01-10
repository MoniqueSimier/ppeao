class CreateActivitePecharts < ActiveRecord::Migration
  def self.up
    create_table :activite_pecharts do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :activite_pecharts
  end
end
